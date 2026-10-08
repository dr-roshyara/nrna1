# E0a-2 Membership Development Report: discovery and development preparation

**Type:** Discovery report for **Principal Reviewer review** · research · **decides nothing; implements nothing** · **Date:** 2026-10-08
**Commission:** PO, *"Membership Development Theme — E0a-2 Discovery and Development Preparation"* (2026-10-08). The author's role is execution architect/researcher, **not** architecture authority.
**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Extends (does not repeat):**
- `2026-10-08-membership-architecture-reconciliation.md` (**R**: decision hierarchy H-/I-/P-, conflicts C-, CD-, INV-ANON-1, INV-TEN-1)
- `2026-10-08-e0a2-question-register.md` (**Q**: EQ-1…16, gates G0–G6)
- `2026-10-08-e0a2-membership-discovery.md` (**D**: topic findings, RC- cards, legacy mapping, proportionality)
- the earlier `2026-08-06-membership-capability-review.md` (MB-1…: *"a member is defined twice"*), consumed

> **Labels:** **FACT** (code, test or record says so) · **DECIDED** (a governance act exists) · **BELIEF** (inference from evidence; may be wrong) · **CANDIDATE** (my proposal) · **OPEN** (needs a decision). Implementation behaviour is further tagged **intentional / legacy / temporary / contradictory / undefined**.

---

## 0. Two points the Principal Reviewer must see first

### 0.1 An instruction conflicts with a DECIDED rule

The commission asks: *"do not call `Member` the canonical Membership entity"* (§26) and proposes *"Membership ≠ Member"* (§3). **EM-VOC-001 (DECIDED, PO 2026-08-12):** *"'Organisation Membership' means the `Member` aggregate — the organisation-side membership concept that carries membership identity, type, fees and term."* The commission's own priority order (Level 1 > Level 5) means I may not override it.

