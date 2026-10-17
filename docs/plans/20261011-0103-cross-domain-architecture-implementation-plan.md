# Plan: Implementing the cross-domain organisational architecture (Membership · Geography · Committee / Governance)

**Status:** **PROPOSED, not approved.** EP-01: no step below may start without explicit Human PO / ARB approval of *that* step's own plan. This document authorises nothing.
**Date:** 2026-10-11 · **Author:** Claude (developer and researcher role). Gap discovery was done by **three independent read-only reviewer subagents**; I spot-checked their P0 claims myself (§2).
**Target architecture (candidate, not ratified):** `docs/publicdigit/membership/architecture/brainstorming/cross_domain_brainstorming/`:
- `20261011_0008_cross_domain_discovery_package_v1.5.md`;
- `20261011_0035_implementation_readiness_review_v1.5.md`;
- `20261011_0100_verification_round_closure.md`.

**Scope:** Membership, Geography, Committee / Governance, plus the Authorization capability and the minimal Communication slice that VS-1 needs. **Election is out of scope.**

---

## 1. Objective

Turn the candidate architecture into running, verified code **in dependency order**:
1. **contain the security gaps** that exist today;
2. **stop data-integrity damage** in legacy code;
3. **obtain the blocking decisions**;
4. **build the contracts and infrastructure** (ports, scope tokens, unit of work);
5. **prove the cross-domain mechanics** in one test-only vertical slice;
6. **extend** Membership facts, Governance, Geography and Jurisdiction;
7. **migrate** legacy data and code with parity tests.

---

## 2. Background: gap discovery results

Three independent reviewers worked read-only, statically, with nothing executed.

| Context | Verdict (reviewer) | Spot-checked by me |
|---|---|---|
| **Membership** | "The ratified kernel is a clean, well-tested island that nothing in production uses." All live writes go through legacy `App\Models\Member`. The kernel persists **Admit and End only**. No MembershipQueryPort. AuthorityCheckPort has no subject / as-of and runs outside the transaction | **M-01** confirmed (no reference to `…\Organisational` outside the kernel). **M-05** confirmed (`MembershipAct` = Admit, End; fact-kind CHECK). **M-03** confirmed (`MemberController::export` :125 has no `authorize`) |
| **Geography** | "Not ready to be the reference for `JurisdictionContains`." **P0:** no organisation filter on reads; three different conventions for the stored path; hard delete with cascade; no history | `valid_from` / `valid_to` **are live**: they are in the registered **Landlord** migration (`Landlord/2025_01_01_000002…:58-59`). **This corrects my architecture documents** (§9). FK `onDelete('cascade')` confirmed (:74, :80) |
| **Committee / Governance** | "Target architecture not implemented anywhere." Four legacy models in three write stores. The Governance API has **no authorisation**. The Governance aggregate is loaded **blank**, so its duplicate guard is dead. A Membership repository save **rewrites the history** of ended assignments | **C-01** confirmed (no `authorize` / Gate in `Api/Governance/CommitteeMemberController.php`). **C-04** confirmed (imports the non-existent `Governance\Domain\Committee\MemberId`). **C-05** confirmed (`Repositories/EloquentCommitteeRepository.php:143-149` updates `left_date` without an `is_active` filter) |

### 2.1 Consolidated gap register (P0 / P1; reviewer IDs kept)

