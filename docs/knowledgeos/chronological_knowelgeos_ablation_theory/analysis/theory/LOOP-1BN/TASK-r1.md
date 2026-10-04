# Task (frozen): V1a r1, a targeted fix before V1b runs

Use ONLY:
- `V1A-LEMMA.md`, `V1B-PREDICTIONS.md`: r0;
- `REVIEW.md` / `.json`: PASS_WITH_LIMITATIONS, 0 blocking;
- `FORMAL-THEORY-R4.md`, `HYPOTHESES.md`, `R6-VALIDATION-MATRIX.md`.

No commands, no web. Apply **only** these:

1. **Controls must be robust to the reporting convention.**
   - C-h, and C-U5 for R-h: rewrite each control as a **per-history evaluation** at a named history (e.g. "evaluate (iii) at I7 and report pass or fail"). **No control may depend on which counterexample is least.**
   - State, for every control, the exact output field and value that proves detection.
   - Mark C-O1 as *not diagnostic of the reading* for R-c, R-d and R-h.
2. **Missing readings M1–M3** (review `readings_check`). Add each one with its HYP licence. Classify it M or not-M, with a proof or with the point where the construction breaks.
   - Where a reading is not-M **and testable in U**, add sealed predictions and a planted control in the same format.
   - Otherwise mark it UNTESTABLE IN U, or REJECTED with a reason (M3 if appropriate).
3. **Consistency and rigour:**
   - make the R-d revision flag consistent with R-c's;
   - fix the proof of Lem V3.9(d) (which equality fails; the Cap-empty witness; base-frame vs U-specific claims);
   - frame R-g at N=0 as **the reading is inconsistent (H-B^g empty)**, not "D1 fails";
   - Lem V3.4(c): note that A6 is unused;
   - qualify Rem V4.1's "R-d ∘ R-e no interaction";
   - correct the wording of R-b, R-c and R-e;
   - fix the P1.8 parenthetical;
   - add labels to Table V3.0, Rem V4.1, Rem V3.4b and to the controls and scoring rule.

**Nothing else.** No change to R4. Statement ids stay stable.

Write **V1A-LEMMA.md**, **V1B-PREDICTIONS.md** (r1) and **CHANGELOG-r1.md**.
