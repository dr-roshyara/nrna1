# 01 — Repair Selection
**Executed 2026-08-30 · Python 3.13.2 · `exec/test_repair_selection.py` → `OUT-REPAIR-SELECTION.txt`**

## Selected: **Repair B — the inquiry register `Q_t ⊆ P`**

## Rationale, in the order it was derived

### 1. B and C2 are the SAME repair
```
I(p) = Asked  ⟺  p ∈ Q_t        a predicate over P and a subset of P are the same object
E(p)                             already a projection of Σ, itself derived from e
=> ΔR(B) ≅ ΔR(C2)
```
The only real difference is **placement**: C2 folds `I` into `Σ`; B leaves `Σ` untouched.

### 2. C2 fails M3 — executed
Nine mandatory operations were examined (`assert, relate, retract, merge, replay, validate, assess,
contradicts, supersede`). **None reads `I` and `E` as an inseparable pair.** `assess()` consumes `e`;
`validate()` consumes `𝒜/ℛ`; `contradicts()` consumes `P,c,t,ℛ`.
> Folding `I` into `Σ` therefore introduces a distinction **the mandatory operation set cannot use** —
> a violation of M3 (no redundant distinction).

### 3. A is REFUTED — executed, with a real counterexample
```
after Ask(p):  |𝒜| = 2               a BOTTOM marker is now a MEMBER of 𝒜
contradicts(real 3.69, BOTTOM) = True   *** SPURIOUS CONTRADICTION ***
is_orphan(BOTTOM) = True                the inquiry marker is itself an orphan
Σ(BOTTOM) = ('Neutral','None')          a non-assertion carries an epistemic status
```
A also requires `BOTTOM ∈ V_D`, which **corrupts the ValueSpace that makes `WellFormed(P)` decidable** —
the single structural fact that makes the theory computable. **A is refuted on executed grounds, not on
taste.**

### 4. Where this DISAGREES with the corpus, and why
Step 281.11 names **`C* = typed inquiry + epistemic`** as the *working* candidate — explicitly
*"subject to minimality and implementation verification"*, and 281.10 states
*"placement in Σ remains a design decision until minimality is demonstrated."*

> **Minimality has now been demonstrated, and it selects B.** This is not a departure from the corpus's
> direction; it is the corpus's own stated selection procedure, executed. **The placement question is
> thereby answered: Σ-B (missingness stays in the inquiry layer) — not Σ-A, not Σ-C.**

### 5. Selection criteria, scored
| Criterion | A | B | C2 |
|---|---|---|---|
| Most minimal | ✗ larger ΔR | **✓** | ✗ fails M3 |
| Preserves all invariants | ✗ breaks `V_D`, pollutes `𝒜` | **✓ 8/8** | ✓ but modifies `Σ` |
| Consistent with existing theory | ✗ `Σ` on a non-assertion | **✓ `Σ` stays a function of `e`** | ✗ `Σ` gains a non-evidential component |
| Lowest implementation cost | ✗ per-query assertion | **✓ one set** | ✓ equal content, extra coupling |
