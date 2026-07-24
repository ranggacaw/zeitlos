import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import MatchForm from './Partials/MatchForm';

export default function Create() {
    const { data, setData, post, errors } = useForm({
        opponent: '',
        match_date: '',
        match_time: '',
        venue: '',
        maps_url: '',
        ticket_price: '',
        dress_code: '',
        facilities: '',
        notes: '',
        payment_label: '',
        payment_amount: '',
        payment_due_at: '',
        payment_instructions: '',
        whatsapp_announcement: '',
        status: 'scheduled',
        zeitlos_score: '',
        opponent_score: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('admin.matches.store'));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-foreground">
                    Add Match
                </h2>
            }
        >
            <Head title="Add Match" />

            <div className="py-12">
                <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                    <MatchForm
                        data={data}
                        setData={setData}
                        errors={errors}
                        submit={submit}
                        submitLabel="Create match"
                    />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
