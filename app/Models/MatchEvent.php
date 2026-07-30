<?php

namespace App\Models;

use Database\Factories\MatchEventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchEvent extends Model
{
    /** @use HasFactory<MatchEventFactory> */
    use HasFactory;

    public const TYPE_GOAL = 'goal';

    public const TEAM_ZEITLOS = 'zeitlos';

    public const TEAM_OPPONENT = 'opponent';

    protected $fillable = [
        'match_id',
        'scorer_id',
        'assist_player_id',
        'event_type',
        'team',
        'minute',
    ];

    protected function casts(): array
    {
        return [
            'minute' => 'integer',
        ];
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    public function scorer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'scorer_id');
    }

    public function assistPlayer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'assist_player_id');
    }
}
