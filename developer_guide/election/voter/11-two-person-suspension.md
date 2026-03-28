# 11 — Two-Person Suspension Workflow

**Migration:** `database/migrations/2026_03_22_213421_add_suspension_proposal_columns_to_election_memberships.php`
**Model:** `app/Models/ElectionMembership.php`
**Controller:** `app/Http/Controllers/ElectionVoterController.php`

---

## Why Two-Person Suspension

A single committee member being able to immediately suspend a voter creates an abuse vector — a malicious or mistaken officer could disenfranchise a voter with no oversight. The two-person rule ensures:

- One member **proposes**
- A *different* member **confirms**
- Either the proposer (or an admin) can **cancel** before confirmation

---

## State Machine

```
active
  │
  ├─ propose (officer A)
  │     └─ suspension_status = 'proposed'  (status still 'active')
  │           │
  │           ├─ confirm (officer B ≠ A)
  │           │     └─ status = 'inactive', suspension_status = 'confirmed'
  │           │
  │           └─ cancel (proposer A or admin)
  │                 └─ suspension_status = 'none'  (back to normal)
  │
  └─ (direct suspend via old route is preserved but not shown in UI)
```

---

## Database Columns Added

| Column | Type | Default | Purpose |
|--------|------|---------|---------|
| `suspension_status` | `enum(none,proposed,confirmed)` | `none` | Tracks proposal state |
| `suspension_proposed_by` | `string` nullable | `null` | Name of proposing officer |
| `suspension_proposed_at` | `timestamp` nullable | `null` | When proposal was made |

---

## Model Methods

```php
// Propose — officer A
$membership->proposeSuspension(User $proposer): void

// Confirm — officer B (must not be proposer)
$membership->confirmSuspension(User $confirmer): void

// Cancel — proposer or admin
$membership->cancelSuspensionProposal(): void

// Guards
$membership->isSuspensionProposed(): bool
$membership->canConfirmSuspension(User $user): bool
// Returns false if user is the proposer or status != 'proposed'
```

---

## Routes

```
POST /organisations/{org}/elections/{election}/voters/{membership}/propose-suspension
     → elections.voters.propose-suspension

POST /organisations/{org}/elections/{election}/voters/{membership}/confirm-suspension
     → elections.voters.confirm-suspension

POST /organisations/{org}/elections/{election}/voters/{membership}/cancel-proposal
     → elections.voters.cancel-proposal
```

---

## Controller Guards

### `proposeSuspension`
- `status` must be `active`
- `has_voted` must be `false`
- `suspension_status` must be `none` (no duplicate proposals)

### `confirmSuspension`
- `canConfirmSuspension(auth()->user())` must return `true`
  - Fails if user is the proposer (same name)
  - Fails if no proposal is pending

### `cancelProposal`
- `isSuspensionProposed()` must be `true`
- `suspension_proposed_by === auth()->user()->name` OR user has `admin` role
- Returns **403** (not a redirect) if neither condition holds

---

## UI Behaviour

| User | Sees |
|------|------|
| Any officer on active voter | **Propose** button |
| Officer who proposed | **Cancel** button (Propose disappears) |
| Different officer, proposal pending | **Confirm** button |
| Admin, proposal pending | **Cancel** button |
| Row with pending proposal | Amber row highlight + suspension banner sub-row |

The suspension banner sub-row shows proposer name and timestamp:

```
⚠ Suspension proposed by Alice · 23 Mar 2026
  Awaiting confirmation from a second committee member
```

---

## Security Notes

- Self-confirmation is blocked at **both** model (`canConfirmSuspension`) and controller levels
- Voted voters (`has_voted=true`) cannot have suspensions proposed — vote integrity is preserved
- All actions are logged to `voting_security` channel with proposer/confirmer IDs

---

## Tests

| File | Tests | What it covers |
|------|-------|---------------|
| `tests/Unit/Models/ElectionMembershipSuspensionTest.php` | 7 | Model methods, guard logic |
| `tests/Feature/Election/ElectionVoterSuspensionTest.php` | 7 | HTTP actions, security, state transitions |

```bash
php artisan test \
  tests/Unit/Models/ElectionMembershipSuspensionTest.php \
  tests/Feature/Election/ElectionVoterSuspensionTest.php \
  --no-coverage
```

Expected: **14 passed, 33 assertions.**
