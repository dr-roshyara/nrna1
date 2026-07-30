# Cross-Context Integration Contract

**Status:** **DESCRIPTIVE — derived from the existing implementation, not invented.** Documents rules the code already follows and Deptrac already enforces. **Elevating any clause to normative status (an ADR, or a new fitness test) is a separate decision this document does not take.**
**Date:** 2026-07-31 · **Role:** Chief Software Architect (Cross-Context Integration Contract Audit) · **Method:** every rule below cites implementation evidence; nothing is asserted from an ADR alone.

---

## 1. Existing integration pattern (Phase 1 — all chains, not one)

**Every** cross-context interaction in the solution today:

| # | Producer | Event | Consumer | Consumer class |
|---|---|---|---|---|
| 1 | Adjudication | `DeterminationIssued` | **Election** | `DeterminationIssuedReactionHandler` |
| 2 | Adjudication | `DeterminationIssued` | **Contestation** | `AdjudicateChallengeHandler` |
| 3 | Election | `ElectionCorrectionApplied` | **Contestation** | `ResolveChallengeHandler` |

*(Exactly three consumers exist: `grep -rl "implements InboxHandler" app/Contexts/` returns these three files and no others.)*

| Question | Answer | Evidence |
|---|---|---|
| **What crosses the boundary?** | An `InboxMessage` carrying a **payload array of primitives** (+ eventId, eventType, organisationId, correlationId, causationId) | `InboxMessage`'s constructor: `public array $payload` |
| **What remains inside the producer?** | The domain event class, the aggregate, the producer's VOs, **and the hydrator** | see §2 R-3 |
| **What does the consumer reconstruct?** | Its **own local VOs**, from payload primitives | `DeterminationId::fromString($this->stringField($message->payload, 'determinationId'))` |
| **What object type reaches the handler?** | `InboxMessage` — a Shared Application type | `handle(InboxMessage $message): void` in all three handlers |

## 2. Architectural invariants (Phase 2 — discovered, with evidence)

| # | Invariant | Evidence |
|---|---|---|
| **R-1** | **A consumer never imports another context's Domain namespace.** | Exhaustive scan across Adjudication × Contestation × Election × Governance: **zero** files import another context's `\Domain` |
| **R-2** | **A consumer never imports another context's Infrastructure either** — including its hydrators. | `grep "Hydrator"` in Election's and Contestation's Application layers: **zero matches** |
| **R-3** | **The hydrator is producer-side.** It reconstructs the producer's *own* event inside the producing context for event-bus dispatch — it is not the consumer's reconstruction mechanism. | `OutboxEventProcessor:141` is the **only** production caller of `hydratorFor()` |
| **R-4** | **Consumers reconstruct local VOs from primitives.** | Election builds its own `DeterminationId`, `ElectionId`, `RulingOutcome` from `$message->payload` |
| **R-5** | **The wire payload carries primitives only** — strings, enum backing values, ISO-8601 timestamps, and (since ADR-T22) flat arrays of strings. | `DeterminationIssued` v3 · `ChallengeRouted` v1 payloads |
| **R-6** | **Provenance travels on the envelope, never in the domain event.** | `InboxMessage` carries correlation/causation; consumers call `EventProvenance::fromConsumed($message->correlationId, $message->eventId)` |
| **R-7** | **The producer never names its consumers.** Registration is consumer-side, keyed by consumer context. | `InboxHandler::consumerContext()`; PB-006's *Registration ≠ Delivery* |
| **R-8** | **Every consumer is idempotent, at two seats.** | Inbox dedupe `(event_id, consumer_context)` **plus** handler/aggregate-level idempotency |
| **R-9** | **Identity crosses as an opaque string.** | ADR-T16 realized: Contestation's `DeterminationId` ≠ Adjudication's; both reconstruct locally |

**R-1 is machine-enforced, not aspirational:** Deptrac encodes per-context hexagonal layers where *cross-context = violation by omission*, in fail mode since PB-007 7D, currently 0 violations. The contract is already executable architecture.

## 3. Boundary object lifecycle (Phase 3 — how one becomes another)

```
PRODUCER CONTEXT                          SHARED PLATFORM                    CONSUMER CONTEXT
────────────────                          ───────────────                    ────────────────
Aggregate / Application service
   │ records
   ▼
Domain Event                 ← NEVER crosses. Producer-internal.
   │ adapter maps to primitives (+ schema_version)
   ▼
OutboxEvent row              ← Infrastructure DTO: persistence + transport
   │ relay reads
   ├─ hydratorFor()->hydrate()  → Domain Event again, IN-PROCESS, producer-side only (R-3)
   │                              (for event-bus dispatch; never leaves the context)
   ▼
IntegrationEvent envelope    ← + correlation/causation (D-1 fields)
   │ IntegrationEventDispatcher: resolve consumers, one message each
   ▼
                             InboxMessage             ← Infrastructure/Application DTO:
                             { eventId, eventType,      delivery + dedupe. THE CROSSING.
                               payload[], organisationId,
                               correlationId, causationId }
                                        │ Inbox::consume() (one txn per consumer)
                                        ▼
                                                              InboxHandler::handle(InboxMessage)
                                                                 │ reads primitives
                                                                 ▼
                                                              LOCAL Value Objects (R-4, R-9)
                                                                 ▼
                                                              own Aggregate / Process Manager
                                                                 ▼
                                                              own Domain Event → own outbox,
                                                              provenance via fromConsumed() (R-6)
```

