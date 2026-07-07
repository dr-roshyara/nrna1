<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Shared\Inbox;

use App\Contexts\Shared\Application\Inbox\CausalPreconditionMissing;
use App\Contexts\Shared\Application\Inbox\InboxHandler;
use App\Contexts\Shared\Application\Inbox\InboxMessage;
use App\Contexts\Shared\Infrastructure\Inbox\InboxEvent;
use App\Contexts\Shared\Infrastructure\Inbox\InboxHandlerRegistry;
use App\Contexts\Shared\Infrastructure\Inbox\RedriveParkedInboxEvents;
use App\Domain\Shared\Clock\ClockInterface;
use App\Infrastructure\Shared\Clock\FrozenClock;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * PB-003-C5 — inbox:redrive recovery: deterministic, deadline-bounded re-drive.
 *
 * Owner: Shared Infrastructure · Layer: Infrastructure
 * Traceability: Blueprint §7 F4, §7.1 Recovery · ADR-T4 · Matrix: Inbox
 * Time is injected (FrozenClock) — execution never discovers time (R2).
 */
final class InboxRedriveTest extends TestCase
{
    use RefreshDatabase;

    private string $orgId;
    private \DateTimeImmutable $t;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orgId = Organisation::factory()->create()->id;
        // One authoritative test instant for both the query (Carbon) and the engine (FrozenClock).
        $this->t = new \DateTimeImmutable('2026-07-06T12:00:00+00:00');
        Carbon::setTestNow(Carbon::instance(\Carbon\Carbon::parse($this->t->format(DATE_ATOM))));
        $this->app->instance(ClockInterface::class, new FrozenClock($this->t));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function parkedRow(array $overrides = []): InboxEvent
    {
        return InboxEvent::create(array_merge([
            'event_id' => (string) Str::uuid(),
            'consumer_context' => 'Contestation',
            'event_type' => 'DeterminationIssued',
            'payload' => ['probe' => true],
            'organisation_id' => $this->orgId,
            'status' => 'parked',
            'park_attempts' => 1,
            'parked_until' => $this->t->modify('-1 minute'),   // due by default
            'park_deadline' => $this->t->modify('+1 hour'),    // not past deadline
        ], $overrides));
    }

    /** @param callable(InboxMessage):void $onHandle */
    private function register(callable $onHandle, string $context = 'Contestation', string $type = 'DeterminationIssued'): object
    {
        $handler = new class($onHandle, $context, $type) implements InboxHandler {
            public int $calls = 0;
            /** @param callable(InboxMessage):void $onHandle */
            public function __construct(private $onHandle, private string $ctx, private string $type)
            {
            }
            public function consumerContext(): string
            {
                return $this->ctx;
            }
            public function eventTypes(): array
            {
                return [$this->type];
            }
            public function handle(InboxMessage $message): void
            {
                $this->calls++;
                ($this->onHandle)($message);
            }
        };
        $this->app->make(InboxHandlerRegistry::class)->register($handler);

        return $handler;
    }

    private function redrive(): void
    {
        $this->app->make(RedriveParkedInboxEvents::class)->handle();
    }

    public function test_due_parked_row_is_reinvoked_and_processed(): void
    {
        $handler = $this->register(fn () => null);
        $row = $this->parkedRow();

        $this->redrive();

        $this->assertSame(1, $handler->calls);
        $this->assertSame('processed', $row->fresh()->status);
    }

    public function test_not_yet_due_parked_row_is_untouched(): void
    {
        $handler = $this->register(fn () => null);
        $row = $this->parkedRow(['parked_until' => $this->t->modify('+10 minutes')]);

        $this->redrive();

        $this->assertSame(0, $handler->calls);
        $this->assertSame('parked', $row->fresh()->status);
    }

    public function test_past_deadline_row_is_dead_lettered_without_invoking_handler(): void
    {
        $handler = $this->register(fn () => null);
        $row = $this->parkedRow(['park_deadline' => $this->t->modify('-1 second')]);

        $this->redrive();

        $this->assertSame(0, $handler->calls, 'past-deadline row must NOT re-invoke the handler');
        $this->assertSame('dead', $row->fresh()->status);
    }

    public function test_missing_handler_dead_letters(): void
    {
        // No handler registered for this (context, type).
        $row = $this->parkedRow(['consumer_context' => 'NobodyRegistered']);

        $this->redrive();

        $this->assertSame('dead', $row->fresh()->status);
    }

    public function test_reattempt_that_still_fails_reparks_and_increments_attempts(): void
    {
        $handler = $this->register(function (): void {
            throw new CausalPreconditionMissing('still not ready');
        });
        $row = $this->parkedRow(['park_attempts' => 1]);
        $originalDeadline = $row->park_deadline;

        $this->redrive();

        $fresh = $row->fresh();
        $this->assertSame('parked', $fresh->status);
        $this->assertSame(2, $fresh->park_attempts, 'attempts must increment');
        $this->assertSame(1, $handler->calls);
        $this->assertEquals(
            $originalDeadline->format('Y-m-d H:i:s'),
            $fresh->park_deadline->format('Y-m-d H:i:s'),
            'deadline is an absolute horizon — must be PRESERVED across re-parks, never slid'
        );
    }

    public function test_repeated_redrive_is_deterministic_and_processes_once(): void
    {
        $handler = $this->register(fn () => null);
        $row = $this->parkedRow();

        $this->redrive();
        $this->redrive();   // second run: row already processed → not selected

        $this->assertSame(1, $handler->calls, 'determinism: processed exactly once (Rule 1/4)');
        $this->assertSame('processed', $row->fresh()->status);
    }

    public function test_processed_and_dead_rows_are_never_selected(): void
    {
        $handler = $this->register(fn () => null);
        $this->parkedRow(['status' => 'processed', 'parked_until' => $this->t->modify('-1 minute')]);
        $this->parkedRow(['status' => 'dead', 'parked_until' => $this->t->modify('-1 minute')]);

        $this->redrive();

        $this->assertSame(0, $handler->calls);
    }
}
