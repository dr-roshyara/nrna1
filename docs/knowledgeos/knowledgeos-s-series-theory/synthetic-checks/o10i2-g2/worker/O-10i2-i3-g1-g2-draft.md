# O-10i2, O-10i3, O-10g1, O-10g2 — worker draft (workerC)

| Field | Value |
|---|---|
| Clock | 2026-10-18 05:5x |
| Status | WORKER DRAFT, unreviewed. A separate refute-first review is expected. |
| Read | Only: O-10a §1–3 and §8; O-10i; O-10g; O-10f1–f4 §4 (and its §0 header). Nothing else from the repository. |
| Protocol | `PROTOCOL.md` sha256 `70cbe131bd0b7451f13a033da4eabc6a46c650f224ece9bbc21676dcb2e9c98a`, frozen 2026-10-18 05:52:12 CEST, before any code was written |
| Code | `checks.py` sha256 `12faabe6b46402328f4e7dfca343ffdf456280bb0c4a42ea2a8b4a1ff7db3d59`, hash recorded before the first run |
| Results | `RESULTS.txt` sha256 `84a4a92da49bb3e2599f0f5732e43b8aac9fee2aaddd5d97a5402335bb9612f8` (Python 3.13.2, Linux 7.2.8 Fedora 44; run as `python3 -I -B checks.py`) |
| Disclosure | Before the first run, I found and fixed one bug in K6(d) (the diff entry used `range(len(g)-1)` and the wrong index for the new value). The code hash above is the hash **after** the fix and before any run. No run happened before the fix. The protocol was not changed. |
| Labels | DEFINITION · PROVED THEOREM (worker, unreviewed) · ANALYTIC ARGUMENT · EXPLICIT COUNTEREXAMPLE · FINITE COMPUTATIONAL CHECK · EXPLORATORY · STAKEHOLDER DECISION |
| Evidence weight | K1, K2, K4, K5 and K6 guard hand proofs and were written by the same author. They can catch mistakes in the algebra or in the code, but they are **not independent evidence**. K3 is exploratory. |

## 0. Setting (DEFINITION)

- **Histories.** L ⊆ Σ\* is prefix-closed, s = |Σ|, and h_u is the length-u prefix of h. The finite checks use L = Σ^{≤n}.
- **Decisions.** D(h_u, t) for t ≤ u takes values in a finite set V, with m = |V| ≥ 2 and b = ⌈log₂ m⌉. vec(h_u) := (D(h_u, t))_{t≤u}.
- **Requirement (P_bi).** The record at u determines vec(h_u). By O-10i B1 (Theorem 2 applied to the vector-valued decision vec), the greatest append-only record preserving (P_bi) has kernel
  h ≡\*_bi h′ ⇔ |h| = |h′| ∧ ∀u ≤ |h| : vec(h_u) = vec(h′_u).
- **PC.** PC is the set of length-preserving, prefix-closed equivalences on L (O-10a Prop. 0). Every π ∈ PC is a tree of classes:
  - the parent of a class at level t+1 is well defined;
  - c_π(C) is the number of child classes of C.
- **Encoding schemes.** Every storage statement below names exactly one of these:
  - **NODE.** The per-node integer cost along h is cost_π(h) = Σ_{t<|h|} ⌈log₂ c_π([h_t]_π)⌉.
  - **GW.** One global fixed width, GW(π) = ⌈log₂ max_C c_π(C)⌉ over the nodes at levels < n. Total log length is n·GW.
  - **GLOBAL.** A per-length global code ⌈log₂ N_n(π)⌉, where N_t is the number of classes at level t. **This code is not append-only** (O-10a §8).
  - **FL-plain.** The full bitemporal entry at u is (u+1)·b bits, one fixed-width value per t.
  - **FW-cf.** Context-free fixed width at position u: ⌈log₂ (number of syntactically possible entries at u)⌉. The decoder does not use the past.
  - **VL-list.** The difference entry at u is a count field of ⌈log₂(u+1)⌉ bits, then, for each changed t, an index of ⌈log₂ u⌉ bits and a value of b bits, then the new value D(h_u, u) in b bits.
  - **VL-list-k.** The same as VL-list, but under a horizon k. The index is the offset u − t ∈ {1..min(k,u)}, in ⌈log₂ min(k,u)⌉ bits, and the count field is ⌈log₂(min(k,u)+1)⌉ bits.
  - Convention: ⌈log₂ 1⌉ = 0.

