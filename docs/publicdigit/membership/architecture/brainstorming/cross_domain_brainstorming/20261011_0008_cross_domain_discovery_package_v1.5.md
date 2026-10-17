# Cross-Domain Organisational Architecture: Consolidated Discovery Package v1.5

**Date:** 2026-10-11 · **Author:** Claude (architecture discovery and requirements analysis; **no production code**)
**Status:** CANDIDATE. Authority: generated. Not ratified; authorises nothing.
**Scope:** membership, people, committees, positions, authority, geographical jurisdiction and discussion forums. **Election is out of scope.** Election sources are used only for organisational vocabulary or organisational authority.

**Why a consolidated document.** v1.0–v1.4 amend each other in five layers, so no single document states the current candidate architecture. This package **replaces reading v1.0–v1.4 for current content**. Those documents remain the history and are cited for the evidence each one produced. It also resolves six problems that were still open: §0.2 lists them.

**Labels:**
- **[SRC]** a source fact verified in the repository this round;
- **[RPT]** reported in a repository document, not re-verified this round;
- **[INF]** inference;
- **[REC]** recommendation;
- **[OPEN]** needs an authorised decision.

**Status dimensions (v1.4 §5):** Proposal · Approval · Implementation · Verification · Acceptance. These are never collapsed into one "done".


> **Errata (2026-10-11):** several statements here are corrected by `docs/plans/20261011-0103-cross-domain-architecture-implementation-plan.md` §9 (E-1 `valid_from` / `valid_to` are live in the registered **Landlord** geography migration; E-2 the Membership kernel persists only Admit / End; E-3 token creation must cover the separate `create()` statement; E-4 VS-1 re-scoped to `IsMember` / End; E-5 deciding inside the transaction needs a repository-port change). The independent gap reviews behind them are summarised there in §2.

---

## 0. Principal review of v1.4

### 0.1 What v1.4 gets right (retained)
- **One authoritative owner per fact;** contexts integrate through query ports and version-only guards. Events are triggers, never truth.
- **Authority is derived, never stored.**
- **K2 guard semantics:** grants and conditions need T; prohibitions and denies bind on T ∨ U.
- **Write-boundary guard** with `FOR SHARE` on version rows, plus scope tokens for absence.
- **Committee + PositionHoldings** as a documented two-root transaction with a sorted lock order.
- **Statistics:** use the exact bound only, and only with a sampling frame.

### 0.2 What was still unproven or wrong (resolved here)

| # | Problem | Resolution in v1.5 | Evidence |
|---|---|---|---|
| U-1 | **ADR conflict not surfaced:** ADR-0001 (*Accepted*, `docs/adr/ADR-0001-committee-read-write-separation.md`) places the committee **write model in the Membership context** (`app/Contexts/Membership/Domain/Committee/`, which exists [SRC]). The ratified Membership act (2026-10-08) note **N-6** calls that legacy code a boundary defect to be placed in Governance [SRC]. v1.0–v1.4 recommended Governance without naming the ADR that must be superseded | DR-07 (§6): a **status act on ADR-0001** is a hard prerequisite of every Governance slice | [SRC] ADR-0001; act N-6; audit row K-3 (2026-10-09) |
| U-2 | **Scope tokens that do not exist yet:** the predicate "is P a member of O" for a non-member has **no Membership aggregate**, so no token exists to lock. Lazy token creation re-opens the phantom race | WB-1a: guards **upsert-then-lock**; creators **upsert-increment** | g11 E4: lazy read **16 / 35** unauthorised; guard-upsert **0 / 35** (PostgreSQL 18.6) |
| U-3 | **Lock-upgrade deadlock:** a transaction that guards a token (`FOR SHARE`) and then writes it deadlocks against a twin, *even with sorted order* | WB-5a: a token the transaction will write is locked `FOR UPDATE` at acquisition | g11 E5: share-then-update **36 / 70** deadlocks; for-update up front **0 / 70** |
| U-4 | **Lock-order mismatch:** WB-5 says "sorted by (context, scope)", but the illustrative SQL used `ORDER BY scope` on a single table. These differ whenever tokens live in per-context tables, and plain text order depends on collation | WB-5 restated (§5.4): a fixed **context rank** plus `COLLATE "C"` within a context; one acquisition phase before any mutation | [INF] from E3 / E5; probe uses one table |
| U-5 | **Multi-module and alternative grants unverified** | proven sound **in general** (§4.3) + bounded check | g8 P3: 207,000 cases, **0** unsound, **0** permits with a missing module; completeness gap **481** |
| U-6 | **The real-time revocation claim** rested on a probe only | a proof sketch under stated assumptions (§5.5) | argument + g11 E1 / E2 |

### 0.3 What remains unproven
- every protocol on the **real schema and Laravel transaction code** (no implementation exists);
- more than two concurrent sessions;
- route-level security (`PBDIGIT-266`, not executed);
- all business rules (none approved);
- cross-database behaviour (no design beyond XD-10).

---

## 1. Canonical glossary

**Kinds:**
- **AR:** aggregate root;
- **E:** entity inside an aggregate;
- **VO:** value object;
- **F:** fact (append-only record);
- **D:** derived predicate;
- **P:** policy / rule object;
- **PR:** projection;
- **AS:** application service.

