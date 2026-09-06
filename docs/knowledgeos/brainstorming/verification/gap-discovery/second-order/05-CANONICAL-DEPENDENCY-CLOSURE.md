# 05 — Canonical Dependency Closure

**Mandate Part E.** Executed: `exec/so_exp06_closure.py`.

The graph is the first-order pass's (`03-FOUNDATIONAL-ONTOLOGY.md`), **re-marked** with the
second-order findings. Several marks reverse first-order ones; each reversal is cited.

---

## 1. Node marks

**29 nodes.** Mandate's seven states.

### CLOSED / DERIVED — 10 (34.5 %)

| Node | Ground |
|---|---|
| `Observation` | §253.6 irreducible candidate; instantiated in the EKP |
| `Time` | §025w bitemporal model exists |
| `Source` | EKP `owner` / `authority` / `code_refs` |
| `Entity`, `Dimension` | §263 |
| `Proposition` | §262 verdict: typed triple `(E,D,V)` |
| `Provenance` | §265 verdict: `π ∈ K` as a **reference** |
| `RelationType` | §263 vocabulary; 6 types live in the EKP |
| `History` | §247 `𝒦=(K,H)`; §265 audit subsystem; `exp_provenance` Test 6 |
| `Lineage` | reachability in `ℛ_der`, `O(n+m)`, implemented |

### CLOSED / PARAMETRIC — 5 (17.2 %)

| Node | Ground |
|---|---|
| `Value` | §264: `V_D` per dimension is deployment-declared |
| **`K`** | **SO-EXP-01/03: congruent for every class-1 operation, both dependency variants, 208-state domain. §271.35 marks `K` 🟢.** Parametric because minimality is relative to `𝒯` (§254 `Minimality(K\|𝒯)`) |
| `Operation` | §256.2 + §259.7: 15 named, 5 classes; the set is open **by design** (§256.32) |
| `T_algebra` | §256.27 / §257.35 typed registry; partiality explicit (§256.16–17) |
| `Equivalence` | congruence computed for the class-1 set; relative to `𝒯` per §259.16 |

**`CLOSED/PARAMETRIC` is not a euphemism for open.** It means the node is fully determined *once a
deployment declares its parameter*, and the corpus deliberately declines to declare it.

### CLOSED / NORMATIVE — 2 (6.9 %)

| Node | Ground |
|---|---|
| `Authority` | §187.28–29 stipulation, **implemented**: 132/132 `humanActRef`, fail-closed resolver. Normative in origin, empirically settled in practice (`02`) |
| `Governance` | two-level model implemented; the EKP constitution self-amends via ADR + ARB, `frozen` machine-enforced |

### OPEN / DERIVABLE — 10 (34.5 %)

`Context` · `Assertion` · `Relation` · `Policy` · `Rule` · `Evidence` · `Assessment` · **`Σ`** ·
`Measurement` · `K*`

Each has a stated method and no missing decision. `Σ` carries a commissioned step (272) that does
not exist.

### OPEN / EMPIRICAL — 2 (6.9 %)

| Node | Ground |
|---|---|
| `AuthorityAct` | G-57: 79 free-text strings, **0** typed objects — recorded but not machine-checkable |
| `Determination` | DS-1: specified at §157.22 / §165.8, lost in the band transition, **zero instances** |

### OPEN / NORMATIVE — **0 (0.0 %)**

### CONTRADICTORY — **0 (0.0 %)**

---

## 2. The graph is now acyclic — and it matters *why*

```
strongly-connected components with >1 node: 0
```

The first-order 7-node cycle

```
Assertion → Evidence → Rule → Policy → Authority → Assertion
```

is broken because **`Authority` now has no outgoing edge**. The first-order pass drew
`Authority → Assertion` from §187.23's "recorded basis `B`". The implementation settles it: the basis
is a **reference to an act outside the system** — 132/132 `humanActRef`, **0** typed acts inside, and
a resolver that explicitly *"states the requirement; it cannot perform it."*

**Two cautions, both mandatory:**

1. **Acyclicity is *purchased* by that termination.** It is a consequence of the two-level model, not
   an independent result. If a deployment chose to internalize authority (D-2 option B), the cycle
   returns.
2. **The first-order mandate's §22 stands: acyclicity ≠ semantic completeness.** The graph is a DAG
   and nine of its nodes still lack coherent meanings. These are different properties and this
   document does not let one stand for the other.

---

## 3. The five closures, computed separately

| # | Closure | Value | Reading |
|---|---|---|---|
| **1** | **Semantic** | **20/29 = 69.0 %** | 9 nodes lack a coherent, non-overloaded meaning: `Context`, `Assertion`, `Relation`, `Policy`, `Rule`, `Evidence`, `Assessment`, `Σ`, `Determination` |
| **2** | **Computational** | **20/29 = 69.0 %** | 9 nodes not typed-and-computable: the same set with `Measurement` and `K*` replacing `Assertion` and `Relation` |
| **3** | **Evidential** | **16/29 = 55.2 %** | 16 nodes supported by execution or implementation observation |
| **4** | **Governance** | **2/3 = 66.7 %** at regime level | `Authority` and `Governance` closed; `Policy` internals 🔴. **Open at the level of the individual act** (G-57) |
| **5** | **Implementation correspondence** | **15/29 = 51.7 %** | 15 nodes have a real instance in a running system |

**These are five different numbers and must not be averaged.** Governance is the most closed;
implementation correspondence the least. A single "complete/incomplete" label would hide the actual
shape of the result:

> **The theory is empirically strong and semantically weak in exactly one region — the
> `Evidence → Assessment → Σ` chain and its `Policy`/`Rule` inputs.**

---

## 4. The frontier, drawn

```
      CLOSED / DERIVED                     CLOSED / PARAMETRIC
  Observation  Time  Source              Value   K   Operation
  Entity  Dimension  Proposition                T_algebra
  Provenance  RelationType                     Equivalence
  History  Lineage
                                          CLOSED / NORMATIVE
                                          Authority   Governance
  ─────────────────────────────────────────────────────────────────
                       T H E   F R O N T I E R
  ─────────────────────────────────────────────────────────────────
      OPEN / DERIVABLE                     OPEN / EMPIRICAL
  Context   Assertion   Relation            AuthorityAct
  Policy → Rule → Evidence                  Determination
        → Assessment → Σ  ◀── the commissioned Step 272
  Measurement       K*
```

Every open node is **derivable or empirical**. **None is normative.**

---

## 5. What changed from the first-order closure statement

| First-order (`16` §F) | Second-order |
|---|---|
| Semantic closure: **NOT achieved** | **69 %**, with the deficit localised to one chain |
| Computational closure: **NOT achieved** — "`𝒪` unenumerated ⟹ everything inherits" | **69 %.** `𝒪` **is** enumerated (§256.2 + §259.7); `K`, `T`, `≡` are `CLOSED/PARAMETRIC` by execution |
| Governance closure: **PARTIAL** — "the implementation contradicts the stipulation" | **Closed at regime level.** The contradiction claim is **withdrawn**; 132/132 coverage |
| Graph: **cyclic** | **Acyclic**, with the reason and its price stated |
| "the theory is missing a premise" | The premise exists (`𝒪`), is enumerated and classified, and **the answer is invariant across its open remainder** |

---

**Next:** `06-NORMATIVE-DECISION-REGISTER.md`.
