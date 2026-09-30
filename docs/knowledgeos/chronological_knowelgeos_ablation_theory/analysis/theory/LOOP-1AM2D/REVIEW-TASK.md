# Independent review (frozen)

Researcher A answered `TASK.md` from `SOURCES.md` (in `ANSWER.json` / `ANSWER.md`). Use only these files.

Check each of the following:
1. Only permitted evidence was used.
2. Every quote is verbatim, and supports the value it is attached to. Check `raise_kind` (EXTENSION vs NEW), `actor`, evidence and its unit, and `recorded_exception` individually.
3. Nothing was silently inferred. Pay attention to:
   - an actor inferred from the register's host;
   - evidence inferred from context.
4. No category confusion:
   - a clarification vs a new standard;
   - a candidate vs an adopted clause;
   - not-raised vs deferred;
   - a decision vs a recommendation.
5. Any missed event.
6. Whether the verdicts follow the frozen rule in §4. Recompute them under your own corrected coding, including the given prior events.
7. The strongest alternative reading that would change a verdict, and whether the text rules it out.

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive. Do not average.

Write **REVIEW.md** (summary · per-event findings · recomputed verdicts · alternatives · disagreement register · what can and cannot be established) and **REVIEW.json** ({verdicts_A, verdicts_reviewer, confound_broken_A, confound_broken_reviewer, disagreements: [...]}).
