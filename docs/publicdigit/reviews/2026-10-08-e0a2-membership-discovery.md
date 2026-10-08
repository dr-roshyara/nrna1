# E0a-2 Membership Discovery: existence, recognition, authority, admission, multiplicity, termination, rejoin, lifecycle, suspension

**Type:** Domain discovery · research · **decides nothing** · **Date:** 2026-10-08
**Plan:** `docs/plans/20261008-0008-membership-domain-discovery-plan.md` (Phases E–J, re-scoped as E0a-2 by the PO commission of 2026-10-08)
**Companions:**
- `2026-10-08-membership-architecture-reconciliation.md` (decision hierarchy **H-/I-/P-**, conflicts **C-**, diagrams)
- `2026-10-08-e0a2-question-register.md` (**EQ-n**, gates **G0–G6**)

**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)

> **Rule from E0a-2 on:** no model element without a requirement, a business rule, an adopted decision, an architectural invariant, an integration contract or an existing implementation constraint. Each element below names its basis.
>
> **"Organisation Membership"** = the `Member` aggregate (EM-VOC-001, DECIDED). The code name `Member` is kept until EQ-12 is decided.

---

## 1. Topic findings

Each topic records four things:
- what the code does (**IMPLEMENTED**, which is not truth);
- what is **DECIDED**;
- the open options;
- the question ID.

### 1.1 Existence and recognition (EQ-1, EQ-2)

- **IMPLEMENTED.** Five creation paths write `members.status='active'` **at the moment of creation**:
  - application approval (`ApproveMembershipApplication` → `Member::register`);
  - admin `addMember` (`OrganisationRoleController:74-112`, `fees_status=exempt`);
  - invitation accept (`OrganisationMemberInvitationController:211-222`, `unpaid`);
  - CSV import (`MemberImportService` → `Member::register`);
  - `OrganisationUserImportService:345-353`.
- **Consequence: existence = recognition = activity, in one act.** No path records a recognition decision separately. The application path does not write `reviewed_by` / `reviewed_at`.
- **DECIDED:** nothing on the organisation side. The Election side requires **"active Organisation Membership"** in Full Membership (EM-FM-002, deferred), so "active" needs a precise meaning (`HasMembership`, reconciliation §6.3).
- **Options (EQ-1):**
  - (a) existence at the admission decision, which is today's de-facto behaviour;
  - (b) at decision + first payment (business case `…0006:69-76`);
  - (c) at the effective date (future-dated memberships).
- **EQ-2:** may a recorded-but-not-yet-recognised membership exist (pending)? Today: no. Applications are a separate aggregate (`membership_applications`), which supports V4's *"APPLIED belongs to MembershipApplication"*.

### 1.2 Authority (EQ-5)

- **IMPLEMENTED:**
  - approve/reject = owner/admin (`MembershipPolicy:33-43`, read from `user_organisation_roles`);
  - membership types = owner only;
  - fees = owner/admin;
  - suspend/archive check `manageMembers`, which is **defined nowhere**, so they always return 403;
  - `MemberController::store` checks a policy for `Member` that is **not registered**, so it is always denied;
  - renewal = owner/admin or the member themselves.
- **DECIDED:**
  - EM-FM-005 names an *"Organisation Chief"* with **no referent** (EM-OPEN-014);
  - the election-side precedent (ADR §3) is that authority is **organ-scoped**, never a global role by itself.
- **Frame (class A, PROPOSED):** every membership act has an **actor**, an **organ** under which the actor acts (configured per tenant: board, membership secretary, admin, …), a **decision** and **evidence**.
- **Who (class C):** the tenant's statutes.

### 1.3 Admission paths (EQ-6)

- **IMPLEMENTED:** internal application; public application (no account; the type is chosen by the admin at approval); admin direct add; invitation; CSV import.
- `requires_approval` and `form_schema` are stored per type and **never read**.
- **Open:**
  - Is a direct add without an application allowed (and by whom)?
  - Does an invitation acceptance constitute admission, or only acceptance of an offer that an organ already decided?

### 1.4 Multiplicity, overlap and rejoin (EQ-3, EQ-8)

- **IMPLEMENTED:**
  - `members.organisation_user_id` is **UNIQUE**, which allows **at most one** Member row per affiliation, ever (soft-deleted rows still hold the key);
  - the FK is **`ON DELETE CASCADE`** from `organisation_users`;
  - so the code assumes "one membership per person per organisation, for life". Re-joining after `ended` is impossible without reviving the same row.
