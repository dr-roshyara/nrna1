# next-epistemic-act-function-and-gap-classification

**Scope(s):** THEORY-LEVEL · **Row count:** 7 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Gap != GapType, KR-EPISTEMIC-AGENCY-2026-09, NextEpistemicAct:(K_t,Q_t,Delta_t,A,C,EC)->a_t · **Aliases:** The metro/Urdu epistemic-agency example
**Candidate group membership (NOT an identity claim):**
- G0851: links `next-epistemic-act-function-and-gap-classification` with `kr-epistemic-agency-2026-09-formal-specification` — labels share the notation 'KR-EPISTEMIC-AGENCY-2026-09'

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0068, scope THEORY-LEVEL): A human anecdote (being asked 'Do you speak Urdu?' then asked for metro directions) formalized into a candidate 'epistemic agency' layer: observation->interpretation->intent hypothesis->clarification->inquiry->gap detection->gap classification->investigation selection->evidence->determination->action. Proposes NextEpistemicAct as a candidate function selecting the next epistemically useful operation, and reinterprets Lord/Sarathi as generate-candidate-actions vs select-among-them functions. Distinct from the older, unrelated 'inv-kos-agent-001-epistemic-agency' (B0006, a constitutional-invariant register row named Epistemic Agency); this is a candidate architecture/function, not a register row, so kept as a separate object though thematically adjacent.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2833] §"How does an epistemic system know what to do when the original request is not itself the real task? ... The inquiry was not completely known at the beginning."
- CANDIDATE-CONCEPTUAL-BIRTH: [S2833] §"NextEpistemicAct: (K_t,Q_t,Delta_t,A,C,EC) -> a_t ... This is a major capability."
- CANDIDATE-FORMAL-BIRTH: [S2833] §"K_t |=? Q ... No. So Zero(K_t,Q) != 0 ... You have discovered an epistemic gap. But then something interesting happens. You don't simply say "I don't know." You begin an epistemic search strategy."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2833. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction evidence recorded. The ACTIVE label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S2833 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2833 |
| type_signature | PRESENT | S2833 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2833 |
| examples | PRESENT | S2833 |
| warnings | PRESENT | S2833 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S2833] (EXAMPLE/ARGUMENT) Uses a worked human example (a man asks 'Do you speak Urdu?', is answered 'How can I help?', then reveals he needs metro directions) to argue the real inquiry Q is not always known at the outset; formalizes the sequence Observation O1 -> intent hypotheses H1 -> clarifying question -> the actual inquiry Q emerging only after interaction.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S2833]` types=[EXAMPLE/ARGUMENT] scope=THEORY-LEVEL — "Uses a worked human example (a man asks 'Do you speak Urdu?', is answered 'How can I help?', then reveals he needs metro directions) to argue the real inquiry Q is not always known at the outset; formalizes the sequence Observation O1 -> intent hypotheses H1 -> clarifying question -> the actual inquiry Q emerging only after interaction." (anchor: "How does an epistemic system know what to do when the original request is not itself the real task? ... The inquiry was not completely known at the beginning.")
- `[S2833]` types=[FORMALIZATION/EXAMPLE] scope=THEORY-LEVEL — "Formalizes gap discovery (K_t does not entail Q, so Zero(K_t,Q)!=0, i.e. Delta_Q(K_t)!=empty) as triggering not a bare 'I don't know' but an active epistemic search strategy considering available resources (map, search, asking someone, information display), i.e. a policy for reducing the gap." (anchor: "K_t |=? Q ... No. So Zero(K_t,Q) != 0 ... You have discovered an epistemic gap. But then something interesting happens. You don't simply say "I don't know." You begin an epistemic search strategy.")
- `[S2833]` types=[FORMALIZATION/CONCEPT] scope=THEORY-LEVEL — "Proposes 'Epistemic Agency' as a missing KnowledgeOS layer answering 'how does the system decide what epistemic activity to perform next', formalized as NextEpistemicAct:(K_t,Q_t,Delta_t,Resources,Constraints,EpistemicStandard)->a_t, where a_t in {answer directly, ask clarification, search, inspect map, consult another source, compare hypotheses, gather observation, defer, report uncertainty, escalate, act}." (anchor: "NextEpistemicAct: (K_t,Q_t,Delta_t,A,C,EC) -> a_t ... This is a major capability.")
- `[S2833]` types=[PRINCIPLE/WARNING] scope=THEORY-LEVEL — "Argues an interpretation (e.g. 'he needs help') that could be wrong must be tracked as a hypothesis with explicit confidence rather than silently converted into an established fact; the epistemic state should preserve observation, hypothesis, confidence, action, and how the hypothesis was later confirmed/refined." (anchor: "Observation: Person asked "Do you speak Urdu?" Hypothesis: Person may need assistance. Confidence: uncertain ... KnowledgeOS therefore should not silently convert interpretation into fact.")
- `[S2833]` types=[EXTENSION/EXAMPLE] scope=THEORY-LEVEL — "Reinterprets the earlier Lord/Sarathi research not as 'Lord = God-like kernel' but as two functions: Lord(K_t,Delta,Q,Resources)->CandidateActions (generate possible epistemic moves) and Sarathi(K_t,Delta,CandidateActions,Cost,Risk,Context)->a_t (select among those moves), explicitly tagged [PROP], not to be put into Theory v1.2." (anchor: "Lord-like function: Generate possible epistemic moves ... Sarathi-like function: Select/navigate among those possible moves.")
- `[S2833]` types=[DISTINCTION] scope=THEORY-LEVEL — "Distinguishes semantic uncertainty (why did he ask that question -- requires clarification) from epistemic uncertainty (which direction is correct -- requires search/observation/consultation), giving Gap != GapType: the system needs to know what kind of gap it has before choosing an intervention, and notes a third possible gap where the inquiry itself is underspecified (Gap_semantic could precede Gap_epistemic)." (anchor: "Semantic uncertainty ... Uncertainty(Intent) ... Epistemic uncertainty ... Uncertainty(Answer) ... Gap != GapType.")
- `[S2833]` types=[PRINCIPLE] scope=THEORY-LEVEL — "States a candidate stronger definition of KnowledgeOS's purpose: the system should not merely answer inquiries but must manage the process of becoming able to answer them, extracted from seven human-behaviour-to-hypothesis mappings (interpretation separate from observation; intent as hypothesis until supported; interactive inquiry elicitation; required gap detection; gap-reduction investigation actions; epistemic action selection; determination preceding response) and recommends KR-EPISTEMIC-AGENCY-2026-09 as the next research artifact." (anchor: "KnowledgeOS should not merely answer inquiries; it must manage the process of becoming able to answer them.")

## Notes for P3
- No unusual internal tension observed across this label's 7 captured row(s); evidentiary base is proportionate to row count.