| ID | Sev | Gap | Fix type |
|---|---|---|---|
| C-01 | **P0** | Governance API add / remove committee member: no authorisation; any verified org member can make anyone chair | security |
| C-02 / M-02 | **P0** | committee application review: no reviewer authority; self-approval possible; three writes not in one transaction; route group lacks `ensure.organisation` | security + integrity |
| M-03 | **P0** (privacy) | member export (personal data) available to any participant of the organisation; **not in PBDIGIT-266** | security |
| G-01 | **P0** | geography reads have no organisation filter; parent units from another organisation are accepted; the isolation test is a false positive (G-01a) | security (tenant isolation) |
| C-03 | **P0** | Governance committee aggregate loaded blank, so INV-P2 / P3 are not enforced; re-adding someone overwrites their role silently | integrity |
| C-04 | **P0** | Governance remove handler imports a missing class, so DELETE always returns 500 | defect |
| C-05 | P1 (integrity) | every save of a Membership committee rewrites `left_date` of all historical assignments | integrity |
| G-02 | **P0** | stored geography path written three different ways; containment unreliable; seeded Nepal data has no paths | integrity |
| G-03 | **P0** | hard delete cascades over subtrees; committee and member geography links silently set to NULL; no history | integrity |
| M-05 | **P0** (for VS-1) | no MembershipQueryPort; no Suspend / Restore / ChangeType facts | missing capability |
| M-07 / M-08 | **P0 / P1** | authority is checked outside the write transaction; the adapter owns the transaction; no lock timeout; no 55P03 classification | architecture conformance |
| M-09 | P1 | `create()` inserts an empty Membership row in a standalone statement; no `admissions:o` token | architecture conformance |
| M-10 | P1 | `knownAt(Instant)` replays by timestamp (TR-4 violation) | replay correctness |
| C-06 / C-07 / C-08 | P1 | capacity limits never match (role names vs paths); unique indexes created only under a config condition; no locks or version | integrity / concurrency |
| C-10 / C-11 / C-12 | P1 | stored per-actor role in AuthorityChain; Spatie `hasRole('admin')` global across organisations; global `users.is_committee_member` grants voter-list access | authorization |
| C-13 | P1 | Governance domain imports Membership domain types (27 files) | boundary |
| G-04 / G-05 / G-05a / G-06 / G-07 / G-08 | P1 | version bumps bypassed; dead sync code; Membership geography validation silently skipped; six committee-geography representations; "level" overloaded; no GeographyQueryPort | integrity / boundary |
| M-01 / M-04 / M-12 / M-13 | P0 / P1 | dual truth (kernel vs legacy); broken legacy use cases; 275 committee files in Membership; three `MemberId` VOs | migration |

---

## 3. Scope

**In:**
- security containment of the items above;
- legacy integrity fixes that need no architecture decision;
- contracts, infrastructure and slices for Membership, Authorization, Governance, Geography / Jurisdiction;
- minimal Communication (VS-1 only);
- parity migrations.

**Out:**
- election workflows;
- the full Forum features (S-8);
- OrganisationStructure / UnitAffiliation (S-5, listed but not detailed here);
- tenancy redesign (XD-15 assumes the shared database).

---

## 4. Design decisions this plan depends on (none approved)

| ID | Decision | Recommended | Needed before |
|---|---|---|---|
| DR-07 | Committee ownership (ADR-0001 vs N-6) | **A as the ownership act, B as the cutover mechanism.** Governance owns Committee. Legacy Membership committee code is frozen and acts as the **behavioural oracle** (legacy model B, per the Committee reviewer). Governance runs in shadow with reconciliation until parity | Phase 7 |
| XD-1, XD-4, XD-7, XD-11, XD-12, XD-13 (DO), XD-14, XD-15 | v1.5 / closure round-1 package | as proposed | Phase 3 |
| XD-5 | Geography versioning | **modified B** (Geography reviewer): monotonic revision tokens `geo:official:{country}` + `geo:org:{o}`, plus an append-only `geo_unit_revisions` table. Snapshot = (scope, rev). Moving org-custom units to OrganisationStructure would reduce it to plain B | Phase 8 |
| Membership owner | `admissions:o` token and the unit-of-work change in the kernel | — | Phase 4 |
| BD-SUSP / BD-TYPE (Membership) | Suspend / Restore and ChangeType semantics | business | Phase 6 |
| RD-1…RD-5, BQ-P1, BQ-P2, BQ-P3, BQ-INC, BQ-J1, BQ-DEL | Governance model | v1.5 | Phase 7 / 8 |
| XD-3 | identity scope, PersonRef ↔ legacy `organisation_user_id` mapping | — | Phase 9 (cutover) |

---

## 5. Phased plan

Each phase is one or more stories. Each story gets its own EP-01 plan, TDD (RED → GREEN), a developer guide and EP-02 review.

### Phase 0 — Security containment (independent of every architecture decision)

- **Entry:**
  - isolated test database provisioned, with evidence reviewed (ARB-SEC-01);
  - `PBDIGIT-266` authorised;
  - **new backlog items proposed** (creating them needs authorisation): M-03 (export), G-01 (geography tenant isolation), C-11 (global `hasRole('admin')`), C-12 (`is_committee_member`), C-16 (debug routes).
- **Work:** RED feature tests first, as a non-privileged user of the same and of another organisation, for:
  - C-01 / C-02 (`PBDIGIT-266` items 2–3);
  - M-03;
  - G-01 (multi-org test with levels and units populated);
  - C-16.

  Then the interim guards: `manageCommittee` ability, `ensure.organisation` on the committee group, the export ability check, the geography `organisation_id` filter + parent-organisation validation + an org-scoped unique key, and removal of the debug routes. C-04 (the import) is fixed only **after** its guard exists.
