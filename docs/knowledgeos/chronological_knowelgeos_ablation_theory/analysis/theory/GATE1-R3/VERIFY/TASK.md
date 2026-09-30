# Verification of a coding-manual revision (frozen)

Files:
- `OLD-MANUAL-GATE1.md`, `OLD-MANUAL-RESERVE.md`: the manuals before the revision;
- `R3-MANUAL-GATE1.md`, `R3-MANUAL-RESERVE.md`: after the revision;
- `RECOMMENDATIONS.md`: the seven clarifications that were adopted.

Use only these files; no commands, no web.

Verify each of the following:
1. **Fidelity.** Is each r3 manual exactly its old manual plus a section G, with no other change? Check line by line, and report any other difference. Does section G reproduce the seven recommendations verbatim, except for the dropped "RECOMMENDATION ONLY." prefix?
2. **Consistency.** Does any G rule contradict, or silently change the meaning of, a rule in sections A–F? For each conflict, quote both texts and say which prevails under G's own override clause. Pay special attention to:
   - G2's V1 precedence vs any existing V1 aggregation rule;
   - G6's AMBIGUOUS threshold vs existing AMBIGUOUS guidance;
   - G7's V2 applicability vs the V2 definition;
   - reserve section F vs G.
3. **Codability.** Can a coder apply each G rule from the text alone? Flag undefined terms (e.g. "§C guard", "guarded act satisfied", "demonstrated need") and whether sections A–F define them.
4. **Asymmetry.** The Gate 1 manual has no section F; the reserve manual does. Does G depend on section F anywhere, so that it would be incomplete in the Gate 1 manual?
5. **Verdict:** PASS · PASS_WITH_LIMITATIONS · FAIL, with the exact defects for any FAIL. This checks the correctness of the adoption only; it does not decide whether to adopt.

Write **VERIFY.md** and **VERIFY.json** ({fidelity_ok, other_differences, verbatim_ok, conflicts: [{rule, existing_quote, g_quote, prevails}], undefined_terms: [...], f_dependency, verdict, defects: [...]}).
