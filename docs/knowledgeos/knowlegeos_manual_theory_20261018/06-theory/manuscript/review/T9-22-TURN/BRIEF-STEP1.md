# Theory turn 1 (T9-22: A and the output forms): commission, step 1 (blind)

*2026-11-11. Three reviewers, independently: **A** (kos-lane-reviewer, Opus), **B** (kos-lane-reviewer, Sonnet) and **C** (kos-theory-reviewer), as the Principal dispatched ("three reviewers (two kos-lane-reviewer on different models, one kos-theory-reviewer; none on an outside service)").*
- *Mode: **topic review** (`03-methodology/REVIEWER-INSTRUCTIONS.md`), for a theory turn that reads no new corpus source.*
- *Working instructions in force: `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`, the index of the working instructions under Rulebook v1.4. Where it differs from the Principal's decision record, the record wins.*
- *W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/` (repository-relative). Use absolute paths under the repository root.*

## Step 1 only: your own answer, before you see the Professor's

There are no new sources, so the blind step is **your own answer to the turn's question**, written from the book sections listed below, before you open anything the Professor wrote for this turn. Hand it back and stop. You will receive step 2 after your step-1 answer has been saved with its sha256.

**The commission's turn 1 (verbatim, from `03-methodology/COMMISSIONS-CHAIN-R2-R1-THEORY-TURNS-20261103.md`; you do not need to open that file):**

> **Objective.** Fix what the theory can fix about the kernel's interface, **by derivation, not by choice**: the mechanism granularity and batching (G of A = (B, G)), and the output forms of Q1's act list, Q2 and Q3.
>
> **Research question.** Does Req's kind structure (U6's per-kind cut) and the decided theory determine the mechanism granularity, batching, and the three output forms; and where it does not, what exactly is left to decide?
>
> **Deliverable.** For G, batching and each output form: **derived** (with the derivation), **narrowed** (to named options), or **a decision** (one question for the Principal). Then: §9.20.5's minimality restated for the derived or decided forms; T9-22 closed or reduced to a decision; the consequences for T9-03 and T9-04 stated, not settled.
>
> **Limits.** Fixing G by choice breaches the Req/A approval's condition 4 (no selection criterion adopted); only a derivation, or a question to the Principal, is allowed. No primitive fixed; no selection criterion among minimal candidates.

**T9-22 as it stands (Ch. 9 TODO row, verbatim; do not open TODO.md at this step):**

```
| **T9-22** | **The output forms of Q1–Q3 that decide minimality** | open question | §9.20.9 (i); §9.20.6 | 𝒮's minimality depends on which outputs of Q1–Q3 count (three forms) | T9-02 turn | Open *(row added at the consolidation S0353–S0377, D-12)* | a decision on the output form, or a result per form | minimality stated per form, or the form decided |
```

**Decided premises you may use:**
- U6 (a): O1 is one requirement per act kind.
- U5 (a): EF.
- U3 (b): G and batching kept unfixed. *A derivation may fix them; a choice may not.*
- U2 (a): Q4's grade is g1's class, in the kernel.
- C1 = constitute.
- D and W's kinds.
- The kernel type §9.1 C6.
- Entry identity, §9.16 L93.
- The T9-02 deliverable ruling.

**Not to be used as decided:** F13, F37, F6, T9-14, T9-21, and G7's F13 part.

## What to write (about 2 pages, tables preferred)

1. **For each of five objects** (G; batching; Q1's "acts the answer rests on"; Q2's output and scope; Q3's "found (with the grant)"), your class, **derived**, **narrowed** or **a decision**, with:
   - the derivation, with lines;
   - or the named options and what separates them;
   - or the one question.
2. **Minimality** (§9.20.5) restated for your forms. Name a pair of records that your forms separate or identify.
3. **T9-22:** closed, or reduced to which decision.
4. **T9-03 and T9-04:** the consequences, stated, not settled.
5. **Disproof:** what would refute each of your answers.
6. Your own judgments marked [I]; never state more than the lines support.

## Files you may open at this step (only these)

- `03-methodology/REVIEWER-INSTRUCTIONS.md`; `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`; this brief.
- Book sections, top-level `.md` files only:
  - `06-theory/manuscript/chapter_09/` files **09.01 to 09.20** (`09.01-*` … `09.20-*`);
  - `chapter_01/01.16-*`;
  - any other `chapter_0[0-8]/` or `chapter_10/` section a cited line points to, never a `review/` folder;
  - `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/RESEARCH-PURPOSE.md`.
- W's tools in `tmp/tools/`, run on a scratch copy only.
- **Not at this step:** any `chapter_09/09.21-*` file; `chapter_09/09.00-index.md`, `chapter_09/09.99-change-log.md`, `chapter_09/TODO.md`; `06-theory/manuscript/BOOK-OUTLINE.md`; any other file in this review folder.
- **Never:**
  - `00-access/`; `02-FILES.jsonl`;
  - any other review folder (restrict every search to the files above; do not search `06-theory/manuscript/` recursively);
  - any other file under `03-methodology/`; `review-architecture/`, `review-implementation/`;
  - S0155, S0156, S0157, S0161, S0170, S0174, S0233, S0249, S2444; `docs/knowledge_tranfer/*`;
  - sealed, hold-out or quarantine files; `_batch_input*`; `docs/knowledgeos/chronological-read/*`; `.env*`;
  - any application code.
- No corpus source is needed. If you consult one, it must be on the allowed list, `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`, and you say so.
- If a file is refused or not allowed, record it and continue; do not inspect it another way.

## Files you may copy to your scratch directory (only these)

- The book files listed above as allowed at this step, **each named individually**. No directory copy: no `cp -r`, `rsync` or glob of `06-theory/` or `manuscript/`.
- `tmp/tools/*.py`, `tmp/tools/*.tsv` and `tmp/tools/QUOTE-BASELINE.txt`, each by its own path.

Copy nothing else. If a tool needs a file not on this list, do not run it; record that.

## Rules (the Principal)

- Keep verified facts, interpretations and recommendations apart; never state more than the evidence supports.
- **No git history:** do not run `git log`, `git show`, `git diff`, `git blame` or any other command that reads earlier versions of a file or lists a commit's files.
- **No globs:** every search, listing, read or copy names its files one by one by full path. No wildcard, no recursive search, no directory listing over corpus or book folders.
- Edit nothing in the repository. Your scratch directory, outside the repository, is named in your dispatch; use only it, and run scripts with `python3 -I`. No corpus or book text to any outside service.
- **The outside-authors rule:** persons named as people in prose are not reproduced (refer to them by role). Eponymous technical terms and bibliographic citations of external works stay unchanged.

## Hand-back

Begin with "T9-22 STEP 1 ANSWER <A, B or C>". End with every file you opened, every file you copied, and every command and search you ran.
