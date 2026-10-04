# Voter Admission & Voting Entitlement — review of the architect review, and implementation feasibility

**Date:** 2026-10-04 · **Type:** architecture review + implementation feasibility (read-only) · **Authority:** none. This is a recommendation, and no implementation is authorised. It is the input for EP-01 approval and the ADR.

**Inputs:**
- `2026-10-04-election-only-voter-admission-gate-investigation.md` (the *investigation*)
- the architect review of that investigation (the *review*, pasted by the PO 2026-10-04)

**Method:** I checked every claim in the review against the code at `9254cb410`. Nothing was modified.

> **FACT** = verified in code with a citation. **RECOMMENDATION** = my architectural judgement. Where I diverge from the review, I say so and give the evidence.

---

## 1. Verdict

**The review's architecture is implementable, mostly additively, at low-to-moderate risk.** I recommend approving it with **six refinements** (§3). Two of them correct facts the review could not see from the investigation alone:
1. **"Gate" is already a term in the Election context.** It names the two election-wide *acceptance gates* (committee two-thirds decisions, `EM-GOV-016`, `app/Contexts/Election/Domain/OperatingCore/Gate/*`). Calling voter admission a "gate" in code would give one word two meanings in the same context. Use **Admission Requirement** and **Admission Evidence** in code; "verification step" is fine in the UI.
2. **"Protocol-grade" evidence has no persistence home today.** The protocol port `ProtocolAppend` exists, but it has **no adapter** (no implementation in `Infrastructure/`, no binding). The only working audit is the file-based `ElectionAuditService`. Meeting the review's §16 requires a decision, not just a coding task (§3 R-5).

The review's strongest point (§7, `VotingEntitlement` as a named concept) is **better supported than the review knew**. The entitlement rule is implemented **four times today, and two of the copies disagree** (§2.3). That inconsistency alone justifies the refactor, even without admission.

---

## 2. Verified facts behind the decision

### 2.1 The entitlement check is spread across 4 places, called from 13 sites

| # | Implementation | Callers | Conditions |
|---|---|---|---|
| E1 | `User::isVoterInElection()` (`app/Models/User.php:316-337`), cached 300 s, key `user.{id}.voter.v2.{election}` | `EnsureElectionVoter.php:37`, `VoteEligibility.php:96`, and E2 when `$useCache=true` | role=voter ∧ status=active ∧ **org = election.org** ∧ ¬deleted · tenant scope **removed** (PBDIGIT-65/69) |
| E2 | `EnsuresVoterMembership::ensureVoterMembership()` uncached branch (`app/Traits/EnsuresVoterMembership.php:34-40`) | `VoteController.php:1586` (store, inside the transaction), `:2276` (verify) | role=voter ∧ status=active ∧ ¬deleted · ⚠ **no org check** · ⚠ **tenant scope still applied** |
| E2' | the same trait, cached branch → E1 | `CodeController.php:71,236,410,532`, `VoteController.php:234,475` | as E1 |
| E3 | `ElectionVotingController@start` (`:110-125`) | entry point | membership ∧ role=voter ∧ status≠**removed** ∧ ¬has_voted |

**Consequence (FACT):** the most security-critical check, E2 inside `store`, applies *different* scope rules from the gate the voter passed earlier (E1). E2 omits the organisation match and keeps the ambient tenant scope. PBDIGIT-65/69 forbade relying on that ambient scope at voting time (ADR-002 clarification (i)). Admission would have to be added to all four copies consistently, so unifying them is a precondition, not an option.

### 2.2 Where the lifecycle lock sits

`ElectionLifecycleEngineImpl::isLocked()` (`:295-304`) returns false for `Draft`, `SetupAdministration` and `SetupNomination`, and true from `ReadyForVoting` onward (and for `Suspended`). That is the natural *"admission policy becomes immutable"* point the review asks for (§14).

Two related gaps (FACT):
- `PATCH /settings` (`routes/election/electionRoutes.php:355`) has **no lifecycle guard**, only `can:manageSettings`. A guard for the new field must be added explicitly.
- Verification is allowed while `canVote || !isLocked` (`OperationCapabilityMapper.php:45`): in Draft, SetupAdministration, SetupNomination **and VotingActive**, but **not** ReadyForVoting. Admitting voters *during* voting is possible today. Whether that is wanted is a product question (Q3).

