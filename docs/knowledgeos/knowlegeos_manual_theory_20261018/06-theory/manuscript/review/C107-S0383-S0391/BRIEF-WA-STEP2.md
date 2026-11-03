# C107, the window commission A: step 2 (review)

*2026-11-03. Window reviewer WA (Opus), after your step-1 inventory was saved with its sha256 (`SHA256-STEP1-A.txt`). Rulebook v1.4; `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). Re-read your saved inventory first (`STEP1-INVENTORY-A.md` in this folder).*

## The object
- `06-theory/manuscript/chapter_08/08.101-lenses-declined-as-architecture-a-verifier-that-read-first-an-appointment-engine-bypassed-and-a-write-path-that-fails-under-a-live-owner.md` (new, draft) and its row in `chapter_08/08.00-index.md`.
- `STEP8-AND-CHECKS.md` in this folder (dependencies, the line checks, the checks, the proposed R3).
- Changed theory since the last window review: the C106 settlement (`16010b115`; §8.100 v2; R3 on §8.82 C2, §8.98 C4, C6).

## Questions
1. The REVIEWER-INSTRUCTIONS checks for a transition review: each claim against the cited source and book lines; labels; homes; R2; the quote check by hand; R3 for any correction.
2. Completeness against your inventory: what did §8.101 leave out, and is any omission a loss of theory?
3. C1–C2 (commission A): lenses as instruments against §8.93 C5, §8.33 C3, §8.91 C5; the Turing tension (S0383 L898 against S0388 L208, S0389 L289); identity in relational structure against §9.16 L93, §8.93 C2, T9-14; failure modes against K1 and §8.29 C5.
4. C3–C4 (commission A): the PASS made unqualified against CON-0127 and §8.100 C4; the absence claim against CON-0120 and §8.29 C2; the disposition quoted (S0385 L220) and entailed (S0386 L52) against §8.27 C1; §8.73 C1, §8.98 C3; §8.43 C6, §8.50 C3.
5. C5–C6 (commission B): the bypassed engine and its falsified guard premise against §8.29 C5, K1, §8.86 C4; the FAIL against §8.100 C5, §8.15 C1 (CON-0215), §9.13 L46–56, §9.20 Th3; "corroboration" against §8.31.
6. C7–C8 (commission B): repair locus vs root cause; S0388 as a secondary report on sources the book has read (S0321, S0356–S0373, S0383–S0384) against §8.94 C1, §8.83 C3, §8.96; and for both commissions, the scope (within D, the estate, or the whole book) of every claim.
7. Anything presented as decided that is the Principal's.

## Access (binding)
- **Allowed:** this brief, your saved inventory, `STEP8-AND-CHECKS.md`; `03-methodology/REVIEWER-INSTRUCTIONS.md`; the working book (the top-level files in `06-theory/manuscript/chapter_*/`, never a `review/` subfolder; `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/RESEARCH-PURPOSE.md`); W's tools (`tmp/tools/`, run on a scratch copy only); the window sources of your commission (A: S0383–S0386; B: S0387–S0391); and, for checking only, S0380, S0381 and S0382 (on the allowed list; check each path there first).
- **No git history:** do not run `git log`, `git show`, `git diff`, `git blame` or any other command that reads earlier versions of a file or lists a commit's files. Read each allowed file as it is on disk.
- **No globs (the Principal, 2026-11-03):** every search, listing, read or copy names its files one by one, by full path; no wildcard (`*`, `?`, `[...]`), no recursive search (`grep -r`, `find`), and no directory listing over corpus or book folders.
- **The allowed list (the Principal, 2026-11-03)** is `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Locate sources only through it.
- **A file you may not open, you may not search either.** Restrict every search to the files above; do not search `06-theory/manuscript/` recursively (it holds review folders).
- **Copy list (the Principal, 2026-11-02):** to run a tool on a scratch copy, copy only the files in `COPY-LIST-STEP2.txt` in this folder, each by its own path (for example `while read f; do mkdir -p "$SCR/$(dirname "$f")"; cp "$f" "$SCR/$f"; done < docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C107-S0383-S0391/COPY-LIST-STEP2.txt`, run from the repository root). No directory copy: no `cp -r`, `rsync`, or glob of `06-theory/` or `manuscript/`. Copy nothing else; if a tool needs a file not on the list, do not run it, and record that. Run `undefined_terms.py` with `--no-corpus` (corpus files are not on the list).
- **Never:** any other review folder; `00-access/`; any other file under `03-methodology/`; S0155, S0156, S0157, S0161, S0170, S0174; `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, `docs/knowledgeos/chronological-read/*`. If a file is refused or not allowed, record it and continue.
- Scratch files: `c107-wA-*.txt`, in your scratch directory. Edit nothing. No corpus text to any outside service. Do not reproduce any private person's name; the outside-authors rule (persons by role; eponymous terms and bibliographic citations unchanged; paths unchanged).

## Report
Begin "C107 REVIEW REPORT (A)". Findings table (W-nn · severity · R/N · claim · evidence · correction · R3 lines), where R/N marks whether the finding concerns a restatement item (R: the source restates what the book already settled, and the finding is about how the draft maps it) or new theory (N) (the Principal, 2026-11-03); give the R and N counts after the table; a verdict per claim (C1–C4); coverage; at most 10 lines of summary. End with every file opened and every command and search run.

## Your commission (A)
- Review **C1–C4** of §8.101 only, against your inventory of S0383–S0386. The other commission reviews the remaining claims; do not review them. Report the lineage note and §8.101.3 only where they concern your sources.
