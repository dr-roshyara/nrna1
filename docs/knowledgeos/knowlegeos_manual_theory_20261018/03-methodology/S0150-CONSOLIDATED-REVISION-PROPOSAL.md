# The S0150 consolidated rule revision (PROPOSED)

| Field | Value |
|---|---|
| Status | **PROPOSED, NOT IN FORCE.** The rule freeze holds until the S0150 evaluation (decision record, 2026-10-20). Nothing here changes `RULEBOOK-v1.3.md`, `REVIEWER-INSTRUCTIONS.md` or the practices in force |
| Author | the Rule Reviewer, 2026-10-23, on the Principal's "yes" |
| Decided by | the Principal, once, at S0150, after the S0150 consolidation and its planted-gap test, with the Professor's evaluation figures filled in below |
| Replaces | the pending list `RULEBOOK-v2-PROPOSAL-REPORT.md` §19 (its items are carried here; §19 points to this file) |
| Result if adopted | **one rulebook again**: v1.3 revised, or v2, carrying everything in force; the decision record stops carrying practices |

*Markers: **[V]** verified in the lane's records · **[P]** evidence pending, to be filled in from the Professor's S0150 evaluation (prompt of 2026-10-23, item 5) · **[R]** the Rule Reviewer's recommendation.*

---

## 0. The first decision: which text becomes the one rulebook

| Option | What it means | For | Against |
|---|---|---|---|
| **A. v1.3 revised** | v1.3 keeps its structure; entries 54–60 and the six practices are folded into its sections; dormant and historical text is removed | the Professor and reviewers know its section numbers; no renumbering of citations in records | v1.3's structure grew by accretion (§12a after §12d, §11 branches) |
| **B. v2** | the v2 proposal (with its §0 theory first, the four claim questions, §7 dispositions, §10 critical problems) becomes the rulebook, updated with everything decided since | built as one text, each requirement once; already mirrors entries 56–60 | new section numbers; the Professor must re-learn the text once |

**[R]** Decide on evidence: if the Professor's evaluation shows that the scattered rules caused misses (for example the S0125 consolidation), take **B**; otherwise **A** is cheaper. Either way, the items below apply.

## 1. Items for the revision

