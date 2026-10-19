# O-10h: pre-declared high-power replication of O-10a C2 and C4

| Field | Value |
|---|---|
| Author / clock | Session B, 2026-10-17 20:52 |
| Protocol | `../synthetic-checks/o10h/PROTOCOL.md`, sha256 `5cf1cce8fcf93ad668845816e5ab5a6cac15100882a1774c62ada9aa03efd682`, frozen at 20:32:01, **before the code** |
| Code | `o10h_replication.py`, sha256 `e5396e18a057fe539d7529c3d1b8b48898c65da382bfc4817b5fcfd3c7af5ef0` (written 20:33) |
| Results | `RESULTS.txt`: first run 20:33:42, re-run 20:52:07 to save the output with its hashes; identical output. Python 3.13.2, Linux 7.2.8. |
| Review | INTERNALLY REVIEWED — SAME MODEL FAMILY (refute first). The reviewer did **not** re-run the script. |

## 1. Results (exactly as produced)

| Measure | Result |
|---|---|
| C2 (≡\* is the greatest feasible record) | **300 / 300 hold; 0 failures** |
| C4 (privacy feasible ⇔ r\* complies) | **300 / 300 hold; 0 failures** |
| Accepted / rejected draws | 300 accepted / 85 rejected (all rejections had \|F\| < 10) |
| Feasible-family size \|F\| | min 10 · median 133 · max 7,176 |
| W1 (last step only) | differs on 216; detected on 216 |
| W2 (last two steps) | differs on 76; detected on 76 |
| W3 (identity) | differs on 300; detected on 300 |
| **W4** (t = 0 condition omitted) | **differs on 0: a vacuous candidate** |
| **W5** (C4 wrong rule) | detected on 296 / 300: **vacuous by construction** (see §2) |

## 2. Defects and deviations (disclosed; the protocol is not edited)

| Item | Problem |
|---|---|
| **W4** | a design defect. ε is a single history, so the t = 0 condition never matters. A meaningful replacement would omit t = 1; that would be a new protocol. |
| **W5** | a constant rule. The identity record always determines s, so W5's verdict is always "infeasible", and "detected on 296" only counts the instances where ≡\* fails to determine s. **It is not a test.** |
| W1–W3 detection | **guaranteed** once C2 holds, because a greatest element is unique. It checks the **harness**, not the theorem. |
| Selection rule | cannot produce a false confirmation of a proven theorem. It removes coverage of strongly discriminating decisions (the rejected \|F\| < 10 cases). The upper bound (20,000) was vacuous, because the total PC count is 7,176. The maximum-\|F\| instance is trivial (D constant). |
| q ∈ {0.1, 0.2, 0.3} | skews the sample towards near-constant decisions |
| Same enumerator | C2 uses O-10a's `pc_feasible`, so this is **not an independent implementation**. The reviewer's separate enumerator agrees on the total PC count (7,176). |
| Hash recording | the protocol required recording the code hash. The first run printed only the environment. Fixed **after** the run by saving `RESULTS.txt`, with both hashes, from a re-run that produced identical output. |

## 3. What this adds

- **Implementation confidence** for O-10a's checks at higher power: median 133 feasible records, against a large vacuous share in the original population.
- **No independent evidence** for Theorem 2 beyond its proof.
- **No information** about n > 3 or other alphabets.

## 4. If a further replication is wanted (a new pre-declared cycle; not run)

- An independent enumerator.
- A W4 that omits t = 1.
- A W5 that is not constant (for example "feasible iff some single-step-only record complies").
- q extended to 0.5.
- The rejected low-|F| cases included as a separate stratum.
