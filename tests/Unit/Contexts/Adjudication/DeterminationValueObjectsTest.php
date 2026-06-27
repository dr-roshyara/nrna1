<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\DeterminationState;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\Exception\IllegalDeterminationTransition;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Covers the Determination value objects, enums, and transition exception.
 * (Retrofit acknowledged: these VOs were written before this test — corrected
 * here; the aggregate and event below are built test-first.)
 */
final class DeterminationValueObjectsTest extends TestCase
{
    /** @return array<string, array{callable(string): object}> */
    public static function stringValueObjects(): array
    {
        return [
            'DeterminationId' => [fn (string $v) => DeterminationId::fromString($v)],
            'ChallengeRef' => [fn (string $v) => ChallengeRef::fromString($v)],
            'Reason' => [fn (string $v) => Reason::fromString($v)],
            'IssuedByAuthority' => [fn (string $v) => IssuedByAuthority::fromString($v)],
            'Jurisdiction' => [fn (string $v) => Jurisdiction::fromString($v)],
            'EvidenceEnvelopeRef' => [fn (string $v) => EvidenceEnvelopeRef::fromString($v)],
        ];
    }

    #[DataProvider('stringValueObjects')]
    public function test_string_value_object_round_trips(callable $make): void
    {
        $vo = $make('value-1');
        $this->assertSame('value-1', $vo->toString());
    }

    #[DataProvider('stringValueObjects')]
    public function test_string_value_object_rejects_empty(callable $make): void
    {
        $this->expectException(InvalidArgumentException::class);
        $make('');
    }

    #[DataProvider('stringValueObjects')]
    public function test_string_value_object_rejects_whitespace(callable $make): void
    {
        $this->expectException(InvalidArgumentException::class);
        $make('   ');
    }

    public function test_determination_state_terminality(): void
    {
        $this->assertTrue(DeterminationState::Final->isTerminal());
        $this->assertFalse(DeterminationState::Draft->isTerminal());
        $this->assertFalse(DeterminationState::Issued->isTerminal());
    }

    public function test_enums_expose_expected_cases(): void
    {
        $this->assertSame('upheld', DeterminationOutcome::Upheld->value);
        $this->assertSame('dismissed', DeterminationOutcome::Dismissed->value);
        $this->assertSame('legitimate', Legitimacy::Legitimate->value);
        $this->assertSame('illegitimate', Legitimacy::Illegitimate->value);
    }

    public function test_illegal_transition_message_names_state_and_command(): void
    {
        $e = IllegalDeterminationTransition::from(DeterminationState::Final, 'issue');
        $this->assertStringContainsString('issue', $e->getMessage());
        $this->assertStringContainsString('final', $e->getMessage());
    }
}