- **Exit:** every listed route returns 403 / 404 as specified; the multi-org geography test is green; no committee write route without a guard (grep check + test); independent review.

### Phase 1 — Legacy integrity fixes (no architecture dependency)

- **Entry:** Phase 0 tests exist (so fixes cannot weaken them); stories authorised.
- **Work:**
  - **C-05:** RED test "a save does not alter ended assignments" → restrict step 4 to active rows;
  - **C-07:** make the partial unique indexes unconditional for pgsql; verify `pg_indexes` on the real databases (read-only);
  - **C-02 atomicity:** one transaction around the review handler;
  - **G-03:** hard delete forbidden (retire via `valid_to`); FK `RESTRICT` (migration, separately approved);
  - **G-02:** one path convention, backfilled from the `parent_id` chain, invariant query = 0 mismatches; seeder fixed;
  - **G-05a:** geography validation uses the default connection plus an organisation filter; fix the missing import.
- **Exit:** RED → GREEN per item; invariant queries 0; regression suites green; **no domain-model change**.

### Phase 2 — Decisions (PO / ARB; runs in parallel with Phases 0–1)

- **Entry:** decision package (v1.5 §6, readiness review §8, closure §3).
- **Exit:**
  - DR-07 act recorded (new ADR; ADR-0001 → superseded; ADR index updated; freeze rule);
  - round-1 XD decisions recorded;
  - XD-5 recorded;
  - the business-question owners named;
  - architecture errata (§9) acknowledged.

### Phase 3 — Contracts (specification + in-memory contract tests)

- **Entry:** XD-1, XD-4, XD-7, XD-11, XD-12, XD-13, XD-15.
- **Work:**

  | Contract | Content |
  |---|---|
  | `MembershipQueryPort` v0 | `IsMember`, admission dates, durations at `(asOf v, knownAtVersion)`; returns `{answer ∈ T/F/U, scope, version}`; closed-world declaration. `Suspended` / `CurrentType` come after Phase 6 |
  | `AuthorityCheckPort` (G-AUTH-1, additive) | `AuthorityContext{subject, decisionRef?, asOf}`; extended `AuthorityEvidence`; read set |
  | **Unit-of-work / acquisition port** | the transaction boundary moves from the adapter to the application, so foreign tokens can be locked in the same transaction (M-08) |
  | `ScopeTokenPort` | TC-1…TC-14 incl. closure §2 owner ancestor tokens, **covering the `create()` statement** (§9 erratum E-3) |
  | `DecisionRecord` + PDP API | readiness review §4; g12 vectors as the contract test |
  | `GeographyQueryPort` | `contains`, `ancestors` at a given revision; returns `{scope → rev}` |

- **Exit:** signatures approved; in-memory contract tests green; g12 vectors pass for the reference implementation.

### Phase 4 — Membership kernel infrastructure

- **Entry:** Phase 3; Membership owner approval; isolated database.
- **Work:**
  - `admissions:o` token locked `FOR UPDATE` and bumped in the **same** transaction as `create()` + the first fact (M-09);
  - unit of work (M-08);
  - `SET LOCAL lock_timeout`, `statement_timeout`; 55P03 classification + bounded retry → `DEPENDENCY_FAILURE` (TC-9 / TC-10);
  - `knownAtVersion` (M-10);
  - append-only trigger on `org_membership_facts` (M-11);
  - the `PBDIGIT-264` F-4 FK;
  - G-AUTH-1 wired: authority decided **inside** the transaction after acquisition (M-07, WB-10).
- **Exit:**
  - the existing kernel suites green;
  - new DB tests on the isolated database: absent-scope guard vs first creation (DB-T5), lock-timeout retry (DB-T7), authority revocation vs Admit, seq replay;
  - g11 E9 / E4 re-run on the isolated database matches.

### Phase 5 — VS-1 (re-scoped): protected forum contribution, test-only

- **Change from closure §7:** the fixture policy is **"members may post"** (`IsMember`); the revocation race uses **End** (membership ended), not suspension, because Suspend does not exist (M-05).
- **Entry:** Phases 3–4; XD-14 (Communication minimal); feature flag off; no route.
- **Exit:** closure §7 DB-T1…DB-T10, with "suspension" read as "End"; DB-T5 per Phase 4; release gate unchanged (**no production route before the Membership cutover**, Phase 9).

