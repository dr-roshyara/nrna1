# Cross-Domain Organisational Architecture v1.3: Write-Boundary Authorization, Read-Set Footprints and the Policy Combination Contract

**Date:** 2026-10-10 23:42 · **Author:** Claude (developer and researcher role)
**Amends:** v1.2 (`20261010_2350_cross_domain_architecture_v1.2.md`) → v1.1 → v1.0. Unchanged parts are not restated.
**Responds to:** "Principal review: Cross-Domain Organisational Architecture v1.1" (2026-10-10).
**Status:** **CANDIDATE. Authority: generated. Not ratified; authorises nothing.** Scope: cross-functional DDD architecture for Membership, Member, Committee, positions ("roles"), Permission, geographical committees and Forum. **Election is out of scope.**

**Labels:**
- **Proposed:** written down, no approval.
- **Approved:** a recorded PO / ARB act exists.
- **Implemented:** the code exists in the repository.
- **Verified:** an executable check was re-run in this session and passed.
- **Accepted:** a formal acceptance act exists.

Model results are **verified within bounds**, never "proved".

---

## 0. Position on the review

**I agree with the review.** It reviewed v1.1. v1.2, written after it, already closed part of it: the participation contract draft is integrated, and temporal tests T-1, T-3, T-5 and T-6 have passed (`g9`). This document closes the rest.

| Review item | Agreement | v1.3 |
|---|---|---|
| §2.1 combination contract incomplete | agree | §1: full combination, activation and missing-module rules |
| §2.2 operational response to U | agree | §2: outcome taxonomy, with INDETERMINATE and DEPENDENCY_FAILURE separate from DENY |
| §2.3 **TOCTOU** between check and write | agree; it is the most important new finding | §3: write-boundary guard. **g10 Q1:** check-then-write and unlocked re-check each allow **2** unauthorised writes; an atomic read-set precondition allows **0** |
| §2.3 "reconsider caching CONTRIBUTE" | **agree with a refinement** | the model shows a *cached* decision is safe **if** the write carries the atomic precondition (g10 Q1 S3: 0 unauthorised). So CONTRIBUTE caching is allowed only together with the guard, never without it |
| §2.4 negative knowledge; `k` as a vector | agree | §4: **footprint versions** instead of returned rows. **g10 Q2:** a returned-rows read set gives 23 replay mismatches and 31 stale entries trusted; footprint versions give **0 / 0**. `k` is the read set itself (a per-aggregate vector) |
| §2.5 two aggregate roots in one transaction | agree | §5: an explicit protocol. **g7 Q3** (3 concurrent commands, 1,680 interleavings): committee-only 738 incompatibility violations; with PositionHoldings **0**, capacity 0 |
| §4 `upperBoundCX` unexplained | agree | §6: definition, purpose and limits |
| §4 ELECTION_MANIFESTO scope | agree | §7: organisational vocabulary and organisational authority rules only |
| §6 gates ≠ implementation progress | agree | §8: two separate measures |

**New research model:** `docs/publicdigit/membership/architecture/models/g10_toctou_and_absence.py`. `g7` is extended with Q3. Both were run in this session.

---

## 1. Policy combination contract (completes v1.1 §2)

**Inputs to one decision:**
- the **semantics version** S, owned by Authorization;
- the **required modules**: PlatformModule, OrgModule(org(resource)) and ResourceModule(type(resource)), each pinned as `(moduleId, version)`.

| Rule kind | Sources | Combination across modules | Guard semantics (K2) |
|---|---|---|---|
| Grant | **ResourceModule only** (INV-AUTH-OWN) | within the module: highest priority, deny wins at a tie, abstain removed | binds on T |
| Ordinary deny | ResourceModule only | same priority set as grants | binds on T ∨ U |
| Mandatory prohibition | any module | **disjunction:** any binding prohibition denies | binds on T ∨ U |
| Mandatory condition | any module | **conjunction:** every condition must be T | must be T |

**Missing or unretrievable module:** the outcome is `DEPENDENCY_FAILURE`, never a decision computed from the modules that did load. Rationale: a missing OrgModule could hide a prohibition, which is the same argument as K2 lifted to modules.

**Activation protocol (forward-only):**
1. The owner submits an immutable version.
2. **Module lint** (blocking): no mandatory prohibition and condition that bind on the same satisfiable valuation with opposite effect; no dead grant; no shadowed prohibition (SMT check).
3. **Cross-module lint** (warning, not blocking): does this version make a grant in another *active* module dead? Example: a new organisation prohibition that silences every committee-forum grant. The result is reported to the affected module owners.
4. Activation sets `effectiveFrom ≥ activation time`. Back-dating needs a separate authorised act (v1.1 §6.1).
5. The old version stays readable for replay.

