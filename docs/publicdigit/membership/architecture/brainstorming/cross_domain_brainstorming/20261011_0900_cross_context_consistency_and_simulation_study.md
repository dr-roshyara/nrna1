# Cross-Context Consistency and Simulation Study: CommitteeType · Committee · Geography · Membership · Assignment · Position · Permission

**Date:** 2026-10-11 · **Author:** Claude (architecture research; **no production code, no Membership edits, no database writes**)
**Status:** RESEARCH. Authority: generated. Not ratified. This document decides nothing; every rule below is **provisional** unless it cites an approval.
**Working principle (from the commission):** model first, simulate second, integrate third. **Single write authority; no second Membership implementation; integrate only against contracts agreed with the Membership owner (Strangler Fig).**
**Scope:** Committee / Governance, Geography, Membership (as a contract consumer only), Assignment, Position, Permission. Election is out of scope; election-side tables are listed only where they carry committee, role or permission meaning.

**Evidence:**
- legacy inventory by an independent read-only reviewer (2026-10-11), with four claims re-verified by me (marked ✔);
- the earlier gap reviews (plan `docs/plans/20261011-0103-…` §2);
- model `docs/publicdigit/membership/architecture/models/g14_cross_context_consistency.py` (run 2026-10-11, SHA-256 `fd24831b…`);
- earlier models g7, g9–g13.

**Path abbreviations:**
- **M** = `app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/`;
- **D** = `database/migrations/`;
- **G** = `app/Contexts/Geography/Infrastructure/Database/Migrations/Landlord/`.

---

## 0. Answers to the seven research questions (summary)

| # | Question | Answer (evidence in §1–§5) |
|---|---|---|
| 1 | Distinct representations per concept? | **CommitteeType 9, Committee 5, Geography references 11, Membership 6, Assignment 6, Position / Role 5, Permission 7 mechanisms** (§1) |
| 2 | Authoritative, duplicated, derived or obsolete? | per row in §1. Notably: **three independent assignment stores with no shared key and different meanings of `member_id`**; four `admin_level` numberings; several dead tables |
| 3 | Cardinalities as implemented? | committee → **at most one** geo unit (target needs M:N); person → committee: one active row in B, unlimited in A; **one active holder per role_path** (contradicts role limits) (§2) |
| 4 | Approved, proposed or unknown rules? | almost every cross-context rule is **proposed or unknown**. Ratified: the Membership architecture, EM-VOC-001 / 002, ADR-T11. Accepted but contradicted: ADR-0001 (§3) |
| 5 | Which contradictory states can be represented? | all of C1–C8 plus four newly found (C9–C12); no constraint prevents most of them (§3) |
| 6 | Automatically detectable vs needing a decision? | g14 S3: **18 / 60** type × level × organisation combinations are decidable from data alone (cross-organisation references); **42 / 60 need an approved type ↔ level mapping**. Read-only detector queries in §3 |
| 7 | Minimum contracts to integrate without coupling? | **four read contracts + one guard contract** (§6); none requires editing Membership internals now |

**"Can Geography represent all committees?" No, and it must not try.** The code already contains committees that are deliberately non-geographic:
- `central` is *forbidden* from having a geography reference (M `…000002:109-116`; `CommitteeType.php:150-158`);
- wings have optional geography;
- structure levels allow `geo_policy = none`;
- every geography column on `committees` is nullable.

Geography should answer *where a place is and how it relates to other places*. *Which committee answers for which places* is a separate relationship, owned by Governance (once DR-07 is decided) and configured through an **approved mapping**.

**An existing asset fits this:** `committee_structures` / `committee_structure_levels` with `geo_policy` none / required / optional (M `2026_05_06_000001:15-56`). It is an **organisation-configurable level model that already exists**. It is the natural home for the type ↔ level mapping that C1 needs, rather than inventing a new one. [recommendation]

---

## 1. Legacy representation inventory (condensed)

