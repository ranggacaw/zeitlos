import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, Link, useForm } from '@inertiajs/react';

const labelClass = 'block text-sm font-bold text-foreground';
const fieldClass = 'mt-2 block min-h-11 w-full rounded-xl border-border bg-input text-foreground shadow-sm focus:border-ring focus:ring-ring';

export default function Scoring({ match, scoringPlayers = [], events = [] }) {
    const goalForm = useForm({
        scorer_id: '',
        assist_player_id: '',
        minute: '',
    });

    const finalForm = useForm({
        zeitlos_score: match.zeitlos_score ?? '',
        opponent_score: match.opponent_score ?? '',
    });

    const liveForm = useForm({});

    const submitGoal = (e) => {
        e.preventDefault();
        goalForm.post(route('admin.matches.scoring.events.store', match.id), {
            preserveScroll: true,
            onSuccess: () => goalForm.reset('scorer_id', 'assist_player_id', 'minute'),
        });
    };

    const removeEvent = (event) => {
        if (confirm(`Remove goal by "${event.scorer ?? 'Unknown'}"?`)) {
            goalForm.delete(route('admin.matches.scoring.events.destroy', [match.id, event.id]), {
                preserveScroll: true,
            });
        }
    };

    const submitFinal = (e) => {
        e.preventDefault();
        finalForm.post(route('admin.matches.scoring.final-score.store', match.id), {
            preserveScroll: true,
        });
    };

    const startLive = () => {
        liveForm.post(route('admin.matches.live.store', match.id), { preserveScroll: true });
    };

    const playerLabel = (player) => `#${player.jersey_number ?? '-'} ${player.name}${player.position ? ` (${player.position})` : ''}`;

    return (
        <AuthenticatedLayout
            header={
                <div className="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p className="text-sm font-semibold uppercase tracking-[0.2em] text-muted-foreground">Live console</p>
                        <h2 className="text-2xl font-bold leading-tight text-foreground">Zeitlos vs {match.opponent}</h2>
                    </div>
                    <Link href={route('admin.matches.index')} className="text-sm font-medium text-primary hover:text-primary">Back to matches</Link>
                </div>
            }
        >
            <Head title="Match Scoring" />

            <div className="py-8 sm:py-10">
                <div className="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                    <section className="rounded-3xl border border-border bg-card p-5 text-card-foreground shadow-sm sm:p-6">
                        <div className="grid gap-5 lg:grid-cols-[1fr_auto] lg:items-center">
                            <div>
                                <p className="text-xs font-bold uppercase tracking-[0.2em] text-muted-foreground">Current score</p>
                                <div className="mt-3 flex items-center gap-4 text-4xl font-black tracking-tight text-foreground sm:text-5xl">
                                    <span>{finalForm.data.zeitlos_score === '' ? match.zeitlos_score ?? 0 : finalForm.data.zeitlos_score}</span>
                                    <span className="text-muted-foreground">-</span>
                                    <span>{finalForm.data.opponent_score === '' ? match.opponent_score ?? 0 : finalForm.data.opponent_score}</span>
                                </div>
                                <p className="mt-2 text-sm text-muted-foreground">Recorded goals: {events.length}. Use final score when the match ends.</p>
                            </div>
                            <div className="rounded-2xl border border-border bg-background p-4">
                                <p className="text-xs font-bold uppercase tracking-wide text-muted-foreground">Status</p>
                                <p className="mt-1 text-2xl font-black capitalize text-foreground">{match.status}</p>
                                {match.status !== 'finished' && match.status !== 'live' && (
                                    <button
                                        type="button"
                                        onClick={startLive}
                                        disabled={liveForm.processing}
                                        className="mt-3 inline-flex min-h-11 w-full items-center justify-center rounded-xl bg-secondary px-4 py-2 text-sm font-bold text-secondary-foreground shadow hover:bg-secondary"
                                    >
                                        Start live match
                                    </button>
                                )}
                            </div>
                        </div>
                    </section>

                    <div className="grid gap-6 lg:grid-cols-[1fr_1.1fr]">
                        <section className="rounded-2xl border border-border bg-card p-5 text-card-foreground shadow-sm sm:p-6">
                            <div className="flex flex-col gap-1">
                                <p className="text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">Fast entry</p>
                                <h3 className="text-xl font-black text-foreground">Record goal</h3>
                                <p className="text-sm text-muted-foreground">Pick scorer, optional assist, and minute.</p>
                            </div>

                            <form onSubmit={submitGoal} className="mt-5 space-y-4">
                                <div>
                                    <label className={labelClass}>Scorer</label>
                                    <select
                                        value={goalForm.data.scorer_id}
                                        onChange={(e) => goalForm.setData('scorer_id', e.target.value)}
                                        className={fieldClass}
                                    >
                                        <option value="">Select scorer</option>
                                        {scoringPlayers.map((player) => (
                                            <option key={player.id} value={player.id}>
                                                {playerLabel(player)}
                                            </option>
                                        ))}
                                    </select>
                                    <InputError className="mt-2" message={goalForm.errors.scorer_id} />
                                </div>

                                <div>
                                    <label className={labelClass}>Assist (optional)</label>
                                    <select
                                        value={goalForm.data.assist_player_id}
                                        onChange={(e) => goalForm.setData('assist_player_id', e.target.value)}
                                        className={fieldClass}
                                    >
                                        <option value="">No assist</option>
                                        {scoringPlayers.map((player) => (
                                            <option key={player.id} value={player.id}>
                                                {playerLabel(player)}
                                            </option>
                                        ))}
                                    </select>
                                    <InputError className="mt-2" message={goalForm.errors.assist_player_id} />
                                </div>

                                <div>
                                    <label className={labelClass}>Minute (optional)</label>
                                    <input
                                        type="number"
                                        min="0"
                                        inputMode="numeric"
                                        value={goalForm.data.minute}
                                        onChange={(e) => goalForm.setData('minute', e.target.value)}
                                        placeholder="e.g. 23"
                                        className={fieldClass}
                                    />
                                    <InputError className="mt-2" message={goalForm.errors.minute} />
                                </div>

                                <button
                                    type="submit"
                                    disabled={goalForm.processing}
                                    className="inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-black text-primary-foreground shadow hover:bg-primary disabled:opacity-50"
                                >
                                    Record goal
                                </button>
                            </form>
                        </section>

                        <section className="rounded-2xl border border-border bg-card p-5 text-card-foreground shadow-sm sm:p-6">
                            <div className="flex items-center justify-between gap-3">
                                <div>
                                    <p className="text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">Timeline</p>
                                    <h3 className="text-xl font-black text-foreground">Recorded goals</h3>
                                </div>
                                <span className="rounded-full bg-muted px-3 py-1 text-sm font-bold text-muted-foreground">{events.length}</span>
                            </div>
                            <ul className="mt-4 divide-y divide-border">
                                {events.length === 0 ? (
                                    <li className="py-3 text-sm text-muted-foreground">No goals recorded yet.</li>
                                ) : events.map((event) => (
                                    <li key={event.id} className="flex items-center justify-between gap-3 py-3">
                                        <div>
                                            <p className="text-sm font-bold text-foreground">
                                                {event.scorer ?? 'Unknown'}
                                                {event.minute !== null && event.minute !== '' && (
                                                    <span className="ml-2 text-xs text-muted-foreground">{event.minute}'</span>
                                                )}
                                            </p>
                                            <p className="text-xs text-muted-foreground">
                                                {event.assist ? `Assist: ${event.assist}` : 'No assist'}
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            onClick={() => removeEvent(event)}
                                            className="rounded-lg border border-destructive px-3 py-2 text-sm font-bold text-destructive hover:text-destructive"
                                        >
                                            Remove
                                        </button>
                                    </li>
                                ))}
                            </ul>
                        </section>
                    </div>

                    <section className="rounded-2xl border border-border bg-card p-5 text-card-foreground shadow-sm sm:p-6">
                        <div>
                            <p className="text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">Finish match</p>
                            <h3 className="text-xl font-black text-foreground">Final score</h3>
                        </div>
                        <form onSubmit={submitFinal} className="mt-5 space-y-4">
                            <div className="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label className={labelClass}>Zeitlos score</label>
                                    <input
                                        type="number"
                                        min="0"
                                        inputMode="numeric"
                                        value={finalForm.data.zeitlos_score}
                                        onChange={(e) => finalForm.setData('zeitlos_score', e.target.value)}
                                        className={fieldClass}
                                    />
                                    <InputError className="mt-2" message={finalForm.errors.zeitlos_score} />
                                </div>
                                <div>
                                    <label className={labelClass}>Opponent score</label>
                                    <input
                                        type="number"
                                        min="0"
                                        inputMode="numeric"
                                        value={finalForm.data.opponent_score}
                                        onChange={(e) => finalForm.setData('opponent_score', e.target.value)}
                                        className={fieldClass}
                                    />
                                    <InputError className="mt-2" message={finalForm.errors.opponent_score} />
                                </div>
                            </div>
                            <div className="flex flex-col gap-3 sm:flex-row sm:items-center">
                                <PrimaryButton disabled={finalForm.processing}>Finalize match</PrimaryButton>
                                {match.status === 'finished' && (
                                    <span className="text-sm text-chart-2">Match is marked finished.</span>
                                )}
                            </div>
                        </form>
                    </section>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
