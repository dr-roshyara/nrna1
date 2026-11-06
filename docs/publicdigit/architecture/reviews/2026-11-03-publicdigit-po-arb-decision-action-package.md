# PublicDigit PO / ARB decision action package

| | |
|---|---|
| **Status** | **DECISION PREPARATION.** It prepares actions for the PO, the ARB, the Board and the Membership owner. It decides, approves, ratifies and authorises nothing, and changes no status in any other record. |
| **Authority** | generated (Claude; architecture, governance and requirements roles). Reviewer: the Principal DDD Architect. |
| **Date** | 2026-11-03 |
| **Snapshot** | branch `election-flow-updated`, HEAD `19b3082a3` (3 commits after `4032d6ca9`, all KnowledgeOS). |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0). |
| **Builds on** | `2026-11-03-baseline-stabilisation-and-strategic-decision-preparation-review.md` (REVIEW; abbreviated "the review" below). |

**Labels.** [F] verified fact with source · [D] existing decision by a named authority · [P] proposal · [OQ] open question · [EO] engineering observation · [A] assumption. Source wording and statuses are quoted, not translated.

**Paths.** R/ = `docs/publicdigit/reviews/` · B/ = `docs/publicdigit/backlog/`.

---

## 1. Executive summary

1. [F] **Nothing strategic changed since the review.** No decision record, register, ARB pack, context map, business rule or ratified Membership document changed between `4032d6ca9` and HEAD. The review's statuses and dependencies still hold, except for the correction in item 2.
2. [F] **Correction to the review and to the as-is baseline report (G-1).** The voter↔vote linkage through the audit receipt hash **is already recorded**: B/`PBDIGIT-241-voter-ballot-linkage-and-receipt-secret-in-logs.md:14` ("Attached decision — A8 (PO 2026-10-05) … deterministic voter ↔ ballot link … Decision required: remove it, or keep it as a governed dispute-resolution capability"). The whole linkage class (A1–A8) sits under a PO-approved roadmap with an **accepted interim risk** until a "Phase 2.0 Anonymity ADR". That ADR does not exist yet. The security action is therefore **a PO decision to commission Phase 2.0**, not a new discovery or a new backlog item (§7).
3. **Ready now for the PO:**
   - name the Membership owner and send M-1…M-5 (§3–§4);
   - widen and send the statutes request (§6);
   - commission the Phase 2.0 Anonymity ADR, and decide how PBDIGIT-284 ("Fix before any production election") relates to the accepted interim risk (§7).
