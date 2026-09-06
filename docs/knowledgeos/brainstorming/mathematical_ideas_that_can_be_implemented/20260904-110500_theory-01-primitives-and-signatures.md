# Theory 01 — **Primitives and Signatures**

**Document 01 of 15** · 2026-09-04 · Theory v1.2 FROZEN · kernel NOT SELECTED

Everything in this set is built from six objects and one operator. This document fixes their
signatures. **All statements here are `[DEF]` unless tagged otherwise — definitions are
stipulated, carry no truth claim, and are replaced rather than refuted.**

---

## 1. The objects

| Symbol | Name | Signature | Reads |
|---|---|---|---|
| $D$ | **data** — a case | a finite multiset of records $r$ | — |
| $T$ | **transformation** | $T : \mathcal D \to \mathcal R$ | some fields of $D$ |
| $R$ | **representation** | $R = T(D)$ | — |
| $Q$ | **inquiry** | $Q : \mathcal D \to Y$ | some fields of $D$ |
| $\Pi$ | **observable** | $\Pi : \mathcal R \to O$ | some fields of $R$ |
| $O$ | **decoder** | $O : \mathcal R \to Y$ | $R$ only |
| $C$ | **constraint** | a predicate on $R$ | $R$ |

`[DEF]` A **record** in the tested carriers is $r = (\text{value},\ \text{source},\ \text{tag},\ \text{timestamp})$,
optionally carrying a derived $\text{rank}$. **This is an experimental carrier and is not
declared to be the mathematical carrier of KnowledgeOS** (see §5).

`[DEF]` The **inquiry frame** is the triple

$$\Pi_{\text{frame}} = (Q, C, O)$$

— *what is asked*, *what must hold*, *how the answer is decoded*. The decoder $O$ is **fixed in
advance**; it is not chosen after seeing $R$. Choosing $O$ post hoc converts realization into
curve-fitting.

---

## 2. The elimination operator $E_S$ — and why it is **typed**

`[DEF]` For $S$ a set of record indices,

$$E_S(R) \;=\; \text{the representation obtained by removing } S \text{ and RE-ESTABLISHING the level's invariants.}$$

> ### `[EXP]` Set subtraction $D \setminus S$ is **INVALID** wherever a field is relative.

If a level carries **ranks within a group**, removing a record changes the *surviving records'*
own values. The naïve $D \setminus S$ leaves stale ranks and silently produces a representation
that the level's own rules forbid. In the tested implementations $E_S$ therefore **recomputes**
rank at the relational level.

`[REC]` **Every elimination operator must declare the level it acts at.** An untyped
`E_S` is a defect, not a simplification.

---

## 3. The three predicates

`[DEF]` **Zero** — *can this be removed without changing the specified observation?*

$$\mathrm{Zero}_{T,\Pi}(S;D) \iff \Pi(T(D)) = \Pi(T(E_S(D)))$$

`[DEF]` **Adequacy** — *is the meaning still there?*

$$\mathrm{Adequate}(T,Q) \iff \hat H\!\left(Q \mid T(D)\right) = 0$$

`[DEF]` **Realization** — *can the actual consumer recover it?*

$$\mathrm{Realized}(T,Q,O) \iff O(T(D)) = Q(D)$$

**Note the subscripts.** `Zero` is indexed by $(T,\Pi)$ and by the **subset** $S$; adequacy by
$(T,Q)$ and the **population**; realization by $(T,Q,O)$ and the **case**. They are not three
readings of one quantity. They are three quantities over three different index sets, and
document 02 shows they come apart.

---

## 4. The anti-circularity requirement — `FR-003` candidate

> ### `[EXP]` $\Pi$ and $Q$ must read **disjoint fields**, and disjointness must be discharged on the **triple** $(\Pi, Q, T)$, not the pair $(\Pi, Q)$.

If $\Pi$ and $Q$ share a field, `Zero` and preservation are no longer independently defined and
part of any relationship between them is built into the definitions.

**Why the triple.** $\Pi$ reads $T(D)$, not $D$, and $T$ can *derive* an output field from
sources $\Pi$ never declared. Two concrete failures, both found in KR-BRIDGE-02:

```
rank = f(value, tag)          because T_D ranks WITHIN tag group
Π falls back to rank          ⟹ Π effectively reads {value, tag}
Q reads {source, tag}         ⟹ they SHARE tag
```

```
dedup selects survivors by value
  ⟹ the surviving source/tag/time columns depend on value
  ⟹ the record COUNT depends on value
  ⟹ Π_arity — which declares NO field reads at all — effectively reads {value}
```

`[EXP]` **Declaration is not verification.** Two hand-written provenance maps were wrong;
a perturbation test caught both, code review caught neither. The effective read-set must be
**computed** through $T$'s provenance and **verified empirically**.

`[REC]` Promote as `FR-003` once a second independent adopter exists (`ES-006.1`).

---

## 5. ⚠️ The carrier is **not** declared

`[OPEN]` **KnowledgeOS has no declared mathematical carrier.** Every experiment used a
deliberately small numerical carrier so that fibers would collide and adequacy would be
measurable. That is a *calibration* choice, not an ontological one.

| what was used | what it is not |
|---|---|
| $(\text{value},\text{source},\text{tag},\text{time})$, $\lvert D\rvert \le 6$ | a knowledge representation |
| 4–8 distinct values | a value domain |
| $\arg\max_{\text{source}}$, tag counts, deciles | business inquiries |

> **`[OPEN]` The move from this to documents, claims, entities, relations, provenance,
> contradictions, temporal states and evidence is the open research problem.** No result in
> this set should be quoted without its carrier.

---

## 6. Type discipline — the rules that hold

| Rule | Status |
|---|---|
| $E_S$ declares its level and re-establishes that level's invariants | `[DEF]` + `[EXP]` (relational levels break without it) |
| $\Pi$ and $Q$ are read-disjoint on $(\Pi,Q,T)$, verified empirically | `[EXP]`, candidate `FR-003` |
| $O$ is fixed before $R$ is seen | `[DEF]` |
| A transformation family is either **sequential** ($R_{k+1} = T_k(R_k)$) or **parallel** ($R_k = T_k(D)$) — never silently mixed | `[EXP]`, and it is load-bearing: document 04 shows the DPI applies to one and not the other |
