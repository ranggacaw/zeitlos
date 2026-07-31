## 1. Retire /dashboard and fix post-auth redirects
- [x] 1.1 Remove the `/dashboard` route block from `routes/web.php`.
- [x] 1.2 Delete `resources/js/Pages/Dashboard.jsx`.
- [x] 1.3 Add `User::preferredHomeUrl(): string` returning `/admin` for admins and `/` otherwise -- `app/Models/User.php`.
- [x] 1.4 `AuthenticatedSessionController::store`: redirect via `redirect()->intended($request->user()->preferredHomeUrl())`.
- [x] 1.5 Replace `route('dashboard', absolute: false)` with the role-aware home URL in `RegisteredUserController`, `VerifyEmailController`, `EmailVerificationPromptController`, `EmailVerificationNotificationController`, and `ConfirmablePasswordController`, preserving `?verified=1` on the verify-email path.

## 2. Filament admin login redirect + password eye
- [x] 2.1 Create `app/Filament/Pages/Auth/Login.php` extending `Filament\Pages\Auth\Login`; override `getPasswordFormComponent()` to chain `->revealable()`.
- [x] 2.2 Register it via `->login(\App\Filament\Pages\Auth\Login::class)` in `app/Providers/Filament/AdminPanelProvider.php`.

## 3. Breeze login eye + public navigation links
- [x] 3.1 `resources/js/Pages/Auth/Login.jsx`: add `showPassword` state, toggle the password `TextInput` `type` between `password`/`text`, render an inline eye/eye-slash SVG button inside a relative wrapper (no new dependency).
- [x] 3.2 Point the "Admin" link to `/admin` instead of `route('dashboard')` in `resources/js/Layouts/PublicLayout.jsx` and `resources/js/Layouts/AuthenticatedLayout.jsx`.
- [x] 3.3 `resources/js/Utils/rosterPhotos.js`: return `photo_path` unchanged when it starts with `http://` or `https://`, otherwise keep the existing `/storage/` prefix logic.

## 4. Player photo (upload + URL, single column)
- [x] 4.1 In `app/Filament/Resources/Players/Schemas/PlayerForm.php`, replace the `photo_path` `TextInput` with `FileUpload::make('photo_path')->image()->disk('public')->directory('players')->visibility('public')`.
- [x] 4.2 Add `TextInput::make('photo_url')->label('Photo URL')->dehydrated(false)` that writes its value into `photo_path` via `afterStateUpdated` and pre-fills from `photo_path` via `formatStateUsing` when the stored value is an absolute URL.

## 5. Tests and docs
- [x] 5.1 Update redirect assertions in `tests/Feature/Auth/AuthenticationTest.php`, `RegistrationTest.php`, and `EmailVerificationTest.php` (admin -> `/admin`, regular -> `/`).
- [x] 5.2 Remove or repurpose `tests/Feature/PublicTeamPagesTest.php::test_authenticated_dashboard_route_remains_protected` since `/dashboard` no longer exists.
- [x] 5.3 Adapt `tests/Feature/AdminTeamManagementTest.php` player create/edit `fillForm` to set the photo via the `photo_url` field; assert `photo_path` persists the URL string.
- [x] 5.4 Run `composer test` (or `php artisan test`) and `npm run build`; fix any fallout.
- [x] 5.5 Update `AGENTS.md`: remove the "Keep `/dashboard` reserved" and "/dashboard stays reserved for Breeze" notes; record that admins land on `/admin` post-login.
- [ ] 5.6 Verify the password eye toggle works on `/login` and `/admin/login`, and that an uploaded photo and a pasted URL both render on the public roster and player pages. (manual)

## Post-Implementation
- [x] Update `AGENTS.md` in the project root for new changes in this spec.