- **DECIDED:** nothing.
- **Options:**
  - (a) one membership for life, reactivatable;
  - (b) a sequence of memberships, non-overlapping (V4 M-7);
  - (c) concurrent memberships of different types (for example a youth plus a full membership).
- (b) and (c) change the meaning of "the person's membership" everywhere, including `HasMembership`.

### 1.5 Termination (EQ-7) and its election effect

- **IMPLEMENTED:**
  - `ExpireMemberships` (scheduled) moves active → `expired`. Renewal does **not** restore `active`.
  - `endMembership()` (→ `ended`, waives fees, sets ElectionMemberships `removed`) is **dormant**: no caller.
  - `archive` is unreachable (`manageMembers` undefined; `archived` is not in the DB CHECK).
  - Deleting `organisation_users` **hard-deletes** the Member through the cascade.
- **DECIDED (deferred):** in Full Membership mode, *"removal"* automatically suspends the ElectionMembership (EM-FM-003). Which act counts as removal is **FM-5 (OPEN)**.
- **This discovery's contribution:** EQ-7 must define the **end kinds** (resignation, expulsion, lapse, death, …), and then **state for each whether it is "removal"** in the EM-FM-003 sense. FM-5 is answered **as a consequence**, not separately.
- The hard-delete cascade is a **legacy hazard**: it destroys the membership record and any decision history. The target forbids physical deletion of a membership by an affiliation change (class A candidate, PROPOSED).

### 1.6 Lifecycle and suspension (EQ-4, EQ-9)

- **IMPLEMENTED, with three conflicting vocabularies:**
  - DB: active / expired / suspended / ended;
  - aggregate: active / inactive / suspended / archived;
  - a third value object: draft … archived (8 values);
  - the committee lineage: active / suspended / terminated.
- **DECIDED precedent (election side, SM2):** INVITED → ACTIVE ⇄ SUSPENDED → REMOVED; REMOVED is final. It is **not** binding on Membership, but it is the platform's adopted pattern.
- **Open:**
  - the canonical state set;
  - automatic vs decided transitions (expiry: automatic? lapse after grace?);
  - final states;
  - **suspension as a state vs a restriction** (EQ-9). The restriction model composes better with EM-FM-004 / EM-GOV-003 (*causes must stay distinct*). Whether organisation-side suspension triggers EM-FM-003 is FM-6.

### 1.7 Type and term (EQ-10, EQ-11): the invariant analysis V4 asked for

- **IMPLEMENTED:**
  - renewal: new expiry = max(old, now) + `type.duration_months`;
  - lifetime (null duration) is not renewable;
  - the approval fee = `type.fee_amount`, due at +duration (or 12 months);
  - **approval sets no expiry**, even for a fixed-duration type (an inconsistency).
- **Candidate invariant INV-TT-1:** *a membership's term at grant and at each renewal is determined by the duration of the type assigned to it at that moment.*
  - If adopted, the **type assignment** (typeRef + assignedAt) and the **term** must change atomically. Both belong **inside** the membership consistency boundary.
  - The **type definition** (name, fee, duration policy) is configuration **outside** it.
  - This matches the adopted EM-VOC-001 (*the concept carries type, fees and term*) for assignment and term. It differs from EM-VOC-001 only on **fees**: a fee *ledger* has its own lifecycle (pending/paid/waived/overdue) and its own writers, which suggests a separate aggregate referencing the membership.
  - Hence EQ-11 (amend EM-VOC-001 or not).
  - **Not decided.**

---

## 2. Requirement cards

The rules below already have authority, so each card's **Status** is that authority. Cards for open rules are **templates** with `❓` slots; they are not requirements yet.

### 2.1 Cards for DECIDED rules

