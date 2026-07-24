import PublicLayout from '@/Layouts/PublicLayout';
import { Head, Link } from '@inertiajs/react';

function StatChip({ label, value }) {
    return (
        <div className="rounded-2xl border border-border bg-muted p-3 sm:p-4">
            <p className="text-[0.6rem] uppercase tracking-[0.25em] text-muted-foreground sm:text-xs">{label}</p>
            <p className="mt-1.5 truncate text-xl font-black text-foreground sm:text-2xl">{value}</p>
        </div>
    );
}

function MatchSummary({ title, match }) {
    if (!match) {
        return (
            <section className="rounded-3xl border border-dashed border-border bg-card p-5">
                <p className="text-xs font-semibold uppercase tracking-[0.25em] text-primary">{title}</p>
                <p className="mt-3 text-sm text-muted-foreground">No match data is available yet.</p>
            </section>
        );
    }

    return (
        <section className="rounded-3xl border border-border bg-card p-5 text-card-foreground shadow-xl">
            <p className="text-xs font-semibold uppercase tracking-[0.25em] text-primary">{title}</p>
            <h2 className="mt-3 text-2xl font-black leading-tight text-foreground sm:text-3xl">
                Zeitlos vs {match.opponent}
            </h2>
            <p className="mt-2 text-sm text-muted-foreground">{match.match_date} at {match.match_time} · {match.venue}</p>
            {match.status === 'finished' ? (
                <p className="mt-4 text-4xl font-black text-chart-2 sm:text-5xl">
                    {match.zeitlos_score}-{match.opponent_score}
                </p>
            ) : (
                <p className="mt-4 line-clamp-3 rounded-2xl bg-muted p-3 text-sm text-muted-foreground">
                    {match.whatsapp_announcement}
                </p>
            )}
        </section>
    );
}

export default function Welcome({ upcomingMatch, recentResult, players = [], leaders = [] }) {
    return (
        <PublicLayout>
            <Head title="Zeitlos Team Hub" />

            <div className="grid gap-5 lg:grid-cols-[1.25fr_0.75fr]">
                <section className="rounded-3xl border border-border bg-card p-6 text-card-foreground shadow-2xl sm:p-8">
                    <p className="text-xs font-bold uppercase tracking-[0.3em] text-chart-2 sm:text-sm">
                        Public dashboard
                    </p>
                    <h2 className="mt-3 text-3xl font-black leading-tight tracking-tight text-foreground sm:text-5xl">
                        Match info, squad lists, and team stats in one place.
                    </h2>
                    <p className="mt-4 max-w-2xl text-base text-muted-foreground sm:text-lg">
                        Follow Zeitlos fixtures, active players, match rosters, and goal contributions without admin access.
                    </p>
                    <div className="mt-6 grid gap-3 sm:grid-cols-3">
                        <StatChip label="Active players" value={players.length} />
                        <StatChip label="Top scorer" value={leaders[0]?.name ?? 'None'} />
                        <StatChip label="Next opponent" value={upcomingMatch?.opponent ?? 'TBD'} />
                    </div>
                </section>

                <div className="grid gap-5">
                    <MatchSummary title="Next match" match={upcomingMatch} />
                    <MatchSummary title="Recent result" match={recentResult} />
                </div>
            </div>

            <div className="mt-5 grid gap-5 lg:grid-cols-2">
                <section className="rounded-3xl border border-border bg-card p-5 text-card-foreground sm:p-6">
                    <div className="flex items-center justify-between gap-4">
                        <h3 className="text-xl font-black text-foreground sm:text-2xl">Roster Snapshot</h3>
                        <Link
                            href={route('public.roster')}
                            className="rounded-full bg-accent px-3 py-1.5 text-xs font-bold text-accent-foreground transition hover:bg-accent"
                        >
                            View all
                        </Link>
                    </div>
                    <div className="mt-4 grid gap-3 sm:grid-cols-2">
                        {players.length === 0 ? (
                            <p className="text-sm text-muted-foreground">No active players yet.</p>
                        ) : (
                            players.map((player) => (
                                <Link
                                    key={player.id}
                                    href={route('public.players.show', player.id)}
                                    className="rounded-2xl bg-muted p-4 transition hover:bg-accent"
                                >
                                    <p className="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-muted-foreground">
                                        #{player.jersey_number} · {player.position}
                                    </p>
                                    <p className="mt-2 text-base font-black text-foreground">{player.name}</p>
                                </Link>
                            ))
                        )}
                    </div>
                </section>

                <section className="rounded-3xl border border-border bg-card p-5 text-card-foreground sm:p-6">
                    <div className="flex items-center justify-between gap-4">
                        <h3 className="text-xl font-black text-foreground sm:text-2xl">Leaderboard</h3>
                        <Link
                            href={route('public.leaderboard')}
                            className="rounded-full bg-accent px-3 py-1.5 text-xs font-bold text-accent-foreground transition hover:bg-accent"
                        >
                            Full table
                        </Link>
                    </div>
                    <div className="mt-4 space-y-2.5">
                        {leaders.length === 0 ? (
                            <p className="text-sm text-muted-foreground">No player stats yet.</p>
                        ) : (
                            leaders.map((player, index) => (
                                <div
                                    key={player.id}
                                    className="flex items-center justify-between rounded-2xl bg-muted p-3 sm:p-4"
                                >
                                    <div className="min-w-0">
                                        <p className="text-xs text-muted-foreground">#{index + 1}</p>
                                        <p className="truncate font-black text-foreground">{player.name}</p>
                                    </div>
                                    <p className="shrink-0 text-sm font-bold text-chart-2">
                                        {player.goals} G · {player.assists} A
                                    </p>
                                </div>
                            ))
                        )}
                    </div>
                </section>
            </div>
        </PublicLayout>
    );
}
