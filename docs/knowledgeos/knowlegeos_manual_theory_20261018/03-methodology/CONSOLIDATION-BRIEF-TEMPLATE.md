# Consolidation brief template (fixed; maintained by the Governance Engineer)

| Field | Value |
|---|---|
| Status | **In force** from the next consolidation (S0200 decision D6, decision record addendum 2026-10-29T02:08:48Z) |
| Maintained by | the Governance Engineer; changed only on evidence, with the change noted in §6 |
| Used by | the Professor, who commissions every consolidation and test reviewer from it |
| Rules it implements | Rulebook v1.4 §12; `REVIEWER-INSTRUCTIONS.md`; S0200 decisions D2–D6 |

**What is fixed and what is filled in.** Text in this template is copied verbatim. Only fields in `<angle brackets>` are filled in, each from the source named beside it. A brief that changes fixed text, or leaves a field open, is not used until the Governance Engineer has seen it.

---

## 1. For the Professor (not copied to reviewers)

### 1.1 Folder layout (decision D4)

| Folder | Contains | Reviewers |
|---|---|---|
| `06-theory/manuscript/review/CONSOLIDATION-<Sfirst>-<Slast>/` | `SCOPE.md`, `REVIEWER-NOTE.md`, one `COMMISSION-<X>.md` per reviewer, `INTEGRATION-COMMISSION.md`, the reports and their sha256 files, `BOOK-UNDER-REVIEW/`; later the settlements | each opens **only the files its commission lists** |
| `00-access/consolidations/<Sfirst>-<Slast>/` | everything for the Professor only: freeze record, time table, access notes on the reviewers' transcripts; in a test consolidation also the Governance Engineer's instructions to the Professor | **never** (forbidden in every commission) |

**No file that mentions a test, a key, plants or a comparison with the working book is placed in the reviewers' folder** — not even in a test-free consolidation, so that the folder looks the same either way.

### 1.2 Before any reviewer starts

1. Fill in `SCOPE.md` (§2), copy `REVIEWER-NOTE.md` (§3), write each commission (§4) and the integration commission (§5).
2. Check the **coverage plan** (§2.6): every section in scope is read in full by at least one named reviewer. If one reviewer cannot cover a long chapter, plan a split commission now, not after the review (S0200: Ch. 8, about 4,100 lines, was read in full by neither A nor B, and needed reviewer C).
3. Commit the briefs with a neutral subject, and report the commit to the Principal for the Governance Engineer.
4. **Test consolidation** (every second): reviewers start only after the Governance Engineer has seen the briefs (the Governance Engineer prepares the copy anyway). **Other consolidations:** reviewers may start at once; the Governance Engineer checks the briefs afterwards, and any defect found is corrected in the next brief.

### 1.3 The Governance Engineer's check of a brief

| # | Check |
|---|---|
| G1 | every commission has a "Files you may open" list; `00-access/` and the working book are forbidden |
| G2 | no file in the reviewers' folder mentions a test, key, plant or comparison |
| G3 | the scope follows §12 and D5: windows' new sections; passages changed by the windows' settlements; rotated chapters per `BOOK-OUTLINE.md`; concepts homed in scope only |
| G4 | the coverage plan gives every in-scope section a full reader |
| G5 | the exclusion list matches the access record (exposed and non-allowed sources) |
| G6 | R2 is run with the scope (D2); the D3 check is in the instructions |
| G7 | fixed text unchanged |

---

## 2. `SCOPE.md` (fixed before the reviewers start)

```markdown
# Consolidation <Sfirst>–<Slast>: scope (fixed before the reviewers start)

*<date>. Due after <Slast> (Rulebook v1.4 §12). Windows <Cfirst>–<Clast>.*

**The object** is the frozen copy `BOOK-UNDER-REVIEW/`, and only it.

## 2.1 Sections in scope (in the copy)
- **Written in <Cfirst>–<Clast>:** <section files>   ← from the windows' settlements
- **Changed by the settlements of <Cfirst>–<Clast>, only the passages changed:** <file: passage>   ← from the settlements' "where applied" columns
- **Rotated, in full:** <chapters>   ← the rotation in BOOK-OUTLINE.md
- **Also:** `BOOK-OUTLINE.md` (the rotation section) and `THEORY-MAP.md` (position, counts, the lines for <Cfirst>–<Clast>).
- **SEQ order** of the chapters the windows touched: <chapters>. The question is whether each chapter's sections and index follow a reading order in which nothing is used before it is introduced.

## 2.2 Concepts (only these)
- **Homed in the sections in scope**, by status: <VALIDATED list> · <PROVISIONAL list> · <INVALID list>   ← from CONCEPT-REGISTER.tsv, by home
- **Rule:** a verdict for each PROVISIONAL, a second, independent verdict for each VALIDATED, and each INVALID core checked as restated (not re-litigated).
- All other concepts without a consolidation verdict **wait for their chapter's rotation** (v1.4 §12).

## 2.3 Coverage basis (by name)
- <review/Cnn-…/STEP1-INVENTORY.md, one per window>. Nothing else from the review folders.

## 2.4 Sources
- In index order, all on the allowed list: <Sfirst>–<Slast>. Others only from `CORPUS-ALLOWED.tsv`.
- Outside books and articles the sources cite are not opened; their authors are not reproduced.
- **Never:** <exposed and non-allowed sources, from 00-access/ACCESS-RECORD.md>; sealed, hold-out or quarantine files.

## 2.5 Budget
- About <minutes> of review per reviewer (the last consolidation: <figure>). Out-of-scope observations are one line each, not reviewed. What cannot be covered is reported, not skimmed.

## 2.6 Coverage plan
| Section(s) | Read in full by |
|---|---|
| <sections> | <reviewer A / B / C> |
```

