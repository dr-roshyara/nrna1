# PB-007 (Merge Gate) — Phase 1 Discovery

**Status:** Discovery (no IDD, no code). **2026-07-10.** ARB pre-condition satisfied first: gates are classified **mandatory-architecture vs quality-improvement** so PB-007 stays an architecture qualification, not a tooling exercise.

## 1. Gate classification (ARB-required)
| Gate | Class | Rationale |
|---|---|---|
| Architecture fitness suites (`GreenfieldCoreArchitectureTest`, Messaging fitness) | **Mandatory architecture gate** | Executable architecture — hexagonal completeness, event ownership, anonymity |
| greenfield PHPStan (max) | **Mandatory architecture gate** | Type-level correctness of the greenfield Core |
| **Deptrac (F-1)** | **Mandatory architecture gate** | Dependency direction + bounded-context isolation — architecture conformance, deferred-by-roadmap to here |
| Widened full regression (incl. `tests/Feature/Contexts/Shared`) | **Mandatory architecture gate** | Behavioural safety net (F-PB006-4 lesson) |
| **Infection (F-2)** mutation testing | **Quality-improvement gate** | Test-suite strength (MSI); valuable, but not an architecture conformance question |
| Coverage thresholds / pre-existing max-level PHPStan cleanup (~50 findings) | **Quality-improvement** | Recorded debt; never a merge blocker retroactively |

Merge rule that follows: **mandatory gates block the merge; quality gates report and trend** (thresholds ratcheted deliberately, not imposed retroactively).

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

## Constraints (standing)
No domain/aggregate/platform-logic changes · pre-existing findings recorded, not fixed · workflow: Discovery → IDD → ARB → RED → GREEN → Qualification → Completion Review → STOP.

## STOP — awaiting ARB review of this discovery + the gate classification before the PB-007 IDD.
