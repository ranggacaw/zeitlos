<?php

namespace App\Team;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\MatchRoster;
use Illuminate\Support\Collection;

class PublicMatchPresenter
{
    public function present(?FootballMatch $match, bool $includeRoster = false): ?array
    {
        if (! $match) {
            return null;
        }

        $match->loadMissing(['events.scorer', 'events.assistPlayer']);

        if ($includeRoster) {
            $match->loadMissing('rosterEntries.player');
        }

        return [
            'id' => $match->id,
            'opponent' => $match->opponent,
            'match_date' => $match->match_date?->toDateString(),
            'match_time' => $match->match_time,
            'venue' => $match->venue,
            'maps_url' => $match->maps_url,
            'ticket_price' => $match->ticket_price,
            'dress_code' => $match->dress_code,
            'facilities' => $match->facilities,
            'notes' => $match->notes,
            'payment_label' => $match->payment_label,
            'payment_amount' => $match->payment_amount,
            'payment_due_at' => $match->payment_due_at?->toDateTimeString(),
            'payment_instructions' => $match->payment_instructions,
            'whatsapp_announcement' => $match->whatsapp_announcement,
            'status' => $match->status,
            'zeitlos_score' => $this->score($match, MatchEvent::TEAM_ZEITLOS),
            'opponent_score' => $this->score($match, MatchEvent::TEAM_OPPONENT),
            'roster' => $includeRoster ? $this->roster($match->rosterEntries) : [],
            'events' => $match->events
                ->sortBy(fn (MatchEvent $event) => $event->minute ?? PHP_INT_MAX)
                ->map(fn (MatchEvent $event) => [
                    'minute' => $event->minute,
                    'event_type' => $event->event_type,
                    'team' => $event->team,
                    'scorer' => $event->team === MatchEvent::TEAM_OPPONENT ? 'Enemy team' : $event->scorer?->name,
                    'assist' => $event->assistPlayer?->name,
                ])
                ->values(),
        ];
    }

    private function score(FootballMatch $match, string $team): int
    {
        if ($match->status === FootballMatch::STATUS_FINISHED) {
            return $team === MatchEvent::TEAM_OPPONENT
                ? ($match->opponent_score ?? $match->liveOpponentScore())
                : ($match->zeitlos_score ?? $match->liveZeitlosScore());
        }

        return $team === MatchEvent::TEAM_OPPONENT
            ? $match->liveOpponentScore()
            : $match->liveZeitlosScore();
    }

    private function roster(Collection $entries): array
    {
        return $entries
            ->map(fn (MatchRoster $entry) => [
                'id' => $entry->id,
                'name' => $entry->player?->name ?? $entry->guest_name,
                'role' => $entry->role,
                'jersey_number' => $entry->player?->jersey_number,
                'position' => $entry->player?->position,
            ])
            ->groupBy('role')
            ->map(fn (Collection $group) => $group->values())
            ->all();
    }
}
