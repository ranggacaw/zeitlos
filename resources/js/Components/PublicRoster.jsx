import { rosterPhotoUrl } from '@/Utils/rosterPhotos';
import { Link } from '@inertiajs/react';

const knownPositions = [
    'Goalkeeper', 'GK',
    'Defender', 'DF', 'CB', 'Center Back', 'Full Back', 'Right Back', 'Left Back',
    'Midfielder', 'MF', 'CM', 'Central Midfield', 'Attacking Midfield', 'Defensive Midfield',
    'Forward', 'Striker', 'FW', 'ST', 'CF', 'SS', 'Winger', 'Left Winger', 'Right Winger', 'LMF', 'RMF', 'LWF', 'RWF',
];

const rosterGroups = (players) => [
    { title: 'Goalkeepers', items: players.filter((p) => ['Goalkeeper', 'GK'].includes(p.position)) },
    { title: 'Defenders', items: players.filter((p) => ['Defender', 'DF', 'CB', 'Center Back', 'Full Back', 'Right Back', 'Left Back'].includes(p.position)) },
    { title: 'Midfielders', items: players.filter((p) => ['Midfielder', 'MF', 'CM', 'Central Midfield', 'Attacking Midfield', 'Defensive Midfield'].includes(p.position)) },
    { title: 'Forwards', items: players.filter((p) => ['Forward', 'Striker', 'FW', 'ST', 'CF', 'SS', 'Winger', 'Left Winger', 'Right Winger', 'LMF', 'RMF', 'LWF', 'RWF'].includes(p.position)) },
    { title: 'Other', items: players.filter((p) => !knownPositions.includes(p.position)) },
].filter((group) => group.items.length > 0);

export default function PublicRoster({
    players = [],
    eyebrow,
    title = 'Zeitlos Roster',
    badge = 'Full Squad',
    action,
    titleAddon,
    headingTag: HeadingTag = 'h2',
    className = 'rounded-[3rem] bg-surface-container-lowest p-6 sm:p-8',
    headerClassName = 'mb-6 flex flex-wrap items-end justify-between gap-4',
    headingWrapperClassName = '',
    titleClassName = 'mt-3 text-3xl font-black font-lexend uppercase tracking-tighter text-on-surface sm:text-5xl',
    contentClassName = 'space-y-10',
    gridClassName = 'grid grid-cols-3 gap-4 sm:grid-cols-3 lg:grid-cols-4',
    groupHeadingClassName = 'text-[0.65rem] font-bold font-lexend uppercase tracking-[0.25em] text-on-surface-variant',
    playerCardClassName = 'group flex flex-col rounded-[2rem] bg-surface-container-low p-4 transition-colors hover:bg-surface-container',
    playerPhotoClassName = 'relative mb-4 aspect-square w-full overflow-hidden rounded-2xl bg-surface-container-highest',
    playerMetaClassName = 'text-[0.65rem] font-bold font-lexend uppercase tracking-[0.25em] text-primary',
    playerNameClassName = 'mt-1 break-words text-xs font-black font-lexend uppercase leading-tight text-on-surface sm:text-base',
    emptyClassName = 'rounded-2xl border border-dashed border-border p-6 text-sm font-manrope text-on-surface-variant',
    emptyText = 'No active players are available yet.',
}) {
    return (
        <section className={className}>
            <div className={headerClassName}>
                <div className={headingWrapperClassName}>
                    {eyebrow && (
                        <p className="text-xs font-black font-lexend uppercase tracking-[0.3em] text-primary sm:text-sm">{eyebrow}</p>
                    )}
                    <HeadingTag className={titleClassName}>{title}</HeadingTag>
                    {titleAddon}
                </div>
                {action ?? (
                    badge && (
                        <span className="border-b-2 border-primary pb-1 text-[0.65rem] font-bold font-lexend uppercase tracking-widest text-primary">{badge}</span>
                    )
                )}
            </div>
            {players.length === 0 ? (
                <p className={emptyClassName}>{emptyText}</p>
            ) : (
                <div className={contentClassName}>
                    {rosterGroups(players).map((group) => (
                            <div key={group.title}>
                                <div className="mb-4 flex items-center gap-4">
                                    <div className="h-[2px] w-6 bg-primary"></div>
                                    <h3 className={groupHeadingClassName}>{group.title}</h3>
                                </div>
                                <div className={gridClassName}>
                                    {group.items.map((player) => {
                                    const photoUrl = rosterPhotoUrl(player);

                                    return (
                                        <Link
                                            key={player.id}
                                            href={route('public.players.show', player.id)}
                                            className={playerCardClassName}
                                        >
                                            <div className={playerPhotoClassName}>
                                                {photoUrl ? (
                                                    <img src={photoUrl} alt={player.name} className="h-full w-full object-cover" />
                                                ) : (
                                                    <div className="flex h-full w-full items-center justify-center text-on-surface-variant opacity-50">
                                                        <span className="material-symbols-outlined text-5xl">person</span>
                                                    </div>
                                                )}
                                            </div>
                                            <div className="flex flex-1 flex-col">
                                                <p className={playerMetaClassName}>
                                                    {player.jersey_number ?? '-'} · {player.position}
                                                </p>
                                                <h4 className={playerNameClassName}>
                                                    {player.name}
                                                </h4>
                                            </div>
                                        </Link>
                                    );
                                })}
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </section>
    );
}
