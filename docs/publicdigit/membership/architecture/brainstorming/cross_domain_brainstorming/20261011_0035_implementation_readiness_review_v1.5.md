# Implementation-Readiness Review of Cross-Domain Architecture v1.5, with Normative Contracts

**Date:** 2026-10-11 · **Author:** Claude (architecture discovery; **no production code, no data changes**)
**Reviews:** `20261011_0008_cross_domain_discovery_package_v1.5.md` (v1.5 stays the candidate baseline).
**Responds to:** "Principal review: Does v1.5 have implementation quality?" (2026-10-11).
**Status:** CANDIDATE. Authority: generated. Not ratified; authorises nothing. Election is out of scope.

**Why this document is not v1.6.** It adds no new domain concepts. It contains:
- the implementation-quality review;
- **normative contracts**: the decision algorithm, ScopeToken, temporal rules per fact category, DR-07, and the tenancy facts;
- the **minimum vertical slice**.

v1.5 sections not mentioned here stand.

**Labels:**
- **[SRC]** verified in the repository this round (file:line);
- **[RUN]** executed this round;
- **[RPT]** reported elsewhere, not re-verified;
- **[INF]** inference;
- **[REC]** recommendation;
- **[OPEN]** needs authorised decision.


> **Errata (2026-10-11):** several statements here are corrected by `docs/plans/20261011-0103-cross-domain-architecture-implementation-plan.md` §9 (E-1 `valid_from` / `valid_to` are live in the registered **Landlord** geography migration; E-2 the Membership kernel persists only Admit / End; E-3 token creation must cover the separate `create()` statement; E-4 VS-1 re-scoped to `IsMember` / End; E-5 deciding inside the transaction needs a repository-port change). The independent gap reviews behind them are summarised there in §2.

---

## 1. Answer: is v1.5 implementation-ready?

**No, not as a whole.** Readiness by slice:

