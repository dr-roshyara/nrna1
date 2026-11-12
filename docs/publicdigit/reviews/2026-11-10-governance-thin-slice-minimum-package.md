# Governance thin slice for the minimum package (Membership, Newsletter, Forum) — PROPOSED

| | |
|---|---|
| **Status** | **ACCEPTED 2026-11-10 (D-157; act `docs/publicdigit/reviews/2026-11-10-minimum-package-tactical-designs-acceptance-act.md`).** No implementation authorised. Written by Claude, not independently reviewed |
| **Commission** | "Principal Instruction — Governance Thin Slice for PublicDigit Minimum Product", accepted by the Principal 2026-11-10 |
| **Consumes (does not replace)** | Governance rounds 1–6 (model document §20, §33, §34, §40, §41); round 7 (`2026-11-10-governance-tactical-model-round-7-committees.md`); gap analysis G-1 … G-5 |
| **Scope** | D-146 (Membership, Newsletter, Forum); D-147 (units, placement by act, top-unit default; **no area rules, no Reference Geography**) |

**Labels:** [AD] decided · [BF] fact · [AC] conclusion · [TD] tactical design · [WD] working decision · [P] proposal · [H] hypothesis · [OQ] Principal question · [TU] technical uncertainty.

## 0. Disagreements and additions (stated first)

1. **Agreed:** a rule citation must not bypass competence checking. **But the existing model already treats it so** (§20.1 "asserted" competence, marked not machine-checked). §3 below defines what an asserted competence must carry so that it is **evidence, visible and challengeable, never a bypass**.
2. **Addition, a gap the commission does not name: how authority starts.**
   - Who may enter an organ's decisions is "per the organisation's rules" (V-59).
   - Platform configuration creates no authority (D-1a, PO-ORG-MEM-01 A).
   - **So who records a newly joining organisation's first committee, its office holders and its rules?**
   - Without an answer, **no admission, no editor and no moderator can ever exist**. This was OQ-1, now decided by D-148.
3. **Editor and moderator need no new concept.** Both are **functional offices** (Positions held for a term) attached to the designating committee. They reuse Position, tenure and mandates. **They are not delegations:** a delegation passes on the delegator's own powers, whereas an editor holds a function the rules assign.
4. **Geography:** release 1 has **no** Governance–Geography or Organisation–Geography reads. Competence is scoped **by units**; D-136's principle (never infer authority from names, levels or the map) **still binds** through G-I9 below.

## 1. Business summary

**For the three products to be trustworthy, PublicDigit must be able to show who was entitled to:**
- **admit** a member;
- **send** a newsletter in the organisation's name;
- **moderate** a forum.

**That entitlement comes from the organisation's written rules and its committees' decisions, never from who has an admin login.** Governance therefore needs, in release 1:
- the organisation's **rules on file** (as documents, D-97);
- its **committees**, attached to units;
- the **offices** in them and **who holds them when**;
- the **decisions** that create offices and appoint holders, with their evidence;
- **one question others can ask:** "*was this person authorised to do this, for this unit, on this date?*"

## 2. What the existing model already answers

| Need | Answer | Source |
|---|---|---|
| Committees, offices, terms | Organ, Position (tenures inside) | [WD] §33.1, §41.2 |
| Starting and ending a term | Basis envelope: Part, RecordedFact, Expiry, … | [WD] §41.4 |
| Vacancy only by decision | D-24 | [AD] |
| Committee ends with its unit when nothing is said | D-145 | [AD] |
| Decisions immutable, competence on the decision date | Resolution; D-12 | [WD], [AD] |
| Powers live in the rules | D-38 | [AD] |
| Admission authority comes from a competent organ or office, not admin rights | PO-ORG-MEM-01 A | [AD] |
| Membership asks Governance, stores evidence only | **AuthorityCheck port** | **[RAT]** Membership contract outline |
| Editor designated by the committee at a meeting; none until then | D-58, D-60 | [AD] |
| Newsletter official purposes only | D-59 | [AD] |
| Moderators come from positions or rules, never from points | Deliberation DL rules (§23) | [WD] |
| Moderation review: per rules; default the tribunal | D-53 | [AD] |
| Forum participation derived from belonging and tenures | §23, §35 | [WD] |
| Workspace access is Organisation's, enforced by Authorization | D-104 | [AD] |

## 3. Competence evidence: what makes a decision authoritative [TD]

