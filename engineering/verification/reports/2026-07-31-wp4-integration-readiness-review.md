# WP-4 Integration Readiness Review

**Date:** 2026-07-31 · **Role:** Chief Software Architect · **Commission:** DA instruction (integration readiness, not implementation)
**Question:** is the architecture ready for the **first cross-context integration** — Adjudication consuming Contestation's `ChallengeRouted`?
**Constraints honored:** no production code · no ADR modified · no bounded context redesigned · no temporary abstraction · WP-3B untouched · **published language unmodified**

---

## 1. Cross-context interaction diagram (as the code actually works)

```
CONTESTATION (producer)                     SHARED MESSAGING (platform)                 ADJUDICATION (consumer)
─────────────────────                       ───────────────────────────                 ───────────────────────
Challenge::route()
   → ChallengeRouted            (domain event, Contestation-internal)
   → ChallengeOutboxAdapter
        ::writeRouted()
   → outbox_events row  ──────► OutboxEventProcessor (relay)
                                  hydratorFor('ChallengeRouted')
                                    ->hydrate(payload)          ← producer's OWN event,
                                                                  reconstructed in-process
                                  dispatch on the event bus
                                ─► IntegrationEventDispatcher
                                     ConsumerResolver → ordered consumers
                                     per consumer: InboxMessage
                                       { eventId, eventType, payload[], organisationId,
                                         correlationId, causationId }   ← DATA ONLY
                                ────────────────────────────────────► Inbox::consume()
                                                                          InboxHandler::handle(InboxMessage)
                                                                            reconstruct LOCAL VOs
                                                                            (Adjudication\…\ChallengeRef)
                                                                          → AdjudicationProcessManager::openFor()
```

**The single most important fact, verified in code:** what crosses the boundary is **`InboxMessage` carrying a raw payload array** — never a domain object, never the producer's event class.

## 2. Boundary dependency review

| Check | Evidence | Result |
|---|---|---|
| Do consumers import a producer's Domain event? | `grep` for `Contexts\Adjudication\Domain\Events` inside Election/Contestation → **0 matches** | ✅ **None** |
| What does the existing cross-context consumer import? | `DeterminationIssuedReactionHandler` imports **only** `Election\Domain\*`, `Election\Application\Port\*`, `Shared\Application\{Inbox,Messaging}` | ✅ Own context + Shared only |
| Handler signature | `handle(InboxMessage $message): void` — a **Shared Application** type | ✅ No producer type in the contract |
| Is the rule machine-enforced or merely intended? | Deptrac: per-context hexagonal layers, **cross-context = violation by omission**; fail-mode active since PB-007 7D (0 violations) | ✅ **Enforced, not aspirational** |
| Direction | Contestation → outbox → relay → dispatcher → inbox → Adjudication. Nothing in Contestation names Adjudication | ✅ One-way |

**The producer-side hydrator's true role, clarified:** `OutboxEventProcessor` is the **only** production caller of `hydratorFor()`. It reconstructs the producer's own event **inside the producing context's representation** for event-bus dispatch. The hydrator therefore **never crosses into the consumer** — which is precisely why no consumer imports it. `ChallengeRoutedHydrator` is producer-side infrastructure, not a shared contract type.

## 3. Consumer responsibility map (architectural ownership, not classes)

| Responsibility | Owner | Already exists? |
|---|---|---|
| Publish the routing fact | **Contestation** | ✅ WP-3A |
| Transport, ordering, delivery, audit continuity | **Shared Messaging platform** (relay · `IntegrationEventDispatcher` · `ConsumerResolver` · inbox) | ✅ PB-003/PB-006 |
| Decide *that* it consumes | **Adjudication** (registers itself; the producer must never know) | ❌ **WP-4** |
| Reconstruct the message into its own model | **Adjudication** — local VOs from wire strings (ADR-T16) | ✅ `ChallengeRef` exists |
| Start the adjudication | **Adjudication** — PM-1 `openFor()` | ✅ WP-2 |
| Correlation propagation | **Shared** contract, **consumer**-applied: `EventProvenance::fromConsumed()` | ✅ exists |
| Retry | **Messaging platform** (relay retry; transient ⇒ rollback leaves no inbox row) | ✅ |
| Duplicate handling | **Two seats, both present:** inbox dedupe `(event_id, consumer_context)` **and** PM idempotency (active-process guard + terminal guard) | ✅ |
| Failure classification (business condition → messaging outcome) | **Adjudication's Application boundary** | ❌ **WP-4, see §5 prerequisite** |
| Interpretation / orchestration / local decisions | **Adjudication** | ✅ PM owns them |

## 4. Published-language contract review (`ChallengeRouted`, unmodified)

Payload: `schema_version` · `challengeId` · `routedTo` · `occurredAt`.

