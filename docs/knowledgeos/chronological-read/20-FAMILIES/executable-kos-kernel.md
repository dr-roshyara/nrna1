# executable-kos-kernel

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `O={assert,relate,withdraw,restatus}`, `T: K x Op -> K` · **Aliases:** `TG-4`, `exec/kos_kernel.py`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0041`, scope `OBJECT`: The first executable KnowledgeOS transformation kernel built in the entire research programme: a total, deterministic, replayable T over a four-operation set (assert, relate, withdraw, restatus) with cascading withdrawal over derives edges; verified to have no preconditions, no postconditions, and no invertibility (withdraw is destructive).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1706 §"A KnowledgeOS transformation CAN be made total, deterministic and replayable -- this is the first program in the research programme that does it. Five steps titled "executable"/"execute"/"simulation" ... contain no program (01-THEORY-EVOLUTION-MAP.md EV-F2). This is what those steps asserted; here it is, and it works, for a four-operation O."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S1706 §"A KnowledgeOS transformation CAN be made total, deterministic and replayable -- this is the first program in the research programme that does it. Five steps titled "executable"/"execute"/"simulation" ... contain no program (01-THEORY-EVOLUTION-MAP.md EV-F2). This is what those steps asserted; here it is, and it works, for a four-operation O."]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1706. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S1706 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S1706 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1706, S1706, S1706 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1706 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Findings TG-6/TG-7: the corpus specifies no preconditions, postconditions, or failure semantics for T (e.g. no answer for withdrawal removing an Accepted assertion, a cascade emptying K, or whether a rejected transformation is itself recorded), making T a partial function presented as total and preventing replay from reproducing histories containing refusals -- directly weakening the provenance/audit claims in the companion document 11-PROVENANCE-LINEAGE-HISTORY.md; separately, T is confirmed not invertible (withdraw is destructive), consistent with Step 266 §266.15's own 'no invertibility established' marking. [S1706]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1706] types=['EXPERIMENTAL-RESULT', 'CORRECTION', 'IMPLEMENTATION'] scope=OBJECT — "Finding TG-4: exec/kos_kernel.py implements T:K x Op -> K over a four-operation set (assert, relate, withdraw, restatus) with cascading withdrawal over derives edges and replay as a fold; verified properties are total (raises only on unknown op name), deterministic (pure functions over frozen sets), replay-equals-fold-of-T by construction, and terminating (withdraw fixpoints in at most |A| iterations); explicitly framed as fulfilling, for the first time in the research programme, what five prior steps titled executable/execute/simulation (025a-4, 025a-5, 025b, 051, 056) only claimed to do without containing an actual program (per 01-THEORY-EVOLUTION-MAP.md finding EV-F2)." (anchor: "A KnowledgeOS transformation CAN be made total, deterministic and replayable -- this is the first program in the research programme that does it. Five steps titled "executable"/"execute"/"simulation" ... contain no program (01-THEORY-EVOLUTION-MAP.md EV-F2). This is what those steps asserted; here it is, and it works, for a four-operation O.")
- [S1706] types=['LIMITATION', 'CORRECTION'] scope=THEORY-LEVEL — "Finding TG-5: the executable kernel's good properties (TG-4) hold only relative to the specific four-operation set chosen for this session; every corpus property quantifying over the operation set (sufficiency Step 259, congruence Step 260.9, minimality Steps 260/266.19, state equality) is underdetermined until the set is fixed, and exp_congruence.py EXP-3 shows the sufficiency answer actually flips once an ordinary governance-plausible history-sensitive operation (ever_contested) is added, even though it does not appear in the chosen minimal or full operation sets tested." (anchor: "The transformation is well-behaved RELATIVE TO a four-operation set this session chose. Every corpus property that quantifies over T ... is UNDERDETERMINED until O is enumerated ... Add one history-sensitive operation ... and K=(A,R) stops being sufficient.")
- [S1706] types=['LIMITATION', 'ARGUMENT'] scope=OBJECT — "Findings TG-6/TG-7: the corpus specifies no preconditions, postconditions, or failure semantics for T (e.g. no answer for withdrawal removing an Accepted assertion, a cascade emptying K, or whether a rejected transformation is itself recorded), making T a partial function presented as total and preventing replay from reproducing histories containing refusals -- directly weakening the provenance/audit claims in the companion document 11-PROVENANCE-LINEAGE-HISTORY.md; separately, T is confirmed not invertible (withdraw is destructive), consistent with Step 266 §266.15's own 'no invertibility established' marking." (anchor: "Without failure semantics, T is a PARTIAL FUNCTION PRESENTED AS TOTAL, and replay cannot reproduce histories containing refusals. This directly weakens the provenance/audit claims in 11-PROVENANCE-LINEAGE-HISTORY.md.")

## Notes for P3
- Thin evidence base (n=3 row(s)) — treat conclusions here as provisional.
