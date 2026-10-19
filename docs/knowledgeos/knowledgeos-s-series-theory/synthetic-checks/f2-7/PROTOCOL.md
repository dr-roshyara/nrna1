# F2-7 joint necessity: protocol (frozen before any code)

## Definitions (as in report 20261018_0546 §1; worker draft §2.2)

**Worlds and decisions.**
- A world is w = (x̄, ō) ∈ W ⊆ (X×O)ⁿ. X_{W,s} is the set of x-prefixes of length s; W_s is the set of world prefixes.
- d_s = F_s(x̄_s), and c_s = [o_s = d_s].
- An **append-only evidence family** G = (G_1…G_n) has kernels forming a length-preserving, prefix-closed equivalence on x-histories (PC).

**Verifiability.** Ver_s ⇔ on W_s, (G_s(x̄_s), ō_s) determines c_s. Ver ⇔ Ver_s for all s.

**Confusability.** x̄, x̄′ ∈ X_{W,s} are **s-confusable** iff d_s differs between them and some ō of length s is admissible with both (in W_s), with ō_s ∈ {d_s(x̄), d_s(x̄′)}.

**Conditions.**
- **(S-multi):** for every t, every d_t-distinct pair of X_{W,t} is t-confusable.
- **(S-joint):** for every t, every d_t-distinct pair (x̄, x̄′) of X_{W,t} is *forced*, i.e. its length-s prefixes are s-confusable for some s ≤ t.
- **(J):** for **all** append-only families G: [Ver_s for all s] ⇔ [G_s determines d_s on X_{W,s} for all s].

## Claims

| Claim | Statement |
|---|---|
| T1 (theorem under test) | (J) ⇔ (S-joint) |
| T2 | (S-multi) ⇒ (S-joint) (trivial: s = t) |
| T3 (strictness search, exploratory) | there exist instances with (S-joint) ∧ ¬(S-multi), i.e. per-step necessity is *not* jointly required. Report the count and the smallest instance found |
| C-F2-8 (control) | the named F2-8 instance has ¬(J) and ¬(S-joint) |

## Population

- n = 2; X = {0,1}; O = {0,1}.
- F_1 is a random table on X; F_2 is a random table on X².
- W is a random subset of the 16 worlds, containing at least one ō for each x̄ that is present. Each x̄ is present with probability 0.9; each world (x̄, ō) is kept with probability p ∈ {0.3, 0.6, 0.9}.
- 3 × 2,000 instances. Seed 20261022.

## Method

- **Ver_s and "G_s determines d_s"** are computed **directly from the definitions** by brute force over W_s and X_{W,s}, not via confusability.
- **The PC families on the x-histories present** are enumerated exhaustively per instance. Every partition of each length layer is filtered by an explicit pairwise prefix-closure check.
- **(S-joint) and (S-multi)** are computed from the confusability definitions.

## Failure criteria

- Any instance where (J) and (S-joint) disagree → T1 refuted. Report the instance; do not change the protocol.
- T3 is exploratory. A count of 0 does **not** refute T1.

## Limits

- n = 2, binary only. The test checks T1 in a tiny domain; the proof is separate.
