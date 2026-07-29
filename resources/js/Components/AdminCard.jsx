export default function AdminCard({ as: Component = 'section', className = '', children }) {
    const classes = [
        'rounded-2xl border border-border bg-card text-card-foreground shadow-sm',
        className,
    ].filter(Boolean).join(' ');

    return <Component className={classes}>{children}</Component>;
}
