# v1-2-sim-evaluation-semantics-experiment-g-final

**Scope(s):** OBJECT · **Row count:** 5 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `A6`, `Eval_c primary object`, `KR-SIM-2026-09-02-G`, `N1-N10` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0770** [`knowledge-admission-ladder` · `v1-2-sim-evaluation-semantics-experiment-g-final`] — labels share the notation 'A6'

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0058`, scope `OBJECT`: The consolidated Sat_c/evaluation-semantics experiment (final, ID-collision-corrected as -G): establishes Eval_c(K,r,Gamma)->EVal_c as the primary object with Sat_c:=value-of-Eval_c a tested (not assumed) projection; finds the three-valued content evaluator not total (undefined on contradiction), and that value-of-Eval_c collapses nine semantically distinct evaluation situations into the single value U (A6, refuting the sufficiency of a three-valued codomain); retracts two prior invented evaluators (Eval_Gov, Eval_Time) as corpus-unsupported; runs eight falsification tests N1-N10, refuting eight of ten; and delivers the final SAT STATUS verdict 'SEMANTICALLY INCOHERENT' under the theory's currently-proposed model (not merely partially executable), because the content evaluator is not a function on contradictory states and the value projection destroys distinctions the theory's own Rule 3 requires.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2420 §"ID collision, reported not resolved. The commissioning document assigns KR-SIM-2026-09-02-E, which is already taken by the evaluator run. This experiment is recorded as -G. Provenance preserved; nothing renamed."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2420 §"ID collision, reported not resolved. The commissioning document assigns KR-SIM-2026-09-02-E, which is already taken by the evaluator run. This experiment is recorded as -G. Provenance preserved; nothing renamed."]

## Lifecycle
last_seen: S2420. Candidate lifecycle: **ACTIVE**. Evidence: no retraction/supersession/contradiction evidence recorded; the ACTIVE classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2420, S2420 |
| open_questions | PRESENT | S2420 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2420] types=['GOVERNANCE'] scope=METHODOLOGICAL — "Discloses and resolves (by disclosure, not silent renaming) an experiment-ID collision between the commissioning document's assigned ID and the already-used evaluator-run ID, preserving provenance." (anchor: "ID collision, reported not resolved. The commissioning document assigns KR-SIM-2026-09-02-E, which is already taken by the evaluator run. This experiment is recorded as -G. Provenance preserved; nothing renamed.")
- [S2420] types=['RETRACTION'] scope=OBJECT — "Formally retracts Eval_Gov and Eval_Time from the -E run as self-correction: both supplied semantics the corpus does not license, per Rule 1 (no invented semantics) and Rule A3/A4 of the commissioning protocol." (anchor: "Eval_Gov (authority table) -- the corpus defines no governance artifact ... Eval_Time (interval coverage) -- ... equated interval coverage with validity. ... This is a self-correction: -E supplied semantics the corpus does not license.")
- [S2420] types=['EXPERIMENTAL-RESULT'] scope=OBJECT — "A6 refutes N2 (three values sufficient): nine semantically distinct evaluation situations, spanning multiple theory statuses, collapse into the single projected value U, while top/bot/C/UNDEFINED each get exactly one situation." (anchor: "Distinct evaluation situations collapsing into each projected value: ... U | 9 ... Sat_c := value o Eval_c is a lossy projection, and the loss is concentrated exactly at U")
- [S2420] types=['GOVERNANCE', 'EXPERIMENTAL-RESULT'] scope=THEORY-LEVEL — "Delivers the final and strongest verdict of the whole Sat_c lane: SEMANTICALLY INCOHERENT, distinguished explicitly from the weaker 'partially executable' verdicts given elsewhere, because two specification-level defects (non-totality and lossy projection) are load-bearing." (anchor: "SAT STATUS: SEMANTICALLY INCOHERENT -- under the model the theory currently proposes. Not merely "partially executable": the three-valued content evaluator is not a function on contradictory states (N1), and the projection defining Sat destroys the distinction…")
- [S2420] types=['FUTURE-RESEARCH'] scope=METHODOLOGICAL — "Prescribes the Contr/fourth-value experiment (with the Zero readings extended in the same step) as the smallest next experiment, explicitly noting the stop condition that multiple semantic models remain equally supported and none should be guessed between." (anchor: "The Contr / fourth-value experiment -- with the Zero readings extended in the same step. ... D-0 shows that extending the codomain without extending the four Zero readings makes closure silently swallow the new value. Five OPEN items are one decision.")

## Notes for P3
- Completeness is sparse even relative to its row count (only 2/12 dimensions PRESENT) — most of this object's shape is NOT-EVIDENCED-IN-CAPTURE.
