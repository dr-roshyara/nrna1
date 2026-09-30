# state-history-distinction

**Scope(s):** OBJECT · **Row count:** 4 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** H_t=(e_1,...,e_n), K_t != H_t, K_t=State(H_t) · **Aliases:** state vs history

**Candidate group membership (NOT an identity claim):**
- **G0598** [`situation-state-history-falsification-mandate` · `state-history-distinction`] — explicit agent-stated uncertainty: 'situation-state-history-falsification-mandate' POSSIBLY relates to 'state-history-distinction' (batch B0061). Note: Mandatory first investigation: do not translate Situation=K_t without qualification; construct and falsification-test a three-layer candidate History->Situation->State-projection model against whether KnowledgeOS already contains distinct concepts for operation history, event history, state, knowledge state, provenance, identity, and observation.
- **G0601** [`situation-state-refutation-result` · `state-history-distinction`] — explicit agent-stated uncertainty: 'situation-state-refutation-result' POSSIBLY relates to 'state-history-distinction' (batch B0061). Note: Executed result (A1): two distinct action histories (turn_on) and (turn_on,turn_off,turn_on) under a toy successor-state theory produce an IDENTICAL state {F} while remaining distinct situations, forcing Situation != State; independently corroborated by a PRIOR KnowledgeOS-native experiment KR-HISTORY-2026-09-02 (referenced as already having shown K_t/A_t-identical, History-distinct pairs before this Reiter material was read), so the finding is labeled KnowledgeOS-DERIVED, CORROBORATED BY REITER, not Reiter-derived.

## Sources (how this label entered the ledger)

- **OBJECT-INDEX**, batch B0061, scope OBJECT: Candidate correction to the extraction's stronger claim K_t=history: retains the situation-calculus insight that a situation is a sequence of actions, but derives only K_t=State(H_t) (state as a function of history) rather than identifying state with history, fitting existing state/event/operation/provenance identity distinctions.

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2526 §"A situation is not a snapshot of the world. It is a finite sequence of actions. ... K_t=history. I would not adopt that. ... State \neq History ... H_t \xrightarrow{State} K_t rather than K_t=H_t"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2526 §"DK.1 State and History [DERIVED] ... DK.3 Successor-State Semantics [PROP — EXPERIMENT REQUIRED] ... DK.5 Executable History [PROP] ... DK.6 Transition Composition [DERIVED], semantics of choice and iteration remain [OPEN] ... DK.8 Epistemic Update [PROP] ... DK.9 Temporal and Concurrent Transitions [PROP — REQUIRES TIME/FRAME DECISION]"]

## Lifecycle

last_seen: S2549. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/contradiction rows found). This ACTIVE classification is a heuristic based on how recently (by source_id, last_seen=S2549) this label was last used in the captured contribution set, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S2526, S2549 |
| dependencies | PRESENT | S2549 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2526 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2549 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE

## Assumption register

NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S2526]` types=[CORRECTION, DISTINCTION] scope=OBJECT — "Rejects the extraction's claim K_t = history; retains only the weaker H_t --State--> K_t (state is a function of history), giving State != History, consistent with existing state/event/operation/provenance identity distinctions." (anchor: "A situation is not a snapshot of the world. It is a finite sequence of actions. ... K_t=history. I would not adopt that. ... State \neq History ... H_t \xrightarrow{State} K_t rather than K_t=H_t")
- `[S2526]` types=[GOVERNANCE, RESTATEMENT] scope=THEORY-LEVEL — "Proposes a 9-section 'KNOWLEDGEOS — DYNAMIC KNOWLEDGE AND TRANSITION SEMANTICS' research-draft addition (DK.1 State/History [DERIVED], DK.2 Transition Applicability [DERIVED], DK.3 Successor-State Semantics [PROP-EXPERIMENT REQUIRED], DK.4 Boundary/Persistence [DERIVED+PROP], DK.5 Executable History [PROP], DK.6 Transition Composition [DERIVED, choice/iteration OPEN], DK.7 Reasoning Over Transitions [DERIVED], DK.8 Epistemic Update [PROP], DK.9 Temporal/Concurrent Transitions [PROP-REQUIRES TIME/FRAME DECISION]), explicitly not yet called Theory v1.3, with per-section status tags distinct from the KR.1-KR.10 DL-thread tags in S2523/S2524." (anchor: "DK.1 State and History [DERIVED] ... DK.3 Successor-State Semantics [PROP — EXPERIMENT REQUIRED] ... DK.5 Executable History [PROP] ... DK.6 Transition Composition [DERIVED], semantics of choice and iteration remain [OPEN] ... DK.8 Epistemic Update [PROP] ... DK.9 Temporal and Concurrent Transitions [PROP — REQUIRES TIME/FRAME DECISION]")
- `[S2549]` types=[VALIDATION, GOVERNANCE] scope=CROSS-OBJECT — "Cites a specific PRIOR, independent KnowledgeOS experiment (KR-HISTORY-2026-09-02, test T2) that established History!=State on the corpus's own evidence before the Reiter material was read, finding no state-only function can reconstruct historical closure, 0 kernel reads of history (statically verified), and ClosureEvent-in-Kernel refuted; assigns the precise independence label KnowledgeOS-DERIVED/CORROBORATED-BY-REITER and explicitly warns 'this must not be inverted in later citation' -- Reiter supplies vocabulary and a proof architecture, not the discovery itself." (anchor: "KR-HISTORY-2026-09-02 (M-closure-event-kernel-vs-history.md) ran the decisive configuration before this source was read: K_t, K_{t+1} identical AND History_1 != History_2 ... T2 — can historical closure be reconstructed from state alone? No ... 0 reads, statically verified ... ClosureEvent in K REFUTED [NEG] ... Independence label: KnowledgeOS-DERIVED, CORROBORATED BY REITER. Not Reiter-derived.")
- `[S2549]` types=[EXPERIMENTAL-RESULT, LIMITATION] scope=OBJECT — "Reports a corpus keyword-file-count search (History:7, knowledge-state:2, provenance:32, identity:16, Observation:18, 'operation history':0, 'event history':0) showing the corpus has an attested History/State distinction but NO attested intermediate Situation level and no operation-history/event-history concepts at all, so importing Reiter's three-level History->Situation->State model would add an unattested level; recorded OPEN, not adopted." (anchor: "History | 7 ... knowledge state | 2 ... provenance | 32 ... identity | 16 ... Observation | 18 ... "operation history" | 0 ... "event history" | 0 ... The corpus distinguishes History from State. It has NO attested intermediate Situation level ... Recorded as OPEN, not adopted.")

## Notes for P3

No internal tension or unusual evidentiary pattern noticed while assembling this file; the rows are mutually consistent at the level this pass can check.
