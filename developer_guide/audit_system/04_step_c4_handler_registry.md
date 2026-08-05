# Step C4 — The Routing Seam: `InboxHandlerRegistry`

**Layer:** Infrastructure (pure PHP; container singleton).
**Namespace:** `App\Contexts\Shared\Infrastructure\Inbox\InboxHandlerRegistry`.
**Delivered by:** PB-003-C4 (`3bc0b35dc`).

---

## Purpose

C4 answers one question: *given a delivered event, which handler runs it?* The registry maps `(consumer_context, event_type)` to exactly one `InboxHandler`. It is the **architectural boundary** between generic messaging infrastructure and bounded-context business code:

- Shared Infrastructure owns *registration and lookup*.
- Each consuming context owns *its handlers* and registers them from *its own* service provider.

Because of that split, adding a sixth bounded context requires **registration only** — no edit to Shared Infrastructure. That is the Open/Closed Principle made concrete, and it is what "reusable platform capability" means in practice.

---

## The contract

```php
final class InboxHandlerRegistry
{
    /** @var array<string, InboxHandler> keyed by "consumerContext\0eventType" */
    private array $handlers = [];

    public function register(InboxHandler $handler): void
    {
        $context = $handler->consumerContext();

        foreach ($handler->eventTypes() as $eventType) {
            $key = $this->key($context, $eventType);

            if (isset($this->handlers[$key])) {
                throw new \LogicException(/* already registered — one handler per pair */);
            }
            $this->handlers[$key] = $handler;
        }
    }

    /** @throws UnregisteredInboxHandler when no handler is registered for the pair */
    public function handlerFor(string $consumerContext, string $eventType): InboxHandler
    {
        return $this->handlers[$this->key($consumerContext, $eventType)]
            ?? throw new UnregisteredInboxHandler($consumerContext, $eventType);
    }

    public function has(string $consumerContext, string $eventType): bool { /* ... */ }

    private function key(string $consumerContext, string $eventType): string
    {
        return $consumerContext . "\0" . $eventType;   // NUL separator — unambiguous
    }
}
```

### Keyed by the *pair*, on purpose

The key is `(consumer_context, event_type)`, mirroring the dedupe key from C2. This is an intentional divergence from the producer-side `EventHydratorRegistry`, which keys by `event_type` alone. The reason is the same as D-03: the same event type is legitimately handled by multiple contexts, each with its own handler. Keying by the pair lets Election and Contestation both register a handler for `DeterminationIssued` without collision.

### Two failure modes, both loud

- **Duplicate registration** (two handlers claim the same pair) → `LogicException` at boot. This is a programming error; it should crash startup, not smuggle ambiguity into production.
- **Unknown pair at lookup** → `UnregisteredInboxHandler`. This is *not* transient — retrying cannot conjure a handler — so the recovery layer dead-letters the row immediately with reason `UNREGISTERED_INBOX_HANDLER` (see C5). Silence here would mean events vanishing without trace; instead they dead-letter loudly.

```php
final class UnregisteredInboxHandler extends \RuntimeException
{
    public function deadLetterReason(): string { return 'UNREGISTERED_INBOX_HANDLER'; }
}
```

---

## Wiring: register from *your* provider, not Shared

The registry is a container singleton (bound in `AppServiceProvider`). Each context registers its handlers into that singleton from its own service provider. Conceptually, PB-004's Election provider will do:

```php
// app/Contexts/Election/Infrastructure/Providers/ElectionEventProvider.php (PB-004)
$this->app->afterResolving(InboxHandlerRegistry::class, function (InboxHandlerRegistry $registry) {
    $registry->register(new ApplyDeterminationCorrectionHandler(/* ...context deps... */));
});
```

Note the direction of the dependency: the **Election** context imports the Shared registry and registers *into* it. Shared never imports Election. The C6A test `test_messaging_layer_imports_no_bounded_context` enforces that this direction is never reversed.

---

## How to add a new consumer (checklist)

1. Write a handler in your context implementing `InboxHandler` (C1). Return your context name from `consumerContext()`; list the event types you accept.
2. Inside `handle()`, do the work; throw the right classification marker on non-success (C1).
3. Register the handler from your context's service provider into the `InboxHandlerRegistry` singleton.
4. Add a wiring test (see below). **Do not touch any file under `App\Contexts\Shared`.** If you find yourself needing to, the design has drifted — stop and review.

---

## Testing

- `tests/Unit/Contexts/Shared/Inbox/InboxHandlerRegistryTest.php` — register/lookup/`has`; duplicate pair throws `LogicException`; unknown pair throws `UnregisteredInboxHandler` carrying the dead-letter reason; the same event type registered under two different contexts coexists.
- `tests/Feature/Contexts/Shared/Inbox/InboxHandlerRegistryWiringTest.php` — the container singleton resolves and retains registrations.

---

## Traceability

Blueprint §6 · ADR-T4 · D-03 (key includes consumer_context) · ER-03 (justified divergence from `EventHydratorRegistry`).
