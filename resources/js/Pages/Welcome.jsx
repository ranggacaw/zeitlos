import PublicRoster from '@/Components/PublicRoster';
import PublicLayout from '@/Layouts/PublicLayout';
import { Head, Link } from '@inertiajs/react';

function ActionRow({ href, children }) {
    return (
        <Link
            href={href}
            className="flex items-center justify-between rounded-[1.3rem] border border-border bg-background p-4 font-bold text-foreground transition-colors hover:border-primary/40 hover:bg-accent"
        >
            <span>{children}</span>
            <span className="text-primary" aria-hidden="true">-&gt;</span>
        </Link>
    );
}

function InfoCard({ label, value, helper }) {
    return (
        <article className="rounded-[1.3rem] border border-border bg-background p-4 sm:p-5">
            <p className="text-[0.68rem] font-black uppercase tracking-widest text-muted-foreground">{label}</p>
            <p className="mt-3 truncate text-xl font-black text-foreground sm:text-3xl">{value}</p>
            <p className="mt-1 text-xs font-semibold text-muted-foreground sm:text-sm">{helper}</p>
        </article>
    );
}

function MatchCommandCenter({ match }) {
    const rosterCount = match?.roster
        ? Object.values(match.roster).reduce((total, group) => total + group.length, 0)
        : 0;
    const mapHref = match?.maps_url || (match?.venue ? `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(match.venue)}` : null);

    return (
        <section className="relative overflow-hidden rounded-[2rem] border border-border border-t-4 border-t-primary bg-background p-5 sm:p-7 lg:min-h-[430px] lg:p-8">
            <div className="pointer-events-none absolute -right-16 -top-20 h-52 w-52 rounded-full bg-primary opacity-10" />
            <div className="relative flex flex-col gap-5 lg:h-full lg:justify-between">
                <div>
                    <p className="text-xs font-black uppercase tracking-[0.28em] text-primary">Next up</p>
                    <h1 className="mt-3 max-w-2xl text-4xl font-black uppercase leading-none tracking-tight text-foreground sm:text-6xl lg:text-7xl">
                        {match ? `Zeitlos vs ${match.opponent}` : 'Next match TBD'}
                    </h1>
                </div>

                <div className="grid grid-cols-2 gap-3">
                    <InfoCard
                        label="Kickoff"
                        value={match?.match_time ?? 'TBD'}
                        helper={match?.match_date ? `${match.match_date} · arrive early` : 'time not set yet'}
                    />
                    <InfoCard
                        label="Availability"
                        value={rosterCount ? `${rosterCount} ready` : 'Open'}
                        helper={rosterCount ? 'players on roster' : 'roster not set yet'}
                    />
                </div>

                <div className="grid gap-3 sm:grid-cols-[1fr_auto] sm:items-end">
                    <div className="rounded-[1.3rem] border border-dashed border-primary bg-background p-4">
                        <p className="text-[0.68rem] font-black uppercase tracking-widest text-primary">Field</p>
                        <p className="mt-2 text-base font-semibold leading-relaxed text-foreground">
                            {match?.venue ?? 'Field will be announced when the next match is scheduled.'}
                        </p>
                    </div>
                    {mapHref ? (
                        <a
                            href={mapHref}
                            target="_blank"
                            rel="noreferrer"
                            className="flex min-h-14 items-center justify-center rounded-xl bg-primary px-6 text-sm font-black uppercase tracking-widest text-primary-foreground transition-opacity hover:opacity-90"
                        >
                            Open maps
                        </a>
                    ) : (
                        <span className="flex min-h-14 items-center justify-center rounded-xl bg-muted px-6 text-sm font-black uppercase tracking-widest text-muted-foreground">
                            Maps TBD
                        </span>
                    )}
                </div>
            </div>
        </section>
    );
}

function TeamSnapshot({ players, leaders }) {
    return (
        <section className="rounded-[2rem] border border-border border-t-4 bg-background p-5 sm:p-6">
            <div className="flex items-center justify-between gap-4">
                <div>
                    <p className="text-xs font-black uppercase tracking-[0.24em] text-primary">Live pulse</p>
                    <h2 className="mt-2 text-2xl font-black tracking-tight text-foreground">Team snapshot</h2>
                </div>
                <span className="rounded-full border border-border px-3 py-1 text-xs font-black uppercase tracking-wider text-muted-foreground">Public</span>
            </div>
            <div className="mt-5 grid grid-cols-2 gap-3">
                <div className="rounded-[1.3rem] border border-border bg-background p-4">
                    <p className="text-xs font-bold uppercase tracking-widest text-muted-foreground">Squad</p>
                    <p className="mt-2 text-3xl font-black text-foreground">{players.length}</p>
                </div>
                <div className="rounded-[1.3rem] border border-border bg-background p-4">
                    <p className="text-xs font-bold uppercase tracking-widest text-muted-foreground">Leaders</p>
                    <p className="mt-2 text-3xl font-black text-foreground">{leaders.length}</p>
                </div>
            </div>
        </section>
    );
}

