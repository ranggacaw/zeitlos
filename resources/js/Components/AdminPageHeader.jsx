export default function AdminPageHeader({ eyebrow = 'Admin CMS', title, children }) {
    return (
        <div className="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p className="text-sm font-semibold uppercase tracking-[0.2em] text-muted-foreground">{eyebrow}</p>
                <h2 className="text-2xl font-bold leading-tight text-foreground">{title}</h2>
            </div>
            {children}
        </div>
    );
}
