# F-series approvals: repository custody, protocol, record correction, test execution, KC-1

**PROPOSED / NOT RATIFIED. This package decides nothing. Draft r0, 2026-10-17.** It sets out five **separate** approvals for the Human ARB. No approval implies any other.

**Status labels:** [EST] established · [COND] conditional · [REC] research recommendation · [HDR] human decision required.

---

## Verified repository facts [EST]

- **Branches.** The working tree is on `election-flow-updated` (remote `origin`, GitHub).
  - `knowelegeos-modelling` (`153ad7bf6`) is a **strict ancestor** of `election-flow-updated`. It has 0 commits of its own, so the two branches have **not** diverged.
  - `election-flow-updated` = `knowelegeos-modelling` + 28 commits. These include:
    - F-series: `3a03ad4cd`, `86bd63fc2`, `55a8a1b9d`, `d2ac9b372`, `bccfd044a`, `1b45cc503`;
    - S-series;
    - the Laravel 12 upgrade;
    - the PBDIGIT-304 clean-ups;
    - `925e58045` "documents" (186 files).
  - (This corrects the earlier report, which said the branches had diverged.)
- **Push state.** `election-flow-updated` is 4 commits ahead of `origin/election-flow-updated` (`55a8a1b9d`, `d2ac9b372`, `bccfd044a`, `1b45cc503`, all F-series). F-LOG-0306 and its sync (`3a03ad4cd`, `86bd63fc2`) are already on the remote.
- **Last verified F-series baseline:** `1b45cc503`.
- **Uncommitted F-series work** (nothing staged):
  - `F-GOVERNANCE-LOG.md`: +31 lines, appended only (F-LOG-0309, F-LOG-0310);
  - `analysis/theory/R7/KC-1-VALIDATION/`: 23 files, plus the two drafts in this package.
- **Unrelated uncommitted work from other sessions:** 12 modified tracked files and 20 untracked files. Every one of them must be left untouched.

---

## Approval 1: repository custody [HDR]

**Recommendation [REC]:** keep the F-series line on `election-flow-updated`, where its commits already are, and do not rewrite history.

1. **Commit only the F-series paths, by explicit path**, on the current branch:
   - `F-GOVERNANCE-LOG.md`;
   - `analysis/theory/R7/KC-1-VALIDATION/`;
   - the state files, after a sync.
   Do not commit any of the 32 unrelated paths.
2. **Leave `knowelegeos-modelling` where it is.** Fast-forwarding it to `election-flow-updated` would carry the Laravel 12 upgrade and the PBDIGIT work into it. That is the owner's integration decision, not an F-series act.
3. **Optional:** create the marker branch `f-series/kc1-baseline` at the new commit, so the F-series baseline is easy to find. Branch creation only; no checkout.

**Why this:**
- No reset, rebase, cherry-pick, merge, stash or switch is needed.
- The F-series files are documentation-only paths that no other workstream touches.
- Checking out a dedicated branch in this **shared** working tree would disturb other sessions' uncommitted work.

