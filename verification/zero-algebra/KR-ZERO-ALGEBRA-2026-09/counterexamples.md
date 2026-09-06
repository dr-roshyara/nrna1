# Counterexamples — minimal witnesses

**Every witness below is the smallest found in the generated corpus, not a constructed illustration.**
Full sets in `witnesses/*.json`.

---

## `H5` REFUTED — individually Zero, **jointly not** · adversarial case **I**

```
D  = [ x , x ]                    n = 2  — MINIMAL
T  = T2_dedup      Π = P6_scope

Zero({0}) = True        removing either duplicate leaves T(D) unchanged
Zero({1}) = True
Zero({0,1}) = False     removing BOTH destroys the surviving token
```

**Mechanism:** each duplicate is redundant **only because the other is present.** Eliminability is a
property of the element *in context*, and the context is destroyed by the co-elimination.

> **131 witnesses.** `[NEG]` **Zero does not distribute over union.**

---

## `H6` REFUTED — individually non-Zero, **jointly Zero** · adversarial case **J**

```
D  = [ +c , −c ]                  n = 2  — MINIMAL
T  = T1_stopword   Π = P9_balance     (net = #positive − #negative)

Π(T(D))        = ("balance",  0)
Zero({0}) = False   removing +c  → net −1
Zero({1}) = False   removing −c  → net +1
Zero({0,1}) = True  removing both → net 0     ← restored
```

**Mechanism: cancellation.** Two opposed items each individually load-bearing, jointly inert.

> **63 witnesses.** `[NEG]` **Group Zero does not decompose.** Together with case I:
> **`Zero` is not element-wise in EITHER direction.**

---

## `H2` REFUTED — non-commuting elimination

```
D = [ c , ref1 , c ]              n = 3  — MINIMAL
A = T4_context (drop an item whose predecessor has the same token)
B = T5_reference (drop reference tokens)      Π = P4_provenance

L_A(L_B(D)) ≠ L_B(L_A(D))
```

**Mechanism, identified as the spec requires:** **`B` changes the applicability of `A`.** Removing
`ref1` makes the two `c`s **adjacent**, so `T4`'s adjacency condition now fires — a condition that did
not hold before. **Elimination changed the context that the other operator's precondition reads.**

> **11 witnesses; every top-ranked pair involves `T4_context`.** `[EXP]` **Non-commutativity is not
> generic — it is localized to context-dependent transformations.**

---

## `H1` REFUTED for `L_simultaneous` — non-idempotence

```
D = [ the , the , key , key ]     n = 4
T = T2_dedup       Π = P9_balance

L(D) ≠ L(L(D))
```

**Mechanism: context creation.** Simultaneous removal of all currently-Zero elements produces a
representation in which **new** elements are Zero — exactly the case-I structure, iterated.

> `L_sequential` and `L_rule` **are** idempotent on the same corpus. **The verdict depends on the
> operator, and "is `L` idempotent?" is not well-posed without naming it.**

---

## `H11` REFUTED — the declared rule is not counterfactual Zero

**4 203 element tests · agreement 55.75 %.**

| disagreement | n |
|---|---|
| formal Zero, rule says keep | **301** |
| **rule says eliminate, formally NOT Zero** | **1 559** |

> **The heuristic over-eliminates by a wide margin.** `[NEG]` **A rule that looks obviously safe —
> "drop stop-words and repeats" — removes material the preservation contract needs in more than a
> third of all element tests.** This is the experiment's most directly practical finding.
