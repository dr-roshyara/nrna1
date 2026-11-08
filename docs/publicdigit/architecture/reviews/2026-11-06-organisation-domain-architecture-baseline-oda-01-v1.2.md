# ODA-01 v1.2: Organisation domain architecture baseline (second correction)

> **This document is discovery and conceptual design. It approves, ratifies and authorises nothing, and it is not a ratified architecture decision.** Every [P] item is a proposal. Every Principal / PO and ARB question in it remains open.

| | |
|---|---|
| **Version** | **v1.2**, the second controlled correction, authorised by the Principal on 2026-11-06 ("Principal authorisation — ODA-01 v1.2") |
| **Preceding versions (preserved unchanged)** | v1.0 `2026-11-03-organisation-domain-architecture-baseline-oda-01.md`, sha256 `4617c571…c740`. v1.1 `2026-11-03-organisation-domain-architecture-baseline-oda-01-v1.1.md`, sha256 `7ee60dac…6dc8`. Both are committed in `f6471aaf0` |
| **Correction basis** | (1) the Claude subagent review of v1.1, `2026-11-06-ir-oda-01-v1.1-claude-subagent-review.md` (**"S-review"**, findings F-1…F-10), committed in `ffcee7b8a`. (2) The committed PO-ORG-MEM-01 decision record. (3) Additional findings by this author, verified at source (§0.2, rows V-1…V-3) |
| **PO-ORG-MEM-01** | `docs/publicdigit/reviews/2026-11-03-organisation-po-org-mem-01-membership-ownership-decision-record.md`, committed in **`c632fe45d`** |
| **Status** | **CORRECTED CONCEPTUAL BASELINE, FOR PRINCIPAL REVIEW** and for the still-outstanding independent human or non-Claude review. Not approved |
| **Author** | Claude, the author of v1.0 and v1.1. **A correction by the original author: not an independent review and not a self-approval.** The S-review is a Claude review, so it is not independent either |
| **Date** | 2026-11-06 |
| **Snapshot** | branch `election-flow-updated`; HEAD recorded in the final report to the Principal |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels.**
- **[F]** verified fact, with source;
- **[D]** decision, with its authority and recording status;
- **[P]** proposal;
- **[H]** hypothesis;
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
- POMEM = R/`2026-11-03-organisation-po-org-mem-01-membership-ownership-decision-record.md`
- S2C = R/`2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md`

**Out of scope.** PBDIGIT-330 (committee office-holder responsibility, activity and performance recording) is a separate future discovery initiative. It is **not** part of this baseline.

---

## 0. Change control

### 0.1 Summary of changes from v1.1 to v1.2

| Area | v1.1 | v1.2 |
|---|---|---|
| PO-ORG-MEM-01 | "approved; not in a decision record; wording unrecorded" | **recorded and committed** (`c632fe45d`). Decision A quoted verbatim (§2.2). Its verbatim fidelity was checked by Session A against the Principal's original text, and the Principal confirmed the check (§0.3). Its scope is stated precisely: it decides business ownership of Membership, and it does **not** decide the ACT §11 reading, context ownership of the Constitution or the Constitution's representation |
| Who decides | the definition said "through its own organs" | **"a competent organisational organ or an authorised office holder, within constitutional competence or valid delegation"** (Decision A). The definition, the model and the structure sections no longer narrow decision-making to organs (§3, §4, §6) |
| Constitution ownership | "business owner of the constitution is the Organisation (A)" | withdrawn. Decision A says "The Organisation's Constitution (statutes)", a possessive, in a decision about **Membership**. It does not decide who owns the constitution *record* (§5.3) |
| Placement options | O-1 and O-2 only; O-3 silently dropped | **O-3 restored.** All three options compared, with shared costs counted for every option they apply to and facts labelled separately from judgements (§5.3) |
| Precedent evidence | "article citations and delegation sit in Governance code"; "no version store" | **mixed precedent.** `ConstitutionalBasis` sits in Governance. `ConstitutionalArticleReference` and a versioned, effective-dated, hash-pinned, supersession-tracked `DoctrineArtifact` store sit in **Membership**, in memory and unwired (§2.3, §5.3) |
| Citation | "`(document, article, version)`; any change needs a CR" | distinguished: carrying the citation inside today's string fields needs no schema change, but **declaring that this is what those fields mean** interprets the frozen evidence contract, and **structured fields would need a Change Request**. The frozen evidence semantics are not changed here (§5.1) |
| Four-way separation | presented as complete | kept as sound but **not closed**: an organ's resolution or decision record is a candidate fifth element (PREP OQ-12) (§5.1) |
| D-4 | authority "PO DECIDED"; tension stated | source corrected: **D-4 was decided by the Human ARB as, or on behalf of, the Governance owner** (S2C `:293`). New sub-question: what the mandatory `ruleRef` / `policyVersion` strings hold for D-4 acts (§5.2) |
| Labels and counts | several [F] that are inference; "five concepts", "five link kinds" | corrected: "no statute held in the repository" (fact) vs "organisations operate without statutes" (inference); the structure epoch is a deprecated shim; `forWorldwideOrg` has test callers; classifications are **differently scoped, unreconciled**; geography is **four link kinds to one reference concept** (§2.3, §8, §10–§11) |
| Register identifiers | M-1…M-17, E-1…E-6 | renamed **ODA-M-1…** and **ODA-E-1…** to remove collisions with DR-07a's M-1…M-5 and the PO's dues decisions E-1…E-5 (§0.4). M-4 and M-12 split; ODA-M-18 and ODA-M-19 added; the technical-owner row is deferred (§15) |

**Unchanged in substance:**
- the core separations: Organisation ≠ Membership ≠ Governance ≠ Election ≠ Tenant; unit ≠ organ; GeoUnit ≠ jurisdiction; platform access ≠ constitutional authority;
- C-1 (extended in v1.1);
- the BR-13a handling;
- the reference-organisation sequence;
- no recommendation on placement.

### 0.2 Correction register

