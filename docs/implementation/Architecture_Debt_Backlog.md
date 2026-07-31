# Architecture Debt Backlog

**Status:** ✅ **CLEARED (2026-07-06)** — all five items fixed; Architecture suite **131/131 green** (0 failures, 1 legitimate skip). The condition "re-run `--testsuite Architecture` until green, then the full suite becomes a hard CI gate" is now MET.
**Origin:** pre-existing failures surfaced when the Architecture testsuite was registered (Push A / F-4). None were in the greenfield Core — all legacy. Fixed under the dedicated legacy workstream (2026-07-05/06), separate from Push A/B commits.

| ID | Failing test | Resolution (2026-07-05/06) |
|----|--------------|----------------------------|
| **AD-001** ✅ | `CommitteeDomainPurityTest` | `CommitteeStructureId` → pure-PHP ULID generator; `CommitteeSlug` → pure-PHP slugify. `Illuminate\Support\Str` removed from Membership Domain layer. |
| **AD-002** ✅ | `ElectionStateMachineConsistencyTest` | `APPLY_CANDIDACY: 'apply_candidacy'` added to `resources/js/Constants/ElectionActions.ts` (14→15, matches PHP enum). |
| **AD-003** ✅ | `ElectionLifecycleStateConsistencyTest` (×2) | Tests asserted the stale `state` cache column; per the enum docblock the ENGINE is sovereign. Tests now assert `currentState()->value`. Engine logic was correct. |
| **AD-004** ✅ | `Phase_C25_SovereigntyLeakagePreventionTest` (×3) | The `C:0` suspicion was CONFIRMED — `grepFiles()` split on `:` broke on Windows drive letters, and `findPhpFiles()`'s malformed `find` exclude silently scanned ZERO files. Both helpers rewritten in pure PHP. The repaired guards then exposed REAL findings, all fixed: hardcoded `'not eligible'` vocabulary purged from 8 controllers; `getStateMachineData` name-substring false-positive (pattern now matches call syntax); comment-only `allowsAction` reference documented; **and a real business bug — `AdminElectionController::approve()/reject()` compared `state !== 'pending_approval'`, a value not in the lifecycle enum, so admin approval always short-circuited** — fixed via `currentState() === ElectionLifecycleState::SubmittedForApproval`. |
| **AD-005** ✅ | `VocabularyProhibitionTest` (risky) | Empty-directory early-return replaced with `markTestSkipped()` — no more assertion-less pass. |

**Bonus fix (same sweep):** `MiddlewareExecutionOrderTest` 3 failures — all stale pre-Laravel-11 test assumptions (TenantContext is route-alias by design; `/dashboard` is public by design; `csrf_token()` needs a request first). Tests corrected; 9/9 green.

## Lessons recorded
- The detectors themselves were platform-broken and created **false confidence** — guards reported "OK" while checking almost nothing. Repairing them immediately caught a real business defect. Architecture tests are executable governance, not documentation.
- Behaviour tests can fail in the opposite direction: implementation correct, test assumptions stale. Both directions need the same discipline: verify, then fix the wrong side.

## Recognized class — **Governance Verification Drift** (named by the ARB, 2026-07-31)

**AD-004 · AD-009 · AD-010 form one class:** *the architecture stays correct while the mechanisms that verify it quietly become incomplete.*

| Instance | How the verifier shrank |
|---|---|
| **AD-004** | Broken helpers made guards scan **zero files** while reporting OK |
| **AD-009** | A hand-maintained completeness list covers **2 of 4** produced event types |
| **AD-010** | Handler **registration** — the PB-006 failure mode — is asserted by nothing general |

**Why it is dangerous:** its signature is a **green** gate. A failing test announces itself; a shrinking one does not, so drift outlives ordinary review and creates false confidence — AD-004's repair immediately exposed a real business defect that had been hidden behind a passing guard.

**Counter-measure (wording corrected at ARB review, 2026-07-31):**

> **Verification should derive its scope from authoritative system metadata wherever possible, rather than from manually synchronized inventories.**

The principle is **not** *"constants are bad"* — this codebase has legitimate ones (schema-version windows per ADR-T5, terminal statuses in `AdjudicationProcessStatus`, lifecycle state enums, immutable semantic vocabularies). Those are **authoritative definitions**, not inventories *of* something else. What the three instances actually demonstrate is narrower and sharper: **a list that must be kept in step with a growing system by hand will drift, and drift green.**

