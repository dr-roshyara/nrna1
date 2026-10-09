# Plan — make the Election test module green (`tests/Feature/Election`)

**Status:** PROPOSED, awaiting PO approval (EP-01). No test or production change before approval.
**Commission:** PO 2026-10-07, "fix the failing election tests, TDD first approach".

## Evidence
- **Current:** `php artisan test tests/Feature/Election` (78 files) has **83 failed / 704 passed**.
- **Baseline:** the same module at `7ae825d1e` (before PBDIGIT-248 GREEN, 222, 247, 250–254) has 84 failed.
- **Test-by-test comparison:**
  - **0 new failures**;
  - 1 fixed since (`b2 identity verification rejects legacy ip and device fields`, by PBDIGIT-248).
- **Conclusion:** all 83 are **pre-existing debt**, not regressions of this week's work.

## Principle (TDD for an existing RED)
The failing tests already are the RED. For each failure the root cause is classified **before** any change:

| Kind | Meaning | Allowed fix |
|---|---|---|
| **Fixture defect** | The test setup is wrong (duplicate rows, missing seed, wrong harness) | Fix the setup; the assertion stays |
| **Outdated test** | An **approved** later decision changed the behaviour (lifecycle derived from facts, constitutional transition guard, removed routes / APIs) | Update the test **with the decision cited**; never weaken what it protects. If the protected behaviour no longer exists, the test is retired with a recorded reason. |
| **Production defect** | The test is right and the code is wrong | The test stays RED, then the code is fixed (GREEN) |

**No assertion is changed just to make it pass.** Every change of expectation cites its decision. Ambiguous cases go to the PO.

## Categories (from the 83 failure messages)

