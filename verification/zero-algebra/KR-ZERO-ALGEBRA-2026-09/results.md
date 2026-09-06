# Results

**Base seed `20260902` · 1 200 cases per family · deterministic · `[DEFECT]` pairings excluded.**

---

# 1. The algebraic verdict

> ## `L` is **not a projection**.
> ## The tested algebra is a **terminating, non-confluent rewriting process**.

The spec asked whether the algebra is a *projection*, a *rewriting system*, a *fixed-point operator*,
or something else. **The evidence answers precisely:**

| property | verdict | evidence |
|---|---|---|
| **terminating** | **YES** | `H3` — 0 cycles, 0 non-convergent, **≤ 2 iterations**, 743 immediately stable |
| **monotone decreasing** | **YES** | `H4` — 0 violations |
| **confluent** | **NO** | simultaneous ≠ forward ≠ reverse in **169 / 1 189** cases |
| **idempotent** | **operator-dependent** | `L_sequential` ✔ · `L_rule` ✔ · **`L_simultaneous` ✘** |
| **commutative** | **NO** | 11 witnesses, all context-dependent transforms |
| **element-wise** | **NO, in BOTH directions** | `H5` 131 · `H6` 63 |

> **Terminating but not confluent** is the exact characterisation: iteration always halts, and
> **which normal form it halts at depends on the elimination order.** A projection would give one
> answer; this gives several.

---

# 2. `H1`–`H11`

| | hypothesis | verdict | evidence |
|---|---|---|---|
| **H1** | `L² = L` | **PARTIALLY CONFIRMED** | `L_simultaneous` **4 non-idempotent**; `L_sequential`, `L_rule` 0 |
| **H2** | `L_A L_B = L_B L_A` | **REFUTED** | 11 / 1 162 non-commuting |
| **H3** | iteration reaches a fixed point | **CONFIRMED** | 0 cycles · 0 non-convergent · max 2 iterations |
| **H4** | iteration monotone decreasing | **CONFIRMED** | 0 violations |
| **H5** | individual Zero ⟹ group Zero | **REFUTED** | **131** violations |
| **H6** | group Zero ⟹ individual Zero | **REFUTED** | **63** violations |
| **H7** | Zero invariant under reference change | **REFUTED** | **541 / 4 260** differ |
| **H8** | Zero independent of contract detail | **REFUTED** | **495 / 4 049** differ |
| **H9** | invariant ≡ Zero | **REFUTED** | 1 865 invariant-not-Zero · 858 Zero-not-invariant |
| **H10** | `Remainder = D − Eliminated` | **REFUTED** | `A = B` in only **816 / 1 182** |
| **H11** | `RuleEliminable = CounterfactualZero` | **REFUTED** | **55.75 %** agreement |

**8 refuted · 2 confirmed · 1 partial. No binary outcome was forced.**

---

# 3. `RQ1`–`RQ8`

## RQ1 — the counterfactual is materially different from the rule `[NEG]`

4 203 element tests, **55.75 %** agreement. **1 559 elements the rule would remove are not Zero**;
301 that are Zero the rule keeps. **The asymmetry matters: the rule's error is predominantly
over-elimination.**

## RQ2 — idempotence is operator-relative `[EXP]`

Mechanism for the `L_simultaneous` failures: **context creation** — removing all currently-Zero
elements yields a representation in which new elements become Zero. **This is case I iterated.**

## RQ3 — non-commutativity is localized `[EXP]`

11 witnesses; **every top-ranked pair involves `T4_context`.** Mechanism identified per the spec:
**`B` changes the applicability of `A`** — removing a reference token makes two tokens adjacent, so
the adjacency-conditioned transform now fires. **Elimination changed the context another operator's
precondition reads.**

## RQ4 — convergence holds `[EXP]`

743 immediately stable · 444 converged · **0 cycles · 0 non-convergent · max 2 iterations.**

## RQ5 — monotone `[EXP]`

0 violations of `Retain(L(D)) ⊆ Retain(D)`. **But note the interaction with RQ2**: monotone *and*
non-idempotent together are what identify the process as **iterative reduction**, not projection.

## RQ6 — elimination order matters `[NEG]`

**169 / 1 189 divergent** between simultaneous, forward-sequential and reverse-sequential.
**This is the non-confluence result**, and it is distinct from RQ3: here a *single* operator's
internal ordering changes the outcome.

## RQ7 — Zero is contract-relative `[EXP]`

**495** differences. Most divergent pairs **all involve `P9_balance`** — a cancelling contract
disagrees with every non-cancelling one. **The spec anticipated this and instructed it not be treated
as failure. It is not: it is the central positive finding that `Zero` is genuinely `Π`-indexed.**

## RQ8 — boundary preservation blocks real eliminations `[EXP]`

Of **1 223** cases where removal left the visible result unchanged, **196 (16.03 %) were blocked once
the full boundary contract was applied.**

> **One elimination in six is prevented by boundary information alone** — provenance, uncertainty,
> scope, contradiction state. **This is direct empirical support for the principle
> *same result + changed boundary ⟹ NOT Zero*.**

---

# 4. Remainder — the distinction that must be preserved `[EXP]`

