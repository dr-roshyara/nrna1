# Cross-Domain Organisational Architecture v1.4: Write-Boundary Verification Specification and Governance Contract Approval Sheet

**Date:** 2026-10-10 · **Author:** Claude (architecture discovery; **no implementation**)
**Amends:** v1.3 (`20261010_2342_cross_domain_architecture_v1.3.md`) → v1.2 → v1.1 → v1.0.
**Responds to:** "Principal review of Cross-Domain Organisational Architecture v1.3" (2026-10-10).
**Status:** **CANDIDATE. Authority: generated. Not ratified; authorises nothing.**
**Scope:** cross-domain DDD architecture for Membership, Member, Committee, positions, Permission, geographical committees and Forum. Election is out of scope.

---

## 0. Position on the review

**I agree with every point.** Two points are now backed by evidence from a real PostgreSQL server, not only by bounded Python models:

| Review point | Result | Evidence |
|---|---|---|
| §2.1: a version check inside the transaction is not enough without serialisation against the revoking write | **confirmed.** A plain version read inside a READ COMMITTED transaction lets **16 / 35** schedules commit an unauthorised post, the same as no check. Locking the version row `FOR SHARE` gives **0 / 35** | `g11` E1 |
| §2.1 (implicit): would SERIALIZABLE isolation alone suffice? | **No, for real-time revocation.** SERIALIZABLE with a plain read: **16 / 35** posts committed *after* the revocation committed, with **0** serialization failures. It produces a valid *logical* order (post before revocation) but not real-time order. "Revoked authority must not act after the revocation commits" needs a lock, not only serializability | `g11` E1 |
| §2.2: phantom facts (absence has no row to lock) | **confirmed.** Locking the suspension rows the query found (none) gives **16 / 35** unauthorised. A **scope version token**, bumped by every suspending write, gives **0 / 35** | `g11` E2 |
| §2.3: the two-root transaction needs a fixed lock order | **confirmed.** Opposite lock order deadlocks in **36 / 70** schedules; sorted order in **0 / 70** | `g11` E3 |
| §3.1: `3/n` is wrong for small n; the bound needs a sampling model | agree; corrected in §4 | — |
| §3.2: keep the five statuses separate dimensions | agree; adopted as a multi-dimensional status record in §5 | — |

**About `g11`.** It is a **research probe**, not application code:
- **file:** `docs/publicdigit/membership/architecture/models/g11_pg_write_boundary.php`;
- **target:** a **private throwaway PostgreSQL 18.6 cluster** created with `initdb` in the session scratch directory. TCP was disabled (`listen_addresses=''`), the server listened on a private Unix socket, the cluster was stopped afterwards, and no project configuration or shared database was used;
- **safety:** the probe refuses any target other than a Unix-socket database not named `nrna_test`;
- **schedules:** it executes every interleaving of two real sessions. Waiting is simulated by `lock_timeout` + savepoint + retry, which is equivalent under READ COMMITTED (a new snapshot per statement);
- **environment:** PHP 8.5.11; script SHA-256 `ce658cb4…dfad0`.

**What `g11` does not show** (it is verification of a *proposed protocol*, not of the platform):
- the real schema;
- Laravel's transaction handling;
- more than two sessions;
- the application's isolated test database (that is still `PBDIGIT-266`'s infrastructure gap).

---

## 1. PostgreSQL write-boundary verification specification (proposed contract)

### 1.1 Concepts

| Term | Definition |
|---|---|
| **Scope token** | a row `(scope, version)` per (fact type × subject scope), e.g. `membership:(p,o)`, `tenure:(p,s)`, `susp:(p,o)`, `positionholdings:(p,o)`, `committee:c`, `jurisdiction:c`, `geo-snapshot`, `policy:(moduleId)`. It covers **existing and not-yet-existing** facts in its scope |
| **Footprint** | the set of scope tokens a predicate's answer depends on (v1.3 §4.2; now stated as **scopes, not rows**) |
| **Read set** | `{scope → version}` captured by the decision; it *is* the knowledge vector `k` |

**Mapping to the ratified pattern:** the Membership aggregate's own version (its `seq`, append-only log per (p, o)) **already is** the scope token for every Membership predicate, including absence of a Suspend. That is why `g10` Q2 found 0 failures. Other contexts must give each aggregate the same per-scope version.

