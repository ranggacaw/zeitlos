import { Link } from '@inertiajs/react';

export default function ResponsiveNavLink({
    active = false,
    className = '',
    children,
    ...props
}) {
    return (
        <Link
            {...props}
            className={`flex w-full items-start border-l-4 py-2 pe-4 ps-3 ${
                active
                    ? 'border-primary bg-accent text-accent-foreground focus:border-ring focus:bg-accent focus:text-accent-foreground'
                    : 'border-transparent text-muted-foreground hover:border-border hover:bg-accent hover:text-accent-foreground focus:border-ring focus:bg-accent focus:text-accent-foreground'
            } text-base font-medium transition duration-150 ease-in-out focus:outline-none ${className}`}
        >
            {children}
        </Link>
    );
}
