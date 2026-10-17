We now have enough evidence to continue C3, but I want to establish a strict architectural boundary before you modify production code.

## C3 — AUTHORITATIVE VOTING ENTITLEMENT AND LEGACY `can_vote` BOUNDARY

### 1. Core architectural decision

The authoritative answer to:

> “May this voter vote in this election?”

is **`VotingEntitlementQuery`**.

There must be exactly one authoritative election-scoped entitlement decision.

The conceptual chain is:

Registration / ElectionMembership
→ Admission Requirements / Evidence
→ Voting Entitlement
→ final voting-action gate

`User.can_vote` must NOT remain an independent authorization authority.

Do NOT mechanically replace every `can_vote` occurrence with `VotingEntitlementQuery`. First classify each usage.

---

## 2. Meaning of `User.can_vote`

Treat the existing `User.can_vote` as **legacy persisted approval state**.

It may still exist during migration/compatibility, but:

- it must not override `VotingEntitlement`
- it must not bypass the Election-Only admission policy
- it must not be used as an election-independent source of truth for voting authorization
- setting `User.can_vote = true` must never be sufficient to make a voter entitled to vote in an election where admission requirements are unsatisfied

In particular, these legacy operations are potential bypasses and must be investigated:

- `BulkApproveVoters`
- `BulkDisapproveVoters`
- `VoterlistController::approveVoter`
- `VoterlistController::rejectVoter`
- `ElectionProcessController::bulkApproveVoters`
- `ElectionProcessController::bulkDisapproveVoters`

Do not redesign/delete them blindly. First establish their current callers/routes/tests and determine whether they are still operationally required.

---

## 3. `can_vote` versus `VotingEntitlement`

Keep this distinction explicit:

### VotingEntitlement

Answers:

> Is this voter currently entitled to vote in this specific election?

It considers the authoritative election-scoped conditions, including:

- registered ElectionMembership
- voter role
- organisation match
- active membership
- election lifecycle/votability
- applicable VoterAdmissionPolicy
- applicable AdmissionEvidence

### `can_vote`

Where the existing system requires a final voting-access boolean, it represents the **final result/gate**, not an independent grant.

It must never be manually set to `true` in a way that bypasses the entitlement decision.

Do not introduce a second competing `canVote()` authority.

---

## 4. `has_voted` remains separate

Do NOT put `has_voted` into the core `VotingEntitlement` domain decision.

A voter can remain entitled even after voting.

At the action boundary:

    VotingEntitlement == entitled
        AND
    has_voted == false
        AND
    any applicable voting-session/security conditions pass
        =>
    voter may cast a ballot

This preserves the distinction between:

- entitlement to vote
- ability to cast another ballot

---

## 5. `Code.can_vote_now` is NOT `User.can_vote`

Do not merge these concepts.

`Code.can_vote_now` belongs to the voting/code/session flow.

It is not the Election Admission Policy and it is not the authoritative VotingEntitlement.

Likewise:

- `VoterSlug` is a technical access/session mechanism
- `Code` is a verification/voting-session mechanism
- neither may independently grant election voting entitlement

A valid slug/code must never become a bypass of `VotingEntitlement`.

---

## 6. IP verification is NOT identity admission

The existing `VotingSecurityService` performs IP-related voting security checks.

Do NOT put IP verification into `VoterAdmissionPolicy`.

Identity admission v1 is specifically:

    OfficerIdentityVerification

IP restriction is a separate voting-security condition.

Therefore:

    AdmissionEvidence
        !=
    IP/security evidence

Keep the bounded contexts/concepts separate.

---

## 7. Critical `VotingSecurityService` rule

The existing service currently uses `User.can_vote` in:

- `canIssueVotingSlug()`
- `validateVoterEligibility()`

Do not simply replace those references mechanically.

Determine what each method is supposed to answer.

If it asks:

> “May this voter vote in this election?”

it must eventually rely on the authoritative election-scoped entitlement mechanism.

If it asks:

> “May a technical voting slug/session be issued?”

then it must still respect the authoritative entitlement and must not use `User.can_vote` as an independent bypass.

If it is purely an IP/security check, keep it separate.

---

## 8. Recovery slug

The existing recovery-slug path checks:

    !$user->is_voter || !$user->can_vote

This is a potential C3 bypass.

A recovery slug is a technical access mechanism, not a grant of voting entitlement.

A voter whose:

- admission evidence is revoked,
- membership is suspended,
- election is not votable,
- or other authoritative entitlement condition fails

must not regain voting access merely because an administrator generates a recovery slug.

Do not change this blindly. Add/adjust the C3 tests at the correct authorization boundary.

---

## 9. Emergency lockdown

Do not automatically merge emergency lockdown with Identity Suspension.

Keep these concepts separate unless the existing domain proves they are the same:

