# 05 — `ChallengeRouted` as published language (WP-3A)

**Step:** WP-3A — *Published Language Infrastructure* · **Decision realized:** ADR-T21

## Purpose

`ChallengeRouted` was an **internal** Contestation domain event: recorded by `Challenge::route()`, never announced. ADR-T21 makes it the **correction loop's head trigger**, consumed by the Adjudication Process Manager. This step gives it published-language status.

## The relationship that defines "published language"

```
Published Language
        ├── Publication    — the event travels over the wire (outbox row, schema v1)
        └── Registration   — the event can be reconstructed from that wire form (hydrator)
```

> **Published language requires BOTH.** An event is not part of the published language unless it can both travel over the wire *and* be reconstructed from that wire representation.

Either alone is incomplete: publication without registration produces an unreconstructable row; registration without publication produces a reader for something nothing sends.

## Where it fits

Contestation owns the Challenge and therefore **owns and publishes** this event — TP-2: *Contestation requests, never creates* an adjudication. Adjudication **consumes** it (WP-4). Identity crosses as strings; the consumer reconstructs its own local VOs (ADR-T16).

## Key files

| File | Role |
|------|------|
| `Domain/Events/ChallengeRouted.php` | **Unchanged.** Already existed; WP-3A *publishes* it rather than creating it |
| `Infrastructure/Outbox/ChallengeOutboxAdapter.php` | `writeRouted()` — the publication half; payload **schema v1** |
| `Infrastructure/Outbox/ChallengeRoutedHydrator.php` | The registration half; reconstructs the minimal domain event |
| `Infrastructure/Providers/ContestationServiceProvider.php` | Registers the hydrator in `EventHydratorRegistry` |

## How it works

```php
// Publication — provenance is SUPPLIED, never minted here
$outbox->enqueue($provenance, new ChallengeRouted($challengeId, 'constitutional-council', $at));
```

On the wire (schema v1):

```json
{ "schema_version": 1,
  "challengeId": "7c8d9e0f-…",
  "routedTo": "constitutional-council",
  "occurredAt": "2026-07-30T10:15:30+00:00" }
```

Reconstruction: `EventHydratorRegistry::hydratorFor('ChallengeRouted')` → the registered hydrator → the minimal domain event.

## Design decisions

- **The domain event stays minimal** (PB-005's F-2 ruling): exactly the three facts routing records. Publication-time enrichment, if ever needed, belongs to the Application layer — never to the domain event. Pinned by a test asserting the event's property set.
- **Provenance is supplied, never minted here** (ADR-MP-06). WP-3A adds **no** mint site. Relocating the correlation origin to the routing act is a separate slice — *Correlation Origin Relocation* — which **depends on the existence of a routing application service**; there is no application-layer caller of `Challenge::route()` today. Attempting it now would leave **zero** production mint sites and break the correlation chain.
- **Causation is null for a chain start.** ADR-MP-06's invariant is *causation = immediate parent*; a chain-starting event has no parent, and `EventProvenance::start()` returns `(correlationId, null)` accordingly.
- **Schema starts at v1.** A newly published event has no vPrevious, so v2 is rejected and an absent marker means v1 (ADR-T5).
- **Anonymity (ADR-T11/AT-Q7):** the payload announces *that a challenge was routed* — never who raised it, and nothing that could link a voter to a vote. Asserted at both payload-shape and real-wire levels.

## Testing

- `tests/Unit/Contexts/Contestation/Infrastructure/Outbox/ChallengeRoutedHydratorTest.php` — round-trip fidelity · minimality · version handling (v1 · absent = v1 · v2 rejected) · missing field · payload anonymity.
- `tests/Feature/Contexts/Contestation/ChallengeRoutedPublicationTest.php` — outbox row at schema v1 · chain-start provenance stamped unchanged (causation null) · registry resolves the booted hydrator · real-wire anonymity.
- Written **RED first** (11 tests, 10 failing for expected reasons) before any production code.

## Pitfalls

- **Do not add `EventProvenance::start()` anywhere in Contestation** until the routing application service exists — the minting fitness test allowlists exactly one chain origin, and a second mint would violate *one mint per conversation*.
- **Do not enrich the domain event** at publication time; enrichment lives in the Application layer (the `ChallengeResolvedIntegration` carrier is the precedent, and it was explicitly ruled a temporary carrier, not a pattern).
- `correlation_id` / `causation_id` are **UUID columns** — test data must be valid UUIDs.
- **Known catalog inconsistency (recorded, not fixed here):** `Canonical_Event_Catalog_v1.0.md` is **🧊 FROZEN** and marks `ChallengeRouted`, `ChallengeAdjudicated` and `ChallengeResolved` as `internal`, although all three are now published. The catalog's own rule is *"Changes follow ADR-T5 — version, never mutate"*, so correcting the marking requires a **v1.1 catalog** — an ARB act, not an implementation act. This slice implements against the ADR and records the staleness, following the precedent PB-005 set for two of these same events.

## Traceability

ADR-T21 (issued 2026-07-26; authorized as WP-3 on 2026-07-30) · ADR-T3 (transactional outbox) · ADR-T5 + `Event_Registry.md` · ADR-MP-06 (Constitutional Audit Invariant) · ADR-T16 · ADR-T20 · ADR-T11/AT-Q7 · PB-005 F-2 (minimal domain event) · plan `.claude/plans/WP-3-challengerouted-published-language.md`.
