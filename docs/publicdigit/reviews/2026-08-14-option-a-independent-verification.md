# Option-A repair — **independent verification (Session 1)**

**Date:** 2026-08-14 · **Verified:** `5d46498e` against grant v5 (`f6bb5504`), boundary v2 (`3499ea38`), RED (`32215fea`), TE6 pin (`ac313368`) · **Baseline:** `SD-1` = 1,376 · `L3` = 240
**Session 3's report was NOT consumed as evidence — every result below was observed independently.**

---

## Verdict

> ✅ **Option-A implementation INDEPENDENTLY VERIFIED.** The six-clause invariant holds where the grant claims it; the diff is inside the approved boundary; the acceptance suite passes under my own run; the P6 defect scenario now yields the correct answers with a warm cache; the bounded regression check shows no change from the frozen baseline. **One OUT-OF-GRANT observation confirmed and left untouched (`start()`).**

## A · Diff audit — VERIFIED

`git show 5d46498e`: **3 production files, +18/−3** — `User.php` · `ElectionVotingController.php` · `ElectionMembership.php`. **No `BelongsToTenant` trait change · no middleware change · no credential-layer change · no infrastructure refactor · nothing out of scope.** The three touched aspects are **parts of the two granted violations** (predicate · projection · the cache identity both share), **not three independently authorized defects.**

## B · Scope mechanics — VERIFIED

The trait registers exactly one scope named **`'tenant'`** (`BelongsToTenant.php: addGlobalScope('tenant', …)`). The repair removes **only** that scope — `withoutGlobalScope('tenant')`, not `withoutGlobalScopes()` — so **SoftDeletes remains in force** (proven behaviourally by TE6). The required organisation is obtained **from the Election itself** (`Election::withoutGlobalScope('tenant')->select('organisation_id')->whereKey($electionId)` subquery); **no `TenantContext`/session value participates in the entitlement evaluation.**

## C · Cache transition — VERIFIED

Identity remains **(voter, election)**: `user.{id}.voter.v2.{election_id}` — deliberately **not** made tenant-dependent, correct because the answer is now context-free. **All 3 key sites migrated** (remember + both forget sites incl. `ElectionMembership::booted()`); **zero old-namespace stragglers** (measured). The `.v2` namespace makes every pre-repair entry — including poisoned ones — unreachable.

## D · Acceptance suite — run independently

**`VotingEntitlementAmbientInvarianceTest`: 8 tests · 27 assertions · 0 failures** — all eight commissioned scenarios present by name (TC1 mismatch denied · TC2 absent credential denied · TE1–TE4 ambient invariance incl. warm-cache sequence and entry projection · TE5 non-member false everywhere · TE6 soft-deleted election ineligible).
⚠️ **Reporting discrepancy, noted not judged: Session 3's report said "7 tests / 20 assertions"; I measure 8/27** — consistent with the TE6 pin (`ac313368`) landing after that report was written.

## E/F · Independent reproduction — the P6 mirror

Same construction as P6 (throwaway rows, testing DB, cleaned up):

```
membership: role=voter, status=active, org A          cache key cleared (v2)
ambient B, A, B, A — warm cache throughout  →  TRUE, TRUE, TRUE, TRUE
non-member, ambient B then A (warm)         →  FALSE, FALSE
```

**The original defect read `false, false, true`. The repaired system answers the entitlement fact identically under every ambient context, and the warm cache now transports a CORRECT answer.** Deny-poisoning is not reproducible: no wrong-context answer exists to poison with, and pre-repair entries are namespace-isolated.

## G · Bounded regression — VERIFIED, no change from baseline

* **`EM-VOT-002` suites: 8/8 PASSED** — the previously verified rule is intact.
* **`ElectionShowControllerTest`: 12 tests, 5 failures — the SAME FIVE names as the frozen baseline** (compared name-by-name, not by count). **PRE-EXISTING / UNRELATED — not regression.**
* Full-universe re-run **not** performed (bounded per commission; pre-change evidence stands as the baseline).

## H · OUT-OF-GRANT OBSERVATION — `ElectionVotingController::start()`

**Confirmed real:** `start()` (`:104`) resolves the election unscoped, then at `:113-115` runs `$user->electionMemberships()->where('election_id', …)->first()` — **the ambient-scoped lookup pattern, with no bypass.** **It IS on the voting path** (the entry action that creates the voter slug). **Consequence class: same false-denial family — a two-org voter in the wrong ambient context may be refused entry.** **NOT repaired, NOT included in the Option-A verdict, and no repair is recommended — it is a potential follow-up requiring its own authorization.** *(Runtime reproduction not performed; static confirmation only.)*

## Classification summary

| | |
|---|---|
| **VERIFIED** | diff boundary · scope mechanics · SoftDeletes preservation · election-derived organisation · cache identity + migration · all 8 acceptance scenarios · P6-mirror behaviour · EM-VOT-002 intact · consumer-class failures unchanged from baseline |
| **NOT VERIFIED** | nothing within the grant failed verification |
| **OUT-OF-GRANT OBSERVATION** | `start()` ambient-scoped membership lookup (§H) · the wider family items already registered (§5a out-of-scope list) |
| **PRE-EXISTING / UNRELATED** | `ElectionShowControllerTest`'s 5 baseline failures |
| **REGRESSION** | **none demonstrated** |

**Nothing repaired, modified or recommended by this verification. `SD-1` = 1,376 · `L3` = 240 · `EM-OPEN-021` untouched. Session 1 STOPS.**

**Traceability:** `5d46498e` diff · `BelongsToTenant.php:44-46` · `User.php` (v2 key + subquery) · `ElectionMembership.php:243-248` · `ElectionVotingController:104-115` · acceptance run (8/27) · mirror A/B values above · frozen manifest (failure-name comparison) · grant v5 `f6bb5504` §5a
