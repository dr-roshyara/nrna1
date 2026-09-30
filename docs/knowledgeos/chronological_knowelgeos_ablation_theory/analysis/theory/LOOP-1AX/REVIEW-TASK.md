# Independent review of ACT / NORM coding (frozen)

A coder applied `SCHEMA-r2.md` to 36 span records (`TASK.md`, `RECORDS.json`, `SOURCES.md`); the output is CODING.json / NOTES.md. Use only these files; you cannot run code.

Check **every** record:
1. **Rule R-A1:** was any ACT inferred from a NORM? E.g. an "authorized to begin" span coded ACT_OCCURRED for the beginning. List every violation.
2. **R-A2:** does every ACT_REFUSED / ACT_WITHDRAWN have an identified proposal or attempt in the span or its section? Quote it.
3. **R-A3:** is every register row that performs a norm-making act coded ACT_OCCURRED for that act? Is its stated content additionally coded as NORM where present?
4. **Independence of the components:** is either component suppressed because the other was coded (e.g. NORM set to NONE because the ACT was coded)?
5. **Epistemic status:** is any INFERENCE labelled SOURCE_FACT? Is any premise missing?
6. **Quotes:** verbatim, within the span or its section.
7. **UNKNOWN:** is it used where undecided, and not used where the span decides?

Give your corrected values for every disagreement.

Then report:
- the counts of the ACT dataset (act.state ∉ {NONE, UNKNOWN}) and the NORM dataset (norm.deontic ∉ {NONE, UNKNOWN}), and their overlap, under **your** corrected coding;
- the records where you are uncertain.

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({violations_RA1: [...], violations_RA2: [...], violations_RA3: [...], suppression: [...], status_errors: [...], corrected: [{record_id, field, coder, reviewer, class, quote}], datasets_reviewer: {act_n, norm_n, overlap_n, act_ids, norm_ids}, uncertain: [...], disagreements: [...]}).
