# truth-knowledge-lattice-program

**Scope(s):** THEORY-LEVEL · **Row count:** 15 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Truth, Evidence, Belief, Knowledge, Determination, Decision, OperationalReality` · **Aliases:** `the boundary of truth (Step 192)`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 191's closing research program, taken up in Step 192: formally distinguishing what KnowledgeOS means by true/valid/known/accepted via a truth/knowledge lattice, tested against five named hard cases of divergence between truth, belief, support, determination, and operational reality."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1394 §"What exactly does KnowledgeOS mean when it says that something is 'true', 'valid', 'known', or 'accepted'? ... derive a formal truth/knowledge lattice"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1395 §"Decision = g(Determinations,Evidence,Rules,Objectives,Authority). But: Decision \neq Truth."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1395. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1395 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1395 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1395 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1395 |
| examples | PRESENT | S1395 |
| warnings | PRESENT | S1395 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S1394 |

## Rationale
Because Truth(p,t) is generally unobservable (reality exists independently of observation; the system only observes O(p,t)), the architecture should never claim to directly store objective truth -- only observations, evidence, and assessments. [S1395] Strong architectural recommendation: never create a generic Knowledge{statement, truth:bool} object (semantically dangerous); use explicit, separately-related concepts Proposition/Observation/Evidence/Assessment/Determination/Decision instead, connected by a richer typed graph (Proposition branching to Evidence/Assessment/Determination, which branches to Decision, etc.). [S1395] Chapter 1-4 synthesis restating the four Gita-derived architectural distinctions established across recent steps (Conflict!=Error, State!=Lineage, Knowledge!=Action, CurrentKnowledge does not imply CompleteHistory), explicitly framed as a lens for discovering semantic distinctions, never a source of software requirements. [S1395] States the strongest single result of Step 192: KnowledgeOS should model the epistemic relationship to propositions (Observation->Evidence->Assessment->Knowledge) rather than Truth itself as a system property, while preserving Knowledge != Reality. [S1395] Step 192 verdict: the nine-category chain (Reality->...->Outcome) are different semantic categories, not statuses of one object; the most important insight is that the system does not need to know truth in order to govern knowledge about truth -- the stated foundation for a serious epistemic engineering architecture. [S1395]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1394] types=[FUTURE-RESEARCH, OPEN-QUESTION] scope=THEORY-LEVEL — "Opens Step 192: having separated Technical->Semantic->Epistemic->Governance->Operational, the next problem is to formally distinguish Truth, Evidence, Belief, Knowledge, Determination, Decision, and OperationalReality via a formal truth/knowledge lattice, tested against five hard cases (objectively true but unknown; believed but false; supported but later refuted; officially determined but objectively wrong; operationally true but not yet recorded)." (anchor: "What exactly does KnowledgeOS mean when it says that something is 'true', 'valid', 'known', or 'accepted'? ... derive a formal truth/knowledge lattice")
- [S1395] types=[WARNING, DISTINCTION] scope=THEORY-LEVEL — "Names the dangerous semantic collapse Truth=Evidence=Knowledge=Decision as one of the most dangerous a KnowledgeOS-like system can make; poses six distinct questions about a proposition p (actually true? believed? evidenced? accepted as knowledge? formally determined? decided upon?) that must not be represented by one Boolean field." (anchor: "Truth = Evidence = Knowledge = Decision. They are not the same thing.")
- [S1395] types=[DEFINITION, DISTINCTION] scope=THEORY-LEVEL — "Formal separation of six distinct functions -- Truth(p,t), Evidence(p,E,t), Belief(a,p,t), Knowledge(p,t), Determination(p,t), Decision(d,t) -- declared an architectural requirement, not merely philosophical elegance." (anchor: "Truth\neq Belief\neq Evidence\neq Knowledge\neq Determination\neq Decision.")
- [S1395] types=[PRINCIPLE, ARGUMENT] scope=THEORY-LEVEL — "Because Truth(p,t) is generally unobservable (reality exists independently of observation; the system only observes O(p,t)), the architecture should never claim to directly store objective truth -- only observations, evidence, and assessments." (anchor: "the architecture should generally not pretend that it directly stores objective truth. Instead it stores: Observations ... Evidence ... Assessments.")
- [S1395] types=[PRINCIPLE] scope=THEORY-LEVEL — "Epistemic asymmetry: Reality->Observation->Knowledge is implementable, but Knowledge->Reality is not (the reverse is inference); therefore Knowledge is a model of reality, not reality itself." (anchor: "Knowledge is a model of reality, not reality itself.")
- [S1395] types=[INVARIANT, DISTINCTION] scope=THEORY-LEVEL — "Formalizes truth as a latent binary T_p; the system computes only P(T_p=1|E,M,A) (e.g. 0.97), which is a posterior belief given evidence and model, not a claim that T_p=0.97 -- uncertainty about truth is not itself probabilistic truth." (anchor: "Uncertainty about truth \neq truth itself.")
- [S1395] types=[INVARIANT, DEFINITION] scope=OBJECT — "Defines B_a(p,t) (actor a believes p at t); B_a(p)=1 while Truth(p)=0 is possible, so belief does not imply truth -- obvious philosophically but architecturally important. Organizational belief B_org(p) is further distinguished as not merely the sum of individual beliefs, but arising from review/consensus/policy/acceptance/governance, requiring its own domain semantics." (anchor: "Belief does not imply truth.")
- [S1395] types=[INVARIANT, EXAMPLE] scope=OBJECT — "A Determination D_authority(p,t) (an authorized authority establishing p under organizational rules) is stronger than evidence support but still does not imply Truth(p), because governance procedures can be wrong; the correct representation when later evidence refutes a determination is Determined(p,t1) followed by Refuted(p,t2), never a silent rewrite of history." (anchor: "Determined(p) \not\Rightarrow Truth(p). ... We must not rewrite history.")
- [S1395] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — "A Decision(d) is an action-selection state defined as g(Determinations,Evidence,Rules,Objectives,Authority); it remains categorically distinct from Truth." (anchor: "Decision = g(Determinations,Evidence,Rules,Objectives,Authority). But: Decision \neq Truth.")
- [S1395] types=[DEFINITION, RESTATEMENT] scope=THEORY-LEVEL — "Presents the complete semantic separation table mapping seven categories to their meaning: Truth (state of reality), Evidence (observational basis), Belief (actor's epistemic position), Knowledge (accepted epistemic state), Determination (authorized establishment), Decision (governed choice of action), Outcome (result in reality) -- called one of the strongest conceptual tables derived so far." (anchor: "Truth: state of reality ... Evidence: observational basis ... Decision: governed choice of action ... Outcome: result in reality.")
- [S1395] types=[FORMALIZATION] scope=THEORY-LEVEL — "States the full causal chain across all nine categories, explicitly noting the arrows are transformations/epistemic relations, not equivalences." (anchor: "Reality \rightarrow Observation \rightarrow Evidence \rightarrow Inference \rightarrow Knowledge \rightarrow Determination \rightarrow Decision \rightarrow Action \rightarrow Outcome \rightarrow Reali…")
- [S1395] types=[CONSTRAINT, ARGUMENT] scope=THEORY-LEVEL — "Strong architectural recommendation: never create a generic Knowledge{statement, truth:bool} object (semantically dangerous); use explicit, separately-related concepts Proposition/Observation/Evidence/Assessment/Determination/Decision instead, connected by a richer typed graph (Proposition branching to Evidence/Assessment/Determination, which branches to Decision, etc.)." (anchor: "Do not create a generic Knowledge object like: Knowledge { statement, truth: true }. ... Instead use explicit concepts")
- [S1395] types=[ANALYSIS, RESTATEMENT] scope=THEORY-LEVEL — "Chapter 1-4 synthesis restating the four Gita-derived architectural distinctions established across recent steps (Conflict!=Error, State!=Lineage, Knowledge!=Action, CurrentKnowledge does not imply CompleteHistory), explicitly framed as a lens for discovering semantic distinctions, never a source of software requirements." (anchor: "Conflict\neqError. ... State\neqLineage. ... Knowledge\neqAction. ... CurrentKnowledge\nRightarrowCompleteHistory.")
- [S1395] types=[ARGUMENT, RESTATEMENT] scope=THEORY-LEVEL — "States the strongest single result of Step 192: KnowledgeOS should model the epistemic relationship to propositions (Observation->Evidence->Assessment->Knowledge) rather than Truth itself as a system property, while preserving Knowledge != Reality." (anchor: "KnowledgeOS should not model Truth directly as a simple system property. It should model the epistemic relationship to propositions.")
- [S1395] types=[RESTATEMENT, ARGUMENT] scope=THEORY-LEVEL — "Step 192 verdict: the nine-category chain (Reality->...->Outcome) are different semantic categories, not statuses of one object; the most important insight is that the system does not need to know truth in order to govern knowledge about truth -- the stated foundation for a serious epistemic engineering architecture." (anchor: "The system does not need to know truth in order to govern knowledge about truth.")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
