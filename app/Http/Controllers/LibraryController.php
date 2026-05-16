<?php

namespace App\Http\Controllers;

use App\Models\Library;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    // Middleware 'auth' sudah diset di routes/web.php
    // JANGAN pakai $this->middleware() — Laravel 11 tidak support di constructor

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
