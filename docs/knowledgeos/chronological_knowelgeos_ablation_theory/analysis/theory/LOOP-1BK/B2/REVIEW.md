# Targeted re-review of FORMAL-THEORY-R4 r2 (frozen)

Inputs used, all from this directory: REVIEW-TASK.md, FORMAL-THEORY-R4.md (r2), CHANGELOG-r2.md, TASK.md (the r2 task), REVIEW-r1.md, REVIEW-r1.json and INSTRUMENT-FINDING.md. No commands were run and the R5 enumeration was not executed. Every check was done by hand.

**Limit of the regression check.** The r1 text of FORMAL-THEORY-R4.md is not in this directory, because the file now holds r2, and CHANGELOG-r1.md is not here either. So "no change beyond TASK-r2" was checked against CHANGELOG-r2 and against REVIEW-r1's description of r1. It was not checked by a line-by-line diff.

## Verdict: **PASS_WITH_LIMITATIONS**

- **B-1 is resolved.** r2 uses fix (a): a frame is now any subset of 𝓗 (Def 1.8). I re-checked the proofs of Prop 4.1(i′), Prop 4.1(iii) and Lem 5.2 step by step. Every step is valid. No step assumes anything that is not a hypothesis or a consequence of the definition of a frame.
- **The countermodels still work.** Every countermodel frame is a subset of 𝓗, and each countermodel still violates exactly one hypothesis.
- **NB-1 to NB-6 are fixed.** NB-3 is fixed by an erratum in CHANGELOG-r2, because CHANGELOG-r1 was not an input.
- **No regressions.** I found no new invalid step and no change outside the scope of TASK-r2.

The limitations are all disclosed in r2, and none of them blocks:
- the R5 enumeration has not been run;
- the generic statements cannot be tested in U (§7.3);
- the generic statements now cover only frames that are subsets of 𝓗;
- F-c may have no power;
- F-b falls back to a within-family reading if R2 (F15) does not permit an outside reviewer.

---

## 1. B-1: the generic proofs, step by step

### 1.0 Fix (a) and the closure hypotheses
Def 1.8 now says: "A frame is any subset 𝓗′ ⊆ 𝓗". So every member of every frame satisfies F1 and F2. This follows from the definition of a frame, so it is not a hidden axiom.

A5, A7 and A9 could only become inconsistent with this if one of them forced some history outside 𝓗 into 𝓗′. None does:
- **A5:** h[σ*, f*] satisfies F1 by assumption, and it satisfies F2 because F2 mentions neither σ nor f.
- **A7:** a fresh addition that satisfies F1 also satisfies F2. A (+act) item has no constitution, and (+ref) only adds pairs to Ref.
- **A9:** a change of only con, or only d, that satisfies F2 also satisfies F1, because F1 mentions neither con nor d.

So the restriction is consistent. Lem 4.0a and Lem 4.0b are unaffected: in Lem 4.0b, h¹ and h² satisfy F1 vacuously and F2 is inherited.

### 1.1 Prop 4.1(i′). Hypotheses A1, A2, A3, A4, A5, A7. **Valid.**

| Step | Check |
|---|---|
| F1 in h | h ∈ 𝓗′ ⊆ 𝓗. This follows from Def 1.8, not from an extra axiom |
| Steps 1–5 preserve F1 | F1 reads E^iss, τ, v, f and σ. Steps 1 and 3–5 touch none of these. Step 3 sets the work target τ_W, which is a separate coordinate (Def 1.7). Step 2 adds a₀ with f = ∞, which imposes no F1 condition |
| Each result is in 𝓗′ | Each step is a fresh addition to a history already in 𝓗′, and its result satisfies F1. So A7 applies |
| O′ is unchanged | (+actor), (+act) and (+work) by A4, which needs both histories in 𝓗′; that was just shown. (+ref) by A3: the two histories differ only in Ref, and both are in 𝓗′ |
| h̃ ∈ R_B′ | (ρ₀, a₀) gives D-rec and (ρ₀, w₀) gives B-recW |
| Apply (i) | (i) needs A1, A2 and A5, which are all hypotheses here |
| ⊆ S | D-rec forces R ≠ ∅ in H-D′. B-rec contains D-rec in H-B′ |
| The four-way equality | The chain of inclusions is closed |

*Nit (harmless):* step 1 never fires. R ≠ ∅ already gives 𝒜 ≠ ∅, because k(ρ) ∈ 𝒜.

