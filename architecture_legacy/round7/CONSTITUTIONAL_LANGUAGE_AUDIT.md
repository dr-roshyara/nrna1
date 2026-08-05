# Round 7C: Constitutional Language Audit

**Date:** 2026-06-14  
**Purpose:** Classify every denial explanation by language level — technical, domain, constitutional, or citizen

## Language Level Definitions

| Level | Description | Example |
|-------|-------------|---------|
| **Technical** | Developer terminology, implementation detail | `invalid_lifecycle`, `Action not defined in constitution` |
| **Domain** | Election vocabulary, understandable to officers | `Nomination phase must complete before voting` |
| **Constitutional** | Governance language, references rules | `The constitution requires the nomination phase to complete before voting may begin` |
| **Citizen** | Plain language, no jargon, actionable | `Voting is not yet open. The nomination period is still in progress. Check back after candidates are approved.` |

## Complete Denial Path Inventory

### Policy 1: LifecycleCapabilityBaselinePolicy (2 denial paths)

| # | Detail String | Level | Problem |
|---|-------------|-------|---------|
| L1 | `Action not defined in constitution` | **Technical** — references "constitution" as a code artifact | Developer-facing. An officer can't act on "action not defined." |
| L2 | `Action not allowed in state {state}` | **Technical** — raw state name leaks | `Action not allowed in state voting_active` — no explanation of why or what to do |

### Policy 2: OverlayCapabilityPolicy (1 denial path)

| # | Detail String | Level | Problem |
|---|-------------|-------|---------|
| O1 | **(null — no detail string provided)** | **Missing** | `shortCircuit(Suspended)` with no detail. User sees nothing. |

### Policy 3: TrustCapabilityPolicy (7 denial paths)

| # | Detail String | Level | Problem |
|---|-------------|-------|---------|
| T1 | `Constitutional review required before participation` | **Domain** — mentions constitutional review | Slightly formal but clear. Actionable: "seek review." |
| T2 | `Re-verification required before participation` | **Domain** — mentions re-verification | Actionable: "complete re-verification." |
| T3 | `$result->reason` (dynamic) | **Varies** — quality depends on what `TrustEvaluationResult` provides | Untraceable without inspecting the trust evaluator output. Unknown quality. |
| T4 | `Constitutional review required before participation: {reason}` | **Domain** — dynamic reason appended | Better than T1, includes context. |
| T5 | `Trust cannot be established at this time: {reason}` | **Domain** — explains trust failure | Actionable if reason is specific, generic if reason is vague. |
| T6 | `Trust evaluation failed` | **Technical** — no explanation what trust evaluation is | A citizen would not know what "trust evaluation" means. |
| T7 | `Constitutional review required` | **Domain** — better, shorter | Adequate but no actionable next step. |

### Policy 4: EvidenceCapabilityPolicy (5 denial paths)

| # | Detail String | Level | Problem |
|---|-------------|-------|---------|
| E1 | `$result->reason` (dynamic) | **Varies** | Same as T3 — quality depends on evaluator. |
| E2 | `Constitutional review required before participation: {reason}` | **Domain** | Same as T4. |
| E3 | `Trust cannot be established at this time: {reason}` | **Domain** | Same as T5. |
| E4 | `Trust evaluation failed` | **Technical** | Same as T6. |
| E5 | `Constitutional review required` | **Domain** | Same as T7. |

### Policy 5: BallotAuthorizationPolicy (4 denial paths)

| # | Detail String | Level | Problem |
|---|-------------|-------|---------|
| B1 | `Single code protocol requires one ballot code` | **Domain** — explains protocol requirement | Clear. Actionable: "provide one code." |
| B2 | `Ballot code already consumed` | **Domain** — clear state | Clear. Actionable: "code used, contact support." |
| B3 | `Dual code protocol requires both view and commit codes` | **Domain** — explains protocol | Clear. Actionable: "provide both codes." |
| B4 | `View step must be completed before commit in dual code protocol` | **Domain** — explains ordering | Clear. Actionable: "complete view step first." |

## Summary by Policy

| Policy | Denial Paths | Technical | Domain | Constitutional | Citizen | Missing |
|--------|:-----------:|:---------:|:-----:|:--------------:|:------:|:------:|
| LifecycleCapabilityBaseline | 2 | **2** (100%) | 0 | 0 | 0 | 0 |
| OverlayCapability | 1 | 0 | 0 | 0 | 0 | **1** |
| TrustCapability | 7 | 2 (29%) | 5 (71%) | 0 | 0 | 0 |
| EvidenceCapability | 5 | 2 (40%) | 3 (60%) | 0 | 0 | 0 |
| BallotAuthorization | 4 | 0 | **4** (100%) | 0 | 0 | 0 |
| **Total** | **19** | **6 (32%)** | **12 (63%)** | **0 (0%)** | **0 (0%)** | **1 (5%)** |

## Key Findings

| Finding | Evidence |
|---------|----------|
| **Constitutional language absent from denial messages (not absent from system)** | No denial path references constitutional rules or governance principles by name. Constitutional language exists in `ElectionConstitution::RULES`, capability names, and lifecycle definitions, but is not surfaced through denial-path messaging. The system has constitutional language — it is not exposed to citizens. |
| **Zero citizen-level explanations** | No denial tells a citizen what to do next in plain language. |
| **One null-detail gap** | OverlayCapabilityPolicy (suspension) produces no explanation at all. |
| **32% of explanations are technical** | Leaked implementation terms: `state`, `action not defined`, `trust evaluation`, `protocol`. |
| **Best-quality explanations** | BallotAuthorizationPolicy — all 4 are domain-level, specific, and actionable ("Ballot code already consumed"). |
| **Worst-quality explanations** | LifecycleCapabilityBaselinePolicy — both paths leak raw state/action names. |
| **DENIAL_LABELS map degrades domain language** | Regardless of backend quality, the frontend `DENIAL_LABELS` map replaces all detail with 7 generic labels. Even a detailed "Ballot code already consumed" becomes "Unmet Requirements." |

## Ubiquitous Language Assessment

| Language Source | Status |
|----------------|--------|
| `ElectionConstitution::RULES` (14 actions + resume) | ✅ Strong constitutional language |
| `CapabilityDenialReason` enum (7 reasons) | ⚠️ Domain-level — usable, but not citizen-facing |
| Backend `denial_detail` strings (19 paths) | ⚠️ Mixed — 32% technical, 63% domain, 5% null, 0% constitutional |
| Frontend `DENIAL_LABELS` map (7 labels) | ❌ **Degrades all explanations to technical level** — "Unmet Requirements" replaces everything |

## Conclusion

| Question | Answer |
|----------|--------|
| Are explanations constitutional? | **None (0%)** — all are domain-level or technical |
| Are explanations citizen-ready? | **None (0%)** — no plain-language explanations exist |
| Are explanations actionable? | **B1-B4, T2, T4, T5** — 7 of 19 give a user a clear next step |
| Is the ubiquitous language preserved to UX? | **No** — `DENIAL_LABELS` map degrades all backend language to generic labels |
| Backend readiness (corrected) | **~85%** — detail exists for most paths but quality varies. One null-gap in OverlayPolicy. |

**Recommendation:** Before baseline promotion, add `citizen_explanation` as a field alongside `denial_detail` for governance-critical actions, and remove the `DENIAL_LABELS` map in favor of surfacing the backend's actual explanation text.
