# Round 7B: Evidence Validation Report

**Date:** 2026-06-14  
**Purpose:** Challenge all Round 7 findings before baseline promotion

## Part 1 — Evidence Quality Classification

### Part 2 — Challenge the Transparency Score

**Critical finding:** The score model weights UI display equally to backend availability, but does not distinguish constitutional criticality. An "approve" action (governance decision) is weighted the same as "archive" (operational closure). The score of 46.7% is directionally correct but the methodology needs refinement.

**Recommended alternative:** Weight actions by constitutional criticality: governance actions (approve, reject, open_voting, publish_results) at 2×, operational actions (archive, resume) at 1×.

### Part 3 — Backend Explainability Readiness (Challenging "100%")

| Finding | Status | Evidence |
|---------|--------|----------|
| `denial_reason` always present | ✅ **100%** | Every prohibited/shortCircuit call includes a `CapabilityDenialReason` enum value |
| `denial_detail` always present | ❌ **NOT 100%** | `OverlayCapabilityPolicy::evaluate()` calls `shortCircuit(CapabilityDenialReason::Suspended)` with **no detail string** — the `$detail` parameter defaults to `null` |
| `denial_detail` is meaningful | ⚠️ **~70% meaningful** | Some are specific ("Ballot code already consumed"), some are technical ("Action not allowed in state voting_active"), some are generic ("Trust evaluation failed") |
| `denial_detail` uses constitutional language | ⚠️ **Partially** | Technical terms like `invalid_lifecycle`, `unmet_precondition` leak through. Not all terms would be meaningful to election officers. |
| `denial_detail` is deterministic | ✅ **Yes** | All detail strings are static or derived from fixed data — no randomness, no temporal dependency |

**Revised backend readiness score: ~85-90%** (not 100%). The OverlayPolicy gap means any suspension-related denial has a null detail.

### Part 4 — Frontend Explainability Consumption

Confirmed: 13 of 15 actions receive no denial explanation in the UI. The 2 that do (`open_voting`, `close_voting`) use the generic `denialLabel()` map rather than the specific `denial_detail` from the backend.

### Part 5 — Governance UX Assessment

| Action | Discoverability | Explainability | Recoverability | Score |
|--------|:-:|:-:|:-:|:-:|
| `submit_for_approval` | Poor | None | None | ❌ |
| `open_voting` | Good | Partial | Partial | ⚠️ |
| `close_voting` | Good | Partial | Partial | ⚠️ |
| All others | Poor | None | None | ❌ |

### Part 6 — DDD Review: Is this really presentation-layer debt?

| Hypothesis | Evidence | Verdict |
|-----------|----------|---------|
| **A: Presentation-layer debt** | 93% of explanations not displayed. Data arrives at frontend correctly. Composables just need exposure. | ✅ **Primary cause** — the data exists, the UI doesn't use it |
| **B: Application-layer responsibility leak** | `useElectionCapabilities` doesn't expose `denialDetail()`. The composable could add it. | ⚠️ **Contributing factor** — missing composable API |
| **C: Missing domain policy explanation service** | Backend already produces explanations in `CapabilityDecision.detail` | ❌ **Not relevant** — domain already provides |
| **D: Missing anti-corruption layer** | The `DENIAL_LABELS` map in `useElectionCapabilities` replaces backend detail with generic labels | ✅ **Contributing factor** — the map overwrites specific backend explanations |
| **E: Missing governance explanation bounded context** | Would be disproportionate for a supporting subdomain | ❌ **Rejected** — over-engineering |

**Verdict: Primarily presentation-layer debt (A), with two contributing application-layer factors (B, D).**

### Part 7 — Architectural Consequences

| Debt | Architectural | Governance | Citizen Trust | Priority |
|------|:------------:|:----------:|:------------:|:--------:|
| GT-01: `denialDetail()` not exposed | Low | Medium | Medium | P1 |
| GT-02: 13/15 actions opaque | Medium | **High** | **High** | **P1** |
| GT-03: Generic labels override specific detail | Low | Medium | Medium | P2 |

### Part 8 — Baseline Promotion Recommendations

| Finding | Current Status | Recommendation |
|---------|--------------|----------------|
| Backend explainability "100%" | Claimed | ⬇️ **Downgrade to ~85-90%** — OverlayPolicy sets null detail |
| Presentation-layer debt only | Claimed | ✅ **Accept with amendment** — primarily presentation + minor composable gap |
| Transparency Score 46.7% | Accepted | ⚠️ **Refine weighting** before baseline promotion |
| Governance Transparency Debt | Accepted | ✅ **Ready for baseline** — evidence quality is adequate |

## Conclusions for Baseline Promotion

| Finding | Confidence | Promote to Baseline? |
|---------|-----------|---------------------|
| Architecture Conformance | HIGH | ✅ Yes — 5/5 baseline claims verified structurally |
| Language Drift (PHP enum) | HIGH | ✅ Yes — usage traced, drift confirmed, P2 |
| Frontend Boundary | HIGH | ✅ Yes — all layers classified correctly |
| UX Governance Debt | HIGH | ✅ Yes — 13/15 actions opaque, evidence solid |
| Backend 100% readiness | **REJECTED** | ❌ **No** — corrected to ~85-90%. OverlayPolicy gap |
| Transparency Score (46.7%) | MEDIUM | ⚠️ **Conditional** — accept as directional, refine weighting later |

**One finding must be corrected before baseline:** The "backend 100%" claim has been challenged and found to have a null-detail gap in the OverlayCapabilityPolicy. The actual backend readiness is ~85-90%.
