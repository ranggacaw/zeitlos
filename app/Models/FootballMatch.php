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

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_LIVE);
    }

    public function rosterEntries(): HasMany
    {
        return $this->hasMany(MatchRoster::class, 'match_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(MatchEvent::class, 'match_id');
    }
}
