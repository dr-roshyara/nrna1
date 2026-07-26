# 02 — The Considered-Evidence Set: `EvidenceSet` and `DeterminationIssued` payload schema v3 (WP-1)

**Step:** WP-1 (first implementation slice of the EPIC-004 roadmap) · **Decision realized:** ADR-T22

## Purpose

A determination's ruling considers a *set* of evidence, not just the primary envelope. Until this step, that set existed only in the deliberation; the record fixed a single `evidenceEnvelopeRef`. ADR-T22 rules that the considered set is **fixed at the act of issuance** (R-4-expanded — the permanent fixation seat is the aggregate's issuance) and travels in the ruling's record (`DeterminationIssued` **is** that record, ADR-T19). What the world sees as considered can never diverge from what was considered.

## Where it fits

- **Domain (Adjudication-local):** `app/Contexts/Adjudication/Domain/Determination/EvidenceSet.php` — a VO per VODP; its parent invariant is INV-4's rider.
- **Application:** `IssueDeterminationCommand` gains `EvidenceSet $evidenceSet` (the authority supplies what it considered).
- **Infrastructure (wire):** `OutboxEventAdapter` stamps **schema_version 3**; `DeterminationIssuedHydrator` enforces the version window **(v3 current, v2 previous — v1 RETIRED)** per the versioning rule (`Event_Registry.md`: vCurrent + vPrevious ONLY).

## Key files

| File | Change |
|------|--------|
| `Domain/Determination/EvidenceSet.php` | NEW — immutable set of opaque evidence refs; rejects empty set, blank refs, duplicates (a fixation record fails loud) |
| `Domain/Events/DeterminationIssued.php` | `?EvidenceSet $evidenceSet` (null only when hydrated from a v2 payload) |
| `Domain/Determination/Determination.php` | `issue()` accepts and fixes the set (ADR-T22 succession) |
| `Application/Command/IssueDeterminationCommand.php` | `EvidenceSet $evidenceSet` field |
| `Application/Service/CoordinatesAdjudication.php` | passes the command's set to `issue()` |
| `Infrastructure/Outbox/OutboxEventAdapter.php` | payload v3: `'evidenceSet' => [...refs]` |
| `Infrastructure/Outbox/DeterminationIssuedHydrator.php` | window (v3, v2); v1 rejected loudly; `evidenceSet` required at v3, null at v2 |

## How it works

```php
$command = new IssueDeterminationCommand(
    /* ...ruling fields... */
    ContestedOutcomeRef::of($electionId, TargetType::ElectionResult, $targetId),
    EvidenceSet::fromRefs('envelope-sha256-abc', 'envelope-sha256-def'),  // what was considered
    $occurredAt,
);
// aggregate: issuance FIXES the set into the event — one act, one record
$determination->issue($outcome, $legitimacy, $reason, $evidenceSet, $at);
```

On the wire (payload v3, additive over v2):

```json
{ "schema_version": 3, "...": "...v2 fields unchanged...",
  "contestedOutcome": {"electionId": "...", "type": "...", "targetId": "..."},
  "evidenceSet": ["envelope-sha256-abc", "envelope-sha256-def"] }
```

Hydration: v3 → set required and reconstructed; **v2 → `evidenceSet === null`** (tolerated vPrevious); **v1 → `InvalidArgumentException`** (retired — the former v1-tolerance tests were superseded by rejection tests; succession, never history editing).

## Design decisions

- **Additive evolution, same event** (ADR-T5/ADR-T22): consumers that don't gate on `schema_version` keep working — verified for Election's and Contestation's handlers before implementation; the full correction loop (IT-1..4) runs green on v3 payloads.
- **Fail-loud VO:** empty/blank/duplicate refs throw. A silent dedupe would alter a constitutional fixation record.
- **Nullable on the event, non-nullable on the command:** new issuances always carry the set; only hydrated v2 history can be null.
- **Pre-deploy check (executed, not assumed):** zero pending v1 `DeterminationIssued` outbox rows before the window shift (verified 2026-07-26: zero pending rows of any version).

## Testing

- `tests/Unit/Contexts/Adjudication/EvidenceSetTest.php` — VO contract (5 tests).
- `DeterminationTest::test_issue_fixes_the_considered_evidence_set_in_the_event` — fixation + immutability keystone.
- `DeterminationIssuedHydratorTest` — v3 fidelity · v2 tolerance (null set) · v1 rejection ×2 · v3-without-set rejected.
- `AdjudicationServiceIntegrationTest` — REAL-wire round-trip: adapter row (schema_version 3 + set) → hydrator → set intact.
- Keystones written RED-first and confirmed failing for the expected reasons before GREEN.

## Pitfalls

- Never write a `FinalizeDeterminationCommand` or emit on `finalize()` — finality is Q-2's temporal policy (WP-6), unchanged by this slice.
- Never put transport (`schema_version`) on the VO or event — versioning lives at the adapter/hydrator seam only.
- The evidence refs are **opaque strings** — never resolve, interpret, or join them to anything voter-linked (ADR-T11).

## Traceability

ADR-T22 (issued 2026-07-26) · EPIC-004K §11 · EPIC-004E INV-4 rider (R-4-expanded) · EPIC-004F EvidenceSet deferral · `EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-1 · work plan `.claude/plans/WP-1-evidenceset-v3.md`.
