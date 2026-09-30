# k-projection-equality-typed-lossy-semantic-result

**Scope(s):** `OBJECT` · **Row count:** 6 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `(A,R) =_semantic pi_K(K_t), modulo drop of {Event,Policy,Action}`, `NOT =_structural, NOT =_observational` · **Aliases:** `equality-typed restatement of D285-6 projection claim`
**Candidate group membership (NOT an identity claim):**
- **G0457**: linked with `k-projection-definable-not-computable-result` — explicit agent-stated uncertainty: 'k-projection-equality-typed-lossy-semantic-result' POSSIBLY relates to 'k-projection-definable-not-computable-result' (batch B0050). Note: Self-critical re-examination applying the newly-mandated equality-typing discipline (from the D285-5 review, S2070) to the reviewer OWN earlier claim (D285-6: (A,R)=pi_K(K_t)): found FALSE under structural equality (Assertion is not a ratified primitive), TRUE under semantic equality but ONLY after unpacking Assertion into its fields (Proposition,Entity,Observation) and modulo the declared drop of {Event,Policy,Action}, and FALSE under observational equality (replay, policy-eval and authorize are unanswerable using only (A,R), since they need Event/Policy/Action) -- concluding projection is only the right word for a LOSSY, semantic-level map, and D285-6 Outcome B (projection) stands but is weaker than an unqualified = suggested.

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0050, scope OBJECT): Self-critical re-examination applying the newly-mandated equality-typing discipline (from the D285-5 review, S2070) to the reviewer OWN earlier claim (D285-6: (A,R)=pi_K(K_t)): found FALSE under structural equality (Assertion is not a ratified primitive), TRUE under semantic equality but ONLY after unpacking Assertion into its fields (Proposition,Entity,Observation) and modulo the declared drop of {Event,Policy,Action}, and FALSE under observational equality (replay, policy-eval and authorize are unanswerable using only (A,R), since they need Event/Policy/Action) -- concluding projection is only the right word for a LOSSY, semantic-level map, and D285-6 Outcome B (projection) stands but is weaker than an unqualified = suggested.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2071] §"CORRECT: (A,R) =_semantic pi_K(K_t) after unpacking Assertion into its fields, and modulo the declared drop of {Event, Policy, Action} ... and explicitly NOT: =_structural (Assertion is not a ratified primitive), =_observational (replay, policy-eval and authorize are unanswerable in (A,R))"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S2078`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S2071 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2071, S2072, S2072, S2072 |
| open_questions | PRESENT | S2078 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S2071]` types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Confirms, via executed test output, that the D285-6 projection claim (A,R)=pi_K(K_t) is only valid when explicitly typed as semantic equality after unpacking Assertion into its component fields and dropping Event/Policy/Action -- under structural equality it is false (Assertion is not itself a ratified primitive) and under observational equality it is false (replay, policy-evaluation and authorization queries cannot be answered from (A,R) alone)." (anchor: "CORRECT: (A,R) =_semantic pi_K(K_t) after unpacking Assertion into its fields, and modulo the declared drop of {Event, Policy, Action} ... and explicitly NOT: =_structural (Assertion is not a ratified…")
- `[S2072]` types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Test 1 of 3: under structural (name-for-name) equality, the D285-6 claim (A,R)=pi_K(K_t) is FALSE, simply because 'Assertion' does not appear among the ratified primitives at all -- a category mismatch, not merely an incomplete mapping." (anchor: "STRUCTURAL equality (same components, same names): pi image = [Entity,Observation,Proposition,Relation,State]; target=[Assertion,Relation]; equal? False -> 'Assertion' is not a ratified primitive at a…")
- `[S2072]` types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Test 2 of 3: under semantic equality, the claim holds, but only once Assertion is unpacked into its constituent fields (Proposition, Entity, Observation, plus non-primitive id/context/time/provenance fields) -- the claim is true, but true only relative to a specific, non-trivial unpacking operation that the original bare statement '(A,R)=pi_K(K_t)' did not make explicit." (anchor: "SEMANTIC equality (same content, ignore packaging): Assertion unpacks to carry {Proposition,Entity,Observation} (+id,c,t,Pi which are not primitives), plus Relation, plus State-as-carrier. covered by…")
- `[S2072]` types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Test 3 of 3: under observational equality (defined as agreement on answers to all mandatory queries), the claim is FALSE, because three specific query classes (replay, policy-evaluation, authorization) require Event/Policy/Action data that (A,R) alone cannot supply -- explicitly naming the projection as lossy by design, not merely incompletely specified." (anchor: "OBSERVATIONAL equality (same answers to all mandatory queries): answerable in (A,R): [member,contradicts,supersede,lineage]; NOT answerable: [replay,policy-eval,authorize] <- these need Event/Policy/A…")
- `[S2072]` types=[CORRECTION] scope=OBJECT — "Issues a formal self-correction of the earlier D285-6/T-B finding (S2063, where Model B 'projection' was found to HOLD): the projection claim must be restated with an explicit equality qualifier (semantic, not structural or observational) and an explicit accounting of what is lost (three primitives: Event/Policy/Action; three query classes: replay/policy-eval/authorize) -- the overall conclusion (Outcome B, projection) survives, but in a strictly weaker and more precisely bounded form than origi…" (anchor: "WRONG (as D285-6 originally wrote it): (A,R)=pi_K(K_t). CORRECT: (A,R) =_semantic pi_K(K_t) after unpacking Assertion into its fields, and modulo the declared drop of {Event,Policy,Action}, and explic…")
- `[S2078]` types=[OPEN-QUESTION] scope=OBJECT — "Flags, as the 'most important question' of the mandate, that semantic equality -- despite being used as the load-bearing relation in both D285-5 and D285-6 (S2063, S2072) -- has never itself been given a complete corpus-grounded formal definition, and could currently only be honestly described as a research-level construct rather than an established primitive." (anchor: "RQ3 Semantic equality: the previous report uses =semantic but does not yet establish a complete formal definition. ... If no: Explicitly state that semantic equality is currently a research-level cons…")

## Notes for P3
- No rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) rows were found for this label in the capture; the object's motivation is not evidenced here.
