# Round 50-04 — Domain Event Design (relationships)

**Phase III (Tactical Realization) · Built against Release 1.0 · Design only · 2026-06-26**
**Status:** 📡 DOMAIN EVENT DESIGN — *conceptual relationships* (envelope · categories · producer/consumers/publish-rights · evolution · failure). Technical payload **contracts** = `50-05` Event Catalogue. Concise by design.

## Tactical Architecture Principles (official — extend Release 1.0)
- **TP-1 Event is the seam** — aggregates collaborate only via domain events; no aggregate reaches across a boundary.
- **TP-2 Request-not-create** — an aggregate never creates another; it publishes intent → an Application Service coordinates the target aggregate.
- **TP-3 Events version, never mutate** — `VoteAccepted v1 → v2 → …`; payloads are never silently changed.

## 1. Event envelope (standard — every event)
| Field | Purpose |
|-------|---------|
| `EventId` | uniqueness / idempotency key |
| `EventType` | routing |
| `AggregateId` | origin |
| `AggregateVersion` | optimistic concurrency |
| `OccurredAt` | audit/order |
| `CorrelationId` | workflow tracing |
| `CausationId` | event chain |
| `SchemaVersion` | evolution (TP-3) |
`Event = Envelope + DomainPayload` (payload defined in 50-05).

## 2. Event registry (category · producer-ONLY · consumers · forbidden)
| Event | Category | Producer (only) | Consumers | Forbidden producers |
|-------|----------|-----------------|-----------|---------------------|
| `VoteAccepted` | Decision | Voting | Evidence · Results(proj) · Audit | all others |
| `EvidenceRecorded` | Evidence | Evidence | Adjudication · Audit | all others |
| `MandateGranted` / `MandateRevoked` | Lifecycle | Appointment | Authorization · Audit | all others |
| `ChallengeRaised` / `Admitted` / `Dismissed` / `Routed` / `Resolved` | Process | Contestation | AdjudicationService · Audit | all others |
| `DeterminationIssued` | Decision | Adjudication | Election/Lifecycle · Legitimacy(proj) · Audit | all others |
| `ElectionCorrectionApplied` | Lifecycle | Election/Lifecycle | Contestation(resolve) · Legitimacy(proj) · Audit | all others |

**Audit consumes all** (fire-and-forget observer, BDR-09). **Each event has exactly one allowed producer** (prevents duplication).

## 3. Anonymity constraint (binding — Q7)
**No event payload may carry voter↔vote linkage.** Only hashed/anonymized identifiers (e.g. `EvidenceRecorded` uses the hashed voter id; `VoteAccepted` carries vote/receipt hashes, never a `user_id`). A fitness test asserts no event schema can reconstruct identity.

## 4. Evolution (TP-3)
Events are **append-only contracts**: new fields → new `SchemaVersion`; breaking change → `vN+1` event; old versions retained until consumers migrate. Governed like Knowledge releases (KRG-style). Never edit a published event.

## 5. Failure policy (uses EXISTING infrastructure)
The codebase already has an **outbox** (`ProcessOutboxEvents`) + **dead-letter** (`DeadLetterEntry`) [G]. Policy:
- **Delivery:** transactional **outbox** (event persisted in the producer's txn) → async dispatch → **at-least-once**.
- **Consumers idempotent** (dedupe on `EventId`) — required since at-least-once.
- **Failure:** retry with backoff → **dead-letter** after N attempts (`DeadLetterEntry`).
- **No cross-aggregate compensation needed** — each aggregate is atomic (TP-1); the correction loop tolerates eventual consistency. `DeterminationIssued` with Election unavailable → outbox holds it → delivered on recovery (no saga rollback).

## 6. Correction-loop event flow (from 50-03)
```
ChallengeRaised ─►[AdjudicationService]─► DeterminationIssued ─►[Election reacts]─► ElectionCorrectionApplied ─► (Contestation resolves Challenge)
```
All hops: outbox → at-least-once → idempotent consumer. No cross-aggregate txn (ADQC Q10).

## 7. Still a design HYPOTHESIS (not frozen)
- **Replay** placement (App Service over Evidence) — confirm at implementation (BDR-06).
- Open modeling Qs unaffected by event design: Mandate-vs-Committee; correction multi-step → Option C coordinator.

## Terminology (dissertation)
Program phases are richer than "Tactical DDD": **Strategic Discovery → Strategic Validation → Tactical Realization.** (This round = Tactical Realization.)

## Roadmap (refined)
```
50-03 ✓ → 50-04 Domain Event Design (relationships, this) ✓
   → 50-05 Event Catalogue (technical payload contracts)
   → 50-06 Policy Catalogue (Invariant/Decision/Authorization/Calculation/Validation)
   → 50-07 Repository & Transaction Design
   → Implementation (greenfield Core: Challenge + Determination)
```
*(50-04 = conceptual relationships; 50-05 = technical contracts — different artifacts.)*

---
*Round 50-04 — Domain Event Design — ISSUED (relationships; design only).*
*Envelope standard (8 fields); categories (Decision/Lifecycle/Evidence/Process); registry with single-producer + consumers + forbidden-producers; Anonymity = no voter↔vote linkage in any payload; TP-3 events version-never-mutate; failure = existing outbox(ProcessOutboxEvents)+dead-letter(DeadLetterEntry), at-least-once + idempotent, no saga. Tactical Principles TP-1/2/3 official. Replay still hypothesis. Next: 50-05 Event Catalogue (contracts). No code.*
