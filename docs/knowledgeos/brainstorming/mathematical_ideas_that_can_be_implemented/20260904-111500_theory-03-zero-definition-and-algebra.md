# Theory 03 — **Zero: Definition, and the Algebra That Failed**

**Document 03 of 15** · 2026-09-04
**Sources:** `KR-ZERO-ALGEBRA-2026-09`, `KR-ZERO-GROUP-2026-09`, `KR-ZERO-ORDER-2026-09`
**Read with:** **document 03a — *The Concept of Zero*** — what `Zero` *is*, what it is routinely
mistaken for, and why it is not an irrelevance relation. This document covers only the definition
and the algebra.

> **This document is mostly negative, and that is its value.** The programme set out to find an
> algebra of eliminability. It found that the obvious algebraic structures do not hold. Reporting
> that honestly is worth more than a structure that fits the tested space by accident.

---

## 1. The definition

$$\mathrm{Zero}_{T,\Pi}(S;D) \iff \Pi(T(D)) = \Pi(T(E_S(D)))$$

`[DEF]` — **and note what it is not.** It is defined at the level of a **subset** $S$ of a
**specific case** $D$, relative to a **specific** $(T, \Pi)$. It is not a property of a field,
not a property of a schema, and not a property of $D$ alone.

`[EXP]` **`Zero` is contract-relative.** `KR-ZERO-ALGEBRA` `H8`: 495 of 4 049 tested pairs
change their `Zero` verdict when the contract detail changes. Eliminability is **not** intrinsic
to the data.

`[EXP]` **`Zero` is reference-relative.** `H7`: 541 of 4 260 differ under a change of reference.

---

## 2. What `Zero` is **not**, algebraically — the refutation table

Let $L$ be the elimination-closure operator (repeatedly eliminate what is `Zero`).

| # | Hypothesis | Verdict | Evidence |
|---|---|---|---|
| `H1` | $L^2 = L$ (idempotent) | **PARTIAL** | `L_simultaneous` has 4 non-idempotent cases; `L_sequential` and `L_rule` have 0 — **idempotence is operator-relative** |
| `H2` | $L_A L_B = L_B L_A$ | **`[NEG]`** | 11 / 1 162 non-commuting |
| `H3` | iteration reaches a fixed point | **`[EXP]` holds** | 0 cycles, 0 non-convergent, max 2 iterations |
| `H4` | iteration is monotone decreasing | **`[EXP]` holds** | 0 violations |
| `H5` | individual `Zero` ⟹ group `Zero` | **`[NEG]`** | **131** violations |
| `H6` | group `Zero` ⟹ individual `Zero` | **`[NEG]`** | **63** violations |
| `H7` | `Zero` invariant under reference change | **`[NEG]`** | 541 / 4 260 |
| `H8` | `Zero` independent of contract detail | **`[NEG]`** | 495 / 4 049 |
| `H9` | invariant ≡ `Zero` | **`[NEG]`** | 1 865 invariant-not-`Zero`; 858 `Zero`-not-invariant |
| `H10` | $\text{Remainder} = D - \text{Eliminated}$ | **`[NEG]`** | equality in only 816 / 1 182 |
| `H11` | `RuleEliminable` ≡ `CounterfactualZero` | **`[NEG]`** | 55.75 % agreement |

### The algebraic verdict

> ### `[EXP]` $L$ is **not a projection**. The tested algebra is a **terminating, non-confluent rewriting process**.

Termination always holds (`H3`, `H4`). Confluence does not (`H2`, and the order results below).
**Which normal form you halt at depends on the elimination order.** A projection would give one
answer; this gives several.

`[REC]` Any implementation that treats "eliminate everything eliminable" as a well-defined
operation is **wrong on the tested space**. It must either fix an order and declare it, or
carry the set of reachable normal forms.

---

## 3. Set structure — `Zero` sets form no standard regime

Let $\mathcal Z$ be the family of `Zero` subsets.

| closure property | holds | fails | verdict |
|---|---|---|---|
| downward-closed | 347 | 40 | **`[NEG]`** |
| upward-closed | 101 | 286 | **`[NEG]`** — the dominant failure |
| union-closed | 237 | 150 | **`[NEG]`** |
| intersection-closed | 385 | **2** | **`[NEG]`** — but see below |

> `[EXP]` **Two refutations at 0.5 % and one at 74 % are both "refuted" and are not the same
> finding.** Intersection-closure is the closest any standard regime comes, and it fits
> **exactly** once the cancelling contract is removed. Reporting them at equal weight would
> misrepresent the structure.

