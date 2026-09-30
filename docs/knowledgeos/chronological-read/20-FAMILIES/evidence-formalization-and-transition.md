# evidence-formalization-and-transition

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `E=(Source,Type,Content,Reliability,Relevance,Context,tau)`, `Sigma_new=Transition(Sigma_old,Evidence)` · **Aliases:** `Question 4`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0019`, scope `OBJECT`: Formal model of Evidence as input to an epistemic-state transition function, including a numeric support/strength calculus later flagged as an unvalidated experimental candidate (no evidence-independence/correlation model).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0776 §"Evidence is information that bears on the truth or falsity of a proposition, and that can be used to update the epistemic state of an assertion ... Evidence = (Source, Type, Content, Reliability, Relevance, Context, tau)"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0776 §"Evidence is information that bears on the truth or falsity of a proposition, and that can be used to update the epistemic state of an assertion ... Evidence = (Source, Type, Content, Reliability, Relevance, Context, tau)"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0776. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0776 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0776, S0776 |
| dependencies | PRESENT | S0776, S0776, S0776 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0776 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0776] types=['DEFINITION', 'FORMALIZATION'] scope=OBJECT — "Formalizes Evidence as a 7-field tuple (Source,Type,Content,Reliability,Relevance,Context,tau) with eight named evidence types (direct observation/testimony/documentation/measurement/inference/logical proof/statistical/historical), establishing Evidence != Assertion and Evidence -> EpistemicStateTransition." (anchor: "Evidence is information that bears on the truth or falsity of a proposition, and that can be used to update the epistemic state of an assertion ... Evidence = (Source, Type, Content, Reliability, Relevance, Context, tau)")
- [S0776] types=['CORRECTION'] scope=OBJECT — "Corrects Reliability and Relevance from being intrinsic properties of Evidence to being relational functions of evidence-plus-context and evidence-plus-proposition respectively, since the same evidence (e.g. CPU=45%) can support one proposition and contradict another depending on which proposition it is evaluated against." (anchor: "I would not make Reliability and Relevance intrinsic properties of evidence... Reliability(E,S,C) ... Relevance(E,P) ... Support \neq Property(E) ... Support = f(E,P,C)")
- [S0776] types=['CORRECTION', 'LIMITATION'] scope=OBJECT — "Rejects labelling the proposed epistemic-status ordering (Unknown->Hypothesized->Assumed->Inferred->Observed->Confirmed) a mathematical lattice, since Conflicting/Unresolved/Rejected do not sit on a single linear ladder and Observed is not comparable to Conflicting; downgrades it to an unstructured 'Epistemic State Space' pending investigation of whether it forms a partial order, lattice, bilattice, or belief-revision structure." (anchor: "I don't think we have established enough mathematics to call this a lattice... Observed \not< Conflicting in any obvious scalar sense. So I would call it: Epistemic State Space for now.")
- [S0776] types=['OPEN-QUESTION'] scope=OBJECT — "Poses Question 4A as a prerequisite to Question 5, asking whether epistemic state is a single ordered scalar or a vector of independent dimensions -- explicitly flagged as foundational to later measure-theoretic and Zero Lens formalization." (anchor: "Question 4A — What exactly is an Epistemic State? Is it one ordered state (Unknown -> Confirmed), or is it a vector of independent dimensions such as acquisition mode, evidential support, resolution, validity, and conflict status?")

## Notes for P3
- Nothing unusual observed while assembling this file; evidence is internally consistent for the rows captured under this label.
