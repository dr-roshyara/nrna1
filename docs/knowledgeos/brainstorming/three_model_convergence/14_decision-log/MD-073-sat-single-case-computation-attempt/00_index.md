# MD-073 — Single-Case End-to-End Computation Attempt for `Sat(K,r,Γ)`

## Authorization and mission

User's direct instruction (via `docs/knowledgeos/brainstorming/what_is_knowlegeos_theory/
20260909-2247_sat-computability-vs-lineage-option-b-mission-spec.md`, option (b)): do **not** rerun
MD-070's 876-file adversarial survey and do **not** reopen GAP-004. Instead, using MD-070/EKS-44 as
the established baseline, attempt exactly **one** narrow end-to-end semantic computation:

> Find one actual, corpus-grounded requirement `r` for which the corpus provides sufficient concrete
> inputs to attempt `EvalReq(K_t,r) → Det_r(...) → Sat(K_t,r)`, using existing corpus-native
> definitions only. Do not invent `Accept_r`, do not assume `Σ/V7`, do not introduce a new `Sat`
> formula or semantic adapter, do not silently repair missing semantics. If the computation cannot be
> completed, stop at the exact point of semantic insufficiency and report the first missing semantic
> dependency. The goal is not to make `Sat` computable — it is to determine whether the *existing*
> theory already contains enough semantics to compute it.

## What this phase is

A **CONSTRUCTED** activity (this reconstruction's own experiment), not a corpus-native artifact — it
does not change what any historical `TheoryState(T_n)` records as having existed. It is narrower in
scope than MD-070: one selected case, traced by hand, rather than a corpus-wide survey.

## Case selected

`r_1 = PaymentConfirmed(S)`, one of the four requirements of `Req_release(S)` in the Theory-00-21
flagship worked example (`mathematical_ideas_that_can_be_implemented/
20260906-075153_theory-part-21a-rev2-worked-example-to-final-decision-outcome.md`, the same document
MD-070 Finding 5 examined). Selected because it is the most concretely evidenced requirement in the
corpus: it has a named knowledge state, a named evidence object, and a named supporting proposition —
the best case the corpus offers, not an easy or favorable one chosen to force a result (mission rule
7).

## Method

1. Reopened the worked example (`20260906-075153...`, in full) and the Part VI calculus that defines
   `EvalReq`/`Det_r` (`20260906-003947_theory-part-06-evidence-evaluation-determination-calculus.md`,
   §6.17–6.18) and the Part II definition of `EC` (`20260906-002452_theory-part-02-formal-ontology-
   and-type-system.md`, Definition 2.20).
2. Attempted to instantiate every argument `EvalReq(K,r_1,EC,Γ)` and `Det_r` require, using only
   values the corpus itself supplies for this case — no substitution, no default, no analogy import
   from a structurally different example.
3. Corpus-wide greps (`EvalReq(`, `Det_r(`) to check whether *any* other document, anywhere, supplies
   a concrete instantiation this case could reuse.
4. Recorded the exact point of failure and classified the case per the mission's three-way schema.

## Verdict, stated up front

**BLOCKED at the first invocation of `EvalReq`, before `Det_r` is even reached — for two independent,
compounding reasons**, not one:

1. Neither of `EvalReq`'s two contextual arguments, `EC` and `Γ`, is ever given a concrete value for
   `r_1` anywhere in the corpus. `EC` has an abstract 6-tuple *schema* (Definition 2.20) that this
   worked example never instantiates — it uses the bare symbol `EC` throughout, never a constructed
   value. `Γ` has no formal definition anywhere in the corpus at all (only informal "context" prose),
   so it has neither a schema nor an instance.
2. Even granting, purely hypothetically, that (1) could be resolved: `Det_r` cannot be reached either.
   A corpus-wide search confirms `Det_r(` occurs in exactly one place in the entire corpus — its own
   definitional statement (§6.18) — as a bare type signature `𝒱 × EC → 𝕊_sat`. No body, rule, or
   instance for any `r`, in any document, exists to receive `EvalReq`'s output.

This sharpens MD-070/EKS-44 (which showed the *existing* worked example stipulates `Sat` rather than
computing it) with a stronger, corpus-wide result: it is not merely that this example takes a
shortcut — **no path through the corpus reaches a computable `Sat` for any case**, because two of the
chain's own required inputs (a concrete `EC`, and `Γ` in any form) and the entire body of `Det_r` are
absent everywhere, not just in this one example.

## Artifacts

- `01_case-trace-and-verdict.md` — the full case record (mission's required fields) and classification.

## Cross-references

- Baseline, not reopened: MD-070 (`14_decision-log/MD-070-gap-004-adversarial-review/`), `EKS-44`.
- New backlog entry filed for the `Γ`-absence finding, additive to `EKS-44`: `EKS-47`
  (`docs/knowledgeos/backlog/EKS-47-*.md`).
- Distinct, unaffected: `G-01` (Gap Register) — the `Sat_v4→Sat_v6` lineage/provenance question. That
  is a different problem (why an argument was dropped across versions) from this phase's question
  (whether the current definition can be executed at all). Not mixed, per the mission's own explicit
  instruction.

## MD-073 STATUS: EXECUTED. HARD STOP — no further phase automatically opened; MD-057–072 preserved
unchanged throughout; GAP-004 not reopened.
