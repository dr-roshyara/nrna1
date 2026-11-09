# Strategic DDD reconciliation and participant channel mapping

| | |
|---|---|
| **Status** | **PROPOSED.** Analysis and recommendations only. It ratifies nothing, promotes no working decision and changes no canonical record. Not independently reviewed (Claude family). |
| **Commission** | Principal, 2026-11-09: "Strategic DDD Reconciliation and Mobile Channel Architecture". |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `architecture/reviews/` |
| **Snapshot** | branch `election-flow-updated`, HEAD `bad1202d0`. Read-only: no code, configuration or schema changed. |
| **Inputs** | demand record `docs/publicdigit/reviews/2026-11-09-business-demand-discovery-record.md` (DD-1…DD-15, R-1…R-16); readiness report `…/2026-11-09-mobile-capability-mapping-readiness.md`; platform report `./2026-11-09-business-capability-and-strategic-ddd-baseline-discovery-report.md` (C1–C18); Session B map `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md`; `../context-map.md`; ratified Membership act `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md`; ADR-002; the code cited inline |

**Labels:** RATIFIED / FROZEN · **ACCEPTED WD** (working decision accepted by the Principal) · PROPOSED · FACT (file:line) · [PK] · [PD] · ASSUMPTION · UNKNOWN.

## 0. Disagreements (stated first)

1. **PBDIGIT-311 is again described as "an officer using a phone to manage voters".** The ticket says only *"Raised by: the PO, on the mobile view of `…/voters/manage`"* (`docs/publicdigit/backlog/PBDIGIT-311-mobile-menu-unreadable.md:3`). The readiness report was corrected on 2026-11-09 and the correction is committed (`bad1202d0`). The conclusion (no mobile administration requirement) stands; the premise should not be repeated.
2. **"Capacity" should not become a new attribute of the strategic model.** The assessment asks for the person's capacity to be made explicit. Two reasons not to model it as a separate dimension:
   - **Name collision:** Session B already uses *capacity* for unit belonging ("person, unit or wing, **capacity**, basis, period", Session B map `:17`, D-3/D-22). A second meaning would overload a working term.
   - **Not necessary** (model-integrity rule: orthogonal, necessary, sufficient): the channel rule can attach to the **operation**. "Approve an application" is an administration operation; "submit an application" is a participant operation. The capacity is implied by the operation invoked, so it needs no field of its own.
   - **PROPOSED instead:** classify every business operation as **participant** or **administration/governance**. A person reaches participant operations on any channel and administration operations on the desktop. The policy outcome is identical to the assessment's.
3. **Finding 1 is two problems, and one is already tracked.**
   - The inconsistency between the single-voter and bulk eligibility checks is **already** `PBDIGIT-265` (OPEN, investigation). The cross-context table reads are **already** `PBDIGIT-318` V2.
   - Only the **capability gap** (organisations cannot set their own eligibility rules by a recorded decision) is new. It is filed as `PBDIGIT-336`; it cites but does not duplicate the existing items (ES-005.4).
4. **"Offline functionality" is not entirely downstream.** Offline or queued **ballot casting** is already excluded by frozen records (BDR-02, VO-1). Only offline behaviour for non-voting participant tasks remains open.

**Agreed:** no Mobile bounded context; one authoritative operation per business action; Governance owns rules and authority, Election evaluates, Membership and Organisation supply facts; phone-browser voting in scope, store-app voting not approved; technology decisions downstream.

## A. Business interpretation of the accepted policy

| Record | Exact wording | Status |
|---|---|---|
| DD-13 | "PublicDigit separates an administrative part (organisation governance and administration: desktop only) from a consumer part (applicants, members, voters, candidates: phone required, desktop optional)." | **ACCEPTED WD** 2026-11-09 |
| DD-15 | "Every member activity **must** be available on the phone; on the desktop it **may** also be offered. Administration stays desktop-only." | **ACCEPTED WD** 2026-11-09 |
| MD-1 | Mobile is an access channel, not a domain, bounded context or source of authority | accepted with the commission's text (not a ratification) |

**Meaning in business terms:**
- **Participants** (applicants, members, voters, candidates) are served **phone-first**. A participant task that works only on a desktop does not meet the policy.
- **The organisation's officers and bodies** work at a **desktop workbench**. No requirement exists to make their work usable on a phone; it is not forbidden by the record, but nothing is built for it (DD-1 (ii) still open: "not optimised" vs "blocked").
- **The split runs through every capability, not between capabilities.** Nearly every participant task has an administrative counterpart in the same capability: apply/approve, request transfer/verify, object/decide, nominate/accept.