4. **Ready now for the ARB** (first tranche, §5):
   - DR-07a readings ARB-1…ARB-4 and ARB-8;
   - INV-ORG-SCOPE-1, after the ARB fixes its scope with respect to O-5;
   - O-14 vocabulary, after the brief is corrected (a fourth Observer meaning, ElectionMembership's term definition).
5. **Blocked:**
   - ARB-5/ARB-6 (need M-1…M-5);
   - ARB-7 (its prerequisites are unmet);
   - P11 (needs DR-07a, O-9 statutes, Q-new-1/2);
   - P5 (needs a decision on the falsification pass F6).
6. [F] **Membership delivery progressed and continues separately:** COR-2 (PBDIGIT-327) and COR-4 (PBDIGIT-329) are ACCEPTED. The next roadmap item, COR-3, has **no backlog story yet**.
7. **Smallest next action:** the PO designates the Membership owner and sends M-1…M-5 (§3).
8. [P] **Business ownership clarification (Principal DDD Architect, 2026-11-03):** the Organisation is the business owner of the Membership domain, and its Constitution is the source of business authority (§3.5). This is a proposal, not a decision. It shows that DR-07's "Membership owner" is a **technical context owner**, not the business owner. M-1, M-4 and M-5 are therefore for the technical owner. The business parts of M-2 and M-3 may need an authorised organisational representative (§3.5.3).

---

## 2. Repository-state validation

| Check | Result |
|---|---|
| Branch / HEAD | [F] `election-flow-updated` / `19b3082a3` |
| Changes since `4032d6ca9` | [F] 3 commits; none touches `docs/publicdigit`, `docs/implementation`, `docs/adr`, `app`, `database` or `tests` |
| Decision and strategic records changed | [F] none (DR-07 paper, ARB pack, register, INV-ORG-SCOPE-1 / INV-AFF-1 records, context map, business rules, Membership ratified set) |
| Working tree | [F] other sessions' uncommitted changes (`.claude/` session files, `developer_guide/election/ip_voting/*`, KnowledgeOS scratch, `bootstrap/cache/packages.php`); the untracked `tests/Isolated/Pbdigit274/Database/VoteAnonymityLinkageTest.php`. None touched |
| Review accuracy | [F] statuses hold. [F] **One correction:** G-1 is recorded (item 1.2 above). [F] Confirmed: PO-DR07-01 recorded; DR-07a OPEN; ARB acts pending; P2 independent of P1 |

---

## 3. Critical PO action: designate the Membership owner

### 3.1 What the PO must decide

[F] The DR-07 paper names the respondent only as "**Membership owner** (another developer): agreement, because the legacy committee code sits in the Membership context" (R/`2026-11-02-governance-dr07-committee-ownership-decision-paper.md:41`). [F] §16 addresses that owner directly: "Your answer is recorded as given. Nothing is assumed" (`:299`). [F] No record names the person.

**Decision required:** *Who is the Membership owner for DR-07a, and in which capacity does that person answer?*

[F] In the DR-07 paper the role is technical: "another developer … because the legacy committee code sits in the Membership context". It is the owner of the **Membership bounded context's code**. It is not the business owner of the Membership domain (§3.5).

[F] Precedent: the PO has acted "as the single owner for Governance" (R/`2026-11-02-membership-gov-end-decision-record.md:4`). [OQ] Whether the PO may also act as the Membership owner, or must designate another person, is not stated in any record. This package does not guess.

### 3.2 Responsibility of the Membership owner (as the records describe it)

| Responsibility | Source |
|---|---|
| Confirm or correct the inventory of the legacy committee write model in `app/Contexts/Membership`, and the invariants it enforces | DR-07 §16 M-1 |
| State the meaning of `CommitteeAssociation` | M-2; OQ-DR07-1 (PO-DR07-01 item 4, `:268`) |
| Agree or object to Governance owning organs (option A) | M-3 |
| Agree or object to freezing the legacy write model as a behavioural reference (B), and propose freeze wording | M-4 |
| Name operational risks before any cutover | M-5 |

**Implementation responsibility versus business authority** [P, consistent with the records]:
- The Membership owner speaks for the **technical state and risks** of code in the Membership context (M-1, M-4, M-5) and gives an **opinion** on M-3.
- Whether committees are organs is already decided by the PO (PO-DR07-01). Who owns organs is for the ARB (ARB-2, ARB-5).
- Organisational and constitutional rules (who may appoint, what an organ may decide) come from statutes (O-10), not from the Membership owner.
- M-2 is mixed: its technical part is what the code does; whether `CommitteeAssociation` *should* mean unit affiliation or organ participation is a business question that may need the PO (see the response template, §4.3).

### 3.3 Consequences of leaving the role unnamed

- [F] ARB-5 (ADR-0001 location clause) and ARB-6 (freeze rule) cannot be decided: both act on code the Membership owner holds.
- [F] ARB-7 lists "Membership owner position (M-1…M-5)" as a precondition (`:318`).
- [F] DR-07a stays OPEN, so O-6, the providers of context-map R-07/R-08/R-09/R-13, the XD-4 permission content and P11 stay blocked.
- [F] The ARB pack advises holding the DR-07a session "after the Membership owner answers DR-07 §16" (R/`2026-11-02-organisation-governance-arb-decision-pack.md:32-35`).

### 3.4 Message the PO can send [P]

> **Subject: DR-07a: designation as Membership owner and request for answers M-1…M-5**
>
> I designate [name] as Membership owner (technical owner of the Membership bounded context's code) for the purpose of DR-07a (committee write-model ownership), as referred to in `docs/publicdigit/reviews/2026-11-02-governance-dr07-committee-ownership-decision-paper.md` §16. [If the PO designates themselves: "I act as Membership owner for DR-07a myself."]
>
> Please answer questions M-1…M-5 of §16 using the template in `docs/publicdigit/architecture/reviews/2026-11-03-publicdigit-po-arb-decision-action-package.md` §4.3. Answers are recorded as given; nothing is assumed. Please mark for each answer whether it is a technical assessment or a business position, and whether PO or ARB approval is needed.
>
> Business premise already decided (PO-DR07-01, 2026-11-02): governance committees are constituted governance organs; organisational units are a distinct concept; existing records are not classified by that ruling; the meaning of `CommitteeAssociation` is for you to state (M-2).
>
> Requested by: [date]. No implementation is authorised by this request.
>
> [If the business parts of M-2 and M-3 go to an organisational representative (§3.5.3): "The business meaning in M-2 and the position in M-3 are also requested from [organisational representative], acting for [organisation] under [statutory basis]."]

### 3.5 Business ownership clarification (PROPOSED by the Principal DDD Architect, 2026-11-03)

#### 3.5.1 The proposed principle [P]

> "The Organisation is the business owner of the Membership domain. Its constitutionally authorised organs and office holders exercise the corresponding business authority. Administrative responsibilities may be delegated, but delegation does not transfer domain ownership. PublicDigit implements the rules and records the authoritative membership facts without acquiring organisational authority."

> Corollary (same proposal): "PublicDigit provides a common Membership domain model, while each Organisation's Constitution determines the applicable membership rules, decision authorities and procedures. Constitutional differences must be represented explicitly rather than hidden inside organisation-specific code or assumed to be identical."

**Status:** a proposal. It needs a PO business decision and ARB validation before it constrains context responsibilities or permissions. It does not reopen the ratified Membership architecture.

#### 3.5.2 Consistency with the records

| Proposal element | Existing record | Relationship |
|---|---|---|
| Governance defines who has authority; Membership manages the lifecycle | [F] act §11: Governance owns "competence, organs, authority and organisational governance rules"; ratified §8: "Governance: the AuthorityCheck port; Membership stores evidence only" | **consistent**: the proposal states the business side of a boundary that is already ratified |
| The Constitution is the source of authority | [F] ratified fact provenance: "decision (actor + authority evidence {actor, capacity, ruleRef, policyVersion, result, checkedAt} …) \| rule (ref, version) \| attested" (`…ratified.md:72`) | **consistent**: each recorded decision can already cite the rule and its version. *How* a constitution becomes `ruleRef` / `policyVersion` is open |
| Organisation-specific rules on a common model | [F] BR-13 / BR-13a (no cross-organisation rules, CONFIRMED); BR-14 (incompatibility scoped per organisation, CONFIRMED); [OQ] CQ-10 (how far rules vary per organisation, needs a platform-level PO decision, §6.3) | **consistent**; CQ-10 is the platform decision that bounds "configuration" |
| PublicDigit acquires no organisational authority | [F] ratified §3: resolution is "pure", independent of acceptance; INV-11 (no entitlement from Membership); GOV-END B1 (exclusion refused until Governance can check competence) | **consistent** |
| Changes to constitutional rules must be authorised and traceable | [F] append-only facts (INV-6/14); [OQ] no record defines how a constitution amendment is itself recorded | partly covered (membership facts); **open** for the constitution itself |

#### 3.5.3 Consequence: three roles, not one

| Role | Who (as recorded) | Answers |
|---|---|---|
| **Business owner of the Membership domain** | [P] the Organisation, through its constitutionally competent organs | what membership means *for that organisation* and who decides each act (statutes, §6) |
| **Authorised organisational representative** (for discovery) | [OQ] not named; acts for the Organisation within delegated authority | business meaning in M-2; position in M-3; the statutes request (§6) |
| **Technical owner of the Membership context** ("Membership owner" in DR-07) | [OQ] not named ("another developer") | M-1 (code inventory, invariants), M-4 (freeze), M-5 (cutover risks); technical part of M-2 |

So the user TODO "an authorised organisational representative answers M-1…M-5" is only partly right. M-1, M-4 and M-5 are about code and need the technical owner. A President or Secretary cannot verify a code inventory. M-2 and M-3 can carry both a technical and a business part. The response template (§4.3) records which is which.

#### 3.5.4 The constitution-amendment question (options A, B, C)

The question: when a Constitution changes, should PublicDigit apply new rules only to later decisions (A), re-evaluate existing memberships (B), or support both as the Constitution's transitional provisions require (C)?

**What the ratified architecture already fixes** [F]:
- facts are append-only and never rewritten (INV-6/14);
- every decision fact records its `ruleRef` and `policyVersion`;
- resolution is a pure function of the recorded facts;
- KnownAt is stable.

**Consequences for the options** [P, analysis only]:
- **A** fits the ratified model directly. Earlier facts stay valid under the rule version they cite.
- **B**, if it means *recomputing* past memberships under new rules, conflicts with append-only facts and stable KnownAt, so it would need a Change Request.
- **B as new acts** is compatible. Transitional effects are carried out as **new, authorised decisions recorded as new facts** (e.g. an End decided under the new rule, effective from the transition date).
- **C** is therefore compatible only in the form "A, plus transitional provisions executed as new authorised facts". Retroactive recomputation is excluded.

**Who decides:**
- the **business** choice (whether transitional provisions exist, and what they say): the statutes and the PO;
- the **architectural** form: the ARB, within the ratified Membership architecture.

**Open** [OQ]: how a constitution and its versions are represented; who records an amendment; how Membership verifies that a decision was taken by a competent organ under a given version (the AuthorityCheck content, P11 / R-08).

#### 3.5.5 The collective-or-delegated question

The Principal asks: does the General Committee decide membership matters collectively, or does the constitution delegate particular decisions to specific office holders or another committee?

**Status:** this cannot be answered from the repository. It is CQ-2 / CQ-3 (register O-10), which only the statutes can answer. It is now an explicit item in the statutes request (§6.1 items 5, 11, 19).

Architectural consequence [P]: the AuthorityCheck contract must be able to express both kinds of answer:
- "organ X decided (collective act, with quorum and resolution reference)";
- "position holder Y decided under delegation Z".

The ratified evidence shape {actor, capacity, ruleRef, policyVersion, result, checkedAt} carries both, if `capacity` can name an organ as well as a position. [OQ] Whether it can is for the P11 / R-08 specification.

---

## 4. M-1…M-5 questionnaire

### 4.1 The questions (source wording, DR-07 paper §16, `:301-306`)

| ID | Exact question | Why it matters | Evidence to check (source) | Decision affected | Respondent | If unanswered |
|---|---|---|---|---|---|---|
| M-1 | "Is §4's inventory of the committee write model in `app/Contexts/Membership` complete and correct? Which invariants does it enforce today?" | Defines the migration scope and the behavioural reference for option B. [EO] The legacy code also contains governance-authority logic (`Domain/Committee/Capability/*`, `Constitutional/*`, `Geo/Authority/AuthorityResolver.php`, `Geo/Graph/DelegationEdge.php`) that §4 does not mention | `Domain/Committee/*`, `Application/Committee*`, `Infrastructure/Repositories/EloquentCommitteeRepository.php` | ARB-5, ARB-6; ARB-7 precondition | Membership owner | ARB-5/6 undecidable; parity criteria (B) undefined |
| M-2 | "What does `CommitteeAssociation` mean: membership through an organisational unit, or participation in an organ?" | Decides whether the work belongs to DR-07a (organs) or DR-07b / O-16 (units). [EO] The ADR-0001 read model reads `committee_associations`, not the declared write store `committee_assignments` (`app/Contexts/Committee/Infrastructure/Persistence/EloquentCommitteeMembershipReadModelAdapter.php:12,19`) | `Domain/Membership/CommitteeAssociation.php`, `CommitteeAssociationLifecyclePolicy.php`, `MembershipApplication.php`, `ReviewMembershipApplicationHandler.php:27-38` | DR-07b, O-16, OQ-DR07-1; ARB-4 caveat | Membership owner (technical); PO if it is a business meaning | O-16 stays open; ARB-4 is decided without knowing which store the read model really projects |
| M-3 | "Do you agree that Governance owns governance organs (option A)? If not, why?" | Option C would need a Change Request against the frozen Membership architecture (paper `:109`); an objection with reasons is decision evidence | ratified §11; PO-DR07-01 | ARB-2, ARB-5 | Membership owner (position) | ARB decides without the owner's position |
| M-4 | "Do you agree to freeze the legacy committee write model (security and bug fixes only) and keep it as the behavioural reference until verified parity (B)? Proposed freeze wording?" | The freeze rule is ARB-6's subject; the owner holds the code it would freeze | B-1; plan `20261011-0103…:83` | ARB-6 | Membership owner | ARB-6 undecidable |
| M-5 | "Operational risks or objections to resolve before any cutover: live users of the legacy endpoints, the `committee_assignments` history, the G-9 end-date defect" | Cutover risk and order. [EO] Three mount points route the Governance committee controller (`routes/api_v1.php:49-56`; `routes/governance-api.php:62-72` loaded twice); the L-3 save overwrites `left_date` on rows absent from the aggregate (`app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:142-149`) | register §5 | ARB-7 | Membership owner | ARB-7 not satisfiable |

### 4.2 What the questionnaire must not do

- It must not supply answers. The [EO] notes above are evidence pointers, not answers.
- It must not turn a code observation into a business rule. For example, the `Committee::assignMember` invariants (`Committee.php:515-557`) describe current code, not organisational law.

### 4.3 Response template (one block per question)

```markdown
### M-n — <short title>

| Field | Answer |
|---|---|
| Answer | <the answer, in the respondent's words> |
| Nature | technical assessment / business position / both |
| Supporting evidence | <files, lines, tests, data checks, records> |
| Assumptions | <what the answer relies on that is not verified> |
| Unresolved ambiguity | <what the respondent cannot answer, and who could> |
| Approval needed | none / PO (business meaning) / ARB (architecture) |
| Respondent and capacity | <name> as Membership owner (designated by the PO on <date>) |
| Date | <YYYY-MM-DD> |
```

---

## 5. Parallel ARB first tranche

These briefs can go to the ARB before the M-1…M-5 answers arrive. Each is a separate item.

### 5.1 INV-ORG-SCOPE-1: ratification and the O-5 scope

**Established** [D]: principle approved by the PO on 2026-11-01 (§1, §1a, §1c); ARB ratification "pending"; enforcement "open; not authorised" (R/`2026-11-01-organisation-inv-org-scope-1-organisation-scoped-access-decision-record.md:42-47`).

**The discrepancy** [F]:

| Source | What it includes |
|---|---|
| ARB pack §1, exact act (`…arb-decision-pack.md:51`) | "INV-ORG-SCOPE-1 (record §1, §1a, §1c) is ratified. OQ-OS-1, OQ-OS-2, OQ-OS-3 **and O-5 remain open**. No enforcement is authorised by this act." Option (b), extending §1a to O-5, "changes the approved wording, so it needs the PO first" (`:48`) |
| Register, row A-1 (R/`2026-11-02-organisation-governance-strategic-design-status-assessment.md:123`) | "ratify INV-ORG-SCOPE-1, **including O-5**" |
| O-5 itself (register `:94`) | "INV-ORG-SCOPE-1 §1a: deletion-eligibility check not a permitted platform operation"; owner "PO + ARB"; source BR-13-S |
| Record §1a (`:34`) | permitted pre-Working-Organisation operations: authentication, organisation discovery, organisation selection, create-or-join routing. The deletion-eligibility check is not listed |

**Competing interpretations:**

| Interpretation | Meaning | Consequence |
|---|---|---|
| (i) Pack | ratify the approved wording only; O-5 stays open | formalises what the PO approved; Stage 2 enforcement design can be scoped; the deletion-eligibility check (XD-PROV AM-1) has no permitted basis until O-5 is decided |
| (ii) Register | ratify including O-5 | changes approved wording without a prior PO act; the ARB would be extending a PO-approved principle |
| (iii) Sequenced | PO decides O-5 first (classify the check as a form of discovery, or amend §1a), then the ARB ratifies the resulting text | one act, but INV-ORG-SCOPE-1 ratification waits on a PO decision |

**Decision required from the ARB:** confirm the scope of the act (i, ii or iii). If (ii) or (iii), name the PO act required first. This package does not choose.

### 5.2 O-14: vocabulary

**Source brief:** ARB pack §2 (`:54-86`), recommended option (a), exact act at `:84`. **Two corrections are needed before the act** [F]:
1. The brief lists three Observer meanings. A fourth exists in code: `CommitteeRole::OBSERVER` (`app/Contexts/Governance/Domain/Committee/Enums/CommitteeRole.php:12`).
2. The brief says ElectionMembership needs "no rename … the meaning is already ratified" (`:75`). Act §11 ratifies **ownership** ("Election owns: ElectionMembership", `…ratification-act.md:323-332`). Register O-14 lists ElectionMembership inside the open split (`…status-assessment.md:103`). The brief should say which part (ownership or term definition) the act adopts.

| Term | Business meaning | Technical meanings (code) | Existing adopted / ratified | Proposed | Unresolved | Responsible context |
|---|---|---|---|---|---|---|
| Member | Person holding an Organisation Membership | registrant `role='member'`; `organisation_users.role` enum; committee role `MEMBER`; three `MemberId` classes; `EnsureOrganisationMember` treats any role row as membership | EM-VOC-001 ADOPTED (`ELECTION_MANIFESTO.md:218,404`); ratified §1 | keep only the domain meaning (pack `:62`) | renaming of technical names (not authorised) | Membership [E] |
| Membership | enduring Person↔Organisation relation of periods and facts | legacy "membership lineage" (committees); election roster "membership" | RATIFIED (`…ratified.md:43`); EM-VOC-002 (affiliation ≠ Membership, ADOPTED `:219`) | — | `CommitteeAssociation` meaning (M-2) | Membership [E] |
| ElectionMembership | Election-owned roster record per (person, organisation, election) | one `role` column ∈ {voter, candidate, observer, admin, committee} (`…create_election_memberships_table.php:24`) | ownership RATIFIED (act §11); EM-VOC-003: does not imply Organisation Membership (ADOPTED `:220`) | split of the role column | term definition (O-14) | Election [E] |
| Role | (a) PositionKind; (b) AccessRole | Spatie roles (global); dashboard roles; seven code vocabularies | none (EM-VOC-002 covers role assignments ≠ Membership) | PositionKind (Governance) / AccessRole (Organisation / Authorisation) (pack `:69-70`) | the split itself | not established |
| Position | office or seat of an organ | `role_path`, `role_hierarchy`; Election `CommitteeSeat` | none; BR-14 (incompatibility per organisation) Confirmed | Governance (pack `:68`) | capacity κ (BQ-P1, O-8) | Governance [P] |
| Observer | (a) election roster role; (b) "no voting rights" membership type | (c) default authorisation label (`app/Models/Election.php:1868`); **(d) `CommitteeRole::OBSERVER`**; (e) newsletter audience `election_observers` | E-4 DECIDED: a voting label on a type is a classification only | split (pack `:76`) | (d) has no proposed meaning yet | as split |

[P] **Decision required from the ARB:** adopt the corrected table (meanings only; ownership marked candidate stays open). The act should say explicitly that no code renaming is authorised (pack `:84`).

### 5.3 DR-07a: readings ARB-1, ARB-2, ARB-3, ARB-4 and ARB-8 (separate items)

| Item | Source (as identified) | Exact ask (paper §17 / pack §3) | Already established | Still open | Consequence of the reading |
|---|---|---|---|---|---|
| **ARB-1** | act §10 (`…ratification-act.md:283-305`): the Membership aggregate "must not absorb … committee participation" | "confirm the reading: an aggregate boundary … not a context-ownership rule" | the text [E] | whether §10 constrains context ownership | if confirmed, §10 does not by itself decide where committee code lives |
| **ARB-2** | act §11 (`:319-321`): Governance "Owns competence, organs, authority and organisational governance rules" | "confirm the reading: 'Governance owns … organs' is the context-ownership rule; with PO-DR07-01 it places governance organs in Governance; no amendment of the ratified act is needed" | the text [E]; PO-DR07-01 [D] | whether ADR-0001's location clause conflicts with ratified §11 | if confirmed, option C would need a Change Request against the ratified act |
| **ARB-3** | PO-DR07-01 (DR-07 paper `:261-281`) | "take note of the business premise" | PO business decision recorded 2026-11-02 [D] | — | the ARB does not re-decide whether committees are organs |
| **ARB-4** | ADR-0001 CQRS principle (`docs/adr/ADR-0001-committee-read-write-separation.md:95-97`, Accepted) | "confirm it remains valid (one write model as the source of truth; read models never write)" | ADR-0001 Accepted [D] | **caveat** [EO]: the current read model projects `committee_associations`, not the declared write store (M-2 row); the ADR artifact is a chat wrapper around a fenced ADR with no named acceptor | if confirmed, the principle survives any move of the write model |
| **ARB-8** | BDR-03 (`docs/architecture/design/Round49-06_Boundary_Decision_Register.md:16`, IMMUTABLE): "Appointment (Authority)" owns Mandate truth; pack §3 (`:103-116`) | "how does option A relate to BDR-03?" (i) Governance **is** the context BDR-03 names, BDR re-issued via ADR; (ii) Appointment stays separate, owning Mandate | BDR-03 frozen [E]; pack recommends (i) [P] | the choice | (i): one context for organs and authority, as §11 groups them; (ii): an explicit boundary contract for every appointment |

**Kept separate:**
- **ARB-5** (ADR-0001 location clause) and **ARB-6** (freeze and parity criteria) need M-1…M-5.
- **ARB-7** (conditions before cutover) is not satisfiable yet: G-7 production use, O-16 classification and O-4 are open.

---

## 6. Widened organisational-statutes request

### 6.1 Request text [P]

> **Subject: Request for statutes and governance rules of [organisation]**
>
> PublicDigit needs the authoritative organisational rules for the subjects below. Please send the statutes and any rules of procedure in force, with their version and in-force date. Where the documents are silent on a subject, please say so; we will not infer a rule.
>
> **A. Positions and incompatibility**
> 1. Which positions exist in each organ (position kinds), and how many holders each may have.
> 2. Which positions may not be held together, and whether any numeric limit applies to positions one person holds.
> 3. Conflict-of-interest and recusal rules for each organ.
> 4. Which positions require membership, and for how long (minimum years). Which exceptions exist (e.g. staff, guests, scrutineers), and who may authorise them.
>
> **B. Membership acts and their competent organ**
> 5. Which organ decides admission, review, exclusion, suspension and restoration, and correction or annulment of membership records.
> 6. Who may attest migrated or historical membership records.
> 7. The exclusion procedure: notice, hearing, appeal, effective date.
> 8. Resignation: when it takes effect (on receipt, or after a notice period).
> 9. Statutory or ex-officio admission: when it applies.
> 10. What happens to a person's office when their membership ends or is suspended.
>
> **C. Organs, terms and delegation**
> 11. Which body is the organisational governance body.
> 12. How organs are constituted and dissolved.
> 13. Terms of office and discharge.
> 14. Delegation: what may be delegated, re-delegation, maximum depth.
> 15. Validity of acts taken by a person whose appointment is later annulled.
> 16. Assignment of territorial jurisdiction to organs.
> 17. Powers of the general assembly, including appeals.
>
> **D. Finance**
> 18. The finance committee's mandate: budget, spending thresholds, fee rules, waivers, write-offs, refunds, and separation of duties between approval and payment.
>
> **E. Decision-making and amendment**
> 19. For each membership decision (items 5–10): is it taken collectively by an organ (e.g. the general committee, with quorum and majority), or delegated to a named office holder (e.g. president, general secretary) or another committee? Where are such delegations recorded, and can they be revoked?
> 20. How is the constitution amended (competent organ, procedure, effective date), and where are amendments recorded?
> 21. Transitional provisions: when a rule changes, do earlier membership decisions remain under the old rule, or must existing memberships be reviewed under the new one?
>
> **F. Delivery**
> - Version, in-force date and amendment history.
> - Authoritative language; translations if any.
> - Confidentiality: may the documents be stored in the PublicDigit documentation repository?
> - Who is authorised to interpret them if questions arise.
> - Requested by: [date].

### 6.2 Request metadata to settle before sending

| Item | State | Owner |
|---|---|---|
| Recipient | not named ("the PO names it", pack `:159`) | PO |
| Organisation | placeholder "[organisation; preferably the intended operator organisation]"; [F] the operator organisation is not yet created (Decision C §2a) | PO |
| Reference sample or binding for a named tenant | not stated | PO |
| Version, in-force date, amendment history | requested in §6.1 F (and items 20–21) | — |
| Representative constitutions from several organisations | [P] needed for CQ-10 (commonality vs variation, §3.5.1 corollary); one organisation's statutes cannot show variation | PO |
| Language | requested | — |
| Confidentiality and corpus placement | requested; placement via `doc-placement.php` after classification | PO; steward |
| Interpretation authority | requested; [F] D-1 and D-3 already require legal confirmation | PO |
| Deadline | to be set | PO |
| Default when silent | [P] the PO decides; recorded as a PO decision, not inferred | PO |
| Rules of procedure (e.g. election committee) | included in §6.1 | — |

### 6.3 What statutes can and cannot answer

| Answerable by one organisation's statutes | Needs a platform-level PO decision |
|---|---|
| A1–A4, B5–B10, C11–C17, D18 for that organisation | **CQ-10** "how far rules may vary per organisation" (which rules are tenant configuration and which are fixed platform rules) |
| | the default behaviour when a tenant's statutes are silent |
| | whether the operator organisation's statutes serve as a reference model for other tenants |

---

## 7. Security triage brief: voter↔vote linkage (G-1)

### 7.1 Correction

[F] The review and the as-is baseline report said the audit receipt-hash linkage was "not recorded in any committed record". **That was wrong.** It is attached decision A8 of B/`PBDIGIT-241-voter-ballot-linkage-and-receipt-secret-in-logs.md:14`, PO 2026-10-05, status "🟡 A8 attached decision OPEN" (`:10`). Both earlier documents keep their text as written; this package records the correction.

### 7.2 Frozen requirement

[F] VO-1 "A Vote Must Not Be Linkable to the Voter Who Cast It" (`docs/architecture/discovery/Round29_Invariant_Catalog.md:251-266`). FROZEN derivatives: Blueprint CI-5 ("No consumer may infer voter identity from any event payload", `docs/implementation/PushB_Architecture_Blueprint.md:39`); ADR-T11. Not weakened or reinterpreted here.

### 7.3 Governance already in place

| Record | Content | Status |
|---|---|---|
| Roadmap `docs/plans/20261005-0647-voting-integrity-anonymity-and-authorization-roadmap-plan.md` §2a | "Phase 2.0 decides what anonymity means. Phase 2.1+ implements the consequences" (`:28`); **interim rule:** "No new persistent voter ↔ ballot link may be introduced" (`:42`); "Accepted interim risk: A5 (PBDIGIT-223), A6 (PBDIGIT-224) and A1–A4, K2–K5 remain live in production until Phase 2.0 + 2.1. Recommendation unchanged: inform the production owner" (`:70`) | **APPROVED by the PO (2026-10-05)** as the roadmap; "only Phase 0 is authorised" (`:3`) |
| Phase 2.0 Anonymity ADR | the decision point for all A-items | **does not exist** (no file under `docs/adr` or `docs/publicdigit/adr`) [F] |
| PBDIGIT-223 | `vote_hash` computable from voter-side data (A5) | OPEN, DECISION PENDING (Phase 2.0) |
| PBDIGIT-224 | step-5 record stores the vote id (A6) | OPEN, decision pending (Phase 2.0) |
| PBDIGIT-225 | cast logs correlate voter and ballot (A1) | OPEN, decision pending (Phase 2.0) |
| PBDIGIT-227 | vote ids encode creation time | OPEN, decision pending |
| PBDIGIT-228 | receipt and view-my-vote expose the ballot | OPEN, decision pending |
| PBDIGIT-241 | F-V6/F-V7 RESOLVED (receipt secret no longer logged); **A8 audit receipt hash OPEN** | partly resolved |
| PBDIGIT-284 | `vote_hash` links vote to voter; "`app.vote_salt` is not defined in any config file … so the salt is the empty string" (`:15`) | "`OPEN` · not authorised · **priority: highest. Fix before any production election**" (created 2026-10-13); RED evidence committed (`6bc02a342`) |

### 7.4 Affected paths and conditions (verified in code, no production data)

| Linkage | Code | Data needed by an attacker | Status |
|---|---|---|---|
| Stored link: vote id in the voter's own step record | `VoteController.php:1871-1874` (`voter_slug_steps.step_data.vote_id`; `voter_slugs.user_id`) | database read | known (A6, PBDIGIT-224) |
| Log link: vote id with slug and IP | `VoteController.php:1875-1879` | application logs | known (A1, PBDIGIT-225) |
| Audit receipt hash `sha256(vote_id . user_id . election_id)` with user, IP | `VoteController.php:1950-1965`; `app/Services/ElectionAuditService.php:46-57, 88-100` | audit JSONL files + `votes` ids of the election | known (A8, PBDIGIT-241) |
| `vote_hash = sha256(code.id . election.id . code_to_open_voting_form . castAt.timestamp . vote_salt)` | `VoteController.php:2924-2932`; [F] no `vote_salt` key in `config/` (grep), so the salt is always `''` | `codes` (carries `user_id`) + `votes` | known (A5, PBDIGIT-223 / -284) |
| Note | `votes.receipt_hash = sha256(private_key . vote.id . app.key)` (`:2952`) is a different value from the audit hash, so there is no direct join; linkage via the audit hash needs enumeration over the election's vote ids | — | [EO] |

**Threat actor:** anyone with read access to the audit trail or logs together with the `votes` table (operators, database administrators, holders of backups), as PBDIGIT-223 `:11` already states. **Attack cost** [A, estimate]: |audit entries| × |votes in the election| SHA-256 operations; for 10⁴ voters about 10⁸ hashes. **Impact:** breaks ballot secrecy for every recorded vote whose inputs survive, including backups (PBDIGIT-223 `:17`). **Severity** [P]: highest; PBDIGIT-284 already says so.

**Guard coverage** [F]:
- `tests/Architecture/GreenfieldCoreArchitectureTest.php:33-38` scans only Contestation, Adjudication, Election and the Shared inbox/outbox; it does not cover `VoteController`, `app/Services` or `Contexts/Elections`.
- `tests/Feature/Voting/VoterBallotLogLinkageTest.php` (PBDIGIT-241) covers log pairing for the final cast and receipt flows.
- Characterization tests assert the known gaps (`tests/Feature/Characterization/Phase1/BallotAnonymityCharacterizationTest.php`).
- The untracked `tests/Isolated/Pbdigit274/Database/VoteAnonymityLinkageTest.php:127-168` targets `vote_hash` recomputation and audit linkage; it belongs to another session's work and was not run or touched.

### 7.5 What still needs runtime verification (PO / Ops; not possible from the repository)

- [OQ] Whether production has run real elections since 2026-10-05, or has any scheduled. This determines whether historical votes are exposed now.
- [OQ] Whether "inform the production owner" (roadmap `:70`) was done; no record of it was found.
- [OQ] Who can read the audit JSONL directory and the database backups in production.
- [A] Production `config('app.vote_salt')` is empty: true by code unless a config file outside the repository defines it.

### 7.6 Options

| Option | Content |
|---|---|
| Containment (no code change) | restrict read access to audit files and backups; do not run real elections until the PO decides; record the decision |
| Containment (code change, separately authorised) | stop writing new audit receipt hashes and new step-5 vote ids. This *removes* links, so it is compatible with the interim rule. It is still a production change and needs PO approval |
| Permanent | the Phase 2.0 Anonymity ADR decides receipt / view-my-vote semantics (Model A vs B), whether `vote_hash` remains, and governed dispute resolution; Phase 2.1+ implements; historical data handled per the ADR |

### 7.7 Evidence required for closure

- RED→GREEN tests per linkage class (the PBDIGIT-284 RED exists).
- A vote-path scan that covers `app/Http/Controllers` and `app/Services`, not only greenfield contexts.
- Recomputation tests showing no (vote, user) pair is derivable from stored data.
- A documented decision on historical data and backups.

### 7.8 Recommended PO triage decision [P]

1. Commission the **Phase 2.0 Anonymity ADR** now.
2. Decide how PBDIGIT-284 ("Fix before any production election") relates to the accepted interim risk of roadmap §2a, and record the answer.
3. Confirm with Ops whether any production election is running or scheduled.
4. **No new backlog item is needed.** A1–A8 are covered by PBDIGIT-223/224/225/227/228/241/284.

This triage runs independently of the DR-07a sequence and does not block it.

---

## 8. Decision dependency matrix (verified at HEAD)

| ID | Decision / action | Authority | Readiness | Dependencies | Blocks | Lane |
|---|---|---|---|---|---|---|
| MO | Designate the Membership owner | PO | **ready now** | — | M-1…M-5 | critical path |
| M-1…M-5 | Answers to DR-07 §16 | Membership owner | after MO | MO | ARB-5/6/7 | critical path |
| ARB-1…4, ARB-8 | DR-07a readings | ARB | **ready now** | — | ARB-5/6 framing | parallel |
| ARB-5/6 | ADR-0001 location clause; freeze rule | ARB | blocked | M-1…M-5 | DR-07a closure | critical path |
| ARB-7 | conditions before cutover | ARB | not satisfiable | M-1…M-5; G-7 use; O-16; O-4; T6 | implementation authorisation | later |
| DR-07a | committee write-model ownership | ARB | partial | the rows above | O-6, P11, XD-4 content, R-07/08/09/13 | critical path |
| O-14 | vocabulary | ARB | ready after brief correction (§5.2) | fourth Observer meaning; ElectionMembership scope | relationship contracts | parallel |
| INV-ORG-SCOPE-1 | ratification | ARB (PO first if O-5 is included) | ready after the scope ruling (§5.1) | O-5 interpretation | Stage 2 enforcement; PBDIGIT-266/309 | parallel |
| P2 | statutes | PO | request widened (§6) | recipient; organisation | O-8, O-9, O-10; T-08 | parallel (external evidence) |
| Phase 2.0 | Anonymity ADR | PO commissions; ADR by the recorded process | not started | — | PBDIGIT-223…228, -241 A8, -284 | security lane |
| P3 | event surface | Board (R-97 owner); [OQ] whether the Board is the ARB | options prepared | Board ruling | P4 | baseline lane |
| P5 | Election code placement | PO / ARB | not ready | F6 commissioning decision | P4 | baseline lane |
| P8 | FeePaid event | PO routing; Membership + Finance owners; AD/CR for blueprint `:61` | payment direction decided (E-1) | event ownership and naming | Standing (E-2) | strategic |
| P9 | pattern applicability | ARB | analysis prepared | — | P4 | baseline lane |
| P4 | baseline authority / 1.2 | ARB via ADR-T + Architecture Review | not ready | P3, P5, P9 | baseline claims | baseline lane |
| XD-4 | authorisation ownership | ARB | structure ready | content: DR-07a, XD-7, O-11 | authorisation contract | parallel (structure) |
| INV-AFF-1 | affiliation principle | ARB; data protection | target principle only | U-1…U-5 (O-3) | D-1 option C; BR-9 | parallel |
| Q-new-1 / Q-new-2 | direct cross-context reads; event-only scope (platform integration) | ARB (routed by the PO 2026-11-02) | ready (pack items 6–7) | — | P11 contract style; many relationships | parallel |
| XD-5 | geography history | PO + ARB | not ready; no act drafted | O-11, OQ-OS-1 | jurisdiction replay | later |
| P11 | Membership ↔ Governance relationship | ARB | not ready to finalise; R-08 outline possible after DR-07a | DR-07a, O-9, Q-new-1/2 | strategic exit | later |
| P7 | product subdomain classification | ARB | analysis prepared | — | investment priorities | later |
| ES-001…006 | per-rule adoption | "the Authority" [OQ] | evidence exists | — | legitimacy of CLAUDE.md bindings | hygiene |
| P16 | strategic exit assessment | PO + ARB | not ready | all strategic rows | tactical governance design | end |
| COR-3, Slice 2b | Membership delivery | PO per slice | COR-3 story not yet created | ratified architecture | cutover | delivery lane |

**Summary by lane:**
- **Can proceed now:** MO; ARB-1…4 and ARB-8; O-14 (after correction); INV-ORG-SCOPE-1 (after the scope ruling); Q-new-1/2; XD-4 structure; INV-AFF-1 as target principle; statutes request; Phase 2.0 commissioning.
- **Blocked:** ARB-5/6/7, DR-07a closure, P11, P4, P5.
- **Needs external evidence:** P2 (statutes), INV-AFF-1 ratification (U-1…U-5), Phase 2.0 production facts.
- **Implementation, not strategic:** COR-3, Slice 2b, S2–S4 (gated per slice), PBDIGIT-266, guard repairs.

---

## 9. Membership delivery status (separate track)

| Item | Status | Source |
|---|---|---|
| Ratified architecture | RATIFIED, FROZEN FOR IMPLEMENTATION; not reopened here | R/`2026-10-08-membership-architecture-ratified.md:3` |
| COR-2 | **ACCEPTED** (PO 2026-11-03), kernel only, residual risks documented | B/`PBDIGIT-327-…:7` |
| COR-4 | **ACCEPTED** (PO 2026-11-03), residual risks documented; closes ACC-260 K-2 and roadmap V-1, V-4, V-5, V-13…V-15, G-A2; "No migration on any …" | B/`PBDIGIT-329-…:7` |
| COR-3 | next per roadmap (W4); [F] **no backlog story found** | grep of `docs/publicdigit/backlog` |
| Slice 2b | held (W5) | roadmap `…20261016_2219_…completion_plan.md` |
| S2–S4 (Membership–Election violations) | gated; each needs PO approval | B/`PBDIGIT-318-…:7` |

No delivery item has completed since the review.

---

## 10. Risks and unresolved questions

| # | Risk / question | Owner |
|---|---|---|
| R-1 | The Membership owner stays unnamed, so DR-07a and everything after it stall | PO |
| R-2 | Real elections run while the anonymity interim risk is live and no Phase 2.0 ADR exists | PO / Ops |
| R-3 | INV-ORG-SCOPE-1 is ratified with an unclear O-5 scope | ARB |
| R-4 | The O-14 act adopts the brief uncorrected (three Observer meanings; ElectionMembership scope) | ARB |
| R-5 | Statutes arrive from an organisation that is not the intended reference, or with no version information | PO |
| OQ-1 | May the PO act as Membership owner? | PO |
| OQ-2 | Is the "Board" (R-97 owner) the ARB? | PO |
| OQ-3 | Who adopts ES-001…006 ("the Authority")? | PO |
| OQ-4 | Who decides an AD/CR against the ratified blueprint `:61`: the Principal Reviewer or the ARB? | PO |

---

## 11. Recommended next actions

1. **PO:** designate the Membership owner and send M-1…M-5 (§3.4).
2. **PO:** commission the Phase 2.0 Anonymity ADR; decide the PBDIGIT-284 / interim-risk relation; confirm production election status with Ops (§7.8).
3. **ARB, first tranche:** INV-ORG-SCOPE-1 scope ruling and ratification; O-14 (corrected); ARB-1…4 and ARB-8; Q-new-1/2.
4. **PO:** settle the statutes metadata (§6.2) and send the request (§6.1).
5. **After M-1…M-5:** ARB-5/6; then the R-08 outline for P11.

Items 2–4 do not wait for item 1.

---

## 12. Updated TODO list

**A. PO actions (now)**
- [ ] Designate the technical Membership context owner (DR-07 "Membership owner"; OQ-1: may the PO act in this role?).
- [ ] Decide whether the business parts of M-2 / M-3 also go to an authorised organisational representative, and name that representative (§3.5.3).
- [ ] Take a business position on the ownership principle "the Organisation owns the Membership domain; its Constitution is the source of authority" (§3.5.1, PROPOSED); then refer it to the ARB.
- [ ] Send M-1…M-5 with the response template.
- [ ] Commission the Phase 2.0 Anonymity ADR.
- [ ] Decide how PBDIGIT-284 relates to the accepted interim risk; confirm with Ops whether a production election is running or scheduled.
- [ ] Settle the statutes request metadata and send the widened request.
- [ ] Route P8 (FeePaid event ownership and naming) to the Membership and Finance owners.

**B. ARB decisions (parallel)**
- [ ] INV-ORG-SCOPE-1: rule on the O-5 scope, then ratify.
- [ ] O-14: correct the brief, then adopt.
- [ ] DR-07a readings ARB-1, ARB-2, ARB-3, ARB-4, ARB-8 (one act each).
- [ ] Q-new-1 and Q-new-2.
- [ ] XD-4 structure (content later).
- [ ] INV-AFF-1 as target principle only.

**B2. Strategic discovery opened by the ownership clarification (after statutes)**
- [ ] Obtain representative constitutions from several organisations; identify common structure and variation (CQ-10).
- [ ] Map organs, positions and delegated authority per membership decision (collective vs delegated, §6.1 item 19).
- [ ] Decide how a constitution and its versions are represented, and how an amendment is recorded (§3.5.4, open).
- [ ] Decide the architectural form of transitional provisions: new authorised facts, never retroactive recomputation (§3.5.4; ARB within the ratified architecture).

**C. Blocked strategic items**
- [ ] ARB-5/6 (after M-1…M-5); ARB-7 (after G-7, O-16, O-4, T6).
- [ ] O-8, O-9, O-10 (after statutes); O-11 → XD-5.
- [ ] P11 (after DR-07a, O-9, Q-new-1/2).
- [ ] P7; P16.

**D. Baseline**
- [ ] Board: P3 (options a/b/c); [OQ] Board vs ARB.
- [ ] PO/ARB: commission or decline F6 → P5.
- [ ] ARB: P9 → P4 (Baseline 1.2 or scoped governing map).

**E. Security (Phase 2.0 lane)**
- [ ] Phase 2.0 ADR → Phase 2.1+ implementation (PBDIGIT-223, -224, -225, -227, -228, -241 A8, -284).
- [ ] Widen the anonymity guard to the legacy vote path (after Phase 2.0 defines the target).

**F. Membership delivery (independent)**
- [ ] Create and plan the COR-3 story (none exists).
- [ ] Slice 2b (W5).
- [ ] S2–S4 boundary slices: obtain per-slice PO approval, then remove the baselined violations.

**G. Hygiene (separate maintenance)**
- [ ] `CONTEXT.md` warning: this commission does not change the active state; the routed file for `app/` work (`.claude/CONTEXT-publicdigit.md`) has uncommitted changes from another session. No action under this commission.
- [ ] `developer_guide/database/` warning: triggered by the COR-4 migration (PBDIGIT-329). That work updated `developer_guide/membership/14_cor4_correction_persistence.md`. [A] The warning comes from the hook's area mapping (`database/migrations` → `database`). Whether a `database` guide is needed is for the COR-4 owner to decide.
- [ ] ES-001…006 per-rule adoption; ES-005.4 DEFERRED but bound in CLAUDE.md (recorded X-3).
- [ ] Classification of the 397 `withoutGlobalScope(s)` calls (OQ-OS-3, separately authorised); the raw count is not a vulnerability count.

---

## 13. Formal notes (only where they clarify a decision)

**N-1. O-14 as a non-implication lattice** (established axioms, ADOPTED / CONFIRMED / RATIFIED):
- User(p, o_boot) ⇏ Member(p, o_boot) (BR-3);
- Aff(p,o,c) ⇏ Member(p,o) (EM-VOC-002);
- EM(p,o,e) ⇏ Member(p,o) (EM-VOC-003);
- Member(p,o,t) ⇏ Entitled(p,e,t) (INV-11).

*Business question:* which technical names must never be read as Member? *Consequence:* each arrow is a testable property. `EnsureOrganisationMember` (any role row ⇒ member) violates the second [EO]. O-14 adds meanings; it adds no new arrows.

**N-2. INV-ORG-SCOPE-1 scope as a set question.** Let Permitted₁ₐ = {authentication, discovery, selection, create-or-join} (record §1a, approved). O-5 asks whether deletion-eligibility ∈ Permitted₁ₐ. Interpretation (i) ratifies Permitted₁ₐ as written. (ii) ratifies Permitted₁ₐ ∪ {deletion-eligibility} without a PO act. (iii) lets the PO decide membership of the set first. *Consequence:* only (i) and (iii) keep the ARB from extending a PO-approved set.

**N-3. Anonymity as non-linkability** (frozen axiom VO-1): no function computable from stored data D yields (vote, user) better than chance. Each A-item is a counterexample over a different subset of D (step data; logs; audit files + votes; codes + votes). *Consequence:* the closure evidence (§7.7) must test each subset separately; a schema-only test does not cover them.

**N-4. Constitution versions and membership facts** (ratified axioms plus an open representation question).

*Business question:* does a constitutional amendment change earlier membership decisions?

*Symbols:* let Cᵥ be the Constitution in version v, in force over [aᵥ, aᵥ₊₁). A decision fact f records ruleRef(f) and policyVersion(f) = v(f) (ratified provenance, `…ratified.md:72`).

*Established axioms:* facts are append-only, and resolution is a pure function of the fact set S. Hence an amendment to C_{v+1} changes no existing f and no Resolution(S); it can only change which **new** decisions are valid.

*Proposed rule [P]:* valid(f) ⇔ competent(actor(f), act(f), C_{v(f)}) at decidedAt(f) ∈ [a_{v(f)}, a_{v(f)+1}).

*Transitional provision [OQ, statutes]:* a set of new decisions g with v(g) = v+1, recorded as new facts.

*Consequence:* option B (recomputation) is excluded by the ratified axioms. Options A and C (C as new facts) are compatible. Who may approve the proposed rule: PO (business) and ARB (form).

ML was not used. Deterministic searches were sufficient.

---

## 14. Evidence and limitations

- **HIGH:** statuses and quotations read from source files at HEAD `19b3082a3`; code facts at the cited lines.
- **MEDIUM:** the readiness judgements (they anticipate authorities' needs); the hook-warning interpretation (§12 G).
- **Not done:** no tests were run; production was not inspected; no person was consulted; no answer was given on behalf of any authority.
- **Corrections recorded:** G-1 was already recorded (PBDIGIT-241 A8); the earlier review and the as-is report are not modified.
- **Boundaries kept:** no code, schema, test, ADR, business rule, decision record, register, context map, Platform Rules Index or ratified Membership document was changed. Membership strategic design has not started. Nothing was committed.

## Traceability

- DR-07 paper §15–§17: R/`2026-11-02-governance-dr07-committee-ownership-decision-paper.md:261-318`
- ARB pack §1–§3, §6: R/`2026-11-02-organisation-governance-arb-decision-pack.md:39-162`
- Register O-5, A-1, O-14: R/`2026-11-02-organisation-governance-strategic-design-status-assessment.md:94,103,123`
- INV-ORG-SCOPE-1 record §1a–§1b: R/`2026-11-01-organisation-inv-org-scope-1-organisation-scoped-access-decision-record.md:32-47`
- Anonymity: `docs/plans/20261005-0647-voting-integrity-anonymity-and-authorization-roadmap-plan.md`; B/`PBDIGIT-223`, `-224`, `-225`, `-227`, `-228`, `-241`, `-284`
- Membership delivery: B/`PBDIGIT-327`, `-329`, `-318`
