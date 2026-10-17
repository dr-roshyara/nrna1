# Independent Theoretical Assessment: S-Series Verdict Logic and Confirmatory Statistics

| | |
|---|---|
| **Author / date** | Session B (Professor), 2026-10-17 |
| **Kind** | A theoretical assessment within the Professor's remit. **It decides nothing**, changes no frozen file or methodology, records no governance act, runs no experiment, and reads no restricted material. |
| **Lane status** | Session A's records state that the Principal set the professor lane aside (TODO l.233; session log l.6, l.35). The Principal has since addressed this commission to Session B directly. Which instruction stands is the Principal's to state; neither is in the governance log (head G-LOG-0140). |
| **Independence** | "Independent" here means *reasoned separately and not adopted from the Researcher*. It does **not** mean independent validation: Session B shares the Researcher's model family and most information sources. Every derivation below still needs a reviewer of a different model family or a human. |
| **Sources** | v1.8 `VERDICT-CRITERIA-PROPOSAL-v1.8.md` (sha `469fddca…`), §3–§6 and §10–§11; N-a/C-4 package v2 (sha `c4c61299…`); placement sheet r2 (`7bc4e3a1…`); Session A packet `20261017_S-SERIES-PRINCIPAL-DECISION-PACKET.md` (W `c470da83c`, sha `55ae2afa…`); B1 v2 (`d6b23886…`); `prereg_v1.py` (`eb6291ab…`); `prereg_v2.py` (`827ce461…`); governance log entries 0094, 0103, 0122, 0124, 0135, 0137 |
| **Unavailable** | Session A's "derivations A and B in the session record of 2026-10-17" (packet D3) are **not in any authorized file**: the session log `.claude/sessions/2026-10-17.md` does not contain them. Both cases are therefore derived afresh here, and agreement with Session A's versions cannot be checked |

**Classification labels:**
- **[E] established by derivation** (here, or checked step by step here);
- **[A] valid only under the stated assumptions**;
- **[P] plausible, not proven**;
- **[C] contradicted** by evidence or counterexample;
- **[U] unresolved**.

---

## 1. Business summary

1. **The linkage-judgement question can be settled in one direction by logic alone.**
   - Under a strict rule, "a pair counts as linked or not linked only if both judgements agree; disagreement leaves it undecided, permanently", a second judgement **can never make a false final verdict more likely** than a single judgement. The worst a second judge can do, even a careless or ill-intentioned one, is turn a definite verdict into "open". **[A]**
   - What logic **cannot** settle is how much safety the second judgement buys. That depends on how often the two judges make the *same* mistake, which is unknown and, for two sessions of the same AI model, may be high.
   - It also cannot settle whether that safety is worth losing correct verdicts to "open". When both judges are equally accurate, the rate at which wrong findings are blocked equals the rate at which right findings are lost. Which matters more is a value judgement, and it belongs to the Principal.
2. **That guarantee has two preconditions that are not yet in place:**
   - the list of candidate theories must not itself be built from these judgements (the open G0/G1 question);
   - disagreements must never be "resolved" later.

   Without them, the guarantee does not hold, and nothing in the current theory replaces it.
3. **Disclosure alone (option b) is logically weaker but simpler.** It changes what a report says, not how often a verdict is wrong. In the narrowest case, a two-claim theory built from H2 and H4, one judgement decides the outcome.
4. **Disagreement with the Researcher.** The Researcher recommends disclosure, because adding rules has repeatedly introduced defects. That is a fair argument about *drafting risk*. It is not an argument about *safety*, where the second judgement is never worse. The minimal second-judgement rule needs one sentence and essentially exists already: v1.8 already treats conflicting rulings as unruled. Which risk weighs more remains the Principal's decision.
5. **Confirmatory tests.**
   - The mathematics is sound: under the four stated assumptions, the error guarantee holds for **any** number of reshuffles and **any** seed, provided both are fixed before the data. **[A]**
   - The study procedure is not yet properly specified: B conflicts in the record, no seed is committed, and there is no single-run rule. **[U]**
   - These are different questions. "The method is valid" is true in theory. "The study is correctly set up" is not yet true.
   - A pre-announced fixed seed would be as valid as a freshly drawn one, if it is committed before the data. The choice between them is about audit trust, not mathematics.