| Term | Definition | Kind | Owner | Status / evidence |
|---|---|---|---|---|
| Person | a natural human | AR (identity) | Identity & Access | owner [RPT] ratified act §1; scope of identity **[OPEN] XD-3** |
| Account | a login identity; not 1:1 with Person | AR | Identity & Access | Proposed |
| Actor | the Account / Person behind one request | VO per request | — | Proposed |
| OrganisationAffiliation | platform access / roles in an organisation (owner, admin, …) | F / relation | Organisation | **[SRC] EM-VOC-002 ADOPTED** ("not Organisation Membership") |
| Organisation | a tenant; root of structure and policy | AR | Organisation | Proposed; tenancy = one shared database, `organisation_id` scoping [RPT, 64 files use `BelongsToTenant` [SRC]] |
| OrganisationalUnit | branch, chapter, wing, department | E in OrganisationStructure | Organisation | Proposed; today mis-modelled as committee types [RPT] |
| UnitAffiliation | a Person's belonging to a unit over time | F log, AR per (p, o) | Organisation | Proposed **[OPEN] XD-2** |
| Membership (EM-VOC-001 "Member") | enduring relation Person ↔ Organisation; one per (p, o) | AR (D1) | Membership | **Approved (ratified 2026-10-08)**; kernel Implemented; unit-Verified [v1.1 run] |
| MembershipPeriod | one episode from Admit to End | E | Membership | Approved |
| MembershipType | classification segment of a period | VO / segment | Membership | Approved |
| Committee | an **organ**: a constituted body with lifecycle, term, positions | AR | **Governance** (target) / **Membership** (ADR-0001, legacy) | **[OPEN] DR-07** |
| Position | an office or seat of a committee; capacity κ; eligibility ε; outlives its holders | E in Committee | Governance | Proposed |
| PositionKind | chair, treasurer, moderator …: **the organisational meaning of "role"** | VO | Governance | Proposed |
| Participation | Person part of a committee over time | F log in Committee | Governance | Proposed (RD-1: subject = Person) |
| Appointment | the act opening a tenure, with provenance and authority evidence | F | Governance | Proposed |
| Tenure | Person holds Position over `[s, e)`; derived from Appoint / Vacate facts | D interval | Governance | Proposed |
| Term | the time period of a committee or tenure | VO | Governance | Proposed |
| Mandate | **scope of authority**: decision kinds × scope; forward-only versions | P | Governance | Proposed |
| Duty | an obligation attached to a tenure; may outlive it until a discharge act | F / D | Governance | Proposed (draft G-1; BQ-P8 open) |
| Delegation | a time-bounded transfer of **authority, never duty**; scope ⊆ Mandate at use | F | Governance | Proposed |
| PositionHoldings | per (Person, Organisation): positions held, for cross-committee invariants | AR | Governance | Proposed (g7) |
| EffectiveTenure | `Tenure ∧ ε(Membership predicates) ∧ CommitteeActive` | D | Governance | Proposed (g9 T-3 / T-6) |
| JurisdictionAssignment | the GeoUnits a committee answers for, over time | F log; AR candidate | Governance | Proposed; exclusivity **[OPEN] BQ-J1** |
| GeoUnit | an administrative area | AR (reference) | Geography | Implemented [SRC] with a per-unit optimistic `version` (GEO-INV-06) |
| Permission | `(action, resourceType)` capability | VO | Authorization | Proposed |
| PolicyModule | an immutable versioned rule set; **grants only for its own resource types** | AR (versioned) | the resource-owning context; Platform / Org modules: restrictions only | Proposed |
| PolicySemantics | rule language, guard logic, combination algorithm | P (versioned) | Authorization | Proposed |
| AuthorizationDecision | outcome + reason + witness for one request | VO | Authorization | Proposed |
| DecisionRecord | decision + read set (scope tokens) + module, semantics and algorithm versions + `t`, `v` | F (audit) | Authorization → Audit | Proposed |
| ScopeToken | a `(scope, version)` row covering existing **and not-yet-existing** facts of one scope | infrastructure VO of each AR | each fact-owning context | Proposed (g10, g11) |
| Forum / Topic / Post | communication space / thread / contribution | AR each | Communication | Proposed; **no code** [SRC] |
| ModerationAction | a moderator's act citing a Permit DecisionRecord | F | Communication | Proposed |
| Mute | a forum-local restriction; **not** Membership suspension | F | Communication | Proposed |
| Subscription / Invitation | opt-in / invite; **never** an access grant | F | Communication | Proposed |
| AuditRecord | evidence that something was recorded or decided | F | Audit | Proposed |

**Forbidden or ambiguous terms** (retained from v1.0 §2.3, plus two new):
- bare "Role", "Affiliation" and "Participant";
- "Mandate" used to mean a time period;
- "Geographical committee" used as a type;
- bare "Election Committee";
- **new:** "Committee membership" (say Participation or Tenure, see EM-VOC-001);
- **new:** "obligation" (say Duty for Governance, Charge for Finance; draft G-1).