| Field | **RC-EO-1: Register an imported person for an Election-Only election without membership** |
|---|---|
| Context | Election |
| Actor | Election Chief / officer with `manageVoters` (the actor set is open: `PBDIGIT-221` decision 2) |
| Goal | make an imported person an ElectionMember of one election |
| Trigger | import / assign / bulk-assign in an election whose population source is IMPORTED_ELECTORATE |
| Preconditions | the person has (or gets) an OrganisationAffiliation with the election's organisation; the election permits voter changes |
| Business rule | EM-EO-001/002/003: no Organisation Membership required; none created |
| Postconditions | ElectionMembership{role=voter, SM2 ACTIVE (bulk/Chief path) or INVITED}; **no** `members` row created; voter **not entitled** until admission (ADR) |
| Evidence | import record; registration audit (`PBDIGIT-203` gap) |
| Acceptance criteria | after import: `members` row count unchanged; ElectionMembership exists; with the admission policy ON, entitlement = false until verification |
| Source | EM-EO-001…003; ADR_20261004 §4; `VoterImportService.php:282-325` |
| Status | **DECIDED** (rule) · **IMPLEMENTED** (Election-Only import) |

| Field | **RC-VOC-2: An affiliation or role is never treated as membership** |
|---|---|
| Context | Organisation/Governance ↔ Membership ↔ Election |
| Actor | system (any eligibility or report) |
| Goal | no consumer infers Organisation Membership from `user_organisation_roles.role='member'`, `organisation_users`, participants or officers |
| Trigger | any membership-dependent decision |
| Preconditions | — |
| Business rule | EM-VOC-002 |
| Postconditions | membership-dependent predicates read only the Organisation Membership (via a published fact, CD-07) |
| Evidence | — |
| Acceptance criteria | an affiliate with role `'member'` and **no** `members` row fails `HasMembership`; Full Membership eligibility refuses them |
| Source | EM-VOC-002 |
| Status | **DECIDED** (rule) · conformance: **CONFLICTING** where `User::isEligibleVoter` and others are mode-blind (IERVP-6) |

| Field | **RC-ENT-1: Registration does not entitle** |
|---|---|
| Context | Election |
| Actor | system |
| Goal | voting requires registration ∧ admission ∧ lifecycle |
| Business rule | ADR invariants 1, 8, 11 |
| Postconditions | entitlement computed by the single `VotingEntitlement` decision; no stored admitted flag |
| Acceptance criteria | registered + policy ON + no evidence ⇒ cannot vote; evidence recorded ⇒ can vote while `canVote()` |
| Source | ADR_20261004 §2.1–2.2 |
| Status | **DECIDED** · **IMPLEMENTED** (`PBDIGIT-213`) |

| Field | **RC-ENT-6: Removing an ElectionMembership never removes Organisation Membership** |
|---|---|
| Context | Election → Membership |
| Business rule | EM-ENT-006; EM-VOC-003 |
| Acceptance criteria | after an ElectionMembership → REMOVED, the person's `members` row is unchanged |
| Source | EM-ENT-006 |
| Status | **DECIDED** |

| Field | **RC-SM2-1: A removed ElectionMembership cannot be reinstated** |
|---|---|
| Context | Election |
| Business rule | SM2: SUSPENDED → REMOVED is final |
| Acceptance criteria | no path (approve, assign, re-import) returns a REMOVED membership to ACTIVE |
| Source | 2026-09-16 decision |
| Status | **DECIDED** · conformance **CONFLICTING** (`AssignVoterHandler` restore; ADR G3; reconciliation C-8) |

| Field | **RC-FM-3: Full Membership: organisation-membership removal auto-suspends the ElectionMembership** |
|---|---|
| Context | Membership → Election |
| Actor | system (automatic) |
| Trigger | ❓ the "removal" act (FM-5 ← EQ-7) |
| Preconditions | the election's population source = MEMBERSHIP_REGISTRY |
| Business rule | EM-FM-003/004/006 |
| Postconditions | the ElectionMembership still exists; not exercisable; the cause is distinguishable from Chief suspension (representation: EM-OPEN-003) |
| Evidence | ❓ the cause record |
| Acceptance criteria | ❓ after EQ-7 / EQ-9 / FM-5…10 |
| Source | EM-FM-003/004/006; D-ENT-2 |
| Status | **DECIDED · DEFERRED** (EM-SEQ-001); **not implemented** (no subscriber) |

| Field | **RC-ANON-1: The persisted Vote has no identity or derivable key** |
|---|---|
| Context | Ballot |
| Business rule | ADR-T11 (INV-ANON-1, reconciliation §8) |
| Acceptance criteria | no column, FK or recomputable hash resolves a Vote to a person, membership, ElectionMembership, evidence or code |
| Source | ADR-T11 |
| Status | **DECIDED** · conformance **OPEN** (`PBDIGIT-223`, `-226`: Phase 2.0 Anonymity ADR) |

