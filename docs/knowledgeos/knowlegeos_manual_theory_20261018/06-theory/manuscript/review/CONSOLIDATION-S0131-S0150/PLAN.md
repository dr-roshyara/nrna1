# Consolidation S0131–S0150: plan (for the Principal's approval; nothing commissioned yet)

*2026-10-24. By the Professor, following `README-PROFESSOR.md` (the Rule Reviewer) and the Principal's instruction of today. Rulebook v1.3 §12, entries 57–60. The object is the frozen copy `BOOK-UNDER-REVIEW/` only.*

## 1. Scope

| Part | Content |
|---|---|
| **Windows since the last consolidation** | C25–C28: §8.18, §8.19, §8.20, §8.21 (in the copy) |
| **Concepts without an independent consolidation verdict** | **CON-0238–CON-0256** (19: 15 PROVISIONAL, 4 INVALID), from the copy's register. A verdict is asked for each PROVISIONAL. For CON-0246, 0247, 0252 and 0255 (INVALID as stated) the restated core is checked, and the INVALID verdict is not re-litigated. **Plus** the concepts homed in the rotated chapters: Ch. 2 (9: 2 VALIDATED, 2 PROVISIONAL, 5 INVALID) and Ch. 3 (44: 5 VALIDATED, 12 PROVISIONAL, 27 INVALID). This includes the Ch. 3 rows marked "not reviewed here" at S0125 |
| **SEQ order** | of every section in scope and of the chapters it touches: used before defined, never defined, stale citation |
| **Rotated, in full** | **Ch. 2** (`chapter_02/02.00`–`02.11`, `02.00a`, `02.99`, `TODO`) and **Ch. 3** (`chapter_03/03.00`–`03.10`, `03.99`, `TODO`), in the copy. Ch. 3 carries the book's 3 inherited quote failures |
| **Also** | the copy's `BOOK-OUTLINE.md` (rotation section, the marks tables) and `THEORY-MAP.md` §9 (position, counts) and the rows touched by C25–C28 |
| **Sources** | S0131–S0150 in index order. All were inventoried blind in their windows, and those inventories are saved (`review/C25-…` to `review/C28-…/STEP1-INVENTORY.md`). Skimmed: S0147, S0149 (C28; S0149's skim lost theory, repaired) |

## 2. Parts and order

| # | Part | Who | Input | Output |
|---|---|---|---|---|
| 0 | **Freeze and record** | Professor | — | `SCOPE.md` (scope as above), `frozen-table.md` (sha256 of every file of the copy), committed. **The working book is frozen from this commit** (no edits to chapter sections, the register, the map or the outline) until the Rule Reviewer has scored |
| 1 | **R3, run on the copy, before the reviewers** | Professor | the copy's register; `inbound_citations.py --root BOOK-UNDER-REVIEW` | direct dependents of the INVALID-as-stated concepts in scope (CON-0246 → 0248, 0247 → 0249, 0252 → 0254, 0255 → 0256, and their homes). The transitive list is recorded only if informative (S0125: 220 items, too wide). Written into `SCOPE.md` |
| 2 | **Reviewer A (Opus)** and **Reviewer B (Sonnet)**, in parallel, independently | subagents | `BRIEF.md` + `REVIEWER-NOTE.md` (verbatim, attached to every commission) | `REPORT-A.md`, `REPORT-B.md`, saved verbatim with their sha256 at receipt |
| 3 | **Integration review (Opus)** | subagent | both reports, the R3 lists, `INTEGRATION-COMMISSION.md` + `REVIEWER-NOTE.md` | `INTEGRATION-REPORT.md` (both / one-only / disagreements; cross-chapter consequences of each correction) |
| 4 | **Settlement, stage 1: on the copy** | Professor | the three reports | `SETTLEMENT-STAGE1.md`: each finding's disposition and the **stated correction** against the copy's text, committed. Then **tell the Principal and the Rule Reviewer**. No change to the working book |
| 5 | *(the Rule Reviewer scores)* | Rule Reviewer | stage 1 | the score; the freeze ends |
| 6 | **Settlement, stage 2: the working book** | Professor | stage 1 and the score | the corrections applied to the working book **where it has the same defect**, register and map updated, entry-58 record. `SETTLEMENT.md`, committed |

**No new blind step** (the Professor's choice, for review, as at S0125). Every source in scope was inventoried blind in its window. Ch. 2 rests on Ch. 0–2 basis files, which reviewers may not read.

## 3. Briefs (contents; the texts are written at part 0)

**`BRIEF.md` (A and B):**
1. **Concept verdicts** for CON-0238–0256 and the Ch. 2 and Ch. 3 concepts, with the INVALID cores checked as restated.
2. **Ch. 2 and Ch. 3 in full:** definitions, derivations, assumptions, quotations spot-checked against the corpus, cross-references, and stale text against later decisions (§9.13 C1, §9.15, §9.16). **Count the defects found in them separately** (entry 58).
3. **SEQ** of the sections in scope.
4. **Architecture view:** what C25–C28 added to primitives, laws or the kernel/application boundary, and what is observation of one estate only; kernel or application, minimal, stable, for each concept.
5. **Step 9:** spot-check the C28 settlement's rows against the copy.
6. **Pattern checks**, recurring in C25–C28:
   - a rule quoted but applied beyond its text;
   - a derivation missing its premise (the converse taken, an unstated "for all");
   - an INVALID concept cited as a home;
   - a source's self-issued status adopted;
   - a skim that lost theory;
   - a measurement carried forward unexamined.
7. **How:**
   - The copy only, its tools (`book_search.py` in the copy), and R1–R3 and the quote check run on the copy, as `REVIEWER-NOTE.md` says.
   - Corpus from the allowed list only. No Ch. 0–2 basis files.
   - Never sealed, hold-out or quarantine files, `.env*`, `_batch_input*` or `chronological-read/*`.
   - No corpus or book text outside. Edit nothing.
   - Do not read the other reviewer's report.
   - Scratch scripts in the scratchpad with prefix `cons_a_` / `cons_b_`, run with `python3 -I`.
   - **End the report with the list of every file opened and every search run** (`REVIEWER-NOTE.md` item 6).
8. **Report**, delivered once through the hand-back, beginning "CONSOLIDATION S0131-S0150 REPORT A" (or "B"). Tables: verdicts; findings (A-nn / B-nn · finding · severity · type · evidence · correction · resolved when); the rotated-chapter defect count; R5 notes ("critical by F4 only, no dependants" where so).

**`INTEGRATION-COMMISSION.md`:**
- both reports (paths and sha256), the R3 lists and `REVIEWER-NOTE.md`;
- tasks: classify each finding (both / A only / B only / disagree) and check one-only findings against the copy;
- for each proposed correction, its cross-chapter consequences (R3 run on the copy);
- an overlap estimate for the rotated chapters, indicative only;
- a list of files opened.

**What the briefs do not say:**
- No mention of a test, planted gaps or any difference from another version.
- No pointer to the working book, beyond `REVIEWER-NOTE.md` item 4's prohibition.

## 4. Isolation

| Side | Rule |
|---|---|
| **Reviewers** | read the copy only; REVIEWER-NOTE item 4's files never; no git history of the manuscript; not each other's reports (A and B); a file list at the end of every report. A report without it is recorded as unverifiable on that point |
| **The Professor** | no edits to the working book from part 0 until the score. **No comparison of the copy with the working book**: I read the copy only where a finding points, and do not diff, hash-compare or open the working counterpart while settling stage 1. Commission files go only in `CONSOLIDATION-S0131-S0150/`. Commit subjects name parts and IDs, never findings or locations before stage 1 is told |
| **The answer key** | sealed outside the workspace (the Rule Reviewer). I do not look for it, and `00-access/ACCESS-RECORD.md` is used only to confirm its sha256 is recorded |
| **Practice-2 work during the freeze** (§6) | read-only corpus searches, logged; no book edit; results kept in `PRACTICE2-NOTES.md` in this folder. Applied at stage 2 |

## 5. Entry 58 record

- **Harness time:** A, B, integration, and the total. Wall clock of the review phase.
- **Settlement time:** stage 1 and stage 2, separately (the README asks for the settlement too).
- **Rotated-chapter defects:** A, B, the overlap and the union, per chapter (Ch. 2, Ch. 3). The integration reviewer's estimate is indicative only.
- **R5 notes:** "critical by F4 only, no dependants", collected.

## 6. Practice 2 for the new concepts (the Principal: "apply practice 2 to new concepts")

**Procedure, per decision record practice 2:**
1. a **logged corpus search** (`tmp/tools/branch_search.py --log`; terms from the register and the book, and the known names of the idea); relevant hits read whole as `topic-full`, at most 8 per concept; the reading position does not move;
2. what the corpus says is cited as **Src**;
3. outside literature: no corpus or book text in any search;
4. the idea stated as established, adapted, extended, contradicted or possibly new.

**CON-0251 first.** Terms: happened-before, causal order, logical clock, partial order, citation order, Lamport, vector clock.
- The candidate outside work is *Ext:* Lamport, L. (1978), "Time, Clocks, and the Ordering of Events in a Distributed System", *CACM* 21(7), 558–565. Its happened-before relation is the transitive closure of process order and send→receive.
- In CON-0251, a citation plays the role of the message, and record order plays the role of process order. "Only if, absent a shared order source" is Lamport's point that without physical clocks events are only partially ordered.
- *Expected classification, to be confirmed by the search:* **established (adapted)**, cited if it applies.

**Then the other new concepts (CON-0239–0256, non-INVALID)**, each with its candidate known name, to be confirmed or rejected by search:
- CON-0245: the lost-update anomaly;
- CON-0253: mutation testing;
- CON-0256: the test-oracle problem;
- CON-0243: tamper-evident logs, already cited in K1;
- CON-0242: reproducibility versus replication;
- the others: search terms from their rows.

**Timing:** the searches run during the review (parts 2–3), read-only. The citations go into the working book at **stage 2**, after the score, together with the settlement. They are new changes, not corrections found by reviewers, so they do not enter stage 1 on the copy.

## 7. Points for the Principal

1. **Stage 1 form:** I read "settle on the copy (stated corrections)" as a settlement document stating each correction against the copy's text, **without editing the copy's files**, so the reviewed object stays as it was. If you meant the copy's files to be edited, I will do that instead.
2. **Integration reviewer:** Opus, as at S0125.
3. **Practice 2 timing:** searches now (read-only); citations at stage 2. The alternative is to hold all practice-2 work until after the score.
