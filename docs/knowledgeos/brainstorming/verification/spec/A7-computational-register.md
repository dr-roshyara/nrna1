# A7 — Computational Feasibility Register (consolidated)

**Status:** CURRENT as of Wave 3 · consolidates A3X §5 (049/069/089 extraction), A4 T-K3/8/9/10, TV-F-013/018. Vocabulary: COMPUTABLE / COMPUTABLE-UNDER-RESTRICTIONS / NOT-ESTABLISHED / INFEASIBLE-AT-SCALE / UNDECIDABLE-IN-GENERAL / NOT-REALIZED.

| Construct | Computability verdict | Complexity | Realized? | Notes |
|---|---|---|---|---|
| Zero(K,EC) | COMPUTABLE-UNDER-RESTRICTIONS (finite R, evaluator oracles, closed 𝒮_gap) | O(|R|·cost(ev)) | witness only | termination of the *operator* proven; of any surrounding loop NOT-ESTABLISHED |
| Ladder step + A6 gate | COMPUTABLE | O(1) | witness only | |
| DC admissibility | COMPUTABLE given component evaluators | O(#components) | witness only | ratified 6-tuple has no ratified conjunction (MV-F-5 stands) |
| Aggregators A₁–A₄ | COMPUTABLE with declared edge conventions | O(n) | witness only | domain holes: BAYES{0,1}, WM ∅ |
| Normalization N | NOT-ESTABLISHED (unconstructed; complexity unknown) | — | no | gates the whole evidence pipeline |
| Replay | COMPUTABLE for evaluator classes 1–4 with recorded context | linear in |H| × step cost | no | TV-F-013 theorem + counterexample boundary |
| GovernanceResolve | COMPUTABLE provided semantics explicitly modeled (source's own proviso) | claimed O(n log n) — HEURISTIC estimate | no | two outcome vocabularies unmapped (TV-F-018) |
| DeriveContract | oracle-relative termination DERIVED-CONDITIONAL | finite source set | no | TV-F-011 |
| Lord selection procedure | per-step COMPUTABLE; loop termination requires budget policy (unratified) | — | no | 𝒯 vs DecisionState typing gap |
| Sārathi | COMPUTABLE given DecisionModel; escalation outcomes first-class | — | no | composed Lord↔Sārathi loop termination NOT-ESTABLISHED (C-046) |
| State-space enumeration | INFEASIBLE-AT-SCALE (2ⁿ; P-13) | exponential | — | symbolic/compositional methods mandated by corpus, unimplemented |
| Universal verification / halting-class questions | UNDECIDABLE-IN-GENERAL (P-12); restricted instances decidable | — | — | 089's scoping independently confirmed |
| Finite-state reachability, rule-set compliance | COMPUTABLE | graph-search / linear | no | |
| Calibration suite | defined procedure absent; nothing executed | — | no | TV-F-014C |
| SNF metrics | mechanically computable; measurement validity is the gap (A-M register) | — | synthetic pilot only | |
| **System as a whole** | **NOT-REALIZED** (L4 empty, CF-015) — total executed base: 3 reference scripts + 1 CSV | — | — | 103 conceptual PASSes carry no computational weight |

**Standing rule enforced:** no "computable on a normal PC" claim admitted without problem size, representation, and complexity — the corpus's own late-file discipline (069 §19–20 `Computability≠Feasibility`) is the register's standard; the early unqualified claims (025g/025h) remain REFUTED-as-unscoped.
