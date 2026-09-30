# 02: The harness witness, I(run), and the reader

## Purpose

Every **execution fact** used by verification comes from the harness transcript (Model D, ADR-R7-01), not from files the agents write:
- a read happened;
- in which run, for which label;
- when;
- in which order.

## Key files

| File | Role |
|---|---|
| `p3b_transcript_syntax.py` | **Neutral** decoder (S5-agnostic): `decode`, `calls` (tool_use ↔ tool_result pairing, persisted outputs), `chain`, `tool_use_count`, `ids`, `notifications`, `read_meta`. It does not depend on `p3b_v1_2_4_instrument` (PF-9) |
| `p3b_s5_r7_witness.py` | `witness(...)` derives the execution witness and evaluates W1–W8. It also holds `witness_bytes`, `digests` and `monotonicity_violations` (W7a) |
| `p3b_read_source.py` | R7 grammar, default-deny, plan hash check, self-describing header |

## Implementation grammar (fixed by this implementation; not semantics)

| Element | Form |
|---|---|
| Dispatch binding | the first prompt line `S5-RUN-BINDING run=<run> batch=<batch> label=<label> canary=<hex16>`, with `canary = sha256(activation_commit ‖ run)[:16]` (manifest header `r7_activation_commit`) |
| Orchestrator markers | a Bash command starting `: S5-ORCH UNITS-VALIDATED batch=<B> label=<L>;` (its output prints `<sha256>  <unit record path>` lines), `: S5-ORCH ASSEMBLED batch=<B>;` and `: S5-ORCH FINAL-VALIDATED batch=<B>;` |
| Completion notification | a `queue-operation` `enqueue` record whose content is a `<task-notification>` (`task-id`, `tool-use-id`, `status`, `<tool_uses>N`) |
| Canonical reader call | `python3 -B <r7_reader_abs> --run … --batch … --label … --step 1\|7\|10 --mode bytes --page K S####`, nothing else in the command |
| Archive layout | `<r7_archive or ~/knowledgeos-witness-archive>/<batch>/{main.jsonl, subagents/, tool-results/}` |
| Committed freeze files | `ledger-p3b-r2/<batch>-R7/{WITNESS.jsonl, WITNESS-DIGESTS.json, WITNESS-UNIT.jsonl, WITNESS-UNIT-DIGESTS.json, INPUT-MANIFESTS.json}` |

## W1: the completed-dispatch decision table (reason codes)

Each code is reported separately:
- `W1-TRANSCRIPT-MISSING`, `W1-TRANSCRIPT-DUPLICATE`;
- `W1-NOTIFICATION-ABSENT`, `W1-NOTIFICATION-MULTIPLE` (a resumed agent: RUN-INVALID);
- `W1-HARNESS-COUNT-MISSING`, `W1-COUNT-MISMATCH`;
- `W1-CHAIN`, `W1-PARSE`;
- `W1-ZERO-TOOL`, `W1-DECLINED`.

**Truncation is a cause, not a state.** A clean truncation shows up as COUNT-MISMATCH; a mid-line one as PARSE.

## W8: execution-capability closure

`_classify` puts every tool call into exactly one class; anything else is a violation:
1. **canonical reader:** a corpus observation;
2. **Read of p ∈ I(run):** an input read, never evidence. Persisted outputs are members only if the harness announced them earlier in the same agent's transcript;
3. **Write** to `permitted_writes(run, role)`;
4. **the handback.**

**SEAL:**
- In production the SEAL check is `lambda t: any(c.quarantine_hits(t))`, a count-only helper, so the verifier never obtains the hold-out lists (H-19 guard).
- Corpus-path access uses the carried-over R5/R6 outside-reader patterns (`R7_CORPUS_RX`).
- Tests substitute `R7_SEAL_CHECK[0]`, `R7_CORPUS_CHECK[0]` and `R7_ARCHIVE[0]`.

## I(run) (FR-1, Option A)

- `U.input_manifest(root, batch, slice_root, plan, run)` derives the manifest from the plan and role. Categories: CONTRACT, ADDENDUM, PLAN, SLICE, and for synthesis UNIT-RECORDS of the same label.
- The composer requires the committed `INPUT-MANIFESTS.json` to **equal** that derivation and every entry's hash to match the bytes.
- `input_manifest_violations` reports prohibited paths. It never removes them.

## Reader (defence in depth; the witness stays authoritative)

- `r7_activation(batch)` returns (header, entry) when the manifest entry carries `r7_plan_sha256`.
- `r7_refusal(opts, sids)` does two things:
  - it refuses every non-R7 run id for an activated batch (the legacy `-R2*` path, M2);
  - for an R7 run, it loads `<slice_root>/<batch>.R7-PLAN.json`, checks its sha256 against the manifest, and applies `reader_plan_check`.
- An R7 page header appends `run R batch B label L inv I`. `I` is a reader-generated invocation id, **a convenience join key only** (FD-5′).

## Testing

- `tests/test_p3b_transcript_syntax.py` (decoder).
- `tests/test_p3b_s5_r7_witness.py` (each W invariant on synthetic harness executions).
- `tests/test_p3b_read_source_r7.py`.
- Full path: `tests/test_p3b_s5_r7_full.py` (Witness class).

## Pitfalls

- Transcript line order is **not** time order; use harness timestamps.
- The ack token is `ACK-` + the last 12 hex of the page sha. It is a consistency check, never evidence.
- **Operational:** real agents make exploratory calls (`ls`, `mkdir`), and each one FAILS the run under W8. Dispatch prompts must be strict, and the orchestrator pre-creates run directories.

**Traceability:** addendum §5, §5.8, §7, §9 · ADR-R7-01 · G-LOG-0087, G-LOG-0088 · readiness result `audit-p3b/20260926_MODEL-D-READINESS-RESULT.md`.