## B. Reconciling the demand evidence with the capability baseline

| Input | Status | Used as |
|---|---|---|
| C1–C18 | PROPOSED draft (Phase 4 C-2), not accepted | association labels only |
| `context-map.md` R-01…R-18 | PROPOSED; lags Session B | not used for ownership |
| Session B map, D-1…D-32 | ACCEPTED WD (2026-11-08) | ownership of Organisation and Governance |
| Membership architecture | **RATIFIED / FROZEN** 2026-10-08 | ownership of Membership; **`Eligibility` is "Election's (not a Membership predicate)"** (act `:110`); INV-11 "no voting entitlement from Membership" (`:125`); Election consumes `IsMember`, `Suspended`, `CurrentType`, tenure (`:154`) |
| ADR-002 Verified ≠ Eligible ≠ Authorized | Accepted 2026-05-30 | eligibility is **process-specific**: "different elections have different requirements" (`docs/adr/ADR-002-verified-eligible-authorized.md:24-26,66-73`) |
| Demand register R-1…R-16 | PROPOSED; [PD]/[PK]; not user-validated | the task set |

**Reconciliation results:**
- **Every register task maps to a capability that already exists in the draft inventory** except two: **voter-list objection** (no owner, §D) and **change of residence → new unit** (C5 belonging exists; the transfer act does not). No new capability is needed for mobile.
- **No capability is participant-only.** All have desktop counterparts. Conversely, **C1 Registry and structure, C2 Governance, C13 Audit, C15 Tenancy** have **no participant task** in the register and remain desktop-only. No phone requirement is invented for them.
- **C11/C12** (Contestation, Evidence): participant-facing only if voter-list objections are placed there (decision PD-A).

## C. Proposed capability-to-channel and capability-to-context mapping

Channel: **P** = phone mandatory, desktop optional (participant). **D** = desktop only. Owner status from the records in §B.

| # | Participant operation (P) | Desktop counterpart (D) | Capability (draft) | Owning context (status) | Authority source | Supported today? (FACT) | Authoritative operation today | Missing |
|---|---|---|---|---|---|---|---|---|
| R-1 | submit membership application | approve or reject | C3 | Membership (RATIFIED) | organisation rules; approval = owner/admin today | yes, two paths | `SubmitMembershipApplication` use case (`MembershipApplicationController.php:116`); **public path writes directly** (`PublicMembershipApplicationController.php:66`) | extract the public path (D-7) |
| R-2 | see own status | — | C3 | Membership (RATIFIED) | none | reads | read model | "card" not modelled |
| R-3 | renew own membership | record renewal fee | C3/C4 | Membership (RATIFIED); fee: open | organisation rules | **yes**, self-renewal (`MembershipRenewalController.php:27-30`) | **controller** creates fee, renewal and updates member (`:62-89`) | a renewal operation |
| R-4 | pay fees | record or waive payment | C4 | **FeePaid ownership OPEN** (Gate F report §6.3); Finance context exists | organisation rules | **no.** Payment is **recorded by an officer** (`MembershipFeeController.php:99-107`, `authorize('recordFeePayment')`); only `ManualPaymentGateway` exists | `RecordFeePayment` use case (officer) | online payment capability; PD-B |
| R-5 | update personal details | — | C3/C14 | Membership / Identity (account model PROPOSED) | the person | not verified | UNKNOWN | ownership of person data |
| R-6 | request listing after change of permanent residence | verify, then change belonging | C5 | Organisation (ACCEPTED WD D-3, D-22) | a governance body (DD-7) | **no** | none | the transfer act; verifying body (Phase 3) |
| R-7 | read notices | publish | C17 | none (no design) | organisation rules | partly: e-mail newsletter (`Membership/OrganisationNewsletterController.php`) | e-mail only | in-product notices: no design |
| R-8 | submit a proposal or opinion | decide by the competent body | C18 / proposal scenario | PROPOSED (`PBDIGIT-331`…`335` track) | the competent organ | partly: Openions | **controller**; **S-7** unescaped output (`OpenionController.php:94`, `Openions.vue:46`) | S-7 first; entry into governance |
| R-9 | take part in discussions | moderate | C18 | none | organisation rules | no | none | no design |
| R-10 | see election information and results | publish results | C6/C10 | Election, Results | election rules | yes | reads (`ResultController`) | — |
| R-11 | see whether on the confirmed voter list | set rule, confirm list | C8 | Election | **the governance body** (DD-11) | partly: approved registrations (`VoterDashboardController.php:28-29`) | read | confirmation as a recorded decision |
| R-12 | object to exclusion | decide the objection | C8 or C11 | **UNKNOWN** | organisation rules | **no**. Contestation's subject set is **closed**: `election_result`, `determination`; a new case needs an ADR-UL (`app/Contexts/Contestation/Domain/Challenge/TargetType.php:10-18`) | none | PD-A |
| R-13 | submit (and withdraw, edit) a nomination | accept, reject | C6 | Election | election rules | submit **yes** (`CandidacyApplicationController.php:105,165`); **withdraw only by an officer** setting status `withdrawn` (`Election/CandidacyManagementController.php:111`) | **controller** write (`:221-230`); `NominationWindowPolicy` exists | operation extraction; participant withdrawal |
| R-14 | propose or support a candidate | check support | C6 | Election | organisation rules (D-1b) | **names only**: the candidate types `proposer_name`, `supporter_name` (`CandidacyApplicationController.php:173-174,226-227`) | none | **the supporter's own act** (§D.4) |
| R-15 | cast a vote (browser) | open, close | C9 | Voting (**FROZEN**) | eligibility from the confirmed list | yes, session-bound (`VoteController.php`) | in controller (4,388 lines) | **unchanged here**; PBDIGIT-284, -286, G-1 |
| R-16 | get a confirmation after voting | — | C9/C10 | Voting (FROZEN) | none | yes (`VotingReceiptController.php:20,89,136`) | controller | G-1 receipt hash open |

