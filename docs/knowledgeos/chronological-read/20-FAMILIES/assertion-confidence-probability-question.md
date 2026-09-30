# assertion-confidence-probability-question

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** conf(P) in [0,1] · **Aliases:** EXP-7, MT-2
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0041`, scope `OBJECT`: Whether per-assertion 'confidence' in [0,1] as used in the corpus is a genuine probability; executed tests (EXP-7, MT-1/MT-2) show two mutually exclusive per-dimension confidences can sum above 1, demonstrating it is not a probability absent a declared shared (Omega,F,P).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1677] §"'confidence in [0,1]' is NOT a probability unless a common (Omega,F,P) is declared. ... Therefore 'uncertainty' is currently a NUMBER, not a MEASUREMENT."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1705. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S1705), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1677 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1677, S1705 |
| assumptions | PRESENT | S1677 |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1677, S1705 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S1677] (EXPERIMENTAL-RESULT/ARGUMENT) EXP-7 shows two mutually exclusive per-assertion 'confidence' values summing to more than 1 (0.8+0.7=1.5), which is impossible for probabilities on a shared disjoint sample space, demonstrating that per-assertion confidence in [0,1] is not a probability unless a common (Omega,F,P) is declared; the corpus declares such a triple only in the abandoned measure-theory regime (25 Aug), never for Sigma or assertion confidence.

## Assumption register
| Statement | Stated | Source ID | Anchor |
|---|---|---|---|
| if two confidence values are probabilities of mutually exclusive events on a shared sample space, they cannot sum to more than 1 | EXPLICIT | S1677 | "If these were probabilities on a common (Omega,F,P) ... the sum could not exceed 1." |

## All rows (source_id order)
- [S1677] types=[EXPERIMENTAL-RESULT, ARGUMENT] scope=OBJECT — "EXP-7 shows two mutually exclusive per-assertion 'confidence' values summing to more than 1 (0.8+0.7=1.5), which is impossible for probabilities on a shared disjoint sample space, demonstrating that per-assertion confidence in [0,1] is not a probability unless a common (Omega,F,P) is declared; the corpus declares such a triple only in the abandoned measure-theory regime (25 Aug), never for Sigma or assertion confidence." (anchor: "'confidence in [0,1]' is NOT a probability unless a common (Omega,F,P) is declared. ... Therefore 'uncertainty' is currently a NUMBER, not a MEASUREMENT.")
- [S1705] types=[EXPERIMENTAL-RESULT, CORRECTION] scope=OBJECT — "Finding MT-1/MT-2: the corpus itself states (Step 264 §264.17, Step 266 §266.25) that no probability space (Omega,F,P) is established anywhere; the only such space existed in the abandoned 2026-08-25 measure-theory regime; executing the consequence shows two mutually exclusive per-dimension confidences (0.8 and 0.7) can legally sum to 1.5, and no theory invariant or running-system constraint forbids this, reframing 'uncertainty != probability' from a stated caution into a live, uncontrolled defect." (anchor: "The corpus knows it has no (Omega,F,P). ... 'uncertainty != probability' is not a caution to be repeated; it is a LIVE DEFECT: the model admits incoherent belief assignments over mutually exclusive values of the same dimension, and no invariant rules them out.")

## Notes for P3
- Agent observation: this label has only 2 recorded row(s); evidence base is thin and the classification above should be read as provisional pending further capture.
