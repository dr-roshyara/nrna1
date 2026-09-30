# Independent review (frozen)

Researcher A anchored 36 events to verbatim spans (`TASK.md`, `EVENTS.json`, `SOURCES.md`); the output is ANSWER.json / ANSWER.md. Use only these files.

Check each of the following:
1. **Verbatim.** Is every span character-for-character present in SOURCES.md, under the stated heading? List any that are not.
2. **Status.** Is each ANCHORED span really specific to one event? Is each UNANCHORED truly absent? Is each SHARED one correctly classed as MERGE vs DUAL-SPAN?
3. **Description additions.** Where the description adds a count, a target or an outcome absent from the span, is that flagged?
4. **The authority pairs (a) and (b), the most important check.**
   - Are both members anchored to distinct spans?
   - Do the spans differ **only** in the acting authority?
   - Is each member an attested act, performed or refused?
   - Is the "declined" Chief adoption stated in the source as an act that was refused, or only as a rule about who may adopt?

   Quote the spans. Classify each pair as: STRICT (on spans) · WEAK · FAILS.
5. **Count** the distinct records after merges, and the UNANCHORED count.
6. The strongest alternative reading for any pair verdict.

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({verbatim_failures: [...], status_disagreements: [...], distinct_records_reviewer, unanchored_reviewer, authority_pairs_reviewer: {a: {verdict, quotes, reason}, b: {...}}, disagreements: [...]}).
