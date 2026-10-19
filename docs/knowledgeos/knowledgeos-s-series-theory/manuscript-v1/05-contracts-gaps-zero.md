# Chapter 5. Contracts, gaps and Zero: an evaluation layer

*Status: INTERNALLY REVIEWED (same model family; refute-first review 2026-10-18; all corrections applied). Not externally reviewed.*

**Research question (RQ3).** The corpus evaluates a knowledge state against an "epistemic contract". The **gap** is the set of unmet requirements; **Zero** means no gap remains. What structure does this evaluation have, and how does it relate to the canonical state?

**Plain meaning.**
- A contract is a list of requirements. **Under Assumption 5.2**, whether each requirement is met is itself a required decision, so the gap is computed from the canonical state.
- Adding a contract adds decisions, which **refines the canonical state**, and so does each new contract version.
- "Fewer unmet requirements" orders states only **partially**, and only **for one contract at a time**. It measures contract satisfaction, not knowledge quality.
- It does not constrain how the state may change: corrections can enlarge the gap.
- A claim of Zero is checkable because it can be **recomputed**. A Zero claim that disagrees with the recomputation is a detectable error.

*Corpus background (SRC; via Session A's family summaries, not primary-verified):* Gap(K, EC) = {r ∈ Req(EC) : ¬Sat(K, r)}; Zero ⇔ Gap = ∅; an order "K′ ≥_EC K ⇔ Gap(K′) ⊆ Gap(K)"; contract invariance; FalseZero ⇔ "Zero claimed while Gap ≠ ∅".

## 5.1 Contracts as decisions

**DEF 5.1.** A **contract** is a pair c = (Req_c, Sat_c):
- Req_c is a set of requirements;
- Sat_c : L × Req_c → {0, 1} is a satisfaction predicate.

Its **gap** at h is Δ_c(h) := {r ∈ Req_c : Sat_c(h, r) = 0}. h is **Zero for c** iff Δ_c(h) = ∅.

**Assumption 5.2 (ASM).** For each r ∈ Req_c, the map h ↦ Sat_c(h, r) is a required decision: it belongs to 𝔇.

*Consequence of the assumption (THM, Prop. 6.6):* adding these maps refines ∼ to ∼ of 𝔇 ∪ {Sat_c(·, r)}. So a new contract, or a new contract version, **changes the canonical state**.

**Proposition 5.3. THM.** Under Assumption 5.2:
- Δ_c is a function of the canonical state: h ∼ h′ ⇒ Δ_c(h) = Δ_c(h′), for legal h, h′.
- For every continuation w with hw ∈ L, Δ_c(hw) is determined by ([h], w). Whether hw ∈ L is itself determined by ([h], w), through λ.

*Proof.* Each Sat_c(·, r) is in 𝔇, so it is constant on ∼-classes (Prop. 3.2(c)). The future statement follows directly from DEF 3.1. ∎

**Consequence (ARCH).** Provided Assumption 5.2 covers every contract version in use, a gap or Zero value **need not** be stored as authoritative state. It is a **derived evaluation** over (canonical state, contract version), and can be recomputed.

## 5.2 The order induced by a contract

**DEF 5.4.** For a fixed contract c, define h ≼_c h′ ("h′ satisfies c at least as well as h") iff Δ_c(h′) ⊆ Δ_c(h).

**Proposition 5.5. THM.**
- (a) ≼_c is a preorder on legal histories, and hence on the legal classes L/∼. The illegal sink class has no gap.
- (b) It is a partial order on **gap classes** {h : Δ_c(h) = G}. It is **not** in general a partial order on states.
- (c) A greatest gap class exists iff a ⊆-least attained gap exists. In particular, **Zero is the top class iff some history is Zero.**
- (d) ≼_c is generally not total.

*Proof.*
- **(a)** ⊆ is reflexive and transitive.
- **(b)** Antisymmetry on gap classes follows from antisymmetry of ⊆.
  - *CE on states:* take Σ = {a, b}, L = Σ\*, one requirement r with Sat ≡ 1, and an extra decision d′ = [last input is a] ∈ 𝔇.
  - Then "a" and "b" have equal gaps (∅) but different canonical states, so they are mutually ≼_c-related without being equal.
- **(c)** The ⊆-least attained gap is the top class. ∅ is the least possible gap, so Zero is the top class iff it is attained.
- **(d)** *CE:* with Req_c = {r₁, r₂}, gaps {r₁} and {r₂} are incomparable. ∎

**What the order is not:**
- **Not knowledge quality.** It measures one contract's requirements only (ANA).
- **Not comparable across contracts.** For contracts c ≠ c′ the gaps live in different requirement sets, so there is no comparison without a declared mapping. Overlapping requirement sets can be compared on the shared requirements once such a mapping is declared. *ANA:* this matches our reading of the corpus's "contract invariance" (SRC).
- **Not a monotonicity constraint.** Transitions may decrease it: a correction can re-open a requirement.

**CE 5.6 (corrections can enlarge the gap).**
- *Setting:* Σ = {assert, retract}, L = Σ\*, with one requirement r whose satisfaction is Sat(h, r) = [#assert(h) − #retract(h) > 0].
- assert gives gap ∅; assert·retract gives gap {r}.
- So the order does not constrain the dynamics. This is consistent with the corpus's non-monotonicity (SRC S-10).

## 5.3 Zero claims and their verification

**DEF 5.7.** A **Zero claim** at h is a recorded statement "Δ_c(h) = ∅". A claim is **false** iff Δ_c(h) ≠ ∅.

**Proposition 5.8. THM (nearly immediate).** Under Assumption 5.2, a Zero claim at h can be checked by anyone who can compute Δ_c from the canonical state at h (or from the history).

*Proof.* By Prop. 5.3, Δ_c is a function of [h]. ∎

**Forward reference (conditional).** Whether a *third party* can check the claim depends on what is **retained** and **trusted**. That is the subject of Part III. A recorded Zero claim is a recorded decision, so it is verifiable **under the conditions established in Ch. 8**, not here.

## 5.4 Limitations

- **Assumption 5.2 may fail.** Some requirements may need information outside the history, such as an external judgement. Then the gap is not a function of the state, and the requirement is effectively an **external decision**. Ch. 8's trust model then applies.
- **Weighted or graded requirements.** If requirements are weighted or graded rather than met/unmet, the set-inclusion order must be replaced and Prop. 5.5 re-proved (**OPEN**). The corpus's exact definition must be checked against the primary source (Ch. 16).
- **The corpus constructs are SRC:** the ten-class gap taxonomy and FalseZero governance are not used as established facts.

## 5.5 Traceability (ARCH)

| Result | Engineering consequence |
|---|---|
| Prop. 5.3 | Gap and Zero **can** be computed as queries over (state, contract version); they need not be stored as authority (given Assumption 5.2 for every version) |
| Prop. 5.5 | ARCH/STAKE guidance: avoid presenting a single "knowledge score" across contracts; the theory supplies no such scalar |
| CE 5.6 | a regression test: a retraction re-opens the gap |
| §5.3 | a recorded Zero claim is a decision record subject to Ch. 8 |