Every row was verified at source by the author of this correction. "S-F" = S-review finding; "V" = a new finding by this author.

| # | v1.1 statement | Finding | Source verification | Correction in v1.2 | Classification | Sections |
|---|---|---|---|---|---|---|
| S-F1(a) | PO-ORG-MEM-01 wording unrecorded (K-11, §0.3, §1, §2.1, M-1) | a decision record exists | [F] POMEM `:3`, `:11-30`; committed in `c632fe45d`; the fidelity note `:61` states that Decision A "was verbatim in both versions" | record cited; Decision A quoted; ODA-M-1 re-scoped to "registered" (closed) | decision | §1, §2.2, §15 |
| S-F1(b) | — | the record is a Claude session's transcription | [F] POMEM header "Recorded by: Claude (Architecture Session B)". **Verified by Session A** against the Principal's original text in the Session B conversation (pasted 2026-11-03 08:19 UTC); Decision A identical. **Confirmed sufficient by the Principal, 2026-11-06** | Decision A treated as [D] verbatim. The record's own §3 "Consistency" statements are the recorder's inference, not part of the decision | decision / inference | §0.3, §2.2 |
| S-F1(c) | "the business owner of the constitution is the Organisation (A)" | overreads A | [F] Decision A: "The Organisation's Constitution (statutes) establishes the competent organisational organs, offices and positions …" (POMEM `:22`) | replaced: "the Constitution is the Organisation's own (A, possessive). Who holds the record, and in which context, is not decided" | decision (scope) | §5.3 |
| S-F1(d) | definition "through its own organs" | narrower than A | [F] A: "A competent organisational organ or authorised office holder makes Membership decisions within its constitutional competence or valid delegation" (POMEM `:24`) | definition and model widened to organs **or** authorised office holders. A gives **weak PO-level support** that offices exist alongside organs (ODA-E-2 stays open). C-2 stands | decision; open | §3, §4, §6 |
| S-F2 | O-1 precedent "in Governance"; "no version store" | mislocated; incomplete | [F] `app/Contexts/Governance/Domain/ValueObjects/ConstitutionalBasis.php:9-25` (Governance). [F] `app/Contexts/Membership/Domain/Committee/Constitutional/Doctrine/`: `ConstitutionalArticleReference.php`, `DoctrineArtifact.php` (`version`, `effectiveFrom`, `effectiveUntil`: `:13-16`), `DoctrineArtifactHash.php`, `DoctrineProvenance.php` (`approvedBy`, `supersedesDoctrineId`, `supersededByDoctrineId`: `:10-13`), `DoctrineRegistryInterface.php`, `InMemoryDoctrineRegistry.php`. [F] scopes national / regional / emergency / caretaker / election (`…/Constitutional/ConstitutionalScope.php:9-13`). [F] no reference to the registry outside its folder (grep `app`) | precedent described as **mixed**; the Doctrine store recorded. [OQ] whether it models platform committee doctrine or an organisation's statutes | fact; open | §2.3, §5.3 |
| S-F3 | O-3 absent; costs counted unevenly | incomplete comparison | [F] v1.0 §5.3 O-3; v1.1 K-14 still refers to its rejection | O-3 restored; the "Organisation context" cost is marked as shared with ODA-M-13; O-1's costs completed (a stretched reading of ACT `:321`; a published language to Membership and Election); facts and judgements labelled | proposal | §5.3 |
| S-F4 | "any change needs a CR" | incomplete | [F] RAT `:72` names `ruleRef`, `policyVersion` and rule provenance `(ref, version)` without fixing referents. [F] code: `AuthorityEvidence.php:17-26`: `actor`, `capacity`, `ruleRef`, `policyVersion` are strings validated non-empty. [F] `Provenance::rule(string $ruleRef, string $ruleVersion)` (`Provenance.php:37`) | three cases distinguished (§5.1). ODA-M-12 split into 12a / 12b. D-4 sub-question added to ODA-M-4b | fact; proposal | §5.1, §5.2, §15 |
| S-F5(a) | "[F] every current organisation uses PublicDigit without statutes" | inference | [F] PRI `:414` (A86): "OPEN; statutes not yet requested". [F] R/`2026-11-02-organisation-xd15-tenancy-and-data-ownership-decision-proposal.md:138`: "Statutes / party constitutions … **absent**" from the business case, business rules and Membership documents | split: [F] no statute is held in the repository and none has been requested; [I] current organisations operate in PublicDigit without any statute being held | fact + inference | §5.2 |
| S-F5(b) | the structure epoch is one of three meanings of `governance_status` | it is a deprecated shim; the gate cannot open through code | [F] `CommitteeStructureEpochContext.php:28-29` `@deprecated Phase B temporary shim`, one caller (`GovernanceCapabilityContextFactory.php:32`). [F] the only write of `governance_status` is `'governance_configured'` (`GovernanceSetupController.php:102`). `OrganisationPolicy::activateGovernance` (`:77-84`) has no caller in `app`, `routes` or `resources/js` (grep). [I] so `allowCommitteeCreation()` (`OrganisationGovernanceStatus.php:14-16`) cannot become true through application code alone | described as two meanings plus a deprecated shim; the consequence recorded as [I] (static reading; not run) | fact; inference | §2.3, §3.3, C-7 |
| S-F5(c) | `forWorldwideOrg` "unused" | test callers exist | [F] unused in `app/`; called by **three** test files: `tests/Unit/Domain/GeographicStructureWorldwideTest.php`, `GeographicStructureTypeValidatorTest.php`, `tests/Unit/Application/GeoReferenceBuilderTest.php`. **The S-review said six; grep finds three** | "unused in production code; covered by three test files" | fact | §2.3, §8 |
| S-F5(d) | D-4 quoted, cited to PREP `:299` | quotation said not to match | [F] the quotation "interim and replaceable by the Governance-owned implementation" **is verbatim** in the source, S2C `:293`. PREP `:299` paraphrases it. **The S-review is right that the citation was wrong, not the quotation** | citation corrected to S2C `:293`. See V-1 | fact | §2.1 |
| S-F5(e) | four classifications "inconsistent" | differently scoped | [F] AR/`2026-11-03-publicdigit-architecture-assessment.md:104`: "Three classifications at three scopes" | "differently scoped and unreconciled" | fact | §11.1, C-12 |
| S-F6 | "five concepts", "five link kinds" | the unit is not geographic; links are four | [I] by inspection of v1.1 §8.1 | one reference concept (GeoUnit) and **four link kinds** (operating area, unit responsibility area, governance jurisdiction, electoral area). Operating area is removed from the diagram until there is evidence | inference | §4, §8, §12 |
| S-F7 | four-way separation presented as complete | an organ's resolution or decision record is missing | [F] PREP `:432` (OQ-12) asks for "an explicit reference to the deciding organ's resolution" | a fifth candidate element recorded as [OQ]. "Interpretation ruling" relabelled [P]: no artefact evidence exists beyond questionnaire D5 | proposal; open | §5.1 |
| S-F8 | "under its own constitution (statutes)" while organisations without written statutes are an OQ | conflict | [I] | the definition states explicitly that, as drafted, it excludes organisations without written statutes. Whether they are in scope stays a PO question (ODA-M-20) | proposal; open | §3.1 |
| S-F8 / M-3 | M-3 a fresh PO decision | largely decided | [D] BR-12 (BR `:35`): the bootstrap organisation is "infrastructure, never a Working Organisation" | ODA-M-3 narrowed to vocabulary (does "Organisation" exclude platform records?) and demo data | decision; open | §15 |
| S-F9 | §0.1 "C-1 unchanged" | it was extended | [F] v1.1 §10 C-1 "Wider than v1.0 found" | "C-1 extended" | fact | §0.1 |
| S-F9 | register identifiers | collision with DR-07a M-1…M-5 | [F] POMEM `:36` "Proceed with DR-07a technical discovery covering M-1 to M-5" | renamed ODA-M-n (§0.4) | fact | §15 |
| S-F9 | technical owner of a future Organisation context in the register | premature | [I] it depends on ODA-M-13 | moved to "deferred until ODA-M-13" | proposal | §15 |
| S-F10 / K-8 | the cited anchor could not be located by the S-review | resolves at its full path | [F] `docs/publicdigit/membership/architecture/brainstorming/cross_domain_brainstorming/20261011_0935_analysis_report.md:606` ("geography-based jurisdiction") | full path given | fact | §8 |
| S-E (register) | Change Request authority not named; POMEM §3 ARB items not mapped | gaps | [F] RAT `:3` "Changes only via an Architecture Decision / Change Request"; ratified by the Principal Reviewer; no named CR authority. [F] POMEM §3: four pending ARB items | ODA-M-19 added; ODA-M-18 added; mapping table (§15.3) | open | §15 |
| **V-1** (new) | D-4 "PO DECIDED" (from PREP `:299`) | the source names the Human ARB | [F] S2C `:293`: D-4 "**DECIDED** (by the Human ARB as, or on behalf of, the Governance owner)". PREP `:299` labels it "PO DECIDED" | v1.2 records the source's authority. [I] Changing D-4 is therefore a matter for the authority that decided it, not for the PO alone. **D-4 itself is not touched** | fact; open | §2.1, §5.2, ODA-M-4 |
| **V-2** (new) | register items E-1…E-6 | collision with the PO's dues decisions E-1…E-5 | [F] PREP `:301` (E-1…E-5 "dues obligation …", PO DECIDED) | renamed ODA-E-1…ODA-E-6 | fact | §15 |
| **V-3** (new) | XD-15 anchor cited without a path | not resolvable as written | [F] see S-F5(a) | full path given | fact | §5.2 |