### 1.2 Protocol rules (WB-1 … WB-9)

| # | Rule | Evidence |
|---|---|---|
| WB-1 | Every fact-changing command (create, revoke, annul, void, correct, **insert of a previously absent fact**) increments every scope token whose footprint it changes, **in the same transaction**, with a row write lock (`UPDATE … SET version = version + 1`) | g11 E2 (scope token 0) |
| WB-2 | A protected write (CONTRIBUTE, MODERATE, ADMINISTER, GOVERN) carries the decision's read set | v1.3 §3 |
| WB-3 | Inside the protected write transaction, before the write: `SELECT version FROM scope_token WHERE scope = ANY(:footprint) ORDER BY scope FOR SHARE`; compare with the read set | g11 E1 (FOR SHARE 0) |
| WB-4 | **Not sufficient** (and therefore forbidden as the guard): a plain read (any isolation level), or a lock on the matched fact rows only | g11 E1 (16), E1 SERIALIZABLE (16), E2 row lock (16) |
| WB-5 | **Lock order:** every transaction acquires scope locks sorted by `(context, scope)`. This applies to guards (FOR SHARE) and to fact commands (UPDATE), including the Committee + PositionHoldings two-root commit | g11 E3 (36 → 0 deadlocks) |
| WB-6 | **On mismatch:** roll back, re-decide (fresh PDP call with a new read set), retry at most N times; then return the new decision's outcome. **Never** fall back to the stale decision | review §2.1 |
| WB-7 | **On deadlock (40P01) or serialization failure (40001):** roll back and retry within the same bound; after exhaustion return `DEPENDENCY_FAILURE` (v1.3 §2), never PERMIT | review §2.1 |
| WB-8 | **Isolation level:** READ COMMITTED with WB-3 locks is the specified level. SERIALIZABLE may be added for unrelated reasons but **does not replace** WB-3 | g11 E1 SERIALIZABLE |
| WB-9 | **Scope of the guarantee:** one PostgreSQL database. Across databases the guarantee does not hold; XD-10 must name a revocation delay Δ **and the action classes for which Δ is acceptable** (proposal: READ only; never MODERATE, ADMINISTER, GOVERN; CONTRIBUTE only if the ARB accepts it) | review §2.1 |

**Guarantee claimed (bounded):** under WB-1…WB-9, for two concurrent sessions, a protected write never commits after a conflicting revocation or a suspension insert has committed (`g11`: 0 / 35 in E1 and E2), and the sorted lock order prevents two-root deadlocks (0 / 70). **Not claimed:** more than two sessions, long-running transactions, lock-wait timeouts in production, replicas (read-replica guards are refused, as in v1.0 §6.7).

### 1.3 Acceptance test specification for the isolated test database (`PBDIGIT-266` environment)

To be executed by an authorised slice, not by this document.

| ID | Test | Pass criterion |
|---|---|---|
| WBT-1 | revocation vs post, all interleavings of the real command handlers | 0 unauthorised posts |
| WBT-2 | suspension insert (absent → present) vs post | 0 unauthorised |
| WBT-3 | annulment of an appointment vs moderation action | 0 unauthorised |
| WBT-4 | Committee + PositionHoldings two-root appoints, opposite logical order | 0 deadlocks; 0 invariant violations |
| WBT-5 | retry exhaustion | outcome `DEPENDENCY_FAILURE`, never PERMIT |
| WBT-6 | guard on a replica / read-split connection | refused |
| WBT-7 | `g11` re-run against the isolated database (same probe, real server settings) | identical counts to §0 |

---

## 2. Governance Participation Semantic Contract: approval sheet

**The contract to approve is not a new document.** It is:
- **base:** `docs/publicdigit/reviews/2026-10-09-participation-semantic-contract-draft.md` (G-1…G-5, IP-1…IP-7);
- **plus** AM-1…AM-9 (v1.2 §3);
- **plus** the transaction protocol (v1.3 §5);
- **plus** WB-1…WB-9 (§1.2).

