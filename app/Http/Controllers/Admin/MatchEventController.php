<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use App\Models\MatchEvent;
use App\Models\Player;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatchEventController extends Controller
{
    public function store(Request $request, FootballMatch $match): RedirectResponse
    {
        $validated = $request->validate([
            'scorer_id' => ['required', 'integer', Rule::exists(Player::class, 'id')],
            'assist_player_id' => ['nullable', 'integer', Rule::exists(Player::class, 'id')],
            'minute' => ['nullable', 'integer', 'min:0'],
        ]);

        MatchEvent::create([
            'match_id' => $match->id,
            'scorer_id' => $validated['scorer_id'],
            'assist_player_id' => $validated['assist_player_id'] ?? null,
            'event_type' => MatchEvent::TYPE_GOAL,
            'minute' => $validated['minute'] ?? null,
        ]);

        return redirect()
            ->route('admin.matches.scoring.index', $match)
            ->with('status', 'Goal recorded.');
    }

    public function destroy(FootballMatch $match, MatchEvent $event): RedirectResponse
    {
        $event->delete();

        return redirect()
            ->route('admin.matches.scoring.index', $match)
            ->with('status', 'Goal removed.');
    }
}
