# gita-eight-primitives-state-vocabulary-not-equal-fields

**Scope(s):** OBJECT · **Row count:** 6 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0055`, scope `OBJECT`: The refined eight-primitive kernel-state notation (S2268), later self-corrected (artifact 31) for still being compositional (set/tuple) rather than a true extensional vocabulary.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2268 §"the eight primitives are the state vocabulary; they are not eight equal fields. The kernel is a stateful epistemic machine whose state is represented through these primitives and whose transformations are mediated by Buddhi ... K_t = <E_t,S_t,V_t,O_t,P_t,R_t,Pi_t,A_t>"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2268 §"Things being known: E,S,P ... Things that happen or are observed: V,O ... Things connecting or constraining: R,Pi ... Things the kernel may perform: A ... the kernel is not a flat eight-element tuple. It has an internal conceptual structure"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2271. Candidate lifecycle: **ACTIVE**. Evidence: no retraction/supersession/contradiction evidence recorded; the ACTIVE classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2268, S2268, S2268 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2268, S2268, S2268 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S2268 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2268] types=['DEFINITION', 'DISTINCTION'] scope=THEORY-LEVEL — "Core DDD distinction: the eight ratified primitives are the state vocabulary, not eight equal fields; the kernel is a stateful epistemic machine whose transformations are mediated by Buddhi. Kernel state notated K_t = <E_t,S_t,V_t,O_t,P_t,R_t,Pi_t,A_t> (using V for Event, Pi for Policy, to keep notation compact), with a Gita-lens correspondence table (Entity~Ksetra, State~avastha/condition, Event~karma/occurrence, Observation~perception/Sanjaya-like reporting, Proposition~jneya, Relation~sambandha, Policy~dharma, Action~karma/kriya)." (anchor: "the eight primitives are the state vocabulary; they are not eight equal fields. The kernel is a stateful epistemic machine whose state is represented through these primitives and whose transformations are mediated by Buddhi ... K_t = <E_t,S_t,V_t,O_t,P_t,R_t,P…")
- [S2268] types=['DISTINCTION', 'FORMALIZATION'] scope=THEORY-LEVEL — "Groups the eight primitives by DDD kind rather than treating them as a flat tuple: things being known (Entity, State, Proposition); things that happen or are observed (Event, Observation); things connecting or constraining (Relation, Policy); things the kernel may perform (Action) -- diagrammed as an epistemic-state vs operational-state internal split, explicitly still a conceptual model, not yet a canonical implementation schema." (anchor: "Things being known: E,S,P ... Things that happen or are observed: V,O ... Things connecting or constraining: R,Pi ... Things the kernel may perform: A ... the kernel is not a flat eight-element tuple. It has an internal conceptual structure")
- [S2268] types=['FORMALIZATION'] scope=THEORY-LEVEL — "Fundamental kernel cycle over the primitives: O_t -> B -> P_t -> R_t -> S_t -> B -> A_t -> V_{t+1} -> O_{t+1} (Observation -> Buddhi -> Proposition -> Relation/context -> State assessment -> Buddhi -> Action -> Event -> new Observation), described as a genuine epistemic feedback loop, with Buddhi invoked twice in one cycle (once for proposition formation, once for action selection)." (anchor: "O_t -> B -> P_t -> R_t -> S_t -> B -> A_t -> V_{t+1} -> O_{t+1} ... an epistemic feedback loop.")
- [S2268] types=['RESTATEMENT', 'FUTURE-RESEARCH'] scope=THEORY-LEVEL — "Closing hypothesis: KnowledgeOS Kernel = an epistemic state machine whose state is expressed through the eight primitives and whose transformations are governed by Buddhi, with the Gita lens supplying vocabulary/hypotheses while the mathematical/DDD lens must determine the actual operators, invariants, state transitions and algebra. Names the next formalization target: the complete Kernel Operator Algebra -- which operations Buddhi can perform on E,S,V,O,P,R,Pi,A, and how Sattva/Rajas/Tamas select or constrain those operators." (anchor: "KnowledgeOS Kernel = an epistemic state machine whose state is expressed through eight primitives and whose transformations are governed by Buddhi. The Gītā lens supplies the conceptual vocabulary and hypotheses; the mathematical/DDD lens must now determine th…")
- [S2270] types=['CORRECTION', 'LIMITATION'] scope=OBJECT — "Self-correcting notational audit: writing K_t as a set of the eight primitives still reads as 'the state IS these eight things' (compositional), which rule 258.35 forbids -- K must be defined extensionally through behavioural sufficiency, not compositionally through an arbitrary tuple. The eight primitives are the vocabulary in which state is expressed, not its components/fields. Notes the audited source itself says 'not eight equal fields' and then writes an 8-tuple anyway -- judged worse than the auditor's own earlier set notation, which was 'less wrong and still wrong.'" (anchor: "NOTATION CORRECTED by 31 §1. Written as a set, this still reads as 'the state IS these eight things' -- which is compositional, and 258.35 requires K be defined extensionally through behavioural sufficiency, not compositionally through an arbitrary tuple. The …")
- [S2271] types=['CORRECTION', 'VALIDATION'] scope=OBJECT — "Corrects S2268's own stated distinction against its own notation: it says 'the eight primitives are the state vocabulary, not eight equal fields' and then writes them as an 8-tuple K_t=<E_t,S_t,V_t,O_t,P_t,R_t,Pi_t,A_t>, which is field structure -- contradicting rule 258.35 (K must be defined extensionally through behavioural sufficiency, not compositionally through an arbitrary tuple). Also self-corrects the auditor's own prior artifact 30, which used set notation {Entity,State,...} -- better than a tuple (no ordering/field positions) but still compositional ('the state IS these eight things'). Final correction (V-Q1): the eight are the vocabulary in which state is expressed, not the state's components or fields; the source's instinct is right, its notation is wrong, and the auditor's own prior notation was less wrong but still wrong." (anchor: "the eight primitives are the state VOCABULARY; they are not eight equal 'fields.' ...and then writes them as an 8-tuple K_t = <E_t,S_t,V_t,O_t,P_t,R_t,Pi_t,A_t> ... A tuple IS field structure. The notation contradicts the caution one line above it. And 258.35 …")

## Notes for P3
- No unusual tensions or evidentiary anomalies were observed for this label within the captured rows.
