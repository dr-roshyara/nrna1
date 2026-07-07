# Step C5 — The Recovery Layer: Execution Engine + Redrive

**Layer:** Infrastructure.
**Namespaces:** `App\Contexts\Shared\Infrastructure\Inbox\{InboxExecutionEngine, RedriveParkedInboxEvents}`, `App\Console\Commands\RedriveInboxEvents`.
**Config:** `config/inbox.php`.
**Delivered by:** PB-003-C5 (`ce7f2b99d`).

---

## Purpose

C5 makes recovery **deterministic**. Out-of-order events get parked (C3); C5 is what brings them back. It introduces two components with a strict division of labour:

- **`InboxExecutionEngine`** — the *one* place that runs a handler and classifies the result. Both fresh delivery (`Inbox::consume`, C3) and recovery (`RedriveParkedInboxEvents`) delegate to it.
- **`RedriveParkedInboxEvents`** — the *one* place that owns retry scheduling: select due parked rows, enforce the deadline, and re-invoke through the engine.

Keeping these separate is what stops `Inbox` from accumulating runtime behaviour. There is exactly one classifier and exactly one scheduler — a property the C6A tests enforce by cardinality (see the next guide).

---

## `InboxExecutionEngine` — run once, classify once

```php
final class InboxExecutionEngine
{
    public function __construct(private readonly ClockInterface $clock) {}

    public function execute(InboxEvent $row, InboxMessage $message, InboxHandler $handler): InboxOutcome
    {
        try {
            $handler->handle($message);
            $row->markProcessed();
            return InboxOutcome::Processed;

        } catch (CausalPreconditionMissing) {
            $now = $this->clock->now();
            $retry = (int) config('inbox.park_retry_minutes', 5);
            $deadlineMinutes = (int) config('inbox.park_deadline_minutes', 60);

            // ABSOLUTE horizon: set at first park, PRESERVED across re-parks.
            $deadline = $row->park_deadline?->toDateTimeImmutable()
                ?? $now->modify("+{$deadlineMinutes} minutes");

            $row->markParked($now->modify("+{$retry} minutes"), $deadline);
            return InboxOutcome::Parked;

        } catch (IdempotentReplay) {
            $row->markProcessed();
            return InboxOutcome::Processed;

        } catch (PermanentInboxFailure $e) {
            $row->markDead();
            Log::error('Inbox message dead-lettered', [
                'dead_letter_reason' => 'PERMANENT_HANDLER_FAILURE',
                'event_id' => $row->event_id,
                'consumer_context' => $row->consumer_context,
                'event_type' => $row->event_type,
                'organisation_id' => $row->organisation_id,
                'error' => $e->getMessage(),
            ]);
            return InboxOutcome::DeadLettered;
        }
        // Any other Throwable propagates → the caller's transaction rolls back.
    }
}
```

Two design points that are easy to get wrong:

### The deadline is absolute and preserved

`park_deadline` is the point past which an event stops being retried and is dead-lettered instead. It is set **once**, on the first park, and re-parks reuse it (`$row->park_deadline ?? now + deadline`). If instead each re-park recomputed the deadline from "now", the deadline would slide forward on every attempt and the row would retry **forever** — the classic recovery-storm bug. `parked_until` (the *next* attempt time) moves each re-park; `park_deadline` (the *give-up* time) does not.

### Time is injected, never discovered

The engine takes a `ClockInterface` and uses `$this->clock->now()`. It never calls the global `now()`. This is temporal determinism: a re-drive decision must be reproducible, and that requires the instant to be an input, not ambient state. In tests you inject a `FrozenClock`; in production the container binds `SystemClock`. (Audit stamps like `processed_at` may still use framework time — they are metadata, not decisions.)

### The engine owns no transaction

`execute()` assumes it is *already inside* a transaction opened by its caller (C3's `consume()` or redrive's per-row transaction). A classified outcome marks the row; any *unexpected* throwable propagates so the caller can roll back. This keeps transaction ownership with the boundary components (ADR-T1) and lets the engine be reused by anything — including a future replay driver.

---

## `RedriveParkedInboxEvents` — the scheduler

