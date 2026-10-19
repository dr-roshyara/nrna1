# O-10i2, O-10i3, O-10g1, O-10g2: correction horizons, bitemporal privacy and storage, and storage bounds

| Field | Value |
|---|---|
| Clock | 2026-10-18 05:59 |
| Process | A worker sub-agent drafted. A refute-first reviewer re-derived H1, H3 and the g2 example with its own brute force (`s1.py`) before reading the draft. Integrated by Session B. |
| Review status | **PASS WITH CONDITIONS — INTERNALLY REVIEWED, SAME MODEL FAMILY.** Corrections C1–C5 are applied below, and **this section governs the draft.** |
| Artifacts | `../synthetic-checks/o10i2-g2/worker/`: PROTOCOL `70cbe131bd0b7451f13a…` (frozen 05:52:12); checks.py `12faabe6b46402328f4e…` (identity recorded 05:53:10). **A pre-run bug fix in K6(d) was made after the protocol froze and before the code identity was recorded, and is disclosed. The reviewer judged it compliant, since the protocol froze checks and criteria, not code. "No run before the fix" is self-reported.** RESULTS sha256 `84a4a92d…`. `../reviewer/s1.py` |
| Evidence weight | K1 has real power: its controls catch an off-by-one window 298/300 and 272/300. **K2's D⁴ = D check is vacuous at n = 4.** **K6(d) is near-tautological:** it guards the code only. K3 is exploratory. |

## 1. O-10i2: correction horizons

**Definition.** Horizon k means D(h_u, t) is frozen once u > t + k. It can arise in two ways:
- as a property of the decision process; or
- as a truncation **policy** D^k applied to a base D.

**The value of k, and which form applies, are STAKEHOLDER DECISIONS.**

| ID | Statement | Status |
|---|---|---|
| **H1** | Under horizon k, the **window log** ⟨(D(h_u, t))_{max(0, u−k) ≤ t ≤ u}⟩ has exactly the kernel of the greatest (P_bi)-preserving record, so it is the greatest record. A frozen value D(h_u, t) = D(h_{t+k}, t) appears in the window at time t + k. **C1:** it is unique **up to kernel** (equivalently, a recoding per node that depends on the past), *not* up to an entry-wise recoding. | PROVED; the reviewer's independent brute force found 0 mismatches over k = 0…4; K1 found 0 / 900 with power controls |
| H3 | Within one truncation family D^k of a fixed base D, a smaller k gives a **coarser or equal** record | PROVED; independent check: 0 violations |
| Entry size | **(a)** plain fixed width: (k+1)·b bits. **(b)** fixed width with the context-free alphabet: ⌈(k+1) log₂ m⌉. **(c)** difference list: ≤ ⌈log₂(k+1)⌉ + k(⌈log₂ k⌉ + b) + b (index width 0 when nothing changes). All are **independent of u**. Each decision has at most k + 1 versions in total | PROVED |
| Tightness | A process attains ⌈(k+1) log₂ m⌉ at every step. **C2:** D = 0 when σ = 0; letters indexed a₁…a_u | ANALYTIC EXAMPLE; not checked by code |
| **C3 (corrected)** | **A horizon is *a* sufficient way to bound entry size, not the only one.** Restricting corrections to a bounded set of times also bounds the per-node cost. The plain variable-length list always grows, through its count field ⌈log₂(u+1)⌉, even with no corrections | reviewer correction |
| What is lost | Exactly the corrections at u > t + k: late evidence, deep retroactive rule changes, late appeal outcomes | COUNTEREXAMPLE |

**Assumptions:** finite V; one decision per step; concurrent corrections already serialized (B5); rule changes subject to the same horizon.

## 2. O-10i3: bitemporal privacy and storage

