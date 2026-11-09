# Business demand discovery: the Principal interview record (Phase 4 input)

| | |
|---|---|
| **Status** | **INTERVIEW ROUND 1 CLOSED (Q1–Q10). A working record.** Answers are the Principal's [PK] or direction. Interpretations are PROPOSED. Nothing here is ratified, and nothing changes a canonical record. |
| **Commission** | Principal, 2026-11-09: "Mobile Business Demand Discovery: Phase 4 Strategic DDD Input" (MD-2, option (a) first). |
| **Frame** | NRNA is the reference organisation (PD-1). Answers are specific to NRNA and never become platform defaults (D-1b). Mobile is an access channel (MD-1, recommended). This record feeds the Phase 4 inventory and stakeholder evidence. It is not a mobile model. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` |
| **Snapshot** | branch `election-flow-updated`, HEAD `5a26453d5` |
| **Starts from** | `2026-11-09-mobile-capability-mapping-readiness.md` (B-1) |

**Labels:** FACT · [PK] · **[PD]** Principal direction (a scope statement by the PO, not yet a ratified decision) · PRODUCT INTENT · ASSUMPTION · UNKNOWN · PROPOSED.

## Interview log

### Q1: the PBDIGIT-311 observation
- **Asked:** were you running a real NRNA election, testing as PO, or something else, when you used `…/voters/manage` on a phone?
- **Answer (2026-11-09):** *"Administration part should be only desktop application. Mobile application should only be for Voters."*
- **Classification:** **[PD]**. This is a scope direction, not a description of the 2026-10-26 event. The capacity in which the PO acted on that day stays UNKNOWN. It no longer matters, because the direction supersedes it.
- **Recorded as DD-1** (below).

### Q2: what "mobile application" means
- **Asked:** browser pages, an installable app, or both over time?
- **Answer (2026-11-09):** *"Mobile applciation is only for members and voters. Members can take part in member activities, voting process. The Current Voting pages are mobile friendly."*
- **Classification:**
  - [PD]: the mobile audience is **members and voters**. This revises DD-1.
  - [PK]: the current voting pages are mobile-friendly. That is a layout statement. The network and IP problem (PBDIGIT-315) is separate and stays with voting security.
- **Not answered: browser vs installable.** On reflection, that is the channel-strategy decision D-1, which this commission defers. **Withdrawn as a question.** The analyst should not have asked it here.
- **Recorded as DD-1 (revised) and DD-2.**

### Q3: which member activities on a phone
- **Interim statement (2026-11-09), before Q3 was answered:** *"But administrative part which only the organsation's governence or administration involves should be only the desktop application."*
- **Classification:** [PD]. This sharpens DD-1: the boundary is drawn by **who performs the task** (the organisation's governance or administration), not by the page. Recorded as **DD-4**.
- **Answer (2026-11-09):** *"all the activities you mention above."* All eight: apply for membership; see membership status or card; pay membership fees; update personal details; read notices; submit a proposal or opinion; take part in discussions; see election information.
- **Classification:** [PD] scope. Necessity (essential / useful / convenient) not yet stated. Recorded as **DD-5**.

### Q4: self-service changes that affect belonging
- **Answer (2026-11-09):** *"all activities as a member should have mobile application. Changing person address does not mean that it has membership to different address. I know currently we dont have this capability. But Theoritically, if a person changes his permanent resident address, then it should be verified by governence body before it is listed in the new place."*
- **Classification:**
  - [PD]: **every** member activity is in mobile scope, not only the eight listed. This broadens DD-5.
  - [PK]: **a personal address is not unit belonging.** Changing an address does not move a member. Recorded as **DD-6**.
  - [PK], theoretical: a change of **permanent residence** may lead to listing in a new place, but only **after verification by a governance body**. Recorded as **DD-7**.
  - The Principal states that the capability does not exist today.

### Q5: necessity of phone access for members
- **Answer (2026-11-09):** *"it is important."*
- **Classification:** [PK]. Recorded as **DD-8**. Not stated whether "important" means essential (members would otherwise be excluded) or useful. Not re-asked; left for the member survey (MD-2 (b)) to settle.

### Q6: are all voters members?
- **Answer (2026-11-09):** *"Publicdigit has two capabilities: a) election only mode. and membership mode. in election only mode, the voters are imported directly. in membership mode, voters must be member and governence body of the organisation decides who is elegible for voting."*
- **Classification:** [PK], and **FACT** for the existence of the two modes:
  - `app/Domain/Election/Enum/VoterSourceStrategy.php:18-28`: `election_only` (voters enrolled directly, "no membership filtering") vs `full_membership` ("requires member status, paid/exempt fees, active membership type"), chosen by `organisations.uses_full_membership`. The mode is fixed per election by its snapshot (`:31-33`).
  - Recorded as **DD-9** and **DD-10**.

### Q7: what the governance body decides about eligibility
- **Answer (2026-11-09):** *"c"*: **both**. The governance body sets the eligibility rule, and then confirms the resulting voter list.
- **Classification:** [PK]. Recorded as **DD-11**.

### Q8: member sees eligibility and can object
- **Answer (2026-11-09):** *"yes. We seperate the administrative part and consumer part!"*
- **Classification:** [PD]: (a) **both**, see and object on the phone. Recorded as **DD-12**. The second sentence states the general principle behind DD-1 and DD-4. Recorded as **DD-13**.

### Q9: candidacy filing on the phone
- **Answer (2026-11-09):** *"yes, nomination is a member task"*.
- **Classification:** [PD]. Recorded as **DD-14**.

### Q10: anything missing on the consumer side
- **Asked:** withdrawing a nomination or editing a candidate profile; a confirmation after voting; nominating or supporting someone else; renewing membership.
- **Answer (2026-11-09):** *"all member activities should be both mobile and desktop application"*, followed by *"or only mobile application."*
- **Classification:** [PD]. Two points:
  - Every consumer task must be available **on the phone**. Desktop availability is allowed but optional ("both … or only mobile"). Recorded as **DD-15**.
  - Read as: all member activities, including the four examples, are in scope. "Complete" was not said; the register below is closed on that reading, and the survey (MD-2 (b)) can add tasks.

## Directions and findings

| # | Statement | Class | Open meaning (to clarify) |
|---|---|---|---|
| **DD-1** (revised by Q2) | Administration is desktop-only. Mobile serves **members and voters**. | [PD] | (ii) "Desktop-only administration": not optimised for phones, or actively blocked? Later, and minor. (i) browser vs installable: **deferred to D-1**. Constraint kept for D-1/D-3: store-app voting is not approved. |
| **DD-2** | Members take part in "member activities" and in the voting process on a phone. | [PD] | **What the member activities are: UNKNOWN (Q3).** Whether every voter is a member in NRNA: UNKNOWN. |
| **DD-4** (sharpens DD-1) | Tasks that **only the organisation's governance or administration** performs are desktop-only. | [PD] | PROPOSED reading: the rule attaches to the **task**, not the person. An officer acting as a member or voter uses member and voter tasks on the phone; the same officer acting for an organ uses the desktop. Edge case, open: an organ member voting inside a governance decision (governance act, desktop) vs a member voting in an election (voter task, phone). |
| **DD-5** (broadened by Q4) | **All** member activities are in mobile scope (the Q3 list is a minimum). | [PD] | Necessity: DD-8. |
| **DD-8** | Phone access is **important** for members. | [PK] | Essential vs useful: not distinguished. Validation: MD-2 (b) survey. |
| **DD-9** | Two voter sources: **election-only mode** (voters imported directly, no membership) and **membership mode** (voters must be members). | [PK] + FACT (`VoterSourceStrategy.php:18-28`) | Mobile consequence (PROPOSED): in election-only mode the voter is an actor **without** member tasks, so for them the phone is for voting only; in membership mode the same person has both member and voter tasks. |
| **DD-10** | In membership mode, **the organisation's governance body decides who is eligible to vote.** | [PK] | **Tension with the code (FACT):** eligibility is computed automatically from membership status (`app/Contexts/Membership/Domain/Voting/VotingEligibilityPolicy.php:47-71`: active → eligible; suspended, terminated or no membership → not), and the criteria (paid/exempt fees, active type) are fixed in the platform (`VoterSourceStrategy.php:27`). A second eligibility policy exists in Elections (N-1). Hard-coded criteria risk D-1b. **Q7** separates deciding the rule from deciding individual voters. Eligibility deciding is a governance task, so desktop (DD-4). |
| **DD-11** (refines DD-10) | In membership mode the governance body (1) **sets the eligibility rule** and (2) **confirms the resulting voter list**. | [PK] | **Gaps against the code (FACT):** (1) the rule is fixed in the platform, not set per organisation by a decision (DD-10). (2) A per-voter approval exists only in legacy form, as a person-level "approved by" (`app/Http/Controllers/VoterlistController.php:40-54`; registrations with status `approved`, `VoterDashboardController.php:28-29`). It is not established that this records a governance body's decision. PROPOSED: both acts are governance decisions with a decision reference (record-first), done on the desktop (DD-4). The confirmed list then feeds the election's immutable snapshot (`VoterSourceStrategy.php:31-33`). Which body, and with which quorum: Phase 3 (D-10, HG-1). |
| **DD-12** | A member sees on the phone whether they are on the confirmed voter list, and can **object** from the phone if wrongly left off. | [PD] | Deciding the objection is a governance or election-administration task, so desktop (DD-13). **Owner of voter-list objections: UNKNOWN.** Contestation exists (`app/Contexts/Contestation`) and is frozen to elections; no record found that its frozen scope covers voter-list objections. Referred to the owners of Contestation/Election; not decided here. Objection deadlines relative to the list confirmation: organisation rules (D-1b). |
| **DD-13** (corrected by DD-15) · **ACCEPTED by the Principal 2026-11-09 (working decision)** | **Principle: PublicDigit separates an administrative part (organisation governance and administration: desktop only) from a consumer part (applicants, members, voters, candidates: phone required, desktop optional).** | [PD] | PROPOSED reading, consistent with MD-1: the split is between **channels and audiences**, not between business capabilities. Each capability keeps one owner and one authoritative operation; a consumer act (apply, request, object) and its administrative counterpart (approve, verify, decide) are two operations of the **same** capability. Not a ratified decision; candidate for the Phase 4 baseline. |
| **DD-14** | Submitting a candidacy (nomination) is a **member task**, done on the phone. Checking and accepting it is election administration, on the desktop (DD-13). | [PD] | **FACT:** a member-facing flow exists: `app/Http/Controllers/CandidacyApplicationController.php` (`applyForm` `:105`, `store` `:165`, which writes a `pending` application with Eloquent inside the controller at `:221-230`, no use case). A domain rule for the nomination window exists (`app/Domain/Election/Policies/NominationWindowPolicy.php`). So, as for public membership apply, the authoritative operation must be extracted before another channel exposes it (D-7). In election-only mode, whether non-member voters can be nominated: UNKNOWN. |
| **DD-15** · **ACCEPTED by the Principal 2026-11-09 (working decision)** | Every member activity **must** be available on the phone; on the desktop it **may** also be offered. Administration stays desktop-only. | [PD] | Consequence (PROPOSED): the phone is the **primary** consumer channel. Where a task is on both, both call the same operation (D-7). Which tasks are phone-only: not stated per task. |
| **DD-6** | A personal address is contact data, not unit belonging. Changing it does not move the member. | [PK] | Consistent with the Session B working model: Organisation owns unit belonging with its own **basis** and **period** (D-3, D-22; `2026-11-08-strategic-context-map-and-governance-tactical-model.md:17`). Address belongs with Membership/Identity data. |
| **DD-7** | A change of permanent residence can lead to listing in a new place only after a **governance body verifies** it. The capability does not exist today. | [PK], theoretical | Which body (leaving unit, receiving unit, central) and under which rule: **organisation rules decide** (D-1b), so **referred to Phase 3** (D-10 organs, HG-1 statutes). PROPOSED channel split: the member requests on the phone (member task); verification is a governance task on the desktop (DD-4). The verification decision becomes the **basis** of the new belonging; the old belonging ends forward-only. Effect on voting rights in a running election: UNKNOWN (Election owns the voter list snapshot). |
| **DD-3** | The current voting pages are mobile-friendly. | [PK] | Not independently tested on devices (mobile report `00`, limitations). |

## Member tasks in mobile scope (DD-5): ownership as far as records allow

Ownership is cited from existing records only. **C-numbers are the PROPOSED draft inventory, not an accepted baseline.**

| Task | Owner (status) | Authoritative operation today | Authority / open point |
|---|---|---|---|
| Apply for membership | Membership (RATIFIED) | partly: public apply writes with Eloquent in the controller (mobile `00` §7) | the decision to approve is an administration task, so desktop (DD-4) |
| See status or card | Membership (RATIFIED) | read | none for reading; a "card" is not modelled (UNKNOWN) |
| Pay membership fees | Finance (FeePaid direction **open**, Gate F report §6.3) | UNKNOWN | who confirms payment, and whether that is an administration task |
| Update personal details | Membership / Identity (account model PROPOSED) | UNKNOWN | does **not** change belonging (DD-6) |
| Request a transfer after a change of permanent residence | Organisation, unit belonging (working, D-3) | **none** (DD-7) | verified by a governance body (Phase 3) |
| Read notices | Communication (no design; C17 draft) | none | who may publish is administration (desktop) |
| Submit a proposal or opinion | proposal scenario (HD-P, PROPOSED); Openions | controller; security finding S-7 (`OpenionController.php:94`, `v-html`) | how a proposal enters governance is a governance question (procedural-validity report) |
| Take part in discussions | no design (C18 draft) | Openions only | moderation is administration (desktop) |
| See election information | Election and Results (RATIFIED / FROZEN boundaries) | reads | publishing results is administration (desktop) |

**PROPOSED pattern:** every member task has a desktop counterpart done by administration or governance (approve, confirm, publish, moderate, decide). DD-4 splits each flow between the two channels; it does not split the business capability.

## PROPOSED consequences of DD-1 (for the Principal, not decided)
- **Fits:** the earlier sibling-project direction "Angular Mobile — Member participation & voting" (`public-digit/CLAUDE.md:566`), which also excluded administrators.
- *(Superseded in part by Q2: the audience now includes members, so the "voting is the only function" tension below holds only if member activities turn out to be minimal.)*
- **Removes:** officer, committee and governance mobile demand (B-3 no longer blocks mobile). Ownership questions stay with Phase 3 for their own sake.
- **Tension:** if "mobile application" means an installable app, its **only** function would be voting. That makes voting the first and only pilot. Mobile report `00` §11 advised against that while PBDIGIT-284 and G-1 (anonymity gaps) are open, and store-app voting is not approved. The browser reading has no such tension: voters already vote through the phone browser (PBDIGIT-315).
- **Gap:** the business case also promises "Mobile-friendly design for users without desktop access" (`EXECUTIVE_SUMMARY.md:32`). A member who is mobile-only could not apply for membership if member tasks are excluded.

## Proposed business-demand register (Q1–Q10)

**Status: PROPOSED.** Evidence is the Principal's [PK]/[PD] plus code FACTs where cited. Not validated with members (MD-2 (b)). Reference organisation: NRNA. "Capability (draft)" cites the PROPOSED C1–C18 inventory as an association only.

| # | Actor | Task | Outcome | Channel | Necessity | Capability (draft) · owner (status) | Authority / counterpart (desktop) | Evidence | Open |
|---|---|---|---|---|---|---|---|---|---|
| R-1 | applicant | apply for membership | becomes a member | phone (desktop optional) | important | C3 · Membership (RATIFIED) | approval by the organisation | [PD] DD-5; FACT controller writes directly | extract the operation (D-7) |
| R-2 | member | see status or card | knows their standing | phone (desktop optional) | important | C3 · Membership (RATIFIED) | none | [PD] | "card" not modelled |
| R-3 | member | renew membership | stays a member | phone (desktop optional) | important | C3/C4 · Membership; Finance | approval or confirmation | [PD] Q10 | renewal not modelled |
| R-4 | member | pay fees | dues settled | phone (desktop optional) | important | C4 · Finance (FeePaid **open**) | payment confirmation | [PD] | who confirms: open |
| R-5 | member | update personal details | data correct | phone (desktop optional) | important | C3/C14 · Membership/Identity (account model PROPOSED) | none; does not change belonging | [PK] DD-6 | |
| R-6 | member | request listing in a new place after a change of permanent residence | belongs to the right unit | phone (desktop optional) | important | C5 · Organisation (working D-3, D-22) | verification by a governance body | [PK] DD-7; capability absent | which body, which rule: **Phase 3** |
| R-7 | member | read notices | informed | phone (desktop optional) | important | C17 · no design | publishing | [PD] | no design |
| R-8 | member | submit a proposal or opinion | heard by the organisation | phone (desktop optional) | important | C18 / proposal scenario (PROPOSED) | the competent body decides | [PD]; FACT S-7 unescaped output | S-7 first; entry into governance: procedural-validity work |
| R-9 | member | take part in discussions | participation | phone (desktop optional) | important | C18 · no design | moderation | [PD] | no design |
| R-10 | member | see election information (candidates, dates, results) | informed | phone (desktop optional) | important | C6/C10 · Election, Results (RATIFIED/FROZEN) | publication of results | [PD] | |
| R-11 | member (voter) | see whether they are on the confirmed voter list | knows they can vote | phone (desktop optional) | important | C8 · Election (RATIFIED) | the governance body sets the rule and confirms the list | [PK] DD-11, DD-12 | rule is hard-coded today (DD-10) |
| R-12 | member (voter) | object to being left off the list | wrong exclusion corrected | phone (desktop optional) | important | C8/C11 · **owner UNKNOWN** | decision on the objection | [PD] DD-12 | Contestation's frozen scope vs voter-list objections |
| R-13 | member (candidate) | submit, withdraw, edit a nomination | stands for a post | phone (desktop optional) | important | C6 · Election (RATIFIED) | acceptance by the election committee | [PD] DD-14; FACT flow exists, logic in controller | non-members in election-only mode |
| R-14 | member | nominate or support someone else | candidate proposed | phone (desktop optional) | important | C6 · Election | acceptance | [PD] Q10 | not modelled; organisation rules (D-1b) |
| R-15 | voter (both modes) | cast a vote | vote counted anonymously | phone browser (desktop optional) | important | C9 · Voting (FROZEN) | eligibility from the confirmed list | [PK] DD-3, DD-9; FACT PBDIGIT-315 | store-app voting not approved; IP issue with voting security |
| R-16 | voter | get a confirmation after voting | assurance | phone (desktop optional) | important | C9/C10 · Voting (FROZEN) | none | [PD] Q10 | **must not link voter to vote** (VO-1); G-1 receipt hash open |

**Desktop only (DD-1, DD-4):** approving applications; verifying transfers; setting eligibility rules and confirming voter lists; deciding objections; accepting nominations; publishing notices and results; moderating; all governance acts; platform administration.

## Answers for the Principal

1. **Business needs established (as [PK]/[PD], not yet user-validated):**
   - the consumer/administration split, with the phone required for every consumer task and the desktop optional (DD-13, DD-15);
   - the 16 consumer tasks above;
   - an address is not belonging (DD-6);
   - moving to a new place needs governance verification (DD-7);
   - in membership mode, eligibility is a governance decision on both rule and list (DD-11).
2. **Capabilities with credible demand on the phone:** every consumer-facing capability (Membership, Election information and nomination, voter list, Voting through the browser, Finance payments, Communication, proposals and discussion). Demand is stated by the PO; necessity is "important", essential vs useful not separated.
3. **Unresolved actor and authority questions:**
   - which body verifies transfers and confirms voter lists (Phase 3);
   - who owns voter-list objections;
   - who confirms fee payments (FeePaid);
   - whether non-member voters can be nominated in election-only mode;
   - how a member proposal enters governance.
4. **Can enter the strategic baseline now (as PROPOSED Phase 4 input):**
   - DD-13/DD-15 as a channel principle beside MD-1;
   - DD-6 (address ≠ belonging), consistent with D-3/D-22;
   - the register as stakeholder evidence.
5. **Must wait:**
   - anything about organs and authority (Phase 3, Gate F);
   - the capability rows themselves (C1–C18 acceptance);
   - communication and discussion (no design);
   - all technology decisions (D-1, D-2, D-4, D-5, D-6).

**Findings for other tracks (not mobile work):**
- **F-1:** eligibility criteria are fixed in the platform, and a governance body cannot set them (DD-10). This conflicts with D-1b and with DD-11. It belongs to the Membership/Election owners. Membership is RATIFIED and frozen, so it is a question for the Principal, not a change.
- **F-2:** member-facing writes sit in controllers (membership apply, candidacy). Extraction (D-7) is needed before any second channel.
- **F-3:** S-7 (unescaped opinions) matters more once members post from phones; it belongs to the D-0 security triage.

## Principal decisions (2026-11-09)
- **DD-13 and DD-15 accepted** as a working channel principle for the Phase 4 baseline, beside MD-1: *"accept DD-13 and DD-15"*. A working decision like Session B's D-items: not a ratification act; it changes no canonical record and authorises no implementation.
- F-1 (hard-coded eligibility criteria): not yet decided.

## Next step
None automatic.
