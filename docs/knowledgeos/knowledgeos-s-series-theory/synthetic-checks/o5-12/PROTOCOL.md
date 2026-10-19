# O5-12 protocol (declared before any code is written or run)

**Question.** If the state budget N is large enough for a non-trivial coarsening of the exact kernel to exist, is the best N-state approximation a coarsening of it, i.e. a quotient of the minimal machine by a congruence?

**Population.**
- Random minimal trim Moore machines M with n = 4 states, Σ = {0, 1}, binary outputs, start 0, seed 20261017.
- Rejection sampling keeps only machines that have at least one congruence with m blocks, 2 ≤ m ≤ 3 (non-trivial).
- Sample size: 60 machines.

**Budget.** N = m*, the smallest block count of a non-trivial congruence of M.

**Loss.**
- L = (1/(T+1)) Σ_{t=0..T} P[f(s(H_t)) ≠ d(H_t)], with inputs i.i.d. uniform.
- Primary horizon T = 6; secondary T = 10.

**Candidate sets.**
- G = all N-state recursive realizations (any table, start 0, any decoder).
- Q = all realizations whose state is a function of M's state on reachable histories. These are the quotients of M by congruences with ≤ N blocks, with any decoder.

**Measures.**
- **Q1:** fraction of machines with min_G L < min_Q L − 1e-12 (approximation strictly beats every coarsening).
- **Q2:** fraction of machines where some G-optimum lies in Q.
- **Q3:** the mean and maximum of (min_Q L − min_G L).
- **Q4:** the same as Q1 at T = 10.

**Decision rule, fixed in advance.**
- If Q1 = 0 at both horizons: report "in this population, coarsenings are optimal when they exist".
- If Q1 > 0: report it as a counterexample to "coarsenings are optimal", together with the smallest such machine.

**Limits.** Synthetic population only. n = 4, |Σ| = 2, one distribution family. The result is not a general theorem.