| Slice | Ready? | Why |
|---|---|---|
| S-0 security (`PBDIGIT-266`) | **ready to execute now**, independent of every architecture decision | needs only the isolated test database (infrastructure). Items 2 and 3 are **committee write paths** in this scope: application review without reviewer authority; Governance API add / remove committee member without a role check [SRC `PBDIGIT-266` table rows 2–3] |
| Contract specification (this document's §4–§7) | ready to review | specification work, not code |
| **VS-1 minimum vertical slice** (§9) | **ready after one decision round** (XD-4, XD-7, XD-11, XD-12, XD-13, XD-15) + the isolated DB | deliberately avoids Governance (DR-07) and every open business question by using **fixture policies** |
| S-2 Governance, S-4 legacy migration | **not ready** | blocked by DR-07 (accepted ADR-0001) and RD-1…RD-5, BQ-P1, BQ-INC |
| S-5 Organisation, S-6 Jurisdiction, S-7 / S-8 Forum (full) | not ready | XD-2, BQ-U1, BQ-J1, XD-5, BQ-F1…F3, BQ-P9, XD-8 |
| any live (production-route) use of Membership facts | **not ready** | the ratified Membership kernel is not cut over; cutover is blocked by ARB-MEM-01 [RPT CONTEXT-publicdigit]. Live member data is still legacy |

---

## 2. What was inspected and executed this round

| Item | Result | Kind |
|---|---|---|
| ADR-0001 status | canonical file `docs/adr/ADR-0001-committee-read-write-separation.md` is **Accepted** (`:20-21`); ADR index lists it **accepted** (`docs/knowledge/portal/adr-index.md:24`); the files in `archive/superseded-adrs/` are **duplicate copies** consolidated for hygiene (`adr-index.md:33`), **not** a supersession | [SRC] |
| Committee write model location | `app/Contexts/Membership/Domain/Committee/{Committee, CommitteeAssignment, CommitteeConstitution, …}.php` exist | [SRC] |
| Ratified act N-6 | "§10: the aggregate must not absorb committee participation … migration must place it in Governance; known legacy boundary defect" (`2026-10-08-membership-architecture-ratification-act.md:19`) | [SRC] |
| Membership commit mechanism | `PostgresMemberRepository.php:159-161`: `UPDATE org_memberships SET version = version + 1 WHERE organisation_id = ? AND id = ? AND version = ?` (compare-and-set, **row lock**); `seq` = new version (`:178`); the docblock states "never retries" (`:39`) | [SRC] |
| Laravel transaction retry | Laravel **11.41.3**. `transaction($callback, $attempts = 1)`: **no retry by default** (`ManagesTransactions.php:23`). Retried errors: 40001 and messages incl. "deadlock detected" (`DetectsConcurrencyErrors.php:19-34`). **PostgreSQL lock timeout (55P03) is not recognised** | [SRC] |
| Isolation level | no `isolation` setting in `config/database.php` → server default (READ COMMITTED) | [SRC] (connection names read; no values printed) |
| Tenancy | connections `pgsql`, `pgsql_audit`, `pgsql_geo`, `testing`, … (`config/database.php:70-140`). **No runtime connection switching** in `app/` (search for `setDefaultConnection`, `DB::purge`, `Config::set('database`). Folders named `Migrations/Tenant` are loaded into the **default shared connection** (`AppServiceProvider.php:405`; `MembershipServiceProvider.php:578`): a folder name, not a separate-database mechanism. 64 files reference `BelongsToTenant`; no tenancy package in `composer.json` | [SRC] |
| Geography temporal support | per-unit optimistic `version` (GEO-INV-06, `GeoAdministrativeUnit.php:32,56`). `valid_from` exists only in `Geography/…/Migrations/Tenant/…create_geo_administrative_units_table.php:75`; that subfolder is **not registered** (registered: `…/Migrations/Landlord` at `GeographyServiceProvider.php:86` and the parent folder at `AppServiceProvider.php:406`). Laravel's loader is non-recursive, so the column is **likely not live** | [SRC] + [INF] |
| Committee ↔ geography today | `database/migrations/2026_05_13_000001_add_geo_unit_id_to_committees.php`, `…_000003_add_canonical_geo_id_to_committees.php`: **one** geo unit per committee (column), not M:N | [SRC] (file names; columns not read) |
| `AuthorityCheckPort` | `check(TenantId $tenant, string $actor, MembershipAct $act): AuthorityDecision` (`AuthorityCheckPort.php:17`): no subject, decision reference or as-of | [SRC] |
| Models | `g12` (new) [RUN]; `g11` E6–E8 (new) [RUN] on a private throwaway PostgreSQL 18.6 cluster in the scratch directory (TCP disabled; stopped afterwards; nothing shared touched) | [RUN] |

**Not inspected:** the route / middleware code of `PBDIGIT-266` beyond the ticket text; Laravel's `DatabaseTransactionsManager` callbacks; vendor raw-PDO paths.

---

## 3. Severity-ranked findings

| ID | Sev. | Finding | Kind | Failure scenario | Required action | Safe to proceed? |
|---|---|---|---|---|---|---|
| IR-1 | **P0** | ADR-0001 (Accepted) vs ratified act N-6 on committee ownership | [SRC] | two teams implement Committee invariants in Membership and in Governance; the authoritative owner is unclear | DR-07 act (§8) | no Governance slice until decided |
| IR-2 | **P0** | the precedence of ordinary rules was ambiguous ("deny wins at equal priority" leaves different priorities undefined) | [SRC v1.5 §4.2] | two implementations return different results for a high-priority grant vs a low-priority deny | §4 normative algorithm + **XD-13** (combining algorithm). g12: the two candidates differ in **12 / 1,920** vectors | after XD-13 |
| IR-3 | **P0** | Laravel does not retry on lock timeout, retries nothing by default, and may silently retry a closure that contains a **pre-computed** decision | [SRC] | (a) a 55P03 surfaces as a 500 error; (b) if an implementation adds `attempts > 1` around a closure that reuses a stale read set without re-guarding, the retry may commit on stale authority | ScopeToken contract TC-9 / TC-10 (§5): the guard runs **inside** the closure on every attempt; an application retry wrapper classifies 55P03; exhaustion → `DEPENDENCY_FAILURE` | contract-level |
| IR-4 | **P0** | the WB-10 order "discover → acquire → evaluate" was underspecified for dependencies only found by reading (delegation) | [SRC v1.5 §5.4] | lock the delegation, read the delegator's tenure unlocked, delegator revoked concurrently → stale-authorised action | TC-6 discovery loop; **g11 E8** below | contract-level |
| IR-5 | **P0** | `recordedAt` is **not** a valid replay cutoff: it is assigned during a transaction, commit order can differ, and the timestamps may disagree with commit order | [INF] from [SRC] CAS + `seq` = version | `AS_DECIDED` replay by timestamp includes a fact committed after the decision, or excludes one committed before | §6 temporal contract TR-4: replay by **per-scope seq** (= token version) | contract-level |
| IR-6 | P1 | v1.5 global statement "all stored relationships use Annul / Void / Correct" conflicts with forward-only Mandate / Policy versions | [SRC v1.5 §2 vs §6] | an implementation offers `Correct(MandateVersion)` and silently changes past decisions | §6 per-category table | yes, once the contract is adopted |
| IR-7 | P1 | ScopeTokenPort could become a backdoor API into another context | [INF] | Forum code bumps a Membership token or reads Membership tables directly | §5 TC-1 / TC-2: only the owner bumps; others may only *acquire shared*; the port acquires in canonical order itself | contract-level |
| IR-8 | P1 | guards hold `FOR SHARE` on **Membership's own aggregate row** (`org_memberships`), so Membership commands wait behind forum transactions | [INF] from [SRC] CAS | latency coupling: a long forum transaction delays admissions or ends for that person | TC-11: guarded transactions must be short; statement and lock timeouts; monitored | operational |
| IR-9 | P1 | the committee ↔ geography relation is single-valued in the schema; the target R-10 is M:N | [SRC] | migration must convert; overlap rules (BQ-J1) decide the target | in S-6 entry criteria | not before S-6 |
| IR-10 | P1 | no live use of new Membership facts before cutover | [RPT] | a vertical slice reading the new kernel sees no real members | VS-1 is **test-only behind a disabled route** (release gate, like the Option E "P1 with release gate" precedent [RPT]) | VS-1 only test-only |
| IR-11 | P2 | `CommitteeType` overloading; two `TenantId` VOs; duplicate events | [RPT] | migration ambiguity | S-4 parity plan | later |

---

## 4. Normative decision algorithm (candidate contract; verified within bounds by `g12`)

```text
decide(request, t):
  1  modules := {Platform@active, Org(org)@active, Resource(type)@active}
     if any module unavailable                      → DEPENDENCY_FAILURE(MODULE_MISSING)
  2  if any module fails lint (should be unreachable) → CONFIGURATION_ERROR(LINT)
  3  facts := PIP reads (FactRead = Known(T|F, scope versions) | Unknown(reason, scope versions?))
  4  D := AND(all mandatory conditions)                       -- any module
          AND NOT OR(all mandatory prohibitions)            -- any module
          AND ORDINARY                                      -- Resource module only
     ORDINARY := DO:  OR(grants) AND NOT OR(denies)        -- deny-overrides, priorities ignored  [REC]
              |  PO:  priority-ordered; ties: deny wins     -- only if a requirement needs exceptions
  5  evaluate D in strong Kleene logic:
       T → PERMIT ;  F → DENY ;
       U → DEPENDENCY_FAILURE if any fact referenced by a rule is Unknown with reason ∈
               {SOURCE_UNAVAILABLE, PROJECTION_STALE, INCOMPLETE_QUERY, OUT_OF_FOOTPRINT}
           else INDETERMINATE
  6  DecisionRecord := {outcome, witness (rules that fixed D's value), read set {scope → version},
                        module versions, semantics version, algorithm version, world declarations, t, v}
```

**Properties:**
- **P-SOUND [RUN g12]:** PERMIT holds in every completion of the unknown facts, and DENY holds in every completion. g12: 0 unsound PERMIT, 0 unsound DENY in 1,920 vectors for both DO and PO.
- **General argument:** regularity of strong Kleene logic (v1.5 §4.3) applies to D because D is a propositional formula.
- **P-MISSING:** a missing module never yields PERMIT (step 1 precedes all evaluation).
- **Completeness gap:** 20 (DO) / 22 (PO) vectors give INDETERMINATE or DEPENDENCY_FAILURE although every completion agrees. These are routed to review or retry, never permitted.
- **Test vectors:** `models/g12_test_vectors.json` (1,920 vectors, expected outcomes for DO and PO; SHA-256 `745fc839…`). **Any implementation must reproduce `expected_<chosen algorithm>` exactly.**

**XD-13 (new): combining algorithm for ordinary rules.**

| Option | Behaviour | Consequence |
|---|---|---|
| **DO** deny-overrides [REC] | any applicable ordinary deny defeats every grant | simple and predictable; exceptions must be modelled as narrower grants or as separate resource types |
| **PO** priority-ordered | the highest applicable level decides; deny wins ties | supports exception rules ("chair may post in a locked topic"); differs from DO in 12 / 1,920 vectors; harder to audit |

Decided by PO / ARB; the policy authors are the affected party.

---

## 5. Normative ScopeToken contract (TC-1…TC-12)

| # | Rule | Evidence |
|---|---|---|
| TC-1 | **Ownership:** every scope token belongs to exactly one context and lives in that context's token table (or **is** its aggregate version row, as Membership's `org_memberships.version` already is [SRC]). **Only the owning context increments it**, inside its own fact-changing command | [SRC] CAS |
| TC-2 | **Port surface:** `ScopeTokenPort::acquire(Set<ScopeRef>, mode = SHARED)` → `Map<ScopeRef, version>`, and `ScopeTokenPort::current(Set<ScopeRef>)`. No other operations. Foreign contexts can never bump, insert or delete. **The port sorts and locks itself**, so callers cannot violate the order | [REC] |
| TC-3 | **Canonical order:** a global context rank (Identity 1 < Organisation 2 < Membership 3 < Governance 4 < Geography 5 < Authorization 6 < Communication 7), then the scope key with `COLLATE "C"`. A transaction acquires **all** tokens (own and foreign) in one acquisition phase before any mutation | g11 E3 / E6 |
| TC-4 | **Mode:** tokens the transaction will write are acquired `FOR UPDATE`; read-only dependencies `FOR SHARE` (WB-5a) | g11 E5 |
| TC-5 | **⚠ Superseded as the default by `20261011_0100_verification_round_closure.md` §2:** owner-maintained ancestor tokens; no foreign insert. **Absent scopes (original text):** an owner creates a token with `INSERT … ON CONFLICT DO UPDATE SET version = version + 1`. The shared acquisition of a possibly absent scope performs `INSERT … ON CONFLICT DO NOTHING` (version 0 = "nothing exists") before locking. Only the owning context's port implementation performs that insert | g11 E4 |
| TC-6 | **Footprint discovery (WB-10 made precise):** (1) *discover*: compute the predicate's static footprint template and read the dynamic parts (e.g. the delegator) without locks; (2) *acquire* in canonical order; (3) *evaluate* under locks through the PIP layer, which **records every scope it reads**; (4) *validate*: if a read touched a scope not yet held, then acquire it now **if it sorts after every held scope**, else **roll back and restart with the union**; (5) repeat until the read set ⊆ the held set. The loop is bounded by the footprint bound (BQ-DEL depth); exceeding it → `DEPENDENCY_FAILURE(FOOTPRINT_UNBOUNDED)` | g11 E8 |
| TC-7 | **Fact mutations that must bump** a token: create, revoke / end, annul, void, correct, re-point (e.g. delegation target), insertion of a previously absent fact, deletion (never expected; append-only), policy activation, geography snapshot publication | v1.5 WB-1 |
| TC-8 | **Mismatch (cached decision):** if acquired versions ≠ decision read set → roll back, re-decide, retry | g10 Q1 |
| TC-9 | **Retries:** the guard and the decision run **inside** the transaction closure on **every** attempt. Retry classification: 40001, 40P01, 55P03 (lock timeout) and version mismatch. 55P03 needs an application-level classifier because Laravel does not detect it [SRC]. Bound N (proposal 3) | [SRC] |
| TC-10 | **Exhaustion:** after N attempts → `DEPENDENCY_FAILURE(RETRY_EXHAUSTED)`; **never** a stale decision; the operation is not partially committed (single transaction) | — |
| TC-11 | **Duration:** guarded transactions are short; `SET LOCAL lock_timeout` and `statement_timeout` per transaction; lock waits monitored (IR-8) | [INF] |
| TC-12 | **Scope of the guarantee:** one PostgreSQL database (XD-15). Read replicas and split read / write configurations are refused for guarded transactions | v1.5 WB-9 |