**Desktop-only capabilities (no participant operation established):** C1 Registry and structure; C2 Governance (all organ acts); C13 Audit; C15 Tenancy; platform administration.

## D. Ownership and authority implications

1. **Eligibility has three owners, and the code merges them.**
   - **Rule content:** decided by the organisation's governance body (DD-11 [PK]; Governance owns competence and authority, Session B).
   - **Evaluation for one election:** Election (ratified act `:110`; ADR-002 "process-specific").
   - **Facts:** Membership (`IsMember`, `Suspended`, `CurrentType`, tenure), Organisation (unit belonging), fee facts (owner open).
   - The code places the rule content in Election code and reads Membership tables directly (§E).
2. **Confirming the voter list is a governance act** (DD-11). It should carry a decision reference (record-first, P-2) and then fix the election's snapshot (FACT: the snapshot is immutable, `VoterSourceStrategy.php:31-33`). Today a person-level "approved by" exists (`VoterlistController.php:40-54`) with no decision reference.
3. **Voter-list objections have no home.** They arise **before** voting, against an administrative list, and are decided under organisation rules. Contestation is frozen to results and determinations (closed set). See PD-A.
4. **Proposer and supporter consent is a validity question.** In party nominations, a nomination usually needs a proposer and a seconder who are themselves eligible members ([PRAC]; **not** evidence for NRNA). The code stores **typed names** chosen by the candidate, so it cannot show that the supporter acted. Whether NRNA requires the supporter's own act is an organisation rule (D-1b) → a question for the Principal later, not a platform default.
5. **Fees: a phone requirement exposes an ownership gap.** "Pay fees on the phone" (R-4) presumes a participant payment operation. Today only officers record payments. Who owns the fact that a fee is paid (Membership `fees_status` vs Finance `FeePaidProjection`) is unresolved. See PD-B.
6. **Residence change is two facts in two contexts** (DD-6, DD-7): contact data (Membership/Identity) changes immediately; belonging (Organisation) changes only on a verified governance decision, which becomes the belonging's **basis**. The old belonging ends forward-only.
7. **No channel decides anything.** Every P operation submits a request or reads a fact; every authority-bearing outcome (approval, verification, eligibility, list confirmation, objection decision, acceptance, result) is a D operation in its owning context.

## E. Contradictions between decisions and the implementation

### E.1 Voting eligibility (Finding 1, verified)

**Where the criteria are fixed (FACT):**

| Criterion | Where | Path |
|---|---|---|
| membership mode: member status `active` | `EloquentVoterEligibilityQueryService.php:113`; `FullMembershipPolicy.php:55-61` | bulk and single |
| fees `paid` or `exempt` | `:114`; `FullMembershipPolicy.php:61` | bulk and single |
| membership type grants voting rights | `:115-116` (`membership_types.grants_voting_rights`) | **bulk only** |
| membership not expired | `:117-118` | **bulk only** |
| not soft-deleted | `:119` | both |
| election-only mode: organisation user `active`, not deleted | `ElectionOnlyPolicy.php` `decideForContext`; `:92-95` | both |
| entitlement at voting time: registered, voter role, organisation match, active, admitted | `VotingEntitlementPolicy.php` `standing()` | — |
| a third policy in **Membership**: active → eligible | `app/Contexts/Membership/Domain/Voting/VotingEligibilityPolicy.php:47-71` | **no caller found** |

