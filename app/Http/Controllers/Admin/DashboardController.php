<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Transaction::whereNotNull('completed_at')->sum('total_amount');
        $totalTransactions = Transaction::whereNotNull('completed_at')->count();
        $totalUsers = User::where('is_active', true)->count();
        $totalGames = Game::where('is_active', true)->count();

        $stats = compact('totalRevenue', 'totalTransactions', 'totalUsers', 'totalGames');

        [$chartLabels, $chartData] = $this->getChartData();
        $topGames = $this->getTopGames();
        $recentTransactions = Transaction::with('user')
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'chartLabels', 'chartData', 'topGames', 'recentTransactions'));
    }

    private function getChartData(): array
    {
        try {
            $viewExists = DB::select("SELECT TABLE_NAME FROM information_schema.VIEWS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'vw_monthly_revenue'");

            if (!empty($viewExists)) {
                $rows = DB::table('vw_monthly_revenue')->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->limit(12)->get()->reverse()->values();
                return [
                    $rows->pluck('nama_bulan')->map(fn($m) => $m)->toArray(),
                    $rows->pluck('total_pendapatan')->map(fn($v) => (float) $v)->toArray(),
                ];
            }
        } catch (\Throwable) {}

        try {
            $rows = DB::table('transactions')
                ->selectRaw('YEAR(created_at) AS tahun, MONTH(created_at) AS bulan, MONTHNAME(created_at) AS nama_bulan, SUM(total_amount) AS total_pendapatan')
                ->whereNotNull('completed_at')
                ->groupByRaw('YEAR(created_at), MONTH(created_at), MONTHNAME(created_at)')
                ->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')
                ->limit(12)->get()->reverse()->values();

            return [
                $rows->pluck('nama_bulan')->toArray(),
                $rows->pluck('total_pendapatan')->map(fn($v) => (float) $v)->toArray(),
            ];
        } catch (\Throwable) {}

        return [[], []];
    }

    private function getTopGames()
    {
        try {
            $viewExists = DB::select("SELECT TABLE_NAME FROM information_schema.VIEWS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'vw_top_selling_games'");
            if (!empty($viewExists)) {
                return DB::table('vw_top_selling_games')->orderBy('total_sold', 'desc')->limit(10)->get();
            }
        } catch (\Throwable) {}

        try {
            return DB::table('games AS g')
                ->leftJoin('publishers AS p', 'g.publisher_id', '=', 'p.publisher_id')
                ->leftJoin('transaction_details AS td', 'g.game_id', '=', 'td.game_id')
                ->leftJoin('transactions AS t', function ($join) {
                    $join->on('td.transaction_id', '=', 't.transaction_id')->whereNotNull('t.completed_at');
                })
                ->selectRaw('g.game_id, g.title, g.cover_image_url, g.avg_rating, p.name AS publisher, COUNT(td.detail_id) AS total_sold, COALESCE(SUM(td.price_at_purchase), 0) AS total_revenue')
                ->where('g.is_active', true)
                ->groupBy('g.game_id', 'g.title', 'g.cover_image_url', 'g.avg_rating', 'p.name')
                ->orderByDesc('total_sold')
                ->limit(10)->get();
        } catch (\Throwable) {
            return collect();
        }
    }
}
