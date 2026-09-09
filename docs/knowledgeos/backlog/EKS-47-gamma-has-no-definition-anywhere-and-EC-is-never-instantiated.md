# EKS-47 — `Γ` has no definition anywhere in the corpus, and `EC` is never instantiated for any real
requirement; `Sat`'s chain is blocked at its first step, corpus-wide, not just in one example

## Problem, in business language

`EKS-44` already found that the theory's own flagship worked example never computes `Sat` — it just
states the answer. That finding, by itself, could still be read as "this one example took a
shortcut; presumably the machinery works elsewhere." A follow-on, narrowly-scoped experiment
(`MD-073`, `three_model_convergence/14_decision-log/MD-073-sat-single-case-computation-attempt/`)
attempted to actually run the machinery for one concrete, real, well-evidenced requirement
(`PaymentConfirmed(S)`) and found something stronger and more specific:

1. **`Γ` — one of the two contextual arguments every use of `Sat`/`Det`/`EvalReq` carries — has no
   formal definition anywhere in the corpus.** Not "no instance for this case": no *schema* either, at
   any level. It is used only as an informal word, "context," in ordinary sentences.
2. **`EC` does have a formal schema (Definition 2.20, a 6-tuple) — but no instance of it is ever
   constructed for any real requirement, including the flagship worked example's own requirements.**
   The worked example uses the bare symbol `EC`, never a built value.
3. **A corpus-wide search (not limited to one document) confirms `EvalReq(` and `Det_r(` each occur in
   exactly one place in the entire corpus — their own definitional statements.** Neither is ever
   invoked with concrete arguments anywhere, by any document, for any requirement.

So the gap `EKS-44` named is not local to one example's writing style; it is a property of the whole
corpus as it stands: there is currently no path, anywhere, from a real requirement and real evidence
to a computed `Sat` value.

## Why this matters

- It changes the shape of the remaining problem. `EKS-44`'s recommended resolution #1 ("compute
  `Det_r`/`EvalReq` explicitly for the worked example's four requirements") cannot be attempted as
  stated — not because it is hard, but because two of its own required inputs (`Γ` in any form, and a
  constructed `EC`) do not exist yet, and the function bodies (`Det_r`, and `EvalReq`'s general case)
  do not exist either.
- Any future phase that wants to make `Sat` computable must therefore first supply, at minimum: a
  definition of `Γ`, a rule for constructing `EC` from a decision contract like `DC_release`, and an
  `EvalReq` procedure for the (very common) single-evidence-object case — not only a `Det_r` body.
  Naming all four, rather than only `Det_r`, changes what "closing this gap" would actually require.

## Recommended resolution (not performed by this ticket — governance/authorship decision required)

Same posture as `EKS-44`: this ticket records the problem; it does not fix it. A future,
separately-authorized phase would need to supply, in dependency order: (1) a definition of `Γ`, (2) a
rule for constructing a concrete `EC` from an existing decision-contract object, (3) a general
`EvalReq` procedure covering at least the single-evidence-object case, (4) a `Det_r` body for at least
one requirement class — each disclosed as new theory construction, not recovered from the existing
corpus (per `MD-073`'s own non-invention constraint, none of the four exists today).

## Discovery context

Found during `MD-073` (Single-Case End-to-End Computation Attempt for `Sat(K,r,Γ)`,
`three_model_convergence/14_decision-log/MD-073-sat-single-case-computation-attempt/`), a phase
specifically authorized to attempt one concrete computation rather than repeat `MD-070`'s corpus-wide
survey. Full evidence: `01_case-trace-and-verdict.md`.

## Evidence

- `mathematical_ideas_that_can_be_implemented/20260906-075153_theory-part-21a-rev2-worked-example-to-final-decision-outcome.md`
  — `EC` used as a bare symbol (lines 806, 1203), never constructed; no `Γ` definition referenced.
- `mathematical_ideas_that_can_be_implemented/20260906-003947_theory-part-06-evidence-evaluation-determination-calculus.md`
  §6.17–6.18 — `EvalReq`'s only worked illustration is a two-independent-source case, inapplicable to
  `PaymentConfirmed(S)`'s single evidence object; `Det_r` given only as a type signature.
- `mathematical_ideas_that_can_be_implemented/20260906-002452_theory-part-02-formal-ontology-and-type-system.md`
  Definition 2.20 — `EC`'s schema, never instantiated for this or any other real requirement.
- Corpus-wide greps for `EvalReq(` and `Det_r(` (recorded in `MD-073`'s `01_case-trace-and-verdict.md`,
  "Method" section) — each returns exactly one hit, its own definition.
