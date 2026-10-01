# Task (frozen): a targeted repair of the provisional Empirical Theory Record (r1)

Use ONLY the files in this directory: the r0 record (`EMPIRICAL-THEORY-RECORD.md` / `.json`), its review (`REVIEW-r0.md` / `.json`, verdict PASS_WITH_LIMITATIONS, 0 blocking), and the same source files as r0, **plus** `RETURN-02-COMPARISON.json` and `RETURN-02-REVIEW.md`.

No commands, no web.

**Apply every item in REVIEW-r0.json** and nothing else:
- `provenance_errors` P1–P6;
- `level_errors` L-1 to L-3;
- `tag_errors` T-1 to T-5;
- `section_h_issues` H-1 to H-4;
- `omissions` O-F1 to O-F7 and O-R1 to O-R9;
- `nonblocking` S-1 and S-2.

**Rules:**
- **Weaken, never strengthen.** Where an item is corrected, the new wording must be no stronger than its source.
- **RETURN-02 is within-family.**
  - Add it as an L1 item with confidence "frozen comparator; same-family".
  - Flip **no** R2_DEPENDENT tag to "no" because of it (T-5).
  - Make no cross-family claim.
- **Second-hand verbatim quotes (P4):** keep them at L0 only with the disclosure "as quoted by <file>". Otherwise move them to L1.
- **Keep every item id stable.** New items get new ids. Removed items are marked `withdrawn` with a reason, never deleted.
- **Keep the record PROVISIONAL pending R2 (cross-family).**

Write the repaired **EMPIRICAL-THEORY-RECORD.md** and **.json**, plus **CHANGELOG-r1.md** mapping every review item id to the change made.
