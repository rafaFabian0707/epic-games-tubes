<?php

namespace App\Http\Controllers;

use App\Models\Library;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // GET /library
    // Library diisi OTOMATIS oleh trigger — controller hanya READ
    public function index()
    {
        $libraryItems = Library::where('user_id', Auth::id())
            ->with(['game.publisher', 'game.platforms', 'game.ageRating'])
            ->latest('acquired_at')
            ->get();

        return view('library.index', compact('libraryItems'));
    }
}
