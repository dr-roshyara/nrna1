# Organisation and governance: strategic-design status assessment (consolidated register, T-10)

**Date:** 2026-11-02 · **Author:** Claude (DDD architect / requirements engineer; authority: generated)
**Status:** RESEARCH / REGISTER. It decides nothing and changes no status.
**Baseline:** branch `election-flow-updated`, HEAD `25170720c`. Committed in `a6b3f6ab8`.
**Reviewed:** 2026-11-02 at HEAD `532b598cd` (the commit after `a6b3f6ab8` is another session's KnowledgeOS commit, which touches no file cited here). The Principal Reviewer quality review led to these changes:
- R-6 class clarified;
- O-1 linked to its K7 brief;
- §4a dependency map added;
- §7 closure path added.

No status changed.
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0).
**Fulfils:** backlog T-10 of `2026-11-02-governance-domain-discovery-map-roadmap-and-readiness.md` §10.

**Method:**
- Every item cites the repository record it rests on, and the classification is taken from that record's own status line.
- **No percentages are used.** The figures in `architecture_legacy/how_far_we_are/20260708_1512_how_far_we_are.md` are a historical snapshot of a different scope.

**Classes:**
- **RATIFIED**: ratified or formally adopted;
- **APPROVED**: decided by the PO, with the ARB act still pending where stated;
- **ACCEPTED (other scope)**;
- **PROPOSED**: awaiting approval;
- **OPEN**: a strategic or business question;
- **GAP**: an implementation contradicts a ratified or approved rule;
- **VERIFY**: evidence is missing.

---

## 0. Scope

**In scope:** the organisation and governance domain:
- Identity (Person, Account);
- Organisation (registry, affiliation);
- Membership;
- Governance (committee, position, appointment, tenure, jurisdiction);
- Geography;
- Authorisation;
- Communication (forum).

**Election appears only at its boundary.**

**Not in scope: EPIC-002.**
- EPIC-002 is **ACCEPTED** for its own scope ("complete for the current scope", ARB 2026-07-25; `docs/implementation/EPIC-003_Tactical_DDD_Entry_Assessment.md:193`).
- Its canonical context map holds five contexts: Collection & Aggregation, Record-Fixing, Custodial Integrity, Self-Verifying Integrity, Adjudication (`docs/implementation/EPIC-002_Canonical_Context_Map.md:54-64`). **None** of them is an organisation or governance context.
- The two scopes must coexist. The only shared point found so far is that Adjudication needs a source of issuing authority (`AuthorityAssertionPort`), which Governance would provide (v1.5 candidate).

## 1. RATIFIED / adopted (binding)

| # | Item | Content | Source |
|---|---|---|---|
| R-1 | Membership architecture | one Membership per (Person, Organisation); append-only facts; periods; Identity owns person equivalence | `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md:41-43,54,124`; act `2026-10-08-membership-architecture-ratification-act.md` |
| R-2 | Context boundaries | "Election owns entitlement and eligibility; … Governance owns competence; Identity owns person equivalence. **There is no shared persistence and no Membership → entitlement shortcut.**" | `2026-10-08-membership-final-ratification-verdict.md:46` |
| R-3 | Aggregate boundary | the Membership aggregate must not absorb committee participation | act §10 `:283-304` |
| R-4 | Vocabulary | EM-VOC-001 (Member aggregate), EM-VOC-002 (affiliation ≠ Membership) | ratified set |
| R-5 | Anonymity | ADR-T11: no voter ↔ vote linkage | `docs/adr/ADR-T-LOG-Tactical-Implementation.md:18` |
| R-6 | Working Organisation | B1 (bootstrap PublicDigit, sign-in routing, one Working Organisation per request) and B10, **approved by the PO 2026-08-06**. Binding as a **PO approval, not an ARB act**; listed here because it is binding (the summary record lists it as APPROVED). | `docs/publicdigit/backlog/PBDIGIT-30-active-organisation-business-lifecycle-discovery.md` |
| R-7 | ADR-0001 | committee write model in `app/Contexts/Membership/Domain/Committee` (**Accepted**, March 2025). Its relation to R-3 is open (see O-6). | `docs/adr/ADR-0001-committee-read-write-separation.md:20-21` |