### Phase 6 — Membership fact coverage

- **Entry:** C-1 accepted (`PBDIGIT-268`); business decisions per fact (BD-SUSP, BD-TYPE).
- **Work:** persist Annul / Void (M-06); then Suspend / Restore; then ChangeType. `MembershipQueryPort` v1 adds `Suspended` and `CurrentType`.
- **Exit:** port answers incl. footprint versions; replay by seq; the VS-1 fixture can then be extended to "non-suspended members".

### Phase 7 — Governance S-2: Committee, Position, Tenure, PositionHoldings

- **Entry:** DR-07; RD-1…RD-4; BQ-P1, BQ-INC; Phases 3–4; the **freeze + oracle** step done (characterisation tests over models A–D with legacy model B as primary oracle; read-only reconciliation query across the stores; divergence report baselined).
- **Work:**
  - Governance-owned identifiers (removes the C-13 inverted dependency; ACL to Membership via the port);
  - bitemporal Appoint / Vacate facts;
  - κ per Position;
  - **database partial-unique constraints as the backstop for single-aggregate invariants** (reviewer challenge accepted) + `FOR UPDATE`;
  - the two-root transaction for INV-P6;
  - EffectiveTenure via `MembershipQueryPort`;
  - shadow writes alongside the legacy store.
- **Exit:** g7 / g9 properties as unit tests; concurrency tests on the isolated database; the reconciliation report trending to 0 diffs.

### Phase 8 — Geography history, tokens, query port; S-6 Jurisdiction

- **Entry:** XD-5; Phase 1 (G-02 / G-03 fixed); DR-07; BQ-J1.
- **Work:**
  - single versioned writer (G-04);
  - `geo_unit_revisions`;
  - revision tokens bumped in the same transaction as each write;
  - retire the dead sync code (G-05);
  - `GeographyQueryPort` replaces the Membership "ACL" calls (G-08);
  - **S-6:** a `jurisdiction_assignments` fact table (M:N, bitemporal) with a parity migration from `committees.geo_unit_id`; the other five geography columns deprecated (G-06); level semantics separated (G-07).
- **Exit:** contract tests; replay pins geography revisions; parity report 0 diffs.

### Phase 9 — Membership cutover (ARB-MEM-01; existing Membership track)

- **Entry:** XD-3 (identity mapping PersonRef ↔ legacy keys); `PBDIGIT-265`; the existing C-1…C-10 cutover gates.
- **Work:** attested-provenance import of `members`; eligibility and consumers switched to `MembershipQueryPort`; legacy membership writes disabled.
- **Exit:** parity report; legacy writes off; **only now** may VS-1 routes be enabled (release gate).

### Phase 10 — S-4 committee parity migration and authorization replacement (VS-2)

- **Entry:** Phase 7 shadow parity; Phase 9 (for membership-dependent eligibility).
- **Work:** cut over to Governance as the source of truth; retire models A and C and the `is_committee_member` flag. Replace role-name checks (`hasRole('admin')`, `manageCommittee`-by-platform-role) with PDP decisions over EffectiveTenure + Mandate.
- **Exit:**
  - zero reconciliation diffs over an agreed period;
  - every committee route decided by the PDP;
  - Membership contains no committee code;
  - projections rebuildable from facts.

### Phase 11 — Later slices

OrganisationStructure + UnitAffiliation (S-5); Forum moderation, jurisdiction forums, mutes, subscriptions (S-8); audit consolidation + replay (S-9); end-to-end acceptance (S-10).

---

## 6. Dependency graph (summary)

```text
Phase 0 (security) ─┬─> Phase 1 (legacy integrity)
                    │
Phase 2 (decisions) ┼─> Phase 3 (contracts) ─> Phase 4 (kernel infra) ─> Phase 5 VS-1 (test-only)
                    │                                   └─> Phase 6 (fact coverage)
                    ├─> Phase 7 Governance (DR-07) ──────────────────────┐
                    └─> Phase 8 Geography / Jurisdiction (XD-5, BQ-J1) ───┤
Phase 9 Membership cutover (XD-3, ARB-MEM-01) ─> VS-1 route release ─> Phase 10 (S-4, VS-2) <─┘
```

---

## 7. Task checklist (Phase 0–2 detail; later phases get their own plans)

