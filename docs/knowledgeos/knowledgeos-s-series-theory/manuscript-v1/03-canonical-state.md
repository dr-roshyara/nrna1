# Chapter 3. Behavioural equivalence and the canonical state

*Status: INTERNALLY REVIEWED (same model family; refute-first review 2026-10-18; all corrections applied). Not externally reviewed.*

**Research question (RQ1).** Which internal state answers every required decision now and after every future input? Is it unique, and can it be computed?

**Plain meaning.**
- Two histories should lead to the same state exactly when no required decision can ever tell them apart, now or after any further inputs. Grouping histories this way gives the **canonical state**.
- It is the **smallest** state that works, and it is unique up to renaming.
- It can be computed when the decisions are specified as finite-state machines. In general it cannot.
- Merely "predicting" the answers is weaker than being **maintainable step by step**.

## 3.1 Behavioural equivalence

**DEF 3.1.** For a decision set 𝔇 (Ch. 2, with OD), define h ∼ h′ iff, for every continuation w ∈ Σ\* and every d ∈ 𝔇, d̂(hw) = d̂(h′w).

We write ∼ for ∼_𝔇.

**Proposition 3.2. THM.**
- (a) ∼ is an equivalence relation.
- (b) ∼ is a right congruence.
- (c) ∼ ⊆ ker d̂ for every d.
- (d) ∼ is the **largest** right congruence contained in ⋂_d ker d̂.

*Proof.*
- **(a)** Inherited from equality.
- **(b)** Let h ∼ h′ and a ∈ Σ. For every w, the word aw is a continuation, so d̂(haw) = d̂(h′aw). Hence ha ∼ h′a.
- **(c)** Take w = ε.
- **(d)** Let π be a right congruence with π ⊆ ⋂ker d̂, and let (h, h′) ∈ π. By right congruence, (hw, h′w) ∈ π for every w, by induction on |w|. So d̂(hw) = d̂(h′w), and hence h ∼ h′. ∎

*Remark (legality). THM.* Since λ ∈ 𝔇 (Ch. 2 convention), h ∼ h′ implies that h and h′ have the same set of legal continuations.
- When L ≠ Σ\*, the illegal histories form a single class, a "sink".
- That class is never equivalent to a legal history.
- Being closed under appending, a non-empty set of illegal histories is infinite.

## 3.2 Two notions of sufficiency

**DEF 3.3.**
- A map R on Σ\* is **predictively sufficient** iff ker R ⊆ ∼. That is, R(h) determines every future decision value: d̂(hw) is a function of (R(h), w).
- R is **recursively sufficient** iff it is the run of a sufficient realization (DEF 2.7).

**Theorem 3.4 (recursive ⇒ predictive). THM.** If R is the run of a sufficient realization, then ker R ⊆ ∼.

*Proof.* By Lemma 2.9, ker R is a right congruence. By sufficiency, d̂ = f_d ∘ R, so ker R ⊆ ker d̂. By Prop. 3.2(d), ker R ⊆ ∼. ∎

**CE 3.5 (predictive does not mean maintainable).**
- *Setting:* L = Σ\* = {a}\*, and 𝔇 = {λ} with λ ≡ 1, so ∼ is the total relation. Let R(aⁿ) = 0 if n ∈ {0, 2}, and R(aⁿ) = n otherwise.
- ker R ⊆ ∼ trivially. But R(ε) = R(aa), while R(a) = 1 ≠ 3 = R(aaa).
- So ker R is not a right congruence, and no update u with R(ha) = u(R(h), a) exists.
- *Remark:* ∼ itself **is** realizable on a carrier no larger than R's image, because ker R ⊆ ∼ gives an onto map from im R to Σ\*/∼. The failure is in **the map R**, not in the information it carries.

**CE 3.6 (a snapshot of the current answer is not a state).**
- *Setting:* L = Σ\* with Σ = {a, b}, and a decision d(h) = [h ends with "ab"]. Let R = d.
- R(a) = R(b) = 0, but d(ab) = 1 ≠ 0 = d(bb). So ker R ⊄ ∼.
- R is not even predictively sufficient. A state must remember at least "the last input was a".

