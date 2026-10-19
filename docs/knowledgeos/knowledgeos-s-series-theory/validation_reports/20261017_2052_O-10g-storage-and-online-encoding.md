# O-10g: does minimizing information reduce storage?

| Field | Value |
|---|---|
| Author / clock | Session B, 2026-10-17 20:52 |
| Protocol / code / results | `o10fig/PROTOCOL.md` (`24b93f08…83a7`), `o10fig_checks.py` (`cb61e3b7…297f`), `RESULTS.txt` |
| Review | INTERNALLY REVIEWED — SAME MODEL FAMILY. The reviewer recounted S2 and S3 independently, with exact fractions, and they matched. |

## 1. Six notions of cost

| Notion | Behaviour under coarsening (π ⊆ π′) | Status |
|---|---|---|
| Information order ⊑ | coarser = less information, **by definition** | DEFINITION |
| Number of classes at length n | never increases | MATHEMATICAL PROOF (trivial). Exhaustive: **735,776 pairs, 0 violations** (n = 3, Σ = {0,1}) |
| Global per-length code ⌈log₂ #classes⌉ | never increases | same as above. **This code is not append-only.** |
| Online fixed-width, **one global width** for all steps | **can increase** | 71,401 / 735,776 pairs. Named instance: merging only level 1 takes the width from 1 to 2 bits |
| Online fixed-width, **separate width per level, summed** *(reviewer variant)* | can increase, rarely | 1,612 pairs (reviewer). The named instance does **not** increase (3 → 3). **Reported results must name the scheme.** |
| Online per-node integer cost along a history | can increase | 66,690 pairs for some history; 2,000 pairs for the maximum over histories |
| Expected per-node Huffman length (uniform inputs) | can increase | 1,088 pairs |
| Ideal fractional coding (−log₂ p per step) | never increases | MATHEMATICAL PROOF (reviewed): Σ_t H(C_{t+1} \| C_t) telescopes to H(C_n), and coarsening cannot raise entropy (data-processing inequality), for **any** input distribution. *Caveat:* real arithmetic coding achieves H + ≤ 2 bits per message and **does not** produce one delimited entry per step, so it lies outside the append-only record model. Not pre-declared; analytic only. |

**Protocol note.** The protocol's phrase "per-step width" was implemented as **one global width** (the reviewer's deviation note 2). The per-level variant gives a different, much smaller count.

## 2. Conclusion

- **Minimizing information does not guarantee lower storage** for append-only logs that write whole-bit entries at each step.
- It **does** for global codes, and in the ideal entropy limit.
- **For architecture:** data minimization and storage minimization are **separate goals**, and the first does not imply the second for streaming logs. Storage claims must name the encoding scheme. **(ARCHITECTURAL PROPOSAL.)**

## 3. Open

| ID | Question |
|---|---|
| O-10g1 | Storage under the bitemporal difference log (O-10i, B3) |
| O-10g2 | Bounds on the worst-case increase for online integer codes |
