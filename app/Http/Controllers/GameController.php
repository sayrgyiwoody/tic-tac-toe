<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Game;
use App\Events\GameJoined;
use App\Events\GameCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $games = Game::with('playerOne', 'playerTwo')
            ->whereNull('player_two_id')
            ->where('player_one_id', '!=', $request->user()->id)
            ->when($search, function ($query, $search) {
                $query->whereHas('playerOne', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(21);


        return inertia('Dashboard', [
            'games' => $games
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if a game with player_one_id and player_two_id null as user id already exists
        $existingGame = Game::where('player_one_id', $request->user()->id)
                    ->whereNull('player_two_id')
                    ->first();

        if ($existingGame) {
            $existingGame->delete();
        }

        $game = Game::create(['player_one_id' => $request->user()->id]);
        $game->load('playerOne','playerTwo');

        GameCreated::dispatch($game);

        return to_route('games.show', $game);
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        Gate::authorize('view', $game);

        $game->load('playerOne','playerTwo');
        return inertia('Games/Show', [
            'game' => $game
        ]);
    }

    public function join(Request $request, Game $game)
    {
        Gate::authorize('join', $game);

        $game->update(['player_two_id' => $request->user()->id]);
        GameJoined::dispatch($game);

        return to_route('games.show', $game);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        Gate::authorize('update', $game);

        $data = $request->validate([
            'state' => ['required', 'array', 'size:9'],
            'state.*' => ['integer', 'between:-1,1']
        ]);

        $game->update($data);


        return to_route('games.show', $game);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}