| Cat. | ≈Tests | Symptom (FACT) | Hypothesis | Proposed fix |
|---|---|---|---|---|
| **A** | 8 | `user_organisation_roles_user_id_organisation_id_unique` violated (the UserFactory's `afterCreating` already creates the role row) | Fixture defect | `updateOrCreate` the role row (the pattern used in `ElectionVotersListTest`) |
| **E** | 8 | Timeline editing: `PermissionDoesNotExist` | Fixture defect (permission not seeded) | Seed the permission in the test setup, or test against the real authority source (to confirm) |
| **F** | 4 | `SecuritySchemaTest`: `pgsql_audit` cannot see an election inserted on `pgsql` (FK violation) | Harness: two connections, no shared transaction | Commit the fixture through the same visibility path (insert the election before the audit write, outside an uncommitted transaction) or run on one connection in the test (to confirm) |
| **B** | ≈15 | Expected `voting_active`, got `ready_for_voting` / `SetupNomination`; `close_voting not allowed in ready_for_voting` | Outdated fixtures: the lifecycle is now **derived from constitutional facts** (approval, phases completed, window, `voting_locked`, an approved candidacy) | Build states with `ElectionScenarioFactory` (`votingActive()` etc.), which the newer suites already use |
| **C** | ≈11 | `You do not have permission to perform 'complete_nomination'. Required role(s): chief, deputy`; `complete_administration not allowed in state 'draft'` | Outdated tests predating the `ConstitutionalTransitionGuard` (actor authority + state preconditions) | Act as an appointed chief / deputy and start from the right state; expectations follow the guard's exception type (`InvalidTransitionException`) |
| **D** | ≈10 | Routes `elections.update-voting-dates`, `elections.index` not defined; `ElectionMembership::bulkAssignVoters()` undefined; `VoterSourceStrategy::FullMembership` / `ElectionOnly` undefined; legacy election without a `voter_source_strategy` snapshot | Removed or renamed APIs | Per test: retarget to the current API, or **retire** if the feature was removed on purpose. **Each removal is confirmed with the PO** (it could also be an unintended regression). |
| **G** | ≈20 | `ElectionShowController` `canVote` / `hasVoted` / redirects / flash; `ElectionActivation` notification not sent; capabilities 16 ≠ 14 actions; `ElectionDashboardAccess` counting ≠ voting_active; `ElectionGracePeriodUI` 403; `ElectionPolicyStateAware` cast vote; `VoterVerificationTest` 403 + IP value; `VotingEntitlementAmbientInvariance` 302 ≠ 200; `ElectionSettingsTest` IP restriction 302 ≠ 403; `EmVot002`; `ElectionSuspension`; `ElectionTransitionTo`; `VotingClosureValidation` | **Mixed:** some are B/C in disguise; some may be real behaviour changes | Diagnose each individually; report "test outdated (decision X)" vs "production defect"; **PO decides each production-relevant case** |

## Execution (one story per category, one commit each; the RED is the existing failure)

| Order | Story | Content | Needs PO input |
|---|---|---|---|
| 1 | Fixture defects (A, E, F) | Pure setup fixes | No (assertions unchanged) |
| 2 | Lifecycle fixtures (B) | `ElectionScenarioFactory` states | Only if a test asserts the old flag semantics |
| 3 | Transition-guard tests (C) | Proper actors and states | No, if the guard decision is cited |
| 4 | Removed APIs (D) | Retarget or retire | **Yes**, per removed feature |
| 5 | Behaviour judgements (G) | Diagnose, then fix code or update the test | **Yes**, per case |

For each story:
1. Diagnose; write the per-test root cause into the story file.
2. Apply the fix.
3. Run the category's tests to green.
4. Run the whole Election module: **no test that passed before may fail**.
5. Run the 97-suite regression vs 582 / 333 / 19.
6. STOP for commit approval.

## Risks
- **Hiding a real defect:** a production defect could hide behind "outdated test". Mitigation: every expectation change cites its decision, and the PO decides production-relevant cases (G, D).
- **Weakening tests:** characterization tests (`CurrentBehaviorTest`, `*CharacterizationTest`) record intended or observed behaviour. If behaviour changed on purpose, the characterization is re-recorded with the decision reference; if not, it is a defect.
- **Effort:** about 29 files. Story 5 (G) is the largest and may split further.

## Open questions
- **Q1:** Are `elections.index`, `elections.update-voting-dates` and `bulkAssignVoters` intentionally removed?
- **Q2:** Should legacy elections without a `voter_source_strategy` snapshot still be supported (backfill), or is that test obsolete?
- **Q3:** Are "voted users see hasVoted" / "eligible voter canVote" on the public election page still required behaviours? They are likely real.

## Progress
- [x] Diagnosis: 83 failures, categorised; baseline comparison shows 0 new
- [x] PO approval of this plan (2026-10-07)
- [x] **Story 1 (setup only) — fixes applied; assertions unchanged:**
  - **A — `ElectionOnlyEntitlementPinTest` (5 of 6 fixed):**
    - Cause: `setUp` attached the organisation role a second time (`$org->users()->attach`), but `UserFactory::afterCreating` already `firstOrCreate`s that unique (user, org) row.
    - Fix: idempotent `UserOrganisationRole::updateOrCreate`.
    - The 6th test (`election only voter without member aggregate passes the ballot gate`) then reached its real assertion: 302, because `Election::factory()` defaults derive **Draft** (no approval, no window). That is **category B**, moved to story 2.
  - **F — `Security\SecuritySchemaTest` (4/4 fixed, file 9/9):**
    - Cause: the redundant `use RefreshDatabase` re-imported the trait's `beginDatabaseTransaction()`, overriding `Tests\TestCase`'s pgsql skip. The election stayed uncommitted and invisible to the separate `pgsql_audit` connection (ADR_20260806_1340): an FK violation.
    - Fix: remove the redeclaration (the convention of `SecurityEventRecorderTest` / `AuditEventTransactionIsolationTest`).
  - **E — `TimelineEditing\TimelineCapabilityAuthorizationTest` (8): NOT a missing seed.** The setup relies on three things that do not exist: the permission `manage_elections` (no producer or checker in `app/`), the factory state `inDraftState()` (undefined), and `update(['state' => …])` (the lifecycle is derived). The endpoint authorises `manageSettings` (election Chief / Deputy) + `ElectionLifecycle::canEditTimeline()`. It is outdated against the authority and state model, so it moves to **story 3**.
- [x] Story 1 committed `3273ce05c` (PBDIGIT-255); clean solo module run: 74 failed, 0 new, 9 fixed.
- [x] **Story 2 (PBDIGIT-256):** 19 lifecycle-fixture tests fixed (see the story file). New shared helper `ElectionScenarioFactory::addApprovedCandidate()`. Finding for story 5: audit `from_state` is read from the persisted column.
- [ ] Stories 3–5
