# Protocol B execution record (no interpretation)

| | |
|---|---|
| Script | `A3/scope_b.sh` (r3), sha256 `e9e0975b3ea71eedd84905b4cb837cdf4aa301a434f3c1403841b99127dc37a8`, executed verbatim from the session scratchpad copy (hash-identical) |
| HEAD at run | `9f1bcab508095c56a4dc74471ba4ada6fd5745e5`, unchanged before and after both runs |
| Runs | (1) cwd = repo root; (2) cwd = `app/`. OUT is in the session scratchpad, outside the work tree |
| Exit codes | root 0 · app 0. `00_errors.tsv` is empty |
| Determinism | `diff -r` of the two OUT directories: **IDENTICAL**. `SHA256SUMS-RUN-APP` equals `RUN-ROOT/SHA256SUMS` |
| Wall time | ~2 m 43 s per run |
| Identity guard | PASS |
