# C01 — Reading integrity · v1.1

**Treatment:** REUSED (S5 contract revision 3, G-LOG-0050; reader `p3b_read_source.py` at committed blob `b7e7fc856`,
sha256 `0d48829a…05902`) + F-SPECIFIC digests (RL-08). **Code:** `scripts/f_read_source.py`, `f_checks.coverage`.

## 1. Rules
1. The only sanctioned reader is `f_read_source.py --run FR-F####-NNN (--info | --page K) F####`. No `cat`, `Read`,
   `grep` or any other access to an F-file's content.
2. Pages are 20,000 characters of decoded text (the S page model, compiled from the pinned blob). The reader logs
   every page itself to `ledger/F####/READ-LOG.jsonl` (page, span, page sha256, content sha256, stdout kind).
3. A file whose bytes differ from the manifest sha256 is refused (`IDENTITY-DRIFT`).
4. Delivery is not consumption. After reading page *k*, append to `ledger/F####/PAGE-DIGESTS.jsonl`:
   `{run_id, page, digest, verbatim_quote}` (quote ≥ 40 characters, from page *k*).

## 2. Evidence hierarchy (RL-08)
```
page hash logged by the controlled reader
  ↓ coverage complete: pages 1..N, 0 missing, hashes re-verified   ← PRIMARY integrity proof
  ↓ summary generated from the page                                 ← supplementary, semantic
  ↓ quote verified against that page                                ← supplementary, semantic
```
The digest proves that a summarization operation was performed over the page. It does not prove attention and never
substitutes for coverage.

## 3. Gate `READ-COMPLETE`
expected pages = consumed pages · missing = 0 · every page hash re-computed from the current bytes · a valid digest
for every page. The integrity record goes to `F-READ-INTEGRITY.jsonl`.

## 4. Otherwise
`READ-PARTIAL` (with the measured coverage and a reason, e.g. `CAPACITY`, RL-06) or `READ-FAILED`. Never a whole-file
claim. F3026 is run as a controlled capacity test when reached.

## 5. Harness note
This environment captures stdout into a file; the pinned reader refuses that (exit 3), so pages are read as
`… --page K F#### | cat`. Whether to re-pin to the S withdrawal of the refusal is FD-12.
