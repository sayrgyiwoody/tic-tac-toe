<?php

use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('lobby',function(User $user){
    return True;
});

Broadcast::channel('games', function (User $user) {
    return True;
});

Broadcast::channel('game.{game}', function (User $user,Game $game) {
    if(! in_array($user->id, [$game->player_one_id, $game->player_two_id])){
        return False;
    }
    return ['id'=>$user->id];
});
