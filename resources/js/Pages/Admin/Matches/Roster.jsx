import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Roster({ match, rosterEntries = [], availablePlayers = [], whatsappText = '' }) {
    const { data, setData, post, delete: destroy, errors } = useForm({
        player_id: '',
        guest_name: '',
        role: 'player',
    });

    const [copied, setCopied] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        post(route('admin.matches.roster.store', match.id), {
            preserveScroll: true,
            onSuccess: () => {
                setData({ player_id: '', guest_name: '', role: 'player' });
            },
        });
    };

    const remove = (entry) => {
        if (confirm(`Remove "${entry.name}" from the roster?`)) {
            destroy(route('admin.matches.roster.destroy', [match.id, entry.id]), { preserveScroll: true });
        }
    };

    const copyText = async () => {
        try {
            await navigator.clipboard.writeText(whatsappText);
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        } catch {
            setCopied(false);
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Roster &mdash; Zeitlos vs {match.opponent}
                </h2>
            }
        >
            <Head title="Match Roster" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                    <div className="grid gap-6 lg:grid-cols-2">
                        <section className="bg-white p-6 shadow sm:rounded-lg">
                            <h3 className="text-lg font-medium text-gray-900">Add roster entry</h3>
                            <form onSubmit={submit} className="mt-4 space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Existing player</label>
                                    <select
                                        value={data.player_id}
                                        onChange={(e) => setData('player_id', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="">Guest (enter name below)</option>
                                        {availablePlayers.map((player) => (
                                            <option key={player.id} value={player.id}>
                                                #{player.jersey_number} {player.name} ({player.position})
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Guest name</label>
                                    <input
                                        value={data.guest_name}
                                        onChange={(e) => setData('guest_name', e.target.value)}
                                        placeholder="Leave blank if an existing player is selected"
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                    <InputError className="mt-2" message={errors.player_id || errors.guest_name} />
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Role</label>
                                    <select
                                        value={data.role}
                                        onChange={(e) => setData('role', e.target.value)}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="player">Player</option>
                                        <option value="goalkeeper">Goalkeeper</option>
                                    </select>
                                    <InputError className="mt-2" message={errors.role} />
                                </div>

                                <PrimaryButton>Add entry</PrimaryButton>
                            </form>
                        </section>

                        <section className="bg-white p-6 shadow sm:rounded-lg">
                            <h3 className="text-lg font-medium text-gray-900">Current roster</h3>
                            <ul className="mt-4 divide-y divide-gray-100">
                                {rosterEntries.length === 0 ? (
                                    <li className="py-3 text-sm text-gray-500">No roster entries yet.</li>
                                ) : rosterEntries.map((entry) => (
                                    <li key={entry.id} className="flex items-center justify-between py-3">
                                        <div>
                                            <p className="text-sm font-medium text-gray-900">{entry.name}</p>
                                            <p className="text-xs uppercase tracking-wide text-gray-500">
                                                {entry.role}{entry.is_guest ? ' · guest' : ''}
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            onClick={() => remove(entry)}
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
                        <div className="flex items-center justify-between">
                            <h3 className="text-lg font-medium text-gray-900">WhatsApp roster text</h3>
                            <button
                                type="button"
                                onClick={copyText}
                                className="inline-flex items-center rounded-md border border-transparent bg-emerald-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-emerald-700"
                            >
                                {copied ? 'Copied!' : 'Copy'}
                            </button>
                        </div>
                        <pre className="mt-4 whitespace-pre-wrap rounded-md bg-gray-50 p-4 text-sm text-gray-700">{whatsappText}</pre>
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
