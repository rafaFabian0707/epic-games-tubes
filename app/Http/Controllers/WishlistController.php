<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * PERBAIKAN: Nama file dan class diubah dari WishlistsController → WishlistController
 * Sesuai dengan routes/web.php yang mengimport WishlistController.
 */
class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        // Cek library — trigger MySQL juga akan cek ini, tapi cek di sini lebih cepat
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
