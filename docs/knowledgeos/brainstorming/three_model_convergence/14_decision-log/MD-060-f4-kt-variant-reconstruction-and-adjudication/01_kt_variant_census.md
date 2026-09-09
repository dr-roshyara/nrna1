# MD-060 §01 — `K_t` Variant Census and Provenance Ledger (Phase A)

Ten-field ledger per variant, primary-sourced (files opened directly, not the register alone).
Internal-document variants (same file, distinct formula) are listed as separate rows, not folded —
matching this reconstruction's own standing discipline of never silently merging.

| ID | Source (exact) | Date | Notation | Components | Types stated | Component semantics | Deps | Claims identity w/ another? | Demonstrated or asserted? | Epistemic status |
|---|---|---|---|---|---|---|---|---|---|---|
| **V1** | M0001, `20260901-145900_model.md` | 2026-09-01 | `K_t={cr_t(P):P∈ℒ}` / `K_t=`ProbDist over `ℒ` | a credence function over a proposition language `ℒ` | codomain `[0,1]`, domain `ℒ` unspecified in detail | "credence" = degree of belief, undefined further | `ℒ` (undefined extent) | No | — | `[HP]` |
| **V2a** | M0006, `20260901-195200_reconstruction-from-available-material.md` | 2026-09-01 | `K_t=KnowledgeState(O,S,E,C,G,t)` | 6-argument function (Observation/State/Evidence/Context/Goal, +t) | untyped | none stated beyond names | — | implicitly with V2b (same file) | **ASSERTED only** — no proof or explicit statement of correspondence, they simply co-occur | `[HP]` |
| **V2b** | M0006, same file, later section | 2026-09-01 | `K_t=(k_{1,t},...,k_{n,t})` | indexed n-tuple, arity unstated | untyped | none stated | — | see V2a | ASSERTED only | `[HP]` |
| **V3** | M0009, `20260901-201400_not-algebraic-geometry-or-topology-first-they-attack-the-wrong-problem.md` | 2026-09-01 | `K_t={(s_1,p_1),...,(s_n,p_n)}`, `p_i=P(s_i\|E_t)` | set of (sentence,probability) pairs | `p_i∈[0,1]`, `s_i` sentence-typed | `p_i` = conditional probability of `s_i` given evidence `E_t` | `E_t` | No | — | `[HP]` — the source's own text immediately raises "serious mathematical questions" about this formulation, unresolved in the file |
| **V4a** | M0043, `20260902-004631_...definitions-axioms-theorems-corollaries.md`, `[DEF-11]` | 2026-09-02 | `K_t∈𝕂` | deliberately abstract — no tuple fixed | `𝕂` = "space of admissible semantic epistemic states" | none — abstraction is deliberate | — | explicitly declines to identify with any specific tuple ("resolves the earlier conflict between multiple candidate tuples") | — | `[DF]`, the most disciplined stance in the census |
| **V4b** | M0043, same file, "§14 Knowledge state components" | 2026-09-02 | `K_t=(Content_t,Support_t,Uncertainty_t,Model_t,Alternatives_t,History_t,Identity_t,Context_t,Inquiry_t,Status_t)` | 10-component | untyped | names only, no formal definitions | — | explicitly labeled "a semantic decomposition, not a mandatory storage tuple" — i.e. NOT claimed identical to V4a's own abstraction, deliberately | — | `[HP]`, explicitly non-mandatory |
| **V5** | M0048, `20260902-085420_definitions-as-formal-specification-one-fundamental-inconsistency.md` | 2026-09-02 | `K_t=(E_t,ρ_t,α_t,π_t,τ_t)`, also `K_t∈EpiState` | 5-component (content/relations/attributions/provenance/temporal) | untyped | names given, one line each | — | "not necessarily the final implementation tuple" — same self-limiting disclaimer as V4b, no cross-reference to it | — | `[HP]` |
| **V6a** | M0076, `20260902-122625_knowledge-is-not-one-pole-a-two-pole-structure-for-kt.md` | 2026-09-02 | `K_t=(Claims_t,Evidence_t,Arguments_t,Standing_t)` | 4-component | untyped | names only | — | superseded within the same file by V6b ("I would go one level deeper") | ASSERTED (author's own stated refinement, not proven) | `[HP]` |
| **V6b** | M0076, same file, immediately following | 2026-09-02 | `K_t=(C_t,E_t,A_t,H_t,S_t,R_t)` | 6-component | untyped | names only | — | see V6a | ASSERTED | `[HP]` |
| **V6-meta** | M0076, same file, later section | 2026-09-02 | `K_t = ` "current knowledge representation extracted/attributed from `E_t`" (`E_t` = "whole argumentative/epistemic field") | — | — | `K_t` explicitly framed as a **projection/extraction** from a richer object `E_t`, not primary | `E_t` | genuinely new structural claim, not present in any other variant | — | `[HP]` — flagged separately, a meta-level distinction (is `K_t` primary or derived?) that applies across the whole family, unaddressed elsewhere |
| **V7** | M0125/M0126 (near-duplicates, register §G/UE-2), `20260902-175304_...knowledge-transfer-for-next-session.md` | 2026-09-02 | `K_t=(A_t,R_t,E_t,Σ_t,H_t,Z_t,L_t,T_t,G_t,C_t,M_t)` | 11-component | one component (`Σ_t`) typed: `Σ=(A,S,R,V,C)`, each enumerated | `Σ_t` fully typed (Acquisition/Support/Resolution/Validity/Conflict, enumerated domains); other ten named only | — | No, but shares `Δ_t`/`Sat` apparatus with V4a (M0132 ratifies both together) | — | `[CG]` (register), `[DF]` for `Σ_t` specifically |
| **V8** | M0287, `20260904-154541_from-k-as-dimension-tuple-to-k-as-dimensions-and-relations.md` | 2026-09-04 | `K=(D,R)` | dimensions + relations — **structurally distinct type** (graph-shaped, not a flat tuple) | `D` = dimension set, `R` = relation set over `D`, both otherwise untyped | `R(d_i,d_j\|Q)` — relations parameterized by a query/context `Q` | `Q` | Explicitly proposed as a *refinement* of the flat-tuple family in general (`K=(d_1,...,d_n) → K=(D,R)`), not of any specific variant | ASSERTED, a stated directional refinement, no formal proof | `[HP]` |

## Provenance summary

**12 distinct primary formulations found** (V1–V8, counting same-document internal variants
separately), against the register's own "9+" — a **disclosed refinement**: the register's own count
folded V2a/V2b and V6a/V6b/V6-meta into single "M0006"/"M0076" entries; this census counts them
individually per this reconstruction's own standing discipline (never silently merge). V8 (M0287) is
one of the register's own flagged "at least two further variants" in the M0283–M0338 tail; **a
second tail variant was not located this phase** — disclosed as a scope limit, not chased further
(56 files in that range, only two — M0287 and a formal-ontology series, M0322/M0323/M0325 — checked
directly for `K_t=` definitions; the formal-ontology series (`theory-part-01` through `-04`, part of
the already-known, separately-tracked "23-part theory rewrite," `EKS-22`) supplied no distinct `K_t`
tuple in the sections checked, beyond a single, isolated `K_t=Reality` line in M0322 — not developed
further there and not counted as a distinct structural variant here).

**No two variants share a cross-reference or an explicit correspondence claim**, except the two
internal same-document pairs (V2a/V2b, V6a/V6b), which are asserted-adjacent but never formally
equated even within their own file.