### 0.3 Verification of PO-ORG-MEM-01 (evidence)

| Check | Result |
|---|---|
| Source of truth | the Principal's approval text, pasted into the Session B conversation on 2026-11-03 at 08:19 UTC (`pasted_content` id `31b2`) |
| Compared with | POMEM §1, as committed in `c632fe45d` |
| Method | line-by-line comparison after whitespace normalisation (Session A, 2026-11-06) |
| Header and Decision A | **identical** |
| Decision B and Constraints | abbreviated and condensed in the first version of the record; restored by Session B (fidelity note, POMEM `:61`) |
| Principal | "the word-for-word match of Decision A … is sufficient for Step 1" (2026-11-06) |

### 0.4 Identifier renaming (this document only)

| v1.1 | v1.2 | Reason |
|---|---|---|
| M-1…M-17 | **ODA-M-1…ODA-M-17**, with M-4 split into **ODA-M-4a / 4b** and M-12 into **ODA-M-12a / 12b**; new **ODA-M-18…ODA-M-20** | DR-07a's M-1…M-5 (POMEM `:36`) |
| E-1…E-6 | **ODA-E-1…ODA-E-6** | the PO's dues decisions E-1…E-5 (PREP `:301`) |
| C-1…C-14 | unchanged (ODA conflict register) | no collision found in the Organisation records |

---

## 1. Repository and governance state

| Item | State |
|---|---|
| Branch | [F] `election-flow-updated` (HEAD in the final report) |
| Session A commits relied on | `f6471aaf0` (v1.0, IR-ODA-01, v1.1, review commission) · `ffcee7b8a` (S-review) · `e59ab47b7` (PBDIGIT-330; out of scope) |
| PO-ORG-MEM-01 | [D] approved 2026-11-03; record committed `c632fe45d` (Session B); Decision A verbatim (§0.3); ARB validation of consequences **pending** (POMEM §3) |
| Working tree | other sessions' changes are not touched |

---

## 2. Existing decisions and facts

### 2.1 Binding decisions

