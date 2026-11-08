# Organisational baseline to tactical design: roadmap plan

| | |
|---|---|
| **Status** | **PROPOSED.** Written on the Principal's instruction ("write the plan document", 2026-11-08). Under EP-01, **each phase requires the Principal's explicit authorisation** before it starts. This plan authorises no phase by itself, and decides no business, governance or architectural question |
| **Author** | Claude, Architecture Session A |
| **Date** | 2026-11-08 |
| **Snapshot** | branch `election-flow-updated`, HEAD `e8cb40095` |
| **Placement** | ES-004.2 (`docs/plans/YYYYMMDD-HHMM-<topic>-plan.md`) |
| **Supersedes** | none. It builds on the ODA-01 v1.3 correction plan (`docs/plans/20261107-0239-oda-01-v1.3-traceability-and-correction-plan.md`), whose documentary scope is closed (`e8cb40095`) |

**Labels.** [F] fact · [D] decision · [P] proposal · [H] hypothesis · [I] inference · [OQ] open question.

---

## 1. Objective

Establish, in this order:
1. **an accepted Organisational Baseline Architecture**: what an Organisation is, how it is structured, who holds which authority, and on what evidence;
2. **a business-domain baseline**: what organisations actually do;
3. **an accepted strategic DDD design**: subdomains, bounded contexts, relationships;
4. **tactical design** for each accepted context, then implementation.

**Governing principle:** understand the organisation first, then derive the software boundaries. Progress is measured by evidence-backed understanding, not by the number of contexts or diagrams.

## 2. Background

- [F] **Settled foundation:**
  - the Membership architecture (ratified 2026-10-08, frozen);
  - PO-DR07-01 (committees are organs; units are distinct);
  - PO-ORG-MEM-01 Decision A / B (`c632fe45d`);
  - PO-ORG-MEM-01-J1 / J3 / J4 (`78f87094c`);
  - D-4, decided by the Human ARB as or on behalf of the Governance owner;
  - PO platform rules D-1…D-3, D-5, E-1…E-5, GOV-END B1;
  - business rules BR-1…BR-14.
  
  Source: `docs/publicdigit/architecture/strategic-design-status.md` §3.
- [F] **ODA-01 status:**
  - v1.2 is a **proposal**;
  - its documentary corrections are closed (`e8cb40095`);
  - v1.3 is not created;
  - **Gate F** (independent review) is open.
- [F] No constitutional source material and no authoritative appointment or delegation evidence is on file. Organisation is a **Candidate** context only (ADR-004).
- [F] Strategic DDD discovery has **not** started.

## 3. Scope

**In scope:** the organisation and governance domain (Identity, Organisation, Membership at its boundary, Governance, Geography, Authorisation), Election at its boundary only, from baseline to tactical design.

**Out of scope:**
- EPIC-002 (accepted separately);
- the Social Contribution initiative (PBDIGIT-331…335; separate workstream);
- any change to the ratified Membership architecture except through a Change Request.

## 4. Design decisions and standing principles

1. **Three meanings of "authority", never merged:**
   - **(A)** real-world organisational authority belongs to each organisation's competent organs and office holders, under its rules and valid delegations (PO-ORG-MEM-01 A);
   - **(B)** the **Governance** bounded context is the software model that owns competence, organs and authority (ACT §11);
   - **(C)** platform permissions govern technical access only and create no organisational authority (PO-ORG-MEM-01 A).
2. **ODA-01 is the discovery input** to the Organisational Baseline Architecture. The new deliverable supersedes ODA-01 for acceptance purposes, so there are never two competing baseline records [P, to be confirmed when Phase 3 is commissioned].
3. **Practical knowledge is a hypothesis source, not evidence.** The Principal's political-party experience feeds hypotheses [H]. Documents establish facts [F]. Competent bodies make decisions [D].
4. **Three-organisation evidence strategy:** NRNA first, then two organisations differing in legal form and geography. Patterns are read only after three organisations.
5. **Open questions stay open** until their owner decides. No authority is assigned that the records do not establish.
6. **Gate F passing is not baseline acceptance.** Acceptance is a separate decision.
7. **A commit gives citation stability, not validity.**

## 5. Phases

