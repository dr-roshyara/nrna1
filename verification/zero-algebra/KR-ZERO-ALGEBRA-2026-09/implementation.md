# Implementation

## Two paths, kept apart by construction

The spec's central methodological requirement: **do not make the two paths identical by
implementation.**

### PATH A — formal counterfactual

```python
def zero(D, T, Pi, idxs):
    return Pi(T(D)) == Pi(T(D.without(idxs)))
```

Takes a **set** of indices, so the same function answers element-wise and group Zero. **No heuristic
enters it.**

### PATH B — declared elimination rule

```python
def rule_eliminable(D, T, Pi, i):
    it = D.items[i]
    if it.token in STOPWORDS: return True
    return any(D.items[j].token == it.token for j in range(i))   # a repeat
```

**A stop-word or a repeat.** It never consults `Π`, never runs the counterfactual, and is
deliberately the kind of rule an implementation would reach for. **The measured disagreement is the
result — see `results.md` H11.**

## Three elimination operators, because "L" is ambiguous

| operator | definition |
|---|---|
| `L_simultaneous` | remove **every** individually-Zero element at once |
| `L_sequential` | remove **one** Zero element, **re-check**, repeat |
| `L_rule` | remove everything PATH B flags |

> **Introducing three was not padding.** `H1` ("is `L` idempotent?") **has no answer until the
> operator is named** — and the three do not agree. Reporting a single verdict for "L" would have
> concealed that.

## The vacuity guard

```python
def contract_is_vacuous(D, T, Pi):
    # every element Zero AND the contract reads metadata AND T destroys metadata
```

**Why it exists.** `T7_meta_destroying` strips all metadata; `P9_balance` reads only polarity. Their
composition makes **every** element trivially Zero. Without the guard this would have inflated every
"Zero rate" in the experiment and would have looked like a finding.

**`[DEFECT]`-class pairings are excluded from every algebraic conclusion**, and reported separately.
**Detected: `T7_meta_destroying × P9_balance` only** — the other metadata contracts embed `P1_result`,
which survives `T7`, so they remain discriminating.

## What was NOT done

- **No semantic comparator invented** — exact structural equality only.
- **No repair of the theory** when a property failed.
- **No case hand-selected** to produce a verdict; all witnesses are the *smallest* found in a
  generated corpus.
