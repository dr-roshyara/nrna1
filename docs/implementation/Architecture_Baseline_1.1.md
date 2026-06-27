# Architecture Baseline — Release 1.1 (FROZEN)

**Status:** 🧊 FROZEN reference implementation — **VALIDATED BY IMPLEMENTATION (Push A)**. The canonical baseline against which all future greenfield-Core code reviews are measured. Change only via ADR-T + Architecture Review.
**Date:** 2026-06-27 · Branch `greenfield-core` · Evidence: 50/50 green (incl. real-DB integration); PHPStan level-max clean; Architecture Release 1.1 Readiness Review = PASS (no frozen decision invalidated).

> **FORMAL DECLARATION: Architecture Release 1.1 successfully validated by implementation.** The architectural foundations — bounded contexts, aggregate design, application orchestration, transactional boundaries, event flow, governance — have been exercised through a real, tested implementation and held. This is a **reference implementation** for the rest of the platform; it is **not** feature-complete or production-ready.

## Frozen reference implementation
| Element | Reference | Status |
|---------|-----------|--------|
| **Challenge** aggregate | `app/Contexts/Contestation/Domain/Challenge/Challenge.php` (Raised→Admitted→Routed→Resolved/Dismissed/Lapsed) | FROZEN |
| **Determination** aggregate | `app/Contexts/Adjudication/Domain/Determination/Determination.php` (Draft→Issued→Final) | FROZEN |
| **AdjudicationService** | `…/Adjudication/Application/Service/CoordinatesAdjudication.php` (orchestration only; Option A) | FROZEN |
| **Repository contracts** | `ChallengeRepository`, `DeterminationRepository` (interfaces in Domain) | FROZEN |
| **EventOutbox** | Application Port (`…/Application/Port/EventOutbox.php`) | FROZEN |
| **Canonical event flow** | `DeterminationIssued` (sole Adjudication event; Catalog v1.0) | FROZEN |
| **Application ports** (Push A) | `IdentityGenerator`, `TransactionManager`, `EventOutbox` | FROZEN |
| **Transaction decorator** (Push A) | `TransactionalAdjudicationService` over frozen `CoordinatesAdjudication` | FROZEN |
| **Persistence** (Push A) | `EloquentDeterminationRepository` + `DeterminationMapper` + `determinations` migration (state-only; `UNIQUE(org,challenge_ref)`) | FROZEN |
| **Outbox adapter** (Push A) | `OutboxEventAdapter` → existing `OutboxEvent`/`outbox:process` | FROZEN |
| **Determination persistence model** | Model B (ADR-T19): aggregate=state; ruling content in the event | FROZEN |

## What this baseline establishes (the pattern to copy)
- **DDD-first hybrid** style (Handbook Part VII): bounded context → aggregate → port → adapter.
- Aggregate: `final`, private ctor + factory, behavior-not-state, invariants internal, forbidden-transition→throw+no-mutation, `pullEvents()`, time injected.
- Value objects: `final readonly`, validate-in-ctor. Cross-context refs = local opaque VOs (ADR-T16).
- One aggregate per transaction (ADR-T1/T14); event-is-seam (TP-1); request-not-create (TP-2); events version-never-mutate (TP-3).
- Q7 anonymity: no voter↔vote linkage anywhere (fitness-tested AT-Q7-001).
- Event publication via outbox port (ADR-T15), never publish-after-commit.

## How to use this baseline in review
A PR conforms iff it follows the reference patterns above and passes the Architecture + Security review gates (Playbook). Divergence requires an ADR-T. Any change to a FROZEN element above requires ADR-T + Architecture Review.

## Outstanding (does not affect the baseline)
- **ADR-T17** (LegitimacyDecision placement) — first item of Push B. **ADR-T19** (Determination persistence model) — RECORDED.
- **Architecture Debt backlog** (`Architecture_Debt_Backlog.md`) — 7 pre-existing legacy arch-test failures (AD-001..AD-005); separate workstream.
- Tooling: Deptrac PHAR URL + Infection coverage driver (CI) — tracked.
- **Push B** — Election reaction closes the loop (`DeterminationIssued` → `ElectionCorrectionApplied` → `Challenge.resolve()` → `ChallengeResolved`) + inbox/dedupe.

---
*Architecture Baseline 1.1 — FROZEN. Reference implementation: Challenge + Determination aggregates, AdjudicationService, repository contracts, EventOutbox port, canonical event flow. The pattern future code copies; the yardstick for code review. Backed by Readiness Review PASS (no frozen decision invalidated).*
