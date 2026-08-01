export function matchResult(match) {
    if (!match) {
        return '';
    }

    if (match.status === 'starting') {
        return 'Starting';
    }

    if (match.status === 'live') {
        return `Live ${match.zeitlos_score ?? 0}-${match.opponent_score ?? 0}`;
    }

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

export function rosterCount(match) {
    return Object.values(match?.roster ?? {}).flat().length;
}

export function formatPaymentAmount(amount) {
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

function DetailItem({ label, value, children }) {
    return (
        <div className="rounded-2xl bg-muted p-3">
            <p className="text-xs font-black uppercase tracking-[0.16em] text-muted-foreground">{label}</p>
            <p className="mt-1 text-sm text-foreground">{children ?? value ?? 'TBD'}</p>
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

export default function MatchDetailsDialog({ match, onClose }) {
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
                    <button type="button" onClick={onClose} aria-label="Close match details" className="shrink-0 rounded-full border border-border p-2 text-foreground transition hover:bg-muted">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth={1.8} strokeLinecap="round" strokeLinejoin="round" className="h-5 w-5" aria-hidden="true">
                            <path d="M6 6l12 12M18 6L6 18" />
                        </svg>
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
                                    {event.minute ? `${event.minute}'` : 'FT'} {event.event_type}: {event.team === 'opponent' ? 'Enemy team' : (event.scorer || 'Zeitlos')}{event.assist ? `, assist ${event.assist}` : ''}
                                </p>
                            ))}
                        </div>
                    </div>
                )}

                {rosterCount(match) > 0 && (
                    <div className="mt-5 rounded-2xl border border-border p-4">
                        <h3 className="text-sm font-black uppercase tracking-[0.16em] text-muted-foreground">Players</h3>
                        <div className="mt-4 space-y-4">
                            <RosterGroup players={roster.player} />
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
