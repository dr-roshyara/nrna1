# Papers → DDD Reference Implementation Guide

**Date:** 2026-06-13  
**Status:** COMPANION DOCUMENT — supports governance-track Round 36A literature reviews  
**Scope:** Reference only. Does NOT make architectural decisions.  
**Governing Session:** Round 36A literature evaluation (separate session)

---

## Preamble

This document maps all papers in `docs/architecture/papers/` to DDD constructs.  
It answers ONE question: **"When I am authorized to implement X, what does it look like?"**

It does NOT:
- Propose new bounded contexts (that is ARB work)
- Override discovered aggregates
- Resolve open governance debts (D39, D42B, D35–D37)

**NRNA accepted bounded contexts (9):**
Trust Attestation · Eligibility · Authorization · Constitutional Governance · Voting · Results/Tallying · Audit · Governance Evidence Replay · Arbitration/Legitimacy

**NRNA confirmed aggregates (5):**
Verification · Vote · GovernanceState · RoleAssignment (candidate) · ReplaySession (candidate)

**Status markers:**
- `[READY]` — can implement when authorized by governance session
- `[BLOCKED BY D42B]` — waiting on verifiability ownership resolution
- `[BLOCKED BY D39]` — waiting on Results/Tallying ownership resolution
- `[BLOCKED BY D35–D37]` — waiting on legitimacy governance resolution
- `[CANDIDATE]` — requires ARB approval before implementation

---

## Section 1 — BOUNDED CONTEXTS

This section maps each paper's patterns to the 9 accepted NRNA contexts.  
Papers that suggest a NEW context are flagged `[NEW CONTEXT CANDIDATE]`.

| Paper | Pattern | Maps To | Notes |
|-------|---------|---------|-------|
| Gritzalis (Principles & Requirements) | Constitutional eligibility rules, generality, secrecy | Constitutional Governance | Informs GovernanceState invariants |
| Gritzalis (Principles & Requirements) | Functional user requirements (voter auth, ballot submission) | Trust Attestation + Eligibility | Multi-step registration flow |
| paper_2 (Mathematics and Politics) | Multi-rule tally evaluation (Borda, Condorcet, Hare) | Results/Tallying | Social choice strategy layer |
| paper_2 (Mathematics and Politics) | Invariant validation across tally rules | Audit | ElectionInvariantValidator |
| paper_2 (Mathematics and Politics) | Banzhaf/Shapley power indices | `[NEW CONTEXT CANDIDATE]` Power Analysis | Not in current 9 contexts |
| paper_2 (Mathematics and Politics) | Adjusted Winner / Arbitration engine | Arbitration/Legitimacy | Pareto evaluation |
| paper_03 (Gelman—Voting Power) | Correlated voter blocks, non-independence | Trust Attestation + Eligibility | Concurrent burst handling |
| paper_03 (Gelman—Voting Power) | Coalition anomaly detection via replay | Governance Evidence Replay | ReplaySession input |
| paper_04 (Aragón-Artacho—Math in Politics) | Apportionment (Hamilton, Jefferson, Webster) | Results/Tallying | Seat allocation Value Objects |
| paper_04 (Aragón-Artacho—Math in Politics) | Risk-Limiting Audit (sequential probability) | Audit | Domain Service |
| paper_04 (Aragón-Artacho—Math in Politics) | Pareto multi-criteria dispute resolution | Arbitration/Legitimacy | LegitimacyEvaluator |
| paper_05 (DDD online voting) | Election lifecycle aggregate (PREPARING → TALLIED) | Voting + Results/Tallying | Election state machine |
| paper_05 (DDD online voting) | Tally strategy interface | Results/Tallying | Strategy pattern |
| paper_06 (Algebraic refactoring) | Score vector O(m) vs ranking space O(m!) | Results/Tallying | Computational model |
| paper_06 (Algebraic refactoring) | Homomorphic aggregation of encrypted scores | `[NEW CONTEXT CANDIDATE]` Aggregation | Not in current 9 |
| paper_06 (Algebraic refactoring) | Threshold decryption ceremony | `[NEW CONTEXT CANDIDATE]` Decryption | Not in current 9 |
| paper_reviw_01 (E2E Survey + DDD mapping) | End-to-end verifiability policy | Audit + Governance Evidence Replay | Cross-context policy |
| paper_reviw_01 (E2E Survey + DDD mapping) | Mixnet shuffle service | Voting | Anonymization before tally |
| paper_reviw_01 (E2E Survey + DDD mapping) | Homomorphic tally (Benaloh model) | Results/Tallying | Replace sum(votes) |
| paper_reviw_01 (E2E Survey + DDD mapping) | Risk-Limiting Audit | Audit | Statistical assurance |
| new_idea_algebraic (Algebraic insight) | Voting = deterministic identity-free transformation | All vote-touching contexts | Core VO-1 principle |
| new_idea_algebraic (Algebraic insight) | Profile aggregate (not Vote aggregate) | Results/Tallying | Aggregated preferences |
| new_idea_algebraic_01 (Crypto model) | VotingToken (one-time, unlinkable) | Trust Attestation | Blind credential |
| new_idea_algebraic_01 (Crypto model) | EncryptedBallot + ZK proof | Voting | Crypto ballot |
| new_idea_algebraic_01 (Crypto model) | BallotBatch (homomorphic sum) | `[NEW CONTEXT CANDIDATE]` Aggregation | |
| new_idea_algebraic_01 (Crypto model) | DecryptionCeremony (t of n trustees) | `[NEW CONTEXT CANDIDATE]` Decryption | |
| new_idea_algebraic_01 (Crypto model) | ElectionProof (pipeline verification) | `[NEW CONTEXT CANDIDATE]` Proof Engine | |
| new_idea_algebraic_01 (Crypto model) | BulletinBoard (append-only public log) | `[NEW CONTEXT CANDIDATE]` Bulletin Board | |
| new_idea_algebraic_02 (Full synthesis) | Complete E2E pipeline | All contexts | See full synthesis |
| new_idea_algebraic_02 (Novel mechanism) | VoteReconstructionRequest (3-party consent) | `[NEW CONTEXT CANDIDATE]` Dispute Extension | Novel — no existing E2E precedent |
| election_guard.md (ElectionGuard) | Anti-Corruption Layer (ACL) between domain and crypto | Infrastructure layer | Not a domain concept |
| election_guard.md (ElectionGuard) | ElectionManifest (immutable election config) | Constitutional Governance | Maps to GovernanceState |
| election_guard.md (ElectionGuard) | GuardianCeremony (DKG) | `[NEW CONTEXT CANDIDATE]` Decryption | |
| election_guard.md (ElectionGuard) | CQRS: write (encrypt) vs read (verify) | All vote-touching contexts | Architectural pattern |
| helios_2008.md (Helios original) | Benaloh Split: audit-or-cast decision | Voting | Challenge ballot pattern |
| helios_2008.md (Helios original) | Sako-Kilian Mixnet anonymization | Voting | CDI to evaluate at 36C |
| helios_2008.md (Helios original) | JSON evidence artifacts (static, outlive infrastructure) | Governance Evidence Replay | Election Record pattern |
| helios_01.md (Helios Chapter 11) | Late-Bound Identity Binding (CDI-05) | Trust Attestation | Auth after ballot prep |
| helios_01.md (Helios Chapter 11) | Governance Fingerprint (CDI-06) | Constitutional Governance | Immutable config hash |
| helios_01.md (Helios Chapter 11) | Last-Ballot-Wins (ephemeral suffix overwriting) | Voting | Research obs only — VO-1 impact |
| helios_01.md (Helios Chapter 11) | Independent Verification Observer (CDI-07) | Audit | External monitor pattern |
| helios_01.md (Helios Chapter 11) | Helios-C Authority Separation | Trust Attestation + Eligibility | Ballot stuffing prevention |

---

## Section 2 — AGGREGATES

---

