# TV-F-025 … TV-F-028 — Findings from deep verification of the 025-series (Phase 2C)

**VERIFY SESSION · 2026-08-29.** Sources: the 33 files of the 025a–025z algebra series, read in full by verification agents under the §3 schema. **TV-F-025 was independently re-derived and numerically confirmed by the lead verifier** (transcript below) — it is not accepted on a subagent's word.

---

## TV-F-025 — **MATHEMATICALLY INVALID**: the likelihood-ratio product in step-025n is gated on an insufficient independence condition

**This is the first genuine, unrepaired mathematical error found anywhere in the KnowledgeOS corpus.**

### Claim under examination (verbatim, step-025n §17–18)
```
LR(E) = P(E|H) / P(E|¬H)
LR(E₁,E₂) = LR(E₁) · LR(E₂)            [boxed]
generally  LR(E₁,…,Eₙ) = ∏ᵢ LR(Eᵢ)
```
gated in §14/§18 by the single condition **`E₁ ⊥ E₂ | H`** ("if evidence is conditionally independent given H").
Consumed downstream at §20: `Odds(H|E₁,…,Eₙ) = Odds(H)·∏ᵢ LR(Eᵢ)`, and re-used in step-025u §45 (`LR₁=19, LR₂=15 ⇒ 285`).

### The defect (first invalid inference)
By definition `LR(E₁,E₂) = P(E₁,E₂|H) / P(E₁,E₂|¬H)`.
Conditional independence **given H** licenses only the numerator split: `P(E₁,E₂|H) = P(E₁|H)P(E₂|H)`.
The denominator requires the *separate* assumption `E₁ ⊥ E₂ | ¬H`. Without it the factorisation does not hold.
**First invalid inference: §18's boxed product, taken from the §14/§18 given-H-only condition.** The correct gate is conditional independence **given H *and* given ¬H** (equivalently, given every hypothesis in the partition).

### Verifier counterexample (independently computed, lead verifier)
Let `P(E₁|H)=P(E₂|H)=0.9` with E₁ ⊥ E₂ | H, so `P(E₁,E₂|H)=0.81`.
Let `P(E₁|¬H)=P(E₂|¬H)=0.1` but with E₁ ⟺ E₂ given ¬H (perfect correlation, within Fréchet bounds), so `P(E₁,E₂|¬H)=0.1`.

| Quantity | Value |
|---|---|
| `LR(E₁)`, `LR(E₂)` | 9.0, 9.0 |
| Corpus formula `LR(E₁)·LR(E₂)` | **81.0** |
| True `LR(E₁,E₂)=0.81/0.1` | **8.1** |
| Overstatement | **10×** |
| Posterior from prior odds 1: claimed vs actual | **0.988 vs 0.890** |

Control: replacing the ¬H-correlation with independence gives `P(E₁,E₂|¬H)=0.01` and `LR_true = 81.0 = ` the product exactly — confirming the missing conjunct is precisely what the formula needs.

- **Level:** L1 Statistics. **Result: INVALID (as stated).** **Severity: MAJOR** — the error direction is *systematically toward overstating support*, which is the exact failure mode ("evidence inflation") that the same file's §10 (`EvidenceCount ≠ InformationCount`) and §48 (`Evidence has no intrinsic universal weight`) exist to prevent.
- **Mitigating SOURCE RESULT:** §19 and §44 forbid applying the product without a justified dependency model, and step-025c-2's worked numeric example (all eight values re-derived and confirmed correct) does not exercise the defective branch. **The corpus's *policy* is safe; its *stated mathematics* is wrong by one missing conjunct.**
- **Non-consequence:** this does **not** invalidate T-K6a (the raw-aggregator impossibility) — that theorem concerns multiset-of-strengths aggregators, not likelihood ratios, and is proven independently. It does **not** invalidate step-025c-2's arithmetic. It **does** put every downstream use of `∏ᵢ LR(Eᵢ)` (025n §20, 025u §45) under the corrected gate.
- **POSSIBLE REPAIR (VERIFIER RECOMMENDATION, not corpus theory):** state the gate as `E_i ⊥ E_j | H` **and** `E_i ⊥ E_j | ¬H` (for a binary hypothesis; conditional independence given every element of the hypothesis partition in general). This is a one-conjunct correction that preserves every downstream use. **It is a new theoretical act and must not be silently applied.**
- **Register sync:** AC C-067.

