import { useEffect, useState } from 'react';

const DISMISS_KEY = 'zeitlos:install-prompt-dismissed';

export default function InstallPrompt() {
    const [deferredPrompt, setDeferredPrompt] = useState(null);

    useEffect(() => {
        if (typeof window === 'undefined') {
            return;
        }

        const isStandalone =
            window.matchMedia?.('(display-mode: standalone)').matches ||
            window.navigator.standalone === true;

        if (isStandalone || window.localStorage.getItem(DISMISS_KEY) === '1') {
            return;
        }

        const handler = (event) => {
            event.preventDefault();
            setDeferredPrompt(event);
        };

        window.addEventListener('beforeinstallprompt', handler);

        return () => window.removeEventListener('beforeinstallprompt', handler);
    }, []);

    const dismiss = () => {
        window.localStorage.setItem(DISMISS_KEY, '1');
        setDeferredPrompt(null);
    };

    const install = async () => {
        if (!deferredPrompt) {
            return;
        }

        deferredPrompt.prompt();
        try {
            await deferredPrompt.userChoice;
        } finally {
            setDeferredPrompt(null);
        }
    };

    if (!deferredPrompt) {
        return null;
    }

    return (
        <div
            role="dialog"
            aria-label="Install Zeitlos app"
            data-install-prompt
            className="fixed inset-x-0 bottom-28 z-50 mx-auto flex max-w-md items-center gap-3 rounded-2xl border border-border bg-card p-4 text-card-foreground shadow-2xl backdrop-blur sm:bottom-6"
        >
            <div className="flex-1">
                <p className="text-sm font-bold text-foreground">Install Zeitlos</p>
                <p className="text-xs text-muted-foreground">
                    Add the team hub to your home screen for quick, app-like access.
                </p>
            </div>
            <div className="flex flex-col gap-2 sm:flex-row">
                <button
                    type="button"
                    onClick={install}
                    className="rounded-full bg-primary px-5 py-2.5 text-xs font-bold text-primary-foreground transition hover:bg-primary"
                >
                    Install
                </button>
                <button
                    type="button"
                    onClick={dismiss}
                    aria-label="Dismiss install prompt"
                    className="rounded-full border border-border px-4 py-2.5 text-xs font-semibold text-foreground transition hover:bg-accent"
                >
                    Not now
                </button>
            </div>
        </div>
    );
}
