# Formal verification task — instructions for the verifier

You receive exactly one specification, `SPEC.md`. Your task is to implement it **independently**, in any language, and report what it computes. You are not asked whether any theory is right: only what the specification's formal claims evaluate to.

## Rules
- Use only `SPEC.md`. Do not look for, or use, any other implementation, result or document related to it.
- Your program must be deterministic and must decide each item **exactly**. State in writing why your method decides "for every R" exactly, not by sampling.
- Where `SPEC.md` is ambiguous, choose one reading, **state it explicitly**, and continue. Do not ask anyone which reading is expected.

## Deliverables
1. `verifier.<ext>`: your implementation.
2. `results.json`, containing for each instance (chain3, V, diamond, antichain2):
   - `state_count_with_A0` and `state_count_without_A0`;
   - `full`: the truth values of D1, D2, D3, D3+, D5, D6 and NV under the full axiom set;
   - `single_removal`: for each of the 11 axioms, the truth value of each proposition with that axiom removed, plus one shortest countermodel trajectory for each proposition that fails;
   - `minimal_sets`: for each of D1, D2, D3, D3+, D5, D6, **all** inclusion-minimal subsets of the 11 axioms under which it holds (brute force over 2^11 is fine; do not assume uniqueness).
3. `METHOD.md`: the method, the exactness argument, every interpretation choice you made, and the runtime.
4. `sha256sum` of each of the three files.

## Independence declaration (sign it)
> "I, [name / model + model id], implemented SPEC.md without access to any other implementation, result, report or discussion of it. The interpretation choices I made are listed in METHOD.md."
