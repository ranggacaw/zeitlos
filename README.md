# Zeitlos Football Team Management PWA

Zeitlos is a Laravel + React/Inertia app for managing a football team and publishing a mobile-first public team site. It includes public read-only pages, authenticated admin tools, live match scoring, seeded demo data, and installable PWA support.

## Features

- Public team dashboard at `/` with mobile-first navigation.
- Public schedule, roster, player detail, and leaderboard pages.
- Admin dashboard under `/admin` for team management.
- Player CRUD, match CRUD, and per-match roster management.
- WhatsApp-ready roster text generation for match communication.
- Live match scoring with goal events, assists, final scores, and stat corrections.
- PWA assets: web manifest, service worker, offline fallback, app icons, install prompt, and iOS safe-area metadata.
- Seeded admin account, players, matches, rosters, and example match events.

## Tech Stack

- PHP 8.2+
- Laravel 12
- Laravel Breeze authentication
- Inertia.js 2
- React 18
- Vite 7
- Tailwind CSS 3
- MySQL by default
- PHPUnit for tests

## Main Routes

- `/` - public team dashboard
- `/schedule` - public match schedule
- `/roster` - public player roster
- `/players/{player}` - public player detail page
- `/leaderboard` - public stats leaderboard
- `/login` - admin/user login
- `/dashboard` - authenticated Breeze dashboard
- `/admin` - admin team management dashboard
- `/admin/players` - player management
- `/admin/matches` - match management
- `/admin/matches/{match}/roster` - match roster management
- `/admin/matches/{match}/scoring` - live match scoring console

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- MySQL or a compatible database

## Setup

1. Install PHP dependencies:

```bash
composer install
```

2. Install JavaScript dependencies:

```bash
npm install
```

3. Create the environment file:

```bash
cp .env.example .env
```

4. Generate the Laravel app key:

```bash
php artisan key:generate
```

5. Configure your database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zeitlos
DB_USERNAME=root
DB_PASSWORD=
```

6. Run migrations and seed demo data:

```bash
php artisan migrate --seed
```

7. Start the development servers:

```bash
composer run dev
```

Alternatively, run Laravel and Vite separately:

```bash
php artisan serve
npm run dev
```

## Demo Admin Login

The database seeder creates an admin user:

```text
Email: admin@zeitlos.test
Password: password
```

Use this account to access `/admin` locally after running `php artisan migrate --seed`.

## PWA Notes

Static PWA assets live in `public/`:

- `public/manifest.webmanifest`
- `public/sw.js`
- `public/offline.html`
- `public/icons/*`

The service worker is registered from `resources/js/app.jsx` only in production and secure browser contexts. In local Vite development it stays disabled so it does not interfere with hot module reloading.

When changing cached shell assets, bump the `CACHE_VERSION` constant in `public/sw.js`.

## Useful Commands

```bash
composer run dev
npm run dev
npm run build
php artisan migrate --seed
php artisan test
composer test
```

## Testing

Run the full test suite with:

```bash
composer test
```

The test suite covers the football domain, public pages, admin team management, live match scoring, authentication/profile flows, and PWA asset integration.

## Project Structure

```text
app/Http/Controllers/PublicTeamController.php       Public team pages
app/Http/Controllers/Admin/                         Admin team management controllers
app/Models/Player.php                               Team players
app/Models/FootballMatch.php                        Matches and scores
app/Models/MatchRoster.php                          Match roster entries
app/Models/MatchEvent.php                           Match scoring events
app/Team/WhatsAppRosterText.php                     Copyable roster text generator
database/migrations/                                App schema
database/seeders/DatabaseSeeder.php                 Demo admin, players, matches, rosters, events
resources/js/Layouts/PublicLayout.jsx               Public mobile app shell
resources/js/Pages/Public/                          Public Inertia pages
resources/js/Pages/Admin/                           Admin Inertia pages
public/manifest.webmanifest                         PWA manifest
public/sw.js                                        Service worker
public/offline.html                                 Offline fallback
tests/Feature/                                      Feature tests
tests/Unit/                                         Unit tests
```

## Production Build

Build frontend assets before deploying:

```bash
npm run build
```

Then run the standard Laravel deployment steps for your environment, including environment configuration, migrations, cache warming, and queue worker setup if queue-backed features are enabled.
