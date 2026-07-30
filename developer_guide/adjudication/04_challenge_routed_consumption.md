# WP-4 — Consuming `ChallengeRouted`: the correction loop's head fires

**Step:** WP-4 (APM wiring) · **Status:** GREEN, awaiting slice acceptance
**Purpose:** connect Contestation's published `ChallengeRouted` to the Adjudication Process Manager, so a routed challenge actually opens an adjudication.

WP-2 built the process manager but wired nothing (*"transport wiring is WP-4"*). WP-3A published the event but nothing consumed it. **This step closes that gap — and it is the whole step.** No new architectural concept: it is the **fourth** instance of an established, machine-enforced consumer pattern.

---

## Where it fits

```
Contestation (producer)                    Shared platform                  Adjudication (consumer)
───────────────────────                    ───────────────                  ───────────────────────
Challenge::route()
  → ChallengeRouted (domain event)  ← NEVER crosses the boundary
  → ChallengeOutboxAdapter::writeRouted()
       payload {schema_version:1, challengeId, routedTo, occurredAt}
                    │
                    ▼
              outbox_events row
                    │  OutboxEventProcessor::handle()
                    ├─ ChallengeRoutedHydrator  ← producer-side ONLY (contract R-3)
                    ▼
              IntegrationEvent (+ correlation/causation)
                    │  IntegrationEventDispatcher → one message per consumer
                    ▼
                              InboxMessage  ← THE CROSSING: primitives only
                                    │  Inbox::consume() — dedupe (event_id, consumer_context)
                                    ▼
                                          ChallengeRoutedReactionHandler::handle()
                                            → ChallengeRef::fromString(payload['challengeId'])
                                            → AdjudicationProcessManager::openFor()
                                            → adjudication_processes row, status = Opened
```

## Key files

| File | Role |
|------|------|
| `app/Contexts/Adjudication/Application/ChallengeRoutedReactionHandler.php` | **New.** The consumer: `handle(InboxMessage)` → local `ChallengeRef` → PM-1 |
| `app/Contexts/Adjudication/Infrastructure/Providers/AdjudicationServiceProvider.php` | **Changed** (`boot()`): registers the handler with `InboxHandlerRegistry` |
| `tests/Feature/Contexts/Adjudication/ChallengeRoutedConsumptionTest.php` | **New.** 6 keystone tests over the REAL path |

## How it works

The handler is deliberately three lines of behaviour:

```php
public function handle(InboxMessage $message): void
{
    $this->processes->openFor(
        ChallengeRef::fromString($this->stringField($message->payload, 'challengeId')),
    );
}
```

It declares **who** consumes and **what**, which is how the platform resolves it:

```php
public function consumerContext(): string { return 'Adjudication'; }

/** @return list<string> */
public function eventTypes(): array { return ['ChallengeRouted']; }
```

Registration is **consumer-side** — PB-006's permanent principle *Registration ≠ Delivery*. Contestation never names Adjudication:

```php
// AdjudicationServiceProvider::boot()
$inboxRegistry = $this->app->make(InboxHandlerRegistry::class);
$handler = $this->app->make(ChallengeRoutedReactionHandler::class);
$inboxRegistry->register($handler);
```

## Design decisions — including four defended absences

The interesting content of this step is what it does **not** contain. Each absence was decided, not skipped:

| Decision | Why |
|---|---|
| **No outcome translator** (gate **G-2**) | *No application-owned outcome-translation responsibility exists in this scope.* Duplicate delivery is the **platform's** seat (inbox dedupe, ADR-T3/T4); a *distinct* message for an already-adjudicated challenge is **PM-1's** seat (its own idempotency); malformed payloads already fail loudly at the producer's hydrator. Nothing was left for an application component to own. |
| **No local `RoutedTo` value object** | `routedTo` is a string the producer records and **no consumer needs** — `openFor()` takes only the challenge identity. Creating a VO would be a component without authority. |
| **No import of Contestation's `ChallengeRouted` class** | Contract **R-1** / ADR-T16: a consumer never imports another context's Domain. Only primitives cross. |
| **No import of Contestation's hydrator** | Contract **R-2/R-3**: the hydrator is *producer-side Infrastructure*. This was a real near-miss — importing it would have created the codebase's **first** cross-context Infrastructure dependency and failed Deptrac. |
| **No provenance minting** | This slice publishes nothing. When Adjudication does publish, it continues the conversation with `EventProvenance::fromConsumed($message->correlationId, $message->eventId)` — never `start()` (contract R-6 · ADR-MP-06). |

**The rule behind all of them:** *a component is introduced only when it owns a responsibility not already owned by the platform or the domain.* No authority → do not create it.

## Testing

Six keystones, all driven through the **real** path — `ChallengeEventOutbox::enqueue()` → `OutboxEventProcessor::handle()` → dispatcher → inbox. Nothing is mocked, so the tests prove the integration rather than a stand-in for it:

| Keystone | Proves |
|---|---|
| Opens exactly one process | The loop head fires; status is `Opened`; `challengeRef` round-trips |
| Redelivery opens no second | Platform dedupe (ADR-T3/T4) |
| A *second routing* opens no second | **PM-1's own** idempotency — dedupe cannot help here, the message is distinct |
| Registration resolves | `has('Adjudication','ChallengeRouted')` — PB-006 |
| Consumption continues the conversation | The inbox row carries the **incoming** correlation (ADR-MP-06) |
| No Contestation side effect | Contestation does not consume its own published routing |

**Gates:** PHPStan max — no errors · Deptrac — **0 violations** · Architecture suite 146 tests green. The 17 Membership failures in `tests/Feature/Contexts` are **pre-existing** (git-stash-verified; AD-008), not caused by this step.

## Pitfalls

- **Don't reach for the producer's hydrator** to "reuse" its parsing. It is producer-side; the consumer reads primitives. This is the single mistake this step was most likely to make.
- **Don't add idempotency inside the handler.** Two seats already exist (platform dedupe + PM-1). A third would be unowned duplication.
- **Don't mint provenance in a consumer** — `fromConsumed()`, always.
- **Relay failure paths are not observable in a `RefreshDatabase` Feature test.** A malformed payload makes the relay's defensive `try/catch` swallow a SQL error, and PostgreSQL then aborts the whole test transaction (`SQLSTATE[25P02]`). Prove permanent-failure behaviour at the **hydrator** level instead. Recorded as **AD-007** — an environment-specific concern, not a production defect (in production each statement autocommits).
- **`AdjudicationProcessManager` resolves through the container** via `AdjudicationProcessStore` + `ClockInterface`; there is no extra binding to add.

---

**Traceability:** ADR-T21 (correction loop) · ADR-T8 (PM is the head only, never a saga) · ADR-T16 (local VOs, opaque identity) · ADR-T3/T4 (at-least-once ⇒ dedupe) · ADR-MP-06 (provenance/audit continuity) · PB-006 (*Registration ≠ Delivery*) · `docs/architecture/Cross_Context_Integration_Contract.md` §5 · EPIC-004K §3 PM-1 · plan `.claude/plans/WP-4-apm-wiring.md` (G-2) · debt AD-007/AD-008.
