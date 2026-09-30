---
source_track: TRACK-A-PHASE-MEASURE
derived_from: [Step 32, Step 60, Step 259, Step 277, step-292/04, theory-08, KR-CONTR-FDE writeup]
cross_track_dependency: none
---

# KSME-13A — Notation Collision Registry

Every symbol confirmed to have 2+ mutually-uncited formal meanings in the admissible corpus, per Symbol
Identity discipline (`glyph + context + source + timestamp + semantic role`).

| Glyph/name | Distinct identities | Cross-referenced? | Verdict |
|---|---|---|---|
| `⊕` | `⊕_Step32` (undefined "clean merge," §32.19); `⊕_Step279` (policy/authority rule-combination, "✅ RESOLVED" in its own document); `⊕_20260826arch` (`K_{t+1}=K_t⊕ΔK`, undefined update operator) | No — zero cross-references found in either direction across all three | 3 distinct, unrelated objects sharing a glyph |
| `Contr` | `Contr_step292` (named prerequisite for SSA-gating, "must be defined first," NO type signature or algorithm given); `Contr_theory08` (a value inside a structured-evaluation codomain, `Contr≠Satisfied`) | Only by analogy (`step-292/04`: "relocated into the transition," `merely-analogizes`, not `instantiates`) | 2 distinct objects, related only by the later author's own explicit analogy |
| `Conflict` (5-way, within Step 32 alone) | `Conflict_Step32algebra` (§32.76, tuple element, no signature ever given); `ConflictSet`/`ConflictRecord` (§32.19/67, Merge's contradiction-result type); `ConflictStatus_Step32state` (§32.35, state-vector field); `Conflicted` (§32.34/36, enum value/transition target); unnamed `A+¬A` (§32.33, prose epistemic state) | No — none cross-reference each other within Step 32 itself | 5 distinct symbols, isolated in separate sections |
| `Conflict(p)` vs `Conflict(p,t)` | `Conflict_Step60predicate` (§60.5, `Support⁺(p)>0∧Support⁻(p)>0`); `Conflict(p,t)` (§60.45-46, temporally-scoped, different arity) | No | 2 distinct arities, never related |
| `Authorize` | `Authorize_Step32` (`𝒟×𝒞⇀𝒳`, §32.1, epistemic-to-executable); `Authorize_Step259` (`Actor×Action×Policy→Decision`, §259, non-state-transforming); `Authorize_277.20`→`Authorize_277.25` (governance predicate, refined within the same document — **this pair IS one evolving identity**) | `277.20`→`277.25`: yes (same document, explicit refinement). All other pairs: no — targeted bidirectional searches found zero cross-references | 3 distinct identities overall (the 277 pair counts as one); no bridge to Step 32 or Step 259 |
| `Reason` (cardinality) | 8-value illustrative taxonomy (`20260902-175306` Appendix B.7); 10-value/21-condition measured result (`theory-08`/`170000`, `KR-CONTR-EVAL`) | Only via later citation, not a stated reconciliation; primary `KR-CONTR-EVAL` source itself not opened (out of scope, `docs/knowledgeos/research/` boundary) | Two dated results, not merged into one canonical list anywhere found — disclosed discrepancy, not resolved |
| `⪯` | Step 32 §32.20 (informal introduction); Step 60 §60.16 (candidate formal definition, explicitly self-labeled "a candidate," properties never verified) | Yes — a real predecessor→successor chain | Not a collision; a genuine unproven refinement |

## What this registry does not do

No collision is resolved by fiat. No symbol is merged across identities without an explicit source-stated
bridge. Every "distinct" verdict above is based on a targeted, disclosed, bidirectional search — not
absence of a search.
