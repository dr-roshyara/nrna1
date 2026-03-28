# 01 — Voter Ballot Section

**File:** `resources/js/Pages/Organisations/Show.vue` (section ⑦)

---

## What It Is

The "Voting Open" section appears at the top of the organisation dashboard whenever there is at least one active election. It replaces the old "Active Election Notice" which was hidden from admins and had no voting CTA.

---

## Visibility Rule

```html
<section v-if="activeElections.length > 0">
```

**Always visible** to any authenticated org member when active elections exist. No role gating. Previously this was `v-if="!canManage && !isOfficer && activeElections.length > 0"` which hid it from owners, admins, and officers.

---

## Per-Election Card States

Each active election renders a card. The card's appearance and CTA depend on `voterStatus(election.id)`:

```js
const voterStatus = (electionId) => {
  const m = props.voterMemberships[electionId]
  if (!m || m.status === 'removed') return 'ineligible'
  if (m.has_voted) return 'voted'
  if (m.role === 'voter' && m.status === 'active') return 'eligible'
  return 'ineligible'
}
```

| `voterStatus` value | Condition | Card style | CTA shown |
|---------------------|-----------|-----------|-----------|
| `eligible` | Active voter, `has_voted=false` | Green border + bg | **Vote Now** button → `elections.show` |
| `voted` | `has_voted=true` | Grey bg | **Vote Cast** ✓ badge |
| `ineligible` | No membership, or `status=removed` | White bg | **Not a voter** grey badge |

Additionally, if `canManage || isOfficer`, a small **View →** link always appears alongside whichever badge/button is shown, so admins can inspect the election page.

---

## Vote Now Link

```html
<a :href="route('elections.show', election.slug)" ...>Vote Now</a>
```

This uses `election.slug` (already included in the elections array from the controller) to build a direct URL to `GET /elections/{slug}` — the `ElectionVotingController@show` endpoint which renders `Election/Show.vue`.

---

## Data Source

The `voterMemberships` prop is a plain object keyed by `election_id`:

```js
// Example prop value
{
  "018e4f2a-...": { role: "voter", status: "active", has_voted: false },
  "018e4f2b-...": { role: "voter", status: "inactive", has_voted: true }
}
```

An election ID not present in the object means the current user has no membership → `ineligible`.

See [02-voter-memberships-prop.md](./02-voter-memberships-prop.md) for how the backend builds this.

---

## What Happens After "Vote Now"

`Vote Now` links to `elections.show` which renders `Election/Show.vue` — the election's own page. From there the voter can read details and click **Start Voting** to enter the real voting flow (code entry → ballot → submission).

```
/organisations/{slug}          ← voter sees "Vote Now"
    ↓
/elections/{election-slug}     ← Election/Show.vue (civic ballot page)
    ↓ POST /elections/{slug}/start
/slug/{vslug}/code/create      ← existing voting workflow
```
