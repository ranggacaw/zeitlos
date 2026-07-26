import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Index({ players = [] }) {
    const { delete: destroy, processing } = useForm();
    const [query, setQuery] = useState('');
    const [status, setStatus] = useState('all');

    const filteredPlayers = players.filter((player) => {
        const haystack = `${player.name} ${player.position} ${player.jersey_number ?? ''}`.toLowerCase();
        const matchesQuery = haystack.includes(query.toLowerCase());
        const matchesStatus = status === 'all' || (status === 'active' ? player.is_active : !player.is_active);

        return matchesQuery && matchesStatus;
    });

    const remove = (player) => {
        if (confirm(`Delete player "${player.name}"?`)) {
            destroy(route('admin.players.destroy', player.id));
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p className="text-sm font-semibold uppercase tracking-[0.2em] text-muted-foreground">Admin CMS</p>
                        <h2 className="text-2xl font-bold leading-tight text-foreground">Players</h2>
                    </div>
                    <Link href={route('admin.players.create')} className="inline-flex min-h-11 items-center justify-center rounded-xl bg-primary px-4 py-2 text-sm font-bold text-primary-foreground shadow hover:bg-primary">
                        Add player
                    </Link>
                </div>
            }
        >
            <Head title="Players" />

            <div className="py-8 sm:py-10">
                <div className="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
                    <section className="rounded-2xl border border-border bg-card p-4 text-card-foreground shadow-sm sm:p-5">
                        <div className="grid gap-3 md:grid-cols-[1fr_auto] md:items-end">
                            <div>
                                <label className="text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">Search players</label>
                                <input
                                    value={query}
                                    onChange={(event) => setQuery(event.target.value)}
                                    placeholder="Name, position, jersey..."
                                    className="mt-2 block min-h-11 w-full rounded-xl border-border bg-input text-foreground shadow-sm focus:border-ring focus:ring-ring"
                                />
                            </div>
                            <div className="flex gap-2">
                                {['all', 'active', 'inactive'].map((item) => (
                                    <button
                                        key={item}
                                        type="button"
                                        onClick={() => setStatus(item)}
                                        className={`min-h-11 rounded-xl border px-4 py-2 text-sm font-bold capitalize ${status === item ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-background text-foreground'}`}
                                    >
                                        {item}
                                    </button>
                                ))}
                            </div>
                        </div>
                        <p className="mt-3 text-sm text-muted-foreground">Showing {filteredPlayers.length} of {players.length} players.</p>
                    </section>

                    <div className="space-y-4 sm:hidden">
                        {filteredPlayers.length === 0 ? (
                            <div className="rounded-2xl bg-card p-5 text-sm text-muted-foreground shadow-sm">No players found.</div>
                        ) : filteredPlayers.map((player) => (
                            <section key={player.id} className="rounded-2xl border border-border bg-card p-4 text-card-foreground shadow-sm">
                                <div className="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 className="font-bold text-foreground">#{player.jersey_number ?? '-'} {player.name}</h3>
                                        <p className="mt-1 text-sm text-muted-foreground">{player.position}</p>
                                    </div>
                                    <span className="rounded-full border border-border bg-muted px-2 py-1 text-xs font-semibold text-muted-foreground">{player.is_active ? 'Active' : 'Inactive'}</span>
                                </div>
                                <div className="mt-4 grid grid-cols-2 gap-3 text-sm font-bold">
                                    <Link href={route('admin.players.edit', player.id)} className="rounded-xl bg-primary px-3 py-3 text-center text-primary-foreground">Edit profile</Link>
                                    <button
                                        type="button"
                                        disabled={processing}
                                        onClick={() => remove(player)}
                                        className="rounded-xl border border-destructive px-3 py-3 text-destructive"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </section>
                        ))}
                    </div>

                    <div className="hidden overflow-hidden rounded-2xl border border-border bg-card shadow-sm sm:block">
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
                                {filteredPlayers.length === 0 ? (
                                    <tr>
                                        <td colSpan={5} className="px-6 py-4 text-sm text-muted-foreground">No players found.</td>
                                    </tr>
                                ) : filteredPlayers.map((player) => (
                                    <tr key={player.id}>
                                        <td className="whitespace-nowrap px-6 py-4 text-sm text-foreground">{player.jersey_number ?? '-'}</td>
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
