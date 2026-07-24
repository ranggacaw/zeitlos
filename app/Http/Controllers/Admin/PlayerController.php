<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PlayerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Players/Index', [
            'players' => Player::query()
                ->orderBy('jersey_number')
                ->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Players/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePlayer($request);

        Player::create($validated);

        return redirect()
            ->route('admin.players.index')
            ->with('status', 'Player created.');
    }

    public function edit(Player $player): Response
    {
        return Inertia::render('Admin/Players/Edit', [
            'player' => $player,
        ]);
    }

    public function update(Request $request, Player $player): RedirectResponse
    {
        $validated = $this->validatePlayer($request, $player);

        $player->update($validated);

        return redirect()
            ->route('admin.players.index')
            ->with('status', 'Player updated.');
    }

    public function destroy(Player $player): RedirectResponse
    {
        $player->delete();

        return redirect()
            ->route('admin.players.index')
            ->with('status', 'Player deleted.');
    }

    private function validatePlayer(Request $request, ?Player $player = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'jersey_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique(Player::class, 'jersey_number')->ignore($player?->id),
            ],
            'position' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'photo_path' => ['nullable', 'string', 'max:255'],
            'joined_at' => ['nullable', 'date'],
            'goals_adjustment' => ['required', 'integer'],
            'assists_adjustment' => ['required', 'integer'],
        ]);
    }
}
