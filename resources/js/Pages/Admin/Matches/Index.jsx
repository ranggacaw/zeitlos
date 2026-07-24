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
                <h2 className="text-xl font-semibold leading-tight text-foreground">
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
                            className="inline-flex items-center rounded-md border border-transparent bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:bg-primary"
                        >
                            Add match
                        </Link>
                    </div>

                    <div className="overflow-hidden bg-card shadow sm:rounded-lg">
                        <table className="min-w-full divide-y divide-border">
                            <thead className="bg-muted">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">Opponent</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">Date</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">Venue</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">Status</th>
                                    <th className="px-6 py-3 text-right text-xs font-medium uppercase tracking-wide text-muted-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-border">
                                {matches.length === 0 ? (
                                    <tr>
                                        <td colSpan={5} className="px-6 py-4 text-sm text-muted-foreground">No matches yet.</td>
                                    </tr>
                                ) : matches.map((match) => (
                                    <tr key={match.id}>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm font-medium text-foreground">{match.opponent}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm text-muted-foreground">{match.match_date}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm text-muted-foreground">{match.venue}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm capitalize text-muted-foreground">{match.status}</td>
                                        <td className="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                            <Link href={route('admin.matches.roster.index', match.id)} className="text-chart-2 hover:text-chart-2">Roster</Link>
                                            <Link href={route('admin.matches.scoring.index', match.id)} className="ml-4 text-secondary hover:text-secondary">Scoring</Link>
                                            <Link href={route('admin.matches.edit', match.id)} className="ml-4 text-primary hover:text-primary">Edit</Link>
                                            <button
                                                type="button"
                                                disabled={processing}
                                                onClick={() => remove(match)}
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
