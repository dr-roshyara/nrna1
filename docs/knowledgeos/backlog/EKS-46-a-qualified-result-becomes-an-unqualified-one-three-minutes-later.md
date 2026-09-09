# EKS-46 — A carefully qualified result becomes an unqualified one three minutes later, and the qualification never comes back

## Problem, in business language

A research result was established with an explicit, load-bearing condition attached. Three minutes
later a second document restated the same result **without the condition**, described it as proven,
and instructed that it should not be reopened. Everything downstream then inherited the
unconditional version.

The condition was not a footnote. It was the part that made the claim true.

## What actually happened

**19:24** — an execution artifact reports a minimality result for the knowledge state, and states
its scope precisely:

> *"The model is executable, so removal is testable. The phrase MINIMAL KERNEL is therefore
> **earned** — but only in the sense step 254 names: **`Minimality(K | 𝒯)`, relative to the
> transformation set**."*

Read plainly: *this kernel is minimal **with respect to a particular set of operations**. Change the
operation set and the result may not hold.*

**19:27** — a second document, written three minutes later, restates it as:

> *"The six assertion fields each survived an executed removal test … The resulting minimality claim
> is therefore no longer merely hypothetical: it is **reported as PROVEN** by the executed
> programme. … Therefore [this step] should **not reopen the already-closed `K` problem**."*

The relativisation `| 𝒯` is gone. "Earned, relative to the transformation set" has become "PROVEN".
And the instruction not to reopen means the missing condition cannot easily be restored by the
documents that follow.

## Why this is a business problem, not a wording preference

- **The condition is the whole content of the result.** A minimality claim relative to a declared
  operation set is a real, checkable, useful finding. An unconditional minimality claim is a much
  stronger assertion that the underlying test does not support. The second version is not a
  simplification of the first; it is a different and larger claim.
- **It travels.** Later work builds on the unconditional version. Anything that inherits "the kernel
  is minimal, proven" inherits a claim the evidence does not carry, and inherits it without any
  marker that something was dropped.
- **The instruction not to reopen closes the repair path.** Ordinarily an over-strong claim gets
  caught when someone re-derives it. Here the re-derivation is explicitly discouraged, so the
  qualification has no natural route back.
- **Nothing is wrong at either end.** The 19:24 artifact is careful and states its scope. The 19:27
  document is not careless — it is summarising an upstream result in good faith. **The defect is
  entirely in the hand-off**, which is exactly why no review of either document alone would catch it.
- **It is invisible to search.** Looking for the minimality claim returns both documents. Nothing
  signals that one of them carries a condition the other has lost.

## What is *not* the problem

- Not a contradiction. The two documents do not disagree; one is strictly weaker than the other.
  This is why it differs from the contradictory-disposition problem already tracked.
- Not a missing document. Both are present, readable, three minutes apart, in the same corpus.
- Not a wrong result. The 19:24 finding appears sound within its stated scope. Only its
  transmission is at fault.
- Not about which knowledge-state definition is correct. That question is open and is not touched
  by this ticket.

## Candidate requirement

When a result crosses from the artifact that established it into a document that consumes it, **the
scope conditions must cross with it.** Options, cheapest first:

1. **Carry the qualifier in the claim itself.** Write `Minimality(K | 𝒯)`, never "minimal", so the
   condition cannot be dropped without visibly altering the notation. Costs nothing; the corpus
   already has the notation.
2. **Require a scope line on any inherited result.** A document restating an upstream finding states
   what the upstream conditioned it on, or states that it checked and there were no conditions.
3. **Treat "do not reopen" as requiring a citation.** An instruction to close a question should name
   the artifact that closed it, so a reader can check what that artifact actually claimed.

**Recommendation: 1 and 3.** Option 1 makes the loss impossible to make silently. Option 3 makes it
recoverable when it happens anyway.

## Relationship to existing items (ES-005.4 — consume or extend, never create a second)

Checked before writing:

- **`EKS-40`** (one batch, contradictory dispositions of the same item) — the closest, and genuinely
  different. There, two documents say **opposite** things and both are quotable. Here they say the
  **same** thing at **different strengths**, and the weaker-conditioned one is correct. A
  contradiction-detector would not fire on this at all.
- **`EKS-38`, `EKS-41`, `EKS-42`, `EKS-43`** — the identifier/notation-collision family. This is not
  a collision: one claim, one meaning, one lost condition.
- **`EKS-35`** (timestamps do not encode argument order) — unrelated; both documents here are in the
  right order.

No existing item covers **silent loss of a scope condition during transmission**. Distinct.

## Urgency

**High for its size.** The affected claim is the knowledge-state minimality result, which the later
kernel work treats as settled and builds on. The fix for the instance is one line of notation; the
cost of leaving it is that every downstream user of the kernel inherits a stronger claim than the
evidence supports, with no marker that anything was lost.

## Evidence

- Source, qualified: `docs/knowledgeos/brainstorming/verification/KNOWLEDGE-STATE-CANONICAL-MODEL.md`
  §3 (artifact D, `mandate: 20260830_1918`, 2026-08-30 19:24) — *"minimality, tested only now that
  the model executes"* … *"earned — but only in the sense step 254 names: `Minimality(K | 𝒯)`,
  relative to the transformation set."*
- Consumer, unqualified: `docs/knowledgeos/brainstorming/phase_measure_theory/20260830-192707_step_262_proposition-assertion-type.md`
  L23–31 — *"reported as PROVEN by the executed programme"*, *"should not reopen the already-closed
  `K` problem."*
- Batch context: ten artifacts A–J carrying `mandate: 20260830_1918`, timestamped 19:22–19:28.
  The consuming document was written at 19:27:07, **inside that window.**

## Status

`OPEN` — raised by the `G-18` provenance investigation, 2026-09-09. Advisory: records a problem and
recommends a convention. Decides nothing, changes no document, and takes no position on which
knowledge-state definition is correct.
