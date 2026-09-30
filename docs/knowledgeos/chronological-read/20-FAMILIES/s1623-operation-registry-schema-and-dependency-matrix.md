# s1623-operation-registry-schema-and-dependency-matrix

**Scope(s):** OBJECT · **Row count:** 16 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** ProvDep/HistDep/PolicyDep in {0,1,?}; R(T_i)=13-field schema; StateReq(T) union ContextReq(T) · **Aliases:** Formal Operation Signature Registry; Step 256
**Candidate group membership (NOT an identity claim):**
- G0393: relationship not yet decided (P3). Per `_LABEL-NORMALIZATION.md`, explicit agent-stated uncertainty (batch B0039) that `s1623-operation-registry-schema-and-dependency-matrix` POSSIBLY relates to `s1613-transformation-system-typed-graph-not-algebra`. Note quoted there matches this label's own OBJECT-INDEX source note nearly verbatim (see Sources below).
- G0399: relationship not yet decided (P3). Per `_LABEL-NORMALIZATION.md`, explicit agent-stated uncertainty (batch B0039) that `s1629-four-operations-identity-precedes-equality` POSSIBLY relates to `s1623-operation-registry-schema-and-dependency-matrix`. Note: "Step 257's detailed formalization of Revise/Transform/Supersede/Merge, each exposing unresolved identity and/or provenance dependencies; establishes Identity as prior to and distinct from Equality (Identity != Equality), and sharpens the provenance conclusion to 'irreducible from T-history but not proven constitutive of K'. Sets Step 258 to formalize object equality, state equivalence, and history equivalence as three distinct relations with a congruence requirement."
- G1634: relationship not yet decided (P3). Per `_LABEL-NORMALIZATION.md`, `s1621-coarsest-sufficient-state-abstraction-and-counterexample-catalogue` and `s1623-operation-registry-schema-and-dependency-matrix` "co-occur in the same contribution's labels[] 2 separate times across the corpus" — a mechanical co-occurrence signal, consistent with this label's own rows 1 and 11 below (both dual-labeled with that other label).
- G1635: relationship not yet decided (P3). Per `_LABEL-NORMALIZATION.md`, `s1613-transformation-system-typed-graph-not-algebra` and `s1623-operation-registry-schema-and-dependency-matrix` "co-occur in the same contribution's labels[] 3 separate times across the corpus" — consistent with this label's own rows 8, 9, 10 below (all three dual-labeled with that other label).
- None of these four groups asserts identity between `s1623-operation-registry-schema-and-dependency-matrix` and any other label.

## Sources (how this label entered the ledger)
- PROPOSAL, batch B0039, scope OBJECT: "Step 256's 13-field operation-registry schema and 9x6 operation-to-information dependency matrix, converting the vague provenance-placement question into a precise per-operation test (ProvDep(T)=1?); shows Add/Revise cannot be defined until identity/equality is resolved and Merge is diagnostic for provenance necessity; names Supersede/Merge/Revise/Transform as the four highest-value discriminating operations, formalized next in Step 257." (`relation_to_existing`: "POSSIBLY:s1613-transformation-system-typed-graph-not-algebra")

No `single_candidate_flags` recorded.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1623 §"We must not force every operation into T:K->K because Step 255 already demonstrated that some operations may depend on external policy, authority, time, evidence or other typed context."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1623 §"ProvDep(T_i) in {0,1,?} ... This gives us a way to empirically determine where history must survive. ... transforms the vague question 'Does provenance belong in K?' into the operational question: exists T in T: ProvDep(T)=1? If yes, then provenance-sensitive information must be available to that op"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1623 §"T must be typed before K can be minimized. Operations may be partial. Operations need not share one homogeneous signature. Validation, Command, Event and Transformation must not be conflated. Provenance/History/Policy dependence must be explicitly tested per operation. Not established: the nine cand"]

## Lifecycle
last_seen: S1629. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (no retracted_by, no superseded_by, not contested). DORMANT is a recency heuristic (all rows fall within batch B0039) — not a confirmed retirement.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1623 (x3) |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1623 (x2), S1626, S1629 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1623 (x7), S1629 — 8 total |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1623 |
| experiments | PRESENT | S1626 |
| open_questions | PRESENT | S1623 (x3) |

