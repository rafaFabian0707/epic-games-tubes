<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Game;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats             = $this->buildStats();
        $gameTypeStats     = $this->buildGameTypeStats();
        $topGames          = $this->getTopGames();
        $activeDiscounts   = $this->getActiveDiscounts();
        $recentGames       = $this->getRecentGames();
        [$chartLabels, $chartData] = $this->getChartData();

        return view('admin.dashboard', compact(
            'stats',
            'gameTypeStats',
            'topGames',
            'activeDiscounts',
            'recentGames',
            'chartLabels',
            'chartData',
        ));
    }

    // =========================================================
    // STATS — semua angka ringkasan
    // =========================================================
    private function buildStats(): array
    {
        $now = Carbon::now();

        return [
            // Game
            'totalGames'           => Game::where('is_active', true)->count(),
            'totalInactiveGames'   => Game::where('is_active', false)->count(),
            'totalBaseGames'       => Game::active()->where('game_type', 'base_game')->count(),
            'totalAddon'           => Game::active()->where('game_type', 'addon')->count(),
            'totalFreeGames'       => Game::active()->where('base_price', 0)->count(),
            'avgRating'            => Game::active()->whereNotNull('avg_rating')->avg('avg_rating') ?? 0,

            // Users
            'totalUsers'           => User::where('is_active', true)->where('is_admin', false)->count(),
            'totalAdmins'          => User::where('is_admin', true)->count(),
            'totalNewUsers'        => User::where('is_admin', false)
                                        ->where('created_at', '>=', $now->copy()->subDays(30))
                                        ->count(),

            // Transaksi
            'totalTransactions'    => Transaction::completed()->count(),
            'totalTransactionsThisMonth' => Transaction::completed()
                                        ->whereYear('completed_at', $now->year)
                                        ->whereMonth('completed_at', $now->month)
                                        ->count(),

            // Revenue
            'totalRevenue'         => Transaction::completed()->sum('total_amount'),
            'revenueThisMonth'     => Transaction::completed()
                                        ->whereYear('completed_at', $now->year)
                                        ->whereMonth('completed_at', $now->month)
                                        ->sum('total_amount'),

            // Discount
            'totalActiveDiscounts' => Discount::active()->count(),
        ];
    }

    // =========================================================
    // GAME TYPE STATS — untuk doughnut chart & progress bar
    // =========================================================
    private function buildGameTypeStats(): array
    {
        $colors = [
            'base_game'   => '#0078f2',
            'addon'       => '#8b5cf6',
            'edition'     => '#06b6d4',
            'bundle'      => '#f59e0b',
            'demo'        => '#10b981',
            'aplikasi'    => '#f97316',
            'editor'      => '#ec4899',
            'langganan'   => '#14b8a6',
            'pengalaman'  => '#a78bfa',
        ];

        $rows  = Game::active()
            ->select('game_type', DB::raw('COUNT(*) as count'))
            ->groupBy('game_type')
            ->orderByDesc('count')
            ->get();

        $total = $rows->sum('count') ?: 1;

        return $rows->map(fn($r) => [
            'type'  => $r->game_type,
            'count' => $r->count,
            'pct'   => round($r->count / $total * 100),
            'color' => $colors[$r->game_type] ?? '#6b7280',
        ])->toArray();
    }

    // =========================================================
    // TOP 5 GAMES — coba view dulu, fallback ke raw query
    // =========================================================
    private function getTopGames()
    {
        try {
            $viewExists = DB::select(
                "SELECT TABLE_NAME FROM information_schema.VIEWS
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'vw_top_selling_games'"
            );
            if (! empty($viewExists)) {
                return DB::table('vw_top_selling_games')
                    ->orderByDesc('total_sold')
                    ->limit(5)
                    ->get();
            }
        } catch (\Throwable) {}

        try {
            return DB::table('games AS g')
                ->leftJoin('publishers AS p', 'g.publisher_id', '=', 'p.publisher_id')
                ->leftJoin('transaction_details AS td', 'g.game_id', '=', 'td.game_id')
                ->leftJoin('transactions AS t', function ($j) {
                    $j->on('td.transaction_id', '=', 't.transaction_id')
                      ->whereNotNull('t.completed_at');
                })
                ->selectRaw('
                    g.game_id,
                    g.title,
                    g.cover_image_url,
                    g.avg_rating,
                    p.name  AS publisher,
                    COUNT(td.game_id)                     AS total_sold,
                    COALESCE(SUM(td.price_at_purchase), 0) AS total_revenue
                ')
                ->where('g.is_active', true)
                ->groupBy('g.game_id', 'g.title', 'g.cover_image_url', 'g.avg_rating', 'p.name')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get();
        } catch (\Throwable) {
            return collect();
        }
    }

    // =========================================================
    // ACTIVE DISCOUNTS — 5 diskon yang sedang berjalan
    // =========================================================
    private function getActiveDiscounts()
    {
        return Discount::with('game')
            ->active()
            ->orderByDesc('discount_pct')
            ->limit(5)
            ->get();
    }

    // =========================================================
    // RECENT GAMES — 5 game terakhir ditambahkan
    // =========================================================
    private function getRecentGames()
    {
        return Game::with('publisher')
            ->latest('game_id')
            ->limit(5)
            ->get();
    }

    // =========================================================
    // CHART DATA — 12 bulan terakhir (coba view, fallback query)
    // =========================================================
    private function getChartData(): array
    {
        try {
            $viewExists = DB::select(
                "SELECT TABLE_NAME FROM information_schema.VIEWS
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'vw_monthly_revenue'"
            );
            if (! empty($viewExists)) {
                $rows = DB::table('vw_monthly_revenue')
                    ->orderBy('tahun', 'desc')
                    ->orderBy('bulan', 'desc')
                    ->limit(12)
                    ->get()
                    ->reverse()
                    ->values();

                return [
                    $rows->pluck('nama_bulan')->toArray(),
                    $rows->pluck('total_pendapatan')->map(fn($v) => (float) $v)->toArray(),
                ];
            }
        } catch (\Throwable) {}

        try {
            $rows = DB::table('transactions')
                ->selectRaw('
                    YEAR(completed_at)     AS tahun,
                    MONTH(completed_at)    AS bulan,
                    DATE_FORMAT(completed_at, "%b %Y") AS nama_bulan,
                    SUM(total_amount)      AS total_pendapatan
                ')
                ->whereNotNull('completed_at')
                ->where('completed_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
                ->groupByRaw('YEAR(completed_at), MONTH(completed_at), DATE_FORMAT(completed_at, "%b %Y")')
                ->orderBy('tahun')
                ->orderBy('bulan')
                ->get();

            return [
                $rows->pluck('nama_bulan')->toArray(),
                $rows->pluck('total_pendapatan')->map(fn($v) => (float) $v)->toArray(),
            ];
        } catch (\Throwable) {
            return [[], []];
        }
    }
}