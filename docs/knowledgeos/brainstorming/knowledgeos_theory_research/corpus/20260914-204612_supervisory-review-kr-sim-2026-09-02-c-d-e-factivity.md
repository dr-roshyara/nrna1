# SUPERVISORY REVIEW: KR-SIM-2026-09-02-C/D/E — FACTIVITY REPAIR & SAT_C CLOSURE EXPERIMENTS

**Reviewer:** Senior Mathematician · Senior Statistician · Senior DDD Architect
**Date:** 2026-09-02
**Status:** COMPREHENSIVE REVIEW COMPLETED
**Authority:** HPA

---

## Executive Summary

These experiments represent the **most important methodological achievements** of the entire KnowledgeOS simulation programme. They:

1. **Discovered** that the eight `Sat_c` classes have **no place to attach truth** — factivity is not a requirement of any class
2. **Demonstrated** that **`Zero` and truth are orthogonal** — a system can be epistemically closed and simply wrong
3. **Proved** that R3 (partial Γ) **as specified fails** to repair factivity
4. **Discovered** that **R1 and R2 are behaviourally identical** — the repair is "stop calling it knowledge"
5. **Generated** a **fourth `Zero` reading** (`Zero_reasoned`) with a derived chain
6. **Demonstrated** that **5 of 8 classes have no evaluator** and every composite arity ≥ 4 is permanently `U`

---

## Part 1: The Factivity Repair Experiment (E)

### 1.1 What Was Tested

The experiment tested three repairs to the factivity problem:

| Arm | Description | Factivity Violation | Coverage |
|:---|:---|:---|:---|
| Baseline | v1.1/v1.2 as written | 0.1175 | 0.3320 |
| **R1** | Rename `K_t` | **n/a — 0 knowledge claims** | 0.3320 |
| **R2** | Externalize factivity | **n/a — 0 knowledge claims** | 0.3320 |
| **R3** | Partial Γ (as specified) | **0.1181 — no better** | 0.1143 |
| **R3′** | Partial Γ with channel consulted | **0.0000** | 0.3497 |

### 1.2 The Key Findings

**Finding 1: R3 as specified does not repair factivity**

`0.1181` against a baseline of `0.1175` — **indistinguishable, and marginally worse.** It buys nothing and costs **66% of coverage.**

**Root cause:** R3 attributes only where a verification channel *exists*. But existence of a channel is not consultation of it. The specification "make Γ partial" is under-determined.

**Finding 2: R1 and R2 are behaviourally identical**

| | R1 Rename | R2 Externalize |
|:---|:---|:---|
| What the kernel emits | `AttributedState A_t` | `ClaimToKnowledge` |
| Who may assert `Knows` | nobody | external verifier |
| New component required | none | the verifier |

**The repair is: "stop calling it knowledge."** Under R1 or R2, there is no longer a contradiction between factivity and Γ-determinacy, because Γ no longer claims factivity.

**Finding 3: R3′ repairs factivity by ceasing to be an epistemic system**

R3′ consults the channel and uses its result. Violations: **0**. Coverage: **1.053× baseline**.

But **71.2% of R3′'s attributions are not products of the epistemic pipeline.** R3′ bypasses the epistemic system rather than repairing it.

### 1.3 Status

| Repair | Fixes Factivity? | Cost | Assessment |
|:---|:---|:---|:---|
| R1 Rename | ✅ yes, by construction | vocabulary only | **Genuine repair** |
| R2 Externalize | ✅ yes | new component | **Genuine repair, most informative** |
| R3 As specified | ❌ no | −66% coverage | **REFUTED** |
| R3′ Corrected | ✅ yes | 71.2% attributions non-epistemic | **Not a repair — bypasses the system** |

---

## Part 2: The `Sat_c` Semantic Closure Experiment (F)

### 2.1 Phase A — Formal Specification

**Finding A-1: Only 3 of 8 classes are executable now**

| Executable | Blocked | The Blocker |
|:---|:---|:---|
| `content` | `status` | **`⪰` is not defined by the theory** |
| `evidence` | `consistency` | **`Contr` undefined** |
| `provenance` | `governance` | **no evaluator exists** |
| | `temporal` | **no temporal semantics defined** |
| | `operational` | **`δ` is Step 290 and open** |

**Finding A-2: No class requires factivity**

```
factivity_requirement: content NONE · evidence NONE · provenance NONE · status NONE
                        consistency NONE · governance NONE · temporal NONE · operational NONE
```

> **A knowledge state can satisfy all eight classes and still be false.** CE-1 is not an accident of Γ: **the satisfaction family has no place to attach truth.**

**Status:** `Sat` remains `[PROP]` — not because of factivity, but because the family has no evaluator for 5 of 8 classes.

### 2.2 Phase B — Adversarial Semantic Testing

**PB-2: `Sat_content` is incoherent under contradiction**

`Sat_content` returns `⊤` if `p ∈ Content(K)` and `⊥` if `¬p ∈ Content(K)`. Both can hold. The codomain `{⊤,⊥,U}` has no value for it.

**Either the codomain needs a fourth value `C`, or `Sat_content` must delegate to `Sat_consistency` first.**

**PB-3: The `Just` hypothesis fails — 2 of 32 cells fail**

Both failures are the same defect: content can be undetermined **because rivals are live**, which the specification did not anticipate.

**PB-4: Blocked-class contagion — the decisive result**

Under Kleene conjunction, with 5 of 8 classes blocked:

| Composite Arity | Permanently `U` | Rate |
|:---|:---|:---|
| 1 | 5/8 | 0.625 |
| 2 | 25/28 | 0.893 |
| 3 | 55/56 | **0.982** |
| **4–8** | **all** | **1.000** |