| construction | | agreement |
|---|---|---|
| **A** `D − Eliminated` | | **A ≡ C in 1 182 / 1 182 — exactly** |
| **B** transformation residual | | A = B in 816 / 1 182 |
| **C** contract-unresolved material | | B = C in 816 / 1 182 |

> **Two of the three coincide exactly; the third does not.** `A ≡ C` — "not eliminated" **is** exactly
> "contract-relevant unresolved material", which is a genuine and non-obvious identity.
> **`B` is a different object.** The transformation residual is what survives `T`; that is not what
> the contract leaves unresolved.
>
> **The distinction the spec ordered preserved is `B` vs `{A, C}`.**

---

# 5. Invariant vs Zero `[NEG]`

| | Zero | not Zero |
|---|---|---|
| **invariant under `T`** | 519 | **1 865** |
| **not invariant** | **858** | 915 |

**Near-orthogonal.** `[NEG]` **`invariant = retained` is false.** They are independent
classifications, exactly as the spec suspected, and both off-diagonal cells are large.

---

# 6. Secondary — reasoning regimes `[EXP]`, heavily caveated

Classical (a contradictory state has no determinate content) vs paraconsistent (it retains its
non-contradictory content): **310 differences / 4 201.**

> **This is NOT yet a legitimate regime difference.** Per the spec, before interpreting it as one it
> must survive checks on **applicability · representation · contract · composition · semantics** —
> and here the two regimes were encoded **as two different contracts `Π`**. Given `H8` (Zero is
> contract-relative), **a difference between two contracts is exactly what `H8` already predicts**, so
> this experiment **cannot distinguish a genuine regime effect from an instance of contract
> relativity.** `[OPEN]`, and it must not be cited as regime-relativity evidence.

---

# 7. Final adjudication `Q1`–`Q14`

| | question | answer |
|---|---|---|
| **Q1** | Is `Zero_{T,Π}(x)` operationally testable? | **YES** — 4 203 element tests executed directly on the definition |
| **Q2** | Does the counterfactual differ materially from heuristic rules? | **YES, materially** — 55.75 % agreement; the rule **over-eliminates** 1 559 times |
| **Q3** | Is `L` idempotent? | **Not well-posed without naming the operator.** `L_sequential`/`L_rule` yes; **`L_simultaneous` no** |
| **Q4** | Is `L` order-independent? | **NO** — both across operators (`H2`) and within one (`RQ6`) |
| **Q5** | Does iterative elimination converge? | **YES** — 0 cycles, ≤ 2 iterations |
| **Q6** | Is elimination monotone? | **YES** — 0 violations |
| **Q7** | Can individually Zero elements become jointly non-Zero? | **YES** — 131 witnesses; minimal `[x,x]` |
| **Q8** | Does the contract materially affect Zero? | **YES** — 495 differences; cancelling contracts diverge most |
| **Q9** | Does boundary preservation prevent tempting eliminations? | **YES** — **16.03 %** blocked |
| **Q10** | Is Remainder equivalent to "not eliminated"? | **NO** for the transformation residual; **YES exactly** for contract-unresolved material |
| **Q11** | Can Invariant, Difference, Zero, Remainder be empirically separated? | **YES** — `H9` near-orthogonal; `H10` separates `B` from `{A,C}` |
| **Q12** | Smallest counterexample against each failed claim? | `counterexamples.md` — `H5`: `[x,x]` · `H6`: `[+c,−c]` · `H2`: `[c,ref1,c]` · `H1`: `[the,the,key,key]` |
| **Q13** | What remains OPEN? | §8 |
| **Q14** | **Does this justify changing Theory v1.2?** | **NO.** No governance authorization exists and none is sought |

---

# 8. What remains `[OPEN]`

- **Semantic equivalence** — never invented; all comparisons structural.
- **Whether the regime difference (§6) is genuine** or an instance of contract relativity.
- **Whether a group/set formulation of Zero exists** that is well-defined. `H5`+`H6` show the
  element-wise formulation fails in both directions; **they do not supply the replacement.**
- **Which normal form is correct** when elimination order changes it — non-confluence tells us they
  differ, not which to prefer. **That is a decision, not an experiment.**
- **The direct-sum decomposition** — **not declared valid**, per the spec's final status rule.
- Whether these results transfer between representation classes — **not assumed**, and the corpus
  mixes `R1`–`R4` rather than establishing transfer.

---

# 9. Final status

**Not selected:** a KnowledgeOS kernel. **Not declared:** `Zero` a kernel primitive · the direct-sum
decomposition valid · `L` a projection *(and idempotence was **not** independently demonstrated —
it fails for one operator)* · the Vedic analogy canonical. **Not created:** Theory v1.3.

> **The Vedic material** (`Yāvadūnam`, `Lopanasthāpanābhyām`, `Śiṣyate Śeṣasaṁjñaḥ`, `Nikhilam`) was
> treated strictly as **external methodological inspiration**. **This experiment does not validate
> Vedic Mathematics**, and the structural analogy's only tested contribution is that it suggested a
> *shape* for an operator — **a shape which the evidence then substantially refuted.**

**This is a research adjudication only.**
