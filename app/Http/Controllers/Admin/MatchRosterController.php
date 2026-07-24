<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use App\Models\MatchRoster;
use App\Models\Player;
use App\Team\WhatsAppRosterText;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class MatchRosterController extends Controller
{
    public function __construct(private readonly WhatsAppRosterText $whatsappRosterText) {}

    public function index(FootballMatch $match): Response
    {
        $match->load('rosterEntries.player');

        return Inertia::render('Admin/Matches/Roster', [
            'match' => $match,
            'rosterEntries' => $this->serializeRoster($match),
            'availablePlayers' => Player::query()
                ->orderBy('name')
                ->get(['id', 'name', 'jersey_number', 'position']),
            'whatsappText' => $this->whatsappRosterText->build($match, $match->rosterEntries),
        ]);
    }

    public function store(Request $request, FootballMatch $match): RedirectResponse
    {
        $validated = $request->validate([
            'player_id' => ['nullable', 'integer', Rule::exists(Player::class, 'id')],
            'guest_name' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::in([MatchRoster::ROLE_PLAYER, MatchRoster::ROLE_GOALKEEPER])],
        ]);

        if (filled($validated['player_id'] ?? null) && filled($validated['guest_name'] ?? null)) {
            throw ValidationException::withMessages([
                'player_id' => 'Choose either an existing player or a guest name, not both.',
            ]);
        }

        if (blank($validated['player_id'] ?? null) && blank($validated['guest_name'] ?? null)) {
            throw ValidationException::withMessages([
                'guest_name' => 'Select a player or enter a guest name.',
            ]);
        }

        MatchRoster::create([
            'match_id' => $match->id,
            'player_id' => $validated['player_id'] ?? null,
            'guest_name' => $validated['guest_name'] ?? null,
            'role' => $validated['role'],
        ]);

        return redirect()
            ->route('admin.matches.roster.index', $match)
            ->with('status', 'Roster entry added.');
    }

    public function destroy(FootballMatch $match, MatchRoster $roster): RedirectResponse
    {
        $roster->delete();

        return redirect()
            ->route('admin.matches.roster.index', $match)
            ->with('status', 'Roster entry removed.');
    }

    private function serializeRoster(FootballMatch $match): array
    {
        return $match->rosterEntries
            ->map(fn (MatchRoster $entry) => [
                'id' => $entry->id,
                'name' => $entry->player?->name ?? $entry->guest_name,
                'role' => $entry->role,
                'is_guest' => $entry->player_id === null,
            ])
            ->sortBy('name')
            ->values()
            ->all();
    }
}
