# PB-007 (Merge Gate) — Phase 1 Discovery

**Status:** Discovery (no IDD, no code). **2026-07-10.** ARB pre-condition satisfied first: gates are classified **mandatory-architecture vs quality-improvement** so PB-007 stays an architecture qualification, not a tooling exercise.

## 1. Gate taxonomy (ARB rulings 1+2 applied)
| Gate | Category | Local/CI | Merge behaviour |
|---|---|---|---|
| Architecture fitness suites (Greenfield + Messaging) | **Architecture** | **Both** | Blocks |
| **Deptrac (F-1)** | **Architecture** | **Both** | Blocks |
| Widened full regression (incl. Shared Feature suites) | **Behaviour** | **Both** | Blocks |
| greenfield PHPStan (max) | **Engineering** | **Both** | Blocks (implementation correctness, not architecture proof - per ARB distinction) |
| **Infection (F-2)** MSI | **Engineering Improvement** | **CI only** (too slow locally) | Reports/trends - baseline then ratchet, never retroactive |
| Coverage | **Reporting** | CI only | Reports |
| Pre-existing max-level findings (~50) | Reporting (debt register) | - | Never a retroactive blocker |

**Single entry point (ARB ruling 3):** developers run ONE command - a composer script (e.g. `composer merge-gate`) executing every blocking gate in order with a single PASS/FAIL; `composer quality-gate` runs the reporting tier. Nobody memorizes five commands.

## 2. Tooling state (Observed)
- **Deptrac:** `deptrac.yaml` EXISTS (hexagonal layering + context isolation; header: "Activate: composer require --dev deptrac/deptrac") but the **binary is NOT installed** (`vendor/bin/deptrac` absent). Config is scoped to Contestation+Adjudication only — "widen as contexts are migrated" ⇒ **Election (now complete hexagonal) and Shared Messaging should join the scope** (IDD decision).
- **Infection:** package **installed** (`infection/infection ^0.29.10`, extension-installer allowed) but **no `infection.json`** — config + scope (greenfield-only) + initial MSI baseline are IDD decisions.
- **CI:** three workflows exist (`knowledge-lint`, `membership-architecture`, `regression-detector`); **no greenfield merge-gate workflow** — IDD decides: new workflow vs extending `regression-detector`.

## 3. Open questions for the IDD
1. Deptrac scope widening (add Election; whether/how to layer-check `Shared` messaging) and ruleset severity (fail vs report per rule initially).
2. Infection scope + initial MSI threshold (baseline-then-ratchet recommended, never a retroactive blocker).
3. CI wiring shape (new `greenfield-merge-gate.yml` recommended — keeps mandatory gates isolated and readable).
4. Installing the Deptrac binary requires `composer require --dev` (network + lockfile change) — flagged for approval.
5. Also on PB-007's plate per ARB: the **CorrelationId-minting fitness test** (deferred here from PB-006 closure).

## 4. DoD-warning classification (ARB ruling 4 - answered with evidence)
PB-006's only provider/database changes: AppServiceProvider +15 lines (the ADR-MP-06 resolver binding + dispatcher listener - MESSAGING wiring, no provider-pattern semantics changed) and ONE additive outbox migration (+31 lines - messaging schema). Both documented in the owning area (guides 07 wiring, 08 schema pointers). **Verdict: the warning reflects an overly broad area mapping, not a documentation gap** - provider/database edits made FOR messaging belong to the messaging guides, which are current. Reminder area-mapping refinement = retrospective observation (the hook is frozen platform).

## Constraints (standing)
No domain/aggregate/platform-logic changes · pre-existing findings recorded, not fixed · workflow: Discovery → IDD → ARB → RED → GREEN → Qualification → Completion Review → STOP.

## STATUS: Discovery APPROVED with 4 rulings (applied above) - IDD produced; awaiting IDD review before implementation.
