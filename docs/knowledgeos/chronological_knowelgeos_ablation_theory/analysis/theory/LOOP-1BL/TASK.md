# Task: independent finite-state verification (R5)

You are an independent verifier. You have ONLY `R5-SPEC.md` (definitions, statements, and §7: the universe U and checks K1–K12). **No expected outcomes are given. Compute; do not assume.**

1. Write your own Python 3 program, **`verify.py`**, standard library only, which:
   - enumerates U exactly as §7.1 specifies (coordinates, ranges, enumeration order), keeping the assignments that satisfy the frame axioms F1 and F2;
   - implements the clauses of H-D and H-B and the classes H-D, H-B and R_B literally from the definitions;
   - implements the observations, additional observables, frame variants U₅/U₆/U₇/U₉ and instance I7 as specified;
   - runs **every** check K1–K12.2. For each check it reports TRUE / FALSE / (for "report" checks) the computed answer. Where §7.2 asks, it gives the least witness or the first counterexample in the §7.1 order.
2. Run it with `python3 verify.py`. Save its output as **`RESULTS.json`**: {"n_frame_histories", "checks": {"K1": {"result", "detail", "witness_or_counterexample"}, …}}.
3. Write **`NOTES.md`**:
   - every place where the spec was ambiguous, and the reading you chose;
   - any check you could not implement, and why;
   - your runtime.

**Rules:**
- Use only files in this directory. Do not read anything outside it. No network.
- Run only `python3`.
- If a check is ambiguous, implement the most literal reading and record it in NOTES.md. **Do not tune your reading to obtain any particular result.**
