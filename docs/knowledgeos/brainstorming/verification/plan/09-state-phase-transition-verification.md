# 09 — State / Phase / Transition Verification Plan (stage V12)

**Adjudicated already:** CANONICAL K_t NOT ESTABLISHED (TV-F-016) · transition family PARTIALLY OVERLAPPING with proposed Δ-unification (TV-F-009) · ladder schema complete, LTS under-specified (TV-F-019) · Zero-residence contradiction CONFIRMED (TV-F-002).

## 1. Disposition-dependent work (blocked until governance acts)
Zero residence → then re-run the K-interface membership question · evidence residence → aggregate boundary · federation-vs-product decision → then the Δ-mapping proofs (δ ↪ Δ, τ ↪ Δ, Update/ℛ = Δ|S_E restrictions — each an equivalence/refinement proof obligation, stated in TV-F-009).

## 2. Executable-now verification work
| Task | PASS criterion |
|---|---|
| Typed Pre/Post repair proof (from TV-F-003) | composition law CONDITIONALLY PROVEN under clause-set typing |
| δ-arity resolution analysis (C-026): determine whether `δ(S,e,P)` is definable as `δ(S,e)` with P read from the event label — check every Q20 usage | either an equivalence argument (P-in-label) or a genuine 3-arity requirement documented |
| Rollback semantics comparison: formalize the three variants (time-travel / new-state / compensation) as distinct operators; show which the invariants (history preservation, provenance) admit | time-travel variant expected CONTRADICTED by history-immutability KIs; formal proof |
| History-form analysis (C-011): prove the event-log form generates the snapshot form (`Replay` prefixes) but not conversely | one-way derivability theorem |
| Guard-oracle interface spec for the TV-F-019 schema | every guard typed as oracle with declared inputs |

## 3. The "Phase" question (new, from UL register)
"Phase" is **UNDEFINED corpus-wide** despite naming the primary folder. Tasks: (i) sweep for any definitional use (phase vs stage vs regime — "formalisms are regimes" is the kernel-cascade resolution; Step-203's spaces are *state* spaces, not phases); (ii) verdict expected: *phase = informal historical label for programme stages, carrying no theoretical content* — if confirmed, the final theory's "State/Phase/Time" part records the absence explicitly rather than inventing a phase concept.

## 4. Time model verification
ValidTime ≠ KnowledgeTime (189 family) · temporal validity intervals (Q13 𝒯_t; K4) · `Decision_t ⇍ Knowledge_{t'>t}` (anti-hindsight) — formalize as a two-timeline structure (event time, validity time) and check every temporal invariant types against it. PASS = all temporal KIs well-typed over the two-timeline model; failures documented.

## 5. Output
`state-transition verification record` per 1600 §22 template for: K (abstract interface), S-federation, δ/τ/Δ, Replay, Rollback×3, History, the ladder LTS, Ω_A/ω — each with the multi-dimensional verdict (math/computational/DDD).
