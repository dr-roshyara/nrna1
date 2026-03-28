# Role-Based Organisation Dashboard

## Overview

The Organisation Show page (`/organisations/{slug}`) is the main landing page for all user types. Different sections are visible depending on the authenticated user's role in the organisation.

This was implemented to replace a single flat layout that showed everything to everyone.

---

## Role Hierarchy

| Role Source | Role Value | Who |
|---|---|---|
| `UserOrganisationRole` | `owner` | Organisation creator/owner |
| `UserOrganisationRole` | `admin` | Appointed admin |
| `UserOrganisationRole` | `voter` | Regular member |
| `ElectionOfficer` | `chief` | Election chief (active) |
| `ElectionOfficer` | `deputy` | Election deputy (active) |
| `ElectionOfficer` | `commissioner` | Election commissioner (active) |

A user can hold a `UserOrganisationRole` **and** be an `ElectionOfficer` simultaneously. For example, an admin who is also appointed as chief will have both `canManage=true` and `isChief=true`.

---

## Permission Flags Computed in Controller

`OrganisationController::show()` computes 9 permission flags and passes them to the Inertia view:

```php
// From UserOrganisationRole
$canManage          = in_array($userRole, ['owner', 'admin']);
$canCreateElection  = in_array($userRole, ['owner', 'admin']);

// From ElectionOfficer (active record only)
$officer        = ElectionOfficer::where('user_id', $user->id)
    ->where('organisation_id', $organisation->id)
    ->where('status', 'active')
    ->first();

$isOfficer      = !is_null($officer);
$isChief        = $isOfficer && $officer->role === 'chief';
$isDeputy       = $isOfficer && $officer->role === 'deputy';
$isCommissioner = $isOfficer && $officer->role === 'commissioner';

$canActivateElection = $isChief || $isDeputy;
$canManageVoters     = $isChief || $isDeputy;
$canPublishResults   = $isChief;
```

All flags are passed as Inertia props:

```php
return inertia('Organisations/Show', [
    // ... organisation, stats, demoStatus, officers, orgMembers, elections ...
    'canManage'           => $canManage,
    'canCreateElection'   => $canCreateElection,
    'canActivateElection' => $canActivateElection,
    'canManageVoters'     => $canManageVoters,
    'canPublishResults'   => $canPublishResults,
    'userRole'            => $userRole,
    'isOfficer'           => $isOfficer,
    'isChief'             => $isChief,
    'isDeputy'            => $isDeputy,
    'isCommissioner'      => $isCommissioner,
]);
```

---

## Section Visibility Matrix

| # | Section | Owner/Admin | Chief | Deputy | Commissioner | Voter/Member |
|---|---|:---:|:---:|:---:|:---:|:---:|
| ① | Organisation Header | ✅ | ✅ | ✅ | ✅ | ✅ |
| ② | Role Context Banner | — | ✅ green | ✅ blue | ✅ slate | — |
| ③ | Stats Grid | ✅ | ✅ | ✅ | ✅ | ✅ |
| ④ | Quick Actions | ✅ | — | — | — | — |
| ⑤ | Demo Results | ✅ | ✅ | ✅ | ✅ | ✅ |
| ⑥ | Demo Setup | ✅ (when no demo) | — | — | — | — |
| ⑦ | Active Election Notice | — | — | — | — | ✅ |
| ⑧ | Elections Grid | ✅ full | ✅ + Activate | ✅ + Activate | ✅ view only | ✅ view only |
| ⑨ | Officer Management | ✅ | — | — | — | — |
| ⑩ | Voter Management | ✅ | ✅ | ✅ | — | — |
| ⑪ | Results Management | ✅ | ✅ | — | — | — |
| ⑫ | Support Section | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## Section Details

### ② Role Context Banner

Shown only to `ElectionOfficer` users. Colour-coded by role:

- **Chief** → `bg-emerald-50 border-emerald-200` — "You are signed in as Election Chief"
- **Deputy** → `bg-blue-50 border-blue-200` — "You are signed in as Election Deputy"
- **Commissioner** → `bg-slate-50 border-slate-200` — "You are signed in as Election Commissioner"

Condition: `v-if="isOfficer"`

---

### ⑦ Active Election Notice

A blue gradient banner shown **only to regular members** (non-admin, non-officer) when at least one active election exists. Displays election name and date range for context.

Condition: `v-if="!canManage && !isOfficer && activeElections.length > 0"`

---

