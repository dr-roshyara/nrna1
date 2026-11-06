# ODA-01 v1.1: Organisation domain architecture baseline (corrected)

> **This document is discovery and conceptual design. It approves, ratifies and authorises nothing, and it is not a ratified architecture decision.** Every [P] item is a proposal. Every Principal / PO and ARB question in it remains open.

| | |
|---|---|
| **Version** | **v1.1**, a controlled correction of v1.0 under commission ODA-02 |
| **Supersedes (for review purposes)** | `2026-11-03-organisation-domain-architecture-baseline-oda-01.md` (**v1.0**), sha256 `4617c57116064c2a10dd15bee41558a90f892bb2a63349a18f609895c6d9c740`. v1.0 is **preserved unchanged**. It is untracked in git, so this hash is its only fixed reference |
| **Correction basis** | IR-ODA-01, `2026-11-03-ir-oda-01-independent-review.md`, sha256 `4fe75edfa806d2df6d4c0496b0b85bec944d9900b0b96a513565f092fc9b3c01` (findings K-1…K-14, C-11…C-14, M-1…M-17, E-1…E-6) |
| **Status** | **CORRECTED CONCEPTUAL BASELINE, FOR INDEPENDENT REVIEW** by a human or a different-model reviewer. Not approved |
| **Author** | Claude, the author of v1.0. **This is a correction by the original author. It is not an independent review and not a self-approval** |
| **Date** | 2026-11-03 |
| **Snapshot** | branch `election-flow-updated`, HEAD `f826d275b`. The commits since v1.0's snapshot `821a3bb29` touch no `app/`, `database/`, `routes/` or `docs/publicdigit/` (IR-ODA-01 header, re-checked) |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels.**
- **[F]** verified fact, with source;
- **[D]** decision, with its authority and recording status;
- **[P]** proposal;
- **[I]** inference or judgement;
- **[OQ]** open question;
- **[R]** risk.

**Paths.**
- R/ = `docs/publicdigit/reviews/`
- AR/ = `docs/publicdigit/architecture/reviews/`
- RAT = R/`2026-10-08-membership-architecture-ratified.md`
- ACT = R/`2026-10-08-membership-architecture-ratification-act.md`
- DR07 = R/`2026-11-02-governance-dr07-committee-ownership-decision-paper.md`
- GMAP = R/`2026-11-02-governance-domain-discovery-map-roadmap-and-readiness.md`
- CMAP = `docs/publicdigit/architecture/context-map.md`
- PRI = `docs/publicdigit/architecture/platform-rules-index.md`
- BR = `docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md`
- PREP = AR/`2026-11-03-organisation-membership-constitutional-authority-discovery-preparation.md`
- BDR = `docs/architecture/design/Round49-06_Boundary_Decision_Register.md`
- IR = IR-ODA-01

---

## 0. Change control

### 0.1 Summary of changes from v1.0 to v1.1

| Area | v1.0 | v1.1 |
|---|---|---|
| Definition (P-ODA-1) | two different versions (§0 vs §3.1); "that PublicDigit recognises"; tenant sentence inside the definition | **one** candidate wording, taken from IR §C.5 as a **candidate** (not approved); no platform recognition, no tenancy; adds external rules, leadership and the exclusion of platform records (§3) |
| Constitution placement | recommended O-2 on an unbalanced comparison | **no recommendation.** Balanced comparison; the four-way separation of source, interpretation, authority evidence and operational rule; citation `(document, article, version)`; the ARB hinge question (§5) |
| Lifecycle | `governance_status` called a platform service status; fail closed called "consistent with D-4" | `governance_status` described from code (three meanings, owner-driven); **the tension with D-4 stated explicitly, and left with the PO** (§3.3, §5.2) |
| Strategic classification | "Organisation foundational supporting; Governance and Membership core candidates" | **provisional only**; no core candidates named; the four conflicting classifications recorded, not reconciled (§11.1) |
| Organisation context scope | six-part scope including affiliation and enablement | scope recorded as **not shown to be coherent**; the review's "grab-bag" concern and its smallest candidate recorded as input for the ARB (§11.2) |
| Context map | §12 drawn as a map | §12 removed as a map; relationships kept as **input to CMAP change control** (§12) |
| Geography | three concepts; Nepal and worldwide factories | four concepts plus **electoral areas**; factory and range facts corrected; scope options without a choice (§8) |
| Facts | K-1…K-14 errors | corrected (§0.2) |
| Conflicts | C-1…C-10 | C-1…C-14, with corrected citations (§10) |
| Decision register | OQ-ODA-1…13 | IR's M-1…M-17 and E-1…E-6 adopted as the register; v1.0 OQ-ODA items mapped (§15) |
| Questionnaire | blocks O and X | proposed improvements recorded (unapproved) (§14) |

**Unchanged in substance:**
- the core separations (Organisation ≠ Membership ≠ Governance ≠ Election ≠ Tenant; unit ≠ organ; GeoUnit ≠ jurisdiction; access role ≠ constitutional authority);
- C-1;
- BR-13a handling;
- the reference-organisation sequence.

### 0.2 Correction register (K-1…K-14)

Every finding was re-verified at its source by the author of this correction. None is marked UNVERIFIED.

