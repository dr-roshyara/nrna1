# DIAG-A — S5 canary restart: failure families, X (instrument/contract) vs A (agent)

Read-only diagnosis. Evidence used: the verify reports `audit-p3b/S5-VERIFY-OB0012-R7.A2.json` (195 strings) and
`audit-p3b/S5-VERIFY-OB0114-R7.json` (145), the evaluation, the rev3 contract (`prompts/20260925_1204_…r2.md`, "rev3"),
the R7 addendum (`prompts/20260926_2400_…r7-addendum.md`, "R7"), `scripts/p3b_s5_r7_*.py`, `p3b_s5_verify.py`, and
`render_prompt`. For provenance only, R5/R6 addenda and the R5 runbook were grepped, to show where the missing
schemas live. No run outputs, slices, views, corpus, archive or review files were opened. S-ids and label names are
not reproduced. Label positions: `L##` = the index in the report's `labels` list, which is manifest order. This is
consistent with the evaluation: OB0114 R7-U is at L03 (the SYNTHESIS), and there are no failures at L04 (EMPTY).

## 0. The inputs the agent actually has (root fact)

- `render_prompt` (`p3b_s5_r7_orchestrate.py:230-262`) lists the I(run) paths. It says "WRITE ONLY these files (Write tool)" and "NO other tool calls … no other Bash … Every other call FAILS the run (W8)" (l.255-259), and "Follow the rev3 contract and the R7 addendum …; this prompt restates no rule" (l.260).
- I(run) = CONTRACT (rev3), ADDENDUM (R7), PLAN, SLICE, SLICE-VIEW, plus UNIT-RECORDS for the synthesis only (`p3b_s5_r7_universe.py:639-645`). The PLAN holds only plan fields (`plan_derive_v7`). The rev7 slices are the rev3 slices with the identity rewritten (`p3b_s5_r7_activate.py r7_slice_text`). Neither carries an output schema.
- R7 incorporates R5/R6 content **by reference**:
  - "Items of revisions 5 and 6 carry over only where §13 says so" (R7:13).
  - §13 "Carried over from R6: … S1 (exact tokens, quotes in bytes …); S2; S3 …" (R7:892).
  - The fact kinds are called "values of the existing fact-kind dimension" (R7 §3.3).
- The R5 and R6 addenda are **not in I(run)**. Reading them is a W8 violation (R7 §5.5, l.497-500).
- So every requirement that lives only in R5/R6 is **UNREACHABLE** for an agent that follows its rules.

## 1. Families (counts per batch; runs affected)

| # | Family (check) | OB0012 | OB0114 | Runs affected | Verdict | X/A |
|---|---|---|---|---|---|---|
| F1 | R7-E claim has no valid evidence path | 98 | 72 | 0012: L01–L05 · 0114: L01, L02, L05 (not L03S) | UNDERSPECIFIED / UNREACHABLE | **X** |
| F2 | R7-E ack tokens ≠ witnessed page tokens | 16 | 25 | all 10 reading runs | UNDERSPECIFIED (value reachable, field/format not) | **X** |
| F3 | R7-E fact quote not anchored on a witnessed page | 6 | 0 | 0012 L03, L05 | SPECIFIED | A |
| F4 | R7-R S1 no pair-evidence check | 52 | 3 | 0012 L01–L05 · 0114 L01, L05 | UNREACHABLE | **X** |
| F5 | R7-R S3 pre-submit lint missing / not bound | 5 | 4 | every non-EMPTY final run (9) | UNREACHABLE (twice over) | **X** |
| F6 | R7-R edge_class None | 1 | 1 | 0012 L02 · 0114 L02 | UNREACHABLE | **X** |
| F7 | SCHEMA + G-05 + G-09: register `output_layer` missing (5 records × 3 strings) | 15 | 15 | 0012 L04 · 0114 L02 | SPECIFIED | A |
| F8 | R7-U status_basis sources outside the object's cited sources | 0 | 17 | 0114 L03S | UNDERSPECIFIED (term undefined) | **X** (medium confidence) |
| F9 | G-12 + R7-R S3 register id or pointer in layer A (3 objects × 2 strings) | 0 | 6 | 0114 L01, L03S, L05 | SPECIFIED (rs_id); mostly specified (phrases) | A |
| F10 | G-05 `births.<kind>` null | 0 | 1 | 0114 L03S | SPECIFIED | A |
| F11 | R7-R S3 S-id range | 0 | 1 | 0114 L02 | SPECIFIED | A |
| F12 | R7-W W8 Edit call | 1 | 0 | 0012 L03 | SPECIFIED | A |
| F13 | R7-W W5 S3-LINT.json changed after its last Write | 1 | 0 | 0012 L03 (the consequence of F12) | SPECIFIED | A |
| | **Total** | **195** | **145** | | | |

