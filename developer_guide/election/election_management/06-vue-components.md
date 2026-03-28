# Vue Components

All components follow **Inertia 2.0** patterns: Composition API, `router.post()`, no raw `fetch()`.

---

## Management.vue

**Path:** `resources/js/Pages/Election/Management.vue`
**Route:** `GET /elections/{election}/management`
**Access:** Chief and deputy only

### Props

| Prop | Type | Source |
|------|------|--------|
| `election` | Object | Election model (all fields) |
| `stats` | Object | `election->voter_stats` (ElectionMembership) |
| `canPublish` | Boolean | `auth()->user()->can('publishResults', $election)` |

### Sections

| Section | Visible when | Action |
|---------|-------------|--------|
| Flash messages | `page.props.flash.success/error` | Auto |
| Current Status | Always | Read-only |
| Voting Statistics | `stats` is not empty | Read-only |
| Voting Control | Always | `openVoting()` / `closeVoting()` |
| Voter Management | Always | "Coming soon" note |
| Result Management | `canPublish === true` | `publishResults()` / `unpublishResults()` |

### Voting Status

The component derives voting state from `election.status`:

```js
const isVotingActive = computed(() => props.election.status === 'active')
```

There is no separate `voting_period_active` field — `status` is the canonical source of truth.

### Actions (Inertia 2.0)

```js
const openVoting = () => {
    if (!confirm('...')) return
    isLoading.value = true
    router.post(route('elections.open-voting', { election: props.election.id }), {}, {
        preserveScroll: true,
        onFinish: () => { isLoading.value = false }
    })
}
```

All actions use `onFinish` (not `onSuccess`/`onError` separately) to reset loading state regardless of outcome. Flash messages from the server (`back()->with('success', ...)`) are displayed via `page.props.flash`.

### Why Not Bulk Voter Buttons

The `bulkApproveVoters` route requires `voter_ids[]` — a list of specific `election_memberships.id` values. Without a voter list UI to select from, the UI shows a "coming soon" message rather than a broken button.

---

## Viewboard.vue

**Path:** `resources/js/Pages/Election/Viewboard.vue`
**Route:** `GET /elections/{election}/viewboard`
**Access:** Any active officer (chief, deputy, commissioner)

### Props

| Prop | Type | Source |
|------|------|--------|
| `election` | Object | Election model |
| `stats` | Object | `election->voter_stats` |
| `readonly` | Boolean | Always `true` from controller |

### Sections

| Section | Always visible |
|---------|---------------|
| Current Status (3 cards) | ✅ |
| Voting Statistics | ✅ (when stats present) |
| Result Viewing link | ✅ (link only shows when `results_published`) |

### No Write Operations

The old `Viewboard.vue` contained `startVoting()`, `endVoting()`, `bulkApproveVoters()`, `bulkDisapproveVoters()` — all using `fetch()`. These have been removed entirely. Write operations belong in `Management.vue` only.

---

## Common Pattern: Flash Messages

Both components display server flash messages:

```vue
<div v-if="page.props.flash?.success" class="bg-green-50 ...">
    <p class="text-green-800">✅ {{ page.props.flash.success }}</p>
</div>
```

The controller sends flash via:
```php
return back()->with('success', 'Results published.');
```

Inertia automatically merges flash into `page.props` on the next page visit.

---

## Generating Route URLs in Vue

Use the `route()` helper (from `ziggy-js`, already installed):

```js
// With election object in scope
route('elections.publish', { election: props.election.id })
// → /elections/uuid-here/publish

route('elections.management', { election: props.election.id })
// → /elections/uuid-here/management
```

