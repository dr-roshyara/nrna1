# 04 — Computability Verification Plan (stage V7)

Per 1600 §5: construct `Input → Algorithm → Output` for every object; never accept "computable" as a statement. Five-class separation enforced: mathematically defined / algorithmically computable / practically computable / computable-only-with-human-judgement / not identifiable from available observations.

## Constructions to produce (or mark impossible)

| Object | Construction task | Blocker | Class expectation |
|---|---|---|---|
| Zero | done at witness level; add explicit input-representation spec (K interface projections + EC) | KA5 closure decision | algorithmically computable rel. evaluator oracles |
| N (normalization) | build a **toy-instance prototype**: finite E with declared ~-classes and ≺-DAG, N = class-collapse + discount; run I-5/I-6 property tests over it | needs only a *declared* toy ~ (no domain oracle) — executable now | algorithmically computable per instance; instance-availability is the real gap |
| Replay | executable demo over class-1/2 evaluators + recorded class-4 artifacts; divergence demo with a stubbed live oracle | none — executable now | per TV-F-013 classes |
| Ladder LTS | executable guarded-transition demo once guards are stubbed as oracles | guard interpretations (governance/policy) | computable-with-oracles |
| DeriveContract skeleton | pseudo-algorithm from 025e's own steps with source-oracle interface; termination proof (finite source set) | oracle interface spec | computable-with-oracles + human-judgement class |
| GovernanceResolve | implement 25F.22's 9 steps over a toy source set; exhibit Resolved/Unresolved/Invalid outcomes incl. the tie→escalation | semantics modeling (the source's own proviso) | algorithmically computable given modeled semantics |
| Lord loop | termination demo **only with** budget policy parameter; non-termination demo without | budget policy is unratified — mark | conditional |
| Invariant checking | executable check of the 39-obligation core over M₁-style states | fresh-ID catalogue (plan 08 step 1) | computable per statement; system = schema |
| Satisfiability witness | encode M₀/M₁ as small executable models (extends TV-F-007 from proof to witness) | none — executable now | — |
| Aggregation edge conventions | tiny executable spec fixing BAYES{0,1}, WM ∅, MAX ∅ conventions as *labeled proposals* | none | — |

**Execution note:** items marked "executable now" are candidates for new computational witnesses in a later authorized wave (scripts under `verification/` scratch only — never repository code paths; same discipline as the GN-46 witnesses). **Nothing is executed under this plan document itself.**

**Prohibited conflations restated:** MATHEMATICALLY DEFINED ≠ OPERATIONALLY COMPUTABLE (η is the standing example) · complexity claims require declared problem size and model (T-K10 discipline).
