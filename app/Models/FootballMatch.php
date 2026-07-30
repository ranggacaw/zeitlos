<?php

namespace App\Models;

use Database\Factories\FootballMatchFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FootballMatch extends Model
{
    /** @use HasFactory<FootballMatchFactory> */
    use HasFactory;

    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_STARTING = 'starting';

    public const STATUS_LIVE = 'live';

    public const STATUS_FINISHED = 'finished';

    protected $table = 'matches';

    protected $fillable = [
        'opponent',
        'match_date',
        'match_time',
        'venue',
        'maps_url',
        'ticket_price',
        'dress_code',
        'facilities',
        'notes',
        'payment_label',
        'payment_amount',
        'payment_due_at',
        'payment_instructions',
        'whatsapp_announcement',
        'status',
        'zeitlos_score',
        'opponent_score',
    ];

    protected function casts(): array
    {
        return [
            'match_date' => 'date',
            'payment_due_at' => 'datetime',
            'ticket_price' => 'decimal:2',
            'payment_amount' => 'decimal:2',
            'zeitlos_score' => 'integer',
            'opponent_score' => 'integer',
        ];
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    public function scopeFinished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_FINISHED);
    }

    public function scopeStarting(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_STARTING);
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_LIVE);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_STARTING, self::STATUS_LIVE]);
    }

    public function rosterEntries(): HasMany
    {
        return $this->hasMany(MatchRoster::class, 'match_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(MatchEvent::class, 'match_id');
    }

    public function goalCountForTeam(string $team): int
    {
        if ($this->relationLoaded('events')) {
            return $this->events
                ->where('event_type', MatchEvent::TYPE_GOAL)
                ->where('team', $team)
                ->count();
        }

        return $this->events()
            ->where('event_type', MatchEvent::TYPE_GOAL)
            ->where('team', $team)
            ->count();
    }

    public function liveZeitlosScore(): int
    {
        return $this->goalCountForTeam(MatchEvent::TEAM_ZEITLOS);
    }

    public function liveOpponentScore(): int
    {
        return $this->goalCountForTeam(MatchEvent::TEAM_OPPONENT);
    }

    public function recalculateLiveScore(): void
    {
        $this->load('events');

        $this->forceFill([
            'zeitlos_score' => $this->liveZeitlosScore(),
            'opponent_score' => $this->liveOpponentScore(),
        ])->save();
    }
}
