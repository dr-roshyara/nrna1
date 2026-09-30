# Research Release Check `01` (L0-DEC-27)

| | |
|---|---|
| **Kind** | the single release check defined by L0-DEC-27. ⛔ **A status report, not a release.** L0 decides |
| **Run by** | the governance/control-plane session. ⚠️ It implemented the controls it measures. The controls themselves were verified by the 1a.6 review, VERIFICATION-02 and VERIFICATION-03 |
| **State measured** | HEAD `c326ecf7c`, plus the human's pinned `governance/governance-state.yaml` (saved 2026-09-23 19:04, committed with this record) |

## Result

> # 🟡 **YELLOW** — no R1 defect; R2 limitations recorded below, each with the condition under which it cannot invalidate the scope.

## The five questions

| # | Question | Answer | Evidence |
|---|---|---|---|
| 1 | Is the approved methodology unchanged since approval? | ✅ | no commit to `prompts/` after `628d02169` (L0-DEC-26). Working tree for `prompts/` is clean. The protocols in force are those of L0-DEC-22 and L0-DEC-26 |
| 2 | Is the research corpus and state identifiable? | ✅ | HEAD `c326ecf7c` · `build-manifest.py --verify` **CURRENT** (manifest hash `54977c6e1213028d…`, 3063 present / 18 missing, recorded) · 27 read receipts + 1 VOID (F0047) · 58 Theory Objects · 40 registered files |
| 3 | Are the known defects classified, with no open R1? | ✅ | L0-DEC-27 (R2/R3 lists) and L0-DEC-28 (N-1…N-4, R3). No new governance defect found by this check (see observation O-1) |
| 4 | Do the controls detect what they claim? | ✅ | fresh verification VERIFICATION-03 (L0-DEC-28, ACCEPT_WITH_FINDINGS). Re-run now: self-test **55/55** · gate battery **66/66** · admit battery **12/12** |
| 5 | Has L0 accepted the current state? | ✅ | **1a.7 accepted (L0-DEC-29)**. **Pins installed by the human**. All five match `--pin-lines`: `485a327da2ca8f9e` · `7084bc523256ccb3` · `9327509ad713a267` · `45e060f20216d64d` · `9c5d3802a2ff6942` |

## Live measurements

| Control | Result |
|---|---|
| `gate-runner.py` | **`STATUS: CLEAR`**, exit 0. 5 applicable activated gates, 0 blocking: KOS-G-001 PASS (29 files, 0 malformed) · KOS-G-002 PASS (58 objects, 46 relations, 0 duplicates) · KOS-G-003 PASS (known 3153, canonical 3081, 0 dangling) · KOS-G-010 PASS (40/40) · KOS-G-022 PASS (43/43 traced) |
| door `governance-preflight.sh` | exit **0**, CLEAR. It states process conformance only |
| `admit.py --audit` | completes; `BINDING_FAILURES_PRESENT`, exit 3. The **only** failure is the nine RC-H-04 IDs |
| ⚠️ CLEAR means | **process conformance against the five activated rules only** (L0-DEC-12). It is not a statement that the research is valid |

## R2 limitations and why each is contained

| R2 item | Contained because | Condition that would change it |
|---|---|---|
| **nine RC-H-04 IDs** F0031–F0038, F0040 (evidence divergence) | the pending commission (Critical Attack Pass 01 on `[E]-01…04` from `SENIOR-RESEARCHER-BASELINE-01.md`) does not rely on them. The baseline mentions them only once, as a governance-status note (L150). The Theory Objects that `[E]-01…04` cite (T-0003, T-0015, T-0023, T-0056) have **no `historical_sources` in F0031–F0040** (measured) | any released work that reads, cites or builds on F0031–F0040 ⇒ **RED** for that work until C-5 |
| IR-A3 (an absent registry gives silent BOUND) | the registry is present and the audit reports the nine IDs (above) | registry absent |
| IR-A6 / A-5 (a receipt proves admission, not reading) | interpretive only; no audit verdict depends on it | a claim of "read" resting on a receipt alone |
| R-2, IR-B1, IR-B2, IR-A5 (runner, fixtures, schema, receipt stream unpinned or unprotected) | tamper-evident via integrity hashes (`gates.yaml` `40f1c2d5b14e42f9`, `governance-state.yaml` `effdbebc6755f04d`, `gate-schema.yaml` `4ebde2c923ede3a6`, `gate-runner.py` `050dfda42f4e62d8` at this check). The self-test runs on every evaluation | an unexplained change to any of these hashes |

## Observation (evidence state, not a governance defect)

- **O-1:** the **non-activated** gate KOS-G-020 (`relations_coverage`) FAILs: 46/58. **12 Theory Objects, T-0047…T-0058, have no relation record.** They come from the pre-acceptance batches 1–3 (L0-DEC-20). This is not a control failure: the gate is not activated, and CLEAR makes no claim about it. ⚠️ It is relevant to scope, because **`[E]-02` relies on T-0056**, one of the twelve. Recorded for L0's scope decision.

## What L0 must still decide

1. **Release: yes or no, under YELLOW.**
2. **The authorized scope, stated explicitly.** The Critical Attack Pass commission exists only as the research session's stated plan. No commission artifact is committed. The R2 containment above holds **for that plan as described**.
3. **Whether O-1 matters for the scope**, for example whether `[E]-02`'s reliance on T-0056 without a relation record is acceptable for an attack pass.

⛔ This check releases nothing. It does not decide 1b, C-5 / RC-H-04, Batch 4, or Phase 2 entry.
