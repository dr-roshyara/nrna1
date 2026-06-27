# Phase B.0/B.1 Corrections (B.2 Deferred)

## Status

**B.0/B.1 COMPLETE** — All fixes implemented and verified.
**B.2 DEFERRED** — Premature until audit requirement is real and event store exists.

---

## Why This Change

### B.0/B.1 Architectural Defects (RESOLVED)

1. **Fake temporal semantics**: `isActiveAt($at)`, `isSuspendedAt($at)`, etc. accept `$at` but completely ignore it — they call `currentStatus()` internally. This is a contract illusion: code promises temporal behavior, delivers none.

2. **`existsAt($at)` is semantically incorrect**: Ignores establishment date, termination time, and restoration cycles — not time-aware at all.

3. **`isActive()` returns wrong value**: Current `isActive()` returns `!isTerminated()` — a SUSPENDED member returns `isActive() = true`. This is a bug.

4. **Policy signature leaks fake time**: `evaluate($lineage, $at)` — policy receives `$at` but passes it to aggregate methods that ignore it.

## Architecture (Corrected)

```
Domain Layer
  MembershipLineage          ← current-state facts ONLY (corrected API)
    isActive(): bool         ← strictly ACTIVE status (not just "not terminated")
    isSuspended(): bool
    isTerminated(): bool
    exists(): bool

Voting Layer
  VotingEligibilityPolicy
    evaluate(?MembershipLineage): VotingEligibilityResult  ← corrected (B.1)
```

> Note: Episodes in MembershipLineage already are an event log (each has status + associatedAt).
> If temporal reconstruction is needed in future, add `stateAt($at)` to MembershipLineage
> using existing episodes — no separate aggregate required.

---

## Phase B.0 Correction — Honest State Query API

**Goal**: Replace fake temporal methods with honest current-state methods.

### Step 1 — RED: `LifecycleStateQueryTest.php`

**File (NEW):** `tests/Unit/Constitutional/Membership/LifecycleStateQueryTest.php`

Tests (no `$at` in any method call):
```
test_active_lineage_is_active()
test_active_lineage_is_not_suspended()
test_active_lineage_is_not_terminated()
test_established_lineage_exists()
test_suspended_lineage_is_suspended()
test_suspended_lineage_is_not_active()
test_terminated_lineage_is_terminated()
test_restored_lineage_is_active_again()
test_status_mutual_exclusivity_exactly_one_true()
```

### Step 2 — GREEN: Fix MembershipLineage methods

**File:** `app/Contexts/Membership/Domain/Membership/MembershipLineage.php`

**Remove:**
- `isActiveAt(\DateTimeImmutable $at): bool`
- `isSuspendedAt(\DateTimeImmutable $at): bool`
- `isTerminatedAt(\DateTimeImmutable $at): bool`
- `existsAt(\DateTimeImmutable $at): bool`

**Fix `isActive()` (EXISTING BUG):**
```php
// ❌ Current: returns true for SUSPENDED members too
public function isActive(): bool {
    return !$this->currentStatus()->equals(MembershipStatus::TERMINATED);
}

// ✅ Corrected: strictly checks ACTIVE
public function isActive(): bool {
    return $this->currentStatus()->equals(MembershipStatus::ACTIVE);
}
```

**Add:**
```php
public function isSuspended(): bool {
    return $this->currentStatus()->equals(MembershipStatus::SUSPENDED);
}

public function isTerminated(): bool {
    return $this->currentStatus()->equals(MembershipStatus::TERMINATED);
}

public function exists(): bool {
    return count($this->episodes) > 0;
}
```

### Step 3 — Retire `TemporalLifecycleQueryTest.php`

Delete `tests/Unit/Constitutional/Membership/TemporalLifecycleQueryTest.php` — superseded by `LifecycleStateQueryTest.php`.

---

## Phase B.1 Correction — Remove Fake Time from Policy

**Goal**: `evaluate()` no longer pretends to accept temporal context.

### Step 4 — Update `VotingEligibilityPolicyTest.php`

All calls change from `evaluate($lineage, $at)` → `evaluate($lineage)`:
```php
// ❌ Before (6 tests all pass $at as second arg)
$result = $this->policy->evaluate($lineage, $now);

// ✅ After (single-arg)
$result = $this->policy->evaluate($lineage);
```

### Step 5 — Update `VotingEligibilityPolicy.php`