| ID | Content | Authority and status | Source |
|---|---|---|---|
| D-ACT10 | the Membership **aggregate** must not absorb Governance competence, committee participation or unrelated organisation structures | RATIFIED; a context-level reading would be ARB-1 (pending) | ACT `:283-305`; DR07 `:312` |
| D-ACT11 | Governance "owns competence, organs, authority and organisational governance rules"; "No shared persistence is required" | RATIFIED | ACT `:309-349` |
| D-RAT | one Membership per (Person, Organisation); Organisation owns OrganisationAffiliation; AuthorityCheck supplied by Governance, Membership stores evidence only; units may map to "another organisation" (I5); evidence fields {actor, capacity, ruleRef, policyVersion, result, checkedAt} | RATIFIED; "changes only via an Architecture Decision / Change Request" | RAT `:3`, `:42-43`, `:72`, `:150`, `:156` |
| PO-DR07-01 | governance committees are organs with positions, appointments and authority; units distinct; records not classified | PO business decision 2026-11-02 | DR07 `:261-291` |
| Principal position (DR-07) | committee code represents organs **and** units ("BOTH") | Principal architectural position 2026-11-02 | DR07 `:222-224` |
| **PO-ORG-MEM-01 A / B** | §2.2 | **[D] APPROVED** 2026-11-03; recorded `c632fe45d`; ARB validation pending | POMEM |
| **D-4** | interim authority adapter: "mirrors the existing owner / admin policy, records `AuthorityEvidence` (actor, role, policy source, time), marked interim and replaceable by the Governance-owned implementation" | **DECIDED by the Human ARB as, or on behalf of, the Governance owner** (V-1) | S2C `:293` |
| BR-4, BR-12 (K′), BR-13, BR-13-S, BR-13a, BR-14 | as v1.1 | PO, 2026-11-02 | BR `:26-40` |
| PBDIGIT-30 B1 | Working Organisation routing | APPROVED | `docs/publicdigit/business_rules/20260806_0818_how_many_organisation.md:61-75, 145-153` |
| INV-ORG-SCOPE-1 | owning = Working Organisation; fail closed | APPROVED principle; ARB pending | CMAP `:89`, `:121` |
| BDR-03 | "Appointment (Authority)" owns Mandate truth; relation to Governance unresolved (ARB-8) | FROZEN programme | BDR `:16`; CMAP `:79-80` |
| ADR-004 | Organisation and Membership listed as Candidate | Accepted | ADR-004 `:30`, `:32` |

### 2.2 PO-ORG-MEM-01 Decision A (verbatim, POMEM `:20-30`)

> The Organisation owns its Membership domain.
>
> The Organisation's Constitution (statutes) establishes the competent organisational organs, offices and positions, and defines the authority and responsibilities under which Membership decisions are made.
>
> A competent organisational organ or authorised office holder makes Membership decisions within its constitutional competence or valid delegation.
>
> An organisational Membership Responsible Person coordinates and administers Membership operations. This person may be the General Secretary, Membership Secretary or another person appointed by the competent organisational body. Administrative responsibility alone does not confer authority to make Membership decisions.
>
> PublicDigit implements the authorised organisational rules, enforces the applicable Membership invariants, and records Membership facts and decision evidence. PublicDigit's technical ownership and platform access permissions do not constitute organisational authority.
>
> The Organisation's authority remains independent of the software used to administer Membership.

**What this baseline takes from Decision A, and what it does not.**

| Taken as decided [D] | Not decided by Decision A (stays open) |
|---|---|
| the Organisation owns its **Membership** domain (business ownership) | context ownership of the Constitution, and its representation (ARB; POMEM §3) |
| the Constitution establishes organs, **offices and positions** | whether ACT §11 covers positions (C-2), and whether Decision A adds to ACT §11 or is its business reading (ARB; POMEM §3) |
| Membership decisions are made by **a competent organ or an authorised office holder**, within constitutional competence or valid delegation | the minimum authority evidence (ARB; POMEM §3; P11, not touched) |
| administrative responsibility ≠ decision authority | conflicts with D-1…D-5, E-1…E-5, GOV-END B1 (ARB; POMEM §3) |
| **business ownership of Membership ≠ PublicDigit's technical ownership**; platform permissions are not organisational authority | the future of D-4 (§5.2) |
| the Organisation's authority is independent of the software | — |

### 2.3 What the code does today (corrected)

v1.1 §2.3 stands, with these corrections:

| Concern | Fact | Source |
|---|---|---|
| Governance setup state | `governance_status` ∈ pending_setup, governance_configured, active, suspended. The only write is `governance_configured`, by the owner role. It gates committee creation (`allowCommitteeCreation`). It also feeds a **deprecated temporary shim** that maps `active` to a structure epoch. `activateGovernance` has no caller, so no code path writes `active` | [F] `GovernanceSetupController.php:102`; `OrganisationPolicy.php:62-84`; `OrganisationGovernanceStatus.php:14-16`; `CommitteeStructureEpochContext.php:28-29`; grep |
| Geography factories | `forWorldwideOrg()`: four levels; no caller in `app/`; called by three test files | [F] `GeographicStructure.php:25-33`; S-F5(c) |
| Constitutional code | Governance: `ConstitutionalBasis` (article + optional text, no version); `AuthorityAssignment`, `DelegationScope`, `AuthorityDelegated`. **Membership:** `ConstitutionalArticleReference` and the `DoctrineArtifact` store (version, effective period, hash, approvedBy, supersession; in-memory registry, no caller outside its folder) | [F] S-F2 |
| Statutes | **no statute is held in the repository and none has been requested** | [F] PRI `:414`; XD-15 proposal `:138` |
| Evidence fields | `ruleRef`, `policyVersion` (and `actor`, `capacity`) are mandatory non-empty strings; a seventh field `competenceVerified` exists (PREP OQ-13) | [F] `app/Contexts/Membership/Domain/Organisational/AuthorityEvidence.php:17-26` |

---

## 3. Deliverable A: Organisation definition

### 3.1 Candidate definition [P]

This is a candidate only. The business meaning is the PO's decision (ODA-M-2); the vocabulary is the ARB's (O-14).

