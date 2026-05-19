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
        $highSpenders      = $this->getHighSpenders();
        $popularGenres     = $this->getPopularGenres();
        [$chartLabels, $chartData] = $this->getChartData();

        return view('admin.dashboard', compact(
            'stats', 'gameTypeStats', 'topGames', 'activeDiscounts',
            'recentGames', 'highSpenders', 'popularGenres',
            'chartLabels', 'chartData',
        ));
    }

    // ── Statistik ringkasan ───────────────────────────────────
    private function buildStats(): array
    {
        $now = Carbon::now();
        return [
            // Game
            'totalGames'           => Game::where('is_active', true)->count(),
            'totalInactiveGames'   => Game::where('is_active', false)->count(),
            'totalBaseGames'       => Game::where('is_active', true)->where('game_type', 'base_game')->count(),
            'totalAddon'           => Game::where('is_active', true)->where('game_type', 'addon')->count(),
            'totalFreeGames'       => Game::where('is_active', true)->where('base_price', 0)->count(),
            'avgRating'            => Game::where('is_active', true)->whereNotNull('avg_rating')->avg('avg_rating') ?? 0,
            // Users
            'totalUsers'           => User::where('is_active', true)->where('is_admin', false)->count(),
            'totalAdmins'          => User::where('is_admin', true)->count(),
            'totalNewUsers'        => User::where('is_admin', false)
                                         ->where('created_at', '>=', $now->copy()->subDays(30))->count(),
            'totalInactiveUsers'   => User::where('is_active', false)->where('is_admin', false)->count(),
            'totalUsersWithTrx'    => User::where('is_admin', false)
                                         ->whereHas('transactions', fn($q) => $q->whereNotNull('completed_at'))
                                         ->count(),
            // Transaksi
            'totalTransactions'    => Transaction::whereNotNull('completed_at')->count(),
            'totalTransactionsThisMonth' => Transaction::whereNotNull('completed_at')
                                               ->whereYear('completed_at', $now->year)
                                               ->whereMonth('completed_at', $now->month)->count(),
            // Revenue
            'totalRevenue'         => Transaction::whereNotNull('completed_at')->sum('total_amount'),
            'revenueThisMonth'     => Transaction::whereNotNull('completed_at')
                                          ->whereYear('completed_at', $now->year)
                                          ->whereMonth('completed_at', $now->month)->sum('total_amount'),
            // Discount
            'totalActiveDiscounts' => Discount::where('is_active', true)
                                         ->where('start_date', '<=', $now)
                                         ->where('end_date', '>=', $now)->count(),
        ];
    }

    // ── Distribusi tipe game ──────────────────────────────────
    private function buildGameTypeStats(): array
    {
        $colors = [
            'base_game'  => '#0078f2', 'addon'       => '#8b5cf6',
            'edition'    => '#06b6d4', 'bundle'      => '#f59e0b',
            'demo'       => '#10b981', 'aplikasi'    => '#f97316',
            'editor'     => '#ec4899', 'langganan'   => '#14b8a6',
            'pengalaman' => '#a78bfa',
        ];
        // SQL equivalent:
        // SELECT game_type, COUNT(*) AS count
        // FROM games
        // WHERE is_active = 1
        // GROUP BY game_type
        // ORDER BY count DESC;
        $rows  = Game::where('is_active', true)
            ->select('game_type', DB::raw('COUNT(*) as count'))
            ->groupBy('game_type')->orderByDesc('count')->get();
        $total = $rows->sum('count') ?: 1;
        return $rows->map(fn($r) => [
            'type'  => $r->game_type,
            'count' => $r->count,
            'pct'   => round($r->count / $total * 100),
            'color' => $colors[$r->game_type] ?? '#6b7280',
        ])->toArray();
    }

    // ── Top 5 game terlaris ───────────────────────────────────
    private function getTopGames()
    {
        try {
            $v = DB::select("SELECT TABLE_NAME FROM information_schema.VIEWS
                WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='vw_top_selling_games'");
            if (!empty($v)) {
                return DB::table('vw_top_selling_games')->orderByDesc('total_sold')->limit(5)->get();
            }
        } catch (\Throwable) {}

        try {
            // SQL equivalent:
            // SELECT g.game_id, g.title, g.cover_image_url, g.avg_rating, p.name AS publisher,
            //        COUNT(td.game_id) AS total_sold,
            //        COALESCE(SUM(td.price_at_purchase),0) AS total_revenue
            // FROM games AS g
            // LEFT JOIN publishers AS p ON g.publisher_id = p.publisher_id
            // LEFT JOIN transaction_details AS td ON g.game_id = td.game_id
            // LEFT JOIN transactions AS t ON td.transaction_id = t.transaction_id AND t.completed_at IS NOT NULL
            // WHERE g.is_active = 1
            // GROUP BY g.game_id, g.title, g.cover_image_url, g.avg_rating, p.name
            // ORDER BY total_sold DESC
            // LIMIT 5;
            return DB::table('games AS g')
                ->leftJoin('publishers AS p', 'g.publisher_id', '=', 'p.publisher_id')
                ->leftJoin('transaction_details AS td', 'g.game_id', '=', 'td.game_id')
                ->leftJoin('transactions AS t', fn($j) =>
                    $j->on('td.transaction_id', '=', 't.transaction_id')->whereNotNull('t.completed_at'))
                ->selectRaw('g.game_id, g.title, g.cover_image_url, g.avg_rating,
                    p.name AS publisher,
                    COUNT(td.game_id) AS total_sold,
                    COALESCE(SUM(td.price_at_purchase),0) AS total_revenue')
                ->where('g.is_active', true)
                ->groupBy('g.game_id','g.title','g.cover_image_url','g.avg_rating','p.name')
                ->orderByDesc('total_sold')->limit(5)->get();
        } catch (\Throwable) { return collect(); }
    }

    // ── Diskon aktif ─────────────────────────────────────────
    private function getActiveDiscounts()
    {
        return Discount::with('game')
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderByDesc('discount_pct')
            ->limit(5)->get();
    }

    // ── 5 game terbaru ────────────────────────────────────────
    private function getRecentGames()
    {
        return Game::with('publisher')->latest('game_id')->limit(5)->get();
    }

    // ── Top 5 user pengeluaran terbesar ──────────────────────
    private function getHighSpenders()
    {
        try {
            $v = DB::select("SELECT TABLE_NAME FROM information_schema.VIEWS
                WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='vw_high_spenders'");
            if (!empty($v)) {
                return DB::table('vw_high_spenders')->orderByDesc('total_spent')->limit(5)->get();
            }
        } catch (\Throwable) {}

        try {
            // SQL equivalent:
            // SELECT u.user_id, u.username, u.email,
            //        COUNT(t.transaction_id) AS total_transactions,
            //        SUM(t.total_amount) AS total_spent
            // FROM users AS u
            // JOIN transactions AS t ON u.user_id = t.user_id AND t.completed_at IS NOT NULL
            // WHERE u.is_admin = 0
            // GROUP BY u.user_id, u.username, u.email
            // ORDER BY total_spent DESC
            // LIMIT 5;
            return DB::table('users AS u')
                ->join('transactions AS t', fn($j) =>
                    $j->on('u.user_id', '=', 't.user_id')->whereNotNull('t.completed_at'))
                ->selectRaw('u.user_id, u.username, u.email,
                    COUNT(t.transaction_id) AS total_transactions,
                    SUM(t.total_amount)     AS total_spent')
                ->where('u.is_admin', false)
                ->groupBy('u.user_id', 'u.username', 'u.email')
                ->orderByDesc('total_spent')
                ->limit(5)->get();
        } catch (\Throwable) { return collect(); }
    }

    // ── Genre paling populer ──────────────────────────────────
    private function getPopularGenres()
    {
        try {
            $v = DB::select("SELECT TABLE_NAME FROM information_schema.VIEWS
                WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='vw_popular_genres'");
            if (!empty($v)) {
                return DB::table('vw_popular_genres')->orderByDesc('total_sold')->limit(6)->get();
            }
        } catch (\Throwable) {}

        try {
            // SQL equivalent:
            // SELECT g.genre_id, g.name AS genre_name,
            //        COUNT(DISTINCT td.game_id) AS total_games,
            //        COUNT(td.detail_id) AS total_sold,
            //        COALESCE(SUM(td.price_at_purchase),0) AS total_revenue
            // FROM genres AS g
            // JOIN game_genres AS gg ON g.genre_id = gg.genre_id
            // JOIN transaction_details AS td ON gg.game_id = td.game_id
            // JOIN transactions AS t ON td.transaction_id = t.transaction_id AND t.completed_at IS NOT NULL
            // GROUP BY g.genre_id, g.name
            // ORDER BY total_sold DESC
            // LIMIT 6;
            return DB::table('genres AS g')
                ->join('game_genres AS gg', 'g.genre_id', '=', 'gg.genre_id')
                ->join('transaction_details AS td', 'gg.game_id', '=', 'td.game_id')
                ->join('transactions AS t', fn($j) =>
                    $j->on('td.transaction_id', '=', 't.transaction_id')->whereNotNull('t.completed_at'))
                ->selectRaw('g.genre_id, g.name AS genre_name,
                    COUNT(DISTINCT td.game_id)  AS total_games,
                    COUNT(td.detail_id)          AS total_sold,
                    COALESCE(SUM(td.price_at_purchase),0) AS total_revenue')
                ->groupBy('g.genre_id', 'g.name')
                ->orderByDesc('total_sold')
                ->limit(6)->get();
        } catch (\Throwable) { return collect(); }
    }

    // ── Chart pendapatan 12 bulan ─────────────────────────────
    private function getChartData(): array
    {
        try {
            $v = DB::select("SELECT TABLE_NAME FROM information_schema.VIEWS
                WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='vw_monthly_revenue'");
            if (!empty($v)) {
                $rows = DB::table('vw_monthly_revenue')
                    ->orderBy('tahun','desc')->orderBy('bulan','desc')
                    ->limit(12)->get()->reverse()->values();
                return [$rows->pluck('nama_bulan')->toArray(),
                        $rows->pluck('total_pendapatan')->map(fn($v)=>(float)$v)->toArray()];
            }
        } catch (\Throwable) {}

        try {
            // SQL equivalent:
            // SELECT YEAR(completed_at) AS tahun,
            //        MONTH(completed_at) AS bulan,
            //        DATE_FORMAT(completed_at, '%b %Y') AS nama_bulan,
            //        SUM(total_amount) AS total_pendapatan
            // FROM transactions
            // WHERE completed_at IS NOT NULL
            //   AND completed_at >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
            // GROUP BY YEAR(completed_at), MONTH(completed_at), DATE_FORMAT(completed_at, '%b %Y')
            // ORDER BY tahun, bulan;
            $rows = DB::table('transactions')
                ->selectRaw('YEAR(completed_at) AS tahun, MONTH(completed_at) AS bulan,
                    DATE_FORMAT(completed_at,"%b %Y") AS nama_bulan,
                    SUM(total_amount) AS total_pendapatan')
                ->whereNotNull('completed_at')
                ->where('completed_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
                ->groupByRaw('YEAR(completed_at), MONTH(completed_at), DATE_FORMAT(completed_at,"%b %Y")')
                ->orderBy('tahun')->orderBy('bulan')->get();
            return [$rows->pluck('nama_bulan')->toArray(),
                    $rows->pluck('total_pendapatan')->map(fn($v)=>(float)$v)->toArray()];
        } catch (\Throwable) { return [[],[]]; }
    }
}
