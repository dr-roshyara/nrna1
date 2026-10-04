# Task (frozen): V1a, reading robustness of D1 (formal)

Use ONLY:
- `FORMAL-THEORY-R4.md`: the reviewed formal theory;
- `HYPOTHESES.md`: the frozen hypothesis text;
- `R6-VALIDATION-MATRIX.md`: rows V1a and V1b.

No commands, no web.

**Background:** R4 proves D1 (Prop 4.1) for arbitrary **frames**, but only for **one reading** of each hypothesis clause (Def 3.1, Rem 3.1a/b, Lem 3.7). V1a asks: **under which alternative, reasonable readings of HYPOTHESES.md does D1 still hold?**

## 1. Readings
List alternative readings. **Each must be derivable from HYPOTHESES.md**, with the HYP phrase quoted. Include at least:
- **R-a:** B-chg target-tied (τ(w) = τ);
- **R-b:** B-conf existential ("some work conforms");
- **R-c:** R-b with B-recW dropped;
- **R-d:** D-st⁺, the biconditional (Lem 3.7);
- **R-e:** D-chg without the capability condition;
- **R-f:** B-recW dropped.

Add **at most 3** further readings, only if HYP clearly licenses them. Combinations matter only where they interact.

## 2. Lemma M (reading monotonicity)
State and prove a lemma giving a **sufficient** property M of a clause reading under which R4's D1(i) construction still works. That construction is h* := h[σ ≡ p, f ≡ ∞], with everything else unchanged; check it against R4's actual proof. Under M:
- D1(i) holds for the modified classes, with the same proof;
- (ii) and (iii) also hold, or the lemma says exactly which part survives.

Be precise about what changes when a reading **alters R_B itself** (e.g. R-c, R-f).

## 3. Classification
For each reading, prove **M**, or show **not-M**.
- Not-M is shown by exhibiting where the construction breaks.
- **Not-M does not mean D1 fails.** It means the proof does not transfer.

## 4. Sealed predictions for V1b (the non-M readings only)
For each non-M reading, predict, with reasoning, whether in R5's universe U (FORMAL-THEORY-R4 §7.1, with the reading substituted):
- D1(i) holds;
- (ii) holds;
- (iii) holds.

If not, predict the **least counterexample's characteristics** (not its exact code). Where U is too small to test a reading (e.g. R-a needs two targets), **say so: UNTESTABLE IN U**.

## 5. Planted positive controls for V1b
For each non-M reading, a planted case where the enumerator **must** detect a failure or separation. For example:
- the frame U₅ (A5 broken) under the reading: D1 must fail;
- O₁ (sees σ): it must separate wherever the reading leaves any σ-difference between the classes.

State exactly what output proves the control was detected.

## Output
Write **V1A-LEMMA.md** (the readings, Lemma M with proof, the classification, a ledger of statement labels) and **V1B-PREDICTIONS.md** (the predictions and the planted controls; this file will be **sealed and withheld from the V1b implementer**).

**Rules:**
- Label every statement (definition / lemma / proposition / conjecture).
- No coding-dependent claim.
- No change to R4.
- If a reading would require revising R4, **say so explicitly; do not revise**.
