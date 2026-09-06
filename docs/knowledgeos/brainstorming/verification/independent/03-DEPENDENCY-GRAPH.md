---
artifact: 03-DEPENDENCY-GRAPH
date: 2026-08-30
status: **3 CYCLES FOUND BY EXECUTION — one is unterminated**
---

# 03 · Dependency Graph and Cycle Detection

Built from the reconstruction in `02`, then traversed by DFS with grey-node back-edge detection
(`attack.py` §D, executed).

## 1. Edges (24 nodes)

```
ValueSpace ← ()                       World ← ()
Entity     ← ()                       Observation ← World
Dimension  ← ValueSpace               Evidence   ← Observation, Policy      ⚠ via Qualify
Proposition← Entity, Dimension, ValueSpace
Assertion  ← Proposition, Evidence, Context, Time, Provenance
Relation   ← Assertion, Evidence, Σ, Time          (corpus 8-tuple)
K          ← Assertion, Relation
Σ          ← Assertion, Policy                    Γ ← Assertion, GovContext
Policy     ← K                                    ⚠ policy-as-content (v0.2 R-1)
Authority  ← Policy                               Authorization ← Authority, Policy
Transformation ← K, Policy, Authority             Invariant ← K
Assessment ← Proposition, Evidence, Context, Policy
History    ← Transformation                       Lineage ← Provenance, Relation
```

## 2. Executed result

```
nodes=24  cycles found=3
  CYCLE: Evidence -> Policy -> K -> Assertion -> Evidence
  CYCLE: Evidence -> Policy -> K -> Relation  -> Evidence
  CYCLE: Policy   -> K -> Relation -> Sigma   -> Policy
```

## 3. Classification (mandate §16)

| Cycle | Class | Terminated? |
|---|---|---|
| `Policy → K → Relation → Σ → Policy` | **SEMANTIC CYCLE** | ⚠️ **partially** — see §3.1 |
| `Policy → K → … → Policy` (governance) | **GOVERNANCE RECURSION** | ✅ **YES** — v0.2 `R-1` + `I-11` |
| `Evidence → Policy → K → Assertion → Evidence` | **SEMANTIC CYCLE via `Qualify`** | 🔴 **NO — unterminated** |

### 3.1 `Σ` in `ℛ` is ill-typed — a cycle created by a field, executed

`Σ` is **policy-relative**. Executed proof:

```
identical evidence (2 supporting), policy(min_support=1) -> Supported
identical evidence (2 supporting), policy(min_support=2) -> Supported
identical evidence (2 supporting), policy(min_support=3) -> Unknown
```

**The same assertion is `Supported` under one policy and `Unknown` under another.** Therefore
`Σ(a)` is ill-typed; only `Σ(a, policy)` is well-typed.

**But the corpus's relation `r = (E₁,E₂,T,R,Q,E,Σ,τ)` carries a *bare* `Σ` field with no policy
parameter.** So the corpus model stores a policy-relative value inside a structure that names no
policy — which is what makes `Policy → K → Relation → Σ → Policy` a cycle rather than a
stratification. **This is a defect in the corpus model, not only in the claimed theory**, and it is
recorded here as such.

*The claimed theory avoids this cycle — by deleting the `Σ` field, i.e. by dropping the capability
rather than typing it.*

### 3.2 The unterminated cycle is the important one

```
Evidence  ←  Qualify(Observation, Policy)
Policy    ∈  K_t                         (policy-as-content, v0.2 R-1)
K         ⊇  𝒜  ∋  Assertion
Assertion ∋  e : Evidence
```

**To qualify an observation into evidence you need a policy; the policy is knowledge content; that
content is an assertion; that assertion is justified by evidence.**

`v0.2`'s `R-1` stratification (`Policy-as-content` vs `Policy-in-force`) **terminates the governance
recursion — the loop about *changing* policy.** It says nothing about *using* a policy inside
qualification. **The two loops are different and only one is closed.**

> **This is the single genuine circularity in the theory, and no artifact in the corpus — research,
> ratified or verification — records it.** It is not resolved by the human-act externalisation
> either: a human act authorises a *policy*, it does not qualify an *observation*.

**Is it a legitimate fixed point?** Testable: it would be, if `Qualify` were monotone over a
complete lattice of evidence sets, giving a least fixed point by Knaster–Tarski. **`Qualify` has no
body, so monotonicity cannot be checked.** Until `Qualify` is defined, **the question is not
answerable, and the cycle must be recorded as a genuine circularity rather than a fixed point.**

## 4. Is the mandate's prescribed pipeline correct? — falsification attempt

```
Primitive → Definition → Invariant → Transformation → Policy → Assessment
          → Authorization → Execution → New Knowledge State
```

**Two edges are wrong, as measured:**

1. **`Invariant → Transformation` is backwards for `Policy`.** `Policy` depends on `K`
   (policy-as-content), and `K` depends on `Assertion`, so `Policy` cannot precede `Transformation`
   in a strict dependency order — the arrow `Policy → K` runs the other way. The pipeline is an
   **execution order**, not a **dependency order**, and the corpus repeatedly conflates them.
2. **`Assessment → Authorization` is not a dependency.** Executed evidence and the corpus's own law
   agree: *"Assessment → verdict → **NEVER grants authority**."* Assessment **precedes** authorization
   temporally and **does not feed** it semantically. Drawing it as a dependency edge is exactly the
   error the corpus's strongest governance rule forbids.

**The pipeline survives as a description of execution sequence. It is falsified as a dependency
graph.** The real dependency graph is §1, and it is not a DAG.