> **Organisation.** A collective body with its own identity, purpose and rules, which decides its internal affairs through its competent organs or authorised office holders, under its own constitution (statutes), within the law and any external rules that bind it.
> - Its identity persists through changes of name, structure, constitution, leadership and status. It does not depend on PublicDigit, and its authority is independent of the software it uses.
> - Membership, Governance, Elections and other capabilities serve the Organisation. None of them constitutes it.
> - A platform record (the bootstrap organisation, demo data) is not an Organisation in this sense.
> - How PublicDigit isolates an Organisation's data (tenancy) is not part of the definition.

**Changes from v1.1 and why:**

| Change | Basis | Consequence |
|---|---|---|
| "through its own organs" → "through its competent organs or authorised office holders" | [D] Decision A (POMEM `:24`) | the definition does not narrow decision authority to organs; offices that decide alone fit |
| "its authority is independent of the software it uses" | [D] Decision A (POMEM `:30`) | principle A of the Principal's authorisation is part of the definition |

**Stated limits of the candidate (not decided):**
- [OQ] **As drafted, the candidate excludes an organisation without written statutes** ("under its own constitution (statutes)"). Whether such organisations are in scope is a PO question (ODA-M-20). If the PO includes them, the phrase becomes "under its own rules, written or not", and Decision A's "Constitution (statutes)" must then be read as "its governing rules". That reading would be a PO clarification, not an inference made here.
- [OQ] Whether companies, foundations without members and unions are "collective bodies" in scope (ODA-M-20).

### 3.2 Distinctions

| Distinction | Assessment | Basis |
|---|---|---|
| Organisation ≠ Person, Membership, Election, Tenant | holds | [D] ACT §11; RAT §1 |
| Organisation ≠ Governance (the context) | holds | [D] ACT §11 |
| Organisation ≠ OrganisationAffiliation / platform access | holds | [D] BR-2 / BR-3; Decision A ("platform access permissions do not constitute organisational authority") |
| **business ownership of Membership ≠ PublicDigit's technical ownership** | holds | [D] Decision A; POMEM §2 (technical Membership owner: technical responsibility only) |
| Organisational unit ≠ governance organ | holds | [D] PO-DR07-01 |
| Office holder ≠ organ; both may decide | holds | [D] Decision A |
| Organisation ≠ platform record | holds | [D] BR-12; vocabulary ODA-M-3 |
| A section may be a unit or a separate Organisation | open | [D] RAT `:150`; [OQ] ODA-E-1 |

### 3.3 Identity and lifecycle

v1.1 §3.3 stands, with one correction. The **governance setup state** is an owner-driven setup marker that gates committee creation. Its third "meaning" in v1.1 (structure epoch) is a deprecated shim (S-F5(b)). [I] Through application code alone, the gate cannot open.

No lifecycle state is an established requirement. The organisational lifecycle, the platform service relationship and the governance setup state remain three distinct things [I].

---

## 4. Deliverable B: conceptual model

Changes from v1.1 §4:
- decisions by organs **or** authorised office holders;
- offices shown as established by the Constitution (Decision A), with their relation to organs open (ODA-E-2);
- operating area removed until there is evidence.

```text
                         Person [ID, E]
                            │
       ┌────────────────────┼──────────────────────────┐
       │ 0..1 per Org       │ 0..n per Org             │ holds (Appointment → Tenure) [U ARB-8]
       ▼                    ▼                          ▼
 Membership [MEM, E]   OrganisationAffiliation     Office / Position [established by the
       │               [owner "Organisation" E;     Constitution: D, Decision A; context P / U ARB-8;
       │                context / store U BR-9]     relation to organs: U ODA-E-2]
       │ of 1                                         │ may decide (within competence or delegation) [D A]
       ▼                                              │
 ┌──────────── ORGANISATION (business concept) ───────┴────────────────────────┐
 │  own identity · purpose · rules                                              │
 │  decides through competent ORGANS or authorised OFFICE HOLDERS [D A]         │
 │  organisational lifecycle [P]                                                │
 │  Organisation Constitution (statutes): "the Organisation's" [D A, possessive]│
 │       versions [P]; record placement O-1 / O-2 / O-3 [U, ODA-M-11, ODA-M-13] │
 │  Organisational Units 0..n [E distinct; ownership U O-16]                    │
 └──────────────────────────────────────────────────────────────────────────────┘
       │ constitutes                      │ scope of
       ▼                                  ▼
 Governance Organ [GOV, E]           Election [ELE, E] ── electoral area (free text) [OQ]
   scope: Organisation / unit(s) /
   function [P; ODA-E-2]
   jurisdiction ──► GeoUnit [GEO, P] ◄── unit responsibility area [P]

 Outside the business concept:
   Platform service relationship · capability enablement · tenant · Working Organisation [PLT; ODA-M-14]
   Platform records (bootstrap, demo) [not Organisations; ODA-M-3]
```

---

## 5. Constitutional foundation

### 5.1 Concepts and citation

| Concept | Meaning | Status |
|---|---|---|
| Constitutional source record | the document(s), versions, effective periods, adoption evidence | [P] |
| Interpretation ruling | a ruling by the body the statutes name on what a provision means | **[P]**; no artefact evidence beyond questionnaire D5 (S-F7) |
| Authority evidence | the result of an authority check for one act {actor, capacity, ruleRef, policyVersion, result, checkedAt} | [D] fields ratified (RAT `:72`); referents not fixed (K-14) |
| Operational rule | a capability's own rule derived from the source, citing it | [P] |
| **Candidate fifth: organ resolution / decision record** | minutes, resolutions, delegation instruments, appointment resolutions: the record that the competent organ or office holder decided | **[OQ]** PREP OQ-12 (`:432`). The set of concepts is **not declared closed** |

**Citation `(document, article, version)`: three cases, kept apart.** The frozen evidence semantics are **not changed** here.

