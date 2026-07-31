# Change: Update admin login entry and player photo handling

## Why
The placeholder Breeze `/dashboard` page serves no purpose now that Filament `/admin` is the real CMS, so authenticated admins should land directly on `/admin`. The login forms lack a show/hide password control, and the player photo field only accepts a free-text storage path even though admins want to either upload an image or paste a URL.

## What Changes
- Remove the `/dashboard` route and `Dashboard.jsx`; redirect authenticated admins to `/admin` and non-admins to `/` (public home) after login, registration, and email verification. Add a `User::preferredHomeUrl()` helper used by all post-auth redirect points.
- Add a show/hide password eye toggle to the Breeze React `/login` (local state + inline SVG) and to the Filament `/admin/login` (a custom `App\Filament\Pages\Auth\Login` that makes the password field `->revealable()`, registered via `->login()`).
- Replace the player `photo_path` text input with a `FileUpload` (stores under `players/` on the `public` disk) plus a dehydrated `photo_url` text field that writes the URL into `photo_path`. Update the public roster/player frontend to pass absolute `http(s)://` URLs through without the `/storage/` prefix.
- Update affected auth/public/admin feature tests and project guidance to match the new entry behavior.

## Impact
- Affected specs: `admin-team-management`
- Affected code: `routes/web.php`, `app/Models/User.php`, `app/Http/Controllers/Auth/**`, `app/Providers/Filament/AdminPanelProvider.php`, `app/Filament/Pages/Auth/Login.php` (new), `app/Filament/Resources/Players/Schemas/PlayerForm.php`, `resources/js/Pages/Auth/Login.jsx`, `resources/js/Pages/Dashboard.jsx` (deleted), `resources/js/Layouts/{PublicLayout,AuthenticatedLayout}.jsx`, `resources/js/Utils/rosterPhotos.js`, `tests/Feature/**`, `AGENTS.md`