| Clause group | Content | Prerequisite decision | Evidence | Status |
|---|---|---|---|---|
| C-A Vocabulary | Person-subject Participation, Position, Tenure, Term, Mandate, Duty, Delegation | RD-1, RD-2 | research 2026-10-09 | Proposed |
| C-B Membership dependency | EffectiveTenure = Tenure ∧ ε ∧ CommitteeActive; derived, no cascade | RD-3, BQ-P2, BQ-P3 | 140 / 476; g9 T-3 / T-6 | Proposed |
| C-C Aggregates | Committee (local) + PositionHoldings (p, o); two-root commit with sorted locks | RD-4, BQ-P1, **BQ-INC** | g7 Q1 / Q3; g11 E3 | Proposed |
| C-D Temporal | ratified fact pattern; resolve from `effectiveAt`; dissolution derived | XD-1 | g9 T-1 / T-3 / T-6 | Proposed |
| C-E Duty | delegation transfers authority, not duty; duty ends by discharge | BQ-P8 | draft G-1 | Proposed |
| C-F Jurisdiction | JurisdictionAssignment lifecycle; exclusivity per BQ-J1 | BQ-J1, XD-5 | not yet modelled | Proposed |
| C-G Authority interface | Governance PolicyModule; footprints; scope tokens WB-1 | XD-4, XD-7, XD-12 | g8, g10, g11 | Proposed |
| C-H Legacy | four committee models re-modelled in Governance | RD-5 | inventory | Proposed |

**Approval rule:**
- A clause group can be approved only when its prerequisite decisions are approved.
- **C-A, C-D, C-G** are approvable after the first decision round (XD-1, XD-4, XD-7, XD-12, RD-1, RD-2).
- **C-B, C-C, C-E, C-F, C-H** wait for the business questions.

---

## 3. Discovery findings added by this round

1. **Real-time revocation and serializability are different requirements.** The platform needs the stronger one: *strict*, real-time ordering for revocations. That is a **domain requirement** and should be written into the Governance and Forum contracts: "an action authorised by a fact must not commit after a revocation of that fact has committed".
2. **The scope token is the unifying concept.** The ratified Membership aggregate version, PositionHoldings, Committee version, forum version and policy-module version are all instances. The architecture rule becomes: **every aggregate exposes one version token; every predicate's footprint is a set of aggregate tokens.** This also settles the review's phantom concern, because an aggregate keyed by subject scope covers facts that do not exist yet.
3. **The aggregate design and the authorization design now constrain each other.** A predicate is admissible in a guard only if its footprint is a bounded set of aggregates (v1.3 §4.2). So a business rule that needs an unbounded scan forces a new aggregate (as PositionHoldings did) or is rejected as a guard. This is a concrete DDD discovery test for every future rule.

---

## 4. Statistical bound, corrected

- **Exact bound:** after *n* independent, representative observations with zero counterexamples, the one-sided 95 % upper bound on the violating proportion is `1 − 0.05^{1/n}`: 0.776 (n = 2), 0.451 (n = 5), 0.259 (n = 10), 0.095 (n = 30).
- **The approximation `3/n` is valid only for larger n** (at n = 30 it overstates the exact bound by about 5 %: 0.100 vs 0.095). At n = 2 it gives 1.5, which is meaningless. **v1.3 §6 is corrected:** use the exact formula, and never `3/n` for small n.
- **Admissibility:** a population bound requires (a) a defined population of organisations, (b) a sampling procedure that is plausibly representative, and (c) a stated detection rate for the interpretation process. **Without (a)–(c), corpus findings are reported as descriptive evidence only.** A valid in-scope counterexample still disproves a universal rule.
- **Recommendation:** drop the `upperBoundCX` column from the rule matrix unless the ARB defines a sampling frame.

---

## 5. Status record (five independent dimensions)

Every decision, contract clause and capability carries a record. **"Done" is not a value.**

```text
Status {
  proposal:      Proposed | Withdrawn
  approval:      None | Approved(by, date, act ref) | Rejected(...)
  implementation:None | Implemented(commit refs)
  verification:  None | Verified(check id, run date, scope: bounded-model | unit | db-backed | route | e2e)
  acceptance:    None | Accepted(by, date, act ref)
}
```

