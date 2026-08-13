# Election-Only independent verification — **P0: `EM-VOT-002` / `PBDIGIT-64`**

**Commission:** Product Owner · Session 1 · **independent verification; no repair, no implementation, no disposition executed**
**Date:** 2026-08-13 · **Verified against:** `f2c2cc4e` *(PBDIGIT-64)* · **Baseline:** frozen 1,376 · **L3:** 240/1,376

> **Session 3's GREEN report was NOT consumed. The code was read, the predicates compared, and an independently chosen regression target was executed.**

---

## 1 · Verdict up front

| | |
|---|---|
| **`EM-VOT-002` enforcement** | ✅ **VERIFIED on BOTH paths** |
| **New lifecycle state invented?** | ✅ **NO** — `EM-OPEN-021` not pre-empted by a chosen state |
| 🔴 **Unreported consequence** | **The fall-through terminates in `InvalidElectionStateException`. 4 in-universe rows regressed from a 24/24 PASSED baseline (2 ERROR · 2 FAILURE), and Session 3's commit reports only its own "8/8"** |

## 2 · What I verified, and how

**Both enforcement points use the SAME predicate — compared side by side, not assumed:**

```php
// command path — ConstitutionalTransitionGuard::isPreconditionMet
'has_approved_candidates' => $election->candidacies()->where('status','approved')->exists(),

// computed path — ElectionLifecycleEngineImpl::hasCandidatesApproved
return $election->candidacies()->where('status','approved')->exists();
```

✅ **Both require `status = 'approved'`, so a PENDING or DRAFT candidacy does NOT satisfy the rule.** **The distinction the commission warned about — *candidate exists* vs *approved candidate exists* — is correctly implemented in both places.**

**Command path:** `RULES['open_voting'].preconditions` = `['voting_window_defined','timezone_set','has_approved_candidates']`.
**Computed path:** `if ($this->isVotingWindowOpenNow($election) && $this->hasCandidatesApproved($election))`.

**`EM-OPEN-021` structurally untouched:** the commit does not modify `ElectionLifecycleState`, which still carries **exactly the 12 members** recorded at programme start. **No substitute state was introduced.**

## 3 · 🔴 The finding — falling through does not land safely

**Independently chosen target:** `CurrentBehaviorTest` — because **I had myself identified `test_election_with_voting_window_open_derives_to_voting_active` as the in-universe proof that `voting_active` is reachable with no action and no candidates.** **If EM-VOT-002 is enforced on the computed path, that row must change behaviour.** It did.

| Baseline | Now |
|---|---|
| **24 / 24 PASSED** | **20 PASSED · 2 ERROR · 2 FAILURE** |

```
1) test_election_with_voting_window_open_derives_to_voting_active
   InvalidElectionStateException: Election … in invalid constitutional state.
   Check: approved_at=…, setup_started_at=…, administration_completed=1
   at ElectionLifecycleEngineImpl.php:164

2) test_close_voting_transition            ← same exception
3) test_voting_active_can_vote             Failed asserting that false is true
4) test_voting_active_allowed_actions      array does not contain 'close_voting'
```

**Session 3's commit states:** *"when unmet, no substitute state is chosen here: derivation falls through to the existing rules below (fallback semantics remain an open PO decision, EM-OPEN-021 untouched)."*

> **Declining to CHOOSE a state is correct governance. But the fall-through does not reach a safe default — it reaches a TERMINAL THROW at `ElectionLifecycleEngineImpl:164`.**
>
> 🔴 **So the de facto answer to `EM-OPEN-021` is already observable: an election with an OPEN VOTING WINDOW and ZERO APPROVED CANDIDATES has NO DERIVABLE STATE and raises `InvalidElectionStateException`.** **That is a behaviour, not an absence of one.**

### Why row 2 is the operationally serious one

**`test_close_voting_transition` now ERRORS.** Since lifecycle state is derived on **every** read, an election in this condition **cannot have its state resolved at all** — which plausibly reaches capability resolution, dashboards, and `close_voting` itself.