### 2.1 PreferenceProfile

**Status:** `[BLOCKED BY D39]`  
**Source Papers:** new_idea_algebraic, paper_2, paper_05  
**Related NRNA Context:** Results/Tallying

#### What It Is
An immutable aggregated multiset of anonymous voter rankings. Replaces tracking individual vote records; holds only the count of each distinct ranking pattern. This is the identity-free input to any tally computation.

#### When to Use
Use when the Results/Tallying context needs to compute a winner without retaining any per-voter data. This aggregate becomes the sole input to all VotingRuleStrategy implementations.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Tallying\Aggregates;

final class PreferenceProfile
{
    // rankingCounts: ["A>B>C" => 42, "B>A>C" => 17, ...]
    private array $rankingCounts;

    private function __construct(array $rankingCounts)
    {
        $this->rankingCounts = $rankingCounts;
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function addRanking(string $rankingKey): self
    {
        $counts = $this->rankingCounts;
        $counts[$rankingKey] = ($counts[$rankingKey] ?? 0) + 1;
        return new self($counts);
    }

    public function getRankingCounts(): array
    {
        return $this->rankingCounts;
    }

    public function totalVotes(): int
    {
        return array_sum($this->rankingCounts);
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe: no voter identity is stored. The Profile contains only aggregated counts. It MUST be derived from anonymous ballot data only — never from a table that has voter IDs.

---

### 2.2 VotingToken `[BLOCKED BY D42B]`

**Source Papers:** new_idea_algebraic_01, helios_2008, paper_reviw_01  
**Related NRNA Context:** Trust Attestation

#### What It Is
A one-time, unlinkable credential issued after identity verification. The token is the ONLY thing passed to the Voting context — no voter ID crosses the boundary. Once consumed, it cannot be reused.

#### When to Use
Use when Trust Attestation needs to authorize a vote without revealing the voter's identity to the Voting context. Implements the Late-Bound Identity Binding pattern (CDI-05).

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\TrustAttestation\Aggregates;

final class VotingToken
{
    private string $tokenHash;    // H(blind_token) — no identity stored
    private bool $consumed;
    private ElectionId $electionId;

    public function consume(): void
    {
        if ($this->consumed) {
            throw new \DomainException("VotingToken already consumed.");
        }
        $this->consumed = true;
    }

    public function isConsumed(): bool
    {
        return $this->consumed;
    }

    public function tokenHash(): string
    {
        return $this->tokenHash;
    }
}
```

#### Constraints / VO-1 Impact
**VO-1 critical.** The tokenHash MUST be derived from a blind token, not from the voter ID. The voter ID MUST be discarded after token issuance. Token storage must not be joinable with any voter identity table.

---

### 2.3 EncryptedBallot `[BLOCKED BY D42B]`

**Source Papers:** new_idea_algebraic_01, election_guard, helios_2008, paper_05  
**Related NRNA Context:** Voting

#### What It Is
An immutable record of a single anonymous vote, represented as an encrypted score vector. Contains a zero-knowledge proof that the encoded preference is valid (no overvotes, scores in range). Cannot be decrypted individually — only the aggregate can be decrypted.

#### When to Use
Use when the Voting context needs to persist a cryptographically sealed preference that is unreadable to anyone (including admins) until the aggregate is decrypted post-election.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Voting\Aggregates;

final class EncryptedBallot
{
    private BallotId $id;
    private string $tokenHash;          // consumed VotingToken reference
    private array $encryptedScores;     // Enc(s(v)) — size m, one per candidate
    private string $commitmentHash;     // H(s(v) || voter_secret) — the receipt
    private ?string $ballotProof;       // ZK proof of well-formedness (optional)
    private \DateTimeImmutable $castAt;

    public function validate(int $candidateCount): bool
    {
        return count($this->encryptedScores) === $candidateCount;
    }

    public function receipt(): string
    {
        return $this->commitmentHash;
    }
}
```

#### Constraints / VO-1 Impact
**VO-1 critical.** `encryptedScores` MUST NOT be individually decryptable. The `commitmentHash` is the voter's receipt — it proves inclusion without revealing the vote. The `tokenHash` reference must not be joinable with voter identity.

---

### 2.4 BallotBatch `[CANDIDATE]`

**Source Papers:** new_idea_algebraic_01, new_idea_algebraic_02, paper_06  
**Related NRNA Context:** `[NEW CONTEXT CANDIDATE]` Aggregation  
**ARB Note:** Requires approval before implementation. No existing context owns this.

#### What It Is
An append-only aggregate that homomorphically accumulates EncryptedBallots into a single ciphertext `Enc(S)`. Once a ballot is added, it cannot be removed or altered. The BallotBatch is sealed before decryption begins.

#### When to Use
Use when all encrypted ballots for an election must be combined into a single encrypted aggregate that can be threshold-decrypted without revealing individual votes.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Aggregation\Aggregates;

final class BallotBatch
{
    private BatchId $id;
    private array $encryptedBallots = [];
    private array $aggregatedCipher = [];   // Enc(S) — homomorphic sum
    private bool $sealed = false;

    public function add(EncryptedBallot $ballot): void
    {
        if ($this->sealed) {
            throw new \DomainException("BallotBatch is sealed. No more ballots.");
        }
        $this->encryptedBallots[] = $ballot;
        $this->aggregatedCipher = Homomorphic::add(
            $this->aggregatedCipher,
            $ballot->encryptedScores()
        );
    }

    public function seal(): void
    {
        $this->sealed = true;
    }

    public function resultCipher(): array
    {
        if (!$this->sealed) {
            throw new \DomainException("Seal BallotBatch before reading cipher.");
        }
        return $this->aggregatedCipher;
    }

    public function ballotCount(): int
    {
        return count($this->encryptedBallots);
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe by design: only the aggregate `Enc(S)` is ever decrypted — never individual ballots. The append-only invariant ensures no ballot can be removed after casting.

---

### 2.5 DecryptionCeremony `[CANDIDATE]`

**Source Papers:** new_idea_algebraic_01, new_idea_algebraic_02, election_guard, paper_06  
**Related NRNA Context:** `[NEW CONTEXT CANDIDATE]` Decryption  
**ARB Note:** Requires approval. Maps to Guardian Ceremony concept from ElectionGuard.

#### What It Is
A stateful aggregate coordinating threshold decryption of a sealed BallotBatch. Collects partial decryption shares from n trustees; finalizes only when t shares are received. Enforces that each trustee submits exactly one share.

#### When to Use
Use when the election has closed, BallotBatch is sealed, and the final score vector S must be recovered from Enc(S) using distributed trustee keys — without any single trustee being able to decrypt alone.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Decryption\Aggregates;

final class DecryptionCeremony
{
    private CeremonyId $id;
    private int $thresholdRequired;
    private array $submittedShares = [];    // [trusteeId => DecryptionShare]

    public function submitShare(TrusteeId $trusteeId, DecryptionShare $share): void
    {
        if (isset($this->submittedShares[$trusteeId->toString()])) {
            throw new \DomainException("Trustee already submitted a share.");
        }
        $this->submittedShares[$trusteeId->toString()] = $share;
    }

    public function isReady(): bool
    {
        return count($this->submittedShares) >= $this->thresholdRequired;
    }

    public function finalize(): array    // returns decrypted score vector S
    {
        if (!$this->isReady()) {
            throw new \DomainException("Threshold not met. Cannot finalize.");
        }
        return ThresholdCrypto::combine(array_values($this->submittedShares));
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe: only `S` (the aggregate score vector) is output — never individual votes. The voter cannot be required to participate in decryption (voter is absent after casting).

---

### 2.6 ElectionProof `[CANDIDATE]`

**Source Papers:** new_idea_algebraic_01, new_idea_algebraic_02, paper_reviw_01  
**Related NRNA Context:** `[NEW CONTEXT CANDIDATE]` Proof Engine  
**ARB Note:** Requires approval. This is the verification backbone of E2E-V.

#### What It Is
An aggregate that captures the full cryptographic evidence trail of an election: all encrypted ballots, the aggregated ciphertext, decrypted scores, and proofs of correct aggregation and decryption. Anyone can verify the election from this aggregate alone.

#### When to Use
Use when results are published and the system must provide universal verifiability — any external observer can confirm: (a) all ballots were included, (b) aggregation was correct, (c) decryption was correct.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\ProofEngine\Aggregates;

final class ElectionProof
{
    public array $encryptedBallots;     // all Enc(v_i)
    public array $aggregatedCipher;     // Enc(S)
    public array $decryptedScores;      // S ∈ ℝ^m
    public array $aggregationProof;     // proof that Enc(S) = Σ Enc(v_i)
    public array $decryptionProof;      // proof that Dec(Enc(S)) = S

    public function verify(): bool
    {
        return $this->verifyAggregation() && $this->verifyDecryption();
    }

    private function verifyAggregation(): bool
    {
        return Homomorphic::verify($this->encryptedBallots, $this->aggregatedCipher);
    }

    private function verifyDecryption(): bool
    {
        return Decryption::verify($this->aggregatedCipher, $this->decryptedScores);
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe: individual votes are not decrypted. The proof exposes only aggregated data. The encryptedBallots list proves inclusion but cannot be decrypted individually.

---

### 2.7 BulletinBoard `[CANDIDATE]`

**Source Papers:** new_idea_algebraic_01, new_idea_algebraic_02, helios_2008, helios_01, election_guard  
**Related NRNA Context:** `[NEW CONTEXT CANDIDATE]` Bulletin Board  
**ARB Note:** Multiple papers confirm this pattern (cross-source). Strongest new context candidate.

#### What It Is
An append-only, publicly readable log of all election artifacts (ballots, batches, decryption shares, results, proofs). Each entry is cryptographically linked to the previous entry (hash chain) and signed by its publisher. No authentication required for reads.

#### When to Use
Use when a voter needs to verify their vote was included (individual verifiability) or when an external observer needs to verify the entire election (universal verifiability). This is the single source of truth for public auditability.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\BulletinBoard\Aggregates;

final class BulletinBoard
{
    private array $entries = [];
    private string $lastHash = '';

    public function append(EpochEntry $entry): void
    {
        if (!$entry->isSigned()) {
            throw new \DomainException("Entry must be signed before appending.");
        }
        if ($entry->previousHash() !== $this->lastHash) {
            throw new \DomainException("Hash chain broken: previous hash mismatch.");
        }
        $this->entries[] = $entry;
        $this->lastHash = $entry->hash();
    }

    public function verifyIntegrity(): bool
    {
        $prevHash = '';
        foreach ($this->entries as $entry) {
            if ($entry->previousHash() !== $prevHash) return false;
            if (!$entry->isSigned()) return false;
            $prevHash = $entry->hash();
        }
        return true;
    }
}
```

#### Constraints / VO-1 Impact
VO-1 critical. Published entries must contain only `commitmentHash` (voter receipt), not plaintext votes. No voter identity may appear in any entry. The `publisherId` field MUST use the anonymized token reference, not voter ID.

---

### 2.8 AuditSession `[BLOCKED BY D39]`

**Source Papers:** paper_04, paper_reviw_01  
**Related NRNA Context:** Audit

#### What It Is
A stateful aggregate tracking a Risk-Limiting Audit (RLA) of a declared election result. Captures the risk limit alpha, current sample size, discrepancy count, and audit decision state. Emits a statistical confidence certificate when the audit passes.

#### When to Use
Use when the election result has been declared and a statistical audit is needed to confirm correctness without a full manual recount. The audit terminates as soon as the mathematical confidence threshold is met.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Audit\Aggregates;

final class AuditSession
{
    private AuditSessionId $id;
    private float $riskLimitAlpha;
    private float $currentConfidence = 0.0;
    private int $sampleSize = 0;
    private AuditDecisionState $state;

    public function evaluateSample(AuditSampleBatch $sample, TallyResult $result): void
    {
        $discrepancy = $sample->calculateDiscrepanciesAgainst($result);
        $logLikelihood = $this->computeWaldLikelihood($discrepancy);
        $this->sampleSize += $sample->size();

        if ($logLikelihood <= $this->riskLimitAlpha) {
            $this->state = AuditDecisionState::VERIFIED;
        } elseif ($sample->isFullPopulationReached()) {
            $this->state = AuditDecisionState::FULL_RECOUNT_REQUIRED;
        } else {
            $this->state = AuditDecisionState::EXPAND_SAMPLE;
        }
    }

    private function computeWaldLikelihood(array $discrepancy): float
    {
        // Sequential Probability Ratio Test (SPRT) per Stark & Lindeman (2012)
        return array_sum(array_map(fn($d) => log(max(0.01, $d)), $discrepancy));
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe: audit operates on declared TallyResult and ballot commitments only. No individual vote decryption is required.

---

### 2.9 VoteReconstructionRequest `[CANDIDATE]`

**Source Papers:** new_idea_algebraic_02 (novel mechanism section)  
**Related NRNA Context:** Arbitration/Legitimacy (or `[NEW CONTEXT CANDIDATE]` Dispute Extension)  
**ARB Note:** This is a novel mechanism not present in standard E2E systems. No existing precedent in Helios or ElectionGuard. Requires explicit ARB authorization.

#### What It Is
A governed appeal mechanism allowing a specific voter to request reconstruction of their own plaintext vote — requiring consent from all three parties: voter, system, committee. This is NOT the default path. It exists only for formal dispute resolution.

#### When to Use
Use ONLY when a voter formally claims their vote was miscounted or not included, AND all three parties consent to reconstruction. In normal elections this aggregate is never instantiated.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Arbitration\Aggregates;

final class VoteReconstructionRequest
{
    private RequestId $id;
    private string $receiptHash;        // voter's commitment hash (not voter ID)
    private ElectionId $electionId;
    private string $appealReason;
    private array $approvals = [];      // ['voter', 'system', 'committee']
    private AppealStatus $status;

    public function approveByVoter(): void
    {
        $this->addApproval('voter');
    }

    public function approveBySystem(): void
    {
        $this->addApproval('system');
    }

    public function approveByCommittee(): void
    {
        $this->addApproval('committee');
    }

    public function isFullyApproved(): bool
    {
        return count(array_unique($this->approvals)) === 3;
    }

    private function addApproval(string $party): void
    {
        if (in_array($party, $this->approvals)) {
            throw new \DomainException("{$party} already approved.");
        }
        if ($this->status !== AppealStatus::PENDING) {
            throw new \DomainException("Request is no longer pending.");
        }
        $this->approvals[] = $party;
    }
}
```

#### Constraints / VO-1 Impact
**VO-1 warning.** This is the ONLY aggregate that can expose an individual plaintext vote. It is permitted only under strict tri-party consent. The reconstruction result MUST be logged on the BulletinBoard for transparency. The voter identity MUST remain unlinkable (receipt hash, not voter ID).

---

## Section 3 — VALUE OBJECTS

---

### 3.1 PreferenceRank `[READY]`

**Source Papers:** paper_05, new_idea_algebraic  
**Related NRNA Context:** Results/Tallying

#### What It Is
An immutable pairing of a candidate ID and its rank position in a voter's ordering. Self-validating: rank must be a positive integer.

#### When to Use
Use when constructing a preference ordering for a ballot in ranked-choice voting scenarios.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Tallying\ValueObjects;

final readonly class PreferenceRank
{
    public function __construct(
        private int $rank,
        private string $candidateId
    ) {
        if ($rank < 1) {
            throw new \InvalidArgumentException("Rank must be a positive integer.");
        }
    }

    public function rank(): int { return $this->rank; }
    public function candidateId(): string { return $this->candidateId; }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. Contains only candidate reference and rank — no voter identity.

---

### 3.2 TallyResult `[BLOCKED BY D39]`

**Source Papers:** paper_2, paper_05, paper_06  
**Related NRNA Context:** Results/Tallying

#### What It Is
An immutable record of the final score vector S for all candidates under a specific voting rule. Contains the winner determination and supports Condorcet paradox detection (cyclic deadlock state).

#### When to Use
Use when a VotingRuleStrategy completes computation and must hand an immutable result to the Arbitration/Legitimacy context for legitimacy evaluation.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Tallying\ValueObjects;

final class TallyResult
{
    private array $scores;          // [candidateId => score]
    private bool $cyclicDeadlock;
    private ?string $absoluteWinnerId;

    private function __construct(
        array $scores,
        bool $cyclicDeadlock = false,
        ?string $absoluteWinnerId = null
    ) {
        $this->scores = $scores;
        $this->cyclicDeadlock = $cyclicDeadlock;
        $this->absoluteWinnerId = $absoluteWinnerId;
    }

    public static function fromScores(array $scores): self
    {
        arsort($scores);
        $winner = array_key_first($scores);
        return new self($scores, false, $winner);
    }

    public static function fromCyclicDeadlock(array $scores): self
    {
        return new self($scores, true, null);
    }

    public static function withAbsoluteWinner(string $winnerId, array $scores): self
    {
        return new self($scores, false, $winnerId);
    }

    public function isCyclicDeadlock(): bool { return $this->cyclicDeadlock; }
    public function getAbsoluteWinnerId(): ?string { return $this->absoluteWinnerId; }
    public function scores(): array { return $this->scores; }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. Contains only aggregated scores — no voter identity.

---

### 3.3 PairwiseMatrix `[BLOCKED BY D39]`

**Source Papers:** paper_2, paper_06, new_idea_algebraic  
**Related NRNA Context:** Results/Tallying

#### What It Is
An m×m matrix where `M[i][j]` = count of voters who ranked candidate i above candidate j. Enables Condorcet winner detection in O(m²) instead of O(m!).

#### When to Use
Use when the CondorcetRule strategy needs to determine if any candidate beats all others pairwise. Also used to detect Condorcet paradoxes (cyclic preferences).

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Tallying\ValueObjects;

final class PairwiseMatrix
{
    private array $matrix = [];
    private array $candidateIds;

    public function __construct(array $candidateIds)
    {
        $this->candidateIds = $candidateIds;
        foreach ($candidateIds as $i) {
            foreach ($candidateIds as $j) {
                $this->matrix[$i][$j] = 0;
            }
        }
    }

    public function accumulatePreferences(PreferenceProfile $profile): void
    {
        foreach ($profile->getRankingCounts() as $ranking => $count) {
            $ordered = explode('>', $ranking);
            for ($i = 0; $i < count($ordered); $i++) {
                for ($j = $i + 1; $j < count($ordered); $j++) {
                    $this->matrix[$ordered[$i]][$ordered[$j]] += $count;
                }
            }
        }
    }

    public function findUniqueCondorcetWinner(): ?string
    {
        foreach ($this->candidateIds as $candidate) {
            $beatsAll = true;
            foreach ($this->candidateIds as $other) {
                if ($candidate === $other) continue;
                if ($this->matrix[$candidate][$other] <= $this->matrix[$other][$candidate]) {
                    $beatsAll = false;
                    break;
                }
            }
            if ($beatsAll) return $candidate;
        }
        return null;    // Condorcet paradox — no unique winner
    }

    public function toArray(): array { return $this->matrix; }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. Contains only aggregated pairwise counts — no voter identity.

---

### 3.4 ApportionmentSeatAllocation `[BLOCKED BY D39]`

**Source Papers:** paper_04  
**Related NRNA Context:** Results/Tallying

#### What It Is
An immutable value object encoding the allocated seat count per candidate/party, together with fractional remainders. Guards against the Alabama Paradox (a candidate losing a seat when total seats increase).

#### When to Use
Use when the Results/Tallying context must distribute proportional seats using Hamilton, Jefferson, Webster, or Huntington-Hill methods.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Tallying\ValueObjects;

final class ApportionmentSeatAllocation
{
    private array $allocatedSeats;          // [candidateId => int]
    private array $fractionalRemainders;    // [candidateId => float]

    public function __construct(array $allocatedSeats, array $fractionalRemainders)
    {
        $totalAllocated = array_sum($allocatedSeats);
        $this->allocatedSeats = $allocatedSeats;
        $this->fractionalRemainders = $fractionalRemainders;
    }

    public function getSeatsFor(string $candidateId): int
    {
        return $this->allocatedSeats[$candidateId] ?? 0;
    }

    public function detectAlabamaParadox(self $previousBaseline): bool
    {
        foreach ($this->allocatedSeats as $id => $seats) {
            $previous = $previousBaseline->getSeatsFor($id);
            if ($seats < $previous) {
                return true;    // Paradox: gained total seats, lost individual seats
            }
        }
        return false;
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. Contains only seat counts per candidate — no voter identity.

---

### 3.5 RiskLimitAlpha `[BLOCKED BY D39]`

**Source Papers:** paper_04, paper_reviw_01  
**Related NRNA Context:** Audit

#### What It Is
An immutable value object representing the risk limit α for a Risk-Limiting Audit. Encapsulates the mathematical threshold below which the audit can safely stop.

#### When to Use
Use when constructing an AuditSession and configuring the sequential probability ratio test (SPRT) threshold.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Audit\ValueObjects;

final readonly class RiskLimitAlpha
{
    public function __construct(private float $value)
    {
        if ($value <= 0.0 || $value >= 1.0) {
            throw new \InvalidArgumentException("Risk limit must be between 0 and 1 exclusive.");
        }
    }

    public function asLogarithmicThreshold(): float
    {
        return log($this->value);
    }

    public function value(): float { return $this->value; }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. A configuration value — no voter data.

---

### 3.6 ConstitutionalVectorMetric `[BLOCKED BY D35–D37]`

**Source Papers:** paper_04, paper_2  
**Related NRNA Context:** Arbitration/Legitimacy

#### What It Is
A multi-criteria vector encoding the current GovernanceState's position along constitutional dimensions (Privacy, Auditability, Finality, Legitimacy). Used by the LegitimacyEvaluator to determine if a disputed state is within the Pareto-optimal constitutional frontier.

#### When to Use
Use when the Arbitration/Legitimacy context must evaluate whether a contested election outcome violates any hard constitutional constraint.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Arbitration\ValueObjects;

final class ConstitutionalVectorMetric
{
    private array $dimensions;  // ['privacy' => 0.9, 'auditability' => 0.7, ...]

    public static function fromState(GovernanceState $state): self
    {
        return new self([
            'privacy'       => $state->calculatePrivacyScore(),
            'auditability'  => $state->calculateAuditabilityScore(),
            'finality'      => $state->calculateFinalityScore(),
            'legitimacy'    => $state->calculateLegitimacyScore(),
        ]);
    }

    public function isWithinParetoOptimalFrontier(): bool
    {
        // No dimension may be below its constitutional minimum
        return $this->dimensions['privacy'] >= 0.8
            && $this->dimensions['auditability'] >= 0.7
            && $this->dimensions['finality'] >= 0.9;
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. Operates on aggregate governance scores — no individual vote data.

---

### 3.7 GovernanceFingerprint `[CANDIDATE]` (CDI-06)

**Source Papers:** helios_01 (CDI-06 from ARB analysis)  
**Related NRNA Context:** Constitutional Governance

#### What It Is
A deterministic hash computed over all election configuration parameters (candidates, ballot styles, eligibility rules, public keys) at the moment the election is frozen. All subsequent aggregates reference this hash to guarantee they operate under identical rules.

#### When to Use
Use when the GovernanceState transitions to a "frozen" or "certified" constitutional state, signaling that the election configuration is immutable and all downstream contexts can safely rely on it.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\ConstitutionalGovernance\ValueObjects;

final readonly class GovernanceFingerprint
{
    private function __construct(private string $hash) {}

    public static function fromConfiguration(ElectionConfiguration $config): self
    {
        $payload = json_encode([
            'candidates'      => $config->candidateIds(),
            'ballot_styles'   => $config->ballotStyles(),
            'eligibility'     => $config->eligibilityRules(),
            'public_key'      => $config->electionPublicKey(),
            'frozen_at'       => $config->frozenAt()->format('c'),
        ], JSON_SORT_KEYS);

        return new self(hash('sha256', $payload));
    }

    public function verify(ElectionConfiguration $config): bool
    {
        return $this->hash === self::fromConfiguration($config)->hash;
    }

    public function value(): string { return $this->hash; }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. Configuration data only — no voter information.

---

### 3.8 EpochEntry `[CANDIDATE]`

**Source Papers:** new_idea_algebraic_01, new_idea_algebraic_02  
**Related NRNA Context:** `[NEW CONTEXT CANDIDATE]` Bulletin Board

#### What It Is
A single signed, hash-chained entry in the BulletinBoard. Carries the payload (ballot, batch, share, result, or proof), its hash, the previous entry's hash, the publisher's signature, and a timestamp.

#### When to Use
Use when any domain event (BallotCast, BatchSealed, ResultPublished) must be permanently recorded on the public BulletinBoard.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\BulletinBoard\ValueObjects;

enum PayloadType: string
{
    case ENCRYPTED_BALLOT   = 'ENCRYPTED_BALLOT';
    case BALLOT_BATCH       = 'BALLOT_BATCH';
    case DECRYPTION_SHARE   = 'DECRYPTION_SHARE';
    case ELECTION_RESULT    = 'ELECTION_RESULT';
    case ELECTION_PROOF     = 'ELECTION_PROOF';
}

final class EpochEntry
{
    private int $epoch;
    private PayloadType $type;
    private array $payload;
    private string $payloadHash;
    private string $previousHash;
    private string $publisherId;        // anonymized — token hash, not voter ID
    private string $signature;
    private \DateTimeImmutable $timestamp;

    public function hash(): string
    {
        return hash('sha256', implode('|', [
            $this->epoch,
            $this->type->value,
            $this->payloadHash,
            $this->previousHash,
            $this->signature,
            $this->timestamp->getTimestamp(),
        ]));
    }

    public function previousHash(): string { return $this->previousHash; }
    public function isSigned(): bool { return !empty($this->signature); }
}
```

#### Constraints / VO-1 Impact
**VO-1 critical.** The `publisherId` field MUST be the anonymized token hash — never a voter ID. The `payload` for ENCRYPTED_BALLOT entries MUST contain only the encrypted scores and commitment hash — never plaintext votes.

---

### 3.9 InvariantReport `[READY]`

**Source Papers:** paper_2  
**Related NRNA Context:** Audit

#### What It Is
A record of social choice invariant violations detected when comparing multiple tally strategies against the same PreferenceProfile. Records Condorcet criterion divergence, cyclic paradoxes, and monotonicity failures.

#### When to Use
Use when the Audit context needs to flag mathematical anomalies in multi-rule tally evaluation — specifically when Borda and Condorcet disagree or a cyclic deadlock is detected.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Audit\ValueObjects;

final class InvariantReport
{
    private array $violations = [];
    private array $paradoxes = [];

    public function flagViolation(string $code, string $description): void
    {
        $this->violations[] = compact('code', 'description');
    }

    public function flagParadox(string $code, string $description): void
    {
        $this->paradoxes[] = compact('code', 'description');
    }

    public function hasViolations(): bool { return !empty($this->violations); }
    public function hasParadoxes(): bool { return !empty($this->paradoxes); }
    public function violations(): array { return $this->violations; }
    public function paradoxes(): array { return $this->paradoxes; }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. Contains only mathematical observations — no voter data.

---

### 3.10 AuditDecisionState `[BLOCKED BY D39]`

**Source Papers:** paper_04  
**Related NRNA Context:** Audit

#### What It Is
An enum-like value object representing the three possible outcomes of a Risk-Limiting Audit evaluation step: STOP_AUDIT_VERIFIED, EXPAND_SAMPLE_SIZE, or TRIGGER_FULL_MANUAL_RECOUNT.

#### When to Use
Use as the return value from `RiskLimitingAuditEngine::evaluateSample()` to drive the AuditSession state machine.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Audit\ValueObjects;

enum AuditDecisionState
{
    case VERIFIED;
    case EXPAND_SAMPLE;
    case FULL_RECOUNT_REQUIRED;

    public static function STOP_AUDIT_VERIFIED(): self { return self::VERIFIED; }
    public static function EXPAND_SAMPLE_SIZE(): self { return self::EXPAND_SAMPLE; }
    public static function TRIGGER_FULL_MANUAL_RECOUNT(): self { return self::FULL_RECOUNT_REQUIRED; }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. State indicator only.

---

## Section 4 — DOMAIN SERVICES

---

### 4.1 VotingRuleStrategyInterface + Implementations `[BLOCKED BY D39]`

**Source Papers:** paper_2, paper_05, paper_06  
**Related NRNA Context:** Results/Tallying

#### What It Is
A Strategy Pattern interface for pluggable social choice algorithms. Implementations include BordaCountRule, CondorcetRule, PluralityRule, and HareSystemRule. Each consumes a PreferenceProfile and produces a TallyResult.

#### When to Use
Use when the Results/Tallying context needs to compute a winner under a specific voting rule — allowing the election configuration to specify the algorithm without hardcoding it.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Tallying\Contracts;

interface VotingRuleStrategyInterface
{
    public function compute(PreferenceProfile $profile): TallyResult;
}

// Borda Count
namespace Domain\Tallying\Strategies;

final class BordaCountRule implements VotingRuleStrategyInterface
{
    public function compute(PreferenceProfile $profile): TallyResult
    {
        $scores = [];
        $candidateCount = count($this->getCandidates($profile));

        foreach ($profile->getRankingCounts() as $ranking => $voterCount) {
            $ordered = explode('>', $ranking);
            foreach ($ordered as $position => $candidateId) {
                $points = ($candidateCount - 1) - $position;   // (n-1) down to 0
                $scores[$candidateId] = ($scores[$candidateId] ?? 0) + ($points * $voterCount);
            }
        }

        return TallyResult::fromScores($scores);
    }
}

// Condorcet Rule
final class CondorcetRule implements VotingRuleStrategyInterface
{
    public function compute(PreferenceProfile $profile): TallyResult
    {
        $matrix = new PairwiseMatrix($this->getCandidates($profile));
        $matrix->accumulatePreferences($profile);

        $winnerId = $matrix->findUniqueCondorcetWinner();

        return $winnerId !== null
            ? TallyResult::withAbsoluteWinner($winnerId, $matrix->toArray())
            : TallyResult::fromCyclicDeadlock($matrix->toArray());
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. Operates only on PreferenceProfile — no voter identity.

---

### 4.2 BallotEncryptionService `[BLOCKED BY D42B]`

**Source Papers:** new_idea_algebraic_01, election_guard, paper_06  
**Related NRNA Context:** Voting

#### What It Is
A stateless service that converts a voter's plaintext preference (score vector) into an EncryptedBallot. Also generates the commitment hash (receipt) and optionally a ZK proof of ballot well-formedness.

#### When to Use
Use when the Voting context receives a voter's selection and must encrypt it before any persistence occurs. The plaintext preference MUST NOT be stored after this service returns.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Voting\Services;

final class BallotEncryptionService
{
    public function encrypt(
        array $scoreVector,
        string $electionPublicKey,
        string $voterSecret
    ): EncryptedBallot {
        $encryptedScores = array_map(
            fn($score) => ElGamal::encrypt($score, $electionPublicKey),
            $scoreVector
        );

        $commitmentHash = hash('sha256',
            json_encode($scoreVector) . $voterSecret
        );

        return new EncryptedBallot(
            id: BallotId::generate(),
            encryptedScores: $encryptedScores,
            commitmentHash: $commitmentHash,
        );
    }
}
```

#### Constraints / VO-1 Impact
**VO-1 critical.** The `scoreVector` (plaintext) MUST be discarded immediately after this service returns. Only the `EncryptedBallot` is persisted. The `voterSecret` is returned to the voter as part of their receipt — it is NOT stored by the system.

---

### 4.3 HomomorphicAggregator `[CANDIDATE]`

**Source Papers:** paper_06, new_idea_algebraic_01, new_idea_algebraic_02  
**Related NRNA Context:** `[NEW CONTEXT CANDIDATE]` Aggregation

#### What It Is
A stateless service that performs homomorphic addition over a collection of encrypted score vectors, producing `Enc(S)` — the encrypted aggregate without decrypting any individual ballot.

#### When to Use
Use when BallotBatch is being accumulated and the system must update the running aggregate `Enc(S)` after each ballot addition.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Aggregation\Services;

final class HomomorphicAggregator
{
    public function aggregate(array $encryptedBallots): array
    {
        if (empty($encryptedBallots)) {
            throw new \DomainException("Cannot aggregate empty ballot list.");
        }

        $aggregate = $encryptedBallots[0]->encryptedScores();

        foreach (array_slice($encryptedBallots, 1) as $ballot) {
            $aggregate = $this->homomorphicAdd($aggregate, $ballot->encryptedScores());
        }

        return $aggregate;
    }

    private function homomorphicAdd(array $a, array $b): array
    {
        // For ElGamal: multiply ciphertexts element-wise
        // Enc(a+b) = Enc(a) * Enc(b)
        return array_map(
            fn($i) => ElGamal::multiply($a[$i], $b[$i]),
            range(0, count($a) - 1)
        );
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe by mathematical design. Homomorphic addition never decrypts. Individual votes remain hidden inside `Enc(S)`.

---

### 4.4 ThresholdDecryptionService `[CANDIDATE]`

**Source Papers:** new_idea_algebraic_01, new_idea_algebraic_02, paper_06  
**Related NRNA Context:** `[NEW CONTEXT CANDIDATE]` Decryption

#### What It Is
A stateless service that combines `t` trustee decryption shares to recover the plaintext score vector `S` from `Enc(S)`. Validates each share's proof before combining.

#### When to Use
Use when DecryptionCeremony has collected enough shares (`isReady() === true`) and the final score vector must be recovered.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Decryption\Services;

final class ThresholdDecryptionService
{
    public function combine(array $decryptionShares): array
    {
        foreach ($decryptionShares as $share) {
            if (!$share->isProofValid()) {
                throw new \DomainException("Invalid decryption share proof from trustee.");
            }
        }

        return ThresholdElGamal::combine(
            array_map(fn($s) => $s->value(), $decryptionShares)
        );
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe: outputs only the aggregate score vector `S`. No individual vote is decrypted.

---

### 4.5 ProofVerificationService `[CANDIDATE]`

**Source Papers:** new_idea_algebraic_01, new_idea_algebraic_02, paper_reviw_01  
**Related NRNA Context:** `[NEW CONTEXT CANDIDATE]` Proof Engine (or Audit)

#### What It Is
A stateless service that independently verifies an ElectionProof by re-computing the homomorphic aggregation and checking the decryption proof. Used by external verifiers and the Audit context.

#### When to Use
Use when an external observer, the Audit context, or the Governance Evidence Replay context needs to confirm that the published election result is mathematically consistent with the published encrypted ballots.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\ProofEngine\Services;

final class ProofVerificationService
{
    public function verify(ElectionProof $proof): bool
    {
        return $this->verifyAggregation($proof)
            && $this->verifyDecryption($proof)
            && $this->verifyResult($proof);
    }

    private function verifyAggregation(ElectionProof $proof): bool
    {
        $recomputed = $this->aggregator->aggregate($proof->encryptedBallots);
        return $recomputed === $proof->aggregatedCipher;
    }

    private function verifyDecryption(ElectionProof $proof): bool
    {
        return ThresholdElGamal::verifyDecryption(
            $proof->aggregatedCipher,
            $proof->decryptedScores,
            $proof->decryptionProof
        );
    }

    private function verifyResult(ElectionProof $proof): bool
    {
        $computedWinner = array_key_first(
            array_flip(rsort($proof->decryptedScores))
        );
        return $computedWinner === $proof->declaredWinnerId;
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. Operates only on encrypted and aggregated data. No individual vote decryption.

---

### 4.6 RiskLimitingAuditEngine `[BLOCKED BY D39]`

**Source Papers:** paper_04, paper_reviw_01  
**Related NRNA Context:** Audit

#### What It Is
A stateless service implementing the Wald Sequential Probability Ratio Test (SPRT) for Risk-Limiting Audits (Stark & Lindeman, 2012). Evaluates a sample batch against the declared TallyResult and returns an AuditDecisionState.

#### When to Use
Use when an AuditSession needs to evaluate the next sample batch and determine whether the audit can stop (VERIFIED), needs more samples (EXPAND_SAMPLE), or requires a full recount (FULL_RECOUNT_REQUIRED).

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Audit\Services;

final class RiskLimitingAuditEngine
{
    public function evaluateSample(
        AuditSampleBatch $sample,
        TallyResult $reportedResult,
        RiskLimitAlpha $alpha
    ): AuditDecisionState {
        $discrepancies = $sample->calculateDiscrepanciesAgainst($reportedResult);
        $logLikelihoodRatio = $this->computeWaldLikelihood($discrepancies);

        if ($logLikelihoodRatio <= $alpha->asLogarithmicThreshold()) {
            return AuditDecisionState::STOP_AUDIT_VERIFIED();
        }

        if ($sample->isFullPopulationReached()) {
            return AuditDecisionState::TRIGGER_FULL_MANUAL_RECOUNT();
        }

        return AuditDecisionState::EXPAND_SAMPLE_SIZE();
    }

    private function computeWaldLikelihood(array $discrepancies): float
    {
        return array_reduce(
            $discrepancies,
            fn($carry, $d) => $carry + log(max(PHP_FLOAT_EPSILON, $d)),
            0.0
        );
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. Audit samples are ballot commitment hashes — not plaintext votes or voter identities.

---

### 4.7 LegitimacyEvaluator `[BLOCKED BY D35–D37]`

**Source Papers:** paper_04, paper_2  
**Related NRNA Context:** Arbitration/Legitimacy

#### What It Is
A stateless service implementing multi-criteria Pareto frontier evaluation for disputed GovernanceState transitions. Determines if a contested state violates any hard constitutional constraint or falls outside the Pareto-optimal legitimacy frontier.

#### When to Use
Use when a dispute or constitutional appeal is invoked against a GovernanceState transition. The evaluator must run BEFORE the Arbitration/Legitimacy context can issue a verdict.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Arbitration\Services;

final class LegitimacyEvaluator
{
    public function assessDisputedState(
        GovernanceState $contestedState,
        array $constitutionalConstraints
    ): EvaluationResult {
        $metric = ConstitutionalVectorMetric::fromState($contestedState);

        foreach ($constitutionalConstraints as $constraint) {
            if ($constraint->isViolatedBy($metric)) {
                return EvaluationResult::REJECT_LEGITIMACY(
                    "Violated: " . $constraint->description()
                );
            }
        }

        if (!$metric->isWithinParetoOptimalFrontier()) {
            return EvaluationResult::TRIGGER_ARBITRATION_INVOCATION();
        }

        return EvaluationResult::CONFIRM_LEGITIMACY();
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. Operates on governance dimension scores — not vote data.

---

### 4.8 ElectionInvariantValidator `[READY]`

**Source Papers:** paper_2  
**Related NRNA Context:** Audit

#### What It Is
A stateless service that asserts formal social choice axioms (Condorcet Criterion, Monotonicity, Pareto) across a multi-rule ElectionEvaluationBundle. Produces an InvariantReport flagging any violations or paradoxes.

#### When to Use
Use when the Audit context needs to validate that multiple tally strategies applied to the same PreferenceProfile don't contradict each other in constitutionally problematic ways.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Audit\Services;

final class ElectionInvariantValidator
{
    public function validate(
        PreferenceProfile $profile,
        ElectionEvaluationBundle $evaluation
    ): InvariantReport {
        $report = new InvariantReport();

        // Condorcet Criterion
        $condorcetWinner = $evaluation->getCondorcetResult()->getAbsoluteWinnerId();
        $bordaWinner = $evaluation->getBordaResult()->getAbsoluteWinnerId();

        if ($condorcetWinner !== null && $bordaWinner !== null) {
            if ($condorcetWinner !== $bordaWinner) {
                $report->flagViolation(
                    'CON_CRITERION_DIVERGENCE',
                    "Borda selected {$bordaWinner}, bypassing Condorcet Winner {$condorcetWinner}."
                );
            }
        }

        // Condorcet Paradox
        if ($evaluation->getCondorcetResult()->isCyclicDeadlock()) {
            $report->flagParadox(
                'CONDORCET_PARADOX_DETECTED',
                'Non-transitive collective preference cycle detected.'
            );
        }

        return $report;
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe. Operates on PreferenceProfile (aggregated counts) and TallyResult — no voter identity.

---

### 4.9 MixnetService `[CANDIDATE]`

**Source Papers:** paper_reviw_01, helios_2008 (Sako-Kilian)  
**Related NRNA Context:** Voting (anonymization pipeline)  
**ARB Note:** Deferred to Round 36C (Threat Modeling). Required for complex question types where homomorphic aggregation fails (ranked-choice, write-ins).

#### What It Is
A stateless service that takes a set of encrypted ballots, re-randomizes and shuffles them, and outputs a mathematically proven shuffle — breaking the link between voter token and ballot position while preserving all votes.

#### When to Use
Use when the election uses question types that cannot be homomorphically aggregated (e.g., ranked-choice with many candidates). The Mixnet produces a shuffled, anonymous set of decryptable ballots.

#### Implementation Sketch (PHP/Laravel)
```php
namespace Domain\Voting\Services;

final class MixnetService
{
    public function shuffle(
        array $encryptedBallots,
        string $electionPublicKey
    ): MixnetResult {
        $shuffled = $encryptedBallots;
        shuffle($shuffled);

        $reRandomized = array_map(
            fn($ballot) => ElGamal::reRandomize($ballot, $electionPublicKey),
            $shuffled
        );

        $proof = $this->generateShuffleProof($encryptedBallots, $reRandomized);

        return new MixnetResult($reRandomized, $proof);
    }

    private function generateShuffleProof(array $input, array $output): string
    {
        // Generates zero-knowledge proof that output is a permutation of input
        // Per Sako-Kilian protocol: 80 shadow mixes + Fiat-Shamir heuristic
        return ZeroKnowledge::provePermutation($input, $output);
    }
}
```

#### Constraints / VO-1 Impact
VO-1 safe: output ballots are re-randomized and shuffled — no link to voter token remains. The shuffle proof confirms all votes are included without revealing order.

---

## Section 5 — DOMAIN EVENTS

The following events are derived from the papers. They are organized by source context. All are `[CANDIDATE]` or status-marked unless already implied by existing architecture.

---

### From Trust Attestation Context

| Event | Source Paper | Status | Description |
|-------|-------------|--------|-------------|
| `VotingTokenIssued` | new_idea_algebraic_01 | `[BLOCKED BY D42B]` | Blind token issued after eligibility confirmed |
| `VotingTokenConsumed` | new_idea_algebraic_01 | `[BLOCKED BY D42B]` | Token marked as used; vote authorized |

---

### From Voting Context

| Event | Source Paper | Status | Description |
|-------|-------------|--------|-------------|
| `BallotEncrypted` | election_guard, algebraic | `[BLOCKED BY D42B]` | Score vector encrypted; plaintext discarded |
| `BallotCast` | helios_2008, election_guard | `[BLOCKED BY D42B]` | EncryptedBallot persisted; receipt issued to voter |
| `BallotChallenged` | helios_2008 | `[CANDIDATE]` | Voter chose to audit ballot (Benaloh Split); nonce revealed |
| `BallotRejected` | paper_05 | `[BLOCKED BY D42B]` | Invalid ZK proof or token already consumed |
| `ReceiptGenerated` | helios_2008 | `[BLOCKED BY D42B]` | Commitment hash given to voter for inclusion verification |

---

### From Aggregation Context (Candidate)

| Event | Source Paper | Status | Description |
|-------|-------------|--------|-------------|
| `BallotAddedToBatch` | new_idea_algebraic_01 | `[CANDIDATE]` | Encrypted ballot included in running aggregate |
| `BatchSealed` | new_idea_algebraic_01, 02 | `[CANDIDATE]` | BallotBatch sealed; no more additions allowed |
| `AggregationFinalized` | paper_06 | `[CANDIDATE]` | Enc(S) computed; ready for decryption |

---

### From Decryption Context (Candidate)

| Event | Source Paper | Status | Description |
|-------|-------------|--------|-------------|
| `DecryptionCeremonyInitiated` | election_guard, algebraic | `[CANDIDATE]` | Trustees notified to submit shares |
| `DecryptionShareSubmitted` | new_idea_algebraic_01, 02 | `[CANDIDATE]` | One trustee's partial decryption share accepted |
| `ThresholdReached` | new_idea_algebraic_01 | `[CANDIDATE]` | t shares collected; ready to finalize |
| `CeremonyCompleted` | new_idea_algebraic_01, 02 | `[CANDIDATE]` | Score vector S recovered; ready for Results |

---

### From Results/Tallying Context

| Event | Source Paper | Status | Description |
|-------|-------------|--------|-------------|
| `TallyComputed` | paper_2, paper_05 | `[BLOCKED BY D39]` | VotingRuleStrategy produced a TallyResult |
| `InvariantViolationDetected` | paper_2 | `[READY]` | ElectionInvariantValidator flagged paradox |
| `ResultPublished` | paper_reviw_01, new_idea_algebraic_02 | `[BLOCKED BY D39]` | Final winner declared; result is immutable |
| `ApportionmentCalculated` | paper_04 | `[BLOCKED BY D39]` | Seat allocation computed |

---

### From Audit Context

| Event | Source Paper | Status | Description |
|-------|-------------|--------|-------------|
| `AuditSessionStarted` | paper_04 | `[BLOCKED BY D39]` | RLA session initiated with risk limit alpha |
| `AuditSampleEvaluated` | paper_04 | `[BLOCKED BY D39]` | SPRT step completed; state updated |
| `AuditVerified` | paper_04, paper_reviw_01 | `[BLOCKED BY D39]` | Statistical confidence threshold met |
| `AuditEscalated` | paper_04 | `[BLOCKED BY D39]` | Full manual recount required |

---

### From Proof Engine / Bulletin Board (Candidates)

| Event | Source Paper | Status | Description |
|-------|-------------|--------|-------------|
| `ProofGenerated` | new_idea_algebraic_01, 02 | `[CANDIDATE]` | ElectionProof built after CeremonyCompleted |
| `ProofVerified` | paper_reviw_01 | `[CANDIDATE]` | ProofVerificationService confirmed proof integrity |
| `EntryAppended` | new_idea_algebraic_01, helios | `[CANDIDATE]` | EpochEntry added to BulletinBoard hash chain |
| `GovernanceFingerprintPublished` | helios_01 CDI-06 | `[CANDIDATE]` | Election configuration frozen and hashed |

---

### From Arbitration/Legitimacy Context

| Event | Source Paper | Status | Description |
|-------|-------------|--------|-------------|
| `LegitimacyConfirmed` | paper_04 | `[BLOCKED BY D35–D37]` | Pareto frontier check passed |
| `LegitimacyRejected` | paper_04 | `[BLOCKED BY D35–D37]` | Hard constitutional constraint violated |
| `ArbitrationInvoked` | paper_04 | `[BLOCKED BY D35–D37]` | Pareto frontier breached; arbitration process started |
| `VoteReconstructionRequested` | new_idea_algebraic_02 | `[CANDIDATE]` | Novel appeal mechanism initiated |
| `VoteReconstructed` | new_idea_algebraic_02 | `[CANDIDATE]` | Tri-party consent met; plaintext recovered for appeal |

---

## Section 6 — CROSS-CUTTING CONCERNS

---

### 6.1 VO-1 Enforcement Points

VO-1 (no voter-vote linkage) is the governing constraint from the NRNA domain. The following table summarizes every construct in this guide that touches vote data and its VO-1 status.

| Construct | VO-1 Safe? | VO-1 Mechanism |
|-----------|-----------|----------------|
| VotingToken | ✅ | Token hash derived from blind credential — not voter ID |
| EncryptedBallot | ✅ | Score vector encrypted; commitmentHash cannot be reversed |
| BallotBatch | ✅ | Homomorphic sum — no individual vote decryptable |
| DecryptionCeremony | ✅ | Only S (aggregate) is decrypted — not individual votes |
| ElectionProof | ✅ | Proof contains only encrypted and aggregated data |
| BulletinBoard | ✅ (with care) | PublisherId MUST be token hash, not voter ID |
| VoteReconstructionRequest | ⚠️ EXCEPTION | ONLY construct where VO-1 is intentionally lifted — under tri-party consent |
| PreferenceProfile | ✅ | Aggregated ranking counts — no voter identity |
| BallotEncryptionService | ✅ | Plaintext discarded immediately after encryption |
| HomomorphicAggregator | ✅ | Mathematical property: Enc(a+b) = Enc(a)·Enc(b) |
| ThresholdDecryptionService | ✅ | Outputs S only — never an individual vote |

---

### 6.2 Cross-Source Pattern Confirmations

The following architectural patterns were confirmed by multiple independent papers:

| Pattern | Papers Confirming | Strength |
|---------|-----------------|---------|
| **Append-only public verification log** | helios_2008, helios_01, election_guard, new_idea_algebraic_01/02 | VERY HIGH — 5 sources |
| **Homomorphic tally (not individual decryption)** | paper_06, new_idea_algebraic_01/02, paper_reviw_01, election_guard | VERY HIGH — 5 sources |
| **Threshold decryption (t of n trustees)** | paper_06, new_idea_algebraic_01/02, election_guard, paper_reviw_01 | VERY HIGH — 5 sources |
| **Voting = identity-free deterministic transformation** | new_idea_algebraic, paper_2, paper_05 | HIGH — 3 sources |
| **Strategy pattern for voting rules** | paper_2, paper_05, paper_06 | HIGH — 3 sources |
| **Independent verifier (external observer)** | helios_2008, helios_01, election_guard | HIGH — 3 sources (CDI-07) |
| **Late-bound identity binding** | helios_01 (CDI-05), new_idea_algebraic_01, helios_2008 | HIGH — 3 sources |
| **Configuration freeze / Governance Fingerprint** | helios_01 (CDI-06), election_guard, paper_05 | MEDIUM — 3 sources |
| **Risk-Limiting Audit** | paper_04, paper_reviw_01 | MEDIUM — 2 sources |
| **Pareto multi-criteria legitimacy** | paper_04, paper_2 | MEDIUM — 2 sources |
| **Benaloh Split (audit-or-cast)** | helios_2008, helios_01 | MEDIUM — 2 sources |

---

### 6.3 E2E Verifiability — The Three Questions

All constructs in this guide collectively answer the three canonical E2E verifiability questions. The table below maps each question to its DDD construct.

| Question | Construct Responsible | Mechanism |
|----------|----------------------|-----------|
| **"Is my vote counted?"** (Individual Verifiability) | EncryptedBallot → receipt; BulletinBoard → inclusion check | Voter checks `commitmentHash` on public BulletinBoard |
| **"Is my vote correctly counted?"** (Universal Verifiability) | BallotBatch → aggregation proof; ElectionProof → decryption proof | Any observer re-computes Σ Enc(v_i) and verifies decryption |
| **"Is my privacy respected?"** (Privacy) | VotingToken (blind); EncryptedBallot (no decrypt); HomomorphicAggregator (aggregate only) | No individual vote is ever decrypted; S only |

---

## Section 7 — NEW CONTEXT CANDIDATES SUMMARY

The following potential bounded contexts emerged from the papers but are NOT in the current 9 accepted NRNA contexts. They require ARB authorization before any implementation.

| Candidate Context | Source Papers | Strongest Evidence | Priority |
|-----------------|--------------|-------------------|---------|
| **Aggregation** | paper_06, algebraic papers | Cross-source confirmation (5 papers) | HIGH |
| **Decryption** | paper_06, algebraic papers, election_guard | Cross-source confirmation (5 papers) | HIGH |
| **Bulletin Board** | helios, election_guard, algebraic | Cross-source confirmation (5 papers) | HIGH |
| **Proof Engine** | algebraic papers, paper_reviw_01 | 3 sources | MEDIUM |
| **Power Analysis** | paper_2, paper_03 | 2 sources (Banzhaf/Shapley) | LOW — mathematical analysis only |

---

## Appendix A — Paper-to-Construct Quick Reference

| Paper | Primary Constructs Contributed |
|-------|-------------------------------|
| Gritzalis (Principles & Requirements) | Constitutional requirements → GovernanceState invariants |
| paper_2 (Mathematics and Politics synthesis) | VotingRuleStrategyInterface, BordaCountRule, CondorcetRule, ElectionInvariantValidator, InvariantReport, TallyResult |
| paper_03 (Gelman—Voting Power) | Architecture validation; no new constructs — validates multi-context separation |
| paper_04 (Aragón-Artacho—Math in Politics) | ApportionmentSeatAllocation, RiskLimitAlpha, AuditSession, RiskLimitingAuditEngine, ConstitutionalVectorMetric, LegitimacyEvaluator |
| paper_05 (DDD online voting) | Election state machine (ElectionState enum), TallyStrategyInterface, PreferenceRank, EncryptedChoice |
| paper_06 (Algebraic refactoring) | Score vector model, PairwiseMatrix, HomomorphicAggregator, ThresholdDecryptionService — feasibility proof |
| paper_reviw_01 (E2E Survey + DDD mapping) | EndToEndVerifiabilityPolicy, MixnetService, RiskLimitingAuditEngine (confirmation), cross-context E2E map |
| new_idea_algebraic | PreferenceProfile, anonymous aggregation model, VO-1 algebraic foundation |
| new_idea_algebraic_01 | VotingToken, EncryptedBallot, BallotBatch, DecryptionCeremony, ElectionResult, ElectionProof, BulletinBoard, domain events |
| new_idea_algebraic_02 | Complete synthesis; VoteReconstructionRequest (novel); full database schema; LaravelModule structure; literature mapping |
| election_guard | Anti-Corruption Layer pattern, GuardianCeremony, CQRS write/read separation, Election Record artifact |
| helios_2008 | Benaloh Split (BallotChallenged event), JSON evidence artifacts, Sako-Kilian Mixnet, ReceiptGenerated event |
| helios_01 | CDI-05 Late-Bound Identity Binding, CDI-06 GovernanceFingerprint, CDI-07 Independent Verification Observer, Configuration Freeze observation |

---

*This document is a companion to the Round 36A governance-track literature reviews. It does not authorize implementation. All [CANDIDATE] and [BLOCKED] items require resolution through the governance session before work begins.*
