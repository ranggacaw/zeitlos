<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\Leaderboard;
use App\Filament\Resources\FootballMatches\FootballMatchResource;
use App\Filament\Resources\Players\PlayerResource;
use App\Models\FootballMatch;
use App\Models\Player;
use Filament\Widgets\Widget;

class AdminOverview extends Widget
{
    protected string $view = 'filament.widgets.admin-overview';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $players = Player::query()->get();
        $liveMatch = $this->liveMatch();
        $nextMatch = $this->nextMatch();
        $recentResult = $this->recentResult();
        $leaders = $players
            ->map(fn (Player $player) => [
                'name' => $player->name,
                'goals' => $player->goalsCount(),
                'assists' => $player->assistsCount(),
            ])
            ->sortByDesc(fn (array $player) => [$player['goals'], $player['assists'], $player['name']])
            ->values();

        return [
            'playerCount' => $players->count(),
            'activePlayerCount' => $players->where('is_active', true)->count(),
            'matchCount' => FootballMatch::count(),
            'liveMatch' => $this->matchSummary($liveMatch),
            'nextMatch' => $this->matchSummary($nextMatch),
            'recentResult' => $this->matchSummary($recentResult),
            'liveMatchUrl' => $liveMatch ? FootballMatchResource::getUrl('live-scoring', ['record' => $liveMatch]) : null,
            'nextMatchUrl' => $nextMatch ? FootballMatchResource::getUrl('edit', ['record' => $nextMatch]) : null,
            'playersUrl' => PlayerResource::getUrl('index'),
            'matchesUrl' => FootballMatchResource::getUrl('index'),
            'leaderboardUrl' => Leaderboard::getUrl(),
            'topScorers' => $leaders->take(3)->values(),
            'topAssists' => $leaders
                ->sortByDesc(fn (array $player) => [$player['assists'], $player['goals'], $player['name']])
                ->take(3)
                ->values(),
        ];
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

    private function matchSummary(?FootballMatch $match): ?string
    {
        if (! $match) {
            return null;
        }

        $date = $match->match_date?->format('M j');
        $score = $match->status === FootballMatch::STATUS_FINISHED
            ? " ({$match->zeitlos_score}:{$match->opponent_score})"
            : '';

        return trim("{$match->opponent}{$score} {$date}");
    }
}
