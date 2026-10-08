# Membership Architecture Reconciliation (E0a-2 entry)

**Type:** Architecture reconciliation · research · **decides nothing** · **Date:** 2026-10-08
**Plan:** `docs/plans/20261008-0008-membership-domain-discovery-plan.md` (APPROVED 2026-10-08; this is its Phases E–J, as re-scoped by the PO's E0a-2 commission of 2026-10-08)
**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable) · **Roles:** DDD Architect · Requirements Engineer · Architecture Governance Analyst
**Placement:** `docs/publicdigit/reviews/` (as the PO directed for this discovery)
**Companion artifacts:**
- `2026-10-08-e0a2-question-register.md` (deliverable B, plus the gates)
- `2026-10-08-e0a2-membership-discovery.md` (deliverables C, D, E)

> **Status vocabulary (used precisely).**
> - **DECIDED / APPROVED:** a PO, ARB or Principal Architect act exists, and its record is cited.
> - **IMPLEMENTED:** the code does it. That carries **no** governance authority.
> - **PROPOSED:** in a document; no act.
> - **OPEN:** a question is registered and unanswered.
> - **UNKNOWN:** the authority status cannot be established from the repository.
> - **CONFLICTING:** two statements cannot both hold.
>
> The words "ratified", "frozen" and "binding" are used **only** when quoting a source.
>
> **The Election Manifesto** as a *catalogue* is PROPOSED, not ratified (`EM-OPEN-017`). Its **rows** cite their own adopting acts (for example A-1…A-6: PO 2026-08-12), and those acts are what give each row authority.

---

## 1. Decision hierarchy

### 1.1 Authoritative (DECIDED / APPROVED)

| ID | Statement (short) | Source | Status | Authority | Impact on the Membership architecture |
|---|---|---|---|---|---|
| H-01 | ElectionMembership = the election-specific entitlement record. Existence ≠ exercisability. Organisation membership changes do not destroy it. One concept across both modes; only the route differs. Removing it never removes Organisation Membership. The Election Chief governs it | EM-ENT-001…007; governance package A-1…A-6 | **DECIDED** | PO 2026-08-12 | Election owns entitlement. Membership never grants it |
| H-02 | Election-Only: Organisation Membership not required; a person may become an ElectionMember directly; no Organisation Membership is created; Organisation Membership removal has no automatic election effect | EM-EO-001…004 | **DECIDED** | PO 2026-08-12 | **Both routes must exist** (§4) |
| H-03 | Full Membership: membership is a superior prerequisite; must be active; removal **automatically suspends** the ElectionMembership; that suspension is distinct from Chief suspension; membership belongs to the "Organisation context", governed by the "Organisation Chief"; admission prerequisite + continuously relevant to **exercisability** | EM-FM-001…006; D-ENT-1/F1 refined by D-ENT-2 | **DECIDED · DEFERRED** (not in force this phase) | PO 2026-08-12 | §5 |
| H-04 | Election-Only is phase 1. Full Membership is a later phase. Full Membership rules must not be inferred from the Election-Only implementation | EM-SEQ-001/002 | **DECIDED** | PO 2026-08-12 | Membership discovery is **allowed**; Membership **implementation** is gated |
| **H-05** | **"Organisation Membership" means the `Member` aggregate**: the organisation-side concept that **carries membership identity, type, fees and term** | **EM-VOC-001** | **DECIDED** | PO 2026-08-12 (Manifesto §4) | **Constrains V4** (§3, C-1, C-2) |
| **H-06** | **A technical organisation association or role assignment is not Organisation Membership, whatever its name or role value** | **EM-VOC-002** | **DECIDED** | same | **Grounds OrganisationAffiliation** (§7) |
| H-07 | ElectionMember ≠ Organisation Member; ElectionMembership does not imply Organisation Membership | EM-VOC-003 | **DECIDED** | same | |
| H-08 | The election governs exercisability. The Chief may suspend independently. Chief suspension ≠ organisation-driven suspension | EM-GOV-001/002/003 | **DECIDED** | PO | §5 Q7 |
| H-09 | **ElectionMembership lifecycle (SM2):** INVITED→ACTIVE, ACTIVE⇄SUSPENDED, SUSPENDED→REMOVED. **REMOVED is final.** Chief bulk import/assignment may create ACTIVE directly | 2026-09-16 governance decision (resolves EM-OPEN-001/002/004) | **DECIDED** | PO/ARB 2026-09-16 | the target for "suspends" in EM-FM-003 |
| **H-10** | **Registration ≠ Admission ≠ Voting Entitlement. Registration creates the ElectionMembership. Admission = `VoterAdmissionPolicy.isSatisfiedBy(evidence)`, *derived, never stored*. `VotingEntitlement = registered ∧ role=voter ∧ status=active ∧ org match ∧ ¬deleted ∧ admitted ∧ ElectionLifecycle.canVote()`. The policy is locked from ReadyForVoting. Verifier = election Chief/Deputy** | ADR_20261004_1549 §2.1–2.3, §4 | **DECIDED**; **IMPLEMENTED** (`PBDIGIT-213`: `110387f63`, `622ee91e9`, `9c482db85`) | PO 2026-10-04 | **The election-side chain is fixed** (§4) |
| H-11 | "Admission" in EM-ENT-003 / PBDIGIT-68 **= Registration**. Voter Admission is the *subsequent* evaluation. The mapping is recorded; nothing renamed | ADR §4, gap **G11** | **DECIDED** (mapping); terminology reconciliation **OPEN** (PO act) | PO 2026-10-04 | vocabulary (§6) |
| H-12 | **Anonymity:** no voter↔vote linkage in any aggregate, event payload or projection; hashes only | ADR-T11 | **DECIDED** (constitutional, build-breaking) | ADR-T log | §8 |
| H-13 | Manifesto ≠ Constitution. `ElectionConstitution` = the implementation home of the election workflow rules (ADR-001). Entitlement, ElectionMembership, suspension and voter-participation rules stay **outside** `ElectionConstitution` | Principal Architect ruling 2026-08-13 (Manifesto :28-34) | **DECIDED** | Principal Architect | terminology (§9) |
| H-14 | Verification authority = the **election-scoped** Chief/Deputy (`election_officers`, `election_id` = this election). Organisation-wide officers grant nothing. The Election Committee leadership domain is not yet modelled (G10) | ADR §3, D2 | **DECIDED** | PO 2026-10-04 | officers are **not** affiliation roles (§7) |
| H-15 | Delivery baseline frozen at PBDIGIT-208. New IDs only for an uncovered delivery obligation | PO 2026-10-01 | **DECIDED** | PO | no new IDs here (§10) |

