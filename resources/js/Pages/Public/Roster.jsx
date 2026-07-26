import PublicRoster from '@/Components/PublicRoster';
import PublicLayout from '@/Layouts/PublicLayout';
import { Head } from '@inertiajs/react';

export default function Roster({ players = [] }) {
    return (
        <PublicLayout>
            <Head title="Roster" />
            <PublicRoster players={players} eyebrow="Active squad" />
        </PublicLayout>
    );
}
