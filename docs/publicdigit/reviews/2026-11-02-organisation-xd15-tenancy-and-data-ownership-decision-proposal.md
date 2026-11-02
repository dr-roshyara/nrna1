# Decision D (XD-15): tenancy and data ownership, decision proposal

**Date:** 2026-11-02 · **Author:** Claude (DDD architect / requirements engineer; authority: generated)
**Status:** **PROPOSAL for the Principal (PO) and the ARB.** It decides nothing.
- XD-15 remains **OPEN** in `docs/publicdigit/reviews/2026-10-11-cross-domain-decision-round-1-sheet.md:63-73`.
- No product code, schema, configuration or data was touched.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0).
**Baseline:** branch `election-flow-updated`, HEAD `d812c937d`.

**Labels:**
- **[F]** fact from current source;
- **[R-D]** ratified or approved decision;
- **[P]** proposed decision (not approved);
- **[M]** model output under stated assumptions;
- **[I]** inference;
- **[R]** recommendation;
- **[O]** open;
- **[U]** unknown.

---

## 1. The business problem

PublicDigit serves organisations and political parties. Four business needs pull on the tenancy decision:

1. **Isolation and confidentiality.** Membership, governance and affiliation records of a political party are sensitive; they reveal political affiliation. One organisation's data must not reach another, and should be kept from the platform beyond what is necessary.
   - Evidence: XD-3a conditions C-1…C-4 [P]; INV-AFF-1 [P]; INV-ORG-SCOPE-1 [R-D principle].
2. **Organisational autonomy.** Each organisation's data lifecycle (backup, restore, export, retention) should be manageable on its own. [I from the multi-database investigations; no ratified requirement found]
3. **One person, several organisations, one login.**
   - BR-4: one person can be associated with several organisations (confirmed by the PO);
   - XD-3a: one platform Person [P];
   - approved PBDIGIT-30 B1.3 / B1.4: sign-in routes by the person's real organisations.
4. **One application operates all organisations.** Multi-database investigation #2 [research]; the Principal's stated direction, 2026-11-01 (conversation).

## 2. Requirements and invariants in force

| Item | Content | Status | Source |
|---|---|---|---|
| Ratified Membership | one Membership per (Person, Organisation); append-only facts; kernel keyed by `(organisation_id, …)` | **[R-D] ratified 2026-10-08** | `2026-10-08-membership-architecture-ratified.md:43,54,124`; `database/migrations/2026_10_09_100000_create_org_membership_tables.php:26-33` |
| PBDIGIT-30 B1 invariant | every authenticated request runs inside exactly one Working Organisation | **[R-D] approved by the PO 2026-08-06** | `docs/publicdigit/backlog/PBDIGIT-30-…md` (B1) |
| INV-ORG-SCOPE-1 | Owning Organisation = Working Organisation; fail closed; platform-level clause | **principle approved by the PO 2026-11-01; ARB pending** | `2026-11-01-organisation-inv-org-scope-1-…-decision-record.md` |
| INV-AFF-1 | each organisation stores its affiliation records in its own database; no persisted cross-organisation register | [P] | `2026-11-01-organisation-inv-aff-1-…-decision-record.md` |
| XD-3a / XD-3b | a platform-wide Person (bare identifier); personal data organisation-local | [P] | the XD-3a / XD-3b records |
| XD-PROV | provenance organisation-local | [P] | the XD-PROV record |
| XD-10 / XD-12 | write-boundary guarantees through scope tokens and locks; a cross-database delay "only for READ unless the ARB extends it" | [P, v1.5 candidate]; XD-12 **open** in the decision sheet ("☐ adopt", `:111-117`); the sheet ties the XD-12 scope to XD-15 (`:72-73`) | `…/20261011_0008_cross_domain_discovery_package_v1.5.md:324-325` |
| INV-DB-1…3 | (1) organisation facts only in that organisation's database; (2) a separate platform store; (3) rows carry `organisation_id`, mismatch → stop | **proposed in conversation 2026-11-01, never recorded.** INV-DB-3 is subsumed by INV-ORG-SCOPE-1's enforcement expectation. | XD-3a record §6 lists them as open |

