# Task (frozen): do the sources support the coded evidence values?

Use ONLY `SOURCES.md` (register row R-36 and the promotion matrix it adopts) and `CODED.json` (7 coded RAISE events from R-36, each with a coded evidence value `e`: '2+' for promoted items, '1' for not-promoted ones). No other path, no web, no commands.

For each coded event:
1. Identify the matrix item it corresponds to (# number and title). Quote it.
2. Quote the evidence the sources state for that item: slice / ticket / context counts, or the column entries.
3. Is the coded `e` **stated** by the sources, **derived** from them (say how), or **not supported**? Give the source's value and its **unit** (slices / tickets / contexts / occurrences).
4. Is the outcome (promoted / not promoted) stated in R-36 or in the matrix? Which one decides? Quote it.
5. Is the stated reason for each non-promotion **evidence-based** (a count below a bar) or **something else** (scope, domain-specificity, a prior ruling)? Quote it.

Then answer: **how many (promoted, not-promoted) pairs differ in stated evidence count and share every other stated property?** State the unit used.

Write **ANSWER.json** ({items: [{event_id, matrix_item, evidence_quote, coded_e, source_e, unit, basis: STATED|DERIVED|NOT-SUPPORTED, outcome_source, nonpromotion_reason_type}], evidence_pairs: {n, pairs, unit}, notes}) and **ANSWER.md** (labels SOURCE FACT / INFERENCE / UNKNOWN; list the ambiguities).

There is no expected answer.
