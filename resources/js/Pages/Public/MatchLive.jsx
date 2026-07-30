import PublicLayout from '@/Layouts/PublicLayout';
import { Head, Link, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';

const STATUS_LABELS = {
    starting: 'Starting soon',
    live: 'Live now',
    finished: 'Final score',
};

function rosterCount(match) {
    return Object.values(match.roster ?? {}).flat().length;
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
                    <span key={player.id} className="rounded-full border border-border bg-background px-3 py-1 text-xs font-bold text-foreground">
                        {player.jersey_number ? `#${player.jersey_number} ` : ''}{player.name}
                    </span>
                ))}
            </div>
        </div>
    );
}

function Timeline({ events = [] }) {
    if (events.length === 0) {
        return (
            <p className="rounded-2xl border border-dashed border-border bg-background p-5 text-sm font-semibold text-muted-foreground">
                No goals recorded yet. This timeline updates when the admin records a goal.
            </p>
        );
    }

    return (
        <div className="space-y-3">
            {events.map((event, index) => (
                <div key={`${event.minute}-${event.scorer}-${index}`} className="grid grid-cols-[auto_1fr] gap-3 rounded-2xl border border-border bg-background p-4">
                    <span className="flex h-11 w-11 items-center justify-center rounded-xl bg-primary text-sm font-black text-primary-foreground">
                        {event.minute ? `${event.minute}'` : 'Goal'}
                    </span>
                    <div>
                        <p className="text-sm font-black text-foreground">
                            {event.team === 'opponent' ? 'Enemy team' : (event.scorer || 'Zeitlos')} scored
                        </p>
                        {event.team === 'opponent' && <p className="mt-1 text-xs font-semibold text-muted-foreground">Opponent goal</p>}
                        {event.assist && <p className="mt-1 text-xs font-semibold text-muted-foreground">Assist: {event.assist}</p>}
                    </div>
                </div>
            ))}
        </div>
    );
}

export default function MatchLive({ match }) {
    const [liveMatch, setLiveMatch] = useState(match);
    const [connectionState, setConnectionState] = useState(window.Echo ? 'Live updates on' : 'Polling updates');
    const score = `${liveMatch.zeitlos_score ?? 0} : ${liveMatch.opponent_score ?? 0}`;
    const roster = liveMatch.roster ?? {};
    const mapHref = liveMatch.maps_url || (liveMatch.venue ? `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(liveMatch.venue)}` : null);

    useEffect(() => {
        setLiveMatch(match);
    }, [match]);

    useEffect(() => {
        const interval = window.setInterval(() => {
            router.reload({ only: ['match'], preserveScroll: true });
        }, 10000);

        return () => window.clearInterval(interval);
    }, []);

    useEffect(() => {
        if (!window.Echo) {
            return undefined;
        }

        const channelName = `public-match.${liveMatch.id}`;
        const channel = window.Echo.channel(channelName)
            .listen('.PublicMatchUpdated', (event) => {
                setLiveMatch(event.match);
                setConnectionState('Live updates on');
            });

        if (window.Echo.connector?.pusher?.connection) {
            window.Echo.connector.pusher.connection.bind('disconnected', () => setConnectionState('Reconnecting, polling backup on'));
            window.Echo.connector.pusher.connection.bind('connected', () => setConnectionState('Live updates on'));
        }

        return () => {
            channel.stopListening('.PublicMatchUpdated');
            window.Echo.leave(channelName);
        };
    }, [liveMatch.id]);

    return (
        <PublicLayout>
            <Head title={`Live Score: Zeitlos vs ${liveMatch.opponent}`} />

            <div className="space-y-5">
                <section className="rounded-[2rem] border border-border bg-card p-5 text-card-foreground shadow-sm sm:p-7">
                    <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p className="text-xs font-black uppercase tracking-[0.28em] text-primary">
                                {STATUS_LABELS[liveMatch.status] ?? liveMatch.status}
                            </p>
                            <h1 className="mt-2 text-3xl font-black tracking-tight text-foreground sm:text-5xl">
                                Zeitlos vs {liveMatch.opponent}
                            </h1>
                            <p className="mt-2 text-sm font-semibold text-muted-foreground">
                                {liveMatch.match_date || 'TBD'} at {liveMatch.match_time || 'TBD'} · {liveMatch.venue || 'Venue TBD'}
                            </p>
                        </div>
                        <Link href={route('public.schedule')} className="inline-flex min-h-11 items-center justify-center rounded-sm border border-border px-4 text-xs font-black uppercase tracking-widest text-foreground transition hover:bg-muted">
                            Back to schedule
                        </Link>
                    </div>

                    <div className="mt-6 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 rounded-sm bg-background p-4 text-center">
                        <p className="text-sm font-black uppercase tracking-widest text-muted-foreground">Zeitlos</p>
                        <p className="text-3xl font-black text-foreground">{liveMatch.zeitlos_score ?? 0}</p>
                        <span className="text-xl font-black text-muted-foreground">:</span>
                        <p className="text-3xl font-black text-foreground">{liveMatch.opponent_score ?? 0}</p>
                        <p className="text-sm font-black uppercase tracking-widest text-muted-foreground">{liveMatch.opponent}</p>
                    </div>

                    <div className="mt-5 flex flex-wrap items-center gap-3">
                        <p className="rounded-sm px-4 py-2 text-sm font-black uppercase tracking-widest bg-background text-foreground">
                            {liveMatch.status === 'finished' ? `Final ${score}` : connectionState}
                        </p>
                        {mapHref && (
                            <a href={mapHref} target="_blank" rel="noreferrer" className="inline-flex min-h-11 items-center justify-center rounded-sm border border-border  bg-primary text-primary-foreground px-4 text-xs font-black uppercase tracking-widest transition hover:bg-muted">
                                Open maps
                            </a>
                        )}
                    </div>
                </section>

                <div className="grid gap-5 lg:grid-cols-[minmax(0,1fr)_22rem]">
                    <section className="rounded-[1.75rem] border border-border bg-card p-5 text-card-foreground sm:p-6">
                        <h2 className="text-xl font-black tracking-tight text-foreground">Goal timeline</h2>
                        <div className="mt-4">
                            <Timeline events={liveMatch.events} />
                        </div>
                    </section>

                    <section className="rounded-[1.75rem] border border-border bg-card p-5 text-card-foreground sm:p-6">
                        <div className="flex items-center justify-between gap-3">
                            <h2 className="text-xl font-black tracking-tight text-foreground">Roster</h2>
                            <span className="rounded-full bg-muted px-3 py-1 text-xs font-black text-muted-foreground">{rosterCount(liveMatch)}</span>
                        </div>
                        {rosterCount(liveMatch) === 0 ? (
                            <p className="mt-4 rounded-2xl border border-dashed border-border bg-background p-5 text-sm font-semibold text-muted-foreground">Roster not published yet.</p>
                        ) : (
                            <div className="mt-4 space-y-4">
                                <RosterGroup label="Players" players={roster.player} />
                                <RosterGroup label="Goalkeepers" players={roster.goalkeeper} />
                                <RosterGroup label="Captains" players={roster.captain} />
                                <RosterGroup label="Guests" players={roster.guest} />
                            </div>
                        )}
                    </section>
                </div>
            </div>
        </PublicLayout>
    );
}