> ⚠️ **HYPOTHESIS, NOT ESTABLISHED: such an election may be UNMANAGEABLE — unable even to close voting, because derivation throws before any action can be evaluated.** **I have not traced every consumer, so this is a hypothesis carrying real operational risk, not a demonstrated defect.** **It is the single item I would put in front of the Product Owner first.**

## 4 · Classification, kept separate

| Category | Finding |
|---|---|
| **VERIFIED BUSINESS RULE** | `EM-VOT-002` enforced on both paths, approval-status-correct |
| **OBSERVED IMPLEMENTATION** | fall-through → `InvalidElectionStateException` |
| **TEST VERIFICATION** | 2 of the 4 regressions (`can_vote`, `allowed_actions`) are **arguably CORRECT new behaviour** — a candidate-less election *should not* be votable. **Their fixtures are now obsolete, which is Mission 9's LEGITIMATE category** |
| 🔴 **UNREPORTED REGRESSION** | **4 in-universe rows** moved off a 24/24 baseline; the commit reports only its own 8/8. **Session 3 appears not to have run the in-universe regression** |
| **BUSINESS DECISION — now URGENT** | **`EM-OPEN-021`.** Previously deferrable; the fall-through gives it an observable answer (*throw*), so deferral is no longer neutral |
| **IMPLEMENTATION DEFECT** | **NOT CLAIMED.** Whether "throw" is wrong depends on `EM-OPEN-021`, which is the PO's |

## 5 · What I did NOT do

**No production, Constitution, schema, migration, fixture or test change · no test greened, renamed or deleted · `EM-OPEN-021` not decided · no fallback state proposed · no fixture repaired · 1,376 unchanged · `L3` unchanged at 240 (verification is not classification) · Session 3 not authorised or blocked by me.**

## 6 · Evidence still required for a full readiness verdict

**This document covers P0 only.** Not yet verified: **P1** entitlement (`PBDIGIT-65`/`69`) · **P1** `PBDIGIT-62` · **P1** Election-Only lifecycle map · **P2** ballot/submission/one-vote · **P2** results/publication. **Sections 5–18 of the commissioned structure remain unpopulated and are NOT implied by section 1's verdict.**

**Also unmeasured: the FULL in-universe regression.** **I ran one independently chosen class, not 1,376 rows.** **The true regression count is `NOT ESTABLISHED` and may exceed 4** — every class asserting a window-open election without an approved candidate is a candidate.

## 7 · Next action, by session

| Session | Action |
|---|---|
| **Product Owner / ARB** | **`EM-OPEN-021` — now urgent.** What state applies when the window is open with zero approved candidates? *(Also still open: `SD-15` · `BR-1.12` · `SD-4` · `EM-OPEN-019`, whose implemented threshold is observably **40** — `capacity_eligibility`, *"Free plan (≤40 voters)"*)* |
| **Session 3** | run the **in-universe regression**, then classify each affected fixture as legitimately-obsolete vs merely-failing — **per Mission 9, not by adding data until green** |
| **Session 1** | measure the full regression count; then P1 |

---

**P0 VERIFICATION COMPLETE — `EM-VOT-002` ENFORCED ON BOTH PATHS · ONE UNREPORTED CONSEQUENCE ESCALATED**
**`EM-OPEN-021` UNRESOLVED AND NO LONGER NEUTRAL · NOTHING REPAIRED**

**Traceability:** `f2c2cc4e` · `ConstitutionalTransitionGuard::isPreconditionMet` · `ElectionLifecycleEngineImpl::hasCandidatesApproved` · `:164` throw · `ElectionConstitution::RULES['open_voting']` · `ElectionLifecycleState` (12 members, unmodified) · `CurrentBehaviorTest` baseline 24/24 (matrix) vs measured 20/2/2 · EM-VOT-002 ruling artifact · disposition register `2026-08-13-test-estate-disposition-candidates.md`