| Case | What it means | Needs |
|---|---|---|
| (i) **Use**: writing a document-article-version citation as the *content* of the existing `ruleRef` and `policyVersion` strings | no schema or field change; [F] the fields are free non-empty strings (`AuthorityEvidence.php:17-26`) | nothing technical. But see (ii) |
| (ii) **Declare**: stating that `ruleRef` / `policyVersion` *denote* a constitutional document, article and version | fixes a referent that RAT `:72` leaves open; [I] that interprets a frozen contract | an ARB reading at least; whether it counts as a Change Request is ODA-M-19 |
| (iii) **Structure**: separate fields, or making the citation mandatory | changes the ratified contract | a **Change Request** (RAT `:3`; PREP OQ-12) |

[OQ] The document identity part of the citation depends on whether "the constitution" is one document or several (ODA-E-3).

### 5.2 Operating before statutes; D-4

- [F] No statute is held in the repository, and none has been requested (PRI `:414`; XD-15 proposal `:138`).
- [I] Current organisations therefore operate in PublicDigit without any statute being held. This is an inference from the absence of records, not a statement about what statutes the organisations have.
- [D] D-4 lets the existing owner / admin policy authorise admissions, "marked interim and replaceable by the Governance-owned implementation". It was decided **by the Human ARB as, or on behalf of, the Governance owner** (S2C `:293`; V-1).
- [D] Decision A states that platform access permissions do not constitute organisational authority, and POMEM §3 records D-4 as unchanged.
- **Tension (stated, not resolved).** A rule that "no decision may claim constitutional competence until statutes are recorded" either keeps D-4 acts as explicitly **interim, non-constitutional provenance**, or stops them. Calling elections in election-only organisations raises the same question.
- **New sub-question (S-F4).** `ruleRef` and `policyVersion` are mandatory strings. What do they hold for a D-4 act before any statute exists? The answer must not misstate a platform policy as a constitutional rule.
- **D-4 is not terminated, extended, redefined or implemented here.** Authority: ODA-M-4a (business stance) and ODA-M-4b (evidence representation; the authority that decided D-4, and ODA-M-19 if a Change Request is needed).

### 5.3 Placement of the source record: O-1, O-2, O-3 (no recommendation)

**Common ground under every option:**
- [D] "The Organisation's Constitution" (Decision A, possessive). Decision A concerns Membership; **it does not decide who holds the record or in which context**. This baseline does **not** infer that the Organisation is the bounded-context owner of the Constitution.
- [D] Governance owns competence, organs and authority (ACT §11). [I] Under every option, amendment validity, interpretation of competence and authority checks are Governance questions, and each capability derives its own operational rules.
- [OQ] Whether "organisational governance rules" (ACT `:321`) includes the source text is an ARB reading (ODA-M-11). **This baseline does not treat Decision A as resolving it.**

| | **O-1: Governance holds the source record** | **O-2: an Organisation context holds the source record** | **O-3: no PublicDigit-held source record** |
|---|---|---|---|
| Content | the versioned record lives in Governance and is published to other capabilities | the versioned record lives in an Organisation context, as evidence only | capabilities cite external documents by `(document, article, version)`; PublicDigit holds no text |
| Needs | [I] an ARB reading that ACT `:321` includes source *text*. This stretches the wording: ARB-2 reads §11 as organs and competence (DR07 `:313`). [I] Also a published language from Governance to Membership and Election | (a) an established Organisation context (**shared cost**: needed under ODA-M-13 by any outcome that creates the context, not only by O-2). (b) [I] a Governance → Organisation authority-evidence flow for amendments, creating an Organisation ⇄ Governance cycle that must be recorded explicitly | [I] an agreed citation convention (§5.1) and a retention policy for external references |
| For | [I] no new context. [F] **mixed** precedent: `ConstitutionalBasis` in Governance; but `ConstitutionalArticleReference` and the versioned `DoctrineArtifact` store sit in Membership (S-F2) | [I] the constitution also governs purpose, membership, finance and elections, so a record outside Governance serves them without routing through Governance | [F] matches today's state (no statute held). [I] smallest model; no ownership question until evidence |
| Against | [I] Governance serves consumers whose rules it does not apply (a dependency concern) | [I] risk of two owners of constitutional truth unless the Organisation context holds the text as evidence only, with no interpretation and no derived rule | [I] PublicDigit cannot guarantee that a cited version stays retrievable, so the retention concern of PREP §8.3 (itself [P]) is not met; disputes over which text applied are harder to settle |
| ACT §11 | preserved if the ARB reads it to include the source text | preserved if the ARB reads it as the derived rules only | not engaged |

**Sequence for the ARB (unchanged in kind):**
1. the separation of concepts (ODA-M-12a);
2. the ACT §11 reading (ODA-M-11);
3. placement only after at least one real statute has been read (ODA-E-3).

O-3 may also serve as an explicit interim state until then [I]. That is an observation, not a recommendation.

---

## 6. Structure and governance

- **Units vs organs.** [D] PO-DR07-01. That the legacy `committees` table holds both is the Principal's position (DR07 `:224`). Record classification is O-16 (not resolved here).
- **Who decides.** [D] **A competent organ or an authorised office holder**, within constitutional competence or valid delegation (Decision A). Neither is privileged in this baseline.
- **Offices.** [D] Decision A: the Constitution establishes offices and positions. [OQ] Whether an office exists outside any organ, and the scope of organs, are evidence questions (ODA-E-2). Decision A gives weak support for offices alongside organs.
- **Positions and ACT §11.** [D] named in PO-DR07-01 and Decision A (business rulings). **Not assigned to ACT §11 here** (C-2).
- **Appointment, Tenure, Mandate.** [P] vocabulary; context placement [U] ARB-8 (BDR-03).
- **Delegation.** [D] "valid delegation" (Decision A); [F] Governance code has delegation classes; the model is [P].
- **Administrative responsibility ≠ decision authority.** [D] Decision A (Membership Responsible Person).

**No Governance responsibility moves into an Organisation context.**

---

## 7. Capabilities

v1.1 §7 stands. Governance optionality is ODA-M-7. Capability enablement placement is ODA-M-14, and `uses_full_membership` is overloaded [F]. No new dimension is proposed.

---

## 8. Geography

### 8.1 One reference concept, four link kinds