> **Every composite requirement of arity ≥ 4 is permanently `U`.** The satisfaction family is **effectively inert** until the five blocked classes are unblocked.

**PB-5: The family is not provably well-founded**

`Sat_op(K,r) = Sat_κ(δ(K,o))` is recursive. Well-founded **iff** `κ` is drawn from the seven non-operational classes. The theory does not restrict `κ`.

### 2.3 Phase C — Zero Closure

**Finding C-1: A fourth `Zero` reading**

```
Zero_reasoned ⟺ no ⊥ ∧ no U that the AGENT could still act on
```

*"The agent has done everything it can; what remains belongs to the theory or the world."*

**It is genuinely distinct** — demonstrated by a separating case.

**Finding C-2: The four readings form a chain — DERIVED**

```
Zero_strict ⇒ Zero_reasoned ⇒ Zero_weak
```

This is now **derived** from the definitions and verified over **1,620,000 assignments** with 0 counterexamples.

**Finding C-3: Zero closure holds on a state whose knowledge is false**

Case B1 is the factivity counterexample: the system attributed a falsehood. It is nevertheless:

```
Zero_weak = true      Zero_reasoned = true
```

Because **no `Sat_c` requires factivity**.

> **`Zero` and truth are orthogonal.** A system can be epistemically closed — every applicable requirement satisfied, nothing left for the agent to do — and simply wrong.

### 2.4 Status

| Element | Status |
|:---|:---|
| `Sat` as interface | `[PROP]` — unchanged, now for specified reasons |
| Eight-class decomposition | `[PROP]`, useful — localized every blocker |
| `Just(U)` reason vocabulary | `[PROP]`, incomplete — 2/32 cells failed |
| Kleene conjunction | `[PROP]` — PB-4 shows its cost |
| Four-valued codomain | `[OPEN]` — raised from content class |
| Well-foundedness | `[OPEN]` |
| `Zero_reasoned` | `[PROP]` — new, distinct, chain-ordered |
| Factivity | `[OPEN]` — family has no hook for it |

---

## Part 3: What This Means for Step 281

### 3.1 The Key Findings

1. **The repair to factivity is not theoretical — it's naming.** R1 and R2 are behaviourally identical. The repair is: "stop calling it knowledge."

2. **`Zero` and truth are orthogonal.** A system can be epistemically closed and simply wrong. This is the sharpest statement of the CE-1 obstruction.

3. **The satisfaction family is inert until 5 classes are unblocked.** Every composite of arity ≥ 4 is permanently `U`.

4. **A fourth `Zero` reading exists** — `Zero_reasoned` — and the four readings form a chain.

### 3.2 The Immediate Actions

1. **Decide between R1 and R2** — `[NORMATIVE]` decision. R2 buys an observable verification rate; R1 does not.

2. **Supply evaluators for governance, temporal, and operational classes** — These are the five blocked classes. Until they are unblocked, the satisfaction family is inert.

3. **Address the factivity hook** — The family has no place to attach truth. This must be resolved before `Sat` can be closed.

4. **Decide the `Zero` reading** — `[NORMATIVE]` decision. Four readings exist; which is canonical?

---

## Part 4: The Supervisory Verdict

### 4.1 Assessment

| Category | Rating | Justification |
|:---|:---|:---|
| **Methodology** | ✅ Excellent | Rigorous, reproducible, honest |
| **Findings** | ✅ Significant | Four `Zero` readings; factivity orthogonality |
| **Repair experiment** | ✅ Decisive | R3 refuted; R1/R2 identified |
| **Sat_c closure** | ✅ Comprehensive | 5 of 8 classes blocked; PB-4 decisive |
| **Honesty** | ✅ Excellent | Failures reported alongside successes |

### 4.2 Status

```
The v1.2 simulation experiments are ACCEPTED as valid experimental results.
```

### 4.3 The Final Statement

The experiments establish:

1. **Two of three factivity repairs work, and they are the same intervention: "stop calling it knowledge."**
2. **R3 as specified fails** and costs 66% coverage.
3. **No `Sat_c` class requires factivity** — the family has no place to attach truth.
4. **5 of 8 classes have no evaluator** — the satisfaction family is inert until they are unblocked.
5. **A fourth `Zero` reading exists** — `Zero_reasoned`.
6. **The four readings form a chain** — `Zero_strict ⇒ Zero_reasoned ⇒ Zero_weak`.
7. **`Zero` and truth are orthogonal** — a system can be epistemically closed and simply wrong.

---

## Part 5: HPA Ruling

### 5.1 Ruling

```
The experiments are ACCEPTED.
The findings are ADOPTED as experimental evidence.
```

### 5.2 Immediate Actions Required

1. **Decide between R1 and R2** — `[NORMATIVE]` decision
2. **Supply evaluators for governance, temporal, and operational classes**
3. **Address the factivity hook** — the family has no place to attach truth
4. **Decide the `Zero` reading** — `[NORMATIVE]` decision

### 5.3 The Final Statement

```
The experiments have produced exactly the kind of evidence the programme needs:
    - They identify the specific repairs that work (R1/R2)
    - They identify the specific classes blocking Sat closure (5 of 8)
    - They demonstrate that Zero and truth are orthogonal
    - They generate a fourth Zero reading with a derived chain

The theory is NOT YET CLOSED.
But the path to closure is now clearly visible.
```

---

**HPA Supervisory Ruling**
**Date: 2026-09-02**
**Status: ACCEPTED**
**Next: STEP 281.7 — SUPPLY EVALUATORS FOR GOVERNANCE, TEMPORAL, AND OPERATIONAL CLASSES**

---

*END OF REVIEW*