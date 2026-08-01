# WP-7 Slice 7A — GREEN Report

**Date:** 2026-08-01 · **Phase:** GREEN · **Authorization:** R-47 (ARB) · ARB verdict *"RED accepted; engineering may proceed with GREEN within the existing architectural authorization."*
**Status:** ✅ **7A GREEN — all 11 keystones pass, first run.** ⛔ **But the merge gate cannot complete, for a PRE-EXISTING defect in accepted work.**
**Repository Integrity Gate:** ✅ PASSED.

> ## ⛔ FINDING F-7A-1 — the merge gate has been RED since 2026-07-31, and it is not mine
>
> `composer merge-gate` **fatals** in the GreenfieldCore suite:
>
> ```
> Class Tests\Support\Adjudication\InMemoryAdjudicationProcessStore contains 1 abstract
> method and must therefore be declared abstract or implement the remaining methods
> (AdjudicationProcessStore::latestForChallenge)
> ```
>
> **Provenance, established from dates rather than assumed:**
>
> | Fact | Evidence |
> |---|---|
> | `latestForChallenge` added to the port | `22d604844` — **2026-07-31, WP-6 GREEN** |
> | The in-memory double last touched | `ac5b34042` — **2026-07-30, WP-2 GREEN** |
> | My working tree | **Election files only** — the Adjudication double is untouched by 7A |
>
> **A class last edited on the 30th cannot implement a method added on the 31st.** WP-6 updated the port and the production adapter **but not the test double**.
>
> **It is worse than the fatal suggests.** After repairing the double, the test file *itself* fails: it constructs `AdjudicationProcessManager` **with 2 arguments where WP-6's constructor takes 5**. **`AdjudicationProcessManagerTest` — 4 tests — has been entirely dead since 2026-07-31.**
>
> ### ⚠️ Governance consequence, stated plainly
>
> **R-43 accepted WP-6 on evidence reading *"all gates pass."* That was inaccurate at the time it was recorded** — `composer merge-gate` could not have completed. **I am not reopening R-43**; acceptance is the ARB's act and stands until the ARB says otherwise. **I am reporting that one line of its evidence does not hold, because concealing it would be the Governance Verification Drift this programme has already named.**

---

## 1. 7A's own result — GREEN, first run

```
Slice 7A keystones + C-1 guard:   11 tests, 14 assertions — OK
Deptrac:                          0 errors  (119 uncovered, 668 allowed, 0 warnings)
PHPStan (greenfield, level max):  No errors
Architecture suite:               149 tests, 632 assertions — OK
```

| Keystone | Result |
|---|---|
| 1 · CW resolves per election type | ✅ |
| 2 · LSM resolves per election type | ✅ |
| 3 · organisation override wins | ✅ |
| 4 · missing CW **throws, never defaults** | ✅ |
| 5 · non-numeric LSM throws | ✅ |
| 6 · non-positive CW throws, **not clamped** | ✅ |
| 7 · MAD **consumed, not copied** (AP-2) | ✅ |
| R-D1 · both adapters agree on MAD | ✅ |
| C-1a/b/c · guard + its discrimination self-test | ✅ |

## 2. A-1's evidence limit is now CLOSED

The package labelled one limit on D2: *"Deptrac passes unmodified" is an **analytical prediction, not an executed result** — no code exists.*

> **It is now executed. Deptrac reports 0 errors with `deptrac.yaml` unmodified**, against real code in `app/Contexts/Election/`. **The prediction became a measurement, and it held.**

**Why it holds:** no production file imports across contexts. Election's adapter reads **`config/adjudication.php`** — a governance-owned *configuration value*, not Adjudication's *code*. **Both contexts are downstream of Q-2, not of each other.**

## 3. What was written

| File | Role |
|---|---|
| `app/Contexts/Election/Application/Port/EvidencePreservationDurations.php` | consumer-side port — CW · MAD · LSM in Election's language |
| `app/Contexts/Election/Infrastructure/Config/ConfiguredEvidencePreservationDurations.php` | adapter — precedence **organisation → election type → default**, **fail closed** |
| `config/election_preservation.php` | CW + LSM, INTERIM-marked · **MAD deliberately absent** |
| `ElectionServiceProvider` | one binding added |

**Mirrors the precedent exactly, as directed.** `ConfiguredEvidencePreservationDurations` differs from `ConfiguredAdjudicationDurations` only in **vocabulary, owned parameters and config keys** — same precedence algorithm, same `is_numeric` rejection, same `< 1` throw, same `DateInterval` construction. **No new resolution algorithm was invented.**

**AP-1 honored structurally:** no `max()`/`min()`, no `??` to a literal, no `env()` in the adapter — and **C-1 now watches this file**, so a future clamp fails a test rather than a review.

**AP-2 honored:** `config/election_preservation.php` carries **no MAD key**, and says so in a comment explaining why. C-1b asserts it.

## 4. What I changed outside 7A — one mechanical repair, disclosed

`tests/Support/Adjudication/InMemoryAdjudicationProcessStore.php` — added the missing `latestForChallenge`, returning the latest state **unfiltered by terminality** (which is the method's whole purpose: distinguishing a redelivered decision on a *concluded* process from a late one on an *expired* process).

**It is correct and will be needed regardless — but it does not unblock the gate on its own**, because the test file's constructor call is also stale. **I stopped there.** Rewriting `AdjudicationProcessManagerTest` for WP-6's 5-argument constructor is **WP-6 remediation in accepted work** — real work requiring its own authorization, not a fold-in.

## 5. What 7A cannot claim

| Claim | Status |
|---|---|
| 7A's keystones pass | ✅ **yes** |
| Deptrac · PHPStan · Architecture pass | ✅ **yes** |
| **`composer merge-gate` completes** | ⛔ **NO — blocked by F-7A-1, which predates 7A** |

**7A's own Definition of Done is not fully demonstrable until F-7A-1 is repaired**, and that repair is not mine to authorize. **I am not claiming a green gate I do not have.**

## 6. Recommendation to the ARB

1. **Authorize WP-6 remediation** as a small corrective slice — repair `AdjudicationProcessManagerTest` against the 5-argument constructor and re-run the gate. *(4 dead tests, one file.)*
2. **Note against R-43** that its *"all gates pass"* line did not hold. **Whether that affects the acceptance is the ARB's call, not mine** — the delivered *production* code was independently verified by the Architecture suite, Deptrac and PHPStan, all of which do pass; what failed is a **unit test double**, not shipped behaviour.
3. **7A GREEN otherwise stands for review.**

**Note on C-1's classification, recorded as the ARB framed it:** the guard is **good enough for WP-7, not the final architectural solution** — it is regex-based over a hand-maintained adapter list, and should evolve toward an **AST-based fitness function**. Recorded as a known limitation, not presented as a finished mechanism.

**A quiet vindication worth naming:** F-7A-1 was invisible to eight architecture and governance commissions and surfaced within minutes of *running the gate*. **The ARB's instruction to stop reviewing and start executing produced better evidence than another review would have.**

---

**Traceability:** R-47 (slice 7A authorization) · R-46 (approved plan §5 slice 7A) · R-44 (A-1 — consumer-side port; its prediction now executed) · **AP-1** (fail closed — keystones 4–6, and no clamp in the adapter) · **AP-2** (one home — keystone 7, C-1b, and MAD's absence from the new config) · **R-D1** (cross-adapter agreement) · precedent `ConfiguredAdjudicationDurations` (mirrored exactly, per ARB direction) · `22d604844` and `ac5b34042` (F-7A-1's provenance). **No architecture reopened · no governance modified · no ruling reinterpreted · scope not extended beyond 7A.**