### 1.2 Prop 4.1(iii). Hypotheses A1, A2, A4, A5, A7. **Valid.**

| Step | Check |
|---|---|
| F1 in h | h ∈ 𝓗′ ⊆ 𝓗 (Def 1.8). This is the step REVIEW-r1 flagged ("By F1 in h"); it now has a ground |
| Step 1: F1 of h[σ′, f′] | Checked act by act. If f′(a) = t ∈ T, then f(a) = t and (t, τ(a)) ∉ Vio. So σ′ = σ at that point, and σ = v(a) there by F1 in h. Lem 4.0b (A1, A2, A5) then gives h² ∈ 𝓗′ and O′(h²) = O′(h) |
| Step 2: x ∈ 𝒜 | B-recW gives a referenced work event, and its executor is in 𝒜 |
| Step 2: the added acts | Induction on the added acts. Each a_{t,τ} has v = σ′(t)(τ) and f = t, so its own F1 condition holds. σ′ and the existing acts are untouched. (+act) allows prep 1 and f ∈ T, so each addition is in 𝓗′ by A7, and O′ is unchanged by A4 |
| B-chg | Each change point is either explained by an original act under f′ or lies in L, where an added act explains it. The act disjunct of B-chg has no capability or status condition |
| B-conf | Correct as re-argued. A work point in Vio gets p ∈ P. A work point outside Vio did not violate in h, and σ′ = σ there |
| B-rec | Ref is unchanged, and E and W only grow |
| The three bullets of (iii) | They hold as checked |

### 1.3 Lem 5.2. Hypotheses A3, A8, A9, plus A1, A2, A5 for the H-D cases. **Valid.**

| Step | Check |
|---|---|
| F2 in h and h⁰ | h⁰ ∈ 𝓗′ ⊆ 𝓗. This is the step REVIEW-r1 flagged ("F2 still holds"); it now has a ground |
| h⁰ in the H-D cases | h ∈ R_B′ holds directly in the first case. In the second case it holds because H-D′ ∩ H-B′ ⊆ R_B′. h⁰ = h[p, ∞] is then in H-D′ ∩ H-B′ by Lem 4.0b and the proof of (i). Ref, con, d, r and the items are unchanged |
| h₂: drop con(a) | F2 holds for a (no condition) and is inherited for every b ≠ a. h₂ ∈ 𝓗′ by A9, applied to h⁰ ∈ 𝓗′. O′ is unchanged by A3 |
| h₂′: d(a) := r(ρ) | con(a) is undefined, so F2 places no condition on a, and the other acts are unchanged. h₂′ ∈ 𝓗′ by A9. O′ is unchanged by A8 |
| h₁: con(a) := ρ | F2 holds for a, because (ρ, a) ∈ Ref and d(a) = r(ρ). h₁ ∈ 𝓗′ by A9. O′ is unchanged by A3 |
| Class membership | The H-B clauses read neither con nor d. In the H-D cases, σ ≡ p and f ≡ ∞ are kept, so Chg = ∅, D-st is vacuous (Adopted, which reads d, is never invoked) and D-rec reads only Ref |

Under fix (a), the drop-all-con preliminary of fix (b) is not needed, and r2 correctly does not add it.

### 1.4 Does any step now presuppose an axiom that is not a hypothesis?
**No.** The only axioms used of the starting history are F1 and F2. Both now hold by Def 1.8 for every member of every frame. Every closure step (A5, A7, A9) and every observation step (A1–A4, A8) is a stated hypothesis of the result in question, and each hypothesis list in §4.1 matches what the proofs use. Nothing else changed in Lem 3.7, Lem 4.0b, Cor 4.3 or CM-A7(b), and these remain valid.

### 1.5 Countermodel frames under fix (a)

| CM | Frame | Frame ⊆ 𝓗? | Violates exactly one? |
|---|---|---|---|
| CM-A1, A2, A3a, A3b, A4a, A4b, A8 | 𝓗 | yes | yes. The observation changes; the frame satisfies A5, A7 and A9 (Lem 4.0a) |
| CM-A5 | 𝓗₅ = 𝓗 + "c★ ⇒ σ ≡ q" | yes | yes. A5 fails. A7 holds because additions keep σ and c. A9 holds. O satisfies A1–A4 and A8 |
| CM-A6 | 𝓗 with V = P = {p} | yes (frames are relative to the parameters) | yes |
| CM-A7(a), (b) | 𝓗₇ = 𝓗 + "everything is referenced" | yes | yes. A5 and A9 do not touch Ref |
| CM-A9 | 𝓗₉ = 𝓗 + "c★ ⇒ no con = ρ" | yes | yes. A5 holds. A7 holds because additions are unconstituted |
| R5: U, U₅, U₆, U₇, U₉ | F1 ∧ F2 assignments (§7.1) | yes | as K12.2 states |

