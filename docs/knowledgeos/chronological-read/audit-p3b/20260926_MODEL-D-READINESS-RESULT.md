# Model-D readiness experiment: result

| | |
|---|---|
| **Kind** | Readiness experiment (EP-03 input to Revision-7 design). ⚠ authority: generated. **Uncommitted. It authorizes nothing and implements nothing** |
| **Question** | Is the Claude Code harness transcript sufficient to serve as the authoritative execution witness for S5 reader activity (Model D, `20260926_REVISION-6-REAUDIT-ROOT-CAUSE-ARCHITECTURE.md` §3.4)? |
| **Method** | real harness subagents on a fully synthetic batch; the real reader code; the production transcript parser plus an experimental raw extractor; 18 attack cases on copies |
| **Verdict** | **READY, conditional** on C1–C7 (§11). No required component was missing. Two paths were not directly observed in a subagent transcript; both fail closed (§10) |
| **State** | revision 6 NOT ACTIVATED · S5 NOT AUTHORIZED · H-19 SEALED · no production corpus read · no S5 state changed (§14) |

**Trust model (stated explicitly).**
- **Trusted:** the harness, the orchestrator and the OS account.
- **What the transcript is and is not:** it is **not universally independent**. It is independent only of the *LLM agent's self-authored evidence and log files*: an agent cannot, through its tools, suppress, edit or forge the harness's record of its own tool calls and results.
- **The orchestrator:** it can edit transcript files like any other file of the account. Protection against that is only relative to digests frozen and committed at validation and assembly.

---

## 1. Objective

Prove or disprove experimentally, on synthetic fixtures, that a harness transcript lets a verifier reconstruct:

> reader invocation → exact run/batch/label/source → page hash → acknowledgement or refusal → harness time → agent identity → ordering

which is what the architecture report's E\*, R\*, F\* and T\* need. The purpose is not to make Model D pass.

## 2. Synthetic fixture