```php
final class RedriveParkedInboxEvents
{
    public function __construct(
        private readonly InboxHandlerRegistry $registry,
        private readonly InboxExecutionEngine $engine,
        private readonly ClockInterface $clock,
    ) {}

    public function handle(): void
    {
        $now = $this->clock->now();

        InboxEvent::query()
            ->parkedDue($now)                 // injected instant — the one authoritative clock
            ->orderBy('parked_until')
            ->get()
            ->each(fn (InboxEvent $row) => $this->redriveOne($row->getKey()));
    }

    private function redriveOne(string $id): void
    {
        DB::transaction(function () use ($id): void {
            $row = InboxEvent::query()->lockForUpdate()->find($id);

            // Concurrency guard: another worker may have finalised it first.
            if ($row === null || $row->status !== 'parked') {
                return;
            }

            $now = $this->clock->now();

            // Past the absolute deadline → dead-letter without re-invoking.
            if ($row->park_deadline !== null && $row->park_deadline->toDateTimeImmutable() <= $now) {
                $row->markDead();
                $this->alert($row, 'PARK_DEADLINE_EXCEEDED');
                return;
            }

            try {
                $handler = $this->registry->handlerFor($row->consumer_context, $row->event_type);
            } catch (UnregisteredInboxHandler $e) {
                $row->markDead();
                $this->alert($row, $e->deadLetterReason());
                return;
            }

            $message = new InboxMessage(
                eventId: $row->event_id,
                eventType: $row->event_type,
                payload: $row->payload,
                organisationId: $row->organisation_id,
                correlationId: $row->correlation_id,
                causationId: $row->causation_id,
            );

            $this->engine->execute($row, $message, $handler); // SAME engine as consume()
        });
    }
}
```

What redrive does — and, importantly, does **not** do:

- **It selects, checks the deadline, resolves the handler, and delegates.** No business logic. The handler owns all business behaviour.
- **Each row runs in its own transaction with a row lock**, then re-checks `status === 'parked'`. This makes concurrent redrive runs safe: if two schedulers pick up the same row, one finalises it and the other sees a non-parked row and skips.
- **It rebuilds the `InboxMessage` from the persisted row** and calls the same engine `consume()` uses. This is the recovery invariant: *re-invocation can only produce outcomes reachable by normal delivery — same handler, same message, only the timing differs.* Recovery can never manufacture a business outcome that ordinary processing couldn't (a property documented under D-10 and reviewed at every step).

---

## The command and the schedule

```php
// app/Console/Commands/RedriveInboxEvents.php
final class RedriveInboxEvents extends Command
{
    protected $signature = 'inbox:redrive';

    public function handle(RedriveParkedInboxEvents $redrive): int
    {
        try {
            $redrive->handle();
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Inbox re-drive failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
```

```php
// routes/console.php — beside outbox:process
Schedule::command('inbox:redrive')->everyMinute();
```

The command is a thin shell that mirrors `outbox:process` (sibling consistency, ER-03) and delegates to the service. It carries no logic of its own.

---

## Config

```php
// config/inbox.php
return [
    'park_retry_minutes'    => (int) env('INBOX_PARK_RETRY_MINUTES', 5),
    'park_deadline_minutes' => (int) env('INBOX_PARK_DEADLINE_MINUTES', 60),
];
```

The retry *policy* (bounded retries → dead-letter) is architectural and fixed; the *numbers* are operational (decision D-05). Operations can tune cadence and give-up window via env without a code change or an ADR.

---

## Failure classification, end to end

| Condition at redrive | Action | Outcome |
|----------------------|--------|---------|
| parked, due, precondition now met | re-invoke handler | `Processed` |
| parked, due, precondition still missing | re-park (new `parked_until`, `attempts++`, deadline preserved) | `Parked` |
| parked, due, past `park_deadline` | dead-letter, no re-invoke | `DeadLettered` (`PARK_DEADLINE_EXCEEDED`) |
| parked, handler unregistered | dead-letter | `DeadLettered` (`UNREGISTERED_INBOX_HANDLER`) |
| already `processed`/`dead` | not selected | — |
| unexpected throwable during re-invoke | transaction rolls back, row stays `parked` | retried next tick |

---

## Running it locally

`inbox:redrive` needs the `inbox_events` table to exist in the target database. It is created by the migration on the **test** database (via `RefreshDatabase`) and in any environment where you have run migrations. **Do not** run `migrate:fresh`/`migrate:refresh` against the development database to make it work — that is forbidden project-wide. The recovery logic is proven by the feature tests below, which run against the test DB.

---

## Testing

`tests/Feature/Contexts/Shared/Inbox/InboxRedriveTest.php` (uses `RefreshDatabase`, `Carbon::setTestNow`, and an injected `FrozenClock`): due → processed; not-yet-due → untouched; past-deadline → dead (no handler call); handler missing → dead; re-attempt that still fails → re-parked with `attempts++` **and deadline preserved**; running redrive twice → processed exactly once (determinism); processed/dead never selected.

---

## Traceability

Blueprint §7 (F4 recovery) / §7.1 · ADR-T1 (row-per-txn) · ADR-T4 · D-05 (configurable numbers) · D-10 (retry timing owned by redrive; engine extraction; injected time) · reuses `App\Domain\Shared\Clock\ClockInterface`.
