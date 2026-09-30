# Adversarial review of a schema-revision decision package (frozen)

A drafter wrote `PACKAGE.md` / `PACKAGE.json` from the files in this directory (TASK.md describes the assignment). Use only these files.

Attack the package. In particular:
1. **Source fidelity.** Are the counts, examples and affected-record lists correct against EVENTS.json and the audit files? Are disputed classifications presented as certain?
2. **Necessity.** Is the norm/act distinction really necessary, or does an analysis-time filter over v1 (e.g. using `speech_act` or genre fields) achieve the same? Is the case overstated?
3. **The dual-nature problem.** A ruling that permits something is *itself* an act (an act of permitting). Does the proposal handle records that are simultaneously an ACT of norm-making and a NORM about another act? Is a single `observation_type` field enough, or does it need a two-level representation (the act of the record vs the content it states)?
4. **Circularity / hindsight.** Is the proposal tailored to rescue or sink particular variables? Would it be adopted on its merits without knowing the outcomes?
5. **Failure modes and falsification.** Are they concrete, and testable?
6. **Minimality.** Is there a smaller change?
7. **Options.** Are they fair and complete? Is any option presented in a loaded way?

Verdict: **PASS** (the package fairly supports a human decision) · **PASS_WITH_LIMITATIONS** · **FAIL** (with the exact defects).

Classify each issue as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({verdict, fidelity_issues: [...], necessity_assessment, dual_nature_assessment, minimal_alternative, loaded_options: [...], disagreements: [...]}).
