---
knowledge_id: HUB-ADJUDICATION
title: Topic Hub — Adjudication
knowledge_type: portal
bounded_context: adjudication
status: approved
authority: derived
audience: [developer, architect, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [adjudication, determination, challenge, hub]
related_to: [PORTAL-INDEX, HUB-ELECTION]
documents: [ADJ-README]
---

# Topic Hub — Adjudication

> The pilot domain — fully assembled in [`domains/adjudication/`](../../domains/adjudication/README.md). This hub is the quick map; the domain folder is the detail.

## The domain in one line

Given a challenge (from Contestation) and an evidence envelope, an **authority issues a Determination** (outcome `Upheld`/`Dismissed`) which becomes **Final** and immutable. Lifecycle: `Draft → Issued → Final`.

## Where everything lives

| Aspect | Knowledge | Code |
|---|---|---|
| Overview | [domain README](../../domains/adjudication/README.md) | [`app/Contexts/Adjudication`](../../../../app/Contexts/Adjudication) |
| Aggregate | [Determination aggregate](../../domains/adjudication/model/determination-aggregate.md) | [`Determination.php`](../../../../app/Contexts/Adjudication/Domain/Determination/Determination.php) |
| State machine | [Determination state machine](../../domains/adjudication/state-machines/determination-state-machine.md) | [`DeterminationState.php`](../../../../app/Contexts/Adjudication/Domain/Determination/DeterminationState.php) |
| Events | — | [`DeterminationIssued.php`](../../../../app/Contexts/Adjudication/Domain/Events/DeterminationIssued.php) |
| Wiring | [implementation](../../domains/adjudication/implementation/wiring.md) | [`AdjudicationServiceProvider.php`](../../../../app/Contexts/Adjudication/Infrastructure/Providers/AdjudicationServiceProvider.php) |
| Decision | [ADR-T-LOG](../../../adr/ADR-T-LOG-Tactical-Implementation.md) | — |
| Tests | [test notes](../../domains/adjudication/tests/README.md) | [`tests/Unit/Contexts/Adjudication`](../../../../tests/Unit/Contexts/Adjudication) |

## Boundary

Owns **only**: identity, constitutional outcome, legitimacy, issuing authority, jurisdiction, evidence references, finality. Does **not** own challenge/election workflow, correction execution, replay, notification, or persistence. Collaborates with **Contestation** (challenge) and **Evidence** (envelope) by reference only.