**Every authority answer carries a competence evaluation of one kind** (§20.1):

| Kind | Release 1 | What it must carry | Consumers see |
|---|---|---|---|
| **Worked out** | Possible where a structured grant is on file | The grant (provision, version) and its evaluated conditions | "checked" |
| **Asserted** | **The normal case in release 1** (statutes are not yet encoded) | (1) **the provision cited** (article) in a **rules document on file** (fingerprinted, D-97); (2) **the sitting** at which it was decided (date, minutes or extract as evidence, D-60); (3) **the recorder and their recording authority** (V-59, D-148); (4) the organ's attachment to a unit covering the scope | "**asserted from the rules, not machine-checked**", with the citation |
| Gap declared / contradiction | As defined (D-65, D-70) | Diagnostic | Not authorised |
| **Not established** | Nothing recorded | — | **Not authorised** |

**Why this is not a bypass:**
- an asserted answer **cannot exist without the four elements**;
- each element is evidence a member can see and **challenge** (D-53, D-67);
- **an assertion never manufactures a committee, an office or a term**: those must exist as records.

**Release 1 policy [P]:** Membership, Communication and Deliberation **accept "asserted"** and record its kind. **They refuse "not established"**, and the refusal is reported.

## 4. Minimum capability: required, kept, deferred

| Element (rounds 6–7) | Classification |
|---|---|
| Organ with unit attachments; lifecycle incl. **D-145** | **Required** (units exist in release 1) |
| Position with tenures; one holder per exclusive office (G-I1); D-24; resignation, expiry, death (D-37), abolition (D-66) | **Required** |
| **Functional offices**: newsletter editor (per series and scope), forum moderator (per forum scope) | **Required** (new kinds of Position; no new aggregate) |
| RuleProvision: **rules documents on file** with provisions citable; structured grants optional | **Required** (document level); structured grants **optional** |
| Resolution with parts and competence evaluation; Sitting as evidence | **Required** (minimal Sitting: date, organ, minutes evidence) |
| DecisionLog (knowledge positions) | **Required** (history "as known at τ") |
| **AuthorityCheck query** (§6) | **Required** |
| Mandate events to Authorization | **Required** (UI gating), never the truth (§41.6) |
| Incompatibility guard | **Kept, inactive**: no incompatibility rules recorded in release 1; the hook stays |
| AuthorityAssignment (delegation), D-76, D-78 | **Deferred** (no product need); invariant kept: no delegation without the rules allowing it |
| RemedyApplication (Adjudication remedies) | **Deferred**; challenges are recorded manually until Adjudication is in scope |
| Precedence grants and evaluation; `BelongingAllocationsDecided` precedence | **Deferred with area rules** (D-147). **The principle stays:** G-I9 |
| Reference Geography reads | **None** (G-I10) |
| Submission (motions from Deliberation, D-51) | **Deferred** unless the forum release includes formal motions |

## 5. Context relationships (release 1)

| Fact | Owner | Consumer | What the consumer decides | Never crosses | If unknown, unavailable or ended |
|---|---|---|---|---|---|
| Committees, offices, terms, decisions, competence | **Governance** | Membership, Communication, Deliberation (via AuthorityCheck) | Whether to accept the act | Membership facts written by Governance; placement decided by Governance in release 1 | **Not authorised**; act refused and reported; **never "assume authorised"** |
| Membership truth | **Membership** (RAT) | Governance (CR-01 C3, when rules require membership for an office) | Eligibility for office | Membership decisions taken by Governance | Appointment pending or refused (§41 semantics) |
| Units, placement, belonging | **Organisation** | Governance (attachments, `StructureAsAt`); Deliberation and Communication (audiences, forum participation) | Who is in a forum or audience | Units created by Governance alone (Governance decides; Organisation records, D-29) | As at τ, complete or refused (D-73) |
| Person, account | **Identity** | all, by `PersonRef` | — | Authority, eligibility | No binding → not a holder |
| Newsletter issues, delivery | **Communication** | — | Sends only with a valid editor tenure at send time | Editor designation decided in Communication | Not sent; reported |
| Forum posts, moderation acts | **Deliberation** | — | Moderates only with a valid moderator tenure at the act's time | Moderator appointment decided in Deliberation | Act refused |
| Workspace access | **Organisation** (D-104) | Authorization | — | **Admin access ≠ authority** | — |
| Mandates | Governance (derived) | **Authorization** (gates screens) | Technical allow / deny | Mandates read back as truth | Governance re-checks at the act (G-I4) |

