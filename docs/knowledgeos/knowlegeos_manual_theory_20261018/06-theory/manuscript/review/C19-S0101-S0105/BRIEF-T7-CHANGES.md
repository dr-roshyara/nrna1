# C19, commission T: all changes of the turn-7 settlement, in full, and the application of the Principal's turn-7 answers

*2026-10-20. By the Professor. The Principal: "C19 (S0101–S0105): its review includes a separate commission for all changes of the turn-7 settlement (§9.13 v7, §9.14 v4, §9.15 v4, §9.16 v2), in full." The Principal's answers after turn 7 were applied before C19 (§9.13 v8, §9.15 v5, §9.16 v3); "All of these are reviewed in C19". Reviewers T1 = Opus, T2 = Sonnet, independently. **Mode:** topic review (`03-methodology/REVIEWER-INSTRUCTIONS.md`), with no blind step: these are Ch. 9 sections, not sources.*

**The rules of `review/TURN7-REASSESS/COMMON.md` bind:**
- access;
- challenge by type;
- verification marks;
- method;
- report format.

Read that file first. Where it names turn 7, read "this commission".

**Deliver your report once, through the hand-back.** Do not write a file. The Professor saves it verbatim and records its sha256 at hand-back.

## Object, in full (read each file whole)

1. `W/06-theory/manuscript/chapter_09/09.13-the-evaluator.md`, **v8**.
2. `W/06-theory/manuscript/chapter_09/09.14-when-pairs-suffice.md`, **v4**.
3. `W/06-theory/manuscript/chapter_09/09.15-the-trust-boundary.md`, **v5**.
4. `W/06-theory/manuscript/chapter_09/09.16-reassessment.md`, **v3**.
5. The models:
   - `W/tmp/tools/ce_model.py` **v9**, run `ce_model-run-20261020-v9.txt` (about 40 s);
   - `W/tmp/tools/helly_check.py` **v4**, run `helly_check-run-20261020-v4.txt` (about 5 min; read the run rather than re-running, unless you need a check).
6. Also: the §9.11 F36 note; the §9.06 and §9.10 pointers; THEORY-MAP rows F22, F26, F30, F36; register rows CON-0191–CON-0194; EXTERNAL-SOURCES EXT-084–089.

**The settlement and the answers:** `W/06-theory/manuscript/review/TURN7-REASSESS/SETTLEMENT.md`, §1–§4 (the 65 rows), §6 (the questions returned) and §8 (the Principal's answers verbatim, and where they are applied).

## The changed theory, listed (the settlement's rows are the full list for v7, v4, v4, v2)

**§9.13:**
- **v7** (settlement §1):
  - the marks' support is now a closure under citations, annulments and endings (sound, model check 19; conservative);
  - the tombstone's premises (position, integrity result, a check on what remains);
  - I15's status is a recorded claim;
  - the tie note made conditional;
  - the inputs table;
  - reasons naming components;
  - R2's undefined list;
  - CON-0191.
- **v8** (the Principal's answers):
  - acts at the same act time are **unordered**: "ordered before" is U at ties; in the program reading, an atom o ← not o per tied pair, and ¬(e ∧ o) split into two rules; reasons name the pair; checks 10b, 15b, 15c;
  - **consumption** admitted as a kind that ends its grant for acts ordered after it, with an append guard preventing a second consumption; check 21;
  - **removal** is a recorded act (kind `remove`) that the tombstone names (`removed_by`); a mark when the removal is not authorized; check 22;
  - tombstones keep every field D requires;
  - the wider marks kept;
  - two of the Professor's choices: signed-as-other requires recorder ≠ actor; marks for an act's authorization (check 22b).

**§9.14 v4** (settlement §2):
- the exact formula h = max(2, sup |J| + t(J)) for thresholds with forbidden values on total orders;
- some partial orders only (check 8);
- the coupling statement and its boundary;
- R5 (c)'s premises;
- the rivals kept to their abstracts;
- degenerate cases.

**§9.15:**
- **v4** (settlement §3):
  - the criterion reworded;
  - K2, K4, K5 restated;
  - G1, G3, G4 restated;
  - R3's scope;
  - R4's signed-as-actor row as one direction with L3 per entry and L8;
  - the L1a cell;
  - R6 recast;
  - R8 (c).
- **v5:**
  - G4 with consumption admitted and single use prevented;
  - K2 under unordered ties;
  - K4 needed for single use and held acts;
  - R8 (c) under R7's provisional adoption.

**§9.16:**
- **v2** (settlement §4):
  - R2 with each application's record in D, and the identity and merge rule;
  - R3 by invariant confluence;
  - R4 as a sufficient condition;
  - R5 conditional;
  - R6 with law 26;
  - R7 revised;
  - Src corrections.
- **v3:**
  - **R3a**: decision 1 per law, with reasons (prevent single use and once-only handling of held acts; detect law 26, role exclusivity and acting under ended authority);
  - R2 under unordered ties;
  - R4 v3's consequence for F22, the Professor's derivation: (C) with kernel-provided coordination points;
  - R5 with removal acts;
  - R6 and R7 adopted.

## Resolved when

- Every claim in the four sections is sound as stated, or corrected.
- The 65 settlement rows are each actually met.
- The Principal's answers are applied **as worded, no more and no less**.
- The Professor's own choices are sound and marked as his.

## Questions in particular

1. **Unordered ties (§9.13 v8).**
   - Is Φ still monotone? Do KK and WF still agree on D?
   - Does R3's lemma (no citation cycle ⇒ KK = WF) survive the o ← not o atoms, which are negative self-loops?
   - Is the program split of ¬(e ∧ o) correct in strong Kleene?
   - Find a record in D where the two routes differ.
2. **Consumption.**
   - Is "a consumption ends its grant for acts ordered after it" the right reading of single use (§1.13.2's CONSUMED)?
   - Does the append guard prevent what decision 1 says must be prevented?
   - What does CE* answer when two consumptions arrive by merge, against the guard?
3. **Removal acts.** Is the removal's authorization evaluated correctly? Can an unauthorized removal change any value? Is "removal not authorized" the right consequence?
4. **§9.16 R3a.**
   - Does each row's reason follow the Principal's criterion ("effects cannot be undone by a later recorded act")?
   - Is the line between "effect on standing" and "effect in the world" applied consistently? Single use's standing effect is also corrected by evaluation (the second consumption is F).
5. **§9.16 R4 v3.** Is "(C) with kernel-provided coordination points" derived from §9.6's definitions and the decisions, or asserted? F22 was reserved for the Principal: is the derivation offered as a consequence, not as a decision?
6. **§9.15 v4/v5.** Does every K row and every L row meet exactly one clause of the reworded criterion? Is R4's one-directional proof valid with L8?
7. **§9.14 v4.** Is the formula's derivation complete, including attainment, and the case J = X?
8. **The Professor's choices.** Are "signed-as-other requires recorder ≠ actor" and "marks for an act" technical or normative? If normative, they belong to the Principal.

## Report

Deliver through the hand-back:
- a verdict table;
- a findings table (ID T1-nn or T2-nn · finding · severity · evidence · correction · resolved when);
- proof-step tables for every derivation you judge adequate (R4);
- coverage.

End with a summary of at most 10 lines. Process independence, not personal independence, in one line.
