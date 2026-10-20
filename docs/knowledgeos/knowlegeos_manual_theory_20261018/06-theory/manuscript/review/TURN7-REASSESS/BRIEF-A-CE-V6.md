# Turn 7, commission A: §9.13 v6 in full

*Reviewers A1 = Opus, A2 = Sonnet. Read `COMMON.md` first; its rules bind.*

## Object

`W/06-theory/manuscript/chapter_09/09.13-the-evaluator.md` as it now stands (v6), **in full**. The history:
- turn 6 commission A reviewed v4 and settled v5 (`review/TURN6-TRUST/SETTLEMENT.md` §1);
- v6 applies the Principal's decisions after turn 6 (COMMON.md);
- turn 7 added one note on ties in §9.13.2.

Also review:
- `W/tmp/tools/ce_model.py` v7 and its run (checks 13, 15c, 15d, 17 and the `tie` parameter in particular);
- CON-0191;
- §9.12's I15 row.

## The changed theory, listed in full

**v5 (turn-6 settlement, commission A):**
1. Decision 3 is applied as "when U, the reason names the act and the link". Φ gives T with a second intact basis and F when the link is already defeated (R2; §9.13.5 row 6; check 15d).
2. A dangling citation puts the record outside D; it is no longer "F, the safe direction" (§9.13.2, §9.13.7; check 13).
3. D's citation rule is stated once: the append guard and the static test agree.
4. The reason for D is scoped to record order. Retroactivity in act time and forward targets are named as open (§9.13.10).
5. The decision record's wording for D is quoted verbatim, with its second reason. The incremental check for D_acyclic is stated as a difference of cost.
6. Refusal reasons:
   - the walk starts from U alternatives and follows only the U literals of U bodies;
   - strongly connected components are named;
   - a self-annulling act gets its own label, and a ring needs at least three acts;
   - the covers reason applies only on U alternatives;
   - check 15c tests soundness: 68 named acts, all U.
7. P's four boundary cases are stated in Θ_f; P is reserved.
8. "No citation cycle (which makes the program tight)"; n = non-root acts.
9. §9.09 T1 has the merge condition.
10. The lemma's step 8 names the greatest unfounded set; R2 (a) names the fixed point.
11. Check 3b enumerates the fixed points of mutual annulment.
12. S1761 is labelled Int.

**v6 (the Principal, after turn 6):**
- every entry requires a recorder and an authentication status (signed-as-actor, signed-as-other, unsigned) apart from the actor;
- removal is by tombstone: identity, kind and every field D requires are kept; the content is dropped; the record stays in D;
- Q1's output carries marks: "rests on transcribed entries", "rests on unsigned entries", "rests on removed content". "Support" is a superset of what the answer rests on;
- decision 3 is marked decided, with the root and unneeded-basis points.

**Turn 7:** a note in §9.13.2's order line: across applications, ties must be broken by content (§9.16 R2).

## Resolved when

- Every claim in §9.13 v6 is sound as stated, or corrected.
- The three decisions are applied as worded, no more and no less.
- D's field list, the tombstone rule and the marks match the code (`in_D`, `tombstone`, `support`, `marks`).
- The v5 rows are each actually met.

## Questions in particular

1. **Marks.**
   - Are the marks defined over what the answer **rests on** (the decision's words), or over a superset?
   - Is "support" well-defined for U and F answers, and for answers that rest on no entry (F by C1)?
2. **The tombstone.**
   - Does keeping "every field D requires" give the decision's "the fields citations rely on", no more and no less?
   - Is the integrity check defined on a tombstone?
   - Can a tombstone change any answer?
3. **I15.**
   - Is "signed-as-other" coherent for an entry whose actor and recorder are the same?
   - Do the defaults in the model hide any earlier check's dependence on the new fields?
4. **Decision 3.** Is "a cited basis the act doesn't need does not matter" met by the reason walk (reasons only from U alternatives)?
5. **Ties.** Does anything in §9.13 depend on position on ties beyond the key (t, s)?

## Report

`W/06-theory/manuscript/review/TURN7-REASSESS/REPORT-A1.md` (Opus) or `REPORT-A2.md` (Sonnet). Use finding IDs A1-nn or A2-nn.
