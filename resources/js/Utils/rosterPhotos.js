const rosterPhotoModules = import.meta.glob('../../assets/roster/*.{png,jpg,jpeg,webp,avif}', {
    eager: true,
    import: 'default',
    query: '?url',
});

const rosterPhotos = Object.fromEntries(
    Object.entries(rosterPhotoModules).map(([path, url]) => [path.split('/').pop().split('.')[0], url]),
);

const slugify = (value) => value
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/(^-|-$)/g, '');

export function rosterPhotoUrl(player) {
    if (player.photo_path) {
        if (/^https?:\/\//i.test(player.photo_path)) {
            return player.photo_path;
        }

        return `/storage/${player.photo_path}`;
    }

    const nameSlug = slugify(player.name ?? '');
    const firstNameSlug = slugify((player.name ?? '').split(/\s+/)[0] ?? '');

    return rosterPhotos[nameSlug] ?? rosterPhotos[firstNameSlug] ?? null;
}