**Classification legend:** **AUTH?** authoritative candidate · **DUP** duplicate · **DER** derived / projection · **DEAD** obsolete · **?** unknown.

### 1.1 CommitteeType / level

| Representation | Where | Values | Class |
|---|---|---|---|
| `committees.type` varchar(30), nullable | M `…000002:48`; D `2026_05_13_143633:13` | free string; CHECKs mention central / ward / district / province only (M :109-127) | AUTH? |
| `CommitteeType` VO | `Domain/ValueObjects/CommitteeType.php:27-47` | central, province, district, ward, youth_wing, women_wing, student_wing, diaspora, geographic | AUTH? (code) |
| `CommitteeCategory` enum | `…/ValueObjects/CommitteeCategory.php:22-28` | different names (youth, women …) | DUP |
| `committee_structures` / `_levels` (`geo_policy`, `role_limits`) | M `2026_05_06_000001:15-56` | draft / active / deprecated; none / required / optional | **AUTH? (configurable level model)** |
| `committees.snapshot_*`, `created_from_structure_id` | M `2026_05_07_000002:37-88` | documented "immutable", but **rewritten on every persist** (`EloquentCommitteeAggregateRepository.php:36-56`) | DER |
| `governance_level_definitions` | D `2026_05_10_000010:13-30` | committee level **and** geo level in one row | ? |
| `committee_role_mappings`, `CommitteeType` controller | M `…000006`; `app/Http/Controllers/CommitteeType.php` | seeded with `example-tenant`; empty controller | DEAD |

### 1.2 Committee

| Representation | Where | Notes | Class |
|---|---|---|---|
| `committees` | M `…000002:36-97` + later columns | soft deletes; three different status value sets (migration comment, `CommitteeStatus.php:20-23`, update request) | AUTH? |
| `constitutional_committees` | M `2026_05_12_000001` | **no `organisation_id`**; its repository `save()` is a TODO no-op | DEAD |
| Governance `Committee` aggregate | `Governance/…/EloquentCommitteeRepository.php:31-48` | loaded blank (existence check only) | DER |
| `committee_governance_projections` | D `2026_05_10_000001` | **no tenant column** | DER |

### 1.3 Geography references (eleven places)

- **`geo_administrative_units`** (AUTH?): global or organisation rows; `valid_from` / `valid_to` live, unused; no tenant scope.
- **On `committees`:**
  - `geo_unit_id` (AUTH?);
  - `operational_geo` (DUP; written **instead of** `geo_unit_id` by one writer, `CommitteeManagementController.php:111`);
  - `operational_geo_reference` / `_cache` (DUP);
  - `region_code` / `country_code` / `canonical_geo_id` / `snapshot_geo_scope` (DER / DUP).
- **Member side:** `members.residence_geo_unit_id` (AUTH? member side).
- **On `users`:**
  - `residence_geo_unit_id` (DUP);
  - `geo_unit_id` / `geopath` / `geopath_segments`: **read in code but no migration creates them** (DEAD, phantom; read with a `?? 1` fallback in the committee application controller);
  - `region` (election-side).
- **On `organisations`:** `base_country_code` / `base_region_id` (**no FK**).
- **Client-supplied:** application geography comes from the request (`ApplyForCommitteeMembershipRequest.php:22-24`).

### 1.4 Membership

| Representation | Where | Values | Class |
|---|---|---|---|
| `members` (legacy, live) | D `2026_03_07_145529`; CHECK D `2026_04_03_155711:10` | DB: active / expired / suspended / ended. **Two code value objects with other value sets** | AUTH? (legacy) |
| `organisation_users` | D `2026_03_07_145528` | owner / admin / staff / member; active / inactive / suspended | DUP |
| `user_organisation_roles` | D `2026_03_05_000003` | free role string; `permissions` json; no temporal columns | AUTH? (for authorisation) |
| Approval flow | `MembershipApplicationController.php:253-282` | writes all three tables in one action | 3-way DUP |
| `org_memberships` kernel | D `2026_10_09_100000` | append-only Admit / End facts; **no live writer** | AUTH? (ratified model; not live) |

