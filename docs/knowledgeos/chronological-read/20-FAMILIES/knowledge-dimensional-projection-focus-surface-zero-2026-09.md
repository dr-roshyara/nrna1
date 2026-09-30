# knowledge-dimensional-projection-focus-surface-zero-2026-09

**Scope(s):** THEORY-LEVEL · **Row count:** 20 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `0_i = no operative influence attributed to d_i, not absence of d_i`, `Focus_S(K)`, `K=(d_1,...,d_n)`, `P_S(K)=restrict(K,S)`, `Surface(K)=Obs_{1..n}(K)` · **Aliases:** `Focus vs Surface`, `Knowledge Zero as dimensional zeroing`, `cross-dimensional interaction`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0065`, scope `THEORY-LEVEL`: Reframes 'Knowledge Zero' as the deliberate zeroing/neutralization of selected dimensions of a multidimensional knowledge state K=(d_1,...,d_n) to construct a Focus_S(K) (attention restricted to S) versus a Surface(K) (all dimensions jointly observed) observational frame, where d_i=0 denotes 'no operative influence attributed to dimension i under the current frame', explicitly not ontological absence, not an algebraic identity element, and not proof of future irrelevance. Documents cross-dimensional interaction (a dimension zero in isolation but non-zero jointly), the non-commutativity candidate R(P_S(K)) != P_S(R(K)) between focused reasoning and observational focus, and repeatedly strips premature scalar 'determination_score' fields from the proposed KR-STATE-01 Axis C data schema in favor of raw typed observation records.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2701 §"Focus_S(K)=Projection_S(K) ... Surface(K)=Obs_{\{1,\ldots,n\}}(K)"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S2701 §"d_1=0 when observed alone, but (d_1,d_2)\neq(0,d_2) under a relational observation. Then d_1 has no standalone contribution but has a relational contribution."]
- CANDIDATE-FORMAL-BIRTH: [S2701 §"P_S(K) = (d_i')_{i=1}^n where d_i'=d_i if i in S, 0_{D_i} if i not in S ... 0_{D_i} represents the identity/neutral element of dimension D_i"]
- CANDIDATE-OPERATIONAL-BIRTH: [S2710 §"Axis C -- Dimensional Zeroing and Observational Focus ... If adding one dimension changes determination only in combination with another dimension, we have evidence of cross-dimensional epistemic interaction."]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2710. Candidate lifecycle: **ACTIVE**. Evidence: no retraction/supersession/contradiction evidence recorded; the ACTIVE classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | PRESENT | S2701, S2708, S2710 |
| formal_definition | PRESENT | S2701, S2701 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2701, S2705, S2708, S2710, S2710 |
| examples | PRESENT | S2701 |
| warnings | PRESENT | S2701, S2708 |
| experiments | PRESENT | S2710 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2701] types=['DEFINITION'] scope=THEORY-LEVEL — "Defines Focus_S(K) as the projection retaining only dimensions in S (others zeroed/neutralized) and Surface(K) as the full joint observation across all dimensions, with 'surface does not mean shallow'." (anchor: "Focus_S(K)=Projection_S(K) ... Surface(K)=Obs_{\{1,\ldots,n\}}(K)")
- [S2701] types=['EXAMPLE', 'CONCEPT'] scope=OBJECT — "Identifies cross-dimensional interaction: a dimension zero in isolation but non-zero jointly, explicitly linked to KR-ZERO's higher-order eliminability finding." (anchor: "d_1=0 when observed alone, but (d_1,d_2)\neq(0,d_2) under a relational observation. Then d_1 has no standalone contribution but has a relational contribution.")
- [S2701] types=['FORMALIZATION', 'CORRECTION'] scope=OBJECT — "Formalizes the projection operator P_S with omitted dimensions set to 0_{D_i}; immediately corrected within the same file: this over-claims an algebraic identity, and P_S should instead be defined as restrict(K,S) with omitted dimensions 'not observed' rather than replaced by an algebraic zero, preserving 'Projection != Algebraic Zero' until proven otherwise." (anchor: "P_S(K) = (d_i')_{i=1}^n where d_i'=d_i if i in S, 0_{D_i} if i not in S ... 0_{D_i} represents the identity/neutral element of dimension D_i")
- [S2701] types=['FORMALIZATION', 'CORRECTION'] scope=OBJECT — "Proposes then retracts an additive Determination Coupling Differential (independent/synergistic/masking regimes): the additive/numeric assumption about Determine is premature, so the first experiment should use a qualitative Compare(...) classification instead of Delta-Det in R." (anchor: "\Delta Det = Det(Q,P_{S\cup\{j\}}K) - [Det(Q,P_SK)+Det(Q,P_{\{j\}}K)]")
- [S2701] types=['HYPOTHESIS', 'DISTINCTION'] scope=THEORY-LEVEL — "Poses the live question of whether reasoning operates on the projected state (focused reasoning, R(P_S(K))) or on the full state with only the view projected (observational focus, P_S(R(K))), proposing R.P_S =?= P_S.R as a foundational experiment." (anchor: "R(P_S(K))\neq P_S(R(K)) ... That non-commutativity could itself be a major Knowledge Algebra phenomenon.")
- [S2701] types=['HYPOTHESIS'] scope=THEORY-LEVEL — "Hypothesizes that a focused projection can sometimes yield better determination than the full surface view (deliberate neutralization reducing interference), connecting Zero, Focus and purification into one candidate pattern still requiring experimental confirmation." (anchor: "Determine(Q,Focus_S(K)) > Determine(Q,Surface(K)) ... More information \not\Rightarrow better epistemic determination.")
- [S2701] types=['WARNING', 'CORRECTION'] scope=METHODOLOGICAL — "Removes a scalar bounded 'determination_score' field from the proposed KR-STATE-01 Axis C schema, replacing it with a raw typed status enum (DETERMINED/UNDERDETERMINED/CONTRADICTED/UNKNOWN) to avoid assuming Determine is measurable, scalar and bounded." (anchor: "This field: "determination_score": {"type": "number", "minimum": 0.0, "maximum": 1.0} is premature.")
- [S2705] types=['DISTINCTION', 'CORRECTION'] scope=THEORY-LEVEL — "Separates Zero (a claim about influence) from Focus/projection (a choice about what to observe), stating 'Projection may instantiate a Zero assumption' but Projection != Zero, and warning that a projected P_{1}(K)=(d_1,0,0) view must not be read as proving d_2=d_3=0 in the underlying state." (anchor: "Zero and Focus are currently conflated ... Zero concerns influence: Influence_i(K|Q,C)=0. Focus concerns what we choose to make operative/observable: Focus_S(K).")
- [S2708] types=['DEFINITION'] scope=OBJECT — "Defines 0_i as 'no operative influence attributed to dimension i under the specified frame' rather than absence of the dimension, applied to a worked example K^{(1,3)}=(d_1,0,d_3,0)." (anchor: "d_i=0 \Longleftrightarrow the influence of dimension i is treated as neutralized under the current model. This is different from saying d_i=\varnothing or d_i does not exist.")
- [S2708] types=['DISTINCTION', 'WARNING'] scope=OBJECT — "Distinguishes assumed/observed/validated Zero: a projection P_S(K) creates only an assumed/constructed zero, and only agreement of Determine(Q,P_S(K)) with the full state gives evidence the zeroing was adequate." (anchor: "We must never silently convert assumed zero influence into proven zero influence. There should be a distinction: Zero^{assumed}_i versus Zero^{observed}_i versus potentially Zero^{validated}_i.")
- [S2708] types=['EXTENSION'] scope=THEORY-LEVEL — "Connects dimensional-Zero-as-modeling-assumption to the temporal re-basing thread: a dimension's influence status can transition 0->0->non-zero across states, giving the conceptual chain 'dimension exists -> influence neutralized -> focus created -> reasoning occurs -> knowledge rebases -> previously neutralized dimension may become operative'." (anchor: "0^t \rightarrow 0^{t+1} \rightarrow +^{t+2} because its influence becomes operative after the knowledge substrate changes.")
- [S2708] types=['CONSTRAINT'] scope=OBJECT — "States three non-implications that constrain any future formal definition of dimensional Zero: it must not entail non-existence, future irrelevance, or intrinsic irrelevance of the dimension." (anchor: "Zero_i \not\Rightarrow d_i=\varnothing ... Zero_i \not\Rightarrow FutureInfluence_i=0 ... Zero_i \not\Rightarrow IntrinsicIrrelevance_i")
- [S2709] types=['CORRECTION'] scope=OBJECT — "Corrects the claim that 0_{D_i} is an identity/neutral element: proposes P_S(K)=restrict(K,S) with omitted dimensions 'not observed' rather than replaced by an algebraic zero, preserving 'Projection != Algebraic Zero' pending a later derived structure." (anchor: "We have not established that every epistemic dimension has an algebraic identity ... zeroing a dimension is an observational operation, not necessarily an algebraic operation inside that dimension.")
- [S2709] types=['CORRECTION'] scope=OBJECT — "Retracts the additive Determination Differential formula in favor of a qualitative Int(Q;S,j|K)=Compare(...) classification (no interaction / candidate synergy / candidate interference / structural interaction) until a numeric determination scale and composition rule is independently established." (anchor: "Delta Det ... assumes that Determine produces a numeric quantity with an additive interpretation. We haven't established that.")
- [S2709] types=['HYPOTHESIS'] scope=THEORY-LEVEL — "Poses focused-reasoning versus observational-focus as a fundamental architectural question, proposing R.P_S =?= P_S.R as a candidate major Knowledge Algebra experiment." (anchor: "Does reasoning operate on the projected state, or does projection merely determine what is observed while reasoning still has access to the full state? ... R(P_S(K))\neq P_S(R(K))")
- [S2709] types=['HYPOTHESIS'] scope=THEORY-LEVEL — "States the candidate finding that focused observation can outperform full-surface observation for a given question, connecting Zero/Focus/purification into one candidate pattern." (anchor: "A focused projection may produce better determination for a particular question ... if the determination metric supports such comparison. ... More information \not\Rightarrow better epistemic determination.")
- [S2710] types=['DEFINITION'] scope=THEORY-LEVEL — "Restates the Focus/Surface definitions and the geometric picture of multiple partial-dimension views of the same underlying state K." (anchor: "Focus_S(K)=Projection_S(K) where S identifies the dimensions being observed. ... Surface(K)=Obs_{\{1,\ldots,n\}}(K)")
- [S2710] types=['RESTATEMENT'] scope=THEORY-LEVEL — "Restates 'Knowledge Zero' as a vector of per-dimension neutral states rather than a scalar zero, cautioning against fixing 1/0 polarity before the experiment specifies it." (anchor: "Knowledge Zero = (Z_1,Z_2,\ldots,Z_n) where each Z_i describes a zero/neutral state relative to one dimension and one observation contract.")
- [S2710] types=['DISTINCTION'] scope=THEORY-LEVEL — "Distinguishes the knowledge state from an observation view of it, so a change in focused view across time (S_t != S_{t+1}) need not signal contradiction in the underlying knowledge." (anchor: "K_t \neq Obs_S(K_t). ... F_t\neq F_{t+1} does not necessarily mean knowledge itself became contradictory. The observation frame changed.")
- [S2710] types=['EXPERIMENT'] scope=OBJECT — "Proposes a KR-STATE-01 Axis C protocol: construct controlled projections P_S(K) over dimension subsets S, measuring preserved/lost/newly-visible determination and cross-dimensional interaction." (anchor: "Axis C -- Dimensional Zeroing and Observational Focus ... If adding one dimension changes determination only in combination with another dimension, we have evidence of cross-dimensional epistemic interaction.")

## Notes for P3
(none beyond what is noted above)
