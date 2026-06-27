---
knowledge_id: ADJ-DISC-BOUNDARY
title: Determination — Boundary Discovery
knowledge_type: ddd-discovery
bounded_context: adjudication
status: approved
authority: authoritative
audience: [architect, developer, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [adjudication, discovery, boundary, ddd]
related_to: [ADJ-README]
documents: [ADJ-MODEL-DETERMINATION]
---

# Determination — Boundary Discovery

> Why the Determination aggregate owns what it owns (Round 50-07 v1.2). This is the discovery record that the [aggregate design](../model/determination-aggregate.md) and [state machine](../state-machines/determination-state-machine.md) derive from.

## Question explored

When a challenge is decided, *which* facts and behaviours belong inside the Adjudication context, and which belong to neighbouring contexts (Contestation, Evidence, Election correction)?

## Findings

- **Owns:** identity, constitutional outcome (`Upheld`/`Dismissed`), legitimacy, issuing authority, jurisdiction, evidence reference, finality.
- **Does not own:** challenge intake/routing (Contestation), evidence storage (Evidence), correction execution (Election), notification, replay, persistence semantics.
- **Collaboration is by reference + domain events only** — `ChallengeRef`, `EvidenceEnvelopeRef`; emits `DeterminationIssued`. No direct cross-context code imports (Deptrac-enforced).

## Invariants surfaced

- Issued exactly once; `Final` is terminal and immutable.
- Illegal transitions are inert (no mutation, no event).
- Ruling content lives in the emitted event / read row, not retained in aggregate state.
- Time is injected (clock authority), never read inside the domain.

## Excluded (revisit only via ADR)

- Remand / partial outcomes (deferred in v1.2).
