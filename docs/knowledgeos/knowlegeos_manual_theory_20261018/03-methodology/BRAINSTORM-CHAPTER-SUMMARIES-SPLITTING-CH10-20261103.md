# Brainstorm: chapter summaries, splitting long chapters, and Chapter 10

| Field | Value |
|---|---|
| Status | **BRAINSTORM — nothing decided.** Prepared on the Principal's request of 2026-11-03 ("make a brainstorming on this topics: can we make summary of each chapter keep updating it till end. if the chapters are long we should split them . Professor should also suggest how to implement and update the chapter 10 which is responsible for implementation") |
| Prepared by | the Governance Engineer (session `57ddce41…`), 2026-11-03, from repository records only. It writes no theory and changes no rule |
| Decisions it leads to | three, each the Principal's (§5), taken one at a time |

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
| **8** | **97** | **12,940** | one section per reading window, in reading order |
| **9** | **23** | **4,709** | theory turns (kernel type, Req, evaluator, state space…) |
| 10 | 8 | 1,328 | **the strategic design (derived)**: subdomains, bounded contexts, ownership of invariants, context map, published language |

- **[V] Chapters are already split into section files**: the largest single file is 554 lines (§1.14). The size problem is the number of sections in Ch. 8 and Ch. 9, not long files.
- **[V] No chapter has a summary.** Each chapter has an index file (`NN.00-index.md`: a table of sections with a status line each); `BOOK-OUTLINE.md` carries status marks per deliverable; `TARGET-TOC.md` (adopted as a reading order, DB-7) maps sections onto Parts I–VI.
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

**Recommended [P]: A**, with rules 1–5, starting at the S0353–S0377 consolidation.

## 3. Topic 2 — splitting long chapters

**[I] The real candidates are Ch. 8 (97 sections, 52 % of the book) and Ch. 9 (23 sections).** Other chapters are small.

**Constraint [V]:** DB-7 forbids renumbering. Moving files would also break tool paths (`book_search.py`, R2, R3) and review records that name paths.

**Options:**

| | How | Pro | Con |
|---|---|---|---|
| A | **Split logically, not physically:** keep every file and number; give Ch. 8 one summary per consolidation block (S0001–S0035 … S0353–S0377) and Ch. 9 one per group of turns; the chapter summary points to the block summaries | no renumbering, no broken paths; matches how the work is already reviewed (in consolidations) | Ch. 8 stays one long folder |
| B | Physical split into sub-folders (`chapter_08/block-S0353-S0377/…`), numbers unchanged | easier to browse | path changes across tools and every review record; needs tool changes |
| C | Renumber into new chapters per `TARGET-TOC.md` Parts | cleanest structure | **against DB-7**; every citation changes; high risk, a separate Principal decision |
| D | Move Ch. 8's window findings into the theory chapters they serve, leaving Ch. 8 as a log | the theory chapters become the book | large rewrite; Rulebook §4's two-step rewrite rule would apply to each move |

**Recommended [P]: A now.** It gives most of the benefit with no rule or tool change. **D** is the real long-term question (Ch. 8 holds evidence that belongs to Parts II–IV) and could be one item for a later whole-book review, not now.

## 4. Topic 3 — Chapter 10, and what "implementation" can mean now

**[V] Where Ch. 10 stands:** five settled strategic-design sections (SD1–SD5); open rows T10-15 and T10-17–T10-21 (owner tests, glossaries per context, the applications' own rules, the classification of g1's rule, names in the published language), most "after the G1 turn". Since the last Ch. 10 revision, Ch. 9 has moved: U1–U6 (2026-11-01), the T9-02 state space (§9.20, 2026-11-02; record = state, item attributes derived), U2 (g1's class in the kernel).

**[I] What the Professor can usefully propose without crossing the boundary:**
1. **Impact of Ch. 9 on Ch. 10:** which Ch. 10 rows the recent results change (e.g. does §9.20's "record is the state" affect §10.3's owners or §10.5's published language?), stated as consequences, not decisions.
2. **A plan for closing T10-15, T10-17–T10-21:** what each needs (the G1 turn? a theory turn? evidence?), in which order, and what the Verification Auditor's rerun needs from Ch. 10.
3. **A readiness statement for tactical design / stage 1:** what must be true in Ch. 10 before the Principal could authorise the stage-1 package — **a list of conditions, not the package**.

**[V] What it cannot do under the decisions in force:** prepare the stage-1 package, design technical interfaces, or start implementation (2026-10-31 item 5; the standing "no architecture activation, no implementation"). Writing "how to implement" would also put engineering content into a theory book whose Ch. 10 says it "fixes no technical interface".

**Options:**

| | Ask the Professor for | Effect |
|---|---|---|
| A | **A proposal (1)–(3) above**: impact, closure plan, readiness conditions; a document for the Principal, no change to Ch. 10 until approved | keeps theory and implementation apart; gives the Principal the path to stage 1 |
| B | A Ch. 10 revision now (apply the Ch. 9 consequences) | progress on the text; but it is a strategic-design turn under its own mandate, with review, and it pauses reading |
| C | Implementation guidance in Ch. 10 | **not authorised**; mixes tracks; needs the stage-1 decision first |

**Recommended [P]: A**, as a written proposal from the Professor at the S0353–S0377 consolidation (or after it), not interrupting reading.

## 5. The three decisions, one at a time (copy-ready)

1. **Chapter summaries:**
   > "Each chapter gets a summary file (NN.SUMMARY.md: establishes · failed or restated · open · changed), restating settled sections only with section numbers and labels, no new claims; written by the Professor, updated at each consolidation and after each theory turn, and checked against the sections by the consolidation reviewers. Start at the S0353–S0377 consolidation."
2. **Splitting:**
   > "Split Ch. 8 and Ch. 9 logically, not physically: no renumbering and no file moves (DB-7); Ch. 8 gets one summary per consolidation block and Ch. 9 one per group of turns, linked from the chapter summary."
3. **Chapter 10:**
   > "At the S0353–S0377 consolidation, the Professor writes a proposal for Chapter 10: (1) which Ch. 10 rows the Ch. 9 results since its last revision change, as consequences; (2) a plan for closing T10-15 and T10-17–T10-21; (3) the conditions Ch. 10 must meet before a stage-1 package could be authorised. No change to Ch. 10 and no implementation work until I approve."

## 6. Costs and risks

- **[I] Time:** a first summary per chapter is perhaps one consolidation's extra work; later updates are small. Ch. 8's block summaries for the 14 existing consolidations are the largest one-off item; they could be written back to front, newest block first.
- **[I] Drift:** the main risk of any summary; rules 1 and 2 of §2 are the guard.
- **[I] Review load:** the consolidation brief grows by one check per changed chapter.
- **[U]** Whether the Professor sees a better shape; the decisions above can ask for its view first.
