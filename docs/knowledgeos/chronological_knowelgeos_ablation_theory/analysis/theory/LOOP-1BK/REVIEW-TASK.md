# Review of the R4 formal-theory draft (frozen)

Use only the files here. Audit FORMAL-THEORY-R4.md:
1. **Base discipline:**
   - Does every data-dependent statement cite an item in R3-CODING-INDEPENDENT.json?
   - Is any coding-dependent claim present (act or norm counts, A′ codings)?
   - Is any section-H "may not" violated?
2. **Neutrality:** are H-D and H-B model classes derived faithfully from HYPOTHESES.md? Is either built into the primitives or into O?
3. **Logic:**
   - Is D1 stated precisely?
   - Is the proof valid? Check each step.
   - Do the countermodels actually violate exactly one assumption each, and do they break the conclusion?
   - Are the sufficient conditions in (e) actually sufficient? Construct a counterexample if they are not.
4. **Performative ambiguity:** is the definition well-formed? Is the conjecture falsifiable and labelled as a conjecture? Is it kept separate from the evidence?
5. **Levels:**
   - Is every statement labelled correctly in the ledger?
   - Is any conjecture or empirical observation presented as a proposition?
   - Is any finite check presented as a proof?
6. **Minimality:** is any primitive or assumption unused, or unneeded?
7. **R5 spec:** is it precise enough for an independent implementer? Is each expected outcome derivable from the text?

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL**, with every blocking issue. An invalid proof, a hypothesis used as an axiom, or a coding-dependent claim is blocking.

Write **REVIEW.md** and **REVIEW.json** ({verdict, base_violations: [...], neutrality_issues: [...], proof_issues: [...], countermodel_issues: [...], sufficiency_issues: [...], level_errors: [...], minimality_issues: [...], r5_spec_issues: [...], blocking: [...], nonblocking: [...]}).
