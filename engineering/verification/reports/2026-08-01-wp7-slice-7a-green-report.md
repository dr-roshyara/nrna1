# WP-7 Slice 7A — GREEN Report

**Date:** 2026-08-01 · **Phase:** GREEN · **Authorization:** R-47 (ARB) · ARB verdict *"RED accepted; engineering may proceed with GREEN within the existing architectural authorization."*
**Status:** ✅ **7A GREEN — all 11 keystones pass, first run.** ⛔ **But the merge gate cannot complete, for an inconsistency that appears to PREDATE 7A** *(wording corrected after ARB review — see F-7A-1)*.
**Repository Integrity Gate:** ✅ PASSED.

> ## ⛔ FINDING F-7A-1 — a WP-6 interface/test inconsistency, surfaced today, dating from 2026-07-31 — and not introduced by 7A
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
> **It is worse than the fatal suggests.** After repairing the double, the test file *itself* fails: it constructs `AdjudicationProcessManager` **with 2 arguments where WP-6's constructor takes 5**. **`AdjudicationProcessManagerTest` — 4 tests — cannot execute today, and the file-level mismatch dates from 2026-07-31.** *(Whether the suite was actually run in the interval is not something this execution can show.)*
>
> ### ⚠️ Governance consequence — **wording corrected after ARB review**
>
> **What this execution establishes:** *the current execution uncovered a pre-existing inconsistency between WP-6's production interface and its supporting tests. It appears to predate slice 7A and should be investigated before relying on earlier gate evidence.*
>
> **What my first draft claimed, and should not have:** *"R-43's evidence was inaccurate at the time it was recorded."*
>
> **The flaw in that reasoning was specific: I conflated *"the gate would have failed"* with *"the evidence was inaccurate."*** The first is a claim about **code**; the second is a claim about **what someone did**. **If the gate was never executed at closure, the evidence line is *unsupported*, not *false*** — a materially different finding, and I cannot tell which from here.
>
> **What I can and cannot show:**
>
> | Claim | Status |
> |---|---|
> | The interface/test mismatch exists **today** | ✅ **proven** — reproduced |
> | It **predates slice 7A** | ✅ **proven** — my diff touches Election only |
> | The two files were mutually inconsistent **from 2026-07-31** | ✅ **proven** — commit dates |
> | The GreenfieldCore suite **has included that path throughout** | ✅ **corroborated** — `phpunit.xml` unchanged since before 2026-07-30 |
> | **The gate was run at WP-6 closure and failed** | ❌ **NOT established** — I never reproduced the historical run |
> | **R-43's evidence was inaccurate when recorded** | ❌ **NOT established** — it requires the line above |
>
> **Establishing the last two means reproducing the gate at the WP-6 closure commit. I have not done that, and I will not assert a historical state I did not observe.**
>
> **Referred to the ARB for investigation. R-43 is not reopened, and no claim is made about its evidence.**

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
| **`composer merge-gate` completes** | ⛔ **NO — blocked by F-7A-1, an inconsistency that appears to predate 7A** |

**7A's own Definition of Done is not fully demonstrable until F-7A-1 is repaired**, and that repair is not mine to authorize. **I am not claiming a green gate I do not have.**

## 6. Recommendation to the ARB

1. **Authorize WP-6 remediation** as a small corrective slice — repair `AdjudicationProcessManagerTest` against the 5-argument constructor and re-run the gate. *(4 dead tests, one file.)*
2. **Investigate the pre-existing inconsistency** before relying on earlier gate evidence. **A definitive answer is cheap: reproduce `composer merge-gate` at the WP-6 closure commit.** Until that is done, **no claim should be made about R-43's evidence in either direction.** Note in mitigation either way: the delivered *production* code was independently verified by the Architecture suite, Deptrac and PHPStan, **all of which pass**; what is broken is a **unit test double**, not shipped behaviour.
3. **7A GREEN otherwise stands for review.**

**Note on C-1's classification, recorded as the ARB framed it:** the guard is **good enough for WP-7, not the final architectural solution** — it is regex-based over a hand-maintained adapter list, and should evolve toward an **AST-based fitness function**. Recorded as a known limitation, not presented as a finished mechanism.

**A quiet vindication worth naming:** F-7A-1 was invisible to eight architecture and governance commissions and surfaced within minutes of *running the gate*. **The ARB's instruction to stop reviewing and start executing produced better evidence than another review would have.**

---

**Traceability:** R-47 (slice 7A authorization) · R-46 (approved plan §5 slice 7A) · R-44 (A-1 — consumer-side port; its prediction now executed) · **AP-1** (fail closed — keystones 4–6, and no clamp in the adapter) · **AP-2** (one home — keystone 7, C-1b, and MAD's absence from the new config) · **R-D1** (cross-adapter agreement) · precedent `ConfiguredAdjudicationDurations` (mirrored exactly, per ARB direction) · `22d604844` and `ac5b34042` (F-7A-1's provenance). **No architecture reopened · no governance modified · no ruling reinterpreted · scope not extended beyond 7A.**
