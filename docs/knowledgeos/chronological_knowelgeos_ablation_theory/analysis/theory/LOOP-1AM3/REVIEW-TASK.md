# Independent review (frozen)

Researcher A answered `TASK.md` from `SOURCES.md` (in `ANSWER.json` / `ANSWER.md`). Use only these files.

Check each of the following:
1. Only permitted evidence was used.
2. Every quote is verbatim, and supports the value it is attached to. Check `kind`, `conformance` and `ground` individually.
3. Nothing was silently inferred. Pay particular attention to:
   - conformance inferred for P from the *absence* of a collapse statement (that would be a silent default);
   - kind equality inferred from both objects being "rulings" where the text distinguishes their kinds.
4. No category confusion:
   - the kind of the object vs the kind of the act;
   - held vs refused;
   - the ground of the hold vs a later remedy.
5. Any missed quote.
6. Whether the witness classifications follow the frozen rule in §2. Recompute them under your own corrected coding.
7. The strongest alternative reading that would change a classification, and whether the text rules it out.

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive. Do not average.

Write **REVIEW.md** (summary · per-field findings · recomputed classification · alternatives · disagreement register · what can and cannot be established) and **REVIEW.json** ({witness_conformance_A, witness_conformance_reviewer, witness_kind_A, witness_kind_reviewer, disagreements: [...]}).