### 1.2 Implemented without a governance decision (IMPLEMENTED; authority OPEN or UNKNOWN)

| ID | Statement | Evidence | Status | Note |
|---|---|---|---|---|
| I-01 | `voter_source_strategy` is an **immutable per-election snapshot** (`election_only` / `full_membership`) | `ebf3d1a5a`; `VoterSourceStrategy` docblock | **IMPLEMENTED**; decision **OPEN** (`PBDIGIT-66` D-5: *"Is the voter-source strategy an immutable constitutional snapshot?"*) | the natural carrier of the **population source** (§4) |
| I-02 | Election-Only import creates a global `User` + `user_organisation_roles{role:'member'}` + `organisation_users{status:active}` + `ElectionMembership{voter, active}` + `VoterInvitation` | `VoterImportService.php:282-325` | **IMPLEMENTED** | the role value `'member'` is **not** membership (H-06) |
| I-03 | The DB FK `election_memberships → user_organisation_roles` was **dropped** (2026-05-19) | `2026_05_19_000001_harden_election_memberships_for_election_only_mode.php` | **IMPLEMENTED** | so deleting the role row no longer cascades. Bears on FM-5 / W-7 (§5) |
| I-04 | `Member::endMembership()` sets the user's active ElectionMemberships to `removed` | `Member.php:172-192`; **no caller** | **IMPLEMENTED (dormant)** | **CONFLICTING** with H-03 (suspend, not remove), H-02 (ignores mode) and H-09 (ACTIVE→REMOVED is not an allowed transition) |
| I-05 | Nothing in Elections subscribes to Membership events | `git grep` of providers, listeners, `Contexts/Elections` → none | **IMPLEMENTED (absent)** | EM-FM-003 is unimplemented (FM-11); consistent with DEFERRED |
| I-06 | Membership renewal: new expiry = max(old expiry, now) + `type.duration_months`; lifetime types (null duration) are not renewable. **Approval sets no expiry** | `MembershipRenewalController.php:40-90`; approval use case | **IMPLEMENTED** (inconsistent) | the type→term invariant (§3 C-2) |

### 1.3 Proposed (non-authoritative)

