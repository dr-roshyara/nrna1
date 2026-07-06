---
name: Phase 2 Architectural Discipline
description: Critical lesson on VO composition, validation delegation, and boundary stability in constitutional governance modeling
type: feedback
originSessionId: 0b8d9422-360e-4388-83e6-9972ffe33c53
---
## Rule: Reuse Phase 1 VOs — Eliminate Primitive Obsession

**Why:** Early Phase 2 draft used raw arrays/strings for authorityPath and adjudicatedBy. This violated:
1. Domain purity (primitive obsession)
2. DRY principle (validation logic duplicated in two places)
3. Type safety across contexts

**How to apply:** When designing Phase 2+ VOs, check Phase 1 first:
- Does a VO already model this concept? Use it.
- Never duplicate validation logic between layers.
- Compose from existing VOs rather than creating new primitives.

Example: DecisionTrace should use `AuthorityPath` VO (not `array`) and `MemberId` VO (not `string`).

---

## Rule: Avoid Semantic Inflation of VOs

**Why:** VOs can accidentally absorb system structure instead of expressing meaning.
- ConstitutionalBasis started to include: article + document + version + effective dates
- This turned a "citation reference" into a "mini-document model"
- Boundary collapse: VO → mini-aggregate

**How to apply:** When designing a VO, ask:
- Does this express one semantic unit?
- Or does it model system structure (lifecycle, versioning, temporal validity)?
- If the latter, those are separate entities/VOs.

Correct split:
- ConstitutionalBasis = citation only (article + optional reference)
- ConstitutionalDocument = entity (handles version, effective dates)

---

## Rule: Validation Belongs in the VO That Owns the Concept

**Why:** DecisionTrace initially validated authorityPath and adjudicatedBy directly.
But these concepts don't belong to DecisionTrace — they belong to AuthorityPath and MemberId.

**How to apply:** When a DecisionTrace receives a VO parameter:
- The VO has already validated itself in its factory method
- DecisionTrace only validates its own concerns (e.g., evaluatedRules non-empty)
- Never re-validate a VO's properties inside the consumer

Result: Cleaner tests, single source of truth for validation.

---

## Forensic vs. Behavioral Layers

**ConstitutionalBasis** (forensic layer):
- Records "which rule was cited"
- Zero decision logic
- Pure reference object

**DecisionTrace** (forensic layer):
- Records "what was evaluated, what matched, who decided, when"
- Zero rule execution
- Pure audit record

These are different from:
- GovernanceDecision (immutable record of a decision)
- Policies (interpret facts, not record them)

**Warning:** Phase 2 temptation = "we have all the audit data, let's compute from it"
**Resistance:** Audit ≠ logic. Record the truth, don't derive governance from metadata.
