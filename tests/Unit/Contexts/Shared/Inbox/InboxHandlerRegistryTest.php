<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Shared\Inbox;

use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Infrastructure\Inbox\InboxHandlerRegistry;
use App\Contexts\Shared\Infrastructure\Inbox\UnregisteredInboxHandler;
use PHPUnit\Framework\TestCase;

/**
 * PB-003-C4 — InboxHandlerRegistry: (consumer_context, event_type) → handler.
 *
 * Owner: Shared Infrastructure · Layer: Infrastructure (pure PHP — testable without Laravel)
 * Traceability: Blueprint §6 · ADR-T4 · D-03 (key includes consumer_context) · Matrix: Inbox
 *
 * Intentional divergence from EventHydratorRegistry (ER-03, justified): keyed by
 * (consumer_context, event_type), not event_type alone — the SAME event is
 * consumed by multiple contexts (Election + Contestation).
 */
final class InboxHandlerRegistryTest extends TestCase
{
    /** @param list<string> $eventTypes */
    private function handler(string $context, array $eventTypes): InboxHandler
    {
        return new class($context, $eventTypes) implements InboxHandler {
            /** @param list<string> $eventTypes */
            public function __construct(private string $context, private array $eventTypes)
            {
            }

            public function consumerContext(): string
            {
                return $this->context;
            }

            public function eventTypes(): array
            {
                return $this->eventTypes;
            }

            public function handle(InboxMessage $message): void
            {
            }
        };
    }

    public function test_returns_handler_for_a_registered_context_and_event_type(): void
    {
        $registry = new InboxHandlerRegistry();
        $handler = $this->handler('Contestation', ['DeterminationIssued']);

        $registry->register($handler);

        $this->assertSame($handler, $registry->handlerFor('Contestation', 'DeterminationIssued'));
    }

    /** D-03 justification: same event_type, different consumer_context = two handlers. */
    public function test_same_event_type_routes_to_different_handlers_per_context(): void
    {
        $registry = new InboxHandlerRegistry();
        $election = $this->handler('Election', ['DeterminationIssued']);
        $contestation = $this->handler('Contestation', ['DeterminationIssued']);

        $registry->register($election);
        $registry->register($contestation);

        $this->assertSame($election, $registry->handlerFor('Election', 'DeterminationIssued'));
        $this->assertSame($contestation, $registry->handlerFor('Contestation', 'DeterminationIssued'));
    }

    public function test_a_handler_registers_all_of_its_event_types(): void
    {
        $registry = new InboxHandlerRegistry();
        $handler = $this->handler('Contestation', ['DeterminationIssued', 'ElectionCorrectionApplied']);

        $registry->register($handler);

        $this->assertTrue($registry->has('Contestation', 'DeterminationIssued'));
        $this->assertTrue($registry->has('Contestation', 'ElectionCorrectionApplied'));
    }

    public function test_duplicate_context_and_event_type_pair_is_rejected(): void
    {
        $registry = new InboxHandlerRegistry();
        $registry->register($this->handler('Contestation', ['DeterminationIssued']));

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('DeterminationIssued');

        $registry->register($this->handler('Contestation', ['DeterminationIssued']));
    }

    public function test_unknown_pair_fails_loudly_with_dead_letter_reason(): void
    {
        $registry = new InboxHandlerRegistry();

        try {
            $registry->handlerFor('Election', 'NeverRegistered');
            $this->fail('expected UnregisteredInboxHandler');
        } catch (UnregisteredInboxHandler $e) {
            $this->assertStringContainsString('NeverRegistered', $e->getMessage());
            $this->assertStringContainsString('Election', $e->getMessage());
            $this->assertSame('UNREGISTERED_INBOX_HANDLER', $e->deadLetterReason());
        }
    }

    public function test_has_reports_registration_state(): void
    {
        $registry = new InboxHandlerRegistry();
        $registry->register($this->handler('Election', ['DeterminationIssued']));

        $this->assertTrue($registry->has('Election', 'DeterminationIssued'));
        $this->assertFalse($registry->has('Contestation', 'DeterminationIssued'));
        $this->assertFalse($registry->has('Election', 'ElectionCorrectionApplied'));
    }
}
