# C05 — Cross-file relationship discovery · v1.1

**Treatment:** F-SPECIFIC research objective. Inherits unchanged: v3.5 R5 (no identity resolution before
reconciliation), R9 (duplicate ≠ independent evidence), R10 (later evidence never rewrites earlier records), and the
v3.5 A11 negative-verdict bar (a bounded search yields UNWITNESSED / NEGATIVE-BOUNDED, never INDEPENDENT).
**Code:** `scripts/f_crossfile.py`, `f_checks.cross_file_candidates`, `check_analysis`. **Gate:** part of `ANALYZED`.

## 1. Incremental pass (every file, human choice "Both")
`f_crossfile.py F####` lists deterministic candidates: normalized term keys (C03 §5) shared with each **earlier,
AUDITED** F-ID. Every candidate gets one record in `CROSS-FILE.jsonl`:
```json
{"candidate_id":"FXC-…","disposition":"RELATED|NOT-RELATED-AFTER-INSPECTION|UNDETERMINED",
 "relation_hint":"SAME|REFINEMENT|EXTENSION|REDEFINITION|REPLACEMENT|SPECIALIZATION|DERIVED-FROM|CONTINUATION|HOMONYM|CONTRADICTION|ANALOGY|UNWITNESSED",
 "basis":"what was compared and why this disposition",
 "this_quote":"verbatim from this file","other_quote":"verbatim, from the other file's audited ledger",
 "source_claimed":true|false}
```
RELATED needs a hint and both quotes. The hint is a **research observation (Level 2), not an identity verdict**; the
relationship itself is decided only in a later reconciliation stage. `source_claimed: true` only if the file itself
states the relation (then it is also an XC lineage claim at Level 1). The agent may add relationship observations the
mechanical candidates miss (as ANALYSIS records, scale CROSS-OBJECT, with both quotes), never remove a candidate.

## 2. Checkpoint pass (every CP, human choice "Both")
Over all files AUDITED so far: term-key graph, co-occurrence of inventory items across files, definition drift per
term (all DEFINITION items of one key, in list order), unresolved candidates revisited. Output
`checkpoints/CP-##/CROSS-FILE-PASS.jsonl` + a report. Findings are Level 2; hypotheses they suggest are registered per
C10 at the checkpoint (`registered_after_f` = the last AUDITED F-ID).

## 3. Rules
No look-ahead. Exact duplicates (RL-03) and `CONTENT-IDENTICAL-TO-S` files (RL-04) are marked in every cross-file
count, so that repetition is not counted as independent recurrence (R9).
