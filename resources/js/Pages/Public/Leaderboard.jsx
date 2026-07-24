import PublicLayout from '@/Layouts/PublicLayout';
import { Head, Link } from '@inertiajs/react';

export default function Leaderboard({ leaders = [] }) {
    return (
        <PublicLayout>
            <Head title="Leaderboard" />
            <section className="rounded-3xl border border-white/10 bg-white/10 p-5 sm:p-6">
                <p className="text-xs font-bold uppercase tracking-[0.3em] text-emerald-300 sm:text-sm">Goal contributions</p>
                <h2 className="mt-3 text-3xl font-black text-white sm:text-4xl">Leaderboard</h2>
                <div className="mt-6 overflow-hidden rounded-3xl border border-white/10">
                    {leaders.length === 0 ? (
                        <p className="p-6 text-sm text-slate-300">No player stats are available yet.</p>
                    ) : (
                        leaders.map((player, index) => (
                            <Link
                                key={player.id}
                                href={route('public.players.show', player.id)}
                                className="grid grid-cols-[auto_1fr_auto] items-center gap-3 border-b border-white/10 bg-slate-950/60 p-3 transition last:border-b-0 hover:bg-slate-900 sm:gap-4 sm:p-4"
                            >
                                <span className="flex h-9 w-9 items-center justify-center rounded-full bg-amber-300 text-sm font-black text-slate-950 sm:h-10 sm:w-10">
                                    {index + 1}
                                </span>
                                <div className="min-w-0">
                                    <p className="truncate font-black text-white">{player.name}</p>
                                    <p className="truncate text-xs text-slate-400">
                                        #{player.jersey_number} · {player.position}
                                    </p>
                                </div>
                                <p className="text-right text-xs font-bold leading-tight text-emerald-300 sm:text-sm">
                                    {player.goals} goals<br />{player.assists} assists
                                </p>
                            </Link>
                        ))
                    )}
                </div>
            </section>
        </PublicLayout>
    );
}
