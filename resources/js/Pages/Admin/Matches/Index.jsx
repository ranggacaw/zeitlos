import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Index({ matches = [] }) {
    const { delete: destroy, processing } = useForm();

    const remove = (match) => {
        if (confirm(`Delete match vs "${match.opponent}"?`)) {
            destroy(route('admin.matches.destroy', match.id));
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Matches
                </h2>
            }
        >
            <Head title="Matches" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="mb-6 flex justify-end">
                        <Link
                            href={route('admin.matches.create')}
                            className="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-700"
                        >
                            Add match
                        </Link>
                    </div>

                    <div className="overflow-hidden bg-white shadow sm:rounded-lg">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Opponent</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Date</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Venue</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Status</th>
                                    <th className="px-6 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-200">
                                {matches.length === 0 ? (
                                    <tr>
                                        <td colSpan={5} className="px-6 py-4 text-sm text-gray-500">No matches yet.</td>
                                    </tr>
                                ) : matches.map((match) => (
                                    <tr key={match.id}>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{match.opponent}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{match.match_date}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{match.venue}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm capitalize text-gray-500">{match.status}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                            <Link href={route('admin.matches.roster.index', match.id)} className="text-emerald-600 hover:text-emerald-900">Roster</Link>
                                            <Link href={route('admin.matches.scoring.index', match.id)} className="ml-4 text-amber-600 hover:text-amber-900">Scoring</Link>
                                            <Link href={route('admin.matches.edit', match.id)} className="ml-4 text-indigo-600 hover:text-indigo-900">Edit</Link>
                                            <button
                                                type="button"
                                                disabled={processing}
                                                onClick={() => remove(match)}
                                                className="ml-4 text-red-600 hover:text-red-900"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
