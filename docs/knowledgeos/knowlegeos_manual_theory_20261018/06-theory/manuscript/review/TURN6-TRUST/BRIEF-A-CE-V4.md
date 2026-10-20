# Turn 6, commission A: §9.13 v4 in full

*Reviewers A1 = Opus, A2 = Sonnet. Read `COMMON.md` first; its rules bind.*

## Object

`W/06-theory/manuscript/chapter_09/09.13-the-evaluator.md` as it now stands (v4), **in full**, not only the changes. The history:
- turn 4 reviewed v1 and settled v2;
- turn 5 commission A reviewed v2–v3 and settled v4 (`review/TURN5-CONSISTENCY/SETTLEMENT.md` §2);
- the Principal's answers after turn 5 are in that settlement's §6 and in the decision record, addendum 2026-10-20T19:03:44Z.

Also review:
- the model `W/tmp/tools/ce_model.py` v4 and its run `ce_model-run-20261020-v4.txt` (run `python3 -I ce_model.py`, about 15 s);
- CON-0191;
- the dependants §9.07 result 2, §9.08 Lemma D, §9.09 T1, §9.10 Q1/Q3 and the vacuity table, and §9.12's C1 lines.

## Resolved when

Every claim in §9.13 v4 is sound as stated or corrected. In particular:
- **R1** (totality), **R2** (forced values; the derivation of U's causes), **R3** (the lemma, steps 0–8, and its tightness form) and **R4–R5** are proofs or are corrected;
- **the refusal reasons** (§9.13.3) are a definition that matches `u_reasons`, `ce_star_explained` and `act_explained`, and covers every U on D;
- **D's field list, citation rule and design limit**, and **Θ_f's table**, match the code;
- **the Principal's decisions** (keep D, with its reason; indirect self-cancelling within decision 3) are applied as the decision record states them, no more and no less;
- **the turn-5 settlement rows A1-01 … A2-12** are each actually met.

## Questions in particular

1. The lemma's step 8 now derives KK ≤ WF from "WF is a Fitting fixed point". Is that derivation valid?
2. R2's derivation of U's causes (every U rests on a U; the walk reaches a cycle; on D the cycle has a negative edge). Is it valid, and does the model's walk find every such cycle (checks 15, 15b)?
3. Is the general refusal-reason definition coherent?
   - Does "self-cancelling: an annulment whose target is among the grants its own authority rests on" match the decision's "any link of its own authorization chain"?
   - Is any U case left without a named reason?
4. Is the decision's reason for keeping D ("authorization after the fact"; "checkable at each new entry") consistent with the rest of §9.13? Consider backdated grants admitted by position, and forward targets.
5. Are the Θ_f rows (root annulment; conjunctive P; revocation scope) consistent with the operator and the code?
6. Are the dependants consistent with v4?

## Report

`W/06-theory/manuscript/review/TURN6-TRUST/REPORT-A1.md` (Opus) or `REPORT-A2.md` (Sonnet). Use finding IDs A1-nn or A2-nn.
