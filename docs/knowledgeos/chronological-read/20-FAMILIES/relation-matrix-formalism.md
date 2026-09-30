# relation-matrix-formalism

**Scope(s):** THEORY-LEVEL · **Row count:** 17 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** R(A,B) subset of {create,reference,support,contradict,derive,authorize,transform,invalidate,observe,own} · **Aliases:** semantic graph G=(V,E)
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 202's formal ten-relation-type vocabulary and directed semantic graph over the frozen concept vocabulary, walking every major pairwise relation from Observation through Outcome and back, with the finding that Authority intersects the system only at the decision/action boundary.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1421] §"R(A,B)\subseteq \{create,reference,support,contradict,derive,authorize,transform,invalidate,observe,own\}. The crucial point is that not every relation is allowed."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1421] §"R(A,B)\subseteq \{create,reference,support,contradict,derive,authorize,transform,invalidate,observe,own\}. The crucial point is that not every relation is allowed."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1421. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1421 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1421 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1421 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1421 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
[S1421] (FORMALIZATION/ARGUMENT): Represents the architecture as a directed semantic graph G=(V,E) over the seventeen frozen concepts; the first candidate graph reveals that Authority does not sit upstream of knowledge -- it intersects the system specifically at the decision/action boundary, not throughout the epistemic pipeline.

