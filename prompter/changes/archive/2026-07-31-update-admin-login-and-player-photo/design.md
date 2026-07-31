## Context
Three coupled admin/auth UX fixes ship together: retiring the unused `/dashboard`, adding show/hide password to both login surfaces, and letting admins set a player photo by upload or URL. The `/dashboard` removal has a wide blast radius (6 auth controllers, 2 layouts, several tests) that needs a single agreed redirect rule. The photo feature stores two different value shapes (storage-relative path vs absolute URL) in one column, which needs a clear contract so the frontend renders both correctly.

## Goals / Non-Goals
- Goals: admins land on `/admin` after auth; both login forms have a password reveal; player photo accepts upload or URL in one column; public rendering handles both.
- Non-Goals: no new DB columns, no public-side redesign, no photo support on non-Player entities, no JS unit-test harness.

## Decisions

### Post-auth redirect rule
- Add `User::preferredHomeUrl(): string` returning `/admin` for `isAdmin()`, `/` otherwise.
- All post-auth redirect points call it; when no user is in scope (none of the 5 secondary controllers lack a user at redirect time after authentication/verification), use `/`.
- Login store uses `redirect()->intended($user->preferredHomeUrl())` so a intended URL still wins when present, defaulting to the role-aware home.
- Use a path string (`/admin`) rather than a Filament route name because the project intentionally asserts no named admin dashboard route exists (`AdminTeamManagementTest`).

### Filament password reveal without a custom view
- Override `getPasswordFormComponent()` in a custom `App\Filament\Pages\Auth\Login` and chain `->revealable()` (Filament v4 native text-input reveal). Avoids publishing/maintaining a custom Blade login view.
- Register via `->login(Login::class)` in `AdminPanelProvider`.
- Fallback if `->revealable()` is unavailable in this v4 version: publish the login Blade view and add the toggle markup.

### Player photo: one column, two input modes
- `photo_path` stays the single persisted column and holds either `players/<file>.jpg` (from FileUpload on the `public` disk) or a full `http(s)://` URL.
- `FileUpload::make('photo_path')` handles uploads; a dehydrated `TextInput::make('photo_url')` writes URLs into `photo_path` via `afterStateUpdated` and pre-fills via `formatStateUsing` when the stored value is an absolute URL.
- Frontend (`rosterPhotos.js`) passes values starting with `http://`/`https://` through unchanged and otherwise keeps `/storage/{photo_path}`.

### Breeze login eye
- Inline SVG eye/eye-slash toggle driven by local `showPassword` state; no new icon dependency is added to `package.json`.

## Risks / Trade-offs
- Changing `photo_path` to a `FileUpload` may break the existing `fillForm(['photo_path' => ...])` test -> mitigated by switching the test to the `photo_url` field.
- `redirect()->intended()` could send an admin back to a previously-intended public URL instead of `/admin` -> acceptable; the role-aware default still only applies when no intended session URL exists.

## Migration Plan
1. Ship the redirect + dashboard removal first so auth entry points are consistent.
2. Add the two password reveal toggles.
3. Switch the player photo field and update frontend/test contracts.
4. Rollback: revert route/controller/form changes; no schema migration is involved, so no data rollback is needed.

## Open Questions
- None remaining after scope confirmation.
