# Organisational baseline and Gate F: strategic discovery and readiness assessment

| | |
|---|---|
| **Status** | **PROPOSED discovery evidence and assessment.** It decides, ratifies, accepts and authorises nothing. **It is not an organisational baseline document**: the baseline under review is ODA-01 v1.2, and the roadmap forbids "two competing baseline records" (plan §4 item 2). This report assesses and reconciles against ODA-01 v1.2 and the Session B records. Not independently reviewed (Claude family). |
| **Commission** | Principal, 2026-11-09: "Organisational Baseline Completion and Gate F Strategic Discovery" (recommendations accepted by the Principal). |
| **Placement** | path as commissioned; `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Snapshot** | branch `election-flow-updated`, HEAD `5a26453d5` |

**Labels:** **FACT** · **INTERPRETATION** · **ASSUMPTION** · **UNKNOWN** · **RECOMMENDATION** · **PRINCIPAL DECISION** · **[PK]** = the Principal's recorded practical knowledge (*direct* or *as reported*; never an organisation's rule until a statute confirms it) · **[PRAC]** = general practice in party digitalisation known to the analyst, used only to frame questions, never as a rule. Abbreviations: **ODA** = ODA-01 v1.2 (`docs/publicdigit/architecture/reviews/2026-11-06-organisation-domain-architecture-baseline-oda-01-v1.2.md`); **DREC** = discovery record 2026-11-08; **SBM** = Session B model 2026-11-08; **DR07** = DR-07 paper; **M15** = DR-07a technical discovery M-1…M-5; **CMAP** = `docs/publicdigit/architecture/context-map.md`.

## 0. Disagreements with the commission (stated first)

1. **Gate F cannot be closed by any Claude session.**
   - FACT: Gate F is the independent review of ODA-01 v1.2 by "a reviewer who is **not** Claude: a human architect or a different model family" (`docs/publicdigit/architecture/reviews/2026-11-06-ir-oda-01-v1.2-review-commission.md:6-8`).
   - FACT: The only "Gate F review" so far was done by a Claude subagent. It states that it "does NOT meet the commission's … criterion" and leaves the verdict to the Principal (`…/2026-11-08-ir-oda-01-v1.2-gate-f-review-claude-subagent.md:12`). That file is also **untracked** in git.
   - FACT: Phase 3 acceptance further needs "independent review passed" **and** "at least one statute" (roadmap §5, Phase 3 row).
   - This report therefore answers the substantive question (is the *content* ready?), not the procedural one.
2. **Gate F is not the organ/unit classification.** The classification (O-16 / DR-07b) is an open item *inside* the baseline. Gate F is the review *of* the baseline. They are related but distinct.
3. **FeePaid has no ratified direction to "reverse".** Reconciled in §6.3.
4. **"Which records are organs and which are units" is the wrong question for the territorial committee records.** The evidence (§4) shows that each such record **merges a unit with its committee**. The task is to split them, not to classify them.

## 1. Executive conclusion (business language)

- **The core business model is clearer than the records' open-item lists suggest.** An organisation is divided into **units** (territorial units such as a district, and wing branches such as the youth wing). These are the places people belong to. Separately, it has **organs** (conventions, committees and similar bodies) that **decide**.
  - A unit can exist without a committee; the Highest Committee then acts for it ([PK] V-28, *direct*).
  - When a unit is divided, its committee ends and new committees are established for the new units ([PK] §2.3, *direct*).
  - So a unit and its committee have **different lifecycles**, and they are two concepts, not one.
  - The PO already decided this as a concept (PO-DR07-01).
- **Why the confusion exists:** everyday language ("district committee"; in Nepali party usage, समिति) uses one word for the unit and for its executive body. The legacy `committees` table repeated that: each territorial row is a unit **and** its committee fused together. **The fix is a split, not a classification.**
- **What is not yet stable:**
  - the exact definition of "Organisation" at its edges (organisations without written statutes; whether a national branch is a unit or a separate organisation);
  - the meaning of `CommitteeAssociation`;
  - per-type confirmation with data;
  - whether Organisation is its own bounded context.
- **Gate F:** the content has known corrections pending (the v1.3 plan). The independent non-Claude review has not happened. No statute is on file. **It cannot be closed today.** The organ/unit distinction itself is ready to be relied on.

## 2. Canonical source reconciliation

| Record | Status (as recorded) | What it settles for this question | Source |
|---|---|---|---|
| Ratified Membership act §10–§11 | RATIFIED | Governance owns "competence, organs, authority and organisational governance rules"; the Membership aggregate must not absorb committee participation or structures; no shared persistence | ODA §2.1 D-ACT10, D-ACT11 |
| Ratified Membership architecture | RATIFIED | "Organisation owns OrganisationAffiliation"; units may map to "another organisation" (I5) | ODA §2.1 D-RAT |
| PO-DR07-01 | PO business decision, 2026-11-02 | governance committees are organs; organisational units are a distinct concept; **existing records not classified**; `CommitteeAssociation` unresolved | DR07 `:261-291` |
| Principal position "BOTH" | Principal architectural position, 2026-11-02 | the committee code represents organs **and** units; model them separately | DR07 `:222-245` |
| PO-ORG-MEM-01 A / B; J1, J3, J4 | APPROVED / DECIDED | the Organisation owns its Membership domain; a competent organ **or authorised office holder** decides Membership matters; platform permission ≠ authority. **J1: Decision A applies to Membership decisions only** | ODA §2.2; status record §3 |
| ODA-01 v1.2 | PROPOSED; Gate F open; v1.3 correction plan approved for wording only; v1.3 not created | candidate definition §3.1; distinctions §3.2; model §4; four geography links §8.1; "Organisation context **not shown to be coherent**" §11.2; register §15 | ODA |
| Claude Gate F review of v1.2 | **does not meet the Gate F criterion**; verdict "Ready with corrections" | finds Decision A *applied* beyond Membership in the definition, distinctions, diagram, structure and glossary (A.1) | `…gate-f-review-claude-subagent.md:12-24` |
| DREC (V-1…V-59), D-1…D-32 | PROPOSED evidence; working decisions. **D-2: §2.7–2.9 are "provisional Phase 5 material until Phases 3 and 4 are accepted"** | the organisational facts in §4 below; D-3 (Organisation owns unit belonging), D-21 (only the Highest Committee changes structure, *as reported*), D-22 (Organisation tactical model) | DREC `:79-130`, `:416-494` |
| SBM §15–§16 | PROPOSED working baseline | a consolidated map with R-1…R-15 | SBM |
| CMAP §4–§5 | PROPOSED; the PO-approved consolidation point | R-01…R-18; "Organisation → Membership as a decision flow" and "Election → Governance" are **not drawn** | CMAP `:101-128` |
| M15 (DR-07a M-1…M-5) | technical draft; **needs independent review before use** (Decision B) | `CommitteeAssociation` in code: a member's standing in a `committees` row with a join reason (residence / exception / manual); **no role, position, term or authority**; "the code cannot settle M-2" | M15 `:30-32`, `:158-190` |
| ADR-004 | Accepted | Organisation is a **Candidate** context | ODA §2.1 |
| Statutes, standing orders | **none on file** | — | status record §9a; governance procedural-validity report §0 |

**Contradictions found (reported, not resolved):**

| # | Records | Contradiction |
|---|---|---|
| X-1 | ODA §11.2 vs D-3/D-22 | ODA: the Organisation context is "not shown to be coherent" (ODA-M-13 needs at least one statute). Session B: a working Organisation tactical model. **Reconciled by D-2** (Session B is provisional Phase 5 material). This is a sequencing tension, not a contradiction of substance |
| X-2 | CMAP §5 vs SBM §16 | beyond the clash in R-numbering (platform report G-2), the **content** differs: SBM adds Governance → Organisation decisions (R-1), Election → Governance results (R-7) and Membership → Organisation coverage (R-3); CMAP explicitly does not draw Election → Governance and lacks Governance → Organisation. CMAP has rows SBM lacks (Identity R-01…R-03, sign-in discovery R-14, affiliation → Election R-16) |
| X-3 | CMAP R-11 vs SBM R-10 | Geography → **Governance** (CMAP) vs Reference Geography → **Organisation** (SBM). **ODA §8.1's four link kinds reconcile both**: unit responsibility area (link 2) belongs to Organisation; governance jurisdiction (link 3) belongs to Governance |
| X-4 | status record §9a vs Session B | stale state (platform report HD-1) |
| X-5 | ODA §3.1/§4/§6/§13 vs J1 | Decision A's "organ or office holder" is applied beyond Membership (Claude Gate F review A.1); the v1.3 correction plan covers it |

## 3. Organisational concepts

The key below separates business meaning from table and class names. "Kind": **S** = structural entity · **A** = authority-bearing body or holder · **R** = relationship · **F** = fact or record.

| # | Concept | Real-world meaning | Why needed | Created / changed / ended by | Decides? | Kind | Depends on | Owner (status) | Evidence | Unknown |
|---|---|---|---|---|---|---|---|---|---|---|
| 1 | **Organisation** | a collective body with its own identity, purpose and rules, deciding its internal affairs through its competent organs (or authorised office holders, where its rules provide) | the subject everything serves | its founders and its own constitutional acts; not the platform | through its organs | S | — | business concept; **context status: Candidate** (ADR-004; ODA §11.2) | ODA §3.1 [P]; Decision A [D] | edges: ODA-M-2, ODA-M-20; whether a national branch or sister body is a unit or a separate Organisation (ODA §3.2 "open"; RAT I5) |
| 2 | **Organ** | a constituted body that decides: a convention or assembly, the Highest Committee, a unit's committee, a commission | the bearer of competence | a decision of the competent organ (§2.3: Highest Committee "establishing act") | **yes**, within competence | A | rules; usually a unit (its jurisdiction) | **Governance** (RATIFIED "organs"; D-10 `Organ` working) | PO-DR07-01 [D]; DREC §2.3 [PK] | which organs each organisation has (statutes) |
| 3 | **Organisational unit** | a territorial unit (district, country, …) or a wing branch through which people belong | belonging; the scope of organs' jurisdiction; voter sources | Highest Committee decisions (D-21, *as reported*); dissolution or division | **no**; the Highest Committee acts for a unit without a committee (V-28, *direct*) | S | the organisation's level rules (V-16, V-17) | **Organisation** (working D-3, D-22; formal DR-07b / O-16 open) | PO-DR07-01 [D]; V-26…V-29 | wings: same territorial units or own divisions (V-21: per rulebook) |
| 4 | **Geographical unit (official)** | an administrative area defined by a state | reference for addresses, residence and unit boundaries | the state; revisions | no | F (reference data) | — | **Reference Geography** (working D-31) | V-22 *direct*: keep the organisation's territorial structure and official geography apart | stable official identity (SBM §16 R-10) |
| 5 | **Committee** | **a word, not one concept.** In PO-DR07-01: an organ. In everyday use and in the legacy table: also the unit | — | — | as an organ, yes | — | — | **RECOMMENDATION:** in the ubiquitous language, "committee" names a *kind of organ* only; never a unit | PO-DR07-01; DR07 `:224` | O-14 (ARB vocabulary) |
| 6 | **Position / office** | a place with defined powers: chair, secretary, member's seat, delegate's place | who holds which powers | established by the constitution or a decision; filled by election or appointment (tenure); vacated only by a formal decision (D-24, *as reported*) | the holder may decide within powers (Decision A, for Membership; J1) | A (holder) | an organ (or unit, SBM §5) | **Governance** (working D-4, D-10) | Decision A [D]; D-4, D-7 | offices outside any organ (ODA-E-2) |
| 7 | **Role** | **three different things share the word**: platform role (owner/admin), election officer role, an organisational position | — | — | platform roles: **never** organisational authority (Decision A) | — | — | Platform / Election / Governance respectively | Decision A; D-23 | O-14: reserve "role" for platform and election roles |
| 8 | **Authority (competence)** | the right of an organ or office to decide a kind of matter within a scope | legitimacy of every decision | conferred by the constitution or rules; residual competence (§2.2 [PK], *as reported*) | — | R | rules; organ; scope | **Governance** (RATIFIED) | act §11; D-12 | residual-competence source clause (§2.2) |
| 9 | **Authority assignment / delegation** | one organ transferring competence to another body or person | decisions below the Highest Committee | a decision of the delegating organ (V-29: the Highest Committee may assign responsibility for a committee-less unit) | — | R | two organs, or organ → person; a decision | **Governance** (`AuthorityAssignment`, D-7) | V-29 *direct*; FACT: code records `delegatedBy: MemberId`, no decision reference (procedural-validity report §3) | whether delegation to a person or only to a body (V-29 [OQ]) |
| 10 | **Jurisdiction** | the scope (units, territory or function) over which an organ's competence applies, over time | which committee is competent for which matter | organ establishment; decisions | — | R | organ, units | **Governance** (`JurisdictionAssignment` [P]; ODA §8.1 link 3) | ODA §8.1 | the word is also used for "arbitration" (`ConstitutionalArbitrationKernel`, BDR-05 v1.1); needs a separate name |
| 11 | **Membership** | a person's membership of the organisation (periods, type) | rights to belong, vote, stand | Membership acts by a competent organ or office holder (Decision A) | no | F | Identity | **Membership** (RATIFIED, frozen) | act; ratified architecture | — |
| 12 | **Unit belonging** | a person's attachment to a unit in a capacity (member, observer, staff) over a period | voter lists, local rights | decisions or rules (territorial assignment, V-24) | no | R | membership coverage (R-3) | **Organisation** (working D-3) | DREC §2.5; SBM §9 | **is the legacy `CommitteeAssociation` this?** (M-2) |
| 13 | **Organisational structure** | the tree of units over time | everything "as at a date" | Highest Committee decisions as change sets (D-21, D-29) | — | S | units, levels, rules | **Organisation** (working D-22) | DREC §2.3; SBM §9–§11 | — |
| 14 | **Governance rule** | a provision of the constitution or of Governance Rules, versioned; **the resolution prevails over the rulebook** (V-19, *direct*) | the basis of every decision | rule-making decisions (resolutions recorded in minutes, V-18, *direct*) | — | F | organ competence | **Governance** (`RuleSet`, working D-10); placement of the **source text**: ODA §5.3 O-1/O-2/O-3 open (ODA-M-11) | V-17…V-19 | text placement |
| 15 | **Organisational decision** | the act of a competent organ or office holder, with a decision date and an effective date | every change of authoritative state | the organ; never changed, only followed by new decisions | — | F | rules, competence, organ | **Governance** (working D-10, D-12) | D-12; procedural-validity report | procedure layers (statutes) |

**Universal distinction that follows (RECOMMENDATION, INTERPRETATION H):** a **record correction** ("the data was entered wrongly") is not an **organisational change** ("the organisation decided something"). A correction needs recording authority and a reason, and never a decision. A change needs a decision basis. Without this distinction, either every typo needs a resolution, or administrators can change structure by calling it a correction. That is the second finding of the procedural-validity report (structure changes without a decision basis).

## 4. Organ versus unit: classification and evidence

### 4.1 The distinction (supported)
| Test | Unit | Organ | Evidence |
|---|---|---|---|
| Can exist without the other | yes: a unit with no committee (V-28, *direct*) | functional organs exist without a unit of their own [PRAC]: election, disciplinary and advisory bodies; **UNKNOWN** for NRNA | V-28 |
| Lifecycle | created, divided, merged, dissolved by Highest Committee decisions | established by a separate act; **ends automatically when its unit ends** (§2.3, *as reported*); may be dissolved by decision while the unit continues (V-48, SBM §5 `Organ`) | DREC §2.3; SBM §5 |
| On division | the unit ends; successors are created | "the original committee ends and the Highest Committee establishes two new committees" (*direct*) | DREC §2.3 |
| Holds people as | belonging (members) | positions and tenures (office holders) | PO-DR07-01; D-3, D-10 |
| Decides | no | yes | PO-DR07-01 |
| One unit, several organs? | — | yes: a unit's **convention** elects its **committee** (DREC §2.6 [PK]; [PRAC]: party statutes typically give each unit a members' or delegates' assembly and an executive committee) | DREC §2.6 |
| One organ, several units? | — | yes: the Highest Committee acts for every committee-less unit (V-28) and changes all structure (D-21) | V-28; D-21 |

**Consequence (INTERPRETATION, H):** a unit and an organ are related **many-to-many over time**. The legacy one-row-per-"committee" model therefore cannot represent:
- a unit without a committee;
- a dissolved committee in a continuing unit;
- a unit's convention and committee side by side.

### 4.2 Classification of existing records
**FACT (code):** `CommitteeType` = `central`, `province`, `district`, `ward`, `youth_wing`, `women_wing`, `student_wing`, `diaspora`, `geographic` (`app/Contexts/Membership/Domain/ValueObjects/CommitteeType.php:27-35`). A row carries **unit characteristics** (members attach through `CommitteeAssociation` with join reason *residence*; a geographic reference; `parent_committee_id`) **and organ characteristics** (`committee_assignments`, i.e. positions; M15 §3) (DR07 §4; M15 `:30`).

| Record type | Reading (INTERPRETATION) | Confidence | Why not higher |
|---|---|---|---|
| `central` | the organisation's top unit (level 0, V-16) **fused with** the Highest Committee | M | data not inspected (U-Q3 needs authorisation) |
| `province`, `district`, `ward`, `geographic` | a territorial unit **fused with** its committee | M | the same; also whether every row has a committee |
| `youth_wing`, `women_wing`, `student_wing` | a wing branch (structure, V-26) **fused with** a wing committee at a level; FACT: the type also hard-codes eligibility ("age 18-35", "women only", `CommitteeType.php` docblock) | L–M | V-21: wing placement is a rulebook matter |
| `diaspora` | UNKNOWN: a unit for members abroad, a wing, or a separate organisation | L | no [PK] |
| any functional organ (election committee, commission) | **not in this table** in the evidence found. The election committee is Election's (D-23) | — | — |

**No record can be classified *as* a unit or *as* an organ with confidence** (FACT: no data was inspected; INTERPRETATION: most rows are both). **The confident result is the transformation rule (INTERPRETATION, M; RECOMMENDATION for O-16):** each territorial or wing row = one **Unit** (Organisation) + zero or one **Organ** (Governance), with `committee_assignments` → Positions and tenures, and `CommitteeAssociation` → unit belonging **if** M-2 confirms the reading. Validation needs:
- (a) the Membership owner's M-2 answer;
- (b) read-only counts per type: rows with assignments, rows with associations, rows with both or neither (DR07 §18 U-Q3; **needs authorisation**).

## 5. Answers A–K

| | Question | Answer | Label |
|---|---|---|---|
| A | Definition of Organisation | ODA §3.1's core is uncontested: a collective body, its own identity, purpose and rules, deciding through its competent organs, its authority independent of the software, platform records excluded. **Open edges:** the J1 correction (office holders decide *Membership* matters by Decision A; elsewhere per its rules); organisations without written statutes (ODA-M-20); national branches or sister organisations as units vs separate Organisations (RAT I5; [PRAC]: diaspora national councils and party sister organisations are often separately registered bodies; **to be confirmed for NRNA**) | core INTERPRETATION H; edges UNKNOWN |
| B | Organ vs unit | §4.1 | H |
| C | Records classifiable confidently | none as records; the **split rule** is confident (§4.2) | M |
| D | Not yet classifiable | `diaspora`; wing placement; rows without assignments or associations; all of it without data | — |
| E | Geography vs authority | **three distinct hierarchies:** official geography (state; Reference Geography); the organisation's unit tree (organisation-defined, may adopt official boundaries; NRNA's continent level has no official counterpart, V-16); and **authority**, which follows rules, not the tree. **Authority for a committee-less unit jumps to the Highest Committee, not to the parent unit's committee** (V-28, *direct*) | H for the separation; [PK] for the jump |
| F | Unit without organ? | yes (V-28, *direct*) | [PK] H |
| G | Organ over several units? | yes (Highest Committee; delegation, V-29) | [PK] H |
| H | Organ, Position, Role, Authority | an organ **has** positions; persons **hold** positions through tenures; competence attaches to organs and powers to positions; mandates are **derived** from tenures (D-7); "role" is a platform or election term, not organisational | working [D] |
| I | What creates, modifies and retires organs and units | decisions of the competent organ: the Highest Committee for structure (D-21, *as reported*); an organ ends by consequence of its unit ending, or by decision (§4.1) | [PK] / working [D] |
| J | Decision vs operational change | **decision needed:** units, levels, organs, positions and tenures, competence and delegation, rules, membership acts. **Operational:** platform access roles, notification settings, display and contact data, uploading a record of a decision. **Record correction:** a separate path with reason and recording authority, never a decision (§3) | RECOMMENDATION |
| K | Decisions cause consequences without moving authority | Governance decides; Organisation applies change sets from the effective date, checks only that a recorded decision exists (not competence, D-12), and replies "applied" or "inapplicable" (C-4). Authority never sits in Organisation | working [D] D-12, D-21, D-29 |

## 6. Context-map assessment

### 6.1 Relevant relationships (both numberings)
| Relationship | CMAP | SBM | Authoritative owner → consumer | Basis | Assessment |
|---|---|---|---|---|---|
| Governance → Organisation: structural decisions | **absent** | R-1 | Governance → Organisation | working [D] D-21, D-29 | **justified** (§5 K). Missing from the canonical map |
| Organisation → Governance: structure "as at" | absent | R-2 | Organisation → Governance | working [D] D-29 | justified (competence on a date needs the structure on that date) |
| Membership → Governance: predicates | R-07 (MembershipQueryPort, PROPOSED) | R-14 | Membership → Governance | [P]; needs the Membership §8 Change Request | the same relationship, two numbers; justified for appointments outside elections |
| Governance → Membership: authority evidence | R-08 | R-9 | Governance → Membership | **RATIFIED** (AuthorityCheck) | stable |
| Membership → Organisation: coverage | ("Organisation → Membership as a decision flow" not drawn) | R-3 | Membership → Organisation (**facts**, not decisions) | [P] D-27 | justified; not in conflict with CMAP's exclusion (the direction is opposite and it carries facts) |
| Governance ⇄ Election | R-13 is Governance → Adjudication only | R-6a/b/c, R-7, R-8 | both directions | working [D] D-23, D-32 | justified by business (appointments, rules, results → tenures). CMAP's "Election → Governance not drawn" is **superseded in substance** by D-32 (a working decision), but not in the canonical record |
| Governance → Authorization: mandates | R-09 (Appointment → Authorization, FROZEN) | R-11 | Governance (as Appointment, D-4) → Authorization | FROZEN contract; producer changed by working D-4 (an ADR is needed) | stable, pending the ADR |
| Geography links | R-11 Geography → Governance | R-10 Reference Geography → Organisation | official geography → Organisation (unit areas) **and** → Governance (jurisdiction) | ODA §8.1 | both are partly right (X-3) |
| Communication / Deliberation | R-12 Authorization → Communication | — | — | [P] O-15 | no change justified by this round |

### 6.2 Coherence verdict
**Coherent in substance, incoherent as a record (INTERPRETATION, H).** Nothing in the business evidence contradicts the boundaries. The canonical CMAP, however, is behind the working decisions (missing Governance ⇄ Organisation and Governance ⇄ Election), uses numbers that clash with SBM's, and has three rows SBM lacks. **No missing bounded context** was found. **Missing capabilities:** record correction vs organisational change (§3); organ proceedings (procedural-validity report).

### 6.3 FeePaid reconciliation (commissioned)
| Source | Says | Weight |
|---|---|---|
| ratified act §11 `:334-343` | Finance owns **settlement**, refunds, credits, accounting; "Membership may provide membership-related business facts" | RATIFIED text |
| ratification act N-5 `:18` | "Blueprint §5 'Membership → Finance: FeePaid' is ambiguous. **EP-01 treats FeePaid as a Finance fact consumed by Membership**" | a **recorder's note** on how a step treats it |
| CMAP R-05 | "FeePaid direction **not established** (N-5 is a recorder's reading)" | PROPOSED consolidation |
| status record `:186`; e0b-s decision record `:39` | "Still with the PO: the FeePaid direction question" | resumption record |
| code | `MembershipPaymentService` → `MembershipFeePaid` → Finance listener; FeePaid is a Membership domain event (`app/Contexts/Membership/Domain/Fee/Events/FeePaid.php`) | IMPLEMENTED |

**Conclusion (INTERPRETATION, H):**
- No ratified decision fixes the direction.
- The only statement near the ratified record (N-5) favours "Finance fact, consumed by Membership", and the code contradicts that reading.
- Changing the code to match N-5 later would **not** reverse ratified architecture. Keeping the code would leave it at odds with N-5's reading.
- This is not a decision; it remains with the PO (PD-6), and it is not needed for this baseline.

## 7. Universal vs organisation-specific

| Category | Items |
|---|---|
| **Platform-wide concepts** | Organisation; unit; organ; position; tenure; competence; delegation; decision (decision date, effective date); rule version; unit belonging; record correction vs organisational change; "as at" resolution |
| **Organisation-specific content** | names of organs and levels (NRNA: International Coordination Council, continents; party: Central Committee); which organs exist; level patterns and exceptions (rule log, V-17); wing placement (V-21); who may submit or decide; quorum and majorities |
| **Organisation-configurable rules** (recorded as the organisation's rule versions, never platform defaults, D-1b) | competence allocations; delegations; vacancy handling; delegate rules; capacities that require membership |
| **Evidence to record** | resolutions and minutes references (V-18); decision and effective dates; who entered a decision and the mandate relied on (V-59); procedural facts as declared |
| **Evaluations only after an organisation adopts an interpretation** (P-2, P-3) | competence checks beyond the recorded rule; quorum and majority validity; eligibility predicates |

**Code that hard-codes organisation-specific content as universal (FACT; POTENTIALLY MISALIGNED with D-1/D-1b):**
- `ApprovalRequirementPolicy` (`CONSTITUTIONAL_AMENDMENT → ICC_BOARD`, `ApprovalRequirementPolicy.php:15-23`);
- `CommitteeType` wing eligibility ("age 18-35", "women only", docblock `:20-24`);
- `GeoLevelType`, a fixed seven-level enum without continent or country levels (`app/Contexts/Geography/Domain/Enums/GeoLevelType.php`; DREC C-a).

## 8. Boundaries: confirmed, provisional, hypothetical

| | Boundaries |
|---|---|
| **Confirmed** (ratified, frozen or PO-decided) | Membership; Governance owns competence, organs, authority and rules; organ ≠ unit as concepts (PO-DR07-01); platform permission ≠ authority (Decision A); Authorization owns no truth (BDR-07); the election correction loop (FROZEN) |
| **Provisional** (working decisions, PROPOSED) | Organisation as owner of units, structure and belonging (D-3, D-22; ODA §11.2 says not yet coherent; ODA-M-13); Governance absorbs Appointment (D-4, ADR pending); Reference Geography at platform level (D-31); the relationship set of SBM §16 |
| **Hypothetical** | Deliberation and Communication contexts; the source-text placement O-1/O-2/O-3; `CommitteeAssociation` = unit belonging; national branches as units vs Organisations |

## 9. Tactical implications (only where justified)

| Candidate | Rule it protects | Evidence | Why this context | What breaks elsewhere | Status |
|---|---|---|---|---|---|
| **Unit and Organ as separate identities with separate lifecycles**; an organ references its unit(s) by ID only (no shared persistence) | a unit may exist with no organ; an organ may end while its unit continues; one unit may have several organs | V-28, V-48, DREC §2.3, §2.6; PO-DR07-01 | the unit is structure (Organisation, D-3); the organ is authority (Governance, act §11) | fused (as today): dissolving a committee would end the unit and its members' belonging; a committee-less unit could not exist | **candidate, strongly supported** (already in D-10 `Organ` and D-22 `Unit`) |
| An organ ends **as a consequence** of its unit ending (Governance reacts to Organisation's `UnitEnded`) | §2.3 "ceases automatically; no separate decision" | DREC §2.3 (*as reported*) | Governance owns the organ's life; Organisation announces the fact | Organisation ending organs = Organisation exercising authority | candidate; *as reported* evidence |
| **Record-correction path distinct from decisions** in both contexts | decisions immutable (D-10); corrections never create authority | §3 | each context corrects its own records | without it, corrections become hidden decisions | candidate [P] |
| **No organisation-specific constants in platform code**; content comes from the organisation's rule version | D-1b | §7 | Governance (`RuleSet`) | one organisation's law imposed on all | established principle (D-1b) |
| Legacy split: row → Unit + optional Organ; assignments → positions and tenures; associations → belonging (if M-2) | as row 1 | §4.2 | — | — | **migration hypothesis only**, for O-16 |

Nothing about proceedings, quorum or agendas is derived here (procedural-validity report §4: unresolved without statutes).

## 10. Principal decisions (genuine only)

| # | PRINCIPAL DECISION | Why evidence cannot settle it | Alternatives → consequences | Recommendation | When |
|---|---|---|---|---|---|
| **PD-1** | **Reference organisations:** NRNA first, then one political party? | a product and strategy choice about whose rules shape the platform | NRNA first: the request is drafted (roadmap App. A) and NRNA is the first named user. A party first: closer to party digitalisation, but no request drafted. Both: more variation, slower | **NRNA first, then one party** (the reviewer's and the previous report's recommendation). Send the NRNA request with: constitution, Governance Rules or rulebook **and resolutions** (V-19: the resolution prevails), standing orders, the rule log, structure (levels, units, wings, national councils' legal status) | **now** |
| **PD-2** | **Gate F reviewer:** who performs the non-Claude review of ODA-01 v1.2 (or v1.3)? Or does the Principal amend the commission? | the commission's criterion excludes Claude; only the Principal can name a reviewer or change the criterion | a human architect (best independence, cost); a different model family (cheaper; still an AI); amend the criterion (fastest; weakens the gate the Principal set) | name a different-model-family reviewer now; create v1.3 first, so the review is not of a superseded text (gate D) | **now**, if Phase 3 is to move |
| **PD-3** | **M-2: what is `CommitteeAssociation` in business terms?** The Principal is both PO and technical Membership owner (M15 header) | a business meaning cannot be read from code (M15 `:32`) | (a) membership held through a unit (= unit belonging, Organisation); (b) participation in an organ (Governance); (c) something else | (a) is the evidence-based reading (join reasons residence / exception / manual; no role, term or authority). Confirming it lets O-16 proceed | **now**; cheap, and it unblocks O-16 |
| **PD-4** | **Authorise read-only counts per committee type** (rows with assignments, associations, both, neither; per environment) | data access needs authorisation (DR07 §18) | yes → the split rule can be validated; no → classification stays a hypothesis | yes, read-only, aggregate counts only | with PD-3 |
| **PD-5** | **Vocabulary (as ARB, O-14):** "committee" = a kind of organ only; "unit" for structure; "role" never for organisational authority | an ARB vocabulary act | adopt now / with the full O-14 package | adopt with O-14; record it now as a working term (like W-1/W-2) | can wait |
| **PD-6** | FeePaid direction | already with the PO | — | not needed for this baseline | can wait |

**Not asked:** the organ ≠ unit concept (PO-DR07-01 decided it); Governance's ownership of organs (RATIFIED); platform permission ≠ authority (Decision A).

## 11. Exact remaining knowledge gaps

1. **Statutes:** organs per organisation, competences, residual competence clause, wing rules, the legal status of national branches. Needs PD-1.
2. **`CommitteeAssociation` meaning.** Needs PD-3.
3. **Per-type data validation of the split.** Needs PD-4.
4. **Whether functional organs (commissions, disciplinary or advisory bodies) exist** for NRNA, and where. Needs statutes.
5. **The independent review of ODA-01.** Needs PD-2.
6. **J1 correction of ODA's definition wording** (v1.3).
7. **Whether Organisation is a coherent bounded context** (ODA-M-13; needs at least one statute).
8. **CMAP consolidation** (X-2, X-3; platform report HD-2).

**Can be established without statutes, and is established here:** the organ/unit split and its lifecycle rules ([PK] *direct*); the three-hierarchy separation (E); record correction vs change; the universal vs specific catalogue (§7).

## 12. Recommended next substantive discovery step

**Resolve the legacy committee records into units and organs, with the Principal as Membership owner and PO.** Answer M-2 (PD-3); review the per-type counts (PD-4); test the split rule in §4.2 type by type, including `diaspora` and the wings. The result is the evidence O-16 / DR-07b need, and it is the first point where the organisational baseline touches real data. It can run **in parallel** with sending the NRNA request (PD-1) and arranging the Gate F review (PD-2), and needs neither.

## Traceability
- **Read:**
  - ODA-01 v1.2 §2–§15;
  - the Gate F review commission `:1-30`;
  - the Claude Gate F review `:1-24`;
  - DR-07 paper §4, §14, §15, §18, §19;
  - M15 `:1-40`, `:158-226`;
  - DREC `:52-130`, `:416-494`;
  - SBM §1–§19;
  - CMAP §4–§5;
  - ratification act `:18`;
  - status record §3–§9a;
  - roadmap plan §5, Appendix A–B;
  - the procedural-validity and scenario reports (2026-11-09).
- **Code:** `CommitteeType.php:20-35`; `ApprovalRequirementPolicy.php`; `GeoLevelType.php`; `app/Contexts/Membership/Domain/Fee/Events/FeePaid.php` (listing).
