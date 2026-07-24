import PublicLayout from '@/Layouts/PublicLayout';
import { Head } from '@inertiajs/react';

const STATUS_TONE = {
    upcoming: 'bg-amber-300/15 text-amber-300',
    finished: 'bg-emerald-300/15 text-emerald-300',
    default: 'bg-white/10 text-slate-300',
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
        <article className="rounded-3xl border border-white/10 bg-slate-950/60 p-5">
            <div className="flex items-start justify-between gap-3">
                <div className="min-w-0">
                    <StatusBadge status={match.status} />
                    <h3 className="mt-3 text-xl font-black leading-tight text-white sm:text-2xl">
                        Zeitlos vs {match.opponent}
                    </h3>
                    <p className="mt-2 text-sm text-slate-300">{match.match_date} at {match.match_time} · {match.venue}</p>
                </div>
                {match.status === 'finished' && (
                    <p className="shrink-0 text-3xl font-black text-emerald-300 sm:text-4xl">
                        {match.zeitlos_score}-{match.opponent_score}
                    </p>
                )}
            </div>

            <div className="mt-4 grid gap-2.5 sm:grid-cols-2">
                <p className="rounded-2xl bg-white/10 p-3 text-sm text-slate-300">
                    Kit: {match.dress_code || 'TBD'}<br />Facilities: {match.facilities || 'TBD'}
                </p>
                <p className="rounded-2xl bg-white/10 p-3 text-sm text-slate-300">
                    {match.payment_label || 'Payment'}: {match.payment_amount ?? 'TBD'}<br />Due: {match.payment_due_at || 'TBD'}
                </p>
            </div>

            {match.whatsapp_announcement && (
                <p className="mt-3 line-clamp-4 rounded-2xl bg-emerald-300/10 p-3 text-sm text-emerald-100">
                    {match.whatsapp_announcement}
                </p>
            )}

            <p className="mt-4 text-sm font-bold text-slate-400">Roster entries: {rosterCount}</p>
        </article>
    );
}

function MatchSection({ title, matches }) {
    return (
        <section className="rounded-3xl border border-white/10 bg-white/10 p-5 sm:p-6">
            <h2 className="text-2xl font-black text-white sm:text-3xl">{title}</h2>
            <div className="mt-5 space-y-4">
                {matches.length === 0 ? (
                    <p className="rounded-2xl border border-dashed border-white/20 p-6 text-sm text-slate-300">
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
