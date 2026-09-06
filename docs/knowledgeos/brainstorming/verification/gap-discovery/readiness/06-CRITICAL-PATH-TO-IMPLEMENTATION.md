# 06 — Critical Path to Implementation

Answers mandate **§14**. **Derived from the dependency graph** (`04`, `exec/minimum_implementable.py`),
not copied from a proposed sequence. Where it coincides with GN-84's recorded decision tree, that is
noted — coincidence is corroboration only where the derivations are independent, and here they are
not fully (GN-84 was read).

---

## 1. The path, derived

Topological order over the kernel's requirement set, blockers marked:

```
CURRENT STATE
  3 of 18 required constructs unblocked (Provenance, RelationType, Assertion)
  Operations and Transformations SEVERED
  1 of 25 constructs carries a governance act
  0 shared symbols between the ratified and verification lanes
        │
        ▼
BLOCKER 1 ── ARCHITECTURE  ── which `K` is canonical?
        │    ratified `K_t` (8 primitives) vs `K=(𝒜,ℛ)`; 0 shared vocabulary (C1)
        │    REQUIRED: GOVERNANCE          [not derivation — there is no contradiction to resolve]
        ▼
BLOCKER 2 ── DERIVATION    ── enumerate `ℐ` (= `R_mandatory`)     G-67
        │    blocks BOTH the operation-necessity test AND Sufficient(K,𝒪,ℐ)
        │    REQUIRED: DERIVATION
        ▼
BLOCKER 3 ── DERIVATION    ── state identity + equality rule       C-5 #3, TG-06
        │    "postconditions are undecidable without it"
        │    REQUIRED: DERIVATION  (resolve TG-06: `id` hashes mutable `e.state`)
        ▼
BLOCKER 4 ── OPERATION     ── run the necessity test, then ratify a registry
        │    criterion exists (277); never run (GN-75); 6 rival minimal registries (GN-84)
        │    REQUIRED: DERIVATION (test) → GOVERNANCE (ratify)
        ▼
BLOCKER 5 ── TRANSFORMATION ── typed rejection/failure semantics    C-5 #2
        │    AND resolve `Reject` vs I-12 + Art. 8                  AF-F-31 CONTRADICTORY
        │    REQUIRED: GOVERNANCE (the contradiction is inside the ratified surface)
        ▼
BLOCKER 6 ── TRANSFORMATION ── define `δ`: signature, pre, post, partiality, composition
        │    canon has 0 signatures, 0 postconditions (C2, C3); TG-09 no commit body
        │    REQUIRED: DERIVATION
        ▼
IMPLEMENTATION-READY SPECIFICATION
```

**Six blockers. Three require governance, three require derivation. None requires new theory
discovery** — every one is a decision about, or a formalization of, material already present.

---

## 2. Why Blocker 1 is first, and is not derivation

The two lanes are **disjoint, not contradictory** (C1: 0 shared symbols). There is nothing to
adjudicate mathematically — no shared term disagrees. **A derivation cannot choose between two
self-consistent vocabularies; only an authority can.**

And it must be first: every downstream artifact must be written *about* one `K`. Deriving `ℐ` before
knowing which `K` its invariants range over would produce a register that has to be redone.

---

## 3. Why `ℐ` (Blocker 2) is the keystone

Two independent obligations require the same object:

| Obligation | Needs |
|---|---|
| operation-necessity test `o primitive ⟺ ∃r ∈ R_mandatory : r ∉ Closure(𝒯_{-o})` | `R_mandatory` |
| `Sufficient(K,𝒪,ℐ)` expressibility conjunct | `ℐ` |

They are the same set. **Neither lane records this.** Until `ℐ` exists:

- the registry cannot be tested for minimality (only for *computational* minimality — GN-84's
  distinction),
- state sufficiency cannot be evaluated,
- and — per `07` §3 — **the minimum implementable subset is not even determinate.**

---

## 4. The four separations the mandate requires

### Must resolve **before** implementation

| | Blocker | Class |
|---|---|---|
| 1 | which `K` is canonical | **G** |
| 2 | enumerate `ℐ` | **D** |
| 3 | state identity + equality (TG-06) | **D** |
| 4 | operation registry: test, then ratify | **D → G** |
| 5 | typed rejection semantics + `Reject` vs I-12/Art. 8 | **G** |
| 6 | `δ` body: signature, pre/post, partiality, composition | **D** |

### Can be implemented **later**

`History` platform concept · `Replay` engine · `Authorize` runtime · measurement executor ·
`Γ` evaluator · EKP field additions (evidence, `t`, `Π`) · `ℛ` acyclicity enforcement.
**All `I`. None blocks the kernel specification** — they block a *complete product*.

### Can remain **governance-open**

**GC-1 / TG-21** — the two rival policy-loop closures. *Rationale:* `Policy` is already ratified and
its **content** is not on the kernel's critical path; the collision is about which *termination* of
the change-loop stands. It must be visible; it need not be resolved to specify a kernel.
**GC-2** ratification authority — likewise.

### Can remain **empirically unobservable**

`Q_t` · `Missingness` · and the 16 constructs with no L5 witness — **because the EKP does not
implement them.** Per the Step-280 verdict, *"no theory revision can close"* this. It blocks
**empirical certification**, not implementation. **A kernel can be built and tested at Level 4.**

### Book-only work

Part V.5/V.6 **status and dependency** prose · Part VI open questions · research-history framing of
Steps 272A–284. **Blocked:** Parts III and IV pending the GN-74 structural ruling.

---

## 5. What this path is *not*

It is **not** the shortest path to a *working system* — that would start with the `I` items, which are
cheap. It is the shortest **legitimate** path to a specification **two engineers could implement
identically**, which is the mandate's question.

**Coincidence with GN-84's recorded tree** — *derivation → falsification → reconcile → HPA decision
on prerequisite canonical questions → derive/ratify registry → transformation semantics →
implementation specification → implementation/tests* — is close but **not identical**: GN-84 does not
contain Blocker 1 (which `K`) or Blocker 2 (`ℐ`). Those are this session's additions, and both are
upstream of everything GN-84 lists.
