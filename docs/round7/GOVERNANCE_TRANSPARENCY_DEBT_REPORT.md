# Round 7B: Governance Transparency Debt Report

**Date:** 2026-06-14  
**Purpose:** Quantify where constitutional explanations exist but are not surfaced to users

## Scoring Method

For each of the 15 constitutional actions, score 5 criteria:

| Criterion | Weight |
|-----------|--------|
| `denial_reason` available in backend? | 1 point |
| `denial_detail` available in backend? | 1 point |
| Transmitted to frontend via Inertia? | 1 point |
| Displayed to user in UI? | 2 points |
| Explanation is actionable (user knows what to do)? | 2 points |
| **Maximum** | **7 points** per action |

## Action-by-Action Score

| Action | Reason Avail? | Detail Avail? | Transmitted? | Displayed? | Actionable? | Score | % |
|--------|:------------:|:------------:|:------------:|:----------:|:----------:|:----:|:-:|
| `submit_for_approval` | ✅ 1 | ✅ 1 | ✅ 1 | ❌ 0 | ❌ 0 | **3/7** | 43% |
| `approve` | ✅ 1 | ✅ 1 | ✅ 1 | ❌ 0 | ❌ 0 | **3/7** | 43% |
| `reject` | ✅ 1 | ✅ 1 | ✅ 1 | ❌ 0 | ❌ 0 | **3/7** | 43% |
| `auto_submit` | ✅ 1 | ✅ 1 | ✅ 1 | ❌ 0 | ❌ 0 | **3/7** | 43% |
| `begin_setup` | ✅ 1 | ✅ 1 | ✅ 1 | ❌ 0 | ❌ 0 | **3/7** | 43% |
| `revise_and_resubmit` | ✅ 1 | ✅ 1 | ✅ 1 | ❌ 0 | ❌ 0 | **3/7** | 43% |
| `complete_administration` | ✅ 1 | ✅ 1 | ✅ 1 | ❌ 0 | ❌ 0 | **3/7** | 43% |
| `complete_nomination` | ✅ 1 | ✅ 1 | ✅ 1 | ❌ 0 | ❌ 0 | **3/7** | 43% |
| `apply_candidacy` | ✅ 1 | ✅ 1 | ✅ 1 | ❌ 0 | ❌ 0 | **3/7** | 43% |
| `open_voting` | ✅ 1 | ✅ 1 | ✅ 1 | ⚠️ 1 | ⚠️ 1 | **5/7** | 71% |
| `close_voting` | ✅ 1 | ✅ 1 | ✅ 1 | ⚠️ 1 | ⚠️ 1 | **5/7** | 71% |
| `publish_results` | ✅ 1 | ✅ 1 | ✅ 1 | ❌ 0 | ❌ 0 | **3/7** | 43% |
| `archive` | ✅ 1 | ✅ 1 | ✅ 1 | ❌ 0 | ❌ 0 | **3/7** | 43% |
| `suspend` | ✅ 1 | ✅ 1 | ✅ 1 | ❌ 0 | ❌ 0 | **3/7** | 43% |
| `resume` | ✅ 1 | ✅ 1 | ✅ 1 | ❌ 0 | ❌ 0 | **3/7** | 43% |

## Governance Transparency Score

| Metric | Value |
|--------|-------|
| Total possible points | 105 (15 actions × 7) |
| Actual points | 49 |
| **Transparency Score** | **46.7%** → **Significant Debt** |
| Fully transparent actions (score ≥ 6) | 0 of 15 |
| Partially transparent (score 4-5) | 2 of 15 |
| Mostly opaque (score ≤ 3) | 13 of 15 |

## Classification

| Range | Classification | Actions |
|-------|---------------|---------|
| 90-100% | Excellent | 0 |
| 70-89% | Good | 0 |
| 50-69% | Moderate Debt | 2 (`open_voting`, `close_voting`) |
| 0-49% | Significant Debt | **13** |

## Breakdown by Layer

| Layer | Max | Actual | % | Debt |
|-------|-----|--------|---|------|
| Backend produces denial_reason | 15 | 15 | 100% | None |
| Backend produces denial_detail | 15 | 15 | 100% | None |
| Transmitted to frontend | 15 | 15 | 100% | None |
| Displayed to user | 30 | 2 | 7% | **Significant** |
| Actionable explanation | 30 | 2 | 7% | **Significant** |

## Key Insight

The backend and contract layers score **100%** — the constitutional governance model fully supports explainability. The debt is concentrated entirely in the **presentation layer**, where 93% of governance explanations are discarded before reaching the user.

This is not a technical architecture problem. It is a UX implementation gap within a sound constitutional architecture.

## Conclusion

| Finding | Value |
|---------|-------|
| Governance Transparency Score | **46.7% — Significant Debt** |
| Backend/contract explainability readiness | 100% |
| Presentation-layer debt | 93% of explanations not displayed |
| Primary cause | `denialDetail()` not exposed by composable; conditionally rendered buttons lack explanation text |
