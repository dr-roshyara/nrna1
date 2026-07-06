<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Shared\Inbox;

use App\Contexts\Shared\Application\Inbox\CausalPreconditionMissing;
use App\Contexts\Shared\Application\Inbox\IdempotentReplay;
use App\Contexts\Shared\Application\Inbox\InboxOutcome;
use App\Contexts\Shared\Application\Inbox\PermanentInboxFailure;
use PHPUnit\Framework\TestCase;

/**
 * PB-003-C1 — Inbox port package: outcome vocabulary + the exception
 * classification contract handlers use (Blueprint §8: already-done /
 * transient / permanent; §7 F4: causal precondition missing → park).
 *
 * Owner: Shared Application · Layer: Application port (pure PHP)
 * Traceability: Blueprint §7/§8 · ADR-T4 · Matrix: Inbox
 */
final class InboxClassificationTest extends TestCase
{
    public function test_outcome_vocabulary_is_exactly_the_four_blueprint_outcomes(): void
    {
        $this->assertSame(
            ['Processed', 'Duplicate', 'Parked', 'DeadLettered'],
            array_map(fn (InboxOutcome $o) => $o->name, InboxOutcome::cases()),
        );
    }

    public function test_causal_precondition_missing_carries_reason_and_is_runtime_exception(): void
    {
        $e = new CausalPreconditionMissing('challenge not yet Adjudicated');

        $this->assertInstanceOf(\RuntimeException::class, $e);
        $this->assertStringContainsString('challenge not yet Adjudicated', $e->getMessage());
        $this->assertSame('challenge not yet Adjudicated', $e->reason());
    }

    public function test_classification_markers_are_pure_interfaces_without_methods(): void
    {
        foreach ([IdempotentReplay::class, PermanentInboxFailure::class] as $marker) {
            $ref = new \ReflectionClass($marker);
            $this->assertTrue($ref->isInterface(), "$marker must be an interface");
            $this->assertSame([], $ref->getMethods(), "$marker must declare no methods (pure marker)");
        }
    }

    public function test_context_exceptions_can_adopt_markers(): void
    {
        $alreadyDone = new class('already adjudicated') extends \DomainException implements IdempotentReplay {
        };
        $constitutional = new class('correction on archived election') extends \DomainException implements PermanentInboxFailure {
        };

        $this->assertInstanceOf(IdempotentReplay::class, $alreadyDone);
        $this->assertInstanceOf(PermanentInboxFailure::class, $constitutional);
        $this->assertNotInstanceOf(PermanentInboxFailure::class, $alreadyDone);
    }
}