**Concepts found duplicated or overloaded in code** [RPT], to resolve under DR-07 / RD-5:
- four "member in committee" models (`CommitteeAssociation`, `Committee` + `CommitteeAssignment`, Governance `Committee` role map, `OrganisationParticipant` / election seats);
- `CommitteeType` mixing level, wing and geography;
- two `TenantId` value objects and two `MemberRegistered` events (capability review 2026-08-06).

---

## 2. Relationship catalogue

**Nature:** **S** = stored fact; **D** = derived; **P** = projection; **E** = evaluated decision.
**Every stored relationship** uses the ratified fact pattern: `effectiveAt` / `decidedAt` / `recordedAt` / `seq`, with Annul / Void / Correct.

| ID | Source → target | Meaning · cardinality | Temporal | Owner | Nature | Invariant supported | Depending commands / queries | Consistency / failure | Contract | Evidence · open |
|---|---|---|---|---|---|---|---|---|---|---|
| R-01 | Person → Membership | belongs to organisation · 1 : N (≤ 1 per org) | bitemporal | Membership | S | INV-M | Admit, End; `IsMember` | in-aggregate | MembershipQueryPort | Approved |
| R-02 | Membership → Period | episodes · 1 : N, disjoint per (p, o) | bitemporal | Membership | S | INV-EP, INV-9 | Admit, End, Annul | G\* in-aggregate | same | Approved |
| R-03 | Period → Type | segments · 1 : N | bitemporal | Membership | S | — | ChangeType | in-aggregate | `CurrentType` | Approved (BD-TYPE) |
| R-04 | Person → Unit (UnitAffiliation) | belongs to unit · M : N | bitemporal | Organisation | S | UA-1 (primary?), UA-2 (dissolved unit non-effective, D) | Join, Leave; `UnitAffiliated` | per (p, o) aggregate; eligibility ε_u reads Membership | OrganisationQueryPort | XD-2 [OPEN]; BQ-U1 (non-member affiliation) |
| R-05 | Unit → parent Unit | structure · tree; typed non-structural links separate | bitemporal | Organisation | S | O-1 acyclic, O-2 ≤ 1 parent | Move, Dissolve; `UnitAncestor` | whole-structure aggregate per org | same | Proposed |
| R-06 | Committee → Unit (governs / represents / advises / serves) | M : N, kinds may coexist | bitemporal | Governance | S | kind explicit | Assign; `CommitteeGoverns` | in Committee | GovernanceQueryPort | Proposed |
| R-07 | Person → Committee (Participation) | M : N; ≤ 1 episode per (p, c) at t | bitemporal | Governance | S | INV-P2 | Join, Leave; `Participates` | in Committee | same | RD-1 [OPEN] |
| R-08 | Person → Position (Tenure via Appointment) | ≤ κ holders per position at t | bitemporal | Governance | S → D | INV-P3, INV-P4, INV-P6 | Appoint, Vacate, Annul; `EffectiveTenure` | Committee + PositionHoldings, two-root | same | RD-2, RD-4, BQ-P1, BQ-INC [OPEN] |
| R-09 | Position / organ → Mandate | N : 1 versioned | forward-only | Governance | S (rule) | scope of authority | AmendMandate | immutable versions | same | Proposed |
| R-10 | Committee → GeoUnit (Jurisdiction) | M : N; overlap per BQ-J1 | bitemporal | Governance (assignment); Geography (units) | S | G-J1 valid units; exclusivity? | AssignJurisdiction; `JurisdictionContains` | committee-local, or a JurisdictionRegistry if exclusive | same + GeographyQueryPort | BQ-J1, XD-5 [OPEN] |
| R-11 | Person → Forum (access) | — | evaluated at `t` | Authorization over the Forum module | **E** | A-1, FO-4 | every forum command | PDP + write guard | PDP API | Proposed |
| R-12 | Person → Forum (Subscription / Invitation) | M : N | valid time | Communication | S | SU-1 (no access by itself) | Subscribe, Invite | in Forum | — | BQ-F1 / F2 [OPEN] |
| R-13 | Forum → scope anchor (org / unit / committee / jurisdiction / invitation) | N : 1 | valid time | Communication | S | FO-1 | CreateForum | in Forum | — | Proposed |
| R-14 | Delegation: tenure / organ → person / organ | — | bitemporal | Governance | S | scope ⊆ Mandate at use; **delegate's authority requires the delegator's authority at use** | Delegate, Revoke | footprint includes the **whole chain** (§5.3) | GovernanceQueryPort | **BQ-DEL [OPEN]** (re-delegation, depth) |
| R-15 | Tenure → Duty | duty ⊇ held tenure until discharge | bitemporal | Governance | S / D | IP-2, IP-3 | Discharge | in Committee | `OpenDuties` | BQ-P8 [OPEN] |
| R-16 | facts + policy → Decision | function | `t`, `v`, `k` | Authorization | **E** | A-1…A-5 | all protected commands | §4, §5 | PDP API | Proposed |

---

## 3. Context map

