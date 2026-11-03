# C103, the window commission: step 2 (review)

*2026-11-02. Window reviewer W (Opus), after your step-1 inventory was saved with its sha256 (`SHA256-STEP1.txt`). Rulebook v1.4; `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). Re-read your saved inventory first (`STEP1-INVENTORY.md` in this folder).*

## The object
- `06-theory/manuscript/chapter_08/08.96-twelve-answers-with-a-claim-as-aggregate-root-ten-invariants-and-an-event-stream-per-identity.md` (new, draft) and its row in `chapter_08/08.00-index.md`.
- `STEP8-AND-CHECKS.md` in this folder (dependencies, the line checks, the checks, the proposed R3).
- Changed theory since the last window review: the C102 settlement (`39f1f6b5a`; §8.95 v2; R3 on §8.94 C1). The draft was committed before your step-1 dispatch, together with an R3 note on §8.95's lineage.

## Questions
1. The REVIEWER-INSTRUCTIONS checks for a transition review: each claim against the cited source and book lines; labels; homes; R2; the quote check by hand; R3 for any correction.
2. Completeness against your inventory: what did §8.96 leave out, and is any omission a loss of theory?
3. C1–C2: the aggregate root against §8.90 C1, §8.95 C2 and §9.20 Th2; identity against §8.95 C3, §1.10 L35, §9.16 L93 and §8.92 C4.
4. C3–C4: the ten invariants one by one, and the stream per identity against §9.20 Th4, §8.92 C2, §8.87 C2 and K6.
5. C5–C6: the support relation against §9.18 L111–113 and §9.18.6; the postulates and the identity-only item against §8.94 C6, §9.13 L129 and §8.91 C6; the R3 note on §8.95's lineage; scope (within D or the whole book) of every claim.
6. Lineage: S0372 (A) as S0371's uploaded document; (B) quoting S0370 (A) as S0372's.
7. Anything presented as decided that is the Principal's.

## Access (binding)
- **Allowed:** this brief, your saved inventory, `STEP8-AND-CHECKS.md`; `03-methodology/REVIEWER-INSTRUCTIONS.md`; the working book (the top-level files in `06-theory/manuscript/chapter_*/`, never a `review/` subfolder; `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/RESEARCH-PURPOSE.md`); W's tools (`tmp/tools/`, run on a scratch copy only); the window source S0372; and, for checking only, S0366, S0367, S0369, S0370 and S0371 (on the allowed list; check each path there first).
- **No git history:** do not run `git log`, `git show`, `git diff`, `git blame` or any other command that reads earlier versions of a file or lists a commit's files. Read each allowed file as it is on disk.
- **A file you may not open, you may not search either.** Restrict every search to the files above; do not search `06-theory/manuscript/` recursively (it holds review folders).
- **Copy list (the Principal, 2026-11-02):** to run a tool on a scratch copy, copy only the files in `COPY-LIST-STEP2.txt` in this folder, each by its own path (for example `while read f; do mkdir -p "$SCR/$(dirname "$f")"; cp "$f" "$SCR/$f"; done < docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C103-S0372/COPY-LIST-STEP2.txt`, run from the repository root). No directory copy: no `cp -r`, `rsync`, or glob of `06-theory/` or `manuscript/`. Copy nothing else; if a tool needs a file not on the list, do not run it, and record that. Run `undefined_terms.py` with `--no-corpus` (corpus files are not on the list).
- **Never:** any other review folder; `00-access/`; any other file under `03-methodology/`; S0155, S0156, S0157, S0161, S0170, S0174; `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, `docs/knowledgeos/chronological-read/*`. If a file is refused or not allowed, record it and continue.
- Scratch files: `c103-w-*.txt`, in your scratch directory. Edit nothing. No corpus text to any outside service. Do not reproduce any private person's name; the outside-authors rule (persons by role; eponymous terms and bibliographic citations unchanged; paths unchanged).

## Report
Begin "C103 REVIEW REPORT". Findings table (W-nn · severity · claim · evidence · correction · R3 lines); a verdict per claim (C1–C6); coverage; at most 10 lines of summary. End with every file opened and every command and search run.