### ⑧ Elections Grid — ElectionCard Actions

`ElectionCard.vue` receives three permission props:

| Prop | Type | Purpose |
|---|---|---|
| `canActivate` | Boolean | Show Activate button (chief/deputy, planned elections only) |
| `canManage` | Boolean | Show Manage → link (admin/chief/deputy) |
| `isReadonly` | Boolean | Show "View only" badge (commissioner, member) |

```vue
<ElectionCard
  :can-activate="canActivateElection && election.status === 'planned'"
  :can-manage="canManage || isChief || isDeputy"
  :is-readonly="isCommissioner || (!canManage && !isOfficer)"
  @activate="activateElection"
/>
```

Card renders:
- `canActivate` → amber Activate button
- `canManage && !isReadonly` → blue Manage → link
- `isReadonly` → grey "View only" badge (no link)

---

### ⑨ Officer Management

Shown only to `canManage` (owner/admin). Lists active officers as pill chips with role badge. Links to the full officer management page at `/organisations/{slug}/election-officers`.

Empty state shown when no officers are appointed yet.

---

### ⑩ Voter Management

Shown to `canManageVoters || canManage` — i.e. owner, admin, chief, deputy.

Shows three summary stats (Total Members, Active, Live Elections) and a "Manage →" link to the voter list for the first election. Commissioner and regular members do not see this section.

---

### ⑪ Results Management

Shown when `(canPublishResults || canManage) && completedElections.length > 0`.

Lists completed elections as link chips. Each chip shows whether results are published or not. Links directly to the election's management page for publish/unpublish actions.

Deputy officers cannot publish results — they see neither this section nor the publish button in Management.vue.

---

## Vue Props in Show.vue

```js
const props = defineProps({
  organisation:        { type: Object, required: true },
  stats:               { type: Object, default: () => ({}) },
  demoStatus:          Object,
  canManage:           Boolean,
  canCreateElection:   Boolean,
  canActivateElection: Boolean,   // chief or deputy
  canManageVoters:     Boolean,   // chief or deputy
  canPublishResults:   Boolean,   // chief only
  userRole:            String,    // owner | admin | voter | null
  isOfficer:           Boolean,
  isChief:             Boolean,
  isDeputy:            Boolean,
  isCommissioner:      Boolean,
  officers:            { type: Array, default: () => [] },
  orgMembers:          { type: Array, default: () => [] },
  elections:           { type: Array, default: () => [] },
})
```

Computed helpers inside Show.vue:

```js
const activeElections    = computed(() => props.elections.filter(e => e.status === 'active'))
const completedElections = computed(() => props.elections.filter(e => e.status === 'completed'))
```

---

## Shared Components Used

All new components live in `resources/js/Components/`:

| Component | Props | Purpose |
|---|---|---|
| `StatusBadge.vue` | `status`, `size` | Coloured dot + label badge for election statuses |
| `ActionButton.vue` | `variant`, `size`, `loading`, `disabled`, `href` | Consistent button; renders `<a>` or `<button>` |
| `EmptyState.vue` | `title`, `description` | Centred empty state with icon + action slots |
| `SectionCard.vue` | `title`, `subtitle`, `variant`, `padding` | White card with icon, actions slots |

`ElectionCard.vue` is a page-scoped partial in `resources/js/Pages/Organisations/Partials/`.

---

## Files Changed

| File | Change |
|---|---|
| `app/Http/Controllers/OrganisationController.php` | Added ElectionOfficer lookup; added 9 new Inertia props |
| `resources/js/Pages/Organisations/Show.vue` | Full rewrite with 12 role-gated sections |
| `resources/js/Pages/Organisations/Partials/ElectionCard.vue` | Added `canManage`, `isReadonly` props; three-state action area |
| `resources/js/Components/StatusBadge.vue` | New — election status badge |
| `resources/js/Components/ActionButton.vue` | New — consistent button component |
| `resources/js/Components/EmptyState.vue` | New — empty state component |
| `resources/js/Components/SectionCard.vue` | New — card wrapper component |

---

## Security Note

The permission flags are computed server-side and are **not** user-supplied. The `ElectionOfficer` lookup enforces `status = 'active'` — pending or rejected officers receive no elevated permissions.

The Vue `v-if` gates are a **UX convenience only**. All sensitive actions (activate, publish, manage voters) are protected by `ElectionPolicy` in the controller via `$this->authorize()`. Removing a `v-if` in the browser would show a broken link or receive a 403.
