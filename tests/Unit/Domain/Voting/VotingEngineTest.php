<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Voting;

use App\Domain\Voting\Service\VotingEngine;
use App\Domain\Voting\Service\EligibilityEvaluator;
use App\Domain\Voting\Service\VoteAggregator;
use App\Domain\Voting\Service\QuorumCalculator;
use App\Domain\Voting\ValueObject\EligibilitySnapshot;
use App\Domain\Voting\ValueObject\BallotCollection;
use App\Domain\Voting\ValueObject\QuorumDefinition;
use App\Domain\Voting\ValueObject\VotingOutcome;
use App\Domain\Voting\Aggregate\VotingSession;
use PHPUnit\Framework\TestCase;

class VotingEngineTest extends TestCase
{
    private VotingEngine $engine;
    private EligibilityEvaluator $eligibilityEvaluator;
    private VoteAggregator $voteAggregator;
    private QuorumCalculator $quorumCalculator;

    protected function setUp(): void
    {
        $this->eligibilityEvaluator = new EligibilityEvaluator();
        $this->voteAggregator = new VoteAggregator();
        $this->quorumCalculator = new QuorumCalculator();

        $this->engine = new VotingEngine(
            $this->eligibilityEvaluator,
            $this->voteAggregator,
            $this->quorumCalculator
        );
    }

    /**
     * @test
     * Voting engine executes and produces VotingSession
     */
    public function test_engine_executes_and_returns_voting_session(): void
    {
        $eligibility = new EligibilitySnapshot(
            eligibleVoters: ['voter_1', 'voter_2', 'voter_3']
        );

        $ballots = new BallotCollection(
            ballots: [
                ['voter' => 'voter_1', 'selection' => 'candidate_a'],
                ['voter' => 'voter_2', 'selection' => 'candidate_b'],
                ['voter' => 'voter_3', 'selection' => 'candidate_a'],
            ]
        );

        $quorum = new QuorumDefinition(threshold: 2);

        $session = $this->engine->execute($eligibility, $ballots, $quorum);

        $this->assertInstanceOf(VotingSession::class, $session);
        $this->assertTrue($session->quorumMet);
    }

    /**
     * @test
     * Voting engine is deterministic (same inputs → same fingerprint)
     */
    public function test_voting_engine_is_deterministic(): void
    {
        $eligibility = new EligibilitySnapshot(
            eligibleVoters: ['voter_1', 'voter_2', 'voter_3']
        );

        $ballots = new BallotCollection(
            ballots: [
                ['voter' => 'voter_1', 'selection' => 'candidate_a'],
                ['voter' => 'voter_2', 'selection' => 'candidate_b'],
                ['voter' => 'voter_3', 'selection' => 'candidate_a'],
            ]
        );

        $quorum = new QuorumDefinition(threshold: 2);

        $session1 = $this->engine->execute($eligibility, $ballots, $quorum);
        $session2 = $this->engine->execute($eligibility, $ballots, $quorum);

        $this->assertSame($session1->fingerprint(), $session2->fingerprint());
    }

    /**
     * @test
     * Voting engine is order-independent for ballots
     */
    public function test_voting_engine_is_order_independent(): void
    {
        $eligibility = new EligibilitySnapshot(
            eligibleVoters: ['voter_1', 'voter_2', 'voter_3']
        );

        // Order 1
        $ballots1 = new BallotCollection(
            ballots: [
                ['voter' => 'voter_1', 'selection' => 'candidate_a'],
                ['voter' => 'voter_2', 'selection' => 'candidate_b'],
                ['voter' => 'voter_3', 'selection' => 'candidate_a'],
            ]
        );

        // Order 2 (different insertion order)
        $ballots2 = new BallotCollection(
            ballots: [
                ['voter' => 'voter_3', 'selection' => 'candidate_a'],
                ['voter' => 'voter_1', 'selection' => 'candidate_a'],
                ['voter' => 'voter_2', 'selection' => 'candidate_b'],
            ]
        );

        $quorum = new QuorumDefinition(threshold: 2);

        $session1 = $this->engine->execute($eligibility, $ballots1, $quorum);
        $session2 = $this->engine->execute($eligibility, $ballots2, $quorum);

        $this->assertSame($session1->fingerprint(), $session2->fingerprint());
    }

    /**
     * @test
     * Voting engine handles quorum met correctly
     */
    public function test_voting_engine_evaluates_quorum_correctly(): void
    {
        $eligibility = new EligibilitySnapshot(
            eligibleVoters: ['voter_1', 'voter_2', 'voter_3', 'voter_4', 'voter_5']
        );

        $ballots = new BallotCollection(
            ballots: [
                ['voter' => 'voter_1', 'selection' => 'candidate_a'],
                ['voter' => 'voter_2', 'selection' => 'candidate_a'],
            ]
        );

        // Quorum requires 3+ votes
        $quorum = new QuorumDefinition(threshold: 3);

        $session = $this->engine->execute($eligibility, $ballots, $quorum);

        // 2 votes < 3 threshold → quorum NOT met
        $this->assertFalse($session->quorumMet);
    }