### 2.3 Existing building blocks to reuse (canonical discovery)

| Need | Already exists | Use |
|---|---|---|
| Per-election rule as a value object | `VoterSourceStrategy` (`app/Domain/Election/Enum/VoterSourceStrategy.php`), with `fromElection` and a persistence boundary | **Copy the pattern** for the admission policy |
| Gate evidence (officer identity attestation) | `VoterVerification` + `IdentityAttestedEvent` (Trust context) = ADR-002 *Verified* | **Reuse as Admission Evidence**; no new table |
| Voter-registry domain policy location | `App\Contexts\Elections\Domain\Policies\*` (`ElectionOnlyPolicy`, `VoterEligibilityPolicy`), handlers in `Contexts\Elections\Application\Handlers` | **Home for the new domain code** (see R-6 for the context question) |
| Lifecycle lock | `ElectionLifecycle::of($e)->...` / `isLocked` | **Consume it**; don't add a column |
| Protocol | `ProtocolAppend` port (`Contexts/Election/Domain/OperatingCore/Port`), **no adapter** | Cannot be consumed yet (R-5) |
| Architecture guards | `tests/Architecture/*` (domain must not import Illuminate, `VocabularyProhibitionTest` applies only to `Security/Simplified/Interpretation`) | No conflict with the proposed names |

---

## 3. Review of the review: point by point

