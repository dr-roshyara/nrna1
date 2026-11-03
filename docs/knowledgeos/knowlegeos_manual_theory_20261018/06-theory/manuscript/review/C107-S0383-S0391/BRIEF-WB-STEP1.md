# C107, the window commission B: step 1 (blind inventory)

*2026-11-03. Window reviewer WB (Opus). Rulebook v1.4. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`.*

Write the blind inventory (REVIEWER-INSTRUCTIONS "Step 1") of the sources below, in index order, each read whole to its last line (read long files in consecutive parts; some files have no final newline). For each source, also give **your depth judgement**: full, or skim. Under v1.4 §3, skim only a purely operational source: a diagram, a log, a listing. Hand back and stop.

| S-ID | Path (repository-relative) | Lines |
|---|---|---|
| S0387 | `docs/knowledgeos/reviews/2026-08-23-KOS-OPERATING-MODEL-001-AMENDMENT-001-APPOINTMENT-SEQUENCING-DEFECT-001.md` | 175 |
| S0388 | `docs/knowledgeos/reviews/20260823-2212-KOS-BRAINSTORMING-KERNEL-THREAD-READING-REPORT.md` | 310 |
| S0389 | `docs/knowledgeos/brainstorming/00_INDEX.md` | 313 |
| S0390 | `docs/knowledgeos/reviews/2026-08-23-KOS-OPERATING-MODEL-001-AMENDMENT-001-INDEPENDENT-VERIFICATION.md` | 364 |
| S0391 | `docs/knowledgeos/reviews/2026-08-23-KOS-OPERATING-MODEL-001-AMENDMENT-001-FAIL-DISPOSITION-AND-REPAIR-PATH.md` | 158 |

All are on `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`.

## Rules for the inventory (the Principal)
- Keep what a source says, what it reports, and your own judgements ([I]) apart.
- A finding is not resolved because code or tests pass.

## Access (binding)
- **Allowed:** `03-methodology/REVIEWER-INSTRUCTIONS.md`, this brief, and the sources above. **Nothing else**: not the book, the register, the map, the review folders, or any other corpus file, including files the sources cite. The inventory is blind.
- **A file you may not open, you may not search either** (no grep, listing or pattern over it). This applies to every path not listed as allowed.
- **No git history:** do not run `git log`, `git show`, `git diff`, `git blame` or any other command that reads earlier versions of a file or lists a commit's files. Read each allowed file as it is on disk.
- **No globs (the Principal, 2026-11-03):** every search, listing, read or copy names its files one by one, by full path; no wildcard (`*`, `?`, `[...]`), no recursive search (`grep -r`, `find`), and no directory listing over corpus or book folders.
- **The allowed list (the Principal, 2026-11-03)** is `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Locate sources only through it.
- **Copy list (the Principal, 2026-11-02):** you may copy to your scratch directory only the sources listed above, each by its own path. No directory copy (no `cp -r`, `rsync` or glob). Copy nothing else.
- **Never open** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on anything a source says about them; mark such items "(access)". Never `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, or `docs/knowledgeos/chronological-read/*`. No searches or listings of `docs/knowledgeos/`, except a targeted check of these five paths against the allowed list.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- If you write a scratch file, name it `c107-wB-*.txt`.
- No corpus text to any outside service. Edit nothing. Do not reproduce any private person's name. **The outside-authors rule:** persons named as people in prose are referred to by role; eponymous technical terms and bibliographic citations stay unchanged; paths and filenames are quoted unchanged.

## Hand-back
Begin "C107 STEP 1 (B): BLIND INVENTORY". Per source: numbered items with line numbers; distinctions, rules, claims, contradictions, changes over time, open questions; [I] for your own judgements. Then a cross-source table and a lineage note. End with every file you opened, and the commands you ran.
