import PublicLayout from '@/Layouts/PublicLayout';
import { Head, Link } from '@inertiajs/react';

function StatChip({ label, value }) {
    return (
        <div className="rounded-2xl border border-white/10 bg-white/10 p-3 sm:p-4">
            <p className="text-[0.6rem] uppercase tracking-[0.25em] text-slate-400 sm:text-xs">{label}</p>
            <p className="mt-1.5 truncate text-xl font-black text-white sm:text-2xl">{value}</p>
        </div>
    );
}

function MatchSummary({ title, match }) {
    if (!match) {
        return (
            <section className="rounded-3xl border border-dashed border-white/20 bg-white/5 p-5">
                <p className="text-xs font-semibold uppercase tracking-[0.25em] text-amber-300">{title}</p>
                <p className="mt-3 text-sm text-slate-300">No match data is available yet.</p>
            </section>
        );
    }

    return (
        <section className="rounded-3xl border border-white/10 bg-white/10 p-5 shadow-xl shadow-black/20">
            <p className="text-xs font-semibold uppercase tracking-[0.25em] text-amber-300">{title}</p>
            <h2 className="mt-3 text-2xl font-black leading-tight text-white sm:text-3xl">
                Zeitlos vs {match.opponent}
            </h2>
            <p className="mt-2 text-sm text-slate-300">{match.match_date} at {match.match_time} · {match.venue}</p>
            {match.status === 'finished' ? (
                <p className="mt-4 text-4xl font-black text-emerald-300 sm:text-5xl">
                    {match.zeitlos_score}-{match.opponent_score}
                </p>
            ) : (
                <p className="mt-4 line-clamp-3 rounded-2xl bg-slate-950/70 p-3 text-sm text-slate-200">
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
                <section className="rounded-3xl border border-white/10 bg-slate-900/80 p-6 shadow-2xl shadow-black/30 sm:p-8">
                    <p className="text-xs font-bold uppercase tracking-[0.3em] text-emerald-300 sm:text-sm">
                        Public dashboard
                    </p>
                    <h2 className="mt-3 text-3xl font-black leading-tight tracking-tight text-white sm:text-5xl">
                        Match info, squad lists, and team stats in one place.
                    </h2>
                    <p className="mt-4 max-w-2xl text-base text-slate-300 sm:text-lg">
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
                <section className="rounded-3xl border border-white/10 bg-white/10 p-5 sm:p-6">
                    <div className="flex items-center justify-between gap-4">
                        <h3 className="text-xl font-black text-white sm:text-2xl">Roster Snapshot</h3>
                        <Link
                            href={route('public.roster')}
                            className="rounded-full bg-white/10 px-3 py-1.5 text-xs font-bold text-amber-300 transition hover:bg-white/20"
                        >
                            View all
                        </Link>
                    </div>
                    <div className="mt-4 grid gap-3 sm:grid-cols-2">
                        {players.length === 0 ? (
                            <p className="text-sm text-slate-300">No active players yet.</p>
                        ) : (
                            players.map((player) => (
                                <Link
                                    key={player.id}
                                    href={route('public.players.show', player.id)}
                                    className="rounded-2xl bg-slate-950/60 p-4 transition hover:bg-slate-900"
                                >
                                    <p className="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-slate-400">
                                        #{player.jersey_number} · {player.position}
                                    </p>
                                    <p className="mt-2 text-base font-black text-white">{player.name}</p>
                                </Link>
                            ))
                        )}
                    </div>
                </section>

                <section className="rounded-3xl border border-white/10 bg-white/10 p-5 sm:p-6">
                    <div className="flex items-center justify-between gap-4">
                        <h3 className="text-xl font-black text-white sm:text-2xl">Leaderboard</h3>
                        <Link
                            href={route('public.leaderboard')}
                            className="rounded-full bg-white/10 px-3 py-1.5 text-xs font-bold text-amber-300 transition hover:bg-white/20"
                        >
                            Full table
                        </Link>
                    </div>
                    <div className="mt-4 space-y-2.5">
                        {leaders.length === 0 ? (
                            <p className="text-sm text-slate-300">No player stats yet.</p>
                        ) : (
                            leaders.map((player, index) => (
                                <div
                                    key={player.id}
                                    className="flex items-center justify-between rounded-2xl bg-slate-950/60 p-3 sm:p-4"
                                >
                                    <div className="min-w-0">
                                        <p className="text-xs text-slate-400">#{index + 1}</p>
                                        <p className="truncate font-black text-white">{player.name}</p>
                                    </div>
                                    <p className="shrink-0 text-sm font-bold text-emerald-300">
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
