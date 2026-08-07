<?php

namespace App\Team;

use App\Models\FootballMatch;
use App\Models\MatchRoster;
use App\Models\Player;
use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class WhatsAppMatchTemplateImport
{
    public function create(string $template, string $opponent = 'Internal Game'): FootballMatch
    {
        $parsed = $this->parse($template);

        $match = FootballMatch::create([
            'opponent' => $opponent,
            'match_date' => $parsed['match_date'],
            'match_time' => $parsed['match_time'],
            'venue' => $parsed['venue'],
            'maps_url' => $parsed['maps_url'],
            'ticket_price' => $parsed['ticket_price'],
            'dress_code' => $parsed['dress_code'],
            'facilities' => $parsed['facilities'],
            'notes' => $parsed['notes'],
            'payment_amount' => $parsed['payment_amount'],
            'payment_instructions' => $parsed['payment_instructions'],
            'whatsapp_announcement' => $parsed['whatsapp_announcement'],
            'status' => FootballMatch::STATUS_SCHEDULED,
        ]);

        $this->import($match, $template, false);

        return $match;
    }

    public function import(FootballMatch $match, string $template, bool $replaceRoster = true): array
    {
        $parsed = $this->parse($template);

        $match->fill(array_filter(Arr::only($parsed, [
            'match_date',
            'match_time',
            'venue',
            'maps_url',
            'ticket_price',
            'dress_code',
            'facilities',
            'notes',
            'payment_amount',
            'payment_instructions',
            'whatsapp_announcement',
        ]), fn ($value) => filled($value)))->save();

        if ($replaceRoster) {
            $match->rosterEntries()->delete();
        }

        $created = 0;

        foreach ($parsed['roster'] as $entry) {
            $player = Player::query()
                ->whereRaw('LOWER(name) = ?', [Str::lower($entry['name'])])
                ->first();

            $attributes = [
                'match_id' => $match->getKey(),
                'role' => $entry['role'],
            ];

            if ($player) {
                $attributes['player_id'] = $player->getKey();
                $attributes['guest_name'] = null;
            } else {
                $attributes['player_id'] = null;
                $attributes['guest_name'] = $entry['name'];
            }

            MatchRoster::query()->firstOrCreate($attributes);
            $created++;
        }

        return [
            'roster_count' => $created,
        ];
    }

    public function parse(string $template): array
    {
        $text = trim(str_replace("\r\n", "\n", $template));

        return [
            'match_date' => $this->parseDate($this->lineValue($text, 'date')),
            'match_time' => $this->parseTime($this->lineValue($text, 'time')),
            'venue' => $this->lineValue($text, 'venue'),
            'maps_url' => $this->parseMapsUrl($text),
            'ticket_price' => $this->parseAmount($this->lineValue($text, 'price')),
            'dress_code' => $this->lineValue($text, 'dress'),
            'facilities' => $this->parseFacilities($text),
            'notes' => $this->parseNote($text),
            'payment_amount' => $this->parsePaymentAmount($text),
            'payment_instructions' => $this->parsePaymentInstructions($text),
            'whatsapp_announcement' => $text,
            'roster' => $this->parseRoster($text),
        ];
    }

    private function lineValue(string $text, string $type): ?string
    {
        $patterns = [
            'venue' => '/^.*:\s*(.+)$/mi',
            'time' => '/^.*:\s*([0-9]{1,2}[:.][0-9]{2}[^\n]*)$/mi',
            'date' => '/^.*:\s*([^\n]*\d{4})$/mi',
            'price' => '/^.*:\s*([0-9]+\s*k?)\s*$/mi',
            'dress' => '/^.*:\s*(?:jersey\s+)?(.+)$/mi',
        ];

        preg_match_all($patterns[$type], $text, $matches);

        return match ($type) {
            'venue' => $matches[1][0] ?? null,
            'time' => $matches[1][0] ?? null,
            'date' => $matches[1][0] ?? null,
            'price' => collect($matches[1] ?? [])->first(fn ($value) => Str::contains(Str::lower($value), 'k')),
            'dress' => collect($matches[1] ?? [])->first(fn ($value) => Str::contains(Str::lower($value), 'zeitlos')),
        };
    }

    private function parseDate(?string $value): ?string
    {
        if (! $value || ! preg_match('/(\d{1,2})\s+([a-z]+)\s+(\d{4})/i', $value, $matches)) {
            return null;
        }

        $months = [
            'januari' => 1,
            'februari' => 2,
            'maret' => 3,
            'april' => 4,
            'mei' => 5,
            'juni' => 6,
            'juli' => 7,
            'agustus' => 8,
            'september' => 9,
            'oktober' => 10,
            'november' => 11,
            'desember' => 12,
        ];

        $month = $months[Str::lower($matches[2])] ?? null;

        if (! $month) {
            return null;
        }

        return CarbonImmutable::create((int) $matches[3], $month, (int) $matches[1])->toDateString();
    }

    private function parseTime(?string $value): ?string
    {
        if (! $value || ! preg_match('/(\d{1,2})[:.](\d{2})/', $value, $matches)) {
            return null;
        }

        return sprintf('%02d:%02d', (int) $matches[1], (int) $matches[2]);
    }

    private function parseAmount(?string $value): ?float
    {
        if (! $value || ! preg_match('/(\d+)/', $value, $matches)) {
            return null;
        }

        $amount = (int) $matches[1];

        return Str::contains(Str::lower($value), 'k') ? $amount * 1000 : $amount;
    }

    private function parsePaymentAmount(string $text): ?float
    {
        if (! preg_match('/dp\s+min(?:imal|inal)?\s+([0-9]+\s*k?)/i', $text, $matches)) {
            return null;
        }

        return $this->parseAmount($matches[1]);
    }

    private function parseMapsUrl(string $text): ?string
    {
        preg_match('/https?:\/\/[^\s]+/i', $text, $matches);

        return $matches[0] ?? null;
    }

    private function parseFacilities(string $text): ?string
    {
        if (! preg_match('/^include\s*:\s*(.+)$/mi', $text, $matches)) {
            return null;
        }

        return trim($matches[1]);
    }

    private function parseNote(string $text): ?string
    {
        if (! preg_match('/^note\s*:\s*(.+)$/mi', $text, $matches)) {
            return null;
        }

        return trim($matches[1]);
    }

    private function parsePaymentInstructions(string $text): ?string
    {
        if (! preg_match('/PEMBAYARAN DP:\s*(.+?)(?:\n\s*Maps\s*:|\n\s*LIST KIPER)/is', $text, $matches)) {
            return null;
        }

        return trim($matches[1]);
    }

    private function parseRoster(string $text): array
    {
        return array_merge(
            $this->parseRosterSection($text, 'LIST KIPER', MatchRoster::ROLE_GOALKEEPER),
            $this->parseRosterSection($text, 'LIST PLAYER', MatchRoster::ROLE_PLAYER),
        );
    }

    private function parseRosterSection(string $text, string $heading, string $role): array
    {
        if (! preg_match('/'.preg_quote($heading, '/').'\s*(.+?)(?=\n\s*LIST\s+[A-Z]+|\z)/is', $text, $matches)) {
            return [];
        }

        preg_match_all('/^[^\S\r\n]*\d+\.[^\S\r\n]*(.+?)[^\S\r\n]*$/m', $matches[1], $names);

        return collect($names[1] ?? [])
            ->map(fn (string $name): string => trim($name))
            ->filter(fn (string $name): bool => filled($name) && ! Str::contains($name, '?'))
            ->map(fn (string $name): array => [
                'name' => $name,
                'role' => $role,
            ])
            ->values()
            ->all();
    }
}
