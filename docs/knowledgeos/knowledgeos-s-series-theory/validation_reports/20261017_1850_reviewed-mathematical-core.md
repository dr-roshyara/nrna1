# Reviewed mathematical core (Task 5)

| Field | Value |
|---|---|
| Author | Session B (Professor), acting as integrator |
| Date | 2026-10-17 18:50 |
| Consolidates | 1805 report §3 (worker) · 1830 report (O-1/O-2, already integrated) · a third fresh-context reviewer sub-agent on 1805 §3–§4 |
| Review strength | **INTERNALLY REVIEWED** = a fresh-context sub-agent of the same model, working by hand. **Not independent validation; D6 remains required.** |
| Inputs | No corpus, no computation, no governed file edited |
| Supersedes | the corresponding rows of the 1805 report §4 index; the 1805 report itself is not edited |

## 1. One refutation, and how it was integrated

**E2 and R4(e) are REFUTED as stated; the conclusion survives, and is stronger.** The reviewer found further maximal admissible partitions that my 1805 "three maxima" claim missed.

*The new partition σ.*
- Strip the leading a's of each history and group by the first remaining symbol. The classes are a\* (including ε), "first non-a is b" and "first non-a is c".
- σ is a right congruence, and each class carries at most two first-symbol channels: {a}, {a,b}, {a,c}.
- σ is maximal, and it is below none of the three first-symbol partitions, because σ groups ε with a.
- Its mirror images give **at least six maxima**.

*Integrator check.* I re-derived σ by hand and agree:
- appending a symbol to a\* lands in a\*, the b-class or the c-class;
- the b- and c-classes are closed under appending;
- merging a\* with the b-class forces c ∼ bc, which gives three channels.

*Corrected statement of E2.*
- The cap policy has **at least six pairwise-incomparable maximal admissible partitions**.
- Its minimum state count is 3, and this minimum is not unique. The reviewer's 2-state pigeonhole argument is accepted pending D6.
- The policy is downward-closed but **not principal**, and **not a union of three principal ideals**.

**R4(d)** (a downward-closed family has a greatest element ⇔ it is principal) is unaffected. It is basic order theory, so its weight is "elementary".

## 2. Reviewed core table

Each row gives the corrected statement, the review outcome, and what it would mean for KnowledgeOS **if** Gate D confirms the assumptions.

### 2.1 Sufficiency and decidability

**C-0′(a): recursive sufficiency ⇒ predictive sufficiency (ker R ⊆ ∼).**
- Review: CONFIRMED.
- If confirmed: a kernel with a deterministic update cannot lose future decisions.

**C-0′(b): predictive sufficiency does not imply that the given map R admits an update.**
- Correction: "on the same state space" is replaced by "the map R itself". ∼ is always realizable on a carrier of R's size.
- Review: CONFIRMED WITH CORRECTIONS (the example uses a constant 𝔇).
- If confirmed: "predicts the answers" ≠ "can be maintained incrementally".

**C-0′(c): a snapshot R = d for "ends with ab" is not predictively sufficient.**
- Review: CONFIRMED.
- If confirmed: stored answers are not a kernel.

**C-1: uniform undecidability of ∼.**
- The problem is Π⁰₁-complete for uniformly total-computable d.
- Review: CONFIRMED.
- If confirmed: kernel comparison is algorithmic only for a restricted specification class.

**C-1⁺: a single fixed polynomial-time d can have an undecidable ∼.**
- Needs: a polynomial-time-decodable prefix-free code and a polynomial universal simulation.
- Decidable classes are given **examples only**: finite-state, bounded horizon, deterministic pushdown (via Sénizergues; literature, not verified).
- Review: CONFIRMED WITH CORRECTIONS.
- If confirmed: the specification class for 𝔇 becomes a requirements decision.

**C-6 / C-6′: a canonical quotient needs definedness to be observable.**
- Definedness can be made observable through a legality decision or through an observable ⊥.
- Treating undefined values as "don't care" gives a non-transitive compatibility relation and non-unique minimal machines (Paull–Unger).
- **The label "PROVED" is withdrawn**, because no reduction was stated. The NP-completeness result (Pfleeger 1973, for explicit finite machines) is LITERATURE, not verified.
- Review: CONFIRMED WITH CORRECTIONS.
- If confirmed: legality or definedness must be part of the model, not left implicit.

### 2.2 Components, compression and the gap order

**C-2 and the XOR counterexample: component necessity is relative; irredundant ≠ minimum.**
- Corrections:
  - **static** setting, with x and y ranging jointly;
  - in a dynamic setting {c₃} may not be updatable (for example, x := a needs y);
  - the irredundant sets induce **different** sufficient partitions, so "information content is fixed" holds only for ∼ itself.
