<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Shared\Messaging;

use App\Contexts\Shared\Application\Inbox\CausalPreconditionMissing;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Application\Inbox\PermanentInboxFailure;
use App\Contexts\Shared\Infrastructure\Inbox\Inbox;
use App\Contexts\Shared\Infrastructure\Inbox\InboxHandlerRegistry;
use App\Contexts\Shared\Infrastructure\Messaging\IntegrationEventDispatcher;
use App\Contexts\Shared\Infrastructure\Messaging\RegistryConsumerResolver;
use App\Contexts\Shared\Infrastructure\Outbox\IntegrationEvent;
use App\Models\Organisation;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;
use Tests\TestCase;

/**
 * PB-006 Step 6A (RED) — the `IntegrationEventDispatcher` (ADR-MP-06): the Messaging
 * Platform's delivery capability. Registration ≠ Delivery — this closes the gap between
 * the relay's output and consumer inboxes. Behaviour per the IDD RED matrix:
 * discovery (ordered, deterministic — consumerContext ascending) · one InboxMessage per
 * consumer with identity/org/correlation/causation propagated (D-1 envelope fields) ·
 * per-consumer delivery with CONSUMER ISOLATION · replay lands on inbox dedupe ·
 * empty consumer set = no-op · AUDIT CONTINUITY (inbox event_id = outbox event_id).
 */
final class IntegrationEventDispatcherTest extends TestCase
{
    private string $orgId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orgId = (string) Organisation::create([
            'name' => 'Dispatcher org',
            'slug' => 'dispatcher-'.Str::uuid(),
            'type' => 'tenant',
            'is_default' => false,
        ])->id;
    }

    private function envelope(?string $eventId = null, string $type = 'ProbeEvent'): IntegrationEvent
    {
        return new IntegrationEvent(
            eventId: $eventId ?? (string) Str::uuid(),
            eventType: $type,
            aggregateType: 'Probe',
            aggregateId: (string) Str::uuid(),
            organisationId: $this->orgId,
            payload: ['probe' => true],
            occurredAt: new DateTimeImmutable('2026-07-10T10:00:00+00:00'),
            correlationId: (string) Str::uuid(),   // D-1: additive envelope field
            causationId: (string) Str::uuid(),     // D-1: additive envelope field
        );
    }

    /** @param list<string> $callLog */
    private function consumer(string $context, array &$callLog, ?callable $behaviour = null): InboxHandler
    {
        return new class($context, $callLog, $behaviour) implements InboxHandler {
            /** @param list<string> $callLog */
            public function __construct(
                private string $context,
                private array &$callLog,
                private $behaviour,
            ) {
            }

            public function consumerContext(): string
            {
                return $this->context;
            }

            public function eventTypes(): array
            {
                return ['ProbeEvent'];
            }

            public function handle(InboxMessage $message): void
            {
                $this->callLog[] = $this->context;
                if ($this->behaviour !== null) {
                    ($this->behaviour)($message);
                }
            }
        };
    }

    /** @param InboxHandler ...$handlers */
    private function dispatcher(InboxHandler ...$handlers): IntegrationEventDispatcher
    {
        $registry = new InboxHandlerRegistry();
        foreach ($handlers as $handler) {
            $registry->register($handler);
        }

        return new IntegrationEventDispatcher(
            new RegistryConsumerResolver($registry),
            $this->app->make(Inbox::class),
        );
    }

    private function inboxRow(string $eventId, string $consumer): ?object
    {
        return DB::table('inbox_events')
            ->where('event_id', $eventId)
            ->where('consumer_context', $consumer)
            ->first();
    }

    public function test_delivers_to_the_single_registered_consumer_with_audit_continuity(): void
    {
        $calls = [];
        $envelope = $this->envelope();

        $this->dispatcher($this->consumer('Alpha', $calls))->dispatch($envelope);

        $this->assertSame(['Alpha'], $calls, 'handler invoked exactly once');
        $row = $this->inboxRow($envelope->eventId, 'Alpha');
        $this->assertNotNull($row, 'one inbox row per consumer');
        $this->assertSame('processed', $row->status);
        // Audit continuity: the inbox row is traceable to exactly ONE outbox event.
        $this->assertSame($envelope->eventId, $row->event_id);
        $this->assertSame($this->orgId, (string) $row->organisation_id, 'tenant propagated');
        $this->assertSame($envelope->correlationId, $row->correlation_id, 'correlation propagated (D-06)');
        $this->assertSame($envelope->causationId, $row->causation_id, 'causation propagated (D-06)');
    }

    public function test_delivers_to_all_consumers_in_deterministic_ascending_order(): void
    {
        $calls = [];
        // Registered in REVERSE order — discovery must still order consumerContext ascending.
        $dispatcher = $this->dispatcher(
            $this->consumer('Beta', $calls),
            $this->consumer('Alpha', $calls),
        );
        $envelope = $this->envelope();

        $dispatcher->dispatch($envelope);

        $this->assertSame(['Alpha', 'Beta'], $calls, 'ordered consumer set: consumerContext ascending');
        $this->assertNotNull($this->inboxRow($envelope->eventId, 'Alpha'));
        $this->assertNotNull($this->inboxRow($envelope->eventId, 'Beta'));
    }

    public function test_a_parking_consumer_does_not_block_the_other_consumer(): void
    {
        $calls = [];
        $parking = $this->consumer('Alpha', $calls, function (): void {
            throw new CausalPreconditionMissing('predecessor missing');
        });
        $envelope = $this->envelope();

        $this->dispatcher($parking, $this->consumer('Beta', $calls))->dispatch($envelope);

        $this->assertSame('parked', $this->inboxRow($envelope->eventId, 'Alpha')?->status);
        $this->assertSame('processed', $this->inboxRow($envelope->eventId, 'Beta')?->status, 'consumer isolation: Beta unaffected');
    }

    public function test_a_dead_lettering_consumer_does_not_block_the_other_consumer(): void
    {
        $calls = [];
        $failing = $this->consumer('Alpha', $calls, function (): void {
            throw new class('permanent') extends RuntimeException implements PermanentInboxFailure {
            };
        });
        $envelope = $this->envelope();

        $this->dispatcher($failing, $this->consumer('Beta', $calls))->dispatch($envelope);

        $this->assertSame('dead', $this->inboxRow($envelope->eventId, 'Alpha')?->status);
        $this->assertSame('processed', $this->inboxRow($envelope->eventId, 'Beta')?->status, 'consumer isolation: Beta unaffected');
    }

    public function test_redelivery_is_replay_safe_via_inbox_dedupe(): void
    {
        $calls = [];
        $consumer = $this->consumer('Alpha', $calls);
        $envelope = $this->envelope();

        $this->dispatcher($consumer)->dispatch($envelope);
        $this->dispatcher($consumer)->dispatch($envelope); // same event_id redelivered

        $this->assertSame(['Alpha'], $calls, 'handler invoked once; second delivery deduped');
        $this->assertSame(1, DB::table('inbox_events')->where('event_id', $envelope->eventId)->count());
    }

    public function test_no_registered_consumer_is_a_no_op(): void
    {
        $envelope = $this->envelope(type: 'ProbeEvent');

        $this->dispatcher(/* none */)->dispatch($envelope);

        $this->assertSame(0, DB::table('inbox_events')->where('event_id', $envelope->eventId)->count());
    }
}