**Real-time revocation (v1.5 §5.5).** The proof sketch's assumptions now map to TC rules:
- A1 → TC-1 / TC-7;
- A2 → TC-3 / TC-4 / TC-6;
- A3 → READ COMMITTED [SRC default];
- A4 → TC-12.

The remaining gap: the **actual command handlers must obey TC-1…TC-10**. Only real-schema tests can show that (VS-1).

---

## 6. Temporal contract per fact category (resolves IR-5, IR-6)

| Category | Examples | effective time | correction | storage | replay |
|---|---|---|---|---|---|
| **TR-1 lifecycle facts** | Membership facts; Participation; Appoint / Vacate; UnitAffiliation; JurisdictionAssignment; Committee lifecycle; Delegation; Mute | `effectiveAt` (past or future, bounded by policy, e.g. Membership D-5 [RPT]) | **Annul / Void / Correct** append new facts (ratified algebra) | append-only; per-scope `seq` assigned under the scope lock | facts with `seq ≤ readSet[scope]` |
| **TR-2 rule versions** | Mandate; PolicyModule; PolicySemantics; Position definition (κ, ε) | `effectiveFrom ≥ activation` | **forward-only**: no Annul or Correct; Void only before activation; a mistake is fixed by a new version (back-dating only by an authorised act creating a *new* version) | immutable versions + token bump on activation | the pinned version id |
| **TR-3 reference snapshots** | geography (XD-5) | snapshot validity | a new snapshot | immutable snapshots | the pinned snapshot id |
| **TR-4 decisions** | DecisionRecord | `t` (evaluation), `v` (valid time used) | **never corrected**; a re-evaluation is a new linked record | append-only | `AS_DECIDED` = the stored read set; `IN_HINDSIGHT` = current heads |
| **TR-5 content** | post body revisions | none (no truth claim) | revisions; moderation state is TR-1 | append-only revisions | revision id |
| **TR-6 derived predicates** | EffectiveTenure; IsMember; vacancies; access | computed at (`v`, read set) | not stored as truth; projections are rebuildable (IP-4) | — | recomputed |

