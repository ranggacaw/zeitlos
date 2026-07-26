import PublicRoster from '@/Components/PublicRoster';
import PublicLayout from '@/Layouts/PublicLayout';
import { Head, Link } from '@inertiajs/react';

function StatChip({ label, value }) {
    return (
        <div className="rounded-2xl bg-surface-container-low p-3 sm:p-4">
            <p className="text-[0.6rem] font-lexend uppercase tracking-[0.25em] text-on-surface-variant sm:text-xs">{label}</p>
            <p className="mt-1.5 truncate text-xl font-black font-lexend text-on-surface sm:text-2xl">{value}</p>
        </div>
    );
}

function MatchSummary({ title, match }) {
    if (!match) {
        return (
            <section className="rounded-[2rem] bg-surface-container-lowest p-5 relative overflow-hidden flex flex-col justify-between">
                <p className="text-xs font-bold uppercase font-lexend tracking-[0.25em] text-on-surface-variant">{title}</p>
                <p className="mt-3 text-sm font-manrope text-on-surface-variant/70">No match data is available yet.</p>
            </section>
        );
    }

    return (
        <section className="rounded-[2rem] bg-surface-container-lowest p-6 relative overflow-hidden flex flex-col justify-between">
            <div className="absolute top-[-20%] right-[-10%] opacity-5 pointer-events-none">
                <span className="material-symbols-outlined text-[150px]">sports_soccer</span>
            </div>
            
            <p className="text-xs font-bold uppercase font-lexend tracking-[0.25em] text-on-surface-variant mb-2 z-10">{title}</p>
            <h2 className="mt-3 text-2xl font-black font-lexend uppercase italic leading-tight text-primary sm:text-3xl z-10">
                Zeitlos vs {match.opponent}
            </h2>
            <p className="mt-2 text-sm font-manrope font-medium text-on-surface-variant/60 z-10">{match.match_date} · {match.match_time} · {match.venue}</p>
            
            {match.status === 'finished' ? (
                <p className="mt-6 text-4xl font-black font-lexend text-on-surface sm:text-5xl z-10">
                    {match.zeitlos_score}-{match.opponent_score}
                </p>
            ) : (
                <p className="mt-6 line-clamp-3 rounded-2xl bg-surface-container-low p-4 text-sm font-manrope text-on-surface-variant z-10">
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

            <div className="grid gap-6 lg:grid-cols-[1.25fr_0.75fr]">
                <section className="rounded-[3rem] bg-surface-container-lowest p-8 sm:p-10 relative overflow-hidden border-l-8 border-primary">
                    <p className="text-xs font-black uppercase font-lexend tracking-[0.3em] text-primary sm:text-sm">
                        Public dashboard
                    </p>
                    <h2 className="mt-4 text-4xl font-black font-lexend leading-none tracking-tighter text-on-surface sm:text-6xl uppercase">
                        PRECISION<br/>HUB
                    </h2>
                    <p className="mt-4 max-w-2xl text-base font-medium font-manrope text-on-surface-variant sm:text-lg">
                        Follow Zeitlos fixtures, active players, match rosters, and goal contributions without admin access.
                    </p>
                    <div className="mt-8 grid gap-4 sm:grid-cols-3">
                        <StatChip label="Active players" value={players.length} />
                        <StatChip label="Top scorer" value={leaders[0]?.name ?? 'None'} />
                        <StatChip label="Next opponent" value={upcomingMatch?.opponent ?? 'TBD'} />
                    </div>
                </section>

                <div className="grid gap-6">
                    <MatchSummary title="Next match" match={upcomingMatch} />
                    <MatchSummary title="Recent result" match={recentResult} />
                </div>
            </div>

            <div className="mt-6 grid gap-6 lg:grid-cols-2">
                <PublicRoster
                    players={players}
                    title="Squad Roster"
                    action={(
                        <Link
                            href={route('public.roster')}
                            className="shrink-0 text-xs font-bold font-lexend uppercase tracking-widest text-on-surface-variant transition-colors hover:text-primary"
                        >
                            View all
                        </Link>
                    )}
                    headingTag="h3"
                    headerClassName="mb-4 flex items-center justify-between gap-4 pb-6"
                    headingWrapperClassName="flex flex-wrap items-end gap-3"
                    titleClassName="text-2xl font-black font-lexend uppercase tracking-tighter text-on-surface"
                    titleAddon={(
                        <span className="mb-1 border-b-2 border-primary pb-1 text-[0.65rem] font-bold font-lexend uppercase tracking-widest text-primary">Full Squad</span>
                    )}
                    contentClassName="space-y-8"
                    gridClassName="grid grid-cols-3 gap-4 sm:grid-cols-3"
                    emptyClassName="text-sm font-manrope text-on-surface-variant"
                    emptyText="No active players yet."
                />

                <section className="rounded-[3rem] bg-surface-container-lowest p-6 sm:p-8">
                    <div className="flex items-center justify-between gap-4 pb-4 mb-4">
                        <h3 className="text-2xl font-black font-lexend uppercase tracking-tighter text-on-surface">Leaderboard</h3>
                        <Link
                            href={route('public.leaderboard')}
                            className="text-xs font-bold font-lexend text-primary uppercase tracking-widest border-b-2 border-primary pb-1 hover:opacity-80 transition-opacity"
                        >
                            Full table
                        </Link>
                    </div>
                    <div className="space-y-3">
                        {leaders.length === 0 ? (
                            <p className="text-sm font-manrope text-on-surface-variant">No player stats yet.</p>
                        ) : (
                            leaders.map((player, index) => (
                                <div
                                    key={player.id}
                                    className="flex items-center justify-between rounded-2xl bg-surface-container-low p-4 sm:p-5 hover:bg-surface-container transition-colors"
                                >
                                    <div className="min-w-0 flex items-center gap-4">
                                        <div className="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-black font-lexend text-sm">
                                            {index + 1}
                                        </div>
                                        <p className="truncate font-black font-lexend uppercase text-on-surface">{player.name}</p>
                                    </div>
                                    <p className="shrink-0 text-sm font-bold font-lexend text-primary uppercase tracking-widest">
                                        {player.goals}G · {player.assists}A
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
