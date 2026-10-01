# Review of SCHEMA-r3 consolidation (frozen)

Use only the files here.

Check:
1. **Drafting fixes:** is each listed item fixed, with no substantive change beyond it?
2. **Equivalence:**
   - Does SCHEMA-r3-CODER.md contain **exactly** the rules of SCHEMA-r3.md? Verify EQUIVALENCE.md row by row.
   - Is anything added, dropped or changed in meaning?
3. **Contamination:**
   - Does SCHEMA-r3-CODER.md mention hypotheses, earlier codings, the reliability study, or expected answers?
   - Are its worked examples invented (not RECORDS.json spans)?
   - Could any example anchor a coder toward a particular record's answer?
4. **Codability:** could a fresh coder code all 36 records from SCHEMA-r3-CODER.md alone? Rate the residual risk.
5. **KNOWN-AMBIGUITIES:** is it complete and correct, i.e. are the r2 review's missing items added?

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL**, with every blocking issue. Non-equivalence or contamination is blocking.

Write **REVIEW.md** and **REVIEW.json** ({verdict, drafting_fixed: {...}, equivalence_ok, equivalence_issues: [...], contamination_issues: [...], codability_risk, coder_schema_words, known_ambiguities_ok, blocking: [...], nonblocking: [...]}).