| ID | Statement | Source | Status |
|---|---|---|---|
| P-01 | FC-1…FC-12 (Person ≠ Membership ≠ Member; Membership entity with its own id; `[from, until)`; three histories; contracts, not tables; **"No Member entity in the core context"**; **"MembershipType … outside the aggregate"**) | V4 (`…/brainstorming/20261008_1301_…`) | **PROPOSED**. Its self-description "Ratified" is **not supported** by any act |
| P-02 | Selection Contract; Evaluation → Admission → ElectionMembership → Entitlement → Exercisability; bound admission | Blueprint `…_1303_…`; 0035/0038/0041 | **PROPOSED** (the chain order is **CONFLICTING** with H-10) |
| P-03 | INV-SEL-1/2 (eligibility holds at the reference date; later fact changes affect only exercisability, and only where a rule names them) | 0041 §2 | **PROPOSED** |
| P-04 | OrganisationAffiliation; Membership Standing (published, with reason codes); one publisher per fact | Discovery Part 1; 0041 §6 | **PROPOSED** |
| P-05 | PO inputs PI-1…PI-5 (election-owned criteria: committee level and type, dues, disciplinary ban; the election committee decides) | brainstorm 0032–0038 | **PO business input**; **not adopted rules** |

### 1.4 Open (registered elsewhere; consumed, not re-asked)

`FM-1…FM-15` / `W-1…W-8` · `EM-OPEN-003` (exercisability concept; where a suspension lives) · `EM-OPEN-014` (Organisation Chief) · `EM-OPEN-017` (Manifesto ratification) · `PBDIGIT-66` D-1…D-6 · ADR G8 (Full Membership adoption), G10, G11 · `PBDIGIT-221` decisions 1–4 · `PBDIGIT-223`/`226` (anonymity; Phase 2.0 Anonymity ADR). The new E0a-2 questions are in the register.

---

## 2. Conflicts found (not resolved silently)

| # | Conflict | Sides | Disposition |
|---|---|---|---|
| **C-1** | V4 FC-6/FC-7 *"No Member entity in the core Membership context"* vs **EM-VOC-001** *"Organisation Membership means the `Member` aggregate"* | P-01 vs H-05 | **Partly reconcilable.** EM-VOC-001 fixes the **referent** (the organisation-side membership concept, currently implemented as the `Member` aggregate). It does not fix a class name. V4's *rename* (Member → Membership; "member" as a role) is a **naming proposal compatible with the referent**, but it needs a **PO terminology act**, because it changes adopted vocabulary wording. **Until then, the adopted term stands.** → EQ-12 |
| **C-2** | V4 naming rule 4 *"Do not put MembershipType, obligations, dues or standing into the Membership aggregate"* vs **EM-VOC-001** *"… carries membership identity, type, fees and term"* | P-01 vs H-05 | **CONFLICTING.** The adopted description places **type, fees and term inside** the concept. V4's split is admissible only by a **PO amendment of EM-VOC-001**. The invariant analysis (discovery §3.3) supports a *partial* split: type **assignment** + term inside; type **definition** + fee ledger outside. This is a **candidate amendment**, not a decision → EQ-11 |
| **C-3** | Blueprint chain *Eligibility → Admission → ElectionMembership → Entitlement → Exercisability* and *"ElectionAdmissionDecision"* as a stored record, vs **ADR** *Registration creates the ElectionMembership → Admission derived, never stored → VotingEntitlement includes `canVote()`* | P-02 vs H-10 | **The ADR prevails.** The blueprint adapts: Selection Contract + Evaluation precede **Registration**. Voter Admission stays derived. A stored **refusal ground** (if bound admission is adopted) is *evidence*, never a positive admitted flag. Exercisability is not a stage after entitlement: **`canVote()` is inside entitlement**, and other exercisability causes (suspension) act through `status`/SM2 (§4) |
| C-4 | Blueprint *"ElectionMembership ⇒ VotingEntitlement"*, *"VotingEntitlement ⇒ Eligibility"* (timeless) | P-02 vs H-10, H-01 (EM-ENT-004) | **Withdrawn.** Correct forms: `VotingEntitlement(p,E) ⇒ ElectionMembership(p,E, role=voter)`; and `Registered(p,E) ⇒ EligibleAt(p,E,ref_E)` (P-03, PROPOSED) |
| C-5 | `Member::endMembership()` → ElectionMembership `removed` | I-04 vs H-03, H-02, H-09 | **Conformance gap** (dormant code). Do not wire it up. Recorded for `PBDIGIT-68` |
| C-6 | Blueprint / V4: a single route *Person → Membership → … → Vote* | P-02 vs H-02, H-04 | **Withdrawn.** Two population routes (§4) |
| C-7 | EM-FM-005 *"Organisation context … Organisation Chief"* vs code namespace `App\Contexts\Membership` and ADR-F3.2 D8 *"Membership owns lifecycle truth"* (accepted before the current governance model; **UNKNOWN** standing) | H-03 vs legacy | **OPEN.** Naming and ownership → EQ-5, EQ-13 |
| C-8 | SM2 *"REMOVED is final"* vs `AssignVoterHandler` `findWithTrashed → restoreAndUpdate` (re-assignment restores a soft-deleted ElectionMembership) | H-09 vs code | **Conformance gap, already registered** as ADR G3 (*"one-actor approve/suspend/assign-restore"*). Soft-delete ≠ SM2 REMOVED needs checking in that slice |

