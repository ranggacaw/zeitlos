import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Edit({ player }) {
    const { data, setData, patch, errors, processing } = useForm({
        name: player.name ?? '',
        jersey_number: player.jersey_number ?? '',
        position: player.position ?? '',
        is_active: Boolean(player.is_active),
        photo_path: player.photo_path ?? '',
        joined_at: player.joined_at ?? '',
        goals_adjustment: player.goals_adjustment ?? 0,
        assists_adjustment: player.assists_adjustment ?? 0,
    });

    const submit = (e) => {
        e.preventDefault();
        patch(route('admin.players.update', player.id));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Edit Player
                </h2>
            }
        >
            <Head title="Edit Player" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                    <form onSubmit={submit} className="space-y-6 bg-white p-6 shadow sm:rounded-lg">
                        <div className="grid gap-6 sm:grid-cols-2">
                            <div className="sm:col-span-2">
                                <label className="block text-sm font-medium text-gray-700">Name</label>
                                <input
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <InputError className="mt-2" message={errors.name} />
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700">Jersey number</label>
                                <input
                                    type="number"
                                    min="1"
                                    value={data.jersey_number}
                                    onChange={(e) => setData('jersey_number', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <InputError className="mt-2" message={errors.jersey_number} />
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700">Position</label>
                                <input
                                    value={data.position}
                                    onChange={(e) => setData('position', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <InputError className="mt-2" message={errors.position} />
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700">Joined at</label>
                                <input
                                    type="date"
                                    value={data.joined_at ?? ''}
                                    onChange={(e) => setData('joined_at', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <InputError className="mt-2" message={errors.joined_at} />
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700">Photo path</label>
                                <input
                                    value={data.photo_path ?? ''}
                                    onChange={(e) => setData('photo_path', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <InputError className="mt-2" message={errors.photo_path} />
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700">Goals adjustment</label>
                                <input
                                    type="number"
                                    value={data.goals_adjustment}
                                    onChange={(e) => setData('goals_adjustment', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <InputError className="mt-2" message={errors.goals_adjustment} />
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700">Assists adjustment</label>
                                <input
                                    type="number"
                                    value={data.assists_adjustment}
                                    onChange={(e) => setData('assists_adjustment', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                                <InputError className="mt-2" message={errors.assists_adjustment} />
                            </div>

                            <div className="sm:col-span-2">
                                <label className="inline-flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        checked={Boolean(data.is_active)}
                                        onChange={(e) => setData('is_active', e.target.checked)}
                                        className="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    />
                                    <span className="text-sm font-medium text-gray-700">Active</span>
                                </label>
                                <InputError className="mt-2" message={errors.is_active} />
                            </div>
                        </div>

                        <div className="flex items-center gap-4">
                            <PrimaryButton disabled={processing}>Save player</PrimaryButton>
                            <Link href={route('admin.players.index')} className="text-sm text-gray-600 underline hover:text-gray-900">
                                Cancel
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
