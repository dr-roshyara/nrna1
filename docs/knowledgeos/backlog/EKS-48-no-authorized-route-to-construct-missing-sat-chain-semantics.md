# EKS-48 — The `Sat(K,r,Γ)` operational gap can only be closed by *constructing* new theory, and no authorized construction route exists — a first build phase is proposed

## Problem, in business language

The corpus now establishes — by two independent runs, `MD-073` and `MD-074` — that **no path
through the existing corpus computes `Sat(K,r,Γ)` for any real requirement**: two of the chain's
own required inputs (a concrete `EC`, and `Γ` in any form) do not exist anywhere, and the two
function bodies (`EvalReq`'s general case and `Det_r`) do not exist either. `EKS-44` showed the
flagship worked example stipulates rather than computes; `EKS-47` showed the block is corpus-wide,
not local to one example; `MD-074` confirmed it by direct primary-source re-execution and added
four sharpenings.

The practical consequence is not a research ambiguity — it is a **process dead end**. What the
corpus is missing is not something that can be *found* in more documents; it is theory that would
have to be **written for the first time** (`Γ`'s definition, an `EC`-construction rule, a general
`EvalReq` procedure, a `Det_r` body, and a `Δ`/`Zero` step over the 3-argument `Sat`). And this
corpus's own rules make that a governed act: the tracked `Sat` chain has **never received a
governance-adoption event of any kind** (`MD-069`), and AI-authored knowledge enters the corpus as
`authority: generated` and is **never authoritative without human review**. So the real blocker
today is: **the missing semantics have no authorized author and no ratified route to enter the
corpus.** Nobody is blocked by a hard question; everybody is blocked by an absent decision.

This ticket does not ask that decision to be *made* here. It records that the decision is required,
and proposes the concrete phase it would authorize.

## Why this matters

- **It is the difference between an open *question* and an open *decision*.** `EKS-44/47` and
  `MD-074` have converged on exactly what does not exist. Further investigation will not change
  that; only an authorship decision can. Leaving that decision unmade parks the whole F4
  satisfaction chain in the same "typed but never computed" state it has occupied since Sep 6.
- **It prevents the gap from closing itself silently.** If someone eventually writes these
  definitions without a disclosed-construction marker, the corpus absorbs them as if recovered —
  the exact failure class `EKS-46` documents (a condition dropped in transmission). A named,
  pre-authorized phase with disclosure rules is the way to make construction *visible* rather than
  accidental.
- **It names the decision-maker's choice precisely**, so the request can be acted on in one
  sitting: authorize a build phase (this proposal), decline it (record the gap as deliberately
  open), or scope it differently.

## Recommended next phase (proposed — NOT performed by this ticket)

A construction phase, provisionally named **`SAT-OPERATIONAL-CLOSURE-v1`**, that authors — and only
authors — the missing semantics, each disclosed as **new theory construction**, never as corpus
recovery. Dependency order per `EKS-47`, refined by `MD-074`'s fourth sharpening (the typed chain
also has no downstream consumer):

1. **A definition of `Γ`** — the severest gap: no schema, no instance, no type exists anywhere.
   The phase would propose a type and semantics for the context argument carried by
   `Eval`/`EvalReq`/`Det_r`/`Det`. (New theory, first instance in the whole corpus.)
2. **An `EC`-construction rule** — a rule that builds a concrete `EC = ⟨Req, Rules, Scope, ER, TR,
   AR⟩` (Definition 2.20) from an existing decision-contract object such as `DC_release`
   (worked example §21A.3), or from first principles. Choosing how to instantiate `EC` forces a
   position on `GAP-002` (4-field vs 6-field `EC_t`) — a lineage choice the phase must make
   explicitly, not inherit silently.
3. **A general `EvalReq` procedure** — currently the only function in the chain with **no type
   signature or codomain** (`MD-074` sharpening 2), whose only content is a two-independent-source
   illustration. The phase must at minimum cover the **single-evidence-object case** (`MD-074`
   sharpening 1/3: `r_1` has one evidence object; the two-source illustration does not apply).
   If the procedure is built by generalizing the illustration, that generalization is itself new
   theory and must be disclosed as such, not presented as corpus-derived.
4. **A `Det_r` body for at least one requirement class** — including a definition of `𝕊_sat`
   (currently only the symbols `Satisfied`/`Satisfied_strong`/`Satisfied_weak` appear in use; the
   set is never defined).
5. **Downstream wiring** — a `Δ`/`Zero` step consuming the 3-argument `Sat(K,r,Γ)` (`MD-074`
   sharpening 4: the corpus's `Δ`/`Zero` definitions and every `Sat` stipulation are wired to the
   2-argument `Sat(K,r)`, so the decisive typed chain has no consumer).

