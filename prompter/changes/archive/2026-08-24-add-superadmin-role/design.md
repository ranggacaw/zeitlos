## Context
Zeitlos uses Filament for admin team management. Currently, any user with `role === 'admin'` can access the admin panel and manage teams, matches, and players. However, business rules restrict actions like managing rosters and live scoring once a match has status `finished`. Furthermore, there is no User Management UI within Filament to assign roles or create accounts.

## Goals / Non-Goals
- **Goals**:
  - Implement a `superadmin` role with universal override privileges.
  - Allow SuperAdmins to edit rosters, record/delete goals, and re-finalize scores for finished matches.
  - Allow SuperAdmins to delete any matches.
  - Provide a Filament `UserResource` guarded exclusively for SuperAdmin users.
  - Keep standard Admin users constrained to normal active/scheduled match workflows and prevented from accessing User Management.
- **Non-Goals**:
  - Complex multi-permission ACLs or permission matrices (simple role hierarchy `user` < `admin` < `superadmin` is sufficient).
  - Public interface changes (public match pages remain read-only consumers of domain data).

## Decisions
- **Role Hierarchy & `isAdmin()` Behavior**:
  - `User::isAdmin()` will return `true` if `$this->role === self::ROLE_ADMIN || $this->role === self::ROLE_SUPERADMIN`.
  - This ensures all standard admin panel access, middleware (`EnsureAdmin`), and Inertia/Filament authorization continue to function seamlessly for SuperAdmins without scattering checks across the app.
  - `User::isSuperAdmin()` will specifically check `$this->role === self::ROLE_SUPERADMIN`.
- **Match Action Visibility**:
  - Check `$record->status !== FootballMatch::STATUS_FINISHED || auth()->user()?->isSuperAdmin()`.
  - Display informative subheadings on roster/live-scoring pages: "Finalized · SuperAdmin edit mode" for SuperAdmins vs "Finalized · read-only" for standard Admins.
- **User Management in Filament**:
  - Place `UserResource` under navigation group `Administration` (or `Team management`) with `canAccess()` / `canViewAny()` returning `auth()->user()?->isSuperAdmin()`.
  - Include validation to prevent a SuperAdmin from deleting their own user account.

## Risks / Trade-offs
- **Accidental alterations to historical finished matches** → Mitigated by requiring explicit SuperAdmin role and standard Filament modal confirmations on destructive actions.
