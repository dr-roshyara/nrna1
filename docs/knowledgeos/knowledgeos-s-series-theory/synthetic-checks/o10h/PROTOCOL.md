# O-10h: pre-declared high-power replication of O-10a C2 and C4 (frozen before any code is written)

**Claims under test**
- **C2:** ≡\* is the greatest element of F = {π ∈ PC : π ⊆ D}.
  - *Failure condition:* any instance where ≡\* ∉ F, or where some π ∈ F is not ⊆ ≡\*.
- **C4:** for s : L → {0,1}, some π ∈ F does not determine s ⇔ ≡\* does not determine s.
  - *Failure condition:* any instance where the two sides differ.

**Population (exhaustive within each instance; instances sampled at random)**
- Σ = {0,1}, n = 3, L = all strings of length ≤ 3 (15 histories).
- Decision count m ∈ {1, 2}, each decision binary, with P(value = 1) = q.
- q ∈ {0.1, 0.2, 0.3}. For each q there are **100 accepted instances**, for 300 in total.
- Seed: 20261019, Python `random.Random`, consumed in the order q = 0.1, 0.2, 0.3; within each q, the instance draws come in sequence.
- m is drawn uniformly from {1, 2} per instance.

**Selection rule (fixed in advance; it uses only the size of F)**
- Accept an instance iff 10 ≤ |F| ≤ 20000.
- Rejected draws are counted and reported.

**Deliberately wrong candidates.** Each must fail the C2 greatest-element test on every instance where it differs from ≡\* as a relation.

| ID | Wrong candidate |
|---|---|
| W1 | same length, and equal D at the **last step only** |
| W2 | same length, and equal D at the **last two steps** only |
| W3 | the identity relation (the raw log) |
| W4 | ≡\* with the t = 0 prefix condition omitted |
| W5 (for C4) | the rule "feasible iff the identity record does not determine s" |

*Detection:*
- W1–W4 are "detected" if the candidate fails "∈ F and contains every member of F".
- W5 is "detected" on instances where its verdict differs from the true one.

**Expected outcomes**
- C2 and C4 hold on 300 / 300 instances.
- W1–W4 are detected on 100% of the instances where they differ from ≡\*.
- W5 is detected on at least one instance.

**Failure criteria**
- Any C2 or C4 failure → the claim is refuted. Report the instance; do not change the protocol.
- Any undetected W1–W4 that differs from ≡\* → a test defect. Report it.

**Recorded:** the protocol hash, the code hash, the Python version, the platform, and every result, including rejected-draw counts.

**Limits.**
- This exhaustive enumeration inside tiny instances checks an implementation and can expose counterexamples.
- It adds little to the proof of Theorem 2, and it does not establish anything about larger n or other alphabets.
