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
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $liveMatch ? 'Live: '.$liveMatch : 'No live match' }}</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-4 ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10">
                <p class="text-sm text-gray-500 dark:text-gray-400">Next / Recent</p>
                <p class="mt-2 text-sm font-medium text-gray-950 dark:text-white">Next: {{ $nextMatch ?? 'None scheduled' }}</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Recent: {{ $recentResult ?? 'No result yet' }}</p>
            </div>
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
