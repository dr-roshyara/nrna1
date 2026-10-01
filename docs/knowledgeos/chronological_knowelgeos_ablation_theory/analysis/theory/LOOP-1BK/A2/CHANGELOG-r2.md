# CHANGELOG-r2: FORMAL-THEORY-R4 r1 → r2 (minimal proof repair; human act F-LOG-0221)

| | |
|---|---|
| Inputs (only) | `FORMAL-THEORY-R4.md` (r1); `REVIEW-r1.md` / `REVIEW-r1.json` (FAIL, one blocking issue); `R3-CODING-INDEPENDENT.json`, `HYPOTHESES.md`, `H5.md`, `INSTRUMENT-FINDING.md` |
| Scope | Only the items in TASK.md: B-1 / RG-1, NB-1 to NB-6. Nothing else changed. Statement ids are unchanged; no statement was added, withdrawn or renumbered |
| Status | r2 is **not re-reviewed**. No commands were run, and the R5 enumeration was not executed |

## 1. Blocking item B-1 (RG-1): fix (a) chosen

**Fix used: (a), "define frames as subsets of 𝓗".** Fix (b), the preliminary steps, was **not** used.

*Why (a).* It is the reviewer's minimal fix. It removes the gap at its source, the definition of a frame, so all three proofs can use F1 and F2 of the starting history directly. It changes no countermodel.

*Change in Def 1.8.* r1 said "a frame is any subset 𝓗′ ⊆ 𝓢". r2 says "a frame is any subset 𝓗′ ⊆ 𝓗", so every member of every frame satisfies F1 ∧ F2. 𝓗 is now defined before frames.

*The check the task requires, now part of Def 1.8.* Every countermodel frame still qualifies:

| Frame | Where used | Subset of 𝓗? |
|---|---|---|
| 𝓗 | base pair; Lem 4.2; Cor 4.3; §4.5; Lem 5.2 on 𝓗; CM-A1, CM-A2, CM-A3a, CM-A3b, CM-A4a, CM-A4b, CM-A8 | yes (trivially) |
| 𝓗₅ | CM-A5 | yes: 𝓗 plus one axiom |
| 𝓗₇ | CM-A7(a), (b) | yes: 𝓗 plus one axiom |
| 𝓗₉ | CM-A9 | yes: 𝓗 plus one axiom |
| CM-A6 frame | CM-A6 | yes: it is 𝓗 for the parameters V = P = {p} (frames are relative to the Def 1.1 parameters) |
| U, U₅, U₆, U₇, U₉ (R5) | §7 | yes: §7.1 keeps only F1 ∧ F2 assignments |

So no countermodel, and no "violates only" or "others hold" column of §4.4, changes. Def 1.8 also records that A5, A7 and A9 stay consistent with the restriction. Every history these hypotheses put into 𝓗′ is in 𝓗: h[σ*, f*] keeps F2, fresh additions keep F2 (as in the proof of Lem 4.0a), and changes of only con or only d keep F1.

*Re-proved in full (§4.3, §5):*

| Statement | r1 step that failed | r2 proof |
|---|---|---|
| Prop 4.1(i′) | "each step satisfies F1" presupposed F1 in h | h ∈ 𝓗′ ⊆ 𝓗 satisfies F1. An induction over the five additions shows that each preserves F1: steps 1, 3, 4 and 5 do not touch E^iss, τ, v, f or σ, and step 2 adds an act with f = ∞. Each is in 𝓗′ by A7. Steps 1–3 leave O′ unchanged by A4 and steps 4–5 by A3. The result is in R_B′, then (i) applies. Both inclusions of the four-way equality are written out |
| Prop 4.1(iii) | Step 1, "by F1 in h" | F1 in h now holds by Def 1.8. Step 1's F1 check is written per act, and p ∈ P is fixed explicitly. Step 2 is an induction on the added acts (A7, A4), and the existence of x ∈ 𝒜 is derived from B-recW. B-chg, B-conf (re-argued directly: a non-Vio work point did not violate in h), B-rec and all three bullets of (iii) are re-checked |
| Lem 5.2 | "F2 still holds" presupposed F2 in h | F2 in h now holds by Def 1.8. A starting history h⁰ (h itself, or h[σ ≡ p, f ≡ ∞] in the H-D cases) is shown to be in 𝓒, to have the same O′, and to satisfy F2. Each of the three steps (drop con(a); set d(a) := r(ρ); set con(a) := ρ) is checked for F2 act by act, then placed in 𝓗′ by A9, and given O′ by A3, A8, A3. Class membership is re-checked for all four 𝓒 |

