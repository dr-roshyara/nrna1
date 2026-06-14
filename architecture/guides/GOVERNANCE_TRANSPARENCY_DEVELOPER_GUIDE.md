# Developer Guide: Governance Transparency & Capability UX

**Date:** 2026-06-14  
**Audience:** Frontend and full-stack developers  
**Purpose:** Prevent reintroduction of Governance Transparency Debt and Language Governance Debt

---

## 1. Background

Round 7 of the architecture discovery process identified two related debts:

**Governance Transparency Debt:** The backend produces explainable authorization decisions via `CapabilityDecision.detail`, but 93% of those explanations never reached the user. Of 15 constitutional actions, only 2 (`open_voting`, `close_voting`) showed any denial explanation.

**Language Governance Debt:** The frontend `DENIAL_LABELS` map replaced backend-specific explanations (e.g., "Ballot code already consumed") with generic labels (e.g., "Unmet Requirements"). This constitutes a competing ubiquitous language — the frontend translated governance language without authorization.

The fix introduced `denialDetail()` to `useElectionCapabilities` and established a display precedence: `denialDetail() > denialLabel() > denialReason()`. This guide ensures those fixes are maintained.

---

## 2. Explanation Sources

| Source | Method | Example | When to Use |
|--------|--------|---------|-------------|
| **Backend policy** | `denialDetail(action)` | "Ballot code already consumed" | **Preferred** — carries the specific policy explanation |
| **Frontend label map** | `denialLabel(action)` | "Unmet Requirements" | **Fallback** — only when `denialDetail()` is null |
| **Raw reason enum** | `denialReason(action)` | `unmet_precondition` | **Last resort** — technical enum value, not user-facing |

### Precedence Rule

Always use the most specific source available:

```typescript
// ✅ Correct: prefer backend detail, fall back to label, fall back to reason
denialDetail(ElectionActions.X)
  ?? denialLabel(ElectionActions.X)
  ?? denialReason(ElectionActions.X)

// ❌ Wrong: skips backend detail
denialLabel(ElectionActions.X)

// ❌ Wrong: hardcodes English text
'Action unavailable'
```

---

## 3. Governance UX Rules

For any governance action button (submit, approve, open voting, publish results, suspend, etc.):

### Preferred (in order)

1. **Visible + Enabled** — the action is available, the user can perform it.
2. **Visible + Disabled + Explanation** — the action is unavailable, but the user sees why and what must happen next.

### Avoid

- **Hidden without explanation** — `v-if="canX"` with no fallback removes the action from the DOM entirely. The user cannot know the action exists, why it's blocked, or when it becomes available.

### Rationale

Governance transparency requires that citizens and election officers can answer five questions:

1. What action exists?
2. Why is it unavailable?
3. What must happen next?
4. Who is responsible?
5. When might it become available?

Hidden actions answer none of these. Disabled actions with `denialDetail()` answer questions 1-3.

---

## 4. Action Visibility Decision Matrix

| Situation | Recommended Pattern | Condition |
|-----------|-------------------|-----------|
| Action allowed | Enabled button | `canDo(action) === true` |
| Denied, detail available | Disabled button + `denialDetail()` below | `!canDo(action) && denialDetail(action)` |
| Denied, detail null | Disabled button + `denialLabel()` below | `!canDo(action) && !denialDetail(action) && denialLabel(action)` |
| Denied, only reason available | Disabled button + `denialReason()` below | `!canDo(action) && !denialDetail && !denialLabel && denialReason` |
| Admin-only action (not in this UI) | Not rendered | Separate scope, documented |
| Loading state | Disabled button with spinner, no explanation | `isLoading === true` |

---

## 5. Code Examples

### ✅ Good — disabled button with backend detail, fallback to label

```vue
<ActionButton
  :disabled="!canPublishResults"
  variant="success"
  size="md"
  @click="canPublishResults ? publishResults() : undefined"
>
  {{ t.sections.results.btn_publish }}
</ActionButton>
<p
  v-if="!canPublishResults && (denialDetail(ElectionActions.PUBLISH_RESULTS) ?? denialLabel(ElectionActions.PUBLISH_RESULTS))"
  class="mt-2 text-xs text-slate-400 font-medium"
>
  {{ denialDetail(ElectionActions.PUBLISH_RESULTS) ?? denialLabel(ElectionActions.PUBLISH_RESULTS) }}
</p>
```

### ✅ Good — handling null denial gracefully

```vue
const explanation = (action) => denialDetail(action) ?? denialLabel(action) ?? denialReason(action)
```

### ❌ Bad — silent hide (recreates Governance Transparency Debt)

```vue
<!-- ❌ User never sees this action or explanation -->
<ActionButton v-if="canPublishResults" variant="success" @click="publishResults">
  Publish Results
</ActionButton>
```

### ❌ Bad — label-only (degrades backend language)

```vue
<!-- ❌ Loses specific backend explanation -->
{{ denialLabel(ElectionActions.PUBLISH_RESULTS) }}
```

---

## 6. DDD Alignment

The governance language chain flows through four layers:

```
ElectionConstitution (Constitutional language)
  ↓
Capability Policies (Domain policy language)
  ↓
Capability Snapshot / CapabilityDecision (Application language)
  ↓
useElectionCapabilities / denialDetail() (Frontend — preserves language)
```

### Rules

- **Capability Policies** produce governance language. They are the authoritative source.
- **Capability Snapshots** transport governance language to the frontend without transformation.
- **Frontend should preserve** governance language — not replace it with generic labels.
- **Frontend should not create** a competing ubiquitous language. The `DENIAL_LABELS` map is a fallback for cases where the backend provides no detail, not a primary explanation source.

Reference: ARG-02 Language Governance (Architecture Baseline v1.0)

---

## 7. Review Checklist

Before merging any governance-related UI change, verify:

| Check | Criteria |
|-------|----------|
| Action visible? | Is the action button rendered (even if disabled)? |
| Detail surfaced? | Is `denialDetail()` called and rendered? |
| Precedence correct? | Is the precedence `denialDetail() ?? denialLabel() ?? denialReason()` used? |
| Fallback present? | Is there a fallback when `denialDetail` is null? |
| Language preserved? | Is the backend explanation shown, not just a generic label? |
| Explanation actionable? | Does the text tell the user what must happen next? |
| No silent `v-if`? | Is there no `v-if="canX"` on governance action buttons without explanation? |
| i18n considered? | Is the text wrapped in `$t()` or is it from backend `denial_detail`? |

### Pre-submit

```bash
# Verify capability consumption pattern
grep -rn "v-if=\"can" resources/js/Pages/Election/Management.vue | grep -v "denialDetail\|denialLabel"
```

---

## 8. Future Work

The current implementation improves transparency while preserving architecture stability. Longer-term, ADR-006 (Governance Language Ownership & Flow) may introduce:

- Formal governance explanation policies
- Citizen-language explanation generation
- Dedicated governance language service

Until then, this guide represents the agreed standard.

**Key principle:** The constitutional governance model produces explainable decisions. Our job is to preserve that explainability through to the user interface.
