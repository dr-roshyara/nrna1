# PB-006 (Integration Validation IT-1..IT-8) — Implementation Design Document (IDD)

**Status:** Design (Phase 2) — awaiting ARB review. **No RED, no code until approved.** **2026-07-10.**
**Grounds:** Blueprint §9 (IT-1..8) · Discovery `docs/implementation/PB-006_Discovery_Findings.md` (F-PB006-1) · **ADR-MP-06** (IntegrationEventDispatcher, ARB-accepted under the R-29 escape clause).

## 1. Strategic ownership
| Concern | Owner | Note |
|---|---|---|
| **Delivery** (outbox → consumer inboxes) | **Shared Messaging Platform** (`IntegrationEventDispatcher`, ADR-MP-06) | The **Inbox does NOT own delivery — it is one consumer**. Registration ≠ delivery. |
| Consumer registration | Consuming contexts (existing providers) | Unchanged. |
| Consume semantics (dedupe/park/redrive/dead-letter) | Inbox (frozen, PB-003) | Unchanged — the dispatcher only calls `Inbox::consume`. |
| Business reactions | Election / Contestation (qualified) | Untouched. |
| The IT-1..8 test suite | PB-006 | Test authoring over the REAL path once the dispatcher exists. |

## 2. Dispatcher design (per ADR-MP-06 — four explicit responsibilities)
`App\Contexts\Shared\Infrastructure\Messaging\IntegrationEventDispatcher` (name/location per ARB: Messaging-owned, NOT under Inbox):
1. **Receive** — invoked with the relay's `IntegrationEvent` (wiring: a listener on the relay's dispatched `IntegrationEvent`, registered in the Shared/App provider; the relay itself is not modified).
2. **Consumer Discovery (ARB refinement)** — the dispatcher depends on a **`ConsumerResolver`** port (Messaging-owned): `consumersFor(eventType): list<InboxHandler>` returning the **ordered** consumer set (**ordering rule: `consumerContext()` ascending, lexicographic** — reproducible regardless of registration order). The registry stays an implementation detail behind `RegistryConsumerResolver` (which uses one **additive** registry query). Determinism: identical event + identical registry state ⇒ identical ordered set.
3. **Inbox Message Creation** — one `InboxMessage` per resolved consumer: `event_id`, `event_type`, `payload`, `organisation_id`, `correlation/causation` — all propagated from the `IntegrationEvent` (which the relay builds from the outbox row). *(Gap check: `IntegrationEvent` currently carries eventId/type/aggregate/org/payload/occurredAt — if correlation/causation are absent on the envelope, adding them is an additive envelope field, flagged below as D-1.)*
4. **Delivery** — `Inbox::consume($message, $handler)` per consumer, each consumer wrapped so one consumer's failure/park/dead-letter never blocks another (**consumer isolation**); outcomes are per-consumer, observable, never re-thrown into the relay loop except for transient infrastructure faults (which the relay's existing retry handles).

No consumers registered for a type ⇒ no-op (not an error). The dispatcher holds **no state** (replay-safe: redelivery lands on inbox dedupe) and **no business decisions** (ADR-MP-01).

## 3. Slicing (each RED→GREEN→stop-at-gates)
- **6A — Dispatcher (platform slice):** `handlersFor()` (additive) + `IntegrationEventDispatcher` + listener wiring. RED-first; its own **Trustworthiness Qualification** (§6).
- **6B — IT-1..IT-8 suite:** the eight Blueprint scenarios over the REAL path (`outbox → OutboxEventProcessor → dispatcher → inbox → handler`), replacing hand-stitched delivery in E2E tests.
- **6C — Qualification + Completion Review:** Architecture + DDD + Trustworthiness; EP-02; STOP (no PB-007).

## 4. RED Behaviour Matrix — 6A (dispatcher)
| # | Scenario | Expected |
|---|---|---|
| 1 | event type with ONE registered consumer | exactly one inbox row `(event_id, consumer)`; handler invoked once |
| 2 | event type with TWO consumers (e.g. `DeterminationIssued` → Election + Contestation) | one row per consumer; both handlers invoked; deterministic set |
| 3 | one consumer parks (`CausalPreconditionMissing`) | that consumer's row `parked`; the OTHER consumer still processed (isolation) |
| 4 | one consumer dead-letters (permanent) | that row `dead`; other consumer processed; relay run not aborted |
| 5 | redelivery of the same `IntegrationEvent` | inbox dedupe → `Duplicate` per consumer; no second effect (replay safety) |
| 6 | no consumer registered for the type | no-op; no row; no error |
| 7 | tenant propagation | inbox rows carry the producing row's `organisation_id`; consumption is org-scoped |
| 8 | correlation/causation propagation (D-06) | values on the inbox rows equal the outbox row's |
| 9 | **deterministic ORDERED routing** (ARB) | two consumers registered in reverse order → invoked in `consumerContext()` ascending order, identical on repeat |
| 10 | **audit continuity** (ARB) | every created inbox row's `event_id` equals the originating outbox event's `event_id` — each `InboxMessage` traceable to exactly ONE `OutboxEvent` |

