# construction-evidence-validation-role-separation

**Scope(s):** OBJECT · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `S1-F029 Candidate B` · **Aliases:** `evidence must not validate itself`
**Candidate group membership (NOT an identity claim):**
- **G0318** [`anti-reasoner-circularity-constraint` · `construction-evidence-validation-role-separation`] — explicit agent-stated uncertainty: 'construction-evidence-validation-role-separation' POSSIBLY relates to 'anti-reasoner-circularity-constraint' (batch B0031). Note: S1-F029's constraint that the same evidence item must not serve as both construction evidence and independent validation evidence for the same claim, to prevent circular self-validation; S2-R-F029 elevates it to an IMPLEMENTATION QUESTION (a declared role attribute per evidence reference plus an admission-time guard), identifying it as the anti-reasoner-circularity-constraint (S1-F025) recurring one layer down at the evidence level rather than the decision-procedure level.

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0031, scope OBJECT): S1-F029's constraint that the same evidence item must not serve as both construction evidence and independent validation evidence for the same claim, to prevent circular self-validation; S2-R-F029 elevates it to an IMPLEMENTATION QUESTION (a declared role attribute per evidence reference plus an admission-time guard), identifying it as the anti-reasoner-circularity-constraint (S1-F025) recurring one layer down at the evidence level rather than the decision-procedure level.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1287] §"Build what? An evidence reference that carries its role in the claim it supports -- construction versus independent validation -- so one item cannot silently occupy both... What problem does it solve? Circular self-validation... This is S1-F025's circularity at evidence level rather than procedure level -- the same defect one layer down... Verdict -> IMPLEMENTATION QUESTION. Fourth in the register, and the first whose missing fact is semantic rather than locational."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S1287] §"Build what? An evidence reference that carries its role in the claim it supports -- construction versus independent validation -- so one item cannot silently occupy both... What problem does it solve? Circular self-validation... This is S1-F025's circularity at evidence level rather than procedure level -- the same defect one layer down... Verdict -> IMPLEMENTATION QUESTION. Fourth in the register, and the first whose missing fact is semantic rather than locational."
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1287. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | PRESENT | S1287 |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | PRESENT | S1287 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1287] types=[IMPLEMENTATION, EXTENSION] scope=OBJECT — "Elevates "the same evidence must not serve as both construction evidence and independent validation evidence for a claim" to an IMPLEMENTATION QUESTION (fourth in the review series's register, and the first whose missing fact is semantic rather than locational): proposes a declared role attribute per evidence reference (construction vs independent validation) plus an admission-time guard refusing an item that appears in both roles for the same claim -- a one-attribute-plus-one-guard implementation, not a subsystem -- solving circular self-validation as the anti-reasoner circularity constraint (S1-F025) recurring one layer down, at the evidence level rather than the procedure level; open question is whether EvidenceLinks' existing "reliability conditions" field already encodes this role, a semantic question a grep cannot settle." (anchor: "Build what? An evidence reference that carries its role in the claim it supports -- construction versus independent validation -- so one item cannot silently occupy both... What problem does it solve? Circular self-validation... This is S1-F025's circularity at evidence level rather than procedure level -- the same defect one layer down... Verdict -> IMPLEMENTATION QUESTION. Fourth in the register, and the first whose missing fact is semantic rather than locational.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
