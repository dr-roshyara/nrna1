# Commit Governance — Controlled Counterfactual Experiment

**Date:** 2026-09-29. Corpus is evidence, not authority. Does not activate the hook, does not
finalize the commit format, does not build EKS/PKS, does not train ML, does not freeze the Kernel.

## 1 · Research question

> Does session evidence contain information not already contained in work-item, temporal,
> governance, and provenance evidence — tested via controlled counterfactual ablation, not
> correlation?

## 2 · Previous null result (recap)

`2026-09-29-KOS-COMMIT-GOVERNANCE-EMPIRICAL-VALIDATION.md` §6/§8: of 14 real commits containing a
bare, unverified UUID-shaped string, all 14 were already `CONFIRMED` via citation + temporal
evidence alone — 0 classification changes.

## 3 · Corrected hypothesis status (accepted correction, not defended)

That document's §8 table called `CitesSession(c,s) → improved M4 determination` **`REFUTED`**. This
overclaimed. The corrected status:

```
H_session: "Session evidence improves determination."
OBSERVED:   Δ determination = 0, n=14 (bare, unverified citation only)
SUPPORTED:  no marginal information gain was observed in that specific sample
NOT SUPPORTED: a general claim that session evidence is useless
NOT FALSIFIED: the possibility that verified session evidence helps
UNKNOWN:    whether session evidence helps when paired with independent corroboration
```

## 4 · Controlled experiment design

The ideal factorial design (`WORK_ITEM {present,absent} × SESSION {present,absent} × TEMPORAL
{valid,invalid}`) **cannot be built from naturally-occurring data**: direct query of the corrected
379-commit population found **zero** commits combine a session reference with either `TEMPORAL_MISMATCH`
or `UNCITED` — only `(work_item present, session absent, *)` and `(work_item present, session
present, CONFIRMED)` cells are populated. A natural factorial design is impossible here; reported as
a finding, not worked around by fabricating governance records.

**Design used instead**: counterfactual deterministic ablation over real evidence, with one
dimension (`session_cited`) toggled hypothetically and a second, genuinely-real dimension
(`session_log_corroborates`) checked against actual committed files
(`.claude/sessions/<date>.md`) — grep for the case's work-item string in that date's session log.
This keeps Question A (provenance/corroboration — checkable) and Question B (authorization — not
addressed here) strictly separate, per the required distinction.

## 5 · Ground-truth / observability model

| Input to the rule | Status for the 4 originally-selected cases | Status across all 27 `TEMPORAL_MISMATCH` cases |
|---|---|---|
| `session_cited` | **Hypothetical/counterfactual** — none of these commits actually cite a session in their own message (confirmed: 0/27 do) | same — never real for any case in this corpus today |
| `session_log_corroborates` | **`DIRECTLY_OBSERVABLE`**, verified by direct grep before any prediction was recorded | **`DIRECTLY_OBSERVABLE`** for 26/27, checked systematically after the initial 4-case selection was flagged as biased (see §8) |

This distinction is the entire point of the experiment: the rule's *precondition* is real; its
*trigger* (a commit actually citing a session) is not yet real anywhere in this corpus.

## 6 · Evidence ablation

