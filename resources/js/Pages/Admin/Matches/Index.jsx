import AdminCard from '@/Components/AdminCard';
import AdminPageHeader from '@/Components/AdminPageHeader';
import AdminTable, { EmptyTableRow } from '@/Components/AdminTable';
import { AdminFilterPanel, AdminSearchInput, SegmentedButtons } from '@/Components/AdminFilters';
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
                <AdminPageHeader title="Matches">
                    <Link href={route('admin.matches.create')} className="inline-flex min-h-11 items-center justify-center rounded-xl bg-primary px-4 py-2 text-sm font-bold text-primary-foreground shadow hover:bg-primary">
                        Add match
                    </Link>
                </AdminPageHeader>
            }
        >
            <Head title="Matches" />

            <div className="py-8 sm:py-10">
                <div className="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
                    <AdminFilterPanel summary={`Showing ${filteredMatches.length} of ${matches.length} matches.`}>
                        <AdminSearchInput
                            label="Search matches"
                            value={query}
                            onChange={(event) => setQuery(event.target.value)}
                            placeholder="Opponent, venue, date..."
                        />
                        <SegmentedButtons
                            options={['all', 'scheduled', 'live', 'finished']}
                            value={status}
                            onChange={setStatus}
                            className="overflow-x-auto pb-1 md:pb-0"
                            capitalize
                        />
                    </AdminFilterPanel>

                    <div className="space-y-4 sm:hidden">
                        {filteredMatches.length === 0 ? (
                            <div className="rounded-2xl bg-card p-5 text-sm text-muted-foreground shadow-sm">No matches found.</div>
                        ) : filteredMatches.map((match) => (
                            <AdminCard key={match.id} className="p-4">
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
                            </AdminCard>
                        ))}
                    </div>

                    <AdminTable>
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
                                <EmptyTableRow colSpan={5}>No matches found.</EmptyTableRow>
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
                    </AdminTable>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