## 6. Tactical design [TD]

### 6.1 Aggregates

| Aggregate | Release-1 responsibility | Invariants |
|---|---|---|
| **Organ** | A committee attached to ≥ 1 active unit; established, attached, detached, ended (incl. D-145 on `UnitEnded`) | G-I7: attachments are active units on every date of its life |
| **Position** (tenures) | Ordinary and **functional offices** (editor: series + unit scope; moderator: forum scope); tenures with Basis | G-I1 one holder per exclusive office; D-24; tenure only with a Basis; **a functional office belongs to an organ and ends with it** (D-145) |
| **RuleProvision** | Rules documents and versions; citable provisions; optional structured grants | Versions never overlap; a citation must resolve to a provision in a document version on file |
| **Resolution** | A committee's decision with parts (create office, appoint, end term, designate editor or moderator) and competence evaluation per part | Immutable (GD-1); competence on the decision date (D-12); **asserted requires all four elements** (§3) |
| **DecisionLog** | Knowledge positions per organisation | GD-11, GD-12 |
| **RecordingAuthority** *(within Position)* | The **registrar**: an office whose holder may enter decisions (V-59) | Recording ≠ deciding (G-6); **bootstrap per D-148** |

### 6.2 AuthorityCheck (query, the one contract)

`AuthorityCheck(organisation, actor PersonRef, act kind, scope unitRef | seriesId | forumId, at d, as known at τ)`

**Answer:**
- `Authorised(tenureRef, competence kind, citation, decisionRef)`;
- `NotAuthorised(reason)`;
- `Unknown(reason)`, which is treated as not authorised.

| Act kind | Consumer | Authorised when |
|---|---|---|
| `admit-member`, `decide-application` | Membership | The actor holds, at d, an office (or acts for an organ) whose competence for admissions in a scope covering the unit is worked out or asserted |
| `send-newsletter(series)` | Communication | The actor holds an editor tenure for that series and scope at the send time |
| `moderate(forum)` | Deliberation | The actor holds a moderator tenure for that forum or a containing forum, where the rules say so |

**The consumer stores the answer as the basis of its act.** A later change never rewrites it; a challenge goes through Adjudication.

### 6.3 Commands and events (subset of §33.4 and round 7)

| Aggregate | Commands | Events |
|---|---|---|
| Organ | `EstablishOrgan`, `AttachUnit`, `DetachUnit`, `EndOrgan` | `OrganEstablished`, `OrganAttachmentChanged`, `OrganEnded` |
| Position | `CreatePosition(kind incl. editor / moderator, scope)`, `AbolishPosition`, `StartTenure`, `EndTenure(reason)`, `RecordResignationSubmitted`, `RecordDeath` | `PositionCreated`, `PositionAbolished`, `TenureStarted`, `TenureEnded`, … |
| RuleProvision | `FileRulesDocument`, `PublishVersion` | `RulesDocumentFiled`, `RuleVersionPublished` |
| Resolution | `RecordResolution` | `ResolutionRecorded`, `PartApplied`, `PartInapplicable` |
| *(derived)* | — | `MandateGranted`, `MandateRevoked` |
| *(consumed)* | — | `UnitEnded` (Organisation) → D-145 |

### 6.4 Temporal and failure behaviour

- **Every answer is "at d, as known at τ".** Release 1 typically uses d = τ = now; history stays reconstructable.
- **The consumer re-checks at the act**: send time, moderation time, admission decision time.
- **An editor whose term ended a minute ago cannot send**, even if a mandate event has not arrived yet (G-I4).
- **Corrections are new records.** A wrongly entered appointment is corrected by a correction Basis, never edited.

## 7. Scenarios

