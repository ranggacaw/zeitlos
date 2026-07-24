<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MatchFinalScoreController extends Controller
{
    public function store(Request $request, FootballMatch $match): RedirectResponse
    {
        $validated = $request->validate([
            'zeitlos_score' => ['required', 'integer', 'min:0'],
            'opponent_score' => ['required', 'integer', 'min:0'],
        ]);

        $match->update([
            'status' => FootballMatch::STATUS_FINISHED,
            'zeitlos_score' => $validated['zeitlos_score'],
            'opponent_score' => $validated['opponent_score'],
        ]);

        return redirect()
            ->route('admin.matches.scoring.index', $match)
            ->with('status', 'Match finalized.');
    }
}
