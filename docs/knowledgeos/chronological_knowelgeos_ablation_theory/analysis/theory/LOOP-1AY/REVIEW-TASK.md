# Independent review of a support computation chain (frozen)

**Files:**
- the schema: `SCHEMA-r2-Aprime.md`;
- the ACT / NORM coding of 36 span records and its review: `CODING-1ax.json`, `REVIEW-1ax.json`;
- a vocabulary normalization and its review: `MAPPING.json`, `REVIEW-1ayn.json`;
- the lineage to v1 events: `LINEAGE.json`, `EVENTS-v1.json`, `RECORDS.json`;
- three instrument versions `support_1ay*.py`, their results `RESULT*.json`, a note on r1 (`RESULT-NOTE.md`), a post hoc diagnostic (`DIAG-r2-confounding.txt`), and the pre-registration.

**Headline results:** r1, r2 and r3 give **no STRICT witness** for any variable in either the ACT or the NORM dataset. r3 has 14 POSSIBLE pairs for NORM `target`.

Use only these files; you cannot run code.

Check each of the following:
1. **Instrument fidelity:** does each version implement its docstring? Do r2 and r3 change only what they declare? Check in particular:
   - the exec-based composition of r2 / r3 on top of r1 (does it really re-bind `coding`, `rows`, `sect`, `lin`?);
   - the R06 / R07 merge;
   - the mapping application;
   - the r3 field drops.
2. **Were the r2 / r3 fixes specified from diagnostics rather than tuned toward a result?** Is dropping v1_t (a duplicate concept) and v1_s (circular in NORM) justified? Is any **other** duplicate or circular field still present, e.g. `v1_a` vs `actor`, or `v1_k`?
3. **Recompute** from the diagnostic and the inputs:
   - the ACT finding "no same-operation opposite-polarity pair" (count the positives and negatives per operation);
   - the NORM START-EXECUTION pairs.

   For the 14 NORM target POSSIBLE pairs: which fields are unknown, and does filling any of them from the **sources** (not by inference) look feasible?
4. **Interpretation.** Is it correct to conclude that:
   - (a) **act-level guards are NON-IDENTIFIABLE in this corpus** (no refused acts of the same operation), and not falsified?
   - (b) **NORM target-indexing is POSSIBLE but not strict**?

   Or do the results reflect remaining instrument defects? List any defect that could hide a strict witness.
5. What exactly does this chain establish and not establish, as input to an identifiability comparison between a rule-making hypothesis and a broad governance hypothesis?

Classify each issue as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({fidelity: {r1, r2, r3}, fixes_justified, remaining_duplicates_or_circular: [...], act_polarity_recount, norm_possible_pairs: {n, blocking_unknowns, fillable_from_sources}, interpretation: {act_nonidentifiable, norm_target_possible, hidden_by_defect: [...]}, establishes, does_not_establish, disagreements: [...]}).
