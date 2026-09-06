# C — Type System (§25)

26 types declared **before** execution. Full table in `results/audits.json → type_table`.

| Type | Inputs | Carrier | Forbidden substitutions |
|---|---|---|---|
| RealityState | — | world | Observation |
| Observation | RealityState × Channel | percept | RealityState, Evidence |
| Information | Observation | signal content | Evidence |
| Evidence | Observation × Policy | admitted datum | Observation, Interpretation |
| Interpretation | Evidence × Context | semantic content | Evidence, Hypothesis |
| Claim | Interpretation | asserted content | Knowledge |
| Hypothesis | Claim × HypothesisSpace | candidate | Interpretation, Determination |
| HypothesisSpace | Inquiry | `H_Q` | Determination |
| EvidenceAssessment | Evidence × Hypothesis × `H_Q` × Model × Standard × Context | weight/verdict | Determination |
| EpistemicStandard | — | `S^epi` | Authorization, Policy, RealityState |
| Model | — | (Struct, Assump, Param) | RealityState |
| Determination | EvidenceAssessment × HypothesisSpace | `A_t ⊆ H_Q` | Knowledge, Decision |
| KnowledgeAttribution | Determination × EC | `Knows(a,p,c,t)` | Determination, EpistemicState |
| EpistemicState | * | `E_t` | KnowledgeState |
| KnowledgeState | EpistemicState × Q × C × EC | `K_t = Γ(…)` | EpistemicState, IdealState |
| Inquiry | — | `Q=(Target,Purpose,Context,Req,Constraints)` | Decision |
| IdealState | Q × C × S × EC | `I_t` | KnowledgeState, RealityState |
| Requirement | Q × EC | `r` | Gap |
| Gap | KnowledgeState × Requirement | `Δ_t` | Zero, Innovation |
| Zero | Gap | predicate | Gap, probability 0, certainty |
| Proposal | KnowledgeState × Gap | proposed act | Decision |
| Decision | Proposal | chosen act | Determination, Authorization |
| Authorization | Decision × Governance | permission | Decision, Action, EpistemicStandard |
| Action | Authorization | world effect | Authorization |
| History | * | append-only log | KnowledgeState |
| Identity | — | stable id | state equality |

## Notation collisions — 10 found, none silently repaired `[NEG]`

| Symbol | Distinct meanings in the theory |
|---|---|
| **`P`** | Proposition (DEF-5) · Probability (DEF-18) · Preservation vector `𝒫` (§66) — **worst, 3-way** |
| **`K`** | KnowledgeState `K_t` (DEF-11) · Kernel `𝒦` (DEF-32) · Knowledge Space `𝕂` |
| **`E`** | Evidence (DEF-7) · EpistemicState `E_t` (v1.1) · Expectation |
| **`H`** | Hypothesis · History (DEF-25) · HypothesisSpace `H_Q` |
| **`I`** | IdealState `I_t` · Invariants `I1..I9` · Information |
| `S` | EpistemicStandard `S^epi` · Support axis of `Σ` · SemDomain |
| `C` | Context · Conflict axis of `Σ` · semantic core `𝒞` |
| `R` | Requirement · Resolution axis of `Σ` · Representation space |
| `M` | Model `M_t` · Measure · Mapping |
| `Q` | Inquiry `Q` · Quotient |

`E` is the most dangerous: **the v1.1 correction reuses `E` for the epistemic state while `DEF-7`
already uses it for Evidence** — and the correction's whole point is that those two must not be
confused. `[OPEN]` The theory should rename one of them; this experiment did not, in order to avoid
silently changing the theory (§25).

No **type collision** was found: no two types share a carrier with interchangeable substitution.
