# uncertainty-typed-object-u-h

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** U(H)=(type,value,model,scope,source), type in {Probability,Interval,SetValued,Unknown,Qualitative} · **Aliases:** Step 031 s31.22-31.24
**Candidate group membership (NOT an identity claim):**
- G1024: [`typed-uncertainty-taxonomy` · `uncertainty-typed-object-u-h`] — working_label token overlap Jaccard=0.50 (shared tokens: ['typed', 'uncertainty'])

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0041 · scope OBJECT: A typed uncertainty object surviving all five tested placements for uncertainty (in Assertion, in Evidence, as a relation, as an external annotation, as a probability distribution) that the audit had declared inexpressible; its update/merge algebra is undefined and it overlaps unreconciled with Sigma=(dir,str).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1683 §"31.24 is exactly the object the audit said does not exist, and it is BETTER than the thing the audit looked for. ... Verdict: SOURCE ESTABLISHES the object, PROPOSED its algebra, NOT ADOPTED in v0.2. Not INEXPRESSIBLE."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1683 §"31.24 is exactly the object the audit said does not exist, and it is BETTER than the thing the audit looked for. ... Verdict: SOURCE ESTABLISHES the object, PROPOSED its algebra, NOT ADOPTED in v0.2. Not INEXPRESSIBLE."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1694 §"The three exclusions were a discovery failure, not a mathematical limit. They are absent from the AUTHORIZED MODEL v0.2 ... so the finding is real, and it is a GOVERNANCE finding: the constructions exist and were never promoted."]

## Lifecycle
last_seen: S1694. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (DORMANT) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1683 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1683 |
| type_signature | PRESENT | S1683 |
| invariants | PRESENT | S1683 |
| dependencies | PRESENT | S1683, S1694 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Step 031 s31.22-31.24 defines a probability object q=(H,P,Model,Context,Time,Evidence), types Unknown(H) as a type distinction rather than P(H)=0.5, and defines U(H)=(type,value,model,scope,source) with type in {Probability,Interval,SetValued,Unknown,Qualitative}; testing five candidate placements for uncertainty (assertion field, evidence field, a relation in R, an external annotation, a probability distribution) shows all five inadequate/refuted, leaving U(H) as the corpus's own surviving answer, though its update/merge semantics (U x U -> U) are undefined and it overlaps unreconciled with Sigma=(dir,str) [S1683].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1683] types=[CORRECTION, FORMALIZATION, ARGUMENT] scope=OBJECT — "Step 031 s31.22-31.24 defines a probability object q=(H,P,Model,Context,Time,Evidence), types Unknown(H) as a type distinction rather than P(H)=0.5, and defines U(H)=(type,value,model,scope,source) with type in {Probability,Interval,SetValued,Unknown,Qualitative}; testing five candidate placements for uncertainty (assertion field, evidence field, a relation in R, an external annotation, a probability distribution) shows all five inadequate/refuted, leaving U(H) as the corpus's own surviving answer, though its update/merge semantics (U x U -> U) are undefined and it overlaps unreconciled with Sigma=(dir,str)." (anchor: "31.24 is exactly the object the audit said does not exist, and it is BETTER than the thing the audit looked for. ... Verdict: SOURCE ESTABLISHES the object, PROPOSED its algebra, NOT ADOPTED in v0.2. Not INEXPRESSIBLE.")
- [S1694] types=[CORRECTION, GOVERNANCE] scope=THEORY-LEVEL — "Corrects a prior verdict ('THEORY CLOSED AGAINST THE STATED CRITERIA -- NOT COMPLETE IN EVERY RESPECT', with 'a theory that cannot say nobody ever asked is closed, not finished') by showing the corpus does say 'nobody ever asked' (the not-assessed Zero-lens dimension state, committed one day before this programme began) and does have formal, typed constructions for non-identifiability and uncertainty; reclassifies the three exclusions as a discovery failure rather than a mathematical limit, and as a governance finding since these constructions are measured absent (zero grep occurrences of unknown/absent/not assessed/uncertain/identifiab) from the authorized v0.2 model despite existing in the raw corpus." (anchor: "The three exclusions were a discovery failure, not a mathematical limit. They are absent from the AUTHORIZED MODEL v0.2 ... so the finding is real, and it is a GOVERNANCE finding: the constructions exist and were never promoted.")

## Notes for P3
(none beyond what is captured above)