---

## 3. Corrections applied to the proposed architecture

1. **Status:** V4 and the Blueprint are **PROPOSED**. Their FC list is a **candidate for row-by-row adoption**.
2. **Two routes:** the population source is a dimension (§4).
3. **The ADR chain is preserved** (§4).
4. **EM-FM-003 is traced and its open parts listed** (§5).
5. **The anonymity boundary is structural** (§8).
6. **Vocabulary:** the "Member" name stays adopted (C-1). The six dimensions are defined with tests (§6). `HasMembership` is defined (§6.3).
7. **OrganisationAffiliation is grounded in EM-VOC-002** (§7).
8. **MembershipType is re-derived** (discovery §3.3; C-2).
9. **"Constitution" vs organisation statutes** (§9). **Tenancy and PersonId** (§10).

---

## 4. The two routes and the election-side chain (faithful to H-01, H-02, H-10)

```text
POPULATION SOURCE (per election, snapshot)          ← I-01 voter_source_strategy (IMPLEMENTED; decision OPEN, PBDIGIT-66 D-5)
  ├─ IMPORTED_ELECTORATE   (= election_only)        ← H-02 EM-EO-001…004  (in force)
  └─ MEMBERSHIP_REGISTRY   (= full_membership)      ← H-03 EM-FM-*        (DEFERRED)

SELECTION CONTRACT (PROPOSED, P-02) = population source + criteria; frozen like the
  VoterAdmissionPolicy (ADR D4, PROPOSED analogy). For IMPORTED_ELECTORATE the
  criteria set may be empty (the imported list IS the population).

        Evaluation (PROPOSED)        ── governs who MAY be registered ──┐
                                                                         ▼
REGISTRATION ─────────► ElectionMembership {role, status ∈ SM2}       (H-10, H-09) ── "admission" in EM-ENT-003 (H-11)
                                │
                                ├─ VOTER ADMISSION = policy.isSatisfiedBy(evidence)   derived, never stored (H-10 inv. 11)
                                │
                                ▼
VOTING ENTITLEMENT = registered ∧ role=voter ∧ status=active ∧ org match ∧ ¬deleted
                     ∧ admitted ∧ ElectionLifecycle.canVote()                (H-10; "may vote NOW")
                                │
                                ▼
                        cast-vote transaction ──► Vote record  (detached: §8)
```

**Exercisability** (EM-ENT-002 / EM-GOV-001) is **not** a separate stage. It is the conjunction *status=active (SM2: not SUSPENDED) ∧ admitted ∧ canVote()* inside VotingEntitlement. Where the suspension cause lives is **EM-OPEN-003** (OPEN).

---

## 5. EM-FM-003 trace: "Organisation Membership removal → ElectionMembership suspended"

