# Review of SCHEMA-r3 (frozen)

A researcher drafted SCHEMA-r3.md, THEORY-CHOICES.md and RATIONALE.md (TASK.md). Use only the files here.

Audit:
1. **Gap closure:** for each gap G1–G6, does r3 give an operational rule that yields one answer, or UNKNOWN? Test it on the example records named in GAPS.md (R15–R21, R01, R26, R05, R27, R29, R10, R23, R02, R30, R06/R07). Report the answer each rule yields, or "still ambiguous".
2. **Minimality:** is anything changed beyond the six gaps?
3. **Neutrality:**
   - Does any rule build H-D or H-B into the observations? Pay particular attention to the R-A3 scope and the "governed act" level.
   - Should any choice have been listed in THEORY-CHOICES instead of decided?
   - Is any choice listed there that could have been decided neutrally?
4. **Consistency:** do the new rules conflict with §1–4, with R-A1 to R-A4, or with each other? Is the priority order complete?
5. **Codability:** could a fresh coder apply r3 without the rationale?

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL**, with every blocking issue. A rule that encodes a hypothesis is **blocking**.

Write **REVIEW.md** and **REVIEW.json** ({verdict, gap_tests: [{gap, record, answer}], still_ambiguous: [...], non_minimal_changes: [...], neutrality_issues: [...], theory_choices_assessment: [...], conflicts: [...], blocking: [...], nonblocking: [...]}).