### 1.5 Assignment

| Store | Key semantics | Writer | Class |
|---|---|---|---|
| `committee_assignments` (B) | `member_id` **varchar(26)** ✔; one active row per (committee, member) **and per (committee, role_path)** ✔ (pgsql only) | `Committee/CommitteeMemberController.php:28-51` validates `member_id` as **uuid + exists:users** ✔, i.e. a 36-character user id into a 26-character column | AUTH? |
| `committee_associations` (A) | `member_id` = **user id**; no unique; no end column | review handler | DUP |
| `committee_member_projection` (C) | `member_id` = **members.id**; hard delete | Governance API | DUP (labelled a projection, written independently) |
| `users.is_committee_member` | — | **no writer in `app/`** | DEAD / ? |
| `organisation_participants`, `election_officers` | election-side | — | out of scope |

### 1.6 Position / Role and Permission

- **Positions:**
  - `role_path` (`1.1.1`);
  - `CommitteeRole` enum (chair / deputy / member / observer);
  - `GovernanceRole` enum (NRNA-specific, with `level()`);
  - central committee role limits keyed by **names** while lookups use **paths**, so the limits never match;
  - `role_hierarchy` table (DEAD).
- **Permissions:**
  - policies decide on **role names** in `user_organisation_roles`;
  - `manageMembers` and `can:admin` are **referenced but undefined**;
  - Spatie roles are **not tenant-scoped**;
  - `committee.member` middleware = the boolean flag;
  - **`is_committee_member` → `commission` role** (`User.php:1098-1101` ✔), i.e. committee membership becomes election-commission access;
  - `AuthorityCheckPort` → `InterimRoleAuthority` (owner / admin may admit; its role reader has **no implementation found**);
  - three free-form `permissions` json blobs that no policy reads.

---

## 2. Cardinalities as implemented vs candidate target

| Relationship | Implemented | Candidate target (PROPOSED) | Gap |
|---|---|---|---|
| Committee → GeoUnit | 0..1 (`geo_unit_id`); up to six parallel columns | 0..N **JurisdictionAssignment** (bitemporal), governed by the structure level's `geo_policy` | M:N + history missing |
| Committee → Type / level | 0..1 free string + a separate structure link | exactly one structure level (organisation-configured) | two classifications |
| Person → Committee (active) | B: 1 per committee; A: unlimited; C: 1 forever | Participation ≤ 1 per (person, committee) at t (INV-P2) | three stores disagree |
| Position holders | **1 active per role_path** (index) | κ(position) from the structure's `role_limits` (INV-P3) | **the index contradicts the limits** (e.g. a limit of 2) |
| Assignment → Membership | none | EffectiveTenure = Tenure ∧ ε(Membership) (derived; **rule OPEN**, BQ-P2 / P3) | — |
| Identity of "member" in assignments | user id (A, B) vs `members.id` (C) | one PersonRef published by Identity / Membership (**XD-3 OPEN**) | **C9** |

---

## 3. Contradiction catalogue

