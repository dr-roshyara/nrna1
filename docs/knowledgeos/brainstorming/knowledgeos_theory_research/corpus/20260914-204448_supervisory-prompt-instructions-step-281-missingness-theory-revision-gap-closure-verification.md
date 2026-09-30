# SUPERVISORY PROMPT INSTRUCTIONS: STEP 281 — MISSINGNESS THEORY REVISION AND GAP CLOSURE VERIFICATION

**Date:** 2026-08-30
**Author:** HPA Supervisory Authority (Senior Mathematician · Senior Statistician · Senior DDD Architect)
**Purpose:** Commission the repair of the missingness defect and the verification of all remaining gaps.

---

## PROMPT INSTRUCTIONS FOR CHATGPT

You are to write **Step 281 — Missingness Theory Revision and Gap Closure Verification**.

Step 280 has executed the empirical closure test. The result is clear:

> **EC = NOT ACHIEVED**

The reason is **Critical Failure #7**: missingness is silently converted into a substantive value. The theory cannot distinguish:

- "nobody ever asked" from
- "we asked and it isn't there"

Both are `a ∉ 𝒜`. Absence-from-a-set is a single value doing two jobs. This is a **theory defect** (`T`), not an implementation defect.

The independent review confirms that Step 281 must **repair the defect** and **re-verify closure**.

The governing principle is:

$$
\boxed{
\text{Contradiction} \rightarrow \text{Reproduce} \rightarrow \text{Classify} \rightarrow \text{Locate} \rightarrow \text{Correct} \rightarrow \text{Retest}
}
$$

Step 280 completed up to **Locate**. Step 281 must complete **Correct** and **Retest**.

---

## Part 1: Mandate

### 1.1 What Step 281 Must Accomplish

1. **Repair the missingness theory defect** (T-1, T-2)
2. **Choose the canonical representation** for missingness, absence, not-asked, and orphan states
3. **Prove that the repair preserves existing invariants** (identity, equality, lineage, replay, transformation, minimality)
4. **Re-run E4** (the failed missingness test) and verify it passes
5. **Re-run affected falsification tests**
6. **Update the gap register** with empirical evidence
7. **Verify all remaining gaps** are genuinely closed or explicitly deferred
8. **Determine whether internal closure is achieved**

### 1.2 What Step 281 Must Not Do

- Do not redesign the entire theory
- Do not introduce unnecessary complexity
- Do not ignore the minimality criterion
- Do not silently repair without evidence
- Do not claim closure without verification
- Do not proceed to Step 282 until missingness is resolved

### 1.3 The Governing Principle

$$
\boxed{
\text{Minimum extension preserving all mandatory distinctions.}
}
$$

The repair must be the **minimum** change required to distinguish all empirically required forms of absence, unknownness, non-asking, and disconnected knowledge, without violating the already-established invariants.

---

## Part 2: The Missingness Defect

### 2.1 What Was Observed (E4)

| Case | Current Representation | Distinguishable? |
|:---|:---|:---|
| Unknown (no evidence) | Σ = (Neutral, None) | ✅ |
| Absent | a ∉ 𝒜 | ✅ |
| **Not-asked** | **a ∉ 𝒜** | ❌ **INDISTINGUISHABLE from absent** |
| Orphan (EKP) | Representable in EKP, NOT in K | ❌ **No K representation** |

### 2.2 The Theory Defect

`𝒜 = Set(Assertion)` has no representation for "a proposition that was never formed." Absence-from-a-set is a single value doing two jobs.

**Error category:** `T` — theory defect.

### 2.3 Candidate Repairs

| Option | Description | Pros | Cons |
|:---|:---|:---|:---|
| **A — Explicit ⊥-assertion** | Introduce a bottom assertion to represent "not asked" | Clean, minimal | May conflate with "unknown" |
| **B — Questions-asked register** | Separate structure recording propositions that were queried | Preserves provenance | Adds complexity |
| **C — Hybrid** | ⊥-assertion + questions-asked register for provenance | Most complete | May be redundant |

---

## Part 3: The Required Analysis

### 3.1 Distinguishability Analysis

