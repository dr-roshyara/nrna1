# C110, the window commission: step 2 (review)

*2026-11-03. Window reviewer W (Opus), after your step-1 inventory was saved with its sha256 (`SHA256-STEP1.txt`). Rulebook v1.4; `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). Re-read your saved inventory first (`STEP1-INVENTORY.md` in this folder).*

## The object
- `06-theory/manuscript/chapter_08/08.104-one-commission-and-three-replies-a-convergence-the-prompt-set-and-hidden-reasoners-with-no-place-in-d.md` (new, draft) and its row in `chapter_08/08.00-index.md`.
- `STEP8-AND-CHECKS.md` in this folder (dependencies, the line checks, the checks, the proposed R3).
- Changed theory since the last window review: the C109 settlement (`b12523c7b`; §8.103 v2; R3 notes in 08.82, 08.98, 08.100 C4 and 08.88's lineage where applied).

## Questions
1. The REVIEWER-INSTRUCTIONS checks for a transition review: each claim against the cited source and book lines; labels; homes; R2; the quote check by hand; R3 for any correction.
2. Completeness against your inventory: what did §8.104 leave out, and is any omission a loss of theory?
3. C1: the convergence read as the prompt's agenda against §8.33 C3, §8.31 C4 and §8.103 C3; the claim that R3 answers a model the commission does not record (check by search of S0397); the identity of S0397 L3196–3835 with S0396 L1–640.
4. C2: each falsification question's mapping within D: the boundary against §9.17 and §8.89 C4; sufficiency against §8.95 C4 (c) and §9.13.2; discrete states against §9.13 L18, §8.88 C1 and §8.102 C3; identity against §9.16 L93, §8.93 C2, §8.95 C3, §8.102 C5 and T9-14; intrinsic properties; the contract and its authority against §8.95 C4 (a), §10.3 and CON-0132; the reading of V3's quotations and of the matrix's DeepSeek column.
5. C3: the five hidden reasoners against §8.94 C6, §8.95 C3–C4, §9.13, §9.16 L93 and §9.18.2; whether each is a kernel judgement within D.
6. Lineage and scope: the seven parts (P, R1, V1, R2, V2, R3, V3), the one adviser voice, the scope (within D, the estate, or the whole book) of every claim; what the window leaves unused (coverage).
7. Anything presented as decided that is the Principal's.

## Access (binding)
- **Allowed:** this brief, your saved inventory, `STEP8-AND-CHECKS.md`; `03-methodology/REVIEWER-INSTRUCTIONS.md`; the working book (the top-level files in `06-theory/manuscript/chapter_*/`, never a `review/` subfolder; `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/RESEARCH-PURPOSE.md`); W's tools (`tmp/tools/`, run on a scratch copy only); the window source S0397; and, for checking only, S0396 (on the allowed list; check each path there first).
- **No git history:** do not run `git log`, `git show`, `git diff`, `git blame` or any other command that reads earlier versions of a file or lists a commit's files. Read each allowed file as it is on disk.
- **No globs (the Principal, 2026-11-03):** every search, listing, read or copy names its files one by one, by full path; no wildcard (`*`, `?`, `[...]`), no recursive search (`grep -r`, `find`), and no directory listing over corpus or book folders.
- **The allowed list (the Principal, 2026-11-03)** is `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Locate sources only through it.
- **A file you may not open, you may not search either.** Restrict every search to the files above; do not search `06-theory/manuscript/` recursively (it holds review folders).
- **Copy list (the Principal, 2026-11-02):** to run a tool on a scratch copy, copy only the files in `COPY-LIST-STEP2.txt` in this folder, each by its own path (for example `while read f; do mkdir -p "$SCR/$(dirname "$f")"; cp "$f" "$SCR/$f"; done < docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C110-S0397/COPY-LIST-STEP2.txt`, run from the repository root). No directory copy: no `cp -r`, `rsync`, or glob of `06-theory/` or `manuscript/`. Copy nothing else; if a tool needs a file not on the list, do not run it, and record that. Run `undefined_terms.py` with `--no-corpus` (corpus files are not on the list).
- **Never:** any other review folder; `00-access/`; any other file under `03-methodology/`; S0155, S0156, S0157, S0161, S0170, S0174; `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, `docs/knowledgeos/chronological-read/*`. If a file is refused or not allowed, record it and continue.
- Scratch files: `c110-w-*.txt`, in your scratch directory. Edit nothing. No corpus text to any outside service. Do not reproduce any private person's name; the outside-authors rule (persons by role; eponymous terms and bibliographic citations unchanged; paths unchanged).

## Report
Begin "C110 REVIEW REPORT". Findings table (W-nn · severity · R/N · claim · evidence · correction · R3 lines), where R/N marks whether the finding concerns a restatement item (R: the source restates what the book already settled, and the finding is about how the draft maps it) or new theory (N) (the Principal, 2026-11-03); give the R and N counts after the table; a verdict per claim (C1–C5); coverage; at most 10 lines of summary. End with every file opened and every command and search run.
