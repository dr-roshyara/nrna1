# PROMPT INSTRUCTIONS: STEP 281 EXECUTION — MISSINGNESS THEORY REVISION

**Date:** 2026-08-30
**Author:** HPA Supervisory Authority
**Purpose:** Execute the corrected Step 281 — select the missingness repair, implement it, and verify closure.

---

## EXECUTION PROMPT FOR CLAUDE CODE CLI

You are to execute **Step 281 — Missingness Theory Revision and Gap Closure Verification**.

Step 280 established that **Empirical Closure is NOT ACHIEVED**. The reason is **Critical Failure #7**: missingness is silently converted into a substantive value. The theory cannot distinguish "nobody ever asked" from "we asked and it isn't there." Both are `a ∉ 𝒜`.

The corrected Step 281 defines:
- **Three candidate repairs** (A, B, C) with precise formal representations
- **A minimality criterion** (M1, M2, M3)
- **Seven mandatory states** to distinguish (M1-M7)
- **An orphan state analysis** (O1, O2, O3)
- **An empirical re-test protocol** (E4-R1 to E4-R7)
- **Affected falsification tests** (F1, F3, F5, F6, F10)
- **An internal closure criterion** (IC281)
- **A governance process** (6 steps)
- **15 success criteria**

Your task is to **execute** this plan and produce the evidence.

---

## Part 1: What You Must Do

### 1.1 Select the Repair

Choose between:

**Option A — Explicit Bottom Assertion:**
```
⊥-assertion = (id, p, "BOTTOM", t, π)
```

**Option B — Inquiry Register:**
```
Q_t ⊆ P
```

**Option C — Typed Epistemic State:**
```
ε_t(p) = (I_t(p), E_t(p))
I_t(p) ∈ {NotAsked, Asked}
E_t(p) ∈ {Absent, Unknown, Supported, Refuted, Conflicted}
```

**Selection Criteria:**
- Which repair is most minimal? (M1, M2, M3)
- Which repair preserves all existing invariants?
- Which repair is most consistent with the existing theory?
- Which repair has the lowest implementation cost?

**Document the selection rationale.**

### 1.2 Distinguishability Analysis

For the chosen repair, verify that all seven states are distinguishable:

| ID | State | Distinguishable? |
|:---|:---|:---|
| M1 | Not Asked | ✅ Must be |
| M2 | Asked + Absent | ✅ Must be |
| M3 | Asked + Unknown | ✅ Must be |
| M4 | Supported | ✅ Must be |
| M5 | Refuted | ✅ Must be |
| M6 | Conflicted | ✅ Must be |
| M7 | Orphan | ✅ Must be (structural) |

**Document the distinguishability proof.**

### 1.3 Minimality Analysis

Verify the minimality criterion:

```
R* is minimal iff:
    1. All mandatory distinctions are preserved
    2. No proper subset is sufficient
    3. No unnecessary distinctions are introduced
```

**Document the minimality proof.**

### 1.4 Invariant Preservation

Verify that the repair preserves:

- Identity preservation
- Equality preservation
- Lineage preservation
- Replay preservation
- Transformation preservation
- K-minimality preservation

**Document the invariant preservation proof.**

### 1.5 Empirical Re-Test — E4

Execute E4-R1 to E4-R7:

| Test | Setup | Expected |
|:---|:---|:---|
| E4-R1 | No query | I(p)=NotAsked |
| E4-R2 | Query, no assertion | I(p)=Asked, E(p)=Absent |
| E4-R3 | Query, insufficient evidence | E(p)=Unknown |
| E4-R4 | Query, supporting evidence | E(p)=Supported |
| E4-R5 | Query, refuting evidence | E(p)=Refuted |
| E4-R6 | Query, conflicting evidence | E(p)=Conflicted |
| E4-R7 | Orphan document | Orphan=True, E(p) unchanged |

**Document all results.**

### 1.6 Re-Run Affected Falsification Tests

Execute:

| Test | Why Affected |
|:---|:---|
| F1 | Policy-free result may otherwise be interpreted as absence |
| F3 | Policy conflict must not collapse into unknown |
| F5 | Missing policy must remain distinct from no inquiry |
| F6 | Expired policy must not be represented as nonexistent |
| F10 | Historical replay must preserve historical state |

**Document all results.**

### 1.7 Update the Gap Register

For each gap, assign one of:

```
CLOSED — Defect repaired, test passes, evidence documented
PARTIAL — Some progress made, but not fully closed
OPEN — No progress made
BLOCKED — Requires prerequisite work
NORMATIVE — Requires governance decision
EMPIRICAL — Requires further empirical testing
```

### 1.8 Determine Internal Closure

Verify IC281:

```
IC281 = True iff:
    1. Defect has formally specified repair
    2. Repair satisfies distinguishability
    3. Repair satisfies minimality
    4. Invariants preserved or explicitly revised
    5. E4 passes
    6. Affected tests pass
    7. Orphan semantics explicitly defined
    8. Implementation traceability exists
    9. Remaining gaps classified
    10. No unresolved contradiction remains
```

