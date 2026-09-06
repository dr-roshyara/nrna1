# 09 — Simulation Design (Part XI)

## What was built, and what was deliberately not built

Not a production KnowledgeOS. A **small research simulator** whose only job is to decide, for a
candidate operator set, which epistemic transformations it can perform.

The central design decision: the simulator does **not narrate epistemic work**. A narrative
simulator (functions that "interpret" and "validate" by manipulating example data) is precisely the
instrument in which semantic smuggling is invisible — the researcher writes a helper, the helper
quietly does the removed operator's job, and the ablation reports a false reconstruction. Instead the
simulator is an **algebra**: operators are atom sets, carriers are derived from (inputs, atoms), and
reconstruction is a fixpoint search. Smuggling then becomes a *detectable edit to the model* rather
than an invisible line of code.

## Function classification (Part XI requirement)

Every function in `research/kernel-reduction/` is classified. **Only domain operators count toward
kernel size**, and there are exactly 14 of them (the `Op` entries in `kr/operators.py`).

| Function | Class |
|---|---|
| the 14 `Op` declarations | **domain operator** |
| `derive_kinds`, `reach`, `atom_pool`, `exclusive_atoms` | data structure operation |
| `evaluate`, `achieved` | data structure operation |
| `loo`, `pairwise`, `triples`, `atom_loo`, `smuggling_probe` | test harness |
| `make_world`, `check`, `guard_active`, `run_with_vacuity` | test harness |
| `run_scenarios` | test harness |
| `H`, `MI`, `context_experiment`, `confounding_experiment` | test harness (measurement) |
| `wilson`, `dump`, `main` | serialization / logging |
| `variants.*` | test harness (alternative model specification) |

**No helper introduces an atom.** Atom introduction happens in exactly one place — the `o.atoms`
argument to `derive_kinds` — which is why the "no arbitrary helper silently performs the work of a
removed operator" requirement is mechanically satisfied rather than merely promised.

## State representation `[PROP]`

```
K_t ≈ { claims, representations, relations, evidence, provenance, assumptions,
        alternatives, uncertainty, context, history }
```

**This is NOT claimed to be the canonical KnowledgeOS state model.** In the simulator this record is
represented by the *reachable carrier set* plus the derivation DAG: `claims` ↔ `Claim`,
`representations` ↔ `Representation`, `relations` ↔ `Relation`, `evidence` ↔ `Evidence`,
`alternatives` ↔ `Hypothesis` + `Discrimination`, `uncertainty`/`assumptions` ↔ `Verdict`,
`context` ↔ the context index carried by `SemanticContent`, `provenance`/`history` ↔ the DAG itself.

## Known limitation of the abstraction `[NEG]`

Because the simulator reasons about *reachability of kinds* rather than about *instances*, it cannot
express failures of degree — a system that produces one poor interpretation and a system that
produces a rich one are indistinguishable to it. It answers "can this operator set do X at all?",
never "how well". Every result in this experiment must be read inside that boundary.
