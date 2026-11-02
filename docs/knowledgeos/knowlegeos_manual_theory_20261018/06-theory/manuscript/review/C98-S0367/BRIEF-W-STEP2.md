# C98, the window commission: step 2 (review)

*2026-11-02. Window reviewer W (Opus), after your step-1 inventory was saved with its sha256 (`SHA256-STEP1.txt`). Rulebook v1.4; `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). Re-read your saved inventory first (`STEP1-INVENTORY.md` in this folder).*

## The object
- `06-theory/manuscript/chapter_08/08.91-a-consistency-boundary-left-open-statuses-the-record-derives-and-a-lens-for-what-is-absent.md` (new, draft) and its row in `chapter_08/08.00-index.md`.
- `STEP8-AND-CHECKS.md` in this folder (dependencies, the line checks, the checks, the proposed R3).
- Changed theory since the last window review: the C97 settlement (`9c2809ffc`; §8.90 v2); the T9-02 theory turn (§9.20 v2, `2b204b003`) and the Principal's ruling retyping T9-02's deliverable (`2620c585d`). The draft was committed before your step-1 dispatch.

## Questions
1. The REVIEWER-INSTRUCTIONS checks for a transition review: each claim against the cited source and book lines; labels; homes; R2; the quote check by hand; R3 for any correction.
2. Completeness against your inventory: what did §8.91 leave out, and is any omission a loss of theory?
3. C1–C2: the atomic boundary and history against §8.89 C2, §9.20 Th1, Th3 and L155; the four statuses against §8.88 C2, C5, §9.15 K1, §9.16 R2 and §9.18.4.
4. C3–C4: the claim vector against §9.20 Th2 and §9.20.7 (with the Principal's ruling of 2026-11-02: it decides neither F13 nor F37); evidence options A–F against §9.18 L111, G7, §9.18.6 (c2) and T9-12 (c).
5. C5–C6: the [IT] lens against §8.51 C1, §8.89 C4 and §9.13 L46–56, L81; the Zero-lens Ded against §9.13 L129 and §9.10 Q1–Q2; scope (within D or the whole book) of every claim.
6. Lineage: (A) as an answer to S0365 (B)'s commission (S0365 L1715, L1803, L1820, L1850); the three-part split of S0367.
7. Anything presented as decided that is the Principal's.

## Access (binding)
- **Allowed:** this brief, your saved inventory, `STEP8-AND-CHECKS.md`; `03-methodology/REVIEWER-INSTRUCTIONS.md`; the working book (the top-level files in `06-theory/manuscript/chapter_*/`, never a `review/` subfolder; `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/RESEARCH-PURPOSE.md`); W's tools (`tmp/tools/`, run on a scratch copy only); the window source S0367; and, for checking only, S0365 and S0366 (on the allowed list; check each path there first).
- **No git history:** do not run `git log`, `git show`, `git diff`, `git blame` or any other command that reads earlier versions of a file or lists a commit's files. Read each allowed file as it is on disk.
- **A file you may not open, you may not search either.** Restrict every search to the files above; do not search `06-theory/manuscript/` recursively (it holds review folders).
- **Copy list (the Principal, 2026-11-02):** to run a tool on a scratch copy, copy only the files in `COPY-LIST-STEP2.txt` in this folder, each by its own path (for example `while read f; do mkdir -p "$SCR/$(dirname "$f")"; cp "$f" "$SCR/$f"; done < docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C98-S0367/COPY-LIST-STEP2.txt`, run from the repository root). No directory copy: no `cp -r`, `rsync`, or glob of `06-theory/` or `manuscript/`. Copy nothing else; if a tool needs a file not on the list, do not run it, and record that. Run `undefined_terms.py` with `--no-corpus` (corpus files are not on the list).
- **Never:** any other review folder; `00-access/`; any other file under `03-methodology/`; S0155, S0156, S0157, S0161, S0170, S0174; `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, `docs/knowledgeos/chronological-read/*`. If a file is refused or not allowed, record it and continue.
- Scratch files: `c98-w-*.txt`, in your scratch directory. Edit nothing. No corpus text to any outside service. Do not reproduce any private person's name; the outside-authors rule (persons by role; eponymous terms and bibliographic citations unchanged; paths unchanged).

## Report
Begin "C98 REVIEW REPORT". Findings table (W-nn · severity · claim · evidence · correction · R3 lines); a verdict per claim (C1–C6); coverage; at most 10 lines of summary. End with every file opened and every command and search run.
