# CHANGELOG r1: V1A-LEMMA.md and V1B-PREDICTIONS.md (r0 → r1)

Scope: TASK.md (frozen), items 1–3 only. Inputs: r0 `V1A-LEMMA.md`, `V1B-PREDICTIONS.md`; `REVIEW.md` / `REVIEW.json` (PASS_WITH_LIMITATIONS, 0 blocking); `FORMAL-THEORY-R4.md`; `HYPOTHESES.md`; `R6-VALIDATION-MATRIX.md`. No command was run. **R4 is not changed.** Existing statement ids are unchanged; new statements have new ids. Where an id's label changed (Rem → Lem), the number is kept.

## Item 1: controls robust to the reporting convention (V1B)

| Change | Where | Review item |
|---|---|---|
| New **Def P0.3**: named output fields (`member`, `iS`, `K4b`, `iiO_c`, `iiiL`, `image`, `diff`), and the rule that a field evaluated at a named history does not depend on which counterexample is least | V1B §0 | REVIEW §5 (C-h defect); nonblocking 3, 12 |
| **C-h rewritten** as a per-history evaluation at I7: `[R-h|U] iiiL(I7) = false`, `[𝐑₀|U] iiiL(I7) = true`, witness W7 named in full with `member(H-B^h, W7) = true` and O(W7) = O(I7). "Reports I7 as a counterexample" removed | V1B Def C-h, Conj C-h.R | nonblocking 3 |
| **C-U5 for R-h rewritten** as a per-history evaluation at the named history H_U5h, in U (`iiiL = true`) and in U₅ (`iiiL = false`). The r0 parenthetical conjecture about the least U₅ counterexample is **removed** (it was a least-counterexample claim inside a control) | V1B Conj C-U5.R | nonblocking 12 |
| Every control now states exact field = value outputs (C-U5, C-O1, C-c, C-d, C-g, C-h, and the new C-i, C-j, C-g⁺) | V1B §2 | TASK item 1 |
| **C-O1 marked not diagnostic of the reading** for R-c, R-d and R-h (its outputs equal R4's K8.1); validity for these readings rests on C-U5 and the reading-specific control | V1B §2.1 | nonblocking 12 |

## Item 2: missing readings M1–M3 (review `readings_check`)

| Reading | HYP licence | Class | Proof / break point | V1b |
|---|---|---|---|---|
| **R-i** (M1): B-rec := E ≠ ∅ | "Both hypotheses accept that norm-making acts exist" (R4 Lem 3.6) + "expected to carry … both" defeasible for both halves | **not-M** (RB2 fails) | V1A Lem V3.13, V3.14: break at h with an unreferenced act and Ref = ∅; base pair fails at o = ∅ | sealed predictions (§1.5, P1.10, P1.11), control C-i, C-U5 and C-O1 outputs; o = ∅ **UNTESTABLE IN U** |
| **R-i′** (M1): no B-side record clause | as R-i, read as a joint statement | **not-M** (RB2 fails) | V1A Lem V3.13, V3.14 | §1.5, P1.12, C-i |
| **R-j** (M2): D-chg ∧ Chg ≠ ∅ | "norms change **only** through such acts", read descriptively (R-g's parallel) | **not-M** | V1A Lem V3.15, V3.16: quiescent histories have Chg = ∅; (ii)'s h_v half fails for every pair; inconsistent at N = 0 | §1.6, P1.13, C-j, C-U5, C-O1 |
| **R-g⁺** (M2): R-g ∧ an act-made change | "through norm-making acts **and** in response to work", both conjuncts descriptive | **not-M** | V1A Lem V3.17 (as R-g) | §1.7, C-g⁺, C-U5, C-O1 |
| **R-k** (M3): B-chg's act disjunct tied to status or capability | H-B's "norm-making acts" read through H-D's qualifiers (weak) | **M**, not T | V1A Lem V3.18: (iii) literal fails at I₇ | **REJECTED** (V1A Rem V3.18a): imports H-D's qualifiers into H-B; not predicted |

Consequential edits needed to keep the documents consistent with the added readings (no other content changed): Def V1.1 note on empty clause sets; Rem V1.4 (M1–M3 now added); Rem V2.7 (R-i, R-i′ fail RB2; RB2 is now exercised); Table V3.0 rows; Prop V5.1 rows; Rem V5.2; Conj V5.3 restricted to (i) (R-j's (ii) failure is not of the conjectured form); Lem V4.1(a) includes R-k; ledger rows; V1B scope line, Def P0.1, P1.9, §3 consequences.

## Item 3: consistency and rigour (V1A unless stated)

| Change | Where | Review item |
|---|---|---|
| **R-d revision flag** set to "yes, if adopted" (SC1/SC2 in 4.1(i), (ii); ≈_{σ,f,Cap}; Lem 4.2; Cor 4.3), consistent with R-c. A single **revision criterion** is stated at the top and applied to every row | Table V3.0; Rem V3.7a (rewritten); header | nonblocking 4 |
| **Lem V3.9(d) proof fixed:** the failing equality is identified as the **middle** one (and the last fails too); the **Cap-empty witness** h_b ∈ H-B^g (N-step Lem 3.5(b), Cap = ∅) is given and verified; the base-frame claim is "Cap contains an iss pair and an adp pair", and the U-specific form {(x,iss),(x,adp)} is moved to V1B. Hypotheses stated for N ≥ 1, P ≠ V, 𝒜 ≠ ∅ | Lem V3.9(d); V1B §1.3 Cap row basis | nonblocking 5 |
| **R-g at N = 0** reframed: the reading is **inconsistent** (H-B^g = ∅); not counted as a D1 failure; Cor 4.1b dropped from R-g's D1-failure list and replaced by the parameter condition N ≥ 1 | Lem V3.9(b); Table V3.0; Rem V3.10a; Prop V5.1; V1B P1.9 | nonblocking 6 |
| **Lem V3.4(c):** note that A6 is unused ((ii-c)'s proof does not use it) | Lem V3.4 proof | nonblocking 10 |
| **"R-d ∘ R-e no interaction" qualified:** no interaction for the image equalities; the S-form failure set does interact (h₁ escapes under D-chg^e); proof given | Lem V4.1(c) | nonblocking 9 |
| **Wording:** R-b ("a weak", not "the weakest", non-vacuous reading); R-c (D-rec comes from "primarily a record of norm-making", not from "both accept acts exist", which is E ≠ ∅; the asymmetric reading of "expected to" disclosed); R-e (derivation marked strained, kept because the matrix names it) | Def V1.3 rows R-b, R-c, R-e (R-f's row points to the corrected R-c) | nonblocking 8 |
| **P1.8 parenthetical** replaced: P1.1's history is the least member of R_B^c (the record base of R-c ∘ R-d) and fails because w1 is absent | V1B §1.8 | nonblocking 13 |
| **Labels:** Table V3.0 labelled Rem in text; Rem V4.1 → **Lem V4.1** with proof; Rem V3.4b → **Lem V3.4b** with proof; each V1B control is a Def with a ".R" Conj for its required output; the scoring rule is **Def P3.1** | V1A §3, §4, ledger; V1B §2, §3 | nonblocking 11 |

## Not changed (outside TASK items 1–3)

- R4: nothing.
- The lemma-numbering sentence (review §6, "R-e's V3.5 precedes R-d's V3.6") is not in the TASK list and is left as is.
- **Found during r1, not changed (needs a human decision):** Conj **P1.7** (least counterexample to (iii)-L under R-h) appears to be wrong when K7's third constraint is applied literally. Take h₀: σ = (q, q); a1 iss, d = 0, v = p, prep 0, f = ∞; a2 absent; Cap = ∅; w1 at t = 0, referenced; Ref = {(ρ1, a1), (ρ1, w1)}; r = 0; c = c1. h₀ ∈ H-D ∩ R_B^h and w1 violates. σ′ must be (p, q), with a change at 1 to q. No act of h₀ can enter at 1 with value q (a1 has v = p), so K7 requires a2 added with f = 1, and an added a2 is unreferenced, which breaks B-rec^h. So h₀ appears to be a counterexample, and it precedes P1.7's history in R-1 order (a1's v = p < q). If so, P1.7's bold features (w1 at t = 1; only act of value q) would be missing from the reported least counterexample, and the R-h (iii) row would be scored as **falsified**. r0 (and the review's check, "with w1 at 0 the work disjunct repairs it") left out K7's third constraint. P1.7 is sealed and outside this task, so it is not edited. The r1 controls do not depend on it: H_U5h was chosen so that its U-side repair needs no added act.
