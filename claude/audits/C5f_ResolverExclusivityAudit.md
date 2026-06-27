# C.5f — Resolver Exclusivity Audit

**Status:** COMPLETE
**Date:** 2026-05-28
**Phase:** C.5 — Sovereignty Stabilization Barrier
**Protocol:** Read-only sovereign archaeology (zero production code changes)

---

## Audit Purpose

Verify that ALL sovereign legitimacy derivation flows exclusively through
PolicySequence — the constitutional resolver. Any code path that produces
allow/deny/block/approve/authorize decisions outside PolicySequence is a
resolver exclusivity violation.

**Invariant:**
```
No code path derives sovereign legitimacy
outside constitutional resolver topology.
```

---

## Audit Methodology

1. Searched all directories in `app/` for legitimacy vocabulary (`canVote*`,
   `isAllowed*`, `deny*`, `block*`, `approve*`, `authorize*`, `trust*`,
   `legitimacy*`)
2. Verified each result against the constitutional resolver boundary:
   - Delegation to `PolicySequence` or `ElectionLifecycle::canVote()` = PASS
   - Independent allow/deny/block decision = FAIL
   - Telemetry-only usage with no enforcement = BORDERLINE (documented)

---

## Findings

### PASS — Delegates to Constitutional Path

| File | Mechanism | Notes |
|------|-----------|-------|
| `app/Http/Middleware/EnsureVotingActive.php` | Calls `ElectionLifecycle::canVote()` | Delegates to constitutional lifecycle |
| `app/Http/Middleware/VoteEligibility.php` | Calls `ElectionLifecycle::canVote()` | Same delegation pattern |
| `app/Application/Election/Security/Overlays/` (7 files) | Returns `OverlaySignal` only | Observational, not sovereign |
| `app/Application/Election/Security/Policies/` (3 files) | Returns `ConstitutionalFinding` only | Classification, not enforcement |
| `app/Http/Controllers/Demo/DemoVoteController.php` | No legitimacy derivation | Pass through only |
| `app/Jobs/` | No matching patterns | Empty |
| `app/Console/Commands/` | Admin commands only | BulkApprove is admin, not sovereign |

---

### FAIL — Resolver Exclusivity Violations

#### F-1: ValidateVotingIp Middleware

**File:** `app/Http/Middleware/ValidateVotingIp.php`
**Route alias:** `validate.voting.ip`
**Registration:** `bootstrap/app.php:91`

**Violation:** This middleware directly blocks requests based on IP mismatch.
It executes as route middleware on `v/{vslug}` prefix group at line 502 of
`routes/election/electionRoutes.php`, BEFORE the controller action invokes
`TrustPolicyEvaluator::evaluate()`.

**Criticality:** SOVEREIGN

**Key code paths:**
- Line 76-84: Blocks with `handleIpMismatch()` when IP doesn't match
- Line 84: `return back()->withErrors(...)` — terminates the request
- Line 102-126: Legacy blocking logic still fully intact

**Current mitigation (D.0.1):** `VOTING_CONSTITUTIONAL_MODE=true` shadow mode
at line 78 passes through instead of blocking. But the blocking code path
at line 84 still exists and is reached when the flag is false (default).

**Topology issue:** The middleware is registered in the route middleware
stack BEFORE the controller. The constitutional resolver runs inside the
controller. Middleware stack order creates de facto sovereignty:
```
vote.eligibility → validate.voting.ip → ... → controller
                                                ↓
                                        TrustPolicyEvaluator
```
`validate.voting.ip` can block the request at line 84 before
`TrustPolicyEvaluator::evaluate()` ever executes. Even in shadow mode,
the middleware still intercepts the request — it just chooses to pass through.

**Retirement path:** D.0.3 (staged — constitutional primary, then passive
fallback, then retirement). Must remain as rollback infrastructure until
D.0.3e.

---

#### F-2: VotingSecurityService

**File:** `app/Services/VotingSecurityService.php`

**Violation:** Contains multiple methods that perform direct sovereignty
derivation — producing `can_vote` allow/deny decisions entirely outside
the constitutional resolver topology.

**Criticality:** HIGH

**Sovereignty methods:**

