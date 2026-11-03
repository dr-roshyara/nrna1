# Brainstorm: chapter summaries, splitting long chapters, and Chapter 10

| Field | Value |
|---|---|
| Status | **BRAINSTORM — nothing decided.** Prepared on the Principal's request of 2026-11-03 ("make a brainstorming on this topics: can we make summary of each chapter keep updating it till end. if the chapters are long we should split them . Professor should also suggest how to implement and update the chapter 10 which is responsible for implementation") |
| Prepared by | the Governance Engineer (session `57ddce41…`), 2026-11-03, from repository records only. It writes no theory and changes no rule |
| Decisions it leads to | three, each the Principal's (§5), taken one at a time |
| Revision | **2, 2026-11-03:** corrected after an independent review relayed by the Principal (counts, consolidation blocks, summary placement, review scope, cadence, scheduling, the Architect boundary); see §7 |

**Labels.** **[V]** verified from the records · **[I]** interpretation · **[P]** proposal · **[U]** unknown.

## 1. The facts

**[V] Book size today (section files, `06-theory/manuscript/chapter_*`):**

| Chapter | Files | Lines | What it is |
|---|---|---|---|
| 0 | 11 | 472 | the problem and the starting model |
| 1 | 20 | 2,048 | foundations (identity, authority, evidence…) |
| 2 | 15 | 925 | |
| 3 | 13 | 1,132 | |
| 4 | 6 | 255 | change and lifecycle (thin) |
| 5 | 10 | 858 | |
| 6 | 10 | 792 | |
| 7 | — | — | retired (DB-6, 2026-10-20) |
| **8** | **94** sections (+ index, change log, TODO) | **12,940** | one section per reading window, in reading order |
| **9** | **20** sections (+ index, change log, TODO) | **4,709** | theory turns (kernel type, Req, evaluator, state space…) |
| 10 | 8 | 1,328 | **the strategic design (derived)**: subdomains, bounded contexts, ownership of invariants, context map, published language |

- **[V] Chapters are already split into section files**: the largest single file is 554 lines (§1.14). The size problem is the number of sections in Ch. 8 and Ch. 9, not long files.
- **[V] No chapter has a summary file.** Ch. 0 has one synthesis section, `00.08-look-back-review-after-sections-0.md`, a form worth reusing. The book already says a summary is not evidence and can misstate its source (§8.26 C2).
- **[V] Tool consequence:** `tmp/tools/book_search.py` indexes every `chapter_*/*.md` except `TODO.md` (`sections()`, L51–56). A summary file inside a chapter folder would be searched as if it were book text. `undefined_terms.py` filters on `\d\d.\d\d` names and is unaffected.
- **[V] Former:** Each chapter has an index file (`NN.00-index.md`: a table of sections with a status line each); `BOOK-OUTLINE.md` carries status marks per deliverable; `TARGET-TOC.md` (adopted as a reading order, DB-7) maps sections onto Parts I–VI.
- **[V] DB-7 (Principal, 2026-10-20):** "`TARGET-TOC.md` is adopted **as a reading order with stable section numbers**; no renumbering." Every citation, R3 note, register row and review refers to section numbers.
- **[V] Chapter 10 is not the implementation chapter.** Its index: "The strategic design (derived) … it fixes no technical interface". Implementation is a **separate track** (`docs/knowledgeos/IMPLEMENTATION-TRACK-STATE.md`); the stage-1 package is not to be prepared until the Principal authorises it (2026-10-31, item 5); the Implementation Engineer is inactive until the stage-1 decision; the Verification Auditor's next rerun waits for the Ch. 10 revision (T10-17–T10-21, open "after the G1 turn").

## 2. Topic 1 — a living summary for each chapter

**Why [I]:** the book is 25,000 lines. Neither the Principal nor a new reviewer can see what a chapter establishes without reading it. The index files list sections, not results. My assessment of 2026-11-02 needed a full pass over the outline to say where the deliverables stand.

**Options:**

| | Form | Pro | Con |
|---|---|---|---|
| A | **One summary file per chapter** (`NN.SUMMARY.md`): what the chapter establishes, what failed, what is open, what changed since the last update; every line cites its section(s) | readable in minutes; a reviewer can check it against the sections | a second text that can drift from the sections |
| B | A summary block at the top of each index file | no new file | the index already carries status lines; mixes two jobs |
| C | One book-level summary only (extend `BOOK-OUTLINE.md`) | one place | too coarse for Ch. 8 and Ch. 9 |

