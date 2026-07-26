<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatchEvent;
use App\Models\Player;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LeaderboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Leaderboard/Index', [
            'players' => Player::query()
                ->orderByDesc('is_active')
                ->orderByRaw('jersey_number is null')
                ->orderBy('jersey_number')
                ->orderBy('name')
                ->get()
                ->map(fn (Player $player) => $this->serializePlayer($player))
                ->values(),
        ]);
    }

    public function update(Request $request, Player $player): RedirectResponse
    {
        $validated = $request->validate([
            'goals_adjustment' => ['required', 'integer'],
            'assists_adjustment' => ['required', 'integer'],
        ]);

        $player->update($validated);

        return redirect()
            ->route('admin.leaderboard.index')
            ->with('status', 'Leaderboard correction saved.');
    }

    private function serializePlayer(Player $player): array
    {
        $eventGoals = $player->scoredEvents()
            ->where('event_type', MatchEvent::TYPE_GOAL)
            ->count();
        $eventAssists = $player->assistedEvents()
            ->where('event_type', MatchEvent::TYPE_GOAL)
            ->count();

        return [
            'id' => $player->id,
            'name' => $player->name,
            'jersey_number' => $player->jersey_number,
            'position' => $player->position,
            'is_active' => $player->is_active,
            'event_goals' => $eventGoals,
            'event_assists' => $eventAssists,
            'goals_adjustment' => $player->goals_adjustment,
            'assists_adjustment' => $player->assists_adjustment,
            'goals' => $eventGoals + $player->goals_adjustment,
            'assists' => $eventAssists + $player->assists_adjustment,
        ];
    }
}