```text
                         ┌───────────────────── Identity & Access (Person, Account) ─────────────────────┐
                         │ ACL: PersonRef only                                                            │
   Organisation ◄────────┤                                                                                │
   (structure, Unit-     │        Membership — RATIFIED ──────── OHS / PL: MembershipQueryPort ──────────►│
    Affiliation, platform│        (p,o) aggregates                                                       │
    roles)               │                                                                               │
        ▲  OHS/PL        │   Governance (Committee, PositionHoldings, Jurisdiction, Mandate, Delegation) │
        │                │        │ conformist to Geography reference data ◄── Geography (GeoUnit)       │
        │                ▼        ▼                                                                      │
        └────── PIPs ──► Authorization (PolicySemantics, PDP, DecisionRecord) ◄── PolicyModules owned by │
                         │        resource contexts (Membership, Organisation, Governance, Forum)        │
                         ▼                                                                               │
                Communication / Forum (Forum, Topic, Post, Moderation, Mute)                              │
   All contexts: ScopeTokenPort (version rows only; guard + bump) · integration events = triggers only · Audit consumes
```

| Relationship | Pattern | Status |
|---|---|---|
| Identity → all | Anti-Corruption Layer (PersonRef) | Proposed |
| Membership → Governance, Organisation, Authorization, Forum | Open Host Service + Published Language (query port) | port design Proposed; Membership model Approved |
| Geography → Governance, Authorization | upstream reference data; consumers conform | Proposed |
| Resource contexts → Authorization | PolicyModule (published, versioned); Authorization owns semantics | Proposed (XD-4) |
| Every context ↔ every writer | **ScopeTokenPort**: the single deliberate transactional coupling, version integers only | Proposed (XD-10, XD-12) |

**Unresolved boundaries:**
- **B-1 Committee ownership:** Governance (target) vs Membership (ADR-0001 Accepted). → DR-07.
- **B-2 Where PositionHoldings lives:** Governance [REC], because it protects Governance invariants.
- **B-3 Where scope tokens live:** a per-context table [REC] vs one shared table. → XD-12.
- **B-4 Tenancy:** one shared database today [RPT]; database-per-tenant is research only, with no ADR [RPT]. Every guarantee in §5 holds **only inside one database**. → XD-10, coupled to any future tenancy ADR.

---

## 4. Authorization semantics (complete)

### 4.1 Inputs
- `PolicySemantics@vS`;
- the required modules `Platform@vP`, `Org(org)@vO` and `Resource(type)@vR`;
- facts as `FactRead = Known(T | F, tokens) | Unknown(reason, tokens?)`.

**Unknown reasons:** `OPEN_WORLD_ABSENT`, `SOURCE_UNAVAILABLE`, `PROJECTION_STALE`, `INCOMPLETE_QUERY`, `OUT_OF_FOOTPRINT`.

### 4.2 Truth table (per rule kind; guard value under strong Kleene)

| Rule kind | Source module | guard = T | guard = F | guard = U | combination |
|---|---|---|---|---|---|
| Grant (alternative grants allowed) | Resource only | candidate permit | no effect | **no effect** (cannot create a grant) | ∃ grant with T, at the highest priority |
| Ordinary deny | Resource only | deny | no effect | **deny** (possible) | deny wins at equal priority |
| Mandatory prohibition | any | deny | no effect | **deny** (possible) | disjunction across modules |
| Mandatory condition | any | satisfied | **deny** | **deny** (not established) | conjunction across modules |

**Module and configuration states:**

| State | Outcome |
|---|---|
| any required module missing | `DEPENDENCY_FAILURE` (never a decision computed from the modules that did load) |
| mandatory conflict detected at runtime | `CONFIGURATION_ERROR` (lint should make this unreachable) |
| blocking U whose reason is `OPEN_WORLD_ABSENT` only | `INDETERMINATE` → review queue of the resource-owning context |
| blocking U whose reason is unavailable / stale / incomplete | `DEPENDENCY_FAILURE` → retry, then alert |
| blocking rule with T or F (business) | `DENY` |
| else, with a grant T | `PERMIT` (still subject to the write guard, §5) |

### 4.3 Soundness: general argument + bounded check

- **Claim:** for any propositional guards, a `PERMIT` under §4.2 implies that every completion of the unknown facts also permits under two-valued evaluation.
- **Argument [INF, standard result]:** strong Kleene logic is *regular*, i.e. monotone in the information order U ≤ T and U ≤ F. If a formula evaluates to T (or F) under a partial valuation, it evaluates to T (or F) under every completion. So:
  - a grant evaluated T holds in every completion;
  - a condition must be T, so it holds in every completion;
  - a prohibition or deny that does not bind evaluated to F, so it fails in every completion;
  - therefore PERMIT holds in every completion.
- **Bounded check:** `g8` P3 (207,000 cases: three modules, alternative / AND / OR grants, missing modules) found **0 unsound permits** and **0 permits with a missing module**.
- **Cost (completeness gap):** **481** cases deny a request that every completion would permit. Of these, 173 involve a tautology grant; the rest are alternative grants that jointly cover every case (e.g. grant `a` and grant `¬a` with `a = U`). These surface as `INDETERMINATE`, not as errors.
- **Option XD-7b:** an exact completion check (enumerate 2^|U| completions) when |U| ≤ 3, else Kleene. It is sound and complete for small U, at exponential cost. **Recommendation:** Kleene by default; the exact check only for the INDETERMINATE review path.