**What is already configurable (FACT):**
- the voter source mode per organisation (`organisations.uses_full_membership`, `VoterSourceStrategy.php:22-28`);
- per membership type, `grants_voting_rights` (organisation data, `app/Models/MembershipType.php:23,36`);
- per election, an admission policy with **one** possible requirement, `OFFICER_IDENTITY_VERIFICATION` (`Domain/ValueObjects/AdmissionRequirement.php:15`), locked after a point (`ChangeVoterAdmissionPolicyHandler.php:39-40`).
- **None** carries a reference to the governance decision that set it (`AdmissionPolicyChange.php:15-19`: from, to, changed, affected voters).

**Not representable without code changes:** whether fees count; whether expiry counts; a cut-off date for joining; a minimum length of membership; unit scope beyond regional posts; the effect of suspension; and, for every rule, the decision that adopted it.

**PROPOSED classification:**

| Universal platform constraints (stay in code) | Organisation policy (should be set by a recorded governance decision) |
|---|---|
| the person exists in the tenant and the organisation matches | which membership statuses and types qualify |
| the voter source mode is fixed per election once snapshotted | whether fees must be paid or may be exempt |
| one person, one ballot; anonymity (FROZEN) | expiry, cut-off date, minimum tenure |
| in membership mode, the voter is a member (DD-9) | unit or regional scope of a post's electorate |
| eligibility is evaluated by Election from facts it does not own (ratified) | identity-verification requirements |
| the confirmed list and its decision are recorded before voting | whether suspension removes the right to vote (**UNKNOWN**: may be statutory; not assumed universal) |

**Contradictions:**
- **with DD-11 and D-1b:** organisation policy is fixed in platform code;
- **with the ratified Membership act (`:110`, INV-11):** an eligibility policy lives in the Membership context (unused, but present). PROPOSED: add it to the `PBDIGIT-318` baseline (Principal decision; not acted on);
- **with ratified "no shared persistence":** Election reads `members` and `membership_types` directly (already `PBDIGIT-318` V2);
- **internally:** single and bulk checks differ (already `PBDIGIT-265`).

**Backlog:** `PBDIGIT-336` (created; strategic capability gap; not authorised). No rule engine and no change proposed.

### E.2 Other participant contradictions
- **R-4:** policy says members pay on the phone; the system has no participant payment.
- **R-13:** members can submit but not withdraw their own nomination.
- **R-14:** support is a typed name, not an act.
- **R-1, R-3, R-13:** participant writes sit in controllers; a second channel would duplicate them unless extracted first (D-7).
- **R-8:** S-7 makes posting from any channel unsafe until fixed (D-0).

## F. Context-by-context tactical-design readiness

| Context | Responsibility established? | Rules understood? | Authority clear? | Dependencies defined? | Operation available? | Tactical design still needed before participant (phone) support | Verdict |
|---|---|---|---|---|---|---|---|
| **Membership** | yes (RATIFIED) | yes, frozen | approval: organisation rules; owner/admin today | Election contract `:154` | submit/approve yes; renewal no; public apply no | renewal operation; public-apply extraction; person-data ownership (R-5) | **ready** for participant operations, within the frozen architecture |
| **Election** (lists, nomination, eligibility) | boundary ratified for eligibility evaluation | partly; rule content missing | governance body for rules and list (DD-11); bodies unnamed (Phase 3) | Membership facts (contract), Governance decisions (not defined) | assign voters yes; nomination in controller | eligibility rules as decided inputs (`PBDIGIT-336`); list confirmation; nomination extraction; participant withdrawal; supporter act | **partly ready**: nomination yes; eligibility after `PBDIGIT-336` decisions |
| **Voting** | FROZEN | yes | — | Election entitlement | yes (browser, session-bound) | none for phone-browser voting; its open items stay in their tracks | **no tactical work from this commission** |
| **Organisation** (belonging, transfer) | ACCEPTED WD | transfer rule unknown | verifying body unknown | Governance decision as basis | none | the transfer act | **not ready**: Phase 3, Gate F |
| **Governance** | ACCEPTED WD; Phase 3 open | statutes unknown (HG-1) | organs not modelled (D-10) | — | committee handlers only | none for participants: desktop-only | **not ready**, and no phone demand |
| **Finance / fees** | FeePaid ownership open | unknown | unknown | Membership `fees_status` vs Finance projection | officer recording only | participant payment, if PD-B chooses it | **not ready** |
| **Contestation** | FROZEN (closed set) | — | — | — | — | only if PD-A places objections here (needs ADR-UL) | **not affected** unless PD-A (b) |
| **Communication, discussion, proposals** | none / PROPOSED | no | no | — | e-mail newsletter; Openions | strategic design first (`PBDIGIT-335` track) | **not ready** |
| **Identity and access** | partly; account model PROPOSED | — | — | — | session login | cross-channel participant authentication is D-4 (deferred) | **downstream** |

