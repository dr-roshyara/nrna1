# 05 — SLICE-VIEW (v2.7) and the orchestrator tool (EG-1, EG-2)

**Purpose.**
- A frozen slice is one line of canonical JSON. The harness Read silently drops a line above its token cap (RI-1).
- **SLICE-VIEW** renders each slice losslessly as short lines.
- **`p3b_s5_r7_orchestrate.py`** produces the per-batch artifacts the verifier requires: I(run), the views, the prompts, the archive and the witness freezes.

**Where it fits:**
- The Universe context: `scripts/p3b_s5_r7_universe.py` (`slice_view`, `slice_view_parse`, `slice_view_violations`, category `SLICE-VIEW`).
- The composer check: `scripts/p3b_s5_r7_verify.py`.
- The tool: `scripts/p3b_s5_r7_orchestrate.py`.
- The contract: addendum §5.8/§5.9, DR-21.

## SLICE-VIEW

**Format:**
- the header line comes first;
- then one line per JSON leaf: `<path>\t<kind>\t<payload>`;
- kinds: `N` scalar, `E` empty container, `S k/n` a string chunk of at most 1,000 characters (JSON-encoded);
- U+2028, U+2029 and U+0085 are escaped.

```python
v = U.slice_view(slice_text)                   # deterministic
assert U.slice_view_parse(v) == json.loads(slice_text)
F = U.slice_view_violations(slice_text, v, "OB0012/label")   # [] or R7-U messages
```

The verifier requires view bytes = `slice_view(slice)` for every planned label. A missing or altered view is `R7-U`.

## The orchestrator (runbook §1)

```bash
python3 -B scripts/p3b_s5_r7_orchestrate.py prepare OB0012 --emit ~/s5-prompts --state P3B-STATE.json
python3 -B scripts/p3b_s5_r7_orchestrate.py archive OB0012 --session ~/.claude/projects/<proj>/<session>.jsonl
python3 -B scripts/p3b_s5_r7_orchestrate.py freeze-units OB0012 <label>   # after the UNITS-VALIDATED marker
python3 -B scripts/p3b_s5_r7_orchestrate.py freeze-final OB0012             # after the final archive
python3 -B scripts/p3b_s5_r7_orchestrate.py verify OB0012
```

**`prepare`:**
- writes the views: it never overwrites a differing view;
- writes the I(run) of the reading runs;
- writes one prompt per run outside the repository. Each prompt starts with the binding line and has passed the quarantine scan.

**`freeze-units`:**
- freezes the SYNTHESIS I(run) (it hashes the unit records);
- writes the unit-validation witness. It only extends an earlier freeze (W7a).

**`freeze-final`:** writes `WITNESS.jsonl` and `WITNESS-DIGESTS.json`, once.

**`archive`:** copies `main.jsonl`, `subagents/` and `tool-results/`. A refresh may only append.

**Design decisions:**
- **Reuse, not re-implementation.** The tool calls `W.witness` with exactly the inputs the verifier uses, so any drift appears as an `R7-W W7` failure at verify time.
- **No dispatch.** Agents are dispatched by the orchestrator session's Agent call.

## Testing

```bash
cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_slice_view test_p3b_s5_r7_orchestrate
```

The replay test checks two things: the tool's artifacts are byte-identical to the fixture's, and the verifier returns BATCH-PASS on them.

## Pitfalls

- **Production binding (EG-4, resolved by G-LOG-0105).** `prepare` refuses with `revision-binding violation` whenever the manifest's `contract.sha256` ≠ `U.ADDENDUM_SHA256`. A future addendum change needs the same narrow rebind: `p3b_s5_r7_activate.py --rebind-contract --reason <G-LOG> --staging <new dir outside the repo>`. It changes the header only, and it requires every batch to be PREPARED at R7.
- **Hub slices (EG-3).** Hub views can need thousands of Reads. The hub reading protocol is open.
- **State evidence vocabulary (EG-7).** `p3b_s5_state.check_evidence` compares the verify report's `result` with `passing_result(to, run)`. That is `BATCH-PASS` for VERIFIED on a revision-7 batch run (`OB####-R7`), and `PASS` otherwise (R2 runs, comparison runs, AUDITED). `p3b_s5_audit_record.py` still refuses `--run <B>-R7`: whether the AUDITED stage applies under R7 is an open question.
- The tool prints counts only. Never paste its witness failures into commits (they can name S-ids).

**Traceability:** G-LOG-0104 · addendum v2.7 `9523c712…` · `audit-p3b/20260928_EG-1-EG-2-IMPLEMENTATION-RECORD.md`.
