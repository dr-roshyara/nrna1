# Revision 6: causal analysis of the revision-5 audit findings (written before any repair code)

**Authority:** G-LOG-0083.

**Inputs:**
- `audit-p3b/20260926_REVISION-5-INDEPENDENT-AUDIT.md`;
- `audit-p3b/r5-audit/{attacks,full_path,scan,stats}.py` and their `.out` files;
- the audited code at `20c0edebc`.

**Common root cause (one sentence):** the revision-5 verifier treats the batch's **self-description** (`R5-BATCH.json`: runs, sizes, packing order, units, timestamps, record contents, the `working_label` in logs) as the ground truth it checks the batch against. It is internally consistent by construction, so a coherent lie passes.

**Revision-6 principle:** every critical state is **derived by the verifier** from authoritative inputs:
- the hash-verified slice;
- the seal-aware resolver's bytes;
- the reader-written per-run logs;
- the manifest-bound plan hash.

Declarations are compared against that derivation.

## Matrix

| Finding | Root cause (function) | Trusted input (r5) | Authoritative input (r6) | Required invariant | Code location (r6) | Regression test (r6) |
|---|---|---|---|---|---|---|
| **R5-01** no record→claim link | `label_failures` checks records only for `source_id`, `reading_state`, `ack_tokens`, `pair_evidence_checks`; the record schema has no facts; no claim enumeration | whatever the object claims | **record facts whose quotes are verified in the resolver's bytes**; the verifier's own enumeration of the object's claims | **P**: every source-requiring claim (timeline point; FOUND absence; FOUND stage-2 value; birth with an S-id; R2 edge; GENUINELY-UNDEFINED) has ≥ 1 evidence ref `(run, source_id, fact_id)` to a fact of the right kind in a planned reading run of that label, with a WHOLE-FILE record where whole-file evidence is required. Claims come from the object (verifier-enumerated), never from a submitted list. Facts exist only in reading-run records, so a claim cannot be its own evidence | `p3b_s5_r6.enumerate_claims`, `fact_violations`, `claim_evidence_violations` | J1, FP-1, FP-6, I (synthesis-only claim) |
| **R5-02** R19 self-declared, string compare | `structure_failures`: `st["units_validated_utc"] < st["synthesis_dispatched_utc"]` (str) | declared stage strings | **reader-written log `utc`** per run; strictly parsed RFC 3339; provenance stages; the synthesis manifest time | **T**: all times parse to aware UTC (malformed → FAIL); max(unit read utc) ≤ units_validated < synthesis_dispatched ≤ synthesis produced_utc; unit record files unchanged since validation (hash in provenance); the synthesis manifest binds the exact validated record-file hashes | `p3b_s5_r6.parse_utc`, `temporal_violations` | C1–C4, FP-6 |
| **R5-03** undeclared/disguised runs | `runs_of(bdesc)` filters logs by **declared** runs; only the assembled log is read | R5-BATCH run lists; the assembled log | **the manifest-bound plan** (run → role → owner → files); the **per-run `READ-LOG.jsonl` files**; the ledger directory listing | **E**: every `<batch>-R6-*` directory is a planned run; every planned reading run has its own log; every entry has run = its directory, batch = batch, and working_label = the plan owner; synthesis runs have zero reads (refused attempts included); reads ⊆ permitted files; refused entries stop the batch. The reader refuses unplanned run/file pairs (defence in depth) | `p3b_s5_r6_verify.execution_violations`; `p3b_s5_r6.reader_plan_check`; `p3b_read_source.py` (R6 grammar) | E1–E4, FP-4, FP-6 |
| **R5-04** ownership spoofable | `coverage_by_label` groups by the agent-supplied `working_label` | `working_label` in log entries | the plan's run → owner relation; the position-bound `L##` | **E/R**: coverage for label L counts only reads by L's own planned runs **for the files those runs are permitted**. A `working_label` ≠ the owner fails. One run has exactly one owner. `L##` = the label's 1-based position in the manifest entry | `plan_derive`, `execution_violations`, `coverage_for_label` | D2, E6 |
| **R5-05** plan unchecked | sizes, packing order and units are compared with R5-BATCH's own values; `plan_sha256` unread | declared sizes, order, units, hash | the resolver's bytes (sizes); the slice's `source_meta` (packing key); the manifest entry's `r6_plan_sha256` | **L**: the plan must equal the verifier's own derivation (path, files, sizes = byte lengths, packing order = `packing_key` order, units = partition, runs); sha256(plan) = the manifest's `r6_plan_sha256`. The packing order is never written into records (no chronological meaning) | `plan_derive`, `plan_violations` | B1, B1b, B2, B3, FP-6 |
| **R5-06** change-point prefix test | `reading_state_violations`: `startswith("CHANGES-")` | the class-name prefix | the class value set | **R**: every timeline class **except** the explicit non-change set {FIRST, RESTATES, EXTENDS, NOT-COMPARABLE} needs a WHOLE-FILE source; unknown or future classes default to requiring whole-file | `p3b_s5_r6.WHOLE_NOT_REQUIRED`, `reading_rule_violations` | D1, D1b |
| **R5-07** S1 quote unverified; substring ids | `s1_violations`: presence of a quote only; `key[0] in json.dumps(x)` | the agent's quote string; substring match | the resolver's bytes; exact pair-id tokens; the reason enum | **S**: a YES/NO quote occurs in the file's bytes; pair ids match as exact `\bRP\d+\b` tokens; a NO needs a register record (topic VERDICT-EVIDENCE-CONFLICT, exact pair token) and an escalation with reason OTHER and detail exactly `VERDICT-EVIDENCE-CONFLICT: <pair_id>`; NOT-DETERMINABLE carries no quote requirement but may never count as YES | `s1_violations_v6` | F1, F3, F4, FP-5 |
| **R5-08** EMPTY exemption | `r5_checks` returns `empty_label_violations` only | the EMPTY classification | the slice (R(L) = ∅) | **S**: EMPTY objects run S3 lint + edge checks + the claim enumeration; they must contain no timeline point, no dependency edge, no S-id-bearing birth, no FOUND/GENUINELY-UNDEFINED, no stage-2 disposition; the escalation names EMPTY-REQUIRED-SET | `empty_violations_v6` + common checks | H2 |

