# Test-estate disposition candidates — **CANDIDATES ONLY, NOTHING EXECUTED**

**Commission:** Product Owner · Session 1 · *"records and classifies · has not started deleting"*
**Date:** 2026-08-13 · **Checkpoint:** `5e116c02` · **Scope:** everything Session 1 has classified to date (**L3 = 240 / 1,376**)

> 🔴 **NO TEST HAS BEEN DELETED, REWRITTEN, RENAMED OR REPAIRED. NO PRODUCTION CODE, SCHEMA, MIGRATION, CONSTITUTION OR FIXTURE HAS BEEN CHANGED.** **This register exists so the candidates stop living scattered across eight batch entries — it is an input to a Phase-2 disposition decision, not a work order.**

**Adopted lifecycle, recorded so the boundary is unambiguous:**

```
Phase 1 EVIDENCE (Session 1, current)  →  Phase 2 DISPOSITION (PO/ARB)  →  Phase 3 CLEANUP (a separate session, re-baselined afterwards)
```

**The frozen 1,376 baseline is what makes Phase 1 meaningful.** **If Session 1 repaired, deleted or re-denominated as it went, the question *"what did the original estate actually verify?"* would become permanently unanswerable.**

---

## A · Retirement candidates — stale, verifying nothing

| Row | Evidence | Why NOT auto-deleted |
|---|---|---|
| `ElectionLifecycleFacadeTest::facade_preserves_canTransitionTo_for_backward_compatibility` | **ERROR** — *"Call to undefined method `ElectionLifecycle::canTransitionTo()`"*. It asserts a backward-compatibility affordance **that does not exist** | **Its removal presumes the BC affordance was intentionally dropped.** **That is an architectural fact I have not established** — the method may have been removed deliberately, or lost. **Retirement candidate, pending that determination.** |

## B · Conformance candidates — the TEST is right, the CODE does not comply

| Row | Evidence | Disposition question |
|---|---|---|
| `ElectionLifecycleFacadeTest::facade_guards_queries_against_deprecated_fields` | **FAILURE** — `assertQueryAllowed(['status' => 'active'], …)` does not throw `DeprecatedQueryException` | **The intent is ALREADY ADOPTED** (`ADR_20260807_1500`: the status column is a compatibility cache, not truth). 🔴 **This is NOT a cleanup candidate — deleting it would erase a legitimate expectation.** **It is a conformance gap: adopted intent, absent enforcement.** |

## C · Keep as RED — the rule is adopted, the implementation is in flight

| Row | Status |
|---|---|
| `VotingButtonsStateMachineIntegrationTest::open_voting_rejects_if_missing_candidates` | **FAILURE, and MUST STAY RED.** Its expectation now agrees with **adopted `EM-VOT-002`**. ⚠️ **Greening it by expecting `voting_window_defined` would permanently erase the only trace of the rule.** **Note it will still fail after implementation — its fixture never defines a voting window — so a fixture prerequisite belongs to whoever implements `EM-VOT-002`, under strict TDD.** |

## D · Blocked on an open business decision — touch neither way

| Row | Blocked on |
|---|---|
| `ConstitutionalTransitionGuardTest::guard_validates_preconditions` | 🔴 **`SD-15` OPEN.** It asserts `complete_administration` requires **`has_committee_members`**; the Constitution requires `['has_posts','has_voters','has_chief']` — **while the action's own description says *"Complete voter import and committee setup"***. **If SD-15 = YES the Constitution gains a precondition; if NO the test AND the description are wrong. Either disposition is premature.** |

## E · Duplication candidates — two rows, one claim

| Pair | Note |
|---|---|
| `ElectionSuspensionTest::suspend_stores_governance_metadata` **·** `::test_suspend_stores_governance_metadata` | apparently the same claim under two names |
| `ElectionCreationTest::test_draft_election_state_is_draft` **·** `::test_election_defaults_to_draft_state` | apparently the same claim |

