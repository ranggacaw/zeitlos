# Change: Update mobile app design

## Why
The public Zeitlos PWA is functional and installable, but it still uses a desktop-first layout with top navigation and generic cards. Match-day users need a mobile-first interface that feels app-like, keeps core routes within thumb reach, and preserves readability on small screens.

## What Changes
- Restyle the public layout with a dark sporty mobile app shell, safe-area-aware spacing, and bottom tab navigation for public routes.
- Polish the dashboard, schedule, roster, player detail, and leaderboard pages with touch-friendly cards, responsive spacing, and consistent mobile-first visual hierarchy.
- Adjust the install prompt so it coexists with bottom navigation and remains non-disruptive on small screens.
- Add frontend build coverage and targeted feature assertions for mobile navigation/install prompt markup where practical.

## Impact
- Affected specs: public-team-pages, zeitlos-pwa
- Affected code: `resources/js/Layouts/PublicLayout.jsx`, `resources/js/Components/InstallPrompt.jsx`, `resources/js/Pages/Welcome.jsx`, `resources/js/Pages/Public/Schedule.jsx`, `resources/js/Pages/Public/Roster.jsx`, `resources/js/Pages/Public/PlayerShow.jsx`, `resources/js/Pages/Public/Leaderboard.jsx`, `resources/css/app.css`, `tailwind.config.js`, `tests/Feature/PublicTeamPagesTest.php`, `tests/Feature/PwaAssetsTest.php`
