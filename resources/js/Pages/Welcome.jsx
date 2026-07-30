import PublicRoster from '@/Components/PublicRoster';
import PublicLayout from '@/Layouts/PublicLayout';
import { Head, Link } from '@inertiajs/react';

function ActionRow({ href, children }) {
    return (
        <Link
            href={href}
            className="group flex items-center justify-between rounded-2xl border border-border bg-card px-4 py-3 text-sm font-bold text-card-foreground transition hover:border-primary/40 hover:bg-background"
        >
            <span>{children}</span>
            <span className="text-primary transition group-hover:translate-x-0.5" aria-hidden="true">-&gt;</span>
        </Link>
    );
}

function StatPill({ label, value }) {
    return (
        <div className="rounded-2xl border border-border bg-background px-4 py-3">
            <p className="text-[0.68rem] font-black uppercase tracking-widest text-muted-foreground">{label}</p>
            <p className="mt-1 truncate text-lg font-black text-foreground sm:text-xl">{value}</p>
        </div>
    );
}

function MatchCommandCenter({ match }) {
    const rosterCount = match?.roster
        ? Object.values(match.roster).reduce((total, group) => total + group.length, 0)
        : 0;
    const mapHref = match?.maps_url || (match?.venue ? `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(match.venue)}` : null);

    return (
        <section className="rounded-[1.75rem] border border-border bg-card p-5 text-card-foreground sm:p-6 lg:p-7">
            <div className="flex flex-col gap-5">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div className="min-w-0">
                        <p className="text-[0.7rem] font-black uppercase tracking-[0.24em] text-primary">Next match</p>
                        <h1 className="mt-2 text-2xl font-black leading-tight tracking-tight text-foreground sm:text-3xl lg:text-4xl">
                            {match ? `Zeitlos vs ${match.opponent}` : 'Match to be announced'}
                        </h1>
                        <p className="mt-2 text-sm font-semibold text-muted-foreground">
                            {match?.match_date ? `${match.match_date} at ${match.match_time ?? 'TBD'}` : 'Schedule will update once the fixture is set.'}
                        </p>
                    </div>
                    {mapHref ? (
                        <a
                            href={mapHref}
                            target="_blank"
                            rel="noreferrer"
                            className="inline-flex min-h-11 shrink-0 items-center justify-center rounded-xl bg-primary px-4 text-xs font-black uppercase tracking-widest text-primary-foreground transition hover:bg-secondary"
                        >
                            Maps
                        </a>
                    ) : (
                        <span className="inline-flex min-h-11 shrink-0 items-center justify-center rounded-xl bg-muted px-4 text-xs font-black uppercase tracking-widest text-muted-foreground">
                            Maps TBD
                        </span>
                    )}
                </div>

                <div className="grid gap-3 sm:grid-cols-3">
                    <StatPill label="Kickoff" value={match?.match_time ?? 'TBD'} />
                    <StatPill label="Roster" value={rosterCount ? `${rosterCount} ready` : 'Open'} />
                    <StatPill label="Venue" value={match?.venue ?? 'TBD'} />
                </div>
            </div>
        </section>
    );
}

function TeamSnapshot({ players, leaders }) {
    return (
        <section className="rounded-[1.75rem] border border-border bg-card p-5 text-card-foreground sm:p-6">
            <div className="flex items-center justify-between gap-3">
                <h2 className="text-lg font-black tracking-tight text-foreground">Team snapshot</h2>
                <span className="rounded-full border border-border bg-background px-3 py-1 text-[0.65rem] font-black uppercase tracking-wider text-muted-foreground">Public</span>
            </div>
            <div className="mt-4 grid grid-cols-2 gap-3">
                <StatPill label="Squad" value={players.length} />
                <StatPill label="Leaders" value={leaders.length} />
            </div>
        </section>
    );
}

function QuickActions() {
    return (
        <section className="rounded-[1.75rem] border border-border bg-card p-5 text-card-foreground sm:p-6">
            <h2 className="text-lg font-black tracking-tight text-foreground">Quick actions</h2>
            <div className="mt-4 grid gap-3">
                <ActionRow href={route('public.roster')}>Full roster</ActionRow>
                <ActionRow href={route('public.schedule')}>Match schedule</ActionRow>
                <ActionRow href={route('public.leaderboard')}>Leaderboard</ActionRow>
            </div>
        </section>
    );
}