**Replay rule (IR-5):** the knowledge cutoff of a decision is its **read-set vector of per-scope versions**, never a timestamp.
- **Why:** `recordedAt` is assigned at statement time; commit order can differ. The per-scope `seq` is assigned under the scope's row lock, so it is consistent with commit order inside each scope [SRC Membership CAS].
- **Corollary:** every TR-1 fact stores the `seq` of each scope it bumped.

---

## 7. Invariant catalogue (enforcement view)

| Invariant | Population (quantifier) | Owner | Enforcement point | Consistency structure | Depends on | Concurrency risk → mitigation |
|---|---|---|---|---|---|---|
| INV-M, INV-EP, INV-9, INV-COV | periods of one (p, o) | Membership | `G*` in the aggregate | one aggregate, CAS | ratified | — (verified) |
| INV-P2 ≤ 1 participation episode per (p, c) at t | participations of c | Governance | Committee aggregate | one aggregate | RD-1, DR-07 | CAS on the committee token |
| INV-P3 \|holders(s, t)\| ≤ κ(s) | tenures of positions of c | Governance | Committee aggregate | one aggregate | BQ-P1 (κ), DR-07 | g7: 0 violations |
| INV-P6 no incompatible pair held by p | positions of p across committees of o | Governance | Committee + **PositionHoldings(p, o)** | **two-root** transaction, TC-3 / TC-4 | **BQ-INC** (if the scope is global, the key becomes (p) and spans organisations, which would cross tenancy) | g7 Q3 738 → 0; E3 / E5 deadlock-free |
| INV-P4 tenure ⊆ participation (if required) | p in c | Governance | Committee aggregate | one aggregate | RD-2, BQ-P2 | — |
| EffectiveTenure = Tenure ∧ ε ∧ Active | derived | Governance | query (PIP) | none (derived) | RD-3, BQ-P3 | footprint = committee + membership tokens |
| Delegation scope ⊆ Mandate at use; chain valid | the chain of a delegate | Governance | PDP via TC-6 | none (derived) + footprint | **BQ-DEL** | g11 E8 |
| Duty persists until discharge | tenure of p | Governance | Committee aggregate | one aggregate | BQ-P8 | — |
| Jurisdiction exclusivity (if adopted) | committees of kind K over GeoUnit g | Governance | JurisdictionRegistry(o, K) | registry aggregate + two-root | **BQ-J1**, XD-5 | analogous to INV-P6 |
| O-1 acyclic, O-2 ≤ 1 parent | units of o | Organisation | OrganisationStructure | one aggregate | — | CAS |
| A-1 PERMIT soundness | every decision | Authorization | §4 algorithm | none | XD-7, XD-13 | g8, g12 |
| A-6 no commit after a committed revocation | every protected write | each writer + owners | TC-1…TC-10 | lock protocol | XD-10, XD-12, XD-15 | g11 E1–E8; real schema: VS-1 |
| FO-4 access rebuildable | forum projections | Communication | rebuild test | none | — | — |

