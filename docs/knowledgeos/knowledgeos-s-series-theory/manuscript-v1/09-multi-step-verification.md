# Chapter 9. Multi-step verification

*Status: INTERNALLY REVIEWED (same model family; refute-first review 2026-10-18 with an independent exhaustive check, 161,024 cases; all corrections applied). Not externally reviewed.*

**Research question (RQ6, processes).** In a process of several steps, with one append-only evidence record, when is every step's decision verifiable? Is the single-step criterion still the right one?

**Plain meaning.**
- Per step, the Ch. 8 criterion carries over, provided the auditor uses the **whole history of outputs**.
- **Across the whole process, the per-step analysis is not the right test**, for two reasons:
  - **(i) Earlier evidence carries forward.** Evidence an append-only record must already keep to verify earlier steps keeps separating those cases later. Earlier evidence can therefore **already cover** later decisions (CE 9.10). This is a statement about which condition is necessary, not a storage saving.
  - **(ii) Faults may be linked across steps.** For example, "either every step is honest or every step is wrong". Here **less evidence really does suffice** (CE 9.11).
- A per-step evidence policy is always **safe**, but can **over-demand**. Any saving depends on modelling the process's failure behaviour correctly.

## 9.1 Model

**DEF 9.1.**
- **Worlds:** w = (x̄, ō) ∈ W ⊆ (X × O)ⁿ. The **joint** fault model W may couple steps.
- **Prefixes:** X_{W,s} is the set of x-prefixes of length s; W_s is the set of world prefixes of length s.
- **Decisions:** d_s := F_s, a map on x-prefixes of length s, applied as d_s(x̄_s). The correctness bit is c_s := [o_s = d_s(x̄_s)].

**DEF 9.2 (append-only evidence).**
- An **evidence family** G = (G₁, …, G_n), with G_s a function of x̄_s, is **append-only** iff G_{s−1}(x̄_{s−1}) is a function of G_s(x̄_s). Equivalently, its kernel is in PC(L) for the prefix-closed language L = {ε} ∪ ⋃_s X_{W,s} (Prop. 2.13).
- Every PC equivalence is realized by some append-only family, namely the class log.

**DEF 9.3 (verifiability at step s).** Ver_s holds iff, on W_s, the view (G_s(x̄_s), ō_s) determines c_s. Here ō_s is the **whole** output history up to s.

**DEF 9.4 (s-confusable).** x̄, x̄′ ∈ X_{W,s} are **s-confusable** iff:
- d_s(x̄) ≠ d_s(x̄′); and
- some output history ō of length s, admissible with both (in W_s), has ō_s ∈ {d_s(x̄), d_s(x̄′)}.

## 9.2 Per-step characterization

**Theorem 9.5. THM.** Ver_s ⇔ G_s identifies no s-confusable pair.

*Proof.* By the argument of Theorem 8.6, with x replaced by x̄_s and o by ō_s, and with the correctness bit read on the **last** coordinate (c_s = [last(ō_s) = d_s]). Equal views require equal **whole** output histories, so the witness must be a common admissible output history. ∎

**Corollary 9.6 (determination ⇒ verifiability). THM.** If G_s determines d_s, then Ver_s holds, for every W.

## 9.3 Joint necessity

**DEF 9.7.**
- **(S-multi):** for every t, every d_t-distinct pair of X_{W,t} is t-confusable.
- **(S-joint):** for every t, every d_t-distinct pair (x̄, x̄′) of X_{W,t} is **forced**: for some s ≤ t, the length-s prefixes x̄_s and x̄′_s are s-confusable.

**Theorem 9.8 (joint characterization). THM.**
- **(S-joint) holds iff**, for **every** append-only G, (∀s Ver_s) ⇒ (∀s G_s determines d_s).
- The converse implication always holds (Cor. 9.6). So (S-joint) is exactly the condition under which "verifiable at every step" and "determining at every step" coincide for every append-only family.

