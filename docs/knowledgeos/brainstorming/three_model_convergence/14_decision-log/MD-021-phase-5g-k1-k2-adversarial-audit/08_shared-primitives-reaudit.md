# Phase 5G — Shared-Primitives Re-Audit (Entity, Proposition, Relation)

Re-applying the strict six-level ladder independently per primitive (per the authorization's §12: "do
not allow one primitive's verdict to determine another's").

| Primitive | Lexical | Conceptual | Functional | Structural | Formal | Identity | Final (re-audited) |
|---|---|---|---|---|---|---|---|
| Entity | shared | shared, different depth (top-level in $K_1$; nested 2 levels in $K_2$) | plausible, not tested | **PARTIAL** (depth mismatch) | not tested | not tested | **PARTIAL CORRESPONDENCE** — unchanged from Phase 5F |
| Proposition | shared | shared, one unpacking step (direct field of `Assertion`) | plausible | **strongest of the three** (direct field, no further nesting) | not tested | not tested | **STRUCTURAL CORRESPONDENCE** — unchanged from Phase 5F, confirmed as the closest of the three |
| Relation | shared | shared, but role-asymmetric (1-of-8 vs. 1-of-2) | plausible, weight-asymmetric | **PARTIAL** | not tested | not tested | **PARTIAL CORRESPONDENCE** — unchanged from Phase 5F |

## Adversarial check performed

Re-verified against raw source (D285-1 §2, D285-6 §3) that no primitive's verdict was contaminated by
another's — each row above traces to its own specific textual evidence, independently re-read this
phase, not inferred by analogy from a neighboring row. **No change to Phase 5F's own per-primitive
verdicts.** This is a case where the adversarial audit **confirms** rather than downgrades — disclosed
plainly, since a rigorous audit that only ever downgrades would itself be suspect.