**One rule, one owner:** the same rule text is never copied into two modules. A rule needed by several resource types belongs to the Org or Platform module as a restriction, or to a shared module referenced by id, never duplicated.

**Verification obligation:** extend `g8` to three modules and to disjunctive grants (the committee-forum template). This is still open (TODO).

---

## 2. Decision outcomes and fact-read results (operational response to U)

**Fact read result (published-language type):**

```text
FactRead = Known(value ∈ {T, F}, footprint: [(aggregate, seq)])
         | Unknown(reason ∈ {OPEN_WORLD_ABSENT, SOURCE_UNAVAILABLE, PROJECTION_STALE,
                             INCOMPLETE_QUERY, OUT_OF_FOOTPRINT}, footprint?)
```

A **closed-world** predicate with an available source returns `Known(F)` for absence; only an open-world predicate returns `Unknown(OPEN_WORLD_ABSENT)` (v1.0 §6.4).

**Decision outcome taxonomy:**

| Outcome | When | Response | Classed as |
|---|---|---|---|
| `PERMIT` | grant T, all conditions T, no prohibition / deny T or U | proceed (write still guarded, §3) | — |
| `DENY` | a prohibition / deny is **T**, a condition is **F**, or no grant applies | final business answer | business |
| `INDETERMINATE` | the only blocking reason involves **U** with reason `OPEN_WORLD_ABSENT` (e.g. "disciplinary status not recorded") | refused now; routed to a **review queue** owned by the resource context; never auto-permitted | business, unresolved |
| `DEPENDENCY_FAILURE` | U from `SOURCE_UNAVAILABLE`, `PROJECTION_STALE` beyond the action-class limit, or `INCOMPLETE_QUERY`; or a required module missing | refused now; retry with backoff; alert above a threshold (v1.2 §7 p-chart) | operational |
| `CONFIGURATION_ERROR` | mandatory conflict at runtime (should be unreachable after lint) | refused; escalated to the platform owner | operational |

**Invariant:** `outcome ≠ PERMIT` for every case with any blocking U. This follows from K2 (g8 P1: 0 unsound permits). The taxonomy only adds *why* the request was refused and *who acts next*.

---

## 3. Write-boundary authorization (TOCTOU) — new contract

### 3.1 Evidence (g10 Q1)

A post needs `EffectiveTenure`. A concurrent Governance writer commits *revoke* or *revoke → reinstate*. "Unauthorised" means the post committed while the tenure was not effective at its commit point.

| Strategy | Interleavings | Unauthorised writes | Refused |
|---|---|---|---|
| S0: decide, then write | 9 | **2** | 3 |
| S1: decide, re-check version, write (separate steps) | 14 | **2** | 8 |
| S2: decide, then **atomic** {re-check read-set versions + write} | 9 | **0** | 6 |
| S3: **cached** decision + atomic precondition | 5 | **0** | 3 |
| S2v / S3v: value comparison instead of version | 9 / 5 | 0 / 0 | 5 / 2 |

**Reading the result:**
- Re-checking *before* the write without atomicity changes nothing (S1 = S0 = 2). The check must be **inside** the write's serialisation point.
- Value comparison also gives 0, because it accepts the ABA case "revoked, then reinstated", where the post is genuinely authorised at commit. But it loses exact replay pinning (§4). **Version comparison is chosen**; its extra refusals (6 vs 5) are retries, not errors.

### 3.2 Contract (proposed)

1. Every PEP for a write class (CONTRIBUTE, MODERATE, ADMINISTER, GOVERN) passes the decision's **read set** (footprint versions, §4) with the command.
2. The owning context's command handler, **inside its write transaction**, calls `ReadSetGuard.assertUnchanged(readSet)`. This is a published port of each fact-owning context that exposes **only version rows**, never domain state.
3. **Modular monolith, one PostgreSQL database:**
   - the guard reads the footprint version rows `FOR SHARE`;
   - every fact-owning command bumps its aggregate version under a row write lock (it already does, via expected-version commits);
   - so a revocation either commits before the guard, and the guard fails, or after the post, and the post was authorised at its commit.
   - **Lock order:** sorted by `(context, aggregateId)` to prevent deadlocks.