---

## 3. `REVIEWER-NOTE.md` (copied verbatim; only the folder name filled in)

```markdown
# Note for the reviewers of the consolidation <Sfirst>–<Slast>

The book is reviewed as a **frozen copy**, so that every reviewer reads the same version while the working files stay open for other work.

1. **The book is `06-theory/manuscript/review/CONSOLIDATION-<Sfirst>-<Slast>/BOOK-UNDER-REVIEW/` only.** Read its sections, map and register there.
2. **Book search:** `python3 -I <copy>/tmp/tools/book_search.py …` (it searches the copy).
3. **Mechanical checks, run on the copy.** Pass `--root <copy>` from your **first** call of every tool that takes it (the tools' default root is the working book). `corpus_quote_check.py` takes the copy's section files as arguments.
   - `python3 -I tmp/tools/register_home_check.py --root <copy>` (R1);
   - `python3 -I tmp/tools/undefined_terms.py --root <copy> --scope <sections in your commission>` (R2): **judge every candidate it lists for your sections, one by one**; the whole-book list is background. *(Until the tool has `--scope`, run it without and keep the lines whose first use lies in your sections.)*
   - `python3 -I tmp/tools/inbound_citations.py --root <copy> "<claim>"` (R3) for every correction you propose;
   - `python3 -I tmp/tools/corpus_quote_check.py <section files of the copy>` (quote check).
4. **Do not open:** the working chapter files (`06-theory/manuscript/chapter_*`), the working `THEORY-MAP.md`, `CONCEPT-REGISTER.tsv` and `BOOK-OUTLINE.md`, `BASELINE-REVISIONS.md`, old texts in review folders, anything in `00-access/`, or git history of the manuscript. **Do not compare files.**
5. **Corpus sources** are read as usual, from the allowed list only; sealed and hold-out files never.
6. **A reference to a claim that does not exist** (an index row, table row or citation naming a claim the section lacks) is checked against the window inventory and the sources **before** it is called an index error: is content missing, or is the pointer stale? Say which, with the evidence.
7. **At the end of your report, list every file you opened and every search and command you ran.** If you reached any file in item 4, say so. Without the list, the review cannot be shown to rest on the frozen copy.
```

---

## 4. `COMMISSION-<X>.md` (one per reviewer: A and B on different models; C… for planned splits)

```markdown
# Consolidation <Sfirst>–<Slast>: reviewer <X> (commission)

*<date>. Rulebook v1.4 §12. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (consolidation review).*

## Files you may open in this folder: only these
`REVIEWER-NOTE.md` (binding; read first), `SCOPE.md`, and this file. Open no other file in this folder, and nothing in `00-access/`. Other reviewers' reports are not given to you, so that your verdicts are independent.

## The object
The frozen copy `BOOK-UNDER-REVIEW/` only. You may read any part of the copy that the sections in your commission cite.

## Tasks, in this order
1. **<the sections assigned to you in SCOPE §2.6>**, read in full, section by section: each claim against its cited source lines (allowed list only), its label (Src/Int/Ded), its homes, and the checks of REVIEWER-INSTRUCTIONS step 3. Defects of fact, attribution, label, derivation, scope, stale text, contradictions between sections, SEQ order.
2. **Concept verdicts** for the concepts in SCOPE §2.2 homed in your sections, by its rule.
3. **Coverage** against the inventories in SCOPE §2.3, for the windows whose sections you read: each item COVERED, MISSING, DISTORTED or JUSTIFIABLY EXCLUDED; rank the misses.
   *(For sections no window inventory covers, such as a rotated chapter, use this item instead:)* **Coverage:** <chapter> was last reviewed in full at <consolidation>; no window inventory covers it. Instead, check every pointer from your sections to later chapters, and every TODO row in <chapter>'s `TODO.md`, against the copy: still open, resolved elsewhere (where), or stale.
4. **R2** with `--scope` on your sections: every candidate judged.
5. **R3** for each correction you propose; give each inbound line a disposition.

Nothing outside your sections is in scope; note out-of-scope observations as one line each. If you cannot cover something, say so rather than skimming it.

## Rules (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name or any outside author's name.

## Report
- Tables: findings (ID · section · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when); concept verdicts (ID · verdict · reason); coverage (item · status · home); R2 (word · judgement); coverage of sections (read in full / not).
- A summary of at most 15 lines, and one line on process independence.
- End with every file you opened and every command and search you ran (REVIEWER-NOTE item 7).
- Deliver it once, beginning "CONSOLIDATION <Sfirst>–<Slast>: REPORT <X>".
```

