<?php

namespace App\Models;

use Database\Factories\MatchRosterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchRoster extends Model
{
    /** @use HasFactory<MatchRosterFactory> */
    use HasFactory;

    public const ROLE_PLAYER = 'player';

    public const ROLE_GOALKEEPER = 'goalkeeper';

    protected $fillable = [
        'match_id',
        'player_id',
        'guest_name',
        'role',
    ];

    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
