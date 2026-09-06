# 12 — Adoption of `Sufficient(K, 𝒪, ℐ)`

**For consumption by Step 282.** Executable: `exec/sufficiency.py` · transcript `exec/OUT-sufficiency.txt`.

**Closes `G-56`**, open since the second-order pass and at **0 adoption** across Steps 272–282.

---

## 1. The definition

Let `F` be a state abstraction — a projection of the full underlying state. Write

$$s_1 \sim_F s_2 \quad:\Longleftrightarrow\quad F(s_1) = F(s_2)$$

Then, for a mandatory operation set `𝒪` and a mandated predicate/invariant set `ℐ`:

$$
\boxed{
\begin{aligned}
\mathrm{Congruent}(F,\mathcal O)\;&:\Longleftrightarrow\;
  \forall\,T\in\mathcal O_{\text{state}},\ \forall c,\ \forall s_1,s_2:\quad
  s_1\sim_F s_2 \;\Rightarrow\; T(s_1,c)\sim_F T(s_2,c)\\[4pt]
\mathrm{Expressive}(F,\mathcal I)\;&:\Longleftrightarrow\;
  \forall\,I\in\mathcal I,\ \forall s_1,s_2:\quad
  s_1\sim_F s_2 \;\Rightarrow\; I(s_1)=I(s_2)\\[6pt]
\mathrm{Sufficient}(F,\mathcal O,\mathcal I)\;&:\Longleftrightarrow\;
  \mathrm{Congruent}(F,\mathcal O)\ \wedge\ \mathrm{Expressive}(F,\mathcal I)
\end{aligned}}
$$

### 1.1 Why the second conjunct is not an addition but a completion

Both conjuncts have the **same shape**: `F`-indistinguishability must be respected.

- **Congruence** asks it of **operations** — arity ≥ 1, codomain = state.
- **Expressibility** asks it of **predicates** — arity 0 over the state, codomain = a value.

> **Expressibility is congruence for the 0-ary case.**

This is precisely why a criterion stated only for *state-transforming* operations cannot see it —
Step 259 §259.8's restriction (*"only state-transforming operations enter the primary congruence
test"*) is correct **and** it excludes exactly the predicates that `ℐ` contains. The restriction is
not wrong; it is incomplete, and `Expressive` is its complement.

---

## 2. The two conjuncts are independent — executed

Neither implies the other, so `Sufficient` is not a restatement of either.

| Direction | Witness | Result |
|---|---|---|
| **Congruent ∧ ¬Expressive** | `F₄ = K=(𝒜,ℛ)` with `I` = §265.11's merge-provenance invariant | `Congruent = True`, `Expressive = False` |
| **Expressive ∧ ¬Congruent** | `F₂ = content+status` with `I` = *"is anything Accepted?"* | `Congruent = False` (fails on `Remove`, `Revise`, `Transform`), `Expressive = True` |

```
(a) F4 = K=(A,R)          Congruent=True   Expressive(I_merge_prov)=False
(b) F2 = content+status   Congruent=False  Expressive(I_any_accepted)=True
```

**Both directions realized. The conjunction is proper.**

---

## 3. The adoption argument: it would have **predicted** Repair B

This is the decisive test, and it is the reason to adopt rather than merely record.

Model the Step 280 defect: a proposition `p`, and whether an inquiry was recorded.

```
F = K alone      F(M1) = ()          F(M2) = ()
                 identifies M1 and M2?  True
                 Expressive(I_was_asked)?  FALSE      witness: False vs True

F = (K, Q_t)     F(M1) = ((), ())    F(M2) = ((), ('p',))
                 identifies M1 and M2?  False
                 Expressive(I_was_asked)?  TRUE
```

**And the part that matters:**

> **`K` alone is CONGRUENT and NOT EXPRESSIVE.** The only operations are `Assert` and `Ask`; `Ask`
> changes only the inquiry register, which `K` does not project — so `K`-indistinguishability is
> preserved by every `K`-transformation. **Congruence reports no fault.**

$$\boxed{\mathrm{Sufficient}(K,\mathcal O,\mathcal I)\ \text{FAILS on the EXPRESSIBILITY conjunct the moment } \textit{“was }p\textit{ asked?”}\ \in \mathcal I}$$

**Consequence.** The criterion locates the Step 280 defect **before the empirical test runs**, and it
names the repair site exactly: *add the smallest carrier that makes `I_was_asked` expressible.*
That carrier is `Q_t`. **That is Repair B — derived from the criterion rather than selected by
comparison after a failure.**

Step 281 chose B by a three-way comparison *after* Step 280 failed. `Sufficient` reaches the same
answer *a priori*. **That is the case for adoption.**

---

## 4. Post-repair re-check

```
Expressive((K, Q_t), I_was_asked) = True
Congruent : Q_t is append-only under Ask; no K-operation reads it, so
            (K,Q_t)-indistinguishability is preserved by both  = True

Sufficient((K, Q_t), 𝒪 ∪ {Ask}, ℐ ∪ {I_was_asked}) = True
```

**Limitation, stated:** a bounded 3-state model of the M1/M2 distinction, not a proof over the full
theory. It shows the criterion **detects** the defect and **accepts** the repair. It does **not** show
`(K,Q_t)` is sufficient for all of `ℐ` — because **`ℐ` has never been enumerated.**

---

## 5. What adoption obliges

`Sufficient` is only as good as its two parameters, and both are open:

| Parameter | State | Obligation |
|---|---|---|
| **`𝒪`** | 15 named, 5-class partition (§256.2, §259.7); 14-element lower bound forced; 4 operations' mandatory status open (**D-1**) | usable now; `Minimality(K\|𝒯)` still relative |
| **`ℐ`** | **never enumerated** | **the new load-bearing gap** |

**`ℐ` now carries the weight `𝒪` used to.** Six invariants were locatable in the second-order pass
(`so_exp04`: 5 of 6 expressible in `K=(𝒜,ℛ)`); `I_was_asked` is a seventh; §265.11's merge-provenance
is the one that fails. **There is no invariant register.**

> **Recommended: `ℐ` becomes a maintained register, seeded with the seven known members, with the
> same discipline `𝒪` received in §256.2 — each entry cited to a corpus sentence with mandatory force.**

---

## 6. Proposed register lines for Step 282

```
G-56  congruence != sufficiency
      status : CLOSED — Sufficient(K,O,I) defined, conjunct-independence executed,
               predictive validity demonstrated against the Step 281 repair
      caveat : bounded model; I not enumerated

G-67  (new)  I -- the mandated invariant set -- has never been enumerated
      class  : (1) actual theoretical hole
      note   : inherits the load-bearing role O held before Step 272A;
               7 members known, 1 of them (s265.11 merge-provenance) currently
               INEXPRESSIBLE in K=(A,R)  [second-order so_exp04]
```

---

## 7. What this does **not** claim

- **Not** that `(K, Q_t)` is sufficient — that needs `ℐ`.
- **Not** that `Sufficient` is *the* complete criterion. It is two necessary conditions shown
  independent. A third may exist; none is known.
- **Not** a replacement for Step 259's congruence work — it **contains** it as the first conjunct.
- **Not** ratified. Offered for Step 282 to adopt, amend or reject.

**And the standing caution applies to this document too:** `Sufficient` is *demonstrated* on a
bounded model and *derived* from the corpus's own two criteria. It is **`DERIVED`, not proven**, and
it should not be described as a theorem until `ℐ` is closed.
