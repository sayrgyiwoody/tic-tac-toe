<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    /** @use HasFactory<\Database\Factories\GameFactory> */
    use HasFactory;

    protected $fillable = [
        'player_one_id',
        'player_two_id',
        'state',
        'current_player_id',
        'starting_player_id',
    ];

    protected function casts() : array
    {
        return [
            'state' => 'json'
        ];
    }

    public function playerOne() : BelongsTo
    {
        return $this->belongsTo(User::class, 'player_one_id');
    }

    public function playerTwo() : BelongsTo
    {
        return $this->belongsTo(User::class, 'player_two_id');
    }

    public function players() : array
    {
        return [
            $this->playerOne,
            $this->playerTwo
        ];
    }

    public function currentPlayer() : BelongsTo
    {
        return $this->belongsTo(User::class, 'current_player_id');
    }

    public function startingPlayer() : BelongsTo
    {
        return $this->belongsTo(User::class, 'starting_player_id');
    }
}
