# `start()` runtime verification — **RUNTIME REPRODUCED**

**Commission:** disposition §8a (Session 2) · executed by Session 1 · **verification only — nothing repaired, Option A not reopened, Session 3 not authorized**
**Date:** 2026-08-14 · **Baseline:** `SD-1` = 1,376 · `L3` = 240 *(unchanged)*

---

## 1 · Verdict

> 🔴 **RUNTIME REPRODUCED. A validly admitted voter is wrongly denied at `POST /elections/{slug}/start` when the ambient session tenant points at another organisation — while the identical request under the matching tenant proceeds all the way into the voting flow.**

## 2 · Exact reproduction

**Fixture (throwaway rows, testing DB, cleaned up):** Organisation A election — `real`, approved, administration+nomination complete, window OPEN, locked, **one approved candidacy** (so `EM-VOT-002` and the full lifecycle gate are satisfiable) · voter with `ElectionMembership(role=voter, status=active, org A)` · authenticated, email-verified.

**Execution:** the production `ElectionVotingController::start()` action invoked with a real `Request` for `POST /elections/{slug}/start` and a real session store; only `session('current_organisation_id')` differed between runs (run order B → A, so no cache could favour the control).

```
AMBIENT B (wrong):   → redirect elections.show
                       error = "You are not registered as a voter for this election."
AMBIENT A (correct): → redirect /v/{slug}/code/create        (voter slug created; voting flow entered)
```

**Same voter · same election · same membership row. Only the session tenant changed.**

## 3 · Why the control run matters

Under ambient A the request passed **every** downstream gate — membership → `has_voted` → lifecycle `canVote()` (including the approved-candidate rule) → IP check → slug creation. **So the ambient-B denial is isolated to the membership lookup**: `start():113-115`'s `$user->electionMemberships()->where('election_id',…)->first()` runs under `BelongsToTenant`'s ambient filter with **no bypass**, exactly the mechanism repaired at the two Option-A sites.

**This is the pre-credential entitlement gate** — it executes *before* any voter slug exists, so credential correspondence (Q-TEN-1's ruled comparison) cannot be involved. **The cached predicate is also not involved:** `start()` queries the relation directly, not `isVoterInElection()`.

## 4 · Comparison with the accepted invariant

**Decision A (clarified) + Q-TEN-1/2 (six clauses):** entitlement is a fact of (voter, election); the Election determines the required organisation; the credential provides the comparand; **ambient session tenant must not filter the answer.**

| Clause | `start()` today |
|---|---|
| Ambient context must not influence entitlement | 🔴 **VIOLATED — measured** |
| Election determines the required organisation | 🔴 not consulted; the SESSION organisation filters instead |
| Credential correspondence | N/A here — pre-credential by design |
| Cache never replays cross-context | N/A — no cache on this path |

**Same defect family as `PBDIGIT-65`, at a site the Option-A grant explicitly did not cover** (it was reported by Session 3 during implementation and correctly left untouched).

## 5 · Decision-ready proposal — **Option A-2 grant (PROPOSED, NOT GRANTED, nothing implemented)**

> **Scope:** repair of the ambient-filtered pre-credential entitlement lookup in the `start()` entry action — **scoped by the violation, not by file prescription** *(the observed site `ElectionVotingController::start():113-115` is evidence)*.
> **Invariant:** the grant-v5 six-clause rule applies verbatim; the entitlement lookup must derive the required organisation **from the Election** and must not be filtered by ambient session/tenant context. *(No cache clause is needed unless the repair introduces one.)*
> **Acceptance scenario:** §2's A/B — ambient B must yield the same outcome as ambient A for the same admitted voter (RED encodes it before any production edit).
> **Sequencing:** the established loop — PO authorization → Session 3 boundary presentation → RED → GREEN → Session 1 independent verification.
> **Out of scope:** everything §5a already excludes (family, `voter_count`, `has_voters`, `EM-VOT-003`, `EM-OPEN-021`, the 375 bypass sites).

**I propose; I do not authorize. The signature is the Product Owner's.**

## 6 · Measured vs not measured

**MEASURED:** the `start()` action's complete internal gate chain under both ambient contexts, via the production controller with a real request and session · the denial message and redirect targets · the success path through slug creation.
**NOT MEASURED:** the route's outer middleware (`web`/`auth`/`verified`) — authentication was established directly; those middlewares do not touch tenancy · behaviour under `TenantContext::set()` (the session fallback was used, mirroring the ticket's mechanism) · any other entry path.
**Cleanup:** all created rows removed in-run (`cleanup ok`); testing DB only; development DB untouched.

---

**COMMISSION COMPLETE · EVIDENCE STRENGTH: RUNTIME REPRODUCED · STOPPING**
**Nothing repaired · no test modified · trait untouched · family not audited · Option A not reopened · Session 3 not authorized · `EM-OPEN-021` untouched**

**Traceability:** disposition §8a commission · `ElectionVotingController:104-160` (gate order: membership → has_voted → lifecycle → IP → slug) · route `POST elections/{slug}/start` = `web · Authenticate:sanctum · EnsureEmailIsVerified` · grant v5 six-clause rule (`f6bb5504`) · Option-A verification (`a8f5afc7` §H) · P6 mechanism (`fc86049f`)
