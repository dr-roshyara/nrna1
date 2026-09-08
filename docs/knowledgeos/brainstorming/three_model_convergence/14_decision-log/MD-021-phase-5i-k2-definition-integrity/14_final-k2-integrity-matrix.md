# Phase 5I — Final K-2 Integrity Matrix (required, per the authorization's §18)

| Question | Result |
|---|---|
| Is Assertion uniquely defined? | **No** — 5 materially distinct records reduce to 2 internally-consistent competing positions plus 1 narrower-scope outlier (`01`, `02`) |
| Are D285-1 and D285-6 equivalent? | **No** — contradictory field sets, unreconciled (`02`) |
| Are `t285_reconcile.py` and `t285_equality.py` equivalent? | **No** — under all 5 tested relations, at most a partial, qualified semantic compatibility; no bridging transformation evidenced (`06`) |
| What does `e_equality.py` actually implement? | A **different question** than the K-1→K-2 projection: K-2's own internal equality-relation hierarchy (structural/semantic/observational/provenance-sensitive), with genuinely executed comparison functions and a content-addressed `id` (`05`) |
| Is Evidence = QualifiedObservation established? | **Stated as a defining equation in one source** (seq 0630 §49.76); not independently demonstrated or tested anywhere — `RESEARCH INTERPRETATION`/definitional, not `DEMONSTRATED` |
| Is `e` = Evidence established? | **Plausible, not established** — consistent usage across code and prose, but no document states the equation explicitly (`05` of Phase 5H, reconfirmed) |
| Is `e` = Observation established? | **No** — `e` is best read as the *output* of qualifying an Observation (i.e., closer to Evidence), not Observation itself |
| Is Qualify uniquely defined? | **No** — 3 variants (1-arg, 2-arg, differently-named `CaptureAndQualify`), unreconciled (`07`) |
| Is K-2 a single object? | **No — COMPETING OBJECT DEFINITIONS** (`08`) |
| Is K-1 → K-2 one projection or multiple projections? | **Multiple** — $\pi_1$ and $\pi_2$, competing, not shown equivalent (`09`) |
| Is the projection total? | **No, for both** — $\pi_1$ has no image for `Observation` at all; $\pi_2$ is conditional on `Qualify` |
| Is it computable? | **No, for both** — $\pi_1$ fails structurally (undefined target); $\pi_2$ fails on `Qualify`'s missing algorithm |
| What information is lost? | Re-classified per `10`: Event/Action = D1 (both projections); Policy = D1 for $\pi_1$, D7 for $\pi_2$ (the `Π` ambiguity); Observation = D5 for $\pi_1$, D4 for $\pi_2$; State = D7 for both |
| What remains unresolved? | The Assertion field-set conflict itself; `Qualify`'s arity and algorithm; `State`'s carrier; whether $\pi_1$ and $\pi_2$ are reconcilable; the `Π` Policy-vs-provenance ambiguity |
| Which gaps are genuine source-research gaps? | The complete Assertion reconciliation; `Qualify`'s algorithm; `State`'s carrier definition; a single reconciled `Qualify` arity — none of these can be closed by further reading, since the corpus's own artifacts (including its own executable code) actively disagree |
| Does any stronger K-1/K-2 equivalence remain justified? | **No — if anything, this phase narrows Phase 5H's own "partial correspondence" finding**, since that finding is now shown to hold only for $\pi_2$, not demonstrated for $\pi_1$ |
