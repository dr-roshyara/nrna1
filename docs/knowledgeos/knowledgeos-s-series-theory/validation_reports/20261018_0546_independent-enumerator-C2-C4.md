# Independent second enumerator for O-10a C2/C4 (Priority B)

| Field | Value |
|---|---|
| Clock | 2026-10-18 05:46 |
| Process | A worker sub-agent was given the **mathematical specification only**: no access to earlier code or results. It froze a protocol, recorded its code identity, then ran once. A separate refute-first reviewer re-ran the check and verified it with a **third**, different method (`indep.py`). |
| Review status | **PASS WITH CONDITIONS — INTERNALLY REVIEWED, SAME MODEL FAMILY** |
| Artifacts | `../synthetic-checks/o10-independent/worker/`: PROTOCOL `c621acf55ea0fc22317a8bd7d58367aae864a485ec1d3ec361f4ed78a38877be` (frozen 05:37:32); check.py `91889b7b18573e91396ed1b7e98cbf7ee18ee86c1ec882bc8f5c672d599e7293` (identity recorded 05:38:13, before the single run); RESULTS.txt. Environment: Python 3.13.2, numpy 2.5.0, Linux 7.2.8. `../reviewer/`: indep.py, indep.out, rerun.txt (byte-identical re-run) |

## 1. Method (independent of the O-10a / O-10h enumerator)

- **Partitions by layer:** all set partitions of each length layer, as restricted-growth strings. Bell numbers 1, 2, 15, 4140, giving a Cartesian product of 124,200 candidates.
- **Prefix-closure:** checked as an explicit pairwise filter.
- **The C4 "truth":** computed as the union of all feasible relations, without using ≡\*.
- **Third method (reviewer):** a layer recursion within child blocks.

## 2. Results

| Stratum | Instances | C2 failures | C4 failures |
|---|---|---|---|
| S0 (extra): **all** 2¹⁵ single binary decision tables | 32,768 (**2¹⁴ effectively distinct**: the value at ε never matters) | 0 | 0 |
| S1: the small-family stratum \|F\| ≤ 9, previously excluded; exhaustive | 5,632 | 0 | 0 |
| S2: seeded random, q ∈ {0.1, 0.3, 0.5}, half of them double decisions, **no size rejection** | 300 | 0 | 0 |
| S3: named (constant, first symbol, last symbol) | 5 | 0 | 0 |

- **\|PC\| = 7,176**, confirmed by **two different methods**.
- The \|F\| distribution is identical between the worker's and the reviewer's methods.

**Non-vacuous negative candidates** (the corrected W4/W5):

| Candidate | Differs from ≡\* (S0) | Detected |
|---|---|---|
| N1 last step only | 30,592 | 30,592 |
| N2 last two steps | 14,000 | 14,000 |
| N3 identity | 32,512 | 32,512 |
| N4 omit t = 1 (replaces the vacuous W4) | 16,384 | 16,384 |
| N5 last-step predicate (replaces the constant W5) | 30,592 | 30,592 |

## 3. Conditions of the review (stated as limits)

1. **C4 is implied by C2 on this domain.** If ≡\* is the greatest element, the union of F equals ≡\*. So C4 is a second calculation path, **not separate corroboration**.
2. **The negative tests show only that the checker is non-trivial.** Once C2 holds, "detected ⇔ differs" is guaranteed by uniqueness. They cannot catch an error shared between the ≡\* computation and the C2 check.
3. **The effective instance count is 2¹⁴,** because of the ε redundancy.
4. **Timestamps are self-attested.** They show the order of creation, not that no results existed before.

## 4. What this establishes, and what it does not

- **Establishes:**
  - C2 (and so C4) holds **exhaustively** for every binary single-decision table, plus 300 random and 5 named instances, on Σ = {0,1}, n = 3;
  - the implementation agrees with an independently designed enumerator, and with a third method.
- **Does not establish:**
  - anything for n > 3, larger alphabets or general codomains (only 150 four-valued instances);
  - any evidence for Theorem 2 beyond its proof.
- **Value:** it closes the "same enumerator" limitation of O-10h.
