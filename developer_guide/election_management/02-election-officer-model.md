# Election Officer Model

## Purpose

`ElectionOfficer` represents a person who has been **formally appointed** to manage an election for a specific organisation. It is **not** a user role (like Spatie `hasRole`) — it is a dedicated appointment record with its own lifecycle.

An officer record:
- Is created when an organisation admin appoints someone
- Starts with `status = 'pending'` (invitation sent, not yet accepted)
- Becomes `status = 'active'` when the invited user accepts via email link
- Can be soft-deleted to remove access while preserving audit history

---

## Database Schema

**Table:** `election_officers`

| Column | Type | Notes |
|--------|------|-------|
| `id` | uuid | Primary key |
| `organisation_id` | uuid | FK → organisations |
| `user_id` | uuid | FK → users |
| `role` | enum | `chief`, `deputy`, `commissioner` |
| `status` | enum | `pending`, `active`, `inactive` |
| `appointed_by` | uuid | FK → users (who appointed) |
| `appointed_at` | timestamp | When appointed |
| `accepted_at` | timestamp | When they accepted the invitation |
| `deleted_at` | timestamp | Soft delete |

**Unique constraint:** `UNIQUE(organisation_id, user_id)` — one officer record per user per organisation (enforced by `unique_officer_per_org` index).

---

## Roles

| Role | Description |
|------|-------------|
| `chief` | Full control. The only role that can publish/unpublish results. |
| `deputy` | Same as chief except cannot publish results. |
| `commissioner` | View-only. Can see the viewboard but cannot manage anything. |

---

## Soft-Delete + Reappointment Pattern

The unique constraint covers trashed rows. If you `create()` a new officer for a user who was previously soft-deleted, you get a `UniqueConstraintViolationException`.

**The correct pattern is restore + update:**

```php
// ElectionOfficerController::store()
$trashed = ElectionOfficer::withTrashed()
    ->where('user_id', $request->user_id)
    ->where('organisation_id', $organisation->id)
    ->whereNotNull('deleted_at')
    ->first();

if ($trashed) {
    $trashed->restore();
    $trashed->update([
        'role'         => $request->role,
        'status'       => 'pending',
        'appointed_by' => auth()->id(),
        'appointed_at' => now(),
        'accepted_at'  => null,
    ]);
    $officer = $trashed;
} else {
    $officer = ElectionOfficer::create([...]);
}
```

This is covered by `test_can_reappoint_soft_deleted_officer`.

---

## Querying Active Officers

Always filter by `status = 'active'`. Pending officers have no access.

```php
// Check if user is an active officer for an organisation
ElectionOfficer::where('user_id', $user->id)
    ->where('organisation_id', $election->organisation_id)
    ->where('status', 'active')
    ->exists();

// Check if user is chief or deputy (can manage)
ElectionOfficer::where('user_id', $user->id)
    ->where('organisation_id', $election->organisation_id)
    ->whereIn('role', ['chief', 'deputy'])
    ->where('status', 'active')
    ->exists();
```

---

## markAccepted()

The `ElectionOfficer` model has a `markAccepted()` method that transitions from `pending` to `active`:

```php
public function markAccepted(): void
{
    $this->update([
        'status'      => 'active',
        'accepted_at' => now(),
    ]);
}
```

Called by `ElectionOfficerInvitationController::accept()` after validating the signed URL.

---

## Legacy Data Migration

Before the `ElectionOfficer` system, commission-role users were stored in `user_organisation_roles` with `role = 'commission'`. A one-time migration converts these:

**Migration:** `2026_03_20_205443_convert_legacy_commission_roles_to_officers.php`

```php
$commissions = DB::table('user_organisation_roles')
    ->where('role', 'commission')->get();

foreach ($commissions as $row) {
    ElectionOfficer::firstOrCreate(
        ['organisation_id' => $row->organisation_id, 'user_id' => $row->user_id],
        ['role' => 'commissioner', 'status' => 'active', ...]
    );
}
```

> ⚠️ Run this migration **before** deploying any code that removes the legacy `user_organisation_roles` fallback.
