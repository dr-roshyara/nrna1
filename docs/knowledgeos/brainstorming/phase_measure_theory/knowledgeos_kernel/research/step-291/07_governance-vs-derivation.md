# 07 — Governance vs Derivation Boundary (mandate §§8, 11)

> **§11: *"Do not call an engineering TODO a governance decision."*** And **§8**: test every plausible
> derivation route for `𝒪`/`𝒯`.

## §8 Derivation routes for `𝒪`/`𝒯`

| Route | Classification | Why |
|---|---|---|
| from `K` (what the state must preserve) | 🔴 **CIRCULAR** | `K ← minimality ← congruence ← 𝒯` |
| from invariants `ℐ` | 🔴 **CIRCULAR** | `ℐ` is downstream of `≡` (`287`) |
| from a minimality proof | 🔴 **BLOCKED** | `259`: *"no valid minimality proof before transformation congruence analysis"* |
| from the ratified **8 primitives** | 🔴 **UNDER-SPECIFIED** | `C-022`/`049` ratifies **primitives**, not operations; the 8 have never been shown minimal against an operation space |
| from the **forced lower bound** | 🟡 **PARTIALLY DERIVABLE** | `256.2`/`259.7`: 14 forced, ≤18 — a **candidate set**, not a membership rule |
| from **`259.7`'s five-kind schema** | 🟡 **PARTIALLY DERIVABLE** | ⭐ **the schema IS derivable and `277` calls classification CLOSED** — it bounds *kinds*, not *members* |
| from **`Admissible(o,K)`** | 🔴 **BLOCKED → `G1`** | the predicate is **named, not evaluable** |
| **declare mandatory membership** | ✅ **NORMATIVE** | the only route with no unmet precondition |

$$\boxed{\begin{array}{c}\textbf{The SCHEMA is derivable (and largely derived). MANDATORY MEMBERSHIP is not.}\\ \textbf{No route derives membership from already-ratified material.}\end{array}}$$

⚠️ **`225.11` discipline: this is an ABSENCE OF EVIDENCE for a derivation route, not a proof that none
exists.** A later step may find one; this finding would be **SUPERSEDED**, not contradicted.

## §11 Case A–E — the boundary is **MIXED**, and the split is the deliverable

$$\boxed{\textbf{CASE E — MIXED}}$$

### ✅ THIS PORTION CAN BE DERIVED
| | |
|---|---|
| the **five-kind operation schema** | `259.7`; `277` classification CLOSED |
| the **typed signatures** of 8 operations | `259.7`, `012 §46`, `25J.43` |
| `𝒯 ⊆ 𝒪` (kind 1) and `𝒪_K` ⊆ kind 5 | `259.7`/`259.8` |
| that **`≡_K` is DEFINABLE** without closing `𝒪`/`𝒯` | `04 §3` — five of six properties blocked, definability not |
| the **`Assess` determination condition** | `259.8` |
| the **congruence criterion** `258-A` | `258.30` — derivable, and **must not be given to governance** |

### 🔧 THIS PORTION IS AN ENGINEERING / DOCUMENTARY TODO — *not* a governance decision
| | |
|---|---|
| the **`𝒪` glyph overload** (operations vs observations) | `01` — **DOCUMENTARY**; repairable with no decision |
| **`261.21`'s self-reference** (defines `𝒪_K` as closed, then denies it) | `04` — **DOCUMENTARY** |
| **registering `TraceOrigin` and `ExplainRevision`** | `02` — they are load-bearing and unregistered |
| a **type for observations** (kind 5 has no schema) | **TYPE-THEORETIC**; kinds 2 and 3 already have signatures |

⚠️ **Four items that Steps 288–290 folded into *"`𝒪` is not closed"* are not decisions at all.**
**Calling them governance questions would have manufactured four decisions.**

### ⚖️ THIS PORTION REQUIRES A DECISION
| | |
|---|---|
| **which operations are MANDATORY** — the membership rule | `N-4`, and it is the whole of the real blocker |
| **`𝒪_K`'s extension** | `N-3` |
| the residue of Decision 3 (which of `258.31`'s 3 repairs) | `N-2` |
| ratify or reject the `261.21` candidate | `N-1′` |

## STATUS
**DERIVED** the schema, the signatures, the subset relations, `≡_K`'s definability, `258-A` ·
**IMPLEMENTATION** 4 documentary/type-theoretic TODOs, no decision needed · **NORMATIVE** 4 items, of
which **mandatory membership is the only structural one** · **G1** `Admissible` · **UNKNOWN** whether a
membership derivation exists