| # | Item | Today | Proposed | Evidence | Decision test at S0150 |
|---|---|---|---|---|---|
| 1 | **One rulebook** | rules in v1.3 (entries up to 60), practices 1–6 and interpretations in the decision record, standing instructions in the prompts | everything in force in one text (option A or B); the decision record keeps decisions only | [V] the S0125 consolidation was missed while the rule stood in v1.3 §12 and entries 57–58 | adopt |
| 2 | **Consolidations enforced by tool** | §12 by instruction only | `close_source.py` refuses the next `full`/`skim` source in order once 25 sources have been read since the last settled consolidation (or a milestone review), until that consolidation is settled | [V] S0125 consolidation skipped, held only after the Rule Reviewer's check (2026-10-23) | adopt; one check in the tool |
| 3 | **Time per window, not per source** | per-source start/close times | the Professor's and the reviewer's harness time per window, with sources and lines; per-source times dropped | [V] per-source figures of 10–68 s are not plausible for the work; the Professor flagged the clock as uncertain | adopt |
| 4 | **Closed forks collect their questions** | F22 closed by a decision-record entry | a fork closed as a sketch gathers new questions in its TODO item and is reopened only by a critical finding or a new corpus source; questions to the Principal only if normative | [V] after closing F22, questions kept arriving (C22, C24) until the closure covered questions | adopt |
| 5 | **R5 calibration** | F4 alone makes a missing premise critical | F4 with no dependants (empty R3 list, no F3 role) is **high**; critical needs F1, F2 or F3, or F4 with dependants | [V] C25 W-02 "critical by rule" with no dependants; [P] the Professor's collected "critical by F4 only" notes | adopt if the notes show more such cases |
| 6 | **Self-checks kept or dropped on evidence** | lineage pass, source-first adversarial attacks, practice 2's corpus search, strength scan | each kept only if it reduced findings or caught what review missed; otherwise dropped | [V] high findings per window C23–C25: 5–8, about as during the run; [P] high findings per window C26–C28; the Professor's per-practice account | keep a self-check only with an example of what it caught |
| 7 | **Window size** | 5 in index order (10 only for a coherent series, review split) | **5 stays** while the reviewer corrects 5–8 high findings per window; a 10-source trial (review split into two commissions of 5) only if C26–C28 show fewer high findings | [V] T3 (10 + 4) missed 6 and introduced 3; [P] C26–C28 high findings | per the test in the cell |
| 8 | **Skim** | §3 as written; first skims in C23 | kept as is; the safeguard (one skimmed source read blind) stays | [V] C23: the safeguard found that S0125's skim lost theory; [P] skims and lost-theory cases by S0150 | keep; reconsider only if skims stay near zero |
| 9 | **Review cadence (entry 57)** | consolidation every 25 with one or two rotated chapters, short integration review; planted-gap test every second consolidation; whole-book review at milestones or every 500 | confirm, or revise per entry 58 | [V] consolidation S0101–S0130: 2,915 s for 30 sources (~10 % on top of the windows), 30 defects in the rotated Ch. 0–1; [P] the S0150 consolidation and its planted-gap result | confirm if the S0150 test does not show a fall in detection |
| 10 | **The canonical home of a concept** | register row and defining section both carry the meaning | the register owns identity, status, review and links, with at most a one-line gloss; the defining section owns the definition and governs when they disagree | [V] the register's meaning column and the sections drifted apart (restated forms, PS8-9) | adopt |
| 11 | **Challenge by type** (practice 5) | interpretation of entry 55 | rule text: mathematical, empirical, definitional and normative items each get their own challenge; definitions need no falsifier | [P] the Professor's account | adopt unless the account shows a problem |
| 12 | **The other practices** (1 research question, 2 prior work corpus first, 3 read before claiming, 4 effort by risk, 6 verification marks) | in the decision record | rule text, each with the evidence that it helped | [V] practice 2 brought 60 verified outside works into the book and showed rediscovered results; [P] the Professor's account for the rest | adopt each with evidence; drop the rest |
| 13 | **Reviewer hand-back fingerprint** | practice since C19 | rule: a reviewer's report is saved with its sha256 at receipt | [V] seven turn-7 reports were transcribed by the Professor before the practice | adopt |
| 14 | **Less record-keeping per window** | settlement, session-log paragraph, CONTEXT update, access entry | one session-log line per review pointing to the settlement; CONTEXT only when the state changes; the access entry before the review stays (it proves the order) | [V] each window's settlement already holds the content the session log repeats | adopt |
| 15 | **The reviewer instructions** | R4's proof-step table and the refutation-adequacy paragraph are pasted into each commission | written into `REVIEWER-INSTRUCTIONS.md` once | [V] v1.3 §6 still requires the paste "until the instructions carry it" | adopt |
| 16 | **Remove what is dormant or history** | §11 branches as a separate mechanism, T2 conditions, closed trial texts, §8 "dropped", §9 closed items, evidence quotes inside rules | branches merged into theory turns (as the v2 proposal does); history only in `METHOD-VERSIONS.md` | [V] branches used once (BRANCH-01); T1–T3 closed | adopt |

## 2. Not proposed (and why)

- **Wider corpus access** (`docs/knowledgeos/` outside the allowed list): no. About 14,000 such files exist, mostly outputs of earlier reconstruction lanes and later brainstorms; hold-out concepts could travel inside them (the Rule Reviewer's analysis, 2026-10-23). Files outside `docs/knowledgeos/` stay grantable case by case.
- **Repository evidence (Q1)** brought forward: only if corpus sources keep pointing to code; none has so far.
- **New checks:** none unless the S0150 test shows a missed class that a check would catch.

## 3. The evaluation figures to fill in at S0150 [P]

| Figure | Source | Value |
|---|---|---|
| Professor's time by activity, S0101–S0150 (reading and writing · reviews · settlements · kernel work · records) | the Professor's evaluation | |
| Harness time per window, C26–C28 (Professor · reviewer) | settlements | |
| High findings per window, C26–C28 | settlements | |
| Defects found later in work that had passed review | settlements, consolidation | |
| Consolidation S0150: harness time; defects in rotated chapters | its settlement | |
| Planted-gap test S0150: found k of n; treatment; blindness | `PLANTED-GAP-RESULT.md` | |
| Skims; skims checked by the safeguard; skims that lost theory | read log, settlements | |
| Each practice: an example where it helped; where it only added work | the Professor's evaluation | |
| "Critical by F4 only" notes | settlements | |
| Open kernel questions, with their origin | §8.18.3, Ch. 9 TODO | |

## 4. The decision at S0150

1. Option A or B (§0).
2. Items 1–16: adopt, adopt modified, or drop, each against its decision test.
3. The rule freeze ends with the adopted text; the decision record keeps the decision only.
