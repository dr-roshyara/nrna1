# ADR — Voter Admission Policy and Voting Entitlement (Election-Only, v1)

**Status: ✅ DECIDED.** The PO decisions D1–D6 and R3-1…R3-6 were made in the 2026-10-04 planning session. **Implementation is authorised for `PBDIGIT-213` only, in the slices C1–C4.**
**Created:** 2026-10-04 · **Work item:** `PBDIGIT-213` · **Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0), `adr/` beside the other PublicDigit ADRs.

**Evidence base (consumed, not restated):**
- `docs/publicdigit/reviews/2026-10-04-election-only-voter-admission-gate-investigation.md` (the investigation)
- `docs/publicdigit/reviews/2026-10-04-voter-admission-implementation-feasibility.md` (the feasibility review)
- `docs/publicdigit/brainstorming/voter_management/voter_verification-architecture.md` and `frontend_voter_management.md` (the PO briefs)
- the approved plan `docs/plans/20261004-1549-election-only-voter-admission-v1-plan.md`

> **What this ADR protects, in one line:** *Importing a voter registers an election-specific entitlement record; it does not by itself grant the right to cast a vote. Admission is determined by the election's voter-admission policy and its evidence. Voting entitlement is the final decision that combines registration, admission, exercisability and the election lifecycle.*

## 1 · Context (facts, at commit `9254cb410`)

- **Import makes the voter active immediately.** Election-Only import writes `ElectionMembership{role:voter, status:active}` straight away (`VoterImportService.php:302-312`).
- **Verify has no effect on voting.** It writes `voter_verifications`, but **no voting path reads it**. `Election::requiresVoterVerification()` has no callers, and the enforcement tests are skipped. An unverified imported voter can vote.
- **The voting-entitlement rule exists in five places, and they disagree.**
  - `User::isVoterInElection` checks the organisation scope.
  - The uncached branch of `EnsuresVoterMembership` (used by `vote.store` and `vote.verify`) does **not** check the organisation, and still depends on the ambient tenant scope.
  - `ElectionVotingController@show` reports `inactive`/`invited` voters as eligible.
  - `@start` blocks only `removed`.
- **The current entitlement cache cannot be invalidated by group.** The entitlement cache (`user.{u}.voter.v2.{e}`, 300 s) lives in the default `file` store, which has no tags.

## 2 · Decision

### 2.1 Model

```
ELECTION (owns voting-time entitlement — ADR-002 clarification (i), EM-ENT-001/007)
 ├─ VoterAdmissionPolicy ...... WHAT a registered voter must satisfy        {"requires":[…]}
 ├─ VerificationAuthority ..... WHO may create HUMAN-produced evidence (only where a human acts)
 └─ ElectionLifecycle ......... isLocked · canVote (existing SSOT)

ADMISSION EVIDENCE (voter_verifications) .... WHAT actually happened
AdmissionDecision = VoterAdmissionPolicy.isSatisfiedBy(AdmissionEvidence)   (empty policy ⇒ admitted)
VotingEntitlement  = registered ∧ role=voter ∧ status=active ∧ org(membership)=org(election) ∧ ¬deleted
                     ∧ AdmissionDecision.admitted ∧ ElectionLifecycle.canVote()
BALLOT PATH (unchanged, outside entitlement): trust gate · IP/device rules · one-ballot lock · anonymity
```

**Vocabulary (binding in code):**
- `VoterAdmissionPolicy`
- `AdmissionRequirement` (v1: `OFFICER_IDENTITY_VERIFICATION`)
- `AdmissionEvidence`
- `AdmissionDecision`
- `VerificationAuthority`
- `VotingEntitlement`

**Rejected names:**
- **No** GateEngine, GenericGate, GateFactory, GateRegistry or AbstractGate.
- "Gate" is **not** used in names, because `Contexts/Election/Domain/OperatingCore/Gate` already means the election-wide acceptance gates (EM-GOV-016).

**UI language:**
- "Voter Admission"
- "Identity verification required"
- "Verify Identity"
- "Suspend Identity"
- "Identity verified" / "Re-verification required" / "Not required"

