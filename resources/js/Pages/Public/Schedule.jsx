import PublicLayout from '@/Layouts/PublicLayout';
import { Head } from '@inertiajs/react';
import { useState } from 'react';

const PAGE_SIZE = 10;

const TABS = [
    ['all', 'All'],
    ['upcoming', 'Upcoming'],
    ['finished', 'Finished'],
];

const STATUS_TONE = {
    upcoming: 'bg-primary text-primary-foreground',
    finished: 'bg-muted text-chart-2',
    default: 'bg-muted text-muted-foreground',
};

function paginationItems(currentPage, totalPages) {
    if (totalPages <= 5) {
        return Array.from({ length: totalPages }, (_, index) => index + 1);
    }

    if (currentPage <= 3) {
        return [1, 2, 3, 'ellipsis', totalPages];
    }

    if (currentPage >= totalPages - 2) {
        return [1, 'ellipsis', totalPages - 2, totalPages - 1, totalPages];
    }

    return [1, 'ellipsis-start', currentPage, 'ellipsis-end', totalPages];
}

function rosterCount(match) {
    return Object.values(match.roster ?? {}).flat().length;
}

function formatPaymentAmount(amount) {
    if (amount === null || amount === undefined) {
        return 'TBD';
    }

    return `IDR ${Number(amount).toLocaleString('id-ID', { maximumFractionDigits: 0 })}`;
}

function formatDateTime(value) {
    if (!value) {
        return 'TBD';
    }

    return String(value).replace('T', ' ').slice(0, 16);
}

function matchResult(match) {
    if (match.status !== 'finished') {
        return 'Upcoming';
    }

    if (match.zeitlos_score === null || match.opponent_score === null) {
        return 'Finished';
    }

    if (match.zeitlos_score > match.opponent_score) {
        return `Won ${match.zeitlos_score}-${match.opponent_score}`;
    }

    if (match.zeitlos_score < match.opponent_score) {
        return `Lost ${match.zeitlos_score}-${match.opponent_score}`;
    }

    return `Draw ${match.zeitlos_score}-${match.opponent_score}`;
}

function StatusBadge({ match }) {
    const tone = STATUS_TONE[match.status] ?? STATUS_TONE.default;

    return (
        <span className={`inline-flex items-center rounded-full px-3 py-1 text-[0.65rem] font-black uppercase tracking-[0.18em] ${tone}`}>
            {matchResult(match)}
        </span>
    );
}

function DetailItem({ label, value, children }) {
    return (
        <div className="rounded-2xl bg-muted p-3">
            <p className="text-xs font-black uppercase tracking-[0.16em] text-muted-foreground">{label}</p>
            <p className="mt-1 text-sm font-bold text-foreground">{children ?? value ?? 'TBD'}</p>
        </div>
    );
}

function RosterGroup({ label, players = [] }) {
    if (players.length === 0) {
        return null;
    }

    return (
        <div>
            <p className="text-xs font-black uppercase tracking-[0.16em] text-muted-foreground">{label}</p>
            <div className="mt-2 flex flex-wrap gap-2">
                {players.map((player) => (
                    <span key={player.id} className="rounded-full border border-border px-3 py-1 text-xs font-bold text-foreground">
                        {player.jersey_number ? `#${player.jersey_number} ` : ''}{player.name}
                    </span>
                ))}
            </div>
        </div>
    );
}