**Rules a summary would need [P]:**
1. **No new claims.** A summary only restates what a settled section says, with its section number and the section's own label (Src · Ded · Int · Hyp). If summary and section disagree, the section wins and the summary is a defect.
2. **Written by the Professor** (it is theory content), **checked at each consolidation**: the consolidation reviewers already check coverage; add "does each chapter summary match its settled sections?" to the brief.
3. **Update cadence:** at each consolidation (every 25 sources) and after each theory turn, for the chapters that changed; not per window (too frequent, and Ch. 8 windows rarely change the theory now).
4. **Fixed shape, short:** for example at most one page per chapter — *Establishes · Failed or restated · Open (with TODO/fork IDs) · Changed since last update*.
5. Not a source of evidence: reviewers cite sections, never the summary.

**Placement (revision 2) [P]: A′ — summaries outside the chapter folders**, in `06-theory/manuscript/SUMMARIES/NN.SUMMARY.md`. No tool, frozen-copy, line-count or coverage change; rule 5 is enforced by location. (The alternative, an exclusion line in `book_search.py`, is a tool change.)

**Status (revision 2) [P]:** a summary is **metadata, not book text**: not in the frozen consolidation copy unless a brief names it, not in line totals, never cited; a defect is repaired in place.

**Rule 2 corrected [P]:** consolidation scope is fixed (the block's sections, changed passages, rotated chapters; each consolidation's `SCOPE.md`). Summaries are checked **only for chapters in scope** of that consolidation; the others are recorded as **unchecked**.

**Recommended [P]: A′**, with rules 1–5 as corrected, **starting at the S0378–S0402 consolidation** (§6).

## 3. Topic 2 — splitting long chapters

**[I] The real candidates are Ch. 8 (97 sections, 52 % of the book) and Ch. 9 (23 sections).** Other chapters are small.

**Constraint [V]:** DB-7 forbids renumbering. Moving files would also break tool paths (`book_search.py`, R2, R3) and review records that name paths.

**Options:**

| | How | Pro | Con |
|---|---|---|---|
| A | **Split logically, not physically:** keep every file and number; give Ch. 8 one summary per block: **S0001–S0035** (covered by batch checkpoints C1–C6, not a consolidation; a block to be defined as such), then each consolidation (S0036–S0065 … S0353–S0377); Ch. 9 **on the same cadence** (the turns settled within each block), not an undefined "group of turns"; the chapter summary points to the block summaries | no renumbering, no broken paths; matches how the work is already reviewed (in consolidations) | Ch. 8 stays one long folder |
| B | Physical split into sub-folders (`chapter_08/block-S0353-S0377/…`), numbers unchanged | easier to browse | path changes across tools and every review record; needs tool changes |
| C | Renumber into new chapters per `TARGET-TOC.md` Parts | cleanest structure | **against DB-7**; every citation changes; high risk, a separate Principal decision |
| D | Move Ch. 8's window findings into the theory chapters they serve, leaving Ch. 8 as a log | the theory chapters become the book | large rewrite; Rulebook §4's two-step rewrite rule would apply to each move |

**Recommended [P]: A now.** It gives most of the benefit with no rule or tool change. **D** is the real long-term question (Ch. 8 holds evidence that belongs to Parts II–IV) and could be one item for a later whole-book review, not now.

## 4. Topic 3 — Chapter 10, and what "implementation" can mean now

