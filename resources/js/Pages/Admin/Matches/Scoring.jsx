import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, Link, useForm } from '@inertiajs/react';

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

    const playerLabel = (player) => `#${player.jersey_number ?? '-'} ${player.name}${player.position ? ` (${player.position})` : ''}`;

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Scoring &mdash; Zeitlos vs {match.opponent}
                </h2>
            }
        >
            <Head title="Match Scoring" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                    <div className="grid gap-6 lg:grid-cols-2">
                        <section className="bg-white p-6 shadow sm:rounded-lg">
                            <h3 className="text-lg font-medium text-gray-900">Record goal</h3>
                            <form onSubmit={submitGoal} className="mt-4 space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Scorer</label>
                                    <select
                                        value={goalForm.data.scorer_id}
                                        onChange={(e) => goalForm.setData('scorer_id', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                                    <label className="block text-sm font-medium text-gray-700">Assist (optional)</label>
                                    <select
                                        value={goalForm.data.assist_player_id}
                                        onChange={(e) => goalForm.setData('assist_player_id', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
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
                                    <label className="block text-sm font-medium text-gray-700">Minute (optional)</label>
                                    <input
                                        type="number"
                                        min="0"
                                        value={goalForm.data.minute}
                                        onChange={(e) => goalForm.setData('minute', e.target.value)}
                                        placeholder="e.g. 23"
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                    <InputError className="mt-2" message={goalForm.errors.minute} />
                                </div>

                                <PrimaryButton disabled={goalForm.processing}>Record goal</PrimaryButton>
                            </form>
                        </section>

                        <section className="bg-white p-6 shadow sm:rounded-lg">
                            <h3 className="text-lg font-medium text-gray-900">Recorded goals</h3>
                            <ul className="mt-4 divide-y divide-gray-100">
                                {events.length === 0 ? (
                                    <li className="py-3 text-sm text-gray-500">No goals recorded yet.</li>
                                ) : events.map((event) => (
                                    <li key={event.id} className="flex items-center justify-between py-3">
                                        <div>
                                            <p className="text-sm font-medium text-gray-900">
                                                {event.scorer ?? 'Unknown'}
                                                {event.minute !== null && event.minute !== '' && (
                                                    <span className="ml-2 text-xs text-gray-500">{event.minute}'</span>
                                                )}
                                            </p>
                                            <p className="text-xs text-gray-500">
                                                {event.assist ? `Assist: ${event.assist}` : 'No assist'}
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            onClick={() => removeEvent(event)}
                                            className="text-sm text-red-600 hover:text-red-900"
                                        >
                                            Remove
                                        </button>
                                    </li>
                                ))}
                            </ul>
                        </section>
                    </div>

                    <section className="bg-white p-6 shadow sm:rounded-lg">
                        <h3 className="text-lg font-medium text-gray-900">Final score</h3>
                        <form onSubmit={submitFinal} className="mt-4 space-y-4">
                            <div className="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Zeitlos score</label>
                                    <input
                                        type="number"
                                        min="0"
                                        value={finalForm.data.zeitlos_score}
                                        onChange={(e) => finalForm.setData('zeitlos_score', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                    <InputError className="mt-2" message={finalForm.errors.zeitlos_score} />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Opponent score</label>
                                    <input
                                        type="number"
                                        min="0"
                                        value={finalForm.data.opponent_score}
                                        onChange={(e) => finalForm.setData('opponent_score', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                    <InputError className="mt-2" message={finalForm.errors.opponent_score} />
                                </div>
                            </div>
                            <div className="flex items-center gap-4">
                                <PrimaryButton disabled={finalForm.processing}>Finalize match</PrimaryButton>
                                {match.status === 'finished' && (
                                    <span className="text-sm text-emerald-600">Match is marked finished.</span>
                                )}
                            </div>
                        </form>
                    </section>

                    <div>
                        <Link href={route('admin.matches.index')} className="text-sm text-gray-600 underline hover:text-gray-900">
                            &larr; Back to matches
                        </Link>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