[S1421] (RESTATEMENT/ANALYSIS): Lists ten distinctions established provisionally at the architectural level by the relation-matrix exercise and explicitly ties them to concrete downstream engineering consequences (aggregate boundaries, APIs, events, persistence, authorization, audit, AI integration, statistical reproducibility) rather than treating them as merely philosophical.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S1421] types=['DEFINITION', 'FORMALIZATION'] scope=OBJECT — "Defines a ten-member relation-type vocabulary (create, reference, support, contradict, derive, authorize, transform, invalidate, observe, own) as the closed set from which any permitted pairwise concept relationship R(A,B) is drawn -- the central point is that not every relation is allowed between every pair of concepts." (anchor: "R(A,B)\subseteq \{create,reference,support,contradict,derive,authorize,transform,invalidate,observe,own\}. The crucial point is that not every relation is allowed.")
- [S1421] types=['FORMALIZATION', 'ARGUMENT'] scope=THEORY-LEVEL — "Represents the architecture as a directed semantic graph G=(V,E) over the seventeen frozen concepts; the first candidate graph reveals that Authority does not sit upstream of knowledge -- it intersects the system specifically at the decision/action boundary, not throughout the epistemic pipeline." (anchor: "G=(V,E) where: V=\{Identity,State,...,KnowledgeArtifact\}. ... Authority does not sit upstream of knowledge. It intersects the system at the decision/action boundary.")
- [S1421] types=['INVARIANT', 'CONSTRAINT'] scope=OBJECT — "An Observation only becomes Evidence through an explicit qualification operation (weighing provenance, reliability, relevance, integrity, context, measurement conditions); consequently Observation and Evidence should not automatically belong to the same aggregate." (anchor: "Observation\not\Rightarrow Evidence. There must be a qualification operation: Q_o(O)\rightarrow E. ... Observation and Evidence should not automatically be the same aggregate.")
- [S1421] types=['FORMALIZATION'] scope=OBJECT — "The Evidence-supports/contradicts-Proposition relation is many-to-many: one evidence item can bear on multiple propositions, and one proposition can depend on multiple evidence items." (anchor: "|Evidence\rightarrow Proposition|=M:N in the conceptual model.")
- [S1421] types=['FORMALIZATION', 'DISTINCTION'] scope=THEORY-LEVEL — "Assessment cannot exist without its proposition (P-evaluatedUnder(M,U,E)->A), formalized as A=f(P,E,M,U,C,t); explicitly rejects modeling this as a mutation of the proposition's own status field, since an assessment can flip (Supported->Refuted) while the proposition remains the same identifiable object -- Proposition has identity, Assessment has temporal validity, a reproducibility requirement." (anchor: "A=f(P,E,M,U,C,t). ... Proposition has identity; Assessment has temporal validity.")
- [S1421] types=['FORMALIZATION'] scope=OBJECT — "Model is part of an assessment's lineage (M-usedBy->A), so two assessments of the same proposition/evidence under different models can legitimately differ, preventing hidden model assumptions from masquerading as objective disagreement." (anchor: "A_1=f(P,E,M_1,U) and: A_2=f(P,E,M_2,U). This prevents hidden model assumptions.")
- [S1421] types=['PRINCIPLE', 'EXTENSION'] scope=OBJECT — "Uncertainty qualifies (rather than merely decorates) Assessment and should not be reduced to a single confidence field, since it can arise from at least six distinct sources (incomplete evidence, measurement error, model assumptions, conflicting evidence, temporal decay, ambiguity) -- uncertainty is a first-class epistemic object." (anchor: "Uncertainty is a first-class epistemic object. ... incomplete evidence; measurement error; model assumptions; conflicting evidence; temporal decay; ambiguity.")
- [S1421] types=['FORMALIZATION', 'DISTINCTION'] scope=THEORY-LEVEL — "Assessment informs but does not imply Decision: Decision=f(Assessment,Authority,NormativeConstraints,Context,Time) -- the point where epistemology becomes governance." (anchor: "Assessment\not\Rightarrow Decision. A decision requires governance context. ... D=f(A,R,N,C,t) where R=Authority; N=normative/policy constraints; C=context.")
- [S1421] types=['RESTATEMENT'] scope=OBJECT — "Decision initiates but does not guarantee Action, since execution can fail; the decision-to-action step is itself a governed transition, not an automatic consequence." (anchor: "Decision\not\Rightarrow Action. Execution can fail. Thus: D \xrightarrow{execution} X is itself a governed transition.")
- [S1421] types=['FORMALIZATION', 'EXTENSION'] scope=THEORY-LEVEL — "Formalizes Action->Outcome as inherently probabilistic (Y~P(Y|X,S,E)), not deterministic -- a major mathematical bridge letting governance reason about expected loss/risk (Risk(d)=E[Loss(Y,d)]), uncertainty, risk appetite, thresholds, and alternatives." (anchor: "Y\sim P(Y\mid X,S,E). ... An action creates a distribution of possible outcomes, not necessarily one predictable result. ... Risk(d)=E[Loss(Y,d)].")
- [S1421] types=['FORMALIZATION', 'RESTATEMENT'] scope=THEORY-LEVEL — "Grounds the full learning-feedback loop formally across all seven pipeline stages, restated as 'knowledge evolves through governed feedback' -- the mathematical core of the learning architecture." (anchor: "E_t \rightarrow A_t \rightarrow D_t \rightarrow X_t \rightarrow Y_{t+1} \rightarrow O_{t+1} \rightarrow E_{t+1}. And therefore: Knowledge evolves through governed feedback.")
- [S1421] types=['CONSTRAINT', 'RESTATEMENT'] scope=OBJECT — "Lineage is cross-cutting (L(O),L(E),L(P),L(A),L(D),L(X),L(Y) all legitimate), with the important restriction that lineage records relationships without itself establishing semantic truth." (anchor: "Lineage records relationships; it does not itself establish semantic truth.")
- [S1421] types=['INVARIANT'] scope=THEORY-LEVEL — "Reaffirms that transition semantics belong to the domain: arbitrary CRUD operations must not be allowed to masquerade as domain transitions." (anchor: "CRUD mutation\neq Domain transition.")
- [S1421] types=['DEFINITION', 'CONSTRAINT'] scope=OBJECT — "A KnowledgeArtifact aggregates references to Proposition/Evidence/Assessment/Model/Uncertainty/Lineage without owning their lifecycles -- it is a knowledge product, not the owner of the underlying domain objects, important for implementation." (anchor: "KA=(P^*,E^*,A^*,M^*,U^*,L^*). But the artifact should not own all those lifecycles. It is a knowledge product, not necessarily the owner of the underlying domain objects.")
- [S1421] types=['EXTENSION'] scope=OBJECT — "Produces a first-cut ten-concept relation matrix (Observation/Evidence/Proposition/Assessment/Model/Uncertainty/Authority/Decision/Action/Outcome rows and columns marked with allowed relations), explicitly labeled a hypothesis, not yet normative." (anchor: "This matrix is not yet normative. It is our current hypothesis.")
- [S1421] types=['RESTATEMENT', 'ANALYSIS'] scope=THEORY-LEVEL — "Lists ten distinctions established provisionally at the architectural level by the relation-matrix exercise and explicitly ties them to concrete downstream engineering consequences (aggregate boundaries, APIs, events, persistence, authorization, audit, AI integration, statistical reproducibility) rather than treating them as merely philosophical." (anchor: "Evidence\neq Proposition ... Permission\neq Authority ... Observation\neq Evidence. These distinctions ... directly affect: aggregate boundaries; APIs; events; persistence; authorization; audit; AI integration; statistical reproducibility.")
- [S1421] types=['VALIDATION', 'RESTATEMENT'] scope=THEORY-LEVEL — "Step 202 per-discipline verdicts: mathematical PASS (relations compose without semantic shortcuts), statistical PASS (model and uncertainty remain explicit), DDD PASS-with-boundaries-still-provisional (vocabulary supports bounded-context discovery but the map is not final), governance PASS (policy and authority now explicitly separated), Gita lens STRENGTHENED (as a conceptual lens, not a mathematical derivation)." (anchor: "PASS (Mathematical) ... PASS — with boundaries still provisional (DDD) ... PASS (Governance) ... STRENGTHENED (Gita lens).")

## Notes for P3
- No internal tension, evidentiary anomaly, or lifecycle-flag discrepancy observed in this label's own rows beyond what the completeness roll-up above already shows.