| # | Question | Answer | Evidence | Status |
|---|---|---|---|---|
| 1 | What act constitutes Membership "removal"? | **Not established.** Candidates: Member `ended` (`endMembership`, dormant) · Member `archived` (unreachable: `manageMembers` undefined; `archived` is not in the DB CHECK) · deleting the `user_organisation_roles` row (its cascade is **gone**, I-03) · `organisation_users` removal | FM-5 / `W-7` | **OPEN** |
| 2 | Termination, expiry, suspension or another state? | The rule text says *removal*. **Expiry and organisation-side suspension are not covered** by the text | FM-6 / `W-5` (*"I will not read 'removal' as covering expiry"*) | **OPEN** |
| 3 | Is the ElectionMembership terminated or suspended? | **Suspended**; existence is untouched | EM-FM-003; EM-ENT-004; A-3 | **DECIDED·DEFERRED** |
| 4 | Automatic? | **Yes** | EM-FM-003 (*"automatically suspends"*) | **DECIDED·DEFERRED** |
| 5 | Full Membership only? | **Yes.** Election-Only: no automatic effect | EM-FM-003 scope; **EM-EO-004** | **DECIDED** |
| 6 | Restoration of membership? | **Not established** (lift automatically, or require an election act?). Can the Chief override? | FM-7 / `W-1`; FM-8 / `W-2` | **OPEN** |
| 7 | Effect on eligibility, entitlement, exercisability or status? | **Exercisability** (EM-FM-006: *"continuously relevant to whether the entitlement is exercisable"*), with **existence preserved**. **Representation is not established:** SM2 has one `SUSPENDED` state, but EM-FM-004 / EM-GOV-003 require the organisation-driven suspension to be **distinct** from the Chief's. That implies either a suspension **cause** dimension or a separate condition. Interaction of two causes: FM-9 / `W-3`. Already voted: FM-10 / `W-4` | EM-FM-004/006; EM-GOV-003; EM-OPEN-003 | **effect DECIDED·DEFERRED; representation OPEN** |
| — | Current implementation | **Not implemented** (I-05: no subscriber). The dormant `endMembership` contradicts it (C-5) | FM-11 | consistent with DEFERRED |

**Nothing above is invented. The open items are the existing FM/W register.** E0a-2 adds only **EQ-9** (suspension: state or restriction, on the **Membership** side), which feeds FM-6.

---

## 6. Semantic dimensions: definition, owner, test, time, evidence

### 6.1 Organisation side (Membership)

| Dimension | Definition | Owner | Test (candidate) | Temporal | Evidence | Status |
|---|---|---|---|---|---|---|
| **Existence** | an Organisation Membership (H-05: the `Member` aggregate) record exists for (person, organisation) | Membership | ∃ record m with m.person = p ∧ m.org = o ∧ ¬deleted | recorded time | creation record | definition PROPOSED; **criterion OPEN (EQ-1)** |
| **Recognition** | the organisation has accepted m through its authorised act | Membership (act) / Governance (authority) | ∃ recognition decision d for m by an authorised actor (EQ-5) | valid time of the decision | decision record (actor, time, reason) | **OPEN (EQ-2)**. Today approval = creation = active (conflated) |
| **Effectiveness** | m is in force at time t | Membership | `validFrom ≤ t < validUntil` (TC-01, PROPOSED) | valid time; `LocalDate` per tenant time zone ❓ | term record | PROPOSED; today `joined_at` / `membership_expires_at` |
| **Lifecycle** | the governed condition of m | Membership | m.state ∈ canonical set (EQ-4) | state history | transition + decision | **OPEN (EQ-4, EQ-9)**. Three vocabularies in code |
| **Standing** | m satisfies its obligations (for example dues) at t | Membership (publisher) | f(obligations, payments, t), with reason codes | as of t | fee ledger, payment evidence | PROPOSED (E0b); **out of scope for E0a-2** |

### 6.2 Election side (fixed by H-10; listed only for disambiguation)

| Dimension | Definition | Owner | Test | Status |
|---|---|---|---|---|
| **Eligibility** | may p be registered for election E under its population source and criteria | Election | `SelectionContract_E(facts@ref_E)` (PROPOSED). Today `VoterEligibilityPolicy` per `voter_source_strategy` | PROPOSED generalisation of IMPLEMENTED |
| **Registration** | creation of ElectionMembership(p,E) | Election | row exists, role, SM2 status | **DECIDED** (H-10, H-11) |
| **Voter Admission** | the registered voter satisfies E's admission policy now | Election | `policy.isSatisfiedBy(evidence)` | **DECIDED**, derived |
| **Voting Entitlement** | may p vote in E now | Election | ADR formula | **DECIDED** |
| **Exercisability** | not a separate stage. It is the "now" part of entitlement (status, admitted, `canVote`) | Election | see §4 | DECIDED in substance (EM-ENT-002); representation EM-OPEN-003 |

**Rule (PROPOSED, class A):** *no status value may stand for more than one dimension.* `ACTIVE` on a Member means lifecycle only. It never implies recognised, effective, in standing, eligible or entitled.

### 6.3 The `HasMembership` predicate (replaces "qualifying member")

```text
HasMembership(personId, organisationId, refDate) :=
  ∃ m ∈ OrganisationMembership(H-05) :
        m.personId = personId ∧ m.organisationId = organisationId
      ∧ Exists(m) ∧ Recognised(m, refDate)            -- EQ-1, EQ-2
      ∧ Effective(m, refDate)                          -- TC-01 interval
      ∧ m.state(refDate) ∈ ADMISSIBLE_STATES           -- EQ-4 / EQ-9 (OPEN: which states)
```

