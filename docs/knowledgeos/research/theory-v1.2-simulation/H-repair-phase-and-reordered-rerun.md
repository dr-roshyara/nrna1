# H — Repair Phase and Re-ordered Re-run · `KR-SIM-2026-09-02-F`

**Sequencing correction accepted.** The prescribed order is

```
repair the specification (PB-2, PB-3, PB-5)  →  supply candidate evaluators  →  re-run B and C
```

The evaluator run (`KR-SIM-2026-09-02-E`) was executed **before** the repairs. This artifact does the
repair phase, derives the Zero chain, and then re-runs the evaluator experiment **on the repaired
specification** to test whether the ordering violation changed any conclusion.

> **No repair is chosen.** Each defect is given candidate repairs, each candidate is tested, and the
> survivors are reported. Choosing remains out of scope.

---

# 1. Repair phase — candidates enumerated and tested, none chosen

## PB-2 — contradiction semantics

Four candidates, tested over `{p only, ¬p only, neither, BOTH}`:

| | candidate | total? | stays 3-valued? | preserves *absence ≠ negation*? | `BOTH` ↦ | cost |
|---|---|---|---|---|---|---|
| **R_a** | four-valued codomain `{⊤,⊥,U,C}` | ✔ | ✘ | ✔ | `C` | **changes the codomain of the whole family** |
| **R_b** | delegate to `Sat_consistency`; content returns `U` on contradiction | ✔ | ✔ | ✔ | `U` | **makes `content` depend on `consistency`, which is itself blocked** |
| **R_c** | `⊥ ≡ (¬p ∈ Content ∧ p ∉ Content)` — exclusive by construction | ✔ | ✔ | ✔ | **`⊤`** | **silently loses the contradiction** |
| **R_d** | invariant: `Content(K)` is contradiction-free | ✘ | ✔ | ✔ | error | pushes the problem into `K`'s construction; `Sat` becomes partial on real states |

> **`R_b` is the only candidate satisfying all three criteria** — total, three-valued, and preserving
> *absence ≠ negation*.
>
> **`R_c` is the trap.** It is total and three-valued and it *looks* clean, but it maps a
> contradictory state to `⊤`: the system would report a requirement **satisfied** by a state that
> holds both `p` and `¬p`. Any repair that restores totality by making `⊤` and `⊥` exclusive risks
> exactly this.
>
> **And `R_b`'s cost is the finding:** the only clean repair **creates a dependency on a blocked
> class.** PB-2 and the `consistency` blocker are not independent problems.

## PB-3 — reason vocabulary

| | candidate | uncovered cells | **exhaustiveness provable?** |
|---|---|---|---|
| baseline | as declared | `(content, UNDERDETERMINED)` | ✘ |
| **R_a** | add `UNDERDETERMINED` to `content` — the one-word repair | none | **✘ — closes the two cells, proves nothing about the rest** |
| **R_b** | one global vocabulary; any class may return any reason | none | **✘ — covers everything and therefore tests nothing**; it destroys the per-class discipline that found the defect |
| **R_c** | **derive** the vocabulary from the `Sat` skeleton: `U` has exactly three structural sources — `NO_EVALUATOR_FOR_THE_PREDICATE`, `PREDICATE_UNDECIDED_ON_THIS_STATE`, `INPUT_ABSENT` | n/a — reasons are *re-typed*, not enumerated | **✔ — the only candidate that could be exhaustive** |

> The concrete reasons (`UNOBSERVED`, `AUTHORITY_SILENT`, …) become **refinements of the three
> structural sources** rather than a list to be extended whenever a case is missed. Enumeration can
> never be proved complete; derivation from the skeleton can.
>
> **Reason-vocabulary exhaustiveness remains `[OPEN]`.**

## PB-5 — operational well-foundedness

| candidate | terminates? | keeps expressiveness? | **makes the defect visible in the value?** |
|---|---|---|---|
| restrict `κ` to non-operational classes | ✔ | ✘ — refuses composite operations outright | ✘ |
| allow operational `κ` with a well-founded measure | ✔ | ✔ | ✘ — and the theory has not defined the measure |
| **stratify `Sat_op` into levels** | ✔ | ✔ | **✔** |
| leave `κ` unrestricted (status quo) | **✘** | ✔ | ✘ |

> **`stratify` strictly dominates.** It is the only candidate that terminates, keeps expressiveness,
> **and repairs PB-5′'s invisibility finding** — the level index is observable, so *level exhaustion*
> becomes distinguishable from *refusal*, which the value `U(KAPPA_OPERATIONAL)` alone could not do.

---

# 2. The Zero chain is now **derived**, not merely observed

```
strict(g)   ⟺ ∀r. Sat(r) = ⊤
weak(g)     ⟺ ¬∃r. Sat(r) = ⊥
reasoned(g) ⟺ weak(g) ∧ ¬∃r. ( Sat(r) = U ∧ bucket(r) ∉ Closing )
```

