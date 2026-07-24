import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import { Link } from '@inertiajs/react';

const fields = [
    { name: 'opponent', label: 'Opponent', type: 'text', span: true },
    { name: 'match_date', label: 'Match date', type: 'date' },
    { name: 'match_time', label: 'Match time', type: 'time' },
    { name: 'venue', label: 'Venue', type: 'text', span: true },
    { name: 'maps_url', label: 'Maps URL', type: 'url', span: true },
    { name: 'ticket_price', label: 'Ticket price', type: 'number' },
    { name: 'dress_code', label: 'Dress code', type: 'text' },
    { name: 'payment_label', label: 'Payment label', type: 'text', span: true },
    { name: 'payment_amount', label: 'Payment amount', type: 'number' },
    { name: 'payment_due_at', label: 'Payment due at', type: 'date' },
    { name: 'zeitlos_score', label: 'Zeitlos score', type: 'number' },
    { name: 'opponent_score', label: 'Opponent score', type: 'number' },
    { name: 'facilities', label: 'Facilities', type: 'textarea', span: true },
    { name: 'notes', label: 'Notes', type: 'textarea', span: true },
    { name: 'payment_instructions', label: 'Payment instructions', type: 'textarea', span: true },
    { name: 'whatsapp_announcement', label: 'WhatsApp announcement', type: 'textarea', span: true },
];

export default function MatchForm({ data, setData, errors, submit, submitLabel }) {
    return (
        <form onSubmit={submit} className="space-y-6 bg-white p-6 shadow sm:rounded-lg">
            <div className="grid gap-6 sm:grid-cols-2">
                {fields.map((field) => (
                    <div key={field.name} className={field.span ? 'sm:col-span-2' : ''}>
                        <label className="block text-sm font-medium text-gray-700">{field.label}</label>
                        {field.type === 'textarea' ? (
                            <textarea
                                value={data[field.name] ?? ''}
                                onChange={(e) => setData(field.name, e.target.value)}
                                rows={3}
                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        ) : (
                            <input
                                type={field.type}
                                step={field.type === 'number' ? '0.01' : undefined}
                                value={data[field.name] ?? ''}
                                onChange={(e) => setData(field.name, e.target.value)}
                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        )}
                        <InputError className="mt-2" message={errors[field.name]} />
                    </div>
                ))}

                <div className="sm:col-span-2">
                    <label className="block text-sm font-medium text-gray-700">Status</label>
                    <select
                        value={data.status}
                        onChange={(e) => setData('status', e.target.value)}
                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="scheduled">Scheduled</option>
                        <option value="finished">Finished</option>
                    </select>
                    <InputError className="mt-2" message={errors.status} />
                </div>
            </div>

            <div className="flex items-center gap-4">
                <PrimaryButton disabled={false}>{submitLabel}</PrimaryButton>
                <Link href={route('admin.matches.index')} className="text-sm text-gray-600 underline hover:text-gray-900">
                    Cancel
                </Link>
            </div>
        </form>
    );
}