    /**
     * @test
     * Voting engine filters ineligible voters
     */
    public function test_voting_engine_filters_ineligible_voters(): void
    {
        $eligibility = new EligibilitySnapshot(
            eligibleVoters: ['voter_1', 'voter_2']
        );

        $ballots = new BallotCollection(
            ballots: [
                ['voter' => 'voter_1', 'selection' => 'candidate_a'],
                ['voter' => 'voter_2', 'selection' => 'candidate_b'],
                ['voter' => 'voter_3', 'selection' => 'candidate_a'],  // NOT eligible
            ]
        );

        $quorum = new QuorumDefinition(threshold: 2);

        $session = $this->engine->execute($eligibility, $ballots, $quorum);

        // Only 2 valid votes (voter_3 filtered out)
        $this->assertTrue($session->quorumMet);
        $this->assertCount(2, $session->outcome->validBallots());
    }

    /**
     * @test
     * Voting session properties are readonly (immutable)
     */
    public function test_voting_session_is_immutable(): void
    {
        $eligibility = new EligibilitySnapshot(
            eligibleVoters: ['voter_1']
        );

        $ballots = new BallotCollection(
            ballots: [['voter' => 'voter_1', 'selection' => 'candidate_a']]
        );

        $quorum = new QuorumDefinition(threshold: 1);

        $session = $this->engine->execute($eligibility, $ballots, $quorum);

        // Verify properties are actually set and immutable
        $this->assertTrue($session->quorumMet);
        $this->assertSame(1, $session->outcome->totalVotes());

        // Readonly properties cannot be modified (enforced by PHP type system)
        // This test verifies the readonly declaration is effective
        $reflection = new \ReflectionClass($session);
        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue(
                $property->isReadonly(),
                "Property {$property->getName()} must be readonly"
            );
        }
    }

    /**
     * @test
     * Voting engine outcome is correctly computed
     */
    public function test_voting_engine_computes_outcome(): void
    {
        $eligibility = new EligibilitySnapshot(
            eligibleVoters: ['voter_1', 'voter_2', 'voter_3', 'voter_4']
        );

        $ballots = new BallotCollection(
            ballots: [
                ['voter' => 'voter_1', 'selection' => 'candidate_a'],
                ['voter' => 'voter_2', 'selection' => 'candidate_a'],
                ['voter' => 'voter_3', 'selection' => 'candidate_b'],
                ['voter' => 'voter_4', 'selection' => 'candidate_a'],
            ]
        );

        $quorum = new QuorumDefinition(threshold: 3);

        $session = $this->engine->execute($eligibility, $ballots, $quorum);

        $this->assertTrue($session->quorumMet);
        $outcome = $session->outcome;
        $this->assertSame(3, $outcome->countFor('candidate_a'));
        $this->assertSame(1, $outcome->countFor('candidate_b'));
    }

    /**
     * @test
     * Voting session fingerprint is deterministic across multiple executions
     */
    public function test_voting_session_fingerprint_determinism(): void
    {
        $eligibility = new EligibilitySnapshot(
            eligibleVoters: ['voter_1', 'voter_2', 'voter_3']
        );

        $ballots = new BallotCollection(
            ballots: [
                ['voter' => 'voter_1', 'selection' => 'candidate_a'],
                ['voter' => 'voter_2', 'selection' => 'candidate_b'],
                ['voter' => 'voter_3', 'selection' => 'candidate_a'],
            ]
        );

        $quorum = new QuorumDefinition(threshold: 2);

        $fingerprints = [];
        for ($i = 0; $i < 5; $i++) {
            $session = $this->engine->execute($eligibility, $ballots, $quorum);
            $fingerprints[] = $session->fingerprint();
        }

        // All 5 executions should produce identical fingerprints
        $unique = array_unique($fingerprints);
        $this->assertCount(1, $unique, 'Fingerprints must be deterministic');
    }

    /**
     * @test
     * Voting engine with empty ballots handles gracefully
     */
    public function test_voting_engine_with_empty_ballots(): void
    {
        $eligibility = new EligibilitySnapshot(
            eligibleVoters: ['voter_1', 'voter_2']
        );

        $ballots = new BallotCollection(ballots: []);

        $quorum = new QuorumDefinition(threshold: 1);

        $session = $this->engine->execute($eligibility, $ballots, $quorum);

        $this->assertFalse($session->quorumMet);
        $this->assertCount(0, $session->outcome->validBallots());
    }
}