function MatchDetailsDialog({ match, onClose }) {
    if (!match) {
        return null;
    }

    const roster = match.roster ?? {};

    return (
        <div className="fixed inset-0 z-50 flex items-end bg-foreground/40 px-3 pb-safe-bottom pt-safe-top sm:items-center sm:justify-center sm:p-6" role="dialog" aria-modal="true" aria-labelledby="match-details-title">
            <button type="button" className="absolute inset-0 cursor-default" aria-label="Close match details" onClick={onClose} />
            <section className="relative max-h-[88vh] w-full overflow-y-auto rounded-t-[2rem] border border-border bg-card p-5 text-card-foreground shadow-2xl sm:max-w-2xl sm:rounded-[2rem] sm:p-6">
                <div className="flex items-start justify-between gap-4">
                    <div>
                        <p className="text-xs font-black uppercase tracking-[0.22em] text-primary">Match details</p>
                        <h2 id="match-details-title" className="mt-2 text-2xl font-black tracking-tight text-foreground">
                            Zeitlos vs {match.opponent}
                        </h2>
                        <p className="mt-2 text-sm font-semibold text-muted-foreground">
                            {match.match_date || 'TBD'} at {match.match_time || 'TBD'}
                        </p>
                    </div>
                    <button type="button" onClick={onClose} className="rounded-full border border-border px-3 py-2 text-sm font-black text-foreground transition hover:bg-muted">
                        Close
                    </button>
                </div>

                <div className="mt-5 grid gap-3 sm:grid-cols-2">
                    <DetailItem label="Status" value={matchResult(match)} />
                    <DetailItem label="Venue">
                        {match.maps_url ? (
                            <a href={match.maps_url} target="_blank" rel="noreferrer" className="text-primary underline underline-offset-4">
                                {match.venue || 'Open map'}
                            </a>
                        ) : match.venue || 'TBD'}
                    </DetailItem>
                    <DetailItem label="Kit" value={match.dress_code} />
                    <DetailItem label="Facilities" value={match.facilities} />
                    <DetailItem label="Payment" value={`${match.payment_label || 'Payment'}: ${formatPaymentAmount(match.payment_amount)}`} />
                    <DetailItem label="Payment due" value={formatDateTime(match.payment_due_at)} />
                </div>

                {(match.payment_instructions || match.notes) && (
                    <div className="mt-5 space-y-3">
                        {match.payment_instructions && <DetailItem label="Payment instructions" value={match.payment_instructions} />}
                        {match.notes && <DetailItem label="Notes" value={match.notes} />}
                    </div>
                )}

                {match.events?.length > 0 && (
                    <div className="mt-5 rounded-2xl border border-border p-4">
                        <h3 className="text-sm font-black uppercase tracking-[0.16em] text-muted-foreground">Match events</h3>
                        <div className="mt-3 space-y-2">
                            {match.events.map((event, index) => (
                                <p key={`${event.minute}-${event.event_type}-${index}`} className="text-sm font-bold text-foreground">
                                    {event.minute ? `${event.minute}'` : 'FT'} {event.event_type}: {event.scorer || 'Zeitlos'}{event.assist ? `, assist ${event.assist}` : ''}
                                </p>
                            ))}
                        </div>
                    </div>
                )}

                {rosterCount(match) > 0 && (
                    <div className="mt-5 rounded-2xl border border-border p-4">
                        <h3 className="text-sm font-black uppercase tracking-[0.16em] text-muted-foreground">Roster</h3>
                        <div className="mt-4 space-y-4">
                            <RosterGroup label="Players" players={roster.player} />
                            <RosterGroup label="Goalkeepers" players={roster.goalkeeper} />
                            <RosterGroup label="Captains" players={roster.captain} />
                            <RosterGroup label="Guests" players={roster.guest} />
                        </div>
                    </div>
                )}
            </section>
        </div>
    );
}

function MatchCard({ match, onSelect }) {
    return (
        <button type="button" onClick={() => onSelect(match)} className="block w-full rounded-3xl border border-border p-4 text-left transition hover:border-primary hover:bg-primary/5 focus:outline-none focus:ring-2 focus:ring-primary sm:p-5">
            <div className="flex items-start justify-between gap-3">
                <div className="min-w-0">
                    <h3 className="text-sm font-black leading-tight text-foreground sm:text-xl">
                        Zeitlos vs {match.opponent}
                    </h3>
                    <p className="mt-2 text-sm text-muted-foreground">
                        {match.match_date || 'TBD'} at {match.match_time || 'TBD'}
                    </p>
                    <p className="text-sm text-muted-foreground">
                        {match.venue || 'TBD'}
                    </p>
                </div>
                <StatusBadge match={match} />
            </div>

            <dl className="mt-4 grid grid-cols-3 gap-2 text-sm">
                <div className="rounded-2xl bg-card p-3 text-card-foreground">
                    <dt className="font-bold text-muted-foreground">Kit</dt>
                    <dd className="mt-1 font-black">{match.dress_code || 'TBD'}</dd>
                </div>
                <div className="rounded-2xl bg-card p-3 text-card-foreground">
                    <dt className="font-bold text-muted-foreground">Pay</dt>
                    <dd className="mt-1 font-black">{formatPaymentAmount(match.payment_amount)}</dd>
                </div>
                <div className="rounded-2xl bg-card p-3 text-card-foreground">
                    <dt className="font-bold text-muted-foreground">Roster</dt>
                    <dd className="mt-1 font-black">{rosterCount(match)}</dd>
                </div>
            </dl>
            <p className="mt-3 text-xs font-black uppercase tracking-[0.18em] text-primary">Tap for details</p>
        </button>
    );
}

