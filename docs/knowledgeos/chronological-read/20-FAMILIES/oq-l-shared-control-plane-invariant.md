# oq-l-shared-control-plane-invariant

**Scope(s):** THEORY-LEVEL · **Row count:** 5 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `OQ-L` · **Aliases:** `gate's verdict may not be issued by the party it constrains`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0003`, scope `THEORY-LEVEL`: Candidate shared invariant across C-5 and C-14; its definitional terms (party, issue, constrain, advisory/authoritative result, machine-generated result, independent ratification) are progressively defined across the batch but the invariant itself remains PROPOSED/OPEN.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0096 §"A party subject to a governed control may not be the sole authority for issuing, accepting, or finalizing the control's verdict about its own compliance, separation, or conformance."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0102 §"a compensating control is authorized ... must preserve the unresolved condition, the limitation, the authority granting the exception, the evidence supporting the exception, and the resulting assurance status."]
- CANDIDATE-FORMAL-BIRTH: [S0102 §"CONSTRAIN: a control constrains a party when the control's outcome conditions whether that party's act may proceed. MACHINE-GENERATED RESULT: an outcome produced by deterministic execution ... without a judgement by any party."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0102. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S0102 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0102 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0102, S0102 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0096 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
A unifying control-plane invariant candidate (proposed, not adopted): a gate's verdict may not be issued by the party the gate constrains; observed support: G-1 already enforces this for closure, and CAP-09's write-paths are deliberately closed, so the invariant is already implemented twice but never stated as a general rule. [S0102]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0096] types=['HYPOTHESIS', 'PRINCIPLE'] scope=THEORY-LEVEL — "A candidate stronger, still-PROPOSED shared control-plane invariant (feeding OQ-L): a party subject to a governed control may not be the sole authority for issuing, accepting, or finalizing that control's verdict about its own compliance, separation, or conformance." (anchor: "A party subject to a governed control may not be the sole authority for issuing, accepting, or finalizing the control's verdict about its own compliance, separation, or conformance.")
- [S0101] types=['LIMITATION'] scope=OBJECT — "Blocking finding F-2: two of six mandated OQ-L definitional terms (CONSTRAIN, MACHINE-GENERATED RESULT) remain undefined, and the second is load-bearing for the analysis's strongest candidate exception (deterministic automated checks with independent execution)." (anchor: "F-2 -- the mandated definitional set is NOT discharged. AMD2: The amended analysis must define: PARTY, ISSUE, CONSTRAIN, ADVISORY vs AUTHORITATIVE RESULT, MACHINE-GENERATED RESULT vs INDEPENDENT RATIFICATION. ... constrain and machine-generated result: not def…")
- [S0102] types=['HYPOTHESIS', 'ANALYSIS'] scope=CROSS-OBJECT — "A unifying control-plane invariant candidate (proposed, not adopted): a gate's verdict may not be issued by the party the gate constrains; observed support: G-1 already enforces this for closure, and CAP-09's write-paths are deliberately closed, so the invariant is already implemented twice but never stated as a general rule." (anchor: "a unifying invariant candidate falls out of it: both control-plane capabilities are defeated by the SAME failure -- self-assertion by the gated party. C-5 is defeated by self-declared separation; C-14 by self-declared compliance.")
- [S0102] types=['DEFINITION', 'FORMALIZATION'] scope=THEORY-LEVEL — "Correction #3 supplies the two missing OQ-L definitions mandated by Verification's F-2: CONSTRAIN (a relation between a control and the party whose act is conditioned, not a relation to whoever runs the check) and MACHINE-GENERATED RESULT (a deterministic, judgement-free outcome whose trustworthiness derives from its execution conditions, not from any party's identity or separation); states that mode-of-production, effect, and answerability are three orthogonal axes that must not be conflated." (anchor: "CONSTRAIN: a control constrains a party when the control's outcome conditions whether that party's act may proceed. MACHINE-GENERATED RESULT: an outcome produced by deterministic execution ... without a judgement by any party.")
- [S0102] types=['CONCEPT'] scope=THEORY-LEVEL — "A compensating-control concept for exceptions to a governed capability is introduced: where an exception is authorized, it must preserve the unresolved condition, its limitation, the authority granting it, the supporting evidence, and the resulting assurance status, rather than silently converting an unresolved status into a resolved one." (anchor: "a compensating control is authorized ... must preserve the unresolved condition, the limitation, the authority granting the exception, the evidence supporting the exception, and the resulting assurance status.")

## Notes for P3
- No unusual tensions or evidentiary anomalies were observed for this label within the captured rows.
