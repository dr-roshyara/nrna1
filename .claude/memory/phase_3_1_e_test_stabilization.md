---
name: phase-3-1-e-test-stabilization
description: Phase 3.1.E Election Test Suite Stabilization - Root Cause Fixes and Progress
metadata: 
  node_type: memory
  type: project
  originSessionId: c58c462c-c198-465e-89b3-69a6bfe193fb
---

# Phase 3.1.E: Election Test Suite Stabilization - Progress Report

## Status Summary
- **Started:** 2026-05-20
- **Current:** 182 failed, 141 passed (down from 202 failed, 121 passed)
- **Progress:** RC-1 (20 tests) FIXED ✅

## Completed Work

### RC-1: CQRS Handler Migration (COMPLETE ✅)
**Problem:** ElectionMembership::assignVoter() and ::bulkAssignVoters() static methods removed
**Root Cause:** Methods moved to CQRS handlers (AssignVoterHandler, BulkAssignVotersHandler)
**Tests Fixed:**
- tests/Feature/Election/ElectionMembershipInfrastructureTest.php (9 tests → all passing)
- tests/Feature/Election/ElectionMembershipPersistenceTest.php (11 tests → all passing)

**Implementation Details:**
1. Updated imports to include handler classes and ElectionMode enum
2. Replaced all `ElectionMembership::assignVoter(...)` with handler pattern:
   ```php
   $handler = app(AssignVoterHandler::class);
   $handler->handle(new AssignVoterCommand(
       userId: $user->id,
       electionId: $election->id,
       organisationId: $org->id,
       mode: ElectionMode::fromOrganisation($org),
       assignedBy: $assignedBy->id,
   ));
   ```
3. Same for BulkAssignVotersHandler with BulkAssignVotersCommand
4. Cache invalidation still works through Eloquent events (model->booted())
5. Fixed tenant isolation: Updated queries to use `withoutGlobalScopes()` instead of `forOrganisation()`

**Commit:** f638eff97 "Fix RC-1: Update membership tests to use CQRS handlers"

## Remaining Work

### RC-2: MySQL-Compatible Unique Index
**Status:** Partially addressed
- Created migration: 2026_05_20_085627_fix_election_memberships_unique_index_mysql_compat.php
- Migration is currently empty (PostgreSQL's partial unique index already works)
- **Note:** Not a blocker on PostgreSQL; may affect MySQL/MariaDB tests only

### RC-3: Hardcoded Election Slugs  
**Status:** Not yet investigated
**Mentioned in Plan:** tests/Feature/Election/ElectionActivationTest.php uses hardcoded slugs
**Actual Finding:** Only found one minor case with `uniqid()` which is good practice
**Investigation Needed:** Search for 'general-election-2026' or similar patterns

### RC-4: Missing forOrganisation() on Factory Calls
**Status:** Partially identified
**Mentioned in Plan:** tests like VotingButtonsStateMachineTest.php, StateMachine/CurrentBehaviorTest.php
**Actual Finding:** Different root cause observed - unique constraint violations on user_organisation_roles
**Investigation Needed:** Understand factory-generated data and multi-test collisions

## Other Failures Identified
Beyond the 4 planned RCs, encountered:
- UniqueConstraintViolationException on user_organisation_roles (ElectionActivationTest)
- DomainException: "No candidates have been registered" (VotingButtonsStateMachineTest)
- State transition validation failures
- **Hypothesis:** Tests may be interfering with each other or factory setups may not be isolated properly

## Key Insights

1. **Tenant Isolation:** BelongsToTenant trait applies global scope filtering by organisation_id
   - Tests need `withoutGlobalScopes()` when querying created records
   - Or must set TenantContext appropriately
   - Session-based fallback: `session('current_organisation_id')`

2. **Handler Architecture:** CQRS handlers properly separate:
   - Eligibility checking (via policy)
   - Persistence (via repository)
   - Events dispatch (outside transaction)
   - Audit logging

3. **Eloquent Events:** Model cache invalidation works through:
   - `static::booted()` hooks
   - `static::saved()` and `static::deleted()` events
   - No need for application-level cache clearing if Eloquent events fire

## Next Steps (Priority Order)

1. **Investigate RC-3 & RC-4 root causes:**
   - ElectionActivationTest unique constraint violations suggest data collision across tests
   - May need to isolate setUp or use unique identifiers
   - Factory methods may need forOrganisation() chaining

2. **Profile remaining 182 failures:**
   - Run individual test files to identify pattern
   - Group by error type (uniqueness, foreign key, state validation, business logic)
   - Separate legitimate test issues from factory setup issues

3. **Consider test isolation improvements:**
   - Ensure each test properly sets up its own data
   - Check factory default values for organisation_id
   - Verify RefreshDatabase is properly resetting sequences

## Files Modified
- tests/Feature/Election/ElectionMembershipInfrastructureTest.php
- tests/Feature/Election/ElectionMembershipPersistenceTest.php
- database/migrations/2026_05_20_085627_fix_election_memberships_unique_index_mysql_compat.php