*Effect on scope (disclosed).* The generic statements (Prop 4.1, Lem 3.7, Lem 4.0b, Cor 4.3, Lem 5.2, Def 5.1) now range over frames ⊆ 𝓗 only. The review's concrete case, 𝓗′ = 𝓗 ∪ {h₀} with h₀ violating F1, is no longer a frame. No R4 statement used a frame outside 𝓗. The base-case results (Cor 4.1b, Lem 5.2 on 𝓗) are unchanged, and so are all statements, hypotheses A1–A9 and countermodels. The review's remark that R5 cannot detect B-1 (§7 of REVIEW-r1) no longer applies, because every frame is now an F1 ∧ F2 frame. §7.3 is unchanged.

## 2. Non-blocking items

| Review id | Where | Change |
|---|---|---|
| **NB-1** (RG-2) | Ex 4.5a; Lem 4.8(a) | μ_h(λ₁) = μ_h(λ₂) = w₁ (Ex 4.5a), and μ_h(λ₁) = w₁ (Lem 4.8(a)), are now stated only when w₁ ∈ W_h. Otherwise μ_h is the empty partial map, and S1 holds vacuously because Q(h) = ∅. "Vacuously when w₁ ∉ W_h" is added to Ex 4.5a(a) and Lem 4.8(a) |
| **NB-2** (RG-3) | Rem 4.9a | Def 4.9 is now tied to **S3 only**: independence bears on veridicality, not on realizability (S2). The rest of Rem 4.9a is unchanged. Table 4.6 is not changed (not in scope) |
| **NB-3** | CHANGELOG-r1, row S-2 | **Erratum.** The phrase "satisfies S1–S3 and S2" is to be read as "satisfies S1–S3 and has an outcome that excludes H-D", which is the ledger wording for Ex 4.5a. CHANGELOG-r1 is not an input of this task, so it is not rewritten; the correction is recorded here |
| **NB-4** | Conj 5.5, F-b; pre-registration requirement | F-b now has a pre-fixed decision rule. **Cells:** disagreeing ACT_SELF_PERFORMED cells on the sealed list outside S_mode. **Who reviews:** two reviewers. Neither is a coder in any sealed pair, the R4 author, or the reviewer who made IF's anatomy. At least one is outside the Claude family or human if R2 (F15) permits; otherwise this is recorded and F-b is read as within-family. Reviewers are blind to coder identity and to each other. **Classes:** IF's (a) and (c). **Validity condition:** κ ≥ 0.67 between the reviewers; if it is undefined because they assign one class throughout, it counts as met; otherwise F-b is undecided. **Threshold:** a cell is a deviation iff both reviewers assign (a). F-b falsifies iff, for every pair with a disagreeing cell, strictly more than half of its cells are deviations. F-b's review protocol is added to the sealed items |
| **NB-5** | Conj 5.5 statement, F-a, F-c, expected consequences | **Scope:** the conjecture is now stated for the register and ruling records **outside S_mode**, with a scope note. On S_mode it predicts less disagreement (second expected consequence, extended accordingly). F-a's list is restricted to the same scope. S_mode is sealed as a subset of the sealed list. **Power:** F-c may have no power, and this is now said. It is void if S_mode = ∅. If S_mode = {R-56}, it rests on one record, and the per-pair proportion can only be 0 or 1. The inputs do not establish that R-56 is among IF's 36 coded records. R4 claims no power for F-c; falsifiability rests on F-a and F-b |
| **NB-6** | §7.2 K11.2, K12.1 | K11.2 defines a **forced** slot as a present iss slot with f(s) ∈ T (f(s) ≠ ∞). In K12.1, "Ref-only or con-only" is split into two groups. Expected: O₃ {Ref}, O₃′ {con}; the other expectations are unchanged |

## 3. Bookkeeping

- Header: revision r1 → r2, with the status and changelog pointers updated.
- §6 ledger: the legend gains an **r2** marker. Marked rows: Def 1.8, Prop 4.1(i′), Prop 4.1(iii), Ex 4.5a, Lem 4.8 (r1), Rem 4.9a, Lem 5.2, Conj 5.5, K1–K12. The Def 1.8, Rem 4.9a and Conj 5.5 short statements are updated to match. All other rows are unchanged.

## 4. Not changed (explicitly)

The following are untouched:
- all other definitions, hypotheses A1–A9, Lem 4.0a, Lem 4.0b and their proofs;
- the proofs of 4.1(i) and (ii);
- every countermodel and instance;
- §4.5 apart from NB-1 and NB-2;
- Table 4.6 and Rem 4.10;
- Def 5.1, Rem 5.2a, Cor 5.3, Prop 5.4, Rem 5.4a and Obs IF-obs;
- §7 apart from K11.2 and K12.1;
- §8.

No new claims are made beyond the re-proofs. The only new content is the pre-registration commitments for F-b and the stated limits on F-c's power. These are protocol commitments, not results.
