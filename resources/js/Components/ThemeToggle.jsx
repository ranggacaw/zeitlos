import { useEffect, useState } from 'react';

const STORAGE_KEY = 'zeitlos:dark-mode';

export default function ThemeToggle({ className = '' }) {
    const [enabled, setEnabled] = useState(false);

    useEffect(() => {
        const stored = window.localStorage.getItem(STORAGE_KEY) === '1';
        setEnabled(stored);
        document.documentElement.classList.toggle('dark', stored);
    }, []);

    const toggle = (checked) => {
        setEnabled(checked);
        document.documentElement.classList.toggle('dark', checked);
        window.localStorage.setItem(STORAGE_KEY, checked ? '1' : '0');
    };

    return (
        <label className={`inline-flex items-center gap-2 text-xs font-semibold text-muted-foreground ${className}`}>
            <input
                type="checkbox"
                checked={enabled}
                onChange={(event) => toggle(event.target.checked)}
                className="rounded border-border text-primary shadow-sm focus:ring-ring"
            />
            <span>Dark</span>
        </label>
    );
}
