# Consolidation S0101–S0130: the short cross-chapter integration review (Rulebook §12, entry 57)

*2026-10-23. After both reviewers. One reviewer (Opus), independent of A and B.*

## Read
- `REPORT-A.md` and `REPORT-B.md` (this folder);
- `../../BOOK-OUTLINE.md`;
- the R3 lists: each report's findings table carries the R3 dependents of every proposed correction (A: column "R3 dependents"; B: in "Correction").

Open the cited book sections where you need them, frozen at commit `3d10a8007`, unchanged since. You may run `W/tmp/tools/dependency_graph.py --down <ids>` (read-only).

## Check across chapters (§12c's four questions)
1. **The same term with different meanings in different chapters.** Both reports name homonyms: owner, reference, conflict, NOT ATTESTED, E1–E10, L1–L3, C1. Which are real across chapters, and which carry a law?
2. **A law in one chapter that rests on a concept another chapter rejects.** In particular:
   - Ch. 1 §1.14.1 against Ch. 9 §9.13 (A-06, R1-1);
   - §1.10's identity against §9.16 R2's content identity (A's R1-6);
   - §1.16.7 against §9.16 R3 (B's R1-5);
   - §8.6 against S0164 as a copy of S0103 (B-04).
3. **The derivation chain** from the primitives to the laws, across chapters. Do C19–C24's kernel-facing additions (D's owner, transfer and single-use fields; K4-k; L9; L10) attach to the primitives of Ch. 0–1? Is the chain broken anywhere?
4. **Duplicates:** one law with two homes (e.g. §1.14.1 and §9.13; B-06's prefix-closure point in §8.14 C5 and §8.17 C5).

## Also
- **Compare A and B:** found by both / by one only. For each finding found by one only, say whether it holds.
- **Check the R3 lists:** for each proposed correction, are the listed dependents complete, and does any dependent need its own correction?

## Report
- Tables: cross-chapter defects (ID I-nn · finding · severity · evidence · correction · resolved when); the both/one-only comparison.
- A summary of at most 8 lines.
- Process independence in one line.
- Deliver once, through the hand-back, beginning "CONSOLIDATION S0101-S0130 INTEGRATION REPORT".

**Constraints:** the same as the reviewers' (no basis files, no sealed files, edit nothing, scratch prefix `cons_i_`).
