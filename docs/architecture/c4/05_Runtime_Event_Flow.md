# Runtime View — Event Flow (project-specific, beyond standard C4)

**Diagrams:** [`plantuml/Runtime_CorrectionLoop.puml`](plantuml/Runtime_CorrectionLoop.puml) (sequence) · [`plantuml/EventFlow.puml`](plantuml/EventFlow.puml) ("C5" event architecture) · [`plantuml/ContextMap.puml`](plantuml/ContextMap.puml) (DDD context map) · [`plantuml/Hexagonal_Adjudication.puml`](plantuml/Hexagonal_Adjudication.puml) (representative hexagon)
**Derived from:** Push B Blueprint §2/§5/§6/§11/§14 · Canonical Event Catalog v1.0 · Round 50-03 · Decision Log D-02/D-03

## Explanation
The correction-loop sequence shows what static C4 cannot: the **five transaction boundaries** (T1–T5, each = one aggregate + its outbox rows, ADR-T1), the outbox → relay(registry) → inbox path, **both outcome branches** (Upheld: Election corrects, then Contestation resolves; Dismissed: Election stays silent, Contestation self-resolves T4+T5'), and identifier propagation — one `CorrelationId` minted at `ChallengeRaised` for the entire loop, `CausationId` chaining each event to its cause, `EventId` as the idempotency key.

The EventFlow view is the catalog-level picture: seven loop events, single producer each, Audit consuming all, versioning rules on the envelope. The Context Map adds DDD relationship semantics (upstream/downstream, published language = Event Catalog, opaque-VO ACL at every boundary), with interpretation-level pattern labels explicitly marked.

## Assumptions
1. Pattern names on the Context Map marked "(interp.)" are readings of the frozen upstream/downstream matrix, not frozen decisions themselves.
2. The inbox lane reflects the APPROVED PB-003 IDD (dedupe key `(event_id, consumer_context)`, park/re-drive) — implementation pending.

## Rationale
This view is the operational contract for PB-004/PB-005 implementers and the incident-investigation map for operations (`WHERE correlation_id = X ORDER BY occurred_at` = one challenge's complete legal history).