4. **On guard failure:** abort the write, re-decide (a fresh PDP call), and retry at most N times. The outcome may become `DENY`.
5. **Caching** (READ unchanged; CONTRIBUTE now allowed) is safe only together with steps 1–3. MODERATE, ADMINISTER and GOVERN remain fresh-decide plus guard.
6. **If contexts ever move to separate databases:**
   - the guard cannot lock across databases;
   - the fallback is a **documented revocation-freshness bound Δ**: revoked authority may still act for at most Δ;
   - this is a risk acceptance and needs an explicit ARB decision (**XD-10**);
   - the repository's multi-database brainstorming must take this into account.

**Coupling note:** the guard couples transactions, not models. It reads version integers through a port. This is the one deliberate cross-context transactional dependency, and it is named in the context map as a **Shared-Kernel-free, version-only port**.

---

## 4. Read-set footprints and negative knowledge

### 4.1 Evidence (g10 Q2)

`NotSuspended(p)` over a Suspend / Restore log; decisions with later appends; 111 cases.

| Read set | Replay "as decided" mismatches | Stale cache entries trusted |
|---|---|---|
| RS-FACTS: only the facts the query returned | **23** | **31** |
| RS-AGG: the version of the aggregate that determines the answer | **0** | **0** |

When no Suspend existed, RS-FACTS recorded **nothing**. The "no suspension" knowledge was lost, exactly as the review predicted.

### 4.2 Footprint rule (proposed)

Every published predicate declares a **footprint**: the set of aggregates whose versions jointly determine its answer, *including absence*. The read set records `(aggregate, seq)` for the footprint, not the rows returned.

| Predicate (v1.2 §4) | Footprint |
|---|---|
| `IsMember`, `Suspended`, `CurrentType`, durations | Membership(p, o) |
| `UnitAffiliated(p, u)`, "∃ affiliation with …" | UnitAffiliationLog(p, o) |
| `UnitAncestor(u, u')` | OrganisationStructure(o) |
| `Participates`, `EffectiveTenure`, `CommitteeActive`, `VacantLegal`, `VacantFunctional` | Committee(c) + Membership(p, o) (ε reads Membership) |
| incompatibility / position limits | PositionHoldings(p, o) |
| `JurisdictionContains(c, g)` | JurisdictionAssignment(c) + Geography snapshot id |
| `GeoAncestor` | Geography snapshot id |
| `Muted(p, f)`, `ForumScope`, `TopicLocked` | Forum(f) / Topic(t) |
| policy | (moduleId, version) × 3 + semantics version |

**Admissibility rule:** a predicate whose answer is not determined by a bounded footprint is **not allowed in a guard**. Example: "is p chair of *any* committee in the organisation" would have to scan every committee. It must be served by an aggregate that owns the set. Here that is PositionHoldings(p, o), which is one more reason for that aggregate.

### 4.3 Knowledge cutoff as a vector

- **The read set *is* `k`:** a vector `{aggregate → seq}`. No global sequence is assumed.
- `decideNow` reads all footprints inside one **REPEATABLE READ** read transaction (one database), so the vector is a consistent cut.
- `reconstructDecision`:
  - `AS_DECIDED` uses the recorded vector;
  - `IN_HINDSIGHT` uses current heads;
  - `AT(time)` maps a timestamp to per-aggregate seq via `recordedAt` (every fact carries it: ratified pattern).
- **If any footprint aggregate cannot be reconstructed** (retention, migration gap), the result is `RECONSTRUCTION_INCOMPLETE`. It is never a guessed decision.
- The DecisionRecord also stores the semantics version, the PDP algorithm version and each predicate's closed / open-world declaration version.

---

## 5. Committee + PositionHoldings transaction protocol

**Evidence (g7 Q3):** three concurrent Appoint commands (two for the same person in incompatible positions in different committees, one competing for a capacity-1 seat); 1,680 interleavings:
- committee-only: **738** incompatibility violations, 0 capacity violations;
- Committee + PositionHoldings: **0 / 0**;
- one aggregate per organisation: 0 / 0, at the cost of serialising every appointment.

**Protocol (proposed; the same recipe as the ratified Membership G\*):**
1. Load PositionHoldings(p, o) and Committee(c) with their versions.
2. Decide: both aggregates' invariants, plus the authority decision (with the §3 guard).
3. Commit both in **one database transaction** with expected versions. Lock order: PositionHoldings before Committee (sorted by aggregate type, then id).
4. On a version conflict: roll back both, reload, re-decide and retry (bounded).
5. Vacate, Annul, Void and Correct follow the same two-root protocol when they change holdings.

**Explicit DDD exception:** this is a documented two-root transaction, justified because INV-P6 is quantified over a person's positions across committees. If Governance ever leaves the shared database, the alternative is a reservation saga (reserve in PositionHoldings → commit in Committee → confirm / release). That is not proposed now.

