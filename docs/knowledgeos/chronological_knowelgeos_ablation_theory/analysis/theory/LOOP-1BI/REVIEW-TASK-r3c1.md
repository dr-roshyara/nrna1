# Re-review of the coder-facing schema r3c-1 (frozen)

You have `SCHEMA-r3.md` (authoritative), `SCHEMA-r3-CODER.md`, `EQUIVALENCE.md`, `CHANGELOG-r3c1.md`, the previous review, `RECORDS.json` and `SOURCES.md` (for the anchoring check only).

Check:
1. **Equivalence:**
   - For every rule id in SCHEMA-r3.md, is the coder text identical in meaning?
   - Construct at least 5 adversarial inputs at the places where the previous review found drops (refusal-to-proposal binding, target, the A3 note, mechanism failure). Do both texts give the same answer?
2. **Anchoring:**
   - Does any example share wording, structure or answer pattern with a specific RECORDS.json record?
   - Do the examples come from one neutral, non-software domain?
3. **Contamination:** any mention of hypotheses, earlier codings, the study or expected answers?
4. **Self-containment:** is anything needed but missing? Are the `NOT IN SCHEMA` markers acceptable, or do they show a gap in SCHEMA-r3 itself?
5. **Codability** risk rating; word count.

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL** (non-equivalence or anchoring is blocking).

Write **REVIEW.md** and **REVIEW.json** ({verdict, equivalence_ok, adversarial_tests: [...], anchoring_issues: [...], contamination_issues: [...], not_in_schema: [...], codability_risk, words, blocking: [...], nonblocking: [...]}).
