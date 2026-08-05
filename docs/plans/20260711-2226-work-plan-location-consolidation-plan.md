# Work-Plan Location Consolidation — Mechanical Migration Plan

**Kind:** Engineering Plan (the first under the ES-004.2 convention — governed, EP-01 object) · **Status:** **APPROVED & CLOSED** (ARB 2026-07-11: "Move nothing … is actually the safest architectural decision. History should not be rewritten merely for cosmetic consistency. I would approve that."). Item 1 executed by definition (nothing moved) · item 2 already in force (DetermineArtifactLifecycle) · item 3 (optional README) not ordered — skipped.
**Objective:** complete the mechanical consequences of the adopted plan-concept decision (Work Plan ≠ Engineering Plan) with zero damage to historical records.

## Already done (prior commits — listed for completeness, no action)
1. `plansDirectory` healed to `./.claude/plans` in BOTH settings files (the no-dot `claude/plans/` typo can no longer reproduce).
2. ES-004.2 scope + naming (`YYYYMMDD-HHMM-<what_is_it_about>-plan.md`, `./docs/plans/`) + both CLAUDE.md pointers.

## Proposed remaining actions
| # | Action | Decision |
|---|---|---|
| 1 | **Move NOTHING.** All 41 existing files in `.claude/plans/`, `claude/plans/`, `docs/plans/` stand where they are. | Rationale: ES-004.2 — *historical records are never path-updated*. Several runtime-named files are **cited by path from the rulings register** (`swirling-jingling-blossom.md` ← R-36) and by ARB ruling (`first-read-docs-to-merry-wreath.md`); moving them breaks governance references. The no-dot folder is frozen history, not a live location — it simply stops growing. |
| 2 | **Forward promotion rule (behavioral, already in force via DetermineArtifactLifecycle):** when Work-Plan content is approved (EP-01) or becomes governance-referenced, it is promoted into `docs/plans/YYYYMMDD-HHMM-…-plan.md`; the runtime file is thereafter disposable. | No tooling; EP-02 review catches misses. |
| 3 | Optional hygiene (ARB may decline): a one-line README in `claude/plans/` stating "frozen historical location — see ES-004.2". | Cheap; prevents future confusion; touches no records. |

**Explicitly rejected:** bulk deletion of auto-named work plans (some are governance-cited; the rest are harmless history) · renaming historical plans to the new convention (path-update of history).

**STOP — ARB approval decides items 1–3.**

---
*Traceability: Plan Concept Decision Paper (HISTORICAL) → ES-004.2 + DetermineArtifactLifecycle; ARB integration instruction 2026-07-11.*
