import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

export default function Index({ matches = [] }) {
    const { delete: destroy, processing } = useForm();
    const [query, setQuery] = useState('');
    const [status, setStatus] = useState('all');

    const filteredMatches = matches.filter((match) => {
        const haystack = `${match.opponent} ${match.venue} ${match.match_date} ${match.status}`.toLowerCase();
        const matchesQuery = haystack.includes(query.toLowerCase());
        const matchesStatus = status === 'all' || match.status === status;

        return matchesQuery && matchesStatus;
    });

    const remove = (match) => {
        if (confirm(`Delete match vs "${match.opponent}"?`)) {
            destroy(route('admin.matches.destroy', match.id));
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p className="text-sm font-semibold uppercase tracking-[0.2em] text-muted-foreground">Admin CMS</p>
                        <h2 className="text-2xl font-bold leading-tight text-foreground">Matches</h2>
                    </div>
                    <Link href={route('admin.matches.create')} className="inline-flex min-h-11 items-center justify-center rounded-xl bg-primary px-4 py-2 text-sm font-bold text-primary-foreground shadow hover:bg-primary">
                        Add match
                    </Link>
                </div>
            }
        >
            <Head title="Matches" />

            <div className="py-8 sm:py-10">
                <div className="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
                    <section className="rounded-2xl border border-border bg-card p-4 text-card-foreground shadow-sm sm:p-5">
                        <div className="grid gap-3 md:grid-cols-[1fr_auto] md:items-end">
                            <div>
                                <label className="text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">Search matches</label>
                                <input
                                    value={query}
                                    onChange={(event) => setQuery(event.target.value)}
                                    placeholder="Opponent, venue, date..."
                                    className="mt-2 block min-h-11 w-full rounded-xl border-border bg-input text-foreground shadow-sm focus:border-ring focus:ring-ring"
                                />
                            </div>
                            <div className="flex gap-2 overflow-x-auto pb-1 md:pb-0">
                                {['all', 'scheduled', 'live', 'finished'].map((item) => (
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
                        <p className="mt-3 text-sm text-muted-foreground">Showing {filteredMatches.length} of {matches.length} matches.</p>
                    </section>

                    <div className="space-y-4 sm:hidden">
                        {filteredMatches.length === 0 ? (
                            <div className="rounded-2xl bg-card p-5 text-sm text-muted-foreground shadow-sm">No matches found.</div>
                        ) : filteredMatches.map((match) => (
                            <section key={match.id} className="rounded-2xl border border-border bg-card p-4 text-card-foreground shadow-sm">
                                <div className="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 className="font-bold text-foreground">Zeitlos vs {match.opponent}</h3>
                                        <p className="mt-1 text-sm text-muted-foreground">{match.match_date} · {match.venue}</p>
                                    </div>
                                    <span className="rounded-full border border-border bg-muted px-2 py-1 text-xs font-semibold capitalize text-muted-foreground">{match.status}</span>
                                </div>
                                <div className="mt-4 grid grid-cols-2 gap-3 text-sm font-bold">
                                    <Link href={route('admin.matches.roster.index', match.id)} className="rounded-xl border border-border bg-background px-3 py-3 text-center text-foreground">Roster</Link>
                                    <Link href={route('admin.matches.scoring.index', match.id)} className="rounded-xl bg-secondary px-3 py-3 text-center text-secondary-foreground">Scoring</Link>
                                    <Link href={route('admin.matches.edit', match.id)} className="rounded-xl bg-primary px-3 py-3 text-center text-primary-foreground">Edit details</Link>
                                    <button
                                        type="button"
                                        disabled={processing}
                                        onClick={() => remove(match)}
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
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">Opponent</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">Date</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">Venue</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-muted-foreground">Status</th>
                                    <th className="px-6 py-3 text-right text-xs font-medium uppercase tracking-wide text-muted-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-border">
                                {filteredMatches.length === 0 ? (
                                    <tr>
                                        <td colSpan={5} className="px-6 py-4 text-sm text-muted-foreground">No matches found.</td>
                                    </tr>
                                ) : filteredMatches.map((match) => (
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
