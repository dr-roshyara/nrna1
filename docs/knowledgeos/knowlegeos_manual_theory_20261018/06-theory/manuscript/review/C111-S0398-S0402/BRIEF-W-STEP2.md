# C111, the window commission: step 2 (review)

*2026-11-03. Window reviewer W (Opus), after your step-1 inventory was saved with its sha256 (`SHA256-STEP1.txt`). Rulebook v1.4; `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). Re-read your saved inventory first (`STEP1-INVENTORY.md` in this folder).*

## The object
- `06-theory/manuscript/chapter_08/08.105-three-book-extractions-as-review-lenses-and-a-restart-that-separates-participation-from-identity.md` (new, draft) and its row in `chapter_08/08.00-index.md`.
- `STEP8-AND-CHECKS.md` in this folder (dependencies, the line checks, the checks, the proposed R3).
- Changed theory since the last window review: the C110 settlement (`579bb6aa3`; §8.104 v2; R3 on §8.103 C3 and §8.103.4, and on the THEORY-MAP C109 line).

## Questions
1. The REVIEWER-INSTRUCTIONS checks for a transition review: each claim against the cited source and book lines; labels; homes; R2; the quote check by hand; R3 for any correction.
2. Completeness against your inventory: what did §8.105 leave out, and is any omission a loss of theory?
3. C1: the logic-manual lens against §9.19.8, T7 and RESEARCH-PURPOSE's two tests; U, doubt, contradiction and error against §8.31 C2, §9.13.4 R2, §9.15 G6 and §8.88 C1; absence against §8.88 C2 and §8.102 C2; the homonym of S0398's T1–T10 with the book's T7.
4. C2: factive knowledge against §8.89 C4 and §8.95 C4 (the homonym); non-iteration and self-certification against §8.13 C4 and §8.100 C4; defeasibility against §9.13.2; structural unknowability against §8.31 C2; assertion levels against §8.92 C4. C3: the restart and participation against §8.11 C1–C2, §8.6, F32, F35, §8.75 C5 and §8.31 C4; the selection decision against §8.13 C4; the in-place amendment against K1.
5. C4: the three groundings against §9.18 L111 and §8.95 C4; inference against §8.88 C2 (iii); artificial coherence against §8.31 C4, §8.33 C3 and §8.104 C1; historical against current knowledge against §9.13.2 and §9.20 Th4; the transitions against §8.102 C2.
6. Lineage and scope: the three extractions as one adviser's readings of books not in the corpus; S0400–S0401 as governance records in §8.100–§8.101's line; the scope (within D, the estate, or the whole book) of every claim; coverage.
7. Anything presented as decided that is the Principal's.

## Access (binding)
- **Allowed:** this brief, your saved inventory, `STEP8-AND-CHECKS.md`; `03-methodology/REVIEWER-INSTRUCTIONS.md`; `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`; the working book (the top-level files in `06-theory/manuscript/chapter_*/`, never a `review/` subfolder; `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/RESEARCH-PURPOSE.md`); W's tools (`tmp/tools/`, run on a scratch copy only); the five window sources; and, for checking only, S0397 (on the allowed list; check each path there first).
- **No git history:** do not run `git log`, `git show`, `git diff`, `git blame` or any other command that reads earlier versions of a file or lists a commit's files. Read each allowed file as it is on disk.
- **No globs (the Principal, 2026-11-03):** every search, listing, read or copy names its files one by one, by full path; no wildcard (`*`, `?`, `[...]`), no recursive search (`grep -r`, `find`), and no directory listing over corpus or book folders.
- **The allowed list (the Principal, 2026-11-03)** is `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Locate sources only through it.
- **Working instructions in force (the Principal, 2026-11-03):** `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md` is the index of the working instructions in force under Rulebook v1.4; it may be read. Where it differs from the Principal's decision record, the record wins.
- **A file you may not open, you may not search either.** Restrict every search to the files above; do not search `06-theory/manuscript/` recursively (it holds review folders).
- **Copy list (the Principal, 2026-11-02):** to run a tool on a scratch copy, copy only the files in `COPY-LIST-STEP2.txt` in this folder, each by its own path (for example `while read f; do mkdir -p "$SCR/$(dirname "$f")"; cp "$f" "$SCR/$f"; done < docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C111-S0398-S0402/COPY-LIST-STEP2.txt`, run from the repository root). No directory copy: no `cp -r`, `rsync`, or glob of `06-theory/` or `manuscript/`. Copy nothing else; if a tool needs a file not on the list, do not run it, and record that. Run `undefined_terms.py` with `--no-corpus` (corpus files are not on the list).
- **Never:** any other review folder; `00-access/`; any other file under `03-methodology/`; S0155, S0156, S0157, S0161, S0170, S0174; `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, `docs/knowledgeos/chronological-read/*`. If a file is refused or not allowed, record it and continue.
- Scratch files: `c111-w-*.txt`, in your scratch directory. Edit nothing. No corpus text to any outside service. Do not reproduce any private person's name; the outside-authors rule (persons by role; eponymous terms and bibliographic citations unchanged; paths unchanged).

## Report
Begin "C111 REVIEW REPORT". Findings table (W-nn · severity · R/N · claim · evidence · correction · R3 lines), where R/N marks whether the finding concerns a restatement item (R: the source restates what the book already settled, and the finding is about how the draft maps it) or new theory (N) (the Principal, 2026-11-03); give the R and N counts after the table; a verdict per claim (C1–C5); coverage; at most 10 lines of summary. End with every file opened and every command and search run.
