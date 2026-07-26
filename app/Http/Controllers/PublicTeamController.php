<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\MatchRoster;
use App\Models\Player;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;

class PublicTeamController extends Controller
{
    public function dashboard(): Response
    {
        return Inertia::render('Welcome', [
            'upcomingMatch' => $this->serializeMatch($this->upcomingMatches()->first(), true),
            'recentResult' => $this->serializeMatch($this->finishedMatches()->first(), true),
            'players' => $this->activePlayers()->map(fn (Player $player) => $this->serializePlayer($player))->values(),
            'leaders' => $this->leaderboardPlayers()->take(5)->values(),
        ]);
    }

    public function player(Player $player): Response
    {
        abort_unless($player->is_active, 404);

        return Inertia::render('Public/PlayerShow', [
            'player' => $this->serializePlayer($player),
            'matches' => $player->rosterEntries()
                ->with(['match.rosterEntries.player', 'match.events.scorer', 'match.events.assistPlayer'])
                ->latest('id')
                ->get()
                ->pluck('match')
                ->filter()
                ->unique('id')
                ->map(fn (FootballMatch $match) => $this->serializeMatch($match, true))
                ->values(),
        ]);
    }

    public function schedule(): Response
    {
        return Inertia::render('Public/Schedule', [
            'upcomingMatches' => $this->upcomingMatches()->map(fn (FootballMatch $match) => $this->serializeMatch($match, true))->values(),
            'finishedMatches' => $this->finishedMatches()->map(fn (FootballMatch $match) => $this->serializeMatch($match, true))->values(),
        ]);
    }

    public function roster(): Response
    {
        return Inertia::render('Public/Roster', [
            'players' => $this->activePlayers()->map(fn (Player $player) => $this->serializePlayer($player))->values(),
        ]);
    }

    public function leaderboard(): Response
    {
        return Inertia::render('Public/Leaderboard', [
            'leaders' => $this->leaderboardPlayers()->values(),
        ]);
    }

    private function activePlayers(): Collection
    {
        if (! Schema::hasTable('players')) {
            return collect();
        }

        return Player::query()
            ->where('is_active', true)
            ->orderByRaw('jersey_number is null')
            ->orderBy('jersey_number')
            ->orderBy('name')
            ->get();
    }

    private function upcomingMatches(): Collection
    {
        if (! Schema::hasTable('matches')) {
            return collect();
        }

        return FootballMatch::query()
            ->scheduled()
            ->with(['rosterEntries.player', 'events.scorer', 'events.assistPlayer'])
            ->orderBy('match_date')
            ->orderBy('match_time')
            ->get();
    }

    private function finishedMatches(): Collection
    {
        if (! Schema::hasTable('matches')) {
            return collect();
        }

        return FootballMatch::query()
            ->finished()
            ->with(['rosterEntries.player', 'events.scorer', 'events.assistPlayer'])
            ->latest('match_date')
            ->get();
    }

    private function leaderboardPlayers(): Collection
    {
        return $this->activePlayers()
            ->map(fn (Player $player) => $this->serializePlayer($player))
            ->sortByDesc(fn (array $player) => [$player['goals'], $player['assists'], $player['name']])
            ->values();
    }

    private function serializePlayer(Player $player): array
    {
        return [
            'id' => $player->id,
            'name' => $player->name,
            'jersey_number' => $player->jersey_number,
            'position' => $player->position,
            'photo_path' => $player->photo_path,
            'joined_at' => $player->joined_at?->toDateString(),
            'goals' => $player->goalsCount(),
            'assists' => $player->assistsCount(),
        ];
    }

    private function serializeMatch(?FootballMatch $match, bool $includeRoster = false): ?array
    {
        if (! $match) {
            return null;
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
            'zeitlos_score' => $match->zeitlos_score,
            'opponent_score' => $match->opponent_score,
            'roster' => $includeRoster ? $this->serializeRoster($match->rosterEntries) : [],
            'events' => $match->events->map(fn (MatchEvent $event) => [
                'minute' => $event->minute,
                'event_type' => $event->event_type,
                'scorer' => $event->scorer?->name,
                'assist' => $event->assistPlayer?->name,
            ])->values(),
        ];
    }

    private function serializeRoster(Collection $entries): array
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
