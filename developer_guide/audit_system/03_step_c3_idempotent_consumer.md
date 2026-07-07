# Step C3 — The Idempotent Consumer: `Inbox::consume()`

**Layer:** Infrastructure.
**Namespace:** `App\Contexts\Shared\Infrastructure\Inbox\Inbox`.
**Delivered by:** PB-003-C3 (`8930f47fd`).

---

## Purpose

C3 is the **entry point for fresh delivery**. When the relay hands an event to a context, that context calls `Inbox::consume($message, $handler)`. The wrapper's one job is to make handling **idempotent**: claim the `(event_id, consumer_context)` slot exactly once, then run the handler inside the *same* transaction, so the claim and the effect commit or roll back together.

`consume()` is deliberately small. It does **not** classify handler outcomes and it does **not** re-drive parked rows — that logic lives in the execution engine and the recovery layer (C5). This is what keeps `Inbox` from growing into a God Service.

---

## The one-transaction contract (ADR-T1)

Everything happens in a single `DB::transaction`:

```php
public function consume(InboxMessage $message, InboxHandler $handler): InboxOutcome
{
    $context = $handler->consumerContext();

    try {
        return DB::transaction(function () use ($message, $handler, $context): InboxOutcome {
            // 1. Short-circuit if this (event_id, consumer_context) is already recorded.
            $existing = InboxEvent::query()
                ->where('event_id', $message->eventId)
                ->where('consumer_context', $context)
                ->lockForUpdate()
                ->first();

            if ($existing !== null) {
                return match ($existing->status) {
                    'processed' => InboxOutcome::Duplicate,
                    'dead'      => InboxOutcome::DeadLettered,
                    default     => InboxOutcome::Parked, // parked: re-drive (C5) owns re-attempts
                };
            }

            // 2. Claim the slot.
            $row = InboxEvent::create([
                'event_id' => $message->eventId,
                'consumer_context' => $context,
                'event_type' => $message->eventType,
                'payload' => $message->payload,
                'organisation_id' => $message->organisationId,
                'correlation_id' => $message->correlationId,
                'causation_id' => $message->causationId,
                'status' => 'parked',
                'park_attempts' => 0,
            ]);

            // 3. Run + classify in the SAME transaction (shared engine, see C5).
            return $this->engine->execute($row, $message, $handler);
        });
    } catch (QueryException $e) {
        // 4. Concurrent claim of the same pair: the other worker won → Duplicate.
        if ($this->isUniqueViolation($e)) {
            return InboxOutcome::Duplicate;
        }
        throw $e;
    }
}
```

### Why each part exists

1. **`lockForUpdate` on the existing row.** Two workers may deliver the same event at the same moment. The row lock serialises them: the first to reach an existing row reads its status; the second waits.
2. **Claim as `parked`, `park_attempts = 0`.** A freshly inserted row starts parked. If the handler succeeds, the engine immediately flips it to `processed` inside the same transaction — so a successful consume never *looks* parked to anyone. Parking only persists if the handler throws `CausalPreconditionMissing`.
3. **Delegate to the engine.** `Inbox::consume` never inspects payload semantics or catches classification markers. It hands `($row, $message, $handler)` to `InboxExecutionEngine::execute()` and returns whatever outcome that yields.
4. **Unique-violation fallback.** If two workers both pass the existence check and both try to `create()`, the database UNIQUE constraint on `(event_id, consumer_context)` rejects the loser with a `QueryException`. That is not an error — it is proof the event is a duplicate, so we return `Duplicate`. `isUniqueViolation()` recognises Postgres `23505`, MySQL `1062`, and SQLite's `UNIQUE constraint failed`.

---

## Outcomes you get back

| Returned | When |
|----------|------|
| `Processed` | Handler ran to completion (or signalled `IdempotentReplay`). |
| `Duplicate` | The pair was already `processed`, or a concurrent claim won the race. |
| `Parked` | Handler signalled `CausalPreconditionMissing`, or the row was already parked from a prior attempt. |
| `DeadLettered` | Handler signalled `PermanentInboxFailure`, or the row was already `dead`. |

A **parked row is never re-invoked by `consume()`.** If a re-delivery arrives for an already-parked event, `consume()` simply reports `Parked` and returns. Retry timing is owned *solely* by the scheduler (decision **D-10**) — this is what makes recovery deterministic instead of driven by however often the relay happens to re-deliver.

---

## How a caller uses it

The relay/dispatcher (wired in PB-004+) looks up the handler and calls consume. Conceptually:

```php
$handler = $registry->handlerFor($context, $message->eventType); // C4
$outcome = $inbox->consume($message, $handler);                  // C3

// The dispatcher does nothing business-specific with $outcome beyond
// logging/metrics — the handler already did the work (or parked/failed).
```

You typically do **not** call `consume()` by hand in business code. You implement an `InboxHandler` (C1) and register it (C4); the platform calls `consume()` for you.

---

## Pitfalls

- **Don't open your own transaction inside a handler** expecting to commit independently — you are already inside `consume()`'s transaction. If you throw, your writes roll back with the claim. That is the point.
- **Don't catch-and-swallow** in a handler to "avoid a park." Swallowing turns a recoverable out-of-order case into a silent, permanent miss. Throw the right marker instead.
- **Don't rely on `park_attempts` incrementing during a fresh consume** — a fresh claim is `attempts = 0`; increments happen only on re-drive (C5).

---

## Testing

`tests/Feature/Contexts/Shared/Inbox/InboxConsumeTest.php` covers the seven scenarios: first success → `Processed`; duplicate of processed → `Duplicate`; concurrent unique-violation → `Duplicate`; precondition missing → `Parked` (+ row parked); permanent failure → `DeadLettered`; idempotent replay → `Processed`; re-delivery of a parked row → `Parked` without re-invoking the handler.

---

## Traceability

Blueprint §6/§7/§8 · ADR-T1 (one txn) · ADR-T4 (dedupe) · D-10 (retry timing owned by redrive).
