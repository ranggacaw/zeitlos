export function AdminFilterPanel({ children, summary }) {
    return (
        <section className="rounded-2xl border border-border bg-card p-4 text-card-foreground shadow-sm sm:p-5">
            <div className="grid gap-3 md:grid-cols-[1fr_auto] md:items-end">
                {children}
            </div>
            {summary && <p className="mt-3 text-sm text-muted-foreground">{summary}</p>}
        </section>
    );
}

export function AdminSearchInput({ label, value, onChange, placeholder }) {
    return (
        <div>
            <label className="text-xs font-bold uppercase tracking-[0.18em] text-muted-foreground">{label}</label>
            <input
                value={value}
                onChange={onChange}
                placeholder={placeholder}
                className="mt-2 block min-h-11 w-full rounded-xl border-border bg-input text-foreground shadow-sm focus:border-ring focus:ring-ring"
            />
        </div>
    );
}

export function SegmentedButtons({ options, value, onChange, className = '', capitalize = false }) {
    const classes = ['flex gap-2', className].filter(Boolean).join(' ');

    return (
        <div className={classes}>
            {options.map((option) => {
                const optionValue = typeof option === 'string' ? option : option.value;
                const label = typeof option === 'string' ? option : option.label;

                return (
                    <button
                        key={optionValue}
                        type="button"
                        onClick={() => onChange(optionValue)}
                        className={`min-h-11 rounded-xl border px-4 py-2 text-sm font-bold ${capitalize ? 'capitalize ' : ''}${value === optionValue ? 'border-primary bg-primary text-primary-foreground' : 'border-border bg-background text-foreground'}`}
                    >
                        {label}
                    </button>
                );
            })}
        </div>
    );
}