| ID | Contradiction | Representable today? | Example / evidence | Automatically detectable? (read-only) | Decision needed | Invariant (status) |
|---|---|---|---|---|---|---|
| **C1** | type vs geography level | yes: nothing links `type` / `level` to `admin_level` | "district" committee → province unit; "central" with a unit; "geographic" type escapes the CHECKs | **only with an approved mapping** (g14 S3: 42 / 60 combinations) | **type ↔ level mapping per organisation** (use `committee_structure_levels.geo_policy`) + the canonical `admin_level` scale (C10) | `geoLevel(unit) ∈ allowedLevels(structureLevel(c))` (PROPOSED) |
| **C2** | committee → another organisation's unit | yes (FK is id-only; `StoreCommitteeRequest:37` unscoped) | — | **yes:** `c.geo_unit_id = g.id AND g.organisation_id IS NOT NULL AND g.organisation_id <> c.organisation_id` (g14 S3: 18 / 60) | none to *detect*; a cross-organisation exception would need approval | `org(unit) ∈ {NULL (official), org(c)}` (PROPOSED; consistent with tenant isolation) |
| **C3** | active assignment, ended membership | yes (`Member::endMembership` touches nothing; no FK) | — | yes (needs the person join chain, see C9) | **BQ-P3 / BQ-P2**: does ending membership end the tenure, suspend its effect, or neither, per position kind? | `A_eff(p,c,t) ⇒ M(p,O(c),t)` (**OPEN**, see g14 S1) |
| **C4** | association active in A, assignment removed in B (or C) | yes: three independent stores | — | yes: FULL OUTER JOIN on (committee, person) | **authoritative writer** (DR-07) + reconciliation policy | one writer per fact (architecture rule, PROPOSED) |
| **C5** | position without permission / permission without position | yes: roles are strings; `manageMembers`, `can:admin` undefined | a chair cannot act; an undefined ability always denies | partly (static scan of abilities vs policy methods; Spatie roles with no permissions) | XD-4 (Authorization ownership) | position ≠ permission; permission derived from tenure + mandate (PROPOSED) |
| **C6** | referenced unit moved or retired | yes: in-place updates; cascade delete; no revision | — | no (no history to compare) | **XD-5** + jurisdiction semantics (see g14 S4) | historical decisions pinned to a revision (PROPOSED) |
| **C7** | cross-organisation reference in membership or assignment | yes (`members` / `users.residence_geo_unit_id`; Governance API) | — | yes (same join as C2) | none to detect | as C2 (PROPOSED) |
| **C8** | conflicting assignment dates | yes; and **B's save rewrites `left_date` of ended rows** | `Repositories/EloquentCommitteeRepository.php:143-149` | partly (cross-store date comparison; corruption not distinguishable from a real end) | reconciliation policy (g14 S2) | append-only tenure facts (PROPOSED, TR-1) |
| **C9** (new) | "member_id" means a **user id** in A and B but a **members.id** in C; B's column is varchar(26) vs a 36-character UUID ✔ | yes | `CommitteeMemberController.php:30` vs M `…000003:45` | yes (length / format check; existence in users vs members) | **XD-3** person identity / PersonRef | one PersonRef across contexts (PROPOSED) |
| **C10** (new) | **four `admin_level` / level numberings** (geo original 1 = province; geo current 1 = country ✔; organisation `geographic_levels` 1 = province; `committees.level` 1 = central) | yes | G `…000002:28` vs G `2026_01_14_000003:37-40` ✔ | no (meaning, not data) | canonical scale + an explicit mapping, never a shared integer | `level` is never compared across scales (PROPOSED) |
| **C11** (new) | **one active holder per role_path** (index) vs role limits > 1 and unlimited members | yes | M `…000003:111-117` ✔ vs role limits | yes (count active per role_path vs `role_limits`) | **BQ-P1** + κ per position | `|holders(s,t)| ≤ κ(s)` (PROPOSED) |
| **C12** (new) | committee membership flag grants **election-commission** access | yes | `User.php:1098-1101` ✔ | yes (static) | product decision (and a security review); election-adjacent | a role / flag is never an authority (PROPOSED; decision-sheet A-5) |

**Reconciliation rule for all of them [REC]:** never "repair" by picking the most recently updated row. Detect, classify and queue for an authorised decision, keeping every source row as evidence (see g14 S2).

---

## 4. Invariants: status register

