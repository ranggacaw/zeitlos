import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import MatchForm from './Partials/MatchForm';

export default function Edit({ match }) {
    const { data, setData, patch, errors } = useForm({
        opponent: match.opponent ?? '',
        match_date: match.match_date ?? '',
        match_time: match.match_time ?? '',
        venue: match.venue ?? '',
        maps_url: match.maps_url ?? '',
        ticket_price: match.ticket_price ?? '',
        dress_code: match.dress_code ?? '',
        facilities: match.facilities ?? '',
        notes: match.notes ?? '',
        payment_label: match.payment_label ?? '',
        payment_amount: match.payment_amount ?? '',
        payment_due_at: match.payment_due_at ?? '',
        payment_instructions: match.payment_instructions ?? '',
        whatsapp_announcement: match.whatsapp_announcement ?? '',
        status: match.status ?? 'scheduled',
        zeitlos_score: match.zeitlos_score ?? '',
        opponent_score: match.opponent_score ?? '',
    });

    const submit = (e) => {
        e.preventDefault();
        patch(route('admin.matches.update', match.id));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Edit Match
                </h2>
            }
        >
            <Head title="Edit Match" />

            <div className="py-12">
                <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                    <MatchForm
                        data={data}
                        setData={setData}
                        errors={errors}
                        submit={submit}
                        submitLabel="Save match"
                    />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