---

## TV-F-026 — `Zero` has ten mutually unreconciled definitions across the 025-series

Building on TV-F-023 (five signatures in Steps 003–025), the algebra series adds five more, each boxed as *the* definition, none declaring supersession:

| # | Source | Form |
|---|---|---|
| 1–5 | Steps 003, 006, 013/015/021, 024§59/025, 025d | see TV-F-023 |
| 6 | 025d §6/§9/§24/§37 | structured gap-set / requirement-difference / `Z_t=Zero(K_t,EC_t)` / `⊕`-collection — **four forms inside one file** |
| 7 | 025d §16 (boxed) | `Zero=(Z_world, Z_knowledge, Z_governance, Z_decision)` — a 4-tuple **object**, not a function |
| 8 | 025r §15 | Zero = the decision-relevant gap (VOI-grounded) |
| 9 | 025t §48 (boxed) | `Zero = Unresolved epistemic/proof obligation` |
| 10 | 025v §38 / 025z §44 (boxed) | `Zero_semantic = MeaningInsufficientlyDetermined` / `Zero = Insufficient epistemic basis for the next permitted action` |

**Additional defect at the codomain:** 025d declares the status set `𝒮` with **9** values (§4), emits `Missing` **one section before introducing it** (§6 vs §7 → 10), then adds `Failed` (§23 → 11), and **never re-declares 𝒮**. The file that formalises Zero leaves Zero's codomain open. `Satisfied` is simultaneously an element of 𝒮, a set-valued function (§9), and a predicate (§12) — **ILL-TYPED**.

