# R7 harness Read-integrity characterization (v2.4 decision package, deliverable D)

| | |
|---|---|
| **Kind** | Engineering measurement. ⚠ authority: generated. **Synthetic data only**: no corpus, no ledger, no slice. **The contract is not modified on the basis of this result** |
| **Harness** | Claude Code `2.1.281` (from the transcript records), 2026-09-27 |
| **Method** | A subagent read synthetic one-long-line files with the Read tool (no offset or limit), ran two Bash calls and handed back a long report. Its **harness transcript** was parsed with the production decoder (`p3b_transcript_syntax`) and the production `W._content_match`, and compared with the known bytes. The long-command case was run in the main session, because the subagent shortened it with brace expansion. The script prints lengths and hashes only (scratchpad `readint/analyse.py`, sha256 `da9642ba…`; manifest `a51a76d8…`) |
| **Result** | **CHARACTERIZED.** Displayed content is byte-exact in every case up to 40,000 characters per line. Beyond a **token cap**, the Read view is **silently truncated to whole lines**: there is no error and no marker in the text, and the signal exists only in `toolUseResult.file` metadata. The verifier never accepts false content. It **does** accept an incomplete input read, which v2.3 permits by design. That is finding **RI-1**, reported and not repaired |

## 1. Read tool: line length versus fidelity

Each file was `HEAD-…` / one long line / `TAIL-…` / an empty last line.

| Case | Line 2 (chars / bytes) | Displayed lines | Line 2 shown exactly | `W._content_match` | `toolUseResult.file` |
|---|---|---|---|---|---|
| 100 | 100 / 100 | 4 of 4 | ✅ | true | numLines 4 = totalLines 4 |
| 1,999 | 1,999 | 4 of 4 | ✅ | true | 4 = 4 |
| **2,000** | 2,000 | 4 of 4 | ✅ | true | 4 = 4 |
| **2,001** | 2,001 | 4 of 4 | ✅ | true | 4 = 4 |
| 5,000 | 5,000 | 4 of 4 | ✅ | true | 4 = 4 |
| 30,000 | 30,000 | 4 of 4 | ✅ | true | 4 = 4 |
| long JSON | 40,000 | 4 of 4 | ✅ | true | 4 = 4 |
| multibyte (ä ö € 𝔸) | 8,000 / 22,000 | 4 of 4 | ✅ | true | 4 = 4 |
| **100,000** | 100,000 | **1 of 4** (only `HEAD-…`) | **line not shown** | **true**, range [1, 1] | **`truncatedByTokenCap: true`**, numLines 1 < totalLines 4 |

**Conclusions:**
- The suspected ~2,000-character line truncation **does not exist** in this harness version: 1,999, 2,000 and 2,001 are all exact.
- Up to at least 40,000 characters per line, the displayed view is byte-identical, including 4-byte UTF-8.
- The limit is a **token cap** on the whole result (the harness's own flag name). It falls between 40,000 and 100,000 characters for this content. It depends on tokenization, not on character count, so no character bound is claimed.
- On truncation the harness **drops whole trailing lines**. It never cuts a line and appends a marker, and `is_error` is false.

## 2. Other channels

| Channel | Test | Result |
|---|---|---|
| Long Bash command (tool_use input) | 10,314-character command. The recorded payload's sha256 was compared with the sha256 computed by the shell over what it executed | **exact** (hash and `wc -c` both match) |
| Persisted output | a Bash print of 60,000 characters | announced (`<persisted-output>`); the file under the session's `tool-results/` is **byte-exact** (60,001 bytes with the newline); the decoder's substituted text equals the expected output; the inline preview is 30,000 characters |
| Long hand-back (the final report) | 4,892-character `SubagentHandback` message holding the integers 1…1200 | recorded **exactly** in the transcript's tool_use input |
| Completion notification | `queue-operation` enqueue | present, `status` completed, `<tool_uses>` 12 = transcript tool_use count 12. **Observation:** in this harness version the notification's `<result>` carries a pointer ("delivered as a message"), not the report; the report lives in the hand-back call. W1 uses only status and count, so it is unaffected |

## 3. Consequences for R7 v2.3 (facts, then one finding)

- **Corpus observations** use the canonical reader through Bash. Their pages are hash-verified, and long output is persisted byte-exactly (§2). No integrity gap was found.
- **Input reads** (Read of p ∈ I(run)) are checked by `content_match`: displayed lines = frozen lines **over the displayed range**. The frozen addendum says exactly this (§5.8: "offset and limit included"), so partial display is admitted by design.

**RI-1 (new; not repaired; for a human decision).**
- **What happens.** A token-capped Read of an input file shows a prefix of its lines. There is no error and no marker. `content_match` is true (a prefix is a faithful subset), and the witness records `displayed_range` but no completeness.
- **Effect.** An agent may act on an **incomplete** contract, plan or slice, and v2.3 will not flag it.
- **What does not happen.** No false content is accepted, and no evidence is affected: input reads never anchor anything (T96).
- **Classification.** Tactical architecture (the W8 input-read semantics). The verifier is **fail-closed for fidelity** and **not fail-closed for completeness**.
- **Candidate repairs, for decision only (not proposed for v2.4 by this package):** (a) record `numLines`, `totalLines` and `truncatedByTokenCap` from `toolUseResult.file` in `read-input` events; (b) require the union of displayed ranges per input file to cover the file; or (c) accept partial input reads explicitly.
- **Practical exposure.** Committed slice or contract files with lines long enough to hit the cap would trigger it. That was not measured, because measuring it would mean reading production inputs.

## 4. Limits

- One harness version, and one run per case (no repetition). The token cap is bracketed, not located.
- A subagent run and the main session may differ in caps. The Read cases were run in a subagent, which is the R7-relevant setting.
- The long-command case ran in the main session.

**Traceability:** addendum v2.3 §5.8 (`content_match`), T90, T96 · implementation record §10 ("Read-tool line truncation … not measured: fail-closed"). That statement is **superseded by this measurement**: fidelity is exact, and completeness is not guaranteed (RI-1).
