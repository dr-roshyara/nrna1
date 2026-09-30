# policy-concept-fact-norm-authority-triad

**Scope(s):** OBJECT · **Row count:** 7 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Fact != Norm != Authority`, `I_74`, `Policy: Context x Action -> {Must,May,MustNot,Conditional}` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope OBJECT): Step 202's newly-added Policy concept (found missing from Step 201's frozen vocabulary), its separation from Authority, the Fact/Norm/Authority triad, invariant I_74 (policy check required before governance-validity), and the exception-handling model (Exception != PolicyDeletion).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1421 §"Step 201 deliberately exposed an omission. We introduced: Normative thinking, but Policy was not included in the frozen vocabulary. ... we must add: Policy to the candidate vocabulary."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1421 §"Policy: Context\times Action \rightarrow \{Must,May,MustNot,Conditional\}. Policy is therefore not evidence. It is not authority. It is not a decision. It constrains decisions/actions."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1421. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S1421) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
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
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S1421]` types=[CORRECTION, EXTENSION] scope=OBJECT — "Discovers via the relation-matrix exercise that Policy was omitted from Step 201's frozen vocabulary despite the prior descriptive/normative distinction requiring it (Must/MustNot/May cannot be modeled without a policy concept); adds Policy as a legitimate vocabulary extension, explicitly framed as resolving a real gap the matrix exposed, not conceptual inflation." (anchor: "Step 201 deliberately exposed an omission. We introduced: Normative thinking, but Policy was not included in the frozen vocabulary. ... we must add: Policy to the candidate vocabulary.")
- `[S1421]` types=[DEFINITION, FORMALIZATION] scope=OBJECT — "Canonical definition of Policy ('what normative constraints govern behavior in this context?') as a function Context x Action -> {Must,May,MustNot,Conditional}, explicitly distinct from evidence, authority, and decision -- it constrains decisions/actions rather than being one." (anchor: "Policy: Context\times Action \rightarrow \{Must,May,MustNot,Conditional\}. Policy is therefore not evidence. It is not authority. It is not a decision. It constrains decisions/actions.")
- `[S1421]` types=[INVARIANT, DISTINCTION] scope=THEORY-LEVEL — "Separates Policy (what may/should be done) from Authority (who may legitimately decide/perform it) as a crucial, previously-missing distinction." (anchor: "Policy\neq Authority. Policy answers: What may/should be done? while: Authority answers: Who may legitimately decide or perform it?")
- `[S1421]` types=[RESTATEMENT] scope=THEORY-LEVEL — "Maps the Gita Chapter 4 'what to do and what not to do' theme cleanly onto DescriptiveKnowledge!=NormativePolicy and the four-part Policy decomposition, explicitly as a conceptual-value-add lens, not a proof source." (anchor: "DescriptiveKnowledge\neq NormativePolicy. And: Policy=Must+May+MustNot+Conditional. This is precisely where the Chapter 4 lens adds architectural value without being used as a proof.")
- `[S1421]` types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_74: a decision or action must be evaluated against applicable policy before being considered governance-valid, subject to an explicit exception mechanism." (anchor: "I_{74}: A decision or action must be evaluated against the applicable policy before being considered governance-valid. Subject to an explicit exception mechanism.")
- `[S1421]` types=[DEFINITION, DISTINCTION] scope=OBJECT — "Formalizes exception handling: a PolicyViolation can become an ExceptionGranted state via an authorized actor satisfying an exception policy, but the exception never deletes or overwrites the original policy -- Exception != PolicyDeletion." (anchor: "PolicyViolation may be transformed into: ExceptionGranted if an authorized actor satisfies the exception policy. ... Exception\neq PolicyDeletion. The original policy remains.")
- `[S1421]` types=[PRINCIPLE] scope=THEORY-LEVEL — "States the formal triad: three fundamentally different reasons an action may occur -- Descriptive ('it happened'), Normative ('it should happen'), Governance ('this actor is authorized to decide/execute it') -- which must never be collapsed; Fact != Norm != Authority proposed as a foundational architectural principle." (anchor: "Fact\neq Norm\neq Authority. This may become one of the foundational philosophical/mathematical principles of the book.")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
