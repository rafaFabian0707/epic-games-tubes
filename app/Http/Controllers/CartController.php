<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * CartController — Mengelola keranjang belanja berbasis session
 *
 * Cart disimpan di session sebagai array of game_id.
 * Tidak ada tabel cart permanen di database (kecuali cart_temp
 * yang dipakai sementara oleh sp_checkout).
 */
class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // GET /cart
    public function index()
    {
        $cartGameIds = session('cart', []);
        $cartGames   = collect();
        $total       = 0;

        if (! empty($cartGameIds)) {
            $cartGames = Game::active()
                ->with(['publisher', 'discounts'])
                ->whereIn('game_id', $cartGameIds)
                ->get();

            $total = $cartGames->sum(fn ($g) => $g->final_price);
        }

        return view('cart.index', compact('cartGames', 'total'));
    }

    // POST /cart/add
    public function add(Request $request)
    {
        $request->validate(['game_id' => ['required', 'integer', 'exists:games,game_id']]);

        $gameId = (int) $request->game_id;
        $user   = Auth::user();

        // BR-01: Jangan boleh tambah game yang sudah dimiliki
        if ($user->alreadyOwns($gameId)) {
            return back()->with('error', 'Game ini sudah ada di library-mu.');
        }

        $cart = session('cart', []);

        if (! in_array($gameId, $cart)) {
            $cart[] = $gameId;
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Game ditambahkan ke keranjang.');
    }

    // DELETE /cart/{gameId}
    public function remove(int $gameId)
    {
        $cart = session('cart', []);
        session(['cart' => array_values(array_filter($cart, fn ($id) => $id !== $gameId))]);

        return back()->with('success', 'Game dihapus dari keranjang.');
    }
}
