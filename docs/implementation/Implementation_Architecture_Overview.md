# Implementation Architecture Overview (one page)

**Status:** implementation-facing · the first page every new developer reads. How the certified architecture maps to running code. References: ADR-T log · Canonical Event Catalog v1.0 · BDR 1.1 · 50-06/07/08.
**Date:** 2026-06-26 · Stack: Laravel 11 · Inertia 2.0 · Vue 3 · PHP 8.

## Layered flow (request → decision → event → read model)
```mermaid
flowchart TD
    P[Presentation — Vue 3 / Inertia 2.0 pages] --> A
    A[Application Service — commands, request-not-create TP-2] --> AGG
    AGG[Aggregate — invariants, state machine 50-07] --> POL
    POL[Policies — Invariant/Decision/Authorization/Validation/Calculation 50-06] --> EV
    EV[Domain Event — Canonical Catalog v1.0] --> REPO
    REPO[Repository — one per aggregate root, ADR-T6] --> INFRA
    INFRA[(Infrastructure — Eloquent / DB)] --> OUTBOX
    OUTBOX[[Transactional Outbox — same txn, ADR-T3]] --> BUS
    BUS{{Event dispatch — at-least-once}} --> INBOX
    INBOX[[Inbox / dedupe on EventId — ADR-T4]] --> RM
    RM[Read Models / Projections — Results, Legitimacy, Audit]
    BUS --> DLQ[(Dead-letter — DeadLetterEntry)]
```

## Boundaries (one transaction = one aggregate + its outbox row — ADR-T1)
```mermaid
flowchart LR
    subgraph Contexts [app/Contexts/* — authoritative code home]
      VOT[Voting]:::op
      EVD[Evidence]:::op
      APP[Appointment]:::op
      CON[Contestation]:::green
      ADJ[Adjudication]:::green
      ELC[Election/Lifecycle]
      AUD[Audit — consumes all]
    end
    VOT -- VoteAccepted --> EVD
    EVD -- EvidenceRecorded --> ADJ
    CON -- ChallengeRouted --> ADJ
    ADJ -- DeterminationIssued --> ELC
    ELC -- ElectionCorrectionApplied --> CON
    classDef op fill:#dde;
    classDef green fill:#dfd;
```
Green = greenfield Core (build first). Edges = domain events only (event is the seam, TP-1); never direct cross-aggregate calls.

## Layer rules (CLAUDE.md discipline, enforced by Deptrac — ADR-T7)
| Layer | Laravel allowed? | Rule |
|-------|------------------|------|
| Domain (aggregates/VOs/policies) | ❌ none | pure PHP; final classes |
| Application (commands/services) | limited | constructor injection; no facades/Eloquent |
| Infrastructure (repos/outbox/inbox) | ✅ freely | Eloquent, facades |
| Presentation (Inertia/Vue) | ✅ | `router.post()` not raw fetch |

## Invariants that cross every layer
**Q7 anonymity** (no voter↔vote linkage anywhere) · **one-aggregate-per-txn** · **events version-never-mutate** · **single producer per event**. All fitness-tested.

---
*Implementation Architecture Overview — layered flow + context boundaries + layer rules on one page; greenfield Core highlighted; event-is-the-seam; Q7 anonymity crosses all layers.*