**Classification:**

| Object | Category | Crosses? |
|---|---|---|
| Producer's Domain Event | Domain Event | **Never** |
| Producer's aggregate / VOs / domain services | Domain model | **Never** |
| Producer's hydrator | Producer Infrastructure | **Never** (R-2/R-3) |
| `OutboxEvent` row | Infrastructure DTO | No — internal transport |
| Payload array (primitives) | **Published Language** | **Yes — this is the contract** |
| `InboxMessage` | Shared Application/Infrastructure DTO | **Yes — the carrier** |
| Consumer's local VOs | Local domain objects | No — created on arrival |

## 4. Dependency verification (Phase 4)

| Check | Result |
|---|---|
| Consumer imports another context's `\Domain\Events` | **0** |
| Consumer imports another context's `\Domain` (any: aggregates, VOs, services) | **0** — exhaustive 4×3 context-pair scan |
| Consumer imports another context's Infrastructure (hydrators) | **0** |
| Deptrac cross-context violations | **0** (fail mode) |

> **No violations. No architectural debt. Every existing interaction conforms to §2.**

## 5. WP-4 consumption specification (Phase 5)

| Question | Answer | Grounds |
|---|---|---|
| Consume `ChallengeRouted` **domain event**? | **No** | R-1 — it is Contestation's Domain class |
| Consume via Contestation's **hydrator**? | **No — and this is the audit's most important correction.** | R-2/R-3: the hydrator is *producer-side Infrastructure*. Importing it would create the codebase's **first cross-context Infrastructure dependency** and fail Deptrac |
| Consume the **`InboxMessage`** carrying the payload? | **Yes** | R-4; identical to all three existing consumers |
| What does Adjudication reconstruct? | **`ChallengeRef`** (its own local VO — already exists) from `payload['challengeId']` | R-4/R-9 |
| A local `RoutedTo` VO? | **No.** `routedTo` is a plain string the producer records, and **no consumer needs it** (`openFor()` takes only the challenge identity). Creating a VO for it would be a component without authority | Traceability rule; WP-4 readiness review §4 |
| Provenance | `EventProvenance::fromConsumed($message->correlationId, $message->eventId)` — **never `start()`** | R-6; the minting allowlist has exactly one chain-origin entry |

**Consequence: WP-4 introduces no new architectural concept.** It adds one `InboxHandler` in Adjudication plus its registration — the fourth instance of an established, machine-enforced pattern.

## 6. The contract (Phase 6)

| Question | Answer |
|---|---|
| **What may cross?** | A **payload of primitives** (the Published Language), carried by an `InboxMessage`, with provenance on the envelope |
| **What may never cross?** | Another context's domain events · aggregates · value objects · domain services · **and its Infrastructure, including hydrators** |
| **Who reconstructs?** | **The consumer, directly from payload primitives, into its own local VOs.** *Not* via the producer's hydrator — that is producer-side |
| **Who owns interpretation?** | The consumer |
| **Who owns orchestration?** | The consumer (its own aggregate or process manager) |
| **Who owns retries?** | The Shared Messaging platform (relay retry; transient failure ⇒ rollback leaves no inbox row) |
| **Who owns idempotency?** | **Both seats:** the platform (inbox dedupe by `event_id` + `consumer_context`) **and** the consumer (idempotent handler/aggregate) |
| **Who decides that a consumer consumes?** | The **consumer**, by registering itself. The producer never knows its consumers |
| **What does the producer own?** | Publication **and** registration of *its own* event (both halves of published-language status), and nothing on the consumer's side |

## 7. Success criteria (self-check)

☑ Contract explicitly documented (§6) · ☑ every existing interaction conforms (§4 — zero violations across all three chains) · ☑ **WP-4 introduces no new architectural concept** (§5) · ☑ no consumer depends on another context's internal model (R-1, R-2) · ☑ one consistent pattern, machine-enforced by Deptrac.

**Open for a future decision (not taken here):** whether to add a dedicated fitness test asserting R-2/R-3 explicitly. Deptrac already forbids the cross-context import that would violate them, so the risk is covered; a named test would make the *reason* legible. Recorded as an observation, not a recommendation.

---

**Traceability:** DA Cross-Context Integration Contract Audit, 2026-07-31 · evidence read-only from `InboxMessage` · `DeterminationIssuedReactionHandler` · `AdjudicateChallengeHandler` · `ResolveChallengeHandler` · `OutboxEventProcessor:141` · `EventHydratorRegistry` · `IntegrationEventDispatcher` · `deptrac.yaml` · ADR-T16 · ADR-T5 · ADR-MP-06 · PB-006 (*Registration ≠ Delivery*) · WP-4 readiness review (`engineering/verification/reports/2026-07-31-wp4-integration-readiness-review.md`). **No artifact modified; no production code written.**
