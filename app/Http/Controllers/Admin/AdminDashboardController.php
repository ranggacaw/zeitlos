<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use App\Models\Player;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function index(): Response
    {
        $players = Player::query()->get();
        $leaders = $players
            ->map(fn (Player $player) => $this->serializeLeaderboardPlayer($player))
            ->sortByDesc(fn (array $player) => [$player['goals'], $player['assists'], $player['name']])
            ->values();

        return Inertia::render('Admin/Dashboard', [
            'playerCount' => $players->count(),
            'activePlayerCount' => $players->where('is_active', true)->count(),
            'matchCount' => FootballMatch::count(),
            'liveMatch' => $this->serializeMatch($this->liveMatch()),
            'nextMatch' => $this->serializeMatch($this->nextMatch()),
            'recentResult' => $this->serializeMatch($this->recentResult()),
            'topScorers' => $leaders->take(3)->values(),
            'topAssists' => $leaders
                ->sortByDesc(fn (array $player) => [$player['assists'], $player['goals'], $player['name']])
                ->take(3)
                ->values(),
            'recentMatches' => FootballMatch::query()
                ->latest('updated_at')
                ->take(5)
                ->get()
                ->map(fn (FootballMatch $match) => $this->serializeMatch($match))
                ->values(),
        ]);
    }

    private function liveMatch(): ?FootballMatch
    {
        return FootballMatch::query()
            ->live()
            ->orderBy('match_date')
            ->orderBy('match_time')
            ->first();
    }

    private function nextMatch(): ?FootballMatch
    {
        return FootballMatch::query()
            ->scheduled()
            ->orderBy('match_date')
            ->orderBy('match_time')
            ->first();
    }

    private function recentResult(): ?FootballMatch
    {
        return FootballMatch::query()
            ->finished()
            ->latest('match_date')
            ->first();
    }

    private function serializeMatch(?FootballMatch $match): ?array
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
            'status' => $match->status,
            'zeitlos_score' => $match->zeitlos_score,
            'opponent_score' => $match->opponent_score,
        ];
    }

    private function serializeLeaderboardPlayer(Player $player): array
    {
        return [
            'id' => $player->id,
            'name' => $player->name,
            'jersey_number' => $player->jersey_number,
            'position' => $player->position,
            'goals' => $player->goalsCount(),
            'assists' => $player->assistsCount(),
        ];
    }
}
