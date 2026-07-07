# Step C1 — The Port: Contracts a Consumer Speaks

**Layer:** Application (pure PHP — no Laravel).
**Namespace:** `App\Contexts\Shared\Application\Inbox`.
**Delivered by:** PB-003-C1 (`a421c2ef7`).

---

## Purpose

C1 defines the **vocabulary** of the Inbox before any machinery exists. It is the hexagonal *port*: the set of framework-free contracts that (a) a consuming context implements to receive events, and (b) the infrastructure uses to talk to that consumer. Nothing here knows about Eloquent, HTTP, queues, or the database. That purity is what lets the same handler be driven by fresh delivery, by recovery re-drive, and one day by replay — without change.

If you are writing a consumer, **this is the only namespace you import.** You never import anything from `...\Infrastructure\Inbox`.

---

## The contracts

### `InboxMessage` — the immutable carrier

One delivered event, crossing the port. A `final readonly` DTO — never an array (Application layer rule: no arrays).

```php
final readonly class InboxMessage
{
    /** @param array<string, mixed> $payload decoded event payload */
    public function __construct(
        public string $eventId,
        public string $eventType,
        public array $payload,
        public string $organisationId,
        public ?string $correlationId = null,
        public ?string $causationId = null,
    ) {}
}
```

Note what is **not** here: no `user_id`, no voter identity, no vote reference. The message carries the tenant (`organisationId`) and causal-trace identifiers (`correlationId`/`causationId`, D-06) so incidents can be filtered by tenant and traced end-to-end — but never anything that links a voter to a vote (ADR-T11).

### `InboxHandler` — the consumer contract

What your bounded context implements. Three methods, no more:

```php
interface InboxHandler
{
    public function consumerContext(): string;   // e.g. 'Election'
    public function eventTypes(): array;          // event types this handler accepts
    public function handle(InboxMessage $message): void;
}
```

`handle()` returns `void`. It does not decide its own outcome by return value — it signals outcome by **throwing a classification marker** (below) or returning normally (success). This keeps the outcome vocabulary closed and uniform across every consumer.

### `InboxOutcome` — the closed result set

An enum (a closed set of outcomes — the correct DDD form, not a value object):

```php
enum InboxOutcome
{
    case Processed;     // handled successfully (or a benign replay)
    case Duplicate;     // already recorded for this (event_id, consumer_context)
    case Parked;        // causal precondition missing — will be re-driven
    case DeadLettered;  // terminal failure — needs human/operational attention
}
```

---

## The classification markers — how a handler signals *why* it stopped

A handler communicates non-success by throwing one of three markers. The execution engine (C5) catches them and maps each to an outcome and a row-state transition. Choosing the right one is the single most important thing a handler author does.

| Throw this | Meaning | Inbox reaction |
|------------|---------|----------------|
| `CausalPreconditionMissing` | "I can't process this **yet** — an event I depend on hasn't arrived." | Row **parked**, re-driven later (bounded by a deadline). |
| `IdempotentReplay` | "I've effectively already done this; re-doing it is a no-op." | Row marked **processed**. |
| `PermanentInboxFailure` | "This can **never** succeed (malformed/contract violation)." | Row **dead-lettered** + alert logged. |
| *(any other `Throwable`)* | Unexpected/transient (DB blip, bug). | Transaction rolls back; row untouched; retried next tick. |

```php
// Marker with a reason, for out-of-order arrival:
final class CausalPreconditionMissing extends \RuntimeException
{
    public function reason(): string { /* ... */ }
}

// Pure marker interfaces (no behaviour):
interface IdempotentReplay {}
interface PermanentInboxFailure {}
```

### The decision rule for handler authors

Ask: *"Could a later re-drive succeed unchanged?"*

- **Yes, once something else arrives** → `CausalPreconditionMissing`. (Example: an Election handler receives `ChallengeResolved` before the `DeterminationIssued` it corrects.)
- **No — it's already done** → `IdempotentReplay`.
- **No — it can never work** → `PermanentInboxFailure`.
- **Don't know / unexpected** → let it throw. The transaction rolls back and the platform retries. Do **not** swallow it.

---

## Why this is a separate step

Defining the port first forces the contract to be designed on its own merits, free of persistence or framework concerns. Every later step (persistence, wrapper, registry, recovery) is an *adapter* to this port. Because the port is pure, the C6A architecture test `test_port_layer_is_framework_free` can assert — forever — that no `Illuminate\`, `Eloquent`, or `Carbon\` import ever leaks in.

---

## Testing

- `tests/Unit/Contexts/Shared/Inbox/InboxMessageTest.php` — the DTO carries and exposes its fields immutably.
- `tests/Unit/Contexts/Shared/Inbox/InboxClassificationTest.php` — the markers map to the intended outcomes (exercised against the engine in C5).

These are pure unit tests — no database, no `RefreshDatabase` needed.

---

## Traceability

Blueprint §6 · ADR-T4 (idempotent consumer) · ADR-T11 (anonymity) · D-06 (tenant/correlation propagation) · D-08 (hydrator receives decoded payload only).