| # | Situation | Outcome |
|---|---|---|
| 1 | Party joins; its statutes are filed; the registrar records the current Central Committee and its holders from the last congress minutes | Records with Basis `RecordedFact` + evidence, by the founding registrar named in the submitted decision (**D-148**) |
| 2 | Central Committee, at a sitting, designates A editor of "Members' Bulletin", national scope | Resolution, asserted competence citing the statutes' article; A's editor tenure starts |
| 3 | A sends an issue | AuthorityCheck → Authorised (asserted) → sent; the basis is stored with the issue |
| 4 | An admin without an editor tenure tries to send | **NotAuthorised**; refused, reported (D-58, PO-ORG-MEM-01) |
| 5 | A's editor term ends; A tries to send | NotAuthorised, even before `MandateRevoked` reaches the UI |
| 6 | District secretary approves an application for a district member, statutes give district committees admission | Authorised (asserted) → Membership records the admission with the evidence |
| 7 | Same, but no admission competence is recorded for district bodies | **NotAuthorised**; the application stays pending until a committee designates the admitting body (**D-149**) |
| 8 | Province committee designates B moderator of the province forum | B moderates the province forum; lower forums only if the rules say so |
| 9 | No moderator designated for a district forum (or a containing one, where the rules allow) | **Read-only** until one is designated (**D-150**) |
| 10 | District D2 dissolved; act silent | D-145: its committee and its editor and moderator offices end; the forum's scope ends with the unit (Deliberation) |
| 11 | Member challenges a moderation act | Recorded; review per D-53 (default the tribunal; manual until Adjudication is in scope) |
| 12 | Ward re-parented on the official map | **No effect** (no Geography in release 1; G-I10) |

## 8. Principal questions (to be asked one at a time)

- ~~OQ-1~~ **Decided: D-148** — a founding registrar named by a decision of the organisation's competent body, submitted with its statutes; entries recorded from evidence, challengeable.
- ~~OQ-2~~ **Decided: D-149** — nobody until a competent committee designates the admitting body or office at a sitting; applications stay pending.
- ~~OQ-3~~ **Decided: D-150** — the forum is read-only until a moderator covering it is designated at a sitting.

## 9. Coherence assessment

- **No duplicate ownership.** Editor and moderator offices are Governance's; sending and moderating are Communication's and Deliberation's.
- **The one new contract is AuthorityCheck**, which **already exists as a ratified contract element** (Membership). Release 1 extends its act kinds to newsletter and forum.
- **Proof obligations [TU]:**
  - re-check at the act;
  - one holder per exclusive office (storage constraint);
  - correctness of the knowledge log.
- **The Governance ⇄ Organisation contract stays provisional** until IR-OTD-04 and acceptance of §49–§50.
- **The design is strategically understood and tactically coherent, OQ-1 … OQ-3 now decided (D-148 … D-150).**
- **Before implementation can be authorised:**
    - acceptance of this slice;
  - the Organisation design §49–§50;
  - designs for Communication and Deliberation.

**Addition (2026-11-10, coherence check K-1, D-154):** AuthorityCheck gains two act kinds, with the same evidence rules as §3:
- **`decide-structural-change(scope)`**: authorised only for a body or office a committee has designated at a sitting, unless the filed rules say otherwise;
- **`place-member(unit)`**: authorised for **the admitting body within the admission act**, and for a designated body for later transfers.

Organisation records structural decisions and placements only with an `Authorised` answer stored as basis (D-29).

**Correction note (2026-11-10, integrated review Addendum A-2; changes no meaning of D-157):** the AuthorityCheck answer uses the **ratified authority-evidence shape**: {actor = `PersonRef`, capacity = office (tenure ref), ruleRef = cited provision, policyVersion = rule-document version, result, checkedAt = τ}. Competence kind (asserted / worked out) and decision reference travel as an additional `basis` reference, never inside `result`. **Exclusivity is a Position attribute** set by the creating decision. Defaults when silent: editor exclusive, moderator non-exclusive (Addendum A-8).

**Correction note (2026-11-11, AuthorityCheck contract review `docs/publicdigit/architecture/reviews/2026-11-11-authoritycheck-organisation-governance-contract-review.md`; changes no meaning of D-157):**
- **The answer = the six ratified fields + a basis:**
  - `outcome` (Authorised · NotAuthorised(reason) · **Undetermined(reason)**);
  - `evaluatedFor` (the business date, D-12);
  - `competenceKind` (workedOut · asserted · contradiction · interimRole);
  - `decisionRef`;
  - `evidence`.
- **Mapping:** `result = outcome is Authorised`; `competenceVerified = competenceKind is workedOut`.
- **Two shapes of the same evidence:**
  - committee decisions are **pushed** with the decided change set (D-29);
  - office holders' acts are **pulled** at the moment of the act.