**X share:**
- OB0012: 172 of 195 (88.2%).
- OB0114: 122 of 145 (84.1%).
- **Overall: 294 of 340 (86.5%).**
- If F8 is counted as A: 277 of 340 (81.5%).
- Floor: the definitively unreachable families alone (F2, F4, F5, F6: 107 strings) are 31.5%.

**Runs:** every one of the 11 runs carries at least one X string (F2 or F5 in each). Runs with **only** X strings: OB0012 L01 and L02, OB0114 L03U01 and L03U02. Seven runs also carry A strings.

## 2. Per family: the requirement and where (if anywhere) the inputs state it

**F1, claim → fact → record → witnessed bytes.**
- **What the verifier requires.** It reads `claim-evidence.json` of the final run as a dict `{claim_id: [{run, source_id, fact_id}, …]}` (`p3b_s5_r7_verify.py:193`; `p3b_s5_r7_evidence.py:163-177`). It then resolves each reference to a fact in that run's `file-reading-records.jsonl` → `facts[]` (`evidence.py:110-121`). The fact must match:
  - the kind;
  - the claim's fields exactly: `birth_kind` = the lower-case births key; ABSENCE `dimension` plus `finding == "DEFINES"`; DEPENDENCY `target_label`;
  - an anchor on the owning run's witnessed bytes;
  - WHOLE-FILE with complete coverage where the claim needs it (`evidence.py:168-173`).
- **What the inputs say.** R7 gives only fragments:
  - the claim id grammar (§3.3);
  - the fact kinds with their field names, but no value domains (§3.3);
  - uniqueness "(run, source_id, fact_id) for facts" and "the claim id in claim-evidence" (§3.1);
  - "the `run` of every claim-evidence reference" (§2.6).
- **What they do not say.** Nowhere do the inputs give the file shape of `claim-evidence.json`, or say that facts live in `file-reading-records.jsonl` under `facts` with `fact_id`/`kind`/`quote`. That schema is in the R6 addendum (row "P: provenance"), which is unreachable. The value domain of `birth_kind`, and the rule that a FOUND needs `finding: DEFINES`, are also not stated.
- **Stage-2 ids** `stage2:<S>:<hit_kind>:<json(hit_key)>:<term_index>:<dim>` (R7 §3.3) leave `json()` undefined. The verifier uses Python `json.dumps` defaults, i.e. `\uXXXX` escapes (`universe.py:360`). 33 of the 94 failing stage-2 ids contain such escapes. An agent writing the literal character cannot match.
- **Systematic, from the report counts alone.** FOUND dispositions equal the failing stage-2 claims exactly: 52 = 52 (OB0012) and 42 = 42 (OB0114). So 100% of stage-2 claims failed. Every label with claims failed in OB0012.
- **Verdict:** UNDERSPECIFIED within the inputs, UNREACHABLE for the full schema → X.
- **Uncertainty:** some of these claims may *also* fail for agent reasons (F3-type unanchored quotes, wrong kind). Only the run outputs could split the 170.

**F2, ack tokens.**
- **What the verifier requires.** For each WHOLE-FILE record with complete coverage, `sorted(record["ack_tokens"]) == sorted(one ACK per byte page 1..n)` (`evidence.py:138-141`). The token format is `ACK-` + the last 12 hex of the page sha (`p3b_s5_r5.py:153`).
- **What the inputs say.** The *value* is reachable: R7 W3 (§5.1) gives the format, and the reader trailer prints it (§9).
- **What they do not say.** The *field name* `ack_tokens`, and the rule that a page read at step 1 and again at step 7 is listed **once**, are in no input. R6 has only "equal the page tokens as a multiset"; the field is in R5.
- **Systematic.** Every reading run fails. Per run, the ack failures track the run's file count (for example, OB0012 L05 reads one file of one page twice and has 1 ack failure).
- **Verdict:** UNDERSPECIFIED → X.
- **Uncertainty:** whether the cause is a missing field, a different name, or duplicated tokens is decidable only from the outputs. All three are X.

**F3, fact quote not anchored.**
- **Where it is stated.** R7 §6 states the rule fully: exact bytes, or N-WS only, inside pages witnessed by the owning run; input reads are never evidence (R7:614-632).
- **Verdict:** SPECIFIED → A. Its only dependency on F1 is that `facts` existed, which it evidently did in these runs.

