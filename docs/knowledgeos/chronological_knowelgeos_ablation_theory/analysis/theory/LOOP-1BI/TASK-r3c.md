# Task (frozen): SCHEMA-r3 consolidation (r3c): no substantive change

Use ONLY the files here:
- r2: `SCHEMA-r3.md`, `THEORY-CHOICES.md`, `RATIONALE.md`, `CHANGELOG-r2.md`, `KNOWN-AMBIGUITIES.md`;
- its review: `REVIEW-r2.md` / `.json`, PASS_WITH_LIMITATIONS, codability risk HIGH;
- `SCHEMA-r2.md`, `RECORDS.json`, `SOURCES.md`.

No commands, no web.

1. **Fix only these drafting items** from REVIEW-r2 `nonblocking`:
   - R-A5 step 1 vs §1 / D10 for shared-span ground acts (R06/R07);
   - PROVENANCE in D15 (ii) vs PV;
   - MECHANISM RESPONSE requires a stated non-effect; state how intended test failures are handled;
   - make R-A5 step 2 conditional;
   - align the TC-1 1c table and recipe;
   - add the tag `act.ground_candidates` (acts excluded by D15 stay computable);
   - define the "normative word" for class-label clauses;
   - fill `mechanism_refusal` on R03.
2. **Correct KNOWN-AMBIGUITIES.md:** fix the header for R15–R17, and add every still-ambiguous item that the review says is missing.
3. **Produce `SCHEMA-r3-CODER.md`: the coder-facing schema.**
   - Self-contained and markup-free: no `[r3-…]` / `[r2-fix-…]` tags, no rationale, no history, and **no mention of hypotheses or of any earlier coding**.
   - The same rules as `SCHEMA-r3.md`, reorganised for a coder:
     1. unit;
     2. fields (with every tag);
     3. definitions;
     4. decision procedures: ACT first, then NORM, each as numbered steps with a priority order;
     5. **one worked example per decision path**, using **invented spans, not RECORDS.json spans**, to avoid anchoring the coders;
     6. the output JSON format.
   - Target ≤ 3,500 words.
4. **Produce `EQUIVALENCE.md`:** a table mapping every rule in SCHEMA-r3.md to its location in SCHEMA-r3-CODER.md, and confirming that no rule was added, dropped or changed.

Write the updated **SCHEMA-r3.md**, **KNOWN-AMBIGUITIES.md** and **CHANGELOG-r3c.md**, plus **SCHEMA-r3-CODER.md** and **EQUIVALENCE.md**.
