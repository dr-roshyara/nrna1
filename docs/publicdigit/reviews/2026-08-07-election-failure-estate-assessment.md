# Election Failure Estate Assessment — PBDIGIT-48 follow-up

**Date:** 2026-08-07 · **Status:** ASSESSMENT ONLY — **no test, fixture or production code was changed**
**Commissioned by:** the Product Owner, after `PBDIGIT-62` proved the estate can hide a live production defect
**Universe:** `tests/Unit/Domain/Election` · `tests/Unit/Application/Election` · `tests/Architecture/Election` · `tests/Architecture/ElectionStateMachineConsistencyTest` · `tests/Feature/Election`
**Measured:** **814 completed — 668 passed, 146 failed** · plus **12 incomplete** and **501 risky**

> **Established before anything else:** the 146 failures are **identical with the migration applied and with the five migrated files reverted** (146/668 both runs). **They are not migration regressions.** That fact is what makes classification — rather than repair — the correct next act.

---

## Method, and its honest limit

Clustered by **error signature and reached code**, not by filename, per the commission. **146 individual verdicts were not attempted** — the evidence for that does not exist without per-test investigation, and guessing was forbidden. Clusters below carry a **confidence** column; everything unproven is **G — UNKNOWN**, which is a finding, not a gap to be filled with inference.

## Cluster table

| # | Cluster (error signature) | Count | Class | Business invariant at stake | Authority | Risk | Proposed action |
|---|---|---|---|---|---|---|---|
| 1 | **Undefined methods** — `ElectionMembership::bulkAssignVoters`, `VotingTrustResult::allow`, `ElectionLifecycle::canTransitionTo` (`BadMethodCallException`, `Call to undefined method`) | **~26** | **F** | none — the API the test calls does not exist | n/a | 🟢 none | Decide per method: unbuilt feature vs renamed API. **Do not delete tests** — several may be specifications for unbuilt capabilities |
| 2 | **`VoterNotEligibleException`** | **16** | **D or B — UNRESOLVED** | *only eligible voters may vote* | `VoterEligibilityService` / `EligibilityEvaluator` (**authority unresolved — MB-5 / `PBDIGIT-49`**) | 🔴 **high** | **Investigate before anything else.** Either fixtures fail to make voters eligible, or eligibility genuinely rejects valid voters. `PBDIGIT-62` is the precedent for assuming the latter is possible |
| 3 | **Database integrity** — `QueryException`, FK violations (23503), unique violations (23505) | **~29** | **B / F** | none directly — fixture construction | n/a | 🟡 medium | Fixture repair. **Same family as the `members_status_check` and `type` defaults already fixed** — these fixtures never expressed valid domain state |
| 4 | **`Failed asserting that false is true`** | **22** | **G — UNKNOWN** | unknown without per-test reading | — | ⚠️ unknown | **Must be opened individually.** This signature is exactly what `PBDIGIT-62` looked like from the outside |
| 5 | **Deprecation-enforcement tests** — `DeprecatedQueryException` / `DeprecatedFieldException` not thrown | **8** | **E — ahead of the ladder** | *the guard blocks deprecated access at the configured level* | `DeprecationPolicy` | 🟢 none | **Do not "fix".** `STRICT_LEVEL = 1`; `isEnforcementActive($n)` returns `STRICT_LEVEL >= $n`, so level-2+ guards are **inactive by design**. These tests assert a **future authorised state** and will pass when `PBDIGIT-48` step 4 raises the level. **They are a readiness signal, not debt** |
| 6 | **`403 is identical to 200`** — create page, timeline view, import preview | **6** | **B or D — UNRESOLVED** | *only authorised officers may reach these pages* | `ElectionPolicy` / middleware | 🔴 **high** | Investigate. A test expecting 200 and getting 403 is usually fixture role setup — **but the inverse direction would be an authorisation bypass**, so confirm the direction per test |
| 7 | **`InvalidTransitionException`** | **8** | **A / E — likely** | *only constitutional transitions are permitted* | `ElectionConstitution` | 🟡 medium | Likely tests driving transitions the constitution now forbids — i.e. the architecture working. Confirm per test before touching |
| 8 | **`ModelNotFoundException`** | **10** | **B / F** | none directly | n/a | 🟡 medium | Same shape as the `PBDIGIT-61` route-binding defect — **check whether any is a contract mismatch rather than a fixture gap** |
| 9 | **Harness/infrastructure** — `RouteNotFoundException` (6), `BindingResolutionException` (6), `ReflectionException` (3), `warning() on null` (3) | **~18** | **F** | none | n/a | 🟢 none | Harness repair. `warning() on null` is the **same shape as the `login` channel defect already fixed** — likely another undefined log channel |

