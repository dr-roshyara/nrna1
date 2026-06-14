# Discovery: PhaseService Assessment

**Date:** 2026-06-13  
**Status:** Complete  
**ADR Reference:** [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)  
**Context:** Investigation for Increment 2 — can Management.vue consume `ElectionPhaseService.phaseFor()`?

## Background

ADR-001 (Reuse Before Create) requires evaluating existing abstractions before introducing new ones. Management.vue's `phaseInfo` computed property duplicates lifecycle state mapping logic that may already exist in `ElectionPhaseService.phaseFor()`.

## 1. `phaseInfo` — Current Implementation

**Location:** Management.vue lines 1191-1206  
**Input:** `currentState` — string from `stateMachine.currentState ?? 'draft'`  
**Output:** `{ icon: string, status: string, badge: string, color: string }`

```typescript
// Maps 11 lifecycle states individually
DRAFT              → { icon: '📋', status: 'Set up election posts...',    badge: 'Draft Phase',         color: 'amber' }
SUBMITTED_FOR_...  → { icon: '⏳', status: 'Awaiting review...',         badge: 'Pending Approval',     color: 'amber' }
APPROVED           → { icon: '✅', status: 'Election approved...',       badge: 'Approved',             color: 'emerald' }
SETUP_ADMINIST...  → { icon: '⚙️', status: 'Configure posts...',        badge: 'Administration Setup', color: 'blue' }
SETUP_NOMINATION   → { icon: '👥', status: 'Democratic candidacy...',    badge: 'Nomination Setup',     color: 'indigo' }
READY_FOR_VOTING   → { icon: '📋', status: 'Setup complete...',         badge: 'Ready for Voting',     color: 'amber' }
VOTING_ACTIVE      → { icon: '🗳️', status: '✓ Voting is active...',     badge: 'Voting Active',        color: 'emerald' }
COUNTING           → { icon: '📊', status: 'Voting has closed...',      badge: 'Counting',             color: 'amber' }
RESULTS_PUBLISHED  → { icon: '✅', status: 'Election complete...',      badge: 'Results Published',    color: 'emerald' }
REJECTED           → { icon: '❌', status: 'Election was rejected...',  badge: 'Rejected',             color: 'amber' }
SUSPENDED          → { icon: '🚫', status: 'Election is suspended...',  badge: 'Suspended',            color: 'red' }
ARCHIVED           → { icon: '📦', status: 'Election is archived...',   badge: 'Archived',             color: 'gray' }
unknown            → { icon: '📋', status: 'Unknown state',             badge: state,                  color: 'slate' }
```

**Purpose:** Purely presentational — provides UI display data (emoji, user-facing text, color tokens, badge labels). No domain logic.

## 2. `phaseFor()` — Existing Domain Service

**Location:** `Domain/Election/ElectionPhaseService.ts` lines 321-396  
**Input:** `lifecycleStateOrString: string`  
**Output:** `{ phase: ElectionPhase | null, lifecycleState: string, isOverlay: boolean }`

```typescript
// Groups 11 lifecycle states into 5 UI phases + overlay detection:
DRAFT, SUBMITTED_FOR_APPROVAL, APPROVED, REJECTED, SETUP_ADMINISTRATION
  → { phase: 'administration', isOverlay: false }

SETUP_NOMINATION
  → { phase: 'nomination', isOverlay: false }

READY_FOR_VOTING, VOTING_ACTIVE
  → { phase: 'voting', isOverlay: false }

COUNTING
  → { phase: 'results_pending', isOverlay: false }

RESULTS_PUBLISHED
  → { phase: 'results', isOverlay: false }

SUSPENDED, ARCHIVED
  → { phase: null, isOverlay: true }
```

**Purpose:** CQRS projection — maps constitutional lifecycle state to UI phase grouping with overlay detection.

## 3. Direct Comparison

