<x-filament-widgets::widget>
    <x-filament::section>
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl bg-gray-50 p-4 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10">
                <p class="text-sm text-gray-500 dark:text-gray-400">Players</p>
                <p class="mt-2 text-3xl font-semibold text-gray-950 dark:text-white">{{ $playerCount }}</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $activePlayerCount }} active</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-4 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10">
                <p class="text-sm text-gray-500 dark:text-gray-400">Matches</p>
                <p class="mt-2 text-3xl font-semibold text-gray-950 dark:text-white">{{ $matchCount }}</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    @if ($liveMatch && $liveMatchUrl)
                        <a href="{{ $liveMatchUrl }}" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">Live: {{ $liveMatch }}</a>
                    @else
                        No live match
                    @endif
                </p>
            </div>
            <div class="rounded-xl bg-gray-50 p-4 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10">
                <p class="text-sm text-gray-500 dark:text-gray-400">Next / Recent</p>
                <p class="mt-2 text-sm font-medium text-gray-950 dark:text-white">
                    Next:
                    @if ($nextMatch && $nextMatchUrl)
                        <a href="{{ $nextMatchUrl }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">{{ $nextMatch }}</a>
                    @else
                        None scheduled
                    @endif
                </p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Recent: {{ $recentResult ?? 'No result yet' }}</p>
            </div>
        </div>

        <div class="mt-6 grid gap-3 sm:grid-cols-3">
            <a href="{{ $playersUrl }}" class="rounded-xl bg-primary-50 px-4 py-3 text-sm font-semibold text-primary-700 ring-1 ring-primary-600/20 transition hover:bg-primary-100 dark:bg-primary-400/10 dark:text-primary-300 dark:ring-primary-400/20 dark:hover:bg-primary-400/15">
                Manage players
            </a>
            <a href="{{ $matchesUrl }}" class="rounded-xl bg-primary-50 px-4 py-3 text-sm font-semibold text-primary-700 ring-1 ring-primary-600/20 transition hover:bg-primary-100 dark:bg-primary-400/10 dark:text-primary-300 dark:ring-primary-400/20 dark:hover:bg-primary-400/15">
                Manage matches
            </a>
            <a href="{{ $leaderboardUrl }}" class="rounded-xl bg-primary-50 px-4 py-3 text-sm font-semibold text-primary-700 ring-1 ring-primary-600/20 transition hover:bg-primary-100 dark:bg-primary-400/10 dark:text-primary-300 dark:ring-primary-400/20 dark:hover:bg-primary-400/15">
                Leaderboard corrections
            </a>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <div>
                <h3 class="text-sm font-semibold text-gray-950 dark:text-white">Top scorers</h3>
                <ul class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    @forelse ($topScorers as $player)
                        <li class="flex justify-between rounded-lg bg-gray-50 px-3 py-2 dark:bg-white/5">
                            <span>{{ $player['name'] }}</span>
                            <span>{{ $player['goals'] }} goals</span>
                        </li>
                    @empty
                        <li>No scorers yet.</li>
                    @endforelse
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-950 dark:text-white">Top assists</h3>
                <ul class="mt-3 space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    @forelse ($topAssists as $player)
                        <li class="flex justify-between rounded-lg bg-gray-50 px-3 py-2 dark:bg-white/5">
                            <span>{{ $player['name'] }}</span>
                            <span>{{ $player['assists'] }} assists</span>
                        </li>
                    @empty
                        <li>No assists yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