**Counts are per error signature and overlap slightly** (a test can emit two). They characterise the estate; they are not a partition.

## Failure Estate summary

| Category | Count |
|---|---|
| **A** Legacy representation assumption | **0 confirmed** *(cluster 7 may contribute)* |
| **B** Stale fixture | **~29 likely** (cluster 3), plus part of 6 and 8 |
| **C** Genuine migration regression | **0 — proven by the revert experiment** |
| **D** Pre-existing production defect | **0 confirmed, 2 clusters suspected** (2 and 6) |
| **E** Obsolete/ahead-of-ladder contract | **8 confirmed** (cluster 5), plus likely part of 7 |
| **F** Infrastructure / harness | **~44** (clusters 1 and 9) |
| **G** Unknown | **22** (cluster 4) — plus every unconfirmed item above |

## Highest-risk items — production investigation, not test cleanup

1. **Cluster 2 — `VoterNotEligibleException` ×16.** The invariant is *only eligible voters may vote*; its authority is **unresolved** (`PBDIGIT-49`/MB-5 record two competing homes). If production wrongly rejects eligible voters, that is **a voter unable to vote in a real election** — the most severe failure this platform has. **This is where `PBDIGIT-62`'s lesson applies hardest.**
2. **Cluster 6 — authorisation 403/200 ×6.** Direction matters absolutely: expecting 200 and receiving 403 is usually a fixture; the reverse would be a bypass. **Must be read per test, never in bulk.**
3. **Cluster 4 — 22 bare assertion failures.** `PBDIGIT-62` presented exactly this way. **Unknown is not low-risk.**

## Tests that should be PRESERVED

* **All of cluster 5** — they encode the *approved* end-state of the deprecation ladder. Deleting them would erase `PBDIGIT-48`'s completion criterion.
* **Cluster 2 and 6** until their direction is established.
* **Any cluster-1 test that specifies an unbuilt capability** — a specification is not debt.

## Tests that should be MIGRATED (not deleted)

Cluster 3, and the fixture-side of clusters 6 and 8 — fixtures that never expressed valid domain state (the same defect already corrected in `HasActiveElectionTest`, `NewsletterCreationTest` and `DashboardResolverElectionPriorityTest`).

## Tests that may EVENTUALLY be deleted — none yet

**No test is proposed for deletion.** Not one has been shown to assert a retired *business rule*, as opposed to using a retired *field*. **A test mentioning `status` is not thereby a legacy test** — the only confirmed legacy assertion so far (`future_active_election_is_counted`) was **rewritten, not deleted**.

## Proposed tickets (evidence-backed, not yet created)

| Proposed | Evidence | Why separate |
|---|---|---|
| **Voter eligibility failure investigation** | 16 `VoterNotEligibleException` in one domain whose authority is already flagged unresolved | Highest customer risk; belongs with `PBDIGIT-49`, not with test cleanup |
| **Election fixture modernisation** | ~29 DB-integrity failures | Mechanical, low risk, parallelisable |
| **Election harness repair** | ~44 undefined-method/binding/route failures | Not domain work at all |

## STOP

Classification complete to the limit of available evidence. **No test deleted, no fixture rewritten, no production code changed, no assertion weakened.** Cluster 5 in particular must **not** be "fixed" — it is measuring the enforcement ladder, which is deliberately at level 1.

**Returned for authorisation of the next work package.**
