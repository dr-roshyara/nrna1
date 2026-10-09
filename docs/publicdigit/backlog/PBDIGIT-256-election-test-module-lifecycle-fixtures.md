# PBDIGIT-256 — Election test module, story 2: lifecycle fixtures

**Type:** Test debt (outdated fixtures) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-07
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable) · PO commission "fix the failing election tests, TDD first approach"
**Plan:** `docs/plans/20261007-0006-election-test-module-green-plan.md` (story 2 of 5) · follows PBDIGIT-255

| | |
|---|---|
| **Status** | 🟢 GREEN (commit pending PO approval): 19 failing tests fixed by correcting their **fixture facts**. No production change; no assertion weakened. |
| **Root cause** | The lifecycle is **derived from facts** (`ElectionLifecycleEngineImpl`), but these tests built elections from older assumptions |

## What the fixtures were missing
| Missing fact | Rule (decision / evidence) | Tests |
|---|---|---|
| **An approved candidate** | EM-VOT-002 (Election Manifesto §4a, adopted; PBDIGIT-64): VotingActive requires one on every path, incl. the derived one (engine step 5) | CurrentBehaviorTest ×4, ElectionPolicyStateAwareTest, ElectionDashboardAccessTest, ElectionOnlyEntitlementPinTest, VotingEntitlementAmbientInvarianceTest ×2 |
| **Voting explicitly opened** (`voting_locked`) | Engine step 5: "voting must be BOTH explicitly opened AND within its window" | VotingButtonsStateMachineTest ×3, VotingClosureValidationTest, EmVot002ApprovedCandidateBeforeVotingTest, ElectionSuspensionTest, + the four above where also missing |
| **A non-Draft election** (approval, completed phases, window) | Factory defaults derive Draft | ElectionOnlyEntitlementPinTest, VotingEntitlementAmbientInvarianceTest ×2 |
| **Setup-administration / counting facts** instead of writing the raw `state` column | The ConstitutionalTransitionGuard derives the state; states are named `setup_administration` / `setup_nomination` / `counting` / `results_published` (ElectionConstitution) | StateMachineTransitionAuditTest ×3, ElectionModelLockAndAuditTest |
| **The authenticated election chief + request tenant context** | The Guard authorises `Auth::user()` (not the `actorId`); `has_chief` / `has_voters` preconditions read officers and tenant-scoped memberships | StateMachineTransitionAuditTest ×3, ElectionModelLockAndAuditTest |

## Notable details
- **One shared helper** `ElectionScenarioFactory::addApprovedCandidate()`, extracted from `votingActive()`, which now uses it, instead of a 5th private copy.
- **`EmVot002ApprovedCandidateBeforeVotingTest`:** voting was never opened in its helper, so the **negative** EM-VOT-002 tests passed *vacuously* (no election could reach VotingActive). With `voting_locked`, only the candidate rule decides, and the negative tests still pass, so EM-VOT-002 is genuinely proven.
- **`StateMachineTransitionAuditTest`:** state names updated to the constitutional vocabulary, following the precedent already in the same file ("the assertion's substance is unchanged").
- **Changes kept per test** where a class's other tests rely on the shared `setUp` (VotingClosureValidationTest, ElectionDashboardAccessTest, ElectionOnlyEntitlementPinTest).

## Flake found by the full-module run (fixed here)
- `VotingEntitlementUnificationTest::start does not depend on the ambient tenant` failed **only in the full run**: `organisations_slug_unique` violated (`ondricka-inc`) in `setUp`.
- **Cause:**
  - `OrganisationFactory` derives `slug = Str::slug(faker->company())`;
  - organisations committed by tests without a wrapping transaction (`Tests\TestCase` skips it on PostgreSQL) stay for the rest of the run;
  - so two random company names can collide.
  - Story 1's `SecuritySchemaTest` change (it now commits, like its sibling security tests) slightly raised the odds; the weakness is the factory.
- **Fix:** `faker->unique()->company()`, the same technique the factory already uses for `email`. The `slug = Str::slug(name)` relationship is unchanged.

## Finding recorded for story 5 (not fixed here)
- **The audit `from_state` is read from the persisted `state` column** (`Election::getCurrentStateAttribute`), while authorisation uses the **derived** state.
- If the derived state advances with time (voting ends → counting) without a transition writing the column, an audit record can carry a stale `from_state`.
- Production behaviour; it needs a PO decision.

**Traceability:** plan above · ElectionLifecycleEngineImpl step 5 · EM-VOT-002 / PBDIGIT-64 · ElectionConstitution · ConstitutionalTransitionGuard.