## 2. APPROVED by the PO (some ARB acts pending)

| # | Item | Status | Source |
|---|---|---|---|
| A-1 | INV-ORG-SCOPE-1 (Owning Organisation = Working Organisation; fail closed; platform-level clause) | **principle approved 2026-11-01; ARB ratification pending** | `2026-11-01-organisation-inv-org-scope-1-…-decision-record.md` |
| A-2 | BR-1…BR-8, BR-13, BR-13a, BR-13-S, BR-14 | confirmed by the PO | `docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md` |
| A-3 | BR-10, BR-11, BR-12 | PO direction (BR-12 needs the B1 amendment, O-1) | same |
| A-4 | D-1 tenancy direction: option C as **candidate** | conditional on validation (U-1…U-5) and the ARB (INV-AFF-1 etc.) | `2026-11-02-organisation-xd15-tenancy-and-data-ownership-decision-proposal.md` §8a |
| A-5 | Membership–Election: option A as the first milestone | decided; the plan is accepted only as PROPOSED | `2026-11-02-membership-election-separation-decision-record.md` |
| A-6 | PBDIGIT-318 S1 (static guards, baseline V1, V1b, V2, V3′) | approved and **implemented** (`17ef51c5e`); **awaiting acceptance** | plan `docs/plans/20261102-0339-…-plan.md` |
| A-7 | PBDIGIT-317 triage | authorised; first step for the PO / Ops | `docs/publicdigit/backlog/PBDIGIT-317-…md` |

## 3. PROPOSED (awaiting approval)

| # | Item | Pending with | Source |
|---|---|---|---|
| P-1 | XD-3a: one platform Person; preferred direction a per-organisation `PersonRef` | PO + ARB + data protection | `2026-11-01-identity-xd3a-…-decision-record.md` |
| P-2 | XD-3b: Person ≠ Account (B2); personal data organisation-local; claim-code linking | ARB | `…-xd3b-…` |
| P-3 | XD-3c: account lifecycle (option C) | PO + ARB | `…-xd3c-…` |
| P-4 | Decision C: PublicDigit default organisation, User role | PO + ARB; the conflict with R-6 was **resolved by K7 (K′)**, so B1 stands | `2026-11-01-organisation-dc-…-decision-record.md` |
| P-5 | INV-AFF-1: organisation-owned affiliation, discovery without a register | **referred to the ARB**; feasibility not established | `2026-11-01-organisation-inv-aff-1-…`; evaluation `2026-11-01-identity-affiliation-discovery-evaluation.md` |
| P-6 | XD-PROV, with AM-1 r2 (deletion by confirmed request) | PO + ARB | `2026-11-02-identity-xd-prov-…-decision-record.md` |
| P-7 | Governance vocabulary (Committee as organ, Position, Appointment, Tenure, Mandate, JurisdictionAssignment, Forum) | ARB (CANDIDATE) | `…/cross_domain_brainstorming/20261011_0008_cross_domain_discovery_package_v1.5.md:60-100` |
| P-8 | Candidate contexts, context map, invariants G-1…G-9 | ARB | `2026-11-02-governance-domain-discovery-map-roadmap-and-readiness.md` §4–§7 |

## 4. OPEN strategic / business questions (by consequence)

