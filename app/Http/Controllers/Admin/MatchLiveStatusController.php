<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use Illuminate\Http\RedirectResponse;

class MatchLiveStatusController extends Controller
{
    public function store(FootballMatch $match): RedirectResponse
    {
        $match->update([
            'status' => FootballMatch::STATUS_LIVE,
        ]);

        return redirect()
            ->route('admin.matches.scoring.index', $match)
            ->with('status', 'Match marked live.');
    }
}
