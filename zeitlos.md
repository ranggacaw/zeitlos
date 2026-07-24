# Prompt: Build "Zeitlos" — Football Team Management PWA

## Role & Context
You are a senior full-stack developer. Build a Progressive Web App (PWA) called **Zeitlos** for a football (mini-soccer) team. The app manages match schedules, player rosters, live scoring, and player statistics (top scorer / top assist).

## Tech Stack
- **Backend:** Laravel (latest LTS), MySQL
- **Frontend:** Inertia.js + React (functional components, hooks)
- **Styling:** Tailwind CSS
- **Auth:** Laravel Breeze (Inertia + React starter kit) with roles: `admin` and `guest` (public/unauthenticated view)
- **PWA:** Installable app — web manifest, service worker, offline caching for static assets and last-loaded data, app icons, splash screen
- **State/Data fetching:** Inertia props + optional Axios for live-score polling/updates

## Roles & Permissions
1. **Admin (logged in)** — full CRUD on: matches/schedules, player roster, match roster (who's playing next match), live score input during a match, top scorer/assist data (auto-calculated from match events, but editable/correctable by admin).
2. **Guest / Visitor (not logged in)** — read-only access to everything: dashboard, player details, schedules, match roster info, top scorer/assist leaderboard. No edit/create/delete buttons should render for guests.

---

## Features & Pages

### 1. Dashboard (`/`)
Public landing page showing:
- **Next/Upcoming match** card (date, time, opponent name, venue, countdown if possible)
- **Top Scorer** (name, photo, goal count) — top 3–5, link to full leaderboard
- **Top Assist** (name, photo, assist count) — top 3–5, link to full leaderboard
- **All Players list** (grid/list with photo, name, number) — clicking a player navigates to `Player Detail` page
- **Recent match results** — e.g. "Zeitlos vs Anjelo — 13-4", with a short list of past results (most recent first)
- **Instagram account link/embed** — team's Instagram handle with a clickable icon/link (and optionally embed latest post or just a styled link button)

### 2. Player Detail Page (`/players/{id}`)
Shows a single player's profile:
- Photo, full name, jersey number
- Position (e.g. Goalkeeper, Defender, Midfielder, Forward)
- Total goals (score) and total assists
- Optional: match-by-match stat history, join date

### 3. Schedule Page (`/schedule`)
Three sections/tabs:
- **Today's match** (if any)
- **Next/upcoming matches** (sorted ascending by date)
- **Past matches** (sorted descending, with final score result)
Each schedule item shows: date, time, opponent team name, venue name, and status (upcoming/live/finished).

### 4. Match Roster / Player List Page (`/matches/{id}/roster` or similar)
This is the pre-match info sheet for the **next match**, editable by Admin only, viewable by everyone. Fields (mirrors the WhatsApp announcement format the team currently uses manually):
- Match title/slogan (e.g. "MAY THE WINS BE WITH YOU")
- Venue name (e.g. "EPIC MINISOCCER") + **Google Maps link/embed**
- Time range (e.g. "18:00 – 20:00 WIB")
- Date
- Ticket/entry price (e.g. "80K")
- Dress code / jersey info (e.g. "JERSEY ZEITLOS")
- Facilities included (e.g. "Lapangan, Wasit, First aid Group / FG")
- Opponent/enemy team name
- Notes (free text, e.g. payment deadline warnings)
- **Payment info** (DP amount, DANA number + name, Bank account + name, where to send proof of transfer)
- **Goalkeeper list** (add/remove names, admin only)
- **Player list** (add/remove names, numbered, admin only — players can be existing roster players or free-text names for guests/subs)
- A **"Copy as WhatsApp text"** button that formats all the above fields into the exact WhatsApp-style announcement text (with `*bold*`, `_underline-ish dividers_`, emojis: 🥅⏰📅💵👔✅) so the admin can paste it directly into WhatsApp groups.

> Reference format to replicate for the "Copy as WhatsApp text" output:
> ```
> *!!MAY THE WINS BE WITH YOU!!*
> *🥅 : EPIC MINISOCCER*
> *⏰ : 18:00 sd 20:00 WIB*
> *📅 : Sabtu, 18 Juli 2026*
> *💵 : 80K*
> *👔 : JERSEY ZEITLOS*
> *Include: Lap, Wasit, FG*
> _________________________
> *NOTE: SEGERA BERGABUNG & BAYAR DP SECEPATNYA AGAR TIDAK KEHABISAN SLOT, KARENA SLOT TERBATAS*
> __________________________________________________
> *PEMBAYARAN DP:*
> *DANA:* 085156292210 an Rafly Fandiansyah
> *BCA:* 7391837345 an Rafly Fandiansyah
> *DP Mininal 20K ✅*
> *Bukti TF kirim ke:* 085156292210
> *Maps :* https://maps.app.goo.gl/xxxx
> *LIST KIPER*
> 1. Gilang
> *LIST PLAYER*
> 1. Hendry
> 2. Rangga
> ...
> ```
> All the bracketed values above must be dynamic fields pulled from the database, not hardcoded.

### 5. Top Scorer & Top Assist Page (`/leaderboard`)
- Full sortable table: player photo, name, position, goals, assists
- Two tabs or two side-by-side tables: Top Scorer / Top Assist
- Admin can manually adjust/correct values here if needed (in addition to auto-calc from live match events)

### 6. Live Match Console (Admin only, `/matches/{id}/live`)
While a match is ongoing, Admin can:
- Set match status to "Live"
- Input running score for both teams (Zeitlos vs Opponent)
- Log goal events: which player scored, minute (optional), which player assisted (optional/nullable)
- These events automatically increment that player's `goals` and `assists` totals used in the Top Scorer/Assist leaderboard
- End the match → status becomes "Finished", final score locked, shows in Dashboard "recent results" and Schedule "past matches"

---

## Suggested Database Schema

- `users` (id, name, email, password, role: enum['admin','member'])
- `players` (id, name, photo, jersey_number, position, is_active)
- `teams` (id, name) — optional, or just store opponent as a string field on `matches`
- `matches` (id, opponent_name, match_date, start_time, end_time, venue_name, venue_maps_url, ticket_price, dress_code, facilities, notes, dp_amount, dana_number, dana_name, bca_number, bca_name, payment_proof_number, status: enum['upcoming','live','finished'], zeitlos_score, opponent_score)
- `match_rosters` (id, match_id, player_id nullable, guest_name nullable, role: enum['goalkeeper','player'])
- `match_events` (id, match_id, player_id, event_type: enum['goal','assist'], minute nullable)
- Player aggregate stats (`goals`, `assists` totals) can be computed via query/aggregation from `match_events`, or cached on the `players` table and updated on each event write — implement whichever is cleaner but keep leaderboard queries fast.

---

## PWA Requirements
- `manifest.json` with app name "Zeitlos", short name, theme color, background color, icons (192x192, 512x512, plus maskable icon variant), `display: standalone`, `orientation: portrait`
- Service worker for caching static assets and enabling "Add to Home Screen"
- Offline fallback page for when there's no connection
- Mobile-first responsive design — **this app will be used almost exclusively on phones** by team members (checking schedule, roster, live score during a match), so design and build mobile-first, then scale up to tablet/desktop as a secondary concern
- Custom "Add to Home Screen" install prompt (don't just rely on the browser's default banner) shown after a user has browsed a couple of pages
- iOS PWA meta tags too (`apple-touch-icon`, `apple-mobile-web-app-capable`, `apple-mobile-web-app-status-bar-style`) since players will likely use iPhones, not just Android
- Respect the phone's safe areas (notch / home indicator) using `viewport-fit=cover` and `env(safe-area-inset-*)` in CSS so content isn't hidden behind the notch or gesture bar

## UI/UX Notes — Mobile-First (PWA)

This is a **phone-first PWA**, not a desktop site that happens to be responsive. Design and test primarily at a 375–430px viewport width. Desktop is a nice-to-have, not the priority.

**Layout & Navigation**
- Single-column layouts throughout — no multi-column grids that require horizontal scrolling or pinch-zoom
- **Bottom tab bar** (not a hamburger menu) fixed to the bottom of the screen for primary navigation: Dashboard, Schedule, Roster, Leaderboard, Profile/Login — this is the standard mobile app pattern and is far faster to use one-handed than a hamburger drawer
- Sticky top header with page title + back button on sub-pages (player detail, live console) so users always know where they are and can navigate back with their thumb
- Keep the bottom tab bar and any sticky headers above `env(safe-area-inset-bottom)` / below `env(safe-area-inset-top)`

**Touch & Ergonomics**
- All tappable elements minimum **44x44px** touch target (Apple HIG standard) — buttons, list rows, icons
- Place primary actions (e.g. "Copy as WhatsApp text", "Add Goal", "Save Score") within easy thumb reach — bottom half of the screen or as sticky action bars, not buried at the top
- Use native-feeling mobile inputs: number pad (`inputmode="numeric"`) for score/price fields, native `<select>` or bottom-sheet pickers for position/status dropdowns, native date/time pickers for match scheduling
- Swipe gestures where natural (e.g. swipe between "Today / Next / Past" schedule tabs)
- Avoid hover-dependent interactions (no hover-to-reveal menus) since phones have no hover state

**Live Match Console (Admin) — Speed Matters**
- This is used mid-match, likely one-handed, possibly in sunlight — make it **big, bold, and fast**:
  - Large "+1 Goal" tap targets per team, with a quick post-tap picker (bottom sheet) to select scorer + assister from the match roster
  - Big, high-contrast running scoreboard fixed at the top of the screen
  - Undo/correct last action easily (mistakes happen fast during a live match)
  - Minimal typing — favor tap-to-select over free text wherever possible

**Visual Style**
- Clean, sporty, dark/team-color themed design (propose a color palette matching a football club identity — e.g. black/gold or black/red for "Zeitlos")
- Dark mode by default (or as the primary theme) — easier on the eyes for evening matches and battery-friendly on OLED phones
- Card-based components (player cards, match cards, roster cards) with generous spacing/padding for easy scanning and tapping
- Skeleton loaders / shimmer states for slow mobile connections, rather than blank screens
- Show an "Admin" badge/menu only when authenticated as admin

**Performance**
- Lazy-load player photos and non-critical images
- Keep initial page payload light — this may be used on mobile data at the field, not always on WiFi
- All forms (match roster editing, live score input) should be simple, large-input, and fast to use from a phone, including with cold/sweaty hands during a live match

## Deliverables
1. Full Laravel + Inertia + React project scaffold with the routes/pages/controllers/models/migrations described above
2. Seed data (a handful of dummy players and one upcoming + one past match) for demo purposes
3. PWA manifest + service worker configured and working
4. Basic auth (login only for admin — no public registration needed, admin account seeded manually)

---

Please scaffold this project step by step: (1) Laravel install + Breeze Inertia/React setup, (2) migrations & models, (3) seeders, (4) controllers & routes, (5) React pages/components, (6) PWA config, (7) styling pass.