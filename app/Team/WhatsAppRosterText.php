<?php

namespace App\Team;

use App\Models\FootballMatch;
use App\Models\MatchRoster;
use Illuminate\Support\Collection;

class WhatsAppRosterText
{
    public function build(FootballMatch $match, Collection $rosterEntries): string
    {
        $lines = collect([
            sprintf('*Zeitlos vs %s*', $match->opponent),
        ]);

        $matchDate = $match->match_date?->format('l, j M Y');

        if ($matchDate) {
            $lines[] = $matchDate;
        }

        if ($match->match_time) {
            $lines[] = sprintf('Kick-off: %s', $match->match_time);
        }

        if ($match->venue) {
            $lines[] = sprintf('Venue: %s', $match->venue);
        }

        if ($match->whatsapp_announcement) {
            $lines[] = '';
            $lines[] = $match->whatsapp_announcement;
        }

        $grouped = $rosterEntries
            ->map(fn (MatchRoster $entry) => [
                'name' => $entry->player?->name ?? $entry->guest_name,
                'role' => $entry->role,
            ])
            ->filter(fn (array $entry) => filled($entry['name']))
            ->groupBy('role');

        $sections = [
            MatchRoster::ROLE_GOALKEEPER => 'Goalkeepers',
            MatchRoster::ROLE_PLAYER => 'Squad',
        ];

        foreach ($sections as $role => $heading) {
            if (isset($grouped[$role])) {
                $lines[] = '';
                $lines[] = sprintf('*%s*', $heading);
                $lines = $lines
                    ->merge($grouped[$role]->pluck('name')->sort()->values()->map(fn (string $name) => '- '.$name))
                    ->values();
            }
        }

        return $lines->implode("\n");
    }
}