---

## 8. DR-07: decision-ready

**Question:** who owns the Committee write model?

**Facts [SRC]:**
- ADR-0001 is Accepted and places it in `Membership/Domain/Committee` (code exists).
- The ratified Membership act (2026-10-08) note N-6 calls this a legacy boundary defect, to be migrated to Governance.
- The ADR index lists ADR-0001 as accepted.
- `PBDIGIT-266` items 2–3 report committee write routes without an authority check.

| Option | Content | Consequences | Migration |
|---|---|---|---|
| **A: supersede now** [REC] | new ADR: Governance owns Committee, Position, Participation, Tenure (target design v1.5); ADR-0001 → *Superseded by ADR-NNNN*; legacy Membership committee code **frozen** (bug and security fixes only) until migrated | consistent with ratified N-6; unblocks S-2; legacy code coexists for a period | S-4 parity migration (four legacy models → Governance), routes moved behind PDP guards |
| B: supersede, but keep the legacy write model authoritative until S-4 completes | as A, but the legacy model stays the source of truth during migration; Governance starts as a shadow | lower immediate risk; dual model for longer; needs a reconciliation report | as A, plus a cutover gate |
| C: retain ADR-0001 | Committee stays in Membership | **contradicts the ratified act §10 / N-6**, so it needs an amendment of the ratified Membership architecture (Change Request), which is a higher bar | none; the Governance target design is withdrawn |