| # | Question | Owner | Blocks | Source |
|---|---|---|---|---|
| O-1 | ~~**K7:** amend B1 (PublicDigit a real organisation; wording "B1′") or keep it~~ **RESOLVED 2026-11-02, PO: K′.** B1 stands; the real organisational life is in a separate operator organisation; BR-12 reworded. Follow-ups: naming (PO); DC-R9 placement (D-1 / ARB); read-only check of real data in the platform organisation (VERIFY, needs authorisation) | PO | P-4, BR-12, PBDIGIT-32 scope (unaffected) | Decision C §2a; **brief** `2026-11-02-organisation-k7-b1-amendment-decision-brief.md` |
| O-2 | B1.5 vs B1.3: enter a remembered Working Organisation without counting? | PO | INV-AFF-1 cost | evaluation OQ-E-2 |
| O-3 | Scale figures U-1…U-5 | PO + Ops | A-4 validation, P-5 feasibility | Decision D §11 |
| O-4 | Isolated test database | PO / Ops | every database test; S2–S4 | ARB-SEC-01 (gate FAIL); T-13 |
| O-5 | INV-ORG-SCOPE-1 §1a: deletion-eligibility check not a permitted platform operation | PO + ARB | P-6 | BR-13-S |
| O-6 | **DR-07:** committee ownership, the reading of R-3 (aggregate vs context), ADR-0001. **OPEN; decision paper prepared 2026-11-02** (`2026-11-02-governance-dr07-committee-ownership-decision-paper.md`, recommends A with B cutover). Premise question OQ-DR07-0 (are committees organs?) goes to the PO | PO + ARB + Membership owner | Governance contracts, F, G, H | decision sheet `2026-10-11-…:51-61`; paper |
| O-7 | XD-4 authorisation ownership; XD-5 geography history; XD-12 write guard | ARB | H, G | decision sheet |
| O-8 | BQ-P1 / CQ-7: which positions are incompatible within an organisation | PO | Governance invariants | v1.5 `:344`; committee discovery `:195` |
| O-9 | BQ-P2 / P3: Membership ↔ tenure | PO | EffectiveTenure | v1.5 `:345-346` |
| O-10 | CQ-2 / 3 / 4 / 10: committee authority and statutes | PO | business-authority matrix | committee discovery `:189-198` |
| O-11 | BQ-J1 jurisdiction exclusivity; official vs custom geography (OQ-OS-1); level scale (C10) | PO + ARB | G | v1.5 `:350`; INV-ORG-SCOPE-1 record |
| O-12 | OQ-ME-1…4: Election reaction to membership end; V3′ retention; foreign keys vs "shared persistence"; contract semantics | Election owner, PO, ARB | S2–S4 | decision record (Membership–Election) §4 |
| O-13 | OQ-PROV-1…9; OQ-3b-2 / 3 / 4; OQ-3a-1 / 2 (legal basis, separate identities) | PO + legal + ARB | P-1, P-2, P-6 | the respective records |
| O-14 | Vocabulary split: member / role / observer / ElectionMembership (T-04) | ARB | the context map | governance map §2 |
| O-15 | Forum product choice; BQ-F1…F3, BQ-P9 | PO | Communication | v1.5 `:352-355` |

## 4a. Decision dependency map (blockers only)

This map adds evidence, next action and hold-backs to §4 and §2. **It does not duplicate their sources:** the source of each item is its row there.

