# S5 canary, attempt 1 (G-LOG-0107): STOPPED. Instrument findings

| | |
|---|---|
| **Kind** | ⚠ authority: generated. Execution-integrity evidence. **No evidence for or against H1–H5** |
| **Batch** | OB0012 (5 SINGLE runs), dispatched 2026-09-30. OB0114 was **not** dispatched |
| **Stop rule** | triggered: the same W-code (W4, a refused reader call) occurred in 5 of 5 runs. No restart |
| **Verdict** | `audit-p3b/S5-VERIFY-OB0012-R7.json`: **BATCH-FAIL**, 23 failures. State: FAILED, class **X**, signature `cc4c64c2…` |
| **Evidence** | transcripts archived at `~/knowledgeos-witness-archive/OB0012/` (392 files, outside git); the misrouted reader logs are copied to `…/OB0012/misrouted-reader-logs/` |

## Findings (FACT unless marked)

**F-1: environment. The reader resolves its repository root from the working directory (class X; the root cause of the stop).**
- `p3b_read_source` derives CR from the session's working directory (git top level), not from its own file location.
- After the session restart, the orchestrator session's primary working directory was a different repository (`ansible-playbook-entwicklerlaptops`). The dispatched agents inherited it.
- The reader therefore looked for `_batch_manifest_p3b_r2.jsonl` under that repository, found none, and refused: "no activated R7 plan for this batch".
- Reproduced: CR from the ansible working directory → activation not found; from the nrna1 working directory → found.

**F-1b: side effect in another repository.** The misrouted reader wrote its 5 refusal logs into the ansible repository (`docs/knowledgeos/chronological-read/ledger-p3b-r2/OB0012-R7-L0{1..5}/READ-LOG.jsonl`, untracked, one refusal record each). They are preserved in the witness archive and left in place for the human to remove. This is also the cause of **R7-E W6** (READ-LOG refusals 0 ≠ witnessed 5).

**F-2: instrument text. The addendum's status header is stale.**
- Its header still says "NOT ACTIVATED" and "S5 execution, dispatch and corpus reading are NOT AUTHORIZED".
- 3 of 5 agents cited it as corroborating the refusal.
- An agent could legitimately read it as an instruction not to proceed. A contract text correction is needed (a new addendum sha, so a rebind).

**F-3: instrument strictness. An end-of-file probe is scored as a W8 failure.**
- In 3 runs (L01, L03, L04) the agent issued one Read past the end of its SLICE-VIEW, following "page until the whole file has been displayed".
- The harness displays only "the file exists but is shorter than the provided offset (…). The file has N lines."
- The witness scores this as `content_match false` (R7-W W8).
- It is benign behaviour, not a fidelity loss: every earlier page matched.

**F-4: observation. Agents diverge under a batch-wide refusal.** Four agents wrote nothing. L05 wrote an escalation-only object (10 CONTRACT-DEVIATION, 2 SCHEMA-LIMITATION) plus 2 register records.

**F-5: process.** The canary pre-check ran every check from the nrna1 working directory, so it never tested the agents' working directory. The pre-check must assert that the dispatch working directory resolves the same CR as the tools.

## What the canary did establish (execution integrity)
- Binding lines, canaries, I(run) and prompt delivery worked.
- Every agent read its inputs (contract, addendum, plan, SLICE-VIEW) via Read within I(run), made exactly one canonical reader call, stopped correctly under W4, and made no forbidden tool call.
- The transcript chain was intact.
- The witness, verifier and state machine produced a correct, reproducible failure record.