- [ ] Infrastructure: isolated test database + provisioning evidence (blocks Phase 0)
- [ ] Propose backlog items: M-03 export, G-01 geography isolation, C-11 global admin role, C-12 committee flag, C-16 debug routes, C-05 history rewrite, G-02 / G-03 geography integrity (needs authorisation to create)
- [ ] `PBDIGIT-266` plan (EP-01) incl. items 2–3 and the new items
- [x] Decision sheet prepared: `docs/publicdigit/reviews/2026-10-11-cross-domain-decision-round-1-sheet.md` (DR-07, round 1, XD-5, Membership owner items, security authorisations). **Not decided.**
- [ ] DR-07 decision → PO / ARB
- [ ] Round-1 decisions → PO / ARB
- [ ] XD-5 (modified B) → PO / ARB
- [ ] Architecture errata (§9) applied to the candidate documents
- [ ] Phase 1 story plans (C-05, C-07, G-02, G-03, G-05a)

---

## 8. Risks

| Risk | Mitigation |
|---|---|
| security fixes wait for architecture decisions | Phase 0 is explicitly independent |
| integrity damage continues (C-05) while planning | Phase 1 fixes need no architecture decision; prioritise C-05 |
| Governance re-model diverges from legacy behaviour | freeze + legacy model B as oracle + shadow + reconciliation (DR-07 B mechanism) |
| kernel port changes (unit of work) disturb the ratified Membership track | Membership owner approval; additive ports; existing suites as regression gate |
| VS-1 built on facts that do not exist | re-scoped to `IsMember` / End (Phase 5) |
| geography data repair (G-02 backfill) hits real data | read-only invariant queries first; a migration only with approval and backup |
| scope creep | one story · one commit · one review; discovered work becomes backlog |

---

## 9. Errata to the candidate architecture documents (to be applied)

| # | Document | Claim | Correction (evidence) |
|---|---|---|---|
| E-1 | v1.5 §5.3; readiness review §2 | "`valid_from` exists only in an unregistered Tenant migration; likely not live" | **Wrong.** `valid_from` / `valid_to` are in the registered **Landlord** migration (`…/Landlord/2025_01_01_000002…:58-59`; provider `GeographyServiceProvider.php:86`, registered `config/app.php:208`). They are live (if migrations ran) but unused |
| E-2 | v1.5 §1 glossary | Membership kernel "Implemented" | only **Admit / End** are persisted; no Suspend, Restore, ChangeType, Annul, Void persistence (`MembershipAct.php`; fact-kind CHECK) |
| E-3 | closure §2.2 | tokens are created "inside the transaction that creates the scope's first fact" | the kernel's `create()` inserts the Membership row in a **separate statement** before the first fact (`PostgresMemberRepository.php:96-114`). The ancestor-token rule must cover `create()` (Phase 4) |
| E-4 | closure §7 VS-1 | fixture "non-suspended members"; suspension race tests | Suspend does not exist; re-scoped to `IsMember` / End (Phase 5) |
| E-5 | v1.5 §5.4 WB-10; readiness review TC-3 | decide inside the transaction after acquisition | needs a repository-port change: the adapter owns the transaction (`PostgresMemberRepository.php:137`; `DecideAndAppend.php:119`) |
| E-6 | closure §2.3 | E9 table placeholders | filled when the g11 run 4 completes (in progress at writing) |

---

## 10. Progress

- 2026-10-11 01:13: decision-round-1 sheet prepared (OPEN); errata notes inserted into v1.5, the readiness review and the closure.
- 2026-10-11 01:03: plan written (PROPOSED). Gap reviews done (three independent reviewers; P0 claims spot-checked). Nothing implemented.

## 11. Open questions

- Who provisions the isolated test database, and when? (infrastructure owner)
- Are the new security findings (M-03, G-01, C-11, C-12, C-16) added to `PBDIGIT-266` or raised as separate items? (PO)
- Should Phase 1 data repairs (G-02 backfill) run on production data, or wait for Phase 8? (PO + infrastructure)

## 12. Next actions

1. Infrastructure: provision the isolated test database (unblocks Phases 0, 4, 5).
2. PO: authorise the security backlog items and the `PBDIGIT-266` plan.
3. PO / ARB: DR-07 and decision round 1.

## 13. Traceability

The three independent reviewer reports (session 2026-10-11, summarised in §2), with file:line evidence in each gap row; my spot-checks in §2; architecture documents as listed above.
