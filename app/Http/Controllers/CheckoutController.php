<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Game;
use App\Models\Transaction;

/**
 * CheckoutController — Mengelola proses transaksi pembelian game
 *
 * ARSITEKTUR PENTING:
 *  - Transaksi bersifat simulasi: pembayaran SELALU berhasil (no payment gateway).
 *  - Controller ini memanggil MySQL Stored Procedure `sp_checkout`.
 *  - Library diisi OTOMATIS oleh trigger `trg_after_transaction_insert`.
 *    Laravel TIDAK boleh insert ke tabel library secara langsung.
 *  - Wishlist dihapus OTOMATIS oleh trigger yang sama.
 *
 * BUSINESS RULES yang diimplementasikan:
 *  - BR-01: No Duplicate Purchase — tolak jika game sudah ada di library.
 *  - BR-16: Transaksi langsung selesai (completed_at = NOW(), tanpa status).
 */
class CheckoutController extends Controller
{
    // =========================================================
    // Middleware: halaman ini hanya untuk user yang sudah login
    // =========================================================

    

    // =========================================================
    // INDEX — Tampilkan halaman ringkasan checkout GET /checkout
    // =========================================================

    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil item dari session cart
        $cartGameIds = session('cart', []);

        if (empty($cartGameIds)) {
            return redirect()->route('cart.index')
                             ->with('info', 'Keranjang belanjamu masih kosong.');
        }

        // Load game detail untuk semua item di cart
        $cartGames = Game::active()
            ->with(['publisher', 'discounts', 'ageRating'])
            ->whereIn('game_id', $cartGameIds)
            ->get();

        // --- BR-01: Pre-check sebelum tampilkan form checkout ---
        // Cek apakah ada game di cart yang sudah dimiliki user
        $alreadyOwnedIds = $user->ownedFromList($cartGameIds);

        if ($alreadyOwnedIds->isNotEmpty()) {
            $ownedTitles = Game::whereIn('game_id', $alreadyOwnedIds)
                               ->pluck('title')
                               ->join(', ');

            return redirect()->route('cart.index')
                             ->with('error', "Game berikut sudah ada di library-mu: {$ownedTitles}. Hapus dari keranjang sebelum checkout.");
        }

        // Hitung total harga (menggunakan accessor final_price di Model)
        $total = $cartGames->sum(fn ($game) => $game->final_price);

        return view('checkout.index', compact('cartGames', 'total'));
    }

    // =========================================================
    // PROCESS — Proses transaksi POST /checkout
    // =========================================================

    public function process(Request $request)
    {
        // Validasi input form
        $request->validate([
            'payment_method' => ['required', 'in:credit_card,debit_card,paypal,gift_card'],
        ]);

        $user        = Auth::user();
        $cartGameIds = session('cart', []);

        // Guard: cart kosong
        if (empty($cartGameIds)) {
            return redirect()->route('cart.index')
                             ->with('error', 'Keranjang kosong. Tidak ada yang bisa di-checkout.');
        }

        // =====================================================
        // BR-01: Validasi Duplikat Pembelian (No Duplicate Purchase)
        // =====================================================
        // Cek ulang di sini (tidak cukup hanya di index()) karena
        // user bisa memiliki game di antara load form dan submit.

        $alreadyOwnedIds = $user->ownedFromList($cartGameIds);

        if ($alreadyOwnedIds->isNotEmpty()) {
            $ownedTitles = Game::whereIn('game_id', $alreadyOwnedIds)
                               ->pluck('title')
                               ->join(', ');

            return back()->with('error',
                "Pembelian dibatalkan. Game berikut sudah ada di library-mu: {$ownedTitles}."
            );
        }

        // =====================================================
        // Load game & siapkan data cart_temp untuk sp_checkout
        // =====================================================

        $cartGames = Game::active()
            ->with('discounts')
            ->whereIn('game_id', $cartGameIds)
            ->get();

        // Guard: pastikan semua game_id di cart valid dan aktif
        if ($cartGames->count() !== count($cartGameIds)) {
            return back()->with('error',
                'Beberapa game di keranjang tidak tersedia lagi. Silakan periksa kembali.'
            );
        }

        // =====================================================
        // Panggil Stored Procedure sp_checkout
        // =====================================================
        // sp_checkout mengharapkan cart_temp terisi terlebih dahulu.
        // Kita isi cart_temp, panggil SP, lalu SP membersihkannya sendiri.

        try {
            DB::transaction(function () use ($user, $cartGames, $request) {

                // 1. Isi tabel cart_temp (sementara, per user)
                //    SP akan membaca ini dan menghapusnya setelah selesai.
                DB::table('cart_temp')->where('user_id', $user->user_id)->delete();

                $cartRows = $cartGames->map(fn ($game) => [
                    'user_id'          => $user->user_id,
                    'game_id'          => $game->game_id,
                    'price_at_purchase' => $game->final_price,
                    'discount_applied' => $game->discount_pct,
                ])->toArray();

                DB::table('cart_temp')->insert($cartRows);

                // 2. Panggil sp_checkout (OUT parameter: transaction_id & result)
                DB::statement('CALL sp_checkout(?, ?, @tid, @res)', [
                    $user->user_id,
                    $request->payment_method,
                ]);

                // 3. Ambil output parameter SP
                $output = DB::selectOne('SELECT @tid AS transaction_id, @res AS result');

                // 4. Validasi hasil SP
                if (! $output || $output->result !== 'SUCCESS') {
                    throw new \RuntimeException(
                        $output->result ?? 'Stored Procedure gagal tanpa keterangan.'
                    );
                }

                // Simpan transaction_id ke session untuk halaman konfirmasi
                session(['last_transaction_id' => $output->transaction_id]);
            });

        } catch (\Throwable $e) {
            // SP sudah handle ROLLBACK di dalam prosedurnya,
            // tapi DB::transaction() di Laravel juga akan rollback.
            return back()->with('error',
                'Transaksi gagal: ' . $e->getMessage() . '. Silakan coba lagi.'
            );
        }

        // =====================================================
        // Setelah berhasil:
        //  - Hapus cart dari session
        //  - Library sudah diisi oleh trigger secara otomatis
        //  - Redirect ke halaman konfirmasi
        // =====================================================

        session()->forget('cart');

        return redirect()->route('checkout.success')
                         ->with('success', 'Pembelian berhasil! Game telah ditambahkan ke library-mu.');
    }

    // =========================================================
    // SUCCESS — Halaman konfirmasi GET /checkout/success
    // =========================================================

    public function success()
    {
        $transactionId = session('last_transaction_id');

        if (! $transactionId) {
            return redirect()->route('home');
        }

        // Ambil detail transaksi beserta game yang dibeli
        $transaction = Transaction::with([
            'details.game.publisher',
            'details.game.ageRating',
        ])
        ->where('transaction_id', $transactionId)
        ->where('user_id', Auth::id())   // Pastikan milik user ini
        ->firstOrFail();

        // Hapus dari session agar tidak bisa diakses ulang via refresh
        session()->forget('last_transaction_id');

        return view('checkout.success', compact('transaction'));
    }
}
