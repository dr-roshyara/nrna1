# semantic-significance-function

**Scope(s):** THEORY-LEVEL · **Row count:** 8 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Classify(m,C,R), D={Identity,Meaning,EpistemicStatus,Authority,Decision,Obligation,Lifecycle,Lineage}, SemanticBoundary(M,C,R), Sigma(m) · **Aliases:** semantic significance function
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0034 · scope THEORY-LEVEL — "Step 191's core formalization: a mutation is semantically significant iff it changes a protected domain dimension in D; includes the Classify function (with Unknown as a legitimate outcome), the change-classification state machine, SemanticBoundary, and the summary M->Classify->T->Validate->S_{t+1} pipeline."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1394 §"SemanticTransition(m) \iff \Sigma(m)=1. ... \Delta D(m)\neq0."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1394 §"SemanticTransition(m) \iff \Sigma(m)=1. ... \Delta D(m)\neq0."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1394. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded. Since no retraction/supersession/contradiction evidence is present, this lifecycle label is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1394 |
| informal_meaning | PRESENT | S1394 |
| formal_definition | PRESENT | S1394 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1394 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1394 |
| examples | PRESENT | S1394 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- (ARGUMENT/CONSTRAINT) Architectural filter: places a 'Semantic Filter' in front of governance/evidence machinery so that technical mutations get normal logging while semantic transitions get governed lineage plus witness; the constitutional machinery must not observe every byte-level mutation. [S1394]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1394] types=[FORMALIZATION, DEFINITION] scope=THEORY-LEVEL — "Defines the semantic significance function Sigma(m): a mutation is semantically significant (Sigma=1) iff it changes at least one protected domain dimension in D={Identity,Meaning,EpistemicStatus,Authority,Decision,Obligation,Lifecycle,Lineage}, i.e. Delta D(m)!=0; worked cases: database index update (Sigma=0), evidence added (Sigma=1), authority revoked (Sigma=1), AI-generated internal token (Sigma=0), AI-generated candidate proposition (Sigma=1)." (anchor: "SemanticTransition(m) \iff \Sigma(m)=1. ... \Delta D(m)\neq0.")
- [S1394] types=[ARGUMENT, CONSTRAINT] scope=THEORY-LEVEL — "Architectural filter: places a 'Semantic Filter' in front of governance/evidence machinery so that technical mutations get normal logging while semantic transitions get governed lineage plus witness; the constitutional machinery must not observe every byte-level mutation." (anchor: "The constitutional machinery should not observe every byte-level mutation. It should observe the mutations that carry domain meaning.")
- [S1394] types=[FORMALIZATION, PRINCIPLE] scope=THEORY-LEVEL — "The classification function must include Unknown as a legitimate third outcome (not just Technical/Semantic), because forcing every mutation into a binary classification creates false certainty; worked example: an AI edit to an ADR file may be Unknown until assessed, and the correct next step is Unknown->Assessment, not inventing a meaning." (anchor: "Classify(m,C,R) \rightarrow \{Technical,Semantic,Unknown\}.")
- [S1394] types=[DEFINITION, DISTINCTION] scope=OBJECT — "Introduces a change-classification state machine (Detected/Classified/Unclassified/Semantic/Technical/Rejected) explicitly kept separate from KnowledgeState -- a classification decision about a mutation is not itself a knowledge state." (anchor: "C=\{Detected,Classified,Unclassified,Semantic,Technical,Rejected\}. ... ClassificationState ... KnowledgeState. They are separate.")
- [S1394] types=[FORMALIZATION] scope=THEORY-LEVEL — "Defines SemanticBoundary(M,C,R) determining whether a mutation crosses the domain boundary: Technical mutations get ordinary handling; Semantic mutations require Witness+Lineage+ApplicableRule and possibly Authority." (anchor: "SemanticBoundary(M,C,R) ... M\in Semantic requires Witness+Lineage+ApplicableRule and possibly Authority.")
- [S1394] types=[INVARIANT, DISTINCTION] scope=THEORY-LEVEL — "Not every semantic transition requires human authority: an observation like 'Nexus responded with HTTP 200' is semantically meaningful but does not require approval; instead SemanticTransition->TransitionType->AuthorityRequirement, keeping the model from becoming bureaucratic." (anchor: "Semantic \not\Rightarrow GovernanceApproval.")
- [S1394] types=[EXTENSION, EXAMPLE] scope=THEORY-LEVEL — "Worked transition-policy matrix over eight transition kinds (cache update, system observation, evidence registration, AI recommendation, determination, governance approval, deployment, log rotation) crossing Semantic/Authority/Evidence/Witness columns, explicitly flagged as needing later derivation from the real KnowledgeOS governance model." (anchor: "| Transition | Semantic? | Authority? | Evidence? | Witness? |")
- [S1394] types=[FORMALIZATION] scope=THEORY-LEVEL — "Summarizes the transition model: a mutation M is classified into T in {Technical,Observational,Epistemic,Governance,Operational}, and for semantic transitions Validate(T)=Preconditions AND Rule AND Witness AND AuthorityRequirement before producing S_{t+1}." (anchor: "M \xrightarrow{Classify(C,R)} T \xrightarrow{Validate(T)} S_{t+1}")

## Notes for P3
(Own observation.) This label is single-source (all 8 rows from S1394) but internally unusually coherent for a single pass: a definition (Sigma(m)), a three-valued classification function with an explicit Unknown outcome, a state machine kept deliberately separate from KnowledgeState, a semantic-vs-authority non-implication result, and a summary pipeline all fit together without contradiction. No cross-checking pass by a second source appears in this label's own evidence, though, so its formal apparatus has not been independently re-derived elsewhere in the corpus (as far as this label's own rows show). Separate observation: this label's notation reuses the symbol Sigma for "semantic significance function," while other labels in the corpus (e.g. epistemic-status work) use Sigma for unrelated purposes (epistemic-status/support-vector notions) — this looks like the same corpus-wide symbol-collision pattern the normalization pass already flags for `K`/`K_t` (see G0759 in `_LABEL-NORMALIZATION.md`); P3 may want to check whether Sigma needs the same collision-fingerprint treatment.