### 2.2 Templates for OPEN rules (become cards when the gates close)

| Card | Context | Actor | Trigger | Business rule | Postconditions | Status |
|---|---|---|---|---|---|---|
| RC-M-EXIST | Membership | ❓ EQ-5 organ | ❓ EQ-1 event | ❓ EQ-1/EQ-2 | membership exists ∧ recognised ∧ effective from `validFrom` | **OPEN** (G1) |
| RC-M-ADMIT | Membership | applicant / organ | application or direct add | ❓ EQ-6 | ❓ | **OPEN** (G1) |
| RC-M-EXPIRE | Membership | system | `validUntil` reached | ❓ EQ-4 (grace, automatic?) | ❓ state; ❓ an FM-6 effect | **OPEN** (G4) |
| RC-M-END | Membership | member / organ | resignation / expulsion / … | ❓ EQ-7 | state final? FM-5: is it "removal"? | **OPEN** (G4) |
| RC-M-SUSPEND | Membership | ❓ organ | decision | ❓ EQ-9 | state or restriction; FM-6 | **OPEN** (G4) |
| RC-M-RENEW | Membership | member / admin | renewal | INV-TT-1 ❓ EQ-10 | the term extended by the type's duration; state ❓ | **OPEN** (G4) |
| RC-M-REJOIN | Membership | person | re-application after end | ❓ EQ-8 / EQ-3 | new vs resumed membership | **OPEN** (G3) |
| RC-T-1 | all | system | any tenant-facing response | ❓ INV-TEN-1 (EQ-14) | no other-tenant relationship disclosed | **PROPOSED** (G5) |

---

## 3. Legacy → target mapping (input to a future strangler migration)

