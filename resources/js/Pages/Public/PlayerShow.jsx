import PublicLayout from '@/Layouts/PublicLayout';
import { Head } from '@inertiajs/react';

export default function PlayerShow({ player, matches = [] }) {
    return (
        <PublicLayout>
            <Head title={player.name} />
            <section className="rounded-3xl border border-white/10 bg-white/10 p-5 sm:p-6">
                <div className="flex flex-col gap-5 sm:flex-row sm:items-center sm:gap-6 lg:flex-row">
                    <div className="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-3xl bg-amber-300 text-4xl font-black text-slate-950 sm:h-32 sm:w-32 sm:text-5xl">
                        {player.photo_path ? (
                            <img src={`/storage/${player.photo_path}`} alt="" className="h-full w-full object-cover" />
                        ) : (
                            player.name.charAt(0)
                        )}
                    </div>
                    <div className="min-w-0">
                        <p className="text-xs font-bold uppercase tracking-[0.3em] text-amber-300">
                            #{player.jersey_number} · {player.position}
                        </p>
                        <h2 className="mt-2 text-3xl font-black leading-tight text-white sm:mt-3 sm:text-5xl">
                            {player.name}
                        </h2>
                        <div className="mt-4 flex flex-wrap gap-2 text-sm font-bold sm:gap-3">
                            <span className="rounded-full bg-emerald-300 px-4 py-2 text-slate-950">{player.goals} goals</span>
                            <span className="rounded-full bg-sky-300 px-4 py-2 text-slate-950">{player.assists} assists</span>
                            <span className="rounded-full bg-white/10 px-4 py-2 text-slate-200">
                                Joined {player.joined_at || 'TBD'}
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <section className="mt-5 rounded-3xl border border-white/10 bg-white/10 p-5 sm:mt-6 sm:p-6">
                <h3 className="text-2xl font-black text-white sm:text-3xl">Match Appearances</h3>
                <div className="mt-5 space-y-3 sm:space-y-4">
                    {matches.length === 0 ? (
                        <p className="rounded-2xl border border-dashed border-white/20 p-6 text-sm text-slate-300">
                            No rostered matches yet.
                        </p>
                    ) : (
                        matches.map((match) => (
                            <article key={match.id} className="rounded-3xl bg-slate-950/60 p-4 sm:p-5">
                                <div className="flex items-center justify-between gap-3">
                                    <div className="min-w-0">
                                        <p className="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-slate-400">
                                            {match.status}
                                        </p>
                                        <h4 className="mt-1 text-lg font-black leading-tight text-white sm:text-xl">
                                            Zeitlos vs {match.opponent}
                                        </h4>
                                        <p className="mt-1 text-sm text-slate-300">{match.match_date} · {match.venue}</p>
                                    </div>
                                    {match.status === 'finished' && (
                                        <p className="shrink-0 text-2xl font-black text-emerald-300 sm:text-3xl">
                                            {match.zeitlos_score}-{match.opponent_score}
                                        </p>
                                    )}
                                </div>
                            </article>
                        ))
                    )}
                </div>
            </section>
        </PublicLayout>
    );
}