`[NEG]` **$\mathcal Z$ is not a matroid** — refuted by a single decisive counterexample, and it
too lies under the cancelling contract.

### The consequence that matters for implementation

> `[EXP]` **$\mathcal Z$ is NOT generated upward by its minimal elements** — 286 / 826 failures,
> entirely in the direction *"a superset of a `Zero` set that is not `Zero`"*. The converse
> cannot fail by construction.

`[EXP]` **There is therefore no compact generator-based encoding of eliminability** in the
tested space. One cannot compute a set of "eliminable cores" and close upward. Any
implementation that caches minimal eliminable sets and expands them **will be wrong**.

---

## 3a. The Remainder — the positive result inside `H10`

`H10` above is `[NEG]`, but reporting only its refutation **understates the experiment.** Three
candidate constructions of "what is left over" were compared:

| | construction | agreement |
|---|---|---|
| **A** | $D - \mathrm{Eliminated}$ | **A ≡ C in 1 182 / 1 182 — exactly** |
| **B** | the transformation residual | A = B in 816 / 1 182 |
| **C** | contract-unresolved material | B = C in 816 / 1 182 |

> `[EXP]` **"Not eliminated" IS exactly "contract-relevant unresolved material"** — a genuine and
> non-obvious identity, holding without exception in the tested space.
>
> `[NEG]` **`B` is a different object.** The transformation residual is **not** what the contract
> leaves unresolved. **The residue of the operation is not the residue of the concern.**

*(Conceptual treatment: document 03a §4.)*

---

## 4. Interaction order — the one strong positive

`[EXP]` **Demonstrated empirically within the tested generator and design, for $n \le 6$:** the
eliminability ladder reaches at least $k = 3$ — there exist sets eliminable only as a triple,
not pairwise.

> ⚠️ **Not a proof.** $k > 3$ is `[OPEN]`; $n \le 6$ is a **design bound**, not a property of
> eliminability. The word *proves* is not available.

`[EXP]` **The phenomenon is transformation-specific**, and the narrowing is the real finding:

| transformation | higher-order rate |
|---|---|
| `T2_dedup` | 28.2 % |
| `T8_interacting` | 22.9 % |
| `T4_context` | 11.2 % |
| everything else | ≤ 0.8 % |
| `T7` | exactly 0 |

> ### `[EXP]` Transformations that read **relations between elements** produce higher-order eliminability. Element-wise transformations do not.

`[NEG]` **But relational transformations are not *necessary* for higher-order `Zero`** — this
was tested separately and refuted. The correlation above is strong; the necessity claim is false.

---

## 5. Boundary information blocks real eliminations

`[EXP]` **One elimination in six is prevented by boundary information alone** — provenance,
uncertainty, scope, contradiction state.

This is direct empirical support for treating boundary/provenance as *load-bearing* rather than
as metadata, and it is the strongest link from the `Zero` lane to the kernel design
(document 12).

---

## 6. Register

| Statement | Status |
|---|---|
| $\mathrm{Zero}_{T,\Pi}(S;D) \iff \Pi(T(D)) = \Pi(T(E_S(D)))$ | `[DEF]` |
| `Zero` is contract-relative and reference-relative | `[EXP]` |
| $L$ is a projection | `[NEG]` |
| $L$ terminates | `[EXP]` |
| $L$ is confluent | `[NEG]` |
| individual `Zero` ⟺ group `Zero` | `[NEG]` (both directions) |
| $\mathcal Z$ is downward/upward/union-closed | `[NEG]` |
| $\mathcal Z$ is intersection-closed | `[NEG]`, but by 2 cases, and 0 without the cancelling contract |
| $\mathcal Z$ is a matroid | `[NEG]` |
| $\mathcal Z$ is generated by its minimal elements | `[NEG]` |
| the ladder reaches $k = 3$ | `[EXP]`, $n \le 6$, tested generator |
| the ladder reaches $k > 3$ | `[OPEN]` |
| relational transformations are **necessary** for higher order | `[NEG]` |
| relational transformations **produce** higher order at far greater rates | `[EXP]` |
| boundary information blocks ~1 in 6 eliminations | `[EXP]` |
| $\mathrm{Remainder} = D - \mathrm{Eliminated}$ for the **transformation residual** | **`[NEG]`** |
| "not eliminated" $\equiv$ "contract-unresolved material" | **`[EXP]`** — exact, 1 182/1 182 |
| a well-defined **group/set** formulation of `Zero` exists | **`[OPEN]`** — `H5`+`H6` refute the element-wise one in both directions and supply no replacement |