---

## Part 2: What You Must Produce

### 2.1 Required Artifacts

Create in `docs/knowledgeos/brainstorming/verification/step-281/`:

```text
01-REPAIR-SELECTION.md
02-DISTINGUISHABILITY-PROOF.md
03-MINIMALITY-PROOF.md
04-INVARIANT-PRESERVATION-PROOF.md
05-E4-RERUN-RESULTS.md
06-AFFECTED-TESTS-RERUN-RESULTS.md
07-GAP-REGISTER-UPDATE.md
08-INTERNAL-CLOSURE-VERDICT.md
09-STEP-281-COMPLETE-REPORT.md
```

### 2.2 Required Execution Artifacts

Create in `step-281/exec/`:

```text
test_repair_selection.py
test_distinguishability.py
test_minimality.py
test_invariant_preservation.py
test_e4_rerun.py
test_affected_falsification.py
OUT-REPAIR-SELECTION.txt
OUT-DISTINGUISHABILITY.txt
OUT-MINIMALITY.txt
OUT-INVARIANTS.txt
OUT-E4-RERUN.txt
OUT-AFFECTED-TESTS.txt
OUT-TIMESTAMP.txt
```

### 2.3 The Traceability Matrix

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

## Part 3: The Final Report Format

At completion, produce:

```text
STEP 281 — MISSINGNESS THEORY REVISION AND GAP CLOSURE VERIFICATION

Missingness repair:
    Defect: T-1, T-2
    Candidates evaluated: A, B, C
    Chosen: [A / B / C]
    Rationale: ...

Distinguishability:
    M1: [PASS / FAIL]
    M2: [PASS / FAIL]
    M3: [PASS / FAIL]
    M4: [PASS / FAIL]
    M5: [PASS / FAIL]
    M6: [PASS / FAIL]
    M7: [PASS / FAIL]

Minimality:
    M1 (Necessity): [PASS / FAIL]
    M2 (Irreducibility): [PASS / FAIL]
    M3 (No redundancy): [PASS / FAIL]

Invariant preservation:
    Identity: [PRESERVED / MODIFIED / BROKEN]
    Equality: [PRESERVED / MODIFIED / BROKEN]
    Lineage: [PRESERVED / MODIFIED / BROKEN]
    Replay: [PRESERVED / MODIFIED / BROKEN]
    Transformation: [PRESERVED / MODIFIED / BROKEN]
    K-minimality: [PRESERVED / MODIFIED / BROKEN]

E4 re-run:
    E4-R1: [PASS / FAIL]
    E4-R2: [PASS / FAIL]
    E4-R3: [PASS / FAIL]
    E4-R4: [PASS / FAIL]
    E4-R5: [PASS / FAIL]
    E4-R6: [PASS / FAIL]
    E4-R7: [PASS / FAIL]

Affected tests re-run:
    F1: [PASS / FAIL]
    F3: [PASS / FAIL]
    F5: [PASS / FAIL]
    F6: [PASS / FAIL]
    F10: [PASS / FAIL]

Gap register:
    Closed: X
    Partial: Y
    Open: Z
    Blocked: W
    Normative: V
    Empirical: U

Internal closure (IC281):
    [ACHIEVED / PARTIAL / NOT ACHIEVED]

Formal closure:
    CONFIRMED

Computational closure:
    CONFIRMED

Empirical closure:
    [ACHIEVED / PARTIAL / NOT ACHIEVED]

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

## Part 4: Non-Negotiable Rules

### Rule 1 — Minimum Extension
Choose the smallest repair that distinguishes all mandatory states. Do not add unnecessary structure.

### Rule 2 — Invariant Preservation
Every existing invariant must be preserved. If an invariant must change, document the change and its justification.

### Rule 3 — Empirical Verification
The repair must be tested empirically. Do not assume it works.

### Rule 4 — No Silent Repair
Document the repair, the reasoning, the tests, and the results. The repair must be reviewed before becoming canonical.

### Rule 5 — No Premature Closure
Do not claim empirical closure until E4 and affected tests pass.

### Rule 6 — The Two Repair Principles
- **Distinguishability:** Every mandatory epistemic state must be distinguishable.
- **Minimality:** The state representation must be the minimum necessary to achieve distinguishability.

### Rule 7 — No Global Claims
Do not claim global theory completeness. That belongs to Step 282.

---

## Part 5: Stop Condition

Stop when:

1. Candidate repair selected and documented
2. Distinguishability proof complete
3. Minimality proof complete
4. Invariant preservation proof complete
5. E4-R1 to E4-R7 executed and documented
6. Affected F-tests (F1, F3, F5, F6, F10) executed and documented
7. Gap register updated
8. IC281 determined
9. Final report produced
10. All failures explicitly documented

**Do not proceed to Step 282 until Step 281 is complete.**

---

**HPA Supervisory Ruling**
**Date: 2026-08-30**
**Status: COMMISSIONED FOR EXECUTION**
**Next: STEP 281 EXECUTION**

---

*END OF PROMPT INSTRUCTIONS*