---
artifact: D285-5 · ACTION / RESULT FORMAL TEST
status: **BOTH PROPERTIES REQUIRED — second holds only under semantic equality.** ⚠️ **AMENDED 2026-08-31 (reviewers A+B): two claims weakened, one citation withdrawn.**
---

# D285-5 · Action ≠ Result

## 1. Property 1 — `o ≠ δ(K,o)`

**Trivially required and not interesting on its own:** `o` is an element of an operation set,
`δ(K,o)` an element of `𝕂`. **Different types.** Any model conflating them is ill-typed.

The corpus already enforces it `CORPUS`: `Command ≠ Transformation` (§256.21–22, boxed).

## 2. Property 2 — SUPERSEDED WORDING

> ### ⛔ SUPERSEDED (reviewer B, 2026-08-31): the strong form below is NOT what this artifact establishes.
>
> **Superseded claim:** *"`δ` is non-injective"* / `δ(K,o₁) = δ(K,o₂) ⇏ o₁ = o₂` stated generally.
>
> **Operative claim:** there exist `o₁ ≠ o₂` and a state `K₀` such that their resulting states are
> equivalent **under a semantic projection that discards `Π`**. This is a property of **`δ ∘ q`** for a
> chosen quotient `q`, **not of `δ`**. **Under `≅_λ` the same `δ` is injective on this very witness.**
>
> *Reviewer B: "I would treat the old stronger wording as **superseded**, not merely annotated."* Done.

### The original derivation, retained as the route to the operative claim

This is the substantive one. **Executed** (`exec/t285_reconcile.py` T-E):

```
o1 = (Assert, a2, origin:scan,   actor:tool)
o2 = (Assert, a2, origin:vendor, actor:human)
delta(K0,o1) = ['a1','a2']      delta(K0,o2) = ['a1','a2']
states equal? True              operations equal? False
```

> **CONFIRMED and REQUIRED.** Two operations differing in provenance, actor and authority produce the
> same resulting state. Collapsing them would make *"who asserted this, on whose authority"*
> unrecoverable from the state — which the estate has already suffered once
> (`IMPLEMENTATION`: two authority acts sharing one `grantId`, *"a silent loss of an authority act"*).

## 3. ⚠️ The qualification the property needs — and it is easy to miss

`EXECUTED` — Under the verification lane's own identity rule `id = H(P, e, c, t, Π)`, **`Π` is inside
the hash.** So `o₁` and `o₂` produce assertions with *different ids*, hence **structurally different
states**, and the antecedent `δ(K,o₁) = δ(K,o₂)` is **false** — the property never fires.

**It fires only under semantic equality** (equal on `(P, c, t)`, ignoring `id`, `e`, `Π`):

| Equality | Antecedent holds? | Property meaningful? |
|---|---|---|
| structural | **no** — `Π ∈ id` separates them | vacuously true, useless |
| **semantic** | **yes** | ✅ **required and load-bearing** |
| observational | yes | required |

> **Typed result:** `δ(K,o₁) =_semantic δ(K,o₂) ⇏ o₁ = o₂`.
> **Stating the property without naming the equality makes it vacuous.** This is exactly the class of
> error that equality typing exists to prevent, and the
> Gītā framing — *"do not define the action by its fruit"* — does not by itself supply the
> qualification. **Philosophy asked the question; the equality typing answered it.**

## 4. Independence

| Route | Reaches the property? |
|---|---|
| **Gītā** `[S]` 2.47–2.48 — duty distinguished from its fruits | yes, as `[P]` |
| **KnowledgeOS, independently** | yes — `Command ≠ Transformation` (§256.21) is derived from typing, with no philosophical input; and the `grantId` collision is an implementation *failure* that demands it |

> **Verdict: the Gītā CORROBORATES a property KnowledgeOS derives on its own.** Per Step 286's own
> methodology that is *"a stronger scientific result than claiming KnowledgeOS was derived from the
> Gītā."* **Classified `R5` on the mathematics, `R2` on the philosophical contribution.**

---

## 8. Amendments (reviewers A + B, 2026-08-31)

| # | Amendment |
|---|---|
| **1** | ⚠️ **`history ⊊ structural ⊊ semantic` is NOT a corpus hierarchy.** Measured: it occurs only in verification-lane artifacts. The corpus (246) defines **four** relations — structural `=` · semantic `≡` · observational `≈` · **provenance-sensitive `≅_λ`** — and *"these are not interchangeable."* **`history` is not among them.** My citation is withdrawn |
| **2** | ⚠️ **`=_semantic` is a research construct, not a corpus primitive.** The corpus names `≡` without a decision procedure, and **whether `Π` is inside it is Step 254 Decision 3 — explicitly OPEN.** The projection used here (discard `id`, `e`, `Π`) **silently resolved that open decision** |
| **3** | ⚠️ **The property establishes LESS than "non-injectivity."** It establishes non-injectivity **modulo a chosen quotient** — a property of `δ ∘ q`, not of `δ`. **Under `≅_λ`, the same `δ` is injective on this very witness** |
| **4** | ⚠️ **The `grantId` citation is WITHDRAWN.** Two operations with one semantic result is a **quotient** question; two authority acts with one identifier is an **id-assignment** defect. Different layers. The `humanActRef` question stands on its own implementation evidence, **not on this property** |

> **The HPA review accepted this artifact as written. These amendments come from applying the
> review's own equality mandate more deeply — the methodology found them, and it found them in the
> artifact the methodology was built from.** Full analysis: `E1-E7-EQUALITY-INVESTIGATION.md`.
