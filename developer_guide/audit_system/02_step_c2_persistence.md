# Step C2 — The Audit Ledger: `inbox_events`

**Layer:** Infrastructure (Eloquent allowed).
**Namespace:** `App\Contexts\Shared\Infrastructure\Inbox\InboxEvent`.
**Migration:** `database/migrations/2026_07_06_000001_create_inbox_events_table.php`.
**Delivered by:** PB-003-C2 (`cec36ee07`).

---

## Purpose

C2 gives the Inbox its memory. Every attempt to consume an event becomes one durable row in `inbox_events`. That row is two things at once:

1. a **dedupe record** — it makes "process this at most once per consumer" enforceable at the database level; and
2. an **audit trail** — a permanent, queryable statement of *what a context was asked to process and what became of it*.

This is the "audit_system" the folder is named for: not a log of who voted, but a ledger of how the platform's events were consumed.

---

## The table

```php
Schema::create('inbox_events', function (Blueprint $table) {
    $table->uuid('id')->primary();

    $table->string('event_id');            // the producer's event identity
    $table->string('consumer_context');    // e.g. 'Election', 'Contestation'
    $table->string('event_type');
    $table->json('payload');

    $table->uuid('organisation_id');       // tenant — NOT NULL, FK to organisations
    $table->uuid('correlation_id')->nullable();
    $table->uuid('causation_id')->nullable();

    $table->enum('status', ['processed', 'parked', 'dead']); // NO default — always explicit
    $table->integer('park_attempts')->default(0);
    $table->timestamp('parked_until')->nullable();
    $table->timestamp('park_deadline')->nullable();
    $table->timestamp('processed_at')->nullable();
    $table->timestamp('created_at')->useCurrent();

    $table->unique(['event_id', 'consumer_context']);   // the dedupe key (D-03)
    $table->index(['status', 'parked_until']);          // redrive selection
    $table->index(['organisation_id', 'created_at']);   // tenant-scoped audit queries
    $table->foreign('organisation_id')->references('id')->on('organisations');
});
```

### The dedupe key is `(event_id, consumer_context)` — not `event_id` alone

This is decision **D-03**, and it matters. The *same* event is legitimately consumed by *more than one* context — a `DeterminationIssued` is processed by both Election (to apply a correction) and Contestation (to resolve the challenge). A global `event_id` primary key would block the second legitimate consumer. Keying by the pair gives each consumer its own idempotency slot.

### Deliberate differences from `outbox_events`

The table mirrors the outbox conventions (UUID PK, tenant + causal identifiers, `useCurrent` created_at) with two intentional divergences:

- **No status default.** The status enum has no default value; every row is created with an explicit status. An implicit initial state is exactly the kind of ambiguity you do not want in an audit ledger.
- **Park bookkeeping columns** (`park_attempts`, `parked_until`, `park_deadline`) — the outbox has no equivalent because parking is a consumer-side concern.

---

## The `InboxEvent` row gateway

An Eloquent model, but a thin one. It owns *state transitions*, nothing else — no business logic, no classification.

```php
final class InboxEvent extends Model
{
    use HasUuids;

    protected $table = 'inbox_events';
    public const UPDATED_AT = null;   // transitions write their own explicit timestamps

    // casts: payload => json; park_attempts => integer; the *_at columns => datetime

    public function markProcessed(): void
    {
        $this->update(['status' => 'processed', 'processed_at' => now()]);
    }

    public function markParked(\DateTimeInterface $parkedUntil, \DateTimeInterface $parkDeadline): void
    {
        $this->update([
            'status'        => 'parked',
            'park_attempts' => $this->park_attempts + 1,
            'parked_until'  => $parkedUntil,
            'park_deadline' => $parkDeadline,
        ]);
    }

    public function markDead(): void
    {
        $this->update(['status' => 'dead', 'processed_at' => now()]);
    }

    // Parked rows whose re-drive time has arrived. $asOf is REQUIRED (see C6A strict clock).
    public function scopeParkedDue($query, \DateTimeInterface $asOf)
    {
        return $query->where('status', 'parked')
            ->whereNotNull('parked_until')
            ->where('parked_until', '<=', $asOf);
    }
}
```

### The status lifecycle

```text
                 (created by Inbox::consume)
                          │
                          ▼
                       parked ──────────────┐
                     │   │   ▲               │  handler throws
   handler succeeds  │   │   │ re-park       │  PermanentInboxFailure
   / IdempotentReplay│   │   │ (attempts++,  │  OR deadline exceeded
                     ▼   │   │  deadline      ▼  OR handler unregistered
                 processed│  │  preserved)  dead
                          └──┘
                   (CausalPreconditionMissing)
```

- **`processed`** and **`dead`** are terminal.
- **`parked`** is the only re-entrant state, and only the recovery layer (C5) may move a parked row — never fresh delivery.

### Time in this model

`markProcessed()`/`markDead()` stamp `processed_at` with framework `now()`. That is **audit metadata**, not a decision input, so ambient time is acceptable there (and the C6A clock allow-list explicitly permits it). Anything that drives a *decision* — the re-drive due-check in `scopeParkedDue()` — takes an **injected** instant instead (`$asOf`). More on that in the C5 and C6A guides.

---

## Querying the ledger (operations & disputes)

Because the row carries `organisation_id` and `created_at` (indexed together), audit queries are cheap and tenant-scoped:

```php
// Everything a tenant's Election consumer was asked to process, newest first:
InboxEvent::query()
    ->where('organisation_id', $orgId)
    ->where('consumer_context', 'Election')
    ->latest('created_at')
    ->get();

// Anything currently stuck as parked, or dead-lettered and needing attention:
InboxEvent::query()->whereIn('status', ['parked', 'dead'])->get();
```

None of these expose voter identity — the payload is domain event data governed by the anonymity rule at the producer.

---

## Testing

`tests/Feature/Contexts/Shared/Inbox/InboxEventTest.php` (uses `RefreshDatabase`):

- persists with identifiers and explicit defaults;
- the same `event_id` is allowed for a **different** `consumer_context` (D-03);
- a duplicate `(event_id, consumer_context)` pair violates the UNIQUE constraint;
- `organisation_id` is mandatory;
- state transitions persist (`markProcessed`/`markParked`/`markDead`);
- `parkedDue($asOf)` returns only parked rows that are due (note: `$asOf` is required — inject an instant).

---

## Traceability

Blueprint §6/§7 · ADR-T4 · D-03 (dedupe key) · D-06 (tenant/correlation ids).
