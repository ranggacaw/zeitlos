<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class MatchController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Matches/Index', [
            'matches' => FootballMatch::query()
                ->orderBy('match_date')
                ->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Matches/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        FootballMatch::create($this->validateMatch($request));

        return redirect()
            ->route('admin.matches.index')
            ->with('status', 'Match created.');
    }

    public function edit(FootballMatch $match): Response
    {
        return Inertia::render('Admin/Matches/Edit', [
            'match' => $match,
        ]);
    }

    public function update(Request $request, FootballMatch $match): RedirectResponse
    {
        $match->update($this->validateMatch($request));

        return redirect()
            ->route('admin.matches.index')
            ->with('status', 'Match updated.');
    }

    public function destroy(FootballMatch $match): RedirectResponse
    {
        $match->delete();

        return redirect()
            ->route('admin.matches.index')
            ->with('status', 'Match deleted.');
    }

    private function validateMatch(Request $request): array
    {
        return $request->validate([
            'opponent' => ['required', 'string', 'max:255'],
            'match_date' => ['required', 'date'],
            'match_time' => ['nullable', 'date_format:H:i'],
            'venue' => ['required', 'string', 'max:255'],
            'maps_url' => ['nullable', 'string', 'max:255'],
            'ticket_price' => ['nullable', 'numeric', 'min:0'],
            'dress_code' => ['nullable', 'string', 'max:255'],
            'facilities' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'payment_label' => ['nullable', 'string', 'max:255'],
            'payment_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_due_at' => ['nullable', 'date'],
            'payment_instructions' => ['nullable', 'string'],
            'whatsapp_announcement' => ['nullable', 'string'],
            'status' => ['required', Rule::in([
                FootballMatch::STATUS_SCHEDULED,
                FootballMatch::STATUS_LIVE,
                FootballMatch::STATUS_FINISHED,
            ])],
            'zeitlos_score' => ['nullable', 'integer', 'min:0'],
            'opponent_score' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
