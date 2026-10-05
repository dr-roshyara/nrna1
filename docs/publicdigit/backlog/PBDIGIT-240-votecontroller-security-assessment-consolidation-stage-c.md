# PBDIGIT-240 — VoteController security assessment: consolidation with the Security Verdict Authority (deferred Stage C)

**Type:** Architecture decision (security authority contract) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** PBDIGIT-239 Stage C read-only analysis (`VoteController::store()` downstream use of the inline security block)
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):** PBDIGIT-239 planned Stage C as a behaviour-preserving migration; the analysis showed it is not one with the current port, so it is split out here. No other story covers the security-assessment contract.

| | |
|---|---|
| **Status** | 🟡 **DEFERRED — architecture decision required.** PO decision 2026-10-05: option (a). Not authorised for implementation. |
| **Current state (FACT [S])** | `VoteController::store()` keeps its **inline security assessment** (lines ≈1484–1518: registered-IP hash, tenant-scoped density, `TrustPolicyEvaluator::evaluate()`, `ConstitutionalLegitimacyDecision::decide()`) — it remains authoritative for the final cast. `VotingSecurityVerdictQuery` / `TrustLegitimacySecurityVerdict` (PBDIGIT-239 Stage A, `0578091f8`) is the **verdict** authority for `CodeController` (Stage B, `d90131361`). |
| **Why Stage C was deferred** | Downstream of the inline block, `store()` needs more than `SecurityVerdict`: the **four-valued `LegitimacyOutcome`** (distinct user messages for Denied / Deferred / Investigate at the D.0.3a gate, ≈1534–1568) and the **`TrustEvaluationEnvelope`** (audit log ≈1520–1526; warning log with trust state, reason, policy sequence; `trackSovereigntyDivergence()` at the gate, the lifecycle-denied path ≈1575–1583 and the membership-denied path ≈1598–1607; also on demo elections). Only the `forCast()` input (≈1679) is covered by `SecurityVerdict`. Replacing the block with `verdictFor()` would merge messages and lose telemetry — not behaviour-preserving. |
| **Equivalence protection meanwhile** | `tests/Feature/Voting/VotingSecurityVerdictAdapterTest.php` — its oracle is a line-verified copy of the inline path; it keeps the extracted verdict and the inline assembly equivalent. **Drift risk:** if the inline block in `store()` changes, the oracle must be updated in the same change. |
| **Decision required before any Stage C** | Should the security authority expose **only `SecurityVerdict`**, or a **richer security assessment** (verdict + `LegitimacyOutcome` + envelope, with `verdictFor()` derived from it)? If richer: decide architecturally **first** who owns `TrustEvaluationEnvelope`, `LegitimacyOutcome`, and the audit / telemetry / divergence records (security context vs Elections port vs controller). |
| **Rejected for now (PO 2026-10-05)** | (b) richer port without that ownership decision · (c) reducing `store()` to the verdict (behaviour change) · (d) extracting only the evidence assembly (premature deduplication) |
| **Observation preserved, not acted on** | The D.0.3a gate already refuses every non-`Allowed` outcome on the real path **before** `forCast()`, so `forCast()`'s `SecurityDenied` / `SecurityReviewRequired` branches are currently unreachable there. This is **not** a reason to delete those branches or collapse the assessment — any change is a separate reachability / lifecycle decision. |
| **Out of scope** | `forCast()` redesign, CAS, VE-01, IP-policy consolidation (2B / Q3), tenancy, whitelist defects, middleware |

**Traceability:** PBDIGIT-239 (blueprint `ca9629b99`, RED `430d28742`, Stage A `0578091f8`, Stage B `d90131361`) · ADR_20261005_0950 §3.0 (`VotingSecurityDecision` role), I3 · `ContextBoundaryFitnessTest` (passes after Stage A / B).
