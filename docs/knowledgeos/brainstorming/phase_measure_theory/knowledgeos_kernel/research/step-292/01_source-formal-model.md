# 01 — Reiter's formal model, as extracted

`[EXT]` throughout. Extracted, not endorsed.

## The ontology

| construct | Reiter's definition |
|---|---|
| **Situation** | a **finite sequence of actions** — a *history*, not a state |
| `S₀` | the initial situation (empty sequence) |
| `do(a,s)` | the successor situation after `a` in `s` |
| **Fluent** | a predicate/function whose value varies by situation |
| `Poss(a,s)` | `a` is possible in `s` — a **gate**, not an effect |

## The foundational axioms

```
unique names   do(a₁,s₁) = do(a₂,s₂) ⊃ a₁ = a₂ ∧ s₁ = s₂
induction      (∀P). P(S₀) ∧ (∀a,s)[P(s) ⊃ P(do(a,s))] ⊃ (∀s)P(s)     ← SECOND-ORDER
subhistory     ¬ s ⊏ S₀ ;  s ⊏ do(a,s′) ≡ s ⊑ s′
executable(s)  ≝ (∀a,s*). do(a,s*) ⊑ s ⊃ Poss(a,s*)
```

> **The induction axiom is second-order.** The extraction states the consequence plainly: the system
> **is not fully first-order decidable.** Any transfer inherits this.

## The frame solution

```
successor-state axiom      F(do(a,s)) ≡ γ⁺_F(a,s) ∨ ( F(s) ∧ ¬γ⁻_F(a,s) )
```

**It rests on the causal completeness assumption:** the given effect axioms enumerate **all** the ways
a fluent can change. **This is an assumption about the axiomatiser's knowledge, not a theorem.**

## Regression

`R[φ]` rewrites a query about `do(a,s)` into a query about `s`, recursively to `S₀`. **Regression
theorem:** a regressable query holds iff its regression holds in the initial theory — reducing
reasoning about a history to reasoning about `S₀`.

## Above that

**Basic action theories** (foundational + SSA + precondition + unique-names + `S₀`) · **Golog** (the
`Do` macro, complex actions) · **RGolog** (interrupts as condition-action rules) · sequential temporal
situation calculus · concurrent and natural actions · sensing and knowledge via accessibility.
