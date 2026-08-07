import ThemeToggle from '@/Components/ThemeToggle';
import { Link } from '@inertiajs/react';
import zeitlosLogo from '../../assets/zeitlos_logo.png';

export default function GuestLayout({ children }) {
    return (
        <div
            className="flex min-h-screen flex-col items-center bg-background pt-6 text-foreground sm:justify-center sm:pt-0"
            style={{
                '--background': 'oklch(1 0 0)',
                '--foreground': 'oklch(0.1884 0.0128 248.5103)',
                '--card': 'oklch(1 0 0)',
                '--card-foreground': 'oklch(0.1884 0.0128 248.5103)',
                '--muted-foreground': 'oklch(0.1884 0.0128 248.5103)',
                '--border': 'oklch(0.9317 0.0118 231.6594)',
                '--input': 'oklch(0.9809 0.0025 228.7836)',
            }}
        >
            <div>
                <Link href="/">
                    <img
                        src={zeitlosLogo}
                        alt="FC Zeitlos logo"
                        className="h-24 w-24 object-contain"
                    />
                </Link>
            </div>

            <ThemeToggle className="mt-4" />

            <div className="mt-6 w-full overflow-hidden bg-card px-6 py-4 text-card-foreground shadow-md sm:max-w-md sm:rounded-lg">
                {children}
            </div>
        </div>
    );
}