| Method | Line | Decision | Mechanism |
|--------|------|----------|-----------|
| `canVoteFromIp()` | 28 | `can_vote = true/false` | Compares `$user->voting_ip` to `$currentIp` |
| `getIpAuditTrail()` | 84 | `can_vote_from_current_ip` key | Same comparison, different entry point |
| `validateVoterEligibility()` | 250 | `eligible: true/false` | Multi-factor eligibility gate |

**Retirement path:** The plan defers VotingSecurityService retirement to D.6
(required for forensics through the deletion window). This is acceptable
for C.5 certification IF the service is NOT used for active enforcement
on the constitutional enforcement path.

**Current usage verification:**
- `canVoteFromIp()` — called from controllers and middleware for IP checks.
  In shadow mode (D.0.1), these calls coexist with constitutional evaluation
  but do not determine the final outcome.
- `validateVoterEligibility()` — called from admin commands and some
  controller paths. Not on the primary voting path.
- The service is legacy procedural infrastructure, not constitutional
  enforcement topology.

---

#### F-3: VoteController Creates LegitimacyOutcome (Borderline)

**File:** `app/Http/Controllers/VoteController.php:1552`

**Pattern:**
```php
$constitutionalOutcome = LegitimacyOutcome::fromTrustState(
    $trustEnvelope->result->evaluationState
);
```

**Issue:** The controller invokes `LegitimacyOutcome` directly rather than
receiving it from a resolver. This breaks the spirit of resolver exclusivity
because a controller is deriving constitutional semantics.

**Current exception:** The F4 fitness function explicitly approves this as a
telemetry-only exception. The `LegitimacyOutcome` is used only for
divergence observation logging (`trackSovereigntyDivergence()`), not for
enforcement.

**Criticality:** LOW (with F4 exception approval documented)

**Retirement path:** Must be replaced by `ConstitutionalLegitimacyDecision`
when that class is implemented (target: D.0.3a).

---

### F-4: ConstitutionalLegitimacyDecision Does Not Exist

**File:** Does not exist

**Violation:** The F4 fitness function (`SovereigntyConvergenceFitnessFunction.php`)
references `ConstitutionalLegitimacyDecision` as the exclusive class for
legitimacy derivation. This class has never been implemented.

**Criticality:** HIGH — this is an architectural gap, not a code violation

**Impact:** There is currently no exclusive resolver authority class.
F4 tests pass because they check that nothing OUTSIDE the resolver derives
legitimacy, but they cannot verify that the resolver IS the exclusive
authority because the designated class does not exist.

**Retirement path:** Must be implemented as part of D.0.3a (constitutional
primary enforcement mode) to establish true resolver exclusivity.

---

## Summary Table

| ID | Violation | File | Criticality | Retirement Target |
|----|-----------|------|-------------|-------------------|
| F-1 | Direct request blocking via middleware order | `ValidateVotingIp.php` | SOVEREIGN | D.0.3a-e (staged) |
| F-2 | Legacy sovereignty derivation methods | `VotingSecurityService.php` | HIGH | D.6 (forensics) |
| F-3 | Controller-level LegitimacyOutcome creation | `VoteController.php:1552` | LOW | D.0.3a (approved F4 exception) |
| F-4 | ConstitutionalLegitimacyDecision class missing | N/A (not implemented) | HIGH | D.0.3a |

---

## Phase D.0.3 Implications

For D.0.3a (constitutional primary enforcement mode), the following
must be true:

1. **Middleware must not block** — `ValidateVotingIp` must remain in
   shadow-only pass-through mode. The `VOTING_CONSTITUTIONAL_MODE=true`
   flag must be the default (it is currently `false` by default).

2. **ConstitutionalLegitimacyDecision must exist** — The resolver
   exclusivity authority class must be implemented. Without it, there is
   no single class that can be verified as the exclusive legitimacy source.

3. **VoteController::fromTrustState() must be replaced** — The inline
   `LegitimacyOutcome::fromTrustState()` call at line 1552 must delegate
   to `ConstitutionalLegitimacyDecision` instead.

These are requirements for D.0.3a, not blocking conditions for C.5
certification — as long as the existing mitigations (D.0.1 shadow mode,
F4 telemetry exception) remain in place for the C.5 observation window.
