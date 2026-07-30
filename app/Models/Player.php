<?php

namespace App\Models;

use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    /** @use HasFactory<PlayerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'jersey_number',
        'position',
        'is_active',
        'photo_path',
        'joined_at',
        'goals_adjustment',
        'assists_adjustment',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'joined_at' => 'date',
            'goals_adjustment' => 'integer',
            'assists_adjustment' => 'integer',
        ];
    }

    public function rosterEntries(): HasMany
    {
        return $this->hasMany(MatchRoster::class);
    }

    public function scoredEvents(): HasMany
    {
        return $this->hasMany(MatchEvent::class, 'scorer_id');
    }

    public function assistedEvents(): HasMany
    {
        return $this->hasMany(MatchEvent::class, 'assist_player_id');
    }

    public function goalsCount(): int
    {
        return $this->scoredEvents()
            ->where('event_type', MatchEvent::TYPE_GOAL)
            ->where('team', MatchEvent::TEAM_ZEITLOS)
            ->count() + $this->goals_adjustment;
    }

    public function assistsCount(): int
    {
        return $this->assistedEvents()
            ->where('event_type', MatchEvent::TYPE_GOAL)
            ->where('team', MatchEvent::TEAM_ZEITLOS)
            ->count() + $this->assists_adjustment;
    }
}
