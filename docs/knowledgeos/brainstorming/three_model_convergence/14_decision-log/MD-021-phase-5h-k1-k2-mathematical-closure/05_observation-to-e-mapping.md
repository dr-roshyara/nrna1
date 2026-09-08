# Phase 5H — `Observation → e` Mapping

## What is `e`? Traced through the executable code, not inferred from the letter alone

D285-6's own notation writes `Observation → e, after Qualify` without expanding `e`. This phase traced
`e` through the corpus's own executable script `exec/e_equality.py` (found this phase, not read in
Phase 5F/5G), which defines an `Assertion` constructor:

```python
def A(P, e, c, t, Pi): return dict(P=P, e=e, c=c, t=t, Pi=Pi, id=H(P,e,c,t,Pi))
```

with a worked example: `a_scan = A(("svc","D.tls","1.3"), (("ev1","supports","active"),), "prod",
("2026-08-01",None), "origin:scan")` — i.e., `e = (("ev1","supports","active"),)`, a **tuple of
evidence-record-shaped items** (an identifier, a relation-type, and a status).

## Is `e` = Evidence?

**Plausibly yes, but not explicitly stated as such anywhere.** The parameter name `e` (lowercase),
its content (a tuple of evidence-shaped records), its role inside the `Assertion` constructor, and
D285-6's own gloss ("`Observation → e (evidence)`") are all consistent with `e` denoting "the evidence
field of an Assertion." **No document literally writes "`e` = Evidence" as a defining equation** — this
is this phase's own reconstruction from consistent usage across code and prose, disclosed as
`RESEARCH INTERPRETATION` (level 4), not `DIRECT SOURCE EVIDENCE` (level 1).

## Totality/computability of `Observation → e`

Per `04`, this mapping is the one explicitly gated by `Qualify`/`CaptureAndQualify` — **conditional,
not total** (an Observation becomes `e` only after satisfying the named checklist), and **not
computable** (the checklist is not formalized as an evaluable predicate).

## Verdict

$e$ is best reconstructed as **the evidence-payload field of the verification lane's `Assertion`
composite**, consistent across both D285-6's prose and the executable code's own parameter usage —
**RESEARCH INTERPRETATION, not direct evidence, since no document states the equation explicitly.**
The mapping `Observation → e` is **conditional and non-computable**, consistent with `04`'s own
`Qualify` findings (the two are the same claim, viewed from opposite ends of the arrow).