## 3.3 The canonical realization

**DEF 3.7.** The **canonical realization** C_𝔇 = (Σ\*/∼, [ε], u_∼, f_∼) is given by u_∼([h], a) := [ha] and f_∼,d([h]) := d̂(h).

**Theorem 3.8 (existence, minimality, uniqueness). THM.**
- (a) C_𝔇 is well-defined, trim and sufficient.
- (b) For every sufficient realization R, the map β_R : s(h) ↦ [h] is a well-defined, onto homomorphism from the trim part of R to C_𝔇.
- (c) Among sufficient realizations, the kernel ∼ is the coarsest: ker s ⊆ ∼, with equality iff β_R is a bijection.
- (d) Any two **trim** sufficient realizations whose kernel equals ∼ are isomorphic. If Σ\*/∼ is finite, every trim sufficient realization has at least |Σ\*/∼| states, with equality iff it is isomorphic to C_𝔇.

*Proof.*
- **(a)** u_∼ is well-defined by Prop. 3.2(b). f_∼,d is well-defined by Prop. 3.2(c). The run of C_𝔇 is h ↦ [h], which is onto, so C_𝔇 is trim. Sufficiency holds by construction.
- **(b)** Well-defined: if s(h) = s(h′), then h ∼ h′ by Thm 3.4. Onto: every class [h] is hit by s(h). Homomorphism: β(u(s(h), a)) = β(s(ha)) = [ha] = u_∼([h], a), and f_∼,d(β(s(h))) = d̂(h) = f_d(s(h)). The initial state maps to [ε].
- **(c)** ker s ⊆ ∼ by Thm 3.4. Equality holds iff s(h) = s(h′) ⇔ h ∼ h′, i.e. iff β is injective on the trim part.
- **(d)**
  - *Equal kernels:* if ker s = ker s′ = ∼, then β_{R′}⁻¹ ∘ β_R is a bijection that commutes with updates and decoders.
  - *Finite case:* β_R is onto, so |S_trim| ≥ |Σ\*/∼|. If the two are equal, an onto map between finite sets of the same size is a bijection, and a bijective homomorphism is an isomorphism.
  - *Infinite case:* if Σ\*/∼ is infinite, the cardinality bound still holds, but equal cardinality does **not** imply isomorphism. Only the kernel criterion of (c) applies. ∎

*Why trimness matters (CE).* Adding unreachable states to C_𝔇 gives a sufficient realization with the same kernel that is not isomorphic to C_𝔇. Uniqueness therefore holds only for **trim** realizations.

## 3.4 What happens when the assumptions fail

- **DET (ANA).** If a decision depends on something other than the history, d̂ is not a function on Σ\*, and DEF 3.1 is undefined. Ch. 6 treats nondeterminism.
- **IC (ANA).** If a decision reads an unrecorded datum (a clock, an external oracle), then two histories that are identical as input sequences may need different answers. No function of the history can be sufficient.

**DEF 3.8a (partial specification).** A **partial specification** gives values d(h) on a set Dom ⊆ Σ\*. A realization **meets** it iff f_d(s(h)) = d(h) for every h ∈ Dom.