### 4.4 Policy activation
- Each module version is immutable.
- **Module lint** (blocking) and **cross-module lint** (warning) run before activation.
- Activation **bumps the module's scope token** `policy:(moduleId)`, so concurrent activation is covered by the write guard (§5) like any other fact.
- The DecisionRecord pins the module versions.

---

## 5. Temporal correctness, write boundary and scope tokens

### 5.1 Clocks and operations (v1.1 §4, retained)

`decideNow` and `reconstructDecision` support three modes:
- `AS_DECIDED`: must reproduce the original result exactly;
- `IN_HINDSIGHT`: re-evaluates with later knowledge;
- `AT(k')`: an explicit knowledge cutoff.

### 5.2 What must be retained for an exact replay
- the read set as **scope-token versions** (covers absence; g10 Q2: 0 vs 23 mismatches for row-only read sets);
- the module and semantics versions;
- the PDP algorithm version;
- the closed / open-world declaration version per predicate;
- `t` and `v`;
- the witness path;
- the outcome classification.

**If any footprint aggregate cannot be reconstructed:** the result is `RECONSTRUCTION_INCOMPLETE`, never a guessed decision.

### 5.3 Footprints (admissibility rule)

A predicate is usable in a guard only if its answer is determined by a **bounded set of scope tokens**:

| Predicate | Tokens |
|---|---|
| Membership predicates | `membership:(p,o)` |
| UnitAffiliated | `unitaff:(p,o)` |
| UnitAncestor | `orgstruct:o` |
| EffectiveTenure | `committee:c` + `membership:(p,o)` |
| incompatibility | `holdings:(p,o)` |
| JurisdictionContains | `jurisdiction:c` + geo tokens (XD-5) |
| forum facts | `forum:f`, `topic:t` |
| policy | `policy:(moduleId)` × 3 |

**Delegation** adds the delegator chain's tokens. **An unbounded re-delegation depth makes the footprint unbounded, so it is inadmissible** unless a maximum depth is decided (BQ-DEL). This is a requirement derived from the security architecture.

**Geography evidence [SRC]:** `GeoAdministrativeUnit` carries a per-unit optimistic `version` (GEO-INV-06). A nullable `valid_from` column exists only in a migration under `Migrations/Tenant` (live status unverified). So for XD-5:
- **option A:** use the per-unit versions along the ancestor path as tokens (no new data, but long footprints);
- **option B:** a country-level snapshot id (one token, coarse invalidation);
- **option C:** fully bitemporal GeoUnits.

### 5.4 Write-boundary protocol (WB-1…WB-9 of v1.4, corrected)

| # | Rule (changes **bold**) | Evidence |
|---|---|---|
| WB-1 | every mutation bumps every affected scope token in the same transaction, including the **insertion of a previously absent fact** | g11 E2 |
| **WB-1a** | **tokens may not exist yet:** creators use `INSERT … ON CONFLICT (scope) DO UPDATE SET version = version + 1`; guards use `INSERT … ON CONFLICT DO NOTHING`, then lock. Alternatively, eager creation at subject creation where a parent always exists. The upsert is required where the subject may not exist (non-member, never-appointed person) | **g11 E4: lazy 16 / 35 vs upsert 0 / 35** |
| WB-2 | protected writes carry the read set | v1.3 |
| WB-3 | guard: lock the footprint tokens and compare | g11 E1 |
| WB-4 | forbidden as guards: plain reads (any isolation level), and locks on matched fact rows only | g11 E1 / E2 / SERIALIZABLE |
| **WB-5** | **one acquisition phase before any mutation, in canonical order:** (1) context rank `Identity < Organisation < Membership < Governance < Geography < Authorization < Communication`; (2) within a context, `ORDER BY scope COLLATE "C"`. This is the order for **all** tokens, guard and write alike | g11 E3 (36 → 0); [INF] for multi-table |
| **WB-5a** | **a token the transaction will write is locked `FOR UPDATE` in the acquisition phase**, never `FOR SHARE` then UPDATE | **g11 E5: 36 / 70 → 0 / 70** |
| WB-6 | on mismatch: roll back, re-decide, bounded retry | — |
| WB-7 | on 40P01 / 40001 / lock timeout: bounded retry, then `DEPENDENCY_FAILURE`, never PERMIT | — |
| WB-8 | READ COMMITTED + WB-3; SERIALIZABLE does not replace the guard | g11 E1 |
| WB-9 | single database only; across databases, XD-10 fixes Δ and the action classes allowed to use it | — |
| **WB-10** (simplification) | for MODERATE / ADMINISTER / GOVERN: **decide inside the write transaction after the acquisition phase**. The read set is then current by construction and no mismatch retry is needed; caching is used only for READ / CONTRIBUTE | [INF] |

### 5.5 Real-time revocation: proof sketch (scope and assumptions explicit)

**Requirement (domain):** an action authorised by a fact must not commit after a committed revocation of that fact.