| Element | Realisation |
|---|---|
| Batch | `OB9901` (synthetic) |
| Labels | `syn-single` (SINGLE, run `OB9901-R6-L01`, source S9911, 3,000 B, 1 page) · `syn-decomp` (DECOMPOSED: units `L02U01` → S9921, 60,000 B, 3 pages; `L02U02` → S9922, 30,000 B, 2 pages; synthesis `L02S`) · `syn-empty` (EMPTY: no runs, no reads). S9923 exists and is permitted to no run |
| Plan | hand-written R6 plan at `<scratch>/root/_batch_input_r2/s5/rev6/OB9901.R6-PLAN.json` (only `reader_plan_check` consumes it) |
| Bytes | generated text ("SYNTHETIC MODEL-D FIXTURE S####: this line is generated test text, not corpus content.") |
| Reader | `syn_reader.py` loads **the real `scripts/p3b_read_source.py`** and calls its real `main` → `main_paged` → the revision-6 `reader_plan_check` → `page_bytes_mode` (the real byte paging, header, trailer and log writer). **Only two substitutions:** (1) `dio.discovery_resolver` is a fake serving the synthetic bytes, and raises on any other id; (2) `dio.s3.CR` is the scratch root, so the plan is read there and the READ-LOG is written there, never to the production ledger |
| Agents (real harness subagents, via the Agent tool) | SINGLE L01 (first dispatch, haiku: it **declined the task** as a suspected injection, making 0 reader calls; second dispatch, sonnet: 1 read and 1 record write) · UNIT L02U01 (haiku: its first reader call was **denied by the harness's auto-mode classifier**, "Credential Exploration"; not retried, see §10) · UNIT L02U02 (haiku: a 2-page multi-page read and a record write) · SYNTHESIS L02S (sonnet: **one reader call, refused by the reader**, "synthesis runs may not read", and an object write) |
| Orchestrator events (main transcript) | 5 Agent dispatches · `MDR-ORCH-VALIDATE` (prints the unit record hashes) · synthesis dispatch · `ASSEMBLY.json` Write · `MDR-ORCH-FINAL-VALIDATE` · plus the pre-run smoke test (a successful read, a refused read and a **malformed** `--page 0` call) |
| Fixture checklist | SINGLE ✔ · DECOMPOSED with 2 units ✔ · EMPTY ✔ · successful byte-mode read ✔ · refused read ✔ (subagent) · malformed call ✔ (orchestrator transcript only, §10) · multi-page read ✔ · agent dispatch ✔ · synthesis dispatch ✔ · validation command ✔ · final write/assembly ✔ |

## 3. Harness observation results (Q1–Q7)

| Q | Question | Result | Evidence |
|---|---|---|---|
| **Q1** | does the transcript carry every byte-mode read event? | **YES** | Per call, the **tool_use** record carries the exact command (so `--run`, `--batch`, `--label`, `--page`, S-id), `agentId`, `uuid`/`parentUuid` and the harness `timestamp`. The **tool_result** carries the reader's full output: header `=== S#### page k/n bytes a-b (content sha256 …; page sha256 …) ===`, the page body, and trailer `=== END S#### page k/n ACK-… ===`, with its own harness timestamp. All 3 successful subagent reads were recovered completely. Refused and failed calls carry `is_error: true` and the text `Exit code N` |
| **Q2** | is `REFUSED: …` captured? | **YES, but not as a separate stderr channel** | Synthesis refusal: content `Exit code 1\nREFUSED: synthesis runs may not read …`, `is_error: true`; `toolUseResult` is then a **string** (`Error: Exit code 1\n…`), not a `{stdout, stderr}` dict. In the orchestrator smoke test, `REFUSED:` and `usage:` appeared inside `toolUseResult.stdout`, with `stderr` empty. **The harness does not reliably separate stdout from stderr** |
| **Q3** | tool-result persistence? | **Not exercised; not reachable by the reader** | No output was persisted: the largest page (24,000 B plus header and trailer) was inline. Byte pages are capped at 24,000 B (`p3b_s5_r5.byte_pages`), below the harness's inline limit in this configuration. `parse_transcript` already resolves `<persisted-output>` files (`tool_results_dir`), but that path is **untested here**. It is fail-closed (C, §9) |
| **Q4** | can `parse_transcript` recover the sequence without ambiguity? | **Partly.** The production parser recovers calls, not results | `p3b_v1_2_4_instrument.parse_transcript` returns tool calls (inputs, timestamps), agent and session ids, persisted-output paths and parse errors. It **does not return tool_result content**, so page hashes, trailers and refusals need an extension (the raw extractor used here). **Two ambiguities, both resolvable by grammar:** (a) run/batch/label exist only in the shell command text, not in the reader's stdout, so they are unambiguous only with **one canonical reader invocation per Bash call**; (b) stdout carries the page body, which could itself contain header- or trailer-like lines, so parsing must be **positional** (first line header, last line trailer) and cross-checked against the recomputed page hash |
| **Q5** | truncation or corruption detectable? | **YES for every evidential manipulation; NO for non-evidential text before freeze** | a mid-line cut is a parse error ✔ · a deleted or reordered record breaks the `parentUuid` chain ✔ · duplicated records give duplicate `uuid` ✔ · **a clean truncation at a line boundary is invisible to the parser and the chain (a prefix is a valid chain); it is detected only by the harness's tool-use count in the orchestrator transcript** ✔ · an edited page hash fails recomputation from the source bytes ✔ · an edited non-evidential string (valid JSON) goes **undetected** (F2) until a digest is frozen |
| **Q6** | can agent A, agent B, synthesis and orchestrator be distinguished? | **YES** | Each subagent transcript has exactly one `agentId`. `subagents/agent-<id>.meta.json` carries `toolUseId` = the orchestrator's **Agent tool_use id**, whose input (the dispatch prompt) names the run. So agent → run comes from **the orchestrator's dispatch act**, not from the agent. Orchestrator commands live in the main transcript. Two dispatches for one run (L01) are both visible, one with 0 reads |
| **Q7** | ordering without agent-written timestamps? | **YES, by harness timestamps; not by line order** | §7. A genuine transcript (U01) had **non-monotone timestamps in line order**, so ordering must use record timestamps, and structure must use the `parentUuid` chain |

## 4. Transcript structure observed

- **Locations:**
  - main transcript: `~/.claude/projects/<project>/<session>.jsonl`;
  - subagents: `<session>/subagents/agent-<agentId>.jsonl`, with `agent-<agentId>.meta.json` holding `{agentType, description, toolUseId, …}`;
  - persisted outputs: `<session>/tool-results/`.
  - All are outside the repository and subject to the harness's retention policy.
- **Record types:** `assistant` (tool_use blocks: `id`, `name`, `input`), `user` (tool_result blocks: `tool_use_id`, `content`, `is_error`, plus a record-level `toolUseResult`), `attachment`, and in the main transcript `queue-operation`.
- **Keys on every record:** `uuid`, `parentUuid`, `timestamp`, `agentId` (subagents), `sessionId`, `cwd`.
- **Task notifications:** the orchestrator's `queue-operation` records carry `<task-id>` and `<tool_uses>N</tool_uses>` per subagent. This is a **harness-written count, held in the orchestrator's transcript and not in the agent's**. Observed: 1, 2, 3, 5 and 5 for the five dispatches, equal to the tool_use blocks in each subagent transcript.

## 5. Reader stdout and stderr capture results

| Outcome | Captured as | Observed where |
|---|---|---|
| successful page | `is_error: false`; content = header + body + trailer; `toolUseResult` = dict with `stdout` (and an empty `stderr`) | U02 ×2, L01 ×1 |
| reader refusal (exit 1) | `is_error: true`; content `Exit code 1\nREFUSED: …`; `toolUseResult` = string | synthesis L02S |
| malformed call (exit 2, `usage:`) | inside the combined output (`toolUseResult.stdout`) of a compound orchestrator command | orchestrator smoke test only |
| harness permission denial | `is_error: true`; content = the classifier message; **no reader output** | U01 |
| READ-LOG side effect | a malformed call writes **no** READ-LOG line; a refusal writes one (`refused: true`, `refusal: PLAN`); a harness denial writes none (the reader never ran) | the synthetic READ-LOGs |

**Additional observation:** the acknowledgement token is `ACK-` + the last 12 hex digits of the page sha256. It adds **no independent evidence** beyond the page hash, since anyone holding the bytes can compute it. It remains useful only as a consistency check between trailer and header.

## 6. Agent–run binding result

**Derivable, not declared.** The chain is:

> subagent transcript `agentId` → `meta.json.toolUseId` → the orchestrator's Agent tool_use → dispatch prompt → run id → plan owner

It was verified for all five dispatches. Every reader call's `--run`, `--batch` and `--label` were compared with the dispatch run and the plan owner (E\*-1). The current binding reads the run from prompt text. Revision 7 should require a canonical machine-readable dispatch line; the pilot instruments' `linkage_breaches` already checks run id plus canary in the first prompt.

## 7. Ordering result (harness timestamps, UTC, 2026-09-26)

| Event | Time | Source |
|---|---|---|
| dispatch L01 (1st), U01, U02 | 13:10:40.653 · 13:10:57.902 · 13:11:06.043 | orchestrator Agent tool_use |
| U02 reader calls (call → result) | 13:11:12.204 → 13:11:14.385; 13:11:19.672 → 13:11:21.275 | U02 transcript |
| U02 record Write | 13:11:29.831 | U02 transcript |
| dispatch L01 (2nd) | 13:11:44.668 | orchestrator |
| L01 reader call · record Write | 13:12:20.113 → 13:12:21.353 · 13:12:27.102 | L01 transcript |
| **unit validation** (`MDR-ORCH-VALIDATE`) | 13:13:29.196 → 13:13:30.580 | orchestrator |
| **synthesis dispatch** | 13:13:40.024 | orchestrator |
| synthesis reader call (refused) | 13:14:21.342 | L02S transcript |
| synthesis object Write | 13:14:30.037 | L02S transcript |
| assembly Write | 13:14:57.843 | orchestrator |
| **final validation** | 13:15:01.048 → 13:15:02.285 | orchestrator |

All of T\*'s relations (reads ≤ units-validated < synthesis-dispatched ≤ synthesis write ≤ assembly ≤ final validation) are established from **harness** times. The agent-written `agent_written_utc: 2026-01-01T00:00:00Z` in the L01 record was contradicted by the harness time of the Write that created it (attack M).

**Caveats:**
- A read's execution lies between its tool_use time and its tool_result time, so an interval, not a point, must be used.
- Main and subagent transcripts share the host clock of one harness process.
- Line order is not time order.

## 8. Truncation and corruption detection

| Manipulation | Detector | Detected |
|---|---|---|
| mid-line truncation | `parse_errors` (production parser) | ✔ |
| invalid JSON line | `parse_errors` + a `parentUuid` break | ✔ |
| clean truncation (whole lines dropped) | **orchestrator-side harness tool-use count ≠ transcript tool_use count** | ✔ (only this detector) |
| record deleted or reordered | `parentUuid` names a record not yet seen | ✔ |
| record duplicated | duplicate `uuid` (+ count and E\*-4) | ✔ |
| page hash edited | recomputation from the resolver's bytes | ✔ |
| non-evidential text edited (valid JSON) | none before a digest freeze | ✘ (F2) |

## 9. Attack matrix (18 cases)

**How the cases were built:**
- Mutations are applied to **copies** of the real transcripts and synthetic READ-LOGs.
- L and M are real events, not mutations.
- A signal counts only if it is **new** compared with the unmutated baseline.
- The baseline itself flags the three real violations: the synthesis read, its refusal and the harness-denied U01 call.

| ID | Attack | OBSERVABLE | DETECTABLE | Reason (signal) |
|---|---|---|---|---|
| A | missing stdout page header (U02 p1) | YES | **YES** | a successful call without header and trailer is classed as a no-reader-output anomaly; the READ-LOG line then has no witnessed call (E\*-4) |
| B | missing page hash in the header | YES | **YES** | the header does not parse → anomaly + E\*-4 |
| B2 | page hash altered, trailer untouched | YES | **YES** | recomputation from bytes ≠ header; witness ≠ READ-LOG (E\*-4) |
| C | missing ack token (trailer removed) | YES | **YES** | "successful call without an END/ack trailer" + E\*-4 |
| D | missing REFUSED line | YES | **YES** | an error result without reader output → anomaly; witness refusals 0 ≠ READ-LOG 1 |
| E1 | truncated (clean line boundary) | YES | **YES** | transcript tool_use count 2 ≠ harness-notified 5 (**only detector**) |
| E2 | truncated mid-line | YES | **YES** | parse error |
| F1 | corrupted (invalid JSON line) | YES | **YES** | parse error + `parentUuid` break |
| F2 | corrupted inside non-evidential text (valid JSON) | YES | **NO** | nothing evidential changed; detectable only against a frozen transcript digest |
| G | duplicated reader event | YES | **YES** | duplicate `uuid` ×4; count 7 ≠ 5; witness multiset ≠ READ-LOG |
| H | reordered records | YES | **YES** | `parentUuid` chain break |
| I | reader call under the wrong run id | YES | **YES** | `--run` ≠ dispatch run (E\*-1) |
| J | wrong batch id | YES | **YES** | `--batch` ≠ OB9901 (E\*-1) |
| K | wrong label | YES | **YES** | `--label` ≠ plan owner (E\*-1) |
| L | synthesis agent performs a reader call (**real**) | YES | **YES** | a synthesis agent with ≥ 1 reader call (E\*-2), refusal witnessed |
| M | back-dated agent-written timestamp (**real**) | YES | **YES** | `agent_written_utc` 2026-01-01 < harness Write time 13:12:27.102 |
| N | READ-LOG line deleted, transcript keeps the call | YES | **YES** | witnessed read missing from READ-LOG (E\*-4) |
| O | fabricated READ-LOG line (correct page hash, U01) | YES | **YES** | READ-LOG read with no witnessed call (E\*-4). The U01 agent was in fact denied: the witness proves no read happened |
| O2 | refused line deleted from READ-LOG | YES | **YES** | witness refusals 1 ≠ READ-LOG 0 |

**Result:** 17 of 18 manipulations detected. The undetected one (F2) changes no evidential content.

## 10. Missing observability

**No required component is missing.** These items are unobserved, conditional or out of Model D's reach:

1. **Malformed call inside a *subagent* transcript: not observed.** The U01 agent that was to issue it was blocked by the harness classifier on its first call. The malformed call was captured in the **orchestrator** transcript, through the same harness mechanism: output text plus exit status, with `usage:` present and no READ-LOG line. *It was deliberately not retried*, because retrying a classifier-denied action through another agent or through the orchestrator would get around a safety decision. Under the proposed grammar it is **fail-closed**: any reader call without a well-formed header and trailer is a violation.
2. **Persisted-output path: not exercised.** The reader cannot produce output above the inline limit (24,000 B pages). This is a **configuration dependency** (the harness's inline output limit must exceed the maximum page output). Fail-closed via C.
3. **No separate stderr channel.** Refusal is identified by `is_error`, exit status and text, never by a stderr field.
4. **Run, batch and label are not in the reader's output**, only in the command text. Shell-level ambiguity (variables, `cd`, several reader calls per command, pipes) must be excluded by grammar.
5. **Non-evidential corruption** (F2) and **orchestrator edits** are outside the witness's reach before digests are frozen and committed (the stated trust model).
6. **Retention:** transcripts live under `~/.claude/projects` and are subject to harness cleanup, so they must be archived at freeze. Raw transcripts contain page text, so the archive must be **outside the repository**, with digests committed.
7. **Operational observations (not observability gaps):**
   - a harness classifier denied one legitimate synthetic reader call, while an identical pattern passed for U02;
   - one haiku agent declined the task as a suspected injection.

   Model D **observes** both (an `is_error` denial; a dispatch with 0 reads). They are S5 **dispatch-reliability** risks for the runbook, not trust-root defects.

## 11. Model-D readiness verdict

**READY, conditional.** The harness transcript contains enough information to reconstruct the execution witness for E\*, R\*, F\* and T\*:

| Required | Observed |
|---|---|
| reader invocation | ✔ tool_use (Bash command) |
| exact run / batch / label / source / page | ✔ command arguments; header S-id and page cross-checked |
| page hash (and byte span, for F\*) | ✔ header `page sha256`, `bytes a-b` |
| acknowledgement or refusal | ✔ trailer; `is_error` + `Exit code N` + `REFUSED:` |
| harness time | ✔ tool_use and tool_result timestamps |
| agent identity and run binding | ✔ `agentId` → `meta.json.toolUseId` → dispatch |
| ordering | ✔ harness timestamps across main and subagent transcripts |
| completeness | ✔ harness tool-use count (orchestrator side) + `parentUuid` chain + parse errors + `uuid` uniqueness |

**Conditions that Revision 7 must carry as requirements:**

| # | Condition |
|---|---|
| **C1** | a **witness extractor** extending `parse_transcript` to tool_result content (positional header and trailer parse, `is_error`, exit status), plus the `parentUuid`/`uuid` checks and the **orchestrator-side tool-use-count cross-check**. **Without the count, clean truncation is undetectable** |
| **C2** | **reader-call grammar:** exactly one canonical reader invocation per Bash call, with no variables, pipes or `cd` indirection. Anything else is a violation (it resolves ambiguity 10.4). This is already the spirit of `scan_level` |
| **C3** | a **successful read is valid only with a well-formed header and a matching trailer**, and the header's page hash must equal the recomputation from resolver bytes (makes 10.1 and 10.2 fail-closed) |
| **C4** | refusal and failure are identified by `is_error` and exit status, never by a stderr field |
| **C5** | a **canonical dispatch line** (run id + canary) in every dispatch prompt; the binding is derived from `meta.json.toolUseId` |
| **C6** | **freeze:** at unit validation and at assembly, write `WITNESS.jsonl` (no corpus text), record raw transcript sha256 values, commit both, and archive the raw transcripts outside the repo |
| **C7** | T\* uses harness intervals (tool_use → tool_result) and never line order or agent-written times |

## 12. Minimum fallback (only if C1–C7 were rejected)

Not needed on the evidence. **If the human rejects the transcript as trust root**, or the harness format proves unstable, the smallest alternative is **a reader-emitted witness line**: the reader appends each call's record to a location outside the agent's write reach. That requires a tool sandbox restricting agent writes to their own output files. Its weakness: it depends on the sandbox, and it adds no protection against the orchestrator.

A cheap **hardening in either case**: make the reader echo `run`, `batch`, `label` and a per-call serial number in its header line. The stdout then describes itself, and the witness stops depending on shell-command parsing (C2 becomes defence in depth).

## 13. Exact implications for Revision-7 design

1. **RC-2 is implementable as D-minimal**, with C1–C7 as its acceptance conditions; HD-2 can be decided on this evidence.
2. **New component:** a witness extractor (reusing `p3b_v1_2_4_instrument.parse_transcript` and extending it to results). **New artifact:** `WITNESS.jsonl` plus the transcript digests. **Runbook:** freeze steps at 7 and 13; the verifier derives R6-PROVENANCE-equivalent stages from harness times.
3. **Verifier inputs change:**
   - READ-LOG, R6-PROVENANCE and RUN-MANIFEST become reconciled artifacts (E\*-4, T\*), not authorities;
   - coverage (R\*) and the fact–page binding (F\*) are computed from witnessed pages and byte spans.
4. **The ack token** should be documented as a consistency check only; it is not evidence.
5. **Reader (optional hardening):** a self-describing header (run, batch, label, serial).
6. **Runbook risk to record (not Model D):** harness classifier denials and agent refusals can stop S5 units. Each is witnessed and must be handled as a FAILED unit (a new run id, a human act), never retried silently.
7. **Unchanged:** the reconstruction architecture, Master Protocol v3.5, R19, and RC-1/RC-3 (not implemented, as instructed).

## 14. Safety record

| Item | Before | After |
|---|---|---|
| `git status --short` (repo) | 249 lines | 249 lines, **identical** (diff empty) |
| `P3B-STATE.json` sha256 | db52ac7a6fc5e653fd87d1b4bca900c69ba1c8a4fa45fc448835792ec134c21a | identical |
| `_batch_manifest_p3b_r2.jsonl` sha256 | 1b383fbc6ff720ebd2f893a74362ce73745dcf99243bc00ced1bcae3a5475682 | identical |
| `P3B-HOLDOUT-SEAL.json` sha256 | 9b99169d30d57b2ecc1d331838652aa6b765ce722059b30095a03df077d589c0 | identical; guard: SEALED (HS-3d32dd44d162) |
| ledger fingerprint | 4fde15fc42513725af23da039d154afb3a688018f039f20b05626a98bdad05e5 | identical; no `OB99xx` directory in `ledger-p3b-r2/` |

- **No production corpus was read.** The only resolver was the synthetic fake, which raises on any non-synthetic id.
- **No H-19 access.**
- **No S5 state changed:** no activation, no manifest regeneration, no binary pre-classification, no S5 dispatch.
- **No code, test, Revision-6 or F-Series file was modified.** RC-1 and RC-3 were not implemented.
- The only repository write is this document (uncommitted).

**Experiment artifacts** (session scratchpad, not in the repo; sha256):

| File | sha256 |
|---|---|
| `syn_reader.py` | 316c06aa6ba0312e407cf76c0cac228b9fd277ebc6591d0817e145193b57cb51 |
| `witness.py` | 52a68d75dca0908651e3d252b913d907bce19e7b298262d136512bffd305ae23 |
| `attacks.py` | 245d19499617aeddc3e3b88bc84b172e2296a0440c3033e9012e1013c0bfd6d4 |
| `out/attacks.json` | 283c0dd9143ddecb7294443b421dfb4bbe2c3a210c77170069a1bbb75227fb47 |
| `out/attacks.out` | a6c6cc6236622f5eb64a606ad36e28098635a8a9c0ab46cc4e5264553cd13eb2 |
| `out/w1.json` | 72a9356be86bf9104400c2d482cdb30758bbfa6d1670ad460354759055b3c0ef |
| `OB9901.R6-PLAN.json` | b25e872e82949d5a770bbaf9c84cb9e46b9d6b82aa9b812d038b49779c287168 |

If the human wants these preserved as evidence, they should be copied into the repo by a separate decision; the scratchpad is session-scoped. **Stop.**
