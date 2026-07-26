import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

function MatchCard({ title, match, empty, actionLabel, actionHref }) {
    return (
        <section className="rounded-2xl border border-border bg-card p-5 text-card-foreground shadow-sm">
            <p className="text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">{title}</p>
            {match ? (
                <div className="mt-3 space-y-2">
                    <div className="flex items-center justify-between gap-3">
                        <h3 className="text-lg font-semibold text-foreground">Zeitlos vs {match.opponent}</h3>
                        <span className="rounded-full border border-border bg-muted px-2.5 py-1 text-xs font-semibold capitalize text-muted-foreground">{match.status}</span>
                    </div>
                    <p className="text-sm text-muted-foreground">{match.match_date}{match.match_time ? ` · ${match.match_time}` : ''}</p>
                    <p className="text-sm text-muted-foreground">{match.venue}</p>
                    {match.status === 'finished' && (
                        <p className="text-2xl font-bold text-foreground">{match.zeitlos_score ?? 0} - {match.opponent_score ?? 0}</p>
                    )}
                    {actionHref && (
                        <Link href={actionHref} className="inline-flex min-h-10 items-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-primary-foreground shadow hover:bg-primary">
                            {actionLabel}
                        </Link>
                    )}
                </div>
            ) : (
                <p className="mt-3 text-sm text-muted-foreground">{empty}</p>
            )}
        </section>
    );
}

function LeaderList({ title, players, stat }) {
    return (
        <section className="rounded-2xl border border-border bg-card p-5 text-card-foreground shadow-sm">
            <div className="flex items-center justify-between gap-3">
                <p className="text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">{title}</p>
                <Link href={route('admin.leaderboard.index')} className="text-sm font-medium text-primary hover:text-primary">Correct stats</Link>
            </div>
            <ol className="mt-4 space-y-3">
                {players.length === 0 ? (
                    <li className="text-sm text-muted-foreground">No players yet.</li>
                ) : players.map((player, index) => (
                    <li key={player.id} className="flex items-center justify-between gap-3 rounded-xl bg-muted px-3 py-3">
                        <div>
                            <p className="text-sm font-semibold text-foreground">{index + 1}. {player.name}</p>
                            <p className="text-xs text-muted-foreground">#{player.jersey_number ?? '-'} · {player.position}</p>
                        </div>
                        <p className="text-xl font-bold text-foreground">{player[stat]}</p>
                    </li>
                ))}
            </ol>
        </section>
    );
}

