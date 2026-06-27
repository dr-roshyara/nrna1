# Implementation Landscape v1.0 (status & roadmap)

**Status:** 🚧 AKB Level-2 · LIVING · current implementation status + maturity + roadmap. Companion to the (authoritative, frozen) **Certified Strategic Architecture Landscape v1.0** — that doc says *what the architecture is*; this one says *how far it is built*. They evolve at different rates.
**Authority:** descriptive of *implementation progress*; it creates no architecture (landscape changes go through Landscape Certification + BDR + Architecture Review). **AKB Principle 01 applies: evidence beats memory.**
**Date:** 2026-06-27 · Branch `greenfield-core`.

## Per-confirmed-BC implementation status (the 5 Confirmed BCs)
| BC | Certified | Implementation | Maturity (BDR v1.1) | Next |
|----|:---------:|----------------|---------------------|------|
| **Evidence** | ✅ | Legacy | Operational | strangler-migrate (Round 49-07 order: Evidence → Appointment → Voting) |
| **Appointment/Mandate** | ✅ | Legacy | Operational | strangler-migrate |
| **Voting** | ✅ | Legacy | Operational | strangler-migrate **last** (live data + anonymity) |
| **Contestation** | ✅ | Greenfield | **Reference** | Push B: `Challenge.resolve()` |
| **Adjudication** | ✅ | Greenfield | **Reference** | Push B: legitimacy + correction reaction |

*Detail — greenfield Core:* Contestation = Challenge aggregate + VOs + events + repo interface (TDD green); Adjudication = Determination aggregate + AdjudicationService + Eloquent persistence + outbox (Push A, 50/50 green).

Non-BC items (no standalone module, per BDR v1.1): Authorization (service), Election Lifecycle (supporting), Audit (infrastructure), Replay (application capability), Results/Legitimacy (read models).

## Implementation maturity
Architecture Maturity Model (AKB §5): currently **Level 6 — Implementation (~10–15%)**.
> **Architecture Release 1.1 has been validated *through the implementation* of the greenfield-Core reference bounded contexts (Contestation and Adjudication).** The remaining confirmed BCs are **architecturally certified but not yet migrated** to the reference-implementation pattern.

See `docs/implementation/Architecture_Baseline_1.1.md`. The greenfield Core is the **reference pattern** future contexts copy.

## Roadmap by bounded context (implementation dashboard)
```
Contestation   ██████████  reference (domain+app)      Push B: resolve()
Adjudication   ██████████  reference (Push A: persistence+outbox)   Push B: legitimacy+reaction
Correction loop ██░░░░░░░░  Push B (Election reaction → ChallengeResolved)
Evidence       ▓▓▓▓▓▓▓▓▓▓  legacy (operational) — to strangler-migrate
Appointment    ▓▓▓▓▓▓▓▓▓▓  legacy (operational) — to strangler-migrate
Voting         ▓▓▓▓▓▓▓▓▓▓  legacy (operational) — migrate last (anonymity)
```
*(█ greenfield reference · ▓ legacy operational · ░ not yet built)*

## Quality gates (greenfield Core engineering health)
| Gate | Status |
|------|--------|
| TDD-first | ✅ |
| Architecture fitness tests | ✅ (registered; greenfield green) |
| PHPStan (level max) | ✅ clean |
| Integration tests (real DB + outbox) | ✅ |
| Transactional outbox | ✅ |
| Deptrac | ⏳ (PHAR URL; config staged) |
| Mutation testing (Infection) | ⏳ (needs pcov/xdebug in CI) |

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
**Copy the pattern; don't reinvent it.** New work implements bounded contexts by **applying the proven Baseline 1.1 pattern**, not by rediscovering architecture:
```
Aggregate (final; invariants; pullEvents)
  → Repository Port (Domain interface)
  → Application Service (orchestration only)
  → Transaction Decorator (owns the txn)
  → Infrastructure Repository (Eloquent + Mapper)
  → Outbox Adapter (EventOutbox port → outbox_events)
  → Architecture fitness tests  →  Integration tests
```

## Architecture debt (separate workstream)
7 pre-existing legacy architecture-test failures (AD-001..AD-005) tracked in `docs/implementation/Architecture_Debt_Backlog.md` — none in the greenfield Core. Tooling: Deptrac PHAR + Infection coverage driver (CI). All tracked, none blocking.

## Honest scope
The 3 Operational BCs are "implemented" in the **legacy** codebase (behavior verified per BDR), **not yet migrated** to the greenfield pattern. Only Contestation + Adjudication are greenfield reference implementations. This document reports status; it does not certify architecture.

## Document relationships
- **Parent:** Architecture Knowledge Portal (AKB index).
- **Authoritative sibling:** Certified Strategic Architecture Landscape v1.0 (`docs/architecture/`).
- **Related:** Architecture Baseline 1.1 · Implementation Architecture Constitution v1.0 · Package Structure & Naming v1.0 · Coding Standard v1.0 · Architecture Debt Backlog · ADR-T log (all in `docs/implementation/` & `docs/adr/`).
- **Children:** push reports (Push A/B), migration guides, implementation slices.

---
*Implementation Landscape v1.0 — status/roadmap companion to the Certified Strategic Architecture Landscape v1.0. 5 Confirmed BCs: 3 Operational (legacy, to migrate) + 2 Greenfield (reference impl). Maturity Level 6 (~10-15%). Push A done; Push B next. Reference pattern = Baseline 1.1. Debt tracked separately.*