Identity Suspension:
    two-person suspension process
    →
    current admission effect revoked
    →
    re-verification required
    →
    reinstatement required
    →
    entitlement restored only when both conditions are satisfied

Emergency Security Lockdown:
    existing security incident mechanism

Investigate its current semantics and callers before modifying it.

---

## 10. Inertia shared `can_vote`

`HandleInertiaRequests` currently exposes:

    user.can_vote

This may remain temporarily as legacy UI state, but it is presentation data only.

The frontend MUST NOT use this shared property as the authoritative decision whether a voter may vote.

Where UI needs election-specific voting eligibility, use server-provided election-scoped entitlement/action state.

Never trust a Vue/HTTP/UI value for authorization.

---

# 11. Election scope is mandatory

Do not use:

    User::where('is_voter', true)

as the authoritative population for an election-specific entitlement decision.

For Election-Only voting, the authoritative target is:

    Election
      +
    ElectionMembership
      +
    user
      +
    election-scoped policy/evidence

The election determines the organisation/lifecycle/policy context.

---

# 12. Suspension semantics

Do NOT implement suspension as:

    User.can_vote = false

That would bypass the approved domain process.

Approved semantics:

    Suspend Identity
        ↓
    existing two-person suspension process
        ↓
    membership remains
        ↓
    current admission effect invalidated
        ↓
    current VotingEntitlement = denied
        ↓
    re-verification required
    AND
    reinstatement required
        ↓
    both satisfied
        ↓
    entitlement restored

Re-verification never reinstates membership.

Reinstatement never re-verifies identity.

Either order is allowed.

---

# 13. C3 test requirement

Before production implementation, create RED tests that prove the authoritative behavior.

At minimum:

1. Policy OFF → existing voter remains entitled.
2. Policy ON + no identity evidence → voter is not entitled.
3. Policy ON + active identity evidence → voter is entitled.
4. Policy ON + revoked evidence → voter is not entitled.
5. Suspended membership → not entitled.
6. Reinstatement alone after suspension → still not entitled if evidence is invalid/revoked.
7. Re-verification alone while suspended → still not entitled.
8. Reinstatement + valid verification → entitled.
9. Wrong election evidence cannot satisfy target election.
10. Wrong organisation cannot satisfy entitlement.
11. Non-voter membership cannot satisfy entitlement.
12. Removed/soft-deleted voter cannot be verified.
13. Valid voter slug cannot bypass denied VotingEntitlement.
14. Recovery slug cannot bypass denied VotingEntitlement.
15. `User.can_vote = true` cannot bypass a required-but-unsatisfied admission requirement.
16. `User.can_vote = false` must not incorrectly become the authoritative election decision where VotingEntitlement says the voter is entitled.
17. `Code.can_vote_now` remains a separate session condition.
18. `has_voted` remains separate from VotingEntitlement.
19. Only target election Chief/Deputy may create identity verification evidence.
20. Verification is allowed only in the approved lifecycle window.
21. UI/shared `can_vote` data cannot authorize voting.

---

# 14. `can_vote` final gate clarification

Use the following terminology consistently in C3 documentation/tests:

    VotingEntitlement
        =
    authoritative election-scoped entitlement decision

    can_vote
        =
    final voting-access result/gate at the legacy/action boundary

    has_voted
        =
    separate one-ballot/duplicate-vote condition

    Code.can_vote_now
        =
    voting/code-session state

    VoterSlug
        =
    technical access/session mechanism

Do not describe all of these as the same “gate”.

---

# 15. TDD process remains mandatory

Continue strictly:

RED
→ show RED result
→ implement minimum production change
→ GREEN
→ full regression
→ refactor only if justified

Do not weaken/delete existing tests merely to make C3 pass.

The previously approved exception remains:

`VoterVerificationEmitsEventsTest` may have fixture-only changes so its fixture satisfies D1/D3. Assertions remain unchanged.

The C2 `AdmissionPolicyC2BoundaryTest` is superseded by C3 enforcement tests as already approved.

---

# 16. Before modifying legacy code

For every `can_vote` / `can_vote_now` occurrence, classify it as exactly one of:

A. authoritative entitlement
B. final action gate
C. legacy persisted compatibility state
D. voting/session state
E. UI/presentation state
F. security constraint
G. obsolete/deprecated behavior

Record the classification before changing it.

If a usage cannot be classified confidently, STOP and report it instead of guessing.

---

## Final C3 architectural invariant

No route, controller, middleware, service, console command, slug, code, frontend flag, or persisted legacy `User.can_vote` value may provide a way to cast a ballot when the authoritative election-scoped `VotingEntitlement` says:

    NOT ENTITLED

The authoritative voting decision must be enforced server-side at the final voting boundary, preferably uncached inside the transaction as already specified in the C3 plan.

Continue now with the RED tests and report the classification/bypass findings before production implementation.