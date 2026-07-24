import PublicLayout from '@/Layouts/PublicLayout';
import { Head, Link } from '@inertiajs/react';

export default function Roster({ players = [] }) {
    return (
        <PublicLayout>
            <Head title="Roster" />
            <section className="rounded-3xl border border-white/10 bg-white/10 p-5 sm:p-6">
                <p className="text-xs font-bold uppercase tracking-[0.3em] text-amber-300 sm:text-sm">Active squad</p>
                <h2 className="mt-3 text-3xl font-black text-white sm:text-4xl">Zeitlos Roster</h2>
                {players.length === 0 ? (
                    <p className="mt-6 rounded-2xl border border-dashed border-white/20 p-6 text-sm text-slate-300">
                        No active players are available yet.
                    </p>
                ) : (
                    <div className="mt-6 grid gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-3">
                        {players.map((player) => (
                            <Link
                                key={player.id}
                                href={route('public.players.show', player.id)}
                                className="rounded-3xl bg-slate-950/60 p-4 transition hover:-translate-y-1 hover:bg-slate-900 sm:p-5"
                            >
                                <div className="flex items-center gap-3 sm:gap-4">
                                    <div className="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-amber-300 text-lg font-black text-slate-950 sm:h-16 sm:w-16 sm:text-xl">
                                        {player.photo_path ? (
                                            <img src={`/storage/${player.photo_path}`} alt="" className="h-full w-full object-cover" />
                                        ) : (
                                            player.name.charAt(0)
                                        )}
                                    </div>
                                    <div className="min-w-0">
                                        <p className="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-slate-400">
                                            #{player.jersey_number}
                                        </p>
                                        <h3 className="truncate text-lg font-black text-white sm:text-xl">{player.name}</h3>
                                    </div>
                                </div>
                                <div className="mt-4 flex items-center justify-between text-sm font-bold text-slate-300">
                                    <span>{player.position}</span>
                                    <span className="text-emerald-300">{player.goals} G · {player.assists} A</span>
                                </div>
                            </Link>
                        ))}
                    </div>
                )}
            </section>
        </PublicLayout>
    );
}
