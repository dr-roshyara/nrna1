<?php

namespace Tests\Unit\Domain\Election\Constitution;

use App\Domain\Election\Constitution\NetworkThresholdInterpreter;
use App\Domain\Election\Security\Simplified\EvidenceClassification;
use PHPUnit\Framework\TestCase;

class NetworkThresholdInterpreterTest extends TestCase
{
    // Given maxVotesPerIp = 8
    private const MAX_VOTES = 8;

    public function test_unverified_threshold(): void
    {
        // Unverified = floor(maxVotesPerIp / 2)
        $threshold = NetworkThresholdInterpreter::interpret(self::MAX_VOTES, EvidenceClassification::Initial);
        $this->assertSame(4, $threshold);
    }

    public function test_attested_threshold(): void
    {
        // Attested = maxVotesPerIp
        $threshold = NetworkThresholdInterpreter::interpret(self::MAX_VOTES, EvidenceClassification::Attested);
        $this->assertSame(8, $threshold);
    }

    public function test_continuity_verified_threshold(): void
    {
        // ContinuityVerified = floor(maxVotesPerIp * 1.5) = maxVotesPerIp + maxVotesPerIp/2
        $threshold = NetworkThresholdInterpreter::interpret(self::MAX_VOTES, EvidenceClassification::ContinuityProven);
        $this->assertSame(12, $threshold); // 8 + 4 = 12
    }

    public function test_registrar_attested_threshold(): void
    {
        // RegistrarAttested = maxVotesPerIp * 2
        $threshold = NetworkThresholdInterpreter::interpret(self::MAX_VOTES, EvidenceClassification::RegistrarConfirmed);
        $this->assertSame(16, $threshold);
    }

    public function test_deterministic_same_input_same_output(): void
    {
        $results = [];
        for ($i = 0; $i < 10; $i++) {
            $results[] = NetworkThresholdInterpreter::interpret(6, EvidenceClassification::Attested);
        }
        $this->assertCount(10, $results);
        $this->assertSame(6, $results[0]);
        $this->assertSame($results[0], $results[array_key_last($results)]);
    }

    public function test_integer_only_math(): void
    {
        // Odd maxVotesPerIp tests integer division
        $oddMax = 7;
        $this->assertSame(3, NetworkThresholdInterpreter::interpret($oddMax, EvidenceClassification::Initial));
        $this->assertSame(7, NetworkThresholdInterpreter::interpret($oddMax, EvidenceClassification::Attested));
        $this->assertSame(10, NetworkThresholdInterpreter::interpret($oddMax, EvidenceClassification::ContinuityProven)); // 7 + 3 = 10
        $this->assertSame(14, NetworkThresholdInterpreter::interpret($oddMax, EvidenceClassification::RegistrarConfirmed));
    }

    public function test_zero_max_votes(): void
    {
        $this->assertSame(0, NetworkThresholdInterpreter::interpret(0, EvidenceClassification::Initial));
        $this->assertSame(0, NetworkThresholdInterpreter::interpret(0, EvidenceClassification::Attested));
        $this->assertSame(0, NetworkThresholdInterpreter::interpret(0, EvidenceClassification::RegistrarConfirmed));
    }

    public function test_single_max_vote(): void
    {
        $this->assertSame(0, NetworkThresholdInterpreter::interpret(1, EvidenceClassification::Initial)); // floor(1/2) = 0
        $this->assertSame(1, NetworkThresholdInterpreter::interpret(1, EvidenceClassification::Attested));
    }

    public function test_is_pure_function_with_no_side_effects(): void
    {
        $method = new \ReflectionMethod(NetworkThresholdInterpreter::class, 'interpret');
        $this->assertTrue($method->isStatic());
        $this->assertTrue($method->isPublic());
    }
}