**NOT confirmed as redundant** — I compared their names and subjects, **not their full assertion sets**. **Confirming true redundancy is Phase-2 work.**

## F · Rename candidates — valid tests, misleading names **(8 measured)**

**These verify something real; their NAMES misdescribe it. Deletion would be wrong; renaming is the disposition question.**

| Row | Name claims | Assertion does |
|---|---|---|
| `ElectionPolicyStateAwareTest` ×4 `..._for_officer` | officer authority | **no actor participates** — asserts `snapshot->canEdit` |
| `..._to_results_pending` | state `results_pending` | asserts **`counting`** — `results_pending` **is not one of the 12 states** |
| `open_voting_transitions_from_nomination_to_voting` | from nomination | asserted pre-state is **`ready_for_voting`** |
| `open_voting_is_idempotent_with_concurrent_requests` | concurrency | **two sequential HTTP calls**, no lock exercised |
| `test_small_election_saves_..._for_auto_approval` | approval routing | **persistence only** (`expected_voter_count => 35`) ⚠️ **and if `EM-OPEN-019` resolves to 30, the name becomes FALSE** |

## G · Coverage-gap candidates — rows that appear to cover a capability but do not

| Rows | Gap |
|---|---|
| `ElectionPolicyStateAwareTest::test_view_results_allowed_in_results_state` · `::test_view_results_denied_before_publication` | **Neither verifies whether results may be VIEWED.** One asserts the state it just set up; the other asserts `canPublishResults` and its own null fixture. **A C11 results-visibility gap concealed behind two PASSED rows.** **Disposition is ADDITION or REWRITE — not deletion** |

## H · Comment-hygiene candidates — stale references to a dropped constraint

**The composite FK `election_memberships → user_organisation_roles` was dropped on 2026-05-19 (Phase C.1). Three artifacts still describe it as live:**

| Artifact | |
|---|---|
| `tests/Feature/Election/ElectionShowControllerTest:54` | *"FK constraint: (user_id, organisation_id) must exist in user_organisation_roles"* |
| `tests/Feature/Election/VoterEligibilityTest:110` | *"seeds the UserOrganisationRole row required by the election_memberships FK"* |
| the CREATE migration | historical record — **must NOT be edited** |

> **These three stale artifacts are why I wrongly published `CF-1`/`CF-2`.** **Beyond the comments, the FIXTURE ROWS they justify may now be unnecessary — but that is a Phase-2 determination, since removing them could break tests for reasons unrelated to the FK.**

## I · Frame anomaly — reported, not resolved

**`Tests\Unit\Domain\Election\Security\OverlaySignalCategoryTest` appears in the file system but shows ZERO rows in the matrix.** **Cause NOT ESTABLISHED** — it may contain no test methods, or may have been absent from the `--list-tests` run that defined the universe. **Recorded as an anomaly to investigate, NOT a cleanup candidate.**

## What this register deliberately does NOT do

**No deletion · no rename · no repair · no fixture edit · no denominator change · no reclassification of any PASSED row.** **No candidate is asserted to be redundant, obsolete or wrong without the evidence stated beside it.** **Categories B and C are explicitly flagged as tests that must NOT be removed**, because a *"failing test → delete test"* reflex would destroy exactly the evidence this programme was convened to produce.

---

**DISPOSITION CANDIDATES REGISTERED — PHASE 2 REMAINS WITH THE PRODUCT OWNER / ARB**
**`L3` = 240/1,376 · `1,376` FROZEN · `SD-15` OPEN · NOTHING EXECUTED**

**Traceability:** batch entries in `docs/plans/20260808-1030-election-verification-execution-plan.md` (commits `c64e1ef1` → `5e116c02`) · `ADR_20260807_1500` · `EM-VOT-002` ruling artifact · Phase C.1 migration `2026_05_19_000001` · `SD-15` · `EM-OPEN-019`
