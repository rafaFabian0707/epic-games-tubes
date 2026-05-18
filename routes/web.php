<?php

use Illuminate\Support\Facades\Route;

// =========================================================
// FRONTEND CONTROLLERS
// =========================================================

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\WishlistController;
<<<<<<< HEAD

// =========================================================
// ADMIN CONTROLLERS
// =========================================================

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
=======
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
>>>>>>> a71ee67145e6d53e035fc3b44ef8850f28a85e1b
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\Admin\PlatformController as AdminPlatformController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\DiscountController as AdminDiscountController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

// =========================================================
// GUEST ROUTES
// =========================================================

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/jelajahi', [GameController::class, 'jelajahi'])
    ->name('jelajahi');

Route::get('/store', [GameController::class, 'index'])
    ->name('store');

Route::get('/store/search', [GameController::class, 'search'])
    ->name('store.search');

Route::get('/game/{id}', [GameController::class, 'show'])
    ->name('game.show');

// News
Route::get('/news', [NewsController::class, 'index'])
    ->name('news.index');

Route::get('/news/{id}', [NewsController::class, 'show'])
    ->name('news.show');

// =========================================================
// USER AUTH ROUTES
// =========================================================

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// =========================================================
// USER ROUTES
// =========================================================

Route::middleware('auth')->group(function () {

    // =========================
    // CART
    // =========================

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

<<<<<<< HEAD
    Route::post('/cart/add', [CartController::class, 'add'])
        ->name('cart.add');
=======
    // Library
    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
>>>>>>> a71ee67145e6d53e035fc3b44ef8850f28a85e1b

    Route::delete('/cart/{gameId}', [CartController::class, 'remove'])
        ->name('cart.remove');

    // =========================
    // CHECKOUT
    // =========================

    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'process'])
        ->name('checkout.process');

    Route::get('/checkout/success', [CheckoutController::class, 'success'])
        ->name('checkout.success');

    // =========================
    // LIBRARY
    // =========================

    // Library hanya READ
    // Data diisi trigger MySQL otomatis
    Route::get('/library', [LibraryController::class, 'index'])
        ->name('library.index');

    // =========================
    // WISHLIST
    // =========================

    Route::get('/wishlist', [WishlistController::class, 'index'])
        ->name('wishlist.index');

    Route::post('/wishlist/add', [WishlistController::class, 'add'])
        ->name('wishlist.add');

    Route::patch('/wishlist/toggle', [WishlistController::class, 'toggle'])
        ->name('wishlist.toggle');

    Route::delete('/wishlist/{id}', [WishlistController::class, 'remove'])
        ->name('wishlist.remove');
});

// =========================================================
<<<<<<< HEAD
// ADMIN ROUTES
=======
// ADMIN AUTH — Login/logout halaman admin (TANPA middleware admin)
// =========================================================

Route::prefix('/admin')->name('admin.')->group(function () {

    // Halaman login admin (akses oleh guest atau user biasa)
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');

    // Logout admin
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

});

// =========================================================
// ADMIN ROUTES — Harus login + is_admin = true
>>>>>>> a71ee67145e6d53e035fc3b44ef8850f28a85e1b
// =========================================================

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Redirect /admin -> /admin/dashboard
        Route::redirect('/', '/admin/dashboard');

        // =========================
        // DASHBOARD
        // =========================

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // =========================
        // RESOURCE CRUD
        // =========================

        Route::resource('games', AdminGameController::class);

        Route::resource('platforms', AdminPlatformController::class);

        Route::resource('news', AdminNewsController::class);

        Route::resource('discounts', AdminDiscountController::class);

        // =========================
        // USER MANAGEMENT
        // =========================

        Route::get('/users', [AdminUserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/{user}', [AdminUserController::class, 'show'])
            ->name('users.show');

        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [AdminUserController::class, 'update'])
            ->name('users.update');

        Route::patch('/users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])
            ->name('users.toggle-admin');

        Route::patch('/users/{user}/toggle-active', [AdminUserController::class, 'toggleActive'])
            ->name('users.toggle-active');

        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
            ->name('users.destroy');
    });