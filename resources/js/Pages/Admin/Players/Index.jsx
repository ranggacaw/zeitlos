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
                <h2 className="text-xl font-semibold leading-tight text-foreground">
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
                            className="inline-flex items-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:bg-primary"
                        >
                            Add player
                        </Link>
                    </div>

                    <div className="overflow-hidden bg-card shadow sm:rounded-lg">
                        <table className="min-w-full divide-y divide-border">
                            <thead className="bg-muted">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">Jersey</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">Name</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">Position</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">Status</th>
                                    <th className="px-6 py-3 text-right text-xs font-medium uppercase tracking-wide text-muted-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-border">
                                {players.length === 0 ? (
                                    <tr>
                                        <td colSpan={4} className="px-6 py-4 text-sm text-muted-foreground">No players yet.</td>
                                    </tr>
                                ) : players.map((player) => (
                                    <tr key={player.id}>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm text-foreground">{player.jersey_number}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm font-medium text-foreground">{player.name}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm text-muted-foreground">{player.position}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm text-muted-foreground">{player.is_active ? 'Active' : 'Inactive'}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                            <Link href={route('admin.players.edit', player.id)} className="text-primary hover:text-primary">Edit</Link>
                                            <button
                                                type="button"
                                                disabled={processing}
                                                onClick={() => remove(player)}
                                                className="ml-4 text-destructive hover:text-destructive"
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
