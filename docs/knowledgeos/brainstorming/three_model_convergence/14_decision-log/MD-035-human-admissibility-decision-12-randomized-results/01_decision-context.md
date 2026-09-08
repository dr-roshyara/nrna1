# Decision Context

## MD-034's finding, restated as the factual basis for this decision (not re-derived here)

`docs/knowledgeos/research/kernel-reduction/12-randomized-results.md` — cited by section number
("§12") in the already-admitted `03-capability-model.md` and `04-operator-contracts.md`, but not
itself read by this reconstruction until MD-034 — contains an explicit, dedicated treatment of `V6`:
defines it precisely ("a `Verdict` requires a surviving-defeater step"), and supplies a genuine,
source-stated methodological argument that the currently-admitted baseline rule (`V0`, admitted via
`06-composition-rules.md`) permits a known failure mode ("`fit ⇒ validation`") that only `V6` blocks
structurally — without adopting `V6` as the design actually used elsewhere in the file's own
experiments.

MD-034's own recommendation: **A — scientifically relevant, admission candidate.** Open items MD-034
explicitly left unresolved: whether `V6` is the correct semantics for B's `Validate` operator (as
opposed to merely structurally preferable on one criterion); the baseline rule's own precondition/
postcondition/failure semantics (still `NOT SPECIFIED BY SOURCE`, unaffected by this file); and a
named tension in MD-033's own reasoning (its "V6 is not a live blocker" conclusion rested on V6's
absence from admissible evidence, which no longer holds once this file is admitted).

## What this decision is, and is not, about

**Is about**: whether `12-randomized-results.md` may be used as an evidence source in future,
separately-authorized research — the same narrow admissibility question MD-028-DQ-1 and MD-032
already answered for the three files admitted before it.

**Is not about**: whether `V6` is correct, preferred, or superior to the baseline; whether the
`fit ⇒ validation` critique is itself sound; whether a composition test should run; whether the
baseline `Validate` contract's remaining gaps (precondition/postcondition/failure semantics) are
now closed. Each of those is a separate question, unaffected by this decision either way.

## Provenance summary carried into this decision

`12` carries the identical Git-provenance status to the three already-admitted files — same
2026-09-06 bulk commit (MD-026's original finding, re-confirmed for this file in MD-034 `02`) — not
a weaker status. This is offered as relevant context for treating it consistently with `03`/`04`/
`06`; it is not, by itself, sufficient reason to admit it.
