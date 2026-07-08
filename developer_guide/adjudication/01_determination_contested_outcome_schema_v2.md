# Step 2 — Determination carries ContestedOutcomeRef; DeterminationIssued payload schema version 2

**Layer:** Domain + Infrastructure.
**Namespace:** `App\Contexts\Adjudication\Domain\Determination` (+ Infrastructure/Outbox, Persistence).
**Delivered by:** PB-004 prerequisite step 2 (`f18d7ae37`).
**Traceability:** ADR-UL-01 · ADR-PL-01 · ADR-T5/T14/T16 · Event Registry decision tree.

---

## Purpose

Election (PB-004) must resolve *which* Election a determination affects. That fact travels as the ubiquitous-language concept `ContestedOutcome` — so the `Determination` carries a **`ContestedOutcomeRef`** and emits it on `DeterminationIssued`. The event is a **published integration event**, so per ADR-T5 + the Event Registry the change is an **additive payload schema version bump (v2)** on the *same* event — **not** a new event class.

## Local VOs (ADR-T16 — Adjudication's own)

Adjudication does **not** import Contestation's `ContestedOutcomeRef`. Cross-context identities travel as strings; each context reconstructs its **own** VO. Adjudication has its own `ContestedOutcomeRef { ElectionId · TargetType · TargetId }`, `ElectionId`, `TargetType` (closed set — ADR-UL to extend), `TargetId`. Reference only; no vote content.

## Aggregate

```php
Determination::prepare(id, challengeRef, issuedByAuthority, jurisdiction, evidenceEnvelopeRef, ContestedOutcomeRef $contestedOutcome)
$d->issue(outcome, legitimacy, reason, at)   // records DeterminationIssued(..., $this->contestedOutcome, $at)
```

`reconstitute(...)` takes `?ContestedOutcomeRef` (**nullable** — rows written before schema v2 have none). The reference is persisted like any other (`challengeRef` etc.), in 3 nullable columns.

## The published event — payload schema version 2

`DeterminationIssued` gains an **optional** `?ContestedOutcomeRef $contestedOutcome` (null for v1 payloads). Same class, same event name. Wire contract (OutboxEventAdapter ⇆ hydrator):

```
payload: { schema_version: 2, …, contestedOutcome: { electionId, type, targetId } | null, occurredAt }
```

- **Producer** (`OutboxEventAdapter`) stamps `schema_version: 2` and the nested `contestedOutcome` (null when absent).
- **Hydrator** dispatches by `schema_version` (absent = 1): **v1 → `contestedOutcome` null**; **v2 → reconstruct the VO**. It supports exactly **vCurrent (2) + vPrevious (1)** (Event Registry). Reconstruction from strings lives **in the hydrator (Infrastructure)** — never on the VO.

## How to extend
- **A new kind of contested outcome?** ADR-UL first → add a `TargetType` case → it becomes a Published Language change (ADR-PL) → bump schema_version if the wire changes. Never add a case silently.
- **Any future field on `DeterminationIssued`?** Consult the Event Registry decision tree: additive/backward-compatible → same event + `schema_version++` + hydrator vCurrent/vPrevious; breaking → a new event name.

## Testing (TDD, RED-first)
- `DeterminationTest::test_issue_carries_the_contested_outcome` — the aggregate emits the ref.
- `DeterminationIssuedHydratorTest` — `test_hydrates_contested_outcome_at_schema_version_2` (v2 → VO) and `test_v1_payload_hydrates_null_contested_outcome` (v1 → null). The pre-existing v1 fidelity/tolerance tests still pass (back-compat).
- Regression: Architecture + Contestation + Adjudication 199 passed / 1 skip; greenfield PHPStan clean; anonymity guard (scans Adjudication) green.

## Pitfalls
- Don't create a `DeterminationIssuedV2` class — additive changes are a schema-version bump on the same event.
- Don't import Contestation's VOs — reconstruct locally (ADR-T16).
- Don't put wire reconstruction (`schema_version`, string parsing) on the VO — that's the hydrator/mapper's job.

## Traceability
ADR-UL-01 · ADR-PL-01 · ADR-T5 (versioning) · ADR-T14 (Challenge read-only) · ADR-T16 (local VOs) · ADR-T11 (anonymity) · Event Registry decision tree.