**Assumptions:**
- (A1) WB-1 / WB-1a hold for every revoking command, i.e. it updates the token under a row lock in its own transaction;
- (A2) the protected transaction locks the token `FOR SHARE` (or `FOR UPDATE`) before writing (WB-3 / WB-5a) and compares it with the decision's version;
- (A3) PostgreSQL READ COMMITTED row locks: a locking read that waits for a concurrent updater returns the updated row version after that updater commits;
- (A4) one database.

**Argument.** Let R be a revocation and P the protected write.
1. **P locks the token before R updates it.** R's UPDATE waits for P's lock, so R commits after P. The requirement is satisfied.
2. **R updates the token before P locks it.** P's lock waits for R; after R commits, P reads the new version (A3) ≠ the decision's version, so P aborts or re-decides. The requirement is satisfied.
3. **R committed before P read anything.** P's lock sees the new version and aborts or re-decides. The requirement is satisfied.

Cases 1–3 are exhaustive for one token. For several tokens, apply the argument per token: P commits only if no token in its footprint changed. ∎ (sketch)

**What the sketch does not cover:**
- facts outside the footprint, which is why the admissibility rule (§5.3) matters;
- cross-database facts (WB-9);
- replicas.

**Evidence:** g11 E1 / E2 found 0 / 35 under the protocol. This does not replace testing on the real schema (WBT-1…WBT-7).

---

## 6. Decision register

**Owner:** PO / ARB unless stated otherwise. **Status for every row:** Proposal = Proposed; Approval = None; Implementation = None.

| ID | Question | Options | Recommendation | Evidence | Consequence | Depends on | Risk rank |
|---|---|---|---|---|---|---|---|
| **DR-07** (new) | supersede ADR-0001 (committee write model in Membership)? | (a) supersede: Governance owns Committee; (b) keep | **(a)**, via a status act, with ratified act N-6 as basis | [SRC] ADR-0001, act N-6 | prerequisite of every Governance slice | — | **1** |
| XD-1 | one temporal pattern for lifecycle facts | ratified algebra (scoped) / per context | ratified, scoped (v1.1 §6.1) | g9 | one meaning of correction | — | 1 |
| XD-4 | Authorization ownership | capability context + resource-owned modules / central PolicySet | capability + modules | v1.1 §2 | no rule duplication | — | 1 |
| XD-7 | unknown-fact semantics | absence = false / K1 / **K2** (+ optional exact check XD-7b) | K2; XD-7b only for review | g8 P1, P3; §4.3 | sound; incompleteness surfaced as INDETERMINATE | — | 1 |
| XD-11 | outcome taxonomy + INDETERMINATE route | adopt §4.2 | adopt; review queue owned by the resource context | — | operational visibility | XD-7 | 2 |
| XD-12 | footprint / scope-token rule incl. WB-1a, per-context tables | adopt §5.3–§5.4 / alternatives | adopt; per-context token tables | g10, g11 E2 / E4 | every aggregate exposes a token | XD-4 | 1 |
| XD-10 | write-boundary mechanism + cross-database Δ | WB protocol / none | WB-1…WB-10; Δ only for READ unless the ARB extends it | g11 E1–E5; §5.5 | lock coupling between contexts, made explicit | XD-12, tenancy ADR | 1 |
| RD-1 | participation subject | Person / Member | Person | research 2026-10-09 | non-members representable | DR-07 | 2 |
| RD-2 | role representation | M1 / M2 / M3 | M2 | research | lossless history | RD-1 | 2 |
| RD-3 | membership dependency | derived / cascade / none | derived | 140 / 476; g9 | no cascade writes | XD-1 | 2 |
| RD-4 | Governance aggregates | G-A / G-A + PositionHoldings / per-org | G-A + PositionHoldings | g7 Q1 / Q3; g11 E3 / E5 | documented two-root transaction | RD-1, BQ-INC | 2 |
| RD-5 | legacy committee models | migrate / re-model in Governance | re-model + parity migration | inventory | — | DR-07 | 3 |
| XD-2 | UnitAffiliation naming / ownership | adopt | adopt (Organisation) | EM-VOC-002 | no collision | — | 3 |
| XD-3 | identity scope | global + consent-gated / org-scoped | global + consent | — | multi-organisation persons | — | 3 |
| XD-5 | geography versioning | A per-unit tokens / B snapshot id / C bitemporal | **B** for replay + guard; C only if boundary disputes are a requirement | [SRC] GEO-INV-06 | jurisdiction replay | BQ-J1 | 3 |
| XD-6 | READ / CONTRIBUTE staleness limits | ops values | ops owner | — | — | XD-9 | 4 |
| XD-8 | post attribution / retention after membership | privacy policy | privacy owner | — | GDPR | BQ-P9 | 4 |
| XD-9 | cache strategy | C-AUTHV / C-WATER / none | C-AUTHV; CONTRIBUTE only with guard | g8 P2; g10 Q1 | — | XD-10 | 4 |

---

## 7. Business-question register (authorised human decisions; no default is assumed)