**Approval required:**
- a Human PO / ARB act recording the chosen option, the new ADR's id and status, the reason (N-6), and the freeze rule for legacy code;
- an ADR index update (`docs/knowledge/portal/adr-index.md`).

**Not decided by this document.**

---

## 9. Recommended next executable slice: VS-1 "protected forum contribution, test-only"

**Purpose:** prove the cross-domain mechanics end to end on the real schema, **without** Governance (DR-07), without business rules (fixture policies), and without live use (no cutover dependency).

**Path:** `Actor → Membership facts (new kernel; MembershipQueryPort) → PDP (§4, chosen algorithm) → acquisition phase (TC-3: Membership token = org_memberships.version; Communication token forum:f) → Post committed → DecisionRecord → AS_DECIDED replay`

| Element | Content |
|---|---|
| **Entry decisions** | XD-4 (Authorization ownership), XD-7 (K2), XD-11 (outcomes), XD-12 + TC-1…TC-12 (tokens), XD-13 (combining), XD-15 (single shared database for this horizon), **XD-14 (new):** create the Communication context with Forum / Topic / Post aggregates (minimal) |
| **Entry prerequisites** | isolated test database (same as `PBDIGIT-266`); EP-01 plan approved |
| **Scope** | one command `ContributePost`; one fixture PolicyModule ("members who are not suspended may post", **labelled fixture, not a business rule**); minimal PDP; ScopeTokenPort for Membership (adapter over the existing version row) and Communication; DecisionRecord table; **no HTTP route enabled in production** (feature-flagged off, release gate) |
| **Positive tests** | an active member posts → PERMIT; post committed; DecisionRecord stored; replay `AS_DECIDED` = PERMIT |
| **Negative tests** | non-member → DENY; suspended → DENY; Membership source unavailable → DEPENDENCY_FAILURE; missing module → DEPENDENCY_FAILURE; all **g12 vectors** for the implemented subset |
| **Race tests (real handlers)** | suspension commits first → post refused; post holds its lock first → suspension commits after the post (A-6); absent-fact insertion (first Suspend for (p, o)); lock timeout → retry → bounded exhaustion → DEPENDENCY_FAILURE; three concurrent sessions |
| **Rollback / failure** | any exception → no post row, no DecisionRecord for a non-committed write (or a record marked aborted; to be decided in the plan); no partial state |
| **Exit gate** | all of the above green on the isolated DB; g11 re-run on the isolated DB identical; independent review; Human PO / ARB acceptance **before** VS-2 (add Governance tenure after DR-07) |