| Legacy | Current meaning (evidence) | Target concept | Context | Migration status | Evidence |
|---|---|---|---|---|---|
| `users` | **global** person account; `email` globally unique; `organisation_id` = current-org pointer | **Person** (PersonId internal, INV-TEN-1) | Identity | keep; stop using `users.organisation_id` as tenancy (it is not one) | `2026_03_05_000002…`; `User.php:167` |
| `user_organisation_roles` | the tenant **authorization role** (owner/admin/commission/voter/member) | **OrganisationAffiliation** (role facet) | Organisation/Governance | merge candidate with `organisation_users` (CD-03); role value `'member'` → EQ-12 | `UserOrganisationRole.php`; policies |
| `organisation_users` | tenant **user record** + status; target of `members.organisation_user_id` (UNIQUE, **CASCADE**) | **OrganisationAffiliation** (record facet) | Organisation/Governance | merge candidate; **remove the hard-delete cascade to `members`** (§1.5) | `2026_03_07_145528…`, `…145529…:17,:29` |
| `organisation_participants` | staff/guest/election_committee categories | affiliation sub-type or Governance participant ❓ | Organisation/Governance | unexamined (out of scope) | migration `2026_04_05_000003` |
| `members` + `App\Models\Member` | Organisation Membership row; status (4 values), `fees_status` + `fee_state` (two fields), type, expiry | **Organisation Membership** (EM-VOC-001) | Membership | the canonical aggregate; reconcile three vocabularies (G4); one fee truth (E0b) | Phase-A code report; `Member.php` |
| `Contexts/Membership/Domain/Member/Member` | DDD aggregate (active/inactive/suspended/archived); cannot rehydrate `expired`/`ended` | same, as the target aggregate | Membership | **the strangler seed**, once G4 fixes the states | `EloquentMemberRepository.php:38-71, :135` |
| `Domain/ValueObjects/MemberStatus` (8 values) + `Domain/Models/Member` | unrouted alternative model | — | — | **retire candidate** (no routes) | Phase-A code report |
| `MembershipLineage` / `CommitteeAssociation` | committee-membership lineage (active/suspended/terminated) | **Committee position** (not Organisation Membership) | Governance | re-home (Part 1 MQ-8 direction); ADR-F3.2 D8 status ❓ (EQ-5 frame) | `Domain/Membership/*`; ADR-F3.2 |
| `membership_types` | type definition incl. `duration_months`, `grants_voting_rights` (never written), `requires_approval` (unused) | **MembershipType definition** (configuration) | Membership | drop `grants_voting_rights` from the target (voting is election-owned; PI-1…4) | `MembershipTypeController.php:52-65` |
| `membership_fees` / payments | fee ledger; three payment paths | **Fee obligation / ledger** (E0b) | Membership (+ Finance evidence) | E0b; `PBDIGIT-209`, `-211` | Phase-A code report |
| `membership_applications` | application aggregate (5 statuses; public and internal) | **MembershipApplication** | Membership | keep; record review decision fields | `Application.php` |
| `election_memberships` (`ElectionMembership`) | the election entitlement record (role, status invited/active/inactive/removed + `suspension_status`) | **ElectionMembership** (SM2) | Election | align statuses to SM2; the organisation-driven suspension cause (EM-OPEN-003) | migrations `2026_03_17_213212`, `2026_03_22_213421` |
| `election_officers` | election-scoped chief/deputy/commissioner | **Election Committee leadership** (G10) | Election | behind `VerificationAuthoritySource` | ADR §3 |
| `VoterSourceStrategy` / `elections.voter_source_strategy` | immutable per-election snapshot | **Population source** of the Selection Contract | Election | generalise (PROPOSED); `PBDIGIT-66` D-5 to adopt | `ebf3d1a5a` |
| `VoterEligibilityPolicy` / `FullMembershipPolicy` / `EloquentVoterEligibilityQueryService` / `VoterEligibilityService` / `User::isEligibleVoter` | ≥4 eligibility predicates; raw SQL into Membership tables | **Selection evaluation** over **published** membership facts | Election (+ Membership OHS) | collapse to one (`PBDIGIT-49`); remove table coupling (CD-07) | 2026-10-08 integration review I-1, I-6 |
| `VoterAdmissionPolicy`, `AdmissionEvidence`, `voter_verifications` | election admission, derived | unchanged | Election | **DECIDED/IMPLEMENTED** | ADR; `PBDIGIT-213` |
| `VotingEntitlementPolicy` / `EloquentVotingEntitlementQuery` | the single entitlement decision | unchanged (exercisability inside) | Election | **DECIDED/IMPLEMENTED** | ADR |
| `ElectionLifecycle::canVote()` | the lifecycle SSOT | unchanged | Election | — | `ADR_20260807_1500` |
| `Member::endMembership()` | dormant; sets ElectionMemberships `removed` | **delete or rewrite** per EM-FM-003 after FM-5 | Membership→Election | do **not** wire it up | reconciliation C-5 |
| `votes.vote_hash`, `cast_at`, `device_fingerprint_hash` | anonymity-relevant | INV-ANON-1 | Ballot | `PBDIGIT-223`/`226` (Phase 2.0 Anonymity ADR) | `VoteStorageTrait.php:40-75` |

### 3.1 Stories traced (inspected, not assumed)

| Story | What it is (from the file) | Relation to E0a-2 |
|---|---|---|
| `PBDIGIT-05` | Import members (EPIC-02, *IMPLEMENTED — NOT VERIFIED*) | an admission path (EQ-6) |
| `PBDIGIT-06` | Members apply and are approved (same) | existence/recognition (EQ-1/2); the "approved member never eligible" defect sits here |
| `PBDIGIT-08` | Determine who is eligible to vote (same) | the Selection evaluation (G6); eligibility homes |
| `PBDIGIT-49` | Voter eligibility has two homes. **OPEN, not authorised** | the predicate collapse (CD-07) |
| `PBDIGIT-68` | ElectionMembership durable entitlement: D-ENT-1 RESOLVED, F1; ADR-002 amendment PROPOSED | H-03 source; C-5 note |
| `PBDIGIT-202` | Full Membership voter import calls the missing `bulkAssignVoters()` (spec in the 2026-10-01 completeness audit) | a Full Membership registration path |
| `PBDIGIT-209` | Membership fee payment cannot be recorded. **OPEN** | E0b (standing) |
| `PBDIGIT-211` | Scheduled jobs see no tenant data. **OPEN, ARB decision required** | expiry/overdue automation (EQ-4) |
| `PBDIGIT-213` | Election-Only voter admission v1. **AUTHORISED**; C0–C4 committed (`110387f63`, `622ee91e9`, `9c482db85`); the story's own row still says *"C4 … awaiting PO review before commit"* | H-10 (the chain) |
| `PBDIGIT-221` | **Add organisation members as voters of an election. OPEN, not authorised; owns registration only** | **the** registration story for the Full Membership route (G6). *Missed by my 2026-10-08 integration review, which is corrected here* |
| `PBDIGIT-257` | The membership-mode switch crashes (HTTP 500). **OPEN** | reaching Full Membership at all |
| `PBDIGIT-223` / `226` / `241` | Anonymity: vote hash recomputable / timestamps join / logs | INV-ANON-1 (223/226 OPEN pending the Phase 2.0 ADR; 241 F-V6/F-V7 resolved) |

