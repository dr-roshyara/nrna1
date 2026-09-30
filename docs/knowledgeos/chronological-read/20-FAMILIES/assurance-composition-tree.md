# assurance-composition-tree

**Scope(s):** OBJECT · **Row count:** 5 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Affected(E)=Descendants(E,G_P)`, `Assurance tree`
**Aliases:** `Assurance dependency closure`
**Candidate group membership (NOT an identity claim):**
- G0194: [`assurance-composition-tree` · `assurance-model`] — explicit agent-stated uncertainty: 'assurance-composition-tree' POSSIBLY relates to 'assurance-model' (batch B0023). Note: Step 42's hierarchical assurance composition (Decision/Assurance/Claim/Evidence tree), cascading invalidation on evidence revocation, dependency-closure formula, incremental recomputation requirement, and the non-monotonic-knowledge argument against assurance monotonicity; distinct from assurance-model (KOS-ATTR-ARCH-001's Assurance Claim/Evidence/Assessment/Outcome domain model).
- G1450: [`assurance-composition-tree` · `decision-contract-admissibility-model`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- G1451: [`assurance-composition-tree` · `safety-invariant-taxonomy-and-gate`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **PROPOSAL**, batch B0023, scope OBJECT (relation_to_existing: POSSIBLY:assurance-model): Step 42's hierarchical assurance composition (Decision/Assurance/Claim/Evidence tree), cascading invalidation on evidence revocation, dependency-closure formula, incremental recomputation requirement, and the non-monotonic-knowledge argument against assurance monotonicity; distinct from assurance-model (KOS-ATTR-ARCH-001's Assurance Claim/Evidence/Assessment/Outcome domain model).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0937 §"Assurance composition tree and cascading invalidation"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0937 §"Assurance composition tree and cascading invalidation"]
- CANDIDATE-FORMAL-BIRTH: [S0937 §"Assurance composition tree and cascading invalidation"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0937. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is DORMANT, this is a heuristic based on how recently (by source_id) this label was last used (S0937), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0937 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0937 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0937 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0937 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0937 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S0937] (ARGUMENT, CORRECTION): Considers the candidate property 'adding valid evidence should not reduce assurance unnecessarily' and rejects it as not universally true, since new evidence can reveal that previous beliefs were wrong -- so Knowledge is not necessarily monotonic: K_t |= A can hold while K_{t+1} not|= A after new evidence, which 'is not a failure, it is knowledge revision'. Consequently Assurance_{t+1}(A) < Assurance_t(A) is a supported, expected transition. Safety is treated as different: a safety invariant should be monotonic in the sense that once a prohibited state is detected the system should not ignore it because new evidence is favorable, though the underlying factual interpretation may change -- so a SafetyDecision must retain the evidence and reasoning that produced it.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0937] types=['FORMALIZATION', 'CONCEPT'] scope=OBJECT — "Revisits assurance composition: assurance objects A1,A2,A3 may be combined by decision policy as A1 and A2 and A3, but each A_i itself carries Validity, Provenance, Confidence and Scope, so assurance composition is hierarchical -- depicted as an assurance tree (Decision -> Assurance A/Assurance B -> Claim/Evidence -> Validation). Cascading invalidation: if Evidence(E) is revoked, Claim(C) may become invalid, then Assurance(A) must be recomputed, then potentially Decision(D) must be revisited." (anchor: "Assurance composition tree and cascading invalidation")
- [S0937] types=['FORMALIZATION', 'PRINCIPLE'] scope=OBJECT — "Formalizes dependency closure as Affected(E) = Descendants(E, G_P) (an evidence revocation propagates through the provenance/dependency graph G_P), and states as a major software property that KnowledgeOS should support incremental assurance recomputation -- it should not need to recompute the entire knowledge base after every change." (anchor: "Affected(E)=Descendants(E,G_P); incremental assurance recomputation")
- [S0937] types=['ARGUMENT', 'CORRECTION'] scope=CROSS-OBJECT — "Considers the candidate property 'adding valid evidence should not reduce assurance unnecessarily' and rejects it as not universally true, since new evidence can reveal that previous beliefs were wrong -- so Knowledge is not necessarily monotonic: K_t |= A can hold while K_{t+1} not|= A after new evidence, which 'is not a failure, it is knowledge revision'. Consequently Assurance_{t+1}(A) < Assurance_t(A) is a supported, expected transition. Safety is treated as different: a safety invariant should be monotonic in the sense that once a prohibited state is detected the system should not ignore it because new evidence is favorable, though the underlying factual interpretation may change -- so a SafetyDecision must retain the evidence and reasoning that produced it." (anchor: "Assurance monotonicity is not universally true; non-monotonic knowledge revision")
- [S0937] types=['EXPERIMENTAL-RESULT'] scope=THEORY-LEVEL — "Runs twelve falsification tests against the Step 42 model, all recorded PASS: (1) epistemic requirements pass but authorization fails => Admissible=False; (2) authorization passes but a hard invariant fails => Block; (3) all conditions known except a safety-critical invariant => Block/Unknown per policy, never automatic authorization; (4) a valid transition from a valid state preserves the invariant; (5) a transition that would violate a domain invariant is rejected; (6) a required approval expires before execution => decision becomes inadmissible; (7) evidence used by a decision is revoked => dependency closure identifies affected assurance and decisions; (8) an AI proposes an action violating a governance rule => proposal may be recorded but execution is blocked; (9) new evidence disproves an earlier assumption => existing assurance can be downgraded/revoked; (10) a decision is evaluated without execution => dry-run result has no side effect; (11) an invariant belongs to bounded context BC_A => KnowledgeOS does not silently redefine it from BC_B; (12) a decision passes technical assurance but lacks governance authorization => TechnicallySupported=True, Authorized=False, therefore Execute=False." (anchor: "Twelve falsification experiments for Step 42 (all PASS)")
- [S0937] types=['RESTATEMENT', 'PRINCIPLE'] scope=THEORY-LEVEL — "Declares STEP 42 -- PASS and restates seven principles as the step's headline results: (1) Sufficient Knowledge != Valid Decision; (2) Valid Decision != Authorized Action; (3) Unknown safety state must not silently become safe; (4) Hard invariants are non-negotiable; (5) AI proposal != authorized execution; (6) Every consequential decision should have an explicit contract; (7) Evidence invalidation must propagate through decision dependencies." (anchor: "Step 42 verdict and seven closing principles")

## Notes for P3
(none beyond what is noted above)