The test to apply: *does this constant DEFINE the thing, or MIRROR a set that lives elsewhere?* `PRODUCED_EVENT_TYPES` mirrors the outbox adapters — so derive it. `AdjudicationProcessStatus`'s terminal cases define the terminal set — so a constant is correct there. Where a mirror is unavoidable, assert its completeness against a discovered set rather than trusting it.

*Recorded as recognized vocabulary and a review heuristic — **deliberately NOT promoted to doctrine**. Three occurrences in one subsystem are enough to recognize a pattern, not to constitutionalize it (R-34 default classification; no new document per R-38; the multi-context promotion bar of R-39 stands).*

## Open items

| ID | Failing test | Issue | Owner |
|----|--------------|-------|-------|
| **AD-006** | `Tests\Feature\Membership\OutboxIntegrationTest` (×3 errors) | `FeeTestFactory` passes `Membership\Domain\ValueObjects\TenantId` where `Fee::create()` expects `Shared\Domain\ValueObjects\TenantId` — legacy test-support drift after TenantId consolidation. Pre-existing (git-stash-verified 2026-07-06 during Push B step 5; NOT caused by the relay refactor). | Membership |
| **AD-007** | *(no failing test — an environment-specific observation)* | **The relay's failure path interacts poorly with a single-transaction test harness.** When `OutboxEventProcessor` handles a malformed payload, its defensive `try/catch` around its OWN status update swallows a SQL error; PostgreSQL then aborts the surrounding transaction, so under `RefreshDatabase` every later statement in the test fails with `SQLSTATE[25P02]` for reasons unrelated to the behaviour under test. **NOT an architectural issue and NOT a production defect** — in production each statement autocommits, so nothing is poisoned. Recorded per ARB direction at WP-4 RED acceptance so the concern is not forgotten: it makes relay *failure-path* behaviour unobservable at Feature level, which is why WP-4's keystone 5 was withdrawn in favour of the hydrator-level proof. Discovered 2026-07-31 (WP-4 RED). | Shared / Messaging platform |
| **AD-009** | `Tests\Architecture\EventRegistryCompletenessTest` — **passes green while checking half its surface** | `PRODUCED_EVENT_TYPES` is a hand-maintained constant listing **2 of the 4** event types actually written to `outbox_events`: it has `FeePaid` + `DeterminationIssued`, and is missing **`ElectionCorrectionApplied`** (`ReactionOutboxAdapter:45`) and **`ChallengeRouted`** (`ChallengeOutboxAdapter:58`). **Not a production defect** — both missing hydrators *are* registered, so behaviour is correct; this is a **guard-coverage gap**. `ChallengeRouted`'s omission was introduced by WP-3A, which shipped a new produced event type without extending the list the test's own comment instructs maintainers to extend. **Second occurrence of the AD-004 class — guards that report green while checking almost nothing.** *Fix:* add the two missing types, and prefer **deriving** the list from the registered outbox adapters over maintaining it by hand (it has now drifted twice). Found 2026-07-31 by the WP-4 post-implementation contract validation. | Shared / Messaging platform |
| **AD-010** | *(no failing test — the gap IS the missing test)* | **Nothing asserts that an `InboxHandler` is registered at all.** `InboxHandlerRegistryWiringTest` asserts only that the registry is a container singleton. A consumer can therefore exist, compile, pass PHPStan max and Deptrac, and **silently never consume** — precisely the failure mode PB-006 named (*Registration ≠ Delivery*). WP-4 is protected only because its own keystone test asserts `has('Adjudication','ChallengeRouted')`; a future consumer would not be. *Fix:* one fitness test asserting every `InboxHandler` implementation under `app/Contexts` resolves in the registry under its own `consumerContext()` × `eventTypes()` — the **inbox-side mirror of the hydrator completeness test** (the asymmetry between the two is the argument). Found 2026-07-31, same commission. | Shared / Messaging platform |
| **AD-008** | `Tests\Feature\Contexts\Membership\CommitteeAssociationRepositoryTest` (×11) · `CommitteeMembershipApplicationTest` (×6) | 13 errors + 4 failures in the Membership context. **Pre-existing — git-stash-verified 2026-07-31 during WP-4 GREEN** (identical counts with WP-4's changes stashed), so not caused by the Adjudication consumer. Same legacy-Membership family as AD-006. | Membership |

---
*Architecture Debt Backlog — AD-001..AD-005 ALL FIXED (2026-07-05/06); Architecture suite green (133/133 as of 2026-07-06 incl. EventRegistryCompleteness); AD-006 opened (legacy Membership test factory, pre-existing).*