`ADMISSIBLE_STATES` is **OPEN**. It is the set that makes EM-FM-002's *"active Organisation Membership"* precise. **Standing is deliberately not in `HasMembership`.** Whether an election requires standing is a **criterion** of that election (P-02), not part of the membership predicate.

---

## 7. OrganisationAffiliation (grounded in H-06 / EM-VOC-002)

**Evidence: four persisted "person–organisation" relations exist, none of which is Organisation Membership.**

| Table | Meaning in code | Written by | Read by |
|---|---|---|---|
| `user_organisation_roles` (unique user+org; role ∈ owner/admin/commission/voter/member) | **authorization role** within the tenant (`UserOrganisationRole::ROLES`; `'member'` = *"default role; read-only org access"*) | organisation creation (owner), invitations, approval, **Election-Only import (`'member'`)** | policies (`MembershipPolicy`, `OrganisationPolicy`) |
| `organisation_users` (status active/inactive/suspended; role owner/admin/staff/member) | tenant-scoped **user record**; target of `members.organisation_user_id` | approval, `addMember`, imports | eligibility (`organisation_users.status='active'` for Election-Only), the Member link |
| `election_officers` (role chief/deputy/commissioner; `election_id` nullable) | **election-scoped appointment** | officer management | `VerificationAuthoritySource` (H-14) |
| `organisation_participants` (staff/guest/election_committee) | operational participant category | participant import | — |

**Finding:** the platform needs a named concept for *"this person has access to, and a role in, this organisation, without being a member"*. EM-VOC-002 already rules that such associations **are not membership**. **Candidate: `OrganisationAffiliation`** (Organisation/Governance context) = `user_organisation_roles` ⊕ `organisation_users`. Today these are two tables for one concept (a legacy duplication).
- **Election officers are not affiliation roles.** They are election-scoped appointments in the Election context (H-14, G10).
- **Election-Only voters** are affiliates with no membership. Their role value `'member'` is a naming collision that EM-VOC-002 already neutralises (*"whatever its name or role value"*).
- **Status:** the concept is PROPOSED, with an **adopted basis** (H-06). Its name → EQ-12.

---

## 8. Anonymity boundary: structural invariant (H-12)

**INV-ANON-1 (restating ADR-T11 structurally):** the persisted Vote (and Result) has **no column, foreign key, event payload or derivable key** that resolves to any of:

```text
Vote ✗── PersonId (users.id)        ✗── Organisation Membership id (members.id)
     ✗── ElectionMembership id      ✗── OrganisationAffiliation id
     ✗── AdmissionEvidence id (voter_verifications)   ✗── EligibilityEvaluation id (future)
     ✗── Code id / user-linked code value  (see note)
```

**"Derivable" is the operative word.** A hash is a linkage if anyone holding the platform's data and configuration can recompute it from identity-linked rows. The process *Person → ElectionMembership → VotingEntitlement → cast* is allowed. **The persisted Vote is the end of a one-way door.**

**Evidence:** `votes` / `results` have no `user_id` (migration comments `:14`, `:17`). **But** `vote_hash = sha256(code.id ‖ election.id ‖ code.code_to_open_voting_form ‖ cast_at ‖ vote_salt)` (`VoteStorageTrait.php:40-50`), and `codes` carries `user_id` and the plaintext code. `votes` also carries a precise `cast_at` and a deterministic `device_fingerprint_hash`. These are **already registered** and decision-pending:
- `PBDIGIT-223` (*vote hash computable from voter-side data*);
- `PBDIGIT-226` (*voter-side timestamps join to the ballot*).

Both are owned by the **Phase 2.0 Anonymity ADR**. `PBDIGIT-241` F-V6/F-V7 has been **resolved**. **No new item.** The Membership architecture **adds no new linkage path**: Membership facts never reach the cast transaction.

---

## 9. Terminology: "constitution" vs organisation statutes

**Evidence:**
- "constitution(al)" is used in about 277 files in `app/` with the meaning of **PublicDigit's own governance rules**: `ElectionConstitution` (ADR-001), `CommitteeConstitution`, `ConstitutionalArbitrationKernel`, …
- The Principal Architect ruling (H-13) defines `ElectionConstitution` precisely.
- No term for the **tenant's own founding rules** exists (`git grep -i "satzung|statute"` → none in `app/` or `docs/publicdigit`).

