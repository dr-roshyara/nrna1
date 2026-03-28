# Candidacy System — Developer Guide

> **Scope:** Real elections only. Demo elections use pre-seeded `Candidacy` records and have no application flow.

---

## Table of Contents

1. [Overview](#overview)
2. [Two Models, Two Purposes](#two-models-two-purposes)
3. [Database Schema](#database-schema)
4. [Routes](#routes)
5. [Controllers](#controllers)
6. [Vue Pages](#vue-pages)
7. [Business Rules](#business-rules)
8. [Route Binding — The Tenant Scope Problem](#route-binding--the-tenant-scope-problem)
9. [Form Submission Mechanics](#form-submission-mechanics)
10. [Status Flow](#status-flow)
11. [File Map](#file-map)
12. [Tests](#tests)

> **How-to guide:** See [approval-procedure.md](./approval-procedure.md) for the complete step-by-step process from application submission to ballot publication.

---

## Overview

The candidacy system has two separate concerns that live in two separate models:

| Concern | Model | Who uses it | When |
|---------|-------|-------------|------|
| Voter applies to stand as candidate | `CandidacyApplication` | Voter (self-service) | Before election starts |
| Candidate appears on the ballot | `Candidacy` | Election officer (staff) | After application approved |

A voter submits a `CandidacyApplication`. An election officer reviews it and, if approved, manually creates a `Candidacy` record to place the candidate on the ballot. These are intentionally separate steps — approval does not automatically create a ballot entry.

---

## Two Models, Two Purposes

### `CandidacyApplication` — The Application

Stores a voter's request to stand as a candidate. Created by the voter via the self-service form. Has a review lifecycle (pending → approved / rejected).

```
voter submits form
    → CandidacyApplication (status=pending)
    → officer reviews
    → status = approved / rejected
```

**Does NOT use `BelongsToTenant` trait** — organisation scoping is enforced manually in every controller method. This was a deliberate choice to avoid the global scope interfering with form submissions before session context is established.

### `Candidacy` — The Ballot Entry

Represents a candidate who will appear on the voting form. Created by election officers via the admin Posts page. Can be linked to a `user_id` (from `UserOrganisationRole`) or entered as a free-text name.

```
officer creates ballot entry
    → Candidacy (status=approved)
    → appears on voting form
```

**Uses `BelongsToTenant` trait** — queries are automatically scoped to the session organisation.

---

## Database Schema

### `candidacy_applications`

| Column | Type | Notes |
|--------|------|-------|
| `id` | UUID, PK | Auto-generated via `HasUuids` |
| `user_id` | UUID, FK → users | Applicant |
| `organisation_id` | UUID, FK → organisations | Tenant scoping |
| `election_id` | UUID, FK → elections | Which election |
| `post_id` | UUID, FK → posts | Which position |
| `supporter_name` | varchar(255) | Required |
| `proposer_name` | varchar(255) | Required |
| `manifesto` | text, nullable | Election statement |
| `documents` | JSON, nullable | Legacy field — not used in current flow |
| `photo` | varchar(255), nullable | Path on `public` disk |
| `status` | varchar(255), default `pending` | `pending` / `approved` / `rejected` |
| `rejection_reason` | text, nullable | Filled by officer if rejected |
| `reviewed_at` | timestamp, nullable | When reviewed |
| `reviewed_by` | UUID, nullable | Officer user ID |
| `created_at` / `updated_at` | timestamps | Standard |

**Composite index:** `(user_id, election_id, status)` — used by the one-per-election duplicate check.

**Migrations:**
- `2026_03_24_205317_create_candidacy_applications_table.php`
- `2026_03_24_224518_add_photo_to_candidacy_applications_table.php`

### `candidacies`

| Column | Type | Notes |
|--------|------|-------|
| `id` | UUID, PK | |
| `organisation_id` | UUID, FK | BelongsToTenant |
| `post_id` | UUID, FK → posts | Position on ballot |
| `user_id` | UUID, nullable, FK → users | Linked member (if known) |
| `name` | varchar(255), nullable | Free-text name (fallback) |
| `description` | text, nullable | Not used in voting flow |
| `position_order` | integer, default 0 | Display order on ballot |
| `status` | varchar(255) | `pending` / `approved` / `rejected` / `withdrawn` |
| `image_path_1/2/3` | varchar(255), nullable | Candidate photos |
| `deleted_at` | timestamp | Soft delete |

---

## Routes

All routes live in `routes/organisations.php` inside the authenticated group:

```php
Route::prefix('organisations/{organisation:slug}')
    ->middleware(['auth', 'verified', 'ensure.organisation'])
```

### Voter Self-Service Routes

```
GET  /organisations/{slug}/candidacy/apply
     → CandidacyApplicationController@create
     → organisations.candidacy.create
     Shows election selection + multi-election form

POST /organisations/{slug}/candidacy/apply
     → CandidacyApplicationController@store
     → organisations.candidacy.apply
     Submits the application

GET  /organisations/{slug}/candidacy/list
     → CandidacyApplicationController@index
     → organisations.candidacy.list
     Lists user's own applications

GET  /organisations/{slug}/elections/{election:slug}/candidacy/apply
     → CandidacyApplicationController@applyForm
     → organisations.elections.candidacy.apply
     Election-scoped form (single election, post selection cards)
```

### Admin / Election Officer Routes

```
POST   /organisations/{slug}/elections/{election}/posts/{post}/candidacies
       → CandidacyManagementController@store
       → organisations.elections.candidacies.store

PATCH  /organisations/{slug}/elections/{election}/posts/{post}/candidacies/{candidacy}
       → CandidacyManagementController@update
       → organisations.elections.candidacies.update

DELETE /organisations/{slug}/elections/{election}/posts/{post}/candidacies/{candidacy}
       → CandidacyManagementController@destroy
       → organisations.elections.candidacies.destroy
```

> **Note:** The admin routes use `{election}` as a plain string (not model-bound). The controller calls `Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail()` manually. This avoids the tenant scope issue.

---

## Controllers

### `CandidacyApplicationController`

`app/Http/Controllers/CandidacyApplicationController.php`

#### `create(Organisation $organisation)`

Loads all active real elections for the organisation, eager-loads their posts, and renders `Organisations/CandidacyCreate`. Also passes `appliedElectionIds` — the list of election IDs where the user already has a pending or approved application — so the frontend can disable those options before the user even tries to submit.

```php
$activeElections = Election::withoutGlobalScopes()
    ->where('organisation_id', $organisation->id)
    ->where('type', 'real')
    ->where('status', 'active')
    ->with(['posts' => fn ($q) => $q->withoutGlobalScopes()->orderBy('position_order')])
    ->get();

// Elections where the user already has a pending or approved application
$appliedElectionIds = CandidacyApplication::where('user_id', $user->id)
    ->where('organisation_id', $organisation->id)
    ->whereIn('status', ['pending', 'approved'])
    ->pluck('election_id')
    ->all();

return Inertia::render('Organisations/CandidacyCreate', [
    'organisation'       => $organisation->only('id', 'name', 'slug'),
    'activeElections'    => $activeElections->values(),
    'appliedElectionIds' => $appliedElectionIds,
]);
```

#### `index(Organisation $organisation)`

Returns the authenticated user's own applications for this organisation. Does **not** show other voters' applications.

```php
$applications = CandidacyApplication::where('user_id', $user->id)
    ->where('organisation_id', $organisation->id)
    ->with(['election:id,name', 'post:id,name'])
    ->orderByDesc('created_at')
    ->get();
```

Renders `Organisations/CandidacyList`.

#### `applyForm(Organisation $organisation, Election $election)`

Election-scoped form. Blocks demo elections immediately. Loads posts for the specific election and checks if the user already has an existing application.

```php
// Block demo elections
abort_if($election->type === 'demo', 404);

// Load posts
$posts = Post::withoutGlobalScopes()
    ->where('election_id', $election->id)
    ->where('organisation_id', $organisation->id)
    ->orderBy('position_order')
    ->get();

// Check existing
$existingApplication = CandidacyApplication::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->whereIn('status', ['pending', 'approved', 'rejected'])
    ->latest()
    ->first();
```

Renders `Election/Candidacy/Apply`.

#### `store(Request $request, Organisation $organisation)`

The main submission handler. Key steps:

1. **Validate** — supporter_name, proposer_name required; photo optional (image only, max 5 MB)
2. **Verify election** — must be active, must belong to this organisation
3. **Verify post** — must belong to the election
4. **Duplicate check** — one application per election (blocks regardless of which post):

```php
$existing = CandidacyApplication::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->whereIn('status', ['pending', 'approved'])
    ->exists();

if ($existing) {
    return back()->with('error', '...');
}
```

5. **DB transaction** — store photo, create record, redirect

Photo storage path: `candidacy/{organisation_id}/{user_id}/photos/` on the `public` disk.

---

### `CandidacyManagementController`

`app/Http/Controllers/Election/CandidacyManagementController.php`

Handles admin CRUD for `Candidacy` (ballot entries). Protected by `ElectionPolicy::managePosts()`.

| Method | What it does |
|--------|-------------|
| `store()` | Creates a new ballot candidate for a post. Accepts user_id (linked member) or free-text name. Up to 3 images. Status is set to `approved` directly — no review needed for staff-created entries. |
| `update()` | Edits candidate details, status, position order, images. |
| `destroy()` | Soft-deletes the candidacy. |

**All three methods** call `abort_if($electionModel->type === 'demo', 404)` — admin candidacy management is also unavailable for demo elections.

Authorization check pattern:
```php
$this->authorize('managePosts', $electionModel);
```

---

## Vue Pages

### Page Map

```
VoterHub (/voter-hub)
    └── "Apply for Candidacy" card
        ├── → CandidacyCreate.vue   (/candidacy/apply)
        │       uses CandidacyApplicationForm.vue
        └── → Apply.vue             (/elections/{slug}/candidacy/apply)

CandidacyList.vue (/candidacy/list)
    └── Shows user's own applications with status

Election/Posts/Index.vue (/elections/{slug}/posts)
    └── Admin page — uses CandidacyManagementController
```

### `Organisations/CandidacyCreate.vue`

**Props:** `organisation`, `activeElections`, `appliedElectionIds`

Shows an empty state if no active elections exist. Otherwise mounts `CandidacyApplicationForm` passing all elections and the `appliedElectionIds` list. The voter selects which election to apply for from a dropdown; posts cascade from that selection.

### `Organisations/Partials/CandidacyApplicationForm.vue`

**Props:** `organisation`, `activeElections`, `appliedElectionIds`

The main application form with five sections:

| Section | Fields |
|---------|--------|
| 01 Position Selection | Election dropdown → Post dropdown (cascades) |
| 02 Nomination Details | `proposer_name`, `supporter_name` |
| 03 Election Statement | `manifesto` (textarea, optional, max 5 000 chars) |
| 04 Candidate Photo | File input (JPG/PNG, max 5 MB), circular preview |
| 05 Declaration | T&C checkbox — blocks submission if unchecked |

Elections listed in `appliedElectionIds` are rendered as disabled `<option>` elements with an `"— Already applied"` label suffix. The voter cannot select them, so the one-per-election rule is enforced at the UI level before any submission occurs.

Submits via `router.post()` using `FormData` (required for file upload). On success, resets all fields and revokes the photo preview URL to free memory.

### `Organisations/CandidacyList.vue`

**Props:** `organisation`, `applications`

Tabular view of the voter's own applications. Each row shows election name, position, submission date, and a colour-coded status badge. If a manifesto was provided, a toggle shows/hides it inline.

### `Election/Candidacy/Apply.vue`

**Props:** `organisation`, `election`, `posts`, `existingApplication`

The election-scoped page — rendered when the voter arrives from a specific election context. Has a civic register aesthetic (parchment background, official seal header, NOMINATION watermark).

**Two states:**

1. **No existing application** — shows clickable post cards (select one), then the nomination form (supporter, proposer, manifesto, photo, declaration)
2. **Existing application** — shows the application status card (pending / approved / rejected) with photo thumbnail. No form is shown; a voter cannot apply twice.

When a post card is clicked, `form.post_id` is updated. The submit button calls `submitForm()` which builds a `FormData` and calls `router.post()` to `organisations.candidacy.apply`.

---

## Business Rules

### One Application Per Election

A voter may apply for **at most one position per election**. This applies even if they try to apply for a different post in the same election.

The rule is enforced at **two layers**:

**1. UI layer — `CandidacyApplicationForm.vue`**

`CandidacyApplicationController::create()` computes the set of elections the user has already applied to:

```php
$appliedElectionIds = CandidacyApplication::where('user_id', $user->id)
    ->where('organisation_id', $organisation->id)
    ->whereIn('status', ['pending', 'approved'])
    ->pluck('election_id')
    ->all();
```

This list is passed as the `appliedElectionIds` prop. The form renders those elections as disabled `<option>` elements:

```html
<option :disabled="appliedElectionIds.includes(e.id)">
  {{ e.name }}{{ appliedElectionIds.includes(e.id) ? ' — Already applied' : '' }}
</option>
```

The voter cannot select a blocked election, so the form can never be submitted for it.

**2. Server layer — `CandidacyApplicationController::store()`**

Even if the UI is bypassed, the server rejects duplicates:

```php
$existing = CandidacyApplication::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->whereIn('status', ['pending', 'approved'])
    ->exists();

if ($existing) {
    return back()->with('error', 'You have already submitted an application for this election. Only one application per election is allowed.');
}
```

**Why `['pending', 'approved']` and not `['pending', 'approved', 'rejected']`?**

A `rejected` application does not block re-application by design. If an officer rejects an application for post A, they can tell the voter to re-apply for post B. Blocking on rejected status would make that workflow impossible.

Verified by two test cases in `CandidacyApplicationTest`:
- `test_cannot_apply_twice_for_same_election`
- `test_cannot_apply_for_different_post_in_same_election`

### Demo Elections are Blocked

`applyForm()` aborts with 404 if `$election->type === 'demo'`. The admin `CandidacyManagementController` also blocks demo elections. Demo elections use `DemoCandidacy` records seeded in the database — no application flow exists.

### Photo Requirements

- Optional field
- Accepted types: `jpg`, `jpeg`, `png` (no PDFs, no GIFs)
- Maximum size: 5 MB (5 120 KB)
- Validated on both client (JavaScript) and server (Laravel validation rule)

### Membership Required

`ensure.organisation` middleware runs on all routes. Non-members are redirected to the dashboard. This is separate from role checks — any member role (voter, officer, chief) can submit an application.

### T&C Declaration

The `CandidacyApplicationForm` and `Apply.vue` both require the declaration checkbox to be ticked before the submit button fires. This is client-side only — not a server validation field.

---

## Route Binding — The Tenant Scope Problem

This section explains a non-obvious architectural decision that will bite you if you add new election-scoped routes.

### The Problem

The `Election` model uses the `BelongsToTenant` trait, which adds a global scope:

```php
// Simplified from BelongsToTenant trait
static::addGlobalScope('tenant', function (Builder $query) {
    $orgId = session('current_organisation_id');
    $query->where('elections.organisation_id', $orgId);
});
```

Laravel's `SubstituteBindings` middleware resolves route model bindings. In the middleware priority list, `SubstituteBindings` runs **after** `auth` but **before** custom route middleware like `ensure.organisation`.

The sequence for an authenticated non-member hitting `/organisations/{org}/elections/{election}/candidacy/apply`:

```
1. auth               → passes (user is logged in)
2. SubstituteBindings → resolves {election:slug}
                        BUT session('current_organisation_id') is null
                        BelongsToTenant scope filters to platform org
                        Election not found → 404  ❌
3. ensure.organisation → never reached
```

Without the fix, non-members get 404 instead of a redirect, and the test `test_non_member_is_redirected` fails.

### The Fix

**`Organisation::resolveChildRouteBinding()`** (`app/Models/Organisation.php`)

Laravel calls this method on the parent model when resolving a scoped child binding. Overriding it for `election` bypasses the global scope:

```php
public function resolveChildRouteBinding($childType, $value, $field = null)
{
    if (strtolower($childType) === 'election') {
        return Election::withoutGlobalScopes()
            ->where('slug', $value)
            ->first();
    }

    return parent::resolveChildRouteBinding($childType, $value, $field);
}
```

**`Election::resolveRouteBinding()`** (`app/Models/Election.php`)

For non-scoped election bindings (where election is a root URL parameter):

```php
public function resolveRouteBinding($value, $field = null): ?self
{
    return static::withoutGlobalScopes()
        ->where($field ?? $this->getRouteKeyName(), $value)
        ->first();
}
```

**Security:** Bypassing the global scope at binding time is safe because every controller method explicitly validates `organisation_id` ownership:

```php
// In applyForm()
abort_if(! $role, 403);

// In store()
$election = Election::withoutGlobalScopes()
    ->where('id', $validated['election_id'])
    ->where('organisation_id', $organisation->id)  // ← ownership enforced here
    ->where('status', 'active')
    ->firstOrFail();
```

### If You Add New Election-Scoped Routes

Any new route using `{organisation:slug}/elections/{election:slug}/...` will benefit from the existing `resolveChildRouteBinding` fix automatically. You do **not** need to do anything extra.

If you add a route where `{election}` is a root parameter (not nested under `{organisation}`), `Election::resolveRouteBinding()` handles it.

---

## Form Submission Mechanics

All candidacy forms submit to:

```
POST /organisations/{slug}/candidacy/apply
     → route name: organisations.candidacy.apply
```

The form uses `FormData` (required for photo upload) and Inertia's `router.post()`:

```javascript
// From CandidacyApplicationForm.vue and Apply.vue
const data = new FormData()
data.append('election_id',    form.value.election_id)
data.append('post_id',        form.value.post_id)
data.append('supporter_name', form.value.supporter_name)
data.append('proposer_name',  form.value.proposer_name)
data.append('manifesto',      form.value.manifesto)
if (photoFile.value) data.append('photo', photoFile.value)

router.post(route('organisations.candidacy.apply', organisation.slug), data, {
    forceFormData: true,
    preserveScroll: true,
    onError(errs)  { errors.value = errs },
    onFinish()     { isSubmitting.value = false },
})
```

**Why `router.post()` and not `fetch()`:** Inertia handles CSRF automatically, interprets the backend redirect response correctly, and delivers flash messages via `page.props.flash`. Using raw `fetch` would receive an HTML redirect instead of a JSON Inertia response.

---

## Status Flow

### CandidacyApplication (Voter-Submitted)

```
[voter submits]
      │
      ▼
  ┌─────────┐
  │ pending │  ← Initial state. Waiting for officer review.
  └────┬────┘
       │
   ┌───┴───┐
   │       │
   ▼       ▼
┌──────┐ ┌──────────┐
│appro-│ │ rejected │
│ ved  │ └──────────┘
└──────┘
  Officer reviewed and
  accepted application.        Officer reviewed and
  Voter is now a candidate      declined application.
  candidate (officer            Voter may re-apply if
  still must create             not blocked by pending/
  a Candidacy record).          approved record.
```

**Re-application after rejection:** Because the duplicate check is `whereIn('status', ['pending', 'approved'])`, a rejected application does NOT prevent re-application. This allows an officer to reject an application for post A and then tell the voter to re-apply for post B.

### Candidacy (Ballot Entry)

```
pending → approved → appears on voting form
        → rejected → does not appear
        → withdrawn → removed from ballot
```

Staff-created candidacies are created with `approved` status by default — the review step is skipped for manual entries.

---

## File Map

```
app/
├── Http/Controllers/
│   ├── CandidacyApplicationController.php     ← Voter self-service
│   └── Election/
│       └── CandidacyManagementController.php  ← Admin ballot management
├── Models/
│   ├── CandidacyApplication.php               ← Application record
│   ├── Candidacy.php                          ← Ballot entry record
│   ├── Organisation.php                       ← resolveChildRouteBinding fix
│   ├── Election.php                           ← resolveRouteBinding fix
│   └── Post.php                               ← Election position model

database/migrations/
├── 2026_03_24_205317_create_candidacy_applications_table.php
└── 2026_03_24_224518_add_photo_to_candidacy_applications_table.php

routes/
└── organisations.php                          ← All candidacy routes (lines 48–91)

resources/js/Pages/
├── Organisations/
│   ├── CandidacyCreate.vue                    ← Election selection page
│   ├── CandidacyList.vue                      ← User's applications list
│   └── Partials/
│       └── CandidacyApplicationForm.vue       ← Full form component
└── Election/
    └── Candidacy/
        └── Apply.vue                          ← Election-scoped form page

tests/Feature/
├── CandidacyApplicationTest.php               ← 10 submission tests
├── CandidacyApplicationMigrationTest.php      ← Schema tests
└── ElectionCandidacyApplyPageTest.php         ← 6 page render tests
```

---

## Tests

### `CandidacyApplicationTest` (10 tests)

| Test | What it verifies |
|------|-----------------|
| `test_guest_cannot_submit_application` | Unauthenticated POST → redirect to login |
| `test_non_member_cannot_submit_application` | Non-member POST → redirect (ensure.organisation) |
| `test_member_can_submit_valid_application` | Happy path — record created with `status=pending` |
| `test_application_requires_supporter_name` | Missing supporter_name → validation error |
| `test_application_requires_proposer_name` | Missing proposer_name → validation error |
| `test_cannot_apply_twice_for_same_election` | Second application → session error |
| `test_cannot_apply_for_different_post_in_same_election` | Different post, same election → blocked |
| `test_photo_is_uploaded_and_stored` | Photo saved to `public` disk at expected path |
| `test_photo_must_be_image` | PDF upload → validation error |
| `test_voter_hub_includes_my_applications` | `myApplications` prop present on voter hub page |

### `ElectionCandidacyApplyPageTest` (6 tests)

| Test | What it verifies |
|------|-----------------|
| `test_guest_is_redirected_to_login` | GET → redirect to login |
| `test_non_member_is_redirected` | GET by non-member → redirect (validates the resolveChildRouteBinding fix) |
| `test_member_can_view_page` | 200 OK, renders `Election/Candidacy/Apply` component |
| `test_page_exposes_posts_for_this_election` | `posts` prop has correct count |
| `test_existing_application_is_exposed_in_props` | `existingApplication.status` matches DB value |
| `test_demo_election_returns_404` | Demo election → 404 |

### Running the Tests

```bash
# Run all candidacy tests
php artisan test tests/Feature/CandidacyApplicationTest.php \
                  tests/Feature/ElectionCandidacyApplyPageTest.php \
                  tests/Feature/CandidacyApplicationMigrationTest.php

# Expected: 18 tests, all passing
```
