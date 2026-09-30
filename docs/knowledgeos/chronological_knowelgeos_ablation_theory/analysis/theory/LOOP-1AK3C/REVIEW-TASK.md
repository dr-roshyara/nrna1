# Independent review of a formal computation (frozen)

Files: `evector.py` (the frozen script; its docstring states the coding rule, models and variants) · `EVENTS.json` (its input: reviewed event coding) · `RESULT.json` (its output) · `SOURCES-1AM2.md` (the verbatim sources behind the events). Use only these files. You cannot run code; recompute by hand.

Check each of the following:
1. **Rule fidelity.** Does the code implement the docstring's rule exactly?
   - unit sets;
   - interval bounds (1 ≤ breadth ≤ repetition);
   - eligibility of P and Q;
   - the falsification condition;
   - the variants.

   List every divergence, and any unit string in EVENTS.json that the code mishandles.
2. **Recomputation.** For each variant and model, recompute the P/Q pools and the falsifying pairs by hand. Report any mismatch with RESULT.json.
3. **Bounds validity.** Is "each instance, occurrence or slice lies in exactly one context" (so breadth ≤ repetition) justified by SOURCES-1AM2.md?
   - Could a single slice span several contexts?
   - Could one R-36 slice be counted as more than one context?

   If the bound fails for some unit, which pairs survive?
4. **Critical dependence.** In the strictest variant (VX+VE25), which events does every falsifying pair depend on? For each one:
   - quote its evidence coding from the sources;
   - state whether the source establishes the coded value or leaves it UNK;
   - recompute that variant with the value set to UNK.

   Label this sensitivity analysis explicitly as POST HOC.
5. **Interpretation.** What does the result establish about "evidence alone determines promotion" when evidence is a (repetition, breadth) vector? What does it not establish? What alternative explanation of the falsifying pairs (for example actor, target, or exception) survives?

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** (summary · rule fidelity · recomputation table · bounds · critical dependence and post hoc sensitivity · interpretation · disagreement register) and **REVIEW.json** ({rule_fidelity_ok, recomputation_matches, critical_events, strict_variant_if_critical_UNK, disagreements: [...]}).
