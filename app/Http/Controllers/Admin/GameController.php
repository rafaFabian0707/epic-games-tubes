<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Age;
use App\Models\Developer;
use App\Models\Feature;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\Publisher;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    // ── INDEX ────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Game::with(['publisher', 'platforms', 'ageRating'])
            ->orderByDesc('game_id');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($s) => $s->where('title','LIKE',"%{$q}%")
                                      ->orWhere('main_desc','LIKE',"%{$q}%"));
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        if ($request->filled('type')) {
            $query->where('game_type', $request->type);
        }

        $games = $query->paginate(20)->withQueryString();
        return view('admin.games.index', compact('games'));
    }

    // ── CREATE ───────────────────────────────────────────────
    public function create()
    {
        return view('admin.games.create', $this->formData());
    }

    // ── STORE ────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $this->validateGame($request);

        DB::transaction(function () use ($request, $validated) {
            $game = Game::create(array_merge($validated, [
                'is_active' => $request->boolean('is_active', true),
            ]));
            $game->genres()->sync($request->input('genres', []));
            $game->features()->sync($request->input('features', []));
            $game->tags()->sync($request->input('tags', []));
            $game->platforms()->sync($request->input('platforms', []));
            $this->saveAgeRating($game, $request);
        });

        return redirect()->route('admin.games.index')
            ->with('success', 'Game berhasil ditambahkan.');
    }

    // ── EDIT ─────────────────────────────────────────────────
    public function edit(Game $game)
    {
        $game->load(['publisher','developer','genres','features','tags','platforms','ageRating']);

        $data = $this->formData($game);
        $data['selectedGenres']    = $game->genres->pluck('genre_id')->toArray();
        $data['selectedFeatures']  = $game->features->pluck('feature_id')->toArray();
        $data['selectedTags']      = $game->tags->pluck('tag_id')->toArray();
        $data['selectedPlatforms'] = $game->platforms->pluck('platform_id')->toArray();

        return view('admin.games.edit', array_merge(['game' => $game], $data));
    }

    // ── UPDATE ───────────────────────────────────────────────
    public function update(Request $request, Game $game)
    {
        $validated = $this->validateGame($request, $game->game_id);

        DB::transaction(function () use ($request, $validated, $game) {
            $game->update(array_merge($validated, [
                'is_active' => $request->boolean('is_active', true),
            ]));
            $game->genres()->sync($request->input('genres', []));
            $game->features()->sync($request->input('features', []));
            $game->tags()->sync($request->input('tags', []));
            $game->platforms()->sync($request->input('platforms', []));
            $this->saveAgeRating($game, $request);
        });

        return redirect()->route('admin.games.index')
            ->with('success', "Game \"{$game->title}\" berhasil diperbarui.");
    }

    // ── DESTROY — nonaktifkan (soft deactivate) ───────────────
    public function destroy(Game $game)
    {
        $game->update(['is_active' => false]);
        return redirect()->route('admin.games.index')
            ->with('success', "Game \"{$game->title}\" telah dinonaktifkan.");
    }

    // ── RESTORE — aktifkan kembali ────────────────────────────
    public function restore(Game $game)
    {
        $game->update(['is_active' => true]);
        return redirect()->route('admin.games.index')
            ->with('success', "Game \"{$game->title}\" telah diaktifkan kembali.");
    }

    // ── HELPERS ──────────────────────────────────────────────
    private function formData(?Game $game = null): array
    {
        return [
            'publishers'  => Publisher::orderBy('name')->get(),
            'developers'  => Developer::orderBy('name')->get(),
            'genres'      => Genre::orderBy('name')->get(),
            'features'    => Feature::orderBy('name')->get(),
            'tags'        => Tag::orderBy('label')->get(),
            'platforms'   => Platform::orderBy('platform')->get(),
            'parentGames' => Game::where('game_type','base_game')
                ->when($game, fn($q) => $q->where('game_id','!=',$game->game_id))
                ->orderBy('title')->get(['game_id','title']),
        ];
    }

    private function validateGame(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'           => ['required','string','max:200'],
            'info'            => ['nullable','in:First_Run,Now_On_Epic,Trial_Available'],
            'media_url'       => ['nullable','url','max:255'],
            'main_desc'       => ['nullable','string','max:5000'],
            'announce'        => ['nullable','string','max:2000'],
            'desc'            => ['nullable','string'],
            'icon_url'        => ['nullable','url','max:255'],
            'base_price'      => ['required','numeric','min:0','max:9999999'],
            'release_date'    => ['nullable','date'],
            'publisher_id'    => ['nullable','exists:publishers,publisher_id'],
            'developer_id'    => ['nullable','exists:developers,developer_id'],
            'cover_image_url' => ['nullable','url','max:255'],
            'game_type'       => ['required','in:base_game,edition,addon,aplikasi,editor,langganan,pengalaman,bundle,demo'],
            'parent_game_id'  => ['nullable','exists:games,game_id'],
            'avg_rating'      => ['nullable','numeric','min:0','max:5'],
            'refund_type'     => ['nullable','in:refundable,self_refundable,non_refundable'],
            'genres.*'        => ['integer','exists:genres,genre_id'],
            'features.*'      => ['integer','exists:features,feature_id'],
            'tags.*'          => ['integer','exists:tags,tag_id'],
            'platforms.*'     => ['integer','exists:platform,platform_id'],
            'age'             => ['nullable','string','max:10'],
            'age_desc'        => ['nullable','string','max:50'],
        ]);
    }

    private function saveAgeRating(Game $game, Request $request): void
    {
        $age = $request->input('age');
        if (!empty($age)) {
            Age::updateOrCreate(
                ['game_id' => $game->game_id],
                ['age' => $age, 'desc' => $request->input('age_desc')]
            );
        } else {
            Age::where('game_id', $game->game_id)->delete();
        }
    }
}