**countermodels_still_ok = true.** No countermodel was edited, and the r1 independence check (REVIEW-r1 §3) carries over unchanged, because every frame was already a subset of 𝓗.

## 2. NB-1 to NB-6

| Id | Fixed? | Evidence |
|---|---|---|
| NB-1 | **yes** | Ex 4.5a and Lem 4.8(a) state μ_h only when w₁ ∈ W_h. Otherwise μ_h is the empty map. "Vacuously when w₁ ∉ W_h" is added in both places |
| NB-2 | **yes** | Rem 4.9a now says Def 4.9 is a criterion for S3 only, and that it does not bear on S2. Ledger row updated. *Residue, not in scope:* the reopening-conditions row of Table 4.6 still says "a candidate route to S2 + S3 (an independent record …)". This is readable as "independence is part of a route", not as "independence is a criterion for S2", so it is harmless |
| NB-3 | **yes, by erratum** | CHANGELOG-r1 is not a TASK-r2 input, and it is not in this directory. The correction is recorded in CHANGELOG-r2 §2. The ledger row for Ex 4.5a already uses the correct wording |
| NB-4 | **yes** | F-b now fixes which cells are reviewed and who reviews: two reviewers, with exclusions, an outside-family or human reviewer if R2 permits, and blinding. It also fixes the classes ((a) and (c), which matches IF's anatomy), a validity condition (κ ≥ 0.67; the undefined-κ case is handled; otherwise "undecided"), and a threshold: a cell is a deviation only if both reviewers say (a), and F-b falsifies only if more than half of the cells are deviations for every pair. The case with no disagreeing cells is handled, and the protocol is added to the sealed items. *Note:* the "for every pair" quantifier makes F-b conservative. This is a pre-fixed choice, not a defect |
| NB-5 | **yes** | Conj 5.5 is now scoped to the records outside S_mode, with a scope note. F-a has the same scope. The second expected consequence covers S_mode and is consistent with F-c. S_mode is sealed as a subset of the sealed list. F-c "may have no power": this is stated explicitly, with the cases S_mode = ∅ and S_mode = {R-56} and the fact that R-56's membership in IF's 36 records is not established. Falsifiability now rests on F-a and F-b |
| NB-6 | **yes** | K11.2 defines a forced slot as one with f(s) ∈ T. K12.1 splits Ref-only from con-only, and the expectations now read O₃ {Ref} and O₃′ {con} |

## 3. Regressions

- **Changes beyond TASK-r2:** none found. Every change I saw maps to B-1 or NB-1–6:
  - the Def 1.8 restriction and its required countermodel check;
  - the three re-proofs;
  - the μ_h conditionals;
  - the Rem 4.9a sentence;
  - the erratum;
  - the F-b rule and its sealing;
  - the narrowed scope of Conj 5.5 and F-a, the extended expected consequence, and the F-c power note;
  - K11.2 and K12.1;
  - header and ledger bookkeeping.

  Statement ids are stable, and nothing was added or withdrawn.
- **New invalid step:** none.
- **Scope change (disclosed, intended):** the generic statements now range over frames that are subsets of 𝓗. The counter-scenario 𝓗 ∪ {h₀} from REVIEW-r1 is no longer a frame. No R4 statement used a frame outside 𝓗, and the base-case results are unchanged.

## 4. Blocking and non-blocking

**Blocking:** none.

**Non-blocking:**
- N-r2-1: In the proof of (i′), step 1 (adding an actor) can never fire, because R ≠ ∅ implies 𝒜 ≠ ∅ through the keepers. Harmless.
- N-r2-2: The reopening-conditions row of Table 4.6 still pairs "an independent record" with "S2 + S3". The table was out of scope for this task, and the wording is consistent with the fixed Rem 4.9a.
- N-r2-3: NB-3 is fixed only by an erratum. CHANGELOG-r1 itself still contains the typo.
- N-r2-4: r2 has not been checked by R5. The generic statements, and so B-1 itself, cannot be tested in U (§7.3).