| ID | Invariant | Scope | Status |
|---|---|---|---|
| INV-M, INV-EP, INV-9, INV-COV | Membership invariants | (p, o) | **Ratified** (2026-10-08) |
| ADR-T11 | no voter ↔ vote linkage | all | **Ratified** |
| EM-VOC-001 / 002 | Membership = the Member aggregate; a role assignment is not Membership | all | **Adopted** |
| X-TEN | every cross-context reference stays within the organisation (or points to official shared reference data) | all | PROPOSED (follows tenant isolation; no recorded act for geography) |
| X-OWN | one authoritative writer per fact | all | PROPOSED (decision sheet; DR-07 open) |
| INV-P2 / P3 / P6 | participation ≤ 1; holders ≤ κ; incompatibility | committee / person | PROPOSED (BQ-P1, BQ-INC open) |
| INV-C1 | geo level fits the committee's structure level | committee | PROPOSED (needs the mapping) |
| INV-C3 | effective assignment ⇒ current membership | assignment | **OPEN** (business rule; BQ-P2 / P3) |
| INV-HIST | assignment and jurisdiction history append-only | Governance | PROPOSED (TR-1) |
| INV-JUR | a historical decision's jurisdiction is reconstructable | decisions | PROPOSED (XD-5) |

---

## 5. Simulation results (bounded models; not evidence about the Laravel implementation)

### S1, C3: assignment vs membership (g14; 3,905 enumerated event sequences of length 1–5)

Events: Admit, End, Appoint, Vacate, and an **Annul of the End** (allowed by the ratified algebra).

| Rule | Sequences violating `A ⇒ M` | Tenure wrongly lost after the End is annulled |
|---|---|---|
| R0 no rule | 2,038 | 0 |
| R1 check once at appointment | **161** (the membership ends later) | 0 |
| R2 check + **cascade** (End writes a vacate) | 0 | **19** |
| **R3 derived** (effective = held ∧ member; nothing written) | **0** | **0** |

**Reading:**
- Checking eligibility once is not enough.
- Cascading writes violate the ratified annulment semantics; this independently reproduces the earlier 140 / 476 finding.
- **The derived rule satisfies the candidate invariant without destroying history.**
- **Whether the invariant applies at all** (external advisers, honorary seats) **is still a business decision**. The model only shows *how* to enforce it if adopted.

### S2, C4 / C8: reconciling duplicated assignment stores (g14; 20,000 seeded synthetic pairs per noise level)

**Assumptions** (stated, not measured): each source independently stale, missing, and (B only) date-corrupted at the rates shown.

| Noise (stale / B-date-rewrite / missing) | P1 latest-updated wins: silent errors · evidence discarded | P2 fixed priority B > A > C: silent errors · discarded | **P3 agree-or-queue:** silent errors · queued · discarded |
|---|---|---|---|
| low (5 % / 5 % / 10 %) | 1,191 · 3,873 | 1,410 · 4,031 | **32 · 2,980 · 0** |
| medium (15 % / 20 % / 20 %) | 3,514 · 8,785 | 4,249 · 9,205 | **436 · 6,939 · 0** |
| high (30 % / 40 % / 30 %) | 6,769 · 12,060 | 7,831 · 12,483 | **1,837 · 9,611 · 0** |

**Reading:**
- "Most recent wins" and fixed precedence produce **silent wrong answers and destroy evidence** at every noise level.
- The evidence-preserving policy reduces silent errors by one to two orders of magnitude and discards nothing.
- Its cost is a **human adjudication queue**; its residual errors come from cases where all available sources agree but are all stale, which no automatic policy can detect.
- The queue size is a planning input for the migration, so **real data must be profiled read-only** before choosing.

### S3, C1 / C2 / C7: detectability (g14; 60 combinations of type × unit level × organisation relation)

- **18** are decidable from data alone (cross-organisation references).
- **42** require an approved type ↔ level mapping, including which types are non-geographic.

The code already shows the mapping is organisation-specific (structure levels, `geo_policy`).

### S4, C6: jurisdiction under geography change (g14)

