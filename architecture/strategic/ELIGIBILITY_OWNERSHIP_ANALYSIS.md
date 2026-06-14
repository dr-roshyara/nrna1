# Eligibility Ownership Analysis

**Date:** 2026-06-13  
**Phase:** Round 6B — Evidence Register review + Eligibility trace  
**Status:** Complete

## 1. Where Eligibility Is Evaluated

### Location A: EligibilityEvaluator (Domain/Voting/)

```php
class EligibilityEvaluator {
    public function evaluate(EligibilitySnapshot $snapshot): array
    {
        return $snapshot->eligibleVoters();
    }
}
```

**Delegates entirely to the snapshot.** The evaluator itself contains no rules — it reads a pre-computed snapshot.

### Location B: TrustPolicyEvaluator::buildEligibilityEvidence() (Application/Election/Security)

```php
private function buildEligibilityEvidence(?Election $election, ?User $user): ?ParticipationEligibilityEvidence
{
    $membership = ElectionMembership::withoutGlobalScopes()
        ->where('election_id', $election->id)
        ->where('user_id', $user->id)
        ->first();
    // Checks: status === 'active', expires_at > now, role === 'voter', suspension_status
}
```

**Queries ElectionMembership directly.** This runs inside the trust evaluation pipeline.

### Location C: ConstitutionalTransitionGuard::isPreconditionMet() (Application/Election/Services)

```php
'has_voters' => $election->voters()->exists() || $election->memberships()->where('role', 'voter')->where('status', 'active')->exists(),
```

**Queries Election + ElectionMembership** as a precondition for lifecycle transitions.

## 2. The EligibilitySnapshot

Owned by: `Domain/Voting/ValueObject/EligibilitySnapshot.php`

The snapshot is consumed by `EligibilityEvaluator` but its origin is unclear — no code was found that constructs or publishes it from within the Voting context. This suggests it is **created upstream** (by Election Governance) and passed to Voting.

## 3. Ownership Conclusion

| Who | What They Own | Evidence |
|-----|--------------|----------|
| **Election Governance** | Deterministic eligibility **definition** (who is a voter, what status is required) | Guard enforces `has_voters` precondition using membership data |
| **Election Governance** | Eligibility **evidence** (frozen participation evidence at evaluation time) | `TrustPolicyEvaluator::buildEligibilityEvidence()` queries `ElectionMembership` |
| **Election Governance** | Eligibility **facts** (voter list, status, expiration) | `ElectionMembership` model under Governance context |
| **Voting** | Eligibility **consumption** (filter ballots by eligible voters) | `EligibilityEvaluator::evaluate(EligibilitySnapshot)` |
| **Voting** | `EligibilitySnapshot` — consumed but not created by Voting | No factory/mutation code found in Voting context |

**Verdict: Election Governance owns eligibility.** Voting consumes a pre-computed snapshot. The relationship is:

```
Election Governance
    └── publishes EligibilitySnapshot (or provides data to build it)
    └── determines who is eligible (via Membership + Trust)
    └── freezes eligibility evidence for replay verification
                ↓
Voting
    └── consumes EligibilitySnapshot
    └── filters ballots to eligible voters only
```

## 4. Impact on Bounded Context Classification

| Finding | Impact |
|---------|--------|
| Voting does NOT own eligibility rules | Weakens the case for Voting as independent bounded context |
| Voting consumes pre-computed snapshot | Supports upstream/downstream pattern (Governance → Voting) |
| Voting has all other context criteria | Own language, persistence, services, value objects |
| No Voting-specific events found | Weakens independent evolution claim |

**Recommended classification:** Voting is a **Candidate Bounded Context** pending resolution of whether the lack of eligibility ownership and independent events is acceptable for context separation, or whether these indicate a Supporting Subdomain relationship.

**Context relationship:** Upstream/Downstream — Election Governance publishes eligibility facts; Voting consumes them.