*Proof.*
- **(⇐)** Assume (S-joint), and Ver_s for all s. Take a d_t-distinct pair. It is forced at some s ≤ t, so its length-s prefixes are s-confusable. By Theorem 9.5, G_s separates them. If G_t identified the pair, prefix-closure would make G_s identify the prefixes, a contradiction. So G_t determines d_t.
- **(⇒)** Suppose (S-joint) fails at t for (x̄, x̄′): for no s ≤ t are the prefixes s-confusable. Let G be the identity, except that it identifies x̄_s with x̄′_s for every s ≤ t at which they differ.
  - G is an equivalence: each level is the identity plus at most one two-element class.
  - G is in PC: the identified pairs' shorter prefixes are identified or equal.
  - For s ≤ t, Ver_s holds, because the only identified pair is not s-confusable (Thm 9.5).
  - For s > t, G is the identity, so it determines d_s, and Ver_s follows from Cor. 9.6. PC does not require right congruence, so dropping the identifications after t is legitimate.
  - But G_t identifies the d_t-distinct pair, so it does not determine d_t. ∎

**Corollary 9.9. THM.**
- (S-multi) ⇒ (S-joint) (take s = t).
- **The converse fails** (CE 9.10). Per-step confusability is **not** jointly necessary.

**CE 9.10 (earlier evidence carries forward). CE.**
- *Setting:* n = 2, binary. F₁: 0 → 1, 1 → 0. F₂: (0,1) → 0, (1,1) → 1. W = {((0,1),(0,1)), ((1,1),(0,0))}.
- *Step 1:* x₁ = 0 and x₁ = 1 have d₁ = 1 and 0, and share o₁ = 0, which equals one of them. So they are 1-confusable.
- *Step 2:* (0,1) and (1,1) have different d₂, but no common output history. So they are not 2-confusable, and (S-multi) fails.
- *(S-joint) holds:* any verifiable family separates x₁ at step 1, and so, being append-only, at step 2 as well.
- *No storage saving here:* G₂ = x₁ already determines d₂. The example shows only that (S-multi) is not necessary for the two notions to coincide.

**CE 9.11 (linked faults make later evidence unnecessary). CE.**
- *Setting:* n = 2, binary, F_t = x_t. The fault model is "all honest or all inverted": ō = x̄, or ō = 1 − x̄.
- *Evidence:* G₁ = x₁, and nothing new at step 2.
- *Result:* Ver₂ holds, because c₂ = c₁ and c₁ is determined at step 1. Yet G₂ does not determine d₂.
- *What per-step analysis would say:* the step-2 **marginal** fault model is unconstrained, so single-step analysis would demand x₂. That is wrong here.
- (S-multi) and (S-joint) both fail: (0,0) and (0,1) share x₁, have no common output history, and so are never confusable at any step.

## 9.4 Evidence

*(J) denotes the statement of Theorem 9.8: for all append-only G, (∀s Ver_s) ⇒ (∀s G_s determines d_s).*

| Check | Domain | Result |
|---|---|---|
| Theorem 9.8 (FIN, pre-declared protocol, by this manuscript's author) | n = 2, binary, random W, 3 × 2,000 instances | 0 mismatches between (J) and (S-joint); 424 strict instances (not re-checked by the Ch. 9 reviewer) |
| Theorem 9.8 (FIN, two independent checkers by two internal reviewers) | n = 2, binary, **every** F and every W with \|W\| ≤ 4 (161,024 cases) | 0 mismatches in both; 15,568 strict instances. One reviewer also checked 23,000 sampled cases, some with n = 3 |
| CE 9.11 control | — | (J), (S-joint) and (S-multi) all false |

**Limits.**
- Binary inputs, and n ≤ 3 (n = 3 sampled only). Generality rests on the proof.
- Records: `../synthetic-checks/f2-7/`, an **unverified pointer** for the Ch. 9 reviewer.

## 9.5 Limits and open problems

- Outputs depend on the input history only. Rules that read **past outputs** need a separate treatment (OPEN in this manuscript; earlier reports distinguish local from global correctness, with counterexamples both ways).
- Deterministic evidence only.
- **OPEN:** the interaction with corrections (Ch. 11) and with storage costs (Ch. 13). Do (S-joint) savings reduce retention cost under realistic fault models?

## 9.6 Traceability (ARCH)

| Result | Engineering consequence |
|---|---|
| Cor. 9.6 | safe default: per-step evidence that determines each decision |
| Thm 9.8 / CE 9.10 | evidence policies may be designed per process: earlier retained evidence can cover later decisions |
| CE 9.11 | savings from linked fault models require the fault model to be documented and trusted (STAKE) |
| Regression tests | CE 9.10 and CE 9.11 |
