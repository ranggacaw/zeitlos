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

    protected int|string|array $columnSpan = 'full';

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
            'scheduledMatchCount' => FootballMatch::scheduled()->count(),
            'liveMatch' => $this->matchSummary($liveMatch),
            'nextMatch' => $this->matchSummary($nextMatch),
            'nextMatchDetail' => $this->matchDetail($nextMatch),
            'recentResult' => $this->matchSummary($recentResult),
            'recentResultDetail' => $this->matchDetail($recentResult),
            'liveMatchTitle' => $liveMatch ? "Zeitlos vs {$liveMatch->opponent}" : null,
            'liveMatchScore' => $liveMatch ? ($liveMatch->zeitlos_score ?? 0).':'.($liveMatch->opponent_score ?? 0) : null,
            'liveMatchUrl' => $liveMatch ? FootballMatchResource::getUrl('live-scoring', ['record' => $liveMatch]) : null,
            'nextMatchUrl' => $nextMatch ? FootballMatchResource::getUrl('edit', ['record' => $nextMatch]) : null,
            'nextMatchRosterUrl' => $nextMatch ? FootballMatchResource::getUrl('rosters', ['record' => $nextMatch]) : null,
            'playersUrl' => PlayerResource::getUrl('index'),
            'createPlayerUrl' => PlayerResource::getUrl('create'),
            'matchesUrl' => FootballMatchResource::getUrl('index'),
            'createMatchUrl' => FootballMatchResource::getUrl('create'),
            'leaderboardUrl' => Leaderboard::getUrl(),
            'openTaskCount' => $this->openTaskCount($liveMatch, $nextMatch),
            'openTaskSummary' => $this->openTaskSummary($liveMatch, $nextMatch),
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

    private function matchDetail(?FootballMatch $match): ?string
    {
        if (! $match) {
            return null;
        }

        $parts = array_filter([
            $match->match_date?->format('D, M j'),
            $match->match_time,
            $match->venue,
        ]);

        return implode(' · ', $parts);
    }

    private function openTaskCount(?FootballMatch $liveMatch, ?FootballMatch $nextMatch): int
    {
        return collect([
            $liveMatch !== null,
            $nextMatch !== null,
            FootballMatch::scheduled()->exists(),
        ])->filter()->count();
    }

    private function openTaskSummary(?FootballMatch $liveMatch, ?FootballMatch $nextMatch): string
    {
        if ($liveMatch) {
            return 'Live score and roster checks';
        }

        if ($nextMatch) {
            return 'Roster and schedule checks';
        }

        return 'Create the next match';
    }
}
