# epistemic-tuple-object

**Scope(s):** OBJECT · **Row count:** 6 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** K=(P,E,M,A,C,T,S,W) · **Aliases:** epistemic tuple

**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- **OBJECT-INDEX**, batch B0034, scope OBJECT: Step 192's proposed semantic-model tuple for a KnowledgeOS knowledge object (proposition, evidence, model, assumptions, context, temporal info, epistemic status, provenance/witness), explicitly not yet a DDD aggregate; includes the likelihood-ratio formalization of Supports(e,p) and the distribution-vs-determination distinction.

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1395 §"EpistemicAssessment(p)=f(E,M,A). ... labels over our epistemic position. They are not claims about metaphysical truth."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1395 §"LR(e) = P(e|p)/P(e|not p). This is a concrete example of why evidence and truth must remain separate."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1395. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction rows found). This DORMANT classification is a heuristic based on how recently (by source_id, last_seen=S1395) this label was last used in the captured contribution set, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | PRESENT | S1395 |
| formal_definition | PRESENT | S1395 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1395 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1395 |
| examples | PRESENT | S1395 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE

## Assumption register

NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S1395]` types=[DEFINITION] scope=OBJECT — "Defines EpistemicAssessment(p)=f(E,M,A) with possible values {Unknown, Plausible, Supported, StronglySupported, Conflicted, Refuted} as labels over epistemic position, explicitly not metaphysical truth claims." (anchor: "EpistemicAssessment(p)=f(E,M,A). ... labels over our epistemic position. They are not claims about metaphysical truth.")
- `[S1395]` types=[DEFINITION, RESTATEMENT] scope=OBJECT — "Reformulates 'known' as AcceptedAsKnowledge(p|C,R) (context+rule dependent) and then as the relational K(a,p,t|C): actor a, at time t, in context C, holds a knowledge status about p -- knowledge is not a timeless property of a proposition alone." (anchor: "AcceptedAsKnowledge(p\mid C,R). ... K(a,p,t\mid C) ... This prevents us from treating knowledge as a timeless property of a proposition.")
- `[S1395]` types=[FORMALIZATION, DEFINITION] scope=OBJECT — "Formalizes Supports(e,p) via a likelihood ratio LR(e)=P(e|p)/P(e|not-p): evidence increasing support for p under a specified interpretation is not the same as True(p)." (anchor: "LR(e) = P(e|p)/P(e|not p). This is a concrete example of why evidence and truth must remain separate.")
- `[S1395]` types=[PRINCIPLE, EXAMPLE] scope=THEORY-LEVEL — "Worked example: P(p|E)=0.95 computed from evidence E later discovered corrupted can drop sharply under corrected evidence E'; the original inference was not irrational, only conditional on evidence available at the time -- epistemic validity is time- and evidence-dependent." (anchor: "Epistemic validity is time and evidence dependent.")
- `[S1395]` types=[FORMALIZATION, DEFINITION] scope=OBJECT — "Proposes an epistemic tuple K=(P,E,M,A,C,T,S,W) [proposition, evidence, model, assumptions, context, temporal information, epistemic status, provenance/witness] as a semantic model of what a KnowledgeOS knowledge object needs to represent -- explicitly a semantic model, not yet a DDD aggregate (which of these fields must change atomically remains a separate design question)." (anchor: "K=(P,E,M,A,C,T,S,W)")
- `[S1395]` types=[EXTENSION, INVARIANT] scope=OBJECT — "Distribution theory applied naturally: an uncertain quantity theta can be stored as a full posterior distribution rather than a point estimate, but Distribution != Determination -- a 0.97 posterior does not grant organizational authority." (anchor: "theta \sim F_{theta|E,M,A}. ... Distribution \neq Determination. A posterior of 0.97 does not grant organizational authority.")

## Notes for P3

All 6 rows trace to a single source document (S1395); the evidentiary base for this label is broad in row count but narrow in provenance (one authoring pass, one document).
