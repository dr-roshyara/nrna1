# FINAL VERDICT — `KR-SIM-2026-09-02-B` (Theory v1.2)

**Model** `KnowledgeOS-Simulation-v1.2` · **Status** `[EXP]`
Same pipeline as the v1.1 run; **only the evaluation layer changed**, so every difference is
attributable to the theory rather than to the simulator.

---

# 1. WHAT IS NOW ESTABLISHED

1. **`Zero ⟺ Δ_t = ∅` is ambiguous under three-valued `Sat`.** It names three predicates —
   `strict`, `weak`, Kleene — and they **disagree on the most favourable case**: a determined,
   corroborated state is `strict=false`, `weak=true`, Kleene `=U`.
2. **`Zero` cannot be evaluated while `governance`, `temporal` and `operational` semantics are
   open.** In the best case those three classes supply every `U`. `Step 261`'s meta-gate reappears
   from an independent direction.
3. **`Zero` is not a state, and is coarser than the missingness taxonomy** — all four unknown kinds
   (`UNOBSERVED / UNINTERPRETED / UNDERDETERMINED / UNOBSERVABLE`) collapse to `U`.
4. **The five relations are non-substitutable in 13 of 20 directions**; the remaining 7 form a
   refinement order `= ⟹ ≅_λ ⟹ ≡ ⟹ ≈`, and `= ⟹ identity`. **The order is λ-relative** — it breaks
   when `λ` contains provenance or weight.
5. **Both v1.1 failures persist.** CE-1 factivity is untouched; CE-3 revision-without-retraction is
   untouched. v1.2 improves the *report* of CE-3 (1 violated + 6 undetermined vs "a gap") without
   repairing it.
6. **Corroboration-dependent results are hostage to an OPEN concept.** Dropping the source component
   of the candidate observation model flips determination, attribution and `Zero`.

# 2. WHAT IS STRONGLY SUPPORTED

* Three-valued `Sat` is a genuine improvement: **absence is separated from negation**, and
  "unknown contradiction" from "no contradiction".
* `Zero` is best read as a **derived view** — a name for `GapPartition(K, Req)`. No computed outcome
  in this experiment depends on the name.
* v1.2's **status vocabulary** is the cheapest high-yield change in the whole programme: it makes
  "we do not know" a first-class result instead of a hedge.

# 3. WHAT FAILED

* **CE-1 (factivity)** — reproduced exactly. And v1.2 **weakened the premise** rather than repairing
  the failure, leaving the contradiction *homeless*: real and reproducible, attached to a claim the
  theory no longer asserts firmly.
* **CE-3 (revision without retraction)** — reproduced exactly. §VIII names
  revision/supersession/contradiction/retraction/history and defines no retirement relation.
* **`Zero_strict` was unreachable in every case tested** — because governance, temporal and
  operational requirements are permanently `U`. Under the strict reading, **no state this experiment
  could construct is ever Zero.**

# 4. WHAT REMAINS OPEN

1. **Which `Zero` reading is the theory's?** `NORMATIVE` — not an experimental question.
2. Is `Zero` a construct or a name? (TG12-8)
3. What defines `⪰` on epistemic status? `Sat_status` uses it; the theory does not define it.
4. What retires evidence? (unchanged across two theory versions)
5. Factivity — untouched by v1.2, and its premise now weaker.
6. What fixes `λ`? The equality order moves with it.

# 5. WHAT THE SIMULATION CANNOT ESTABLISH

* Which `Zero` reading is *correct* — that is a governance choice about what closure means.
* That the 8 requirement classes are exhaustive.
* Anything about real knowledge: the world is synthetic, and under v1.2 the observation concept that
  connects it to the world is explicitly **OPEN**.
* Whether the equality order survives a λ the theory has not chosen.

# 6. KERNEL STATUS

> **NOT TESTED — and under v1.2, not testable.**

Stronger than the v1.1 verdict. v1.2 marks `𝒪_core`, `δ`, `≡`, `≈`, `≅_λ` as unresolved and the
transformation equation as `MODEL / HYPOTHESIS`. A kernel cannot be selected while its operation
semantics are open, and E1 shows even the *closure predicate* is ambiguous. **This is v1.2 behaving
correctly**, not a shortfall of the experiment.

# 7. THEORY STATUS

> ## **B. PARTIALLY EXECUTABLE**

The same verdict as v1.1, for **different and better reasons**.

v1.1 was partially executable because two of its clauses could not both hold. v1.2 is partially
executable because it **openly declares** which contracts are missing — and the experiment confirms
that the declaration is accurate: `Zero` is genuinely ambiguous, `δ` genuinely undefined, governance
genuinely unsupplied, and the results genuinely move when the open observation concept moves.

Not **C**, because a theory whose central closure predicate has three non-equivalent readings is not
"internally coherent under tested conditions". Not **D**, and explicitly not because tests passed.
Not **A**, because nothing here is inconsistent — v1.2 is *incomplete by design and honest about it*.

> `[EXP]` **The weakening was scientifically productive: v1.2 repaired no failure but exposed a
> defect v1.1's phrasing had concealed, and improved the diagnosis of one it inherited.**

# 8. NEXT EXPERIMENT

> **The `Zero` Closure Decision Experiment.**

Three arms, one per reading, over the same states and requirement sets:

| Arm | Question it answers |
|---|---|
| `Zero_strict` | is any state ever Zero once governance/temporal/operational are supplied? |
| `Zero_weak` | does tolerating `U` let the system close on inquiries it should not? |
| `Zero_kleene` | what breaks downstream when `Zero` is itself three-valued — `Adequate`, the gate, the decision lane? |

This is the right next experiment because **`Zero` is the closure predicate of the whole theory**:
adequacy, gap, the decision gate and `I1–I9` all read it. Until it is decided, every downstream
result is conditional on which of three predicates was meant.

**Prerequisite, and cheap:** supply a governance authority and a temporal semantics for the test
harness — E1 shows those two classes alone are responsible for `Zero_strict` never being reachable.

**Do not** run another randomized layer. The v1.1 run produced one non-vacuous signal in 10 000
trials; this run produced five findings from four deterministic cases.