---

## 4. Proportionality: what to model, and when

| Concept | Classification | Basis |
|---|---|---|
| Decision history (actor, organ, reason, time) for membership acts | **Required now** (at G1/G5) | EM-GOV-005 precedent; EQ-5 frame; audit |
| Valid time vs recorded time | **Required now** (at G2) | retroactive corrections of membership dates are routine in associations |
| `[from, until)` intervals | **Required now** (at G2) | V4 TC-01; avoids off-by-one renewals |
| One current term per membership (no gaps) | **Required now** | sufficient for every IMPLEMENTED flow |
| **Multiple effective intervals** per membership (gaps, reactivation) | **Option value**: only if EQ-3/EQ-8 choose "resume the same membership" | V4 §5.4 |
| **Full record history** (field-level correction log) | **Required later** (only if an audit requirement names it) | — |
| **Advanced statutes versioning** (evaluate a membership under the statutes of its creation date) | **Option value**: store a statutes-version reference only when EQ-13 adopts the term and a tenant needs it | V4 §7.4 |
| **IdentityResolution** (merge/split persons) | **Not required** now; **Required later** only if duplicate accounts become a business problem | global unique e-mail already de-duplicates |
| **Biometric / document identity evidence** | **Not required** for Membership. Identity evidence for elections is the ADR's `AdmissionEvidence` | ADR invariant 4 |
| Generic rule engine for selection criteria | **Not required** (include/exclude predicates suffice) | 0035 §10, 0038 §16 |
| Per-tenant pseudonymous PersonId | **Not required** (INV-TEN-1 via scoping) | reconciliation §10 |

---

## 5. Executive verdict

**Accepted as architecture (because already DECIDED):**
- the election-side chain Registration → derived Admission → VotingEntitlement (incl. `canVote`);
- ElectionMembership = the entitlement record (SM2 lifecycle);
- the two routes (Election-Only in force; Full Membership deferred);
- the EM-FM-003 effect (*suspend, not remove*, exercisability only, Full Membership only);
- "Organisation Membership" = the `Member` aggregate (carrying identity, type, fees, term);
- affiliation ≠ membership;
- ADR-T11 anonymity;
- Manifesto ≠ Constitution.

**Proposed, awaiting acts:**
- V4's vocabulary rename (CD-01);
- the EM-VOC-001 amendment (CD-02);
- OrganisationAffiliation (CD-03);
- "Organisation Statutes" (CD-04);
- INV-TEN-1 (CD-05);
- the one-dimension-per-status rule (CD-06);
- no cross-context table coupling (CD-07);
- the Selection Contract as the generalisation of `voter_source_strategy`;
- INV-TT-1;
- INV-SEL-1/2;
- bound admission.

**Open (the organisation side is still undecided):** existence, recognition, multiplicity, lifecycle, suspension, authority, type→term, and every FM-5…10 question. **G1, G3, G4 and G5 are all OPEN, so implementation is not authorised.**

**Conflicting (conformance):**
- the dormant `endMembership` (C-5);
- SM2 vs the assign-restore path (C-8);
- the approved-member-never-eligible defect;
- three status vocabularies;
- the hard-delete cascade.

**Single highest-value next decision: EQ-1 (+ EQ-2)**, when an Organisation Membership exists and whether that is its recognition (register §3). **Runner-up: EQ-15** (bound vs discretionary election admission), which applies to Election-Only **now**.

**Traceability:**
- reconciliation H-01…H-15, I-01…I-06, C-1…C-8, CD-01…CD-07;
- register EQ-1…EQ-16, G0–G6;
- Phase-A code report (2026-10-08);
- `VoterImportService.php:282-325`;
- `2026_03_07_145529_create_members_table.php:17,:29`;
- `MembershipRenewalController.php:40-90`;
- `ExpireMemberships.php:15-19`;
- `MembershipPolicy.php:33-53`;
- `MemberController.php:95, :309, :338`.
