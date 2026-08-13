# Election-Only independent verification — **P6: `PBDIGIT-65`/`69` reproduction**

**Commission:** Product Owner · Session 1 · **falsification only; nothing repaired, no ownership decided, no repair scope selected**
**Date:** 2026-08-13 · **Baseline:** frozen 1,376 · **L3:** 240/1,376 *(unchanged)*

---

## 1 · Verdict

> 🔴 **REPRODUCED — both defects, in one controlled A/B, against the current implementation.**
>
> **The original `PBDIGIT-65` mechanism (tenant scope hides a valid membership) and the `PBDIGIT-69` mechanism (tenant-unaware cache replays the wrong answer into the correct context) are both intact and both demonstrated at runtime today — on the VOTING-TIME predicate that gates the live voting stack.**

## 2 · The original scenario — recovered from the tickets, not inferred

**`PBDIGIT-65`** (2026-08-09, runtime-proven A/B): a voter in **two organisations** holds a valid `ElectionMembership` (`role=voter`, `status=active`) for an election of org X; the **session's `current_organisation_id` points at org Y**; `GET /elections/{slug}` → `ElectionVotingController:41-44` computes `isEligible = false`, because `ElectionMembership` carries `BelongsToTenant` and the global scope filters by `TenantContext::get() ?? session('current_organisation_id')`. *"A voter's right to vote must not depend on which page they visited last."*

**`PBDIGIT-69`** (2026-08-12, measured both directions): `isVoterInElection()` caches a **tenant-dependent answer** under a **tenant-free key** `user.{id}.voter.{election_id}` (TTL 300) — deny-poisoning: a wrong-context read is replayed to the correct context.

## 3 · Falsification target — written before execution

```
GIVEN a user with a valid ElectionMembership (role=voter, status=active) in election E of org A
AND   tenant context set to org B                      (the ticket's "wrong page" state)
WHEN  the voting-time predicate isVoterInElection(E) is asked
THEN  under the TICKET CLAIM it wrongly answers FALSE  (65)
AND   after switching to the CORRECT context org A with the cache still warm
      it STILL answers FALSE                            (69 deny-poisoning)
AND   only clearing the cache in the correct context yields TRUE
```

**Ticket-claim vs business semantics kept separate:** the tickets' expected business behaviour (*same voter + same election ⇒ same answer, independent of navigation*) is stated in `PBDIGIT-65`'s acceptance criteria and was **not in dispute**; no substitution of today's architecture was needed.

## 4 · Execution — measured, with the actual values

**Method:** `artisan tinker --env=testing` against `nrna_test` (throwaway rows, cleaned up after). **`TenantContext::set()` used directly — it is PRIORITY 1 in the scope** (`BelongsToTenant.php:46`), ahead of the session fallback, so this exercises the ticket's exact mechanism one level below HTTP.

```
row: election_id=a27eb224-ce9a-4289-a106-19825dd75342
     org=a27eb224-a2c7-49cc-a0c9-cac30da6b849  role=voter  status=active

1. WRONG tenant (orgB), cold cache      -> isVoterInElection = false   ← 65 REPRODUCED
2. CORRECT tenant (orgA), WARM cache    -> isVoterInElection = false   ← 69 REPRODUCED
3. CORRECT tenant (orgA), cache cleared -> isVoterInElection = true    ← proves scope+cache, not the row
```

**Same user, same election, same membership row across all three steps. Only tenant context and cache state changed.** Steps 2 vs 3 are the identical context — only the cache differs — which is `PBDIGIT-69`'s own isolation method.

## 5 · Static confirmation — every ticket chain-link still present

| Ticket link | Current code | Unchanged? |
|---|---|---|
| `ElectionMembership` uses `BelongsToTenant` | `ElectionMembership.php:32` | ✅ |
| Scope filters by `TenantContext::get() ?? session('current_organisation_id')` | `BelongsToTenant.php:46` | ✅ |
| `isVoterInElection` cache key has no tenant component | `User.php:320` — `user.{id}.voter.{electionId}`, TTL 300 | ✅ |
| Original 65 site computes `isEligible` from the scoped relationship | `ElectionVotingController:37-44` — verbatim | ✅ |

**One thing HAS changed since the tickets:** `ElectionMembership::booted()` now clears caches on save/delete (`:243-248`). **That addresses stale-after-WRITE only. It cannot prevent 69's deny-poisoning, which is created by a READ in the wrong context — as step 2 demonstrates.**

## 6 · Classification

| Question | Answer |
|---|---|
| **Result** | 🔴 **REPRODUCED** (both tickets) |
| **Admission-time or voting-time?** | **VOTING-TIME.** The reproduced predicate is `isVoterInElection` — the same one `EnsureElectionVoter` calls on the live 12-middleware stack (P5 §2). **Admission-time is unaffected:** `EloquentVoterEligibilityQueryService` queries `organisation_users`/`members` via `DB::table()` — no Eloquent model, no global scope *(CF-3 trace)* |
| **Ambient-context involvement** | ✅ **YES — this IS the defect.** The eligibility answer depends on `TenantContext`/session, an infrastructure concern, exactly as the ticket classified it |
| **Boundary violated** | a valid election-scoped entitlement is invisibly filtered by an organisation-scoped persistence concern; the cache then transports the wrong answer **across** tenant contexts |

## 7 · ⛔ Correction to my own P5 — disclosed

**P5 stated the voting-time decision involves *"no `organisation_id`, no tenant session key."* That claim is WRONG and is withdrawn.** I traced the predicate's **explicit** `where` clauses and missed the **model-level global scope**, which invisibly injects `where organisation_id = <tenant context>` into the same query. **Twelfth incident, and the same family as the others: the evidence was one layer below where I looked.** P5's cross-election-boundary finding survives — the `election_id` scoping is real — **but its "no ambient contamination" headline does not, and P6's measurement is the corrected record.**

## 8 · Evidence limitations

* **The HTTP-level replay (login redirect → session tenant → `GET /elections/{slug}`) was NOT re-run**; the reproduction enters at `TenantContext`, which the scope consults **first**. The ticket's own A/B already proved the HTTP entry; P6 proves the mechanism persists.
* The reproduction ran on the **testing** database with throwaway rows (removed afterwards). No production data involved; development database untouched.
* **The 300 s cache participated** (step 2). Per commission §10, no separate cache-semantics investigation was opened.

## 9 · Governance handoff

| Not decided here | Owner |
|---|---|
| **Decision A** — ownership of voting-time eligibility *(runtime location `User.php` + middleware is EVIDENCE, not ownership)* | governance / PO / ARB |
| **Decision B** — repair scope *(these two sites vs the `BelongsToTenant` family)* | Session 2 / PO |
| The three repair locations named in the ticket (route-level tenant · scope applicability · unscoped query) | **none selected — each remains defensible** |
| `EM-OPEN-021` | unchanged, untouched |

**The six untracked `ElectionOnlyEntitlementPinTest` rows were NOT used as evidence, not run, not modified.**

---

**P6 COMPLETE · RESULT: REPRODUCED · STOPPING**
**Nothing repaired · no ownership decided · no repair scope selected · no production, test, fixture, configuration, Constitution, schema or migration change · `SD-1` = 1,376 · `L3` = 240**

**Traceability:** `PBDIGIT-65` ticket (A/B of 2026-08-09) · `PBDIGIT-69` ticket (G-1 measurement) · `ElectionMembership.php:32` · `BelongsToTenant.php:46` · `User.php:315-328` · `ElectionVotingController:37-44` · `ElectionMembership::booted():243-248` · P6 tinker A/B (values in §4) · P5 report §3 (corrected by §7 here)
