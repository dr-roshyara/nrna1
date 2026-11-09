# Governance procedural validity: strategic discovery before tactical DDD

| | |
|---|---|
| **Status** | **PROPOSED discovery evidence.** It decides, ratifies and authorises nothing. It changes no existing record or code. Not independently reviewed (Claude family). |
| **Commission** | Principal, 2026-11-09: "Governance Procedural Validity: Strategic Discovery Before Tactical DDD" (recommendations accepted by the Principal). |
| **Builds on** | `2026-11-09-proposal-to-governance-decision-scenario-discovery.md` (PROPOSED) |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` |
| **Snapshot** | branch `election-flow-updated`, HEAD `5a26453d5` |

**Labels:** **FACT** · **INTERPRETATION** · **ASSUMPTION** · **UNKNOWN** · **RECOMMENDATION** · **[PK]** = the Principal's recorded practical knowledge (discovery record of 2026-11-08; *direct* or *as reported*) · **[PRAC]** = general party practice known to the analyst. **[PRAC] is shown only to name the questions statutes usually answer. It is never used as a rule.**

## 0. Disagreements and the decisive limitation

**Disagreement 1 (minor): "Finance owns the Finance fact FeePaid" is not a settled constraint.**
- The ratification act's recorder note N-5 reads: "EP-01 treats FeePaid as a **Finance** fact consumed by Membership" (`2026-10-08-membership-architecture-ratification-act.md:18`).
- Later records call the direction open: `context-map.md` §4 ("Direction not established in the ratified text"); `strategic-design-status.md:186` ("Still with the PO: the FeePaid direction question"); `2026-11-02-membership-e0b-s-decision-record.md:39`.
- The code runs the other way (Membership → Finance, `MembershipPaymentService`).
- The point is respected here as a working reading, and it has no bearing on this investigation.

**Disagreement 2 (scope of a reported issue): the earlier report overstated issue 2.** The path it named, `GovernanceSetupController` → `ActivateCommitteeStructureUseCase`, is an **owner-only, one-time initial set-up**. It is refused once `governance_status` is `active` (`app/Policies/OrganisationPolicy.php:65-72`). The ongoing path is a different one, `CommitteeManagementController` (§3). The substance of the concern stands; the evidence was mis-pointed.

**The decisive limitation (FACT): no authoritative procedural source exists in any of the Principal's projects.**

| Searched | Result |
|---|---|
| file names containing constitution, statute, Satzung, bidhan/vidhan, standing order, bylaw, niyamawali, विधान, नियमावली, manifesto (all projects under `personal/`) | only **software** "constitution" documents (e.g. `docs/CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md`, `claude/governance/*`), and one PDF |
| `public/manifesto.pdf` (read, pages 1–3) | **an NRNA candidate's election manifesto** (General Secretary candidacy, 12th NRN Global Conference, 14–16 March 2026). Not a statute. It contains **no procedural rule**. As product context only, it pledges digital membership, finance and records management (commitment 1), strengthening National Coordination Councils (2), and transparent membership and elections (3) |
| status record §9a | "No constitutional source material or authoritative appointment / delegation evidence is on file" |
| roadmap Phase 2 | "at least one authoritative, versioned statute registered": ☐ not done; the NRNA request is drafted (Appendix A) and not sent |

**Consequence:** all thirteen procedural questions below are **UNKNOWN as organisation rules**. What this report *can* establish without statutes:
- the **concepts and their ownership**, which are derivable from ratified and working architecture, not from rules;
- the **statute-independent invariants**;
- a **verified implementation picture**;
- which tactical elements are **justified now** and which must stay **unresolved**.

## 1. The process questions: what is known, and from where

| # | Question | Authoritative rule | [PK] recorded | Architecture (existing) | Status |
|---|---|---|---|---|---|
| 1 | Who may submit | none | — | — | **UNKNOWN** |
| 2 | To which organ | none | the General Convention adopts the constitution and defines committee types; the Highest Committee governs between conventions and issues Governance Rules; explicit reservations go to the Convention, otherwise residual competence lies with the Highest Committee (§2.1–§2.2, *as reported*; residual source clause not found) | competence checked on the decision date (D-12) | organs [PK]; rules UNKNOWN |
| 3 | Admissibility | none | — | — | **UNKNOWN** |
| 4 | Who checks admissibility | none | — | — | **UNKNOWN** |
| 5 | Agenda | none | — | — | **UNKNOWN** |
| 6 | Properly convened sitting | none | the Highest Committee records rules "as resolutions in meeting minutes" (V-18, *direct*): **meetings and minutes exist at least for the Highest Committee** | — | existence [PK]; rules UNKNOWN |
| 7 | Quorum | none | — | quorum code exists only for **voting** (`app/Domain/Voting/QuorumRule/*`) | **UNKNOWN** |
| 8 | Decision methods (sitting vote, written circulation, electronic) | none | — | — | **UNKNOWN** |
| 9 | Outcomes | none | a resolution prevails over the rulebook (V-19, *direct*) | D-10 decision kinds: individual act, rule-making, correction, ratification, refusal; the scenario report adds dispositions of a submission [P] | partly [D] working |
| 10 | Record and authentication | none | resolutions are recorded in minutes, then consolidated in a rulebook (V-18); rule log for exceptions (V-17) | who may *enter* a decision in PublicDigit: "organisation rules decide" (V-59, *direct*); recording ≠ validity (D-12; 2026-11-08 model §16) | partly [PK] |
| 11 | When it takes effect | none | office starts with the new working period (V-13); outgoing holders continue (V-12) | effective date ≥ decision date except ratification (D-6, D-12); ratification relates back (RA-4) | [D] working |
| 12 | Communication | none | — | Communication delivers; whether publication is a *condition of effect* is [OQ] | **UNKNOWN** |
| 13 | Reconsideration and challenge | none | the Highest Committee "can intervene" on overdue terms (V-12) | §5 below | **UNKNOWN** |

**Organisation-specific rules vs platform capability (INTERPRETATION, H):** under D-1, D-1a and D-1b, every answer in the "authoritative rule" column belongs to each organisation, and PublicDigit may hold no default. So the platform's job is not to *know* these rules. Its job is to **record each organisation's rule version and the procedural facts of each decision, in a form from which validity under that rule version can be judged**. Who judges is the next question.

## 2. Concepts, meaning and ownership

The ownership below comes from **ratified and working architecture** (act §11: Governance owns "competence, organs, authority, organisational governance rules"; D-4, D-5, D-10, D-12), **not from rules**. No new bounded context follows from the different names: **every concept below is Governance-owned**, except where stated.

| Concept | Business meaning | Owner | Created / changed by | Depends on | Consumers | Authoritative fact or evidence? |
|---|---|---|---|---|---|---|
| Organ | a body that may decide | Governance (D-10 `Organ`) | a decision setting it up | rules; Organisation unit (link) | all | fact |
| Rule version | which rule was in force when | Governance (D-10 `RuleSet`) | a rule-making decision | — | Governance, Organisation (R-15), Election (R-6a) | fact |
| Submission | a matter formally before an organ | Governance [P] (scenario report HD-P2, recommended, not decided) | the entitled submitter, by the act of submitting | rule version at submission; submitter's entitlement (as-at answers) | Deliberation (status), Communication | fact (that it was submitted, and when) |
| Admissibility | whether the organ may take up the matter | Governance | whoever the rules name | rules, organ competence | the submission's outcome | **a decision or a recorded finding** (UNKNOWN which) |
| Agenda | the matters scheduled for a sitting | Governance | whoever convenes (UNKNOWN) | sitting | members of the organ | evidence (of due process) |
| Sitting | an occasion on which an organ acts | Governance | the convening authority (UNKNOWN) | convening rules | the decisions taken at it | **evidence** of procedure; its legal weight UNKNOWN |
| Quorum | the attendance a valid sitting needs | Governance (rule content: each organisation) | — | rules; attendance | decision validity | rule (organisation) + evidence (attendance) |
| Recommendation | advice to the deciding organ | Governance (as part of the matter) | an advising body | — | the deciding organ | **evidence only, never a decision** |
| Official decision | the organ's act | Governance (D-10 `Decision`) | the competent organ | competence on the decision date; procedure per rules | Organisation (R-1), Election (R-6), Authorization (R-11), Deliberation, Communication | **fact** (that it was taken), immutable |
| Minutes / decision record | the authenticated record of decisions | Governance | secretary or chair (UNKNOWN; V-59 "organisation rules decide" who enters it into PublicDigit) | sitting | anyone verifying a decision | **evidence** of the decision (V-18: the resolution in the minutes prevails over the rulebook) |
| Effective date | from when the decision acts | Governance (property of `Decision`) | the decision | rules; ratification | everyone downstream (C-2) | fact |
| Reconsideration | a new decision on the merits of an earlier one | Governance | the competent organ (which one: UNKNOWN) | the earlier decision | as for decisions | fact (a new decision) |
| Legitimacy challenge | a claim that a decision was taken by an incompetent organ or in breach of procedure | **open** (§5) | the challenger | the decision and its procedural evidence | — | a claim (until ruled) |

**Two consequences (INTERPRETATION, H):**
1. **Validity has four layers**, and only the first is already designed:
   - (a) **competence** of the organ on the decision date (D-12);
   - (b) **procedure**: convening, agenda, quorum, method, majority;
   - (c) **form**: record and authentication;
   - (d) **effect**: effective date, publication, ratification.
   
   (b)–(d) are organisation rules and are all UNKNOWN.
2. **Most procedural concepts are evidence, not decisions.** This is consistent with the already accepted directions P-2 ("a software evaluation result means 'consistent with the interpretation the organisation approved', never 'legally valid'") and **P-4 ("record decisions first … add evaluation in advance only when evidence justifies it")** (discovery record §1, accepted as direction [P]).

## 3. Implementation against the business picture (re-verified on 2026-11-09)

| Item | Finding (FACT) | Classification | Consequence | Architectural or technical? |
|---|---|---|---|---|
| `GovernanceDecision` dates | `record()` throws "Effective date cannot be after decision date" when `effectiveFrom > decidedAt` (`app/Contexts/Governance/Domain/GovernanceDecision.php`, `record`) | **POTENTIALLY MISALIGNED** with D-12 / C-2 / D-6 (effective ≥ decision, except ratification). The code allows exactly the case the model forbids (back-dating) and forbids the normal case (future effect) | structural decisions with a future effective date cannot be recorded; back-dated effects need no ratification | **architectural**: an invariant is inverted |
| `GovernanceDecision` use | no production caller of `GovernanceDecision::record` found | **LEGACY / dormant** | the decision record is not in use | — |
| Initial structure set-up | `GovernanceSetupController::store`/`activate` → Membership `DefineCommitteeStructure` / `ActivateCommitteeStructureUseCase` (`execute(array)`), allowed only for platform role `owner` and only while `governance_status !== 'active'` (`OrganisationPolicy.php:65-72`); no decision reference | **POTENTIALLY MISALIGNED** with D-1b ("shared content only as a template an organisation adopts by its own recorded act"): adoption happens by a platform account, with no recorded act | the organisation's initial structure has no organisational basis on record | architectural (authority source) |
| Ongoing committee creation and change | `CommitteeManagementController::store` → `CommitteeModel::create(...)` and `update` → `UpdateCommitteeDetails`, allowed for platform roles `owner`/`admin` (`OrganisationPolicy.php:46-60` `manageCommittee`); no decision reference (`CommitteeManagementController.php:91-115,142-187`) | **POTENTIALLY MISALIGNED** with D-21/C-1…C-5 (structure changes only by a Governance decision) and with PO-ORG-MEM-01 A's principle (platform permission ≠ organisational authority). Also the legacy path under `ADR_COMMITTEE_CREATION_DUAL_PATH.md` (ACCEPTED: deprecate the legacy path) | units and organs change without any decision basis; no history to reconstruct "who decided" | **architectural** |
| `ApprovalRequirementPolicy` | hard-codes, for every organisation: `COMMITTEE_FORMATION → PARENT_COMMITTEE`, `CONSTITUTIONAL_AMENDMENT → ICC_BOARD`, `AUTHORITY_DELEGATION → SOURCE_COMMITTEE` (`app/Contexts/Governance/Domain/Approval/Policies/ApprovalRequirementPolicy.php:15-23`) | **POTENTIALLY MISALIGNED** with D-1/D-1b (no platform-wide default rules). "ICC" is NRNA's body name, so one organisation's practice is coded as everyone's | a platform rule masquerades as organisational law | **architectural** |
| Approval process manager | `GovernanceApprovalProcessManager`: idempotent, coordinates and does not decide; "required approvals list is empty (resolver would populate this in real usage)" | **PARTIALLY ALIGNED** (the pattern matches ADR-T23 "authority decides, manager receives"; its content is missing) | — | technical (incomplete) |
| `AuthorityAssignment` (delegation) | invariants INV-A01 (no self-delegation), INV-A04, INV-A05 (not future-dated); records `delegatedBy: MemberId`, **no decision reference** (`app/Contexts/Governance/Domain/Authority/AuthorityAssignment.php:25-66`) | **PARTIALLY ALIGNED**: a delegation is modelled as a person's act, while the working model treats competence as flowing from rules and organ decisions (D-10, D-12) | delegations cannot be traced to the decision that made them | architectural |
| Governance `Committee` aggregate | member→role map; `removeMember` deletes (`Committee.php:32-59`) | **LEGACY** (already recorded, 2026-11-08 model §6) | no tenure history | architectural (known) |
| RuleSet, Organ, Position, Submission, Sitting, Agenda, Minutes | no classes in `app/` (FACT: grep finds no `RuleSet`, `Organ`, `Position`) | **MISSING** (they exist only in the PROPOSED working model, or not at all) | rule version "as at" cannot be determined; no procedural evidence is recorded | architectural |
| Architecture tests | `tests/Architecture/GovernanceDomainPurityTest.php`, `GovernanceApiPurityTest.php`, `CommitteeDomainPurityTest.php` | **ALIGNED** for what they test (framework purity); **nothing tests that a structural change carries a decision basis** | the main invariant of R-1 has no guard | technical |

## 4. Tactical DDD: what is justified now, and what stays unresolved

**Justified now** (each by an existing ratified or working decision, not by a guessed rule; all [P]):

| Element | Justifying invariant | Source |
|---|---|---|
| `Decision` keeps **decision date** and **effective date** separately; effective ≥ decision except ratification; immutable; forward-only correction by a new decision | D-12, D-6, RA-4, PR-8 | working [D]; forward-only principle |
| `Decision` references **the rule version applied** and the **organ**, with competence judged on the decision date | D-10, D-12, §2.1 [I] "every decision should cite its basis" | working [D] |
| `Decision` carries **procedural evidence as recorded**, a value object that **evaluates nothing**: sitting reference or date, method, the attendance and quorum *as declared in the record*, vote figures if any, minutes reference, who entered it and the mandate relied on | P-2, P-4 (record first), V-18 (minutes), V-59 (who enters), 2026-11-08 model §16 "Recording a decision" | direction [P]; [PK] direct |
| Every structural change in Organisation carries a **Governance decision reference** (and R-1's change-set key) | D-21, D-29, C-1…C-5 | working [D] |
| A **process manager** in Governance that coordinates a matter's handling and receives the organ's outcome; **it never decides** | ADR-T23 / K1 precedent; `GovernanceApprovalProcessManager` | FROZEN precedent; code |
| No platform-wide procedural rule in code; rule content comes only from an organisation's recorded rule version | D-1, D-1b | [D][PO] |

**Unresolved until statutes or standing orders are read** (marked unresolved on purpose; nothing is invented):

| Element | Why unresolved |
|---|---|
| **Sitting as its own aggregate** | justified only if a rule creates a consistency need *across* decisions of one sitting (for example, quorum lost mid-sitting invalidates later items; agenda order binding). Without rules it is evidence inside `Decision` |
| Agenda | whether agenda inclusion is a validity condition (late items, urgency) is UNKNOWN |
| **Quorum evaluation** | P-4: no evaluation before evidence justifies it; until then the record stores the declared quorum |
| Admissibility as a decision vs a finding | depends on whether the rules give it to the organ or an office |
| Methods: written circulation, electronic | UNKNOWN whether permitted, and with what thresholds |
| Submission aggregate | depends on HD-P2 (scenario report) and on the submission rules |
| Publication as a condition of effect | UNKNOWN |
| Events beyond `GovernanceDecisionRecorded` (exists) | follow from the elements above |

## 5. Reconsideration vs legitimacy challenge

| | A. Reconsideration of the merits | B. Legitimacy challenge |
|---|---|---|
| **Claim** | "decide again; there is new information" | "this was not a valid decision: wrong organ, or procedure breached" |
| **Who may hear it** | normally the deciding organ or a superior organ, **per the rules** (UNKNOWN) | **cannot reasonably be the organ whose act is impugned** (INTERPRETATION, H: nobody judges their own case); per the rules (UNKNOWN) |
| **Outcome** | a **new decision** referring to the earlier one (D-10 kinds; forward-only) | a **ruling** on validity, then consequences applied forward (D-28 analogue: a later successful challenge does not erase acts done meanwhile automatically) |
| **Architecture today** | supported in principle by D-10 (correction, refusal as decisions) | **no home.** The Push B loop is FROZEN for election outcomes; the June forum design says "AppealAvailable: false" |

**Statute-independent observations (INTERPRETATION, M):**
- The body that hears a legitimacy challenge, wherever an organisation has one (a disputes body, a disciplinary commission, a convention), is **itself an organ with review competence under the rules**. Its ruling is therefore **a Governance decision about another decision**. This places legitimacy review **inside Governance** without touching the frozen Contestation/Adjudication loop. Contestation/Adjudication would only come into play if the Principal chose a platform-level adjudication service, and that needs an ARB act.
- A precedent already exists in ODA-01: "PublicDigit records the ruling as evidence and does not decide [P]" (`docs/publicdigit/architecture/reviews/2026-11-03-organisation-domain-architecture-baseline-oda-01.md:313`).

**Product capability under each supported possibility:**

| Possibility | Capability needed |
|---|---|
| (i) record only | record an external or internal ruling as evidence linked to the impugned decision, plus the competent organ's follow-up decision. Smallest; consistent with P-2/P-4 and the ODA-01 precedent |
| (ii) review organ inside Governance | (i) plus a matter type "challenge" before a review organ: submission, admissibility, ruling. Reuses the Submission and Decision concepts |
| (iii) platform adjudication | an extension of the FROZEN Contestation/Adjudication scope via ADR/BDR re-issue. Not supported by evidence today |

## 6. Strategic conclusions

1. **Does the context map represent the complete Governance process?** No. The map covers the *consequences* of decisions (R-1, R-2, R-6, R-11). It does not cover how a valid decision comes about (submission, proceedings, record) or how it is challenged. Neither is a new context: both are **Governance's internal capability**.
2. **Confirmed by authoritative evidence:** only what ratified and frozen records establish: Governance owns competence, organs, authority and rules; Membership facts; the Election loop. **No boundary is confirmed by an organisation statute, because none is on file.**
3. **Still hypotheses:** Organisation ⇄ Governance details ([PK] + working [D]); Deliberation as a context; Submission ownership (recommended Governance); where legitimacy review lives.
4. **Deliberation:** a distinct *responsibility* (scenario report), but **a separate bounded context is not justified yet**. Procedural validity starts at submission, so nothing found in this round needs Deliberation to be a context.
5. **Does Governance need a formal proceedings capability?** **Yes, as recorded evidence (H). As rule evaluation, UNKNOWN.** V-18 shows decisions live in minutes. P-4 says record first.
6. **Essential invariants (statute-independent):**
   - a decision is immutable;
   - competence is judged on the decision date;
   - effective ≥ decision except ratification;
   - every decision cites its rule version and organ;
   - recording ≠ validity;
   - recommendations are never decisions;
   - structural change only on a decision basis;
   - no platform-wide default rules;
   - correction is forward-only.
7. **Before tactical design can be frozen:**
   - (a) at least one statute and standing orders, for the four validity layers;
   - (b) Gate F and the organ/unit classification (below);
   - (c) HD-P2 (Submission owner);
   - (d) the legitimacy-review home.
8. **Architectural (not merely technical) gaps:** the inverted date invariant; structure changes without a decision basis; hard-coded platform rules; delegation without a decision basis; the missing RuleSet ("as at" rules). Technical only: the empty approval list; missing guard tests.

**Gate F / organisational baseline (substantive consequence):** procedural validity is a property of *an organ's* act. Which records are organs and which are units is still unsettled: DR-07b / O-16 is open; PO-DR07-01 "existing records are not classified"; the definition of Organisation, ODA-M-2, is open. These are exactly what the organisational baseline (ODA-01, Gate F open) must settle. Until it is accepted, the set of bodies to which proceedings apply is unstable. **Statute-independent invariants (point 6) can be relied on now. Proceedings design cannot be frozen before both Gate F and the statutes.**

## 7. Human decisions (genuine only)

| # | Question | Why investigation cannot settle it | Alternatives and consequences | Recommendation | When |
|---|---|---|---|---|---|
| **HG-1** | **Which organisation's rules are the reference, and will the Principal obtain them?** (NRNA constitution and standing orders; a political party's statute; both) | only the Principal can obtain the documents and decide whose rules shape the product. No source is on file | (a) NRNA first: the request is already drafted (roadmap App. A), and NRNA is the first named user; (b) a party first: closer to "political party digitalisation"; (c) both: evidence of variation sooner, slower | **(a), then one party** (the roadmap plan wants three organisations before patterns, C-8). **Add to the request:** standing orders / meeting rules, motion procedure, minutes rules, disputes or complaints procedure | **now: this unblocks everything** |
| **HG-2** | **Product stance on validity:** record only (PublicDigit records rule versions and procedural evidence; organs judge validity) vs evaluate (PublicDigit checks quorum and majority and may refuse) | a product-responsibility choice with legal weight; the rules cannot settle what the platform should *claim* | record-only: low liability, matches P-2/P-4/D-1a, less automation. Evaluate: more value, a risk of the platform appearing to decide legitimacy | **record-only now; evaluation per rule type later, only where an organisation adopts the interpretation (P-3)**. P-4 already points here; a formal confirmation is optional | can stay open; P-4 suffices for discovery |
| **HG-3** | **Where does legitimacy review live?** (§5 i/ii/iii) | an architectural choice between options all compatible with evidence; (iii) needs an ARB act | see §5 | **(i) now; (ii) once a statute shows a review organ; not (iii)** without an explicit ARB act | later, after HG-1 |
| **HG-4** | **Triage of the code findings** (date rule, decision-less structure changes, hard-coded `ApprovalRequirementPolicy`, delegation without basis) as backlog items | PO prioritisation | create items now (no implementation) / defer | create backlog items; implement only after tactical design | when convenient |

**Not asked (already determined):** Governance owns official decisions and competence (act §11); Organisation applies structure (working D-3/D-21); Membership is unchanged (ratified); election Contestation/Adjudication is not extended (FROZEN); recommendations are not decisions (follows from the ownership of decisions).

## 8. The question for the Principal
The reviewer asked whether the constitution, statutes and standing orders of the target organisation are available. **They are not in any of your projects (§0).** If you hold them elsewhere (PDF, scan, link), adding them to the repository under the roadmap's evidence register (Phase 2, §7 of the plan) is the single step that would most change the next investigation.

## Traceability
- **Records:** scenario report 2026-11-09; discovery record 2026-11-08 §1, §2.1–§2.2, §2.3 (V-17…V-19), §2.4 (V-12, V-13), §6 (V-55, V-59); 2026-11-08 model §16; ratification act `:18` (N-5); context-map.md §4; strategic-design-status §9a, `:186`; roadmap plan §5, Appendix A–B; ODA-01 `:313`; `ADR_COMMITTEE_CREATION_DUAL_PATH.md`.
- **Code:** `GovernanceDecision.php`; `GovernanceApprovalProcessManager.php`; `ApprovalRequirementPolicy.php`; `AuthorityAssignment.php`; Governance `Committee.php`; `GovernanceSetupController.php`; `CommitteeManagementController.php`; `OrganisationPolicy.php:46-72`; `TransactionalActivateCommitteeStructure.php`; `tests/Architecture/Governance*`.
- **External document:** `public/manifesto.pdf` pages 1–3.
