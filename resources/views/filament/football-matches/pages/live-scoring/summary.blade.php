@php
    /** @var \App\Models\FootballMatch $match */
    /** @var \Illuminate\Support\Collection<int, \App\Models\Player> $scoringPlayers */

    $statusLabel = ucfirst($match->status);
    $score = ($match->zeitlos_score ?? 0).' : '.($match->opponent_score ?? 0);
@endphp

<div class="grid gap-6 lg:grid-cols-2">
    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
        <p class="mt-1 text-2xl font-semibold text-gray-950 dark:text-white">{{ $statusLabel }}</p>
        <p class="mt-4 text-sm font-medium text-gray-500 dark:text-gray-400">Score</p>
        <p class="mt-1 text-3xl font-bold text-gray-950 dark:text-white">Zeitlos {{ $score }} {{ $match->opponent }}</p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Available scoring players</p>
        <ul class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-200">
            @forelse ($scoringPlayers as $player)
                <li>
                    {{ $player->name }}
                    @if ($player->jersey_number)
                        <span class="text-gray-500 dark:text-gray-400">#{{ $player->jersey_number }}</span>
                    @endif
                </li>
            @empty
                <li class="text-gray-500 dark:text-gray-400">No active players available.</li>
            @endforelse
        </ul>
    </div>
</div>