**F4, pair-evidence checks.**
- **What the verifier requires.** For each P3a pair whose `what_says_this`/`basis` cites a file of the label, `records[*].pair_evidence_checks[{pair_id, supports, quote}]` (`p3b_s5_r6.py:257-289`; `reconstruction.py:137-146`).
- **What the inputs say.**
  - rev3 has 0 occurrences of `pair_evidence_checks`.
  - R7 has only "(pair_id) for pair checks" (§3.1) and "S1 … carried over from R6" (§13).
  - The field, the scope and the YES/NO/NOT-DETERMINABLE scale are in the R5 addendum item 13 (l.105-108), which is not in I(run).
- **Verdict:** UNREACHABLE → X.

**F5, S3-LINT.**
- **What the verifier requires.** `S3-LINT.json` with `result == "PASS"` and `records_sha256 == sha256(canon([object] + register + p1-gap records of the label))` (`p3b_s5_r7_verify.py:205-207`).
- **Missing schema.** The inputs name the file only (R7 §0 trust table, §2.7, §5.5). The `{result, records_sha256}` schema is in the R5 runbook step 10.
- **Uncomputable value.** Even with the schema, the agent **cannot compute** a sha256 of canonical JSON, or run `s3_lint`: the prompt allows no Bash beyond the reader.
- **Verdict:** UNREACHABLE by construction → X. This is a design/verifier defect, not an agent defect.
- **Link to F12.** The only Edit in the canary (F12/F13) touched exactly this file.

**F6, edge_class.**
- **What the verifier requires.** `dependency_edges[].edge_class ∈ {R1-STRUCTURAL, R2-EVIDENCED}` (`p3b_s5_r5.py:352-364`).
- **Where it is defined.** Only in R5 §11. rev3 and R7 have 0 occurrences. R7 §0 lists "edge classes" as a Reconstruction concern, but R7 §13 does not list edge classes among the carried-over items.
- **Verdict:** UNREACHABLE (and arguably CONTRADICTORY with R7:13) → X.

**F7, register `output_layer`.**
- **Where it is stated** (four places):
  - rev3 §D `"output_layer (register)": ["B","C"]` (l.244);
  - rev3 §E `register_record.output_layer: "B|C"` (l.304);
  - the §13.2 kind→layer table (l.795-806; OBSERVATION → B);
  - the §13.6 core (l.887).
- **Verifier.** `p3b_s5_verify.py:933-946`. One omission in 5 records yields 3 strings each (SCHEMA, G-05, G-09).
- **Verdict:** SPECIFIED → A.

**F8, status_basis sources.**
- **What the verifier requires.** The S-ids in `status_basis.<field>.sources` must be ⊆ the sources of the verifier's enumerated claims (`universe.py:305-323`, `cited = {c["source"] for c in claims(...)}`).
- **What the inputs say.** R7 §3.3 row "⊆ verified cited sources" (l.300, 302). The term is **never defined** in the inputs.
- **Contradiction.** rev3 §E asks for `status_basis.sources:[S####]` as the basis of each status, with §11.2 minimum evidence. A synthesis that escalated its dispositions has few claims, so its status bases fall outside.
- **Verdict:** UNDERSPECIFIED, with a rev3/R7 tension → X, medium confidence. A reader could infer "sources that carry claims"; the word "verified" points to claims.
- **Possible upstream cause.** It may be downstream of F1/F2: L03S reported that the unit records carry no facts.

**F9, layer-A pointers.**
- **Where it is stated.** rev3 G-12 (l.1347): "no layer-B/C content (register ids …) in a layer-A field"; and "Layer A never depends on the register" (l.367).
- **Classification.** The rs_id case is clearly A. The two phrase cases ('register entry', 'research register') match the code's POINTER list (`p3b_s5_verify.py:108`; `reconstruction.py:34`), which is not given verbatim. Also, rev3 §B pairs every SCHEMA-LIMITATION escalation with a register record, which invites pointer text in escalation details.
- **Verdict:** A, with a small underspecification.

**F10, F11.**
- F10: `births.<kind>` has no null in the rev3 §D enum (l.139-147) → A.
- F11: rev3 §C "never write an S-id range" (l.100-103) → A.