For each candidate repair, test:

| State | Representation | Distinguishable? |
|:---|:---|:---|
| Not asked | ? | ✅ Must be distinguishable |
| Asked + absent | ? | ✅ Must be distinguishable |
| Asked + unknown | ? | ✅ Must be distinguishable |
| Asked + supported | ? | ✅ Must be distinguishable |
| Asked + refuted | ? | ✅ Must be distinguishable |
| Asked + conflicted | ? | ✅ Must be distinguishable |
| Orphan (asserted but unconnected) | ? | ✅ Must be distinguishable |

### 3.2 Minimality Analysis

For each candidate repair:

1. What components must be added?
2. What components can remain unchanged?
3. Does the repair violate existing invariants?
4. Is the repair minimal, or is there a smaller change?

### 3.3 Invariant Preservation

For each existing invariant, verify:

- Identity preservation: Can identity still be uniquely determined?
- Equality preservation: Does observational equivalence still hold?
- Lineage preservation: Can lineage still be traced?
- Replay preservation: Can replay still reconstruct historical states?
- Transformation preservation: Can transformations still be computed?
- K-minimality preservation: Is K still minimal under the repair?

### 3.4 Empirical Re-Test

After applying the repair:

1. Re-run E4 (missingness test)
2. Re-run affected falsification tests
3. Verify that all tests pass

---

## Part 4: The Revised Gap Register

### 4.1 Gaps to Close

| # | Gap | Status | Resolution |
|:---|:---|:---|:---|
| T-1 | 'not asked' and 'absent' indistinguishable | OPEN | Apply missingness repair |
| T-2 | 'orphan' has no K representation | OPEN | Apply missingness repair |
| T-3 | No probability space | BLOCKED | Defer to measurement work |
| T-4 | Non-identifiability inexpressible | OPEN | Verify whether repair addresses this |
| I-1 | 15 of 24 constructs not observable | PARTIAL | Document as implementation limitation |
| I-2 | circular_dependency warning | OPEN | Verify resolution |
| I-3 | No Authorize() runtime | PARTIAL | Document as implementation limitation |
| Ε-1 | 16 PASSes are Level 4, not Level 5 | OPEN | Document as implementation limitation |
| Ε-2 | No multi-node deployment | OPEN | Document as implementation limitation |
| Ε-3 | No measurement executor | OPEN | Defer to measurement work |

### 4.2 Closure Criteria

| Foundation | Status | Evidence Required |
|:---|:---|:---|
| K | PENDING | Missingness repair + E4 re-run |
| Σ | PENDING | Missingness repair + E4 re-run |
| Evidence | PARTIAL | Document as implementation limitation |
| T | PARTIAL | Document as implementation limitation |
| Policy | PARTIAL | Document as implementation limitation |
| Authority | PARTIAL | Document as implementation limitation |
| Missingness | REPAIR PENDING | E4 re-run |

---

## Part 5: Required Artifacts

Create in `docs/knowledgeos/brainstorming/verification/step-281/`:

```text
01-MISSINGNESS-REPAIR-ANALYSIS.md
02-MISSINGNESS-REPAIR-DECISION.md
03-INVARIANT-PRESERVATION-PROOF.md
04-K-MINIMALITY-AUDIT.md
05-E4-RE-RUN-RESULTS.md
06-AFFECTED-TEST-RE-RUN-RESULTS.md
07-GAP-CLOSURE-VERIFICATION-MATRIX.md
08-REVISED-GAP-REGISTER.md
09-THEORY-READINESS-ASSESSMENT.md
10-STEP-281-COMPLETE-REPORT.md
```

### 5.1 Required Execution Artifacts

```text
step-281/exec/
├── test_missingness_repair.py
├── test_e4_rerun.py
├── test_affected_falsification.py
├── test_invariant_preservation.py
├── OUT-REPAIR-ANALYSIS.txt
├── OUT-E4-RERUN.txt
├── OUT-AFFECTED-TESTS.txt
├── OUT-INVARIANTS.txt
└── OUT-TIMESTAMP.txt
```

---

## Part 6: The Traceability Matrix

