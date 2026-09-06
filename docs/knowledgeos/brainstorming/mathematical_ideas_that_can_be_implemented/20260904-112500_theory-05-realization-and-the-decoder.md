# Theory 05 — **Realization and the Decoder**

**Document 05 of 15** · 2026-09-04

> The shortest document in the set, and the one with the largest gap between its importance and
> its evidence. **Realization is the least-tested of the three predicates**, and this document
> says so rather than padding it.

---

## 1. Definition

`[DEF]` A representation $R = T(D)$ **realizes** $Q$ under decoder $O$ iff

$$O(R) = Q(D)$$

`[DEF]` $O$ is **fixed in advance**, as part of the inquiry frame $\Pi_{\text{frame}} = (Q,C,O)$.

> **The fixing is the whole content of the definition.** If $O$ may be chosen after seeing $R$,
> then "realization" is just "some function of $R$ equals $Q$", which is adequacy restated.
> Realization is a claim about *the decoder you actually have*, not about the existence of one.

---

## 2. Why realization is not adequacy

| | asks | quantifier | index |
|---|---|---|---|
| Adequacy | is $Q$ determined by $R$? | **∃** a function (implicitly) | population / fiber |
| Realization | does **this** decoder recover $Q$? | **this** $O$ | case |

`[EXP]` The two separate for two independent reasons:

1. **The decoder may be weaker than the information.** $R$ determines $Q$; $O$ does not compute
   the determining function.
2. **The constraint $C$ may fail.** A representation can be adequate and still violate a
   declared constraint (e.g. a $k$-anonymity floor), in which case it is not permitted to be
   decoded at all.

$$\mathrm{Adequate} \;\wedge\; \neg C(R) \;\Rightarrow\; \text{not realizable, though the information is present}$$

`[EXP]` **This is why $C$ belongs in the frame and not in the transformation.** Constraints are
conditions on *use*, not on *construction*.

---

## 3. ⚠️ The evidence is thin, and the reason is a design defect

`[DEFECT]` **`KR-REP-REDUCTION`'s design failed to operationalize the adequacy/realization
separation.** Recorded verbatim from its results, because the failure is instructive:

| separation level | did the design exhibit it? |
|---|---|
| **representation** ($N_{\text{viol}} > 0$) | **yes** — at `R4`, `R3`, `R2` |
| **operator** ($\hat H(Q\mid R) = 0$ but $F < 1$) | **NEVER — the only adequate level, `R5`, has $F = 1.0000$** |

**Why it failed.** The chain's transformations never dropped a record or a source, so $C$ could
never fail; and $O$ was strong enough on the one adequate level. The design excluded its own
target case by construction.

> `[EXP]` **The separation is real** — it follows from the definitions and from the constraint
> mechanism — **but this experiment could not exhibit it, and no experiment yet has.**
> That is the honest position.

`[REC]` The next realization experiment must be designed so that at least one level satisfies
$\hat H(Q\mid R) = 0$ **and** $F < 1$. That requires either a decoder deliberately weaker than
the information, or a constraint that bites at an adequate level. Neither is hard; neither was
done.

---

## 4. The one conjecture

`[CONJ]` **Realization ⟹ Adequacy.** If a fixed decoder recovers $Q$ from $R$ on every case,
then $R$ determines $Q$ on those cases.

**What would refute it:** a case set on which $O(T(D)) = Q(D)$ holds throughout while the fiber
is $Q$-heterogeneous — i.e. the decoder is right by luck, agreeing with $Q$ on the observed
cases but not determined by $R$. **Not tested.** Stated as `[CONJ]` so it can be attacked.

**Note the asymmetry:** the converse, Adequacy ⟹ Realization, is **`[NEG]`** by §2 — a strictly
weaker decoder, or a failing constraint, breaks it.

---

## 5. Register

| Statement | Status |
|---|---|
| $\mathrm{Realized} \iff O(T(D)) = Q(D)$, $O$ fixed in advance | `[DEF]` |
| Adequacy ⟹ Realization | **`[NEG]`** — weaker decoder, or failing $C$ |
| Realization ⟹ Adequacy | `[CONJ]` — refutation condition stated, untested |
| $C$ belongs to the frame, not the transformation | `[EXP]` |
| The adequacy/realization separation has been *exhibited* experimentally | **`[NEG]`** — designed for, never achieved; `[DEFECT]` in `KR-REP-REDUCTION` |
| A decoder may be re-fitted after seeing $R$ | **`[NEG]`** — forbidden by `[DEF]`; doing so collapses realization into adequacy |
