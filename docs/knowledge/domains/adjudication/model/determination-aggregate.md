---
knowledge_id: ADJ-MODEL-DETERMINATION
title: Determination Aggregate
knowledge_type: aggregate
bounded_context: adjudication
status: approved
authority: authoritative
audience: [developer, architect, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [adjudication, determination, aggregate, ddd]
related_to: [ADJ-README]
derived_from: [ADJ-DISC-BOUNDARY]
documents: []
adr: []
state_machine: [ADJ-SM-DETERMINATION]
tested_by: [ADJ-TESTS]
code_refs:
  - app/Contexts/Adjudication/Domain/Determination/Determination.php
  - app/Contexts/Adjudication/Domain/Determination/DeterminationOutcome.php
test_refs:
  - tests/Unit/Contexts/Adjudication/DeterminationTest.php
---

# Determination Aggregate

> The aggregate root that records the ruling on a challenge. Greenfield Core (Round 50-07 v1.2). Source: [`Determination.php`](../../../../../app/Contexts/Adjudication/Domain/Determination/Determination.php).

## Invariants

1. **Issued exactly once** — `issue()` is legal only from `Draft`.
2. **Final is terminal & immutable** — no transition or event after `Final`.
3. **Illegal transitions are inert** — a forbidden command throws `IllegalDeterminationTransition` with **no state mutation and no event**.
4. **Time is injected** — the caller supplies `DateTimeImmutable $at` (clock authority); the aggregate never reads the clock.
5. **No ruling content retained** — outcome/legitimacy/reason live in the emitted event and the read row, not in aggregate state.
6. **Sole constructor of validity** — only `prepare()` (new) and `reconstitute()` (rehydrate) build instances; no public setters, no reflection.

## Structure

Identity + references only:
- `DeterminationId` · `ChallengeRef` (→ Contestation) · `IssuedByAuthority` · `Jurisdiction` · `EvidenceEnvelopeRef` (→ Evidence) · `DeterminationState`.

Value objects guarantee their own validity at construction (`Legitimacy`, `Reason`, `IssuedByAuthority`, `Jurisdiction`).

## Behaviour

| Method | From → To | Emits |
|---|---|---|
| `prepare(...)` | — → `Draft` | — |
| `issue(outcome, legitimacy, reason, at)` | `Draft` → `Issued` | `DeterminationIssued` |
| `finalize(at)` | `Issued` → `Final` | — (none, by design) |
| `reconstitute(...)` | (rehydrate to any state) | — |
| `pullEvents()` | — | returns & clears recorded events (released to outbox by app layer) |

## Outcome

`DeterminationOutcome`: `Upheld` (challenge succeeds → drives downstream `ElectionCorrectionApplied`) · `Dismissed` (fails → no correction). See [`DeterminationOutcome.php`](../../../../../app/Contexts/Adjudication/Domain/Determination/DeterminationOutcome.php).

## See also

- State machine: [Determination state machine](../state-machines/determination-state-machine.md)
- Wiring: [implementation/wiring.md](../implementation/wiring.md)
- Tests: [tests/README.md](../tests/README.md)
