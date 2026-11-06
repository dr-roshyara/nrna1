# Organisation ownership of Membership and constitutional authority: discovery preparation

| | |
|---|---|
| **Status** | docs/publicdigit/architecture/reviews/2026-11-03-organisation-membership-constitutional-authority-discovery-preparation.md.pDECISION PREPARATION AND DISCOVERY DESIGN.** It prepares a PO decision, a discovery method and open questions. It decides, approves, ratifies and authorises nothing, and changes no status in any other record. |
| **Authority** | generated (Claude; DDD, business architecture, requirements and governance roles). Reviewer: the Principal DDD Architect. |
| **Date** | 2026-11-03 |
| **Snapshot** | branch `election-flow-updated`, HEAD `f61553a5d` (2 commits after `19b3082a3`; none touches product, decision or architecture records). |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0). |
| **Builds on** | `2026-11-03-publicdigit-po-arb-decision-action-package.md` §3.5 (abbreviated "the action package"); `2026-11-03-baseline-stabilisation-and-strategic-decision-preparation-review.md`. Both are decision preparation, not approvals. |
| **Revision** | r2 (2026-11-03): role model refined after the Principal's clarification. Adds the Organisational Membership Responsible Person, the platform access role, competence versus delegation, the technical-owner designation, questionnaire §5.6 and evidence analysis §10.1. HEAD unchanged (`508283527`). |

**Labels.** [F] verified fact with source · [D] existing decision by a named authority · [P] proposal · [OQ] open question · [EO] engineering observation · [A] assumption.

**Paths.** R/ = `docs/publicdigit/reviews/` · RAT = R/`2026-10-08-membership-architecture-ratified.md` · ACT = R/`2026-10-08-membership-architecture-ratification-act.md`.

---

## 1. Executive summary

1. [P] The business ownership principle (§3) is written as a decision-ready PO proposal. It is consistent with what is ratified: Governance owns "competence, organs, authority and organisational governance rules" (ACT §11 `:319-321`), and Membership stores authority evidence only (RAT §8 `:156`). It is **not approved**. After PO confirmation, the ARB must validate it before it constrains context responsibilities, interfaces or permissions.
2. [F] Four existing constraints shape the discovery:
   - **Retroactive effect via new authorised facts is allowed; retroactive recomputation is not.** Annul is "a valid decision set aside (ex tunc)" (RAT `:63`); facts are append-only; decisions carry `ruleRef` and `policyVersion` (RAT `:72`).
   - **CQ-10 is not a blank slate.** The PO has already fixed several platform-wide Membership rules (D-1, D-2, D-3, D-4 interim, D-5(b), E-1…E-5, GOV-END B1, BD-REV-ER). Constitutions must be checked against them (§7.3).
   - **Membership's invariants bind even a competent organ's decision.** G\* accepts a fact only if the invariants hold (RAT §6 `:127-136`).
   - **"Constitution" is already used in code for four other things** (§7.4). The new concept needs a qualified term.
3. Discovery is designed as an evidence collection from **several** organisations, with a questionnaire (§5) and a comparison template (§6). Silence in a constitution is recorded as silence, never as a default.
4. The technical-owner track (designate the owner, then M-1…M-5) stays on its critical path and does not wait for constitutional discovery (§4.3, §12).
5. **Smallest next action:** the PO confirms or amends the ownership principle (§3.3). The technical-owner designation stays the smallest action on the DR-07a path; the two are independent.

---

## 2. Verified repository state

| Check | Result |
|---|---|
| Branch / HEAD | [F] `election-flow-updated` / `f61553a5d` |
| Commits since the action package snapshot (`19b3082a3`) | [F] 2; no changes under `docs/publicdigit`, `docs/implementation`, `docs/adr`, `app`, `database` |
| Decisions relied on (re-read at HEAD) | [D] ACT §11 Governance and Membership boundaries; RAT §2, §3, §6, §8 (provenance, purity, G\*, contracts); PO-DR07-01 (DR-07 paper `:261-281`); D-1…D-5 (R/`2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md:290-296`, rows marked "PO DECIDED", D-5(a) "OPEN (engineering)"); E-1…E-5 (R/`2026-11-02-membership-e0b-s-decision-record.md:4`); GOV-END B1 (R/`2026-11-02-membership-gov-end-decision-record.md:4`); BR-13, BR-13a, BR-14 (`docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md:36-40`) |
| Other sessions' work | [F] uncommitted changes in `.claude/`, `developer_guide/election/ip_voting/*`, KnowledgeOS scratch; untracked `tests/Isolated/Pbdigit274/...`. Not touched |
| The action package | [F] unchanged since written; its §3.5 is a proposal |

---

## 3. Proposed PO decision: Organisation ownership of Membership

### 3.1 Proposed wording [P]

> **PO-ORG-MEM-01 (proposed).** The Organisation is the business owner of its Membership domain. Its Organisation Constitution (statutes) establishes the competent organisational organs and delegates authority to those organs or authorised office holders to make specific Membership decisions. Administrative responsibilities may be delegated without transferring ownership of the Membership domain. PublicDigit implements the authorised rules and records Membership facts and decision evidence; it does not acquire organisational authority.