| Component | Step 280 Status | Step 281 Action | Expected Result | Evidence |
|:---|:---|:---|:---|:---|
| Missingness | FAILED (E4) | Repair | PASS | E4 re-run |
| K | CLOSED | Verify | Unchanged | Invariant proof |
| Σ | PARTIAL | Verify | Unchanged | Invariant proof |
| Evidence | PARTIAL | Document | Unchanged | Gap register |
| T | PARTIAL | Document | Unchanged | Gap register |
| Policy | PARTIAL | Document | Unchanged | Gap register |
| Authority | PARTIAL | Document | Unchanged | Gap register |
| Equality | CLOSED | Verify | Unchanged | Invariant proof |
| Lineage | CLOSED | Verify | Unchanged | Invariant proof |

---

## Part 7: The Final Report Format

At completion, report:

```text
STEP 281 — MISSINGNESS THEORY REVISION AND GAP CLOSURE VERIFICATION

Missingness repair:
    Defect: T-1, T-2
    Candidate options: A, B, C
    Chosen: [A / B / C]
    Rationale: ...

Invariant preservation:
    Identity: PRESERVED / MODIFIED / BROKEN
    Equality: PRESERVED / MODIFIED / BROKEN
    Lineage: PRESERVED / MODIFIED / BROKEN
    Replay: PRESERVED / MODIFIED / BROKEN
    Transformation: PRESERVED / MODIFIED / BROKEN
    K-minimality: PRESERVED / MODIFIED / BROKEN

E4 re-run:
    Result: PASS / FAIL
    Evidence: ...

Affected tests re-run:
    Passed: X / Y
    Failed: X / Y

Gap register:
    Closed: X
    Partial: Y
    Open: Z

Formal closure:
    CONFIRMED

Computational closure:
    CONFIRMED

Empirical closure:
    ACHIEVED / PARTIAL / NOT ACHIEVED

Governance closure:
    NOT CLAIMED

Theory completeness:
    NOT CLAIMED

Remaining gaps:
    ...

Next:
    STEP 282 — THEORY CLOSURE DECISION
```

---

## Part 8: Non-Negotiable Rules

### Rule 1 — Minimum Extension

Choose the smallest repair that distinguishes all mandatory states. Do not add unnecessary structure.

### Rule 2 — Invariant Preservation

Every existing invariant must be preserved. If an invariant must change, document the change and its justification.

### Rule 3 — Empirical Verification

The repair must be tested empirically. Do not assume it works.

### Rule 4 — No Silent Repair

Document the repair, the reasoning, the tests, and the results.

### Rule 5 — No Premature Closure

Do not claim empirical closure until E4 and affected tests pass.

### Rule 6 — The Two Repair Principles

- **Distinguishability:** Every mandatory epistemic state must be distinguishable.
- **Minimality:** The state representation must be the minimum necessary to achieve distinguishability.

---

## Part 9: The Supervisory Verdict

### Status

```
Step 281 is COMMISSIONED.
```

### The Final Statement

Step 281 must:

1. **Repair the missingness defect** (T-1, T-2)
2. **Choose the canonical representation** (A, B, or C)
3. **Preserve all existing invariants**
4. **Re-run E4 and affected tests**
5. **Update the gap register**
6. **Determine whether empirical closure is achieved**

The theory does **not** become complete at the end of Step 281. That belongs to Step 282.

---

## Part 10: The Dependency Chain

```
Step 278: Formal Specification → CONFIRMED
Step 279: Computational Closure → ACHIEVED
Step 280: Empirical Closure → EC = NOT ACHIEVED
Step 281: Missingness Theory Revision → COMMISSIONED
Step 282: Theory Closure Decision → PENDING
Step 283: Governance Ratification → PENDING
Step 284: Book Architecture Gate → FUTURE
```

---

**HPA Supervisory Ruling**
**Date: 2026-08-30**
**Status: COMMISSIONED**
**Next: STEP 281 — MISSINGNESS THEORY REVISION AND GAP CLOSURE VERIFICATION**

---

*END OF PROMPT INSTRUCTIONS*