# T-001 Lessons Learned: Domain Events for Trust Attestation

**Status:** Implementation complete and proven  
**Date:** 2026-05-30  
**Outcome:** Feature tests pass (11 tests, 23 assertions)

---

## What We Built

Three immutable event classes that describe trust decisions:

```
IdentityAttestedEvent  — Officer verified an identity
VerificationRevokedEvent  — Officer revoked a verification
(VerificationExpiredEvent deferred — no expiry mechanism exists)
```

Events emitted from `VoterVerificationController` after database state changes.

---

## Critical Discovery: Middleware Dependency

### The Problem

Feature tests initially failed silently:

```
POST /organisations/{org}/elections/{election}/voters/verify
    ↓
302 Redirect (success)
    ↓
voter_verifications table: empty
```

The verification record was not being created, but the request appeared successful.

### Root Cause Found

The `EnsureOrganisationMember` middleware was blocking the request:

```php
// app/Http/Middleware/EnsureOrganisationMember.php
$isMember = $user->organisationRoles()
    ->where('organisation_id', $organisation->id)
    ->exists();

if (!$isMember) {
    return redirect()->route('dashboard');
}
```

The test was missing a `UserOrganisationRole` record.

### The Fix

Feature tests must create TWO records:

```php
// 1. Organisation membership (required by middleware)
UserOrganisationRole::create([
    'user_id' => $officer->id,
    'organisation_id' => $organisation->id,
    'role' => 'admin',
]);

// 2. Election officer role (required by policy)
ElectionOfficer::create([
    'election_id' => $election->id,
    'user_id' => $officer->id,
    'organisation_id' => $organisation->id,
    'role' => 'chief',
    'status' => 'active',
]);
```

Without `UserOrganisationRole`, the entire request is intercepted by middleware before reaching the controller.

---

## Architectural Decisions Applied

### 1. No `electionId` in Events

**Decision:** Remove election context from trust events

**Why:** Trust Attestation Context is independent. Election Context consumes events downstream (ADR-001).

**Evidence:** Events work equally well for:
- Election voting verification
- Membership approval verification
- Delegate nomination verification
- Any other process requiring identity trust

**Learning:** Event design should express the business concept (identity attestation), not the technical context (election).

---

### 2. `userId` Not `identityId`

**Decision:** Use `userId` as field name

**Why:** User is the current aggregate root. No separate Identity entity exists.

**Trade-off:** If a future Identity aggregate is introduced, events will need migration. But premature abstraction is waste.

**Learning:** Name things after the actual domain model, not the aspirational one. Refactor when reality changes.

---

### 3. Direct Constructor, No `::from()` Factory

**Decision:** Prefer simple constructor over factory method

```php
// Chosen
new IdentityAttestedEvent(...)

// Not chosen
IdentityAttestedEvent::from(...)
```

**Why:** Factory methods add value when they provide:
- Validation
- Normalization
- Complex construction logic

Events have none of these. Simple immutable data needs simple construction.

**Learning:** Don't introduce abstractions for their own sake. The simplest thing that works is better than the clever thing that might be needed later.

---

### 4. No Listeners in T-001

**Decision:** Register events, but defer listener implementation

**Why:** No real consumer exists yet. Listeners would be placeholder code.

**When:** Listeners implemented in T-006 (Governance Notification on Revocation)

**Learning:** An event without listeners is still valuable. It:
- Establishes the contract
- Allows independent testing
- Prevents tight coupling
- Forces async design thinking

---

### 5. `VerificationExpiredEvent` Deferred

**Decision:** Don't implement expiry event in T-001

**Why:** No expiry mechanism exists in the system. Can't emit an event no code generates.

**When:** Implement in T-005 (Verification Expiry and Re-verification)

**Learning:** Only emit events for things that actually happen. Hypothetical events are dead code and maintenance burden.

---

## Test Structure

### Unit Tests (5 tests)

Event classes themselves, no dependencies:

```
IdentityAttestedEventTest
  ✓ event can be created with all fields
  ✓ event can be created without notes
  ✓ event is immutable

VerificationRevokedEventTest
  ✓ event can be created with all fields
  ✓ event payload contains only trust concepts
```

**Value:** Prove event contracts work in isolation.

### Feature Tests (6 tests)

Real request-response cycle, using Event::fake():

```
VoterVerificationEmitsEventsTest
  ✓ identity attested event dispatched on store
  ✓ verification revoked event dispatched on revoke
  ✓ existing db state preserved after store
  ✓ existing db state preserved after revoke

SimpleStoreTest
  ✓ verify endpoint creates verification
```

**Value:** Prove events are actually emitted during real application flow. Catch infrastructure assumptions (like `EnsureOrganisationMember`).

---

## What Feature Tests Revealed

The most valuable finding was not "the feature works" but "here's what the test environment must provide":

```
To test VoterVerificationController::store()
    ↓
Must have authenticated user
    ↓
Must have organisation membership (middleware)
    ↓
Must have election officer role (policy)
    ↓
Only then does verification create
```

This is infrastructure knowledge that belongs in feature tests, not in documentation.

---

## For Future Contributors

### When Adding Trust-Related Features

1. **Check middleware** — `EnsureOrganisationMember` blocks unauthenticated or non-member users
2. **Check policies** — `ElectionPolicy::manageSettings()` requires chief/deputy officer role
3. **Event timing** — Events emit AFTER database state changes, but BEFORE audit logging
4. **No event registration needed yet** — Events exist but have no listeners until T-006

### When Implementing T-002 (Trust Levels)

Do NOT:
- Rename userId to identityId
- Add TrustLevelAssigned event (that's trust level business, not attestation)
- Create listeners yet

DO:
- Add trust_level column to voter_verifications
- Update VoterVerificationPolicy to check trust levels
- Preserve IdentityAttestedEvent contract (no changes)

### When Implementing T-005 (Verification Expiry)

DO:
- Create VerificationExpiredEvent (mimicking IdentityAttestedEvent structure)
- Emit from scheduled command (not controller)
- Add listener in T-006

---

## Summary

**T-001 is complete when:**

- ✅ Event classes exist and are immutable
- ✅ Events are emitted on real trust actions
- ✅ Events contain no election/application context
- ✅ Feature tests prove event dispatch works
- ✅ Existing behavior (database, audit) unchanged

**All conditions met.** Infrastructure assumptions discovered and documented.

---

**Last Updated:** 2026-05-30  
**Related:** IMPLEMENTATION_READINESS_REVIEW.md, ADR-001, ADR-002, ADR-003