The identifier `PO-ORG-MEM-01` is a working label for this proposal only. It is not registered in any series.

**Principal's restated wording (2026-11-03, in this commission's discussion; not a recorded PO act):**

> "The Organisation owns its Membership domain. Its Constitution defines the competent organisational bodies and delegates authority and responsibilities for Membership decisions. PublicDigit models and enforces the authorised rules and records the resulting facts; it does not become the source of organisational authority."

**Differences from §3.1 for the PO to choose between:**
- "models and **enforces** the authorised rules" vs "implements the authorised rules". "Enforces" also covers Membership refusing an act that breaks an invariant (N-1).
- "delegates authority **and responsibilities**" vs "delegates authority … administrative responsibilities may be delegated without transferring ownership". The §3.1 wording states the non-transfer of ownership explicitly.

[P] A combined wording keeping both points can be confirmed as one act.

### 3.2 Concepts the decision distinguishes [P]

| Concept | Meaning | Relationship to existing records |
|---|---|---|
| Organisation | business owner of its Membership domain | [F] "the recognising body" (ratified ontology as cited in the governance map); Organisation as a *bounded context* is a candidate (context map §4) — this decision concerns business ownership, not context ownership |
| Organisation Constitution (statutes) | source of organisational authority and the applicable governance rules | [OQ] no record defines how it is represented (§8) |
| Governance | represents organs, competence, authority and delegation | [D] ACT §11 `:319-321` |
| Membership | manages the Membership lifecycle facts; records evidence of authorised decisions; **enforces its own invariants on every decision** | [D] RAT §2–§8; G\* (RAT §6) |
| Competent organ / authorised office holder | makes Membership decisions within the competence the Organisation Constitution gives it, directly or by valid delegation | [D] ACT §11 (Governance owns competence, organs, authority); content per statutes [OQ] |
| Organisational Membership Responsible Person | appointed or authorised by a competent organ; coordinates and administers Membership operations. **Operational responsibility for Membership does not automatically confer constitutional authority to approve, reject, suspend, restore or terminate Membership.** | [P] Principal's clarification (2026-11-03); often the General Secretary, but this is not assumed |
| PublicDigit | models and enforces the authorised rules and records the resulting facts | [D] resolution is pure and independent of acceptance (RAT §3) |
| Technical Membership owner | responsible for the Membership context's code, behaviour, invariants and migration risks; a technical responsibility, not an organisational office | [F] DR-07 paper `:41` ("another developer"); designation §4.4 |
| Platform access role (owner / admin) | a software permission; **PlatformAccessRole ≠ ConstitutionalAuthority** | [D] D-4 interim and replaceable (R/`…slice2-decision-closure.md:293`); [F] `organisation_users.role` ∈ {owner, admin, staff, member} (`database/migrations/2026_03_07_145528_*:18`) |
| Authorised discovery representative | supplies the authoritative statutes and evidence of organisational decisions, and explains the rules, within a mandate or statutory basis; may also hold another role | [OQ] not named for any organisation |

**Competence versus delegation** [P]. A Constitution may:
- (a) assign competence **directly** to an organ or office (no delegation involved);
- (b) allow a competent organ to **delegate** a decision to an office holder or another body;
- (c) assign **administrative responsibilities** to a person (processing, record-keeping);
- (d) leave tasks as **operational execution**, which are not decisions at all.

Only (a) and (b) produce authority for a Membership decision. Not every authority relationship is a delegation.

**One person, several capacities.** A General Secretary may at the same time be the Organisational Membership Responsible Person, a member of the competent organ, the discovery representative and a platform administrator. Each capacity is separate. Platform access never proves constitutional authority.

### 3.3 What exactly the PO must confirm

1. The principle in §3.1, as worded or amended.
2. That **business ownership** (the Organisation) and **technical context ownership** (DR-07 "Membership owner") are different roles, and that M-1…M-5 stay with the technical owner.
3. That PublicDigit records organisational authority as **evidence** and never treats its own platform state (e.g. an access role) as the source of that authority. *Note:* the PO-decided interim adapter D-4 (owner/admin) is explicitly "interim and replaceable by the Governance-owned implementation" (R/`…slice2-decision-closure.md:293`); the principle would make that interim nature binding, not remove D-4.
4. Which organisations serve as reference organisations for discovery (§5), and that the PO, not engineering, names their authorised representatives.
5. The role model of §3.2: in particular, that operational responsibility (the Organisational Membership Responsible Person) and platform access (owner / admin) are each distinct from constitutional decision authority.
6. That each Organisation appoints or authorises its own Membership Responsible Person, and that PublicDigit does not assume which office holds it.

### 3.4 ARB validation required after PO confirmation

| ARB question | Why |
|---|---|
| Does the principle add anything to ACT §11, or is it the business reading of it? | to avoid a silent amendment of the ratified act |
| Where is the Organisation Constitution represented: Governance (ACT §11 "organisational governance rules"), Organisation (candidate), or elsewhere? | context ownership is the ARB's (no context created here) |
| What is the minimum authority evidence Membership must record (§10)? | R-08 / AuthorityCheck contract (P11) |
| Does the principle change any existing PO platform rule (D-1…D-5, E-1…E-5, GOV-END B1)? | §7.3 conflicts must be ruled, not assumed |

