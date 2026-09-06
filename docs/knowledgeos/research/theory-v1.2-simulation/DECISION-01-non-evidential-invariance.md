# DECISION 01 — Non-Evidential Invariance
## **`[DECIDED]`** · 2026-09-02 · taken by governance, not by this lane

> ## **Semantic evaluation must be invariant under transformations that leave the evidential content unchanged.**

```
        E  ∼_evidential  E′        ⟹        Comp(E) = Comp(E′)
```

**`C6` and `C7` are ratified as INSTANCES of this principle, not as free-standing criteria.**

| | instance | non-evidential variation |
|---|---|---|
| **`C6`** | permutation invariance | **enumeration order** |
| **`C7`** | frame-refinement invariance | **recording resolution** |

**The principle is more fundamental than either.** Both were `[PROP]` and this lane's; the symmetry
between them is what makes the general form the right object to ratify. **Ratifying the principle
first, then the tests of it**, avoids the situation where two criteria of the same kind are accepted
or rejected on separate grounds.

---

## 1. What this decides

- **Ratified:** the principle, and `C6`/`C7` as instances of it.
- **A composition operator must be a function of evidential content.** An operator whose output varies
  with something that is not evidential content is **not well-defined on the evidence**.

## 2. What this does NOT decide

> ### ⚠️ It does not decide that `majority` violates it.

**Whether a given variation is *non-evidential* is not settled by this principle — it is settled by
what counts as evidential content**, and that is `DECISION-02`.

**Concretely, for `C7`:** the test compares two positives sharing a timestamp against the same two
carrying distinct ones.

| if a frame is… | is the difference evidential? | does `majority` violate `C7`? |
|---|---|---|
| **merely a representational partition** (`DECISION-02` option A) | **no** — same evidence, finer clock | **YES, it violates** |
| **a semantic context** (`DECISION-02` option B) | **yes** — two semantic contexts support `p` rather than one; **frame individuation is part of the evidence** | **NO, it does not violate** |

> ### **The `C7` verdict against `majority` is CONTINGENT on `DECISION-02`.**
> This is why deferring `majority` vs `intraframe-only` is not merely prudent — **it is necessary.**
> The invariance principle cannot be applied until "evidential content" has an extension.

## 3. Self-correction

**This lane's earlier `[PROP]` observation favouring `intraframe-only` was premature**, and for a
reason it had not identified: it treated *"non-evidential"* as settled, when the term **presupposes
the frame ontology that `DECISION-02` has yet to fix.** The `C7` measurement stands; **the inference
drawn from it did not.**

## 4. Scope

Applies to **composition semantics** as tested. It is **not** extended here to evaluation generally,
to `Zero`, or to determination — those would each need their own argument.

**Nothing is adopted. Theory v1.2 unchanged. Kernel NOT SELECTABLE.**