| Phase | Deliverables | Depends on | Decision owner (as recorded; else [OQ]) | Acceptance criterion | State |
|---|---|---|---|---|---|
| **0** ODA-01 documentary closure | correction plan corrected; resumption record updated | — | Principal | committed `e8cb40095` | ☑ done |
| **1** Gate F independent review ∥ | review of ODA-01 v1.2 using the r3 bundle (`~/ir-oda-01-v1.2-review-handover-r3/`); reconciliation of its findings; Principal's decisions on them | reviewer chosen | Principal commissions and decides; the reviewer only reports | report received; each finding classified as must-fix / open with owner / PO-or-Principal decision / ARB | ☐ |
| **2** External evidence ∥ | NRNA request sent (draft in Appendix A); documents registered (§7); two further organisations selected | PO sends | PO | at least one authoritative, versioned statute registered | ☐ |
| **2b** Authority map: open items ∥ | ARB vs Human ARB identity; Change Request authority (B-4 / ODA-M-19); D-4 replacement authority (DS-4); independent review of the DR-07a findings | — | Principal; Governance for DS-4; [OQ] for B-4 | each item decided, or formally recorded as open with an owner | ☐ |
| **3** Organisational Baseline Architecture v1.0 | commissioned deliverable (name, version, evidence register, acceptance record); definition of Organisation (ODA-M-2), scope (M-20), platform records (M-3); structure and relationship model (ODA-E-1); organ / unit classification incl. legacy `committees` (O-16 / DR-07b); authority, responsibility and delegation model; constitutional source-record placement (ODA-M-11); open-decision register | Phase 1; Phase 2 (at least one statute) | PO (definition, classification); ARB (vocabulary, placement); Principal (acceptance); [OQ] whether ARB acceptance is also required | independent review passed; acceptance recorded | ☐ |
| **4** Business-domain baseline (partly ∥ with 3) | capability inventory; responsibility matrix; stakeholders; ubiquitous language (O-14); scope and objectives (ODA-M-8); reconciliation of classifications (C-12) | Appendix B steps C-1, C-2 can start now; finalised after Phase 3 | PO; ARB (O-14) | accepted together with Phase 3 | ☐ |
| **5** Strategic DDD discovery | candidate subdomains (core / supporting / generic where evidenced); candidate bounded contexts; ownership boundaries (kept separate from business ownership); relationships, incl. the Organisation ⇄ Governance and Membership ⇄ Governance cycles; DR-07a and ARB-1…8 (after the DR-07a review), XD-4, XD-5, INV-ORG-SCOPE-1, INV-AFF-1, O-8…O-10 | Phases 3 and 4 accepted | proposals: Session A; decisions: ARB | candidate model complete, open items listed | ☐ |
| **6** Strategic architecture review | ARB decision record; context map under change control; records superseding ADR-004 for decided contexts; independent review | Phase 5 | ARB; CMAP owner (PO); Principal | strategic baseline accepted (status record §6 T7) | ☐ |
| **7** Tactical design (per context, each under EP-01) | entry assessment; aggregates, invariants, events, ports. Governance first (replaces D-4 via DS-4); Organisation only if Phase 6 establishes it; Membership frozen (only COR-3, Slice 2b, P11); integration contracts; Change Requests where the frozen evidence contract is touched (ODA-CR-1…5) | Phase 6 | ARB; Governance owner (DS-4); Membership owner; Change Request authority [OQ] | an approved EP-01 plan per context | ☐ |
| **8** Implementation | per slice: plan → approval → RED → GREEN → acceptance; legacy committee migration (DR-07a freeze options) | Phase 7 | PO per slice | slice acceptance records | ☐ |

∥ = runs in parallel. Phases 1, 2 and 2b can start now; each needs its own authorisation.

## 6. Task checklist

**Now (Phases 1, 2, 2b):**
- ☐ Principal chooses the Gate F reviewer: a human architect or a different model family.
- ☐ Principal hands over the r3 bundle (`~/ir-oda-01-v1.2-review-handover-r3/`; archive sha256 `86902007…`).
- ☐ PO supplies the NRNA contact and mandate, the request language and confidentiality terms; the PO adapts and sends Appendix A.
- ☐ PO selects two further reference organisations (criteria in §7).
- ☐ Principal commissions the independent review of the DR-07a findings.
- ☐ Principal decides, or formally records as open: ARB vs Human ARB; B-4 / ODA-M-19; D-4 replacement authority.
- ☐ Principal (with Session A) starts Appendix B steps C-1 and C-2 (hypothesis register; capability inventory).