- Reduct NP-hardness: LITERATURE.
- Review: CONFIRMED WITH CORRECTIONS.
- If confirmed: a schema of components is a design choice, constrained by the dynamics.

**C-3: no compression is possible ⇔ the joint ∼ of 𝔇 is the identity.**
- Correction: no single decision needs to require the full history. For example, d_k = "k-th symbol" already forces this.
- Review: CONFIRMED WITH CORRECTIONS.
- If confirmed: append-only full history is forced by the **joint** requirement set.

**C-5: the gap order is a preorder on states and a partial order on gap classes.**
- Correction: Zero is the top class **only if some state attains Δ = ∅**.
- Review: CONFIRMED WITH CORRECTIONS.
- If confirmed: Zero claims need an attainability argument.

### 2.3 Restricted-decoder results

**R1′: under invariance, admissibility of trim realizations depends only on the partition.**
- Corrections: define 𝒜 over **trim** realizations, or assume 𝔉 and 𝔊 are closed under restriction to the reachable part; index invariance across carriers.
- Review: CONFIRMED WITH CORRECTIONS.
- If confirmed: domain policies should be stated at the partition level.

**R2: a greatest element exists ⇔ ⋁𝒜 ∈ 𝒜; it then has minimum index, unique if g has finite index.**
- Correction: the 1805 binary-join remark was backwards. The exact condition is **(J2) + (JD)** (1830 report, A1′). Under the ascending chain condition, (J2) alone suffices.
- Review: CONFIRMED WITH CORRECTIONS.
- Meaning: a reformulation.

**R4(a)–(c): upward-closed policies are inert; separation policy D_ρ = Suff(𝔇 ∪ {d_ρ}); mixtures have greatest ∼′ when non-empty.**
- Review: CONFIRMED. (b) is stronger than stated: it holds as an equality.
- If confirmed: audit requirements are required decisions; anonymity-type rules decide only feasibility.

**R4(d): downward-closed ⇒ (greatest ⇔ principal).**
- Review: CONFIRMED (elementary).
- Gap: convex, interval and mixed non-principal policies are not covered.

**E1: encoding dependence of a single-bit decoder (static).**
- Review: CONFIRMED.

**E2 (corrected): ≥ 6 maxima; minimum 3 states, not unique; not principal.**
- Review: REFUTED as stated; corrected.
- If confirmed: cap-type policies destroy the uniqueness of the kernel.

**E3′: trim realizations meet history-counted k-anonymity ⇔ every attained value has ≥ k histories.**
- Correction: with unreachable padding, the state-counted version is **always** satisfiable.
- Review: CONFIRMED WITH CORRECTIONS.
- If confirmed: privacy rules must count histories, actors or outputs.

### 2.4 Evidence claims and the 1830 results

**B6: 6/6 gives a one-sided Clopper–Pearson lower bound ≈ 0.607 (two-sided ≈ 0.54), only under i.i.d. sampling; T-7 refutes an identity if the witness is valid.**
- Review: CONFIRMED.
- If confirmed: these are existence results only.

**A1–A6, B1–B4 (1830 report).**
- Review: CONFIRMED WITH CORRECTIONS, already applied.

## 3. The state of the theory, after review

**INTERNALLY REVIEWED: the deterministic core.**
1. The kernel is the Nerode quotient of the required decisions. It needs determinism, complete inputs and observable definedness.
2. It stays the unique, fewest-state minimum under:
   - anonymity-type policies;
   - audit and separation policies (which act as extra decisions);
   - quotient-closed update policies;
   - hereditary decoder policies.

**Breaks uniqueness or existence:**
- cap policies (E2, corrected);
- infinite-state reversible updates (A3);
- history-counted anonymity with small classes (E3′).

**OPEN:**
- convex or mixed non-principal policies;
- encoding-level update restrictions;
- whether the static examples (E1, XOR) survive under imposed dynamics. This is a new item from the reviewer: **O-7**.

**Unchanged.**
- No claim about KnowledgeOS itself is primary-source verified.
- **None of this is independently validated (D6).**

## 4. Corrections the manuscript patch proposal must carry (D4)

These are additions to the 1805 and 1830 lists:
- C-0′(b) wording;
- C-1⁺ assumptions, and examples rather than a list;
- C-6′ status downgraded (proof withdrawn; literature only);
- C-2 static/dynamic and the partition point;
- C-3 "joint ∼";
- C-5 attainability;
- R1′ trim/restriction;
- R2 (J2)+(JD);
- E2 corrected;
- E3′ trim/padding.

Nothing is applied until D3 and D4 are decided.
