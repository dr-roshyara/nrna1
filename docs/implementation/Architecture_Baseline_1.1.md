# Architecture Baseline — Release 1.1 (FROZEN)

**Status:** 🧊 FROZEN reference implementation. The canonical baseline against which all future greenfield-Core code reviews are measured. Change only via ADR-T + Architecture Review.
**Date:** 2026-06-27 · Branch `greenfield-core` · Evidence: 48/48 green; Architecture Release 1.1 Readiness Review = PASS (no frozen decision invalidated).

## Frozen reference implementation
| Element | Reference | Status |
|---------|-----------|--------|
| **Challenge** aggregate | `app/Contexts/Contestation/Domain/Challenge/Challenge.php` (Raised→Admitted→Routed→Resolved/Dismissed/Lapsed) | FROZEN |
| **Determination** aggregate | `app/Contexts/Adjudication/Domain/Determination/Determination.php` (Draft→Issued→Final) | FROZEN |
| **AdjudicationService** | `…/Adjudication/Application/Service/CoordinatesAdjudication.php` (orchestration only; Option A) | FROZEN |
| **Repository contracts** | `ChallengeRepository`, `DeterminationRepository` (interfaces in Domain) | FROZEN |
| **EventOutbox** | Application Port (`…/Application/Port/EventOutbox.php`) | FROZEN |
| **Canonical event flow** | `DeterminationIssued` (sole Adjudication event; Catalog v1.0) | FROZEN |

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
- **ADR-T17** (LegitimacyDecision placement) — postponed to Push B start.
- Push A infrastructure (Eloquent/outbox/inbox/CI gates); Push B Election reaction.

---
*Architecture Baseline 1.1 — FROZEN. Reference implementation: Challenge + Determination aggregates, AdjudicationService, repository contracts, EventOutbox port, canonical event flow. The pattern future code copies; the yardstick for code review. Backed by Readiness Review PASS (no frozen decision invalidated).*