**Not modelled:** more than 3 commands, Vacate interleaved with Appoint, capacity > 1. These are TODOs.

---

## 6. `upperBoundCX` (v1.0 §8, F-8): definition, purpose, limits

**Definition:** suppose *n* organisations' statutes were examined and **none** contradicts rule R. The exact one-sided 95 % upper confidence bound on the proportion π of organisations that would violate R is

\[ \pi_{95} = 1 - 0.05^{1/n} \approx 3/n \quad\text{(the "rule of three")}. \]

**Example values:** n = 2 → 0.78; n = 5 → 0.45; n = 10 → 0.26; n = 30 → 0.095.

**Purpose:** a guard against over-claiming. It shows the ARB how little "no counterexample found in n documents" supports a **platform-wide** rule. It is used only for rules proposed as platform invariants, never for organisation-configurable defaults.

**Limits (stated so it is not misused):**
- it assumes independent random sampling, and statutes we happen to have are not a random sample; the true uncertainty is larger;
- it can therefore argue **against** universality but never **for** it. Structural and temporal invariants are justified by definition, not by this number.

**Status:** an optional matrix column. Keeping it is the ARB's decision.

---

## 7. Use of `ELECTION_MANIFESTO.md` as evidence

Only for **organisational** content:
- vocabulary (EM-VOC: Member vs OrganisationAffiliation);
- organ appointment authorities, insofar as they describe organisational governance.

Each such matrix row is labelled `source-scope: election-document, organisational content`. No election-process requirement (rolls, entitlement, ballots) enters this architecture.

---

## 8. Two separate progress measures

### 8.1 Evidence gates (v1.1 §8 rule, unchanged)

**7 / 30.** This round adds verified research evidence (g10; g7 Q3) but no gate reaches its target level: approval or implementation is still missing. The count is a measure of evidence, **not** of implementation share.

### 8.2 Capability matrix (implementation, not weighted, not a percentage)

| Context / capability | State | Basis |
|---|---|---|
| Identity & Access | existing authentication; not reworked for PersonRef / consent linking | code exists (reported) |
| Membership | kernel **Implemented + Verified (unit)**; C-1 implemented, not accepted; **no cutover** | v1.1 re-run (144 tests) |
| Organisation (structure, UnitAffiliation) | **not started**; units mis-modelled as committee types | inventory |
| Governance | legacy: 4 inconsistent committee models; target design Proposed (draft + AM-1…AM-9) | inventory; g7, g9 |
| Geography | **Implemented + Verified (unit)**; no snapshot versioning | v1.1 re-run (51 files) |
| Authorization | semantics Proposed + bounded-verified (g8, g10); **PDP not built**; `PBDIGIT-266` open | — |
| Forum | contract Proposed (v1.2 §5); **no code** | — |
| Audit / replay | DecisionRecord and footprints specified; not built | — |
| End-to-end scenarios | specified (v1.2 §6); not executable | — |

---

## 9. Decision package: additions (to v1.1 §9)

| ID | Question | Recommendation | Evidence |
|---|---|---|---|
| **XD-9 (amended)** | Cache strategy | C-AUTHV by default; caching CONTRIBUTE **only** together with the §3 write guard | g8 P2, g10 Q1 S3 |
| **XD-10** (new) | Write-boundary mechanism | in-transaction `ReadSetGuard` with version rows `FOR SHARE` (single database). Any future cross-database split needs an explicitly accepted revocation bound Δ | g10 Q1 |
| **XD-11** (new) | Outcome taxonomy and INDETERMINATE route | adopt §2. INDETERMINATE goes to a review queue of the resource-owning context; DEPENDENCY_FAILURE goes to retry + alert | g8 P1 |
| **XD-12** (new) | Footprint rule | adopt §4.2, including the admissibility rule for guards | g10 Q2 |
| **RD-4 (amended again)** | Governance transaction | Committee + PositionHoldings as a documented two-root transaction (§5) | g7 Q1, Q3 |
| **BQ-INC** (new, business) | Scope of position incompatibility: global, per organisation, per committee kind? | business decision; PositionHoldings keyed (p, o) assumes per organisation | review §4 |