**F12/F13, W8 Edit and W5.**
- **Where it is stated.** R7 §5.5 names Edit explicitly as a violation (l.500). The prompt is a whitelist ("Write tool"; "NO other tool calls … Every other call FAILS the run").
- **Consequence.** W5 (`witness.py:338-341`) is the direct consequence: the file no longer equals its last witnessed Write.
- **Verdict:** SPECIFIED → A.
- **Why the prompt should still address it.** Correcting one's own output with Edit is standard, plausibly expected LLM-agent behaviour. The prompt's forbidden-examples list names only shell commands and web. The prompt should say "Edit/MultiEdit are forbidden; to correct a file, Write it again in full" (re-Write is legal: W5 compares against the last Write). This matters especially because the Edit went to the unreachable S3-LINT file.

## 3. The agents' claim: output-file schemas missing from the inputs

| Output file | Schema in the inputs? | Where |
|---|---|---|
| `objects.jsonl` | **yes** | rev3 §E object_record, plus the R7 §4.2 amendment |
| `register.jsonl` | **yes** | rev3 §E register_record, §13.2, §13.6, §D |
| `p1-gap.jsonl` | **yes** (record); file name contradictory | rev3 §E p1_gap_record. rev3 §C names it `p1-gap-capture.jsonl` and says "Write nothing else"; R7 §5.5 and §2.7 supersede this with `p1-gap.jsonl` and extra files |
| `file-reading-records.jsonl` | **no** | fragments only: `reading_state` WHOLE-FILE / NOT-CONSUMED (R7 §6, §6.1), fact kinds and fields (R7 §3.3), the fact key (R7 §3.1). No `source_id`/`reading_state` enum (READ-PARTIAL and READ-FAILED are in R5), no `ack_tokens`, no `facts[{fact_id, kind, quote}]` container, no `pair_evidence_checks` |
| `claim-evidence.json` | **no** | only the claim-id grammar and the reference `run` |
| `S3-LINT.json` | **no** | name only; the schema is in the R5 runbook, and the value is uncomputable without Bash |

**The agents' claim is confirmed for 3 of the 6 output files.**

The verifier's structural checks confirm that agents got part of the reading-record schema right by inference. There are no "≠ exactly one per permitted file", "reading_state off-scale", "missing or duplicate fact_id" or "kind outside the closed fact kinds" strings.

## 4. Minimal repair class per X family (not implemented)

A prompt pointer alone is **insufficient** for F1, F2, F4, F5 and F6: the rules are not in any reachable section to point to.

| Family | Minimal repair | Class |
|---|---|---|
| F1, F2, F4, F6 | An agent-output schema annex in R7: the file-reading record (`source_id`, `reading_state` enum, `ack_tokens` = one per page 1..n listed once, `facts[{fact_id, kind, quote, <fields with value domains>}]`, `pair_evidence_checks` with scope and the NO obligations); `claim-evidence.json` = `{claim_id: [{run, source_id, fact_id}]}` plus the exact `json(hit_key)` serializer; `edge_class` and its rules. Alternative: add R5 §11/§13 and R6 P/R/S as a declared I(run) input, which changes the Universe categories as well | contract change + rebind (the addendum sha changes the binding, I(run) and the prompts) |
| F1 (stage-2 ids), optional | Alternatively, have the verifier match stage-2 claim ids on the parsed tuple, not the serialized string | verifier change + re-audit |
| F5 | Remove the agent-computed `records_sha256` binding: the verifier or orchestrator runs S3 itself. Or grant a canonical lint invocation in AllowedToolCalls | verifier/contract design change + re-audit (the cheapest correct option is the verifier computing S3) |
| F8 | Define "verified cited sources" in R7 §3.3 (= sources of the enumerated claims), or relax the rule | contract change + rebind |
| F12/F13 (A, but cheap) | A prompt line forbidding Edit/MultiEdit and saying "re-Write whole" | prompt/instrument repair (RR-6, canary gate) |

A families (F3, F7, F9–F13) need no instrument repair. Optional prompt pointers (for example "register output_layer: rev3 §13.2") are allowed only if they restate nothing.

## 5. Uncertainty

- **F1.** The split of its 170 strings between schema failure (X) and genuine agent error (A) needs the run outputs. The 100% stage-2 failure rate strongly suggests systematic X.
- **F2.** Which exact mismatch (field absent, different name, or tokens duplicated across step 1 and step 7) needs the outputs. All of them are X.
- **F8** is a judgement call (X, medium). It may be a downstream effect of F1 (unit records without facts, as reported by the agents; not verified).
- **Masking.** Strings mask other strings: a repaired X family could expose further A failures. The observed A strings alone already touch 7 of 11 runs (≥ 6), so after an X repair the stop rule could still trigger on A. This is an observation, not a prediction.
- **Label mapping.** The label → L## mapping uses the report's `labels` order, consistent with the evaluation's SYNTHESIS and EMPTY positions.