- **Organisation supplies the unit's ancestor chain** with its request; Governance does not call back.
- **New act kind `record-from-evidence`** for the registrar (D-148, D-159).
- **One authority answer serves an admission and its unit placement** (same act reference).
- **`InterimRoleAuthority` (owner / admin) is never used for a ledger organisation** (PO-ORG-MEM-01 A, D-149, D-160).

**Correction note 2 (2026-11-11, whole-baseline assessment B-2 … B-4; supersedes the `competenceVerified` mapping in correction note 1):**
- **`competenceVerified` = competence evaluated by Governance** (code meaning: "`false` marks a technical application-role check only"), so `competenceVerified = competenceKind ∈ {workedOut, asserted, contradiction}`, and **false only for `interimRole`**. **The difference between worked out and asserted lives only in `competenceKind`.**
- **Registrar recordings have two layers of basis:**
  - (1) the AuthorityCheck answer covers **only the registrar's authority to record** (D-148);
  - (2) each recorded unit or committee carries its **own** basis, **"recorded from evidence of act X"** (minutes, statutes), **not checked by PublicDigit and challengeable** (D-159).
- **The ancestor chain** sent with a request is the structure **as at the act's (d, τ)**, never the current structure.

**Addition (2026-11-11, D-163):** AuthorityCheck act kinds **`renew-membership`, `suspend-membership`, `restore-membership`** (consumer: Membership; ratified six fields + basis). When the rules are silent: nobody until a committee designates (as D-149).

**Correction note 3 (2026-11-11, coherence review §2C; supersedes AuthorityCheck review G-6):**
- **An admission naming a unit is one request with two act kinds, `admit` and `place-member(unit)`, and an outcome for each.** D-154's placement competence is bounded by the admitting body's scope.
- **Ancestors come from Organisation's own Tree** at (d, τ), with the tree version, recorded in the basis as `structureRef`. **Organisation re-asks if the tree version changed.**

**Correction note 4 (2026-11-11, IR-R1-01 M-4):** an AuthorityCheck answer for a subject-specific act (e.g. `issue-claim`, `revoke-claim`) carries **`requestRef`** (the subject and request identifiers) in its basis. **A consumer accepts it only for that request**, so it cannot be replayed.

**Note (2026-11-11, D-167):** "the Highest Committee is competent for the area until a new committee is established" (D-145) no longer applies. **Until a committee is established for the area or a body is designated, acts needing that area's committee return `Undetermined` and wait.** PublicDigit never assigns authority by default.

**Note (2026-11-11, D-171):** "editor exclusive, moderator non-exclusive" is a **platform default** under D-167. It applies only while the rules and the decision creating the office are silent, it is displaced by either, and it is labelled "platform default (D-171)" on the office record.

**Note (2026-11-11, D-172):** an admission that names a unit is placed there **only if `place-member` is Authorised** (from the rules or a designation that includes placement). **D-154(2) no longer gives the admitting body placement competence by default.** Otherwise the member stays in the top unit (D-114, platform default) until a designated body places them.

**Conformance note (2026-11-11, baseline acceptance pass P-1, P-2; implements D-167 and D-168, no new rule):**
- **D-53 (§2 row "Moderation review", scenario 11):** "default the tribunal" no longer applies. **A moderation challenge is recorded and waits until the organisation's rules or a designation name the reviewing body** (D-167).
- **Participation rule (D-168):** Governance exposes **`ParticipationRuleAsAt(organisation, d, τ)`**: the organisation's recorded provision on who may post and receive newsletters, **or "none recorded"** (multi-database design §15).

**Addition (2026-11-11, D-190; ratified by D-193 together with the Platform (tenancy) acceptance):** AuthorityCheck act kinds consumed by **Platform (tenancy)**.
- **Ratified six fields + basis.**
- **`requestRef` per correction note 4:** subject identifier and request identifier.
- **The mapping of `actor` / `capacity` follows the AuthorityCheck contract review §3 and §5:**
  - committee decisions are pushed with their decision;
  - office-holders' acts are pulled at the act.

| Act kind | `actor` | `capacity` | Subject (`requestRef` subject identifier) | Request identifier | `evaluatedFor` | Competent | Rules silent |
|---|---|---|---|---|---|---|---|
| **`designate-platform-contacts`** | the deciding organ that made the Resolution | its sitting | one designation: (Resolution, part, designated `PersonRef`). **The designated person is the subject, never the actor** | the consumer's single-use request id | the Resolution's decision date (D-12) | the body or office the organisation's rules (or a designation) make competent to designate platform contacts | **`Undetermined`** until a competent body designates who decides (D-167, D-181) |
| **`give-case-grant`** (normal route only, D-174) | the act's maker: the giving `PersonRef` (office-holder's act) or the organ (committee decision) | the tenure or the sitting, matching the act | one grant: (organisation, case reference, data area, window, operator, case type) | the consumer's single-use request id | the date of the act giving the grant | the body or office the rules (or a designation) make competent to give case grants | **`Undetermined`**, as above |