| Question | Finding |
|---|---|
| Producer information leaking? | **No.** All three are facts Contestation's own lifecycle records (ADR-T20 `…→Routed→…`). No internal state, no persistence detail, no aggregate version |
| Consumer information embedded? | **No.** Nothing names Adjudication, a queue, a handler, or a context. `routedTo` is a **business destination** (e.g. an authority body), not a technical consumer identity — the producer decides where a challenge is routed as its own business act |
| Still minimal? | **Yes** — exactly three facts; the reconstruction test pins the property set (PB-005 F-2 ruling) |
| Context-neutral? | **Yes** — identity crosses as a string; each consumer builds its own VO |
| Observation (not a defect) | **No current consumer needs `routedTo`.** The APM's `openFor()` uses only the challenge identity. `routedTo` is legitimately the producer's fact and plausibly relevant to PM-4 (which authority decides), so it stays — but its presence is **producer-justified, not consumer-driven**, and that is the correct reason for a published fact to exist |

## 5. Missing infrastructure

| # | Gap | Class |
|---|---|---|
| G-1 | An Adjudication inbox handler for `ChallengeRouted` + its registration by `(Adjudication, ChallengeRouted)` in `AdjudicationServiceProvider` | **WP-4 implementation** (expected) |
| G-2 | **Business-condition → inbox-marker translation for Adjudication** | **Architectural prerequisite — see below** |

**G-2 is the one finding that is not merely "unwritten code".** PB-005's **F-1 ruling** is binding: *the Domain never names messaging outcomes*; business conditions are translated to inbox markers at a single Application-layer boundary. Contestation satisfies this with `ChallengeReactionOutcomeTranslator` + markers. **Adjudication has no equivalent**, and its PM entry points are `void` idempotent no-ops — they return *nothing* a translator could read. So WP-4 must decide, before coding, how PM outcomes reach the inbox as `IdempotentReplay` / `CausalPreconditionMissing` / `PermanentInboxFailure` **without the PM naming any of them**.

*This is a prerequisite, not a redesign:* the rule already exists (F-1), the precedent already exists (Contestation's translator), and the two-seat duplicate handling already exists. What WP-4 owes is applying them — and the sequencing matters, because retrofitting messaging vocabulary into the PM later would violate F-1.

## 6. Architectural risks

| # | Risk | Mitigation already in place |
|---|---|---|
| R-1 | Consumer imports the producer's event class (the classic first-integration mistake) | Deptrac fail-mode + the established `InboxMessage` pattern; would fail the merge gate, not review |
| R-2 | PM gains messaging vocabulary, violating F-1 | §5 G-2 raised as a prerequisite *before* implementation |
| R-3 | Producer learns about its consumer (registration leaking into Contestation) | Registration is consumer-side by construction (`InboxHandlerRegistry` keyed by consumer context); PB-006's **"Registration ≠ Delivery"** principle |
| R-4 | No production emission exists yet (WP-3B deferred), so WP-4 cannot be proven end-to-end from a real raise | Expected and already anticipated: the roadmap's own note — prove with test-seeded routed rows, the `CorrectionLoopIntegrationTest` pattern |
| R-5 | A second correlation mint appears in the consumer | `CorrelationIdMintingTest` allowlists exactly one chain origin; reacting handlers must use `fromConsumed()` |

## 7. Final readiness decision

> ### **READY WITH ONE PREREQUISITE**

**Ready, on evidence:** the producer is autonomous (nothing in Contestation names a consumer) · the consumer will be autonomous (handlers take `InboxMessage`; zero consumers import producer domain events; Deptrac enforces it) · the dependency is one-way · the published language is minimal and context-neutral · every consumer-side building block already exists (PM, local `ChallengeRef`, store, both duplicate-handling seats, `fromConsumed`) · the transport, ordering and audit-continuity platform is complete and qualified.

**The prerequisite (G-2):** decide Adjudication's **business-condition → inbox-marker translation seam** before writing the handler, honoring PB-005's F-1 ruling (the Domain — and here the PM — never names messaging outcomes). Precedent: Contestation's translator. This is a **design decision inside WP-4's authorized scope**, requiring no new ADR and no redesign — but it must be made *first*, because a PM that learns messaging vocabulary cannot be un-taught without rework.

**Success criteria:** ☑ producer autonomous · ☑ consumer autonomous · ☑ no reverse dependency · ☑ published language minimal · ☑ consumer responsibilities explicitly mapped (§3) · ☑ boundaries clean and machine-enforced · ☑ **WP-4 can begin without further architectural redesign, once G-2 is decided as its first act.**

---

**Traceability:** DA readiness commission 2026-07-31 · evidence read-only from `DeterminationIssuedReactionHandler` · `InboxMessage` · `OutboxEventProcessor:141` · `EventHydratorRegistry` · `ChallengeOutboxAdapter` · `AdjudicationProcessManager` · `deptrac.yaml` · ADR-T16 · ADR-T20 · ADR-MP-06 · PB-005 F-1/F-2 · PB-006 (Registration ≠ Delivery) · roadmap §WP-4. **No artifact modified.**