---

## 2. Formal setting for linkage judgements (from v1.8 §3)

Fix a registered candidate T, with claim set C and pair set Π = {{a, b} ⊆ C}.
- Each pair has a latent true status λ(π) ∈ {L, X}: linked or not linked through T's formal object.
- A ruling is r(π) ∈ {L, X}. The leaf is ℓ(π) ∈ {⊤, ⊥, ?}.
- For S ⊆ C with |S| ≥ k_min (v1.8 §3, VERIFIED text):
  - **coherence (c):** S is connected using edges with ℓ = ⊤ (an unruled pair counts as no edge), together with (a) satisfiable, (b) no derived contradiction and (d) the size condition;
  - **incoherence (c′):** S is not connected using edges with ℓ ≠ ⊥ (an unruled pair counts as an edge). Alternatively (a′) an UNSAT certificate or (b′) a derived contradiction.
- ROBUST needs a coherent core meeting CA-1…CA-8 (R2, R3). NCTF needs every subset of C\*(T) of size ≥ k_min to be shown incoherent (N3).
- A J3 concurrence (ground iii) is a further leaf that can remove a claim from C\*(T).

**Regimes:**
- **(b), single judgement:** ℓ_b(π) = r₁(π), mapping L → ⊤ and X → ⊥.
- **(a), strict agreement:** ℓ_a(π) = r₁(π) if r₂(π) = r₁(π), and ? otherwise. No later resolution (package v2 a6). The same rule applies to J3 concurrences.

**Information order.** ? ⊑ ⊤ and ? ⊑ ⊥. An assignment ℓ′ is a **coarsening** of ℓ if, for each π, ℓ′(π) ∈ {ℓ(π), ?}. By construction, **ℓ_a is a coarsening of ℓ_b** whenever both regimes see the same first rulings r₁.

### Lemma 1 (monotonicity) [A]

*Statement.* Fix the manifest, the claim-level findings other than J3, and sound satisfiability checkers. If ℓ′ is a coarsening of ℓ, then outcome(ℓ′) ∈ {outcome(ℓ), OPEN}.

*Proof.*
1. **(c) cannot be gained.** Coarsening only removes ⊤-edges from the coherence graph, so a set coherent under ℓ′ was coherent under ℓ. Conditions (a), (b) and (d) do not depend on leaves.
2. **(c′) cannot be gained.** Coarsening only turns ⊥ into ?, which *adds* edges to the incoherence graph. So a set shown incoherent by (c′) under ℓ′ was so shown under ℓ. Conditions (a′) and (b′) do not depend on leaves.
3. **J3.** Coarsening a J3 leaf can only *withdraw* a ground-(iii) removal, so C\*(T) under ℓ′ ⊇ C\*(T) under ℓ. A claim kept in C\*(T) this way fails CA-3 (it is non-falsifiable as recorded under ℓ, or undetermined), so it cannot enter a core. More claims in C\*(T) can only add subsets that must be shown incoherent.
4. **ROBUST.** By (1) and (3), every coherent core under ℓ′ was a coherent core under ℓ. So ROBUST(ℓ′) ⇒ ROBUST(ℓ).
5. **NCTF.** By (2) and (3), every subset that NCTF(ℓ′) requires to be shown incoherent was shown incoherent under ℓ, and C\*(T) under ℓ′ ⊇ C\*(T) under ℓ. So NCTF(ℓ′) ⇒ NCTF(ℓ), provided the other N-conditions are unchanged; they do not depend on leaves, except N6, which reproduces the same leaves.
6. **Conclusion.** Hence, if outcome(ℓ′) ≠ OPEN, it equals outcome(ℓ). ∎

*Assumptions:*
- (M) a fixed manifest, i.e. candidate generation does not read leaves (G0);
- (S) sound checkers;
- (F) finality: no leaf is rewritten after the fact except by the agreement rule.

This independently re-derives the substance of package v2's Lemma A (conditions C1–C4) for this specific comparison. It is still **same-model reasoning**.

