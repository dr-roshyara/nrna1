# 10 — DDD Semantic Verification Plan (stage V14)

**Direction locked:** `Mathematical distinction → semantic distinction → domain concept → bounded context → domain rule → architectural constraint` — never reversed. Every DDD statement classified: MATHEMATICAL CONSEQUENCE / DOMAIN MODEL CONSEQUENCE / ARCHITECTURAL DESIGN CHOICE / GOVERNANCE-POLICY CHOICE / IMPLEMENTATION CHOICE.

## Prerequisites (why this stage waits)
UL canonical meanings for the COLLIDING terms (plan 01 — especially Assessment/Assertion) · fresh-ID invariant catalogue with layers (plan 08 steps 1–2, because the corpus's own boundary-derivation method [162] derives contexts *from invariants*) · the residence dispositions (evidence, Zero findings — they are aggregate-boundary decisions).

## Verification tasks
| # | Task | PASS criterion |
|---|---|---|
| DDD-1 | Apply 162's method (`Semantic Concept → Invariant → Consistency Requirement → Boundary`) to the KI catalogue; compare resulting boundary candidates with the corpus's candidate maps (v0.1 §6 six candidates; 162 §30; 202 §22 seven-owner model; 203 federation) | per-context verdict: DERIVED (method+KIs produce it) vs DESIGN CHOICE (corpus asserts it without derivation); disagreements recorded |
| DDD-2 | Aggregate adjudication (189's five vs 205's lists): apply 203 §34's own criterion (`Same Aggregate ⟺ shared transactional invariant requires atomicity`) to the KI catalogue per candidate | per-candidate verdict with the criterion's evaluation shown; Lineage and Determination disagreements resolved by criterion or marked UNRESOLVED |
| DDD-3 | Ubiquitous-language conformance: every context's language drawn from plan-01 STABLE terms only; COLLIDING terms may not name domain objects until resolved | zero colliding terms in the derived model |
| DDD-4 | Event/command catalogue verification: Q15-revised's 8 events/7 commands + 008's AssertionAccepted + 204 §28 identity against the derived contexts (which context owns which event) | ownership table; orphan events flagged |
| DDD-5 | Anti-Conway check: for each proposed context, name the *mathematical or semantic distinction* that requires it; contexts justified only by module structure → DESIGN CHOICE | classification per context |
| DDD-6 | Reverse test: does the DDD model preserve theory semantics (no aggregate enforcing an invariant it cannot atomically guard — 203's contractual/eventual consistency kinds applied) | per-invariant enforcement-locus table |

**Output:** DDD verification record per concept + updated A8 columns (D and MD/DA cells). **The DDD model is a Track-B artifact; anything constructed here is PROPOSED RECONSTRUCTION until governance adopts it.**
