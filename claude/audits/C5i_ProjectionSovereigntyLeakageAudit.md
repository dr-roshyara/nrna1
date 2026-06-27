# C.5i — Projection Sovereignty Leakage Audit

**Status:** COMPLETE
**Date:** 2026-05-28
**Phase:** C.5 — Sovereignty Stabilization Barrier
**Protocol:** Read-only sovereign archaeology (zero production code changes)

---

## Audit Purpose

Verify UI/admin layer does not imply constitutional precedence.
Observations in the projection layer must remain a flat set — presentation
ordering must not become governance ordering.

**Invariant:**
```
UI ordering must NOT imply constitutional precedence.
Observations are a flat set.
Presentation ordering is not governance ordering.
```

**Forbidden in projection layer:**
```
"high risk", "critical", "urgent", "escalated"
ranked legitimacy indicators, red-first sovereignty grouping
sorted "criticality", top warnings implying authority
```

---

## Previously Remediated (H.2)

- `VoteDenied.vue` — `current_ip`, `original_ip`, `registered_ip` removed
- `helpers.php:validateVotingIpWithResponse()` — IP fields removed from all return paths
- Verified: 9 tests passing, zero IP references remain

---

## Findings

### P-1: TrustCenterBanner — Numeric Trust Score with Tier Labels

**File:** `resources/js/Components/Dashboard/TrustCenterBanner.vue`

**Violation:** Renders a numeric "trust score" percentage (default 95%) as a
progress bar with classified tier labels:

| Score Range | Tier Label |
|-------------|------------|
| >= 90 | "Excellent — Fully compliant with all standards" |
| >= 75 | "Good — High compliance level" |
| >= 60 | "Satisfactory — Core compliance met" |
| < 60 | "Needs improvement" |

**Constitutional violation:** This is a direct sovereignty score displayed
to end users. A numeric trust value with tier classification implies
constitutional precedence ranking. Users see a percentage that suggests
"how legitimate" their activity is — this is scalar sovereignty exported
to the projection layer.

**Prop lineage:** `complianceData.trustScore` passed from backend controller
to Vue component. The value originates from trust signal aggregation in
`app/Services/Dashboard/TrustSignalService.php` and is not related to the
constitutional resolver path.

**Criticality:** HIGH — active scalar sovereignty in user-visible projection

**Remediation:** Deferred to D.0.3. Must be replaced with constitutional
outcome semantics (e.g., "Verified" / "Additional verification needed"
instead of numeric trust percentage). The tier labels and progress bar
are procedural sovereignty mechanics presented as constitutional authority.

---

### P-2: Dashboard confidence_score Gating UI Visibility

**Files:**
- `resources/js/Pages/Dashboard/Welcome.vue` (lines 354-366)
- `resources/js/composables/useDashboard.js` (lines 63-79)

**Violation:** A `confidence_score` value from `userState` determines which
UI blocks are visible:

```javascript
// Welcome.vue:358
shouldShowTips() {
  return this.userState?.confidence_score < 70;
}
```

The composable classifies users into tiers:
| Score Range | Tier | Effects |
|-------------|------|---------|
| >= 80 | "expert" | Advanced features shown |
| >= 60 | "intermediate" | Mid-level features |
| >= 40 | "beginner" | Basic features |
| < 40 | "new" | Onboarding content |

**Constitutional violation:** A backend-derived confidence score drives
content visibility tiers in the UI. This is scalar authority in the
projection layer — the frontend uses a procedural legitimacy score to
determine what capabilities to reveal.

**Prop lineage:** `DashboardController.php:107` passes `confidence_score`
from `UserStateBuilder`. The score is an onboarding/engagement metric,
not a constitutional outcome. But the projection layer treats it as an
authority signal for UI gating.

**Criticality:** HIGH — scalar-driven UI tiering in the projection layer

**Remediation:** Deferred to D.0.3. Replace confidence_score gating with
constitutional engagement state (e.g., `onboarding_complete: boolean`,
`capability_unlocked: string[]`).