**CE 3.9 (without OD, canonicity fails; in fact no minimum exists).**
- *Setting:* Σ = {a, b}. The specification is d(a) = 0 and d(b) = 1, with every other history (including ε) **don't care**. Dom = {a, b} is not a legal language.
- *Lower bound:* any realization must separate the states after a and after b, so it needs at least 2 states.
- *Realization R₀:* state 0 for ε and for every history ending in a; state 1 for every history ending in b; decoder = state; next state = the last letter. It meets the specification.
- *Realization R₁:* the same, but ε is in state 1. It also meets the specification.
- *Not isomorphic:* an isomorphism must preserve the decoder's value at s₀, which is 0 in R₀ and 1 in R₁.
- *Scale:* a brute force by the reviewer finds 16 two-state realizations meeting the specification, with 8 distinct kernels.
- *Conclusion:* **no minimum exists.** Any common homomorphic image of R₀ and R₁ would be a 2-state realization isomorphic to both, which is impossible.
- *Literature context (LIT, unverified):* minimizing incompletely specified machines is computationally hard; this is attributed to Pfleeger (1973).
- *Remedy:* OD, i.e. a legal language L containing ε, with every value on L **specified** (including d(ε)) and ⊥ outside L. Canonicity then follows from Thm 3.8. **It is restored by that additional specification choice**, not for free.

## 3.5 Computability

**Proposition 3.10 (finite-state specifications are computable). THM.**

*Setting.* Suppose 𝔇 is finite and each d̂ is the output of a finite deterministic Moore machine M_d over Σ, and Σ is finite.

*Claim.* Then ∼ is decidable, and C_𝔇 can be constructed.

*Proof.*
- Let P be the reachable part of the product machine ∏ M_d. It is finite, its output is the vector 𝔇, and its run p satisfies ker p ⊆ ∼ (Thm 3.4).
- *Computing P:* P, the reachable part, is computed by breadth-first search from the initial state. This needs Σ finite.
- *Refinement:* refine a partition of P, starting from "equal output", by splitting blocks whose successors under some letter lie in different blocks. Each split increases the number of blocks, and the count is bounded by |P|, so there are at most |P| − 1 splits.
- *Invariant:* every congruence on P that respects outputs refines the current partition. This holds initially, and is preserved by each split, since a respecting congruence cannot relate states whose successors are separated. So the stable partition ≈ is the **largest** output-respecting congruence on P.
- *Quotient = C_𝔇:* define π := {(h, h′) : p(h) ≈ p(h′)}.
  - π is a right congruence inside ⋂ker d̂, so π ⊆ ∼ by Prop. 3.2(d).
  - Conversely, ∼ pushed forward to P is well-defined (because ker p ⊆ ∼), and it is defined on **all** of P because P is reachable. It is an output-respecting congruence, so it is contained in ≈.
  - Hence π = ∼, and P/≈ ≅ C_𝔇.
- *Deciding:* h ∼ h′ iff p(h) ≈ p(h′). ∎

*Literature:* this is the classical Moore/Hopcroft minimization.

**Theorem 3.11 (undecidable in general). THM.**

*Statement.* The following promise problem is Π⁰₁-complete. Given a Turing machine M promised to compute a total decision d_M over a finite Σ (with decidable equality of values), and histories h, h′: decide whether h ∼ h′.

*Proof.*
- **In Π⁰₁:** h ∼ h′ is "∀w : d̂(hw) = d̂(h′w)", and the matrix is decidable under the promise.
- **Hard:** reduce from non-halting. Given a machine N (run on empty input), let Σ = {0, 1}, L = Σ\*, and let d_N(1w) = 1 iff N halts within |w| steps, and d_N(·) = 0 otherwise. d_N is total and computable. Then "0" ∼ "1" iff N never halts. ∎

**Theorem 3.12 (a single efficient decision can have undecidable equivalence). THM (with stated encoding assumptions).**
- *Encoding assumptions:* machines are encoded by a prefix-free code that can be parsed in polynomial time, and a universal machine simulates t steps in time polynomial in t and the code length.
- *Construction:* define d(x · c · w) = 1 iff x encodes a machine N_x, c = 1, and N_x halts within |w| steps; otherwise d = 0.
- *Result:* d is computable in polynomial time, and for every valid codeword x, x0 ∼ x1 iff N_x never halts. So ∼ is undecidable for this one fixed d. (This also gives the hardness half of Thm 3.11.) ∎

