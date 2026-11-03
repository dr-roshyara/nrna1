# C101, the window commission: step 2 (review)

*2026-11-02. Window reviewer W (Opus), after your step-1 inventory was saved with its sha256 (`SHA256-STEP1.txt`). Rulebook v1.4; `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). Re-read your saved inventory first (`STEP1-INVENTORY.md` in this folder).*

## The object
- `06-theory/manuscript/chapter_08/08.94-the-six-part-aggregate-falsified-a-smaller-boundary-argued-by-traceability-and-history-placed-outside.md` (new, draft) and its row in `chapter_08/08.00-index.md`.
- `STEP8-AND-CHECKS.md` in this folder (dependencies, the line checks, the checks, the proposed R3).
- Changed theory since the last window review: the C100 settlement (`c61f8c12b`; §8.93 v2). The draft was committed before your step-1 dispatch.

## Questions
1. The REVIEWER-INSTRUCTIONS checks for a transition review: each claim against the cited source and book lines; labels; homes; R2; the quote check by hand; R3 for any correction.
2. Completeness against your inventory: what did §8.94 leave out, and is any omission a loss of theory?
3. C1–C2: the falsification and the smaller boundary against §9.20 Th3, §8.92 C1 and §8.91 C1; the justification invariant against A1, §9.15 G2 and §8.87 C2.
4. C3–C4: history in an audit context against §9.20 Th1, L155 and §8.91 C1; identity and (B)'s nine scenarios against §8.92 C4, C5, §8.93 C5 and §9.20 Th3.
5. C5–C6: the Zero answers against §9.15 G1, §9.20.8 L153 and §8.91 C6; the [IT] table and the gates against §8.91 C5, §9.15 K1, K5–K7 and §9.13 L46–56; scope (within D or the whole book) of every claim.
6. Lineage: (P) as S0368 (B)'s commission (S0368 L959–1079); (A) as DeepSeek's second round.
7. Anything presented as decided that is the Principal's.

## Access (binding)
- **Allowed:** this brief, your saved inventory, `STEP8-AND-CHECKS.md`; `03-methodology/REVIEWER-INSTRUCTIONS.md`; the working book (the top-level files in `06-theory/manuscript/chapter_*/`, never a `review/` subfolder; `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/RESEARCH-PURPOSE.md`); W's tools (`tmp/tools/`, run on a scratch copy only); the window source S0370; and, for checking only, S0368 (on the allowed list; check each path there first).
- **No git history:** do not run `git log`, `git show`, `git diff`, `git blame` or any other command that reads earlier versions of a file or lists a commit's files. Read each allowed file as it is on disk.
- **A file you may not open, you may not search either.** Restrict every search to the files above; do not search `06-theory/manuscript/` recursively (it holds review folders).
- **Copy list (the Principal, 2026-11-02):** to run a tool on a scratch copy, copy only the files in `COPY-LIST-STEP2.txt` in this folder, each by its own path (for example `while read f; do mkdir -p "$SCR/$(dirname "$f")"; cp "$f" "$SCR/$f"; done < docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C101-S0370/COPY-LIST-STEP2.txt`, run from the repository root). No directory copy: no `cp -r`, `rsync`, or glob of `06-theory/` or `manuscript/`. Copy nothing else; if a tool needs a file not on the list, do not run it, and record that. Run `undefined_terms.py` with `--no-corpus` (corpus files are not on the list).
- **Never:** any other review folder; `00-access/`; any other file under `03-methodology/`; S0155, S0156, S0157, S0161, S0170, S0174; `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, `docs/knowledgeos/chronological-read/*`. If a file is refused or not allowed, record it and continue.
- Scratch files: `c101-w-*.txt`, in your scratch directory. Edit nothing. No corpus text to any outside service. Do not reproduce any private person's name; the outside-authors rule (persons by role; eponymous terms and bibliographic citations unchanged; paths unchanged).

## Report
Begin "C101 REVIEW REPORT". Findings table (W-nn · severity · claim · evidence · correction · R3 lines); a verdict per claim (C1–C6); coverage; at most 10 lines of summary. End with every file opened and every command and search run.