**Example:** the write-boundary protocol (§1):
- proposal = Proposed;
- approval = None;
- implementation = None;
- verification = Verified(g11, 2026-10-10, scope: bounded-model on a real PostgreSQL server, 2 sessions);
- acceptance = None.

Evidence gates (v1.1 §8) count a gate only when the dimension named as its target is reached.

---

## 6. How far are we from the implementation goal?

- **Architecture discovery:** converged on boundaries. This round added a **domain requirement** (real-time revocation), the **scope-token** unification, and a **write-boundary contract verified on a real PostgreSQL server within bounds**. Discovery work is now limited to the open model extensions listed below and to the decisions.
- **Decisions:** none approved; the package runs through XD-12. The Governance contract is ready to approve in clause groups (§2), but only after the decisions it depends on.
- **Implementation:** unchanged and outside this role. Membership kernel and Geography exist; Governance target design, PDP, Organisation structure, Forum and audit replay are not built.
- **Security:** the write-time protocol is specified and probe-verified; it is not implemented. `PBDIGIT-266` is still not executed.
- **Measures:**
  - evidence gates **7 / 30** (no new gate reached its target: the write-boundary gate's target is *Implemented + Verified*);
  - the capability matrix (v1.3 §8.2) is unchanged.

---

## 7. Remaining TODOs

### P0: Security and decisions
- Provision the isolated test database; run `PBDIGIT-266`; add interim route protection (security owner).
- Decision round 1: XD-1, XD-4, XD-7, XD-11, XD-12, RD-1, RD-2. Then approve contract clause groups C-A, C-D, C-G.
- Decision round 2: RD-3…RD-5, BQ-P1…BQ-P3, BQ-INC, BQ-P8, BQ-J1, XD-5. Then approve C-B, C-C, C-E, C-F, C-H.
- Decision round 3: XD-2, XD-3, XD-6, XD-8, XD-9, **XD-10 (with Δ and the permitted action classes per WB-9)**.
- Answer the forum questions BQ-P9, BQ-F1 and BQ-F2.
- Adopt the domain requirement "no action commits after a committed revocation of its authorising fact" in the Governance and Forum contracts.

### P1: Discovery and specification (no implementation)
- Specify the scope-token catalogue: one token per aggregate, with the footprint of every published predicate (v1.2 §4 × §1.1 here).
- Extend `g11` to three sessions and to the annulment-vs-moderation case (WBT-3).
- Extend `g8` to three policy modules and disjunctive grants, including concurrent module activation.
- Extend `g10` to several facts per decision and several concurrent writers.
- Extend `g7` to Vacate interleaving and capacity > 1; extend `g9` to tenure corrections and delegation chains.
- Model jurisdiction exclusivity after BQ-J1.
- Rule and evidence matrix: source scope and authority per row; statistical bounds only with a defined sampling frame (§4).
- Introduce the five-dimension status record (§5) in the decision register and project tracker.

### P2: Handover to implementation (by an authorised implementation slice, not this role)
- WBT-1…WBT-7 on the isolated database.
- Committee + PositionHoldings; the minimal PDP with WB-guard; legacy committee consolidation; OrganisationStructure + UnitAffiliation; jurisdiction; Forum MVP then moderation and the remaining forum features; audit replay.

### P3: Acceptance
- End-to-end scenarios:
  - former chair;
  - district secretary;
  - suspended in A, active in B;
  - concurrent appointments;
  - revocation during a post;
  - annulment with replay.
- Database-backed, route and integration suites; models kept as regression checks; gates scored by the §5 dimensions only.

---

## 8. Traceability

| Section | Sources |
|---|---|
| §0, §1 | `models/g11_pg_write_boundary.php` (run 2026-10-10 on a private PostgreSQL 18.6 cluster, PHP 8.5.11, SHA-256 `ce658cb4362eb663226b1b2b5ea9e95503cf418db8ed755d126c386d756dfad0`); review of v1.3 §2.1–§2.3 |
| §2 | participation contract draft 2026-10-09; v1.2 §3; v1.3 §5 |
| §3 | g10, g11; v1.3 §4.2 |
| §4 | review of v1.3 §3.1 |
| §5 | review of v1.3 §3.2 |