## Rationale
Three rows carry classified rationale evidence, all from S1623. Add cannot be formally defined until equality/identity is resolved, since two radically different semantics remain open: plain set-union (duplicates silently absorbed) versus identity-aware rejection of objects whose identity already exists. Revise cannot be formally defined until cross-time object identity is resolved: if id(x')=id(x), Revise is a genuine state transition on one entity; if not, Revise reduces to Remove+Add of two different entities — the same identity/equality dependency recurring across multiple operations. Merge is diagnostically important for the provenance question: if a merged object's provenance is the union of its sources' provenances, Merge is inherently provenance-preserving, meaning the operation registry itself (not abstract argument) can reveal whether provenance is operationally essential; Split separately requires an explicit semantic-preservation invariant. `rationale_truncated_count` is 0, so no further rationale-bearing rows are known to exist beyond this capture.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
Rows 1-14 share `source_id` S1623, path `docs/knowledgeos/brainstorming/phase_measure_theory/20260830-185954_step_256_formal-operation-signature-registry.md`.

1. `types=[CONSTRAINT, RESTATEMENT] scope=OBJECT` — "Applies Step 255's finding directly: no operation should be forced into the shape T:K→K, since operations may legitimately depend on external typed context (policy, authority, time, evidence) rather than K alone." Dual-labeled with `s1621-coarsest-sufficient-state-abstraction-and-counterexample-catalogue`.
2. `types=[OPEN-QUESTION, ALTERNATIVE] scope=OBJECT` — Add/identity dependency; see Rationale above.
3. `types=[DISTINCTION, WARNING] scope=OBJECT` — "Distinguishes true deletion from lifecycle-status change as radically different operation semantics, concluding Remove ≠ Withdraw and Reject ≠ Remove unless the theory explicitly decides to identify them — since a status-changed object remains represented in K while a deleted one does not."
4. `types=[OPEN-QUESTION, ARGUMENT] scope=OBJECT` — Revise/identity dependency; see Rationale above.
5. `types=[ARGUMENT] scope=OBJECT` — Merge/provenance and Split invariant; see Rationale above.
6. `types=[PRINCIPLE] scope=THEORY-LEVEL, label_confidence=UNCERTAIN` — "States a determinism-discipline principle: any externally-varying quantity a transformation depends on (e.g. current time, applicable policy) must be an explicit function argument, never an implicit global, or the transformation appears nondeterministic to the mathematical model; separately clarifies determinism (same inputs, same result) is orthogonal to immutability of the overall knowledge base."
7. `types=[DEFINITION] scope=OBJECT` — "Defines the working operation-registry schema R(T_i) with 13 fields, extending the mandate's original 9-field proposal with explicit PolicyDependency and IdentityDependency flags." (anchor: "R(T_i) = (Name, Domain, Codomain, Preconditions, Postconditions, Invariants, Parameters, Dependencies, Determinism, ProvenanceDependency, HistoryDependency, PolicyDependency, IdentityDependency) becomes the working registry schema.")
8. `types=[RESTATEMENT] scope=OBJECT` — "Reaffirms the corpus's established finding that the governed transformation subset is not closed under composition, applying it to the operation-registry construction: composition T_j∘T_i is only meaningful when Codomain(T_i) intersects Domain(T_j), and closure must never be assumed." Dual-labeled with `s1613-transformation-system-typed-graph-not-algebra`.
9. `types=[RESTATEMENT] scope=OBJECT` — "Confirms operations need not share one homogeneous signature shape by giving 4 explicitly differently-typed example operations (T_Add, T_Merge, Validate, Approve), reaffirming the typed-transition-system (not single algebra) conclusion already reached in Steps 249-250." Dual-labeled with `s1613-transformation-system-typed-graph-not-algebra`.
10. `types=[DISTINCTION, RESTATEMENT] scope=OBJECT` — "Reaffirms three category-error-preventing non-collapses for the operation vocabulary: Validate ≠ Transform (validation may determine valid/invalid without changing K); Command ≠ Transformation (command is intent/request, not the state change itself); Event ≠ Transformation (an event records an occurrence, distinct from the state-evolution-defining function)." Dual-labeled with `s1613-transformation-system-typed-graph-not-algebra`.
11. `types=[DEFINITION, FORMALIZATION] scope=OBJECT` — ProvDep/HistDep/PolicyDep test; see Candidate births (formal) above. Dual-labeled with `s1621-coarsest-sufficient-state-abstraction-and-counterexample-catalogue`.
12. `types=[FORMALIZATION] scope=THEORY-LEVEL, label_confidence=UNCERTAIN` — "Refines the kernel-minimization target by splitting an operation's total required information into StateReq(T) (must be resident in K) and ContextReq(T) (may be supplied externally, does not constrain K), restating the optimization target K*=argmin Complexity(K) subject to semantic sufficiency with this refined split." Dual-labeled with `s1620-removal-test-provenance-base-case-and-user-decisions`.
13. `types=[GOVERNANCE, RESTATEMENT] scope=OBJECT` — "Consolidates the step's established-versus-not-established results: established that T must be typed before K can be minimized, operations may be partial, need not share one signature, and 4 key terms must not be conflated, with dependency-testing mandatory per operation; explicitly not established that the 9 candidate operations or any particular signature is final."
14. `types=[FUTURE-RESEARCH] scope=THEORY-LEVEL, label_confidence=UNCERTAIN` — "Names Supersede, Merge, Revise and Transform as the 4 highest-value operations for distinguishing competing state/history models, and sets Step 257 as their complete formalization ... in order to run the first real congruence experiment — explicitly judged stronger evidence than proposing yet another candidate kernel tuple." Lineage claim: SOURCE-CLAIMED-EXTENSION of "step 257".
15. `[S1626] types=[FORMALIZATION, EXPERIMENTAL-RESULT] scope=OBJECT` — "Fully specifies concrete 11-field signatures for 12 named operations (create, assert, retract, relate, merge, supersede, refine, resolve, remove, replay, validate, assess), each rated computable=YES, directly executing the operation-registry schema Step 256 (S1623) had proposed abstractly — e.g. retract has failure mode LOSSY-NO-INVERSE (executed), relate can fail via a cycle, merge may introduce contradiction (executed), replay is order-dependent (executed)." Dual-labeled with `s1626-four-valid-predicates-and-twelve-operation-algebra`. Path: `docs/knowledgeos/brainstorming/verification/KNOWLEDGE-STATE-ALGEBRA.md`.
16. `[S1629] types=[FORMALIZATION, PRINCIPLE] scope=OBJECT` — "Confirms Transform need not be unary (T:K×Q×C→K', a typed state transition rather than a closed K→K function), reaffirms that every semantically relevant external dependency (policy, time, authority, evidence) must be an explicit argument to preserve determinism, and proposes an unestablished provenance-propagation model Prov(k')=Prov(k)∪Prov(q)∪Prov(c) for Transform specifically." Dual-labeled with `s1629-four-operations-identity-precedes-equality`. Path: `docs/knowledgeos/brainstorming/phase_measure_theory/20260830-190222_step_257_formalization-of-the-four-discriminating-operations.md`.

## Notes for P3
- Six of this label's 16 rows are dual-labeled with three other working_labels not in this batch's 20 assigned labels (`s1621-coarsest-sufficient-state-abstraction-and-counterexample-catalogue`, `s1613-transformation-system-typed-graph-not-algebra`, `s1620-removal-test-provenance-base-case-and-user-decisions`, `s1626-four-valid-predicates-and-twelve-operation-algebra`, `s1629-four-operations-identity-precedes-equality`) — all mechanically corroborated by the G0393/G0399/G1634/G1635 co-occurrence signals in the normalization document. This label sits at the center of a densely cross-referenced Step-256/257 research cluster; P3 should treat the whole cluster together when reconciling.
- The evidence base is unusually rich and internally coherent — a single step's document (S1623, "Step 256") systematically working through operation-by-operation implications, followed by two direct successor artifacts (S1626 "12 operations fully specified," S1629 "Step 257") that execute what S1623 proposed abstractly. No contradiction observed; this reads as a well-documented linear research progression.
- Multiple rows explicitly flag identity/equality as an unresolved blocking dependency for defining Add and Revise — this recurring "equality/identity must be resolved first" theme may be worth cross-referencing against other identity/equality-focused labels elsewhere in the corpus, though this file does not itself identify which other working_labels those might be.
