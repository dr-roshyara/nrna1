# AG-1: implementation STOPPED — honoring FALSE-HIT for binary files needs a contract amendment

| | |
|---|---|
| **Kind** | Engineering finding. ⚠ authority: generated. Per G-LOG-0097 ("if this requires a change to the frozen contract (v2.5), STOP and report"), **no code was changed** |
| **Frozen contract** | R7 v2.5 `63fdda65…` |

## Finding

1. **The reader refuses binary content:** `p3b_read_source.py` returns `REFUSED: BINARY-CONTENT`, and binary files are never paged. So all 12 binary files are **unread** by every agent, whatever the decision.
2. **The frozen reading-state rule applies to every unread file** (r5 item 10, carried into R7 via `r5.reading_state_violations`). A disposition on a non-WHOLE-FILE source must have method `NOT-CONSUMED-ESCALATED`, every dimension `ESCALATED`, and a CONTRACT-DEVIATION escalation naming the file (`reading_rule_violations`).
3. **So a FALSE-HIT decision cannot be executed under v2.5.** A FALSE-HIT disposition on an unread binary would FAIL R7-E. The "exempt class set" the contract carries over is the set of timeline change classes (`WHOLE_NOT_REQUIRED`), not a file exemption. No binary exemption exists.
4. **No delivery channel exists either.** The closed I(run) categories (CONTRACT, ADDENDUM, PLAN, SLICE, UNIT-RECORDS, DECLARED-SYNTHESIS-INPUT) and the plan derivation carry no binary decisions.

## Impact (metadata; the 41 labels touching a binary file)

| Group | Labels |
|---|---:|
| touching a NOT-CONSUMED-ESCALATED file (these escalate in any case) | 36 |
| touching a FALSE-HIT file | 18 |
| **touching only FALSE-HIT binaries**: the only labels where the choice matters | **5** |

In those 5 labels, executing the FALSE-HIT files as NOT-CONSUMED-ESCALATED makes each dimension hit only by such a file **ESCALATED instead of possibly GENUINELY-UNDEFINED**. That is a loss of resolution in the conservative direction, never a false resolution.

## Options (human decision)

| Option | What | Contract | Cost |
|---|---|---|---|
| **A (no amendment)** | execute all 12 binaries as NOT-CONSUMED-ESCALATED in S5. The 5 FALSE-HIT decisions stay recorded, but are not operative in S5. The reader's refusal is the delivery: the agent sees `BINARY-CONTENT` and escalates | unchanged | possible over-escalation in ≤ 5 labels; one full-path test that a refused binary + NOT-CONSUMED-ESCALATED disposition + CONTRACT-DEVIATION passes R7 |
| B (amendment v2.6) | (i) the frozen plan carries `binary_decisions` for the label's files, derived from the hash-bound `BINARY-DECISIONS.json`; (ii) the reading-state rule accepts a `FALSE-HIT` disposition, with no CONTRACT-DEVIATION, for a file decided FALSE-HIT, and the verifier checks disposition = decision | a Universe + Evidence change, a contract act, tests first | a larger slice; honours all 12 decisions exactly |

**Recommendation: A.** It changes no frozen artifact, it is conservative, it affects at most 5 of 1,975 labels, and it keeps the path to activation short. B can be chosen if exact fidelity to the 5 FALSE-HIT decisions is judged worth an amendment.

---

## Addendum (2026-09-28, after G-LOG-0098): option A is NOT feasible under v2.5 — design conflict DC-3

I described option A as "the reader's refusal is the delivery". **That was wrong.** The full-path tests, now pinned in `test_p3b_s5_r7_full.AG1BinaryNotConsumed`, show two gates in the frozen contract that together leave no passing path.

| Path | Gate | Result |
|---|---|---|
| The agent calls the reader on a binary file | **W4**: a refused reader call is a stop condition (`p3b_s5_r7_witness.py:366`) | BATCH-FAIL R7-W |
| The agent does not call the reader; NOT-CONSUMED record + NOT-CONSUMED-ESCALATED dispositions + CONTRACT-DEVIATION escalation | **historical G-04** (`p3b_s5_verify.py:851–854`): every stage-2 hit file of a non-hub label must be read at step 1 or 7; there is no exemption for NOT-CONSUMED-ESCALATED | BATCH-FAIL ("1 of 11 stage-2 hit files neither read whole nor scanned"). The census rule also turns the affected GENUINELY-UNDEFINED dimensions into failures |

**Scope:**
- **All 41 labels** that touch a required binary file cannot reach BATCH-PASS under v2.5.
- They lie in **39 of the 396 batches**.
- This is a systematic, non-random false FAIL (fail-closed, no false PASS), and it would bias the S5 population.

The revision-5 binary policy (refuse binary content; decide per file) was never reconciled with G-04 and W4.

**The smallest repair (a contract amendment v2.6-DC3, for decision; nothing implemented):**
1. **Delivery:** the frozen plan carries `binary_decisions` for each label's required binary files. They are derived in the Universe's plan derivation from the hash-bound `audit-p3b/20260928_BINARY-DECISIONS.json`; PLAN is already an I(run) category. The agent does not call the reader on them; a call stays a W4 stop.
2. **Coverage:** in G-04 and the R7 census rule, a required file listed in `binary_decisions` counts as **decided, not missing**, provided its dispositions match its decision:
   - **B-form, recommended:** FALSE-HIT → the hits disposed FALSE-HIT; NOT-CONSUMED-ESCALATED → method NOT-CONSUMED-ESCALATED, every dimension ESCALATED, and a CONTRACT-DEVIATION escalation naming the file. This honors all 12 decisions.
   - **Conservative form:** treat all 12 as NOT-CONSUMED-ESCALATED.
3. **Tests first:** the pinned DC-3 test becomes a PASS; every mismatch (a disposition ≠ its decision, an undecided binary, a reader call on a decided binary) FAILS.

**Freeze 1 and Freeze 2 are unchanged by this repair.** The frame, R(L) and the strata do not depend on it; the files stay required.
