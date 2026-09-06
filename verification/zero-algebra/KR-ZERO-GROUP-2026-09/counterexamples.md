# Counterexamples — minimal witnesses

All witnesses are the smallest found in the generated corpus. Full sets in `witnesses/*.json`.

---

## The robust obstruction — case I, a superset of an eliminable set that is not eliminable

```
D = [ c , ref3 , c ]        T = T8_interacting     Π = P5_uncertainty
                            (no cancelling contract involved)

minimal Zero subsets:   all singletons
predicted Zero by upward closure but NOT Zero:   {0,2} , {1,2} , {0,1,2}
Zero but not predicted:                          none
```

> **267 of 746 cases without the cancelling contract.** This is the one structural failure that
> survives every restriction. **The hypergraph of minimal Zero subsets does not determine `Z`.**

---

## Downward-closure failure — without any cancelling contract

```
D = [ x , c , x , c , x ]   T = T2_dedup    Π = P2_len

Zero({0,1,4}) = True        but  Zero({0}) = False
```

**Mechanism:** `Π = P2_len` compares the *sequence and its length*. Removing element 0 alone leaves
`[c,x,c,x]`, which dedups to `[c,x]` — same length, **different order**. Removing `{0,1,4}` leaves
`[c,c]` → `[c]`… the surviving contract value matches. **The sequence contract is order-sensitive, so
a partial removal can disturb what a full removal restores.**

> **26 witnesses without the cancelling contract.** `[EXP]` robust.

---

## Matroid circuit exchange — the single counterexample, and it needs cancellation

```
D  = [ of , a , the , ref3 , a ]     T = T5_reference    Π = P9_balance

minimal Zero subsets (circuits):  {0,1}  {0,2}  {0,3}  {4}
C₁ = {0,1}   C₂ = {0,2}   shared e = 0
(C₁ ∪ C₂) − e = {1,2}   contains NO circuit          → (C3) FAILS
```

**Mechanism:** element `0` cancels against each of `1`, `2`, `3` individually, but `1` and `2` are on
the **same side of the balance**, so `{1,2}` does not cancel. **Cancellation pairs do not satisfy
exchange, because balance is not an independence structure.**

> **The only failure in 312 testable cases, and it is under the cancelling contract. Without `P9`,
> circuit exchange holds universally.**

---

## Intersection-closure — 2 failures in 387, both needing cancellation

```
D = [ b , ref1 , b , must , key , ref3 ]   T = T6_meta_preserving   Π = P9_balance

A = {1,3,5}  ∈ Z        B = {2,3}  ∈ Z        A ∩ B = {3}  ∉ Z
```

> **Without `P9_balance`, intersection-closure holds in 763 / 763 cases.** This is the closest any
> standard regime comes to fitting the family.

---

## Pairwise non-determination — the `FR-001`-shaped obstruction

**7 ambiguous signatures out of 456.** Two subsets with identical singleton-and-pair Zero signatures
differ in group Zero.

> `[EXP]` **Group eliminability carries information that its pairwise restriction does not.**
> Independent of `FR-001` — different domain, different construction — and **not an extension of it.**

---

## The cancellation family — all 15, all under a cancelling contract

```
D = [ x , x ]   polarities = [ True , False ]   T = T3_normalize   Π = P9_balance

Zero({0}) = False      Zero({1}) = False      Zero({0,1}) = True
```

**Minimal Zero subsets of size ≥ 2 have NO eliminable part — the classification found `mixed = 0`.**
Emergent eliminability, where it exists, is **total**: either the whole subset cancels or nothing in
it is eliminable.
