import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';

function CorrectionForm({ player }) {
    const { data, setData, patch, errors, processing, recentlySuccessful } = useForm({
        goals_adjustment: player.goals_adjustment ?? 0,
        assists_adjustment: player.assists_adjustment ?? 0,
    });

    const submit = (e) => {
        e.preventDefault();
        patch(route('admin.leaderboard.update', player.id), { preserveScroll: true });
    };

    return (
        <form onSubmit={submit} className="grid gap-3 sm:grid-cols-[1fr_1fr_auto] sm:items-start">
            <div>
                <label className="text-xs font-medium uppercase tracking-wide text-muted-foreground">Goals adjustment</label>
                <input
                    type="number"
                    value={data.goals_adjustment}
                    onChange={(e) => setData('goals_adjustment', e.target.value)}
                    className="mt-1 block w-full rounded-md border-border bg-input text-foreground shadow-sm focus:border-ring focus:ring-ring"
                />
                <InputError className="mt-2" message={errors.goals_adjustment} />
            </div>
            <div>
                <label className="text-xs font-medium uppercase tracking-wide text-muted-foreground">Assists adjustment</label>
                <input
                    type="number"
                    value={data.assists_adjustment}
                    onChange={(e) => setData('assists_adjustment', e.target.value)}
                    className="mt-1 block w-full rounded-md border-border bg-input text-foreground shadow-sm focus:border-ring focus:ring-ring"
                />
                <InputError className="mt-2" message={errors.assists_adjustment} />
            </div>
            <div className="flex items-center gap-3 sm:pt-6">
                <PrimaryButton disabled={processing}>Save</PrimaryButton>
                {recentlySuccessful && <span className="text-sm text-chart-2">Saved</span>}
            </div>
        </form>
    );
}

function PlayerCorrection({ player }) {
    return (
        <section className="rounded-2xl border border-border bg-card p-4 text-card-foreground shadow-sm sm:p-5">
            <div className="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h3 className="text-lg font-bold text-foreground">#{player.jersey_number ?? '-'} {player.name}</h3>
                    <p className="text-sm text-muted-foreground">{player.position} · {player.is_active ? 'Active' : 'Inactive'}</p>
                </div>
                <div className="grid grid-cols-2 gap-3 text-center sm:min-w-48">
                    <div className="rounded-md bg-muted p-3">
                        <p className="text-xs uppercase tracking-wide text-muted-foreground">Goals</p>
                        <p className="text-2xl font-bold text-foreground">{player.goals}</p>
                        <p className="text-xs text-muted-foreground">Events {player.event_goals}</p>
                    </div>
                    <div className="rounded-md bg-muted p-3">
                        <p className="text-xs uppercase tracking-wide text-muted-foreground">Assists</p>
                        <p className="text-2xl font-bold text-foreground">{player.assists}</p>
                        <p className="text-xs text-muted-foreground">Events {player.event_assists}</p>
                    </div>
                </div>
            </div>
            <CorrectionForm player={player} />
        </section>
    );
}

export default function Index({ players = [] }) {
    const [query, setQuery] = useState('');
    const [sortBy, setSortBy] = useState('goals');

    const filteredPlayers = players
        .filter((player) => `${player.name} ${player.position} ${player.jersey_number ?? ''}`.toLowerCase().includes(query.toLowerCase()))
        .sort((a, b) => {
            if (sortBy === 'name') {
                return a.name.localeCompare(b.name);
            }

            if (b[sortBy] !== a[sortBy]) {
                return b[sortBy] - a[sortBy];
            }

            return a.name.localeCompare(b.name);
        });

    return (
        <AuthenticatedLayout
            header={
                <div>
                    <p className="text-sm font-semibold uppercase tracking-[0.2em] text-muted-foreground">Admin CMS</p>
                    <h2 className="text-2xl font-bold leading-tight text-foreground">Stats corrections</h2>
                </div>
            }
        >
            <Head title="Leaderboard Corrections" />

            <div className="py-8 sm:py-10">
                <div className="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
                    <section className="rounded-3xl border border-border bg-card p-5 text-card-foreground shadow-sm sm:p-6">
                        <div className="grid gap-5 lg:grid-cols-[1fr_auto] lg:items-end">
                            <div>
                                <p className="text-xs font-bold uppercase tracking-[0.2em] text-muted-foreground">Leaderboard CMS</p>
                                <h3 className="mt-2 text-2xl font-black tracking-tight text-foreground">Fix totals without touching match events</h3>
                                <p className="mt-2 max-w-3xl text-sm leading-6 text-muted-foreground">
                                    Match scoring creates event totals. Adjustments are for corrections, historical goals, or missed assists. Public leaderboard totals update immediately after saving.
                                </p>
                            </div>
                            <div className="grid grid-cols-2 gap-3 text-center sm:min-w-64">
                                <div className="rounded-2xl bg-muted p-4">
                                    <p className="text-xs font-bold uppercase tracking-wide text-muted-foreground">Players</p>
                                    <p className="mt-1 text-3xl font-black text-foreground">{players.length}</p>
                                </div>
                                <div className="rounded-2xl bg-muted p-4">
                                    <p className="text-xs font-bold uppercase tracking-wide text-muted-foreground">Showing</p>
                                    <p className="mt-1 text-3xl font-black text-foreground">{filteredPlayers.length}</p>
                                </div>
                            </div>
                        </div>
                    </section>

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
                                {[
                                    ['goals', 'Goals'],
                                    ['assists', 'Assists'],
                                    ['name', 'Name'],
                                ].map(([value, label]) => (
                                    <button
                                        key={value}
                                        type="button"
                                        onClick={() => setSortBy(value)}
                                        className={`min-h-11 rounded-xl border px-4 py-2 text-sm font-bold ${sortBy === value ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-background text-foreground'}`}
                                    >
                                        {label}
                                    </button>
                                ))}
                            </div>
                        </div>
                    </section>

                    {filteredPlayers.length === 0 ? (
                        <div className="rounded-2xl border border-border bg-card p-6 text-sm text-muted-foreground shadow-sm">No players found.</div>
                    ) : filteredPlayers.map((player) => (
                        <PlayerCorrection key={player.id} player={player} />
                    ))}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