| Finding | Original statement (v1.0) | Review finding | Source verification | Correction (v1.1) | Classification | Affected sections | Remaining uncertainty |
|---|---|---|---|---|---|---|---|
| **K-1** | `geographic_levels` among the fields immutable after creation (§2.3) | not in the immutable list | [F] immutable list `Organisation.php:61-67` = `committee_structure`, `geographic_scope`, `allowed_countries`, `base_country_code`, `base_region_id`. The guard is an Eloquent `updating` event (`:59-75`), not a schema constraint | list corrected; "frozen at creation" narrowed; noted as a model convention | fact | §2.3, §3.3, C-5, R-10 | none |
| **K-2** | worldwide alternative = "Worldwide, Region, Country" | four levels; never called; three archetype profiles exist | [F] `GeographicStructure.php:25-33`; no caller in `app/` (grep). [F] `TenantGeographyProfile::forDiaspora / forNepalParty / forGermanyNGO` (`app/Contexts/Geography/Domain/Models/TenantGeographyProfile.php:63, 76, 89`) | corrected; the worldwide factory is "defined, unused"; the archetypes are recorded as an overfitting risk | fact | §2.3, §8, §14, R-17 | why the archetypes exist is not established |
| **K-3** | `admin_level` 1–4 | widened to 0–10 | [F] `…/Geography/Infrastructure/Database/Migrations/Landlord/2026_01_14_000003_extend_level_range_add_continents.php:33-34` (`CHECK (admin_level BETWEEN 0 AND 10)`); the original column comment says 1–4 (`…/2025_01_01_000002…:28`) | corrected | fact | §2.3, §8 | none |
| **K-4** | `governance_status` is a "platform service status" overloaded with a Governance gate | owner-driven; three meanings; no writer for `active` or `suspended` | [F] the only write is `'governance_configured'` (`app/Http/Controllers/GovernanceSetupController.php:102`). Setup and activation are gated to the **owner** access role (`app/Policies/OrganisationPolicy.php:62-84`). An `activateGovernance` policy exists (`:77-84`), but no code writes `active` (grep `app`, `routes`, `database/seeders`). The status also drives the committee structure epoch (`app/Contexts/Membership/Domain/Committee/Context/CommitteeStructureEpochContext.php:31`) and the committee-creation gate (`OrganisationGovernanceStatus.php:14-16`) | redescribed: an owner-driven **governance setup state** with three meanings (setup progress, committee-creation gate, structure epoch). "Platform service status" is withdrawn as a fact | fact; the meaning is inference | §2.3, §3.3, C-7 | how `active` is reached in production is not established (possibly by data change outside the code) |
| **K-5** | "[F] `committees` models both units and organs" | this is the Principal's position, not a fact | [F] one table with types (`…/Membership/…/Tenant/2026_01_16_000002_create_committees_table.php:41-73`). [D] Principal's position "BOTH" (DR07 `:224`). [D] PO-DR07-01: records are "not classified" (DR07 `:267`) | relabelled: [F] one table with unit-like and organ-like types; [D] the Principal's position that it represents two concepts; [I] the reading | decision (Principal position) + inference | §2.3, §6.1, C-6, R-2 | classification of records: O-16 |
| **K-6** | C-6: committees in Membership are "contrary to ACT §10" | ACT §10 is an aggregate boundary; reading it as a context rule is ARB-1 (pending) | [F] ACT `:283-305` (aggregate boundary); DR07 `:312` (ARB-1 asks the ARB to confirm the aggregate reading); context-level basis ACT `:321` plus recorder's note N-6 (ACT `:19`), which does not amend the act | C-6 re-cited to ACT §11 + N-6; ARB-1 left open | decision citations | §10 C-6 | ARB-1 |
| **K-7** | "ratified Membership handles merger collisions by renumbering" | the source is pre-ratification analysis; the ratified set covers person merges only | [F] R/`2026-10-08-membership-architecture-ratification-readiness.md:277` (readiness review); [F] ratified set: person merge only (R/`2026-10-08-membership-final-decision-register.md:27`; R/`2026-10-08-membership-final-proof.md:68`) | downgraded to [P] pre-ratification analysis | proposal | §9 | organisation merger semantics: open |
| **K-8** | "geography-based" appears only in brainstorming | the cited text says "geography-based jurisdiction"; the platform phrase is in a legacy AI brief | [F] `…/cross_domain_brainstorming/20261011_0935_analysis_report.md:606`; [F] `architecture_legacy/committee/geo_committe_formation/20260513_1257_geo_unit_matrix.md:5-12` ("geography-based committee governance platform for NRNA", with Province → District → Municipality → Ward) | corrected; the legacy brief that pairs NRNA with Nepal's domestic hierarchy is recorded | fact | §8 | none |
| **K-9** | "Elections = core"; no existing classification | four inconsistent classifications exist | [F] AR/`2026-11-03-publicdigit-architecture-assessment.md:104` (AIP-14 Election system core; BDR-08 Election Lifecycle supporting; 2026-10-09 review ranks Election / Voting / Appointment "Supporting"); [F] BDR `:21` (BDR-08), `:57` ("Adjudication+Contestation are the genuine greenfield Core") | "Elections = core" withdrawn; conflict C-12 recorded, not reconciled | fact (the conflict) | §11.1, C-12 | reconciliation: PO + ARB |
| **K-10** | "Absent: statutes, constitution versions" | article-reference value objects and authority / delegation classes exist | [F] `app/Contexts/Governance/Domain/ValueObjects/ConstitutionalBasis.php:9-25` (article + optional text, no version); `app/Contexts/Membership/Domain/Committee/Constitutional/Doctrine/ConstitutionalArticleReference.php`; `app/Contexts/Governance/Domain/Authority/AuthorityAssignment.php`, `ValueObjects/DelegationScope.php`, `Events/AuthorityDelegated.php` | added as **existing precedents** placing constitutional citations and delegation in Governance code; no statute document or version store exists | fact | §2.3, §5.3, §6.2 | precedent weight (legacy) for the ARB |
| **K-11** | PO-ORG-MEM-01 A labelled [D] and argued from its wording ("its Constitution") | no decision record; two candidate wordings; the approved one is not recorded | [F] DR-07a report `:9`, `:330`; PREP `:47-61` (two wordings plus "combined wording") | kept as [D] **approved by the Principal / PO** (ODA-01 commission §3 item 12; Principal's message after IR-ODA-01), **not in a decision record; exact wording unrecorded**. No argument in v1.1 rests on its wording | decision (content); open (wording) | §2.1, §5.3 | M-1 |
| **K-12** | only Mandate flagged to ARB-8 | BDR-03 names an "Appointment (Authority)" context; Appointment and Tenure depend on ARB-8 too | [F] BDR `:16`; CMAP `:79-80` ("whether this is the same context as Governance: UNRESOLVED") | Appointment, Tenure and Mandate all marked [U] pending ARB-8 | open question | §6.2, §11.2, §13 | ARB-8 |
| **K-13** | P-ODA-1 stated twice, differently | internal inconsistency; §3.1 adds tenancy | [F] v1.0 §0 item 3 vs §3.1 | one candidate wording (§3.1) | proposal | §0, §3 | M-2 |
| **K-14** | `policyVersion` identifies a constitutional version; "one retention point" argument for O-2 | RAT fixes field names, not referents | [F] RAT `:72` lists `ruleRef`, `policyVersion` inside authority evidence and rule provenance `(ref, version)` without defining their referents. PREP §8.3 is itself [P] | relabelled [I]; the citation proposal becomes `(document, article, version)`; the O-2 "retention" argument withdrawn as a deciding argument | inference | §5 | M-12 |

### 0.3 Author's notes on the review (recorded, not acted on)

- **K-11.** The reviewer did not have the ODA-01 commission text (IR §B.3). That text and the Principal's later message both state that PO-ORG-MEM-01 was approved. v1.1 therefore keeps the content as [D] but treats its exact wording as unrecorded. This is a note for the reviewer and the PO, not a rejection of K-11.
- **New conflicts.** The review's C-11…C-14 are adopted into §10 as recorded conflicts. They are not resolved.

---

## 1. Repository and governance state

| Item | State |
|---|---|
| Branch / HEAD | [F] `election-flow-updated` / `f826d275b` |
| Working tree (not touched) | [F] modified by other sessions: `.claude/CONTEXT-publicdigit.md`, `.claude/sessions/*` (2026-10-01, -07, -08, -10, -11, -15, 2026-11-03), `.claude/settings.local.json`, `bootstrap/cache/packages.php`, `developer_guide/election/ip_voting/{00,06,07,08}_*.md`, a KnowledgeOS `START-TIMES.tsv`. Untracked: other AR/ 2026-11-03 documents (including `…-ir-sec-01-independent-review.md`, `…-po-security-triage-pbdigit-266-r1-r2.md`), R/`2026-11-03-membership-cor3-decision-brief-cq3-cq9-ga1.md`, brainstorming folders, `platform-rules-index.md`, `tests/Isolated/Pbdigit274/…`, a stray `C:\Users\…\debug_response.txt`, KnowledgeOS scratch |
| PO-ORG-MEM-01 A / B | [D] approved by the Principal / PO, 2026-11-03; **not entered in a decision record; exact wording not recorded** (K-11; M-1) |

---

## 2. What was previously known

### 2.1 Binding decisions (unchanged from v1.0 except where marked)

| ID | Content | Status | Source |
|---|---|---|---|
| D-ACT10 | the Membership **aggregate** must not absorb "Governance competence … committee participation; unrelated organisation structures" | RATIFIED (aggregate boundary; a context reading would be ARB-1, pending) | ACT `:283-305`; DR07 `:312` |
| D-ACT11 | Identity, Membership, Governance ("competence, organs, authority and organisational governance rules"), Election, Finance, Audit; "No shared persistence is required" | RATIFIED | ACT `:309-349` |
| D-RAT1 | Organisation owns OrganisationAffiliation; one Membership per (Person, Organisation) | RATIFIED (whether "Organisation" means the business owner or a context: BR-9 / OQ-C-5) | RAT `:42-43` |
| D-RAT8 | Governance supplies AuthorityCheck; Membership stores evidence only | RATIFIED | RAT `:156` |
| D-RAT-I5 | units and similar "map to a type, a provenance, another relationship or another organisation" | RATIFIED | RAT `:150` |
| PO-DR07-01 | governance committees are organs "with positions, appointments and governance authority"; units distinct; records not classified | PO business decision, 2026-11-02 | DR07 `:261-291` |
| Principal position (DR-07) | "BOTH": committee code represents organs and units | Principal architectural position, recorded 2026-11-02 | DR07 `:222-224` |
| PO-ORG-MEM-01 A / B | business ownership of Membership by the Organisation; DR-07a authorised | **approved, not recorded; wording open** (K-11) | PREP §3.1; DR-07a report `:9` |
| D-4 (interim) | interim authority mirrors owner / admin; "interim and replaceable by the Governance-owned implementation" | PO DECIDED (interim) | PREP `:299` |
| BR-4, BR-12 (K′), BR-13, BR-13-S, **BR-13a**, BR-14 | as in v1.0 | PO, 2026-11-02 | BR `:26-40` |
| PBDIGIT-30 B1 | Working Organisation routing | APPROVED | `docs/publicdigit/business_rules/20260806_0818_how_many_organisation.md:61-75, 145-153` |
| INV-ORG-SCOPE-1 | owning = Working Organisation; fail closed | APPROVED principle; ARB pending | CMAP `:89`, `:121` |
| BDR-03 | "Appointment (Authority)" owns Mandate truth; relation to Governance unresolved | FROZEN programme; ARB-8 | BDR `:16`; CMAP `:79-80` |
| ADR-004 | Organisation, **and Membership**, listed as Candidate | Accepted | ADR-004 `:30`, `:32` (C-11) |

### 2.2 Proposals on record

Unchanged from v1.0 §2.2. These are the Organisation and Geography context candidates, the Governance v1.5 vocabulary, "two hierarchies", tenancy option C, the qualified term "Organisation Constitution (statutes)" and the PREP questionnaire. Brainstorming remains non-authoritative.

### 2.3 What the code does today (corrected)

| Concern | Fact | Source |
|---|---|---|
| Organisation model | one Eloquent class; SoftDeletes; UUID; slug route key | [F] `app/Models/Organisation.php:10-15`, `:54-57` |
| Identity fields | `id`, `name`, unique `slug`, `email`, `address`, `representative` | [F] `database/migrations/2026_03_05_000001_create_uuid_organisations_table.php:14-25` |
| Platform record | `type enum('platform','tenant')`, `is_default`; the bootstrap organisation is a `type = 'platform'` row | [F] same, `:17-18`, seed `:44-60`; [D] BR-12 (BR `:35`) |
| Capability switch | `uses_full_membership` (default true); also selects the election voter source | [F] `2026_04_15_120000_…:19`; `app/Domain/Election/Enum/VoterSourceStrategy.php:18-28` |
| Structure and geography choices | `committee_structure`, `geographic_scope`, `allowed_countries`, `base_country_code`, `base_region_id`: immutable after creation by an Eloquent `updating` guard. **`geographic_levels` is not in that list** (K-1) | [F] `Organisation.php:59-75`; `2026_05_04_000001_…`; `2026_05_05_000002_…` |
| Governance setup state | `governance_status` ∈ pending_setup, governance_configured, active, suspended. Written only to `governance_configured`, by the owner. Gates committee creation and the structure epoch. No writer of `active` or `suspended` found (K-4) | [F] see K-4 |
| Geography defaults | Nepal default used (`Organisation.php:236-239`); `forWorldwideOrg()` defined, four levels, **unused** (K-2); archetype profiles `forDiaspora`, `forNepalParty`, `forGermanyNGO` | [F] `GeographicStructure.php:15-33`; `TenantGeographyProfile.php:63-95` |
| Geography store | `geo_administrative_units`: `admin_level` CHECK 0–10 (K-3), `parent_id`, `valid_from` / `valid_to`; nullable `organisation_id` | [F] Landlord `2025_01_01_000002…:23-77`, `2026_01_14_000003…:33-34`; `database/migrations/2026_05_13_134529…:15-16` |
| Electoral areas | `posts.is_national_wide`, `posts.state_name` (free text); `users.region` | [F] `database/migrations/2026_03_05_000005_create_uuid_posts_table.php:16-17`; `app/Models/User.php:74` |
| Units and organs | one `committees` table with types such as ward / district / province / central / wings, `parent_committee_id`, inside the Membership context. That it represents two concepts is the **Principal's position** (K-5) | [F] Membership Tenant `2026_01_16_000002…:41-73`; [D] DR07 `:224` |
| Constitutional and authority code | `ConstitutionalBasis` (article + text, no version); `ConstitutionalArticleReference`; Governance `AuthorityAssignment`, `DelegationScope`, `AuthorityDelegated` (K-10) | [F] see K-10 |
| Absent | legal form, registration number, founding date, a statute document or version store, organisation-to-organisation links | [F] evidence sweep |
| Current customer named | NRNA, "80+ countries" | [F] `docs/publicdigit/business_case/EXECUTIVE_SUMMARY.md:7`; `BUSINESS_CASE.md:129-134` |

---

## 3. Deliverable A: Organisation definition (corrected)

### 3.1 Candidate definition [P]

Taken from IR §C.5 **as a candidate**. It is not approved; the business meaning is the PO's decision (M-2), the vocabulary the ARB's (O-14).

> **Organisation.** A collective body with its own identity, purpose and rules, which decides its internal affairs through its own organs under its own constitution (statutes), within the law and any external rules that bind it.
> - Its identity persists through changes of name, structure, constitution, leadership and status. It does not depend on PublicDigit.
> - Membership, Governance, Elections and other capabilities serve the Organisation. None of them constitutes it.
> - A platform record (the bootstrap organisation, demo data) is not an Organisation in this sense.
> - How PublicDigit isolates an Organisation's data (tenancy) is not part of the definition.

**What changed from v1.0, and why:**

| Change | Reason | Consequence |
|---|---|---|
| "that PublicDigit recognises" removed | the Organisation exists before and after onboarding; the v1.0 wording contradicted its own lifecycle split (IR §C.3) | platform onboarding belongs to the platform relationship (§3.3) |
| tenant sentence removed | it pre-empted XD-15 and C-4 (IR §C.5) | tenancy stays a separate decision |
| "within the law and any external rules that bind it" added | parties, subordinate bodies and federation members fit without any federation design (IR §C.2) | fail-closed reasoning must allow authority sources other than the statutes (§5.2); questionnaire X2 becomes essential |
| "leadership" added | tenure history depends on it | — |
| platform-record exclusion added | the bootstrap row is not a self-governing body (C-14) | M-3 asks the PO to confirm |
| "self-governing … distinct party" replaced by "own identity, purpose and rules … own organs" | "distinct party" was undefined; BR-13 / BR-13a concern rules across organisations, not identity (IR §C.3) | — |

**Left open by the candidate:**
- [OQ] whether companies, foundations without members, unions or other collective bodies are in scope. The business case mentions corporate governance (`EXECUTIVE_SUMMARY.md:90`). This is not decided here.
- [OQ] whether an organisation **without written statutes** is in scope ("under its own constitution" may need to read "under its own rules, written or not").

### 3.2 Distinctions (unchanged in substance)

| Distinction | Assessment | Basis |
|---|---|---|
| Organisation ≠ Person, Membership, Election, Tenant | holds | [D] ACT §11; RAT §1 |
| Organisation ≠ Governance (the context) | holds; self-governance is a fact about the body, while the Governance context represents organs and competence | [D] ACT §11 |
| Organisation ≠ OrganisationAffiliation / platform access | holds | [D] BR-2 / BR-3, EM-VOC-002 |
| Organisational unit ≠ governance organ | holds | [D] PO-DR07-01 |
| Geographical area ≠ jurisdiction ≠ Organisation | holds | [P] §8 |
| Organisation ≠ platform record (bootstrap, demo) | **added** | [D] BR-12; [P] M-3 |
| A section **may** be a unit or a separate Organisation | open | [D] RAT `:150`; [OQ] E-1 |

### 3.3 Identity and lifecycle (corrected)

**Identity concepts.** Unchanged from v1.0 except as noted:
- OrganisationId is stable and never reused [P];
- name is a mutable attribute [P: whether history is required, PO];
- the slug is technical;
- legal identity is an optional external reference, 0..n;
- registration has two senses (external registry vs platform onboarding);
- `type` in code means platform vs tenant, not a kind of organisation.

**Three distinct things, not two** [I]:

| Concept | What it is | Owner | Evidence |
|---|---|---|---|
| **Organisational lifecycle** | the body's own existence: e.g. active, dormant, dissolved, succeeded | the Organisation, per its statutes and law | [P] states; no evidence yet (E-5) |
| **Platform service relationship** | PublicDigit's service to the Organisation: e.g. onboarding, in service, suspended by the platform, offboarded | Platform (placement: M-14) | [P] states; only SoftDeletes exists in code; "suspended by platform" has **no code evidence** |
| **Governance setup state** | today's `governance_status`: setup progress, committee-creation gate, structure epoch; owner-driven | today Membership / committee code; target unresolved | [F] K-4 |

**No lifecycle state is an established requirement.** The candidate states are inputs to evidence collection (questionnaire O3, O4), not model elements (R-11).

**Identity continuity** [P] (all proposals; none established):
- renaming, restructuring, constitutional amendment, change of leadership and dormancy do not change identity;
- dissolution: the v1.0 invariant "a dissolved organisation accepts no new decisions" is **withdrawn as unsupported**, because liquidation-phase decisions are common [I]. Evidence is needed (E-5);
- merger and succession: **an open question**. v1.0's "BR-13a applies" is withdrawn: BR-13a concerns cross-organisation governance functionality and shared rules (BR `:39`), not an organisation's own lifecycle events. Whether succession is a requirement is for the PO (M-10).

---

## 4. Deliverable B: conceptual model (corrected)

Changes from v1.0 §4:
- tenancy is out of the Organisation box;
- capability enablement and platform service status are moved out, pending M-14;
- organ scope is no longer "exactly one";
- appointments, tenure and mandate are marked [U ARB-8];
- the constitution placement is shown as open.

```text
                         Person [ID, E]
                            │
       ┌────────────────────┼──────────────────────────┐
       │ 0..1 per Org       │ 0..n per Org             │ holds (Appointment → Tenure) [U ARB-8]
       ▼                    ▼                          ▼
 Membership [MEM, E]   OrganisationAffiliation     Office / Position [GOV P; U whether offices
       │               [owner "Organisation" E;     │  exist outside organs: E-2]
       │                context / store U BR-9]     ▼
       │ of 1                                 Governance Organ [GOV, E PO-DR07-01]
       ▼                                            │ scope: the Organisation, one or several units,
 ┌──────────── ORGANISATION (business concept) ────┴─  or a function [P; cardinality E-2]
 │  own identity · purpose · rules · organs                           │
 │  organisational lifecycle [P]                                      │
 │  name, legal references (external) [P]                             │
 │  Organisation Constitution (statutes) ── versions [P]              │
 │       record placement: O-1 (GOV) or O-2 (ORG context) [U, M-11]   │
 │  Organisational Units 0..n [E distinct; ownership U O-16]          │
 └────────────────────────────────────────────────────────────────────┘
       │ scope of                          │ references
       ▼                                   ▼
 Election [ELE, E] ── electoral areas      GeoUnit [GEO, P]
   (free text today)  [OQ, §8]             ◄── operating area [P] · unit responsibility area [P]
                                           ◄── organ jurisdiction (JurisdictionAssignment) [GOV P]

 Outside the business concept:
   Platform service relationship · capability enablement · tenant · Working Organisation [PLT; M-14]
   Platform records (bootstrap, demo) [not Organisations; M-3]
```

Relationships and cardinalities are as in v1.0 §4.2, with these corrections:

| Relationship | v1.0 | v1.1 |
|---|---|---|
| Organ scope | "exactly one scope" (the whole Organisation or one unit) | [P] the whole Organisation, one or several units, or a functional scope; cardinality needs evidence (E-2) |
| Organ has Office / Position | 0..n, implying positions always belong to organs | [OQ] offices may also exist at Organisation level outside any organ (e.g. a General Secretary); E-2 |
| Person holds Position | GOV [P] | [P]; context placement **[U] ARB-8** (K-12) |
| Organisation adopts Constitution | 0..1 current, versions 0..n | unchanged [P]; plus [OQ] whether "the constitution" is one document or several (E-3) |
| Organisation related to Organisation | not modelled | unchanged (BR-13a) |

---

## 5. Constitutional foundation (corrected)

### 5.1 Terms

The v1.0 §5.1 terms are kept, with two added so that four concepts stay separate (IR §E.1 q10):

| Concept | Meaning [P] | Not to be confused with |
|---|---|---|
| **Constitutional source record** | the document(s), their versions, effective periods and adoption evidence | interpretation; derived rules |
| **Interpretation ruling** | a ruling by the body the statutes name on what a provision means (questionnaire D5) | the text; an operational rule |
| **Authority evidence** | the result of an authority check for one act {actor, capacity, ruleRef, policyVersion, result, checkedAt} | the competence itself (RAT `:72`, `:156`) |
| **Operational rule** | a capability's own rule derived from the source, citing it | the source; Governance's competence |

**Citation** [P]: every derived rule or decision cites **`(document, article, version)`**, not only `(article, version)`. Bylaws, rules of procedure and regulations are separate documents (IR §E.1 q7).
- [I] Whether `ruleRef` / `policyVersion` (RAT `:72`) are the fields that carry this citation is an interpretation, not a ratified meaning (K-14).
- Any change to the ratified evidence contract needs a Change Request (PREP OQ-12).

### 5.2 Answers (corrected)

Answers unchanged from v1.0 §5.2 except:

| Question | v1.1 answer |
|---|---|
| Can an Organisation exist and use PublicDigit before its statutes are recorded? | [F] **every current organisation does**: no statute is on file (PRI A86; XD-15 proposal `:138`). [D] D-4 interim lets owner / admin admit members (PREP `:299`) |
| Should competence-based decisions fail closed without a recorded source? | **Tension, stated explicitly; not resolved.** v1.0 said a fail-closed rule is "consistent with D-4". It is **in tension** with it. If no decision may claim constitutional competence until statutes are recorded, then either: (a) acts under D-4 are recorded as **interim, non-constitutional provenance** (D-4's own wording is "interim and replaceable"); or (b) such acts stop. The same applies to who calls elections in election-only organisations. **D-4 is not terminated, extended or redefined here.** PO decision M-4 |
| Other sources of authority | [I] law and external binding rules may also confer or limit authority (§3.1). A fail-closed rule must not assume the statutes are the only source |

### 5.3 Placement: O-1 and O-2 compared (no recommendation)

**What both options share** [I]:
- The **business** owner of the constitution is the Organisation (PO-ORG-MEM-01 A, wording unrecorded). Neither option changes that.
- **Context** placement is a separate question for the ARB. PREP itself says the business-ownership decision "concerns business ownership, not context ownership" (PREP `:67`). v1.0 argued from business ownership to context placement; **that argument is withdrawn.**
- Under both options, Governance decides amendment validity, interprets competence and checks authority (ACT §11; RAT `:156`). Under both options, each capability derives its own operational rules, citing `(document, article, version)`.
- **The options differ in one thing: which context holds the versioned source record.**

| | **O-1: Governance holds the source record** | **O-2: an Organisation context holds the source record** |
|---|---|---|
| Holds the record | Governance | Organisation context |
| Decides amendment validity, interprets competence, checks authority | Governance | Governance |
| Derives operational rules | each capability | each capability |
| Needs | one ARB reading: "organisational governance rules" (ACT `:321`) includes the source text | (a) an ARB act moving the Organisation context out of Candidate (ADR-004 `:32`); (b) a Governance → Organisation authority-evidence flow for amendments, which creates an Organisation ⇄ Governance cycle that must be recorded explicitly |
| For | no new context; matches existing code precedents that place article citations and delegation in Governance (K-10; weak, legacy) | a constitution also governs purpose, membership, finance and elections, so a record outside Governance serves non-governance consumers without routing through Governance |
| Against | Governance serves consumers whose rules it does not apply (a dependency concern, not an ownership one) | risk of **two owners of constitutional truth**: provisions on organs exist as text (ORG) and as interpreted rules (GOV). Acceptable only if ORG holds the text **as evidence only, with no interpretation and no derived rule** |
| ACT §11 preserved? | yes, if the ARB reads it to include the source text | yes, if the ARB reads it as the derived rules only |

**Not recommended either way in v1.1.** Recorded for the ARB [P, from IR §E.3]:
1. decide the four-way separation first, because it holds under either placement (M-12);
2. ask the one reading of ACT §11 (M-11);
3. place the record only after at least one real statute has been read (E-3: one document or several; how amendments take effect).

---

## 6. Structure and governance (corrected)

The v1.0 §6.1 terms are kept, with these corrections:
- **Units vs organs.** [D] PO-DR07-01 establishes the distinction. That the legacy `committees` table represents both is the Principal's position (K-5), and record classification is O-16 (not resolved here).
- **Organ scope.** Not "exactly one" (§4). Joint, regional or functional organs (e.g. an election commission) need evidence (E-2).
- **Offices outside organs.** [OQ] E-2.
- **Working group.** Unchanged: an organ only if given competence [P].

v1.0 §6.2 authority terms are kept, with these corrections:

| Term | v1.1 status |
|---|---|
| Competence, organs, authority | [D] ACT §11 (Governance) |
| Positions | [D] named in PO-DR07-01 (a PO business ruling); **not in ACT §11** (C-2); not assigned to ACT §11 here |
| Appointment, Tenure, Mandate | [P] vocabulary; context placement **[U] ARB-8**: Governance or BDR-03 "Appointment (Authority)" (K-12, C-13) |
| Delegation | [P] vocabulary; [F] delegation classes already exist in Governance code (K-10) |
| Administrative responsibility vs decision | [P] PREP §3.2 |

**No Governance responsibility moves into an Organisation context** (unchanged).

---

## 7. Capabilities (corrected)

The table from v1.0 §7 is kept, with these corrections:
- **Governance:** whether the capability is optional is open (M-7). [F] Today's gate is the owner-driven setup state (K-4), not a platform decision.
- **Elections:** "Elections = core" is withdrawn (K-9).
- **Capability enablement:** v1.0 placed it in the Organisation context. [I] Enablement is what the Organisation switches on in PublicDigit, so it is closer to the platform service relationship. **Placement open: M-14.** [F] `uses_full_membership` is overloaded: it is enablement and also the voter-source selector. No new dimension is proposed.

---

## 8. Geography (corrected)

### 8.1 Concepts: five, not four

| Concept | Meaning [P] | Owner | Evidence |
|---|---|---|---|
| Geographic reference unit (GeoUnit) | an administrative area; official or organisation-custom | Geography [P] | [F] nullable `organisation_id`; CHECK 0–10 (K-3) |
| Organisational unit | a structural part of the Organisation, possibly with a responsibility area | O-16 [U] | [D] PO-DR07-01 |
| Operating area | where the Organisation works | [P]; may simply be derived from units' responsibility areas, so it waits for evidence (IR §I) | [F] `geographic_scope`, `allowed_countries` |
| Governance jurisdiction | an organ's authority area over time (JurisdictionAssignment) | Governance [P] | GMAP `:67`. Note: "jurisdiction" is also used for committee arbitration (`ConstitutionalArbitrationKernel`), so reserving the term must name that use (IR §C.1) |
| **Electoral area** (added) | the area a post is elected for, and the voter's region | Election today | [F] `posts.state_name`, `posts.is_national_wide`, `users.region`, all free text. **[OQ] whether electoral areas must reference GeoUnits is not decided here** |

### 8.2 Facts and scope

- [F] The Nepal four-level hierarchy is the code default in use. The worldwide factory exists but is unused (K-2).
- [F] The phrase "geography-based committee governance platform for NRNA" comes from a legacy AI working brief that pairs NRNA with Nepal's *domestic* hierarchy (K-8). It is not a decision record.
- [I] On current evidence the Nepal hierarchy is an implementation choice, not a domain requirement.

**Scope options** (PO decision M-5; implications only, **no choice made**):

| Option | Implication |
|---|---|
| A. National hierarchies only | simplest; conflicts with the named diaspora customer |
| B. Add diaspora (worldwide → region → country) | per-organisation hierarchies; the unused factory becomes real |
| C. Any official hierarchy plus organisation-custom units | most general; raises OQ-OS-1 and XD-5; risk of becoming a geography platform |

"Geography as a per-Organisation choice over shared reference data" is compatible with all three, and is **not** a choice of C. **No worldwide or universal geography model is adopted.**

---

## 9. Relationships between Organisations (corrected)

The v1.0 §9 taxonomy is kept as **discovery only** (BR-13a), with these corrections:
- **Merger:** downgraded to [P] pre-ratification analysis. The ratified set covers **person** merges only (K-7).
- **Succession:** an open question about the organisation's own lifecycle, not a BR-13a matter (§3.3; M-10).
- **The definition must not exclude** federation members, subordinate bodies or organisations bound by external rules. The §3.1 candidate admits them without designing any relationship (IR §C.2).
- **Unit or separate Organisation:** E-1 (unchanged).

---

## 10. Conflicts (C-1…C-14; none resolved)

| # | Conflict | Evidence | Authority | Next action |
|---|---|---|---|---|
| **C-1** | "Organisation = the recognising body" is presented as ratified but comes from a non-ratified candidate. **Wider than v1.0 found:** also in PREP `:67` and AR/`2026-11-03-baseline-stabilisation-and-strategic-decision-preparation-review.md:89`, `:105` | candidate `:3`, `:27`; GMAP `:56`; R/`2026-11-02-foundation-boundary-and-ubiquitous-language-audit.md:112` | ARB (O-14) | ARB records the correction; document owners annotate (not done here) |
| **C-2** | "positions" is not in ACT §11 | ACT `:321`; DR07 `:265` | ARB (ARB-2, ARB-8) | do not assign positions to ACT §11 meanwhile |
| **C-3** | ODA-01.7 vs BR-13a | BR `:39` | PO | confirm discovery only (M-10); succession stays [OQ] |
| **C-4** | units vs separate Organisations | DR07 `:266`; RAT `:150` | PO on evidence; ARB for consequences | E-1 |
| **C-5** | structure and geography choices frozen at creation (**corrected: `geographic_levels` is mutable**; the guard is a model convention) | `Organisation.php:59-75` (K-1) | PO / ARB at design time | design input only |
| **C-6** | committees (units and organs, per the Principal's position) inside Membership. **Re-cited:** context basis ACT §11 "organs" + N-6; the ACT §10 reading is ARB-1, pending | ACT `:19`, `:283-305`, `:321`; DR07 `:224`, `:312` | ARB (ARB-1, ARB-2); DR-07a | stays in DR-07a / O-16 |
| **C-7** | `governance_status`: **three meanings, owner-driven, no writer for `active` / `suspended`** (corrected) | K-4 | ARB at design time | record facts only |
| **C-8** | "Governance" in ADR-004 (Election Governance) vs ACT §11 | ADR-004 `:15`; ACT `:321` | ARB (O-14) | qualify the terms |
| **C-9** | "Constitution": five or more meanings (also ADR-004's election "Constitution as SSOT") | PRI §2 `:39-55`; PREP §7.4 | ARB (O-14) | qualified term [P] |
| **C-10** | "no universal governance model" assumed, not recorded | PRI `:415` | PO | M-6 |
| **C-11** (new) | Membership: "Candidate" in ADR-004 vs ratified context boundaries in ACT §11; not superseded | ADR-004 `:30`; ACT `:309` | ARB | CMAP change control or a superseding ADR |
| **C-12** (new) | four inconsistent subdomain classifications (AIP-14, BDR-08, the 2026-10-09 review, P-ODA-8) | K-9 | PO (strategy) + ARB | reconcile before adopting any; **not reconciled here** |
| **C-13** (new) | Appointment and Tenure ownership: Governance [P] vs BDR-03 "Appointment (Authority)" | BDR `:16`; CMAP `:79-80` | ARB (ARB-8) | widen ARB-8 |
| **C-14** (new) | the bootstrap organisation row vs the definition | `create_uuid_organisations_table.php:17-18`; BR `:35` | PO (meaning, M-3); ARB (O-14) | exclusion stated in the candidate definition (§3.1), pending M-3 |

---

## 11. Strategic DDD (corrected)

### 11.1 Classification: provisional only

- [F] The business case states PublicDigit's differentiation in terms of **elections**: diaspora focus, multiple languages, mobile, transparent security (`EXECUTIVE_SUMMARY.md:195-200`; `BUSINESS_CASE.md:335-341`). It does not mention constitution-faithful governance or membership (IR §H, grep).
- [F] Four classifications already conflict (C-12).
- [P] **"Organisation = foundational supporting" is kept only as a provisional proposal**, on the ground that any multi-organisation platform needs it.
- **v1.0's statement that constitution-faithful Governance and Membership are "candidates for the core" is withdrawn.** It is a possible strategic hypothesis (from "election tool" to "organisation operating system"). Only the PO can adopt it as strategy (M-8).
- [I] Geography is **split**: official reference data is generic; per-organisation hierarchies, custom units and revision pinning are specialised supporting.
- [I] Platform / tenancy stays outside the Organisation concept. INV-ORG-SCOPE-1 needs a named owner.
- **No bounded context is to be established because of a classification.**

### 11.2 Organisation context: scope not shown to be coherent

v1.0 proposed a six-part scope: identity, lifecycle, constitution record, units, operating area, capability enablement, plus OrganisationAffiliation. **v1.1 does not maintain that this is a coherent bounded context.**
- [I, IR §I] It mixes constitutional facts with access (affiliation) and commercial enablement, which is the conflation risk R-4 warns of.
- Recorded for the ARB as **input, not proposal**: the review's "smallest coherent candidate" is identity, organisational status, the source record (only if O-2) and, on evidence, units (O-16). Operating area may be derived. Enablement and platform service status belong with the platform relationship (M-14). OrganisationAffiliation's context is BR-9 / OQ-C-5.
- Whether an Organisation context is established at all, and with what scope, is **M-13 (ARB)**, after M-11 and evidence.

The context summaries for Governance, Membership, Election and Geography from v1.0 §11.2 stand as descriptions of **recorded** ownership. Corrections:
- Membership is ratified under ACT §11 but still "Candidate" in ADR-004 (C-11);
- Appointment, Tenure and Mandate are [U] ARB-8;
- Election is not called core.

### 11.3 Ownership of authoritative facts (corrected)

| Fact | Authoritative in | Status |
|---|---|---|
| Organisation identity, organisational status | Organisation (business); context **[U] M-13** | [P] |
| Constitution source record and versions | **[U] O-1 / O-2 (M-11)** | — |
| Interpretation rulings, competence, authority | Governance | [D] ACT §11 |
| Operational rules | each capability | [P] M-12 |
| Units | [U] O-16 | — |
| Organs | Governance | [D] |
| Appointments, tenure, mandate | [U] ARB-8 | — |
| Membership facts | Membership | [D] |
| GeoUnits | Geography | [P] |
| Electoral areas | Election (today, free text) | [F] / [OQ] |
| Platform service relationship, enablement, tenant | Platform [U] M-14 | — |

---

## 12. Relationships: input to CMAP change control (corrected)

**v1.0 §12 drew a second context map. That map is withdrawn.** CMAP is the consolidation point. The relationships below are **input to CMAP's change control** (M-17), with the dependency issues found by the review.

| Relationship | Fact owner | Direction | Circular? | Note |
|---|---|---|---|---|
| Organisation ↔ Governance | ORG: identity, record (O-2 only), units (O-16). GOV: organs, competence, authority | ORG → GOV; GOV → ORG (authority evidence for amendments and structural acts) | **yes under O-2**; under O-1 only the unit link remains | acceptable only as two separate minimal published languages, recorded explicitly (precedent GMAP `:153-157`); high risk of duplicated truth unless the source is held as evidence only |
| Organisation → Membership | ORG id, status | ORG → MEM | no | low risk if MEM keeps only `OrganisationId` |
| Organisation → Election | id, status; enablement (M-14) | ORG / PLT → ELE | no | [F] `uses_full_membership` overload |
| Geography → Organisation, Governance (and Election, if M-5 / §8 OQ) | GeoUnits | GEO → ORG, GOV | no | five link kinds (§8.1); revisions pinned |
| Organisation ↔ Platform / tenancy | ORG id, organisational status; PLT tenant mapping, Working Organisation, service status | ORG → PLT; PLT → GOV / MEM / ELE (R-15) | **yes, flagged now**: ORG → PLT → GOV → ORG | depends on M-14 |

No shared persistence and no new cross-context dependency are introduced. ACT `:349` says shared persistence is "not required", which is not the same as forbidden.

---

## 13. Ubiquitous Language (corrected)

The v1.0 §13 glossary stands, with these changes:

| Term | Change |
|---|---|
| Organisation | candidate definition §3.1; status **P**; C-1 noted |
| Platform record | **added**: an `organisations` row that is not an Organisation in the domain sense (bootstrap, demo); M-3 |
| Tenant | unchanged: technical, not part of the definition |
| Governance setup state | **added**: today's `governance_status` (K-4) |
| Constitutional source record, Interpretation ruling, Authority evidence, Operational rule | **added** (§5.1); status P |
| Position | [D] PO-DR07-01; not ACT §11 (C-2) |
| Appointment, Tenure, Mandate | context **U** (ARB-8) |
| Jurisdiction | P; the reserved meaning must name the arbitration use (§8.1) |
| Electoral area | **added**; Election; free text today |
| Operating area | P; may be derived (§8.1) |
| Governance | qualify as "Organisational Governance" vs ADR-004's "Election Governance" (C-8) |

All glossary items remain inputs to the ARB's O-14 act.

---

## 14. Reference organisations (corrected)

### 14.1 Candidates

The candidates are unchanged and **not selected**; the PO names them (M-9). Corrections:
- **Only NRNA is a concrete organisation.** "A ward / district / province party", "a small registered association" and "the operator organisation" are archetypes. Creating the operator organisation is not authorised (PRI `:103`).
- [F] The three archetypes match code that already exists (`TenantGeographyProfile::forDiaspora / forNepalParty / forGermanyNGO`), and two of the four candidates are Nepal-related. **Risk of confirming prior assumptions (R-17).**
- Missing test cases to consider: an organisation **without written statutes**; a **member of a federation or umbrella body** (to test that the definition does not exclude it, with no federation design); and, if the PO puts them in scope, a company or union.
- Minimum evidence before tactical design [P, IR §K]:
  - one concrete organisation's authoritative, versioned statutes;
  - for that organisation: its organs and offices with scopes, unit kinds and separate-organisation status, amendment procedure and effective dates, and external binding rules;
  - then the same from two further concrete organisations that differ on legal form and geography.

### 14.2 Questionnaire improvements (proposal; not approved)

Applied as proposed amendments to v1.0 §14.2 and the PREP §5 questionnaire. **No organisation was contacted, and no statute or organisational fact is invented.**

| # | Improvement |
|---|---|
| Q-1 | **Gate question first:** "Does the organisation have written statutes? If so, which documents (constitution, bylaws, rules of procedure, regulations) and which versions?" Only then the D, S, A, G, C, R, O and X blocks |
| Q-2 | **Answer values:** article and version · `SILENT` (the statute says nothing) · `AMBIGUOUS` (with the text) · `UNKNOWN / NOT PROVIDED` (no evidence supplied). Silence and absence of evidence are different; neither produces a default |
| Q-3 | **Evidence-type column** for every answer: statute · registry extract · minutes or resolution · representative's statement. A representative's statement is recorded as such and never as statutory evidence |
| Q-4 | **O9 reworded neutrally:** "For each organ: over what does it decide (the whole organisation, particular parts, particular functions, particular areas)? Cite the provision." It no longer presupposes "whole organisation or one unit" |
| Q-5 | **O6 / O7 neutral:** ask "how is the organisation divided, if at all?" before using the word "unit" |
| Q-6 | **Representative's authority recorded as evidence** (X3): the mandate or statutory basis, its date, and what it covers |
| Q-7 | **X2 made mandatory:** law, parent body or federation rules that bind internal affairs |

---

## 15. Decision and evidence register

The review's register M-1…M-17 and E-1…E-6 is adopted as the register for this baseline. **Every item stays open with its authority.** v1.0's OQ-ODA items are mapped for traceability.

### 15.1 Unresolved PO decisions

| # | Question | Authority | Wording change from IR | Further evidence? | v1.0 item |
|---|---|---|---|---|---|
| M-1 | Record PO-ORG-MEM-01 A / B with exact wording | PO | none | no | (DR-07a §9.1) |
| M-2 | Business meaning of "Organisation": candidate §3.1 / as v1.0 / reject pending evidence | PO, then ARB (O-14) | none | statutes only for continuity details | OQ-ODA-1 |
| M-3 | Is a platform record (bootstrap, demo) an Organisation? | PO | none | no | new |
| M-4 | Operating before statutes; the D-4 interim interplay (§5.2) | PO | none | no | OQ-ODA-5 |
| M-5 | Geography scope (§8.2 A / B / C) | PO | none | customer list | OQ-ODA-8 |
| M-6 | "No universal governance model" as a platform rule | PO | none | no | OQ-ODA-10 |
| M-7 | Is the Governance capability optional? | PO | none | no | OQ-ODA-9 |
| M-8 | Product strategy for subdomain classification | PO, then ARB (C-12) | none | business strategy | OQ-ODA-2 |
| M-9 | Reference organisations and representatives (≥ 3 concrete, broadened per §14.1) | PO | none | — | (v1.0 §14) |
| M-10 | Confirm §9 is discovery only; succession stays [OQ] | PO | none | — | C-3 |
| — | Technical owner of a future Organisation context | PO | not in IR; kept from v1.0 | — | OQ-ODA-13 |
| — | Is an organisation without written statutes, a company, a foundation or a union in scope? | PO | not in IR as a decision; raised in IR §C.5 / §K | — | new |

### 15.2 Unresolved ARB decisions

| # | Question | Authority | Further evidence? | v1.0 item |
|---|---|---|---|---|
| M-11 | Does ACT §11 "organisational governance rules" include the source text? (hinge for O-1 / O-2) | ARB | no | OQ-ODA-4 |
| M-12 | Adopt the four-way separation and the `(document, article, version)` citation | ARB | no | new |
| M-13 | Organisation context: leave Candidate status? Minimal scope? | ARB | ≥ 1 statute | (v1.0 §11.2) |
| M-14 | Placement of the platform service relationship and enablement | ARB | XD-15 | OQ-ODA-3 |
| M-15 | ARB-1, ARB-2, ARB-8, widened to positions, appointments and tenure | ARB | DR-07a report | OQ-ODA-12 |
| M-16 | O-14 vocabulary: C-1, C-8, C-9, C-11, organisation-level "affiliation", "jurisdiction" | ARB | no | OQ-ODA-11 |
| M-17 | Treat §12 as input to CMAP change control | ARB (CMAP owner: PO) | no | new |

### 15.3 Outstanding external evidence

| # | Question | Questionnaire items | Feeds |
|---|---|---|---|
| E-1 | Is a given section a unit or a separate Organisation? | O6, O7 (Q-5); registry extracts | C-4 |
| E-2 | Organ scope cardinality; offices outside organs | S1–S5, O9 (Q-4) | §4, §6 |
| E-3 | Amendment procedure and effective dates; one document or several | C1, C2, D4, Q-1 | §5.3 |
| E-4 | External binding rules (law, parent, federation) | X2 (Q-7) | §3.1 |
| E-5 | Dormancy, dissolution, liquidation, succession | O3, O4 | §3.3 |
| E-6 | Same structure in every country? | O8 | §8 |

Unchanged and untouched: O-16 / DR-07b, DR-07a, CQ-10, P11, XD-15, BR-9, INV-ORG-SCOPE-1.

---

## 16. Risks (corrected and prioritised, per IR §L)

| Priority | ID | Risk | Needs |
|---|---|---|---|
| 1 | R-12 / R-19 | single decision and review chain; the baseline, the review and this correction come from the same model family, and this correction is by the original author | human or different-model review |
| 2 | R-14 | wrong O-1 / O-2 choice made on v1.0's unbalanced comparison | ARB uses §5.3 of v1.1 and IR §E.3, not v1.0 §5.3 |
| 3 | R-16 | a fail-closed rule before statutes blocks current customers (D-4 admissions; calling elections) | M-4 |
| 4 | R-9 / R-13 | tenancy defines the domain, including through the definition | the candidate definition omits tenancy; M-2 |
| 5 | R-17 | overfitting to NRNA, Nepal and the code's archetypes | §14.1 broadening; M-9 |
| 6 | R-15 | classification inflation ("core" spreading without business evidence) | M-8 |
| 7 | R-8 / R-18 | duplicated truth and documentation drift (parallel maps; propagated labels such as C-1) | M-17; owners annotate C-1 documents |
| 8 | R-2, R-3, R-4, R-5, R-6, R-10 | as v1.0 (R-10 corrected by K-1; R-2 relabelled per K-5) | as v1.0 |
| 9 | R-1, R-7 | as v1.0 | — |
| 10 | R-11 | **over-modelling**, to which v1.0 itself is exposed: name history, legal references 0..n, two four-state lifecycles, link kinds and operating area were all proposed before any statute was read | treat them as **candidates for evidence**, not model elements |

---

## 17. What remains prohibited until further authorisation

- Any application code, schema, migration, aggregate or domain-model change.
- Establishing an Organisation bounded context or any new context.
- Creating a context map; modifying CMAP or PRI.
- Modifying ADRs, BDRs, ratified records, ACT §10 / §11 or the ratified Membership architecture.
- Deciding O-1 / O-2; making any PO (M-1…M-10) or ARB (M-11…M-17) decision.
- Terminating, extending, redefining or implementing D-4.
- Designing federation or any relationship between organisations.
- Contacting organisations or representatives.
- Running tests, migrations or production queries.
- Committing or pushing.
- Tactical DDD design (aggregates, invariants, persistence) before M-11, M-13 and the minimum evidence (§14.1).

---

## Traceability

- **Original:** ODA-01 v1.0 (sha256 `4617c571…c740`), preserved unchanged.
- **Review:** IR-ODA-01 (sha256 `4fe75edf…3c01`); K-1…K-14 re-verified at source by this correction (§0.2).
- **Commission:** ODA-02 (Principal / PO, 2026-11-03).
- **Ratified:** ACT `:9`, `:19`, `:283-305`, `:309-349`; RAT `:42-43`, `:72`, `:150`, `:156`.
- **Decisions:** DR07 `:222-224`, `:261-291`, `:310-313`; BR `:23-40`; PREP `:47-61`, `:67`, `:299`; BDR `:16`, `:21`, `:57`.
- **Code** (static reading only):
  - `app/Models/Organisation.php`;
  - `app/Policies/OrganisationPolicy.php`;
  - `app/Http/Controllers/GovernanceSetupController.php`;
  - `app/Domain/ValueObjects/OrganisationGovernanceStatus.php`;
  - `app/Contexts/Membership/Domain/Committee/Context/CommitteeStructureEpochContext.php`;
  - `app/Contexts/Geography/Domain/ValueObjects/GeographicStructure.php`;
  - `app/Contexts/Geography/Domain/Models/TenantGeographyProfile.php`;
  - `app/Contexts/Governance/Domain/ValueObjects/ConstitutionalBasis.php`;
  - `app/Contexts/Governance/Domain/Authority/**`;
  - Geography Landlord migrations `2025_01_01_000002`, `2026_01_14_000003`;
  - `database/migrations/2026_03_05_000005_create_uuid_posts_table.php`.
- **Not changed:** code, schema, migrations, tests, ADRs, BDRs, CMAP, PRI, ratified records, business rules, ODA-01 v1.0, IR-ODA-01. Nothing committed.
