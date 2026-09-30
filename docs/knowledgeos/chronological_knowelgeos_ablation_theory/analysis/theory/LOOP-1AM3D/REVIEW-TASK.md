# Independent review (frozen)

Researcher A answered `TASK.md` from `SOURCES.md` (in `ANSWER.json` / `ANSWER.md`). Use only these files.

Check each of the following:
1. Only permitted evidence was used.
2. Every quote is verbatim, and supports the value it is attached to. Check `item_kind`, `actor`, `conformance`, `outcome` and `ground` individually.
3. Nothing was silently inferred. Pay particular attention to:
   - conformance inferred for P from the *absence* of a collapse statement (that would be a silent default);
   - item_kind equality inferred where the text distinguishes the items;
   - two acts from one decision treated as independent.
4. No category confusion:
   - the kind of the object vs the kind of the act;
   - held vs withdrawn vs not accepted;
   - accepting an item vs accepting a report about it;
   - the ground of the hold vs a later remedy.
5. Any missed quote.
6. Whether the witness classifications follow the frozen rule in §2. Recompute them under your own corrected coding.
7. The strongest alternative reading that would change a classification, and whether the text rules it out.

Classify each disagreement as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive. Do not average.

Write **REVIEW.md** (summary · per-field findings · recomputed classification · alternatives · disagreement register · what can and cannot be established) and **REVIEW.json** ({pairs_A, pairs_reviewer (each with witness_conformance and witness_item_kind), strict_conformance_pairs_reviewer, clusters_reviewer, disagreements: [...]}).
