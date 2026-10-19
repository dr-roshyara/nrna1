# Chapter 13. Storage and encodings

*Status: INTERNALLY REVIEWED (same model family; refute-first review 2026-10-18 recomputed all numbers; the example family specified, an unsupported asymptotic claim withdrawn; all corrections applied). Not externally reviewed.*

**Research question (RQ10).** Does holding **less information** mean storing **fewer bits**? Under which encodings?

**Plain meaning.**
- With **global** coding (choosing codes knowing all histories of a given length), less information never costs more storage.
- The same holds in the **ideal entropy limit**.
- With **streaming, append-only** logs that write whole-bit entries at each step, data minimization **can increase** storage, in the worst case by a factor growing with the history length.
- Difference logs for corrections save space only when corrections are sparse.
- **Every storage statement must name its encoding and its counting domain.**

## 13.1 Encodings

**Conventions.**
- L ⊆ Σ^{≤n}. For π ∈ PC(L), a **node** is a π-class at length t < n, and c(node) is the number of π-classes at length t + 1 below it.
- b := ⌈log₂ |V|⌉.
- An entry of ⌈log₂ 1⌉ = 0 bits assumes that step boundaries are known externally (e.g. by position). This is compatible with DEF 2.11's delimiters only under that convention.
- A code is **kernel-determined** iff its length on a history depends only on the record's kernel classes along that history.

| Encoding | Cost | Append-only? |
|---|---|---|
| **class count** at length n | N_n(π) := number of π-classes of length-n histories | — |
| **global per-length code** | ⌈log₂ N_n(π)⌉ bits | no |
| **ideal fractional code** under a process μ | expected −log₂ P(entries) | no single delimited entry per step |
| **online single global width** | ⌈log₂ max_node c(node)⌉ bits per step | yes |
| **online per-level width** | ⌈log₂ max_{nodes at level t} c(node)⌉ at step t; "total per-level width" = the sum over t | yes |
| **online per-node integer cost** along h | Σ_t ⌈log₂ c(class_t(h))⌉ | yes |

## 13.2 Where minimization helps

**Proposition 13.1. THM.** If π ⊆ π′ (π′ is coarser), then N_n(π′) ≤ N_n(π). Hence the global per-length cost does not increase.

*Proof.* Each π′-class of length n is a union of π-classes. ∎

**Proposition 13.2. THM.** Under any process μ on histories, with ideal fractional coding, the expected total length of a π-record up to n equals H(C_n), the entropy of the length-n class. Coarsening does not increase it.

*Proof.*
- **Telescoping:** under PC, the class at t is a function of the class at t + 1, so H(C_{t+1} | C_t) = H(C_{t+1}) − H(C_t). Summing, and using H(C₀) = 0 ({ε} is a single class), gives Σ_t H(C_{t+1} | C_t) = H(C_n).
- **Monotonicity:** a coarsening is a function g of the finer class, and H(g(X)) ≤ H(X) (LIT, standard). ∎

*Evidence:* the identity was checked exactly by an internal reviewer for a non-uniform μ on binary histories, n = 3.

*Caveat (LIT):* real arithmetic coding achieves less than H + 2 bits per whole message, and does **not** produce one delimited entry per step. So it lies outside the append-only record model of DEF 2.11.

## 13.3 Where minimization can hurt

**Proposition 13.3 (online single global width can increase; tight). THM + EXAMPLE.**
- *Example:* L = Σ^{≤n} with |Σ| = s. Coarsening the identity record to "total below length n, identity at length n" raises the single global width from ⌈log₂ s⌉ to ⌈n log₂ s⌉, which is the upper bound below.
  - *Why it is PC:* this needs L ⊆ Σ^{≤n}, or identity beyond n; "total" beyond n would violate prefix-closure.
  - *Checked:* s = 2, n = 2–4; and s = 3, n = 2, 3, 4, giving widths 4, 5 and 7.
- *Upper bound:* for coarsening π to π′, the width after coarsening is ≤ ⌈log₂ max_{t<n} N_{t+1}(π′)⌉ ≤ ⌈n log₂ s⌉.

*Proof sketch.*
- **Bound:** a node's children are π′-classes, and each is a union of π-classes at the next level.
- **Example:** the single node at length n − 1 has sⁿ children. ∎

**Proposition 13.4 (per-node cost can increase quadratically). THM + EXAMPLES.**
- *Upper bound:* the per-node cost along a history is at most Σ_{t<n} ⌈(t+1) log₂ s⌉ = O(n² log s).
- *Example family (s = 3):*
  - **Relation:** on Σ = {0,1,2}, lump the histories in {0,2}\* into one class per length, and keep all other histories as identity classes. This is in PC.
  - **Cost:** at the lumped node of length t, c = 1 + 2^t. So the maximum per-node cost along a history is Σ_{t<n} ⌈log₂(1 + 2^t)⌉ = n(n+1)/2: **21 at n = 6**, against 12 for the identity record (n⌈log₂ 3⌉).
  - **Checked:** exhaustively for n = 1..7, by an internal reviewer.
  - **Leading constant:** 0.5 · n².
- *A second family:* an earlier unspecified "Fibonacci-type" family claim is **removed**.
- *Asymptotic claim, withdrawn:* the earlier statement that the leading constant approaches the upper bound is unsupported here (no construction is given).

**Finite evidence (FIN).**
- *Domain:* all 735,776 strictly coarser pairs in PC for Σ = {0,1}, n = 3.
- *Global per-length cost:* 0 increases.
- *Single global width:* increases in 71,401 pairs.
- *Total per-level width* (sum over levels): increases in 1,612 pairs. "Increase at some level" gives 100,275.
- *Per-node cost:* increases along some history in 66,690 pairs; its maximum over histories increases in 2,000 pairs.
- *Expected per-node Huffman length, under uniform μ:* increases in 1,088 pairs.
- *Independence:* recounted independently with exact fractions by an internal reviewer.

## 13.4 Corrections and horizons

**Proposition 13.5 (full bitemporal log vs difference log). THM + CE.**

| Encoding | Comparison |
|---|---|
| any kernel-determined code | **equal cost** on every history (equal kernels, Prop. 11.5) |
| context-free fixed width, counted over **syntactically possible** entries (t ranging over 0..u) | the difference log is never smaller: m(1+m)^u ≥ m^{u+1} (at m = 2, u = 1: 6 vs 4 entries, 3 vs 2 bits) |
| the same, counted over **realizable** entries | **can reverse**: with m = 2, every value sequence realizable and no corrections, the difference entry needs 1 bit and the full entry u + 1 bits |
| variable-length difference list | best case ⌈log₂(u+1)⌉ + b vs (u+1)b; worst case larger by u⌈log₂ u⌉ + ⌈log₂(u+1)⌉ (every step revises all past decisions) |

**Proposition 13.6 (= Prop. 11.13).** Under a correction horizon k, entry sizes are bounded independently of u.

## 13.5 Limits and open problems

- **OPEN:** a general bound on the ratio of per-node costs under coarsening.
- **OPEN:** whether the worst cases arise from horizon reductions.
- **OPEN:** expected-cost versions for realistic processes.

## 13.6 Traceability (ARCH)

| Result | Engineering consequence |
|---|---|
| Prop. 13.1 / 13.2 | enumerative global codes over the class set, or entropy codes with a known μ, realize minimization's storage benefit; generic compressors are not covered |
| Prop. 13.3 / 13.4 | streaming logs with fixed-width entries may not shrink after data minimization; measure before claiming storage savings |
| Prop. 13.5 | use difference logs only where corrections are sparse |
| Every claim | name the encoding and the counting domain |