## MINOR findings folded in (cheap, same slice)

| Finding | Repair |
|---|---|
| R5-09 | range connectors broadened (to, through, until, bis, …, –, —, −, ‒, ―, ~, /, "S9511-13"); pointer regex case-insensitive; P1-gap records included in the lint and its hash |
| R5-10 | exact tokens |
| R5-11 | ack token set **equality** (no extras, no duplicates) |
| R5-12 | an R6 log entry without a page dict fails |
| R5-13 | the scan continues past the first reader call; flags `open(`, `cat` of corpus paths, `$VAR show`; refused entries in any R6 run fail (stop condition) |
| R5-14 | a non-WHOLE-FILE required file needs a CONTRACT-DEVIATION escalation naming it |
| R5-15 | rev-6 coverage re-derived immediately before READ-COVERAGE (historical code path untouched) |
| R5-16 | the statistical specification and code |
| R5-17 | the old verifier is written to a temp directory, not the repo |
| R5-18 | covered by R5-04 (`L##` bound to position) |

## Scope boundary check

- **Does any repair need an architectural change beyond the slice? No.** The plan relation, fact schema, claim-evidence map and provenance stages are **additions to the revision-6 contract** (addendum). Schema E is unchanged: the claim-evidence map and facts live in run files, not in the object.
- **Revision 5** was never activated; it is **withdrawn**. The verifier rejects a revision-5 manifest, and revision 6 supersedes it.
- **Historical revisions 1–4** stay byte-identical.