**Current implementation [F]:**
- **One shared PostgreSQL database** with `organisation_id` scoping. No `central` or `tenant` connection (`config/database.php`). No tenancy package.
- **Missing context silently falls back** to a default organisation (Stage 1, item 3).
- **The earlier XD-15 recommendation was option A (shared database)**, on the ground that the TC-12 write guarantees assume one database (decision sheet `:63-73`). The cross-domain plan marks "tenancy redesign" out of its scope (`docs/plans/20261011-0103-cross-domain-architecture-implementation-plan.md:75`).

## 3. Alternatives

| Option | Description |
|---|---|
| **A: shared database** | all organisations in one database, isolated by `organisation_id` and enforcement (today's model, hardened) |
| **B: separate database per organisation, nothing shared** | each organisation's database holds everything, including accounts; no platform store |
| **C: hybrid** | a **minimal platform store** (Identity: bare Persons, accounts, the platform service relationship; the organisation registry and routing; official reference data) **plus one database per organisation** holding **all organisation-owned facts of every context**: affiliation, Membership, governance, communication, provenance, organisation-local personal data, organisation-custom geography and jurisdiction |
| C-sub (topology) | C on **one database cluster** (many databases, shared failure domain) vs **separate servers** (independent failure domains). A separate infrastructure decision. |

## 4. Evaluation

Ratings: ✓ supports · ~ partly / with conditions · ✗ conflicts. Each rating names its evidence.

| Criterion | A shared | B per organisation, no platform | C hybrid |
|---|---|---|---|
| Business ownership and autonomy | ~ logical only; one backup holds all organisations | ✓ full | ✓ per-organisation database; platform holds no organisation facts |
| Preventing cross-organisation leakage | ~ depends entirely on query discipline. Stage 1 found 359 bypass calls, 18 potential defects, and a non-fail-closed fallback [F] | ✓ physical | ✓ physical for organisation facts; platform store minimal (C-2) |
| Correct organisation-scoped queries and commands | ~ every query must carry the predicate | ✓ the connection is the scope | ✓ the connection is the scope; the row check (INV-ORG-SCOPE-1 enforcement) adds defence |
| Cross-context references | ✓ joins possible (and misusable) | ~ only within one organisation | ~ by reference only: organisation ↔ platform via `PersonRef`; no cross-database foreign keys |
| Identity resolution without disclosure | ✗ all organisations' affiliations sit in one database, which in substance is a central register. **Conflicts with INV-AFF-1 [P].** | ~ no central identity, so no single login (contradicts BR-4 / XD-3a) | ✓ XD-3a / INV-AFF-1 design (discovery at sign-in; feasibility open, below) |
| Membership and committee lifecycle integrity | ✓ one database | ✓ within the organisation | ✓ **Membership and Governance co-located in the organisation's database**, so the intra-organisation invariants (EffectiveTenure, PositionHoldings per (p, o), κ) stay single-transaction [I from v1.5 / g9, g7, g11] |
| Authorisation and jurisdiction | ✓ | ✓ within the organisation | ✓ evaluated in the organisation context; official geography is read-only reference data |
| Transactions across boundaries | ✓ everything local | ✓ local | ~ only platform ↔ organisation crosses, and only for accounts, the registry and identity links. XD-10 already restricts cross-database delay to reads. Account suspension and claim-binding become eventually consistent with a bound Δ [I] |
| Cross-context queries and projections | ✓ (risk: cross-organisation reports) | ~ | ~ cross-organisation analytics need an explicit, approved projection (INV-AFF-1: none by default) |
| Operations: backup, recovery, migrations, observability | ✓ simplest | ✗ N databases **and** N account stores | ~ N organisation databases + 1 platform store. Needs migration orchestration, schema-version tracking per organisation, per-organisation restore, and logs that do not recreate the register |
| Performance and scale | ~ one large database | ✓ | ~ per-organisation load isolated. **Sign-in discovery cost grows with N** (g15: about 9.5 % of sign-ins degraded at N = 100 and 63 % at N = 1,000, with 99.9 % independent availability) [M]; much better on one cluster [M] |
| Security and audit | ~ one breach exposes everything | ✓ | ✓ blast radius one organisation, or the minimal platform store; the platform store needs high availability and strong protection |
| Testing and local development | ✓ | ~ | ~ needs multi-database test fixtures (isolated test database prerequisite, ARB-SEC-01) |
| Cost and implementation complexity | ✓ lowest | ✗ highest (duplicated identity) | ~ substantial, but incremental (organisations move one at a time, investigation #2) |
| Evolution as organisations grow | ~ noisy-neighbour risk; one schema for all | ✓ | ✓ per-organisation scaling; possible placement per region if ever required [U] |

**Decisive observations:**
1. **B conflicts with three recorded positions**: BR-4 (confirmed), XD-3a [P] and approved B1.3 / B1.4. It would require separate logins per organisation.
2. **A conflicts with INV-AFF-1 [P] in substance.** A shared database holding every organisation's affiliations *is* a central affiliation register. A is still the **current state**, and remains the transitional state for organisations not yet migrated.
3. **C is consistent with every recorded decision, approved and proposed.** Its open risks are operational: discovery scale, platform-store availability, migration orchestration.

**Qualification (validation review, 2026-11-02):**
- **"C is the only consistent option" holds only among A / B / C as defined here, and only while INV-AFF-1 and XD-3a stand.** Both are **PROPOSED**, not approved.
- If INV-AFF-1 were not adopted, a hardened A (shared database with fail-closed scoping under INV-ORG-SCOPE-1) would satisfy every **approved** requirement: BR-4, B1, INV-ORG-SCOPE-1 principle, ratified Membership.
- **A variant was not evaluated:** one database with a **separate schema per organisation**. Whether it meets INV-AFF-1's "in its own database" is a matter of interpretation (literal database vs isolation intent). It should be assessed with the topology sub-decision.

## 4a. Per-option validation

| | A: shared database | B: per organisation, no platform store | C: hybrid |
|---|---|---|---|
| **Satisfies** | BR-4; B1; INV-ORG-SCOPE-1 (if enforced fail-closed); ratified Membership; XD-12 as written ("single database") | INV-ORG-SCOPE-1 physically; ratified Membership; INV-AFF-1 | BR-4; B1; INV-ORG-SCOPE-1; INV-AFF-1 [P]; XD-3a / XD-3b [P]; XD-PROV [P]; ratified Membership |
| **Conflicts with** | INV-AFF-1 [P] (records not "in its own database"; one store holds all affiliations) | BR-4 / XD-3a (one login across organisations); B1.3 / B1.4 (no central sign-in routing) | none recorded |
| **Depends on** | strict query discipline (Stage 1: 18 potential defects; non-fail-closed fallback today) | separate accounts being acceptable | INV-AFF-1 and XD-3a being adopted; discovery feasibility (scale U-1…U-3); platform-store availability |
| **Invariants enforceable locally** | all (one database) | all intra-organisation ones | all **intra-organisation** ones (Membership, Governance, Affiliation co-located) |
| **Need cross-database coordination** | none | none (no platform) | **reads only:** sign-in discovery (B1.3); deletion eligibility (XD-PROV AM-1 r2); identity claim-binding (XD-3b); account suspension visible in organisations (Δ, XD-10 [P]) |
| **Security / operational risks** | one breach exposes all; scoping defects leak across organisations | duplicated identity; per-organisation account recovery | platform store is a single point for sign-in; N-database migrations; logs must not recreate the register |
| **Unresolved decisions that could change the outcome** | INV-AFF-1 rejected → A viable | BR-4 / XD-3a reversed → B viable | **D-2** (a cross-organisation write invariant would need an exception to INV-AFF-1); topology; scale |

## 5. A question decisive for D, not yet asked

**Are there business rules that span organisations?**

Examples: "a person may hold office in only one party"; "a person may not be a member of two competing parties".
- **If yes:** under C such rules cannot be enforced without a central cross-organisation index, which INV-AFF-1 forbids. They would need either an explicit, approved and data-protection-reviewed exception, or acceptance that they are not enforced by the platform.
- **If no:** C has no cross-organisation write invariant at all.

BR-4 ("one person can be associated with several organisations") points to "no" for *association*. **Cross-organisation office or exclusivity rules are not addressed anywhere in the corpus** [O].

**Corpus search (2026-11-02, excluding KnowledgeOS):**

| Candidate | Exact statement | Source | Authority / status | Scope | Needs central knowledge? |
|---|---|---|---|---|---|
| Position incompatibility scope | "**BQ-INC** — scope of incompatibility: global, per org, per committee kind?" | v1.5 `:347`; decision sheet `:163` | **open PO question** | "global" = cross-organisation | yes, if "global" |
| Candidate design assumption | incompatibility token `holdings:(p,o)` | v1.5 `:258` | [P] candidate | per organisation | no |
| One active Membership per organisation | "A Member has at most one active Membership per Organization at a time … Configurable … **Pending**" (R-02-2) | `…/cross_domain_brainstorming/# Principal DDD review — Cross-Domain Re.md:955` | brainstorming; aligned with ratified BD-I5 (per person and organisation) | **per organisation** | no |
| Cross-organisation rules in general | "Federation agreements and cross-organization rules — **Not available** — Required evidence" | `…/20261010_1212_brainstorming_membership.md:34` | brainstorming: **records the evidence gap** | cross-organisation | — |
| Overlapping memberships | "Some organizations allow overlapping memberships (e.g., dual membership in federated structures)" | `…/20261010_1217_brainstorming_cross_domain.md:141` | brainstorming suggestion, not a requirement | per organisation or federation | unknown |
| Dual membership across authorities | "Shared personnel … **No (no restriction on dual membership)**" | `docs/architecture/design/Round38A-03_Authority_Capture_Collusion_Threats.md:439` | threat analysis (election-adjacent): an observation of the current design | — | — |
| Federated organisation structures | none found; `organisations` has no parent column | business case documents; organisation migrations | **absent** | — | — |
| Statutes / party constitutions | none in `docs/publicdigit/business_case`, `business_rules` or the Membership documents | — | **absent** | — | — |

**Reading:**
- **The corpus establishes no cross-organisation business rule.**
- **It explicitly records such rules as missing evidence.**
- **It contains one open PO question (BQ-INC) whose "global" answer would create one.**

This absence is **not** a decision that no such rule exists.

**Cross-organisation coordination already in proposed records:** all of it is **reads or preconditions**, not write invariants. The examples are:
- sign-in discovery (B1.3 / INV-AFF-1);
- the deletion-eligibility check (XD-PROV AM-1 r2);
- identity claim-binding (XD-3b; BR-11).

**Consequences for C, per possible answer:**
- **No cross-organisation write invariant:** C enforces every rule inside one organisation's database.
- **Exclusivity of *association*** ("no member of two parties"):
  - **not** enforceable without central knowledge;
  - fan-out discovery could *detect* a conflict at the moment of admission, but an incomplete answer cannot prove absence, so it must block or defer;
  - otherwise it needs an approved exception to INV-AFF-1, or must be left unenforced with the limit stated.
- **Exclusivity of *office*** (BQ-INC "global"): the same, keyed by positions instead of memberships.
- **A detect-only variant** (warn, do not block) still discloses to organisation A that organisation B recognises the person, which conflicts with XD-3a C-1 unless consented.

## 6. Failure modes under C

| Failure | Effect | Mitigation [R] |
|---|---|---|
| one organisation database unavailable | that organisation unavailable; sign-in discovery incomplete. **No automatic entry** (B1.4; INV-AFF-1) | per-organisation health; "some organisations could not be checked" notice |
| platform store unavailable | **no sign-in for anyone** (single point) | high availability; read replicas for authentication |
| request routed to the wrong database | prevented by fail-closed context and the row check (INV-ORG-SCOPE-1) | architecture tests AC-D3, AC-D4 |
| schema drift across N databases | inconsistent behaviour | migration registry; per-organisation version gate (AC-D7) |
| restore of one organisation | must not affect others; identity links (`PersonRef`) remain valid | AC-D8 |
| operator access to all organisation databases (shared application) | policy-level, not physical separation | per-organisation credentials (multi-database D6), audit |
| discovery or logging recreates the register | contradicts INV-AFF-1 | logging rules (INV-AFF-1 evaluation, item 4); AC-D6 |

## 7. Implications for the governance domains

- **Membership, Affiliation, Governance** (committees, positions, appointments, tenures, mandates, jurisdiction assignments), **Communication** (forum) and **provenance** are organisation-owned. They go to the organisation's database, co-located, so their mutual invariants stay transactional [I].
- **Identity** (bare Person, account, service relationship) and the **organisation registry** are platform-level.
- **Geography** splits [I; OQ-OS-1 open]:
  - **official** reference units (`organisation_id` NULL today [F]) are platform reference data, consumed read-only with revision tokens (XD-5 modified B: `geo:official:{country}`);
  - **organisation-custom** units (`organisation_id` set [F]) are organisation-owned (`geo:org:{o}`).
- **Authorisation** is evaluated inside the Working Organisation, against organisation-local facts plus platform-level restrictions (XD-4 [P]).

## 8. Recommendation [R]

**Option C (hybrid), as the target**, with:
- **INV-DB-1 / INV-DB-2 recorded** as proposed invariants;
- **INV-DB-3 covered by INV-ORG-SCOPE-1's enforcement expectation**;
- a **per-organisation migration** from today's shared database. A remains the transitional state, under INV-ORG-SCOPE-1;
- the **topology sub-decision** (one cluster vs separate servers) **deferred** until the scale figures exist. The g15 evaluation shows it decides whether sign-in discovery is workable.

**Not recommended:** B (conflicts with BR-4, XD-3a, B1); A as the target (conflicts with INV-AFF-1).

**Rationale:**
- Among A / B / C, C is the only option consistent with all recorded decisions, approved **and proposed**. It depends on INV-AFF-1 and XD-3a being adopted (§4a).
- It keeps every intra-organisation governance invariant inside one transaction.
- Its costs are operational and measurable, not semantic.

**D-1 should therefore be decided together with, or after, the ARB's view on INV-AFF-1.**

## 9. Decisions requiring authority

| # | Decision | Authority |
|---|---|---|
| D-1 | XD-15 target = C (hybrid) | **PO** (direction) + ARB |
| D-2 | **Cross-organisation business rules: do any exist?** (§5) | **PO** (statute / business) |
| D-3 | record INV-DB-1 / INV-DB-2 | ARB |
| D-4 | topology (cluster vs separate servers): after the scale figures | ARB + Ops |
| D-5 | owner of the tenancy capability (multi-database D3: infrastructure capability vs Platform context) | ARB |
| D-6 | migration policy (one organisation at a time; transition rules) | PO + ARB |
| D-7 | official vs organisation-custom geography placement (OQ-OS-1; XD-5) | ARB |

## 10. Acceptance criteria (verifiable later; none implemented)

| # | Criterion | Kind |
|---|---|---|
| AC-D1 | every table classified as platform / organisation / reference; no organisation-owned table in the platform store | static architecture test over migrations |
| AC-D2 | the platform store holds no organisation-specific fact except the registry and routing | schema lint |
| AC-D3 | every organisation-owned repository obtains its connection from the Working Organisation; a missing context throws | architecture test + unit test |
| AC-D4 | a row whose `organisation_id` ≠ the Working Organisation is rejected | guard test |
| AC-D5 | no foreign key crosses databases; cross-boundary references by identifier only | static test |
| AC-D6 | discovery results appear in no log, cache, trace or metric label | static scan + test |
| AC-D7 | each organisation database reports its schema version; requests are refused on a version mismatch | integration test |
| AC-D8 | restoring one organisation's database leaves every other organisation and every identity link valid | recovery drill |
| AC-D9 | sign-in succeeds when one organisation database is down; that organisation is shown as unavailable; no automatic entry | integration test |

## 11. Unknowns blocking the topology and feasibility parts

| # | Missing parameter | Needed for |
|---|---|---|
| U-1 | number of organisations (now, in 1–3 years) | discovery cost, operations |
| U-2 | planned topology and failure domains | discovery completeness (g15 D2) |
| U-3 | peak and election-day sign-ins | discovery load |
| U-4 | production data volume (multi-database D12) | migration cost |
| U-5 | data-residency or customer-specific hosting requirements | topology |

## 12. Validation review (2026-11-02, HEAD `6308a90cb`)

- **Added:** §4a per-option validation; the corpus search for D-2 (§5); the XD-12 open status.
- **Qualified:** the "only consistent option" claims now state their dependence on INV-AFF-1 and XD-3a (both PROPOSED) and name an unevaluated variant (schema per organisation).
- **Recommendation unchanged (C), still a proposal.** No source record status changed.

## 13. Traceability

- `docs/publicdigit/brainstorming/multi-database-system/` (investigations #1 and #2; read only).
- Decision sheet XD-15 / XD-10 / XD-12.
- XD-3a, XD-3b, INV-AFF-1, INV-ORG-SCOPE-1, XD-PROV records.
- INV-AFF-1 evaluation and model g15.
- Stage 1 static investigation (2026-11-02).