export default function Dashboard({
    playerCount = 0,
    activePlayerCount = 0,
    matchCount = 0,
    liveMatch = null,
    nextMatch = null,
    recentResult = null,
    topScorers = [],
    topAssists = [],
    recentMatches = [],
}) {
    const cards = [
        { label: 'Players', count: playerCount, detail: `${activePlayerCount} active players`, href: route('admin.players.index'), cta: 'Manage roster' },
        { label: 'Matches', count: matchCount, detail: liveMatch ? 'Live match active' : 'Schedule and results', href: route('admin.matches.index'), cta: 'Manage matches' },
        { label: 'Stats', count: topScorers.length, detail: 'Leaderboard corrections', href: route('admin.leaderboard.index'), cta: 'Correct stats' },
    ];

    return (
        <AuthenticatedLayout
            header={
                <div className="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p className="text-sm font-semibold uppercase tracking-[0.2em] text-muted-foreground">Zeitlos CMS</p>
                        <h2 className="text-2xl font-bold leading-tight text-foreground">Matchday control room</h2>
                    </div>
                    <Link href="/" className="text-sm font-medium text-primary hover:text-primary">View public app</Link>
                </div>
            }
        >
            <Head title="Team Admin" />

            <div className="py-8 sm:py-10">
                <div className="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                    <section className="overflow-hidden rounded-3xl border border-border bg-card text-card-foreground shadow-sm">
                        <div className="grid gap-6 p-5 sm:p-6 lg:grid-cols-[1.4fr_1fr] lg:p-8">
                            <div>
                                <p className="text-xs font-bold uppercase tracking-[0.2em] text-muted-foreground">Next best action</p>
                                <h3 className="mt-3 text-3xl font-black tracking-tight text-foreground sm:text-4xl">
                                    {liveMatch ? `Score Zeitlos vs ${liveMatch.opponent}` : nextMatch ? `Prepare roster vs ${nextMatch.opponent}` : 'Create the next match'}
                                </h3>
                                <p className="mt-3 max-w-2xl text-sm leading-6 text-muted-foreground">
                                    {liveMatch
                                        ? 'A match is live. Open scoring first so goals, assists, and final score stay current.'
                                        : nextMatch
                                            ? 'No match is live. Start by checking roster, payment text, and WhatsApp copy for the upcoming fixture.'
                                            : 'There is no upcoming match. Add one to unlock roster planning and matchday scoring.'}
                                </p>
                                <div className="mt-5 flex flex-col gap-3 sm:flex-row">
                                    <Link
                                        href={liveMatch ? route('admin.matches.scoring.index', liveMatch.id) : nextMatch ? route('admin.matches.roster.index', nextMatch.id) : route('admin.matches.create')}
                                        className="inline-flex min-h-11 items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-bold text-primary-foreground shadow hover:bg-primary"
                                    >
                                        {liveMatch ? 'Open live scoring' : nextMatch ? 'Review match roster' : 'Add match'}
                                    </Link>
                                    <Link href={route('admin.players.create')} className="inline-flex min-h-11 items-center justify-center rounded-xl border border-border bg-background px-5 py-3 text-sm font-bold text-foreground shadow-sm hover:border-primary">
                                        Add player
                                    </Link>
                                </div>
                            </div>
                            <div className="grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
                                <Link href={route('admin.matches.create')} className="rounded-2xl border border-border bg-background p-4 shadow-sm hover:border-primary">
                                    <p className="text-sm font-bold text-foreground">New match</p>
                                    <p className="mt-1 text-xs text-muted-foreground">Schedule, venue, payment info</p>
                                </Link>
                                <Link href={route('admin.matches.index')} className="rounded-2xl border border-border bg-background p-4 shadow-sm hover:border-primary">
                                    <p className="text-sm font-bold text-foreground">Rosters</p>
                                    <p className="mt-1 text-xs text-muted-foreground">Players, guests, WhatsApp copy</p>
                                </Link>
                                <Link href={route('admin.leaderboard.index')} className="rounded-2xl border border-border bg-background p-4 shadow-sm hover:border-primary">
                                    <p className="text-sm font-bold text-foreground">Corrections</p>
                                    <p className="mt-1 text-xs text-muted-foreground">Adjust goals and assists</p>
                                </Link>
                            </div>
                        </div>
                    </section>

                    <div className="grid gap-4 sm:grid-cols-3">
                        {cards.map((card) => (
                            <Link
                                key={card.label}
                                href={card.href}
                                className="block rounded-2xl border border-border bg-card p-5 text-card-foreground shadow-sm transition hover:border-primary hover:shadow-md"
                            >
                                <p className="text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">
                                    {card.label}
                                </p>
                                <div className="mt-3 flex items-end justify-between gap-3">
                                    <p className="text-4xl font-black text-foreground">{card.count}</p>
                                    <p className="text-sm font-semibold text-primary">{card.cta}</p>
                                </div>
                                <p className="mt-2 text-sm text-muted-foreground">{card.detail}</p>
                            </Link>
                        ))}
                    </div>

                    <div className="grid gap-6 lg:grid-cols-3">
                        <MatchCard title="Live match" match={liveMatch} empty="No match is live." actionLabel="Open scoring" actionHref={liveMatch ? route('admin.matches.scoring.index', liveMatch.id) : null} />
                        <MatchCard title="Next match" match={nextMatch} empty="No upcoming match scheduled." actionLabel="Manage roster" actionHref={nextMatch ? route('admin.matches.roster.index', nextMatch.id) : null} />
                        <MatchCard title="Recent result" match={recentResult} empty="No finished result yet." actionLabel="Review scoring" actionHref={recentResult ? route('admin.matches.scoring.index', recentResult.id) : null} />
                    </div>

                    <div className="grid gap-6 lg:grid-cols-2">
                        <LeaderList title="Top scorers" players={topScorers} stat="goals" />
                        <LeaderList title="Top assists" players={topAssists} stat="assists" />
                    </div>

                    <section className="rounded-2xl border border-border bg-card p-5 text-card-foreground shadow-sm">
                        <p className="text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">Recently updated matches</p>
                        <div className="mt-4 divide-y divide-border">
                            {recentMatches.length === 0 ? (
                                <p className="text-sm text-muted-foreground">No matches yet.</p>
                            ) : recentMatches.map((match) => (
                                <div key={match.id} className="flex flex-col gap-2 py-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p className="font-semibold text-foreground">Zeitlos vs {match.opponent}</p>
                                        <p className="text-sm text-muted-foreground">{match.match_date} · {match.venue}</p>
                                    </div>
                                    <div className="flex gap-3 text-sm font-medium">
                                        <Link href={route('admin.matches.roster.index', match.id)} className="text-chart-2 hover:text-chart-2">Roster</Link>
                                        <Link href={route('admin.matches.scoring.index', match.id)} className="text-secondary hover:text-secondary">Scoring</Link>
                                        <Link href={route('admin.matches.edit', match.id)} className="text-primary hover:text-primary">Edit</Link>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </section>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