The `{election}` route parameter binds by UUID (the election's primary key).

---

---

## Management.vue — Redesign (March 2026)

Management.vue was fully redesigned using the shared component system. The visual structure changed from a single scrolling page to distinct `SectionCard` blocks.

**Sections (in order):**

| Section | Visibility |
|---|---|
| Page header (election name + StatusBadge) | Always |
| Flash messages | On flash |
| Activation banner (SectionCard `warning` variant) | `election.status === 'planned'` |
| Current status (Election System + Results cards) | Always |
| Voting Statistics (3 summary + breakdown grid) | When stats present |
| Voter empty state | When `stats.total_memberships === 0` |
| Voting Period Control (Open/Close) | Always |
| Voter Management (stats chips + Manage link) | Always |
| Result Management (Publish/Unpublish) | `canPublish === true` |

All buttons replaced with `ActionButton` component (variant-based: success, danger, warning, outline).

---

## Shared Components (`resources/js/Components/`)

Four reusable components were created alongside the Management and Show redesigns.

### StatusBadge.vue

Renders a coloured dot + label for election status values.

```vue
<StatusBadge status="planned" size="sm" />
<StatusBadge status="active" size="md" />
```

| Status | Colours |
|---|---|
| `planned` | amber-50 bg, amber-700 text, animated dot |
| `active` | emerald-50 bg, emerald-700 text, pulsing dot |
| `completed` | slate-100 bg, slate-600 text |
| `archived` | gray-100 bg, gray-400 text |

Props: `status` (required String), `size` ('sm' | 'md', default 'sm')

---

### ActionButton.vue

Consistent button with variant colours, loading spinner, disabled state. Can render as `<a>` when `href` is set.

```vue
<ActionButton variant="warning" size="md" :loading="isActivating" @click="activate">
  Activate
</ActionButton>

<ActionButton variant="outline" size="sm" href="/some/url">
  View
</ActionButton>
```

| Variant | Colours |
|---|---|
| `primary` | blue-600 bg, white text |
| `success` | emerald-600 bg, white text |
| `warning` | amber-500 bg, white text |
| `danger` | red-600 bg, white text |
| `outline` | white bg, gray-700 text, gray-300 border |
| `ghost` | transparent bg, gray-600 text |

Props: `variant`, `size` ('sm' | 'md' | 'lg'), `loading`, `disabled`, `href`, `type`

---

### EmptyState.vue

Centred empty state for zero-data scenarios.

```vue
<EmptyState title="No elections yet" description="Create your first election to get started.">
  <template #icon>
    <svg .../>
  </template>
  <template #action>
    <a href="...">Create Election</a>
  </template>
</EmptyState>
```

Props: `title` (required), `description`
Slots: `icon` (SVG), `action` (CTA button/link)

---

### SectionCard.vue

White card wrapper with consistent padding, border radius, and optional header.

```vue
<SectionCard padding="lg" variant="warning">
  content here
</SectionCard>
```

| Variant | Background |
|---|---|
| `default` | white, slate border |
| `warning` | amber-50, amber border |
| `success` | emerald-50, emerald border |
| `info` | blue-50, blue border |

Props: `title`, `subtitle`, `variant`, `padding` ('sm' | 'md' | 'lg')
Slots: `default` (content), `icon` (header icon), `actions` (header right)

---

## Organisation Show.vue — Role-Based Structure

`Show.vue` was restructured into 12 role-gated sections. See `10-role-based-organisation-dashboard.md` for the full section map and permission logic.

The key pattern — all `v-if` conditions use server-computed props, not client-side role detection:

```vue
<!-- ✅ Correct — uses server-computed prop -->
<section v-if="canManage">...</section>

<!-- ❌ Wrong — never derive from userRole string client-side -->
<section v-if="userRole === 'owner'">...</section>
```

---

## ElectionCard.vue

**Path:** `resources/js/Pages/Organisations/Partials/ElectionCard.vue`

Card displayed in the elections grid on the organisation dashboard. The action area has three states based on props:

```vue
<ElectionCard
  :election="election"
  :activating-id="activatingId"
  :can-activate="canActivateElection && election.status === 'planned'"
  :can-manage="canManage || isChief || isDeputy"
  :is-readonly="isCommissioner || (!canManage && !isOfficer)"
  @activate="activateElection"
/>
```

| Props state | Action rendered |
|---|---|
| `canActivate=true` | Amber "Activate" button (ActionButton warning) |
| `canManage=true, isReadonly=false` | Blue "Manage →" link |
| `isReadonly=true` | Grey "View only" badge (non-interactive) |

---

## Inertia 2.0 Compliance Checklist

| Rule | Management.vue | Viewboard.vue | Show.vue |
|------|:---:|:---:|:---:|
| No raw `fetch()` | ✅ | ✅ | ✅ |
| Uses `router.post()` | ✅ | N/A | ✅ |
| No manual CSRF token | ✅ | ✅ | ✅ |
| Uses `preserveScroll: true` | ✅ | N/A | ✅ |
| Flash via `page.props.flash` | ✅ | N/A | ✅ |
| Composition API (`<script setup>`) | ✅ | ✅ | ✅ |
| No `this.$inertia.reload()` | ✅ | ✅ | ✅ |