| Capability | `phaseInfo` | `phaseFor()` | Equivalent? |
|---|---|---|---|
| Input type | `currentState` (string) | `lifecycleStateOrString` (string) | ✅ Same |
| Output shape | `{ icon, status, badge, color }` | `{ phase, lifecycleState, isOverlay }` | ❌ **Different** |
| Granularity | 11 individual states | 5 groups + 2 overlays | ❌ Different |
| Emoji icon per state | ✅ Full set | ❌ Not provided | ❌ |
| User-facing status text | ✅ 11 display strings | ❌ Not provided | ❌ |
| Badge label per state | ✅ 11 label strings | ❌ Not provided | ❌ |
| Color coding per state | ✅ 7 color variants | ❌ Not provided | ❌ |
| Phase grouping | ❌ Not provided | ✅ Groups into 5 phases | ❌ |
| Overlay detection | ✅ Inline (line 76-106) | ✅ `isOverlay: boolean` | ⚠️ Different shape |
| Fallback for unknown | ✅ Returns slate/generic | ✅ Returns null phase | ⚠️ Different |

## 4. Duplicated, Missing, and Conflicting Rules

### Duplicated (same knowledge in two places)
- **State→phase mapping:** Both know that SUSPENDED is an overlay. Both know VOTING_ACTIVE is a voting state. But they express it differently (inline conditionals vs grouped enumeration).

### Missing in `phaseInfo`
- **Phase grouping awareness:** `phaseInfo` treats each state independently. It doesn't know that DRAFT and SUBMITTED_FOR_APPROVAL belong to the same "administration" phase group.
- **Overlay semantic:** SUSPENDED and ARCHIVED get icons and status text, but the template has a separate `<div>` for suspension. The overlay concept exists in the template (line 76) but is not derived from `phaseInfo`.

### Missing in `phaseFor()`
- **Display metadata:** No icons, status text, badge labels, or color mappings. These are presentation concerns and correctly absent from domain layer.

### Conflicts
- **None.** The two functions operate on different output shapes and serve different purposes. No contradictory mappings exist.

## 5. Migration Feasibility

| Approach | Feasibility | Lines affected | Risk |
|----------|------------|----------------|------|
| **Direct replacement** (use `phaseFor()` output where `phaseInfo` is used) | ❌ **Not feasible** — output shapes are incompatible | Would break all 7 template references | High |
| **Consume `phaseFor()` inside `phaseInfo`** to derive phase grouping + overlay flag, keeping display metadata | ✅ **Feasible** — additive, non-breaking | ~5 lines in `phaseInfo` | Low |
| **Migrate status text to i18n** (currently hardcoded English in `phaseInfo`) | ✅ Separable concern | ~60 lines across locale files | Medium |
| **No change** | ✅ Always feasible | 0 | None |

## 6. Recommended Next Step

**Do NOT extract or rewrite `phaseInfo`.**

Reason: `phaseInfo` is **presentation logic** (icons, colors, display text). It correctly belongs in the Vue page. `phaseFor()` is **domain projection**. They are not in conflict.

Unlike the `handleSubmitForApproval` case (where a genuine business rule was buried in an event handler), `phaseInfo` is doing what a Vue computed property should do: transform state into display data.

The existing `phaseInfo` is correct as-is. It does not duplicate domain logic. It consumes lifecycle state and produces UI metadata.

## 7. Architectural Conclusion

| Question | Answer |
|---|---|
| Does `phaseInfo` duplicate `phaseFor()`? | ❌ No. Different outputs, different purposes. |
| Is there a reuse opportunity? | ✅ Minor — `phaseFor()` could be consumed inside `phaseInfo` for phase grouping, but this adds no current value. |
| Should we refactor? | ❌ Not justified. `phaseInfo` is correct presentation logic. |
| What about the hardcoded status text? | Separable concern — not urgent, not an extraction candidate. |

**Verdict:** Move to a different Increment 2 candidate. The ADR-001 reuse opportunity here is minimal.

## Related

- [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)
- [Migration Plan: Increment 1](../migrations/20260613-election-management-increment-1.md)
- [Discovery: Election Management Analysis](20260613-election-management-analysis.md)
