import MatchDetailsDialog, { formatPaymentAmount, matchResult, rosterCount } from '@/Components/MatchDetailsDialog';
import PublicLayout from '@/Layouts/PublicLayout';
import { Head, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';

const PAGE_SIZE = 10;

const TABS = [
    ['all', 'All'],
    ['active', 'Live'],
    ['upcoming', 'Upcoming'],
    ['finished', 'Finished'],
];

const STATUS_TONE = {
    scheduled: 'bg-primary text-primary-foreground',
    starting: 'bg-chart-4 text-primary-foreground',
    live: 'bg-destructive text-destructive-foreground',
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

function StatusBadge({ match }) {
    const tone = STATUS_TONE[match.status] ?? STATUS_TONE.default;

    return (
        <span className={`inline-flex items-center rounded-full px-3 py-1 text-[0.65rem] font-black uppercase tracking-[0.18em] ${tone}`}>
            {matchResult(match)}
        </span>
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
                                className={`cursor-pointer transition hover:bg-primary/10 focus:bg-primary/10 focus:outline-none ${['starting', 'live'].includes(match.status) ? 'bg-primary/5' : ''}`}
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

export default function Schedule({ activeMatches = [], upcomingMatches = [], finishedMatches = [] }) {
    const [activeTab, setActiveTab] = useState('all');
    const [currentPage, setCurrentPage] = useState(1);
    const [selectedMatch, setSelectedMatch] = useState(null);
    const allMatches = [...activeMatches, ...upcomingMatches, ...finishedMatches];

    useEffect(() => {
        const interval = window.setInterval(() => {
            router.reload({ only: ['activeMatches', 'upcomingMatches', 'finishedMatches'], preserveScroll: true });
        }, 10000);

        return () => window.clearInterval(interval);
    }, []);

    function selectMatch(match) {
        if (['starting', 'live'].includes(match.status)) {
            router.visit(route('public.matches.live', match.id));
            return;
        }

        setSelectedMatch(match);
    }

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
                            <p className="text-xl font-black text-foreground">{activeMatches.length}</p>
                            <p className="text-xs text-muted-foreground">Live</p>
                        </div>
                        <div className="rounded-[1.3rem] border border-border bg-card p-3 text-card-foreground shadow-sm">
                            <p className="text-xl font-black text-foreground">{upcomingMatches.length}</p>
                            <p className="text-xs text-muted-foreground">Upcoming</p>
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
                    <MatchesTable matches={allMatches} currentPage={currentPage} onPageChange={setCurrentPage} onSelect={selectMatch} />
                )}

                {activeTab === 'active' && <MatchCards matches={activeMatches} onSelect={selectMatch} />}

                {activeTab === 'upcoming' && <MatchCards matches={upcomingMatches} onSelect={selectMatch} />}

                {activeTab === 'finished' && <MatchCards matches={finishedMatches} onSelect={selectMatch} />}
            </div>

            <MatchDetailsDialog match={selectedMatch} onClose={() => setSelectedMatch(null)} />
        </PublicLayout>
    );
}