### Theorem 2 (pathwise weak dominance of strict agreement) [A]

*Statement.* Assume (M), (S) and (F), and assume that the first rulings r₁ are the same under both regimes (assumption R: the presence of a second judge does not change the first judge's rulings). Then, on every realization:

  outcome under (a) is false-definite ⇒ outcome under (b) is the same false-definite outcome.

Hence P(false definite verdict | a) ≤ P(false definite verdict | b), **for any joint error distribution of the two judges**: correlated, anti-correlated, or even adversarial in r₂.

*Proof.* ℓ_a is a coarsening of ℓ_b (§2). By Lemma 1, outcome(ℓ_a) ∈ {outcome(ℓ_b), OPEN}. OPEN is not a definite verdict. ∎

**What this does and does not say:**
- The guarantee needs **no independence assumption**. Independence governs *how much* (a) helps, not *whether* it can hurt.
- A second judge who sees the first value, or the provisional outcome, can only **agree** (the leaf keeps r₁, as under (b)) or **disagree** (the leaf becomes ?). The informed-auditor attack of round 12b (finding N1) can therefore reduce (a)'s protection to (b)'s level, but **not below it**, *provided* (F) holds: no "missing audit later supplied" or "conflict resolved" path that sets a leaf to a value other than the agreed r₁. **[A]** N1's actual mechanism is an audit recorded after the outcome is visible. Under (F) as stated here, such an audit can only coarsen or confirm.
- **If (M) fails (G1: generation reads rulings),** the two regimes may produce *different manifests*, and Lemma 1 does not compare them. Theorem 2 is then **[U]**. This is the theoretical core of the placement sheet's finding that G0/G1 must be fixed first. I agree with that finding.
- **If (F) fails** (any re-audit, third ruling or "J5 resolution" that can set a determinate value differing from r₁), dominance can fail. **[C]**: a resolution rule that sets ℓ = ⊥ where r₁ = L creates outcomes that (b) would not produce. The open ambiguity between "unruled until resolved" (v1.8 l.69) and "J5 resolution" (l.270, l.313) is exactly this risk.

### Proposition 3 (the size of the gain, and its price) [E for the algebra; numbers U]

*Setting.* For one deciding pair, let:
- E₁, E₂ be the error indicators of the two rulings, with P(E₁) = ε₁ and P(E₂) = ε₂;
- φ be the phi correlation of E₁ and E₂.

*Statement.*
- P(false determinate leaf | b) = ε₁.
- P(false determinate leaf | a) = P(E₁ ∧ E₂) = ε₁ε₂ + φ·√(ε₁(1−ε₁)ε₂(1−ε₂)). With ε₁ = ε₂ = ε, this is ε² + φ·ε(1−ε).
  - φ = 0 gives ε² (the "independent" figure);
  - φ = 1 gives ε (no gain).
- **Blocked errors** (r₁ wrong, r₂ right) = P(E₁ ∧ ¬E₂). **Lost correct leaves** (r₁ right, r₂ wrong) = P(¬E₁ ∧ E₂).
- **With equal accuracy (ε₁ = ε₂), the two are exactly equal:** both equal ε − P(E₁ ∧ E₂) = ε(1−ε)(1−φ).

*Proof.* Direct algebra on the 2×2 joint distribution of (E₁, E₂). ∎

**Consequences:**
1. Per deciding pair, (a) trades errors blocked against correct conclusions lost **one for one** when the judges are equally accurate. Its value therefore rests on the **loss ratio**: how much worse a false definite verdict is than an OPEN that should have been definite. That ratio is not a mathematical quantity. **[U, a Principal value]**
2. The trade is **asymmetric across outcomes**:
   - a minimal ROBUST core with k_min = 2 depends on one ⊤-pair;
   - an NCTF by disconnection depends on many ⊥-pairs (package v2: up to m pairs), so the loss compounds as ∏(1 − P(lost)).

   (a) therefore makes a true NCTF much harder to reach than a true ROBUST. **[E]** This agrees with package v2's illustration.

### Proposition 4 (what "independent" must mean) [E as conditions; whether they are met: U]

For the φ = 0 figure to apply, the following are needed:
1. **Separate error processes.** The two judges must not share systematic misreadings: different model families, or human and model, or different prompts written by neither drafter. Two sessions of the same model share training and habits, so φ ≫ 0 is plausible **[P]**.
2. **Blinding.** The second judge must not see r₁, the provisional outcome, or any STATUS or test output. Otherwise anchoring raises φ.
3. **Assignment fixed before the ruling**, by a rule that does not depend on the pair's influence on the outcome. Otherwise effort concentrates where agreement is easiest.
4. **Even then, item difficulty correlates errors.** If pair π has its own error rate ε_π, then for two judges who are conditionally independent given π,

   P(E₁ ∧ E₂) = E[ε_π²] ≥ (E[ε_π])²   (Jensen),

   with equality only if difficulty is homogeneous. Ambiguous pairs fool both judges. **So ε² is optimistic even for two independent humans.** [E]

**Implication.** Without an estimate of φ and of the difficulty spread, the safety gain from (a) is **unquantified**. It could be large (human plus model, blinded) or near zero (two sessions of one model). Estimating it would require a study **[needs authorization]**.

### Proposition 5 (the disclosure option, what it leaves) [E]

Under (b), with the same assumptions:
- P(false definite verdict) is unchanged by disclosure. Disclosure is a property of the *report*, not of the decision function.
- In the Kind-P-only branch (V-2 (i), k_min = 2, core = {H2, H4}), the outcome depends on **one** J1 ruling. If it is wrong, the verdict is wrong, with probability ε₁. This agrees with Session A's packet D3.
- The only remaining filter is the Principal's J4 review after the result is visible. It can move only towards OPEN (v1.8 §8.1 step 6), and it relies on a human spotting an error the procedure did not.
- **Neither (a) nor (b) protects candidate assembly.** Package v2's counterexample, a wrong "not linked" split during generation, yields NCTF for every manifest candidate, and no verdict-stage rule can repair it. **[E]** (The argument in package v2 §1 has been checked here.)
- Option (b) does not avoid every new rule. It must still fix one reading of the l.69 versus l.270/313 ambiguity (Session A's draft chooses the pair-level reading of l.69). **[E]**

### 2.1 Conclusion on DS-1

**Which option is stronger on safety.** (a) **weakly dominates** (b) for false definite verdicts, on every realization, provided:
- (M) generation does not read the rulings (G0);
- (F) strict agreement, with no later resolution;
- (S) sound checkers;
- (R) first rulings unaffected by the existence of a second judge.

Its safety gain is unquantified (Proposition 4) and is paid for one-for-one in lost definite verdicts (Proposition 3), mostly at the expense of NCTF.

**What the available theory cannot settle:**
- (i) the G1 case;
- (ii) the size of φ;
- (iii) the loss ratio.

So the theory supports the statement "**(a) is never less safe, and is strictly safer whenever judges' errors are not perfectly correlated, under (M), (F), (S), (R)**". It does not support "(a) is worth its cost". **Both options are conditionally defensible.** The choice turns on the Principal's loss ratio and on whether genuinely different judges are available.

**Where I depart from the Researcher (packet D3):**
1. The packet says (a) "lowers the risk, but only as far as the two judges are really independent". Theorem 2 sharpens this. Independence governs the *amount*, but (a) **cannot raise** the risk under (M), (F), (S) and (R). This removes the worry that a badly chosen second judge makes things worse; the worst case is more OPEN verdicts.
2. The packet's reason for preferring (b) is that added rules have repeatedly introduced defects. That is a legitimate *specification-risk* argument, and the record supports it (S-VCR l.768). But the minimal (a) needs only: "determinate iff two blind rulings by pre-assigned rulers agree; disagreement is permanently unruled; no resolution". v1.8 already contains its core ("two conflicting rulings … the pair counts as unruled", l.69). The defects in rounds 12–13 came from placement and completion machinery (a5, timing), not from this rule. **[P]**: plausible that a minimal (a) is low-risk to draft; an independent review must confirm it.
3. On the decisive dependency, (M)/G0, I agree with the Researcher and with the placement sheet.

**Additional work needed:**
- An independent (different-model-family or human) check of Lemma 1 and Theorem 2.
- A decision or clarification of G0/G1.
- A one-sentence finality rule settling the l.69 versus l.270/313 ambiguity, under either option.
- If the Principal wants the gain quantified: a judge-agreement study estimating ε and φ (needs authorization).

---

## 3. Confirmatory test settings: validity versus procedure

### 3.1 Mathematical validity [A, established by derivation; same-model]

Proposition 1 (PO-01 note; re-derived by Session A, as REPORTED) gives P(p ≤ u) ≤ u under:
- A1: null invariance under the group;
- A2: the group draws independent of the data;
- A3: everything fixed before the data, one run reported;
- A4: the extracted data, as extracted.

Holm then bounds the family-wise error at α for the three Model-0 nulls.

- **B.** The result holds for **every** B ≥ 1. B affects only resolution (min p = 1/(B+1)) and power, not validity. **[E]**
- **The seed.** A2 requires the pseudo-random draws to be independent of the data. Any seed **committed before the data and not chosen in the light of it** satisfies this, whether a published constant (20260927) or a fresh CSPRNG draw. A CSPRNG draw at an act adds **auditability**: it proves the seed was not selected. It does not add validity. **[E]** under the usual idealization that the generator behaves as random; **[A]** for Python's Mersenne Twister, as A2 states.
- **Single run.** Validity is voided if several (B, seed) runs are made and one is chosen. This is a selection effect; the error rate of "best of k runs" exceeds α in general. **[E]**

### 3.2 Was the procedure correctly specified, frozen and executed? [U]

| Element | Record | Status |
|---|---|---|
| B for H1, H2, H4 | Freeze 1: 9,999 (GL-0094; B1 l.68). B = 999 decided only for H5 (GL-0122, 0124); the H2 NR-P threshold is stated at 999 (GL-0135 #1) | **specified inconsistently** |
| Seed | Freeze 1 text requires a CSPRNG draw "at the freeze". No analysis seed is recorded anywhere. GL-0094's "Not authorized … seed" sits beside "Freeze 2, … sampling", so whether it refers to the analysis seed is a reading **[U]** | **not specified** |
| Single run | no rule | **not specified** |
| Execution | none (0 measured labels) | **not executed** |

**Conclusion:**
- the *method* is valid under A1–A4 **[A]**;
- the *study* is not yet correctly specified, because A3 is not met as a recorded rule **[U]**;
- nothing has been executed.

**The H2 power threshold.** The code computes power at the B actually used (`h2_power(m, π, level, B)`, VERIFIED in prereg_v2 l.116–131, via `outcome`). B = 9,999 therefore needs no code change; only the documented "≥ 58" figure would change. **The direction of that change is not derived here** and needs an authorized calculation.

**Agreement with the Researcher (packet D2).** I agree that both options are valid and that the record must be reconciled with RQ-1. On mathematical grounds, a mild preference for 9,999 is defensible: finer p-resolution, slightly higher power, and fidelity to the frozen text. **[P]**: the power gain is expected but was not computed. I add one point the packet does not make: the CSPRNG requirement concerns audit integrity, not validity (§3.1). That matters if the Principal weighs the cost of a seed-drawing act.

### 3.3 H2's independence assumption (kept separate)

- **Validity.** The test is valid for its stated null ("orientations i.i.d. fair"). **[A]**
- **Interpretation.** If orientations within an object are dependent, the null is false for a reason other than π > ½. A rejection then does not license "forward share > ½". Under positive dependence, the rejection rate of the hypothesis π = ½ exceeds α (approximately). **[P]**: the direction holds for positive dependence; the magnitude is unknown.
- **Power.** The NR-P power formula assumes independence. Under dependence it is not exact. **[A]**
- **Q2′** (predecessor-rule subset: "forward" ≡ list order agreeing with the file dates). **[A]**, conditional on the verified code facts. The scope is a subset of unknown size. H2 is **not** shown to be invalid. **[U]**

---

## 4. Classification of the central claims

| # | Claim | Class | Basis / note |
|---|---|---|---|
| 1 | H1/H2/H4 p-values valid (Prop. 1) | **[A]** | A1–A4; same-model only; needs external review |
| 2 | Holm FWER ≤ α over the three Model-0 nulls | **[A]** | given 1 |
| 3 | The guarantee holds for any pre-fixed B and seed | **[E]** | §3.1 |
| 4 | The study's confirmatory procedure is fully specified | **[C]** | B conflict; no seed or single-run rule (§3.2) |
| 5 | The star MCMC scheme is valid in general | **[C]** | constructed counterexample (PO-01 Prop. 2; reproduced by Session A) |
| 6 | H3/H5 false-positive rates controlled or conservative | **[U]** | "conservative" withdrawn; no valid evidence either way |
| 7 | The H3 null is unaffected by swap reducibility, n ≤ 4 | **[E]** | finite exhaustive check (reproduced by Session A) |
| 8 | … for all n | **[P]** | no proof |
| 9 | Triangle reversal preserves T_h3 | **[C]** | n = 4 counterexample |
| 10 | ev(c) ≤ DISCRIMINATING while S5c is prohibited (Prop. 8.4) | **[E]** | from the frozen P3b rules |
| 11 | Only OPEN is reachable now (Prop. 8.5) | **[A]** | v1.8 as written; a proposal |
| 12 | ROBUST is impossible under V-2 (ii) with S5c prohibited | **[A]** | v1.8 R4/CA-9 as written; the prohibition persists; not V-2 (iii) |
| 13 | Under V-2 (i), the core is {H2, H4} | **[A]** | additionally needs k_min = 2 (V-3 open); a shared formal object; a J1 "linked" ruling |
| 14 | The C1 model is necessary for a Kind-P ROBUST | **[A]** | follows from v1.8 CA-8 and the fact that all inputs are agent-produced (VERIFIED) |
| 15 | Q2′ | **[A]** | verified code facts; subset scope |
| 16 | H2 rejection ⇒ forward share > ½ | **[U]** | the dependence issue (§3.3) |
| 17 | Mutual exclusivity of ROBUST / NCTF / OPEN | **[A]** | the source argument (v1.8 §6.2) reads correctly here; needs sound checkers; not re-derived formally |
| 18 | Lemma 1 (monotonicity) | **[A]** | (M), (S), (F); new derivation here |
| 19 | Theorem 2 ((a) never less safe than (b)) | **[A]** | (M), (S), (F), (R); new derivation here |
| 20 | (a) is worth its cost | **[U]** | depends on φ and the loss ratio |
| 21 | Disclosure reduces the probability of a wrong verdict | **[C]** | Prop. 5: it does not |
| 22 | Either option protects candidate assembly | **[C]** | package v2 counterexample |

## 5. What is needed next (none of it authorized by this document)

| Need | Kind | Who could authorize it |
|---|---|---|
| Review of Lemma 1, Theorem 2, Props. 3–5 and Prop. 1 by a human or a different model family | independent review | Principal (DS-5 scope extended) |
| G0/G1: does candidate generation read J1 rulings? | clarification or decision (with V-13/O1) | Principal |
| A finality rule settling l.69 versus l.270/313 | specification, under either option | Principal (via a v1.9 or an adoption act) |
| The loss ratio: false definite vs a missed definite verdict | value judgement | Principal |
| Estimates of ε and φ (judge agreement) | study | Principal (separate act) |
| B, seed and the single-run rule | specification decision with RQ-1 | Principal |
| The H2 threshold at the chosen B | authorized calculation | Principal |
| Session A's derivations A and B | provide in an authorized file, for comparison with §2 | Researcher, on the Principal's request |

## 6. Boundaries kept

- No frozen file, methodology or governance record changed.
- No decision taken or recorded.
- No experiment, simulation, enumeration or model check run.
- No corpus, S5, hold-out, sealed, ledger or batch-input material read.
- No Git operation; no message sent.
- The conclusions above are theoretical. Turning any of them into a rule, an approval or an implementation is reserved for the Principal.