**After Gate F:**
- ☐ Session A reconciles the reviewer's findings with IR12 and CSR12; the Principal decides on them.
- ☐ Principal decides whether to create ODA-01 v1.3 (gate D), or to use ODA-01 v1.2 plus the correction plan as the Phase 3 input.

**Housekeeping:**
- ☐ Plan `:283` "new M-21 and M-22" → aliases (at the next authorised plan edit).
- ☐ Push decision for `e8cb40095` and the earlier ODA-01 commits (Principal).

## 7. Evidence acquisition and traceability (Phase 2)

**Receipt checklist (per document):**
1. Date received, sender, channel.
2. SHA-256; stored copy (location: [OQ], the PO decides).
3. Title, version, effective date and adopting body, as stated in the document.
4. Evidence type: statute · registry extract · minutes / resolution · representative's statement.
5. The representative's authority, recorded as evidence.
6. Gaps marked **SILENT** (the document says nothing) or **UNKNOWN / NOT PROVIDED** (no document supplied).
7. No authority is presumed just because a representative supplied a document.

**Traceability:** an evidence register linking each finding to its source:

> finding → (document, article, version) → the ODA question it answers (ODA-E-1…E-6; questionnaire item) → status

**Selection criteria for the two further organisations:**
- a legal form different from NRNA's;
- a different geography or jurisdiction;
- written statutes with an amendment history;
- ideally one that belongs to a federation or umbrella body (to test the definition only; no federation design, BR-13a);
- an authorised representative available.

**The goal:** classify each concept as common, legal-form-specific, jurisdiction-specific, configurable, or a genuine modelling difference.

## 8. Progress

| Date | Progress |
|---|---|
| 2026-11-08 | Phase 0 done (`e8cb40095`). r3 review bundle built (`~/ir-oda-01-v1.2-review-handover-r3/`): r2 + J-rec + current POMEM and PREP; the Claude reviews of v1.2 and the documents derived from them are excluded. This plan written (PROPOSED) |

## 9. Risks

| Risk | Mitigation |
|---|---|
| Designing around one organisation (NRNA) | three-organisation strategy; patterns only after three |
| Practical knowledge mistaken for constitutional fact | hypothesis register; [H] until a document confirms it |
| Mixing real-world authority, the Governance context and platform permissions | the authority triple test (§4.1; Appendix B C-5) |
| Statutes not obtainable | record UNKNOWN; Phase 3 stays open; no defaults invented |
| The reviewer influenced by earlier Claude reviews | r3 excludes them (§8) |
| Same-family review chain | Gate F needs a human architect or a different model family |
| Two competing baseline records | ODA-01 is explicitly the input, superseded for acceptance (§4.2) |
| Open authority questions block Phases 3, 5 and 7 | Phase 2b runs in parallel; open items carry owners |

## 10. Open questions

| Question | Owner (as recorded; else [OQ]) |
|---|---|
| ARB vs Human ARB identity | [OQ] |
| Change Request authority for the ratified Membership architecture (B-4 / ODA-M-19) | [OQ] |
| Who may extend, replace or supersede D-4 | [OQ]; DS-4 classed as a Governance decision |
| B-8 (durable record of Principle A) | Principal (deferred) |
| B-7 (IR12 into the repository) | Principal (deferred) |
| Whether ARB acceptance is required for Phase 3 | [OQ] |
| The storage location for received evidence | PO |
| Definition of Organisation (ODA-M-2) and the other ODA-M / ODA-E items | as registered in ODA-01 v1.2 §15 and the correction plan |

## 11. Next actions

1. **Principal:** choose the Gate F reviewer and hand over r3.
2. **PO:** authorise and send the NRNA request (Appendix A), once the missing details are supplied.
3. **Principal:** authorise Phase 2b items and Appendix B steps C-1 and C-2.

---

## Appendix A: draft NRNA document request (for the PO to adapt and send; Session A sends nothing)

