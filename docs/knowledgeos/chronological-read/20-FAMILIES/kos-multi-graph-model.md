# kos-multi-graph-model

**Scope(s):** THEORY-LEVEL · **Row count:** 12 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** G_C, G_I, G_K, G_P · **Aliases:** Knowledge/Identity/Provenance/Causal graph family
**Candidate group membership (NOT an identity claim):**
- **G1453**: [`causal-reasoning-assurance-layer` · `kos-multi-graph-model`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus
- **G1481**: [`architecture-conformance-engineering-model` · `kos-multi-graph-model`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1482**: [`knowledgeos-architectural-inventory-methodology` · `kos-multi-graph-model`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0023, scope THEORY-LEVEL): The architecture's family of distinct graph types over the same knowledge base -- G_K (knowledge/semantic relationships), G_I (identity relationships), G_P (provenance/dependency), and G_C (causal relationships, introduced in Step 43) -- explicitly kept semantically separate (e.g. CausalEdge != GenericRelationship, a causal edge is a knowledge assertion in K with its own provenance via G_P, not identical to a G_K edge).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0938 §"Structural causal model X_i=f_i(Pa_i,U_i); CausalEdge != GenericRelationship; need for G_C"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0938 §"Step 43 verdict, six core principles, and G_C as a new graph type alongside G_K/G_I/G_P"]
- CANDIDATE-FORMAL-BIRTH: [S0938 §"Structural causal model X_i=f_i(Pa_i,U_i); CausalEdge != GenericRelationship; need for G_C"]
- CANDIDATE-OPERATIONAL-BIRTH: [S1000 §"101.21 — Architecture must be inferred from dependencies ... examine DependencyGraph. For each component C_i→C_j. Then compare the actual graph against the allowed architecture graph."]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1001. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by/superseded_by empty, contested flag false). The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the ledger, not a confirmed retirement and not a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0938, S0944, S0956, S0973, S0993, S1000, S1001 |
| type_signature | PRESENT | S0938 |
| invariants | PRESENT | S0973 |
| dependencies | PRESENT | S0938, S0944, S0956 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0938, S0944, S0972 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0972, S0973, S1001 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0938] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — "Represents a structural causal model conceptually as X_i = f_i(Pa_i, U_i) (Pa_i = causal parents, U_i = exogenous factors), a mechanism-oriented representation rather than a purely correlational graph. Argues KnowledgeOS needs a distinct causal graph G_C alongside the existing knowledge-relationship graph G_K -- relatedTo does not mean causes, dependsOn does not necessarily mean causes -- formalized as CausalEdge != GenericRelationship." (anchor: "Structural causal model X_i=f_i(Pa_i,U_i); CausalEdge != GenericRelationship; need for G_C")
- [S0938] types=[FORMALIZATION, PRINCIPLE] scope=OBJECT — "A causal claim should carry a GeneralizationScope (e.g. {BusinessUnit, Technology, Environment, Time}) so an agent can judge whether it applies to the current situation. Gives the full CausalClaim tuple: (Cause, Effect, Mechanism, Evidence, StudyDesign, Assumptions, Scope, Time, EffectSize, Uncertainty, Model) -- 'a powerful domain object'. Distinguishes the causal graph G_C from the provenance/dependency graph G_P: a causal edge A->B should itself have provenance (Evidence -> CausalClaim(A->B)); the causal graph is not metaphysical truth -- an edge A->B is a knowledge assertion supported by evidence, so CausalEdge is in K." (anchor: "CausalClaim tuple; causal generalization scope; causal edges are knowledge assertions not metaphysical truth")
- [S0938] types=[RESTATEMENT, PRINCIPLE, CONCEPT] scope=THEORY-LEVEL — "Declares STEP 43 -- PASS and restates six core principles: Correlation != Causation; Observation != Intervention; Temporal precedence != Causality; Prediction != Explanation; Counterfactual != Observation; Causal claims require assumptions. Adds a new mathematical layer G_C (causal graph) to the architecture's existing graph family: G_K (knowledge relationships), G_I (identity relationships), G_P (provenance/dependency), and now G_C (causal relationships) -- 'these graphs interact but have different semantics'." (anchor: "Step 43 verdict, six core principles, and G_C as a new graph type alongside G_K/G_I/G_P")
- [S0944] types=[DEFINITION, DISTINCTION] scope=OBJECT — "Provenance Prov(C)={E_1,...,E_n}, generalized to a DAG-shaped Prov(x); reduced to a relation, not a primitive object: Supports(E,C), DerivedFrom(C1,C2). Causality is another relation Causes(A,B), explicitly not to be conflated with Supports(A,B): Supports != Causes. Software dependency DependsOn(A,B) is yet another relation, and DependsOn != Causes -- a system may technically depend on a component without that component being the causal reason for a given outcome. Consolidates a typed relation set R={Supports, DependsOn, Causes, Identifies, MapsTo, Contradicts, Precedes} that must remain typed: collapsing everything into a generic RelatedTo(A,B) loses semantics and could let an AI infer RelatedTo=>Causes, 'recreating one of the fundamental problems we eliminated in Step 43'." (anchor: "Provenance and Causality reduced to typed relations; Supports != Causes != DependsOn")
- [S0956] types=[FORMALIZATION] scope=OBJECT — "Represents legitimate branching K1->K2^A and K1->K2^B (competing hypotheses) as a version graph G_K=(V,E) with V=KnowledgeStates and E=RevisionRelations, more general than a simple version chain; argues a revision graph should normally be acyclic (a revision should not causally depend on itself) while a domain may separately represent cyclic feedback elsewhere, distinguishing RevisionGraph from CausalGraph. Defines the candidate partial order K_A⪯K_B (K_B is a valid successor/refinement of K_A) and tests it for reflexivity (K_A⪯K_A), antisymmetry (K_A⪯K_B ∧ K_B⪯K_A ⇒ K_A=K_B under the chosen equivalence), and transitivity (K_A⪯K_B ∧ K_B⪯K_C ⇒ K_A⪯K_C)." (anchor: "Knowledge version graph G_K=(V,E); RevisionGraph != CausalGraph; candidate partial order tested for reflexivity/antisymmetry/transitivity")
- [S0972] types=[RESTATEMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Re-derives structural equations X=f_X(U_X), Y=f_Y(X,U_Y) with do(X=x) replacing X's mechanism (experiment 9: observing X=x does not block Z->Y, but do(X=x) removes the incoming mechanism into X -- DifferentCausalSemantics -- PASS), and the G_K (knowledge/dependency) vs G_C (causal) graph distinction. Experiment 10: using one generic 'A->B' edge type for dependency, provenance, temporal order, and causality simultaneously yields SemanticFailure -- explicitly FAIL, reinforcing the requirement for explicit relation types (DerivedFrom, DependsOn, Precedes, Supports, Contradicts, Causes, Authorizes) that must never be reduced to one generic arrow." (anchor: "Structural causal model re-derived (do(X=x) replaces the mechanism); generic-edge-collapse experiment FAILs, reinforcing typed relations")
- [S0973] types=[FORMALIZATION, EXPERIMENTAL-RESULT] scope=OBJECT — "States trust transitivity is not universally valid: Trust(A,B)=High and Trust(B,C)=High does not license Trust(A,C)=High -- experiment 20 rejects that inference -- PASS, 'prevents dangerous trust propagation through agent networks'. Extends the multi-graph family with a Trust graph G_T alongside Knowledge (G_K), Provenance (G_P), Dependency (G_D), and Authority (G_A) graphs, which 'should not be collapsed into one universal graph'. Experiment 21: representing all relationships as one generic relation(source,target,type) with no bounded semantics yields SemanticAmbiguity -- explicitly FAIL, 'the relationships have different invariants' -- while noting a higher-level system may still correlate G_K/G_P/G_A while each retains its own domain semantics." (anchor: "Trust non-transitivity; multi-graph model (G_K,G_P,G_D,G_A,G_T) not collapsible (generic-relation-schema FAILs)")
- [S0993] types=[FORMALIZATION, DEFINITION] scope=OBJECT — "Proposes a provenance-aware knowledge graph pattern: Fact←supportedBy—Evidence, Evidence—origin→Source, Source—trust→TrustAssessment." (anchor: "94.35 — Provenance-aware knowledge graph ... Fact<-supportedBy-Evidence. Evidence-origin->Source. Source-trust->TrustAssessment.")
- [S1000] types=[IMPLEMENTATION, CONCEPT] scope=OBJECT — "Proposes inferring actual architecture from the code dependency graph, compared against the allowed architecture graph." (anchor: "101.21 — Architecture must be inferred from dependencies ... examine DependencyGraph. For each component C_i→C_j. Then compare the actual graph against the allowed architecture graph.")
- [S1000] types=[FORMALIZATION, DEFINITION] scope=OBJECT — "Defines the architecture evidence graph G=(V,E) with typed nodes (Requirement/Principle/ADR/Component/CodeArtifact/Test/Deployment/RuntimeObservation) and typed edges (implements/verifies/dependsOn/deployedAs/observedBy)." (anchor: "101.36 — Architecture evidence graph ... G=(V,E) where nodes include Requirement, Principle, ADR, Component, CodeArtifact, Test, Deployment, RuntimeObservation. Edges: implements, verifies, dependsOn, deployedAs, observedBy.")
- [S1001] types=[FORMALIZATION, DEFINITION] scope=OBJECT — "Formalizes the architecture inventory as a typed graph G_architecture=(V,E) with node types (Repository/Component/BoundedContext/Schema/Policy/ADR/Test/Deployment/Runtime) and edge types (contains/implements/dependsOn/governedBy/verifiedBy/deployedAs/observedBy)." (anchor: "102.60 — Architecture inventory as a graph ... G_architecture=(V,E) with nodes Repository, Component, BoundedContext, Schema, Policy, ADR, Test, Deployment, Runtime. Edges: contains, implements, dependsOn, governedBy, verifiedBy, deployedAs, observedBy.")
- [S1001] types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 27: a critical component with no governedBy edge is a governance gap; result PASS." (anchor: "102.61 — Experiment 27 ... A critical component has no governedBy edge. Expected: governance gap. Result: PASS")

## Notes for P3
(Own observation) This label participates in 3 candidate group(s) (G1453, G1481, G1482); P3 should assess whether any represent the same underlying object as this label, per the reasons recorded in _LABEL-NORMALIZATION.md.