*Consequence (ANA / ARCH).*
- Whether two histories (or two candidate kernels) behave the same can be decided mechanically only when 𝔇 is specified in a class with decidable equivalence.
- Finite-state is one such class (Prop. 3.10).
- *Possibly another (LIT, unverified):* deterministic pushdown languages, by Sénizergues's theorem. The extension to Moore-type decision outputs is not shown here.
- **Choosing the specification language for 𝔇 is a requirements decision (STAKE/ARCH).**

## 3.6 When no compression is possible

**Proposition 3.13. THM.** C_𝔇 is the identity on histories (no two distinct histories share a state) iff ∼ is the identity relation on Σ\*, i.e. iff every pair of distinct histories is distinguished by some decision after some continuation.

*Proof.* This holds by DEF 3.7. ∎

*Remark.* If L ≠ Σ\*, the illegal histories collapse into one infinite sink class (§3.1). So "∼ is the identity" forces L = Σ\*.

**CE 3.14 (no single decision needs to be "replay everything").**
- *Setting:* L = Σ\* with Σ = {a, b}, and two decisions:
  - d₁(h) = the first symbol of h, with the value "none" for ε (a value in V_{d₁}, not ⊥);
  - d₂(h) = h with its first symbol removed, with d₂(ε) := ε.
- *Each alone is not enough:* d₂ merges "a" and "b", and d₁ merges "aa" and "ab".
- *Together they are:* jointly they separate every pair (take w = ε), so ∼ is the identity.
- *Meaning (modest):* the requirement to keep the full history can arise from a **combination** of requirements, none of which is "replay everything" by itself. In this example d₂ already keeps almost everything; it illustrates the logic, not a realistic case.

## 3.7 Connection to the corpus (SRC, conditional)

**The six requirements, mapped conditionally (ANA).** Suppose that:
- the four decision-type requirements (invariants, legality, identity, equality) are members of 𝔇;
- "deterministic replay" is read as **fold-realizability**, i.e. recursive sufficiency (Thm 3.4); and
- "smallest" is read as the **coarsest kernel**, rather than representation size.

Then the corpus's "canonical K" is C_𝔇.

**Unmapped.** "Execute transformations" is not mapped. Transformations produce states or effects, not decision values, and need their own treatment (the five core operations are SRC; see Ch. 16).

**Interpretation.** Each reading above is an interpretation. The corpus may intend representation size by "smallest", which is a different objective (Ch. 13).

**Ordering tension.** The corpus presents the chain "K, then invariants 𝓘, then operations 𝓞" (SRC). By DEF 3.1, C_𝔇 is **determined by** (Σ, 𝔇), so fixing 𝔇 (invariants included) comes first. This is a **conditional** tension, since it presupposes the behavioural definition. The corpus also contains a contract-first ordering (SRC, Gap theory).

**Operation identity (SRC S-3).** Suppose two operations have the same effect on state but must be told apart by provenance or authority.
- **Under IC**, provenance and authority are part of the input letters.
- Some decision in 𝔇 then depends on the path, so the relevant path information enters ∼ automatically. No separate mechanism is needed.
- Without IC, the distinction cannot be made by any function of the history.

## 3.8 Limitations and consequences

**Limitations.**
- DET, IC and OD are assumptions. Ch. 6 relaxes DET. Ch. 16 lists the checks of IC and OD against the real system.
- Theorems 3.11 and 3.12 are worst-case statements. They say nothing about typical specifications.

**Consequences for later chapters.**
- Ch. 4 asks what happens when not every realization is permitted.
- Ch. 7 uses Lemma 2.6 and Prop. 2.13. Its resumable variant uses C_𝔇.

**Traceability (ARCH).**

| Theory item | Engineering counterpart |
|---|---|
| Thm 3.8 | the kernel may be implemented as a fold over the event log; regression test: two histories with equal canonical state give equal answers to every decision, after random continuations |
| CE 3.6 | "store the current answer only" is not a valid state design |
| CE 3.9 | decision specifications must define behaviour on illegal histories (observable ⊥) |
| Thm 3.12 | decision specifications should use a language with decidable equivalence, if kernels are to be compared automatically |