### Constraints (non-negotiable for the phase)

- **Disclosure:** every construct carries a provenance marker stating it is new theory
  construction (drafted, `authority: generated`) and is not authoritative until human/governance
  review — per the corpus's knowledge-lifecycle rules.
- **No silent merge:** new constructs are registered as distinct proposed versions in the lineage
  artifacts (like `Sat`/`Sat_c`/`Sat*` are kept distinct) and are not merged into any existing
  corpus object, lineage, or freeze.
- **No reopened questions:** `GAP-004` stays CLOSED WITH QUALIFICATION (`MD-070`); the typed
  `Sat` definition is not re-adjudicated; no corpus-wide `Sat` survey is re-run.
- **No repair disguised as recovery:** each construct cites the corpus gap it fills and states
  plainly "this is new theory, not corpus recovery."

### Acceptance criteria

Re-run the `SAT-END-TO-END-CLOSURE-TEST-v1` mission (`MD-074`'s trace format) on `r_1 =
PaymentConfirmed(S)` and at least one second requirement, obtaining **COMPUTED** or **PARTIALLY
COMPUTED** using *only* the newly constructed, disclosed definitions — with every new construct
classified in the trace as new-theory (not silently `DERIVED` from the corpus). A phase that cannot
reach a computed value for `r_1` has not closed the gap.

### Deliverables

The five constructs (each a disclosed definitional artifact), a lineage/registry update recording
them as proposed versions, and the acceptance trace.

## The decision this ticket requests

One of:

1. **Authorize `SAT-OPERATIONAL-CLOSURE-v1`** as a scoped construction phase (possibly with a
   narrower first slice — e.g., `Γ` + the single-evidence `EvalReq` procedure only), to be executed
   under the disclosure constraints above, with a named reviewer for the human/governance review.
2. **Decline / defer** construction, recording the operational gap as deliberately open.
3. **Re-scope** — different order, different constructs, or a different mechanism for getting the
   semantics authored.

## Relationship to existing items (ES-005.4 — consume or extend, never create a second)

Checked before writing:

- **`EKS-47`** — the closest, and genuinely different. `EKS-47` records *what is missing* and that
  a future phase "would need to supply" it. This ticket records *why that phase cannot start* (no
  authorized construction route) and specifies the phase concretely. `EKS-47` is the diagnosis;
  this is the prescription awaiting a signature. Not a duplicate.
- **`EKS-44`** — the origin observation (typed but never computed); consumed, not restated.
- **`EKS-46`** — the discipline this proposal exists to protect (no silent qualification/condition
  loss during hand-off). The disclosure markers are the concrete application of its recommendation.
- **`MD-074` / `MD-073`** — the evidence base (decision-log entries, not backlog items).
- **`GAP-001/003/004`** (MD-068/070) — closed-with-qualification or non-blocking; only `GAP-002`
  is genuinely open, and this phase would be *forced* to take a position on it (a reason to
  authorize — the phase resolves a standing ambiguity by choice, disclosed as choice).

No existing item covers **the absence of an authorized authorship route for a corpus gap whose only
closure is construction.** Distinct.

## Urgency

**Medium for the corpus, high for this chain.** Nothing is actively broken; the chain has been in
this state since Sep 6. But the decision is the single unblocking input: every further investigation
phase (`MD-070`→`MD-071`→`MD-073`→`MD-074`) has now returned the same shaped result — the corpus
does not compute `Sat` — and each one ends by naming the same next step. The corpus is converging on
a decision it is not authorized to make.

## Evidence

- `MD-073` — Single-Case End-to-End Computation Attempt: BLOCKED at `EvalReq`'s own inputs
  (`three_model_convergence/14_decision-log/MD-073-sat-single-case-computation-attempt/`).
- `MD-074` — independent verification run confirming BLOCKED, with four sharpenings incl. the
  no-downstream-consumer finding and `EvalReq`'s missing signature
  (`three_model_convergence/14_decision-log/MD-074-sat-end-to-end-closure-test-verification/`).
- `EKS-44`, `EKS-47` — the gap records (typed-but-never-computed; Γ undefined and EC never
  instantiated, corpus-wide).
- `MD-069` — the tracked chain has received no governance-adoption event of any kind.

## Status

`OPEN` — filed 2026-09-09 by `MD-074`, cross-checking `MD-073`. Records a problem (no authorized
construction route) and proposes a phase; **decides nothing and authorizes nothing.** This ticket
itself takes no position on what the semantics should be, and changes no document.