## G. Principal decisions

| # | Decision | Alternatives | Recommendation | Why only the Principal |
|---|---|---|---|---|
| **PD-A** | **Who owns voter-list objections?** | (a) **Election**: a correction step on the list before it is confirmed and snapshotted, decided by the body that confirms the list; (b) **Contestation**: add a subject type by ADR-UL, reopening a frozen closed set; (c) Governance, as a general complaint | **(a).** The objection is part of producing a correct list, before voting, under organisation rules; Contestation stays frozen for results and determinations | context ownership; (b) changes a FROZEN boundary |
| **PD-B** | **What does "pay fees on the phone" mean?** | (a) online payment through a provider (a new Finance capability); (b) the phone shows dues and how to pay, and an officer records payment (today); (c) (b) now, (a) after FeePaid ownership is decided | **(c).** Online payment needs the FeePaid owner first, and adds a regulated, organisation-specific capability | business scope; depends on the open FeePaid direction |
| **PD-C** | **Classify the eligibility criteria** as in §E.1 (universal vs organisation policy), as the scope of `PBDIGIT-336` | accept / amend / reject | **accept**, with "effect of suspension" left UNKNOWN until NRNA statutes are read (HG-1) | defines what the platform may fix for every organisation (D-1b) |
| **PD-D** | **Add the unused Membership `VotingEligibilityPolicy` to the `PBDIGIT-318` baseline** | add / leave | **add** (contradicts the ratified act `:110` and INV-11) | `PBDIGIT-318` scope is PO-gated |
| **PD-E** | **Adopt "classify operations, not persons" (§0.2) instead of a new capacity attribute** | adopt / model capacity explicitly | **adopt**: same policy, no overloaded term | vocabulary (O-14) |

## H. The single next step

**Tactical design of the Membership participant operations** (submit application from the public path, renew membership, read own status) **as channel-neutral operations inside the ratified Membership architecture.**
- **Why this one:** Membership is the only participant-facing context whose responsibility, rules and boundaries are ratified; R-1 to R-3 are phone-mandatory under DD-15; two of the three are controller writes today (§C); the work changes no frozen invariant.
- **Not yet:** Election eligibility (waits for PD-C and `PBDIGIT-336`), Organisation transfer (Phase 3), fees (PD-B), communication (no design), any mobile technology.
- It needs your authorisation as a tactical-design commission (EP-01 plan first).

## Principal outcomes (2026-11-09, after review)
- **PD-A:** accepted with modification: Election owns the objection-handling **process**; the competent deciding authority is identified separately under the applicable rules.
- **PD-B:** online payment deferred; showing payment instructions is **not** assumed to satisfy the requirement.
- **PD-C:** principle accepted; each criterion to be verified against organisational rules before classification.
- **PD-D:** rejected as worded. *Analyst's clarification (open):* the proposal was to list the unused policy in `PBDIGIT-318`'s baseline of violations **to be removed**, not to make eligibility a Membership responsibility (M-TDD-01 §0.3).
- **PD-E:** **ACCEPTED WD**: channel requirements belong to business operations, not to people or capacities.

## Correction note (2026-11-09, MOBILE-STRATEGIC-01)
Rows R-7, R-8, R-9 and the readiness rows "Communication, discussion, proposals: none / no design" were written without reading the Session B decisions D-50…D-64. Deliberation (D-50…D-56) and Communication (D-57…D-64) are accepted working designs (`docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` §23, §24, §35). The corrected catalogue and readiness are in `./2026-11-09-mobile-application-strategic-ddd-baseline.md` §3–4, §10. Text above unchanged.

## Traceability
- **Read:** the inputs in the header; `docs/adr/ADR-002-verified-eligible-authorized.md`; backlog `PBDIGIT-265`, `PBDIGIT-311`, `PBDIGIT-318`, `PBDIGIT-335`, README; the code cited in §C and §E.
- **Created:** this report; `docs/publicdigit/backlog/PBDIGIT-336-organisation-defined-voter-eligibility-rules.md`.
