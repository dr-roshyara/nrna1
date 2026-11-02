# Window C72 (S0310): settlement

*2026-11-02. The Professor. Object: §8.65 draft (`9812e9ad8`). Reviewer W (Opus): step-1 inventory with sha256 (in `9812e9ad8`), report `e306a6c2e` (`SHA256-REVIEW.txt`). 12 findings: 3 high, 9 normal, none critical. All accepted. W also corrected two of its own step-1 statements that the draft had taken over (W-04, W-05). Result: §8.65 v2 (filename kept; title restated).*

## Theory first

- **Held:**
  - the grading of "demonstrated" against §8.64 C3;
  - the third answer's rejection of the projection, as the source's own correction;
  - the agreement that a correct parse is not an evidential class;
  - the actor gap in "KnowledgeOS can decide";
  - the trivial abstainer;
  - context in identity;
  - modality kept;
  - the fork kept open.
- **Corrected:**
  - **UNKNOWN for lack of evidence is g1's "not attested", not standing's U** (W-01). It is standing's U only under the kernel guard declined by T9-20.
  - **S0310's "epistemic correctness" aims at the truth of content on grounds.** The book computes this nowhere, and its third answer, standing, has no place in the split (W-02).
  - **The revision example is a change of context** under the line's own rule, so "identity continuity" contradicts it within the line (W-03).
  - **The arithmetic restated** (W-04 to W-07): the "Contribution" column is a relabelling of earlier figures; series throughput is at most about 13.5k/s; five of six stage tables are mismatched; the projection uses stage-local gains, which the next answer reads as addition.
  - **The cited decision is compatible with both branches of the fork** (W-09).
- **Map:** the THEORY-MAP §7 fork line is annotated with S0310 L1203.

## Dispositions

| W | Disposition | Where |
|---|---|---|
| W-01 | accepted | §8.65 PL4, C3; index |
| W-02 | accepted | §8.65 PL3, C2; index |
| W-03 | accepted | §8.65 PL5, C4; index |
| W-04 | accepted (W's own step-1 error) | §8.65 C1 |
| W-05 | accepted (W's own step-1 error) | §8.65 PL2, C1; index |
| W-06 | accepted; title restated, filename kept | §8.65 title, PL2, C1; index |
| W-07 | accepted | §8.65 C1 |
| W-08 | accepted | §8.65 C3 |
| W-09 | accepted; map annotated | §8.65 C5; THEORY-MAP §7 |
| W-10 | accepted | §8.65 C5 |
| W-11 | accepted | §8.65 PL1, C1 |
| W-12 | accepted | §8.65 C5 |

**Coverage items marked MISSING by W:** all added to §8.65.3 or the claims. These are B 926, A 323, the 99%/1% routing, the homonyms, Expression ≠ Meaning, the governance drift, the domain dependence (W-12), and L100 against L527 (W-11). W's wording point on "It is not anticipated" (L9) is restated.

## R3

§8.65 C1–C5 are not cited outside §8.65. Updated: the 08.00 row for 8.65 and the THEORY-MAP §7 fork line. No register change.

## Process

- The step-2 brief's Q2 carried "§8.62" from the template once more. I corrected it before dispatch (`c54c50bb1`).
- **Effort (phase.py; waiting apart):**

  | Category | Time (UTC) |
  |---|---|
  | Reading | 00:18:26–00:19:02 |
  | Drafting | 00:19:02–00:22:46 |
  | Waiting (W step 2) | 00:22:46–00:28:30 |
  | Rework | 00:28:30 onwards |
  | Settlement | this file, logged at commit |

## Correction after commit

`5a89cdbb3` committed this settlement with the **draft** §8.65. The v2 write had been refused because the file had changed since it was last read, and I did not check the tool result before committing. §8.65 v2 is in the next commit. The index, the map and this file already described v2.
