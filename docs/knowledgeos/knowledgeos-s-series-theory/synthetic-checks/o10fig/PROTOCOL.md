# O-10f / O-10i / O-10g synthetic checks (frozen before any code is written)

Σ, L and PC are as in O-10a. All checks are exhaustive within the stated finite instances. Random instances use seed 20261020.

## O-10f: verifiability

**Model.**
- Each step is a letter (x, o): x is a tuple of k raw **attested** fields, and o is the system's **actual output**, which may be wrong.
- The correct decision is d(h) = F(x of the last letter), for a published rule F.
- An auditor trusts attested fields and F, but not o and not any derived entry.
- A record (o-log, E) is **verifiable** iff, for every pair of histories with the same o-log and the same attested projection log E, the correctness bit [o = d] agrees at every step.

| Check | Statement |
|---|---|
| F1 (named instance) | k = 1, x ∈ {0,1}, F = identity, single step. The outcome log r\* = o is **not** verifiable: (x = 0, o = 0) and (x = 1, o = 0) have the same r\* and different correctness. |
| F2 (exhaustive, single-step) | k = 3 binary fields, letters a random non-empty subset of {0,1}³, o ∈ V_d, F a random table with values in {0,1,2}. **Claim:** (o, E_S) is verifiable ⇔ the projection onto field subset S determines F on the letter set. Checked for **all** subsets S on 300 random instances. *Failure condition:* any mismatch. |
| F3 (exploratory measure, declared as such) | On the F2 instances, report the fraction with ≥ 2 minimal determining field subsets whose kernels are **incomparable**. Also report the O-10a P3 named instance (letters (0,0), (0,1), (1,2), (1,3), (2,2); F = [x ≥ 1]), where {x} and {y} are minimal and incomparable. |
| F4 (analytic, no computation) | Separating examples for recoverability, verifiability, reproducibility and contestability. These are labelled ANALYTIC EXAMPLE. |

## O-10i: revisable decisions (bitemporal)

**Model.** Bitemporal decisions D(h_u, t) for t ≤ u: what the system, at time u, holds the decision for time t to be. The requirement **(P_bi)**: the record at u determines D(h_u, t) for every t ≤ u.

| Check | Statement |
|---|---|
| I1 (exhaustive) | Define h ≡\*_bi h′ ⟺ \|h\| = \|h′\| and D(h_u, t) = D(h′_u, t) for all t ≤ u ≤ \|h\|. It is the greatest element of {π ∈ PC : π preserves (P_bi)}. Σ = {0,1}, n = 3, D a random binary table over (h_u, t); 300 instances. *Failure condition:* any instance where it is not the greatest. |
| I2 (named instance) | Letters: "1" = observe value 1; "0" = observe value 0; "c" = correct the first observation (flip it). D(h_u, 1) = the corrected value of the first observation; D(h_u, u) = the current last observation, or the corrected first one if the last letter is "c". **Claim:** the current-only log ⟨D(h_u, u)⟩_u does **not** determine D(h_2, 1) for some h. Exhibit the pair. |
| I3 (exhaustive) | Two corrections arriving in either order. "Max-merge" gives an order-independent final value on all value pairs in {0,1,2}²; "last-writer-wins" gives an order-dependent value on some pair. |

## O-10g: storage

For π ∈ PC, a node is a π-class at level t, and c(node) is the number of π-classes at level t+1 under it.

| Code | Definition |
|---|---|
| (i) global per-length | ⌈log₂ #classes at length n⌉ |
| (ii) online fixed-width | per-step width ⌈log₂ max_node c(node)⌉ |
| (iii) online per-node | the cost along h is Σ_t ⌈log₂ c(class_t(h))⌉ |
| (iv) online expected | under uniform inputs: the per-node Huffman expected length, summed along the path distribution |

| Check | Statement |
|---|---|
| G1 (exhaustive) | On all pairs π ⊆ π′ in PC over L = all strings with n = 3, Σ = {0,1}: (i) is monotone non-increasing from π to π′. *Failure condition:* any increase. |
| G2 (search, plus named instance) | Pairs exist where (ii) **increases** under coarsening. Named instance: π separates level 1 into {0},{1}, each with 2 child classes; π′ merges level 1 and keeps all 4 level-2 classes. The width goes from 1 to 2. |
| G3 (search) | Report whether (iii) increases for some history under coarsening, the maximum over histories of (iii), and (iv). Each result is reported as found, **true or false**. |

**Limits.** Tiny instances only. Checks of implementation and counterexample searches; no general theorem.