---

## 1. O-10i2: correction horizons

### 1.1 Definitions (DEFINITION)

- **Horizon k as a property of D (H_k).** For all h ∈ L, all u ≤ |h|, and all t < u − k: D(h_u, t) = D(h_{u−1}, t).
  - Equivalently, by induction on u: D(h_u, t) = D(h_{t+k}, t) whenever u > t + k. A decision for t can change only at the times t+1, …, t+k.
  - k = 0 means no corrections at all. k ≥ n (for histories of length ≤ n) means no restriction.
- **Horizon k as a policy.** D^k(h_u, t) := D(h_{min(u, t+k)}, t). D^k satisfies H_k, and D^k = D iff D satisfies H_k.
- **Window entry.** W_k(h_u) := (D(h_u, t))_{max(0, u−k) ≤ t ≤ u}. The **window log** is r_k(h) := ⟨W_k(h_0), …, W_k(h_{|h|})⟩.

**STAKEHOLDER DECISION.** Two things are policy, not mathematics:
- the value of k;
- whether the horizon is enforced as a property of the decision process (rules and evidence procedures never reach further back) or as a truncation policy applied to a process that would reach further.

The horizon also interacts with retroactive rule changes (O-10i B4). Under H_k, a rule change at u can re-evaluate only t ≥ u − k.

### 1.2 Theorem H1: the greatest record under a horizon (PROVED THEOREM; K1 guards it)

*Claim.* If D satisfies H_k, then ker r_k = ≡\*_bi. Hence the window log is the greatest append-only record preserving (P_bi), unique up to a bijective recoding of entries.

*Proof.*
- (≡\*_bi ⊆ ker r_k). W_k(h_u) is a sub-vector of vec(h_u).
- (ker r_k ⊆ ≡\*_bi). Let r_k(h) = r_k(h′), and fix u ≤ |h| and t ≤ u.
  - If t ≥ u − k, then D(h_u, t) is a component of W_k(h_u) = W_k(h′_u).
  - If t < u − k, then H_k gives D(h_u, t) = D(h_{t+k}, t). Since t + k < u and t ≥ (t+k) − k, this value is a component of W_k(h_{t+k}) = W_k(h′_{t+k}), and it equals D(h′_u, t) by H_k applied to h′.
  - So vec(h_u) = vec(h′_u) for every u.
- ker r_k is therefore equal to ≡\*_bi. Greatestness and uniqueness up to recoding follow from O-10i B1. ∎

*Check.* K1 (s = m = 2, n = 4, 300 random H_k-processes for each k ∈ {0,1,2}) found 0 mismatches.

*Power control.* Dropping the oldest window slot (t = u−k) breaks the equality in 298/300 instances (k=1) and 272/300 (k=2). So K1 can detect an off-by-one window.

### 1.3 Entry-size bounds as a function of k