---

## 5. `INTEGRATION-COMMISSION.md`

```markdown
# Consolidation <Sfirst>–<Slast>: integration review (commission)

*<date>. Rulebook v1.4 §12. You are the integration reviewer. Reviewers <list> reviewed the frozen copy independently; their reports are in this folder.*

## Files you may open in this folder: only these
`REVIEWER-NOTE.md` (binding; read first), `SCOPE.md`, <REPORT-A.md, REPORT-B.md, …>, and this file. Open no other file in this folder, and nothing in `00-access/`.

## Tasks (verify and integrate; do not start another full review)
1. **Each finding:** check it against the copy and the cited sources; **confirmed / rejected / modified**, with severity. Merge duplicates.
2. **R3, every line:** for each confirmed correction, give **each inbound line** a disposition: stays true / needs the same change / needs a different change (say which) / not affected. Where the tool cannot resolve a target, grep the copy and say so.
3. **References to missing claims** (REVIEWER-NOTE item 6): for each such finding, check whether content is missing or the pointer is stale, before confirming a remedy.
4. **Conflicts between reviewers:** resolve each with a reason, including pairs of corrections that touch the same text.
5. **Defects in the rotated chapters:** list the confirmed ones by reviewer; add any new defect you find while checking.
6. **Coverage:** list any section in scope that no reviewer read in full, and the concepts left without a verdict. Do not fill the gap.
7. **Access deviations** *(when any reviewer disclosed one; otherwise omit this item)*: disclosed by the reviewers; weigh them, do not re-run them: <each deviation, by reviewer, from the reports' file lists and the Professor's access notes>. Check a sample of the findings of any reviewer who read outside the copy on the copy, and say whether any rests on such a read.
8. **Decisions in scope** *(when the scope includes the Principal's decisions; otherwise omit this item)*: the decision record's text for each, quoted: <quotations, with the addendum's date>. Against this text, say for each finding about them what the record decides, what the copy treats as decided beyond it, and what therefore needs a question to the Principal (state it in one sentence, with the options), rather than an edit.

## Rules (the Principal)
<the three lines of §4's "Rules (the Principal)", copied verbatim>

## Report
- Tables: findings (ID(s) · verdict · severity · chapter · evidence · correction); R3 (correction · inbound line · disposition); conflicts (pair · resolution · reason); rotated-chapter defects; coverage gaps.
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened and every command and search you ran.
- Deliver it once, beginning "CONSOLIDATION <Sfirst>–<Slast>: INTEGRATION REPORT".
```

---

## 6. Changes to this template

| Date | Change | Evidence |
|---|---|---|
| 2026-10-29 | First version, from the S0200 briefs (`BRIEF.md`, `SCOPE.md`, `REVIEWER-NOTE.md`, `COMMISSION-C.md`, `INTEGRATION-COMMISSION.md`) with: per-commission file lists and `00-access/` forbidden (D4); Professor-only and test files moved to `00-access/consolidations/` (D4); R2 with `--scope`, every candidate judged (D2); the missing-claim check (D3); a coverage plan before the review | S0200: reviewer A read `README-PROFESSOR.md` in the reviewers' folder; "keel" in R2's unjudged 687 lines; the C7 trace misdiagnosed; Ch. 8 §8.07–§8.26 read in full by no reviewer |
| 2026-10-31 | REVIEWER-NOTE item 3: `--root <copy>` from the first call, and how `corpus_quote_check.py` is run (change 1); commission item 3: an alternative coverage task for sections no window inventory covers (change 3); integration items 7 (access deviations) and 8 (decisions in scope), each used only when it applies (change 4). **Not adopted:** "or any outside author's name" in the rules line (change 2; the Principal: "I do not confirm this as a Principal rule at this time") | the S0253–S0277 briefs' deviations from the fixed text (Governance Engineer's G7 check, 2026-10-31); the Principal's decision of 2026-10-31 (research record addendum of that date). Evidence: reviewer tool runs without `--root` (S0201–S0225 A; S0253–S0277 B); Ch. 0 and Ch. 1 rotated with no window inventory; S0253–S0277 A's working-book reads; the T9-19 scope question D-02 |
| 2026-10-31 | The outside-authors rule: "or any outside author's name" in the rules line (§4, so also §5), and in SCOPE §2.4 "Outside books and articles the sources cite are not opened; their authors are not reproduced." (change 2 of the S0253 briefs, first not adopted, now confirmed) | the Principal's confirmation, research record addendum of 2026-10-31 ("i accept your recommendation") |