### 3.5 What the decision does not do

- It does not change the ratified Membership architecture (aggregate, temporal model, corrections, concurrency).
- It does not decide CQ-10.
- It does not name any organ, office holder or representative.
- It does not create a bounded context, rules engine or configuration schema.

---

## 4. Responsibility matrix

### 4.1 Roles

| Role | Basis | Responsible for / answers | Must not be asked or assumed |
|---|---|---|---|
| **1. Organisation (business owner)** | [P] PO-ORG-MEM-01 | owns the Membership business domain; its statutes are the normative basis | — |
| **2. Competent organ / authorised office holder** | Organisation Constitution: direct competence (a) or valid delegation (b), §3.2 | makes Membership decisions within its competence | that an office title alone confers authority |
| **3. Organisational Membership Responsible Person** | appointment or authorisation by a competent organ [P] | coordinates and administers: receives and processes applications, maintains or supervises records, coordinates with the competent organ, executes assigned tasks | that administering a process confers authority to decide it; that this is always the General Secretary |
| **4. Technical Membership owner** (DR-07) | technical responsibility; designation §4.4 | code, write paths, legacy committee implementation, invariants, technical behaviour, migration and cutover; M-1…M-5 (M-2/M-3 technical part) | to determine constitutional authority or organisational meaning |
| **5. Platform access role** (owner / admin) | PublicDigit user administration; interim D-4 | technical access | to stand for constitutional authority |
| **Authorised discovery representative** | [OQ] a mandate from a competent organ, or a statutory office whose statutes give that function | statutes, evidence of decisions, the questionnaire (§5); business parts of M-2 and M-3 | to verify a code inventory (M-1), freeze code (M-4) or assess cutover risk (M-5) |

### 4.2 Question routing

| Question | Respondent | Approval needed |
|---|---|---|
| M-1 code inventory and invariants | Role 4 | ARB uses it for ARB-5/6 |
| M-2 meaning of `CommitteeAssociation` | Role 4 (what the code does) + discovery representative (what it should mean) | PO if business meaning |
| M-3 "Do you agree that Governance owns governance organs (option A)? If not, why?" | Role 4 (a **position**, with reasons); the representative may add an organisational view | ARB (ARB-2, ARB-5) |
| M-4 freeze legacy committee model | Role 4 | ARB (ARB-6) |
| M-5 cutover risks | Role 4 | ARB (ARB-7) |
| Constitutional questionnaire §5 (incl. §5.6) | discovery representative, with the statutes as evidence | PO (default when statutes are silent); legal interpretation per the statutes |
| CQ-10 platform variation | PO | ARB for the architectural consequence |
| Representation of constitutions and versions | ARB | — |

### 4.3 Independence of the tracks

[P] The PO designates the technical owner and sends M-1…M-5 now (action package §3.4). Constitutional discovery does not gate it, and it does not gate constitutional discovery.

### 4.4 Technical Membership owner: designation stated by the Principal

**Stated by the Principal (Nab), 2026-11-03, in this session.** It is recorded here as a technical responsibility assignment. It is not a registered decision record, and the DR-07 paper is not edited.

1. Nab, as sole developer of PublicDigit, is the designated technical owner of the Membership bounded context for DR-07 (the role the paper calls "Membership owner", `:41`).
2. Nab answers M-1…M-5 from repository evidence (M-2 and M-3 as described in §4.2).
3. Nab is responsible for technical implementation, migration analysis, code inventory and cutover risk assessment.
4. The assignment does not transfer Membership business-domain ownership from the respective Organisation.
5. It confers no constitutional Membership decision authority within any Organisation.
6. Each Organisation separately appoints or authorises its Organisational Membership Responsible Person.
7. The ARB retains architectural decision and validation responsibility under the established process.

It changes neither the ratified Membership architecture nor the organisational authority model.

**Safeguard [P]: independence of evidence and decision.** The same person holds the technical owner role and also acts in the PO / Principal roles that take or prepare the ARB-5/6 decisions. The project's existing rule "Engineering supplies evidence and never accepts its own work" (EP-02 / R-34) therefore applies. Before the ARB acts on them, the M-1…M-5 answers should receive an **independent review**, as accepted slices have had, so the decision chain does not review its own evidence.

[OQ] The formal recording of this designation in the DR-07 record (e.g. an annotation of paper §2) is for the record's owner. It is outside this document's scope.

---

## 5. Constitutional discovery questionnaire

To be answered by the authorised representative of each reference organisation, **with the statutes as evidence**. Each answer cites article and version. "Silent" is a valid answer; nothing is inferred from silence.

### 5.1 Document identity and validity

