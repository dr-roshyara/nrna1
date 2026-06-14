# Discovery: Voting Domain Inventory

**Date:** 2026-06-13  
**Phase:** Phase 2 — Backend Domain Discovery (Round 5)  
**Status:** Complete

## Files Analyzed

| File | Lines | Role |
|------|-------|------|
| `Aggregate/VotingSession.php` | 52 | **Aggregate** — immutable read-only result of a voting process |
| `Service/VotingEngine.php` | 58 | **Domain Service** — orchestrates eligibility, aggregation, quorum, outcome |
| `Service/EligibilityEvaluator.php` | 20 | **Service** — filters eligible voters from snapshot |
| `Service/VoteAggregator.php` | — | **Service** — normalizes/deduplicates ballots |
| `Service/QuorumCalculator.php` | — | **Service** — evaluates quorum rules |
| `ValueObject/BallotCollection.php` | 55 | **VO** — normalized, sortable ballot array |
| `ValueObject/VotingOutcome.php` | 69 | **Value Object** — deterministic tally with `winner()`, `results()` |
| `ValueObject/EligibilitySnapshot.php` | — | **Value Object** — voter eligibility at a point in time |
| `ValueObject/QuorumDefinition.php` | — | **Value Object** — quorum parameters |
| `QuorumRule/` (6 files) | — | **Policy** — pluggable quorum rules (minimum absolute, basis point) |
| `Semantics/` (3 files) | — | **Language** — governance semantic definitions |

## Aggregate Candidate: VotingSession

```php
final readonly class VotingSession
{
    EligibilitySnapshot $eligibilitySnapshot,
    BallotCollection $ballots,
    VotingOutcome $outcome,
    bool $quorumMet

    fingerprint(): string   // SHA256 for replay validation
}
```

**Key properties:**
- Immutable (`final readonly`)
- Not an Eloquent model — a pure domain object
- Includes `fingerprint()` for replay/deterministic validation
- Composed from 3 value objects

**Note:** VotingSession may be a **Decision Result / Domain Snapshot** rather than a traditional Aggregate Root. True aggregates protect invariants, receive commands, and change state. VotingSession is immutable and computed — closer to a Value Object. Further investigation needed to determine aggregate boundaries.

## Voting Engine Flow

```
VotingEngine::execute()
    ↓
EligibilityEvaluator::evaluate()     → eligible voters list
    ↓
VoteAggregator::aggregate()          → filter + normalize + deduplicate
    ↓
QuorumCalculator::evaluate()          → deterministic comparison
    ↓
VotingOutcome constructor            → pure function tally
    ↓
VotingSession::create()              → immutable result
```

## Invariants Discovered

| Invariant | Location | Type |
|-----------|----------|------|
| Ballots normalized by voter ID sort order | `BallotCollection::__construct()` | Determinism invariant |
| Filter ballots to eligible voters only | `BallotCollection::filterByEligible()` | Eligibility invariant |
| Results computed deterministically | `VotingOutcome::__construct()` | Pure function invariant |
| No voter ID in ballot storage | `BaseVote::$fillable` — no `user_id` column | **Anonymity invariant** |
| Vote stored with cryptographic hash | `BaseVote::vote_hash` | Verifiability invariant |
| Demo votes in separate physical table | `DemoVote extends BaseVote` | Integrity invariant |
| Quorum rules are pluggable | `QuorumRule` interface + `CompositeQuorumRule` | Extensibility invariant |

## Language Alignment

| Voting Concept | Backend Domain | Frontend |
|----------------|----------------|----------|
| Voting action | `VotingEngine::execute()` | — |
| Open voting | `Constitution: open_voting` | `ElectionActions.OPEN_VOTING` |
| Close voting | `Constitution: close_voting` | `ElectionActions.CLOSE_VOTING` |
| Publish results | `Constitution: publish_results` | `ElectionActions.PUBLISH_RESULTS` |
| Eligibility | `EligibilityEvaluator` | (consumed via capabilities) |
| Ballot | `BallotCollection` | (stored in `candidate_01-60` columns) |

## Bounded Context Assessment

| Criterion | Election Governance | Voting |
|-----------|-------------------|--------|
| Own aggregate | ⚠️ Election (model) | ✅ VotingSession (pure) |
| Own invariants | Lifecycle, approval, capacity | Anonymity, determinism, quorum |
| Own events | Lifecycle events | — (no Voting-specific events yet) |
| Own persistence | `elections` table | `votes`, `results` tables |
| Own language | 14 constitutional actions | Voting, Ballot, Quorum, Eligibility |
| Separable? | Likely | ✅ **Likely yes** |

## Conclusion

The Voting domain has emerging bounded-context characteristics: its own language, services, value objects, and invariants. However, the `EligibilityEvaluator` dependency on `EligibilitySnapshot` — which may originate from Election Governance — requires context-mapping validation before concluding these are separate bounded contexts. `VotingSession` is likely a Decision Result / Domain Snapshot rather than an Aggregate Root.

The most architecturally significant finding is the `fingerprint()` method (SHA256 replay validation), pointing toward a **Trustworthiness Architecture** that deserves its own dedicated discovery.

**Assessment:** Strong bounded-context candidate — requires context-map validation and trustworthiness analysis before any refactoring decisions.
