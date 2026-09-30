# 1ak: strict-witness hunt (A: evidence; B/C: operation/state), with a disclosed strict-coding correction

| | |
|---|---|
| Status | research record. Not canonical. Authority: none |
| New corpus exposure | **locate-level only** (matched lines + headings). The pairs added in r3 come from rows already read (R-56, R-58, R-65) |
| Files | `WITNESS3/SPEC-A-LOCATE.json` (frozen `a15325f47`) · `LOCATE-A.json` · `SEMOBS/semobs_r2.py` (`543ac3a1…`) → `RESULT-r2.json` · `SEMOBS/semobs_r3.py` (`b9f4849d…`) → `RESULT-r3.json` (`76e4780e…`) |
| Log | F-LOG-0142 |

## A. Evidence outside the R-36 cluster: NOT FOUND (targeted locate)
- **SOURCE OBSERVATION:** 7 lines match both a raise cue and a count cue; 5 of them name an outcome.
- For every candidate, the enclosing section contains **no promoted counterpart**. Each is a refusal only:
  - 2026-07-28 L496, "observed once; NOT promoted";
  - L582, "Five observations … NOT promoted";
  - 2026-07-31 L185;
  - 2026-07-30 L1215;
  - 2026-07-26 L323.
- A cross-cluster partner would differ in authority, kind or target, or have them unknown. **→ No strict witness. Evidence stays WEAK (1 cluster).**
- **OBSERVATION (locate level; the matched line only):** "Governance Verification Drift deliberately NOT promoted to doctrine — three occurrences in one subsystem recognize a pattern; they do not constitutionalize …" (2026-07-31 L185).
  - That is a refusal at three instances within one context.
  - **It fits a context-diversity bar** (as in matrix #18) and **counts against an instance-count bar of ≥ 2** (v1.1's RAISE bar).
  - HYPOTHESIS: the evidence variable is measured in independent contexts, not instances.

## Strict-coding correction (r2; disclosed)
Two F-LOG-0141 encodings broke schema v1's own rule:
1. **R-95 "in force"** was inferred from the *absence* of a PREPARED marker. NOT-RECORDED ≠ in force, so its outcome is `UNK` and it is removed from the witness set.
2. **START WP-4B at 16:10, state "auth-full"**, rested on an *interpretation* of R-78 (F-LOG-0129 left I-A1 UNDETERMINED), so `s = UNK`.

**→ operation: NOT DEMONSTRATED** (its only witness, R-89 vs R-95, is gone). **State: NOT DEMONSTRATED** at r2.

## B/C. State pairs from already-read rows (r3)
- **Consistency decision (declared):** for START, `s` is the **authorization state of the act's target**. R-47's events are therefore coded target-specifically, as R-47 itself states them.
- **Added SOURCE FACTS:**
  - R-56: "Execution of 7B is a SEPARATE act and has NOT been issued";
  - R-58: "Engineering is authorized to begin Slice 7B RED"; "Slice 7C remains unauthorized";
  - R-65: "Slice 7C AUTHORIZED … engineering may begin RED".

| Variable | Verdict (R4: distinct cluster-pairs) | Witnesses | Stricter check (disjoint clusters) |
|---|---|---|---|
| authority | EMPIRICALLY SUPPORTED | {R-70, R-89}, {R-81…85, R-86} | **2 disjoint** ✓ |
| kind | EMPIRICALLY SUPPORTED | {R-60}, {S0804-L576} | **2 disjoint** ✓ |
| **state** | **EMPIRICALLY SUPPORTED** | {R-47, R-58}, {R-56, R-58}, {R-58, R-65} | **1 disjoint** (all share R-58) → **WEAK** under the stricter criterion |
| evidence | WEAK | {R-36} | 1 |
| **target/scope** | **NOT DEMONSTRATED (4 possible)** | — | — |
| operation | NOT DEMONSTRATED | — | — |
| history | NOT DEMONSTRATED (1 possible) | — | — |
| conformance, route, exception | NOT DEMONSTRATED | — | — |

## FORMAL RESULT (state minimization, candidate)
- Once state is indexed by target, **no target-only pair exists** for execution legality.
- Target/scope then acts as an **index on the state** (authorization(target)), not as an independent legality variable.
- This is the first instance of the state-equivalence question: the earlier "target necessary" result (F-LOG-0140) was an artefact of programme-level state coding.

## Summary by category
- **HISTORICAL FACT:** unchanged.
- **REPRESENTATIONAL RESULT:** superseded by the strict empirical counts. The eight-field set belonged to a lenient table.
- **EMPIRICAL RESULT:**
  - authority and kind pass even the disjoint criterion;
  - state passes R4 but not the disjoint criterion;
  - evidence has one cluster;
  - operation, target, history, conformance, route and exception are not demonstrated.
- **HYPOTHESIS:** evidence is measured in contexts; role separation; the choice layer; scope-as-index.
- **FALSIFIER:** for scope-as-index, a pair with an equal target-indexed authorization state, different targets and opposite legality.
- **UNKNOWN:** R-95's status; WP-4B's state at 16:10; route for the numbering acts.
- **GOVERNANCE DECISION:** none. Gate 1 proper is pending externally; the bundle is untouched.

## Next (only observations that move a verdict)
1. **State, disjoint witness:** an authorization-state pair that doesn't involve R-58 (e.g. R-50's reproduction track, or WP-6 remediation R-52 → R-55). Locate first.
2. **Operation:** two acts by the same authority, kind and target state, with **both statuses stated**, different operations and opposite outcomes. Chief rows where "PREPARED" and "GOVERNING / EFFECTIVE" are stated explicitly.
3. **Evidence:** a strict pair outside R-36, preferably measured in contexts.
4. **Target / scope-as-index:** the falsifier above.
5. **Route, exception, conformance:** designed observations only.

## 1ak-2 (round 2): a disjoint state witness (`SEMOBS/semobs_r4.py`, committed before its run at `239314171`; `RESULT-r4.json`)
**SOURCE FACTS** (rows already read; no new exposure):
- R-81: "Batch 7 partially released: isolation repair ACTIVE, **all other Batch-7 work still frozen**".
- R-86: "WP-4B BATCH 7 IS RELEASED · engineering is AUTHORIZED to execute … **R-84's §12 reconcile**".

**SEMANTIC OBSERVATION:** START(§12 reconcile) is not authorized at R-81 and authorized at R-86.
- Same operation, authority, kind and target. The state differs.
- The reconcile work's Batch-7 membership is stated in R-86, so its state at R-81 is **DERIVED**, not inferred.

**Verdicts under both independence rules:**

| Variable | R4 (distinct cluster-pairs) | Disjoint clusters |
|---|---|---|
| authority | SUPPORTED | **2** ({R-81…85, R-86}, {R-70, R-89}) |
| kind | SUPPORTED | **2** ({S0804-L576}, {R-60}) |
| **state** | SUPPORTED | **2** ({R-58, R-65}, {R-81, R-86}) → **now SUPPORTED under the stricter criterion too** |
| evidence | WEAK | 1 |
| target, history | NOT DEMONSTRATED (possible) | 0 |
| operation, conformance, route, exception | NOT DEMONSTRATED | 0 |

**Theory status (EMPIRICAL, not theoretical):**
- **authority, object kind and target-indexed state** are the three variables with ≥ 2 disjoint strict witnesses;
- evidence is weak;
- target acts as an index on state (candidate);
- everything else is not demonstrated.

**STOP:** a variable received a strict second witness (the stop rule).
