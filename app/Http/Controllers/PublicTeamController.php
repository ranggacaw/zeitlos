<?php

namespace App\Http\Controllers;

use App\Models\FootballMatch;
use App\Models\Player;
use App\Team\PublicMatchPresenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;

class PublicTeamController extends Controller
{
    public function __construct(private readonly PublicMatchPresenter $matches) {}

    public function dashboard(Request $request): Response|RedirectResponse
    {
        if ($request->user()?->isAdmin()) {
            return redirect('/admin');
        }

        return Inertia::render('Welcome', [
            'activeMatch' => $this->serializeMatch($this->activeMatches()->first(), true),
            'upcomingMatch' => $this->serializeMatch($this->upcomingMatches()->first(), true),
            'recentResult' => $this->serializeMatch($this->finishedMatches()->first(), true),
            'players' => $this->activePlayers()->map(fn (Player $player) => $this->serializeRosterPlayer($player))->values(),
            'leaders' => $this->leaderboardPlayers()->take(5)->values(),
        ]);
    }

    public function live(FootballMatch $match): Response
    {
        abort_unless(in_array($match->status, [
            FootballMatch::STATUS_STARTING,
            FootballMatch::STATUS_LIVE,
            FootballMatch::STATUS_FINISHED,
        ], true), 404);

        return Inertia::render('Public/MatchLive', [
            'match' => $this->serializeMatch($match, true),
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
            'activeMatches' => $this->activeMatches()->map(fn (FootballMatch $match) => $this->serializeMatch($match, true))->values(),
            'upcomingMatches' => $this->upcomingMatches()->map(fn (FootballMatch $match) => $this->serializeMatch($match, true))->values(),
            'finishedMatches' => $this->finishedMatches()->map(fn (FootballMatch $match) => $this->serializeMatch($match, true))->values(),
        ]);
    }

    public function roster(): Response
    {
        return Inertia::render('Public/Roster', [
            'players' => $this->activePlayers()->map(fn (Player $player) => $this->serializeRosterPlayer($player))->values(),
        ]);
    }

    public function leaderboard(Request $request): Response
    {
        $selectedStat = in_array($request->query('stat'), ['goals', 'assists', 'appearances'], true)
            ? $request->query('stat')
            : 'appearances';

        return Inertia::render('Public/Leaderboard', [
            'leaders' => $this->leaderboardPlayers($selectedStat)->values(),
            'selectedStat' => $selectedStat,
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

    private function activeMatches(): Collection
    {
        if (! Schema::hasTable('matches')) {
            return collect();
        }

        return FootballMatch::query()
            ->active()
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

    private function leaderboardPlayers(string $selectedStat = 'appearances'): Collection
    {
        $secondaryStats = match ($selectedStat) {
            'assists' => ['goals', 'appearances'],
            'appearances' => ['goals', 'assists'],
            default => ['assists', 'appearances'],
        };

        return $this->activePlayers()
            ->map(fn (Player $player) => $this->serializeLeaderboardPlayer($player))
            ->sort(function (array $a, array $b) use ($selectedStat, $secondaryStats): int {
                return $b[$selectedStat] <=> $a[$selectedStat]
                    ?: $b[$secondaryStats[0]] <=> $a[$secondaryStats[0]]
                    ?: $b[$secondaryStats[1]] <=> $a[$secondaryStats[1]]
                    ?: $a['name'] <=> $b['name'];
            })
            ->values();
    }

    private function serializeRosterPlayer(Player $player): array
    {
        $data = [
            'id' => $player->id,
            'name' => $player->name,
            'jersey_number' => $player->jersey_number,
            'position' => $player->position,
            'goals' => $player->goalsCount(),
            'assists' => $player->assistsCount(),
            'appearances' => $player->appearancesCount(),
        ];

        if ($player->photo_path) {
            $data['photo_path'] = $player->photo_path;
        }

        return $data;
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
            'appearances' => $player->appearancesCount(),
        ];
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
            'appearances' => $player->appearancesCount(),
        ];
    }

    private function serializeMatch(?FootballMatch $match, bool $includeRoster = false): ?array
    {
        return $this->matches->present($match, $includeRoster);
    }
}