## 5. IT-1..IT-8 mapping (6B — Blueprint §9, over the real path)
| IT | Scenario | Path exercised |
|---|---|---|
| IT-1 | E2E Upheld: seed Routed Challenge + legacy election → Adjudication issues (Upheld) → `outbox:process` → dispatcher → Election corrects → `outbox:process` → dispatcher → Contestation adjudicates+resolves. Assert: challenge `Resolved`; correction ledger row; `ChallengeResolved` (resolution=upheld) in outbox; **one CorrelationId across all rows** | full loop ×2 relay runs |
| IT-2 | E2E Dismissed: issue (Dismissed) → Contestation adjudicates + resolves (short-circuit); **Election emits nothing** | full loop, silent Election |
| IT-3 | idempotency: same `DeterminationIssued` delivered twice per consumer → one effect; second = inbox `Duplicate` | dispatcher + inbox dedupe |
| IT-4 | out-of-order: `ElectionCorrectionApplied` delivered before adjudication → `parked`; deliver `DeterminationIssued`; `inbox:redrive` → `Resolved` | park + redrive |
| IT-5 | relay registry: registered type processed; unregistered type → outbox dead-letter (F2 regression) | relay |
| IT-6 | failure injection: F5 transient mid-handler → rollback, clean redelivery succeeds; F9 permanent → inbox dead-letter, no retry | engine classification |
| IT-7 | fitness: AT-EVT-001 single producer · AT-TXN-001 one root+outbox per txn · zero voter↔vote linkage (existing suites re-run as part of PB-006 evidence) | architecture |
| IT-8 | observability: full CorrelationId chain queryable across `outbox_events`+`inbox_events`; CausationId links each hop; organisation on every row | audit trail |
Every failure-model case F1–F9 is covered at least once across IT-3..IT-6 + existing PB-003 suites (mapping table to be included in the 6C evidence).

## 6. Qualification (all three categories at 6C)
- **Architecture:** GreenfieldCoreArchitectureTest · greenfield PHPStan (scope extended to the new Shared\Infrastructure\Messaging class if the config includes Shared — verify; if not, ad-hoc max on the new files) · full regression (exact numbers) · Deptrac deferred-by-roadmap to PB-007.
- **DDD:** ownership per §1 (Messaging owns delivery; Inbox a consumer); published language unchanged; event ownership unchanged; boundaries events-only; **no new pattern beyond the ADR-MP-06 capability itself**.
- **Trustworthiness (incl. the dispatcher as trusted computing base):** deterministic routing · replay safety · tenant isolation · consumer isolation · causal-ordering preservation · correlation/causation propagation · event-identity propagation · anonymity (no voter↔vote linkage on any delivered payload) · forward-only preserved end-to-end.

## 7. Boundaries
DO NOT modify: Outbox, Relay (`OutboxEventProcessor` logic), Inbox, existing registry contracts (only the **additive** `handlersFor()`), PB-004/PB-005 contexts, aggregates, schemas. DO NOT begin PB-007. The dispatcher is the ONLY new production code.

## 8. Decisions (ARB-ruled 2026-07-10)
- **D-1 APPROVED:** `IntegrationEvent` envelope extended **additively** with `CorrelationId`/`CausationId` (messaging concerns; domain events untouched).
- **D-2 APPROVED:** dispatcher wired as a **listener** on the relay's `IntegrationEvent`; the relay stays **byte-identical**.
- ARB refinements folded: `ConsumerResolver` dependency (registry behind it) · deterministic **ordered** routing (`consumerContext()` ascending) · **audit continuity** in the Trustworthiness qualification · "Consumer Discovery" wording · **Registration ≠ Delivery** recorded as a permanent design principle (ADR-MP-06).

## 6B sub-slicing (post-6A; F-PB006-2 authorized by ARB)
- **6B-1 — Correlation chain (F-PB006-2, RED-first):** additive `outbox_events` migration (`correlation_id`/`causation_id`, nullable) · producers stamp them (Adjudication mints a correlation when none exists — loop start absent the raise path; the reacting contexts propagate **correlation = consumed message's correlationId, causation = consumed message's eventId**) · relay copies row → envelope. The producer-side propagation **seam is NOT prescribed — it emerges from RED** (per the F-2 doctrine). Domain events untouched.
- **6B-2 — IT-1..IT-8 suite** over the real path (per §5), including the now-verifiable IT-8 full chain.
- Then **6C** triple qualification + Completion Review.

## STATUS: IDD APPROVED (ARB) — 6A DONE (GREEN accepted); 6B-1 in RED. STOP at each RED/GREEN boundary for review.