function RosterPreview({ players }) {
    return (
        <PublicRoster
            players={players}
            eyebrow="Available players"
            title="Roster"
            action={<Link href={route('public.roster')} className="text-xs font-black uppercase tracking-widest text-primary transition hover:text-secondary">View all</Link>}
            headingTag="h2"
            className="rounded-[1.75rem] border border-border bg-card p-5 text-card-foreground sm:p-6 lg:p-7"
            headerClassName="mb-5 flex flex-row items-end justify-between gap-4"
            titleClassName="mt-1 text-xl font-black tracking-tight text-foreground sm:text-2xl"
            contentClassName="space-y-6"
            gridClassName="grid grid-cols-3 gap-2.5 sm:grid-cols-3 xl:grid-cols-4"
            groupHeadingClassName="text-[0.65rem] font-black uppercase tracking-[0.24em] text-muted-foreground"
            playerCardClassName="group flex flex-col rounded-2xl border border-border bg-background p-2.5 transition hover:border-primary/40 hover:bg-card sm:p-3"
            playerPhotoClassName="relative mb-3 aspect-square w-full overflow-hidden rounded-xl bg-muted"
            playerMetaClassName="text-[0.65rem] font-black uppercase tracking-[0.2em] text-primary"
            playerNameClassName="mt-1 break-words text-xs font-black leading-tight text-foreground sm:text-sm"
            emptyClassName="rounded-2xl border border-dashed border-border bg-background p-5 text-sm font-semibold text-muted-foreground"
            emptyText="No active players yet."
        />
    );
}

function LeaderboardPreview({ leaders }) {
    const previewLeaders = leaders.slice(0, 3);

    return (
        <section className="rounded-[1.75rem] border border-border bg-card p-5 text-card-foreground sm:p-6 lg:p-7">
            <div className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p className="text-[0.7rem] font-black uppercase tracking-[0.24em] text-primary">Season race</p>
                    <h2 className="mt-1 text-xl font-black tracking-tight text-foreground sm:text-2xl">Leaderboard</h2>
                </div>
                <Link href={route('public.leaderboard')} className="text-xs font-black uppercase tracking-widest text-primary transition hover:text-secondary">Full table</Link>
            </div>

            <div className="mt-5 space-y-3">
                {previewLeaders.length === 0 ? (
                    <p className="rounded-2xl border border-dashed border-border bg-background p-5 text-sm font-semibold text-muted-foreground">No player stats yet.</p>
                ) : (
                    previewLeaders.map((player, index) => (
                        <div key={player.id} className="grid grid-cols-[auto_1fr_auto] items-center gap-3 rounded-2xl border border-border bg-background p-3.5">
                            <span className={`flex h-9 w-9 items-center justify-center rounded-xl text-sm font-black ${index === 0 ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground'}`}>
                                {index + 1}
                            </span>
                            <div className="min-w-0">
                                <p className="truncate text-sm font-black text-foreground sm:text-base">{player.name}</p>
                                <p className="text-xs font-semibold text-muted-foreground sm:text-sm">{player.position}</p>
                            </div>
                            <p className={`shrink-0 text-xs font-black uppercase tracking-widest sm:text-sm ${index === 0 ? 'text-primary' : 'text-foreground'}`}>
                                {player.goals}G · {player.assists}A
                            </p>
                        </div>
                    ))
                )}
            </div>
        </section>
    );
}

export default function Welcome({ upcomingMatch, players = [], leaders = [] }) {
    return (
        <PublicLayout>
            <Head title="Zeitlos Team Hub" />

            <div className="grid gap-5 lg:grid-cols-[minmax(0,1.45fr)_minmax(320px,0.75fr)]">
                <MatchCommandCenter match={upcomingMatch} />

                <aside className="grid gap-5">
                    <TeamSnapshot players={players} leaders={leaders} />
                    <QuickActions />
                </aside>

                <RosterPreview players={players} />
                <LeaderboardPreview leaders={leaders} />
            </div>
        </PublicLayout>
    );
}
