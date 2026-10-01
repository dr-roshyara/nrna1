# Task (frozen): a targeted repair of SCHEMA-r3 (r1)

Use ONLY the files here:
- r0: `SCHEMA-r3.md`, `THEORY-CHOICES.md`, `RATIONALE.md`;
- its review: `REVIEW-r0.md` / `.json`, verdict FAIL;
- `SCHEMA-r2.md`, `GAPS.md`, `RECORDS.json`, `SOURCES.md`, `HYPOTHESES.md`.

No commands, no web. Repair **only** the following:

1. **B1 (blocking), symmetry of R-A3 scope.** Self-performed *negative* dispositions (refuse, withdraw, reverse) must be scoped **exactly like positive ones**. Either:
   - both are acts only for R-A3's closed operation list in register rows; or
   - both are tagged SELF_PERFORMED_OUTSIDE_RA3 with `act.state` NONE.

   Choose the symmetric option that changes r2 least. Disclose the choice. Update TC-1 so that 1a/1b/1c describe the negative case correctly.
2. **B2 (blocking), G3 on R01.**
   - A proposal or attempt (R-A2) must be **stated** in the span or section (SOURCE_FACT). An inferred proposal gives UNKNOWN.
   - The refused operation X is the complement of the refusal verb.
3. **Minimality.** Revert every non-minimal change listed in `REVIEW-r0.json` `non_minimal_changes` (D7 lexicon, R-X1, R-A5 beyond G3/G4, D11 beyond G2, D8(ii) generalization, the R-S1 counting clause) to **r2 behaviour**. Keep only what G1–G6 need. Where a reverted extension was needed to keep neutrality (N4 / N5), list it in THEORY-CHOICES instead.
4. **Conflicts.** Resolve all 8 `conflicts`:
   - the D3 circularity;
   - the D11 vs R-N1 vs D8(i) priority;
   - D12 vs no-re-levelling (N2);
   - R-A1 vs R-A2 wording;
   - the D4/D6 split;
   - the merge rule vs the closed list;
   - a NORM tie rule;
   - an operation tie-break, with "highest step" defined.
5. **Selected nonblocking items:** N3 (tag bare authorize performatives); N6 (define REPORTED vs SELF_PERFORMED for rows reproducing another actor's ruling); the D13 two-operation rule; negation handling only as needed for R01.
6. **THEORY-CHOICES:** keep the tag-and-compute-all-alternatives approach. Add the choices the review found missing.

**Rules:** do not choose any rule to reproduce an existing coding. Mark changes `[r3-Gn]` / `[r1-fix-<id>]`.

Write the repaired **SCHEMA-r3.md**, **THEORY-CHOICES.md** and **RATIONALE.md**, plus **CHANGELOG-r1.md**.
