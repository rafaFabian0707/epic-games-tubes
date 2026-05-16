<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Middleware 'auth' sudah diset di routes/web.php
    // JANGAN pakai $this->middleware() — Laravel 11 tidak support di constructor

    // GET /wishlist
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with(['game.publisher', 'game.discounts', 'game.platforms'])
            ->latest('added_at')
            ->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    // POST /wishlist/add
    public function add(Request $request)
    {
        $request->validate(['game_id' => ['required', 'integer', 'exists:games,game_id']]);

        $gameId = (int) $request->game_id;
        $user   = Auth::user();

        // Cek library — game yang sudah dimiliki tidak perlu di-wishlist
        if ($user->alreadyOwns($gameId)) {
            return back()->with('error', 'Game ini sudah ada di library-mu.');
        }

        // Cek duplikat wishlist
        $exists = Wishlist::where('user_id', $user->user_id)
                          ->where('game_id', $gameId)->exists();

        if ($exists) {
            return back()->with('info', 'Game ini sudah ada di wishlist-mu.');
        }

        Wishlist::create([
            'user_id' => $user->user_id,
            'game_id' => $gameId,
        ]);

        return back()->with('success', 'Game ditambahkan ke wishlist.');
    }

    // DELETE /wishlist/{id}
    public function remove(int $id)
    {
        Wishlist::where('wishlist_id', $id)
                ->where('user_id', Auth::id())
                ->delete();

        return back()->with('success', 'Game dihapus dari wishlist.');
    }
}
