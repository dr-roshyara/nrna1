# Reading strategy and efficiency after the S0378–S0402 consolidation

| Field | Value |
|---|---|
| Status | **The Governance Engineer's paper, on the Principal's instruction** of 2026-11-03 ("prepare the reading-strategy paper after the consolidation"), with the target "my plan is at least 100 files a day". **It decides nothing**; each proposal reaches the Principal as a separate decision |
| Prepared by | the Governance Engineer (session `57ddce41…`), 2026-11-11, from repository records only |
| Inputs | `review/CONSOLIDATION-S0378-S0402/SETTLEMENT.md` §3 (trial figures) and `PLANTED-GAP-RESULT.md`; `ANALYSIS-WINDOW-TIMES-AND-SAVINGS-20261103.md`; `REVIEW-STRATEGY-AND-IMPLEMENTABILITY-20261103.md` R3, R5, R6 (advisory); `RULEBOOK-v1.4.md` §2–§3; `tmp/corpus-reading/READ-LOG-CHRONO-20261018.tsv`; commit times of C100–C111 |
| Labels | **[V]** from the records · **[I]** interpretation · **[P]** proposal · **[U]** unknown |

## 1. Where we stand

- **[V]** Reading position S0402 of 2,734 allowed sources (about 15 %); **about 2,330 left**. Reading is paused at S0403 for theory turn 1 (issued 2026-11-11).
- **[V]** Sources logged per day: 6, 15, 19, 37, 25, 50, 5, **62**, 33 (2026-10-25 to 11-03). Best day 62.
- **[V]** Decided since 2026-11-03: windows up to ten sources for coherent series (index W3); role classification (W8); planted-gap preparation off the critical path (K1); minor access deviations recorded without a stop (A6); the theory-turn chain (T2).

**[I] At 100 a day the remaining corpus takes about 23 working days; at today's typical 40, about 58.**

## 2. What the trial shows about speed

| | C100–C105 (1–2 sources a window) | C106–C111 (trial) |
|---|---|---|
| Wall-clock, consolidation settled → last window settled [V] | — | 04:47 → 08:28 on 2026-11-03: **3 h 41 min for 25 sources** |
| Wall-clock per source [I] | about 16 min | **about 9 min** |
| Professor's own minutes per source [V] | 4.6 | 1.5 |
| Misses per source [V] | 0.88 | 0.92 |

**[I] Three conclusions:**
1. **Bigger windows nearly doubled speed without losing quality.** That is already decided.
2. **The Professor's own work is now small (1.5 min a source).** Most of the wall-clock is **waiting for reviewers**: step 1 and step 2 run while the Professor does nothing, and the next window does not start until the last one settles.
3. **At about 9 min a source, 100 sources need about 15 hours of windows**, plus four consolidations. **100 a day is not reachable by window size alone.** About 50–70 in a long working day is realistic today.

## 3. Proposals, ranked by gain

### E1. Overlap windows: read the next window while the reviewers check the last one [P]

- **What:** while window *n* is in reviewer step 1 or 2, the Professor reads and drafts window *n+1*. Window *n+1*'s review brief is issued **only after window *n* settles**, so it lists *n*'s changes as Rulebook §2 item 9 requires. If *n*'s settlement changes something *n+1*'s draft relies on, the Professor corrects the draft before its brief (an R3 note).
- **Why [V]:** the reviewer steps are about half of each cycle (`ANALYSIS…` §1), and the Professor is idle during them. No rule requires idle time: §2 item 9 requires only that the brief carries the previous settlement.
- **Gain [I]:** about 1.5–1.8× more sources an hour, so about 5–6 min a source. This is the **single largest step towards 100 a day** that does not reduce review.
- **Risk:** drafts built on text that settlement then changes (rework). **Measure:** rework minutes per window and the misses per source at S0403–S0427, against the trial.
- **Copy-ready:**
  > "From S0403, the Professor may read and draft the next window while the reviewers check the current one; the next window's review brief is issued only after the current window settles and lists its changes, and a draft that relied on changed text is corrected before its brief. Rework minutes are reported per window; checked at the S0403–S0427 consolidation."

### E2. A mechanical pointer check (P-Q4) [P]

- **What:** a tool, run with the other step-8 checks, that lists every section reference (§N.M, §N.M.K) in changed sections that does not resolve to an existing heading.
- **Why [V]:** a stale pointer was planted and **missed by consolidation reviewers in 3 of the last 4 tests** (S0302, S0352, S0402). At S0402 the quotation check, a tool, caught the reversed-quotation plant (D-01).
- **Gain:** quality, not speed. It removes a class of error people reliably miss, so reviewers can spend attention on judgement.
- **Risk:** none to independence; false positives on references to planned sections (listed, not failures).
- **Copy-ready:**
  > "The Professor adds a pointer check to the step-8 tools: every § reference in a changed section must resolve to an existing heading, or be listed with its reason. It runs before every review and settlement from the next window."

### E3. Consolidations built from window records [P]

- **What:** the consolidation's scope and miss table start from the settled windows' records (findings, R3 notes, per-window report under K2) rather than being re-derived. Reviewers still read the book itself.
- **Gain [I]:** a shorter consolidation preparation; the figure is not measured yet. **Risk:** none to review, if reviewers still read the book. **Measure:** consolidation preparation minutes.
- **Copy-ready:**
  > "Consolidation scope and the per-window table are built from the settled window records; reviewers still read the book itself. Preparation minutes are reported."

### E4. Fewer commits and fewer records per window [P]

- **What:** one commit for draft + step-8 record (today often two or three, `git log` C106–C111), and one for the settlement.
- **Gain [I]:** small, a few minutes a window. **What is kept:** the draft is still committed before the reviewer's step 1 (independence).
- **Copy-ready:**
  > "A window has two commits: the draft with its step-8 record before the reviewer's step 1, and the settlement."

### E5. Measure before trimming review (R5 of the advisory review) [P]

- **What:** for each window since S0303, classify findings as found at the window review only, at the consolidation only, or at both. **No review is reduced until it reports.**
- **Why:** the role classification will move effort away from supporting items; whether step 2 can be lighter on windows that change no concept is a question for data, not assumption.
- **Copy-ready:**
  > "The Governance Engineer adds to the review-quality analysis, for every window since S0303, whether each finding was found at the window review, the consolidation, or both. No review is reduced until it reports."

## 4. What not to change

- **Reading in index order stays the coverage line** (§3). Targeted reading is already available inside theory turns (at most 8 sources by search).
- **Blind-first review and planted-gap tests stay.** The last score was 2 of 4; this is no time to reduce review.
- **Skimming repeated sources stays forbidden** (§3: a source that "repeats earlier material" is read full). The role classification shortens *writing* about them, not *reading* them.

## 5. The arithmetic with everything [I]

| Step | Minutes a source (approx.) |
|---|---|
| Trial, today | 9 |
| + E1 overlap | 5–6 |
| + role classification (one-line supporting items), E3, E4 | 4–5 |
| **100 sources** | **about 7–8 h**, plus four consolidations (about 40 min each; about 1.5 h with a planted-gap test after K1) |

**100 a day becomes reachable on long days with all of the above, while theory turns pause reading.** Theory turns are counted separately and are not traded against sources (advisory R6).

## 6. Decisions for the Principal (one at a time)

1. E1 overlap of windows (largest gain).
2. E2 pointer check.
3. E3 and E4 together (housekeeping).
4. E5 measurement.

**When:** before reading resumes at S0403, which is after theory turn 1 settles (and turn 2, unless the Principal resumes reading earlier).