| ID | Statement | Status |
|---|---|---|
| P4-bi | For upward-closed Π on kernels: a compliant (P_bi)-record exists ⇔ the minimal bitemporal record complies. The difference log has the same kernel, so the same holds for it | PROVED |
| P4-bi-a | Feasibility only improves as the horizon shrinks | PROVED |
| P4-bi-b | **Recording corrections can make privacy infeasible** where a log of historical decisions only would comply | COUNTEREXAMPLE (the example is minimal: s *is* the correction) |
| Global per-length storage (**not append-only**) | Monotone. Recording revisions never lowers it; a smaller horizon never raises it | PROVED |
| Append-only codes | **Not** monotone. Reducing the horizon raised the single global width in 80 / 1200 pairs and the per-node cost for some history in 126 / 1200 (the class count rose in 0 / 1200) | EXPLORATORY (K3) |

## 3. O-10g1: full bitemporal log vs difference log (each claim names its encoding)

| Encoding | Comparison | Status |
|---|---|---|
| Any **kernel-determined** code (per-node; global per-length) | **The same cost on every history** (the kernels are equal) | PROVED |
| **Context-free fixed width, counted over syntactically possible entries** | The difference log is never smaller; at m = 2 it is strictly larger for every u ≥ 1 (⌈1 + u log₂ 3⌉ ≥ u + 2; at u = 1, 3 bits vs 2) | PROVED (**C4 domain qualifier**) |
| The same, **counted over realizable entries** | **Can reverse.** With no corrections (D(h_u, t) = h[t]), the difference entry needs 1 bit and the full entry u + 1 bits | COUNTEREXAMPLE (reviewer, **C4**) |
| Variable-length difference list vs the plain full log | Best case ⌈log₂(u+1)⌉ + b vs (u+1)b. Worst case (every step flips all past decisions) larger by u⌈log₂ u⌉ + ⌈log₂(u+1)⌉. **Difference logs win only when corrections are sparse** | PROVED + COUNTEREXAMPLE |

## 4. O-10g2: how much can coarsening increase online integer cost?

| ID | Statement | Status |
|---|---|---|
| G2-1 | Upper bounds: per-node cost along a history ≤ Σ_t ⌈(t+1) log₂ s⌉ = O(n² log s). Class-count form: ≤ n + n·log₂(1 + (N_n − 1)/n), with no dead ends. **Single global width** ≤ ⌈log₂ max_t N_{t+1}(finer)⌉ ≤ ⌈n log₂ s⌉ | PROVED; K5: 0 violations / 600 |
| G2-2(a) | **Tight:** the global width goes from ⌈log₂ s⌉ to ⌈n log₂ s⌉ (identity → "total below n, identity at n"; the pair is in PC and is a coarsening, as the reviewer re-derived) | PROVED + EXAMPLE |
| G2-2(b) | Per-node cost goes from n⌈log₂ s⌉ to Θ(n²), via explicit families (s = 3, K = {0,2}\*: 21 at n = 6; a Fibonacci family ≈ 0.347n²). The limiting constant approaches the upper bound | EXAMPLES (reviewer re-derived); ANALYTIC limit |
| G2-2(c) | Cost 0 along a history forces the relation to be total (for L = Σ\*) | PROVED |

*Scope:* these are **worst-case existence** results for a fixed alphabet size s ("can increase"), not typical behaviour.

## 5. Architecture implications (ARCHITECTURAL PROPOSAL; not adopted)

- **A correction horizon keeps records bounded.** But it is a trade-off: corrections after the horizon become impossible. **The value of k is a stakeholder choice.** A bound on which times may be corrected is an alternative.
- **Recording corrections can conflict with privacy rules** (P4-bi-b). Privacy review must cover the *corrected* record.
- **Storage claims must name both the encoding and the counting domain** (syntactic vs realizable). Difference logs pay off only when corrections are sparse.
- **Data minimization (coarsening) can multiply per-step storage by up to a factor of n** under online integer codes. Storage and minimization stay separate objectives.

## 6. Open

- Whether the worst cases of §4 occur as horizon reductions (O-10i3-r).
- A general bound on the per-node cost ratio under coarsening.
- The tagged hybrid scheme, and the tightness example (§1), are not checked by code.
- Recording corrections that arrive **after** the horizon (needs a different model).
- The H1 and P4-bi proofs rely on O-10i B1 and O-10a P4. The reviewer reproduced both directions independently, for the PC setting.