### 2.2 Invariants (recorded verbatim)

1. **Registration ≠ Admission ≠ Voting Entitlement.**
2. **Suspension ≠ Removal.** A suspended voter stays in the election's voter registry.
3. **Verification Evidence ≠ Voting Entitlement.**
4. **Identity Verification ≠ IP/Device Verification.** IP/device data on evidence is audit metadata, never proof of identity. `voter_verification_mode` remains a separate voting-security setting and is untouched.
5. **The Election Admission Policy defines WHAT must be satisfied.**
6. **The Verification Authority defines WHO may create human verification evidence.** Authority is not a mandatory property of every requirement. Email/SMS/WhatsApp evidence is voter- or provider-produced, and eID evidence comes from an external identity provider.
7. **The Evidence records WHAT actually happened.**
8. **VotingEntitlement determines whether the voter may vote NOW.** It **composes** registration, voter role, membership status, organisation scope, the admission decision and the election lifecycle. It does **not own**:
   - the admission policy;
   - verification authority;
   - the suspension workflow;
   - IP/device security;
   - ballot anonymity.
9. **Historical fact ≠ current admission effect.**
   - The historical fact is "Chief X verified voter Y at time T". Its current admission effect is "this evidence currently satisfies the requirement".
   - `status = revoked` expresses the **loss of the current admission effect**, never the deletion of the fact.
   - v1 keeps one row per `(election_id, user_id)`, so a later re-verification overwrites the fields. This is a **technical limitation (gap G1), not protocol history**, and is not presented as such.
10. **The backend is authoritative.** The UI never authorises voting. Every voting path reaches the single `VotingEntitlement` decision.
11. **Admission is derived, never stored.** Changing the policy writes **no** membership rows. No `is_admitted` flag exists.

### 2.3 PO decisions

| # | Decision |
|---|---|
| **D1** | Identity verification may be recorded in **SetupAdministration · SetupNomination · ReadyForVoting · VotingActive**. It is denied in Draft, SubmittedForApproval, Approved, Rejected, Suspended, Counting, ResultsPublished and Archived. Verification during VotingActive is legitimate late admission or re-admission, not a bypass. A new operation `verify_voter_identity` with an explicit whitelist replaces `verify_vote` (whose only users were the two verification routes) |
| **D2** | *"Identity verification may be performed by the **Chief or Deputy Chief of the election**."* It is election-scoped. A global or organisation-wide officer role is never sufficient by itself. See §3 |
| **D3** | Evidence records `verified_by`, `verifier_role` (`ELECTION_CHIEF` / `ELECTION_DEPUTY_CHIEF`), `method` (`officer_attestation`), `verified_at`, notes, and the current effect (`status`, `revoked_by/at`, `revocation_reason`). The target must be a registered voter of **this** election |
| **D4** | The policy can be changed only in **Draft · SetupAdministration · SetupNomination**. It is **immutable from ReadyForVoting onward** (`ElectionLifecycle::isLocked()`). This is enforced in the application command **and** by a model `saving` guard (defence in depth); the frontend disabling is a convenience only |
| **D5** | A **confirmed** two-person suspension ends the current admission effect of the voter's identity evidence (`revocation_reason = suspension_confirmed`). Re-verification is then required |
| **D6** | One story, `PBDIGIT-213`, delivered as ADR + C1…C4. C3 is structured as C3.1–C3.5 |
| **R3-1** | The policy is a **primary product workflow**: a "Voter Admission" card on the election settings page with the "Identity verification required" ON/OFF toggle and its own **Save Admission Policy** action, which calls a dedicated endpoint and command |
| **R3-2** | "Affected voters" means *registered voters whose effective voting entitlement would change under the proposed policy*. v1 implements this only for `none ↔ OFFICER_IDENTITY_VERIFICATION` (G9) |
| **R3-3** | Voter management changes are **additive**. The legacy Verify button stays (labelled "Verify Identity") and is wired to the new command. An Admission column is added. **"Suspend Identity" is the business label of the existing two-person Propose-suspension action** |
| **R3-4** | **Suspension decision ≠ identity re-verification.** After a confirmed suspension, **both** reinstatement (existing approve → `status=active`) **and** re-verification are required, in either order. Verification never lifts a suspension |