| ID | Question | Why it matters architecturally | Owner |
|---|---|---|---|
| BQ-P1 | several positions per person at once? limit? | PositionHoldings invariants | PO / statute |
| BQ-P2 | which position kinds require membership (minimum years)? | ε per position | PO / statute |
| BQ-P3 | on membership end: tenure lapses, or is suspended until a vacancy decision? | EffectiveTenure; observable only with re-entry (110 / 540) [RPT] | PO |
| **BQ-INC** | scope of incompatibility: global, per org, per committee kind? | the key of PositionHoldings | PO |
| BQ-P8 | when does a duty end (discharge act, rule)? | Duty lifecycle | PO / statute |
| **BQ-DEL** (new) | may authority be re-delegated? maximum depth? | bounded footprint (§5.3); without a limit, delegation cannot be guarded | PO |
| BQ-J1 | may two committees of the same kind cover one GeoUnit? | JurisdictionRegistry aggregate if "no" | PO |
| BQ-U1 (new) | may non-members hold a UnitAffiliation? | ε_u | PO |
| BQ-P9 | may former members / office holders read historical content? | forum module templates | PO + privacy |
| BQ-F1 | are moderators office holders only, or forum-appointed too? | moderator source | PO |
| BQ-F2 | public (non-member) forums? | anonymous actor in Identity | PO |
| BQ-F3 (new) | does a membership suspension block forum posting everywhere in the organisation? | an Org-module prohibition | PO |

---

## 8. Formal-verification backlog (only items that settle an open uncertainty)

| ID | Question it settles | Model / test | Blocked by |
|---|---|---|---|
| FV-1 | does WB-5 / WB-5a hold across **per-context token tables** and 3 sessions? | g11 E6: three sessions, two token tables, canonical order | — |
| FV-2 | annulment racing moderation (WBT-3 analogue) | g11 E7 | — |
| FV-3 | Vacate interleaved with Appoint; capacity > 1 | g7 Q4 | BQ-P1 |
| FV-4 | tenure Correct / Void; delegation chains (duty retention, chain revocation) | g9 extension | BQ-DEL, BQ-P8 |
| FV-5 | jurisdiction exclusivity | new model | BQ-J1 |
| FV-6 | XD-7b exact-completion check bound (|U| ≤ k) | g8 P4 | XD-7 |
| FV-7 | re-run the access model `g3` on kernel-admissible histories (G-3 UNCHK → checked) | g3 + kernel filter | C-1 acceptance (Membership) |
| WBT-1…7 | the real schema and command handlers on the isolated database | application acceptance tests (v1.4 §1.3) | implementation + `PBDIGIT-266` DB |

**Retired from the backlog** (resolved here):
- multi-module soundness: general argument + g8 P3;
- missing-token safety: g11 E4;
- the upgrade deadlock rule: g11 E5.

---

## 9. Implementation dependency sequence (plan only; each slice needs its own EP-01 approval)

| # | Slice | Entry criteria | Acceptance criteria |
|---|---|---|---|
| S-0 | Security: `PBDIGIT-266` | isolated test DB provisioned (infrastructure owner) | security plan executed; affected routes protected; evidence reviewed |
| S-1 | Scope-token infrastructure (per-context table + ScopeTokenPort) | XD-10, XD-12 approved | WBT-1, WBT-2, WBT-4 pass on the isolated DB; g11 re-run identical |
| S-2 | Governance Committee + PositionHoldings | DR-07, XD-1, RD-1…RD-4, BQ-P1, BQ-INC approved; contract groups C-A…C-D approved | g7 / g9 properties as unit tests; two-root protocol WB-5 / WB-5a; parity with the oracles |
| S-3 | Minimal PDP + DecisionRecord + write guard on the first protected write | XD-4, XD-7, XD-11 approved; S-1 | A-1…A-5 and §4.3 properties; WBT-1 on a real command; replay R-1 |
| S-4 | Legacy committee consolidation | RD-5, S-2 | parity tests over the four legacy models; no behaviour change unless approved |
| S-5 | OrganisationStructure + UnitAffiliation | XD-2, BQ-U1 | O-1 / O-2; UA-2 derived |
| S-6 | JurisdictionAssignment + geography tokens | XD-5, BQ-J1, S-2 | jurisdiction-scoped grant tests; replay pins geography |
| S-7 | Forum MVP (CONTRIBUTE via PDP + guard; READ cache) | S-3, BQ-F1, BQ-F2, BQ-F3, XD-9 | FO-1…FO-4; revocation-race test |
| S-8 | Moderation, jurisdiction forums, mutes, subscriptions, invitations | S-6, S-7, BQ-P9, XD-8 | moderation audit; WBT-3 |
| S-9 | Audit consolidation + replay | S-3 | AS_DECIDED reproduces 100 % of recorded decisions in the test corpus |
| S-10 | End-to-end acceptance | S-2…S-9 | the scenarios in the acceptance TODO list below |

---

## 10. Traceability

