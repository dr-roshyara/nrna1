# 10 — Voter Management UI (`Elections/Voters/Index.vue`)

**Route:** `GET /organisations/{slug}/elections/{id}/voters`
**Controller:** `app/Http/Controllers/ElectionVoterController.php`
**Vue:** `resources/js/Pages/Elections/Voters/Index.vue`

---

## Purpose

Election-specific voter management page. Allows election chiefs, deputies, and org owners/admins to:

- Assign org members as voters (searchable checklist or UUID fallback)
- Bulk-assign multiple members at once
- Approve, propose suspension, confirm suspension, cancel proposal, remove voters
- Export the voter register as CSV
- Search and filter the voter list

---

## Layout

Two-panel layout with no hero section (avoids double-header with `PublicDigitHeader`):

```
┌──────────────────┬──────────────────────────────────────────────────┐
│  SIDEBAR (dark)  │  MAIN CONTENT (parchment)                        │
│  #0d1117         │  #f5f4f0                                         │
│                  │                                                  │
│  Election name   │  Flash messages                                  │
│  Status badges   │  Search + status filter bar                      │
│  Voter stats     │  Voter register table                            │
│  Assign members  │  Pagination                                      │
│  Export CSV      │                                                  │
└──────────────────┴──────────────────────────────────────────────────┘
```

---

## Props from Controller

| Prop | Type | Description |
|------|------|-------------|
| `election` | Object | `id`, `name`, `type`, `status` |
| `organisation` | Object | `id`, `slug`, `name` |
| `voters` | Paginator | `ElectionMembership` rows with `user` relation |
| `stats` | Object | `active_voters`, `eligible_voters`, `by_status` |
| `unassignedMembers` | Array | Org members not yet in this election |
| `filters` | Object | `search`, `status` (current query params) |

---

## Authorization

`ElectionPolicy::manageVoters()` → delegates to `manageSettings()`.

**Who can access:**
- `ElectionOfficer` with role `chief` or `deputy` (status `active`)
- `UserOrganisationRole` with role `owner` or `admin`

`view` (index, export) requires any active officer or org owner/admin.

---

## Voter Register Table Columns

| Column | Notes |
|--------|-------|
| `#` | Row number (page-aware) |
| Voter | Avatar initial, name, email |
| Status | Status pill — see states below |
| Voted | ✓ Cast badge if `has_voted=true` |
| Assigned | Formatted date |
| Actions | Context-sensitive — see below |

### Status States

| DB value | Pill label | Pill colour | Override |
|----------|-----------|-------------|---------|
| `active` | Active | Green | If `suspension_status=proposed` → "Pending Suspension" (amber) |
| `inactive` | Suspended | Amber | — |
| `invited` | Invited | Blue | — |
| `removed` | Removed | Red | — |

---

## Row Actions

| Condition | Button shown |
|-----------|-------------|
| `status !== active && status !== removed && !has_voted` | **Approve** |
| `status === active && suspension_status !== proposed && !has_voted` | **Propose** (suspension) |
| `suspension_status === proposed && proposed_by !== currentUser && !has_voted` | **Confirm** (suspension) |
| `suspension_status === proposed && proposed_by === currentUser && !has_voted` | **Cancel** |
| `status !== removed && suspension_status !== proposed && !has_voted` | **Remove** |
| `has_voted` | 🔒 lock icon — no actions |

---

## Assign Panel (Sidebar)

Searchable checkbox list of `unassignedMembers`. Selecting one or more and clicking **Assign** fires `bulkStore`. A collapsed `<details>` section provides a UUID text input fallback via `store`.

---

## Flash Messages

| Key | Source |
|-----|--------|
| `flash.success` | Single-action responses |
| `flash.error` | Guard rejections |
| `flash.bulk_result` | `{ success, already_existing, invalid }` from `bulkStore` |