### 2.4 Correction made in C1 (a security and business-rule correction, not a refactor)

The in-transaction entitlement check used by `vote.store` and `vote.verify` will now also require *membership organisation = election organisation*, derived from the election rather than from the ambient tenant (PBDIGIT-65/69, ADR-002 (i)). `ElectionVotingController@show` will report `inactive`/`invited` voters as not eligible.

### 2.5 Cache

- **What is cached:** only the registration + admission part.
- **The key is versioned by the policy:** `user.{u}.voter.v3.{e}.{policyFingerprint}`. A policy change produces a new key, so every earlier decision becomes unreachable without enumerating voters.
- **Single-key invalidation:** evidence changes (`VoterVerification`) and membership changes forget the single affected `(u, e)` key.
- **Never cached:** the lifecycle `canVote()`.
- **Authoritative check:** `vote.store` uses the uncached query inside its transaction.
- **Invariant:** no stale entitlement survives a policy or evidence change, in either direction.

## 3 · Verification authority: the Election Committee leadership (PO resolution of a terminology conflict)

There are three different things called "Election Committee" in the repository:
- **(1)** The Manifesto's independent **acceptance** Committee (EM-GOV-029/033, `OperatingCore\Committee\ElectionCommittee`): at least 3 seats, with no Chief or Deputy.
- **(2)** `organisation_participants.participant_type = election_committee`: an organisation-level participant category.
- **(3)** `election_officers` with the roles `chief`, `deputy` and `commissioner`. These are election-scoped (`election_id`, nullable for organisation-wide officers). The UI labels them DE "Vorsitzender / Stellvertreter / Beisitzer".

**PO decision:**
- The intended domain concept is the **Election Committee leadership**: *Election → Election Committee → Chief, Deputy Chief*.
- It is **not** the acceptance Committee (1), which must not be used as the verifier authority.
- v1 **obtains the authority technically** from the election-scoped `election_officers` rows (3): `status = active`, `election_id` = this election, role `chief` → Chief, `deputy` → Deputy Chief. `commissioner` and organisation-wide (`election_id NULL`) rows grant nothing.
- This sits behind the application port `VerificationAuthoritySource`, whose v1 adapter reads `election_officers`.
- **`election_officers` is not renamed or redefined as the Election Committee, and this ADR does not claim that the complete Election Committee domain exists.** Formalising it is follow-up **G10**. When that model exists, only the port's adapter changes.

## 4 · Terminology mapping: "admission" (PO decision: keep the names, record the mapping)

The adopted rule **EM-ENT-003** and the PO ruling **PBDIGIT-68** use *admission* for the act that **creates** the `ElectionMembership` (*"Admission creates an election-specific entitlement"*). PBDIGIT-68's "admission eligibility" is `VoterSourceStrategy`. Two different concepts currently share the word, so this ADR establishes the mapping:

> **Registration** is the act that creates the election membership (the registered voter record). It corresponds to the existing use of "admission" in EM-ENT-003 / PBDIGIT-68.
>
> **Voter Admission** in PBDIGIT-213 means the *subsequent* evaluation of whether an already registered voter satisfies the election's configured admission requirements, such as identity verification.
>
> This ADR does not redefine or rename the existing governance terminology. A future Product Owner terminology clarification may reconcile the wording of EM-ENT-003 with the implemented domain model.

| Concept | Meaning |
|---|---|
| **Registration** | Creates the voter's `ElectionMembership` (unchanged act: import, assign, bulk assign) |
| **Voter Admission** | Determines whether a registered voter satisfies the configured requirements |
| **Admission Evidence** | The proof or fact supporting a requirement |
| **Admission Decision** | Whether the requirements are currently satisfied |
| **Voting Entitlement** | The final answer: may this voter vote now? |

