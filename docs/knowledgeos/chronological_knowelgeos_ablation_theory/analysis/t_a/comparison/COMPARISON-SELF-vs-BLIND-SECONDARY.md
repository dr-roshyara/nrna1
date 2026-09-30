# T-A comparison — SELF vs BLIND SECONDARY (class: EXPERIMENT; reproducibility check, not independent replication)

| | |
|---|---|
| **Ledgers (both sealed before comparison)** | SELF `ledgers/SELF/records.jsonl` sha256 `abfa919b…1ce8` (F-LOG-0040) · BLIND SECONDARY `ledgers/BLIND-SECONDARY/records.jsonl` sha256 `de5f2322…1705` (F-LOG-0043) |
| **Readers** | SELF: Claude, author of H-F2-1-R, not blind. BLIND SECONDARY: a fresh-context Claude subagent, blind to results and predictions (it disclosed that commit subjects revealed a SELF result *exists*). **Same model family, so SECONDARY_REVIEW. The INDEPENDENT reader (L0-DEC-31) is still open** |
| **Tools** | `aggregate.py` (frozen, `13532a5b…`) run separately on each ledger · `compare_ledgers.py` (sha256 `1ef5cbb8…`) |

## 1. Per-ledger frozen aggregation (never averaged)

| Test | SELF | BLIND SECONDARY | Same? |
|---|---|---|---|
| F-A2e | SUPPORTED | **INCONCLUSIVE** | ✗ |
| F-A3g | SUPPORTED | SUPPORTED | ✓ |
| F-A4 | SUPPORTED | SUPPORTED | ✓ |
| F-A5e | INCONCLUSIVE | INCONCLUSIVE | ✓ |
| F-A5g | SUPPORTED | SUPPORTED | ✓ |
| F-A6 | NOT_EVIDENCED | **INCONCLUSIVE** | ✗ |
| F-A0 | SUPPORTED | SUPPORTED | ✓ |
| F-A3m | SUPPORTED | SUPPORTED | ✓ |
| Q-GS, Q-D4 | NOT_RUN | NOT_RUN | ✓ |
| **H-F2-1a (r3 §5)** | **INCONCLUSIVE** | **INCONCLUSIVE** | ✓ |
| Stability extension | NOT_REFUTED_IN_T-A | NOT_REFUTED_IN_T-A | ✓ |
| DIRECT COUNTEREXAMPLES | 0 | 0 | ✓ |

- Aggregates: SELF `0d003b6d…`; BLIND SECONDARY `ledgers/BLIND-SECONDARY/aggregate.json` sha256 `fbe18e695c02510657a6a7afd596929fdeb10f9bc5737a016398c26ee05945db`.
- Both readers agree on the outcome category and on 6 of 8 axiom verdicts. They differ on two verdicts; both differences come from **BLIND-ONLY AMBIGUOUS records with a live counterexample reading** (§3).

## 2. Passage-level table

**Primary rule, fixed in the script before its output was seen:** same source family and Jaccard ≥ 0.30. Output: `comparison/SELF-vs-BLIND-SECONDARY.json` (sha256 `3b94bbc0…`).

| Class | Count |
|---|---|
| AGREEMENT (same test, same classification) | 15 |
| DISAGREEMENT (same test, different classification) | **0** |
| CROSS-TEST (the same passage filed under different questions) | 15 |
| SELF-ONLY | 11 |
| BLIND-ONLY | 17 |
| UNKNOWN | 0 |

**Post-hoc sensitivity rule (labelled post-hoc; chosen after seeing the primary table):** also match on ≥ 80% token containment, because the primary rule under-matches short vs long quotes of the same passage. Output: `comparison/SELF-vs-BLIND-SECONDARY.sensitivity-containment.json` (sha256 `1c727222…`).

| Class | Count |
|---|---|
| AGREEMENT | 18 |
| DISAGREEMENT | **1** |
| SELF-ONLY | 7 |
| BLIND-ONLY | 13 |

The one DISAGREEMENT is F-A5e, P-8 *"an experiment's run state"*: SELF says DIRECT_SUPPORT (LOW); BLIND says AMBIGUOUS, with NOT_BEARING as the competing reading. It does not change a verdict.

## 3. The two verdict-driving differences (both observations preserved; neither resolved)

| Test | Passage | SELF | BLIND SECONDARY |
|---|---|---|---|
| **F-A2e** | F0018 §2, P-3: *"a ladder — monotonic, no return edges except demotion-by-decision"* | filed under **F-A6** as OBJECT_LEVEL, NOT_EVIDENCED (does not bear on A6), and under Q-H6 (cross-test) | **AMBIGUOUS, competing DIRECT_COUNTEREXAMPLE.** Reading B: a decision moves an item down a ladder that the source types *"EVIDENTIAL + governance gate"*, i.e. a governance act may directly change a position the source also treats as evidential |
| **F-A6** | ES-006 `668cc7b22`, Registered pointers: *"DDD Tactical Governance Principles (methodology module — ADOPTED via explicit DA early-promotion exception R-39)"* | **not recorded** (SELF did not identify this passage) | **AMBIGUOUS, competing DIRECT_COUNTEREXAMPLE.** Reading B: an early-promotion **exception** is a rule-level departure from the ES-006.1 bar for one item, which changed a pending object's qualification (to ADOPTED) with no evidential event mentioned |

A further BLIND-ONLY F-A6 record, ES-006.2 (*"changes only from pilot/operational evidence"*), is AMBIGUOUS with NOT_BEARING as the competing reading. It does not drive the verdict.

## 4. Combined T-A result

**r3 has no rule for merging readers**, only *"disagreements are recorded, not averaged"*. Both ledgers, aggregated separately under the frozen rules, give the same category:

> ## **T-A outcome (SELF + BLIND SECONDARY): INCONCLUSIVE.** 0 direct counterexamples in either ledger.

- It is **not independently replicated**. The INDEPENDENT reader is still required.
- It is not a validation, not a refutation, and not canonical.
- It is limited to F0018 plus the 7 ES-006 objects.
- The T-0056 caveat applies.

## 5. What the comparison shows (methodological facts, not theory)

- **Reproducibility of the category:** reproduced (INCONCLUSIVE in both).
- **Reproducibility of the per-axiom verdicts:** 6/8.
- **The SELF reader under-recorded.** It missed the R-39 early-promotion exception, one of the two passages that carry a live counterexample reading. It also resolved the P-3 demotion passage toward "not bearing", where the blind reader kept a counterexample reading open. The direction is consistent with the author bias the independence requirement exists to catch.
- **Open items after comparison:**
  - A5e: unchanged (both readers are ambiguous on *"work produces evidence"*).
  - A6: now has **a candidate counterexample reading (R-39)**, not only an absence of evidence.
  - A2e: has a new ambiguity (P-3 demotion-by-decision).
  - OBS-SF-1: unchanged.
- None of these is resolved here.