| # | Question |
|---|---|
| D1 | Name of the constitution / statutes; version identifier |
| D2 | Adoption date; dates of each amendment; effective date of the version supplied |
| D3 | Authoritative language; translations and their status |
| D4 | Rules of procedure and supplementary regulations in force (titles, versions) |
| D5 | Which body is competent to interpret the constitution |
| D6 | Transitional provisions in the current version |
| D7 | Evidence that the supplied text is authoritative and in force (e.g. registry extract, minutes of adoption) |

### 5.2 Organisational structure

| # | Question |
|---|---|
| S1 | Which organs exist (e.g. general assembly, general committee, other committees)? |
| S2 | How is each organ constituted; quorum; majorities? |
| S3 | Relationship between the general assembly and the general committee (which decides what; appeals) |
| S4 | Which office-holder positions exist; their functions as stated |
| S5 | How office holders are appointed and removed; terms of office |

### 5.3 Membership authority (one block per decision)

Decisions: **admission, review, suspension, restoration, termination, exclusion, resignation, correction, annulment, attestation of historical or migrated records.**

| # | Question (per decision) |
|---|---|
| A1 | Which organ is competent? |
| A2 | Is the decision collective? (quorum, majority) |
| A3 | Is it delegated to an office holder? By which act, recorded where? |
| A4 | May another committee decide it? |
| A5 | Conditions, exceptions, grounds |
| A6 | Rights of the person: notice, hearing, appeal (to which organ, by when) |
| A7 | How the decision is documented (minutes, resolution number, signature) |
| A8 | When it takes effect (date of decision, date of notice, other) |

### 5.4 Delegation

| # | Question |
|---|---|
| G1 | Which authority may be delegated, and which may not? |
| G2 | Who may delegate; to whom? |
| G3 | Is re-delegation allowed; maximum depth? |
| G4 | How is delegation recorded and revoked? |
| G5 | Are acts taken under a later-annulled appointment valid? |
| G6 | Which tasks are administrative execution only (e.g. processing an application) as distinct from deciding? |

### 5.5 Amendment

| # | Question |
|---|---|
| C1 | Which organ amends the constitution; procedure; majority? |
| C2 | When does an amendment take effect? |
| C3 | Do decisions taken under the previous version stay valid? |
| C4 | Must existing memberships be reviewed under a new version, and by which organ? |

### 5.6 Organisational Membership Responsible Person

