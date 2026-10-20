# Theory turn 0 (critical-path chain, Rulebook v1.3 §12b, entry 59): review of stage 2 of the whole-book review S0100

*Brief for two independent reviewers on different models (A = Opus, B = Sonnet). Neither sees the other's report. 2026-10-20. By the Professor.*

## 1. Deliverable and "resolved when"
**Deliverable:** a verdict, claim by claim, on whether the stage-2 corrections are sound, and whether they introduced new defects.
**Resolved when:** every item in §3 has a verdict (holds / holds with a change / does not hold), with evidence, and every finding states severity, the R5 classification where it is a missing premise, and an observable "resolved when" condition.

## 2. What you review (the changed theory of three commits)
Workspace root: `docs/knowledgeos/knowlegeos_manual_theory_20261018/` (call it W). Use `git -C W show <commit>` / `git -C W diff 8c4601ecf 0b10262df -- 06-theory` to see every change.
- `81cfcd1eb`: the four critical corrections (item 4a).
- `1862e2675`: the settlement's real corrections (item 4b), and the shared-file edits (register, map, outline).
- `0b10262df`: register homes CON-0138/0140/0143; seven undefined terms defined.
- Also note `a8a93639f` (the Principal's decisions applied; marks moved to BOOK-OUTLINE §3a) and the quote-anchor fixes made for this brief (§5), so that you are not surprised by them; they are not the object of review.

Supporting records (read them):
- `06-theory/manuscript/review/WHOLE-BOOK-S0100/SETTLEMENT-STAGE1.md` (what was to be corrected, with "resolved when");
- `06-theory/manuscript/review/WHOLE-BOOK-S0100/STAGE2-APPLICATION-RECORD.md` (what the Professor says was done; R3 tables; open items §9);
- `03-methodology/RULEBOOK-v1.3.md` (§2 step 8, §6 with R1–R5 and entries 56–58);
- `06-theory/manuscript/review/WHOLE-BOOK-S0100/DECISION-BRIEFS-S0100.md` (the Principal's decisions at the end).

## 3. Items, in priority order
1. **The four critical corrections:**
   - P2-1, bearer fork F37: §3.1 "What carries the dimensions", CON-0184, CON-0185, CON-0002;
   - P2-2, set-wise conflict: §2.3 (d), §2.6 "Re-run on sets", §2.9 gate rule, §2.5 L22;
   - P4-1, kernel type: §9.1 C2 scope, C6 (CON-0183), the model `tmp/tools/kernel_type_model.py` with its run file, the "Kernel type" lines in §9.6–§9.9;
   - P4-4, §9.9 result 5 and M-T2, with N4-14.

   For each: is the defect the settlement named actually removed? Is the correction itself sound (definitions, derivation, counterexample, scope)? Did R3 find every dependant (search the book yourself)?
2. **CON-0183 to CON-0187**: does each register row say what its home section says? Are status and basis justified?
3. **Forks F36 and F37**: are they stated precisely enough to decide, and is each a genuine fork (not a disguised claim)?
4. **The real corrections (item 4b):** sample them. Priority: the material rows (P1-1 … P5-5, except the four above), then I-rows, then N-rows. Check at least every material row's "Resolved when" claim in the application record against the text.

## 4. Who wrote what (disclosure required by the Principal)
- **Written by the Professor and re-read:** item 1 (`8c4601ecf`), all of item 4a (`81cfcd1eb`), items 4c and 4d (`0b10262df`).
- **Written by forked sessions of the Professor (same model, same context up to the fork), not re-read by the Professor row by row:** all chapter-text changes of item 4b (`1862e2675`): Ch. 0–1 (fork P1), Ch. 2–3 (fork P2, except P2-1/P2-2), Ch. 4–5 (fork P3), Ch. 6 and Ch. 9 (fork P4, except P4-1/P4-4), Ch. 8 (fork P5); and the shared-file edits (register rows CON-0186, CON-0187 and 41 row updates; THEORY-MAP; BOOK-OUTLINE; CHAPTER-PLAN; propagation into other chapters) by a sixth fork. The Professor read only their summaries, their records (`STAGE2-APPLICATION-RECORD.md` §2–§7) and the tool results (R1, R2, quote check). **Treat item 4b as unreviewed text by any author.**
- Tools written by forks: `tmp/tools/containment_check.py`, `tmp/tools/ce_conformance_check.py` (with run files).

## 5. Quote check (`tmp/tools/corpus_quote_check.py`, every chapter section)
- Before stage 2 (`8c4601ecf`): 15 not found (`QUOTE-CHECK-before-stage2-8c4601ecf.txt`).
- **Baseline (entry 60):** `tmp/tools/QUOTE-BASELINE.txt` (whole book at 0b10262df; 17 failures, of which the three in 01.08, 03.01 and 05.04 are marked new for this turn, the other 14 inherited).
- At `0b10262df`: **17 not found** (`QUOTE-CHECK-at-0b10262df.txt`). One earlier failure was fixed in stage 2 (§1.6, S0018 L121, N1-09). **Three are new since stage 2:** §1.8 L15 [S0028 L144–148], §3.1 L64 [S0016 L58–63], §5.4 L60 [S0896 L800–808].
- The Professor checked the three against the sources: all three were **anchor errors** (the words are in the source: S0028 L149; S0016 L61 inside a block-quoted code block; S0896 L815). Fixed before this commission; after the fix: **14 not found**, all of them present before stage 2 (`QUOTE-CHECK-after-fix.txt`). You may check the remaining 14; they are not stage-2 defects.
- The tool checks only the `"…" [Snnnn Lx]` form. Quotes "in meaning" and quotes of non-corpus files are not checked by it.

## 6. Access
- The working book, register, map, outline, TODOs, change logs, review folders and git history of W: allowed.
- Corpus sources: only files listed in `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv` (check the sha256), opened at the anchors you check.
- ~~Ch. 0–2 basis files … read-only, for quote and meaning checks only.~~ **Corrected during the review (message to both reviewers): reviewers have no access to the basis files.** The Principal's grant (ACCESS-RECORD, 2026-10-20T07:16:47Z) covers the Professor only. A check that needs such a file is marked U; any file opened before the correction is listed in the report under "Access outside the grant".
- Never: `.env*`, `_batch_input*`, P3B-HOLDOUT, sealed or quarantine files, `docs/knowledgeos/chronological-read/*`. Do not edit anything. Do not send corpus text to outside services.

## 7. Method (for every material finding)
- scope, definitions and assumptions; the derivation step by step; the strongest counterexample; a materially different rival; the falsifier; labels Src / Ded / Int / Hyp;
- severity critical / high / normal (Rulebook §6; R5 for a missing premise: F1–F4, N1; confirm each cited dependency and name uncited ones);
- for each "adequate" judgement of a derivation, a proof-step table (R4);
- a "resolved when" condition, observable.
- Do not promote a proposal to a theorem; do not invent definitions.

## 8. Report
Write `W/06-theory/manuscript/review/TURN0-STAGE2/REPORT-A.md` (Opus) or `REPORT-B.md` (Sonnet):
1. Verdict table for §3 items 1–3 (item · verdict · evidence · finding IDs).
2. Findings table: ID (A-1… / B-1…) · item · finding · severity (with R5 class) · evidence (file:line, source:line) · correction proposed · resolved when.
3. Item-4 sample: which rows you checked, verdicts.
4. Coverage: what you did not check.
Return only a ≤10-line summary as your final answer.
