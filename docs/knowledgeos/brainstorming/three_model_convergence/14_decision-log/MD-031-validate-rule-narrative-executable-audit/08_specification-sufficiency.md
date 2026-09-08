# Specification Sufficiency

**Question**: Does `06-composition-rules.md` close the specification gap MD-029 identified? Per-field
classification: `CLOSED BY SOURCE` / `PARTIALLY CLOSED` / `NOT CLOSED` / `CONTRADICTED` /
`NOT REQUIRED` / `UNRESOLVED`. Code is never used to close a narrative gap in this table.

| MD-029's original gap | Classification | Reasoning |
|---|---|---|
| Input carriers | **CLOSED BY SOURCE** | via the explicit chain `04`(admitted, atom=`warrant-assessment`) + `06`(this study, `{Claim,Evidence}\|{Hypothesis,Evidence}` → via `warrant-assessment` → `Verdict`) — every link is a direct quotation from narrative-lane text, `03` |
| Output carrier | Already **CLOSED BY SOURCE** (unchanged from MD-029, via `03-capability-model.md`'s C10 row) | not reopened here |
| Preconditions (operator-specific, beyond input availability) | **NOT CLOSED** | `06` states no operator-specific precondition predicate beyond "the listed inputs are available" — `03` |
| Postconditions | **NOT CLOSED** | `06` states only what is produced, not a postcondition over it |
| Warrant-kind constraint on `Claim` | **CLOSED BY SOURCE** — new information beyond what MD-029 had | `06` lines 51–52: `Claim` producible only via `entailment` (DEDUCTIVE); a generate-and-test `Verdict` is explicitly not a `Claim` |
| Policy dependence | **PARTIALLY CLOSED** | established only by chaining one further table row (`Observation,Policy → Evidence`) — indirect, not `Validate`-specific in the source's own framing |
| Context dependence | **NOT CLOSED** | no direct dependency stated; only appears two derivation-steps removed |
| Failure/undefined behavior | **NOT CLOSED** | no operator-specific failure semantics stated anywhere in `06` |
| Which of the ≥2 tested derivation rules (baseline vs. `V6`) is authoritative | **UNRESOLVED** | `06` is silent on `V6` entirely — see `07` |
| Composition constraints (how `Validate`'s step interacts with the fixpoint engine generally) | **CLOSED BY SOURCE** | `Reach(S)` is fully specified (lines 58–69), applies uniformly, `Validate` is not a special case |

## Net answer

**The single most consequential field MD-029 flagged — `Validate`'s own input carriers — moves from
`NOT SPECIFIED BY SOURCE` to `CLOSED BY SOURCE`**, using only narrative-lane text (the already-
admitted `04` plus this study's own read of `06`), with no dependency on the executable code for this
specific conclusion. Several secondary fields (preconditions, postconditions, context dependence,
failure behavior, and — critically — which of the two tested derivation rules is authoritative) remain
open. This is a real, bounded narrowing of the original gap, not a full closure of every field MD-029
listed.
