import ApplicationLogo from '@/Components/ApplicationLogo';
import ThemeToggle from '@/Components/ThemeToggle';
import { Link } from '@inertiajs/react';

export default function GuestLayout({ children }) {
    return (
        <div className="flex min-h-screen flex-col items-center bg-background pt-6 text-foreground sm:justify-center sm:pt-0">
            <div>
                <Link href="/">
                    <ApplicationLogo className="h-20 w-20 fill-current text-primary" />
                </Link>
            </div>

            <ThemeToggle className="mt-4" />

            <div className="mt-6 w-full overflow-hidden bg-card px-6 py-4 text-card-foreground shadow-md sm:max-w-md sm:rounded-lg">
                {children}
            </div>
        </div>
    );
}
