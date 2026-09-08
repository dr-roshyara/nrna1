# Target Structure Register

| # | Structure | Required properties (per authorization §5) |
|---|---|---|
| 1 | Model B's C0 operator set (13 operators + `Qualify`) | input, output, preconditions, postconditions, state transition, side effects, composition rules, domain, invariant obligations |
| 2 | B's `S^epi(E,C,Q)→A` | whether E/C/Q/A are formally typed; semantic conditions; relation to Kernel/state |
| 3 | C1's `ConflictRecord` | definition, components, lifecycle, producer, consumer, invariants, relation to contradiction/evidence/verification |
| 4 | C2's `Θ` (transitions) | definition, type, domain, codomain, relation to state/operators, invariants |
| 5 | A's `Context` gap | whether Context exists elsewhere in A's model; whether it can supply `S^epi`'s missing `C`; source-attested or interpretive |