**[V] Where Ch. 10 stands:** five settled strategic-design sections (SD1–SD5); open rows T10-15 and T10-17–T10-21 (owner tests, glossaries per context, the applications' own rules, the classification of g1's rule, names in the published language), most "after the G1 turn". Since the last Ch. 10 revision, Ch. 9 has moved: U1–U6 (2026-11-01), the T9-02 state space (§9.20, 2026-11-02; record = state, item attributes derived), U2 (g1's class in the kernel).

**[I] What the Professor can usefully propose without crossing the boundary:**
1. **Impact of Ch. 9 on Ch. 10:** which Ch. 10 rows the recent results change (e.g. does §9.20's "record is the state" affect §10.3's owners or §10.5's published language?), stated as consequences, not decisions.
2. **A plan for closing T10-15, T10-17–T10-21:** what each needs (the G1 turn? a theory turn? evidence?), in which order, and what the Verification Auditor's rerun needs from Ch. 10.
3. **A readiness statement for tactical design / stage 1:** what must be true in Ch. 10 before the Principal could authorise the stage-1 package — **a list of conditions, not the package**. **Boundary (revision 2):** this overlaps the Lead DDD Architect's §5a deliverable (mandate rev. 4, I-007, waiting on the stage-1 package). The Professor lists only conditions **on Ch. 10's theory content** (rows settled, consequences applied); readiness for tactical design itself stays the Architect's §5a.

**[V] What it cannot do under the decisions in force:** prepare the stage-1 package, design technical interfaces, or start implementation (2026-10-31 item 5; the standing "no architecture activation, no implementation"). Writing "how to implement" would also put engineering content into a theory book whose Ch. 10 says it "fixes no technical interface".

**Options:**

| | Ask the Professor for | Effect |
|---|---|---|
| A | **A proposal (1)–(3) above**: impact, closure plan, readiness conditions; a document for the Principal, no change to Ch. 10 until approved | keeps theory and implementation apart; gives the Principal the path to stage 1 |
| B | A Ch. 10 revision now (apply the Ch. 9 consequences) | progress on the text; but it is a strategic-design turn under its own mandate, with review, and it pauses reading |
| C | Implementation guidance in Ch. 10 | **not authorised**; mixes tracks; needs the stage-1 decision first |

**Recommended [P]: A**, as a written proposal from the Professor at the S0353–S0377 consolidation, not interrupting reading.

## 5. The three decisions, one at a time (copy-ready, revision 2)

1. **Chapter summaries:**
   > "Each live chapter (0–6, 8–10) gets a summary file, kept outside the chapter folders (06-theory/manuscript/SUMMARIES/NN.SUMMARY.md): establishes · failed or restated · open · changed, restating settled sections only, with section numbers and their own labels, and no new claims; if summary and section disagree, the section wins. A summary is metadata, not book text, and is never cited. Written by the Professor; updated at each consolidation and after each theory turn; checked against the sections only for chapters in scope of that consolidation, the rest recorded as unchecked. Start at the S0378–S0402 consolidation."
2. **Splitting:**
   > "Split Ch. 8 and Ch. 9 logically, not physically: no renumbering and no file moves (DB-7). Ch. 8 gets one block summary for S0001–S0035 and one per consolidation thereafter; Ch. 9 gets one per the same blocks (the turns settled within each); all in SUMMARIES/, linked from the chapter summary."
3. **Chapter 10:**
   > "At the S0353–S0377 consolidation, the Professor writes a proposal for Chapter 10: (1) which Ch. 10 rows the Ch. 9 results since its last revision change, as consequences; (2) a plan for closing T10-15 and T10-17–T10-21; (3) the conditions on Ch. 10's theory content before a stage-1 package could be authorised, leaving tactical-design readiness to the Lead DDD Architect's §5a. No change to Ch. 10 and no implementation work until I approve."

## 6. Costs and risks

- **[I] Time:** a first summary per chapter is perhaps one consolidation's extra work; later updates are small. Ch. 8's block summaries (S0001–S0035 plus the 12 consolidations to S0352, plus S0353–S0377) are the largest one-off item; they could be written back to front, newest block first.
- **[P] Staggered (revision 2):** the S0353–S0377 consolidation already carries the per-window findings-and-misses report (Principal, 2026-11-03); it takes only the Ch. 10 proposal. Summaries start at S0378–S0402.
- **[I] Drift:** the main risk of any summary; rules 1 and 2 of §2 are the guard.
- **[I] Review load:** the consolidation brief grows by one check per changed chapter.
- **[U]** Whether the Professor sees a better shape; the decisions above can ask for its view first.

## 7. Revision 2 (2026-11-03): corrections from the independent review

Accepted, each checked by the Governance Engineer against the files: section counts were file counts (Ch. 8: 94 sections, Ch. 9: 20; the review's 96 and 22 also counted the change log and TODO); "52 %" → 50.8 % (12,940 of 25,471 lines); 12 consolidations exist, not 14; S0001–S0035 had batch checkpoints C1–C6, not a consolidation; `book_search.py` would index summaries in chapter folders (placement A′); review scope per consolidation; a summary's status (metadata); Ch. 9's cadence; staggered start; the Architect boundary. The closing line of the GE's message ("the window-grouping line has to reach the Professor before C101 starts") was stale: §8.94 (C101, S0370 read 2026-11-03T01:38:22Z) cites the ruling.