```php
// ❌ Before
public function evaluate(?MembershipLineage $lineage, \DateTimeImmutable $at): VotingEligibilityResult {
    ...
    if ($lineage->isTerminatedAt($at)) { ... }

// ✅ After
public function evaluate(?MembershipLineage $lineage): VotingEligibilityResult {
    ...
    if ($lineage->isTerminated()) { ... }
    if ($lineage->isSuspended()) { ... }
    if (!$lineage->isActive()) { ... }
```

---

## Critical Files

| File | Action | Phase |
|------|--------|-------|
| `tests/Unit/Constitutional/Membership/LifecycleStateQueryTest.php` | CREATE (RED first) | B.0 |
| `app/Contexts/Membership/Domain/Membership/MembershipLineage.php` | EDIT — fix `isActive()`, add `isSuspended()` `isTerminated()` `exists()`, remove 4 `*At()` methods | B.0 |
| `tests/Unit/Constitutional/Membership/TemporalLifecycleQueryTest.php` | DELETE — superseded | B.0 |
| `tests/Unit/Constitutional/Membership/VotingEligibilityPolicyTest.php` | EDIT — remove `$at` from all `evaluate()` calls | B.1 |
| `app/Contexts/Membership/Domain/Voting/VotingEligibilityPolicy.php` | EDIT — remove `$at` param, use `isTerminated()` `isSuspended()` `isActive()` | B.1 |

---

## Verification

```bash
# B.0 RED — must fail before MembershipLineage is fixed
php artisan test tests/Unit/Constitutional/Membership/LifecycleStateQueryTest.php --no-coverage

# B.0 GREEN
php artisan test tests/Unit/Constitutional/Membership/LifecycleStateQueryTest.php --no-coverage

# B.1 RED — after editing policy test, before updating policy
php artisan test tests/Unit/Constitutional/Membership/VotingEligibilityPolicyTest.php --no-coverage

# B.1 GREEN
php artisan test tests/Unit/Constitutional/Membership/VotingEligibilityPolicyTest.php --no-coverage

# Full constitutional regression — must stay green throughout
php artisan test tests/Unit/Constitutional/Membership/ --no-coverage
```

**Final result:** 52 passing (49 existing − 6 deleted + 9 new), 4 incomplete unchanged. ✅

---

## Improvements (Post-Approval)

Applied per architectural review feedback:

1. **Replace `!empty($this->episodes)` with explicit `count($this->episodes) > 0`**
   - Clearer intent in domain logic
   - Easier to evolve invariants later

2. **Add semantic documentation clarifying three distinct concepts:**
   - `exists()`: membership relationship established historically
   - `isActive()`: current operational status (aggregate fact)
   - voting eligibility: policy decision (governance interpretation)

3. **Update `MembershipLineage.isActive()` with usage guidance:**
   - Domain logic: check operational status for state transitions
   - Policy evaluation: VotingEligibilityPolicy uses this
   - Never use for authorization — use policy instead

4. **Restructure VotingEligibilityPolicy class documentation:**
   - ASCII tree showing fact vs policy distinction
   - Clear DDD separation principle
   - Authorization responsibility is always policy, never aggregate

---

## Explicitly Deferred

| Item | Reason |
|------|--------|
| `MembershipHistory` event-sourced aggregate | Premature — no event store, no persistence model, two sources of truth risk |
| Domain events (`MembershipEstablished`, etc.) | Premature — needed when event store exists |
| `evaluateAt()` temporal policy method | Premature — blocked on `MembershipHistory` |
| `membership_status_changes` audit log table | Next audit requirement trigger (simpler than event sourcing) |
| Elections `VotingEligibilityGateway` integration | Phase B.3 — Elections context not yet created |
| Governance rules (delegation, weighted voting, quorum) | Phase B.4 |

**Trigger for B.2:** When the question *"Was [member] eligible to vote on [date]?"* needs an answer for a real dispute or election audit. Episodes already contain the data — `stateAt($at)` can be added to `MembershipLineage` at that point, no separate aggregate needed.

---

## Commits

1. `8dc2c9109` - Phase B.0/B.1: Correct fake temporal semantics and fix isActive() bug
2. `95febbf24` - Improve semantic clarity: exists() vs isActive() vs voting eligibility
3. `95febbf24` - Document plan storage convention in ./claude/plans/ with datetime stamps
