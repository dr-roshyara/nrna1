# Election Management System — Overview

## What Was Built

This guide covers the **Election Officer System** and **Election Management Dashboard**, implemented in March 2026 as a full TDD refactor of the legacy election management code.

The legacy system used:
- Undefined Laravel Gates (`manage-election-settings`, `view-election-results`, `publish-election-results`)
- `auth:sanctum` (mobile API guard) on Inertia web routes
- Session-based election context (no `{election}` in the URL)
- Raw `fetch()` in Vue components (Inertia 2.0 violation)
- `ElectionPolicy` checking `user_organisation_roles` (Spatie roles), not `election_officers`

The new system uses:
- `ElectionOfficer` model as the single source of truth for all permissions
- Policy-based authorization (`ElectionPolicy`) with 5 explicit methods
- `/elections/{election}/*` route prefix with route model binding
- `auth` + `verified` middleware (web/session guard)
- Inertia 2.0 `router.post()` in Vue components

---

## What Was Delivered

| Component | Files |
|-----------|-------|
| Officer appointment (soft-delete restore) | `ElectionOfficerController.php` |
| Officer invitation email | `OfficerAppointedNotification.php` |
| Invitation acceptance flow | `ElectionOfficerInvitationController.php` |
| Authorization policy | `ElectionPolicy.php` |
| Management dashboard controller | `Election/ElectionManagementController.php` |
| Routes (new) | `routes/election/electionRoutes.php` |
| Management Vue component | `resources/js/Pages/Election/Management.vue` |
| Viewboard Vue component | `resources/js/Pages/Election/Viewboard.vue` |
| DB migrations | `add_results_published_to_elections`, `convert_legacy_commission_roles` |
| **Election creation (owner/admin)** | `ElectionManagementController::create()` + `store()` |
| **Election activation (chief/deputy)** | `ElectionManagementController::activate()` |
| **Email notification on creation** | `Notifications/ElectionReadyForActivation.php` |
| **Create election form** | `resources/js/Pages/Organisations/Elections/Create.vue` |
| **Role-based organisation dashboard** | `OrganisationController::show()` + `Show.vue` — 12 sections, role-gated |
| **Shared UI components** | `StatusBadge`, `ActionButton`, `EmptyState`, `SectionCard` in `resources/js/Components/` |
| Tests | 52 tests, 123 assertions — all green |

---

## Authorization Matrix

### Election Management (sourced from `election_officers`)

| Role | View | ViewResults | ManageSettings | PublishResults | ManageVoters | Activate |
|------|------|-------------|----------------|----------------|--------------|----------|
| **Chief** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Deputy** | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |
| **Commissioner** | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Pending officer** | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Non-officer** | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |

All management authorization is sourced from `election_officers.role` + `election_officers.status = 'active'`. No Spatie roles involved.

### Election Creation (sourced from `user_organisation_roles`)

| Role | Create Election |
|------|-----------------|
| **Owner** | ✅ |
| **Admin** | ✅ |
| **Voter / any officer** | ❌ |

Election *creation* is an organisational governance action — it uses `UserOrganisationRole`, not `ElectionOfficer`. Officers (chief/deputy) manage elections after creation.

### Organisation Dashboard Visibility (sourced from both tables)

| Section | Owner/Admin | Chief | Deputy | Commissioner | Voter |
|---------|:-----------:|:-----:|:------:|:------------:|:-----:|
| Stats, Header, Demo, Support | ✅ | ✅ | ✅ | ✅ | ✅ |
| Quick Actions, Demo Setup, Officer Mgmt | ✅ | — | — | — | — |
| Elections grid (full: Activate + Manage) | ✅ | ✅ Activate | ✅ Activate | view only | view only |
| Voter Management | ✅ | ✅ | ✅ | — | — |
| Results Management | ✅ | ✅ | — | — | — |
| Active Election Notice | — | — | — | — | ✅ |

See `10-role-based-organisation-dashboard.md` for full implementation details.

---

## Test Suite

```bash
# Run all election management tests
php artisan test tests/Feature/Election/

# Expected output:
# Tests: 52 passed (123 assertions)

# Run specific suites
php artisan test --filter="ElectionOfficerManagementTest|ElectionOfficerInvitationTest|ElectionDashboardAccessTest"
php artisan test tests/Feature/Election/ElectionCreationTest.php
php artisan test tests/Feature/Election/ElectionActivationTest.php
```

---

## File Map

```
app/
├── Http/Controllers/
│   ├── Election/
│   │   └── ElectionManagementController.php   ← Dashboard, create, store, activate, viewboard, publish, voting control
│   ├── ElectionOfficerController.php           ← Appoint, remove, list officers
│   ├── ElectionOfficerInvitationController.php ← Accept invitation via signed URL
│   └── OrganisationController.php             ← show() computes 9 role flags; passes to Show.vue
├── Models/
│   ├── ElectionOfficer.php
│   └── Election.php                            ← results_published added
├── Notifications/
│   ├── OfficerAppointedNotification.php        ← Queued mail with signed URL
│   └── ElectionReadyForActivation.php          ← Queued mail to chiefs on election creation
└── Policies/
    └── ElectionPolicy.php                      ← 6 role-aware methods (incl. create)

database/migrations/
├── 2026_03_19_214205_create_election_officers_table.php
├── 2026_03_20_205442_add_results_published_to_elections_table.php
└── 2026_03_20_205443_convert_legacy_commission_roles_to_officers.php

resources/js/
├── Components/                                 ← NEW shared UI components
│   ├── StatusBadge.vue                         ← Election status badge (planned/active/completed/archived)
│   ├── ActionButton.vue                        ← Consistent button (variant, size, loading, href)
│   ├── EmptyState.vue                          ← Centred empty state with icon + action slots
│   └── SectionCard.vue                         ← White card wrapper with header + variant
└── Pages/
    ├── Election/
    │   ├── Management.vue                      ← Redesigned: SectionCard layout, ActionButton, EmptyState
    │   └── Viewboard.vue                       ← All officers: read-only status + stats
    └── Organisations/
        ├── Show.vue                            ← 12 role-gated sections; consumes all 9 permission flags
        ├── Elections/
        │   └── Create.vue                      ← Election creation form (owner/admin only)
        └── Partials/
            ├── ActionButtons.vue               ← Create Election card gated by canCreateElection
            └── ElectionCard.vue                ← NEW: election card with canActivate/canManage/isReadonly

routes/
├── organisations.php                           ← GET/POST /organisations/{slug}/elections/*
└── election/electionRoutes.php                 ← POST /elections/{election}/activate added

tests/Feature/Election/
├── ElectionOfficerManagementTest.php   (11 tests)
├── ElectionOfficerInvitationTest.php   (7 tests)
├── ElectionDashboardAccessTest.php     (12 tests)
├── ElectionCreationTest.php            (21 tests)
└── ElectionActivationTest.php          (9 tests)

developer_guide/election_management/
├── 01-overview.md                              ← This file
├── 02-election-officer-model.md
├── 03-invitation-flow.md
├── 04-election-policy.md
├── 05-routes-and-controller.md
├── 06-vue-components.md
├── 07-testing.md
├── 08-voter-management.md
├── 09-election-creation-and-activation.md
└── 10-role-based-organisation-dashboard.md     ← NEW
```
