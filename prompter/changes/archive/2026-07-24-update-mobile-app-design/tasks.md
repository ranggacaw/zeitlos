## 1. Public App Shell
- [x] 1.1 Update `resources/js/Layouts/PublicLayout.jsx` to use a mobile-first app shell with safe-area-aware padding, compact branding, desktop header fallback, and fixed bottom tab navigation for public routes.
- [x] 1.2 Update `resources/js/Components/InstallPrompt.jsx` so the prompt is touch-friendly and clears the bottom tab bar on mobile.
- [x] 1.3 Add any shared global CSS needed for safe-area spacing or app-background polish in `resources/css/app.css`.

## 2. Public Page Polish
- [x] 2.1 Restyle `resources/js/Pages/Welcome.jsx` with a mobile-first hero, compact match cards, stat chips, and touch-friendly roster/leaderboard links.
- [x] 2.2 Restyle `resources/js/Pages/Public/Schedule.jsx` with readable stacked match cards, prominent score/status treatments, and comfortable tap targets.
- [x] 2.3 Restyle `resources/js/Pages/Public/Roster.jsx` and `resources/js/Pages/Public/PlayerShow.jsx` with responsive player cards and mobile-friendly player detail sections.
- [x] 2.4 Restyle `resources/js/Pages/Public/Leaderboard.jsx` with compact ranking rows that remain legible on small screens.

## 3. Verification
- [x] 3.1 Update `tests/Feature/PublicTeamPagesTest.php` and/or `tests/Feature/PwaAssetsTest.php` with practical assertions for the public mobile shell or install prompt markup.
- [x] 3.2 Run the PHP feature tests that cover public pages and PWA assets.
- [x] 3.3 Run `npm run build` to verify Tailwind class generation and the production bundle.
- [ ] 3.4 Manually verify the public pages on a narrow mobile viewport, including bottom tabs, touch targets, and install prompt spacing. (manual)

## Post-Implementation
- [x] Update `AGENTS.md` if the mobile public app shell introduces durable conventions future work must follow.
