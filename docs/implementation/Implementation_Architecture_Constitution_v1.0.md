# Implementation Architecture Constitution v1.0 (FROZEN)

**Status:** 🧊 FROZEN · implementation governance (NOT methodology). The normative rules every contributor — human or AI — must obey when writing greenfield-Core code. Consolidates rules spread across ADR-T log, 50-06/07/08/09, Event Catalog, Failure Strategy, Playbook, CLAUDE.md.
**Date:** 2026-06-26 · Enforced by: PHPUnit `tests/Architecture/` + Deptrac + PHPStan (ADR-T7).

## Required reading before any code (in order)
Architecture Release 1.0 → BDR v1.1 → Architecture Migration Plan → 50-01 Aggregate Discovery → 50-02 Aggregate Review → 50-04 Domain Event Design → 50-05 Event Catalogue → 50-06 Policy Catalogue → 50-07 v1.2 State Machines → 50-08 Repository & Transaction → **50-09 Verification gate** → ADR-T log → Canonical Event Catalog v1.0 → Failure Strategy → Greenfield Core Playbook → Architecture Overview → Implementation Traceability Matrix → **this Constitution**.

## Forbidden patterns (build-breaking)
- ❌ Eloquent / `Illuminate\*` / facades / `Carbon` **in Domain or Application**.
- ❌ Repository **outside Infrastructure**.
- ❌ Any **event not in the Canonical Event Catalog**.
- ❌ **Cross-aggregate transaction** (two roots in one txn) — ADR-T1.
- ❌ **Aggregate creating another aggregate** (request-not-create, TP-2).
- ❌ **Infrastructure dependency inside an aggregate**.
- ❌ **Public setters** / mutable value objects / anemic domain.
- ❌ **`user_id` / voter↔vote linkage** anywhere in Domain/Event/projection (Q7, ADR-T11).
- ❌ `now()` / wall-clock **inside Domain** (inject time — clock authority).
- ❌ **Policy bypass** — every decision passes its guard (50-06).

## Mandatory rules
- ✅ **Layering:** Domain ← Application ← Infrastructure (deps point inward only).
- ✅ **One aggregate per transaction** + its outbox row (ADR-T1/T3).
- ✅ **One repository per aggregate root**; reads via read models (ADR-T6).
- ✅ **Events**: readonly, single producer, from the Catalog; version-never-mutate (ADR-T5).
- ✅ **Aggregates**: `final`, private constructor + named factory producing a valid object; invariants enforced internally; expose behavior, not state.
- ✅ **Value objects**: `final readonly`, validate in constructor.
- ✅ **Forbidden transitions throw** `DomainException`, no mutation, audited (50-07).
- ✅ **Idempotent consumers** via inbox/dedupe on `EventId` (ADR-T4).
- ✅ **Fail-closed** on trust-critical dependency loss (Safe Halt — Failure Strategy).

## TDD cycle (mandatory — not RED→GREEN only)
`RED → GREEN → REFACTOR → architecture tests → static analysis (PHPStan max) → mutation tests → Architecture+Security review gates → merge.`

## Toolchain (ADR-T7)
Deptrac (boundaries/layers) · PHPStan level max · PHPUnit (`tests/Architecture` constitutional rules + behavioral) · *(mutation testing to be added: Infection).* CI must run all; any red blocks merge.

## Traceability (every class traces back)
Each implemented class cites its lineage, e.g. `Challenge → 50-01/02/07 → ADR-T1/T11/T12 → BDR-05 → Catalog`. The Implementation Traceability Matrix is the dashboard; the Architecture Review Gate checks it.

## Amendment
This Constitution changes only by an ADR-T + Architecture Review Gate sign-off. It does not reopen architecture (BDR/Release 1.0 frozen).

---
*Implementation Architecture Constitution v1.0 — FROZEN. Required reading order; forbidden patterns (build-breaking); mandatory layering/aggregate/event/repository/TDD/tooling rules; fail-closed; traceability. Implementation governance, not methodology. Amend only via ADR-T + review gate.*
