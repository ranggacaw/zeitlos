<x-filament-widgets::widget>
    <div class="space-y-6 text-[var(--foreground)]">
        <header class="flex flex-col gap-4 border-b border-[var(--border)] pb-5 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-medium text-[var(--muted-foreground)]">Match operations dashboard</p>
                <h2 class="mt-1 text-3xl font-semibold tracking-tight sm:text-4xl">Run today's team admin from one place</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-[var(--muted-foreground)]">Prioritize live match actions, upcoming schedules, roster data, and player stats without making admins hunt through tables first.</p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ $createMatchUrl }}" class="rounded-lg border border-[var(--secondary)] bg-[var(--secondary)] px-4 py-2 text-center text-sm font-semibold text-[var(--secondary-foreground)] transition hover:opacity-90">
                    New match
                </a>
                <a href="{{ $createPlayerUrl }}" class="rounded-lg border border-[var(--border)] bg-[var(--background)] px-4 py-2 text-center text-sm font-semibold text-[var(--foreground)] transition hover:bg-[var(--muted)]">
                    New player
                </a>
            </div>
        </header>

        <section class="grid gap-4 xl:grid-cols-3">
            <article class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4 text-[var(--card-foreground)] shadow-sm sm:p-5 xl:col-span-2">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div>
                        <div class="inline-flex rounded-full border border-[var(--secondary)] bg-[var(--secondary)] px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[var(--secondary-foreground)]">
                            @if ($liveMatch && $liveMatchUrl)
                                Live now
                            @else
                                Next priority
                            @endif
                        </div>
                        <h3 class="mt-4 text-2xl font-semibold tracking-tight sm:text-3xl">
                            {{ $liveMatchTitle ?? ($nextMatch ? 'Next: '.$nextMatch : 'No match scheduled') }}
                        </h3>
                        <p class="mt-2 text-sm text-[var(--muted-foreground)]">
                            @if ($liveMatch && $liveMatchUrl)
                                Keep scoring, roster, and finalization controls close while the match is active.
                            @elseif ($nextMatch)
                                Prepare match details and roster before kickoff.
                            @else
                                Create the next match to start scheduling and roster planning.
                            @endif
                        </p>
                    </div>

                    <div class="rounded-xl border border-[var(--border)] bg-[var(--background)] px-5 py-4 text-center">
                        <p class="text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">
                            {{ $liveMatchScore ? 'Score' : 'Status' }}
                        </p>
                        <p class="mt-1 text-4xl font-semibold tabular-nums">
                            {{ $liveMatchScore ?? 'Ready' }}
                        </p>
                        <p class="mt-1 text-sm text-[var(--muted-foreground)]">
                            {{ $liveMatch ? 'Live match' : 'Admin' }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-3">
                    @if ($liveMatch && $liveMatchUrl)
                        <a href="{{ $liveMatchUrl }}" class="rounded-xl border border-[var(--secondary)] bg-[var(--secondary)] p-4 text-[var(--secondary-foreground)] transition hover:opacity-90">
                            <p class="text-sm font-semibold">Open live scoring</p>
                            <p class="mt-2 text-xs leading-5 opacity-85">Record goals and finalize the score.</p>
                        </a>
                    @else
                        <a href="{{ $createMatchUrl }}" class="rounded-xl border border-[var(--secondary)] bg-[var(--secondary)] p-4 text-[var(--secondary-foreground)] transition hover:opacity-90">
                            <p class="text-sm font-semibold">Create match</p>
                            <p class="mt-2 text-xs leading-5 opacity-85">Schedule the next team event.</p>
                        </a>
                    @endif

                    <a href="{{ $nextMatchRosterUrl ?? $matchesUrl }}" class="rounded-xl border border-[var(--border)] bg-[var(--background)] p-4 transition hover:bg-[var(--muted)]">
                        <p class="text-sm font-semibold">Manage roster</p>
                        <p class="mt-2 text-xs leading-5 text-[var(--muted-foreground)]">Check players, guests, and WhatsApp text.</p>
                    </a>
                    <a href="{{ $nextMatchUrl ?? $matchesUrl }}" class="rounded-xl border border-[var(--border)] bg-[var(--background)] p-4 transition hover:bg-[var(--muted)]">
                        <p class="text-sm font-semibold">Edit match</p>
                        <p class="mt-2 text-xs leading-5 text-[var(--muted-foreground)]">Venue, status, schedule, and payment.</p>
                    </a>
                </div>
            </article>

            <aside class="grid grid-cols-3 gap-3 xl:grid-cols-1">
                <div class="rounded-2xl border border-[var(--border)] bg-[var(--muted)] p-4 text-[var(--muted-foreground)]">
                    <p class="text-sm font-medium">Players</p>
                    <p class="mt-2 text-xl font-semibold tabular-nums text-[var(--foreground)] sm:text-3xl">{{ $playerCount }}</p>
                    <p class="mt-1 text-sm">{{ $activePlayerCount }} active</p>
                </div>
                <div class="rounded-2xl border border-[var(--border)] bg-[var(--muted)] p-4 text-[var(--muted-foreground)]">
                    <p class="text-sm font-medium">Matches</p>
                    <p class="mt-2 text-xl font-semibold tabular-nums text-[var(--foreground)] sm:text-3xl">{{ $matchCount }}</p>
                    <p class="mt-1 text-sm">{{ $liveMatch ? '1 live, ' : '' }}{{ $scheduledMatchCount }} scheduled</p>
                </div>
                <div class="rounded-2xl border border-[var(--border)] bg-[var(--muted)] p-4 text-[var(--muted-foreground)]">
                    <p class="text-sm font-medium">Open tasks</p>
                    <p class="mt-2 text-xl font-semibold tabular-nums text-[var(--foreground)] sm:text-3xl">{{ $openTaskCount }}</p>
                    <p class="mt-1 text-sm">{{ $openTaskSummary }}</p>
                </div>
            </aside>
        </section>

        <section class="grid grid-cols-2 gap-3 lg:grid-cols-3 lg:gap-4">
            <article class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4 text-[var(--card-foreground)] sm:p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-[var(--muted-foreground)]">Next match</p>
                <h3 class="mt-3 text-lg font-semibold">{{ $nextMatch ?? 'None scheduled' }}</h3>
                <p class="mt-1 text-sm text-[var(--muted-foreground)]">{{ $nextMatchDetail ?? 'Create a match to begin roster planning.' }}</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ $nextMatchUrl ?? $matchesUrl }}" class="rounded-lg border border-[var(--border)] bg-[var(--background)] px-3 py-2 text-sm font-semibold transition hover:bg-[var(--muted)]">Edit</a>
                    <a href="{{ $nextMatchRosterUrl ?? $matchesUrl }}" class="rounded-lg border border-[var(--border)] bg-[var(--background)] px-3 py-2 text-sm font-semibold transition hover:bg-[var(--muted)]">Roster</a>
                </div>
            </article>

            <article class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4 text-[var(--card-foreground)] sm:p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-[var(--muted-foreground)]">Recent result</p>
                <h3 class="mt-3 text-lg font-semibold">{{ $recentResult ?? 'No result yet' }}</h3>
                <p class="mt-1 text-sm text-[var(--muted-foreground)]">{{ $recentResultDetail ?? 'Finished match stats will appear here.' }}</p>
                <a href="{{ $leaderboardUrl }}" class="mt-4 inline-flex rounded-lg border border-[var(--border)] bg-[var(--background)] px-3 py-2 text-sm font-semibold transition hover:bg-[var(--muted)]">Review</a>
            </article>

            <article class="col-span-2 rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4 text-[var(--card-foreground)] sm:p-5 lg:col-span-1">
                <p class="text-xs font-semibold uppercase tracking-wide text-[var(--muted-foreground)]">Admin shortcuts</p>
                <div class="mt-3 grid grid-cols-2 gap-2 lg:grid-cols-1">
                    <a href="{{ $playersUrl }}" class="rounded-lg bg-[var(--muted)] px-3 py-2 text-sm font-semibold transition hover:opacity-85">Manage players</a>
                    <a href="{{ $matchesUrl }}" class="rounded-lg bg-[var(--muted)] px-3 py-2 text-sm font-semibold transition hover:opacity-85">Manage matches</a>
                    <a href="{{ $leaderboardUrl }}" class="col-span-2 rounded-lg bg-[var(--muted)] px-3 py-2 text-sm font-semibold transition hover:opacity-85 lg:col-span-1">Leaderboard corrections</a>
                </div>
            </article>
        </section>

        <section class="grid grid-cols-1 gap-3 lg:gap-4">
            <article class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4 text-[var(--card-foreground)] sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-lg font-semibold">Top scorers</h3>
                    <a href="{{ $leaderboardUrl }}" class="text-sm font-semibold text-[var(--primary)] hover:opacity-80">Open all</a>
                </div>
                <ol class="mt-4 space-y-2 text-sm">
                    @forelse ($topScorers as $player)
                        <li class="flex items-center justify-between rounded-xl bg-[var(--muted)] px-3 py-3 text-[var(--muted-foreground)]">
                            <span>{{ $loop->iteration }}. {{ $player['name'] }}</span>
                            <strong class="text-[var(--foreground)]">{{ $player['goals'] }} goals</strong>
                        </li>
                    @empty
                        <li class="rounded-xl bg-[var(--muted)] px-3 py-3 text-[var(--muted-foreground)]">No scorers yet.</li>
                    @endforelse
                </ol>
            </article>

            <article class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4 text-[var(--card-foreground)] sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-lg font-semibold">Top assists</h3>
                    <a href="{{ $leaderboardUrl }}" class="text-sm font-semibold text-[var(--primary)] hover:opacity-80">Open all</a>
                </div>
                <ol class="mt-4 space-y-2 text-sm">
                    @forelse ($topAssists as $player)
                        <li class="flex items-center justify-between rounded-xl bg-[var(--muted)] px-3 py-3 text-[var(--muted-foreground)]">
                            <span>{{ $loop->iteration }}. {{ $player['name'] }}</span>
                            <strong class="text-[var(--foreground)]">{{ $player['assists'] }} assists</strong>
                        </li>
                    @empty
                        <li class="rounded-xl bg-[var(--muted)] px-3 py-3 text-[var(--muted-foreground)]">No assists yet.</li>
                    @endforelse
                </ol>
            </article>
        </section>
    </div>
</x-filament-widgets::widget>