`E0` = commit exists → `E1` = + work-item citation → `E2` = + grant citation (shown not to matter,
`H4`) → `E3` = + temporal containment → `E4` = + session citation, gated on independent
session-log corroboration (this experiment's new dimension). No `E5`/provenance stage was added —
out of scope for this specific test.

## 7 · Results

**TDD discipline, genuinely test-first this time** (correcting the prior disclosed deviation):
predictions were written to a file *before* any code existed
(`controlled_experiment_predictions.txt`, scratchpad); `test_session_ablation.py` was written and
run against a nonexistent module first — **confirmed RED** (`ModuleNotFoundError`); then
`session_ablation.py` was implemented; **confirmed GREEN**, 5/5 unit tests passing.

**Applied to the 4 originally hand-picked real cases** (`480207d66`, `386942459`, `56fa5706f`,
`73d056c84`, all real `TEMPORAL_MISMATCH` commits from 4 distinct work items): all 4 flip to
`CONFIRMED` under the rule, exactly as predicted. Controls behaved as predicted (a `CONFIRMED` base
case stays unchanged; an explicitly uncorroborated synthetic case stays `TEMPORAL_MISMATCH`).

**Bias check, and the actual decisive result**: the 4 cases were hand-picked *because*
corroboration was already known to exist — a real selection bias, disclosed rather than hidden.
Re-ran the corroboration check against **all 27** `TEMPORAL_MISMATCH` cases (not just the 4), with
no case selected for its outcome in advance. Result: **26/27 (96.3%) have real, independently
verifiable session-log corroboration for their work item on their commit date.** This is not
tautological — it is a direct, checkable fact about which files exist in this repository, discovered
by a systematic sweep after the bias was noticed, not cherry-picked.

## 8 · Counterexamples

**1/27**: `1a1d006b1` (2026-09-28, attributed to `KOS-CONTRACT-NEUTRALITY-001`) shows zero
session-log corroboration. Direct inspection: this commit's real subject is *"record KOS-* review-
document placement convention"* — a repo-wide, meta-level chore applicable to all `KOS-*` work
items, not substantively about `KOS-CONTRACT-NEUTRALITY-001` specifically. It was attributed to that
work item by the (already-fixed-once) extractor's earliest-position matching, which still isn't a
perfect semantic match — a further, minor, disclosed instance of representation error, not a
counterexample to the corroboration finding itself.

## 9 · Session-ID information value — reframed

**The bare commit-embedded UUID (already tested, §2) has zero measured value.** **A completely
different, already-existing artifact — the committed session log — has real, largely unused,
corroborating power for 96.3% of the corpus's current temporal-ambiguity cases.** The valuable
signal was never "does the commit cite a session id" — it is "does an independent session-log entry
for that date corroborate the work item," a check the deterministic model (`M1`–`M4`) has never
performed at any point in this research, on data that has been sitting in the repository the whole
time. This is the single most actionable finding of this experiment, and it does **not** require the
new commit-message rule, hook activation, or format decision at all — it only requires teaching the
existing ablation to cross-reference `.claude/sessions/<date>.md`.

## 10 · Governance-mechanism falsification attempt

Searched the 13 already-blind-reviewed cases (§9/§3 of the two prior documents) for a counterexample
to *"a dated, human-attributed statement precedes and justifies the recorded act."* **No
counterexample found.** The nearest candidate, `GOLD-001` (`9f83a369c`), is not a counterexample —
its *absence* of a preceding statement correctly predicted its procedural
`UNAUTHORIZED_BY_RECORD` determination, which is consistent with (further replicates) the invariant
rather than refuting it. `REF-013` shows authority can be track-level rather than grant-specific,
but is still a preceding, dated, human-attributed statement. **Caveat, stated plainly**: this
repository's own governance design may structurally *require* a preceding human act for everything
it records — meaning "no counterexample found" could reflect this corpus's own design choice rather
than a universal law. The invariant is `NOT FALSIFIED`, n=13, not `CONFIRMED` as general theory.

## 11 · Logic / implication status

| Implication | Status |
|---|---|
| `CitesSession(c,s) → improved determination` (bare citation) | `NOT SUPPORTED` (prior experiment, unchanged) |
| `CitesSession(c,s) ∧ SessionLogCorroborates(s) → improved determination` | `SUPPORTED` for the counterfactual rule's logical behavior (26/27 real corroboration base rate); **`UNTESTABLE` against real citation data**, since no commit in this corpus actually cites a session yet |
| `SessionLogCorroborates(work_item, date)` exists independent of any commit | `OBSERVED`, 26/27, directly verified |
| `PrecedingHumanAuthorityStatement` invariant (§9 of prior architecture doc) | `NOT FALSIFIED`, n=13, caveat as above |

## 12 · Kernel implications

None promoted. The reframed finding (§9) strengthens `Provenance`'s existing `OBSERVED` status
(kernel candidate table, prior documents) by showing a concrete, currently-untapped provenance
source (session logs) rather than by adding a new candidate.

## 13 · EKS/PKS implications

None. `OQ-11` untouched.

## 14 · UNKNOWN

Whether cross-referencing session logs would ever *change* a real commit's classification once the
new rule actually starts being used going forward (still untestable — no real commit cites a
session id yet). Whether the 96.3% corroboration rate holds outside this specific corpus's unusually
disciplined session-logging practice (an `EP-01`-mandated habit, not a general property of software
repositories). Whether the one exception (`1a1d006b1`) represents a broader class of
meta/cross-cutting commits the extractor systematically mishandles.

## 15 · Next smallest decisive experiment

Build the session-log cross-reference check directly into the deterministic ablation (a small,
scoped code change to `ablation3.py`'s logic, not a new architecture) and re-run it against the full
379-commit population, to see how many of the 27 real `TEMPORAL_MISMATCH` cases it would actually
resolve using data the repository already has — turning this experiment's finding into a measured,
population-wide result rather than a 27-case manual check. This is smaller and more decisive than
waiting for real commits to adopt the new syntax, and requires no ML, no hook activation, and no new
governed capability.

---

**Traceability:** `2026-09-29-KOS-COMMIT-GOVERNANCE-EMPIRICAL-VALIDATION.md` (source of the
corrected `REFUTED`→`NOT SUPPORTED` framing) · `ablation3_results.json` (population reused
unmodified) · `session_ablation.py`/`test_session_ablation.py` (scratchpad, genuinely RED-first this
time, not committed to the repo — a research script, not a production capability) ·
`2026-09-29-KOS-EKS-PKS-GOVERNANCE-ARCHITECTURE.md` §9/§10 (source of the `PrecedingHumanAuthorityStatement`
hypothesis tested in §10 here).