function RosterPreview({ players }) {
    return (
        <PublicRoster
            players={players}
            eyebrow="Available players"
            title="Roster at a glance"
            action={<Link href={route('public.roster')} className="text-sm font-black uppercase tracking-widest text-primary">View all</Link>}
            headingTag="h2"
            className="rounded-[2rem] border border-border border-t-4 bg-background p-5 sm:p-7 lg:p-8"
            headerClassName="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between"
            titleClassName="mt-2 text-3xl font-black tracking-tight text-foreground sm:text-4xl"
            contentClassName="space-y-7"
            gridClassName="grid grid-cols-3 gap-3 sm:grid-cols-3 xl:grid-cols-3"
            groupHeadingClassName="text-[0.65rem] font-black uppercase tracking-[0.24em] text-muted-foreground"
            playerCardClassName="group flex flex-col bg-background p-3 transition-colors hover:bg-accent sm:p-4"
            playerPhotoClassName="relative mb-4 aspect-square w-full overflow-hidden rounded-xl bg-muted"
            playerMetaClassName="text-[0.65rem] font-black uppercase tracking-[0.2em] text-primary"
            playerNameClassName="mt-1 break-words text-xs font-black uppercase leading-tight text-foreground sm:text-base"
            emptyClassName="rounded-[1.3rem] border border-dashed border-border bg-background p-5 text-sm font-semibold text-muted-foreground"
            emptyText="No active players yet."
        />
    );
}

function LeaderboardPreview({ leaders }) {
    const previewLeaders = leaders.slice(0, 3);

    return (
        <section className="rounded-[2rem] border border-border border-t-4 bg-background p-5 sm:p-7 lg:p-8">
            <div className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p className="text-xs font-black uppercase tracking-[0.24em] text-primary">Season race</p>
                    <h2 className="mt-2 text-3xl font-black tracking-tight text-foreground sm:text-4xl">Leaderboard</h2>
                </div>
                <Link href={route('public.leaderboard')} className="text-sm font-black uppercase tracking-widest text-primary">Full table</Link>
            </div>

            <div className="mt-6 space-y-3">
                {previewLeaders.length === 0 ? (
                    <p className="rounded-[1.3rem] border border-dashed border-border bg-background p-5 text-sm font-semibold text-muted-foreground">No player stats yet.</p>
                ) : (
                    previewLeaders.map((player, index) => (
                        <div key={player.id} className="grid grid-cols-[auto_1fr_auto] items-center gap-4 rounded-[1.3rem] border border-border bg-background p-4">
                            <span className={`flex h-9 w-9 items-center justify-center rounded-xl text-sm font-black ${index === 0 ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground'}`}>
                                {index + 1}
                            </span>
                            <div className="min-w-0">
                                <p className="truncate font-black text-foreground">{player.name}</p>
                                <p className="text-sm text-muted-foreground">{player.position}</p>
                            </div>
                            <p className={`shrink-0 text-sm font-black uppercase tracking-widest ${index === 0 ? 'text-primary' : 'text-foreground'}`}>
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

            <div className="grid gap-5 lg:grid-cols-[1.45fr_0.85fr]">
                <MatchCommandCenter match={upcomingMatch} />

                <aside className="grid gap-5 lg:grid-rows-[auto_1fr]">
                    <TeamSnapshot players={players} leaders={leaders} />

                    <section className="rounded-[2rem] border border-border border-t-4 bg-background p-5 sm:p-6">
                        <h2 className="text-2xl font-black tracking-tight text-foreground">Quick actions</h2>
                        <div className="mt-5 grid gap-3">
                            <ActionRow href={route('public.roster')}>Check full roster</ActionRow>
                            <ActionRow href={route('public.leaderboard')}>Open leaderboard</ActionRow>
                            <ActionRow href={route('public.schedule')}>See upcoming matches</ActionRow>
                        </div>
                    </section>
                </aside>

                <RosterPreview players={players} />
                <LeaderboardPreview leaders={leaders} />
            </div>
        </PublicLayout>
    );
}
