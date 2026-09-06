# 05 — T-4 Non-Identifiability Result
**`exec/f15_nonidentifiability.py` → `OUT-F15.txt`**

## Constructed instance
```
a: Prop(Nexus,Version,3.69), Π=srcA        K1 = {a}
b: Prop(Nexus,Version,3.69), Π=srcB        K2 = {b}
K1 == K2 (structural): False               <- DISTINCT states
```
## Observationally equivalent under `O_core`
| Observation | K1 | K2 | same |
|---|---|---|---|
| `query(Nexus,Version)` | `['3.69']` | `['3.69']` | ✓ |
| `Σ` | `('Supporting','Weak')` | `('Supporting','Weak')` | ✓ |
| `\|𝒜\|`, `\|ℛ\|` | 1, 0 | 1, 0 | ✓ |
| `Valid` | True | True | ✓ |
| contradictions | 0 | 0 | ✓ |

**`K₁ ≠ K₂` and `K₁ ≈_O_core K₂`. Non-identifiability instance CONSTRUCTED.**

## Missing primitive, or derived property?
```
NonIdentifiable(K1, K2, O)  :=  K1 ≠ K2  ∧  O(K1) = O(K2)
```
**Two lines over structural equality and an observation set — both already in the theory.**
Adding a primitive would violate **M3** (no redundant distinction).

## The decisive point
> The theory **represents** the difference (`Π(a)=srcA ≠ Π(b)=srcB`) **and** expresses that `O_core`
> cannot detect it. **That is exactly what a non-identifiability statement requires.**

> ## VERDICT: **DERIVED PROPERTY, not a missing primitive. T-4 CLOSED.**
> **Correction to my own prior claim:** I had recorded non-identifiability as *inexpressible*. That
> conflated *"`O` cannot observe it"* with *"the theory cannot say it."* **My earlier status was wrong.**
