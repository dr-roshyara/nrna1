# Task: an independent finite-state enumeration

Write your own program, in any language (Python preferred). Read SLICE-SPEC.md first. It points into R5-SPEC.md and V1B-SPEC.md for the definitions.

Compute everything in SLICE-SPEC §3 for both frames, U and U_P2, and for all 12 readings.

## Output: `SLICE-RESULTS.json`, with exactly this structure
```
{"<frame>": {"n_frame_histories": <int>,
             "readings": {"<reading>": {
                "sizes": {"R_B": <int>, "H-D": <int>, "H-B": <int>, "H-D&H-B": <int>, "H-D&R_B": <int>},
                "(i)-S":  {"holds": <bool>, "scope_size": <int>, "n_counterexamples": <int>, "least_counterexample": <history or null>},
                "(ii)-S": {"holds": <bool>, "scope_size": <int>, "n_counterexamples": <int>, "least_counterexample": <history or null>},
                "(iii)-L":{"holds": <bool>, "scope_size": <int>, "n_counterexamples": <int>, "least_counterexample": <history or null>},
                "(i)-O":  {"holds": <bool>},
                "(ii)-O": {"holds": <bool>}}}}}
```
- Frame keys: `U`, `U_P2`.
- Reading keys: `R0`, `R-b`, `R-c`, `R-d`, `R-e`, `R-f`, `R-g`, `R-g+`, `R-h`, `R-i`, `R-i'`, `R-j`.
- **A history** is a JSON object with exactly the keys sigma, a1, a2, Cap, w1, Ref, con, r, c:
  - `sigma`: [v0, v1];
  - a slot: `"absent"`, or {"kind": "iss", "d", "v", "prep", "f"} with f ∈ {0, 1, "inf"}, or {"kind": "adp", "d"};
  - `Cap`: a list drawn from "(x,iss)", "(x,adp)";
  - `w1`: `"absent"` or {"t": t};
  - `Ref`: a list drawn from "(rho1,a1)", "(rho1,a2)", "(rho1,w1)";
  - `con`: {"a1": "none" | "rho1", "a2": "none" | "rho1"};
  - `r`: 0 | 1;
  - `c`: "c1" | "c2".

## Also return
- `NOTES.md`, containing:
  - every ambiguity and the literal reading you chose;
  - anything you could not compute;
  - your runtime;
  - your language and version.
- The program file(s).

## Rules
- Do not tune any reading to obtain a particular result.
- Where the spec is ambiguous, choose the most literal reading and record it.
- Work only from the files provided. There are no expected answers: compute them.