| Item | Decision required | Owner | Status | Evidence needed | Next action | Must not proceed before it |
|---|---|---|---|---|---|---|
| O-1 K7 | keep B1, amend to B1′, or K′ (separate operator organisation) | PO | **RESOLVED 2026-11-02: K′** | a read-only check of real data in the platform organisation (authorisation needed) | creating the operator organisation is **not authorised**; it needs its own story | any data move or code change for the operator organisation |
| O-6 DR-07 | committee ownership; reading of R-3 (aggregate vs context) against R-7 | PO + ARB | OPEN | gathered in the paper (§3–§4) | **PO answers paper §13**, then Membership owner, then ARB | Governance contracts; the Membership ↔ Governance relationship (T6); O-8…O-10 modelling |
| O-7 XD-4 | who owns authorisation decisions | ARB | OPEN | permission model inventory (v1.5 §2) | ARB agenda | the authorisation contract; G-5 remediation design |
| O-7 XD-5 | geography history and jurisdiction | ARB | OPEN | O-11 answers | ARB agenda, after O-11 | Geography context boundary |
| O-8 | which positions are incompatible within an organisation | PO | OPEN (scope fixed by BR-14) | statutes | PO, with statutes | Governance invariants |
| O-9 | Membership ↔ tenure | PO | OPEN | statutes | PO | EffectiveTenure; Membership ↔ Governance contract |
| O-10 | committee authority, statutes | PO | OPEN | statutes (not in the corpus; not yet requested, T-08) | PO supplies statutes | the business-authority matrix (T-08) |
| O-14 | vocabulary split | ARB | OPEN | governance map §2 conflicts | ARB agenda | **declaring the context map final** |
| A-1 ARB act | ratify INV-ORG-SCOPE-1, including O-5 | ARB | APPROVED by PO; ARB pending | Stage 1 investigation | ARB agenda | Stage 2 (G-5) design or tests |
| P-5 ARB act | INV-AFF-1 | ARB | PROPOSED; referred | V-6 (needs O-3) | ARB agenda | A-4 tenancy validation; D-1 final decision |
| O-12 / A-6 | S1 acceptance; OQ-ME-1…4 | PO / reviewer; Election owner; ARB | S1 implemented, **not accepted**; OQ-ME open | review of `17ef51c5e` | PO / reviewer accepts S1 | S2 (also: MemberTest change approval, O-4), S3 (contract semantics, Membership-owner agreement), S4 (V-1, OQ-ME-2/3) |
| O-3 | U-1…U-5 | PO + Ops | OPEN | the figures | PO / Ops supply | A-4 validation; V-6 |
| O-4 | isolated test database | PO / Ops | OPEN (ARB-SEC-01 FAIL) | database assigned | PO / Ops | any database-touching test (S2–S4, V-5) |
| PBDIGIT-317 | whether secrets were written and exposed | PO / Ops + Security | **investigation, not a confirmed incident** | V-3 log channel and access | PO / Ops read-only log-access check | nothing waits on strategic design; **independent** |

## 5. GAP: implementation contradicts a ratified or approved rule

| # | Rule | Gap | Source | Path to closure |
|---|---|---|---|---|
| G-1 | R-2 | V1: `Member::endMembership` writes `election_memberships` | `app/Models/Member.php:184-189` | S2 |
| G-2 | R-2 (entitlement shortcut) | V1b: `Member::canVoteInElection` | `app/Models/Member.php:142-149` | S2 |
| G-3 | R-2 | V2: the Election adapter reads Membership / affiliation tables | `app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php` | S3 |
| G-4 | R-2 | V3′: `voters.member_id` → `members` ON DELETE CASCADE | `database/migrations/2026_03_07_145529_create_voters_table.php:30` | S4 |
| G-5 | A-1 | missing context silently substituted; 379 bypass sites (18 potential defects); cross-organisation role cache; unvalidated `X-Tenant-Id` | `2026-11-02-inv-org-scope-1-stage1-static-investigation.md`; PBDIGIT-266 items 5–11; PBDIGIT-309 | Stage 2 (not authorised) |
| G-6 | R-6 / A-2 | registration creates role `member` instead of User; non-atomic; Google sign-up creates no affiliation (F-C-2…F-C-5) | Decision C §6 | not planned |

**Gap against accepted ADR-0001 (R-7), found by the DR-07 paper §4:**
- **G-7:** a second committee-membership write path in Governance (`POST` / `DELETE api/v1/governance/committees/{id}/members`). Its aggregate is loaded blank (`app/Contexts/Governance/Infrastructure/Repositories/EloquentCommitteeRepository.php:31-48`), so two contexts write committee membership through different stores.
- Production use is unverified.
- Remediation: not planned; follows DR-07.

