import PublicLayout from '@/Layouts/PublicLayout';
import { Head } from '@inertiajs/react';

const STATUS_TONE = {
    upcoming: 'bg-muted text-primary',
    finished: 'bg-muted text-chart-2',
    default: 'bg-muted text-muted-foreground',
};

function StatusBadge({ status }) {
    const tone = STATUS_TONE[status] ?? STATUS_TONE.default;
    return (
        <span className={`inline-flex items-center rounded-full px-3 py-1 text-[0.65rem] font-bold uppercase tracking-[0.2em] ${tone}`}>
            {status}
        </span>
    );
}

function MatchCard({ match }) {
    const rosterCount = Object.values(match.roster ?? {}).flat().length;

    return (
        <article className="rounded-3xl border border-border bg-muted p-5">
            <div className="flex items-start justify-between gap-3">
                <div className="min-w-0">
                    <StatusBadge status={match.status} />
                    <h3 className="mt-3 text-xl font-black leading-tight text-foreground sm:text-2xl">
                        Zeitlos vs {match.opponent}
                    </h3>
                    <p className="mt-2 text-sm text-muted-foreground">{match.match_date} at {match.match_time} · {match.venue}</p>
                </div>
                {match.status === 'finished' && (
                    <p className="shrink-0 text-3xl font-black text-chart-2 sm:text-4xl">
                        {match.zeitlos_score}-{match.opponent_score}
                    </p>
                )}
            </div>

            <div className="mt-4 grid gap-2.5 sm:grid-cols-2">
                <p className="rounded-2xl bg-card p-3 text-sm text-card-foreground">
                    Kit: {match.dress_code || 'TBD'}<br />Facilities: {match.facilities || 'TBD'}
                </p>
                <p className="rounded-2xl bg-card p-3 text-sm text-card-foreground">
                    {match.payment_label || 'Payment'}: {match.payment_amount ?? 'TBD'}<br />Due: {match.payment_due_at || 'TBD'}
                </p>
            </div>

            {match.whatsapp_announcement && (
                <p className="mt-3 line-clamp-4 rounded-2xl bg-muted p-3 text-sm text-foreground">
                    {match.whatsapp_announcement}
                </p>
            )}

            <p className="mt-4 text-sm font-bold text-muted-foreground">Roster entries: {rosterCount}</p>
        </article>
    );
}

function MatchSection({ title, matches }) {
    return (
        <section className="rounded-3xl border border-border bg-card p-5 text-card-foreground sm:p-6">
            <h2 className="text-2xl font-black text-foreground sm:text-3xl">{title}</h2>
            <div className="mt-5 space-y-4">
                {matches.length === 0 ? (
                    <p className="rounded-2xl border border-dashed border-border p-6 text-sm text-muted-foreground">
                        No matches to show.
                    </p>
                ) : (
                    matches.map((match) => <MatchCard key={match.id} match={match} />)
                )}
            </div>
        </section>
    );
}

export default function Schedule({ upcomingMatches = [], finishedMatches = [] }) {
    return (
        <PublicLayout>
            <Head title="Schedule" />
            <div className="grid gap-5 lg:grid-cols-2">
                <MatchSection title="Upcoming Matches" matches={upcomingMatches} />
                <MatchSection title="Finished Matches" matches={finishedMatches} />
            </div>
        </PublicLayout>
    );
}