| | Meaning [P] | Owner | Evidence |
|---|---|---|---|
| **Reference concept: GeoUnit** | an administrative area; official or organisation-custom | Geography [P] | [F] nullable `organisation_id`; `admin_level` CHECK 0–10 |
| Link 1: operating area | where the Organisation works | [P]; may be derived from units; **not in the model diagram until there is evidence** | [F] `geographic_scope`, `allowed_countries` |
| Link 2: unit responsibility area | the GeoUnits a unit covers | O-16 [U] | [P] |
| Link 3: governance jurisdiction | an organ's authority area over time | Governance [P] | GMAP `:67`; the arbitration use of "jurisdiction" must be named |
| Link 4: electoral area | the area a post is elected for; the voter's region | Election (today, free text) | [F] `posts.state_name`, `posts.is_national_wide`, `users.region`; [OQ] whether it should reference GeoUnits |

The **organisational unit** itself is not a geographic concept. It appears here only through link 2.

### 8.2 Facts and scope

- [F] The Nepal four-level hierarchy is the code default. `forWorldwideOrg()` has no production caller and is covered by three tests.
- [F] The phrase "geography-based committee governance platform for NRNA" comes from a legacy AI brief (`architecture_legacy/committee/geo_committe_formation/20260513_1257_geo_unit_matrix.md:5-12`). "Geography-based jurisdiction" appears in brainstorming (`docs/publicdigit/membership/architecture/brainstorming/cross_domain_brainstorming/20261011_0935_analysis_report.md:606`). Neither is a decision.
- Scope options A / B / C (as v1.1 §8.2): **no choice made** (ODA-M-5).

---

## 9. Relationships between Organisations

v1.1 §9 stands: discovery only (BR-13a).
- Merger is [P] pre-ratification analysis.
- Succession is [OQ] (ODA-M-10).
- The definition admits federation members and organisations bound by external rules without designing anything.

---

## 10. Conflicts (C-1…C-14; none resolved)

v1.1 §10 stands, with these changes:

| # | Change in v1.2 |
|---|---|
| C-2 | also named in Decision A ("offices and positions"), which is a business ruling. ACT §11 still does not name positions. **Stands** |
| C-7 | corrected (S-F5(b)): two meanings plus a deprecated shim; no code path writes `active` |
| C-12 | "differently scoped and unreconciled" (S-F5(e)) |
| C-15 (new) | **D-4 authority:** PREP `:299` labels D-4 "PO DECIDED"; the source S2C `:293` says decided by the Human ARB as, or on behalf of, the Governance owner. Authority: the owners of those records. Not resolved here |

---

## 11. Strategic DDD

### 11.1 Classification: provisional

As v1.1 §11.1, with one wording change: the existing classifications are **differently scoped and unreconciled** (AIP-14 at platform-system level; BDR-08 at subdomain level; the 2026-10-09 review over candidates; P-ODA-8). Organisation as foundational supporting stays provisional. No core candidates are named. ODA-M-8 (PO strategy), then the ARB.

### 11.2 Organisation context

As v1.1 §11.2: **not shown to be a coherent bounded context**. The review's smallest candidate is recorded as input for ODA-M-13. ODA-M-13 also carries O-2's shared cost (§5.3).

---

## 12. Relationships: input to CMAP change control

As v1.1 §12, with the geography row corrected: **four link kinds** from GeoUnit to Organisation, Governance and Election (§8.1). No shared persistence and no new dependency are introduced. No context map is created or changed.

---

## 13. Ubiquitous language (changes only)

| Term | Change in v1.2 |
|---|---|
| Organisation | candidate §3.1, now including "competent organs or authorised office holders" |
| Office | [D] established by the Constitution (Decision A); relation to organs [OQ] ODA-E-2 |
| Authorised office holder | **added**: a person holding an office who may make decisions within constitutional competence or valid delegation (Decision A) |
| Governance setup state | two meanings plus a deprecated shim |
| Interpretation ruling | [P] |
| Organ resolution / decision record | **added** as [OQ] (§5.1) |
| Geographic link kinds | four (§8.1) |

All other entries are as v1.1 §13. All remain inputs to O-14.

---

## 14. Reference organisations

v1.1 §14 stands: only NRNA is concrete; the risk of archetype overfitting (R-17); the questionnaire improvements Q-1…Q-7 (unapproved proposal). One addition: the questionnaire's R-block (PREP §5.6) and blocks A and G should ask, **per decision, whether an organ or an office holder decides, and on which basis (competence or delegation)**. That follows Decision A's "organ or authorised office holder".

---

## 15. Decision and evidence register

### 15.1 Principal / PO

| # | Question | Authority | Status | Evidence | Changed from v1.1 |
|---|---|---|---|---|---|
| ODA-M-1 | record PO-ORG-MEM-01 A / B | PO | **closed: recorded `c632fe45d`; Decision A verified verbatim (§0.3)** | — | re-scoped |
| ODA-M-2 | business meaning of "Organisation" (candidate §3.1) | PO, then ARB (O-14) | open | none for meaning | candidate widened (office holders) |
| ODA-M-3 | does "Organisation" exclude platform records (bootstrap, demo)? | PO (vocabulary) | open; **largely decided by BR-12** for the bootstrap row | — | narrowed |
| ODA-M-4a | business stance on operating before statutes; future of D-4 | PO **together with the authority that decided D-4** (V-1) | open | — | split |
| ODA-M-5 | geography scope | PO | open | customer list | — |
| ODA-M-6 | "no universal governance model" as a platform rule | PO | open | — | — |
| ODA-M-7 | Governance capability optional? | PO | open | — | — |
| ODA-M-8 | product strategy for classification | PO, then ARB | open | business strategy | — |
| ODA-M-9 | reference organisations and representatives | PO | open | — | — |
| ODA-M-10 | §9 discovery only; succession [OQ] | PO | open | — | — |
| ODA-M-20 | are organisations without written statutes, companies, foundations, unions in scope? | PO | open | — | numbered (was unnumbered) |
| — | technical owner of a future Organisation context | PO | **deferred until ODA-M-13** | — | moved out of the register |

