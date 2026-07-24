import PublicLayout from '@/Layouts/PublicLayout';
import { Head, Link } from '@inertiajs/react';

export default function Leaderboard({ leaders = [] }) {
    return (
        <PublicLayout>
            <Head title="Leaderboard" />
            <section className="rounded-3xl border border-border bg-card p-5 text-card-foreground sm:p-6">
                <p className="text-xs font-bold uppercase tracking-[0.3em] text-chart-2 sm:text-sm">Goal contributions</p>
                <h2 className="mt-3 text-3xl font-black text-foreground sm:text-4xl">Leaderboard</h2>
                <div className="mt-6 overflow-hidden rounded-3xl border border-border">
                    {leaders.length === 0 ? (
                        <p className="p-6 text-sm text-muted-foreground">No player stats are available yet.</p>
                    ) : (
                        leaders.map((player, index) => (
                            <Link
                                key={player.id}
                                href={route('public.players.show', player.id)}
                                className="grid grid-cols-[auto_1fr_auto] items-center gap-3 border-b border-border bg-muted p-3 transition last:border-b-0 hover:bg-accent sm:gap-4 sm:p-4"
                            >
                                <span className="flex h-9 w-9 items-center justify-center rounded-full bg-primary text-sm font-black text-primary-foreground sm:h-10 sm:w-10">
                                    {index + 1}
                                </span>
                                <div className="min-w-0">
                                    <p className="truncate font-black text-foreground">{player.name}</p>
                                    <p className="truncate text-xs text-muted-foreground">
                                        #{player.jersey_number} · {player.position}
                                    </p>
                                </div>
                                <p className="text-right text-xs font-bold leading-tight text-chart-2 sm:text-sm">
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
