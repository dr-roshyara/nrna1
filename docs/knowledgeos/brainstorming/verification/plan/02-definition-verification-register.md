# 02 — Definition Verification Register (initial pass, stage V3)

15-question test (1556 §8) applied to the load-bearing definitions. Verdicts multi-dimensional; defect classes per the 10-category D-register. Full pass is OPEN pending V2 canonical meanings for COLLIDING terms.

| Definition | Defined? | Dom/Cod | Existence | Unique? | Well-formed | Computable | Instantiable | Defects | Verdict |
|---|---|---|---|---|---|---|---|---|---|
| Ω: W→O | abstract | yes | assumed frame | n/a | yes | oracle | yes (M₀) | none at abstraction level | **WELL-TYPED / DEFINED** |
| Identifiable(g,Ω) | yes | yes | — | n/a | yes | co-semi-decidable in general | yes | none | **DEFINED + PROVEN characterization** (P-02) |
| Zero(K,EC) | yes (vector form) | cod violated in use | yes (given ev) | yes given ev | yes | yes rel. oracles | witnessed | 𝒮_gap not closed (C-014); 9 signatures | **DEFINED with codomain defect** |
| EC / η | EC partial; η signature-only | η inputs insufficient | **η REFUTED as function** | no (versioning) | EC partial | procedure-conditional | worked example r₁..r₈ | missing construction; arity conflict with DeriveContract | **η UNDEFINED-AS-CLAIMED; EC PARTIAL** |
| Ladder + A6 | yes | yes | yes | yes | yes | yes | witnessed | dynamics absent (guards uninterpreted) | **DEFINED + PROVEN statics; dynamics OPEN** |
| AcceptancePolicy ρ_A | role yes; content language no | — | assumed | uniqueness NOT established (MV-F-7) | — | policy-relative | examples only | undefined predicates; genesis | **PARTIALLY DEFINED** |
| Evidence e | tuple variants | yes per variant | yes | token vs class identity split needed | yes | representable | yes | ~ undefined; residence [UNRESOLVED] | **DEFINED-with-G-CRITICAL dependency** |
| ~ , ≺ | **no** | structure forced (equiv/DAG) | instances unknown | — | — | unknown | no | the central missing objects | **UNDEFINED — BLOCKING** (evidence layer) |
| N (normalization) | property N1 only | no | necessary (T-K6a) but unconstructed | no | — | unknown | no | missing object | **UNDEFINED — BLOCKING** |
| A₁–A₄ operators | yes (formulas) | edge holes | yes | n/a | yes | yes | executed | domain holes (MV-F-18) | **DEFINED with declared-partiality defect** |
| δ (Q15-rev) | yes | yes (partial) | yes on valid histories | deterministic claimed | yes | yes per event type | examples | arity conflict in Q20; Zero-residence contradiction infects tuple | **DEFINED-but-damaged** (TV-F-002) |
| τ (204) | yes | total-typed vs guarded prose | — | — | Pre/Post untyped (TV-F-003) | — | examples | typing defects | **DEFINED with type defects** |
| Replay | yes (recursion) | yes | yes under KA4 | yes | yes | yes | witnessed-adjacent | oracle boundary (classes) | **DEFINED + CONDITIONALLY PROVEN** |
| DC(d) | components yes | 6- vs 7-tuple | — | — | no ratified conjunction (MV-F-5) | component-wise | witnessed | arity conflict; Q-loss | **PARTIALLY DEFINED** |
| ⪰_C | postulated | axioms absent | — | — | no | conflict-detection conditional | — | order axioms never stated | **UNDER-DEFINED** |
| K_t | 7 variants | — | — | — | — | — | — | no canon (TV-F-016) | **NOT ESTABLISHED**; abstract-interface PROPOSED |
| Invariant system | ~130 statements | schema over 12 undefined predicates | 𝒱≠∅ under interpretation | — | per-statement mostly | — | M₀/M₁ | ID collisions; liveness-free | **DEFINED-AS-SCHEMA** |
| Uncertainty objects | per-taxonomy | component domains undefined | — | — | — | — | — | 5 constructs | **UNDER-DEFINED** |
| P(H\|E,M) | model-relative | space never constructed | pockets only | — | given model | pockets | LR example | likelihood availability | **CONDITIONALLY DEFINED** |
| SNF metrics | formulas yes | estimands mostly missing | — | — | scale defects | mechanically | synthetic | AM register | **DEFINED-WITHOUT-MEASUREMENT-BASIS** (except CR) |

**Defect-class totals (initial):** missing definition 6 · ambiguous 4 · inconsistent/contradicted 3 · circular 0 confirmed (⭕-risks tracked separately) · non-computable-as-claimed 2 · undefined dom/cod 4 · notation collisions systemic (C-060) · unjustified assumption pervasive (see A2/E). **Full-pass order:** ~/≺/N → EC/η reframe → δ/τ typing → ρ_A predicates → the rest.
