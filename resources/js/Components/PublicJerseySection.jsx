import { useState } from 'react';

import goalkeeperJersey from '../../assets/jersey/goalkeeper_zeitlos.jpeg';
import playerJersey from '../../assets/jersey/player_zeitlos.jpeg';

const jerseys = [
    {
        title: 'Player Jersey',
        label: 'Home kit',
        image: playerJersey,
        alt: 'Zeitlos 2025/2026 player jersey',
    },
    {
        title: 'Goalkeeper Jersey',
        label: 'Keeper kit',
        image: goalkeeperJersey,
        alt: 'Zeitlos 2025/2026 goalkeeper jersey',
    },
];

export default function PublicJerseySection({ className = '' }) {
    const [selectedJersey, setSelectedJersey] = useState(null);

    return (
        <>
            <section
                id="jersey"
                className={`scroll-mt-24 rounded-[1.75rem] border border-border bg-card p-5 text-card-foreground sm:p-6 lg:p-7 ${className}`}
            >
                <div className="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p className="text-[0.7rem] font-black uppercase tracking-[0.24em] text-primary">Team kit</p>
                        <h2 className="mt-1 text-xl font-black tracking-tight text-foreground sm:text-2xl">2025/2026 Jersey</h2>
                    </div>
                    <span className="w-fit rounded-full border border-border bg-background px-3 py-1 text-[0.65rem] font-black uppercase tracking-wider text-muted-foreground">
                        Official look
                    </span>
                </div>

                <div className="mt-5 grid gap-4 sm:grid-cols-2">
                    {jerseys.map((jersey) => (
                        <article key={jersey.title} className="overflow-hidden rounded-2xl border border-border bg-background">
                            <button
                                type="button"
                                onClick={() => setSelectedJersey(jersey)}
                                className="block aspect-[4/5] w-full bg-white p-2"
                            >
                                <img src={jersey.image} alt={jersey.alt} className="h-full w-full bg-white object-contain" />
                            </button>
                            <div className="p-4">
                                <p className="text-[0.65rem] font-black uppercase tracking-[0.24em] text-primary">{jersey.label}</p>
                                <h3 className="mt-1 text-base font-black tracking-tight text-foreground">{jersey.title}</h3>
                            </div>
                        </article>
                    ))}
                </div>
            </section>

            {selectedJersey && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4" onClick={() => setSelectedJersey(null)}>
                    <div className="relative max-h-full w-full max-w-3xl rounded-2xl bg-white p-3" onClick={(event) => event.stopPropagation()}>
                        <button
                            type="button"
                            onClick={() => setSelectedJersey(null)}
                            className="absolute right-3 top-3 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-black/80 text-xl font-black text-white"
                            aria-label="Close jersey preview"
                        >
                            X
                        </button>
                        <img src={selectedJersey.image} alt={selectedJersey.alt} className="max-h-[85vh] w-full object-contain" />
                    </div>
                </div>
            )}
        </>
    );
}
