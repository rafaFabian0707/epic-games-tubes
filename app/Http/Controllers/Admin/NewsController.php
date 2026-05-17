<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

/**
 * Admin\NewsController — CRUD berita/artikel
 *
 * SKEMA tabel `news` (berdasarkan migration aktual proyek):
 *   news_id       INT PK AUTO_INCREMENT
 *   title         VARCHAR(200)
 *   cover_url     VARCHAR(255)
 *   main_content  TEXT NULLABLE         ← ringkasan / intro artikel
 *   date          VARCHAR(32) NULLABLE  ← string tanggal (misal "May 15, 2026")
 *   publisher     TEXT                  ← nama penulis/sumber
 *   content       LONGTEXT              ← isi lengkap artikel
 *   media_url     VARCHAR(255) NULLABLE ← URL video/gambar tambahan
 *   is_active     TINYINT(1) DEFAULT 1
 *
 * CATATAN:
 *  - Tabel news TIDAK memakai timestamps() di migrationnya,
 *    sehingga News model menetapkan $timestamps = false.
 *  - Kolom `date` berupa string (bukan DATE/DATETIME) — simpan
 *    sebagai string yang sudah diformat (misal dari input date HTML).
 *  - Kolom `publisher` bukan FK — ini string nama sumber artikel.
 */
class NewsController extends Controller
{
    // =========================================================
    // INDEX — Daftar semua berita
    // GET /admin/news
    // =========================================================

    public function index(Request $request)
    {
        $query = News::orderByDesc('news_id');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'LIKE', "%{$q}%")
                    ->orWhere('publisher', 'LIKE', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $newsList = $query->paginate(20)->withQueryString();

        return view('admin.news.index', compact('newsList'));
    }

    // =========================================================
    // CREATE — Form tambah berita baru
    // GET /admin/news/create
    // =========================================================

    public function create()
    {
        return view('admin.news.create');
    }

    // =========================================================
    // STORE — Simpan berita baru
    // POST /admin/news
    // =========================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:200'],
            'cover_url'    => ['required', 'url', 'max:255'],
            'main_content' => ['nullable', 'string', 'max:3000'],
            'date'         => ['nullable', 'string', 'max:32'],
            'publisher'    => ['required', 'string', 'max:200'],
            'content'      => ['required', 'string'],
            'media_url'    => ['nullable', 'url', 'max:255'],
            'is_active'    => ['nullable', 'boolean'],
        ], [
            'title.required'     => 'Judul berita wajib diisi.',
            'cover_url.required' => 'URL gambar cover wajib diisi.',
            'cover_url.url'      => 'Cover URL harus berupa URL yang valid.',
            'publisher.required' => 'Nama publisher/penulis wajib diisi.',
            'content.required'   => 'Isi artikel wajib diisi.',
        ]);

        News::create([
            'title'        => $validated['title'],
            'cover_url'    => $validated['cover_url'],
            'main_content' => $validated['main_content'] ?? null,
            'date'         => $validated['date'] ?? null,
            'publisher'    => $validated['publisher'],
            'content'      => $validated['content'],
            'media_url'    => $validated['media_url'] ?? null,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    // =========================================================
    // EDIT — Form edit berita
    // GET /admin/news/{news}/edit
    // =========================================================

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    // =========================================================
    // UPDATE — Simpan perubahan berita
    // PUT /admin/news/{news}
    // =========================================================

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:200'],
            'cover_url'    => ['required', 'url', 'max:255'],
            'main_content' => ['nullable', 'string', 'max:3000'],
            'date'         => ['nullable', 'string', 'max:32'],
            'publisher'    => ['required', 'string', 'max:200'],
            'content'      => ['required', 'string'],
            'media_url'    => ['nullable', 'url', 'max:255'],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        $news->update([
            'title'        => $validated['title'],
            'cover_url'    => $validated['cover_url'],
            'main_content' => $validated['main_content'] ?? null,
            'date'         => $validated['date'] ?? null,
            'publisher'    => $validated['publisher'],
            'content'      => $validated['content'],
            'media_url'    => $validated['media_url'] ?? null,
            'is_active'    => $request->boolean('is_active', $news->is_active),
        ]);

        return redirect()->route('admin.news.index')
            ->with('success', "Berita \"{$news->title}\" berhasil diperbarui.");
    }

    // =========================================================
    // DESTROY — Hapus berita permanen
    // DELETE /admin/news/{news}
    //
    // Berita tidak punya FK ke tabel lain, aman untuk hard delete.
    // =========================================================

    public function destroy(News $news)
    {
        $title = $news->title;
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', "Berita \"{$title}\" berhasil dihapus.");
    }
}