**Candidate terminology (PROPOSED, PO act needed: EQ-13):**
- **"Organisation Statutes"** (DE: *Satzung*) = the tenant's own rules about its membership and organs, held by PublicDigit as **evidence/configuration**.
- **"Constitution"** stays reserved for PublicDigit's governance (H-13).
- V4's "Constitution (versioned)" therefore reads as **"Organisation Statutes (versioned)"**.
- This is **not stylistic**: it prevents a collision on the word that carries the most authority.

---

## 10. Tenancy and PersonId

**Evidence:**
- `users.id` is a **global** UUID, and `users.email` is **globally unique** (`2026_03_05_000002…:12-16`).
- `users.organisation_id` is a **current-organisation pointer** (`User::currentOrganisation()`, `:167`; registration sets the platform org, `RegisterController.php:62`), **not** a tenant boundary.
- Tenant relations are the affiliation tables (§7). So **PersonId is platform-global and internally correlated across tenants.**

**Observed exposure:** the Election-Only import refuses with *"User '{email}' does not exist in the platform"* (`VoterImportService`, row validation). A tenant admin can therefore learn whether **a platform account exists** for an e-mail address (an existence oracle). It does **not** reveal *which* other organisations the person belongs to. **No path was found that shows a tenant another tenant's relations.** This was not exhaustively verified.

**INV-TEN-1 (PROPOSED, class A):** *a tenant-facing response never reveals a person's relationship (affiliation, membership, election participation) with any other organisation.* **Enforcement is class D** (query scoping, response shaping). **Whether account existence is itself protected** is a PO question (EQ-14). Per the proportionality rule, **no per-tenant pseudonymous identity is introduced now**. The global PersonId stays internal.

---

## 11. Context map (candidate)

```text
                 ┌────────────── IDENTITY (platform) ──────────────┐
                 │ Person = users (global id, global e-mail)       │  PersonId internal; never exposed cross-tenant (INV-TEN-1)
                 └───────────────┬────────────────────────────────┘
                                 │ PersonId
                 ┌───────────────▼────────────────────────────────┐
                 │ ORGANISATION / GOVERNANCE (tenant)              │
                 │  Organisation · Organisation Statutes (EQ-13)   │
                 │  OrganisationAffiliation (H-06)  ◄── access, org roles, Election-Only electorate
                 │  Committees / jurisdiction (PI-1; later)        │
                 └──────┬─────────────────────────────┬───────────┘
                        │ affiliation facts           │ authority facts (EQ-5)
        ┌───────────────▼──────────────┐              │
        │ MEMBERSHIP (tenant)          │◄─────────────┘
        │  Organisation Membership     │   = `Member` aggregate (H-05): identity, type assignment, fees, term
        │  (lifecycle EQ-4/EQ-9)       │
        │  Membership Standing ── OHS ─┼──┐  (PROPOSED published fact; E0b)
        └──────────────────────────────┘  │
                                           │ published facts only (no tables: P-01 FC-12)
   ┌───────────────────────────────────────▼───────────────────────────────────────┐
   │ ELECTION (tenant)                                                              │
   │  Population source:  IMPORTED_ELECTORATE ◄── affiliation   |  MEMBERSHIP_REGISTRY ◄── Membership OHS │
   │  Selection Contract (PROPOSED) → Registration → ElectionMembership (SM2)        │
   │  VoterAdmissionPolicy + Evidence (derived) → VotingEntitlement (incl. canVote)  │
   │  election_officers: Chief/Deputy (H-14)                                         │
   └───────────────────────────────────────┬───────────────────────────────────────┘
                                           │ cast (one-way)
                                   ┌───────▼────────┐
                                   │ BALLOT / VOTE  │  INV-ANON-1: no identity, no derivable key
                                   └────────────────┘
```

## 12. Final architecture diagram (after reconciliation; ordering per ADR)