---

## 10. Tenancy (verified facts vs target options)

**Fact [SRC]:**
- one shared PostgreSQL database with `organisation_id` scoping;
- no runtime connection switching;
- "Tenant"-named migration folders loaded into the shared connection;
- no tenancy package.

**Options:**

| Option | Transactions and tokens | Identity / replay | Migration cost |
|---|---|---|---|
| **XD-15-A shared DB + strict `organisation_id`** [REC for this horizon] | TC-12 holds for everything | cross-organisation persons are trivial | none |
| B database per organisation | TC holds *within* one organisation; central data (identity, platform modules, geography) is outside → needs Δ (XD-10) or immutable pinned versions | identity links cross databases | large (reported ~70–110 files [RPT]) |
| C hybrid (central + per-organisation) | as B | as B | as B |

**Recommendation:** decide **A** for the current architecture horizon. Revisit only on a concrete isolation requirement (regulatory, contractual). **No distributed consistency mechanism should be designed before that.**

---

## 11. Verification matrix

| Contract | Bounded model | Unit | DB-backed (isolated) | Route | End to end |
|---|---|---|---|---|---|
| Decision algorithm §4 | g8 P1 / P3, **g12** [RUN] | g12 vectors in PHP (VS-1) | — | VS-1 (flag off) | VS-n |
| ScopeToken TC-1…TC-12 | g10, **g11 E1–E8** [RUN, private cluster] | port contract tests | **VS-1 race tests**; g11 on the isolated DB | — | VS-n |
| Temporal TR-1…TR-6 | g9, g10 Q2 | replay unit tests | VS-1 replay | — | annulment + replay scenario |
| Governance invariants | g7, g9 | Committee / PositionHoldings units | S-2 two-root tests | S-2 | concurrent-appointment scenario |
| Security `PBDIGIT-266` | — | — | — | S-0 feature tests | — |

---

## 12. Maturity, gates and TODOs

**Maturity:**
- **Architecture:** candidate baseline (v1.5) plus normative contracts; one ownership blocker (DR-07).
- **Executable contracts:** decision algorithm and test vectors exist; ScopeToken and temporal contracts are specified.
- **Implementation:** unchanged (Membership kernel, Geography).
- **End to end:** not demonstrated.
- **Evidence gates:** **7 / 30**. This round produced verification evidence only at bounded-model level, so no gate reached its target.

