<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin;


// =========================================================
// GUEST ROUTES — Bisa diakses tanpa login
// =========================================================

Route::get('/', [HomeController::class, 'index'])->name('home');

// Jelajahi — browse semua game dengan filter
Route::get('/jelajahi', [GameController::class, 'jelajahi'])->name('jelajahi');

// Katalog & detail game
Route::get('/store', [GameController::class, 'index'])->name('store');
Route::get('/store/search', [GameController::class, 'search'])->name('store.search');
Route::get('/game/{id}', [GameController::class, 'show'])->name('game.show');
Route::get('/game/{id}/addons', [GameController::class, 'addons'])->name('game.addons');
Route::get('/game/{id}/achievements', [GameController::class, 'achievements'])->name('game.achievements');

// Berita
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

// =========================================================
// AUTH ROUTES
// =========================================================

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

});

Route::get('/tes', function () {
    // return view('welcome');
    return view('welcome');

});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =========================================================
// USER ROUTES — Harus login
// =========================================================

Route::middleware('auth')->group(function () {

    // Keranjang
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{gameId}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    // Library — diisi otomatis oleh trigger MySQL, hanya read dari Laravel
    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// =========================================================
// ADMIN ROUTES — Harus login + is_admin = true
// =========================================================

Route::middleware(['auth', 'admin'])
    ->prefix('/admin')
    ->name('admin.')
    ->group(function () {

        // Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Route::resource('/games', Admin\GameController::class);
        // Route::resource('/publishers', Admin\PublisherController::class);
        // Route::resource('/news', Admin\NewsController::class);
        // Route::resource('/discounts', Admin\DiscountController::class);
        // Route::resource('/users', Admin\UserController::class);

        // // BARU v4.0 — Platform management
        // Route::resource('/platforms', Admin\PlatformController::class);
    });