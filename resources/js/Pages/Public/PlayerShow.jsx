import PublicLayout from '@/Layouts/PublicLayout';
import { rosterPhotoUrl } from '@/Utils/rosterPhotos';
import { Head } from '@inertiajs/react';

export default function PlayerShow({ player, matches = [] }) {
    const photoUrl = rosterPhotoUrl(player);

    return (
        <PublicLayout>
            <Head title={player.name} />
            <section className="rounded-3xl border border-border bg-card p-5 text-card-foreground sm:p-6">
                <div className="flex flex-col gap-5 sm:flex-row sm:items-center sm:gap-6 lg:flex-row">
                    <div className="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-3xl bg-primary text-4xl font-black text-primary-foreground sm:h-32 sm:w-32 sm:text-5xl">
                        {photoUrl ? (
                            <img src={photoUrl} alt="" className="h-full w-full object-cover" />
                        ) : (
                            player.name.charAt(0)
                        )}
                    </div>
                    <div className="min-w-0">
                        <p className="text-xs font-bold uppercase tracking-[0.3em] text-primary">
                            #{player.jersey_number ?? '-'} · {player.position}
                        </p>
                        <h2 className="mt-2 text-3xl font-black leading-tight text-foreground sm:mt-3 sm:text-5xl">
                            {player.name}
                        </h2>
                        <div className="mt-4 flex flex-wrap gap-2 text-sm font-bold sm:gap-3">
                            <span className="rounded-full bg-chart-2 px-4 py-2 text-background">{player.goals} goals</span>
                            <span className="rounded-full bg-primary px-4 py-2 text-primary-foreground">{player.assists} assists</span>
                            <span className="rounded-full bg-muted px-4 py-2 text-muted-foreground">
                                Joined {player.joined_at || 'TBD'}
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <section className="mt-5 rounded-3xl border border-border bg-card p-5 text-card-foreground sm:mt-6 sm:p-6">
                <h3 className="text-2xl font-black text-foreground sm:text-3xl">Match Appearances</h3>
                <div className="mt-5 space-y-3 sm:space-y-4">
                    {matches.length === 0 ? (
                        <p className="rounded-2xl border border-dashed border-border p-6 text-sm text-muted-foreground">
                            No rostered matches yet.
                        </p>
                    ) : (
                        matches.map((match) => (
                            <article key={match.id} className="rounded-3xl bg-muted p-4 sm:p-5">
                                <div className="flex items-center justify-between gap-3">
                                    <div className="min-w-0">
                                        <p className="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-muted-foreground">
                                            {match.status}
                                        </p>
                                        <h4 className="mt-1 text-lg font-black leading-tight text-foreground sm:text-xl">
                                            Zeitlos vs {match.opponent}
                                        </h4>
                                        <p className="mt-1 text-sm text-muted-foreground">{match.match_date} · {match.venue}</p>
                                    </div>
                                    {match.status === 'finished' && (
                                        <p className="shrink-0 text-2xl font-black text-chart-2 sm:text-3xl">
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