| Change | FOLLOW_ID (live) | SUCCESSOR links | SNAPSHOT (pinned revision) |
|---|---|---|---|
| unit moved: past decisions changed on replay · current coverage | 1 · 1 | 1 · 1 | **0** · 1 |
| unit retired with successor: replay changed · current coverage | 2 · **0** | 0 · 2 | **0** · **0** |

**Reading:** no single semantics gives both stable replay and correct current coverage. **Use SNAPSHOT for historical decisions, plus SUCCESSOR for current governance.** That needs revision tokens (XD-5, modified B) **and** an explicit successor relation on retirement. [REC; decision XD-5]

### Concurrency and tenant isolation (earlier models; summary)

On a private PostgreSQL 18.6 cluster, bounded:
- **g11 E1–E9:** plain reads and row-only locks → unauthorised outcomes (16 / 35; 2,300 / 4,200; 2,520 duplicate memberships); owner scope tokens and a canonical lock order → 0.
- **g7:** cross-committee incompatibility needs a per-person aggregate.

**None of this is evidence about the Laravel implementation.**

---

## 6. Minimum contract set (to agree with the owners; no implementation now)

| Contract | Provider | Consumer(s) | Content (PROPOSED) | Agreement needed from |
|---|---|---|---|---|
| **MembershipQueryPort** | Membership | Governance (eligibility), Authorization, Adjudication | `isMember(org, personRef, asOf, knownAtVersion) → {T|F|U, scope, version}`; durations; closed-world | **Membership owner** (no change to their implementation now; contract text only) |
| **PersonRef** | Identity (with Membership) | all | one identifier for "the person" across A / B / C stores and the kernel (resolves C9) | XD-3 |
| **GeographyQueryPort** | Geography | Governance, Membership, Adjudication | `unit(org, id, revision)`, `contains / ancestors(org, id, revision)`; organisation-scoped; revision tokens | XD-5 |
| **CommitteeStructurePort** | Governance (structure levels) | Governance, Authorization | `levelOf(committee)`, `allowedGeoLevels(level)`, `geoPolicy(level)`, `κ(position)`: the approved mapping that C1 / C11 need, **reusing `committee_structures`** | DR-07 + structure ownership |
| **Write-guard contract** | each owner (ScopeTokenPort) | any protected write | v1.5 / closure TC rules | XD-12 |

---

## 7. How this works alongside the Membership developer

- **Produced here:** contract *text* (§6), contradiction catalogue, invariants and simulations. **Nothing in `app/Contexts/Membership` was edited.** Uncommitted Membership changes by the other session are untouched.
- **Next with the Membership owner:** confirm the semantics of `isMember` (suspension? future-dated?), PersonRef, and the kernel's version or `seq` as the footprint token.
- **No dual writes;** the legacy stores stay in the inventory until an authoritative source and a migration policy are agreed.

---

## 8. Progress and remaining TODOs

- **Done this round:**
  - E9 closed (bounded);
  - legacy inventory;
  - contradiction catalogue C1–C12;
  - invariant register;
  - simulations S1–S4.
- **Architecture decisions:** DR-07 (committee ownership), XD-3 (PersonRef), XD-5 (geography revisions + successor), the canonical level scale (C10), the type ↔ level mapping home (`committee_structures`?), XD-4 (permission ownership).
- **Business decisions:** BQ-P2 / P3 (membership ↔ tenure: does INV-C3 apply, continuously, to which positions?), BQ-P1 (κ vs the role_path unique index, C11), C12 (committee flag → commission access).
- **Research next:**
  - **read-only data profiling** of the live or test database, with authorisation, to count C1–C12 occurrences and size the S2 queue;
  - extend S2 with real distributions;
  - a model of successor chains (multi-step retirements).
- **Contracts:** agree §6 with the Membership owner and the Governance / Geography owners.
- **Security (unchanged):** the isolated test database and `PBDIGIT-266` (self-approval, unguarded Governance API, geography isolation), now also C12.
- **Only then:** controlled integration and migration (Strangler Fig, single writer, shadow + reconciliation).
