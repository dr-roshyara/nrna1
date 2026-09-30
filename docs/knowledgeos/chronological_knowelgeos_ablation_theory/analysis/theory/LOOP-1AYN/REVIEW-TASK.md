# Independent review of a vocabulary normalization (frozen)

A worker mapped free-text values (VALUES.json) to fixed vocabularies (TASK.md); the output is MAPPING.json. Context is in RECORDS.json. Use only these files.

Check every mapping:
1. Is it faithful to the value's meaning **and** its span?
2. **Over-merging:** were distinct meanings collapsed into one code (e.g. plan vs execute; the Chief vs the ARB; adopt vs hold)? That would manufacture matches.
3. **Under-merging:** were equal meanings split?
4. Is UNMAPPABLE used honestly?
5. Is any target identifier invented, i.e. not present in the value?

Give corrected codes for every disagreement.

Report:
- the number of disagreements;
- the over-merges;
- the under-merges;
- whether the mapping, as corrected, can be used to pair records **without manufacturing contrasts**.

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({n_items, n_disagreements, corrected: [{record_id, field, worker, reviewer, class, why}], over_merges: [...], under_merges: [...], usable_for_pairing: bool, caveats: [...]}).
