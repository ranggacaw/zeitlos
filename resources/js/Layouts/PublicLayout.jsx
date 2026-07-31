import { Link, usePage } from '@inertiajs/react';
import zeitlosLogo from '../../assets/zeitlos_logo.png';
import InstallPrompt from '../Components/InstallPrompt';
import ThemeToggle from '../Components/ThemeToggle';

const navItems = [
    ['public.home', 'Dashboard', 'dashboard'],
    ['public.schedule', 'Schedule', 'schedule'],
    ['public.roster', 'Roster', 'roster'],
    ['public.leaderboard', 'Leaderboard', 'leaderboard'],
];

function TabIcon({ name, className }) {
    const paths = {
        dashboard: 'M4 5h6v6H4zM14 5h6v6h-6zM4 15h6v6H4zM14 15h6v6h-6z',
        schedule: 'M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2zM3 10h18M8 2v4M16 2v4',
        roster: 'M12 13a4.5 4.5 0 100-9 4.5 4.5 0 000 9zM4 20.5a8 8 0 0116 0',
        leaderboard:
            'M8 4h8v4a4 4 0 01-8 0V4zM6.5 6H4.5a2 2 0 002 4M17.5 6h2a2 2 0 01-2 4M9.5 13h5M12 13v6M9 20h6',
    };

    return (
        <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth={1.8}
            strokeLinecap="round"
            strokeLinejoin="round"
            className={className}
            aria-hidden="true"
        >
            <path d={paths[name]} />
        </svg>
    );
}

export default function PublicLayout({ children }) {
    const auth = usePage().props.auth;

    return (
        <div className="relative min-h-screen bg-background text-foreground">

            <div className="relative mx-auto flex min-h-screen max-w-5xl flex-col px-4 pt-[calc(env(safe-area-inset-top)+0.75rem)] sm:px-6 lg:max-w-7xl lg:px-8">
                <header className="flex items-center justify-between gap-4 rounded-xl border border-border bg-card p-3 text-card-foreground shadow-lg backdrop-blur sm:p-4">
                    <Link href={route('public.home')} className="group flex items-center gap-2">
                        <span className="flex h-9 w-9 items-center justify-center overflow-hidden rounded-lg bg-primary sm:h-10 sm:w-10">
                            <img
                                src={zeitlosLogo}
                                alt="FC Zeitlos logo"
                                className="h-full w-full object-contain"
                            />
                        </span>
                        <span className="leading-tight">
                            <span className="block text-[0.6rem] font-bold uppercase tracking-[0.4em] text-primary">
                                FC Zeitlos
                            </span>
                            <span className="block text-base font-black tracking-tight text-foreground sm:text-lg">
                                Team Hub
                            </span>
                        </span>
                    </Link>

                    <nav className="hidden flex-wrap gap-2 text-sm font-semibold sm:flex">
                        {navItems.map(([name, label]) => (
                            <Link
                                key={name}
                                href={route(name)}
                                className={`rounded-full px-4 py-2 transition ${
                                    route().current(name)
                                        ? 'bg-primary text-primary-foreground'
                                        : 'bg-accent text-accent-foreground hover:bg-accent'
                                }`}
                            >
                                {label}
                            </Link>
                        ))}
                        {auth?.user ? (
                            <Link
                                href={route('dashboard')}
                                className="rounded-full bg-secondary px-4 py-2 text-secondary-foreground transition hover:bg-secondary"
                            >
                                Admin
                            </Link>
                        ) : (
                            <Link
                                href={route('login')}
                                className="rounded-full border border-border px-4 py-2 text-foreground transition hover:bg-accent"
                            >
                                Log in
                            </Link>
                        )}
                        <ThemeToggle className="rounded-full border border-border px-4 py-2" />
                    </nav>

                    <div className="flex items-center gap-3 sm:hidden">
                        <ThemeToggle />
                        {auth?.user ? (
                            <Link
                                href={route('dashboard')}
                                className="rounded-full bg-secondary px-4 py-2 text-xs font-bold text-secondary-foreground"
                            >
                                Admin
                            </Link>
                        ) : (
                            <Link
                                href={route('login')}
                                className="rounded-full border border-border px-4 py-2 text-xs font-semibold text-foreground"
                            >
                                Log in
                            </Link>
                        )}
                    </div>
                </header>

                <main className="flex-1 pb-28 pt-6 sm:pb-10 sm:pt-8">{children}</main>
            </div>

            <nav
                aria-label="Public navigation"
                data-public-nav
                className="fixed inset-x-0 bottom-0 z-40 border-t border-border bg-background pb-safe-bottom backdrop-blur sm:hidden"
            >
                <div className="mx-auto flex max-w-5xl items-stretch">
                    {navItems.map(([name, label, icon]) => {
                        const active = route().current(name);
                        return (
                            <Link
                                key={name}
                                href={route(name)}
                                aria-current={active ? 'page' : undefined}
                                className={`flex flex-1 flex-col items-center justify-center gap-1 py-2.5 text-[0.65rem] font-semibold transition ${
                                    active ? 'text-primary' : 'text-muted-foreground hover:text-foreground'
                                }`}
                            >
                                <TabIcon name={icon} className="h-6 w-6" />
                                <span>{label}</span>
                            </Link>
                        );
                    })}
                </div>
            </nav>

            <InstallPrompt />
        </div>
    );
}
