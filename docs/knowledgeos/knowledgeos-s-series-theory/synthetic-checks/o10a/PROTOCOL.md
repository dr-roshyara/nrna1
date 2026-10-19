# O-10a synthetic checks: protocol (declared before the code is written)

**Model.**
- Alphabet Σ = {0,1}; histories of length ≤ n, with n = 3.
- A legal language L, which is prefix-closed and contains ε. Two cases: L = all strings, or a random prefix-closed subset.
- Decisions d : L → V, with no assumption outside L. Up to 2 decisions, each with values in {0,1,2}.

**Objects.**
- An online append-only record is r(h·a) = r(h)·ρ(h, a); its kernel is ker r.
- PC = the set of prefix-closed, length-preserving equivalences on L (h π h′ ⇒ |h| = |h′| and every prefix pair is π-related).
- D = ⋂ ker d, restricted to equal lengths.
- ≡\* is defined by: h ≡\* h′ iff |h| = |h′| and h_t D h′_t for every prefix length t.

**Claims to check.**

| Claim | Statement |
|---|---|
| C1 | Every online record's kernel is in PC; every π ∈ PC is the kernel of an online record. (Checked analytically; the code tests that the class-log record realizes π.) |
| C2 | ≡\* ∈ PC, ≡\* ⊆ D, and ≡\* is the **greatest** element of {π ∈ PC : π ⊆ D}, found by brute-force enumeration of PC. |
| C3 | The kernel of the fixed letter-wise record φ = [·]≈ (D10-1, L = all strings) is contained in ≡\*. Some instance makes the containment strict (the first-letter decision). |
| C4 | For a private attribute s : L → {0,1} and a family F = {π ∈ PC, π ⊆ D, π does not determine s}: F ≠ ∅ iff ≡\* does not determine s. |
| C5 | The field-subset (projection) family is not join-closed. Instance: fields x, y, z = x ⊕ y with decision x ⊕ y; minimal field sets {z} and {x,y} have different kernels. (Analytical; also enumerated.) |
| C6 | The count of classes per length is monotone non-increasing under coarsening (exact integers), while a naive per-step log size is not monotone (counterexample constructed). |

**Generation.** Seed 20261017. 300 random instances per L-type, plus fixed named instances.

**Limits.** Tiny finite instances only. These checks can refute claims; they cannot prove them for all cases.
