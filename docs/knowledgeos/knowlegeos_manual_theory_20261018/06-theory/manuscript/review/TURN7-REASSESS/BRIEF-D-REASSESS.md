# Turn 7, commission D: §9.16, the reassessment, and the Ext→Src corrections

*Reviewers D1 = Opus, D2 = Sonnet. Read `COMMON.md` first; its rules bind.*

## Deliverable

**The Principal's instruction:** "Reassess what depends on these, including the history and trust results and where F22 and K4 belong." Also, from turns 2–3: "run the corpus searches in turn 7, the reassessment, and correct Ext to Src where the corpus says the same."

## Object (all new; review in full)

1. `W/06-theory/manuscript/chapter_09/09.16-reassessment.md`:
   - R1, the dependency table;
   - R2, records merged without a serialization point, with its proof, the race, and model check 18;
   - R3–R4, where K4 arises and where F22 and K4 belong;
   - R5, tombstones;
   - R6, the F36 recommendations;
   - R7, a primitive/world-assumption test;
   - §9.16.4–9.16.9.
2. **The Ext→Src corrections:**
   - §9.11.6: three rows and the closing sentence, plus the References table;
   - §9.12.8: the Searle and bitemporal rows;
   - §9.10.9: the valid/transaction time row and the replay row;
   - §9.8.4: the *Src (turn 7)* paragraph;
   - the reference rows in those sections.
3. **The turn-7 pointers:** §9.06 item 4; §9.10 O1 (Identity); §9.11 R1 step 5; §9.13.2 (order line); §9.15 (G4, K2, K4).
4. `W/tmp/tools/ce_model.py` v7: `Record(tie=…)`, `linear_extension`, check 18.
5. CON-0194; EXT-084–086; the THEORY-MAP rows F22, F26, F36; the Ch. 9 index row.

**Corpus first:** the scan rows and topic-full rows of 2026-10-20T20:09–20:16Z in `W/tmp/corpus-reading/READ-LOG-CHRONO-20261018.tsv`. The eight sources read whole are S0943, S0990, S0916, S0534, S0915, S0969, S1519 and S0991.

## Resolved when

- **R1:** every row's status is right.
- **R2:** (a)–(d) are proofs or are corrected. In particular, does CE* read order anywhere except the key and D's citation rule? Is the causal premise enough for (a)? Is the race a genuine countermodel to order-independence under position ties?
- **R3:** no law of the book that needs a uniqueness across applications is missing.
- **R4:** F22's "plane iff" is derived, not asserted.
- **R5:** the claim that a tombstone changes no answer is sound.
- **R6:** the recommendations are labelled as recommendations, with reasons that hold.
- **R7:** the definition is coherent, and the boundary cases and the rival are fair.
- **The Ext→Src corrections:** each Src quote says what the book says it says, and nothing labelled Src goes beyond the corpus.

## Questions in particular

1. **R2 (b).**
   - Find a record pair and two linear extensions that give different answers under ties by identity.
   - Is "ties by content" well-defined when two entries have the same act time and the same content-derived identity? (They are then the same entry: check.)
2. **R2 (d).** Is "a cut across records is in D iff it is closed under citation" right, given D's other rules? Is it exactly Lemma D's condition?
3. **R3.**
   - Is "plural bases are no violation" right for CE*?
   - Does any law in §9.11's table (laws 1–31) bound a count across applications besides 9b/24 and 26?
   - Is law 21 a uniqueness?
4. **R4.** Is "(C) delegated otherwise" true if K4-d is chosen for a law, given that detection's aggregate must also be shared?
5. **R5.** Do Φ and the currency rule read any field a tombstone drops? What of `content`, `ok` and `removed`?
6. **R7.**
   - Does condition (ii) make every world assumption a non-primitive by definition, and so beg the question?
   - Is "read by some reference definition of Req" well-defined while Q4 has none?
7. **Ext→Src.** Are the informal corpus statements fairly called "the same" as the outside works they sit beside? Example: S0943's "Something bad never happens." beside Alpern and Schneider's definition.
8. **Prior work.** Are EXT-084–086 described within their public abstracts? Is anything closer missing, for example invariant confluence or coordination avoidance? Check only through public pages.

## Report

`W/06-theory/manuscript/review/TURN7-REASSESS/REPORT-D1.md` (Opus) or `REPORT-D2.md` (Sonnet). Use finding IDs D1-nn or D2-nn.
