# ADR-005: Language Ownership & Governance

**Status:** Accepted  
**Date:** 2026-06-13  
**Context:** Language drift analysis (Round 4C) identified a gap between the authoritative language source and its derived representations.

## Discovery Evidence

The following language sources were compared across the full stack:

| Source | Action Count | Status |
|--------|-------------|--------|
| `ElectionConstitution::RULES` (PHP, Domain) | 14 + 1 (resume) | **Authoritative** |
| `ElectionAction` PHP enum | 10 | ⚠️ Missing 5 actions |
| `ElectionActions` frontend constant (TypeScript) | 14 | ✅ Matches Constitution |
| `StateMachineContract` frontend interface | 15 | ✅ Matches Constitution |
| `ElectionLifecycleState` PHP enum | 12 states | ✅ Matches frontend constants |

## Decision

### 1. `ElectionConstitution` is the language authority

All constitutional action names originate from `ElectionConstitution::RULES` keys. No other source may introduce new actions independently. This applies to:

- Backend PHP enums
- Backend constants
- Frontend TypeScript constants
- Frontend contract interfaces
- API documentation
- Test assertions

### 2. All other representations are derived

The PHP `ElectionAction` enum, the frontend `ElectionActions` constant, and the `StateMachineContract` type are **derived representations** of the authoritative Constitution. They must be kept synchronized.

### 3. Synchronization rule

If an action is added to `ElectionConstitution::RULES`, all derived representations must be updated within the same change set. Pull requests that introduce new Constitution actions without updating the PHP enum and frontend constants shall not be approved.

### 4. Corrections required

The following 5 actions are missing from the `ElectionAction` PHP enum and must be added:

- `begin_setup`
- `revise_and_resubmit`
- `complete_nomination`
- `apply_candidacy`
- `archive`

The enum should be expanded to include all 15 actions from the Constitution (14 + resume) and kept synchronized thereafter.

## Consequences

**Positive:**
- Prevents future language drift between backend and frontend
- Establishes clear authority chain for ubiquitous language
- Low-effort correction (5 enum cases)

**Negative:**
- Requires discipline in PR review to enforce synchronization
- Does not address whether enum is the best typed representation (future ADR could consider auto-generation)

## Related

- [ADR-004: Strategic Context Boundaries](ADR-004-Strategic-Context-Boundaries.md)
- Discovery: Language Drift Analysis (`discoveries/20260613-language-drift-analysis.md`)
