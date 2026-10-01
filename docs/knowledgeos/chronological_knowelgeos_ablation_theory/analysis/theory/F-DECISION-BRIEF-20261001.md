# F-Series decision brief, 2026-10-01 (human gate after Option A capture)

| | |
|---|---|
| Status | **ADVISORY.** It recommends; it decides nothing and creates no authority |
| Basis | `F-GOVERNANCE-LOG.md` F-LOG-0178 (Option A) → 0180 (Protocol A sealed) → 0186 (Protocol B sealed) → 0189 (readiness sealed) |
| Coder family | Every coding and review so far is **same-family (Claude)**. Nothing below is independent confirmation |
| Strength review | `kos-theory-reviewer`: PASS_WITH_LIMITATIONS. Its six corrections and four omissions are applied in this version |

## 1. Evidence acquired

| Package | What it established (descriptive) | Records |
|---|---|---|
| **A: execution traces** | 51 matching commits on the local refs. 3 are EXECUTION-TRACE. The earliest is `e4724a44` "WP-4C-1 batch B1 -- RED", 2026-08-04T00:30:25Z, **RECORDING_ORDER only**. 21 of the 51 are programme artefacts (self-contamination). **Completeness vs the host: NOT SHOWN** | `LOOP-1BC/` |
| **B: norm-status channels** | **No** locally checkable channel qualifies as an independent norm-in-force record. Q5 is not determinable by existence check for every evidenced channel. The overlap test has almost no power (≤ 3 author identities) | `LOOP-1BD/` |
| **Readiness** | No independent norm-state observable in A ∪ B; D1 holds on the acquired evidence. Whether one exists elsewhere: **NOT_DETERMINABLE**. **The analyst's sealed expectation (NO) was not confirmed.** Weigh the analyst's forecasts accordingly | `LOOP-1BE/` |

## 2. Evidence not acquired
- Anything held on the host: PR approvals, branch-protection or required-check settings, CI run history, audit logs, membership.
- Signer identity for the 23 signature headers.
- The content of status and register files: Protocol B was existence-only.
- Items left uncoded or not acquired (1be erratum E3):
  - `IMPLEMENTATION_PROGRESS.md:58`;
  - the further execution reports (R-83 grep, "Container resolution executed", R-41, S0815);
  - R11 (WP-4B RED);
  - the UNCLASSIFIABLE commit in A (probably a programme artefact; inferred).
- A non-Claude coding: Gate 1 bundle -02 is with the human (human Decision 1, `F-GOVERNANCE-LOG.md`).

## 3. Where the theory stands (the strongest statements the evidence supports)

1. **Observation schema A′** (`SEMOBS-R2/SCHEMA-r2-Aprime.md`; human act, `F-GOVERNANCE-LOG.md`): acts and norms are coded separately per span. There are **no act-level contrasts**: 23 performed vs 1 refused (`LOOP-1BB/STATE.md`).
2. **H-D vs H-B is NON-IDENTIFIABLE on the acquired evidence.**
   - The cause is D1 combined with the absence of an independent norm-state observable.
   - This is a **limitation of the corpus and of the authorized scope**. It is **not** a result about which hypothesis is true.
3. **Candidate anomalies stay open (n = 1 each; not falsifiers):**
   - WP-4B RED vs the norm then in force;
   - R-89 / WP-4C-1 authorized before WP-4B was accepted (an inference).

   Both are confounded with "which norm was in force at t", and that is the missing observable.
   - R-94 executed without Decision Authority adoption is also open, but with an alternative non-anomalous reading: per R-90 it is a scope question, not a capability breach. Its confound is the **norm type**, not the time t.
4. **Methodological observations (same-family; one programme; n = 1 each):**
   - capture first, interpret later;
   - the symmetry rule;
   - M-TIME;
   - no forced negatives;
   - same-family coder agreement was degenerate and cue-driven (`F-GOVERNANCE-LOG.md`, 1av / 1av-b entries);
   - self-contamination by programme artefacts (R-SELF; `LOOP-1BC/`);
   - targeted repair rounds converged in 1bd (5 → 1 → 1 → 0; `LOOP-1BD/RECONCILIATION.md`), while 1bb's layered repairs did not (`F-GOVERNANCE-LOG.md` F-LOG-0177).

## 4. Next experiment (only if authorized)

**The highest-information single step is a bounded, read-only, host-side scoping of the one motivating instance:**
- **Question:** did any host-kept record attest whether WP-4C-1 was permitted at, or around, 2026-08-04T00:30:25Z?
- **Records to check:** required checks or branch protection on the target branch, PR approvals, and CI runs for `e4724a44` and its PR.
- **Method:** the same discipline as A and B. Pre-registered, existence and metadata first, no interpretation, outcomes that include NOT_DETERMINABLE.

**Why this:** it is the only channel class that the readiness review found **could** break D1, and it does so only conditionally (see H1).

**If no host record is found:** that removes this one channel **for this instance**. The existence question stays open elsewhere.

**Limit:** host membership data shows repository access, **not** normative capability (who may adopt, authorize or dispose). At most it is an input to a capability register (1be erratum E4).

## 5. Decisions for the human (none is taken here)

| # | Decision | Info value (from `LOOP-1BE/A/READINESS.md` §4 and its review) | Note |
|---|---|---|---|
| **H1** | Authorize the host-side read-only scoping of §4 (a new evidence channel; host access) | **High for D1, only if** the host rules map onto register targets **and** the approvers are not the norm-maker identities. **Neither condition is known**, so the rating states a possibility only | Needs credentials or API access from the human; the scope is bounded to one instance |
| **H2** | Authorize signer verification of the 23 signature headers | Low for D1. Medium for the P2 timing question, **only if** the signed commits include the relevant ones | Requires keys or host data. A signature attests a commit, not a norm |
| **H3** | Decide the R-SELF rule (exclude `.claude/`, `claude/` and programme artefacts from future captures) | Low | Cleans the counts in A and B (including the probable self-artefact in A); cannot create a norm-state observable |
| **H4** | Hand Gate 1 bundle -02 to a non-Claude coder (human Decision 1; handover pending) | Low for D1. Medium for the reliability of the P2/P7 codings | **Coder independence is not source independence:** a second coder still reads text written by the norm-maker |
| **H5** | Alternatively, **close the H-D/H-B discrimination line** with a NON-IDENTIFIABLE-on-corpus result, and have the theory state the observation schema plus the identifiability limit as its finding | — | A legitimate end state. DDD and architecture would then build only on what is supported: the act/norm separation and the method observations, **not** on H-D or H-B |

**Recommendation:**
- **H1, bounded** (its value is conditional, as stated above), **together with H4** (for coding reliability).
- Decide **H3** at the same time, because it is cheap.
- If host access is not available, **H5**.
