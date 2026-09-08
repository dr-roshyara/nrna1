# Semantic Kernel Equivalence Framework — Characterized, Not Executed

Source: `19-directive-adoption-and-research-restructure.md` §7 (already fully quoted in MD-037, not
re-quoted here in full — see that study's own artifact 05 for the source text).

## What it claims to formalize

Two things, explicitly kept as separate, sequential tests: **behavioural equivalence** — two kernel
configurations `𝒦₁`/`𝒦₂` produce the same externally observable behaviour `B(𝒦,W,Q,E,C) = (K′,
Assessment, Alternatives, Gap, Revision, Decision-eligibility)` on every admissible test case — and
**epistemic preservation** — an additional, necessary vector `P = (Meaning, Evidence, Warrant,
Uncertainty, Alternatives, History, Identity, Context, Inquiry, Authorization)` must also match
on every dimension declared essential. Behavioural equality alone is explicitly stated as
insufficient.

## Is it itself sufficiently specified?

**No — and the source says so itself.** Both `B` and `P` are named as *tuples of dimensions*, not as
computable functions — no source text anywhere in the series states how `Assessment`, `Warrant`, or
any other listed component is actually computed or compared. The document's own text states the
framework is *"not to be run before level 2 and level 3 [state type; semantic equivalence] have
answers"* — and `19`'s own restructure table (already quoted in MD-037 `05`) marks both of those
levels **`OPEN`**. The framework's own proposing document concedes it is premature as currently
specified.

## Required inputs/outputs

Inputs: two kernel configurations, a set of test cases spanning `(W, Q, E, C)` (worlds, inquiry,
evidence, context). Output: a pass/fail on behavioural equivalence, then (if passed) a pass/fail on
epistemic preservation across the ten named `P` dimensions.

## Behavioural, epistemic, or both?

**Both, explicitly sequential** — behavioural equivalence is the first, necessary-but-insufficient
gate; epistemic preservation is a second, additional requirement layered on top.

## Would it be capable, in principle, of testing "surviving a defeater"?

**Only as a container, not as a solution.** `Warrant` is one of `P`'s own ten named dimensions —
so in principle, a fully-specified version of this framework applied to `𝒦_{V0}` vs. `𝒦_{V6}` would
be exactly the right *shape* of instrument to test whether the survival requirement changes
epistemically-relevant behaviour. But the framework does not itself supply what "Warrant" (or
"survival") means — it would need that definition as an *input*, the same missing piece MD-037
searched for and did not find. **The framework cannot answer the question this study is preparing
evidence about; it names the right question and provides a place to put the answer, once one
exists.**

## What additional definitions would still be required

Computable definitions for every `B` and `P` component — most directly relevant here, a formal
account of `Warrant` sufficient to distinguish a survived defeater from a merely-considered one
(`I9`'s own weaker condition, per `03`/`04` of MD-037). Nothing in the framework's own specification
supplies this.
