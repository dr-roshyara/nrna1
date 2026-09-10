# EKS-55 — A tested, superior evaluator existed four days before `Det_r`/`EvalReq` and was never consulted

## What was found

`M0127` (`mathematical_ideas_that_can_be_implemented/20260902-175306_kr-contr-fde-2026-09-external-
writeup.md`, 2026-09-02) formally defines and empirically validates `Standing(p) =
(S⁺(p),S⁻(p),R(p),P(p),Ctx(p),Cond(p))` — a six-component, typed evaluator for a proposition's own
epistemic standing, tested against 14 adversarial scenarios and shown to preserve 12/14 (versus 2/14
for a Boolean collapse, 8/14 for a four-valued FDE collapse). This is real, disclosed, corpus-native
empirical validation — materially stronger evidence of usefulness than anything `Eval`, `EvalReq`, or
`Det_r` (T21, `mathematical_ideas_that_can_be_implemented/20260906-003947_theory-part-06-...md`,
2026-09-06 — four days later) ever received (zero invocations anywhere, per MD-076).

Direct verification this phase (`14_decision-log/MD-080-responsibility-transfer-chronological-
reconstruction/`) confirms **zero occurrences of `Standing(` anywhere in the T21 rewrite's 21 parts**,
and zero reappearances anywhere in the corpus this reconstruction has read after 2026-09-02, except one
occurrence inside an already-excluded self-referential file (`documents7.md`, flagged MD-077/078).

## Why this is load-bearing

This is not a duplicate of the already-tracked absence findings (`EKS-44`/`47`/`48`, `Det_r`'s own
missing body) — those concern what T21 itself lacks. This finding concerns **available, tested,
corpus-native material that a future construction phase would need to know about before attempting to
build `Det_r`/`EvalReq` from scratch.** If `EKS-48`'s own three-way decision is ever resolved toward
"authorize construction," the natural, evidence-respecting starting point is not an unconstrained new
design but an explicit evaluation of whether `Standing(p)`'s own already-tested structure can be
adapted — checked first, not invented alongside a structure the corpus already validated and then
apparently forgot. Proceeding to construct `Det_r`/`EvalReq` without first checking `Standing(p)` would
risk re-deriving, less successfully, work the corpus already did once.

## Why this is not already covered

Checked against `EKS-44` (typed-but-uncomputed `Sat` — a different claim, about T21's own object, not
about available alternative material), `EKS-45`/`EKS-54` (notation collisions — a different
*phenomenon*: those concern the same symbol denoting different objects; this concerns *different*
symbols, `Standing` and `Eval`/`EvalReq`, plausibly performing the *same responsibility* without ever
being connected), `EKS-48` (the construction-authorization decision itself — this ticket supplies
evidence for that decision, it is not the decision).

## Recommended disposition

Not a request to adopt `Standing(p)` as `Det_r`'s own replacement (that would be construction/
canonicalization, requiring separate authorization). Recommended: if and when `EKS-48`'s own decision
is resolved toward construction, this ticket's own evidence should be the first thing consulted, before
any new evaluator design is attempted.

## Status

`PROPOSED`. Filed by MD-080 (`three_model_convergence/14_decision-log/model-boundary-decisions.md`,
this phase's own entry). Not authorized for action; governance disposition pending.
