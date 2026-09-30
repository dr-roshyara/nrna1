# situation-state-refutation-result

**Scope(s):** OBJECT · **Row count:** 5 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `P1: Situation=A_t REFUTED`, `collision_demonstrated=True` · **Aliases:** `A1 history/state collision result`
**Candidate group membership (NOT an identity claim):**
- **G0601** [`situation-state-refutation-result` · `state-history-distinction`] — explicit agent-stated uncertainty: 'situation-state-refutation-result' POSSIBLY relates to 'state-history-distinction' (batch B0061). Note: Executed result (A1): two distinct action histories (turn_on) and (turn_on,turn_off,turn_on) under a toy successor-state theory produce an IDENTICAL state {F} while remaining distinct situations, forcing Situation != State; independently corroborated by a PRIOR KnowledgeOS-native experiment KR-HISTORY-2026-09-02 (referenced as already having shown K_t/A_t-identical, History-distinct pairs before this Reiter material was read), so the finding is labeled KnowledgeOS-DERIVED, CORROBORATED BY REITER, not Reiter-derived.

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0061, scope OBJECT): Executed result (A1): two distinct action histories (turn_on) and (turn_on,turn_off,turn_on) under a toy successor-state theory produce an IDENTICAL state {F} while remaining distinct situations, forcing Situation != State; independently corroborated by a PRIOR KnowledgeOS-native experiment KR-HISTORY-2026-09-02 (referenced as already having shown K_t/A_t-identical, History-distinct pairs before this Reiter material was read), so the finding is labeled KnowledgeOS-DERIVED, CORROBORATED BY REITER, not Reiter-derived.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2545] §""situation_1": ["turn_on"], "situation_2": ["turn_on","turn_off","turn_on"], "state_1": ["F"], "state_2": ["F"], "states_identical": true, "situations_identical": false, "collision_demonstrated": true, "corollary": "no function of the STATE can recover the history -- so Situation != State is not a stipulation, it is forced""
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2549] §"KR-HISTORY-2026-09-02 (M-closure-event-kernel-vs-history.md) ran the decisive configuration before this source was read: K_t, K_{t+1} identical AND History_1 != History_2 ... T2 — can historical closure be reconstructed from state alone? No ... 0 reads, statically verified ... ClosureEvent in K REFUTED [NEG] ... Independence label: KnowledgeOS-DERIVED, CORROBORATED BY REITER. Not Reiter-derived."

## Lifecycle
last_seen: S2556. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S2545, S2546, S2549, S2556 |
| Dependencies | PRESENT | S2549 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | PRESENT | S2556 |
| Experiments | PRESENT | S2545, S2546 |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2545] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Executed counterexample: two distinct action histories collapse to the identical fluent state {F}, demonstrating that no state-valued function can recover the generating history, i.e. Situation != State is forced by construction rather than assumed." (anchor: ""situation_1": ["turn_on"], "situation_2": ["turn_on","turn_off","turn_on"], "state_1": ["F"], "state_2": ["F"], "states_identical": true, "situations_identical": false, "collision_demonstrated": true, "corollary": "no function of the STATE can recover the history -- so Situation != State is not a stipulation, it is forced"")
- [S2546] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Executed confirmation across two example pairs that observational equivalence (identical fluent state under any query) does not imply situation identity, since both pairs have differing history lengths despite matching states -- the machine-checked basis for naming a third, previously-unnamed equivalence level (observational equivalence) between syntactic identity and semantic equivalence." (anchor: ""claim": "observational equivalence does not imply situation identity", ... "all_obs_equivalent_but_distinct": true")
- [S2549] types=[CORRECTION, VALIDATION] scope=OBJECT — "Formally refutes P1 (Situation=KnowledgeOS state), noting the underlying Reiter extraction's own crosswalk table directly contradicts its own next sentence -- the exact 'correspondence != derivation' failure the mandate warns against." (anchor: "P1: Situation = KnowledgeOS state — REFUTED. And refuted by KnowledgeOS's own prior evidence, not by Reiter's authority. ... A source that maps Situation -> K_t two lines above stating that situations are not states has supplied a correspondence, not a derivation.")
- [S2549] types=[VALIDATION, GOVERNANCE] scope=CROSS-OBJECT — "Cites a specific PRIOR, independent KnowledgeOS experiment (KR-HISTORY-2026-09-02, test T2) that established History!=State on the corpus's own evidence before the Reiter material was read, finding no state-only function can reconstruct historical closure, 0 kernel reads of history (statically verified), and ClosureEvent-in-Kernel refuted; assigns the precise independence label KnowledgeOS-DERIVED/CORROBORATED-BY-REITER and explicitly warns 'this must not be inverted in later citation' -- Reiter supplies vocabulary and a proof architecture, not the discovery itself." (anchor: "KR-HISTORY-2026-09-02 (M-closure-event-kernel-vs-history.md) ran the decisive configuration before this source was read: K_t, K_{t+1} identical AND History_1 != History_2 ... T2 — can historical closure be reconstructed from state alone? No ... 0 reads, statically verified ... ClosureEvent in K REFUTED [NEG] ... Independence label: KnowledgeOS-DERIVED, CORROBORATED BY REITER. Not Reiter-derived.")
- [S2556] types=[GOVERNANCE, WARNING] scope=METHODOLOGICAL — "Issues an explicit forward-looking warning against misreporting the strongest finding's independence label (KnowledgeOS-DERIVED, CORROBORATED BY REITER for Situation!=State) if later citation inverts it; restates a named standing rule 'C-1': no mechanism enters the kernel merely because it is useful (useful is not irreducible), applied here to explicitly deny regression any kernel claim despite being the step's most transferable finding." (anchor: "every result in 02 carries an independence label. And the single strongest finding — Situation \neq State — is labelled KnowledgeOS-DERIVED, CORROBORATED BY REITER, because the corpus established it first, on its own evidence. If a later citation inverts that label, the finding will have been misreported. ... C-1 restated: No mechanism enters the kernel merely because KnowledgeOS can use it. Regression is useful. Useful is not irreducible. No kernel claim is made.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