### 15.2 ARB (and Change Request)

| # | Question | Authority | Evidence | Changed from v1.1 |
|---|---|---|---|---|
| ODA-M-4b | how D-4 acts are evidenced before statutes exist (what `ruleRef` / `policyVersion` hold) | ARB; Change Request if the contract changes (ODA-M-19) | — | split from M-4 |
| ODA-M-11 | does ACT §11 "organisational governance rules" include the source text? (also POMEM §3 item 1) | ARB | none | — |
| ODA-M-12a | adopt the separation of concepts (source, interpretation, authority evidence, operational rule; candidate fifth: organ resolution record) | ARB (vocabulary act) | none | split |
| ODA-M-12b | the citation convention: case (i) / (ii) / (iii) of §5.1 | ARB; **(iii) is a Change Request** | **ODA-E-3** (one document or several) | split; evidence dependency added |
| ODA-M-13 | Organisation context: status and minimal scope | ARB | ≥ 1 statute | carries O-2's shared cost |
| ODA-M-14 | placement of platform service relationship and enablement | ARB | XD-15 | — |
| ODA-M-15 | ARB-1 / ARB-2 / ARB-8, including positions, appointments and tenure | ARB | DR-07a report | — |
| ODA-M-16 | O-14 vocabulary | ARB | — | — |
| ODA-M-17 | §12 as input to CMAP change control | ARB (CMAP owner: PO) | — | — |
| ODA-M-18 | conflicts of Decision A with D-1…D-5, E-1…E-5, GOV-END B1 (POMEM §3 item 4) | ARB | statutes (PREP §7.3) | **new** |
| ODA-M-19 | who decides a Change Request to the ratified Membership evidence contract (RAT `:3`), and whether case (ii) of §5.1 already counts as one | Principal / PO to name the authority | — | **new** |

### 15.3 Mapping of the PO-ORG-MEM-01 pending ARB items (POMEM §3)

| POMEM §3 item | Covered by |
|---|---|
| whether the decision adds to ACT §11 or is its business reading | ODA-M-11, ODA-M-15 |
| where the Organisation Constitution is represented | ODA-M-11, ODA-M-13 (O-1 / O-2 / O-3) |
| the minimum authority evidence | ODA-M-12b, ODA-M-4b; PREP OQ-6, OQ-12; P11 (not touched) |
| conflicts with D-1…D-5, E-1…E-5, GOV-END B1 | ODA-M-18 |

### 15.4 External evidence

| # | Question | Feeds |
|---|---|---|
| ODA-E-1 | is a given section a unit or a separate Organisation? | C-4 |
| ODA-E-2 | organ scope; offices outside organs; **per decision, organ or office holder, and on which basis** | §4, §6, §14 |
| ODA-E-3 | amendment procedure and effective dates; one document or several | §5.1, §5.3, ODA-M-12b |
| ODA-E-4 | external binding rules | §3.1 |
| ODA-E-5 | dormancy, dissolution, liquidation, succession | §3.3 |
| ODA-E-6 | same structure in every country? | §8 |

Unchanged and untouched: O-16 / DR-07b, DR-07a, CQ-10, P11, XD-15, BR-9, INV-ORG-SCOPE-1, D-4.

---

## 16. Risks

v1.1 §16 stands, with these changes:

| ID | Change |
|---|---|
| R-12 / R-19 | the S-review is also a Claude review; **the independent human or non-Claude review is still outstanding** |
| R-14 | now also: an option set missing O-3 (corrected) |
| R-16 | now also: the mandatory evidence strings may misstate D-4 acts as constitutional (§5.2) |
| R-20 (new) | **identifier collisions** across records (M-n, E-n) lead to wrong cross-references at the ARB. Mitigation: the ODA- prefix (§0.4) |
| R-21 (new) | **authority mis-attribution of D-4** (C-15): changing D-4 through the wrong authority |

---

## 17. Prohibited until further authorisation

Unchanged from v1.1 §17:
- no code, schema, migration, aggregate or domain-model change;
- no bounded context established;
- no context map created; CMAP and PRI unchanged;
- no ADR, BDR, ratified record, ACT §10 / §11 or Membership-architecture change;
- O-1 / O-2 / O-3 not decided;
- no PO or ARB decision made;
- D-4 not terminated, extended, redefined or implemented;
- no federation design;
- no contact with organisations;
- no tests, migrations or production queries;
- no commit or push;
- no tactical design.

PBDIGIT-330 is not part of this baseline.

---

## Traceability

| Item | Reference |
|---|---|
| v1.0 | sha256 `4617c571…c740`; commit `f6471aaf0` |
| v1.1 | sha256 `7ee60dac…6dc8`; commit `f6471aaf0` |
| IR-ODA-01 (separate Claude agent) | sha256 `4fe75edf…3c01`; commit `f6471aaf0` |
| S-review (Claude subagent, Sonnet) | commit `ffcee7b8a` |
| PO-ORG-MEM-01 decision record | POMEM; commit `c632fe45d`; Decision A verified (§0.3) |
| Authorisation | "Principal authorisation — ODA-01 v1.2" (2026-11-06), conditions 1–11, principles A–C |
| Sources opened for this correction | POMEM; S2C `:293`; PREP `:299`, `:301`, `:432`; PRI `:414`; XD-15 proposal `:138`; RAT `:3`, `:72`; DR07 `:313`; AR/`2026-11-03-publicdigit-architecture-assessment.md:104`; `AuthorityEvidence.php`; `Provenance.php`; the Membership `Constitutional/Doctrine/` folder; `ConstitutionalScope.php`; `CommitteeStructureEpochContext.php`; `GovernanceCapabilityContextFactory.php`; `OrganisationPolicy.php`; `GovernanceSetupController.php`; the `forWorldwideOrg` test callers |
| Not changed | code, schema, tests, ADRs, BDRs, CMAP, PRI, ratified records, business rules, POMEM, v1.0, v1.1, IR-ODA-01, S-review. Nothing committed |