function MatchCards({ matches, onSelect }) {
    if (matches.length === 0) {
        return (
            <p className="rounded-3xl border border-dashed border-border bg-card p-6 text-sm font-semibold text-muted-foreground">
                No matches to show.
            </p>
        );
    }

    return (
        <div className="space-y-3">
            {matches.map((match) => <MatchCard key={match.id} match={match} onSelect={onSelect} />)}
        </div>
    );
}

function MatchesTable({ matches, currentPage, onPageChange, onSelect }) {
    const totalPages = Math.max(1, Math.ceil(matches.length / PAGE_SIZE));
    const start = (currentPage - 1) * PAGE_SIZE;
    const visibleMatches = matches.slice(start, start + PAGE_SIZE);
    const pages = paginationItems(currentPage, totalPages);

    return (
        <section className="overflow-hidden rounded-[1.55rem] border border-border bg-card text-card-foreground shadow-sm">
            <div className="flex flex-col gap-3 border-b border-border bg-muted p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 className="text-xl font-black text-foreground">All matches</h2>
                    <p className="mt-1 text-sm font-semibold text-muted-foreground">
                        Showing {matches.length === 0 ? 0 : start + 1}-{Math.min(start + PAGE_SIZE, matches.length)} of {matches.length}
                    </p>
                </div>
                <p className="rounded-full border border-border bg-card px-4 py-2 text-sm font-black text-foreground">
                    10 per page
                </p>
            </div>

            <div className="overflow-x-auto">
                <table className="w-full min-w-[48rem] text-left text-sm">
                    <thead className="border-b border-border bg-muted text-xs uppercase tracking-[0.16em] text-muted-foreground">
                        <tr>
                            <th className="px-5 py-4 font-black">Match</th>
                            <th className="px-5 py-4 font-black">Date</th>
                            <th className="px-5 py-4 font-black">Venue</th>
                            <th className="px-5 py-4 font-black">Roster</th>
                            <th className="px-5 py-4 text-right font-black">Status</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-border bg-card">
                        {visibleMatches.length === 0 ? (
                            <tr>
                                <td colSpan={5} className="px-5 py-8 text-center font-semibold text-muted-foreground">
                                    No matches to show.
                                </td>
                            </tr>
                        ) : visibleMatches.map((match) => (
                            <tr
                                key={match.id}
                                role="button"
                                tabIndex={0}
                                onClick={() => onSelect(match)}
                                onKeyDown={(event) => {
                                    if (event.key === 'Enter' || event.key === ' ') {
                                        event.preventDefault();
                                        onSelect(match);
                                    }
                                }}
                                className={`cursor-pointer transition hover:bg-primary/10 focus:bg-primary/10 focus:outline-none ${match.status === 'upcoming' ? 'bg-primary/5' : ''}`}
                            >
                                <td className="px-5 py-4 text-foreground">Zeitlos vs {match.opponent}</td>
                                <td className="px-5 py-4">{match.match_date || 'TBD'}</td>
                                <td className="px-5 py-4">{match.venue || 'TBD'}</td>
                                <td className="px-5 py-4">{rosterCount(match)}</td>
                                <td className="px-5 py-4 text-right"><StatusBadge match={match} /></td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            <div className="flex flex-col gap-3 border-t border-border p-4 sm:flex-row sm:items-center sm:justify-between">
                <p className="text-sm font-bold text-muted-foreground">Page {currentPage} of {totalPages}</p>
                <div className="flex flex-wrap items-center gap-2 text-sm">
                    <button
                        type="button"
                        disabled={currentPage === 1}
                        onClick={() => onPageChange(currentPage - 1)}
                        className="rounded-full border border-border px-4 py-2 text-foreground transition hover:bg-muted disabled:text-muted-foreground"
                    >
                        Previous
                    </button>
                    {pages.map((page) => page.toString().startsWith('ellipsis') ? (
                        <span key={page} className="px-1 text-muted-foreground">...</span>
                    ) : (
                        <button
                            key={page}
                            type="button"
                            onClick={() => onPageChange(page)}
                            className={`rounded-full border border-border px-4 py-2 transition ${
                                currentPage === page
                                    ? 'bg-primary text-primary-foreground'
                                    : 'bg-card text-foreground hover:bg-muted'
                            }`}
                        >
                            {page}
                        </button>
                    ))}
                    <button
                        type="button"
                        disabled={currentPage === totalPages}
                        onClick={() => onPageChange(currentPage + 1)}
                        className="rounded-full border border-border px-4 py-2 text-foreground transition hover:bg-muted disabled:text-muted-foreground"
                    >
                        Next
                    </button>
                </div>
            </div>
        </section>
    );
}

export default function Schedule({ upcomingMatches = [], finishedMatches = [] }) {
    const [activeTab, setActiveTab] = useState('all');
    const [currentPage, setCurrentPage] = useState(1);
    const [selectedMatch, setSelectedMatch] = useState(null);
    const allMatches = [...upcomingMatches, ...finishedMatches];

    function selectTab(tab) {
        setActiveTab(tab);
        setCurrentPage(1);
    }

    return (
        <PublicLayout>
            <Head title="Schedule" />

            <div className="space-y-6">
                <section className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p className="text-xs font-black uppercase tracking-[0.28em] text-primary">Schedule</p>
                        <h1 className="mt-2 text-2xl font-black tracking-tight text-foreground sm:text-5xl">Matches</h1>
                    </div>
                    <div className="grid grid-cols-3 gap-2 text-center text-sm sm:w-80">
                        <div className="rounded-[1.3rem] border border-border bg-card p-3 text-card-foreground shadow-sm">
                            <p className="text-xl font-black text-foreground">{upcomingMatches.length}</p>
                            <p className="text-xs text-muted-foreground">Upcoming</p>
                        </div>
                        <div className="rounded-[1.3rem] border border-border bg-card p-3 text-card-foreground shadow-sm">
                            <p className="text-xl font-black text-foreground">{finishedMatches.length}</p>
                            <p className="text-xs text-muted-foreground">Finished</p>
                        </div>
                        <div className="rounded-[1.3rem] border border-border bg-card p-3 text-card-foreground shadow-sm">
                            <p className="text-xl font-black text-foreground">{allMatches.length}</p>
                            <p className="text-xs text-muted-foreground">Total</p>
                        </div>
                    </div>
                </section>

                <section className="flex gap-2 overflow-x-auto pb-1 text-sm" aria-label="Match filters">
                    {TABS.map(([tab, label]) => (
                        <button
                            key={tab}
                            type="button"
                            onClick={() => selectTab(tab)}
                            className={`shrink-0 rounded-full border border-border px-4 py-2 transition ${
                                activeTab === tab
                                    ? 'bg-primary text-primary-foreground'
                                    : 'bg-card text-card-foreground hover:bg-muted'
                            }`}
                        >
                            {label}
                        </button>
                    ))}
                </section>

                {activeTab === 'all' && (
                    <MatchesTable matches={allMatches} currentPage={currentPage} onPageChange={setCurrentPage} onSelect={setSelectedMatch} />
                )}

                {activeTab === 'upcoming' && <MatchCards matches={upcomingMatches} onSelect={setSelectedMatch} />}

                {activeTab === 'finished' && <MatchCards matches={finishedMatches} onSelect={setSelectedMatch} />}
            </div>

            <MatchDetailsDialog match={selectedMatch} onClose={() => setSelectedMatch(null)} />
        </PublicLayout>
    );
}
