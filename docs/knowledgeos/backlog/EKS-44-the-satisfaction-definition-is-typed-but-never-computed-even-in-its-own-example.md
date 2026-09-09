# EKS-44 — The `Sat` definition is typed but never computed, even in its own worked example; and one
claim is presented simultaneously as Definition, Theorem, and Axiom

## Problem, in business language

The Sep-6 "KnowledgeOS Verified Theory" rewrite (`mathematical_ideas_that_can_be_implemented/
20260906-*`, 21 parts) proposes the missing piece of a four-day-old open question: how does a
knowledge state actually get judged as satisfying a requirement (`Sat(K,r,Γ)=Det_r(EvalReq(K,r,
EC,Γ),EC)`). This looks, on its face, like a closure. It is not. Two independent problems mean a
reader relying on this document as a working answer would be misled:

1. **The document's own flagship demonstration never uses the formula it just spent six parts
   building.** When the theory is finally walked through a full worked example end to end — the one
   place it should show the machinery actually running — the example simply asserts the outcome
   ("`Sat(K,r_i)=Satisfied`") instead of computing it. This is the same shortcut every earlier,
   already-abandoned attempt at this same question took.
2. **One claim is filed under three different kinds of statement at once** — a *definition*
   (something stipulated by convention), a *theorem* (something proved from other things), and an
   *axiom* (something assumed as a foundation) — for the identical sentence, `Zero(K,EC)⟺Δ(K,EC)=∅`.
   These three kinds of statement are supposed to mean different things about how confident a reader
   should be and where the claim's authority comes from; using all three for one sentence erases that
   distinction exactly where a careful reader would look to it for guidance.

## Why this matters

- A future reader (human or AI) citing "`Sat` is now defined" without checking whether it was ever
  actually exercised would carry forward an overstatement this reconstruction (MD-070) specifically
  set out to check for and found.
- The Definition/Theorem/Axiom conflation is a small methodological lapse in isolation, but it sits at
  the exact load-bearing claim (`Zero`) the whole `EC_t→Req→Sat→Δ_t→Zero` chain's own closure theorem
  depends on — the kind of place where category discipline matters most, not least.
- Both findings recur a pattern this backlog already tracks under different names: `EKS-39` (a research
  object silently changing kind mid-corpus) and `EKS-37` (one item typed as two incompatible things at
  once) are the closest prior instances — this is a further, distinct occurrence, this time inside a
  single, self-consistent-looking 21-part rewrite rather than across separate documents.

## Recommended resolution (not performed by this ticket — governance/authorship decision required)

1. Either compute `Det_r`/`EvalReq` explicitly for the worked example's four requirements, or mark the
   example's own Determination section as illustrative-only (not a demonstration of the formula).
2. Resolve the Definition/Theorem/Axiom triple for `Zero(K,EC)⟺Δ(K,EC)=∅` to one category — most
   naturally Definition 23.1 as the source, Theorem 24.1 retained as its derived consequence, and
   Axiom A7 (§39) either removed or explicitly relabeled as a restatement, not an independent axiom.

This ticket does not perform either fix — it records the problem for a future, separately-authorized
phase.

## Discovery context

Found during MD-070 (Independent Adversarial Review of GAP-004, `three_model_convergence/
14_decision-log/MD-070-gap-004-adversarial-review/`), a phase specifically commissioned to give the
Sep-6 `Sat` definition the same adversarial scrutiny this corpus's own reviewers applied to 11+ other
major claims across MD-057–069. Full evidence: `01_findings.md` (Findings 3 and 5),
`02_verdict-and-corrections.md`.

## Evidence

- `mathematical_ideas_that_can_be_implemented/20260906-002301_theory-part-01-foundational-distinctions-and-plan-for-full-rewrite.md`
  — Definition 23.1 (§23), Theorem 24.1 (proved from it), Axiom A7 (§39, restates both verbatim).
- `mathematical_ideas_that_can_be_implemented/20260906-075153_theory-part-21a-rev2-worked-example-to-final-decision-outcome.md`
  §21A.17 — "All are satisfied: `Sat(K,r_i)=Satisfied` for `i=1,...,4`," with no invocation of
  `Det_r`/`EvalReq`/`Eval` anywhere in the document's 1859 lines.