**Before any later integration (owner's task):**
- Confirm that the F-series paths are touched only by F-series commits (`git log 153ad7bf6..HEAD -- docs/knowledgeos/chronological_knowelgeos_ablation_theory/` shows only F-series commits).
- Review the 28-commit range separately for non-F work.

**Commands, to run only after Approval 1:**
```bash
git add docs/knowledgeos/chronological_knowelgeos_ablation_theory/F-GOVERNANCE-LOG.md \
        docs/knowledgeos/chronological_knowelgeos_ablation_theory/analysis/theory/R7/KC-1-VALIDATION
git commit -m "docs(knowledgeos): F-LOG-0309 KC-1 deferral, F-LOG-0310 bounded validation (no story applies)" # + body, trailer
git branch f-series/kc1-baseline HEAD      # optional marker; no checkout
# no push unless separately authorized
```

---

## Approval 2: validation protocol [HDR]

**Recommendation [REC]:** approve the protocol **subject to** applying corrections P1–P5 (blocking) and P6–P10 (should-fix) from `PROTOCOL-CORRECTIONS-DRAFT.md`, followed by one independent re-audit. Freeze it **only after** both.

**What this approval would authorize:**
- editing `external-protocol/` to apply P1–P11;
- rebuilding the packets and re-running the leak check and the dry run;
- one fresh audit.

**What it would not authorize:**
- freezing;
- distributing anything;
- recruiting encoders.

| benefit | cost | remaining limit |
|---|---|---|
| The first test with external encoders; it closes the PARSE gap that blind2 left open | One correction and audit cycle, then encoder recruitment | Claude-authored texts; one corpus; PARSE's taxonomy is the analyst's; the result says nothing about which option is correct |

**The freeze itself is a separate human act.** It consists of `python3 -B tools/freeze.py --write --key-path <outside path>` followed by a commit, done before any distribution.

---

## Approval 3: historical-record correction [HDR]

**Recommendation [REC]:** keep `KC-1-READINESS-AUDIT.md` r2.1 unchanged as a historical record. Issue **r2.2** as an edit of the same file, carrying a change table that states "no arithmetic changes" (git preserves r2.1). Alternatively, rely on F-LOG-0310 alone. That entry is append-only and already records items 3, 4 and 8.

The nine defects (`VALIDATION-REPORT.md` §3), triaged:

| class | defects |
|---|---|
| **Prevents misleading future readers** | 2 (pre-registration anchor); 3 (packets were not equally specific on the decisive content; F-LOG-0310 records it); 4 (blind1 B is not principle C); 8 ("(c) range" mixes two sources) |
| **Required before E4/R8** | 1 (stale review status); 5 (K18 composition labelled as computed); 6 ("no verdict changes" needs a scope); 7 (open-item lists omit R11 and CV9/§3b) |
| **Cosmetic** | 9 (word count in `blind2/key_sources.md`) |

**What this approval would authorize:** the r2.2 text edits only. No numbers change.

---

## Approval 4: external test execution [HDR]

**Separate from Approval 2.** Freezing the protocol does **not** authorize recruitment, transmitting materials, collecting responses or running the test.

**What this approval needs to state:**
- who may participate: human cohorts, and which non-Claude vendors;
- n per arm (at least 5, 10 recommended);
- the deadline;
- the named adjudicators, including an independent third adjudicator (P9);
- how responses are handled and stored;
- the data-handling terms;
- that no encoder may have access to this repository.

Claude cannot recruit humans or call non-Claude models in this environment. Execution is therefore run, or delegated, by the human.

---

## Approval 5: KC-1 [HDR] (already deferred)

- **KC-1 stays deferred under F-LOG-0309.** This package does **not** ask for KC-1 to be decided again.
- **It is reopened when either:**
  - (i) the external round is complete and reported; or
  - (ii) the human explicitly waives the external round and decides on the existing evidence, with the limitation recorded.

**The options, in plain language** (for when KC-1 is reopened). The figures are counts of E3-REL rows: the S-reading count, then the S′ count, out of 20 eligible rows. They are not ratios.

| option | what a guard row counts | E3-REL | records changed | Rq1 [E]: text alone fixes the answer | Rq7c [E-s]: crossing only via a coupling constraint | follow-on acts | evidence still missing |
|---|---|---|---|---|---|---|---|
| (a) T3 | The items whose link the rule forbids | 13/8 | none (recording only) | ~ (needs T3's list) | fails | KC-4 | external test of T3-with-list |
| (b) SH | Nothing: a pure prohibition touches no item | 9/4 | 6 host cells → none; new E3-REL acceptance | ~ (one uniform rule) | passes | KC-2, KC-3, KC-4 | external test of SH |
| (c) + K1c | Only asserted parts, by the K1c parse | 9/4 | 5 cells; new acceptance | ✗ without the parse; with it, untested | passes | KC-2, KC-3, KC-4 (+KC-5 under K rest) | external PARSE test (after P1) |
| (c) + R15ref / C-Σ | as K1c, but R15 reads the exception's items | 10/5 | 4 cells; new acceptance | as above | passes | as above | not in the mandatory arms |
| (c) + R15ref-R14 | as R15ref, plus R14 | 11/6 | 3 cells; new acceptance | as above | passes | as above | not tested |
| Branch S | Host lists as (a); E3-REL measures coupling separately | 9/4–11/6 by parse | E3:111 amended; new acceptance | as (c) | passes for E3-REL | KC-3 + amendment, KC-4 | as (c) |
| Defer (current) | — | 13/8 stays accepted under (α) | none | — | — | none | — |

**Why the counts differ.** Only R14, R15, R16 and R21 can change. They count under (a) because each forbids a link between items on both sides. They drop out under (b) and (c) wherever the forbidding is not counted.

Neither Rq1 nor Rq7c binds unless the human adopts it. The smallest count is not "better". Agreement between encoders does not make a normative rule correct.

---

## Explicitly not authorized by this package

Commits; pushes; branch switches, resets, merges, rebases, cherry-picks or stashes; edits to the protocol, the audit or the records; the protocol freeze; recruitment and test execution; KC-1 and KC-2 to KC-5; E4/R8; E5.

---

## Remaining route to the final theory

1. Approval 1: custody and commit.
2. Approval 2: protocol corrections, re-audit, then freeze.
3. Approval 3: r2.2 record corrections.
4. Approval 4: one external round.
5. Report the result, then KC-1 (or a waiver of the round).
6. Only the triggered KC acts; independent review of the resulting records.
7. Resolve or carry forward the E4/R8 blockers: C6; the spanning rule (F-LOG-0271); D12; C-γ; K18; R11, (g), (h), H13, A1, A5, CV9/§3b.
8. E4/R8 authorization, then review, then adoption.
9. E5: codebase, owning context, domain evidence beyond the low-crossing cut, conformance criterion.

**Smallest responsible next action [REC]:** Approval 1 (custody and commit). It secures the uncommitted F-series record without touching other work, and every later step depends on it.