---

### P-3: VerificationReport "Critical" Severity Badge

**File:** `resources/js/Pages/Result/VerificationReport.vue:32`

**Violation:**
```html
<span v-if="Math.abs(diff.official - diff.raw) > 2"
      class="status-badge error">Critical</span>
```

Renders a visible "Critical" severity badge in discrepancy analysis.

**Constitutional violation:** Scalar severity label ("Critical") displayed
as a badge in the UI. This is projection-layer severity ranking, not
constitutional outcome semantics.

**Criticality:** MEDIUM — discrepancy metadata, not legitimacy derivation

**Remediation:** Deferred to D.0.3. Replace "Critical" with factual
description (e.g., "Discrepancy detected — manual review required").

---

### P-4: VoteVerify "Critical Alert" Section

**File:** `resources/js/Pages/Vote/VoteVerify.vue` (lines 52-58)

**Violation:**
```vue
{{ $t('pages.vote_verify.critical_alert.title') }}
{{ $t('pages.vote_verify.critical_alert.description') }}
```

Renders a "Critical Alert" box during vote verification with translated
severity labels.

**Also affected:**
- `resources/js/Pages/Vote/Verify.vue` — "Critical Warning" section
- `resources/js/Pages/Vote/DemoVote/Verify.vue` — "Critical Warning" section

**Constitutional violation:** The word "critical" as a user-facing severity
classification implies constitutional concern level. This creates a
projection-layer sovereignty signal that users interpret as legitimacy
guidance.

**Criticality:** MEDIUM — UX warning, but uses scalar sovereignty vocabulary

**Remediation:** Deferred to D.0.3. Replace "Critical Alert" / "Critical
Warning" with factual descriptions (e.g., "Verification required —
please review your code").

---

### P-5: HTML Comments Using "critical" (Borderline)

**Files:** Multiple Vue templates

Developer-only HTML comments using "critical" as section labels. These
do not render to users but reflect a development mindset that associates
UI sections with severity ranking. Not a direct projection violation
but a documentation hygiene concern.

**Criticality:** LOW — developer comments only, no user impact

---

## Summary Table

| ID | File | Violation | Criticality | Source |
|----|------|-----------|-------------|--------|
| P-1 | `TrustCenterBanner.vue` | Numeric trust score + tier labels | HIGH | Backend trust signal |
| P-2 | `Welcome.vue` + `useDashboard.js` | confidence_score UI gating | HIGH | Backend UserState |
| P-3 | `VerificationReport.vue` | "Critical" severity badge | MEDIUM | Frontend computation |
| P-4 | `VoteVerify.vue`, `Verify.vue`, `DemoVote/Verify.vue` | "Critical" alert/warning labels | MEDIUM | Translation keys |
| P-5 | Multiple files | HTML comments with "critical" labels | LOW | Developer documentation |

---

## Constitutional Classification

The projection layer has **active scalar sovereignty** in two forms:

1. **Numeric scores driving UI behavior** (P-1, P-2): `trustScore`
   percentage and `confidence_score` thresholds determine what users
   see and how they experience the platform. This is procedural
   scoring exported to the frontend as implied legitimacy.

2. **Severity vocabulary** (P-3, P-4): "Critical" as a user-facing
   label implies constitutional concern ranking. This is scalar
   sovereignty vocabulary in the projection layer.

Both forms violate the Projection Ordering Neutrality doctrine:
```
Presentation ordering is not governance ordering.
```

---

## Phase D.0.3 Implications

All findings must be remediated before D.0.3e (final middleware retirement)
but do NOT block C.5 certification or D.0.3a (constitutional primary
enforcement), as they are projection-layer concerns, not enforcement
path concerns.

**Remediation strategy:**
1. Replace numeric scores with constitutional outcome labels
   (e.g., "Verified" / "Needs verification")
2. Replace severity vocabulary with factual descriptions
3. Replace score-based UI gating with state-based gating
   (e.g., `onboarding_complete`, `feature_unlocked`)