**TODOs in priority order (dependency → acceptance criterion):**

- **P0 · Security:** run S-0 / `PBDIGIT-266` on the isolated DB (→ infrastructure provides the DB) → each route returns 403 for a non-privileged user of the same and of another organisation, or a fix story exists. Keep `PBDIGIT-270` separate.
- **P0 · DR-07:** PO / ARB act choosing A / B / C (→ none) → recorded act + ADR index update.
- **P0 · Decision round 1:** XD-4, XD-7, XD-11, XD-12 (incl. TC-1…TC-12), XD-13, XD-14, XD-15, and XD-1 (with TR-1…TR-6) → each recorded with option, rationale and status.
- **P0 · VS-1 plan (EP-01):** after round 1 + the isolated DB → plan approved; then VS-1 implementation by an authorised slice → §9 exit gate.
- **P1 · Business questions:** BQ-P1, BQ-P2, BQ-P3, BQ-INC, BQ-DEL, BQ-P8, BQ-J1, BQ-U1, BQ-P9, BQ-F1, BQ-F2, BQ-F3 (→ PO / statute owners) → answered or explicitly deferred with the affected features named.
- **P1 · Contracts:** MembershipQueryPort / GovernanceQueryPort signatures with footprints and world declarations; AuthorityCheckPort extension (subject, decision ref, as-of); DecisionRecord schema; PolicyModule activation + lint (→ round 1) → reviewed and approved.
- **P1 · Verification:** run g11 on the isolated DB (→ the DB); g7 Q4 (Vacate interleaving, κ > 1; → BQ-P1); g9 delegation chains (→ BQ-DEL, BQ-P8); a jurisdiction-exclusivity model (→ BQ-J1); g3 on kernel-admissible histories (→ C-1 acceptance) → each with stated bounds.
- **P1 · Migration design:** S-4 parity plan for the four legacy committee models and the single-valued committee geo columns (→ DR-07, RD-5).
- **P2 · Slices:** VS-2 (Governance tenure in the PDP; → DR-07, RD-1…RD-4, VS-1 accepted); S-2, S-4, S-5, S-6, S-7, S-8, S-9 in v1.5 §9 order, each with its own plan.
- **P3 · Acceptance:** former chair; district secretary; suspended in A, active in B; concurrent appointments; revocation during a post; annulment with replay. All on the isolated DB and real routes.

**Known / recommended / needs authority / unverified:**
- **Known:** §2 [SRC] and [RUN] rows.
- **Recommended:** §4 DO, TC-1…TC-12, TR-1…TR-6, DR-07 option A, XD-15 option A, VS-1.
- **Needs human authority:** DR-07, every XD / RD / BQ item.
- **Unverified:** behaviour of the real command handlers under TC; route security (`PBDIGIT-266`); anything beyond two or three sessions; the live status of the Geography `valid_from` column (inferred from the loader, not tested).

---

## 13. Traceability (new artefacts)

| Artefact | Purpose | Evidence |
|---|---|---|
| `models/g12_pdp_reference_algorithm.py`, `models/g12_test_vectors.json` | IR-2: one normative algorithm; DO vs PO; test vectors | [RUN] 1,920 vectors; DO and PO 0 unsound PERMIT / DENY; differ in 12 |
| `models/g11_pg_write_boundary.php` E6–E8 | FV-1 (three sessions, per-context tables), FV-2 (decide-inside vs annulment), IR-4 (discovery via delegation) | [RUN] completed 2026-10-11 00:39, exit 0, executed script SHA-256 `23e63c81…`. **E6:** plain read 2,300 / 4,200 unauthorised; canonical `FOR SHARE` 0 / 4,200, 0 deadlocks. **E7:** inside-plain 16 / 35; inside-locked 0 / 35. **E8:** partial locks 16 / 35; extended locks 0 / 35. Limits and the corrected token contract: `20261011_0100_verification_round_closure.md` §1–§2 |
