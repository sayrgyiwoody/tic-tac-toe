<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;

class GamePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function join(User $user, Game $game)
    {
        return $game->player_one_id !== $user->id && $game->player_two_id === null;
    }

    public function view(User $user, Game $game)
    {
        return $user->id === $game->player_one_id || $user->id === $game->player_two_id;
    }

    public function update(User $user, Game $game)
    {
        return $user->id === $game->player_one_id || $user->id === $game->player_two_id;
    }
}