| Claim | Kind | Source |
|---|---|---|
| ADR-0001 Accepted; committee write model in Membership; directory exists | [SRC] | `docs/adr/ADR-0001-committee-read-write-separation.md`; `app/Contexts/Membership/Domain/Committee/` |
| N-6: committee code is a legacy boundary defect, to go to Governance | [SRC] | `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratification-act.md:19` |
| EM-VOC-001 / 002 ADOPTED | [SRC] | `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md:218-219` |
| `AuthorityCheckPort::check(TenantId, string actor, MembershipAct)`, no subject / as-of | [SRC] | `app/Contexts/Membership/Application/Organisational/Ports/AuthorityCheckPort.php:17` |
| no Forum code; no tenancy package; 64 files reference `BelongsToTenant` | [SRC] | repository search 2026-10-11 |
| GeoUnit per-unit optimistic version | [SRC] | `app/Contexts/Geography/Domain/Entities/GeoAdministrativeUnit.php:32,56` |
| g8 P3; g11 E1–E5 | [SRC] run this round | `models/g8_pdp_guards_and_cache.py` (sha256 `1e39d301…`); `models/g11_pg_write_boundary.php` (sha256 `75a59a58…`), private PostgreSQL 18.6 cluster, PHP 8.5.11 |
| g7, g9, g10 results | [RPT] (run in earlier rounds) | models README revisions 6–8 |
| Membership kernel 144 tests | [RPT] (run 2026-10-10) | v1.1 §8 |
| four committee models; duplicate TenantId / events | [RPT] | research 2026-10-09 §1; capability review 2026-08-06 |
| ADR-003 (governance-driven revocation): consequences decided by Governance, nothing automatic | [SRC], election-scoped; used only as consistent evidence for "no cascade" | `docs/adr/ADR-003-governance-driven-revocation.md` |
| §4.3 soundness argument; §5.5 proof sketch | [INF] | this document |

---

## 11. Maturity assessment

| Dimension | Assessment |
|---|---|
| Domain concepts and ownership | **converged:** every material relationship has an owner or a named open question (DR-07, XD-2, BQ-*) |
| Bounded contexts | strong candidate; **one boundary blocked by an accepted ADR** (DR-07); the tenancy coupling is explicit |
| Invariants | every invariant has a population, scope and enforcement point (§2, §5); the delegation footprint needs BQ-DEL |
| Authorization semantics | complete truth table; soundness argued generally and checked within bounds; completeness gap quantified |
| Write-boundary consistency | protocol corrected (WB-1a, WB-5, WB-5a, WB-10); proof sketch with explicit assumptions; probe-verified on PostgreSQL; **not implemented** |
| Decisions | none approved; register prioritised by risk (DR-07, XD-1, XD-4, XD-7, XD-10, XD-12 first) |
| Implementation | Membership kernel and Geography exist; everything else in scope is not built |
| Evidence gates | **7 / 30** (no gate reached its target this round) |
| End to end | not demonstrated |

---

## 12. TODOs

### Security
- Provision the isolated test database; execute `PBDIGIT-266`; add interim route protection (security / infrastructure owner).
- Keep the credential-exposure item (PBDIGIT-270, reported) separate and moving.
- After implementation: run WBT-1…WBT-7 on the isolated database.

### Architecture decisions
- **First:** DR-07 (status act superseding ADR-0001), XD-1, XD-4, XD-7, XD-12, XD-10.
- **Then:** XD-11, RD-1…RD-5.
- **Then:** XD-2, XD-3, XD-5.
- **Then:** XD-9, XD-6, XD-8.
- Coordinate XD-10 with any future tenancy ADR (single-database assumption).

### Business questions
- Answer BQ-P1, BQ-P2, BQ-P3, BQ-INC, BQ-P8, BQ-DEL, BQ-J1, BQ-U1, BQ-P9, BQ-F1, BQ-F2 and BQ-F3.

### Domain contracts
- Approve the Governance contract in clause groups (v1.4 §2) once DR-07 and the round-1 decisions are made.
- Specify the per-context ScopeTokenPort (token naming, upsert rules WB-1a, context rank, collation).
- Specify the MembershipQueryPort / GovernanceQueryPort / OrganisationQueryPort signatures with footprints and closed / open-world declarations.
- Specify the AuthorityCheckPort extension (subject, decision reference, as-of) and the DecisionRecord schema.
- Specify the PolicyModule contract, activation protocol (token bump), module lint and cross-module lint.

### Formal verification
- FV-1 (three sessions, per-context tables), FV-2 (annulment vs moderation).
- FV-3…FV-5 after BQ-P1 / BQ-DEL / BQ-P8 / BQ-J1.
- FV-6 (exact-completion bound), FV-7 (g3 on kernel-admissible histories after C-1 acceptance).
- Rule and evidence matrix: source scope and authority per row; population bounds only with a sampling frame.

### Implementation handover (plan only)
- Slices S-0…S-10 in §9 order, each with its own EP-01 plan and approval.
- Never start S-2 before DR-07, and never start S-7 before S-3.

### Acceptance
- End-to-end scenarios:
  - former chair keeps the general forum, loses the committee forum;
  - district secretary moderates the district forum;
  - suspended in A, active in B;
  - concurrent appointments (incompatibility, capacity);
  - revocation during a post;
  - annulment with replay (AS_DECIDED).
- Database-backed, route-level and integration suites.
- Bounded models and DB-free suites as regression gates.
- Gates scored only by the five status dimensions.
