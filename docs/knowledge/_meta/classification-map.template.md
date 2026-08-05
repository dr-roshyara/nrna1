# Classification Map — Template

**Purpose:** the **Phase 1** deliverable of `docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md`. One row per document: **what it is**, and therefore **where it derives to**.

> **Producing this map moves nothing.** It is the prerequisite for migration, not the migration. **Classification precedes placement.**

## How to fill it

**Classify first, then resolve — never the reverse.** Current location is *not* evidence of classification (ADR invariant 1); record it only so the delta is visible.

```bash
php scripts/doc-placement.php --scope=<product-specific|cross-product|session-state> \
                              [--maturity=<research|qualified|adopted>] [--domain=<id>]
php scripts/doc-placement.php --list      # domains, roots, and rules
```

**Exit code 2 means PENDING** — the classification is real but its placement is unruled. **Record `PENDING` in the map. Do not guess a destination.**

## The map

| # | Current location | Scope | Steward | Maturity | Domain | Derived location | Delta | Notes |
|---|---|---|---|---|---|---|---|---|
| 1 | `docs/implementation/PKS_….md` | product-specific | *(role)* | *(status)* | `pks` | `docs/pks` | **move** | |
| 2 | `engineering/knowledge/methodology/Layer_Verification_Rule.md` | cross-product | Decision Authority | research | — | **PENDING** | **hold** | awaits the stewardship decision |
| 3 | `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md` | cross-product | Decision Authority | adopted | — | `engineering` | **none** | already conformant |

**Delta values:** `none` (already conformant) · `move` (derived location differs) · `hold` (PENDING — no destination yet) · `unclassifiable` (needs a decision before it can be classified).

## Required summary

| Metric | Count |
|---|---|
| Documents classified | |
| Already conformant (`none`) | |
| Requiring a move (`move`) | |
| Held pending a ruling (`hold`) | |
| Unclassifiable | |
| **Inbound references to moving files** | |

**The last row is the migration's real cost.** Baseline measured 2026-08-01: **501** inbound references to `docs/implementation/`, **119** of them to `PKS_*` files.

## Completeness

The map is complete when **every** document under `docs/` appears exactly once, including root-level files with no obvious domain. **A document that cannot be classified is a finding, not an omission** — record it as `unclassifiable` with the question it raises.

---

**Traceability:** the ADR (Phase 1, Step 1.2) · `docs/knowledge/schema/documentation-placement.yaml` (the registry) · `scripts/doc-placement.php` (the resolver) · **R-70** (classification model) · **R-71** (placement derived, never ad hoc).