| # | Question |
|---|---|
| R1 | Which organisational body appoints or authorises the Membership Responsible Person? |
| R2 | Which office or position normally holds this responsibility? |
| R3 | Can another person be appointed instead? |
| R4 | What is the formal basis of the appointment (statute article, organ resolution)? |
| R5 | Which administrative activities may this person perform? |
| R6 | Which Membership decisions, if any, may this person make independently, and on which basis (direct competence or delegation)? |
| R7 | Which decisions require a collective decision of an organ? |
| R8 | May the responsible person delegate administrative tasks, and to whom? |
| R9 | How are the appointment and its removal recorded? |
| R10 | How is the person's operational role distinguished in the organisation's own records from a decision of the competent organ (e.g. minutes recording the organ's resolution and the person executing it)? |

Answers use the same values as §6: article and version, or `SILENT`, or `AMBIGUOUS`. Silence never produces a default authority.

Questionnaire items S, A, G, C and R extend the statutes request in the action package §6.1 (items 1–21). They are the same questions in per-decision form, not a second request.

---

## 6. Multi-organisation comparison template

One row per question, one column per organisation. Cell values: the answer with its **article and version**; or `SILENT`; or `AMBIGUOUS` (with the text); or `NEEDS INTERPRETATION` (with the interpreting body).

```markdown
| Item | Org A (statutes vX, art.) | Org B | Org C | Pattern | Conflicts with platform decision? |
|---|---|---|---|---|---|
| A1 admission: competent organ |  |  |  | common / varies / silent | e.g. D-1, D-2 |
| A2 admission: collective? quorum/majority |  |  |  |  |  |
| A3 admission: delegated? to whom? |  |  |  |  |  |
| A6 admission: appeal |  |  |  |  |  |
| … (one row per §5 item × decision) |  |  |  |  |  |
```

**Pattern column** (filled only after at least three organisations):
- `common`: same answer everywhere supplied;
- `varies`: different answers;
- `optional`: present in some, silent in others;
- `silent`: silent everywhere;
- `ambiguous`: at least one ambiguous text.

**Conflicts column:** checks each answer against the existing PO platform decisions (§7.3). A conflict is recorded, never resolved in the template.

[A] Three or more organisations are needed before any pattern is meaningful. With one organisation, the template records facts about that organisation only.

---

## 7. Common concepts versus organisation-specific rules

### 7.1 Common concepts (established in records)

| Concept | Source | Status |
|---|---|---|
| Person | RAT `:41`; ACT §11 | RATIFIED |
| Member, Membership | EM-VOC-001 (`ELECTION_MANIFESTO.md:218`); RAT `:43` | ADOPTED / RATIFIED |
| Admission (Admit fact), End(kind), Annul, Void, Correct; Renew, Suspend/Restore, ChangeType | RAT §2 `:56-74` | RATIFIED (only Admit, End, Annul, Void, Correct implemented) |
| Decision provenance and authority evidence {actor, capacity, ruleRef, policyVersion, result, checkedAt} | RAT `:72` | RATIFIED |
| Competence, organs, authority, organisational governance rules | ACT §11 | RATIFIED (owner: Governance) |

### 7.2 Concepts the discovery must examine (no definition yet)

Organisational organ, Position, Delegation, Organisation Constitution, constitutional version: all **[P] or [OQ]** (vocabulary O-14 OPEN; DR-07a OPEN). They enter the model only after discovery evidence and the relevant decisions.

### 7.3 Platform-level decisions already taken (constrain CQ-10)

Discovery must check each organisation's statutes against these. A conflict goes to the PO; it is not resolved by configuration.

| Decision | Content (short) | Authority | Possible constitutional conflict to look for |
|---|---|---|---|
| D-1 | every admission carries exactly one recognised basis (declaration, statutory-rule reference, or attested historical evidence for migration only) | PO DECIDED (legal confirmation noted) | statutes allowing admission without declaration (e.g. automatic, ex officio) |
| D-2 | no pending Member: the application stays outside the Member aggregate; approval records Admit | PO DECIDED | statutes with a probationary or candidate-member status |
| D-3 | ending acts are a closed list: resignation, exclusion, death; `legacy` for migration; non-payment is a ground for exclusion, term expiry is derived | PO DECIDED | statutes with automatic lapse, other ending acts |
| D-4 | interim authority mirrors owner/admin; marked interim and replaceable by Governance | PO DECIDED (interim) | **any** statute that assigns admission or ending to an organ (expected to differ) |
| D-5(b) | backdating allowed; future-dated admissions and endings refused | PO DECIDED | statutes with future effective dates (e.g. end of term, notice periods) |
| E-1…E-5 | dues obligation in Membership, payment in Finance; Standing; type change in a period allowed; voting label on a type is a classification only; lifetime and dues-free types | PO DECIDED | statutes tying rights to fee status differently |
| GOV-END B1 | resignation and death recordable by owner/admin with evidence; exclusion refused until Governance can check competence | PO DECIDED (interim) | statutes defining who excludes |
| BD-REV-ER (b) | a correction may not absorb a whole period | PO DECIDED | statutes allowing annulment of a whole membership |
| BR-13, BR-13a | no cross-organisation business rules | Confirmed | federations, umbrella statutes |
| BR-14 | position incompatibility per organisation | Confirmed | — |
| Ratified invariants (INV-M, INV-9, INV-11 …) | RAT §5 | RATIFIED | statutes allowing two simultaneous memberships in one organisation (ACT §14: the only parameter that would reopen the ontology) |

Open platform decisions still relevant [OQ] (FBA `:294`): BD-1, BD-2, BD-SUSP, BD-TERM4, BD-TEN, BD-NU, BD-NORM.

### 7.4 Vocabulary hazard: "Constitution" in code

| Existing use | What it is | Source |
|---|---|---|
| `ElectionConstitution` | election state-transition rules registry ("THE SINGLE SOURCE OF TRUTH for what actions are constitutionally allowed"); a platform rule set, not organisation statutes | `app/Domain/Election/Constitution/ElectionConstitution.php:1-14`; ADR-005 (Accepted) |
| constitutional policy (ADR-B) | frozen, hashed per-election policy record | `docs/publicdigit/adr/ADR_20261014_1138_Constitutional_Policy_Frozen_Record.md:3` (APPROVED CONDITIONALLY) |
| constitutional arbitration (GEO) | committee legitimacy arbitration kernel | `app/Contexts/Membership/Domain/Committee/Constitutional/*` |
| `constitutional_committees` | table with name, jurisdiction scope; **no `organisation_id`** | `…/Tenant/2026_05_12_000001_create_constitutional_committees_table.php:13-20` |
| `ConstitutionalBasis` | Governance value object: article + reference text | `app/Contexts/Governance/Domain/ValueObjects/ConstitutionalBasis.php` |

[P] Use a qualified term ("Organisation Constitution (statutes)") and add it to O-14.

[EO] Two existing artefacts are **precedents** for discovery, not authority:
- ADR-B's frozen, hashed policy record per election (versioning with a hash);
- `ConstitutionalBasis` as an article reference.

### 7.5 CQ-10 options for the PO (not decided)

| Option | Meaning | Consequence |
|---|---|---|
| (a) Fixed platform rules plus a closed set of variation points | the PO lists which items (from §5) may vary per organisation; everything else is platform-wide | predictable model; organisations whose statutes fall outside are not supported, or need a PO exception |
| (b) Variation by evidence | each varying item found in ≥ 1 reference organisation becomes a candidate variation point, decided item by item | grows with evidence; needs governance of each addition |
| (c) Organisation-specific rules everywhere, platform invariants only | everything not in §7.3 varies | maximal fit; highest modelling and verification cost; risk of a de-facto rules engine |

[P] Whatever the option, the existing decisions in §7.3 stay platform-wide unless the PO reverses them, and ratified invariants change only through a Change Request.

---

## 8. Constitution versions and amendments

### 8.1 What is fixed by the ratified architecture [D]

- Facts are append-only; no fact is rewritten (INV-6/14).
- Every decision fact carries provenance, including `ruleRef` and `policyVersion` (RAT `:72`); rule provenance carries `(ref, version)`.
- Resolution is a pure function of the recorded facts (RAT §3).
- G\* accepts a fact only if the invariants hold after resolution (RAT §6).

### 8.2 What remains open [OQ]

| Question | Candidate owner of the answer |
|---|---|
| How an Organisation Constitution is identified | ARB (representation) |
| How its versions are identified (version label, content hash?) | ARB; precedent ADR-B [EO] |
| How adoption and effective dates are recorded | statutes (D2) + ARB |
| Who records an amendment, as what kind of record | ARB; competent organ per statutes (C1) |
| How the applicable version for a decision is established | [P] the version in force at `decidedAt` (§13 N-2), subject to statutes C2 |
| How a decision proves the actor's authority under that version | P11 / R-08 (AuthorityCheck content); blocked by DR-07a, O-9 |

### 8.3 Representation constraint [P]

Whatever representation is chosen, `policyVersion` on a Membership fact must identify a constitutional version that **stays retrievable** for as long as the fact exists. Otherwise historical decisions lose their provenance. This is a retention requirement on the representation, not a design.

---

## 9. Transitional provisions

| Situation | Treatment | Status |
|---|---|---|
| A. Ordinary change | new decisions cite the new version; earlier facts keep their `ruleRef` / `policyVersion` | [D] follows from RAT §2, §3 |
| B. Transitional provision requiring action on existing memberships | executed as **new authorised decisions**, recorded as new facts (e.g. an End decided under the new version) | [P] compatible with RAT; needs PO / statutes for content |
| B′. Transitional provision with ex tunc effect (e.g. setting aside earlier admissions) | executed as authorised **Annul** or **Correct** facts, within their ratified rules (Annul targets only decision-provenance facts; Annul(Void), Correct(Annul), Correct(Void) refused; BD-REV-ER (b): no correction absorbs a whole period) | [P] compatible only within these limits |
| C. Retroactive recomputation under the new version | **excluded** by the ratified architecture; would require a Change Request | [D] |

[OQ] Whether a transitional provision may be executed in bulk (many facts under one organ decision) is a business question for the statutes. Its architectural form (one fact per membership, each citing the same decision) is for the ARB.

---

## 10. Governance–Membership responsibility model (conceptual)

```text
 ORGANISATION [P owner]
      │ adopts / amends
      ▼
 Organisation Constitution (statutes), versioned [OQ representation]
      │ assigns competence directly (a) · permits delegation (b) · assigns administrative tasks (c)
      ▼
 COMPETENT ORGAN / AUTHORISED OFFICE HOLDER ── represented by ──► GOVERNANCE [E: competence, organs,
      │ Membership decision (collective act | act under delegation)        authority — ACT §11]
      ▼
 Authority decision + evidence {actor, capacity, ruleRef, policyVersion, result, checkedAt} [E shape, RAT :72]
      │ via AuthorityCheck port [E port; contract content OPEN — P11]
      ▼
 MEMBERSHIP [E] — checks invariants (G*) — records the fact with provenance
      │ may refuse a competently decided act that breaks an invariant
      ▼
 Membership facts → other contexts evaluate their own rules (Election: entitlement, INV-11)

 ORGANISATIONAL MEMBERSHIP RESPONSIBLE PERSON [P]
      └─ coordinates, processes applications, records or submits evidence of the organ's decision
         (operational; does not decide unless competence (a) or delegation (b) says so)

 PLATFORM ACCESS ROLE (owner / admin) [E interim D-4]
      └─ controls technical access; establishes no organisational authority

 PUBLICDIGIT TECHNICAL TEAM (technical Membership owner, §4.4)
      └─ implements and maintains the approved software model; holds no organisational authority
```

`[E]` established · `[P]` proposed · `[OQ]` open. This is a conceptual picture, not a context map.

| Fact | Owner | Status |
|---|---|---|
| Organs, positions, competence, delegation | Governance | [D] ACT §11 (organs); positions and delegation as concepts [P] |
| Constitution text and versions | [OQ] Governance or Organisation | ARB |
| Membership facts (Admit, End, …) with provenance | Membership | [D] |
| Authority evidence stored on the fact | Membership stores; Governance supplies | [D] RAT §8 |
| Collective vs delegated nature of the act | Governance (to supply); `capacity` must express both organ and position | [OQ] P11 |

### 10.1 Can the ratified evidence fields express the role distinctions?

Ratified contract: {actor, capacity, ruleRef, policyVersion, result, checkedAt} (RAT `:72`). No new field is proposed here.

| Distinction | Expressible with the ratified fields? | Evidence / gap |
|---|---|---|
| Who performed or recorded the act | `actor` | [EO] in the implementation, `actor` is the person whose role is checked (`InterimRoleAuthority.php:38-48`) |
| Organ versus individual office or position | `capacity` is a free string, so it can name an organ or a position | [EO] `AuthorityEvidence.php` stores it unconstrained; the interim adapter writes `organisation_role:<role>` |
| Collective decision (quorum, majority, resolution or minutes reference) | **not expressible as a separate element**; `ruleRef` names the rule (statute article), not the organ's resolution | [OQ] no ratified field carries the decision reference of a collective act; D-2's `declarationRef` covers the applicant's declaration only |
| Operational recorder versus deciding organ | **ambiguous**: does `actor` act *as* `capacity`, or record *on behalf of* the organ named in `capacity`? The ratified text does not say | [OQ] |
| Applicable constitutional rule and version | `ruleRef`, `policyVersion` | expressible; retention requirement §8.3 |
| Platform access versus constitutional authority | not by the six ratified fields alone; `capacity` = `organisation_role:admin` *looks like* a capacity | [EO] the implementation adds a **seventh field `competenceVerified`**, set `false` for the interim role check (`AuthorityEvidence.php`; `InterimRoleAuthority.php:50`). It expresses exactly this distinction, but it is **not in the ratified contract** |

**Questions for the ARB** (recorded only; nothing changed):
- OQ-12: does the ratified evidence contract need an explicit reference to the deciding organ's resolution, and an explicit "on behalf of" relation between `actor` and `capacity`? A change to the ratified contract needs a Change Request.
- OQ-13: is the implemented `competenceVerified` field a conforming refinement of the ratified contract or a deviation that needs recording? (It was first observed in the P11 preparation sweep, not yet assessed by the ARB.)

**Recorded dependencies, respected:** P11 is not finalised here (needs DR-07a, O-9, Q-new-1/2). [F] The current only AuthorityCheck implementation is `InterimRoleAuthority` (unbound; Admit for owner/admin, End denied).

---

## 11. Open questions and decision authorities

| # | Question | Authority |
|---|---|---|
| OQ-1 | Confirm or amend PO-ORG-MEM-01 | PO |
| OQ-2 | Which organisations are reference organisations; who represents each | PO |
| OQ-3 | CQ-10: variation scope (options §7.5) | PO; ARB for consequences |
| OQ-4 | Where the Organisation Constitution is represented | ARB |
| OQ-5 | Version identity and retention (§8.3) | ARB |
| OQ-6 | Minimum authority evidence; can `capacity` name an organ? | ARB (P11 / R-08) |
| OQ-7 | Conflicts between statutes and D-1…D-5, E-1…E-5, GOV-END B1 (§7.3) | PO |
| OQ-8 | Qualified term for "Organisation Constitution"; add to O-14 | ARB |
| OQ-9 | `constitutional_committees` has no `organisation_id`: tenant scope of this table | ARB / INV-ORG-SCOPE-1 owners |
| OQ-10 | Bulk execution of transitional provisions | statutes; ARB (form) |
| OQ-11 | ~~Whether a PO may act as technical Membership owner~~ answered for the current phase: the Principal states he holds the role (§4.4); safeguard: independent review of M-1…M-5 | PO |
| OQ-12 | Evidence contract: reference to a collective decision; "on behalf of" between `actor` and `capacity` (§10.1) | ARB (Change Request if the ratified contract changes) |
| OQ-13 | Status of the implemented `competenceVerified` field against the ratified contract (§10.1) | ARB |
| OQ-14 | How PublicDigit will know who each Organisation's Membership Responsible Person is, and from when (record of appointment, §5.6 R9) | PO; ARB for representation |

---

## 12. Dependency matrix and recommended sequence

| ID | Action | Authority | Depends on | Enables | Lane |
|---|---|---|---|---|---|
| SQ-1 | Confirm or amend PO-ORG-MEM-01 | PO | — | SQ-4 framing; ARB validation | A business ownership |
| SQ-1′ | Designate technical owner; send M-1…M-5 | PO | — | ARB-5/6 | B existing decisions |
| SQ-2 | Name reference organisations and representatives | PO | SQ-1 (recommended, not required) | SQ-3 | C discovery |
| SQ-3 | Collect statutes and questionnaire answers (§5) | representatives | SQ-2 | SQ-4 | C |
| SQ-4 | Fill the comparison template (§6); list conflicts with §7.3 | engineering prepares; representatives confirm | SQ-3 (≥ 3 organisations for patterns) | SQ-5, SQ-6 | C |
| SQ-5 | Decide CQ-10 and the §7.3 conflicts | PO | SQ-4 | SQ-6 | C |
| SQ-6 | Representation of constitutions, versions, evidence (§8, §10) | ARB | SQ-1, SQ-5; DR-07a | P11 | C → B |
| SQ-7 | P11 Membership–Governance relationship | ARB | DR-07a, O-9 (statutes), Q-new-1/2, SQ-6 | strategic exit | B |

**In parallel, unchanged:** Phase 2.0 Anonymity ADR commissioning; ARB first tranche (ARB-1…4, ARB-8, INV-ORG-SCOPE-1 with O-5 scope, O-14, Q-new-1/2, XD-4 structure, INV-AFF-1 target principle); Membership delivery COR-3 and Slice 2b.

---

## 13. Risks, assumptions and evidence gaps

**Risks:**
- R-1: one organisation's statutes are taken as representative of all (mitigation: §6 pattern only after ≥ 3).
- R-2: silence is read as a default (mitigation: `SILENT` is a recorded value; defaults are PO decisions).
- R-3: "Constitution" collides with existing technical terms (§7.4).
- R-4: interim D-4 authority (owner/admin) is mistaken for constitutional authority (mitigation: the principle §3.3 item 3).
- R-5: a configurable rules engine is designed before variation is known (mitigation: §7.5 options are decided after evidence).
- R-6: transitional provisions are implemented as recomputation (mitigation: §9 C is excluded).

**Assumptions:** [A] reference organisations will be able to supply authoritative, versioned statutes; [A] at least three organisations are reachable.

**Evidence gaps:** no statutes in the corpus; no organisational representative named; the legal validity of any provision is out of scope.

### Formal notes (only where they clarify)

**N-1. Authority does not override invariants.** accept(f) ⇔ authorised(f) ∧ G\*(S ∪ {f}). Authorisation is necessary, not sufficient. *Consequence:* a competent organ's admission that would create overlapping periods (INV-9) is refused, and the refusal must be explainable to the organ.

**N-2. Applicable version** [P]. With versions Cᵥ in force over [aᵥ, aᵥ₊₁): v(f) = the v with decidedAt(f) ∈ [aᵥ, aᵥ₊₁), unless the statutes' C2 say otherwise. valid(f) ⇔ competent(actor(f), act(f), C_{v(f)}). *Consequence:* recording `policyVersion` at decision time is sufficient for later audit, provided §8.3 retention holds.

**N-3. Transitional effect.** For a transition at aᵥ₊₁, the permitted effects are new facts g with v(g) = v+1 (End, Annul, Correct within their rules). Changing Resolution(S) for a fixed S is not permitted (RAT §3, purity). *Consequence:* option C of the Principal's A/B/C question is implementable only as "A plus new facts".

ML was not used. Deterministic searches were sufficient.

---

## 14. Updated next-step TODO list

**Lane A: business ownership**
- [ ] PO: confirm or amend PO-ORG-MEM-01 (§3.1), including §3.3 items 1–4.
- [ ] ARB: validate per §3.4 after PO confirmation.

**Lane B: existing decision programme (unchanged, critical path for DR-07a)**
- [x] Technical Membership owner designated: Nab (stated 2026-11-03, §4.4); formal annotation of the DR-07 record is for its owner.
- [ ] Nab answers M-1…M-5 from repository evidence (response template: action package §4.3).
- [ ] Independent review of the M-1…M-5 answers before the ARB acts on ARB-5/6 (§4.4 safeguard).
- [ ] ARB first tranche (action package §5).

**Lane C: constitutional discovery**
- [ ] PO: name reference organisations (≥ 3 recommended) and their authorised representatives.
- [ ] Representatives: supply statutes and answer §5 (with articles and versions).
- [ ] Engineering: fill §6; list conflicts with §7.3.
- [ ] PO: decide CQ-10 and the §7.3 conflicts.
- [ ] ARB: representation of constitutions, versions and authority evidence; qualified term (O-14).
- [ ] ARB: P11 after DR-07a, O-9, Q-new-1/2.

**Independent lanes**
- [ ] PO: commission the Phase 2.0 Anonymity ADR (PBDIGIT-223, -224, -225, -227, -228, -241 A8, -284).
- [ ] Membership delivery: COR-3 story (none exists yet); Slice 2b.

**Maintenance (outside this document)**
- [ ] `developer_guide/database/` warning: for the COR-4 owner to assess.
- [ ] `CONTEXT.md`: not changed by this commission.

**Smallest next action:** the PO confirms or amends PO-ORG-MEM-01. On the DR-07a path, the smallest action stays the technical-owner designation. Neither waits for the other.

---

## Traceability

- Ratified: RAT §2, §3, §5, §6, §8 (`:56-74, :78-92, :112-125, :127-136, :152-157`); ACT §10–§14.
- PO decisions: D-1…D-5 (R/`2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md:290-296`); E-1…E-5 (R/`2026-11-02-membership-e0b-s-decision-record.md`); GOV-END B1 (R/`2026-11-02-membership-gov-end-decision-record.md`); BD-REV-ER (R/`2026-11-02-membership-bd-rev-er-decision-record.md`); PO-DR07-01 (DR-07 paper §15); BR-13, BR-13a, BR-14.
- Preparation documents: action package §3.5, §6; baseline-stabilisation review §4, §8.
- Code precedents: `app/Domain/Election/Constitution/ElectionConstitution.php`; `docs/publicdigit/adr/ADR_20261014_1138_Constitutional_Policy_Frozen_Record.md`; `app/Contexts/Governance/Domain/ValueObjects/ConstitutionalBasis.php`; `app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/2026_05_12_000001_create_constitutional_committees_table.php`.
- No code, schema, test, ADR, business rule, decision record, context map, BDR, Platform Rules Index or ratified document was changed. Nothing was committed.