- **PBDIGIT-213 does not change the meaning of the existing registration/admission act.** It *introduces a post-registration admission evaluation*. `VoterAdmissionPolicy` never creates, restores or modifies an `ElectionMembership`. A reader must not infer otherwise from its name.
- The adopted entitlement rules are respected, not changed:
  - EM-ENT-002 (*existence ≠ exercisability*): admission is one input to exercisability.
  - EM-GOV-001 (*the election governs exercisability*): the policy is election-owned.
- **Terminology debt (PO follow-up; not a prerequisite for any slice):** reconcile the wording of EM-ENT-003 / PBDIGIT-68 with this model. Rewording the Manifesto or PBDIGIT-68 is deliberately **out of scope**, so that domain implementation and governance-vocabulary revision stay separate.
- **EM-OPEN-001**, *"after Election-Only admission, is the membership immediately ACTIVE?"*, is answered for this design: *Election-Only registration does not by itself make the voter entitled when the election's admission policy requires evidence*.

## 5 · Placement and constraints respected

- **Code placement:**
  - Domain (pure PHP, final): `app/Contexts/Elections/Domain`
  - Application: `app/Contexts/Elections/Application`
  - Infrastructure: `app/Contexts/Elections/Infrastructure`
  - This is beside the existing voter-registry code. The greenfield `Contexts/Election/OperatingCore` is not touched.
- **Policy placement:** the policy is **not** placed in `ElectionConstitution::RULES` or in the hashed security-articles snapshot. This respects the Principal Architect ruling of 2026-08-13 that voter-participation rules stay outside `ElectionConstitution`. It is an election-owned rule with its own immutability guard.
- **Anonymity (ADR-T11):** entitlement reads `election_memberships`, `voter_verifications` and `election_officers` only. It never reads `votes`, `results` or `codes`.

## 6 · Consequences

**Positive:**
- There is one authoritative entitlement rule.
- An unverified voter cannot vote when the policy requires verification.
- The election-scope gap in the in-transaction check is closed.
- Status manipulation (approve, assign-restore, re-import) cannot admit anyone, because admission is derived from evidence.
- Future mechanisms (email, phone, eID, OR-clauses) plug in as new requirements and evidence without redesigning `ElectionMembership` or the voting controllers.

**Negative / accepted:**
- **G1:** the single-row evidence overwrites earlier facts.
- **G2:** the `ProtocolAppend` port has no adapter, so evidence is audited through the file-based `ElectionAuditService` (with the subject voter and role recorded).
- **G3:** the one-actor reactivation paths remain. They can no longer admit anyone, but they still bypass two-person governance.
- **G9:** impact is calculated only for the v1 transition.
- **G10:** the Election Committee leadership is not yet modelled.

## 7 · Gaps raised (not in scope)

| Gap | Description |
|---|---|
| G1 | Immutable verification history |
| G2 | `ProtocolAppend` adapter |
| G3 | One-actor approve/suspend/assign-restore |
| G4 | `destroy` cross-election check; `bulkAssignVoters` crash |
| G5 | Legacy test fixture repairs |
| G6 | `voter_verification_mode` IP/device enforcement |
| G7 | Email/phone/eID mechanisms and OR-clauses |
| G8 | Full-membership adoption |
| G9 | Impact beyond `none ↔ OIV` |
| G10 | Election Committee leadership domain (Chief, Deputy Chief): lifecycle and appointment rules, and its relation to `election_officers` and EM-GOV-029 |
| G11 | Terminology debt: reconcile "admission" in EM-ENT-003 / PBDIGIT-68 (= Registration) with Voter Admission (§4). PO act |

## 8 · Traceability

- ADR-002 (+ clarification 2026-08-13)
- `ADR_20260807_1500_Election_Lifecycle_Single_Source_Of_Truth`
- Election Manifesto EM-ENT-001…007, EM-GOV-001/005/016/026/028/029, EM-OPEN-001/002/003/007
- PBDIGIT-65/69 (entitlement scope), PBDIGIT-68 (durable entitlement)
- The 2026-08-13 "Manifesto ≠ Constitution" ruling
