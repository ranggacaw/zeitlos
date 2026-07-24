import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Dashboard({ playerCount = 0, matchCount = 0 }) {
    const cards = [
        { label: 'Players', count: playerCount, href: route('admin.players.index') },
        { label: 'Matches', count: matchCount, href: route('admin.matches.index') },
    ];

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Team Admin
                </h2>
            }
        >
            <Head title="Team Admin" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="grid gap-6 sm:grid-cols-2">
                        {cards.map((card) => (
                            <Link
                                key={card.label}
                                href={card.href}
                                className="block rounded-lg border border-gray-200 bg-white p-6 shadow transition hover:border-indigo-400 hover:shadow-md"
                            >
                                <p className="text-sm font-medium uppercase tracking-wide text-gray-500">
                                    {card.label}
                                </p>
                                <p className="mt-2 text-4xl font-bold text-gray-900">
                                    {card.count}
                                </p>
                                <p className="mt-3 text-sm font-medium text-indigo-600">
                                    Manage {card.label.toLowerCase()} &rarr;
                                </p>
                            </Link>
                        ))}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