```text
                           IDENTITY  (Person = users; global, internal)
                               │
                               ▼
                  ORGANISATION AFFILIATION  (EM-VOC-002; access & org roles)
                               │
             ┌─────────────────┴──────────────────┐
             ▼                                    ▼
   ELECTION-ONLY  (in force)            FULL MEMBERSHIP  (DEFERRED, EM-SEQ-001)
             │                                    │
   Imported Electorate                  Organisation Membership (= Member aggregate, EM-VOC-001)
   (affiliates; no Membership,                    │
    EM-EO-001…003)                      [Standing / Governance facts — E0b/E0c, PROPOSED]
             │                                    │
             └──────────────┬─────────────────────┘
                            ▼
           Population source  (voter_source_strategy snapshot — IMPLEMENTED; PBDIGIT-66 D-5 OPEN)
           + Selection criteria (PROPOSED)  →  Evaluation (PROPOSED; governs who MAY be registered)
                            │
                            ▼
                    REGISTRATION  ("admission" in EM-ENT-003; ADR §4 / G11)
                            │
                            ▼
                 ElectionMembership  {role, SM2: INVITED→ACTIVE⇄SUSPENDED→REMOVED}
                            │                 ▲
                            │                 └── Full Membership only: organisation-membership "removal"
                            │                     ⇒ automatic suspension, distinct cause (EM-FM-003/004; FM-5…10 OPEN)
                            ▼
              Voter Admission = policy.isSatisfiedBy(evidence)   — derived, never stored
                            │
                            ▼
      VotingEntitlement = registered ∧ voter ∧ active ∧ org ∧ ¬deleted ∧ admitted ∧ canVote()
                            │
                            ▼
                       CAST VOTE  (transaction)
                            │
                            ✗   persisted Vote: no PersonId · no MembershipId · no ElectionMembershipId
                                · no EvidenceId · no derivable key   (ADR-T11; PBDIGIT-223/226 pending)
```

---

## 13. Decision log (this reconciliation)

**No decision is taken here.** The log records (a) authoritative decisions **consumed**, and (b) **candidate decisions** submitted for an act.

| Decision ID | Decision | Rationale | Evidence | Owner | Date | Impact | Status |
|---|---|---|---|---|---|---|---|
| RD-01 | The ADR's election chain governs. The Selection Contract precedes **Registration** | H-10 is DECIDED and IMPLEMENTED | ADR §2, §4 | consumed (PO 2026-10-04) | 2026-10-04 | Blueprint corrected (C-3) | **DECIDED** (consumed) |
| RD-02 | Two population routes are mandatory | H-02, H-04 | EM-EO-*, EM-SEQ-* | consumed (PO 2026-08-12) | 2026-08-12 | C-6 | **DECIDED** (consumed) |
| RD-03 | "Organisation Membership" = the `Member` aggregate | H-05 | EM-VOC-001 | consumed | 2026-08-12 | C-1, C-2 | **DECIDED** (consumed) |
| CD-01 | Rename the concept to *Organisation Membership* in code and docs; "member" becomes a role | V4 FC-6/7 | C-1 | **PO** (terminology act) | — | vocabulary | **PROPOSED** → EQ-12 |
| CD-02 | Amend EM-VOC-001: type **assignment** + term inside, type **definition** + fee ledger outside | invariant analysis | C-2; discovery §3.3 | **PO** | — | aggregate | **PROPOSED** → EQ-11 |
| CD-03 | OrganisationAffiliation as the named concept for the affiliation tables | H-06 | §7 | **PO** (name) / ARB (context) | — | context map | **PROPOSED** → EQ-12 |
| CD-04 | "Organisation Statutes" for the tenant's rules | H-13 collision | §9 | **PO** | — | vocabulary | **PROPOSED** → EQ-13 |
| CD-05 | INV-TEN-1 (no cross-tenant relationship disclosure) | multi-tenancy | §10 | **ARB** | — | all contexts | **PROPOSED** → EQ-14 |
| CD-06 | No status value carries more than one dimension | §6 | V4 §8 | **ARB** | — | all contexts | **PROPOSED** |
| CD-07 | No persistence-level inference across contexts | Part 1 O-1; V4 FC-12 | review I-1 | **ARB** | — | Election ↔ Membership | **PROPOSED** |

**Traceability:** `ELECTION_MANIFESTO.md` §1, §2, §3 (EM-GOV-001…003), §4 (EM-VOC-001…003), §5, §6, §9 · `2026-08-12-election-membership-governance-decision-package.md:14-23` · `2026-09-16-election-membership-suspension-removal-governance-decision.md` · ADR_20261004_1549 (all) · ADR-T log `:18` (ADR-T11) · `PBDIGIT-66`, `-68`, `-213`, `-221`, `-223`, `-226`, `-241` · code: `VoterImportService.php:282-325`, `2026_05_19_000001…`, `2026_03_05_000002…`, `2026_03_05_000003…`, `Member.php:172-192`, `MembershipRenewalController.php:40-90`, `VoteStorageTrait.php:40-75`, `User.php:167`.
