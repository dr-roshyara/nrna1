# Phase 5I — Assertion Ontology Reconstruction (the A–G classification)

Applying the authorization's §2 taxonomy to the 5 `Assertion`-shaped records (D1–D5, `01`):

- **A. Same object / different notation**: **Not supported.** The field *sets themselves* differ
  (D1/D3 include `Evidence,Context,Time,Provenance`; D2/D4 include `Observation,id,c,t,Π`) — this is
  more than notational variance; the actual named contents disagree.
- **B. Same object / different implementation representation**: **Partially plausible for D2↔D4**
  (prose and code agree with each other) **and D1↔D3** (prose and code agree with each other) — but
  this reading cannot bridge the D1/D3-vs-D2/D4 divide, since the two *pairs* themselves disagree on
  substance, not merely on representation format.
- **C. Same conceptual object / different projections**: **Not evidenced** — no document proposes
  that D1/D3's version and D2/D4's version are two different *projections* of one shared underlying
  Assertion; no such underlying object is ever stated.
- **D. Temporal evolution/versioning**: **Plausible but not evidenced.** `t285_equality.py`'s own
  docstring frames itself as correcting an *equality claim* ("D285-6's own claim... was not a
  well-formed proposition"), which could suggest it is chronologically later and more refined than
  `t285_reconcile.py` — but neither script's own header states a version relationship, and
  `t285_reconcile.py`'s own computed `ABSENT` bucket (`03`) is demonstrably **not** updated to match
  D285-1's own later Sañjaya revision, which argues *against* a clean "later = more refined" story:
  if `t285_reconcile.py` were simply an earlier draft entirely superseded by `t285_equality.py`, that
  would be a tidy resolution, but `t285_equality.py` does not itself address the `State`/`Observation`
  distinction either — it inherits D2/D4's own inclusion of `Observation` in `UNPACK` without
  commentary, neither confirming nor engaging with the Sañjaya finding.
- **E. Competing hypotheses**: **Best-supported reading.** D1/D3 (one research position: Assertion
  carries `Evidence/Context/Time/Provenance`) and D2/D4 (a second research position: Assertion carries
  `Observation` plus `id/c/t/Π`) read as two independently-developed formalizations of "what an
  Assertion is," each internally consistent (prose matches its own paired code), neither explicitly
  aware of or reconciled with the other.
- **F. Incompatible definitions**: **Also applies, as a factual description of the state of the
  evidence** — the two field sets cannot both be complete and correct simultaneously (D1/D3 has no
  `Observation`; D2/D4 has no `Evidence/Context/Provenance` as such, though `c`/`Π` may correspond
  loosely to `Context`/`Provenance` — see `06` for the mapping test).
- **G. Unresolved**: **The correct final classification for the relationship between the two
  positions**, combining E and F: they are best understood as **two competing, internally-consistent,
  mutually-unreconciled hypotheses about the same named concept** — not a homonym (both concern "what
  fields does an Assertion carry"), not a notational variant, and not demonstrably one-supersedes-the-
  other.

## Verdict

**G — UNRESOLVED, with the specific character of E+F (competing hypotheses, not reconciled, genuinely
incompatible as literally stated).** This is not assumed from the shared word "Assertion" (per the
authorization's own warning) — it is derived from the fact that each of the two positions is
internally self-consistent across its own prose/code pairing, which is exactly the signature of two
independently-maintained research threads, not a single evolving or notational artifact.
