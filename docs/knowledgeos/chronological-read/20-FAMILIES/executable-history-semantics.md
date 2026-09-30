# executable-history-semantics

**Scope(s):** OBJECT · **Row count:** 5 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Executable(h) iff forall e_i in h: Poss(e_i,K_i)` · **Aliases:** `executable transition/history`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0061, scope OBJECT): Candidate distinction between a formally-representable transition, a permitted transition, an actually-executable transition, and a transition that actually occurred, formalized via an executable-history predicate requiring every action in the history to satisfy Poss at its point of occurrence.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2526] §"an executable history as one where every action occurring in the history satisfied its preconditions ... Executable(h)\iff\forall e_i\in h: Poss(e_i,K_i) ... a state transition that is formally representable / permitted / actually executable / actually occurred. These must not be collapsed."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2526] §"an executable history as one where every action occurring in the history satisfied its preconditions ... Executable(h)\iff\forall e_i\in h: Poss(e_i,K_i) ... a state transition that is formally representable / permitted / actually executable / actually occurred. These must not be collapsed."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2526] §"DK.1 State and History [DERIVED] ... DK.3 Successor-State Semantics [PROP — EXPERIMENT REQUIRED] ... DK.5 Executable History [PROP] ... DK.6 Transition Composition [DERIVED], semantics of choice and iteration remain [OPEN] ... DK.8 Epistemic Update [PROP] ... DK.9 Temporal and Concurrent Transitions [PROP — REQUIRES TIME/FRAME DECISION]"

## Lifecycle
last_seen: S2544. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2526, S2536 |
| type_signature | PRESENT | S2526 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S2527 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2526, S2527 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2544 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2526] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — "History != ExecutableHistory: candidate Executable(h) iff for all e_i in h, Poss(e_i,K_i); introduces the explicit four-way distinction between formally-representable, permitted, actually-executable, and actually-occurred transitions, which must not be collapsed — proposed as a new research item 'Executable Transition/History Semantics' to strengthen delta." (anchor: "an executable history as one where every action occurring in the history satisfied its preconditions ... Executable(h)\iff\forall e_i\in h: Poss(e_i,K_i) ... a state transition that is formally representable / permitted / actually executable / actually occurred. These must not be collapsed.")
- [S2526] types=[GOVERNANCE, RESTATEMENT] scope=THEORY-LEVEL — "Proposes a 9-section 'KNOWLEDGEOS — DYNAMIC KNOWLEDGE AND TRANSITION SEMANTICS' research-draft addition (DK.1 State/History [DERIVED], DK.2 Transition Applicability [DERIVED], DK.3 Successor-State Semantics [PROP-EXPERIMENT REQUIRED], DK.4 Boundary/Persistence [DERIVED+PROP], DK.5 Executable History [PROP], DK.6 Transition Composition [DERIVED, choice/iteration OPEN], DK.7 Reasoning Over Transitions [DERIVED], DK.8 Epistemic Update [PROP], DK.9 Temporal/Concurrent Transitions [PROP-REQUIRES TIME/FRAME DECISION]), explicitly not yet called Theory v1.3, with per-section status tags distinct from the KR.1-KR.10 DL-thread tags in S2523/S2524." (anchor: "DK.1 State and History [DERIVED] ... DK.3 Successor-State Semantics [PROP — EXPERIMENT REQUIRED] ... DK.5 Executable History [PROP] ... DK.6 Transition Composition [DERIVED], semantics of choice and iteration remain [OPEN] ... DK.8 Epistemic Update [PROP] ... DK.9 Temporal and Concurrent Transitions [PROP — REQUIRES TIME/FRAME DECISION]")
- [S2527] types=[EXTENSION, DISTINCTION] scope=OBJECT — "Extends the prior three-way (representable/permitted/executable/occurred) distinction into a four-row table: History (any event sequence, representable), Executable History (all preconditions satisfied, [PROP]), Actual History (what actually happened, requires observation), Legal History (what was authorized, requires governance) -- adding 'Legal History' as a new, governance-linked category not present in S2526." (anchor: "History | Any sequence of events | Can be represented ... Executable History | ... [PROP] ... Actual History | What actually happened | Requires observation ... Legal History | What was authorized | Requires governance")
- [S2536] types=[FORMALIZATION] scope=OBJECT — "Full foundational situation-calculus axioms: unique names for situations (do(a1,s1)=do(a2,s2) implies a1=a2 and s1=s2), a second-order induction axiom over situations (explaining why the system is not fully first-order decidable), and subhistory ordering axioms (no situation precedes S0; s precedes do(a,s') iff s precedes-or-equals s')." (anchor: "do(a_1,s_1)=do(a_2,s_2)\supset a_1=a_2\land s_1=s_2 ... Induction Axiom (Second-Order): (\forall P).P(S_0)\land(\forall a,s)[P(s)\supset P(do(a,s))]\supset(\forall s)P(s) ... \neg s\sqsubset S_0 ... s\sqsubset do(a,s')\equiv s\sqsubseteq s' ... The induction axiom is second-order, which is why the system is not fully first-order decidable.")
- [S2544] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Executed confirmation that a history is executable only if Poss holds at every prefix: entering a locked door directly is non-executable, while unlocking then entering is executable; Poss thus functions purely as a precondition gate on whether an action may occur, not as an effect or a knowledge-qualification test." (anchor: ""claim": "executable(s) requires Poss at every prefix", "blocked_situation": ["enter"], "blocked_executable": false, "unblocked_situation": ["unlock","enter"], "unblocked_executable": true, "precondition_is_a_gate_not_an_effect": true")

## Notes for P3
No internal tension observed across this label's own rows in this pass; nothing further flagged.