**How this report handles it:**
- It uses **"Organisation Membership"** (EM-VOC-001's own term) for the concept;
- it calls `Member` its **current code name**;
- "member" as a *role* (V4 FC-6) stays **PROPOSED**.

**Reconciling the two needs a PO terminology act (EQ-12 / CD-01).** It is not mine to make.

### 0.2 Corrections to my own earlier statements (evidence found in this round)

| Earlier statement | Correction | Evidence |
|---|---|---|
| *"An approved member can never be an eligible voter"*, listed as a **defect** (Part 1 §6.1; review §I-4) | **Partly wrong.** Approval leaving `fees_status=unpaid` is **intentional and tested** ("Path A": approve → unpaid → *mark paid* → eligible). The real product blocker is narrower: **no product UI writes `grants_voting_rights`**, so a typed member fails the type clause (IERVP-2), plus fee recording is broken (`PBDIGIT-209`). The test passes only because its fixture sets `grants_voting_rights => true` | `tests/Feature/Membership/ApplicationApprovedToVoterEligibilityTest.php:51-53, :107-262`; `MemberApprovalCreatesCorrectMemberTest.php:183` |
| The commission's preferred direction *"Finance → payment evidence → Membership"* | **The current architecture is the reverse.** Membership records fees and payments. **Finance is downstream**: it projects Income from Membership's `FeePaid` / `MembershipFeePaid` (*"Finance must treat this as data to project, never as a directive"*) | `app/Contexts/Finance/Events/FeePaidProjection.php:1-12`; `app/Listeners/CreateIncomeForMembershipFee.php`; `EventServiceProvider.php:50-60, :84` |
| My integration review missed `PBDIGIT-221` | Corrected (D §3.1; integration review, correction 2) | — |

**Observation (classified Engineering, owned by `PBDIGIT-209` GREEN, *"align … the `CreateIncomeForMembershipFee` listener"*):** the listener is registered **twice**, once in `$listen` (`:50-53`) and again with `Event::listen` in `boot()` (`:84`), with auto-discovery off. That is **BELIEF:** one payment produces two Income rows (not runtime-verified). **No new ID:** this is a proposed addendum to `PBDIGIT-209`.

---

## 1. Membership existence (EQ-1): decision analysis

| Candidate | Evidence | Consequence | Status |
|---|---|---|---|
| **Application creates Membership** | **Against:** `membership_applications` is a **separate aggregate** with its own lifecycle (draft / submitted / under_review / approved / rejected; `lock_version`, `reviewed_by`, `reviewed_at`, `rejection_reason` in the schema). Public applications exist with **no person account** (`user_id` null). Rejection leaves no Member | Membership would have to model *rejected* and *withdrawn* as membership states. The person would "be a member" before any organ decided | **FACT:** not how the system works. **BELIEF:** not the business meaning |
| **Admission decision creates Membership** | **For:** all five creation paths create the Member **at the decision moment**, already `active` (D §1.1). The tests pin *"approving … sets membership_type_id"*, *"generates membership_number"* (`MemberApprovalCreatesCorrectMemberTest:94-150`). The legacy guides: *"User applies → Admin approves → Member record created"* (`ablauf:610-620`) | existence = the decision. Recognition is **the same act**. Effectiveness defaults to that moment (but no `joined_at` / expiry is written on the approval path: **contradictory**) | **FACT (implemented, intentional).** As domain truth: **CANDIDATE** |
| **Effective date creates Membership** | No future-dated membership exists anywhere (code, tests, guides). `ExpireMembershipsCommandTest:100` (*"future member is not expired"*) concerns a future **expiry**, not a future start | separates existence from effectiveness; allows "admitted, effective 1 January" | **OPEN.** No evidence for or against a business need |
| **Registry entry creates Membership** | CSV import and `addMember` create members **without** a recorded decision (registry entry as the act). The business case describes the *registry* as the product (*"authoritative member list"*) | migration/import becomes a legitimate existence path; it needs a **recorded basis** (for example "imported from the prior register on date X by Y") | **FACT (implemented).** Its legitimacy as an existence act: **OPEN** |
| **Decision + first payment** | Business case: *"Approved → Fee Generated → Payment Recorded → Active Member"* (`docs/membership/…0006:69-76`). **Contradicted** by the code and tests (active at approval; payment changes only `fees_status`) | existence or activity conditional on dues: **mixes lifecycle with standing** (violates the six-dimension separation) | **CONFLICTING** (business case vs implementation). Proper home: standing, not existence (**CANDIDATE**) |

**Synthesis (CANDIDATE, for the Principal Reviewer):** *an Organisation Membership comes into existence through an **admission act** by an authorised organ, or through a **registry-entry act with a recorded basis** (import/migration). The application precedes it and is not part of it.* Payment affects **standing**, not existence.
- **Not decided.** The deciding question is EQ-1, and its tenant-parameter part is whether an organisation may *require* first payment before effectiveness (class B).

## 2. Do existence, recognition, effectiveness and lifecycle need separate concepts? (EQ-2)

| Concept | Does the business distinguish it? (evidence) | Verdict |
|---|---|---|
| Existence vs **recognition** | **Today: no.** The one act creates and recognises. But **imports** create without any recorded recognition decision, which is exactly where the two come apart | **CANDIDATE:** keep them as one act for admission, and require a *recorded basis* for registry entry. A separate "recognised" flag **only if** EQ-2 says a membership can exist unrecognised (no evidence of that need) |
| Existence vs **effectiveness** | `joined_at` and `membership_expires_at` exist; expiry is scheduled; lifetime = no expiry; renewal extends the term. **So effectiveness is already a distinct, temporal fact** | **FACT:** distinct. Model as an interval (`[from, until)`, TC-01 PROPOSED) |
| Effectiveness vs **lifecycle** | `ExpireMemberships` turns an *effectiveness* fact (expiry passed) into a *lifecycle* state (`expired`). **That is the inference FC-11 warns against.** Renewal then extends the interval but **does not** restore the state (**contradictory**) | **CANDIDATE:** "lapsed" is **derived** from effectiveness and should not be a stored state, unless the business attaches a *decision* to lapse (EQ-4) |
| Lifecycle vs **standing** | `fees_status` (dues) sits on the Member row beside `status`; eligibility reads both | **FACT:** two dimensions already; the publisher question is in §11 |

## 3. Authority (EQ-5): traced, per operation

| Operation | Actor today | Authority basis today | Evidence recorded today | Decision record? | Effect | Status |
|---|---|---|---|---|---|---|
| **Create via admission** | owner/admin (`MembershipPolicy::approveApplication`, `:33-36`) | `user_organisation_roles.role` ∈ {owner, admin} | application row → `approved`. **`reviewed_by` / `reviewed_at` NOT written** on this path | **No** (fields exist, unused) | Member active + pending fee | FACT. Legacy: the role is the authority; **no organ** |
| **Create via direct add** | owner/admin (`OrganisationRoleController::authorizeAdmin`) | role | none | No | Member active, `exempt` | FACT |
| **Create via import** | **any** user with any role in the org (`MemberImportController:162-170`) | none meaningful | import job | No | Member active | FACT. **Contradictory** (no authority) |
| **Create via invitation** | the inviter: owner/admin; the invitee accepts | role + token | invitation row | partial | Member active, `unpaid` | FACT |
| **Recognise** | (same act as creation) | — | — | — | — | FACT: not separate |
| **Change type / term** | type definitions: owner only; a member's type: no command found; term: renewal by owner/admin **or the member** (`MembershipRenewalController`) | role / self | `membership_renewals` row (old/new expiry, fee, `renewed_by`) | **Yes, for renewal** | expiry extended | FACT |
| **Suspend** | `manageMembers`, **undefined**, so always 403 | none | — | — | unreachable | FACT. **Undefined** |
| **Terminate / archive / end** | `manageMembers` (undefined); `endMembership` has no caller; expiry is automatic | none | — | — | unreachable / automatic | FACT. **Undefined** |
| **Election-side precedent** | Chief/Deputy, **election-scoped**; a global role is never sufficient by itself | ADR §3 / D2 (**DECIDED**) | evidence row with `verified_by`, `verifier_role`, `method`, `verified_at` | Yes | — | DECIDED (Election; precedent only) |
| **Committee-side precedent** | lineage: `suspend` / `terminate` require actor, reason, timestamp; *"termination requires institutional justification"* | ADR-F3.2 (accepted-legacy) + tests | episode + reason | Yes | — | FACT (tested), for **committee association** |

**Findings:**
- **(a)** The organisation side has **no organ concept**. Authority = a platform role (owner/admin).
- **(b)** Three of the five membership acts are **unreachable or unauthorised**.
- **(c)** Both neighbouring designs (the election verification authority, the committee lineage) already use **actor + organ + reason + evidence**.

**CANDIDATE frame (class A):** every membership act records *{actor, organ acted under, decision, reason (where the act is adverse), decidedAt, effectiveAt}*. **Which organ** performs which act is **class C** (statutes). The platform offers a configurable mapping (act → organ), defaulting to owner/admin for backward compatibility. **OPEN:** EQ-5 and EM-OPEN-014 ("Organisation Chief").

## 4. Admission: is `MembershipApplication` a real concept? (EQ-6)

- **FACT:**
  - it is a real aggregate (`Domain/Application/Application`) with events (`ApplicationSubmitted`, `ApplicationApproved`, `ApplicationRejected`);
  - two channels (internal; public without an account);
  - duplicate guards, rate limits, expiry of public applications (60 days, hard-coded);
  - **about ten** feature tests (`PublicApplication*Test`, `MembershipApplicationTest`, `ReviewMembershipApplicationHandlerTest`).
- **Model A** (*Application → AdmissionDecision → Membership*) is **the implemented model**.
- **Model B** (*Membership in PENDING*) has **no evidence**. No code, test or guide creates a pending membership.
- **CANDIDATE:** keep Model A. The **AdmissionDecision** is the application's terminal transition **plus** the recorded decision (actor, organ, reason). It creates the membership.
- **OPEN:**
  - whether admission without an application (direct add, import, invitation) is legitimate, and under which organ (EQ-6);
  - `requires_approval` per type is stored and **never read** (**undefined**).

## 5. Multiplicity, overlap, rejoin (EQ-3, EQ-8)

**Evidence:**

| Source | Says | Tag |
|---|---|---|
| `members.organisation_user_id` **UNIQUE** + `ON DELETE CASCADE` | at most one membership row per affiliation, **ever** | FACT · legacy (BELIEF: a convenience constraint, not a business rule) |
| `docs/membership/…0003:225` | *"[after ending] To reinstate, a **new application** is required"* | proposed (guide) |
| renewal guide + `canSelfRenew()` (90 days **after** expiry) | a **lapsed** membership can be **resumed** by renewal within a window | FACT (implemented) + guide |
| committee lineage tests | *"terminated member can **reapply creating new** constitutional relationship"*; *"reapplication is separate from restoration"*; *"preserves institutional history"*; *"terminated cannot be reactivated directly"* | FACT (tested), committee association |
| SM2 (election side) | REMOVED is final | DECIDED (Election) |

**BELIEF (well supported):** the business distinguishes **two ends**:
1. **Lapse** (expiry). The same membership can be **resumed** (renewal window).
2. **Termination** (ended, expelled, resigned). This is **final**. Coming back is a **new** admission, which (per both precedents) creates a **new** relationship identity with history preserved.

The UNIQUE constraint **contradicts** (2), because a second membership row can't exist.

**Non-overlap (CANDIDATE invariant INV-MULT-1):** *for one person and one organisation, at most one Organisation Membership is non-terminated at any time.* It is CANDIDATE because concurrent memberships of different types (for example youth plus full) are **not evidenced anywhere**.

| Question | Candidate answer | Basis |
|---|---|---|
| Who owns it? | Membership | it is about the membership relationship |
| When enforced? | at **admission** (the only act that creates a non-terminated membership) | §1 |
| Consistency | **synchronous** at admission (rare, human-paced writes; a violation would be a real double membership) | BELIEF |
| Mechanism (class D, after the invariant) | **a partial unique index** on (person, organisation) WHERE not terminated, plus an application-level check for a clear error. Aggregate-only cannot see siblings; a serialised processor and reconciliation are disproportionate for this write rate | candidate; engineering decides |
| Override | none needed if it is a real invariant. An organ-authorised **correction** path for data errors | OPEN |
| Who reconciles legacy violations? | migration (the UNIQUE index guarantees none exist today) | FACT |

## 6. Lifecycle: from business events (EQ-4)

| Business event | Who decides | Automatic? | Evidence today | Membership still exists? | State it implies (candidate) |
|---|---|---|---|---|---|
| Admitted | organ (§3) | no | application approval (partial) | yes (created) | *in force* |
| Term ends without renewal | — | **yes** (scheduler) | `ExpireMemberships` (tested: lifetime never expires; expiry is exclusive of "today") | yes | **lapsed**: derived from effectiveness (§2) ❓ grace (`config` key unused) |
| Renewed | member (self, within the window) or organ | no | `membership_renewals` | yes | in force (**the code fails to restore `active`**: contradictory) |
| Suspended | ❓ organ | no | `Member::suspend()` exists, **unreachable** | yes | **suspended**: a state or a restriction (§7) |
| Restored from suspension | ❓ organ | no | `Member::reactivate()` (no route) | yes | in force |
| Resigned / ended | member or organ | no | `endMembership` (dormant) | **yes, as history** | **terminated** (final) |
| Expelled | ❓ organ (+ appeal?) | no | none | yes, as history | terminated (final) |
| Archived | ❓ | ❓ | `ArchiveMember` (unreachable; `archived` not in the DB CHECK) | — | **BELIEF:** a record-management concept, not a lifecycle state |

**CANDIDATE canonical set (for EQ-4):** `IN_FORCE`, `SUSPENDED`, `TERMINATED` (final), with **lapse derived** from the effectiveness interval.
- It matches both precedents: committee lineage ACTIVE / SUSPENDED / TERMINATED; election SM2 ACTIVE / SUSPENDED / REMOVED.
- `DORMANT`: **no evidence** (drop). `APPLIED` / `PENDING`: belong to the application (§4). `ARCHIVED`: record management.
- **Not decided.**

## 7. Suspension vs restriction (EQ-9)

The distinction the commission asks for is **supported by the evidence**:

| Kind | What is suspended | Evidence | Owner | Candidate representation |
|---|---|---|---|---|
| **Membership suspension** | the relationship itself (all membership rights) | `Member::suspend()`, `MemberSuspended` event, DB value `suspended`; lineage `suspend()`; guides | Membership | a **lifecycle state** (SUSPENDED), with decision, reason and optionally `until` (EM-OPEN-009 analogue: temporary?) |
| **Participation restriction** | **one right** (for example voting in elections), while the membership stays in force | PI-5 (PO: *"suspended from election via a disciplinary committee"*); EM-GOV-002 (Chief suspension: election-scoped) | **Governance** (the disciplinary organ), or Election for election-scoped ones | a **restriction** record {right, scope, from/until, decision}. **Not** a membership state |

**Consequence for Election (stated only so the published contract carries the right facts):**
- EM-FM-003 speaks of membership **removal**. Whether a membership **suspension** also triggers it is **FM-6 (OPEN)**.
- A participation restriction is a **different fact**, published by its owner, never folded into membership state.
- **CANDIDATE:** both kinds exist and are not merged. Each is a separate fact.

## 8. Termination (EQ-7)

| Question | Evidence | Status |
|---|---|---|
| What terminates? | resignation, ending (`endMembership` reason field), expulsion (no evidence in code), death (none); lapse is **not** termination (§5) | OPEN (the end-kind list) |
| Who may terminate? | nobody, reachably (§3). Lineage: an actor with justification | OPEN (EQ-5) |
| When effective? | `ended_at` (immediate) | CANDIDATE: `effectiveAt` may differ from `decidedAt` (a resignation "at year end" is common; BELIEF) |
| Retroactive? | no evidence of need | **not required now** (proportionality) |
| Reversible? | lineage: *"cannot be reactivated directly"*; SM2: final; guide: *"new application required"* | CANDIDATE: final. A data **correction** (wrong person) ≠ reversal, which needs a correction path |
| Rejoin? | yes, via a new application (guide, lineage) | CANDIDATE |
| New MembershipId on rejoin? | both precedents: **new identity**; the UNIQUE constraint prevents it today | CANDIDATE: **new identity**, history kept. **Open for the Principal Reviewer** |
| Is it "removal" for EM-FM-003? | — | ⚠️ the mapping must be stated for **each** end kind (**FM-5**) |

## 9. Temporal semantics

| Time | Meaning | Needed now? | Evidence |
|---|---|---|---|
| `effectiveFrom` / `effectiveUntil` `[from, until)` | when the membership is in force | **yes** | `joined_at`, `membership_expires_at`, renewal arithmetic |
| `decidedAt` | when the organ decided | **yes**, with the decision record (§3) | `reviewed_at` exists (unused) |
| `recordedAt` | when the system recorded it | **yes**, cheaply (`created_at`) | timestamps |
| a full bitemporal history (corrections of past validity) | — | **not required now** | no requirement found |
| multiple intervals per membership | gaps / resumption | **only** to represent *lapse → resume* without gap semantics. **BELIEF:** a single interval plus a renewal ledger (`membership_renewals`, which exists) suffices | FACT: the renewal ledger exists |

**Calendar basis:** `LocalDate` in the **tenant's time zone** (EM-VOC-008 D-3 precedent: the device time zone is never a business rule). The tenant time-zone source is **OPEN** (G2).

## 10. MembershipType (EQ-10, EQ-11)

| Question | Answer from evidence | Tag |
|---|---|---|
| What is it? | a per-organisation class of membership: name, fee, currency, duration (null = lifetime), `requires_approval` (unused), `form_schema` (unused), `grants_voting_rights` (read by 5, written by none in product code) | FACT |
| Who owns it? | Membership; owner-only management (`MembershipPolicy::manageMembershipTypes`) | FACT |
| Does a membership reference a type? | yes, `members.membership_type_id`, nullable (admin-added members have none) | FACT. Nullable = **legacy** (EM-VOC-001 says the concept *carries* a type) |
| Does type influence duration? | **yes**, at renewal (`+duration_months`), and lifetime = not renewable. **Not** at approval (no expiry written: contradictory) | FACT |
| Obligations / dues? | yes: the approval fee = `type.fee_amount`; renewal creates a fee; `fee_amount_at_time` snapshots it | FACT |
| Standing? | indirectly (via dues) | FACT |
| Voting rights? | **legacy:** `grants_voting_rights`; tests `MembershipTypeVotingRightsTest`, `MemberVotingRightsTest` encode it | FACT (legacy, tested). **Target: MembershipType ≠ VotingEntitlement** (PO direction PI-1…4; P-02 PROPOSED) |

**Invariant analysis:**
- **INV-TT-1 (CANDIDATE):** *a membership's term at grant and at each renewal is computed from the duration of the type assigned to it at that moment.* That couples the **type assignment** and the **term** atomically, so **both belong inside** the membership boundary.
- The **type definition** is configuration **outside** it. Its fee and duration are **snapshotted** at use (`fee_amount_at_time` already does this for fees; the term needs no snapshot because it is stored as dates).
- This agrees with EM-VOC-001 for type and term. **For "fees"**, the fee *ledger* has its own lifecycle and writers. **BELIEF:** a separate aggregate referencing the membership (EQ-11).
- **Mid-term type change:** no command exists. Guide: *"duration cannot be changed once members have this type"* (`0003:80`). **OPEN.**
- **The type-change effect on voting tests:** the legacy tests pin the type→voting coupling. Retiring it (P-02) means retiring those tests. **Principal Reviewer decision.**

## 11. Standing and dues: who publishes "dues compliance"?

- **FACT:**
  - fees and payments are recorded **in Membership** (three payment paths; `PaymentPolicy`; `FeePaid` domain event; `RecalculateMemberFeeStatus`);
  - Finance **projects** Income from those events and is forbidden to interpret them;
  - eligibility reads `members.fees_status` directly (raw SQL from Elections).
- **BELIEF:** whichever way payment evidence flows, the **publisher of dues compliance is Membership**, because the *obligation* is defined by the membership type and the membership term (§10).
- Finance being upstream (an external payment provider) or downstream (as today) changes **where payment evidence enters**, not who publishes standing.
- **CANDIDATE:**
  - Membership is the sole publisher of *dues compliance* (and later *standing*);
  - Finance stays a downstream projection, as **already implemented**;
  - an *external* payment-evidence source (bank or PSP) would enter Membership through an ACL;
  - **the commission's "Finance → Membership" direction is not supported by the current architecture.** Changing it would be an **architecture decision**.

**Two truths today:** `fees_status` (legacy path) vs `fee_state` (DDD path), and eligibility reads only the first (D §1, MB-x). One truth is a prerequisite for any published standing (E0b).

## 12. Published contract: which facts are actually consumed?

**Consumers found in code (FACT):**

| Consumer | Reads | How |
|---|---|---|
| Elections: eligibility (`EloquentVoterEligibilityQueryService`, `VoterEligibilityService`) | membership status, `fees_status`, type `grants_voting_rights`, `membership_expires_at`, `deleted_at` | **raw SQL** (CD-07 violation) |
| `User::isEligibleVoter` | the same | Eloquent |
| Newsletter (`NewsletterService:158,171`) | `grants_voting_rights` (audience) | Eloquent |
| Committee lineage | `MemberId` reference | domain reference |
| Dashboards | counts by status and fees | read models |
| Finance | `FeePaidProjection` (amount, currency, method) | event (**the only real published contract today**) |

**CANDIDATE contract** (*MembershipFacts v0*): only the facts consumers use, **without** voting semantics.

| Field | Meaning | Note |
|---|---|---|
| `organisationRef`, `personRef` | tenant-scoped references | INV-TEN-1: no cross-tenant correlation |
| `membershipRef` | the membership identity | new on rejoin (§8, CANDIDATE) |
| `inForce(asOf)` | existence ∧ recognised ∧ effective ∧ ¬suspended ∧ ¬terminated | the precise form of EM-FM-002's "active". **Depends on EQ-1/2/4/9** |
| `lifecycleState` | IN_FORCE / SUSPENDED / TERMINATED (+ a lapse flag derived) | EQ-4 |
| `effectiveFrom`, `effectiveUntil` | interval | TC-01 |
| `membershipTypeRef` | classification **only** (no voting meaning) | Election criteria may filter on it |
| `duesCompliant(asOf)` + `reasonCodes` | standing component | **E0b.** Only once one fee truth exists |
| `asOf`, `sourceVersion` | staleness and replay | |

- **Owner:** Membership.
- **Version:** semantic, starting at v0 (unpublished).
- **Mechanism:** an implementation choice (a query port first; events later only if a consumer needs push, for example EM-FM-003).
- **Consumer expectations:**
  - Election evaluates criteria at the **reference date** (EQ-16);
  - consumers never read tables.
- **Not finalised.** Gated on G1/G4.

## 13. Audit: which requirements actually exist?

| Requirement | Source | Strength |
|---|---|---|
| Decisions on applications record the reviewer, time and rejection reason | schema columns (`reviewed_by`, `reviewed_at`, `rejection_reason`, `lock_version`) | **implied** (built, unused) |
| Termination requires a justification; history is preserved | lineage tests + ADR-F3.2 (accepted-legacy) | **tested**, committee side |
| Renewal history (old/new expiry, fee, who) | `membership_renewals` | **FACT** |
| Payment audit trail | business case (*"complete audit trail"*); `PaymentPolicy` (unique reference) | proposed + implemented |
| Election-side: every material event recorded | EM-GOV-005 | **DECIDED**, *Election only* |

**CANDIDATE (proportionate):** one **MembershipDecision** record per adverse or constitutive act (admit, suspend, restore, terminate, correct) = {act, membershipRef, actor, organ, reason, decidedAt, effectiveAt, recordedAt}. **No** event sourcing, **no** field-level history, **no** generic audit framework.

---

## 14. Candidate Organisation Membership aggregate (after invariant analysis)

| Element | Content | Label |
|---|---|---|
| **Aggregate root** | Organisation Membership (code: `Member`; EM-VOC-001) | **PROVEN** as a concept (DECIDED vocabulary); name **OPEN** (EQ-12) |
| Identity | `MembershipId`, independent of person and organisation; new on rejoin | id **PROVEN** (exists); new-on-rejoin **CANDIDATE** |
| References | `PersonRef` (via affiliation today), `OrganisationId` | **PROVEN** |
| Value: effective interval | `[effectiveFrom, effectiveUntil)`; null = lifetime | **CANDIDATE** (dates **PROVEN**) |
| Value: lifecycle state | IN_FORCE / SUSPENDED / TERMINATED | **OPEN** (EQ-4) |
| Value: type assignment | `typeRef` + `assignedAt` | **CANDIDATE** (INV-TT-1) |
| Entity: decisions | MembershipDecision (§13) | **CANDIDATE** |
| Commands | `Admit` (from AdmissionDecision / registry entry with basis) · `Renew` · `Suspend` · `Restore` · `Terminate` · `CorrectRecord` · (`ChangeType`: **OPEN**) | **CANDIDATE** |
| Invariants inside | one identity; one organisation; interval well-formed; transitions legal (TERMINATED final); term derived from the type at grant/renewal (INV-TT-1); every adverse act carries organ + reason | **CANDIDATE** |
| Invariants outside | INV-MULT-1 (non-overlap, per person+organisation): domain check at `Admit` + DB partial unique index | **CANDIDATE** |
| Domain services | admission eligibility (no in-force sibling); dues-compliance evaluation (E0b) | **CANDIDATE** / E0b |
| Events | `MembershipAdmitted`, `MembershipRenewed`, `MembershipSuspended`, `MembershipRestored`, `MembershipTerminated` (+ the existing `FeePaid` from the fee aggregate) | **CANDIDATE.** The names must follow the EQ answers |
| **Outside** (separate aggregates) | MembershipApplication (**PROVEN**) · MembershipType definition (**PROVEN**) · Fee / payment ledger (**PROVEN** as separate; BELIEF it stays so) · CommitteeAssociation / MembershipLineage (**PROVEN**: committee side, Governance direction) | — |

**Legacy conflicts the aggregate must resolve:**
- **two** `Member` aggregates + **three** status vocabularies (MB-1 / MB-2);
- the UNIQUE + CASCADE constraint;
- lapse stored as a state;
- renewal not restoring the state;
- nullable type;
- two fee fields.

## 15. Requirement register (REQ-MEM)

| REQ | Context / Actor | Goal | Trigger | Preconditions | Business rule | Postconditions | Temporal | Authority | Evidence | Acceptance criteria | Source | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|
| **REQ-MEM-001** | Membership / applicant | apply for membership | submission (internal or public) | organisation accepts applications; no open application by the same person (guard) | an application is not a membership | application `submitted`; **no** membership | `submittedAt` | — | application record | a submitted application creates no membership row (supported: the tests create members only on approval) | FACT (impl + tests) | **PROPOSED** as a requirement |
| **REQ-MEM-002** | Membership / organ | admit an applicant | approval | application submitted; ❓ organ (EQ-5) | admission creates the membership (§1 synthesis) | membership exists, in force from ❓ (EQ-1); a pending fee if the type has a fee | `decidedAt`, `effectiveFrom` | ❓ organ | decision {actor, organ, decidedAt} | ❓ beyond today's tests (type set; number generated) | FACT + CANDIDATE | **OPEN** (G1) |
| **REQ-MEM-003** | Membership / organ | reject an applicant | rejection | submitted | rejection creates no membership | application `rejected` + reason | `decidedAt` | ❓ | reason (column exists) | rejected → no membership | FACT | **PROPOSED** |
| **REQ-MEM-004** | Membership / system | lapse at the end of the term | `effectiveUntil` passed | in force; not lifetime | lapse is derived from the interval (**CANDIDATE**); grace ❓ | not in force after `effectiveUntil` (+grace ❓) | `[from, until)` exclusive end (tested: *"expiring today is not expired"*) | automatic | the interval | lifetime never lapses (tested) | FACT (tested) + CANDIDATE | **OPEN** (EQ-4) |
| **REQ-MEM-005** | Membership / member or organ | renew | renewal | in force or lapsed within the window; not lifetime | new until = max(until, now) + type duration (INV-TT-1) | interval extended; fee created; **membership in force** | renewal ledger | self (window) or organ | `membership_renewals` | renewal of a lapsed membership restores in-force (**fails today**) | FACT + CANDIDATE | **PROPOSED** |
| **REQ-MEM-006** | Membership / organ | suspend the membership | decision | in force | ❓ EQ-9 (state vs restriction) | SUSPENDED; reason recorded | `decidedAt`, `until`? | ❓ organ | decision | — (none supported yet) | CANDIDATE | **OPEN** |
| **REQ-MEM-007** | Membership / member or organ | terminate | resignation / expulsion / … | not terminated | termination is final; rejoin = new admission | TERMINATED; history kept | `decidedAt`, `effectiveAt` | ❓ organ (+appeal ❓) | decision + reason | terminated cannot be reactivated (precedent tests) | CANDIDATE + precedent | **OPEN** (EQ-7) |
| **REQ-MEM-008** | Membership / system | prevent double membership | `Admit` | — | INV-MULT-1 | admission refused if an in-force or suspended sibling exists | at admission | — | — | second admission refused | CANDIDATE | **OPEN** (EQ-3) |
| **REQ-MEM-009** | Membership / system | keep affiliation ≠ membership | any | — | EM-VOC-002 | role/affiliation never yields membership facts | — | — | — | = RC-VOC-2 | **DECIDED** | **DECIDED** |
| **REQ-MEM-010** | Membership → consumers | publish membership facts | query/event | — | CD-07 (no table reads) | consumers use MembershipFacts v0 | `asOf` | — | `sourceVersion` | Election eligibility reads no `members` table | CANDIDATE | **PROPOSED** |
| **REQ-MEM-011** | Membership / organ | record every constitutive or adverse act | any of 002/003/006/007 | — | §13 | a MembershipDecision exists | decided / effective / recorded | organ | the record itself | each act has actor + organ + time (+ reason if adverse) | CANDIDATE | **PROPOSED** |
| **REQ-MEM-012** | Membership / importer | enter the existing register | import | ❓ authority (today: anyone with a role) | registry entry needs a recorded basis | membership exists + basis recorded | `effectiveFrom` from the source | ❓ | the import record | ❓ | FACT + CANDIDATE | **OPEN** (EQ-1, EQ-6) |

*(Already-DECIDED cards RC-EO-1, RC-ENT-1, RC-ENT-6, RC-SM2-1, RC-FM-3, RC-ANON-1 are in D §2.1 and not repeated.)*

## 16. Decision register (delta to R §1; nothing here is decided by me)

| Item | Class | Status |
|---|---|---|
| "Organisation Membership" = the `Member` aggregate (EM-VOC-001) vs the commission's "Membership ≠ Member" | A | **CONFLICTING** (§0.1). Needs a PO terminology act |
| Approval leaves dues unpaid; *mark paid* = exempt ("Path A") | B | **IMPLEMENTED, intentional** (tested). Business meaning of paid vs exempt **OPEN** (Part 1 MQ-4) |
| Finance downstream of Membership for fees | A | **IMPLEMENTED, intentional** (`FeePaidProjection` docblock). The commission's assumption **CONFLICTING** |
| Existence = admission decision (or a registry entry with basis) | A | **CANDIDATE** (§1) |
| Lapse derived, not stored | A | **CANDIDATE** (§2, §6) |
| States IN_FORCE / SUSPENDED / TERMINATED | B | **CANDIDATE** (§6) |
| Membership suspension (state) ≠ participation restriction (Governance) | A | **CANDIDATE** (§7) |
| Termination final; rejoin = new identity | A | **CANDIDATE** (§8; two precedents) |
| INV-MULT-1 + partial unique index | A + D | **CANDIDATE** (§5) |
| INV-TT-1 (type assignment + term inside) | A | **CANDIDATE** (§10) |
| Membership publishes dues compliance | A | **CANDIDATE** (§11) |
| Retire type→voting coupling (and its tests) | A | **PROPOSED** (PI-1…4 direction); a **Principal Reviewer decision** |
| The organ concept for membership authority | A frame / C assignment | **OPEN** (EQ-5, EM-OPEN-014) |
| `CreateIncomeForMembershipFee` registered twice | Engineering | **BELIEF**: addendum to `PBDIGIT-209` |

## 17. Legacy → target (problem and migration; complements D §3)

| Current | Current meaning | Problem | Target concept | Migration |
|---|---|---|---|---|
| `Contexts/Membership/Domain/Member/Member` + `App\Models\Member` | two live Member models (MB-1) | divergent rules | one Organisation Membership aggregate | choose the DDD one as the seed; adapt the Eloquent one as persistence |
| `members.status` (4) / `MemberStatus` (4, different) / VO `MemberStatus` (8) | three vocabularies | rehydration fails on `expired`/`ended` | IN_FORCE / SUSPENDED / TERMINATED + derived lapse | map: active→IN_FORCE; suspended→SUSPENDED; ended→TERMINATED; expired→IN_FORCE with a past `until` (derived lapse); archived→record flag |
| `members.organisation_user_id` UNIQUE + CASCADE | one membership per affiliation, forever; hard delete | blocks rejoin; destroys history | person+org reference; INV-MULT-1 partial index; no cascade delete | additive: a new partial index, then drop UNIQUE, then replace the cascade with restrict |
| `fees_status` + `fee_state` | two dues truths | eligibility reads one | one dues-compliance fact (E0b) | E0b; `PBDIGIT-209` |
| `membership_types.grants_voting_rights` | type grants voting | violates PI-1…4; unwritable | none (election criteria may filter on `typeRef`) | deprecate after the Principal Reviewer's decision; retire `MembershipTypeVotingRightsTest`, `MemberVotingRightsTest` with it |
| `MembershipPolicy` (owner/admin) / `manageMembers` (undefined) / no MemberPolicy | role = authority | no organ; 3 acts unreachable | an act→organ mapping (EQ-5) | after G5 |
| `ExpireMemberships` (stores `expired`) | lapse as a state | FC-11 inference; renewal does not undo it | derived lapse | after G4 |
| `endMembership()` | dormant; removes ElectionMemberships | contradicts EM-FM-003 / EM-EO-004 / SM2 | `Terminate` + a published `MembershipTerminated` fact | delete once `Terminate` exists |
| `membership_applications` | real application aggregate; decision fields unused | no decision record | MembershipApplication + AdmissionDecision record | write the existing columns first (smallest step) |
| `MembershipLineage` / `CommitteeAssociation` | member↔committee relationship (tested lifecycle) | shares the word "membership" (L-2) | committee association (Governance direction) | re-home later; **its lifecycle is the precedent** for §6/§8 |
| `organisation_users` / `user_organisation_roles` | affiliation (record + role) | two tables, one concept; role value `'member'` | OrganisationAffiliation | R §7; EQ-12 |

## 18. Context map (only boundaries the evidence supports)

```text
IDENTITY ── users (global; email unique) ── PersonRef (internal)          [FACT]
   │
ORGANISATION/GOVERNANCE ── organisations · OrganisationAffiliation (organisation_users + user_organisation_roles) [FACT tables; concept CANDIDATE]
   │                       · committees / lineage (Contexts/Governance, Contexts/Committee exist)              [FACT]
   │                       · disciplinary restriction (PI-5)                                                    [OPEN: no code]
   ▼
MEMBERSHIP ── Organisation Membership · MembershipApplication · MembershipType · Fee/Payment ledger               [FACT]
   │   └── MembershipFacts v0 (query port)  ─────────────►  ELECTION (consumer; unchanged; R §4, §12)            [CANDIDATE]
   │
   └── FeePaid / FeePaidProjection ──────────────────────►  FINANCE (Income projection; downstream)             [FACT]
MEMBERSHIP STANDING: a published fact of MEMBERSHIP (E0b), not a separate context                               [CANDIDATE]
```

---

## 19. Reviewable summary (for the Principal Reviewer)

1. **What we KNOW (FACT)**
   - Membership is created at an approval, import, add or invitation act, already active. The application is a separate, real aggregate.
   - Lapse is computed by a scheduler and stored as `expired`; renewal does not restore the state.
   - Suspend, archive and end are unreachable or have no organ.
   - At most one membership per affiliation, ever (UNIQUE + CASCADE).
   - Finance is downstream of Membership.
   - Approval-with-unpaid-dues is intentional (tested).
   - `grants_voting_rights` has no product writer.
   - Elections reads Membership tables directly.
   - The committee lineage has a tested lifecycle in which a terminated relationship can only be re-entered as a new one.
2. **What we BELIEVE**
   - The business distinguishes lapse (resumable) from termination (final; rejoin = new relationship).
   - The UNIQUE constraint is a convenience, not a rule.
   - Payment belongs to standing, not existence.
   - Membership suspension and participation restriction are different facts.
   - The double listener duplicates Income.
3. **What is DECIDED (relevant here)**
   - EM-VOC-001/002/003, EM-EO-*, EM-FM-* (deferred), EM-SEQ-*, EM-ENT-*, SM2, ADR_20261004, ADR-T11, Manifesto ≠ Constitution (R §1.1).
   - Nothing on the organisation-side lifecycle or authority.
4. **What is PROPOSED**
   - The V4 principles (incl. "member = role");
   - CD-01…07;
   - MembershipFacts v0;
   - the retirement of type→voting.
5. **What is UNKNOWN**
   - whether any tenant needs future-dated, concurrent or retroactive memberships;
   - whether "archived" means anything to the business;
   - the tenant time-zone source;
   - whether account-existence disclosure matters (EQ-14).
6. **What CONFLICTS**
   - the commission's "Membership ≠ Member" vs EM-VOC-001;
   - "Finance → Membership" vs the implementation;
   - the business case (active after payment) vs the implementation (active at approval);
   - UNIQUE vs rejoin;
   - stored `expired` vs renewal;
   - dormant `endMembership` vs EM-FM-003 / EM-EO-004 / SM2;
   - two Member models and three vocabularies.
7. **Requires a PO/ARB decision**
   - EQ-1/2 (existence/recognition);
   - EQ-4 (states; lapse derived?);
   - EQ-7/8 (termination final; rejoin identity);
   - EQ-9 (suspension vs restriction);
   - EQ-10/11 (type and term; EM-VOC-001 amendment);
   - EQ-12 (terminology);
   - the retirement of type→voting.
8. **Requires a tenant/statutory decision (class C)**
   - which organ performs each act (EQ-5);
   - grounds for suspension and expulsion; appeal;
   - whether first payment is required before effectiveness (B parameter);
   - grace days;
   - whether approval is required per type.
9. **Requires an architecture decision**
   - CD-07 (no table coupling) and MembershipFacts v0 as the mechanism;
   - INV-MULT-1 enforcement (a partial unique index);
   - INV-TEN-1;
   - Membership as the sole publisher of dues compliance (vs the commission's Finance-first direction);
   - the aggregate seed choice (MB-1).
10. **Ready for implementation:** **nothing in the Membership domain model.** Gates G1/G3/G4/G5 are OPEN, and Full Membership is deferred (EM-SEQ-001).
    - Independently authorisable defect stories exist (`PBDIGIT-257`, `-202`, `-209`).
    - **One no-regret step**, should the PO wish to authorise it: write the **already-existing** decision columns (`reviewed_by`, `reviewed_at`) on the approval path. It is additive, model-neutral, and evidence for whichever EQ-1 answer is chosen. It is not authorised by this report.

### The smallest next decision that unlocks the most

> **EQ-7 + EQ-8 as one question: "Is ending a membership final, so that coming back is a new admission that creates a new membership identity, while a lapse can be resumed by renewal?"**

Why this one rather than EQ-1, which I recommended in Q §3: Phase-A evidence shows EQ-1 is **already nearly answered by practice** (existence = the admission act). Its remaining part (registry entry with basis; payment-before-effect) is narrow.

The termination/rejoin answer, by contrast, unlocks at once:
- the **state set** (EQ-4: whether lapse is a state or derived);
- **identity semantics** (new id on rejoin);
- the **non-overlap invariant** and its mechanism (EQ-3, INV-MULT-1, the UNIQUE→partial-index migration);
- the **termination events** that **FM-5** needs ("which act is removal");
- the legacy mapping of `expired` / `ended` / `archived`.

Both precedents (the tested committee lineage; election SM2) already point to "final + new identity", so the decision is **small and well-evidenced**.

---

**STOP. Findings are presented for Senior DDD Architect / Principal Reviewer review. No implementation will follow without a decision record.**

**Traceability:**
- tests: `ApplicationApprovedToVoterEligibilityTest.php:51-262`, `MemberApprovalCreatesCorrectMemberTest.php:94-201`, `ExpireMembershipsCommandTest.php:60-129`, `tests/Unit/Constitutional/Membership/{Reapply,Terminate,Restore}MembershipTest.php`;
- code: `MembershipLineage.php:1-60`, `FeePaidProjection.php`, `EventServiceProvider.php:44-92`, `MembershipPolicy.php`, `MemberController.php:95,:309,:338`, `MemberImportController.php:162-170`, `MembershipRenewalController.php:20-98`, `2026_03_07_145529_create_members_table.php:17,:29`, `2026_04_03_155710…:29-34`;
- docs: `docs/membership/…0003:80,:225`, `…0006:69-76`, `2026-08-06-membership-capability-review.md` (MB-1/MB-2);
- governance: EM-VOC-001…003, SM2 (2026-09-16), ADR_20261004 §3, ADR-F3.2 (accepted-legacy).
