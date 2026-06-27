# Implementation Landscape v1.0 (status & roadmap)

**Status:** 🚧 AKB Level-2 · LIVING · current implementation status + maturity + roadmap. Companion to the (authoritative, frozen) **Certified Strategic Architecture Landscape v1.0** — that doc says *what the architecture is*; this one says *how far it is built*. They evolve at different rates.
**Authority:** descriptive of *implementation progress*; it creates no architecture (landscape changes go through Landscape Certification + BDR + Architecture Review). **AKB Principle 01 applies: evidence beats memory.**
**Date:** 2026-06-27 · Branch `greenfield-core`.

## Per-confirmed-BC implementation status (the 5 Confirmed BCs)
| BC | Maturity (BDR v1.1) | Implementation today | Next |
|----|----------------------|----------------------|------|
| **Evidence** | Operational | exists in legacy code (immutable System of Record) | strangler-migrate (Round 49-07 order: Evidence → Appointment → Voting) |
| **Appointment/Mandate** | Operational | exists in legacy code (Mandate lifecycle) | strangler-migrate |
| **Voting** | Operational | exists in legacy code (anonymous vote SoR; Q7) | strangler-migrate **last** (live data + anonymity) |
| **Contestation** | Greenfield | **reference implementation** — Challenge aggregate + VOs + events + repository interface (TDD, green) | Push B: `Challenge.resolve()` |
| **Adjudication** | Greenfield | **reference implementation** — Determination aggregate + AdjudicationService + Eloquent persistence + outbox (Push A, 50/50 green) | Push B: legitimacy + correction reaction |

Non-BC items (no standalone module, per BDR v1.1): Authorization (service), Election Lifecycle (supporting), Audit (infrastructure), Replay (application capability), Results/Legitimacy (read models).

## Maturity
Architecture Maturity Model (AKB §5): currently **Level 6 — Implementation (~10–15%)**. Architecture Release **1.1 validated by implementation** (Push A; see `docs/implementation/Architecture_Baseline_1.1.md`). The greenfield Core is the **reference pattern** future contexts copy.

## Roadmap
```
Push A ✅  Adjudication persistence + outbox + arch suite (DONE)
Push B 🚧  Election reaction closes the loop:
            ADR-T17 (LegitimacyDecision placement) → DeterminationIssued → ElectionCorrectionApplied
            (ContainedOnly) → Challenge.resolve() → ChallengeResolved  (+ inbox/dedupe)
   ↓
Migration  strangler the 3 Operational BCs (Evidence → Appointment → Voting), per Round 49-07
   ↓
Empirical evaluation → LIT-METHOD → Release 1.1 → (later) remaining landscape items
```

## Reference-implementation principle
New work implements **bounded contexts by applying the proven pattern** (Architecture Baseline 1.1: aggregate → repository port → mapper → outbox adapter → transactional decorator → fitness tests), **not** by rediscovering architecture. Copy the pattern; don't reinvent it.

## Architecture debt (separate workstream)
7 pre-existing legacy architecture-test failures (AD-001..AD-005) tracked in `docs/implementation/Architecture_Debt_Backlog.md` — none in the greenfield Core. Tooling: Deptrac PHAR + Infection coverage driver (CI). All tracked, none blocking.

## Honest scope
The 3 Operational BCs are "implemented" in the **legacy** codebase (behavior verified per BDR), **not yet migrated** to the greenfield pattern. Only Contestation + Adjudication are greenfield reference implementations. This document reports status; it does not certify architecture.

---
*Implementation Landscape v1.0 — status/roadmap companion to the Certified Strategic Architecture Landscape v1.0. 5 Confirmed BCs: 3 Operational (legacy, to migrate) + 2 Greenfield (reference impl). Maturity Level 6 (~10-15%). Push A done; Push B next. Reference pattern = Baseline 1.1. Debt tracked separately.*
