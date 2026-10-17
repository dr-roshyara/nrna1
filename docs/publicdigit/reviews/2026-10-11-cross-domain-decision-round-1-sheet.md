# Cross-Domain Architecture: Decision Round 1 Sheet (for Human PO / ARB)

**Date:** 2026-10-11 · **Prepared by:** Claude (developer and researcher role)
**Status:** **OPEN.** Every "Decision" field is blank on purpose. This sheet records no approval and must not be read as one. A recommendation is not a decision.
**Purpose:** let the PO / ARB decide the items that block the plan `docs/plans/20261011-0103-cross-domain-architecture-implementation-plan.md` (PROPOSED), in dependency order, with the evidence for each item in one place.
**Sources:**
- candidate architecture `docs/publicdigit/membership/architecture/brainstorming/cross_domain_brainstorming/`:
  - `…0008_cross_domain_discovery_package_v1.5.md`;
  - `…0035_implementation_readiness_review_v1.5.md`;
  - `…0100_verification_round_closure.md`;
- the plan's §2 independent gap reviews;
- models in `docs/publicdigit/membership/architecture/models/` (g7–g13).

**Scope:** Membership, Committee / Governance, Authorization, Geography / Jurisdiction, minimal Communication. Election is out of scope.

**Model evidence:** "verified within bounds" means research models only (PostgreSQL probes on a private throwaway cluster, Python enumerations). **None of it is evidence about the running application.**

---

## Part A: Security authorisations (independent of every architecture decision)

These protect the running application today. **The single highest-priority prerequisite is the isolated test database** (infrastructure owner; ARB-SEC-01). It is needed to execute any of them test-first.

| # | Proposed item (draft; not filed) | Evidence (verified by the reviewer + my spot-check where marked ✔) | Expected RED test | Sev |
|---|---|---|---|---|
| A-1 | Extend **`PBDIGIT-266`** execution to items 2–3 (committee application review; Governance API add / remove committee member) | `Api/Governance/CommitteeMemberController.php`: no `authorize` / Gate ✔. `ReviewMembershipApplicationRequest.php:11-14` allows any verified user; the route group has no `ensure.organisation` | a non-privileged member of the same organisation, and a member of another organisation → **403** on add, remove and review; the applicant cannot approve themselves | P0 |
| A-2 | **New:** member export authorisation | `MemberController::export` (:125) has no `authorize`; every other action in the controller has one ✔ | a participant without the member-management ability → 403; another organisation → 403/404 | P0 (personal data) |
| A-3 | **New:** Geography tenant isolation | `GovernanceGeoUnitQueryService.php:55-96`, `:135-160` have no `organisation_id` filter; `GeoUnitController.php:96,106` accepts a foreign `parent_id`; the global unique key `uk_country_code`; the isolation test passes for the wrong reason (`GeoUnitControllerTest.php:103-118`) | two organisations, both with levels and units: no cross-read, no foreign parent, codes unique per organisation | P0 |
| A-4 | **New:** global role checks | `hasRole('admin')` in `ElectionVoterController.php:532`, `DashboardResolver.php:447,545,695`; no Spatie `team` key | a global admin role does not authorise actions inside an organisation where the user has no role | P1 |
| A-5 | **New:** `users.is_committee_member` grants access (`CheckUserRole.php:60,89`; `EnsureCommitteeMember.php:24`; `VoterlistController.php:104,147`) | static | the flag alone (no tenure in that organisation) → 403 | P1 |
| A-6 | **New:** debug routes in route files (`governance-api.php:16-61`; `api_v1.php` `test-auth`, `test-post-debug`) | static | routes absent in non-local environments | P2 |

**Decision A (PO):** authorise filing A-2…A-6 as backlog items and include A-1 in the `PBDIGIT-266` plan → ☐ yes ☐ no ☐ modified: ____

