<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\Player;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Inertia\Inertia;
use Inertia\Response;

class MatchScoringController extends Controller
{
    public function index(FootballMatch $match): Response
    {
        $match->load(['rosterEntries.player', 'events.scorer', 'events.assistPlayer']);

        return Inertia::render('Admin/Matches/Scoring', [
            'match' => $match,
            'scoringPlayers' => $this->scoringPlayers($match),
            'events' => $this->serializeEvents($match),
        ]);
    }

    private function scoringPlayers(FootballMatch $match): array
    {
        $players = $match->rosterEntries
            ->map(fn ($entry) => $entry->player)
            ->filter();

        if ($players->isEmpty()) {
            $players = Player::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        return $players
            ->map(fn (Player $player) => [
                'id' => $player->id,
                'name' => $player->name,
                'jersey_number' => $player->jersey_number,
                'position' => $player->position,
            ])
            ->sortBy('name')
            ->values()
            ->all();
    }

    /**
     * @param  EloquentCollection<int, MatchEvent>  $events
     */
    private function serializeEvents(FootballMatch $match): array
    {
        return $match->events
            ->sortBy([['minute', 'asc'], ['id', 'asc']])
            ->map(fn (MatchEvent $event) => [
                'id' => $event->id,
                'scorer' => $event->scorer?->name,
                'assist' => $event->assistPlayer?->name,
                'minute' => $event->minute,
            ])
            ->values()
            ->all();
    }
}