**Decision order (the review's order, with additions):**
1. XD-1;
2. XD-4 + XD-7 + XD-11;
3. RD-1…RD-5 + BQ-J1 + BQ-INC;
4. XD-2;
5. XD-3;
6. XD-5;
7. XD-6 + XD-8;
8. XD-9 + XD-10 + XD-12.

Every record carries: question, options, recommendation, rationale, consequences, evidence, owner and status. **None is decided.**

---

## 10. How far are we from the implementation goal?

- **Research and conceptual architecture:** converged. This round changed no boundary. It closed three production-boundary gaps (write race, negative knowledge, two-root transaction) with executable evidence. Further research should be driven only by a specific open decision or obligation.
- **Security readiness:** incomplete.
  - TOCTOU now has a contract, but no implementation.
  - `PBDIGIT-266` is still unexecuted; it needs the isolated database.
  - Database-free unit tests say nothing about route-level or transactional security.
- **Decision readiness:** the package is complete through XD-12. Nothing is approved.
- **Implementation:**
  - Membership kernel and Geography exist.
  - Organisation, the target Governance, Authorization, Forum and Audit replay are not built.
- **Measures:** evidence gates **7 / 30**; capability matrix §8.2. **Neither is an implementation percentage**, as the review requires.

---

## 11. Remaining TODOs

### P0: Security and decisions
- Provision the isolated test database; run the `PBDIGIT-266` security plan; add interim route protection now, independent of the PDP.
- PO / ARB decisions in the §9 order: XD-1 → XD-4 / XD-7 / XD-11 → RD-1…RD-5 / BQ-J1 / BQ-INC → XD-2 → XD-3 → XD-5 → XD-6 / XD-8 → XD-9 / XD-10 / XD-12.
- Answer BQ-P1…BQ-P3, BQ-P8, BQ-P9, BQ-F1 and BQ-F2.
- Approve the Governance contract as the 2026-10-09 draft + AM-1…AM-9 + the §5 transaction protocol.

### P1: Contracts and verification
- Specify `ReadSetGuard` (version-row port per fact-owning context, lock order, retry bound) and test it against PostgreSQL on the isolated database once available.
- Specify `FactRead`, the footprint of every published predicate, and `DecisionRecord` (footprint vector, module and semantics versions, algorithm version, world declarations).
- Specify the PolicyModule contract, activation protocol, module lint and cross-module lint.
- Extend `g8` to three modules and disjunctive grants; extend `g10` to more than one fact type per decision and to multiple concurrent writers.
- Extend `g7` to Vacate interleaved with Appoint and to capacity > 1; extend `g9` to Correct / Void on tenures and delegation chains.
- Model jurisdiction exclusivity after BQ-J1.
- Populate the rule and evidence matrix with source scope labelled (§7); keep `upperBoundCX` only if the ARB keeps it (§6).

### P2: Implementation (each slice only after approval)
- Governance: Committee + PositionHoldings with the two-root protocol; ports with footprints; EffectiveTenure.
- Authorization: minimal PDP (K2, modules, outcome taxonomy, DecisionRecord); `ReadSetGuard` in the first write path.
- Legacy committee migration with parity tests; OrganisationStructure + UnitAffiliation; JurisdictionAssignment + geography snapshot ids.
- Forum MVP: CONTRIBUTE through PDP + guard; READ cache; FO-4 rebuild test; revocation-race test. Then moderation, jurisdiction forums, mutes, subscriptions and invitations.
- Audit consolidation; `AS_DECIDED` replay test with footprint vectors.

### P3: Acceptance and assurance
- Executable end-to-end scenarios:
  - former chair (general forum kept, committee forum lost);
  - district secretary moderation;
  - suspended in A, active in B;
  - concurrent appointments with incompatibility and capacity;
  - revocation during a post (TOCTOU);
  - annulment and retroactive correction with replay.
- Run database-backed, route-level and integration suites, not only database-free units. Keep g3…g10 as regression gates.
- Monitor INDETERMINATE and DEPENDENCY_FAILURE rates (p-chart, signal only). Use NLP, clustering and anomaly detection only as human-verified research aids.

---

## 12. Traceability

| Section | Sources |
|---|---|
| §0 | principal review of v1.1 (2026-10-10); v1.2 §1–§2 |
| §1 | v1.1 §1–§2; review §2.1 |
| §2 | v1.0 §6.4; g8 P1; review §2.2 |
| §3 | `models/g10_toctou_and_absence.py` Q1 (run 2026-10-10); review §2.3 |
| §4 | g10 Q2; v1.2 §4; review §2.4 |
| §5 | `models/g7_governance_aggregate_boundary.py` Q1, Q3 (run 2026-10-10); ratified Membership act §6 (G\*) |
| §6 | v1.0 §1.2 F-8; review §4 |
| §8 | v1.1 §8 (re-run evidence); review §6 |
