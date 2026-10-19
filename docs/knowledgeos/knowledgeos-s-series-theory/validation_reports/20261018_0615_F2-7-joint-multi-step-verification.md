# F2-7: joint necessity for multi-step verification (resolved)

| Field | Value |
|---|---|
| Clock | 2026-10-18 06:15 |
| Process | Session B wrote the proof and the protocol. The protocol (`../synthetic-checks/f2-7/PROTOCOL.md`, sha256 `a2c5d91f0d8317cd84f7fdc494fef7e730acd360dc288ce51b8154c8773138d4`) was frozen at 06:12:44, before any code. The code identity `c6526c3c51bfbb171347c3897508ae8f1a667d229825868fe017b3481151cbf5` was recorded before its single run. A refute-first reviewer checked the proof **before** opening any file, then built its **own** checker with a different construction of PC (`../synthetic-checks/f2-7/reviewer/chk.py`, `run.py`), and re-ran the author's script (byte-identical output). |
| Review | **PASS WITH CONDITIONS — INTERNALLY REVIEWED, SAME MODEL FAMILY.** The six corrections are applied. Timestamps are self-attested; the folder is untracked in git. |

## 1. Statement

**Setting.**
- Worlds are w = (x̄, ō) ∈ W ⊆ (X×O)ⁿ.
- d_s = F_s(x̄_s) is the decision, and c_s = [o_s = d_s] is the correctness bit.
- An append-only evidence family G has a PC kernel on x-histories. **Every PC equivalence is realizable by such a G**: for example, G_s = the sequence of classes of all prefixes up to s.
- **Ver_s:** the view (G_s, **the whole o-prefix ō of length s**) determines c_s on W_s.
- **s-confusable:** a d_s-distinct pair such that some length-s o-history admissible with both has o_s equal to one of the two decisions.

**Theorem F2-7J** [PROVED; INTERNALLY REVIEWED].
- For all append-only G: (∀s Ver_s) ⇒ (∀s G_s determines d_s) **iff (S-joint)**: every d_t-distinct pair at step t has prefixes that are **s-confusable for some s ≤ t**.
- *Note (correction 1):* the converse implication, determination ⇒ Ver, holds for **every** W, because the view gives both d_s and o_s. So the content of the theorem is one-directional.

*Proof.*
- **(⇐)** A d_t-distinct pair is forced at some s ≤ t. Ver_s makes G_s separate the prefixes. If G_t identified the pair, prefix-closure would make G_s identify its prefixes, which is a contradiction.
- **(⇒)** Suppose S-joint fails at t for (x̄, x̄′). Take G = the identity, except that it identifies (x̄_s, x̄′_s) for every s ≤ t at which they differ. This G is an equivalence and is PC. The only identified pairs are non-confusable prefixes, so Ver holds at every step. But G_t does not determine d_t. ∎

## 2. Consequences

- **(T2) (S-multi) ⇒ (S-joint)** (take s = t). **(T3) The converse fails:** per-step necessity is **not** jointly required.
- **Smallest instance (|W| = 2), checked by hand:** F₁: 0→1, 1→0; F₂: (0,1)→0, (1,1)→1; W = {((0,1),(0,1)), ((1,1),(0,0))}.
  - The step-2 pair has no common output history, so it is not 2-confusable.
  - Its first steps are 1-confusable.
  - So any verifiable family already separates them at step 1, and therefore at step 2.
- **Plain meaning:** **evidence needed early in a process can make later evidence unnecessary**, for the decisions it already separates. Conversely (F2-8), the joint fault model can make later evidence unnecessary for other reasons. A per-step evidence policy is safe but can **over-demand**.

## 3. Computational evidence

| Checker | Domain | Instances | (J) vs (S-joint) mismatches | (J) false | Strict (S-joint, not S-multi) |
|---|---|---|---|---|---|
| author (protocol) | n = 2 binary, random W, p = 0.3 / 0.6 / 0.9 | 3 × 2,000 | 0 | — | 272 / 151 / 1 |
| reviewer (independent) | n = 2 binary, **all 64 F × every W with \|W\| ≤ 4** (exhaustive) | 161,024 | 0 | 68,304 | 15,568 |
| reviewer | n = 2, \|W\| between 5 and 16 (sampled) | 20,000 | 0 | 3,449 | 1,250 |
| reviewer | n = 3, \|W\| ≤ 6 (sampled) | 3,000 | 0 | 1,740 | 262 |

- **The control F2-8** gives J = false, S-joint = false, S-multi = false, in both checkers.
- **Correction 5:** the author's p = 0.9 stratum is **near-vacuous** for the ¬S-joint side (S-joint held in 1,998 of 2,000). The p = 0.3 stratum and the reviewer's exhaustive sweep cover that side.
- **Correction 6:** the "smallest strict instance" printed per stratum is a within-sample minimum. The global minimum is |W| = 2, as above.
- **Cosmetic (correction 4):** a docstring says ε is included; it is not.
- **Limits:** binary inputs, n ≤ 3 (n = 3 only sampled). Generality rests on the proof.

## 4. Architecture implication (ARCHITECTURAL PROPOSAL)

**Evidence-retention rules may be designed jointly across a process, not step by step.** Earlier evidence can already guarantee the verifiability of later decisions.
- *Safe default:* per-step "evidence determines the decision" (always sufficient).
- *Possible saving:* drop later evidence where earlier confusability already forces separation.

Any saving relies on correctly modelling the process's fault model, which is a trust assumption.