**Most serious consequence — the boxed form fails its own file's headline test.** Step-025d opens by asking, verbatim: *"Can Zero(K,G,EC) be computed from the KnowledgeState?"* Its boxed answer includes `Z_world = Distance(W_t, W*)`, but `W_t` (world state) is unavailable to the system by the corpus's own foundational invariant (`X_t ≠ K_t`, step-016 §9; restated 025a-1 §1 and 025c-3 §25). **SOURCE RESULT: "25D — PASS". VERIFIER OBSERVATION: the boxed definition is not computable from the KnowledgeState, so the file's own test is failed, unrepaired.**
**Severity: MAJOR (blocks canonicalisation of the Δ/Zero thread; interacts with pending governance decision #1).** **Register sync:** AC C-068.

---

## TV-F-027 — The Lord/Sārathi role assignment is reversed within the 025-series, unflagged

- **025h §28 (the file that formalises Sārathi):** Lord answers *"What next?"*; **Sārathi answers "What decision?"**, with utility "Central" to Sārathi. Signature `S(K,G,D,M,C) → DecisionResult`.
- **025r §11:** Sārathi demoted to `(K, Models, Constraints) → DecisionCandidate`; §35 concedes the boundary "is a DDD design decision".
- **025z §68–69 (the closing file of the series):** *"Lord = Epistemic Decision and Action Orchestrator"* — the option set now **includes `Decide`** — while **Sārathi is reassigned to "Reasoning, Planning, Interpretation, OptionGeneration"**.

**The series ends with its two named agents' defining responsibilities effectively swapped relative to its own opening file, with no supersession act and no acknowledgement.** This compounds the signature drift already recorded: Lord moves generator → optimizer (025c-3 argmax ratio) → selector-with-policy (025g) → decision orchestrator (025z); Sārathi's output type moves `DecisionResult` (5-way sum) → `DecisionCandidate` → option list; its codomain appears in three incompatible forms inside 025h alone (§24 3-way, §34 adds `ValueModelUnderspecified`, §37 5-way with `ModelUnderspecified`).
**Severity: MAJOR for the DDD model** (the Zero/Lord/Sārathi triad is the corpus's principal architectural device and its role boundaries are not fixed); **MINOR mathematically.** **Register sync:** AC C-069.

---

## TV-F-028 — Three silent formal regressions: later files drop earlier formalisations without argument

The 025-series re-derives earlier corpus ground in 14 of 19 files with near-zero citation. In three cases the re-derivation is **strictly weaker than what it replaces**, and no file notes the loss:

1. **Beta-Bernoulli reliability → qualitative reliability.** Step-019 §10/§40 established `p | D ~ Beta(α+k, β+n−k)` with reliability represented as a *distribution* preserving uncertainty (verified correct — computation C4). **025u** re-derives trust/reliability as a qualitative `Reliability(a,q,t)` with "evidence-based and versioned" reputation and **no distributional model at all**.
2. **Strong-Kleene three-valued logic → informal status sets.** Step-018 §25 gave connective tables verified by the lead verifier to be exactly strong Kleene (commutative, associative, monotone in the knowledge order — computation C1). **025o** replaces this with a four-status space `{Supported, Refuted, Unknown, Conflicted}` carrying **no truth tables and no connective semantics**; **025t §45** then needs precisely 3-valued behaviour (missing premise → `Blocked`, not `False`) and re-derives it informally without citing R1–R14.
3. **Five-time temporal model → three times.** Step-016 established `T_e ≠ T_o ≠ T_k ≠ T_d` plus interval `T_v` (verified; computation C8). **025w §1** silently contracts this to three dimensions — and cannot keep even those stable, naming the third *KnowledgeTime* (§1), *AcceptanceTime* (§2) and *EpistemicStateTime* (§5) in one file. **Which two of the five were dropped, and why, is never discussed.**

**VERIFIER OBSERVATION:** these are not refinements. In each case a *formalised* construct is replaced by a *named* one. Under the mandate's T-scale this is a regression from T2 (Formalised) to T1 (Defined), executed silently — the inverse of the maturity ratchet the corpus claims elsewhere. **Severity: MAJOR for the survivor theory** — the final theory must take the *earlier* formalisation in all three cases and record the later files as non-superseding.
**Register sync:** AC C-070.

---

## Verifier observations (recorded, not findings)

1. **025k's closure claim is unfalsifiable as stated.** §23 asks whether `Update(K_t,E_t) ∈ 𝒦`, but **𝒦 is never constructed**; the 11 ✅ rows of the §48 closure table certify intentions, not properties. The three ❌ rows (universal monotonicity / commutativity / associativity) are honest, but associativity's ❌ carries **no argument at all** in 025k — it is inherited from 025j §47's deferral.
2. **025l's "convergence theorem candidate" is definitionally true.** `H_A=H_B ∧ Ω_A=Ω_B ∧ EC_A=EC_B ∧ M_A=M_B ⟹ Derive(…) ≡ Derive(…)` is function application; the real burdens (Derive *can* be deterministic; events eventually delivered; `≡` decidable) are assumed, asserted and absent respectively. The **CRDT analogy is used correctly** — as a property of the history layer only (025l §21, re-derived correctly in 025x §15).
3. **Two "normal-PC computable" boxed claims (025h §32/§41, 025j §51) are ASSERTED** — supported by primitive-name lists and one partitioning argument, and partially undercut by **025t §50's own concession** that constraint satisfaction is SAT/SMT-hard.
4. **025c-2 is the mathematical high point of the series:** eight hand-worked Bayesian values (LR 9.5, 4.5, 0.0625; posteriors 0.905, 0.977, 0.728) **all independently re-derived and confirmed correct** by the verification agent. It is also the only real computation in 33 files.
5. **Two honesty markers deserve the record.** 025a-1 line 5, verbatim: *"I attempted to execute the first prototype in the current environment, but the execution environment did not successfully complete the run. So I will not claim experimental results that I have not actually obtained."* And 025a-5 §29: *"We have not yet executed thousands of randomized transitions in an actual program in this response."* These are the corpus behaving correctly under the evidence rule — and they make the unconditional PASS verdicts of 025d–025g, stamped on layers built atop that unexecuted kernel, harder to defend (verdict strength rises as evidence strength falls).
6. **`DeriveContract(G,S)` exists in name only.** No `η` symbol appears anywhere in the series. It is **partial** on the file's own evidence (025e §21 `InvalidContract`; §31 `ECStatus=Conflicted`; §36 undefined pending a governance algebra), and its two hardest inputs — the authority order and NL-normalisation — are external oracles. This **corroborates TV-F-011 (η-as-total-function REFUTED) from the source side**.
7. **Evidence-tuple schism:** `E` is a 10-field epistemic tuple in 025n §1 and an 8-field cryptographic tuple in 025y §6 — same symbol, disjoint field sets, never reconciled.
8. **Execution across all 33 files of the 025-series: ZERO.** Every code fence is a `text` block; ~120 "falsification tests" are expected-behaviour statements stamped PASS against operators that have no definitions. Neither of the corpus's two genuinely executed artifacts (`134245`, `135038`) is referenced anywhere in the series.
