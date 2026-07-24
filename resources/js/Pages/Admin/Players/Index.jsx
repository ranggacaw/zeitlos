import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Index({ players = [] }) {
    const { delete: destroy, processing } = useForm();

    const remove = (player) => {
        if (confirm(`Delete player "${player.name}"?`)) {
            destroy(route('admin.players.destroy', player.id));
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Players
                </h2>
            }
        >
            <Head title="Players" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="mb-6 flex justify-end">
                        <Link
                            href={route('admin.players.create')}
                            className="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-700"
                        >
                            Add player
                        </Link>
                    </div>

                    <div className="overflow-hidden bg-white shadow sm:rounded-lg">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Jersey</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Name</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Position</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Status</th>
                                    <th className="px-6 py-3 text-right text-xs font-medium uppercase tracking-wide text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-200">
                                {players.length === 0 ? (
                                    <tr>
                                        <td colSpan={4} className="px-6 py-4 text-sm text-gray-500">No players yet.</td>
                                    </tr>
                                ) : players.map((player) => (
                                    <tr key={player.id}>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{player.jersey_number}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{player.name}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{player.position}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{player.is_active ? 'Active' : 'Inactive'}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                            <Link href={route('admin.players.edit', player.id)} className="text-indigo-600 hover:text-indigo-900">Edit</Link>
                                            <button
                                                type="button"
                                                disabled={processing}
                                                onClick={() => remove(player)}
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
