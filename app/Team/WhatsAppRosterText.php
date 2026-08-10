<?php

namespace App\Team;

use App\Models\FootballMatch;
use App\Models\MatchRoster;
use Illuminate\Support\Collection;

class WhatsAppRosterText
{
    public function build(FootballMatch $match, Collection $rosterEntries): string
    {
        $grouped = $this->groupRosterNames($rosterEntries);

        if ($match->whatsapp_announcement && $this->hasIndonesianRosterSections($match->whatsapp_announcement)) {
            return $this->replaceIndonesianRosterSections($match->whatsapp_announcement, $grouped);
        }

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

        $sections = [
            MatchRoster::ROLE_GOALKEEPER => 'Goalkeepers',
            MatchRoster::ROLE_PLAYER => 'Squad',
        ];

        foreach ($sections as $role => $heading) {
            if (isset($grouped[$role])) {
                $lines[] = '';
                $lines[] = sprintf('*%s*', $heading);
                $lines = $lines
                    ->merge($grouped[$role]->map(fn (string $name) => '- '.$name))
                    ->values();
            }
        }

        return $lines->implode("\n");
    }

    private function groupRosterNames(Collection $rosterEntries): Collection
    {
        return $rosterEntries
            ->map(fn (MatchRoster $entry) => [
                'name' => $entry->player?->name ?? $entry->guest_name,
                'role' => $entry->role,
            ])
            ->filter(fn (array $entry) => filled($entry['name']))
            ->groupBy('role')
            ->map(fn (Collection $entries) => $entries->pluck('name')->sort()->values());
    }

    private function hasIndonesianRosterSections(string $template): bool
    {
        return preg_match('/^\*?LIST KIPER\*?\s*$/mi', $template) === 1
            || preg_match('/^\*?LIST PLAYER\*?\s*$/mi', $template) === 1;
    }

    private function replaceIndonesianRosterSections(string $template, Collection $grouped): string
    {
        $text = str_replace("\r\n", "\n", trim($template));

        $sections = [
            'LIST KIPER' => $grouped->get(MatchRoster::ROLE_GOALKEEPER, collect()),
            'LIST PLAYER' => $grouped->get(MatchRoster::ROLE_PLAYER, collect()),
        ];

        foreach ($sections as $heading => $names) {
            $replacement = $this->formatIndonesianRosterSection($heading, $names);
            $text = preg_replace(
                '/^\*?'.preg_quote($heading, '/').'\*?\s*\n.*?(?=^\*?LIST (?:KIPER|PLAYER)\*?\s*$|\z)/ims',
                $replacement."\n",
                $text,
                1,
            );
        }

        return trim($text);
    }

    private function formatIndonesianRosterSection(string $heading, Collection $names): string
    {
        $lines = collect(['*'.$heading.'*']);

        $names->values()->each(fn (string $name, int $index) => $lines[] = sprintf('%d. %s', $index + 1, $name));

        return $lines->implode("\n");
    }
}
