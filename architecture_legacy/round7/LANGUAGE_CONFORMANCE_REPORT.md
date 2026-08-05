# Round 7: Language Conformance Report

**Date:** 2026-06-14  
**Purpose:** Verify alignment among all language representations

## Source Comparison

| Source | Action Count | Missing | Status |
|--------|-------------|---------|--------|
| `ElectionConstitution::RULES` (authoritative) | 15 | — | ✅ SSOT |
| `ElectionAction` PHP enum | 10 | `begin_setup`, `revise_and_resubmit`, `complete_nomination`, `apply_candidacy`, `archive` | ⚠️ INCOMPLETE |
| `ElectionActions` frontend constant | 15 | none | ✅ FULL |
| `StateMachineContract` frontend interface | 15 | none | ✅ FULL |
| `ElectionLifecycleState` PHP enum | 12 | none | ✅ FULL |
| `ElectionLifecycleStates` frontend constant | 11 | `suspended` is in PHP but lifecycle only | ✅ FULL |

## Enum Usage Impact

| Usage | Location | Drift Impact |
|-------|----------|-------------|
| `ElectionAction::tryFrom($action)` | `TransitionMatrix.php:132` (deprecated) | **None** — deprecated code path |
| Type hints referencing `ElectionAction` | Not found | **None** — enum is not used in signatures |

**Finding:** The `ElectionAction` PHP enum is effectively unused in production code. The deprecated `TransitionMatrix` references it via `tryFrom()`, but this path is superseded by the Constitution + Guard. The drift has zero runtime impact.

## Conclusion

| Language Source | Alignment | Remediation Priority |
|----------------|-----------|---------------------|
| Constitution (SSOT) | ✅ — | — |
| Frontend constants | ✅ Full | — |
| Frontend contract types | ✅ Full | — |
| Lifecycle state enums | ✅ Full | — |
| PHP `ElectionAction` enum | ⚠️ 5 missing | **P2** — low impact, enum unused in production |

## Architectural Rule

`ElectionConstitution::RULES` is the authoritative language source.

The following artifacts are derived representations:
- `ElectionAction` PHP enum
- `ElectionActions` frontend constants
- `StateMachineContract` capability definitions
- `CapabilityResolver` action registry
- `ElectionLifecycleEngineImpl` allowed-actions lists

Future additions, removals, or renames of actions must originate in the Constitution and propagate outward. No derived representation may introduce actions independently.

## Hardcoded String Audit

**Method:** Searched all 15 action strings across `app/` excluding Constitution, frontend constants, enum definitions, and tests.

**Finding:** `ElectionLifecycleEngineImpl::getAllowedActions()` re-lists allowed actions per state as inline arrays. These should ideally be derived from the Constitution rather than duplicated. `OperationCapabilityMapper` also contains a `'apply_candidacy'` string with its own state check — a second capability evaluation point outside the resolver.

**Status:** Minor duplication in EngineImpl. One instance of parallel capability logic in middleware. No immediate risk — documented for P3 remediation.
