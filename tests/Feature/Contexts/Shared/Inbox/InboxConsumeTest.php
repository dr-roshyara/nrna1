<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Shared\Inbox;

use App\Contexts\Shared\Application\Inbox\CausalPreconditionMissing;
use App\Contexts\Shared\Application\Inbox\IdempotentReplay;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Application\Inbox\InboxOutcome;
use App\Contexts\Shared\Application\Inbox\PermanentInboxFailure;
use App\Contexts\Shared\Infrastructure\Inbox\Inbox;
use App\Contexts\Shared\Infrastructure\Inbox\InboxEvent;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * PB-003-C3 — Inbox wrapper: idempotent consume() with the 7 IDD §11-3 scenarios.
 *
 * Owner: Shared Infrastructure · Layer: Infrastructure
 * Traceability: Blueprint §6/§7/§8 · ADR-T4 (dedupe) · ADR-T1 (one txn) · Matrix: Inbox
 */
final class InboxConsumeTest extends TestCase
{
    use RefreshDatabase;

    private string $orgId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orgId = Organisation::factory()->create()->id;
    }

    private function message(?string $eventId = null): InboxMessage
    {
        return new InboxMessage(
            eventId: $eventId ?? (string) Str::uuid(),
            eventType: 'DeterminationIssued',
            payload: ['probe' => true],
            organisationId: $this->orgId,
            correlationId: (string) Str::uuid(),
            causationId: (string) Str::uuid(),
        );
    }

    /** @param callable(InboxMessage):void $onHandle */
    private function handler(callable $onHandle, string $context = 'Contestation'): InboxHandler
    {
        return new class($onHandle, $context) implements InboxHandler {
            public int $calls = 0;

            /** @param callable(InboxMessage):void $onHandle */
            public function __construct(private $onHandle, private string $context)
            {
            }

            public function consumerContext(): string
            {
                return $this->context;
            }

            public function eventTypes(): array
            {
                return ['DeterminationIssued'];
            }

            public function handle(InboxMessage $message): void
            {
                $this->calls++;
                ($this->onHandle)($message);
            }
        };
    }

    private function inbox(): Inbox
    {
        return $this->app->make(Inbox::class);
    }

    public function test_processed_happy_path_invokes_handler_once_and_persists_processed(): void
    {
        $handler = $this->handler(fn () => null);
        $msg = $this->message();

        $outcome = $this->inbox()->consume($msg, $handler);

        $this->assertSame(InboxOutcome::Processed, $outcome);
        $this->assertSame(1, $handler->calls);
        $this->assertSame('processed', InboxEvent::query()->firstOrFail()->status);
    }

    public function test_duplicate_delivery_does_not_reinvoke_handler(): void
    {
        $msg = $this->message();
        $first = $this->handler(fn () => null);
        $this->inbox()->consume($msg, $first);

        $second = $this->handler(fn () => null);
        $outcome = $this->inbox()->consume($msg, $second);

        $this->assertSame(InboxOutcome::Duplicate, $outcome);
        $this->assertSame(0, $second->calls, 'handler must NOT be re-invoked for a duplicate');
        $this->assertSame(1, InboxEvent::query()->count(), 'dedupe: one row per (event_id, consumer_context)');
    }

    public function test_causal_precondition_missing_parks_with_until_and_deadline(): void
    {
        $handler = $this->handler(function (): void {
            throw new CausalPreconditionMissing('challenge not yet Adjudicated');
        });

        $outcome = $this->inbox()->consume($this->message(), $handler);

        $this->assertSame(InboxOutcome::Parked, $outcome);
        $row = InboxEvent::query()->firstOrFail();
        $this->assertSame('parked', $row->status);
        $this->assertSame(1, $row->park_attempts);
        $this->assertNotNull($row->parked_until);
        $this->assertNotNull($row->park_deadline);
    }

    public function test_idempotent_replay_marker_is_treated_as_processed(): void
    {
        $handler = $this->handler(function (): void {
            throw new class('already adjudicated') extends \DomainException implements IdempotentReplay {
            };
        });

        $outcome = $this->inbox()->consume($this->message(), $handler);

        $this->assertSame(InboxOutcome::Processed, $outcome);
        $this->assertSame('processed', InboxEvent::query()->firstOrFail()->status);
    }

    public function test_permanent_failure_marker_dead_letters(): void
    {
        $handler = $this->handler(function (): void {
            throw new class('correction on archived election') extends \DomainException implements PermanentInboxFailure {
            };
        });

        $outcome = $this->inbox()->consume($this->message(), $handler);

        $this->assertSame(InboxOutcome::DeadLettered, $outcome);
        $this->assertSame('dead', InboxEvent::query()->firstOrFail()->status);
    }

    public function test_transient_throwable_rolls_back_leaving_no_row_and_rethrows(): void
    {
        $handler = $this->handler(function (): void {
            throw new \RuntimeException('DB deadlock');
        });

        try {
            $this->inbox()->consume($this->message(), $handler);
            $this->fail('transient exception must propagate');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('DB deadlock', $e->getMessage());
        }

        $this->assertSame(0, InboxEvent::query()->count(), 'rollback must leave NO row (redelivery-clean)');
    }

    /**
     * Scenario 7 (§11-3): an existing terminal/parked row short-circuits without
     * re-invoking the handler — the observable contract a concurrent race shares
     * (a race resolves to the same Duplicate/parked outcome via the unique key).
     */
    public function test_existing_dead_row_returns_deadlettered_without_handler(): void
    {
        $msg = $this->message();
        InboxEvent::create([
            'event_id' => $msg->eventId,
            'consumer_context' => 'Contestation',
            'event_type' => $msg->eventType,
            'payload' => $msg->payload,
            'organisation_id' => $this->orgId,
            'status' => 'dead',
        ]);

        $handler = $this->handler(fn () => null);
        $outcome = $this->inbox()->consume($msg, $handler);

        $this->assertSame(InboxOutcome::DeadLettered, $outcome);
        $this->assertSame(0, $handler->calls);
    }
}