* **`reasoned ⇒ weak`** — immediate: `weak` is the first conjunct of `reasoned`. `[DEFINITIONAL]`
* **`strict ⇒ reasoned`** — `∀r.Sat(r)=⊤` gives (i) no `r` with `⊥`, so `weak` holds; (ii) no `r`
  with `U`, so the second conjunct is vacuous. `[DERIVED]`

Machine check: **1 620 000 assignments** — every combination of `{⊤,⊥,U}` over four requirement
slots, every bucket labelling, and **every choice of which buckets close** — **0 counterexamples** to
either implication.

> The chain **does not depend on the reason partition.** It survives the partition being revised —
> which matters, because §3 of `G` had to revise it once already. Status upgraded from
> *experimentally supported* to **`[DERIVED]`**.

---

# 3. Did executing out of order change any conclusion?

The evaluator experiment re-run on the repaired specification, applying `R_b` (so `content` inherits
`consistency`'s blockage).

## 3.1 Contagion — **yes, I understated it** `[NEG]`

| arity | as run (before repair) | after repair | Δ |
|---|---|---|---|
| 1 | 0.250 | 0.375 | **+0.125** |
| 2 | 0.464 | **0.643** | **+0.179** |
| 3 | 0.643 | **0.821** | **+0.179** |
| 4 | 0.786 | **0.929** | +0.143 |
| 5 | 0.893 | 0.982 | +0.089 |
| **6** | 0.964 | **1.000** | +0.036 |
| 7–8 | 1.000 | 1.000 | 0.000 |

> **The `-E` run understated the contagion.** Under the correct PB-2 repair, saturation begins at
> **arity 6, not arity 7**, and mid-arity contagion is up to **+0.179** higher. `G`'s figures have
> been corrected at source.

## 3.2 Zero readings — **no, the conclusions are unaffected** `[EXP]`

| variant | `Zero_strict` | `Zero_reasoned` | `Zero_weak` |
|---|---|---|---|
| as run (before repair) | 0 / 80 | 8 / 80 | 36 / 80 |
| **on the repaired spec** | **0 / 80** | **8 / 80** | **36 / 80** |

Identical. `Zero_strict` remains unreachable, `Zero_reasoned` remains strictly between, and the 4.5×
separation from `Zero_weak` stands.

## 3.3 Verdict on the ordering violation

> **It mattered for one result and not the other.** The Phase C′ Zero conclusions are **robust** to
> the ordering; the PB-4′ contagion figures were **wrong in the optimistic direction** and are now
> corrected. The correction **strengthens** the original conclusion — contagion is worse, and the
> case for defining `⪰` and `Contr` is stronger, not weaker.
>
> `[INF]` The general lesson: running a measurement before repairing the specification it measures
> biases the measurement toward the *unrepaired* specification's optimism. That is a reason to keep
> the prescribed order, independent of whether any particular conclusion happens to survive.

---

# 4. Status after the repair phase

| Element | Status |
|---|---|
| `Sat : 𝒦 × ℛ → {⊤,⊥,U}` | **`[PROP]`** — a research-level candidate interface, not a canonical semantic contract |
| PB-2 contradiction semantics | **`[OPEN]`** — `R_b` is the only candidate meeting all three criteria, and it creates a dependency on a blocked class |
| PB-3 reason vocabulary | **`[OPEN]`** — only `R_c` (derive from the skeleton) could be exhaustive; enumeration cannot be |
| PB-5 well-foundedness | **`[OPEN]`, but with a dominant candidate** — `stratify` alone terminates, keeps expressiveness, and makes the defect visible |
| Zero chain `strict ⇒ reasoned ⇒ weak` | **`[DERIVED]`** — upgraded; independent of the partition |
| `Zero_reasoned` | **`[PROP]`** — distinct, measured at 8/80 vs `weak` 36/80 |
| Which `Zero` is correct | **`[NORMATIVE]`** — the experiment improved the decision space; it cannot make the decision |
| Factivity | **`[OPEN]`** — the family has no truth-bearing relation; this does **not** establish that KnowledgeOS should be non-factive |
| `Zero ⇏ Truth` | **`[EXP]` ESTABLISHED** — counterexample B1 |
| Implementing `Sat_c` | **do not** — three defects remain OPEN, and implementing would convert them into apparently settled code |

# 5. Next

**Define `⪰` and `Contr`.** They are the entire remaining blockage, and §1 now shows they are
**coupled to PB-2**: the only clean contradiction repair (`R_b`) routes `content` through
`consistency`, so defining `Contr` is a prerequisite for repairing `Sat_content`, and `Contr` carries
the four-valued codomain question. Three OPEN items collapse into one.

As before: **do not invent them the way the three evaluators were invented.** Those were supplied to
*measure* a blockage; these would be supplied to *close* the theory.