- **Emergency grants (D-174, D-176) are not checked with `give-case-grant`.** Their approvers' designations are checked with `designate-platform-contacts` at recovery (D-183, D-188).
- **This addition does not establish that a Resolution part persists its `ruleRef` / `policyVersion` as fixed data.** That remains open in the Governance tactical model (Platform dependency X-3).
- **Platform is a consumer and creates no authority.**

**Addition (2026-11-12, D-196; ratified by D-200 together with the Identity I-TDD-02 acceptance):** AuthorityCheck act kind consumed by **Identity**.
- **Ratified six fields + basis; `requestRef` per correction note 4.**
- **A claim code establishes an identity binding only; it grants no membership and no access.**

| Act kind | `actor` / `capacity` | Subject (`requestRef` subject identifier) | Request identifier | `evaluatedFor` | Competent | Rules silent |
|---|---|---|---|---|---|---|
| **`manage-claims`** (issue, replace and revoke an ordinary claim code) | the officer's `PersonRef` / their tenure (office-holder's act, pulled at the act) | **issue:** (entry, `PersonRef`) + issuance id; **replace / revoke:** the claim id (+ the new issuance id) | the consumer's single-use request id | the date of the act | the office or body the organisation's rules or a designation empower to manage claims | **`Undetermined`** until a competent body designates (D-167, D-181) |

- **The founding registrar's bootstrap claim is not checked with this act kind** (D-148, D-178, D-195).
- **Identity is a consumer:**
  - it checks the answer's correspondence (organisation, actor = the signed-in person's `PersonRef`, act, subject, outcome, date, freshness, single use);
  - **it never evaluates competence and does not persist the answer;**
  - the organisation keeps the full answer as the basis of its act (I-TDD-02 revision 12 §10.1).

**Addition (2026-11-12, D-194, D-202, D-203, D-206, D-207; ratified by D-208 with the Governance completion acceptance):** membership ending, membership-record correction, non-member placement and decision records.
- **Ratified six fields + basis; `requestRef` per correction note 4.**
- **Full text:** Governance completion design rev 5, §2–§3.

| Act kind | Pattern | Authorised when | Rules silent | Record category |
|---|---|---|---|---|
| **`record-resignation`** (resign; withdraw a scheduled resignation, D-204) | pull | **(a)** actor = subject (the member), **`competenceKind = ownAct`**, `ruleRef` = the organisation's provision or "D-194" (**D-203**); **(b)** an empowered recording office records the member's declaration | (a) still Authorised; (b) Undetermined | B: recorded fact |
| **`exclude-member`** | **push** with the Resolution part | **only an organ** the rules name, or one a competent body designates (D-194) | Undetermined: nothing ends | **A: decision** |
| **`record-death`** | pull | an empowered recording office, with evidence | Undetermined | B |
| **`correct-membership-record`**, **`void-membership-record`** | pull | recording authority with evidence; **a Void is reported and challengeable** (D-206) | Undetermined | B |
| **Annul, route 1** (`annul-membership-act`) | push | a new decision of the body competent for the original act | Undetermined | **A: decision** |
| **Annul, route 2** | push | a tribunal remedy applied as **`RemedyApplied`** (no Resolution, no competence evaluation; D-202 item 8) | — | separate applied fact |
| **`place-person(capacity, unit)`** (non-Member capacities) | pull or push | a designated body competent for that capacity's placement (D-154 / D-172 pattern) | Undetermined: no belonging | — |

**Decision records (D-202):**
- **GD-1:** Resolutions, parts and competence evaluations are never changed.
- **GD-11:** gap-free DecisionLog positions per organisation.
- **GD-12:** record time = database `clock_timestamp()`; **refused on regression, never adjusted.**
- **Per-part competence evaluation carries `ruleRef` and `policyVersion`, fixed once recorded** (closes X-3 for D-193 and D-200 at design level).
- **Corrections fix only the record; a pending challenge changes nothing.**
