# M-04-T1 trial log

*Times are `date -u` stamps taken during the work, plus transcript entry times. Human activity is not recorded. Protocol: `M-04-T1-trial-protocol-PROPOSED.md`.*

## Round 1 — batch-3 correction (2026-10-18)

| UTC | Step | Result |
|---|---|---|
| 15:47:58 | start; protocol written | — |
| ≈ 15:48:40 | tools written: `coverage_check.py`, `register_check.py`, `anchor_check.py`; first anchor check | 304 anchors in range |
| 15:49:29 | defects 1, 3, 4, 5, 6, 7 corrected (Ch. 1, Ch. 2, glossary) | all edits applied |
| ≈ 15:50 | Principal message: keep the step-7 change (E-3) separate → E-3 removed from the trial protocol | — |
| ≈ 15:50 | defect 2: V3 §10 lifecycle integrated into Ch. 1 §1.14.1; anchors verified (E-2) | — |
| 15:50:24–15:50:52 | **the Principal reorganised the manuscript** into `chapter_00/01/02`; files intact; paths adopted | — |
| ≈ 15:51 | Ch. 0 §0.9 question 6 (OQ-9) added; register: `disposition` column, 8 rows added, follow-ups, location fix | — |
| 15:52:22 | E-1 coverage: 146 uncovered lines read; 5 rows added, 12 locations extended, 104 lines marked non-propositional | **0 uncovered** on all five files; register check **0 findings** on 115 rows |
| 15:52:50 | change logs, glossary (valid / well-founded), correction note in `REVIEW-batch-03.md`, read log | — |
| ≈ 15:53 | targeted independent review commissioned (E-8; background only; read-only reviewer) | completed ≈ 15:55 (≈ 2.3 min, 12 tool uses) |

## Measures, round 1

| Measure | Value |
|---|---|
| elapsed (logged), start → review commissioned | ≈ 5.5 min |
| uncovered non-blank lines, five files | 222 before the round (efficiency review §3) → 146 after the first rows → **0** |
| propositional passages found by E-1 beyond the self-review | 5 new rows + 12 location extensions |
| rows added in total | 13 |
| dispositions normalised to step 6a | 115 batch-3 rows; 0 invalid |
| line anchors out of range | 0 of 307 |
| rework inside the round | 2: my own correction note first stated an unverified count ("23 lines"), then a wrong one ("8 new rows"); both fixed before the round closed |
| compound epistemic marks | 52 (informational; single marks are part of A-1, pending) |

## Observations so far (trial, not conclusions)

- **E-1 found what look-backs had missed**, and fast. The cost is reading every uncovered line, which is mostly headers.
- **E-1 checks lines, not propositions.** A line covered by a row can still hold a second, unregistered claim.
- **E-2's tool checks only that anchors are in range**, not that they point at the right text. Real anchor correctness still depends on citing from verified output.
- **E-4 made the register checkable in one call.** The derivation rule needed five hand corrections out of 110 rows.
- **The only rework in the round was in my own summary figures.** Rule E-2 (cite from verified output) applies to counts as well as anchors.

## Round 2 — dispositions of the targeted review (2026-10-18)

| UTC | Step | Result |
|---|---|---|
| ≈ 15:55 | review received: items 1, 2, 4 DEFECTIVE; 3 SOUND WITH MINOR ISSUES; 5 mixed; 4 further defects noticed in passing | — |
| ≈ 15:55–15:58 | every finding re-checked against the text; all confirmed (one re-scoped) | — |
| 15:58:26 | round-2 corrections in Ch. 1, Ch. 2, Ch. 0 §0.7, glossary; register rows updated; checks | register **0 findings** (115 rows); anchors **0 out of range** (310) |
| ≈ 16:00 | disposition record `06-theory/manuscript/REVIEW-targeted-batch03-corrections.md`; workflow proposal v2 | — |

| Measure | Value |
|---|---|
| items reviewed / defective | 5 / 3 (+ 1 with minor issues, 1 mixed) |
| findings accepted | all (one re-scoped) |
| foundational defects found in **corrections** | 1 (static bound reintroducing the refuted case) |
| reserved choices surfaced | 3 (ending policy E; I-2 status; premise P-E) |
| round-2 corrections independently reviewed | **no** (needed before reliance) |
