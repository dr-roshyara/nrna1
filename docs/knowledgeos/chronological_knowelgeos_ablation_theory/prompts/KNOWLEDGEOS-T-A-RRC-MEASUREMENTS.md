# Research Release Check — MEASUREMENTS for T-A (read-only; input to the check, not the check)

| | |
|---|---|
| **Kind** | measurement report. ⚠ authority: generated. ⛔ **Not a Research Release Check result, and not a release.** Per L0-DEC-27 the check (GREEN / YELLOW / RED) belongs to the governance session, and L0 decides the release |
| **Commission** | human, 2026-09-26: *"run the Research Release Check measurements read-only"* |
| **Run by** | the F-lane research session, i.e. the session that would benefit from the release. ⚠ It is **not independent** of the result it measures |
| **State measured** | HEAD `3584bb0d3` (newer than this lane's last commit `89226c49b`; the intervening commits belong to other lanes and do not touch the governance lane) · 2026-09-26 |
| **Read-only proof** | `git status --porcelain --untracked-files=all` of the governance lane was identical before and after (**delta: none**). `/tmp/kos-*` directories before/after: 0/0. Only non-writing modes were used (`--verify`, `--audit`, default runner, `--self-test`, `--pin-lines`); the regression batteries mutate temporary copies only (source inspected before running). No corpus content was displayed; F2800 was identified through a byte-hash diff computed in memory |

---

## Measurements

| Control | Result | At RRC-01 (2026-09-23) |
|---|---|---|
| `evidence/build-manifest.py --verify` | ⚠ **`STATUS: STALE_OR_CORPUS_CHANGED`**, exit 3. 3081 entries, 3063 present / 18 missing. Stored `54977c6e1213028d…`, fresh `a92409ffadc64adf…` | CURRENT |
| ↳ in-memory diff (nothing written) | **exactly 1 changed entry: F2800**, `docs/knowledgeos/brainstorming/knowledgeos_theory_research/corpus/knowledge_os_with_lina_puran/20260831-104505_analyse-review-attached-file-senior-mathematician-statistician-ddd-architect-expert.md`. sha256 `e140e172…` → `a3df1871…`. The file is **untracked** in git (never committed), so the changed bytes cannot be recovered from git. **F0018 unchanged** (`b685f599…` = pin) | — |
| `governance/gate-runner.py` | **`STATUS: CLEAR`**, exit 0. 5 applicable activated gates, 0 blocking. 18 eligible but not activated. 1 measurement not passing: **KOS-G-020 FAIL (not activated)**, 46/58, missing T-0047…T-0058 | CLEAR; the same O-1 |
| `gate-runner.py --self-test` | **55/55** | 55/55 |
| `gate-runner.py --pin-lines` | KOS-G-001 `485a327da2ca8f9e` · KOS-G-002 `7084bc523256ccb3` · KOS-G-003 `9327509ad713a267` · KOS-G-010 `45e060f20216d64d` · KOS-G-022 `9c5d3802a2ff6942` | **all five identical** |
| `.claude/hooks/governance-preflight.sh` | **CLEAR**, exit 0 (process conformance only) | CLEAR |
| `evidence/admit.py --audit` | `BINDING_FAILURES_PRESENT`, exit 3. The only failure is the **nine RC-H-04 IDs** F0031–F0038, F0040. 27 read receipts + 1 VOID (F0047); the receipts file has 28 lines | the same |
| `governance/regression/run_regression.py` | **66/66** match (rev=HEAD, control=worktree) | 66/66 |
| `governance/regression/admit_regression.py` | **12/12** match | 12/12 |
| control-file integrity (sha256, first 16) | gates.yaml `40f1c2d5b14e42f9` · governance-state.yaml `effdbebc6755f04d` · gate-schema.yaml `4ebde2c923ede3a6` · gate-runner.py `050dfda42f4e62d8`. **All identical; none modified in git** | the same |
| methodology (`prompts/`, governance lane) | **no commit** since `628d02169`. ⚠ **4 untracked files**: `evaluation_researchmethod.md`, `review_of_phase1.md`, `review_of_phase_2.md`, `review_of_v1.2 architecture.md` | "working tree for `prompts/` is clean" |
| T-A protocol | frozen r3 sha256 `be16deb7…` = HEAD object (commit `cfcee7bb0`) | — |

---

## The five questions: measured facts only (no GREEN / YELLOW / RED is assigned here)

| # | Question | Facts |
|---|---|---|
| 1 | methodology unchanged? | no commit to the governance-lane `prompts/` since `628d02169`. **But the working tree is not clean**: 4 untracked files, content not read. T-A methodology = frozen r3, unchanged |
| 2 | corpus and state identifiable? | **The manifest does not verify** (STALE; 1 entry, F2800, untracked). HEAD, receipts and the T-A objects are identifiable: F0018 matches its pin; the 7 ES-006 objects are pinned by git blob + sha256 (F-LOG-0031), independent of the manifest |
| 3 | defects classified, no open R1? | known R2 (RC-H-04) unchanged. **New, unclassified:** (a) the F2800 manifest divergence; (b) the untracked `prompts/` files. Classifying them is the governance session's act |
| 4 | controls detect what they claim? | self-test 55/55 · regression 66/66 · admit regression 12/12 · control hashes unchanged. **No material control change** since the last fresh verification (VERIFICATION-03) |
| 5 | L0 accepted the current state? | pins match. HD-1 (r3 frozen) given. **Open for L0:** OQ-6 scope for M-4; the release |

---

## Observations relevant to the T-A scope (for classification by the governance session)

**OBS-1: F2800 manifest divergence.**
- Under L0-DEC-27, *"wrong corpus file, silent provenance change"* is **R1** for any work that reads, cites or builds on the affected file.
- T-A reads **none** of it: M-1 is pinned by a manifest hash that still matches, and M-4 is pinned by git objects.
- A possible containment argument, **for the governance session to accept or reject**: R2 for T-A, on the condition that no T-A reader reads, cites or relies on F2800.
- Separately, the manifest can be rebuilt only by the governance session. **Rebuilding it would change the manifest hash** that M-1's pin cites.
- Relation to the session-start git status (a fact, not interpreted): a similarly named file in the same directory, `…/20260920-104505_analyse-review-attached-file-…md`, was shown as **deleted** at session start.

**OBS-2: KOS-G-020 (not activated).**
- T-0056 is one of the twelve Theory Objects without a relation record.
- **H-F2-1 is formed from T-0013 × T-0014 × T-0056** (F2 acceleration pass).
- It is the same fact as RRC-01's O-1, and now bears directly on T-A's candidate. A scope question for L0; not a control failure.

**OBS-3: untracked methodology-lane files.**
- Four files in the governance lane's `prompts/`, never committed. Content not read.
- Whether they bear on question 1 is for the governance session to decide.

**OBS-4: independence.**
- These measurements were taken by the session that requests the release.
- The governance session should **re-run or accept** them, rather than adopt them unexamined (the same caveat as RRC-01's "implemented the controls it measures").

---

## What remains (not done here)

1. **Governance session:**
   - classify OBS-1 and OBS-3 (R1 / R2 / R3);
   - decide the manifest action for F2800;
   - issue the Research Release Check result GREEN / YELLOW / RED for the T-A scope (`KNOWLEDGEOS-T-A-EXECUTION-GATE-STATUS.md` §3).
2. **L0:**
   - the OQ-6 scope decision for M-4;
   - whether OBS-2 matters for the scope;
   - the release record.
3. **Human:** commission the INDEPENDENT reader.

*No corpus content read. No governance-lane file written. No release requested or decided.*