**Integrity items (no architecture dependency; for authorisation after Part A's tests exist):**
- **I-1 (C-05):** a committee save rewrites `left_date` of ended assignments (`Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:143-149`, no `is_active` filter ✔).
- **I-2 (C-04):** the remove handler imports the non-existent `Governance\Domain\Committee\MemberId` ✔. Fix **after** A-1's guard.
- **I-3 (C-07):** partial unique indexes are created only under a config condition.
- **I-4 (G-03):** geography hard delete with cascading FKs (`Landlord/…000002…:74,80` ✔).
- **I-5 (G-02):** three conventions for the stored geography path.
- **I-6 (G-05a):** Membership geography validation silently skipped (connection `'tenant'`; missing import).

**Decision I (PO):** authorise I-1…I-6 as stories (test-first) → ☐ yes ☐ no ☐ modified: ____

---

## Part B: Architecture decisions, in dependency order

**Format:** question · options · consequences · recommendation · evidence · unblocks.

### B-1 · DR-07: who owns the Committee write model?

| | |
|---|---|
| **Facts** | ADR-0001 is **Accepted** and places the write model in `Membership/Domain/Committee` (`docs/adr/ADR-0001-committee-read-write-separation.md:20-21`; ADR index :24). The ratified Membership act note **N-6** calls it a legacy defect, to be placed in Governance (`2026-10-08-membership-architecture-ratification-act.md:19`). The current Governance `Committee` is a stub loaded blank (C-03); the richest behaviour is legacy model B in Membership |
| **A** | Governance owns Committee, Position, Participation, Tenure. A new ADR supersedes ADR-0001. Legacy committee code is frozen (security and bug fixes only) |
| **B** | as A, **plus** the cutover mechanism: the legacy model stays the source of truth and the **behavioural oracle** until a shadow Governance model reaches reconciliation parity |
| **C** | keep ADR-0001. This contradicts ratified N-6 and needs a Change Request to the ratified Membership architecture |
| **Recommendation** | **A for ownership, with B as the cutover mechanism** (the independent Committee reviewer and I concur) |
| **Unblocks** | Phase 7 Governance, S-4 migration, VS-2 |
| **Decision** | ☐ A ☐ A+B ☐ C ☐ other: ____ · new ADR id: ____ · freeze rule wording: ____ |

### B-2 · XD-15: tenancy assumption for this implementation horizon

| | |
|---|---|
| **Facts** | one shared PostgreSQL database with `organisation_id` scoping; no runtime connection switching; "Tenant"-named migration folders are loaded into the shared connection; no tenancy package (readiness review §2, verified) |
| **A** | shared database + strict `organisation_id` isolation (proven by negative tests) |
| **B** | database per organisation |
| **C** | hybrid (central + per organisation) |
| **Recommendation** | **A**. Every write-boundary guarantee (TC-12) assumes one database; B / C would need a revocation delay Δ (XD-10) for central facts |
| **Unblocks** | XD-10 / XD-12 scope; all lock-based contracts |
| **Decision** | ☐ A ☐ B ☐ C ☐ defer (then XD-12 is scoped "single database only") |

### B-3 · XD-4: who owns authorization rules?

| | |
|---|---|
| **Options** | **A:** Authorization owns the semantics, combination and decision record; **each resource-owning context owns its PolicyModule** (grants only for its own resources); platform and organisation modules add restrictions only. **B:** one central PolicySet owned by Authorization |
| **Consequences** | A: no rule is duplicated; no context can grant on another's resources (INV-AUTH-OWN). B: a single owner of every business rule (contradicts one-owner-per-fact) |
| **Recommendation** | **A** |
| **Decision** | ☐ A ☐ B ☐ other: ____ |

### B-4 · XD-7: how are unknown facts treated?

| | |
|---|---|
| **Options** | **K2 (strong Kleene):** a grant or condition needs **true**; a prohibition or deny binds on **true or unknown**. **Absence-as-false.** **K1** (bind only on true) |
| **Evidence** | g8 P1 (5,625 cases): absence-as-false 238 unsound permits; K1 96; **K2 0**. g8 P3 / g12: K2 sound with three modules and compound guards (0 unsound in 207,000 and 1,920 cases), plus a general soundness argument (Kleene regularity) |
| **Cost** | some requests whose answer is certain are still refused as INDETERMINATE (481 of 207,000; 20 of 1,920); they go to review, never to a silent permit |
| **Recommendation** | **K2** |
| **Decision** | ☐ K2 ☐ other: ____ |

### B-5 · XD-11: outcome taxonomy

| | |
|---|---|
| **Proposal** | PERMIT · DENY · INDETERMINATE (business unknown → review queue of the resource-owning context) · DEPENDENCY_FAILURE (operational → retry / alert) · CONFIGURATION_ERROR |
| **Recommendation** | adopt |
| **Decision** | ☐ adopt ☐ modify: ____ · owner of the review queue per context: ____ |

### B-6 · XD-13: combining algorithm for ordinary rules

| | |
|---|---|
| **Evidence** | g12: DO and PO differ in **12 / 1,920** vectors. The only genuine semantic difference: a higher-priority grant overrides an ordinary deny (e.g. a chair posting in a locked topic). Details in the closure §3 |
| **Options** | **DO** deny-overrides; no priorities in v1; exceptions written into deny guards. **PO** priority-ordered |
| **Recommendation** | **DO** (no rule can silently override a deny; more decisive under unknown facts in group D) |
| **Decision** | ☐ DO ☐ PO ☐ other: ____ |

### B-7 · XD-12: ScopeToken contract (write-boundary consistency)

| | |
|---|---|
| **Proposal** | readiness review TC-1…TC-12 + closure TC-13 / TC-14 + closure §2 **hierarchical owner tokens:** only the owning context creates and increments a token; absent scopes are guarded through the nearest existing **ancestor** token (e.g. `admissions:o`); a context root token is seeded at installation; one canonical lock order (context rank, then `COLLATE "C"`); tokens a transaction will write are locked `FOR UPDATE`; bounded retries incl. lock timeout (55P03, which Laravel does not detect) |
| **Evidence** | private PostgreSQL 18.6 probes g11 E1–E8 (reproduced in two runs): unsafe protocols 16 / 35 and 2,300 / 4,200 unauthorised; the proposed protocol 0 in every experiment; opposite lock order 36 / 70 deadlocks vs 0. **E9 (absent aggregate, concurrent creation): see the closure §2.3 once filled.** No real-application evidence yet |
| **Known code gaps it implies** | Membership `create()` runs as a separate statement (erratum E-3); the transaction boundary sits inside the adapter (E-5); there is no `admissions:o` token (M-09) |
| **Recommendation** | adopt as the contract; implementation via the plan's Phases 3–4 |
| **Decision** | ☐ adopt ☐ adopt with changes: ____ |

### B-8 · XD-1: one temporal pattern, scoped by fact category

| | |
|---|---|
| **Proposal** | readiness review §6 TR-1…TR-6. Lifecycle facts use the ratified Membership algebra (Annul / Void / Correct, append-only, per-scope `seq`). Rule versions (Mandate, PolicyModule, Position definition) are **forward-only**. Geography uses snapshots. Decisions are never corrected. **Replay by per-scope seq, never by timestamp** |
| **Evidence** | g9 (Governance compatibility); g13: seq replay 0 mismatches / 39,933 decisions vs timestamp replay 7,742 |
| **Known code gap** | `Member::knownAt(Instant)` replays by timestamp (M-10) |
| **Recommendation** | adopt |
| **Decision** | ☐ adopt ☐ modify: ____ |

### B-9 · XD-14: create a minimal Communication context

| | |
|---|---|
| **Proposal** | a new context with Forum / Topic / Post aggregates, **only** what the test-only vertical slice VS-1 needs (`ContributePost`); no production route |
| **Recommendation** | adopt (minimal) |
| **Decision** | ☐ adopt ☐ defer |

### B-10 · XD-5: geography history

| | |
|---|---|
| **Facts** | per-unit optimistic version (GEO-INV-06); `valid_from` / `valid_to` **live but unused** (Landlord migration; erratum E-1); updates overwrite in place; hard deletes; three path conventions |
| **Options** | **A:** per-unit versions along the path as tokens (rejected by the Geography reviewer: paths unreliable; new children not covered; footprints up to 11 levels). **B (modified):** monotonic revision tokens `geo:official:{country}` and `geo:org:{o}`, plus an append-only `geo_unit_revisions` table; a snapshot = (scope, revision). **C:** full bitemporal units |
| **Recommendation** | **modified B**; C only if boundary disputes become a requirement; decide separately whether organisation-custom units move to OrganisationStructure (which would reduce it to plain B) |
| **Prerequisite** | security A-3 and integrity I-4 / I-5 first |
| **Decision** | ☐ A ☐ modified B ☐ C · organisation-custom units: ☐ stay in Geography ☐ move to OrganisationStructure |

### B-11 · Membership owner approvals (the ratified kernel is affected)

| | |
|---|---|
| **Items** | (1) `admissions:o` ancestor token locked and bumped in the creating transaction, covering `create()` (M-09, E-3). (2) A unit-of-work / acquisition port, so the transaction can include foreign token locks (M-08, E-5). (3) `AuthorityCheckPort` extension G-AUTH-1 (subject, decision reference, as-of; evidence fields) with authority decided inside the transaction (M-07). (4) `knownAtVersion` (M-10). (5) An append-only trigger on `org_membership_facts` (M-11) |
| **Nature** | infrastructure and port changes; **no change to a ratified domain rule** |
| **Decision** | per item ☐ approve ☐ reject: (1) __ (2) __ (3) __ (4) __ (5) __ |

---

## Part C: Business questions (needed later; not needed for round 1)

| Question | Blocks |
|---|---|
| BQ-P1 (several positions per person), BQ-INC (scope of incompatibility) | Governance aggregates |
| BQ-P2 (membership required per position), BQ-P3 (tenure on membership end) | EffectiveTenure |
| BQ-DEL (re-delegation depth) | bounded footprints |
| BQ-J1 (jurisdiction overlap) | jurisdiction model |
| BD-SUSP / BD-TYPE (Suspend / Restore, ChangeType semantics) | Membership fact coverage |
| XD-3 (identity scope; PersonRef ↔ legacy `organisation_user_id`) | Membership cutover |
| BQ-F1…F3, BQ-P9, XD-8 | Forum |

---

## Part D: What each decision unblocks

| Decided | Unblocks |
|---|---|
| A + infrastructure DB | Phase 0 security containment (the highest priority) |
| I | Phase 1 legacy integrity |
| B-2, B-3, B-4, B-5, B-6, B-7, B-8 | Phase 3 contracts |
| + B-11 | Phase 4 Membership kernel infrastructure |
| + B-9 | Phase 5 VS-1 (test-only; "current members may post"; revocation = End) |
| B-1 + RD-1…RD-4 + BQ-P1 / BQ-INC | Phase 7 Governance |
| B-10 + BQ-J1 + I-4 / I-5 | Phase 8 Geography / Jurisdiction |

**Record of decisions:** each decided item should be copied into the decision register / ADR set with the decider, date, chosen option, rationale and conditions. This sheet stays the evidence pack.