| Review § | Claim | Assessment | Evidence / refinement |
|---|---|---|---|
| 1, 2 | Registration ≠ Admission ≠ Exercisability; `status` conflates them | **AGREE** | investigation §5/§7 |
| 3 | No generic gate table yet; `VoterVerification` is the first gate's evidence | **AGREE** | §2.3 |
| 4, 18 | Design the language for several gates, but don't encode combinations as enum values | **AGREE + make concrete (R-1)** | — |
| 5 | Admission = ALL(required).passed | **AGREE** | An empty requirement set = admitted (today's behaviour) |
| 6 | Admission derived, not a status | **AGREE** | It also neutralises the reactivation bypasses: approve or assign-restore can't admit (investigation S3/S4) |
| 7, 17 | Named `VotingEntitlement` as the single decision | **AGREE — strengthened** | §2.1: four implementations, two diverging |
| 8 | Admission ≠ Exercisability | **AGREE** | — |
| 8, 17 | "NotSuspended" as its own dimension | **REFINE (R-2)** | Separating suspension from `status` is `EM-OPEN-003` (open D-item). Phase 1 must use `status` as it is |
| 10 | Name the gate `OfficerIdentityVerification` | **AGREE on precision, REFINE the noun (R-3)** | "Gate" collides with `OperatingCore/Gate` (acceptance gates) |
| 11 | Keep IP/device binding separate | **AGREE** | `voter_verification_mode` stays a binding rule. Enforcing it is a separate backlog item |
| 12 | `VoterAdmissionPolicy` outside `ElectionConstitution` | **AGREE** | It follows the 2026-08-13 ruling and needs no ADR to overturn it. This closes investigation Q2 |
| 13 | Election owns the rule; Election+Voter owns the evidence | **AGREE** | `voter_verifications` is keyed by `(election_id,user_id)`. Keep that, and add a *registered-target* validation (R-4) |
| 14 | Mutable during administration, immutable at a lifecycle point | **AGREE + make concrete (R-4)** | Lock = `isLocked()` (§2.2); a guard must be added to settings PATCH |
| 15 | Existing voters become "registered, not admitted" | **AGREE** | This is a product decision; the UI shows the impact before confirming |
| 16 | Protocol-grade evidence (who/when/rule/subject) | **AGREE in principle, NOT directly implementable (R-5)** | No protocol adapter. Re-verify overwrites the row. The file audit omits the subject |
| 17 | `CanCastVote` includes ballot-security rules and "not already voted" | **PARTIALLY DISAGREE (R-2)** | See below |
| 19 | Replace the "≥3 gates" threshold with qualitative criteria | **AGREE** | I withdraw the numeric threshold from the investigation |
| close | Write a "Domain Model v0.1" before the ADR | **AGREE, with a constraint** | It is the ADR's model section (Operating Loop phases 2–4), not a new document type (the methodology is frozen). §5 below is the draft |

### Refinements

**R-1 — The policy is a set of requirements, not an enum of combinations.**
- Domain: `VoterAdmissionPolicy` holds a **set** of `AdmissionRequirement`, which today has one case: `OfficerIdentityVerification`.
- Persistence: a JSON column `elections.voter_admission_policy` = `{"requires":["officer_identity_verification"]}`, with default `{"requires":[]}`.
- This costs the same as a string column now, and it means gate 2 needs no schema or enum migration later. The reviewer's §4 concern is met without speculative machinery.

**R-2 — `VotingEntitlement` decides the person's right, not the ballot.**
- Conditions 1–7 of the review's §17 (registration, role, not removed, exercisable status, admission, organisation scope, `ElectionLifecycle::canVote()`) belong in it.
- Conditions 8–9 should stay in the ballot path:
  - **8, ballot security:** IP, trust gate, `LegitimacyOutcome`.
  - **9, one ballot:** `codes.has_voted`, `voter_slugs`.
- Two reasons:
  - **(a) Anonymity (ADR-T11).** Condition 9 is enforced on codes and slugs precisely so that the person-level record never touches anything vote-related. `ElectionMembership.has_voted` is never written (`markAsVoted()` has no callers). Pulling condition 9 into the entitlement would push towards writing it, which is an anonymity-adjacent design change needing its own decision.
  - **(b) Scope.** Folding the trust gate in means refactoring `VoteController::store` (3,000+ lines), which is not justified by admission.
- So the final check in `store` is `VotingEntitlement` **and** the existing ballot-security checks **and** the existing one-ballot check. "All roads to one decision" still holds for the *right to vote*.
- Suspension: phase 1 reads the existing `status`. Separating it is left to `EM-OPEN-003`.

**R-3 — Vocabulary.**
- Code: `VoterAdmissionPolicy`, `AdmissionRequirement::OfficerIdentityVerification`, `AdmissionEvidence`, `VotingEntitlement`, `VotingEntitlementDecision`.
- Avoid `Gate`/`VoterAdmissionGate` in class names while `OperatingCore\Gate` means acceptance gates.
- Docs/UI: "admission requirement", "verification step".
- The Manifesto should get a vocabulary entry so the two meanings never meet.

**R-4 — Lock and target rules, made concrete.**
- The policy can be changed only while `!isLocked` (Draft / SetupAdministration / SetupNomination).
- Enforcement is in the domain/application command, not only in the route.
- Every change is audited, and the UI shows the impact before confirming ("N registered voters will need verification").
- The verify command only accepts a user who has an `ElectionMembership(role=voter)` in *this* election. This fixes investigation S6.

**R-5 — Protocol-grade evidence needs one decision.**

Today: re-verify **overwrites** the single row, which is unique on `(election_id,user_id)` (migration `2026_04_12_000003`). The file audit records the officer but **not the subject voter**.

Options:
- **(a) Minimal.** Keep the row as current-state evidence; fix `ElectionAuditService` entries to carry `subject_user_id`, the requirement, and the policy version. This is file-based, as today.
- **(b) Proper.** Build the `ProtocolAppend` adapter and record `VoterAdmissionEvidenceRecorded` / `VoterAdmissionEvidenceRevoked` as protocol entries. This is the canonical home, but it means wiring a port no feature uses yet, so it is a larger story.
- **(c) Local history.** An append-only `voter_verification_history` table. ❌ This would create a second protocol, which canonical-discovery rules forbid.

**Recommendation: (a) in this epic, plus a backlog item for (b).** Governance must confirm that file-based audit is acceptable as interim evidence under `EM-GOV-005` (Q4).

**R-6 — Which context.**
- There are two: `App\Contexts\Elections` (plural; voter registry: `AssignVoterHandler`, `ElectionOnlyPolicy`) and `App\Contexts\Election` (singular; greenfield Operating Core: committee and acceptance gates).
- ADR-002 clarification (i) assigns voting-time entitlement to *"the Election context"* without naming the folder.
- **Recommendation:** put admission and entitlement in `Contexts\Elections`, where registration already lives, and do not touch the Operating Core. Record this in the ADR (Q5).

---

## 4. Feasibility assessment

| Concern | Assessment |
|---|---|
| **Additive?** | Yes: one new column, new pure-PHP domain classes, one infra query, and four existing check sites re-pointed with their signatures kept |
| **Behaviour change for existing elections** | None. The default empty policy means admitted = registered. **Exception:** unifying E2 adds the organisation check to the in-transaction path (stricter, and aligned with PBDIGIT-65/69). It needs its own pin test |
| **Anonymity** | Unaffected. The entitlement reads `election_memberships` + `voter_verifications` (person ↔ election), never `votes`/`results`/`codes` |
| **Tenant isolation** | Better than today. One query, scope derived from the election (ADR-002 (i)); tests use two organisations |
| **Performance** | One extra `EXISTS` on `voter_verifications(election_id,user_id,status)`, only when the policy is non-empty. The cache stays, with key `v3` |
| **Cache correctness** | **Main technical risk.** E1 caches 300 s and is invalidated only by `ElectionMembership` events (`ElectionMembership.php:255-259`). It must also be invalidated by `VoterVerification` saved/deleted and by policy changes. The uncached path in `store` remains the final guard |
| **Demo elections** | Unchanged (bypass at the trait and middleware) |
| **Layering (CLAUDE.md rules)** | Domain classes are pure PHP and final. The application command gets a DTO. Eloquent stays in the infra query. Route-model binding is not used for writes |
| **Test harness** | ⚠ A blocker for RED. The entitlement and middleware suites currently fail on fixtures (investigation §12 clusters A–D). They must be repaired first as a separate story |

**Conclusion: FEASIBLE.** The risk sits in the cache and the four-way unification, not in the admission rule itself.

---

## 5. Target model (draft for the ADR's model section)

```
Contexts\Elections\Domain                         (pure PHP — no Illuminate)
├── ValueObjects\AdmissionRequirement             enum: OfficerIdentityVerification
├── ValueObjects\VoterAdmissionPolicy             final readonly; set<AdmissionRequirement>
│       none() · fromPersistence(array) · toPersistence() · requires(r) · isEmpty()
│       isSatisfiedBy(AdmissionEvidence): bool          ← ALL(required) ⊆ evidence
├── ValueObjects\AdmissionEvidence                set<AdmissionRequirement> satisfied for (election, voter)
├── ValueObjects\VotingEntitlementDecision        entitled: bool + reason enum
│       NotRegistered · NotVoterRole · Removed · NotExercisable(status) · OrganisationMismatch
│       · NotAdmitted(missing requirements) · ElectionNotVotable
└── Policies\VotingEntitlementPolicy              final; pure decide(RegistrationFacts, VoterAdmissionPolicy,
                                                  AdmissionEvidence, bool electionVotable): VotingEntitlementDecision

Contexts\Elections\Application
├── Ports\VotingEntitlementQuery                  interface: decide(userId, electionId): VotingEntitlementDecision
├── Commands\RecordOfficerIdentityVerification    DTO (electionId, voterId, officerId, binding?, notes?)
├── Handlers\RecordOfficerIdentityVerificationHandler   registered-target check → VoterVerification upsert → audit
├── Commands\ChangeVoterAdmissionPolicy (+Handler)      lock check (ElectionLifecycle) → persist → audit (+ impact count)

Contexts\Elections\Infrastructure
└── Queries\EloquentVotingEntitlementQuery        ONE query: membership (scope from election, tenant scope removed,
                                                  SoftDeletes kept) + verification EXISTS + lifecycle canVote()
    CachedVotingEntitlementQuery (decorator)      key user.{u}.voter.v3.{e}; invalidated by ElectionMembership,
                                                  VoterVerification and policy-change events

Rule (Election)    elections.voter_admission_policy  (jsonb, default {"requires":[]})
Evidence (E+V)     voter_verifications (status=active)              — unchanged schema
Decision (E+V)     VotingEntitlement                                — derived, never stored
```

**Sequence: vote store (final check)**

```
VoteController::store ──► DB::transaction
   ├─ trust gate (unchanged)                       ballot security
   ├─ VotingEntitlementQuery::decide(uncached)      registration ∧ admission ∧ exercisable ∧ org ∧ votable
   │     └─ denied → rollback + reason logged (voting_security)
   ├─ codes.has_voted lock check (unchanged)       one ballot
   └─ persist anonymous vote (unchanged)
```

**Sequence: officer verification**

```
Manage page ─► POST voters/verify ─► controller (authorize manageSettings → chief|deputy of THIS election)
   ─► RecordOfficerIdentityVerificationHandler
        ├─ ElectionMembership(role=voter, election) exists?  else DomainException (S6 fix)
        ├─ VoterVerification upsert (status=active)
        ├─ IdentityAttestedEvent (unchanged)
        ├─ audit: voter_admission_evidence_recorded {subject_user_id, officer_id, requirement, policy}
        └─ cache invalidation (VoterVerification::booted)
```

**Adoptable sentence (the review's own wording, kept):**
> *Importing a voter registers an election-specific voting entitlement candidate; it does not necessarily grant the right to exercise the vote. Admission is determined by the election's voter-admission policy and its required admission evidence. Voting entitlement is the final domain decision that combines registration, admission, exercisability and the election lifecycle.*

---

## 6. Implementation plan: slices

Each slice is one story (`PBDIGIT-nn`), one commit, RED → GREEN, a developer guide under `developer_guide/voter_admission/`, and a session log. None is authorised by this report.

| Slice | Content | Behaviour change | Size |
|---|---|---|---|
| **S0 — Harness repair** *(prerequisite, separate story)* | Fix fixture clusters A–D: duplicate `user_organisation_roles` (factory vs `attach`), stale `assignVoter/bulkAssignVoters`, officer vs membership authority in the verification tests, factory state/strategy. Unskip nothing | none (tests only) | S |
| **S1 — Unify `VotingEntitlement`** | Domain policy + decision VO; `EloquentVotingEntitlementQuery` + cached decorator; E1, E2 and E3 delegate to it with signatures kept; key `v3` | **only** the stricter org check in the in-transaction path | M |
| **S2 — Admission policy** | `AdmissionRequirement`, `VoterAdmissionPolicy`; migration (jsonb, default empty); Election accessor via VO; `ChangeVoterAdmissionPolicy` with lifecycle lock + audit; selector in election settings (read-only when locked), impact count | none (default empty) | M |
| **S3 — Enforce admission** | `AdmissionEvidence` built from `voter_verifications`; conjunct in the policy; invalidation from `VoterVerification`; denial reason surfaced on the entry page | **yes**, for elections that opt in | S |
| **S4 — Verification as evidence** | `RecordOfficerIdentityVerification` handler; registered-target rule; audit with subject; revoke exposed in the UI; IP/device optional when the purpose is admission (subject to Q2) | stricter verify input | S–M |
| **S5 — Manage page** | Backend-computed "Admitted / Awaiting verification" per row plus a filter; stats line; `npm run design-check` | display only | S |

**Explicitly not in this epic** (they become separate backlog items, per the End-of-Commission rule):
- Investigation S3/S4/S5: `approve` reactivation, assign-restore, the one-actor `suspend`.
- S7: `destroy` has no election check.
- The `bulkAssignVoters` crash in the full-membership import.
- Enforcing `voter_verification_mode` IP/device binding (the skipped tests).
- The `ProtocolAppend` adapter (R-5b).
- Suspension as a separate dimension (`EM-OPEN-003`).

---

## 7. Test strategy

**Unit (domain, pure PHP)**
- `VoterAdmissionPolicy`: an empty set is satisfied by anything; one required requirement is satisfied only when present; there are no duplicates; persistence round-trips; an unknown requirement is rejected.
- `VotingEntitlementPolicy`: a table test over {registered, role, status ∈ active/inactive/invited/removed, org match, admitted, votable}, with exactly one denial reason per failing row.

**Feature: entitlement (S1 pins, S3 RED)**
- Policy empty → today's behaviour at `code.create`, `vote.create` and `vote.store` (regression pin).
- Policy requires verification ∧ no evidence → denied at all three; `store` is tested **uncached** and must roll back.
- Evidence active → allowed. Evidence revoked → denied at the next step, which proves cache invalidation.
- Membership `invited` / `inactive` / `removed` / soft-deleted → denied (these pins are missing today).
- Org mismatch at `store` → denied (the E2 divergence fixed in S1).

**Feature: policy (S2)**
- Chief or deputy can change it while `!isLocked`; it is refused in `ReadyForVoting`+ and `Suspended`; other officer roles are refused.
- Every change is audited.
- The impact count is correct.

**Feature: verification (S4)**
- Positive tests for chief and for deputy; a negative test for an officer of another election.
- Non-registered target → rejected.
- The audit entry contains the subject.
- Re-verify after revoke → admitted again.

**Bypass regression**
- With the policy requiring verification, `approve`, assign-by-UUID and re-import each leave an unverified voter **not entitled**. Admission is derived, so these pass without touching those endpoints.

**Tenant isolation (CLAUDE.md Rule 9)**
- Two organisations × two elections: evidence in one admits nothing in the other, under every ambient session context.

**Architecture fitness**
- The new domain classes import no `Illuminate\*`.
- No `where('status','active')` on `election_memberships` in `app/Http` or `app/Traits` (a grep-based architecture test). This keeps the four copies from growing back.

---

## 8. Migration and backward compatibility

- **Migration:** the column `voter_admission_policy jsonb not null default '{"requires":[]}'`. No back-fill, and no rewrite of `election_memberships` or `voter_verifications`.
- **Existing elections** (including `onf-2-f8ba4321`): empty policy → unchanged.
- **Opting in while unlocked:** existing registered voters become *not admitted* until verified. For `onf-2` that is 0 of 5 admitted, because no verifications exist. The impact is shown before confirming (review §15, investigation §14).
- Existing `active` verifications count as evidence.
- **Rollback:** setting the policy back to empty restores the old behaviour while unlocked. The schema rollback drops one column.

---

## 9. Risks

| Risk | Mitigation |
|---|---|
| Stale cache admits a revoked voter for up to 300 s at early steps | Invalidate on `VoterVerification` and policy events; the uncached check in `store` is authoritative |
| Unifying E1/E2/E3 changes behaviour beyond admission | S1 is behaviour-preserving except the documented org check; pin tests land first |
| "Gate" vocabulary collision | R-3 naming + a Manifesto vocabulary entry |
| Protocol evidence judged insufficient | Q4 decided before S4; R-5b as backlog |
| Admitting voters during VotingActive (verify route allows it) | Q3; if forbidden, change the capability mapping in S4 |
| Test harness red → RED tests meaningless | S0 first |

---

## 10. Decisions needed before EP-01 approval

1. **`EM-OPEN-001`**: adopt *"Election-Only registration does not admit when the election's admission policy requires evidence"* (INV-ADM-1…8 from the investigation, with R-2 applied to INV-ADM-3).
2. **Evidence meaning**: is officer verification identity-only, or must it also bind an IP/device? Today the UI forces an IP. (Recommendation: identity-only for admission; binding stays with `voter_verification_mode`.)
3. **Admission timing**: may officers admit voters during `VotingActive`, which the current mapping allows?
4. **Protocol**: is file-based audit acceptable interim evidence under `EM-GOV-005` (R-5a), with the `ProtocolAppend` adapter as follow-up?
5. **Context**: should admission and entitlement live in `Contexts\Elections` (R-6)?
6. **Lock point**: is the lock `isLocked()` (from `ReadyForVoting`), as recommended, or earlier (end of `SetupAdministration`)?

Once these are answered: ADR *"Voter Admission Policy and Voting Entitlement"* (model = §5) → S0 → S1 … S5.

---

**Traceability:**
- Investigation `2026-10-04-election-only-voter-admission-gate-investigation.md`.
- ADR-002 + clarification (i)/(ii).
- Manifesto `EM-ENT-001…007`, `EM-GOV-001/005/016`, `EM-OPEN-001/003/007`.
- The 2026-08-13 "Manifesto ≠ Constitution" ruling.
- PBDIGIT-65/69.
- Code at `9254cb410`: `User.php:316-337`, `EnsuresVoterMembership.php:23-60`, `ElectionVotingController.php:110-125`, `ElectionLifecycleEngineImpl.php:295-304`, `OperationCapabilityMapper.php:45`, `electionRoutes.php:355`, `ElectionMembership.php:255-259`, `Contexts/Election/Domain/OperatingCore/Gate/*`, `Contexts/Election/Domain/OperatingCore/Port/ProtocolAppend.php`.
