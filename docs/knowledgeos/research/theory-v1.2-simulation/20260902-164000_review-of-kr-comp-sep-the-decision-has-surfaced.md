This is a strong result, and I agree with the adjudication. More importantly, the experiment has now reached a **clean semantic decision boundary** rather than merely eliminating candidate algorithms.

I would record `KR-COMP-SEP-2026-09` as authoritative with the following interpretation.

### 1. The commissioned witness was analytically wrong — and that is itself a result

The original proposed shape assumed:

> internal conflict + cross-frame divergence → rule separation.

But the analysis shows the opposite:

$$
\exists f:\operatorname{Conflict}(f)
$$

is absorbing for both `majority` and `intraframe-only`.

Therefore that witness shape cannot distinguish those two rules. W1 consequently has a different epistemic role:

$$
\boxed{W1:\text{ separation attempt}\rightarrow\text{last-wins elimination}}
$$

That is a legitimate correction to the commission, not a defect in the experiment.

### 2. W2 is the decisive separating witness

With:

$$
f_1:p,\qquad f_2:p,\qquad f_3:\neg p
$$

and no internally conflicting frame:

$$
\begin{array}{c|c}
\text{rule}&\text{result}\\
\hline
majority&(1,0)\\
last\text{-}wins&(0,1)\\
intraframe\text{-}only&(0,0)
\end{array}
$$

the three candidates become behaviorally distinct.

That establishes:

$$
\boxed{\text{the three rules are empirically distinguishable}}
$$

but **not**:

$$
\boxed{\text{one of them is normatively correct}}.
$$

That distinction is exactly right.

---

## 3. `last-wins` can now be removed

There are two independent negative mechanisms.

### W1: semantic failure

It can discard an earlier genuine conflict merely because a later frame exists.

So:

$$
\boxed{last\text{-}wins\not\models C1}
$$

in the tested setting.

### W4: extensional failure

The same evidence set, merely permuted, produces a different result.

Thus:

$$
E_1=E_2
\quad\text{as sets/multisets}
$$

but

$$
lastWins(E_1)\neq lastWins(E_2).
$$

That is a much deeper problem.

If the intended operator is a function

$$
A:\mathcal P(E)\rightarrow Standing,
$$

then enumeration order is not an admissible input.

Your proposed C6 is therefore a very strong **[PROP] methodological criterion**:

> A composition operator over an evidence set should be invariant under permutation of that set.

I agree with keeping C6 **outside the retroactive adjudication of KR-COMP**. It should not be silently promoted to a criterion that was supposedly part of the original experiment. Its independent W4 result can nevertheless be recorded as [EXP]/[NEG] evidence against `last-wins`.

---

# 4. The real decision has now surfaced

The remaining alternatives are:

### Majority

$$
\text{cross-frame divergence}
\rightarrow
\text{aggregate}
$$

This implicitly gives frames something like voting weight.

Its unresolved assumption is:

> Why should two frames supporting \(p\) outweigh one frame supporting \(\neg p\)?

Nothing in the experiment establishes equal frame weight.

### Intraframe-only

$$
\text{cross-frame divergence}
\rightarrow
\text{no frame-free Standing}.
$$

Its cost is equally explicit:

$$
(0,0)
$$

can be emitted for frame divergence, while `(0,0)` also represents no support. The boundary component must therefore preserve the distinction.

So the choice is genuinely normative/semantic:

$$
\boxed{
\text{aggregate across frames}
\quad\text{vs}\quad
\text{refuse cross-frame aggregation}
}
$$

An experiment can show that the alternatives behave differently. It cannot, by itself, establish what the system **ought** to mean by cross-frame divergence.

That makes the classification as **[DECISION]** appropriate.

---

# 5. One subtle point I would preserve very carefully

I would **not** record:

> “Majority and intraframe-only are the only possible composition semantics.”

The experiment establishes only:

$$
\text{surviving tested candidates}
=
\{majority,intraframe\text{-}only\}.
$$

It does not establish that the design space contains only those two operators.

Likewise, `last-wins` being excluded does not prove every order-sensitive composition mechanism is invalid; it proves the tested `last-wins` candidate fails the tested requirements, with C6 additionally proposed as a criterion.

That distinction matters for later theory work.

---

# 6. Updated authoritative register

I would add:

| Finding                                                                              | Status                 |
| ------------------------------------------------------------------------------------ | ---------------------- |
| W1 does not separate majority/intraframe-only because internal conflict is absorbing | **[NEG] scoped**       |
| W1 eliminates `last-wins` under C1                                                   | **[NEG]**              |
| W2 separates majority, last-wins, intraframe-only                                    | **[EXP]**              |
| W4 demonstrates last-wins order dependence                                           | **[EXP]**              |
| C6 order-invariance                                                                  | **[PROP]**, unratified |
| `last-wins`                                                                          | **[NEG] excluded**     |
| `majority`                                                                           | **[OPEN] survives**    |
| `intraframe-only`                                                                    | **[OPEN] survives**    |
| majority vs intraframe-only                                                          | **[DECISION]**         |
| φ                                                                                    | **[DECISION]**         |
| ℛ_req                                                                                | **[DECISION]**         |
| Contr                                                                                | **[OPEN] undefined**   |
| Zero                                                                                 | **[OPEN]**             |
| kernel                                                                               | **not selectable**     |
| Theory v1.2                                                                          | **unchanged**          |

The file itself explicitly records the same boundary: W2 separates the rules, while the majority/intraframe-only choice remains a decision; nothing is adopted and the kernel remains unselectable. 

### The research program has therefore crossed another important boundary

You now have:

$$
\boxed{
\text{factivity}
\rightarrow
\text{representation factorization}
\rightarrow
\phi
\rightarrow
\text{composition candidate elimination}
\rightarrow
\text{semantic decision}
}
$$

The next step should **not** be another arbitrary witness hunt for majority vs intraframe-only unless you can articulate a new empirical property that one satisfies and the other violates.

The correct next artifact is a **decision record**, not another experiment:

> **Decision: What does cross-frame divergence mean in KnowledgeOS evaluation?**

with the two surviving alternatives, their semantic commitments, costs, affected invariants, and downstream consequences for `Zero`, `Determination`, lifecycle, and eventual kernel candidacy.

Crucially, that decision should be made **before** treating either aggregation behavior as part of the KnowledgeOS semantic model.