| Scheme | Per-step entry size under H_k (u ≥ k) | Label |
|---|---|---|
| Window log, FL-plain per slot | (k+1)·b, independent of u | PROVED (direct count) |
| Window log, FW-cf | ⌈(k+1) log₂ m⌉ | PROVED |
| Difference log, VL-list-k | at most ⌈log₂(k+1)⌉ + k(⌈log₂ k⌉ + b) + b, and at least ⌈log₂(k+1)⌉ + b | PROVED (direct count; at most k changed t's lie in the window [u−k, u−1]) |
| NODE (any kernel-equivalent log) | ⌈log₂ c⌉ with c ≤ m^{min(u,k)+1}, so at most ⌈(k+1) log₂ m⌉ | PROVED: given the past, only the k+1 window values can be new |
| NODE, tightness | there is a D satisfying H_k with cost exactly ⌈(k+1) log₂ m⌉ at every u ≥ k | EXPLICIT EXAMPLE (analytic; not computationally checked) |

**Tightness example.**
- Take Σ = V^{k+1}, so that letter a_u = (a_u[0], …, a_u[k]).
- Set D(h_0, 0) = 0. For u ≥ 1, set D(h_u, t) = a_σ[σ − t] with σ = min(u, t+k).
- This D satisfies H_k, because D(h_u, t) depends only on h_σ with σ ≤ t + k.
- For u ≥ k, the whole window at u equals the letter a_u. So every node has m^{k+1} children.

**Total revision volume (PROVED, under H_k).**
- Each decision for t has at most k+1 versions: the historical one and at most k corrections.
- So a history of length n carries at most (k+1)(n+1) decision versions.
- Without a horizon, the full entry grows as Θ(u) (O-10i size note) and the VL-list entry grows as Θ(u log u) in the worst case (see §3).

**Assumptions needed for the bound (stated, not derived).**
1. V is finite.
2. There is one decision per time step. Several decisions per step are handled by taking V as a product, and m grows accordingly.
3. Each value D(h_u, t) is a single value. Concurrent corrections are already serialized or merged into the history (O-10i B5: the arrival order or arrival set is part of the input).
4. Rule changes count as corrections and are subject to the same horizon.
5. The horizon is measured in steps of the history.

**A weaker assumption that is not enough.** Bounding only the number of corrections per step by c, with no horizon, keeps the VL-list entry at c(⌈log₂ u⌉ + b) + ⌈log₂(u+1)⌉ + b. That still grows with u, because the index names absolute times. Only a bounded **reach** (a horizon) makes the per-step entry independent of u under VL-list. *(ANALYTIC: direct from the scheme.)*

### 1.4 Proposition H3: the horizon is monotone in information (PROVED THEOREM; K2 guards it)

*Claim.* For k ≤ k′, ≡\*_bi(D^{k′}) ⊆ ≡\*_bi(D^k). A smaller horizon gives a coarser, or equal, greatest record.

*Proof.*
- D^k(h_u, t) = D(h_{min(u,t+k)}, t). Since min(u, t+k) ≤ t + k′, this equals D^{k′}(h_{min(u,t+k)}, t).
- So D^k's vector at u is a function of D^{k′}'s vectors at times ≤ u. Equality of the D^{k′} logs therefore implies equality of the D^k logs. ∎

*Check.* K2 (300 random unrestricted D, n = 4, all pairs k < k′ ≤ 4) found 0 violations, and D^4 has the same kernel as D in 300/300.

### 1.5 What a horizon makes impossible (EXPLICIT COUNTEREXAMPLE + PROVED characterization)

**Characterization.** Under the policy D^k, the corrections that are lost are exactly the pairs (u, t) with u > t + k and D(h_u, t) ≠ D(h_{u−1}, t). This follows directly from the definition. The current authoritative value for t becomes D(h_{t+k}, t) instead of D(h_{|h|}, t).

**Counterexample: the k-record need not determine the true current decision.**
- Take Σ = {0, 1} and L = Σ^{≤2}.
- D is constant 0, except for one correction D(h_2, 0) = a_2. Evidence arriving at time 2 corrects the decision for time 0.
- With k = 1, D^1 ≡ 0, so the greatest D^1-record is trivial. But h = 00 and h′ = 01 have different true current decisions for t = 0.

**What becomes impossible under H_k:**
- corrections driven by evidence arriving more than k steps late;
- retroactive rule changes that reach further back than k;
- appeal or contest outcomes delivered later than k steps after the decision.

Any of these would have to be recorded **outside** D, for example as an annotation that does not change the authoritative value. That is a different model, and this draft does not analyze it.

**Interaction with deletion (pointer).** O-10f4 F4-6 already says that retroactivity policy and deletion policy constrain each other. A horizon k is one such retroactivity policy. **STAKEHOLDER DECISION.**

---

## 2. O-10i3: bitemporal privacy and storage

### 2.1 Theorem P4-bi (PROVED THEOREM)

*Claim.* Let Π be an upward-closed predicate on equivalences on L: if π satisfies Π and π ⊆ π′, then π′ satisfies Π. Then a (P_bi)-preserving online append-only record satisfying Π exists iff ≡\*_bi satisfies Π. The same holds for the difference log, whose kernel equals ≡\*_bi (O-10i B3).

*Proof.* This is O-10a Prop. 4 applied to the single vector-valued decision vec.
- By B1, every (P_bi)-preserving online record has kernel π ⊆ ≡\*_bi.
- If such a π satisfies Π, upward closure gives that ≡\*_bi satisfies Π.
- Conversely, r\*_bi is itself such a record. ∎

The instances that carry over are "does not determine s" (some class, or every class, mixes s-values; both are upward-closed) and k-anonymity on history classes. Differential privacy is excluded, as in O-10a §8.

### 2.2 Corollaries

**Corollary P4-bi-a (PROVED, from H3 + P4-bi).** Feasibility is downward-monotone in the horizon. If a compliant record exists for D^{k′}, then one exists for D^k for every k ≤ k′.

**Corollary P4-bi-b (EXPLICIT COUNTEREXAMPLE).** Recording revisions can make privacy infeasible where the uncorrected setting was feasible.
- Take Σ = {0, 1} and L = Σ^{≤2}.
- D(h_u, u) = 0 for all u, and the single correction is D(h_2, 1) = a_2.
- Let s(h) = a_2 for |h| = 2.
- The historical-decision log ⟨D(h_t, t)⟩_t (equal to D^0) is constant on each length, so it does not determine s.
- ≡\*_bi determines s. So by P4-bi, **no** (P_bi)-preserving record satisfies "does not determine s".
- *Meaning.* The obligation to preserve superseded and corrected values is in genuine tension with upward-closed privacy predicates. The trade-off is a **STAKEHOLDER DECISION**: horizon, retention of corrections, and privacy.

**Caveat (ANALYTIC).** P4-bi says nothing about predicates that are not upward-closed, for example "must retain the superseded value", or the retention rules from O-10a Prop. 4's list of non-instances.

### 2.3 Storage carry-over

| Statement | Scheme | Label |
|---|---|---|
| For π ⊆ π′ in PC, N_n(π′) ≤ N_n(π). This is a fact about kernels and does not depend on the model. So: (i) any (P_bi) record r has N_n(ker r) ≥ N_n(≡\*_bi); (ii) N_n(≡\*_bi) ≥ N_n(≡\*_hist), where ≡\*_hist is the historical-decision log, because ≡\*_bi ⊆ ≡\*_hist (each D(h_t, t) is a component of vec(h_t)); (iii) N_n is non-increasing as k decreases (H3) | GLOBAL (not append-only) | PROVED |
| Recording revisions never lowers GLOBAL storage, and a smaller horizon never raises it | GLOBAL | PROVED (from the row above) |
| GLOBAL monotonicity **does not** transfer to the append-only schemes GW or NODE. Reducing the horizon k+1 → k is a coarsening, and in K3 it **increased** GW in 80/1200 (D, k) pairs, max-over-histories NODE in 83/1200, and NODE for some history in 126/1200. N_n increased in 0/1200, as required | GW, NODE | FINITE COMPUTATIONAL CHECK (EXPLORATORY; population: s = m = 2, n = 4, unrestricted random D) |
| The O-10g non-monotonicity transfers to the bitemporal setting by embedding. A monotemporal family is the k = 0 bitemporal case, so every O-10g example is a bitemporal example | GW, NODE | PROVED (embedding) |

*Not established.* Whether every pair π ⊆ π′ in PC can be realized as (≡\*_bi(D^{k+1}), ≡\*_bi(D^k)) for some D. I do not know, so the realizability of the §4 worst cases as horizon reductions is open.

---

## 3. O-10g1: full log vs difference log under corrections

The two logs have equal kernels (B3). All differences below are differences of **encoding**, not of information.

### 3.1 Theorem G1-0: kernel-determined schemes cannot tell them apart (PROVED THEOREM; K6(d) guards it)

*Claim.* Given the class of the past, the map from the full entry vec(h_u) to the difference entry is a bijection between the possible entries at that node. So under NODE (and under GLOBAL), the full and difference logs cost **exactly the same**, along every history.

*Proof.*
- Given vec(h_{u−1}), the difference entry is computed from vec(h_u). Conversely, vec(h_u) is recovered from vec(h_{u−1}) and the difference entry.
- NODE cost depends only on the kernel's child counts.
- K6(d) found 0 count mismatches in 300 random D. ∎

### 3.2 Scheme FW-cf: context-free fixed width (PROVED; K6(b) guards it)

- The syntactically possible entries at u number m^{u+1} for the full log, and Σ_{C ⊆ {0..u−1}} m^{|C|}·m = m(m+1)^u for the difference log. A context-free decoder cannot exclude "changed to the same value", so each changed slot ranges over V.
- So W_diff(u) = ⌈log₂ m + u log₂(m+1)⌉ ≥ W_full(u) = ⌈(u+1) log₂ m⌉, and W_diff(u) − W_full(u) ≥ ⌊u log₂(1 + 1/m)⌋, using ⌈a + δ⌉ ≥ ⌈a⌉ + ⌊δ⌋.
- **Under FW-cf, the difference log is never smaller than the full log, and it is strictly larger for all large u.** In the enumeration (m ∈ {2,3}, u ≤ 4), the two are equal only at (m,u) = (2,0), (3,0) and (3,1).
- This is the requested example: **the difference log is larger than the full log under FW-cf** at every position u ≥ 1 when m = 2. For example, at u = 1: 3 bits vs 2.
- Under a horizon, FW-cf on the window: ⌈log₂ m + k log₂(m+1)⌉ vs ⌈(k+1) log₂ m⌉.

### 3.3 Scheme VL-list vs FL-plain (PROVED; K6(a), (c) guard it)

| Case at step u ≥ 1 | VL-list (difference) | FL-plain (full) | Comparison |
|---|---|---|---|
| Best: no correction | ⌈log₂(u+1)⌉ + b | (u+1)b | the difference log is smaller by u·b − ⌈log₂(u+1)⌉; Θ(log u) vs Θ(u) |
| Worst: all u past values change | ⌈log₂(u+1)⌉ + u(⌈log₂ u⌉ + b) + b | (u+1)b | the difference log is **larger** by u⌈log₂ u⌉ + ⌈log₂(u+1)⌉ |
| General: c_u = \|C_u\| changes | ⌈log₂(u+1)⌉ + c_u(⌈log₂ u⌉ + b) + b | (u+1)b | the difference log is smaller iff c_u(⌈log₂ u⌉ + b) + ⌈log₂(u+1)⌉ < u·b |

- **Worst-case realizer (EXPLICIT COUNTEREXAMPLE).** D(h_u, t) = (u + t) mod 2 flips every past decision at every step, as a retroactive rule change applied at each step would. K6(a) confirms the formula and VL-list > FL-plain for u = 1..8.
- **Totals over n steps.** FL-plain totals b(n+1)(n+2)/2 = Θ(n² b). With K = Σ_u c_u total corrections, VL-list totals at most Σ_u(⌈log₂(u+1)⌉ + b) + K(⌈log₂ n⌉ + b). So the difference log wins overall iff corrections are sparse: roughly K ≲ n²b / (2(log₂ n + b)).
- **Population caveat.** In K6(c), 300 random unrestricted D, VL-list exceeded FL-plain on 17,062 entries, was smaller on 924 and equal on 614. Random D changes about half the past at every step, which is dense correction and not a realistic workload. This shows only that the comparison depends on the workload.
- **Under a horizon (VL-list-k vs windowed FL-plain).**
  - The worst case is ⌈log₂(k+1)⌉ + k(⌈log₂ k⌉ + b) + b vs (k+1)b. It is bounded and independent of u, but it can still favour the full window.
  - Without a horizon, the full entry is unbounded under FL-plain.
- **Variant scheme, named separately.** Use a 2-bit tag to choose the smaller of VL-list, bitmap and FL-plain, entry by entry. The worst case is then FL-plain + 2 bits and the best case is VL-list + 2 bits. *(ANALYTIC; not checked.)*

---

## 4. O-10g2: worst-case increase under coarsening for online integer codes

The setting is π ⊆ π′, both in PC, on L ⊆ Σ^{≤n} with s = |Σ| ≥ 2.

### 4.1 Lemma G2-0: local bound (PROVED; K5(vi) guards it)

For a π′-class C′, c_{π′}(C′) ≤ Σ_{C ⊆ C′, C a π-class} c_π(C).

*Proof.* Every π′-child of C′ contains the π-child of some π-class C ⊆ C′, and distinct π′-children are disjoint. ∎

**Consequences.**
- GW(π′) ≤ GW(π) + ⌈log₂ max_{C′} μ(C′)⌉, where μ(C′) is the number of π-classes inside C′.
- cost_{π′}(h) ≤ Σ_t (⌈log₂ μ([h_t]_{π′})⌉ + ⌈log₂ cmax_t(π)⌉), where cmax_t is the largest child count at level t. (PROVED)

### 4.2 Theorem G2-1: absolute upper bounds (PROVED; K5(ii)–(v) guard them)

For every π′ ∈ PC and every h with |h| = n:

1. **NODE.** cost_{π′}(h) ≤ Σ_{t<n} ⌈log₂ min(s·|[h_t]_{π′}|, |L_{t+1}|)⌉ ≤ Σ_{t<n} ⌈(t+1) log₂ s⌉ ≤ (n(n+1)/2)·log₂ s + n. Child classes of a level-t class are disjoint sets of level-(t+1) histories, each containing a one-letter extension of a member.
2. **NODE, in terms of class counts.**
   - The child sets along h lie on different levels, so they are disjoint: Σ_t c′_t ≤ T(π′) := Σ_{t=1}^n N_t(π′).
   - If L has no dead ends (every history of length < n extends), then leaves under distinct side branches are distinct, so Σ_t (c′_t − 1) + 1 ≤ N_n(π′) ≤ N_n(π).
   - By concavity, cost_{π′}(h) ≤ n + n·log₂(1 + (N_n(π) − 1)/n).
3. **GW.** GW(π′) ≤ ⌈log₂ max_{t<n} N_{t+1}(π′)⌉ ≤ ⌈log₂ max_{t<n} N_{t+1}(π)⌉ ≤ ⌈n log₂ s⌉.

So the online width after coarsening never exceeds the GLOBAL width of the finer kernel at its widest level.

The increase therefore satisfies cost_{π′}(h) − cost_π(h) ≤ Σ_{t<n} ⌈(t+1) log₂ s⌉ − cost_π(h) = O(n² log s), and GW(π′) − GW(π) ≤ ⌈n log₂ s⌉ − GW(π).

*Check.* K5 (600 random coarsening pairs from online records; s=2, n=4 and s=3, n=3) found 0 violations of (i)–(vi).

### 4.3 Theorem G2-2: tightness

**(a) GW, exact tightness (EXPLICIT EXAMPLE; K4 checks it for s=2, n ≤ 9 and s=3, n ≤ 6).**
- Take π = identity, so GW = ⌈log₂ s⌉.
- Take π′ = the total relation on every level < n and the identity on level n. This is in PC, because prefix-closure only constrains downward and level n is the identity.
- The level-(n−1) class has sⁿ children, so GW(π′) = ⌈n log₂ s⌉. That **meets the upper bound** in G2-1(3).
- Under GW, the total log length goes from n⌈log₂ s⌉ to n⌈n log₂ s⌉: from Θ(n) to Θ(n²).
- Its NODE cost along any history is only ⌈n log₂ s⌉. **GW and NODE blow up on different examples.**

**(b) NODE, quadratic increase (EXPLICIT EXAMPLE family + PROVED formula; K4 checks it).**
- Let K ⊆ Σ\* be prefix-closed. Define π′_K: at each level t, K_t is one class and every other history is a singleton. π′_K is in PC whenever K is prefix-closed (sufficient condition; I do not claim necessity), and identity ⊆ π′_K.
- For h ∈ K, cost_{π′_K}(h) = Σ_{t<n} ⌈log₂(s|K_t| − |K_{t+1}| + 1)⌉, whereas cost_identity(h) = n⌈log₂ s⌉.
  - **s = 3, K = {0,2}\*.** |K_t| = 2^t, so the cost is Σ_t ⌈log₂(2^t + 1)⌉ ≈ n²/2. K4: 21 vs 12 at n = 6.
  - **s = 2, K = strings without the factor "11" (Fibonacci).** K4 brute force matches the formula: n = 9 gives 22 vs 9. Growth is ≈ (log₂ φ / 2)·n² ≈ 0.347 n² (ANALYTIC asymptotic).
- **Conclusion.** Coarsening the **finest** kernel (the raw log) can raise the per-history NODE cost from Θ(n log s) to Θ(n² log s). G2-1(1) shows that this order is the maximum.
- *Constant (ANALYTIC ARGUMENT, not computationally checked).*
  - Take K = strings over {0,1} avoiding 1^r. Its growth rate λ_r → 2 as r → ∞, and s|K_t| − |K_{t+1}| ≈ |K_t|(2 − λ_r).
  - So cost ≈ (n²/2) log₂ λ_r + O(n·|log(2 − λ_r)|). The ratio to the upper bound (n²/2) log₂ s tends to 1, taking n → ∞ first and then r → ∞.
  - For general s, K = (Σ∖{1})\* gives the ratio log₂(s−1)/log₂ s → 1.
  - I claim asymptotic tightness of the leading constant only in this limiting sense.

**(c) Relative statement.**
- **GW (PROVED).**
  - If GW(π) ≥ 1, the ratio GW(π′)/GW(π) is at most ⌈n log₂ s⌉, and example (a) attains about n.
  - If GW(π) = 0, every π-node has exactly one child. Every level-(t+1) class has a parent, so N_t(π) = N_0 = 1 for all t: π is total on every level, π′ ⊇ π is total too, and GW(π′) = 0.
- **NODE.**
  - The absolute increase is O(n² log s) (G2-1, PROVED).
  - Example (b) shows an increase by a factor of order n against the identity (EXPLICIT EXAMPLE).
  - A general upper bound on the **ratio** cost_{π′}(h)/cost_π(h) is **not established**. For L = Σ\*, cost_π(h) = 0 forces π to be total, by the same argument as for GW, so a zero denominator gives no counterexample there. Whether ratios beyond Θ(n) occur is open.
- In every case, the increase cannot be bounded by a constant factor independent of n.

### 4.4 What this means for the earlier claims

- O-10g reported counts of increases at n = 3. G2-1 and G2-2 replace "can increase" with "**can increase by Θ(n²) bits (about a factor n against the raw log), and never by more than O(n² log s) bits in absolute terms**". Every bound is tied to the named scheme (NODE or GW).
- Nothing here transfers to Huffman (expected-length) or ideal entropy coding. Those were outside this task and are not addressed.

---

## 5. Architectural implications (ARCHITECTURAL PROPOSAL; not adopted)

1. **A horizon is the knob that bounds per-step record growth.**
   - Under H_k, the greatest preserving record is the window log, with per-step entries of O(k log m) bits under every named scheme above.
   - Without a horizon, no named scheme bounds the entry independently of u in the worst case.
   - The choice of k, and whether it is enforced as a property or as a truncation, is a **STAKEHOLDER DECISION**. It forecloses late-evidence corrections, deep retroactive rule changes and late appeal outcomes (§1.5).
2. **Privacy and revisability trade off** (P4-bi-b). Feasibility for upward-closed predicates can only improve as the horizon shrinks. Checking a privacy predicate still reduces to checking ≡\*_bi alone.
3. **"Store diffs, it is smaller" is scheme-dependent and workload-dependent.**
   - The difference log is never smaller under context-free fixed width.
   - It is smaller under VL-list only when corrections are sparse.
   - It is identical under any kernel-determined code.
   - Storage claims must name the scheme and the correction-density assumption.
4. **Data minimization (coarsening) can multiply append-only storage by Θ(n)** under NODE or GW. Storage cannot be used as a proxy for minimization, nor minimization as a proxy for storage.

## 6. Not established / open

- **O-10i3-r.** Whether arbitrary PC pairs are realizable as horizon reductions, i.e. whether the Θ(n) blow-ups of §4 occur for (D^{k+1}, D^k). K3 shows that increases occur, but the instances are small.
- **A general bound on the NODE cost ratio** under coarsening (§4.3(c)).
- **The exact leading constant for NODE with s = 2 at finite n.** Only the asymptotic limiting argument (§4.3(b)) is given.
- **Annotation records.** Whether post-horizon corrections can be recorded as non-authoritative annotations without reopening the size bound. This needs a different model.
- **Expected-cost (Huffman) and arithmetic-coding versions** of G2. Not attempted.
- **The tagged hybrid scheme** (§3.3) is analytic and unchecked.
- **The H_k tightness example** (§1.3) is not computationally checked.
- **Independence.** All checks were written by the author of the proofs, so they are guards rather than independent evidence.