**Defects against proposed (not ratified) rules, recorded separately:**
- the committee repository rewrites ended assignment dates (`app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:142-149`), against candidate invariant G-9;
- role limits are never enforced (C11);
- three assignment stores (C4).

**Security, independent:** PBDIGIT-317 (secrets written to the log).

## 6. VERIFY: evidence missing

| # | What | How | Owner |
|---|---|---|---|
| V-1 | deployed foreign keys and indexes (V3′; C11 unique indexes) | read-only catalog query | PO / Ops authorisation |
| V-2 | production platform-organisation slug (fallback path a or b) | read-only query | Ops (PBDIGIT-309) |
| V-3 | `LOG_CHANNEL` override; log access (PBDIGIT-317) | environment check | PO / Ops |
| V-4 | S1 acceptance | review of `17ef51c5e` | PO / reviewer |
| V-5 | PBDIGIT-266 items (static findings) | feature tests | needs O-4 |
| V-6 | INV-AFF-1 discovery at the target scale | the evaluation with U-1…U-5 | after O-3 |
| V-7 | real organisational data in the production platform organisation (before any operator-organisation creation or migration; K7) | read-only queries Q0–Q3, K7 brief §7b | PO / Ops authorisation |

## 7. Exit criteria for the organisational strategic design

| Criterion | Evidence today | State | To close | Closed by (owner) |
|---|---|---|---|---|
| Each context has a defined responsibility, authority and boundary | Membership ratified (R-1, R-2); others candidate (P-7, P-8); Governance owner open (O-6) | **not met** | O-6, O-7; ARB adoption of P-8 contexts | ARB act recorded |
| Core vocabulary unambiguous | member / role / observer overloaded (O-14) | **not met** | O-14 | ARB act recorded |
| Business rules have explicit scope | BR-13 / 13a / 13-S / 14 (A-2); incompatibility within an organisation open (O-8) | **partly met** | O-1, O-8, O-9, O-10 | PO decisions recorded |
| Context relationships specified (owner, decider, exchange, failure, scope, deletion) | Membership ↔ Election specified as a boundary (A-5); Membership ↔ Governance cycle found, unspecified (governance map §5) | **not met** | T6 relationship specifications, starting with Membership ↔ Governance (after O-6); OQ-ME-4 contract semantics | ARB review of the specifications |
| No context makes another's decision | ratified (R-2); S1 **guards against new** Membership ↔ Election violations; existing gaps G-1…G-4 remain at runtime | **partly met** (rule set; gaps tracked) | S1 acceptance; S2–S4 each approved and accepted | PO / reviewer per slice |
| Assumptions visible with owners | this register | **met** | — | — |
| Decisions distinguishable by status | this register | **met** | keep at every session close | each session |
| Implementation implications recorded | §5, PBDIGIT-266 / 309 / 318 | **met** | — | — |
| Reversal conditions documented | Decision D §4a; individual records | **partly met** | reversal conditions for P-1…P-6 when each is decided | the deciding authority |

**Four separate claims; do not merge them:**
1. S1 **implemented** (`17ef51c5e`).
2. S1 **accepted** (not yet).
3. Runtime boundary **corrected** (G-1…G-4 closed by S2–S4; not yet).
4. Strategic design **complete** (this section; not yet).

**Conclusion:** the organisational strategic design is **not complete**.

**The blocking decisions for completion:**
- ~~O-1 (K7)~~ resolved 2026-11-02 (K′);
- O-6 (DR-07);
- O-7 (XD-4 / XD-5);
- O-8, O-9, O-10 (governance business rules);
- O-14 (vocabulary);
- the ARB acts on A-1 and P-5.

O-3 / O-4 block validation and testing, not the model.

## 8. Traceability

All records cited inline. Previous summaries:
- governance map §1, §9, §10;
- session log `.claude/sessions/2026-11-02.md`.
