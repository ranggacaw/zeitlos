import PublicJerseySection from '@/Components/PublicJerseySection';
import PublicRoster from '@/Components/PublicRoster';
import PublicLayout from '@/Layouts/PublicLayout';
import { Head } from '@inertiajs/react';

export default function Roster({ players = [] }) {
    return (
        <PublicLayout>
            <Head title="Roster" />
            <div className="space-y-5">
                <PublicJerseySection />
                <PublicRoster players={players} eyebrow="Active squad" />
            </div>
        </PublicLayout>
    );
}