> **Subject:** Request for the authoritative statutes and organisational rules of [organisation name] for PublicDigit
>
> Dear [authorised contact],
>
> PublicDigit is designing how its platform represents organisations' membership, governance and elections. To do this faithfully, we need your organisation's authoritative rules rather than our assumptions. We would be grateful if you could provide the following, together with the details listed for each document.
>
> **1. Documents requested**
> 1. The constitution / statutes currently in force.
> 2. Earlier versions and the amendment history (or a list of amendments with their dates).
> 3. Rules of procedure, by-laws or regulations adopted under the statutes (for example membership, election, finance or committee regulations).
> 4. Statutes or rules of any regional, country-level or other subordinate bodies, if such bodies exist and have their own rules.
> 5. Any decisions that delegate authority (for example resolutions empowering an office holder or committee), where these are written down.
> 6. Registry extracts, if the organisation is legally registered anywhere.
>
> **2. For each document, please state**
> - its title and version identifier;
> - the date of adoption and the date it took effect;
> - the body that adopted it;
> - its authoritative language, and the status of any translation;
> - whether it is the current version.
>
> **3. About the person supplying the material**
> - your name and role;
> - the basis on which you are authorised to provide these documents (for example a resolution, a mandate or a statutory function).
>
> **4. If written rules do not exist** for some matter, please say so. "No written rule" is a useful answer, and we will not assume a default.
>
> We will use the material only for designing PublicDigit, and we will record exactly which version we relied on.
>
> Kind regards, [PO name, role]

**Still needed from the PO:**
- the contact and their mandate;
- the language of the request;
- confidentiality or data-handling terms;
- whether the operator organisation is also covered (ODA-M-3).

**Second step, after the documents arrive:** the detailed questionnaire (ODA-01 v1.2 §14.2 Q-1…Q-7; preparation document §5 blocks D, S, A, G, C, R; ODA blocks O, X).

## Appendix B: parallel discovery method (Phases 3 and 4)

| Step | Activity | Output | Label discipline |
|---|---|---|---|
| C-1 | Hypothesis register from the Principal's political-party experience: levels, units, committees, offices, typical decisions and processes (joining, renewing, electing, appointing, removing, delegating) | one row per hypothesis, source "practical knowledge, Principal" | [H] until evidence confirms it |
| C-2 | Capability inventory: what organisations actually do (membership administration, internal elections, appointments, dues, communication, discipline, policy-making …) | capability list | [H] or [F] by source |
| C-3 | Structure inventory: organisation, units and sections, governance organs, offices, separate organisations | element list with relationships | [F] only from statutes |
| C-4 | Cross-matrix: capability × structural element → who decides, who administers, who executes | responsibility matrix | every cell carries its evidence and label |
| C-5 | Authority triple test on every "authority" statement: (A) real-world organisational authority, (B) Governance bounded context, (C) platform permission | each statement tagged A, B or C | never merged |
| C-6 | Terminology register: meaning per organisation; common versus organisation-specific | input to O-14 | proposals; the ARB decides the vocabulary |
| C-7 | Evidence reconciliation as statutes arrive: confirm, revise or reject each hypothesis; record rejections | updated register | a hypothesis never silently becomes a fact |
| C-8 | Cross-organisation comparison, after three organisations | pattern table | patterns only after three |

C-1 and C-2 can start now. C-3, C-4 and C-7 follow as documents arrive. No classification is final before evidence and the competent decision exist.

---

## Traceability

- **Instructions:** "PublicDigit — Gate F and External Evidence Preparation" (Principal, 2026-11-08); "build r3 and write the plan document" (Principal, 2026-11-08).
- **Records:**
  - `docs/publicdigit/architecture/strategic-design-status.md` (§3, §4, §9a);
  - `docs/publicdigit/reviews/2026-11-03-organisation-po-org-mem-01-membership-ownership-decision-record.md`;
  - `docs/publicdigit/reviews/2026-11-07-organisation-po-org-mem-01-principal-clarifications-j1-j3-j4-decision-record.md`;
  - `docs/publicdigit/architecture/reviews/2026-11-06-organisation-domain-architecture-baseline-oda-01-v1.2.md`;
  - `docs/publicdigit/architecture/reviews/2026-11-06-ir-oda-01-v1.2-review-commission.md`;
  - `docs/plans/20261107-0239-oda-01-v1.3-traceability-and-correction-plan.md`.
- **Review bundle:** `~/ir-oda-01-v1.2-review-handover-r3/` (outside the repository).
- **Not changed by this plan:** code, schema, tests, ADRs, BDRs, the context map, the Platform Rules Index, ratified records, ODA-01.
