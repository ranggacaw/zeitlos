import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, Link, useForm } from '@inertiajs/react';
import PlayerFormFields from './Partials/PlayerFormFields';

export default function Create() {
    const { data, setData, post, errors, processing } = useForm({
        name: '',
        jersey_number: '',
        position: '',
        is_active: true,
        photo_path: '',
        joined_at: '',
        goals_adjustment: 0,
        assists_adjustment: 0,
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('admin.players.store'));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-foreground">
                    Add Player
                </h2>
            }
        >
            <Head title="Add Player" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                    <form onSubmit={submit} className="space-y-6 bg-card p-6 text-card-foreground shadow sm:rounded-lg">
                        <PlayerFormFields data={data} setData={setData} errors={errors} />
                        <div className="flex items-center gap-4">
                            <PrimaryButton disabled={processing}>Create player</PrimaryButton>
                            <Link href={route('admin.players.index')} className="text-sm text-muted-foreground underline hover:text-foreground">
                                Cancel
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
