# `KR-COMP-SEP-2026-09`
# The Separating Witness

**Commissioned by** `KR-COMP-2026-09` §10: *"a witness with genuine conflict inside one frame **and**
divergence across others."*
**Baseline** v1.2 unchanged · no v1.3 · `app/` untouched · **nothing adopted** · deterministic.
**Code** `kos12/comp.py` · **Results** `results/comp/SEP*.json`.

---

## 1. Headline

> ### The commissioned witness shape does **not** separate the three rules. A different shape does — and the commissioned shape eliminates a rule outright instead.

| | outcome |
|---|---|
| **`W1`** — the commissioned shape (conflict in one frame **+** divergence across others) | **separates only `last-wins`** (2 distinct outputs) — **but ELIMINATES it** |
| **`W2`** — asymmetric divergence, **no** internal conflict | **SEPARATES ALL THREE** (3 distinct outputs) |
| **`C6`** — order-invariance *(this lane's criterion)* | **eliminates `last-wins` a second time, independently** |

**Net: `last-wins` is eliminated twice over. `majority` and `intraframe-only` survive, now
behaviourally separated — and the choice between them is a DECISION, not an experiment.**

---

## 2. Why the commissioned shape does not separate

**Internal conflict is ABSORBING under both `majority` and `intraframe-only`.** Both short-circuit to
`(1,1)` the moment any frame conflicts internally:

```
majority          if ∃ frame with (S⁺ ∧ S⁻) → (1,1)   … then counts
intraframe-only   if ∃ frame with (S⁺ ∧ S⁻) → (1,1)   … then cross-frame policy
```

> **So adding internal conflict to a witness DESTROYS the separation rather than creating it.** The
> two rules differ only in what they do when **no** frame conflicts internally — which is exactly the
> case the commissioned shape excludes by construction.
>
> **The separating witness must have NO internal conflict.**

## 3. The separating witness — `W2`

Three frames under `φ = {time, context}`, **none internally conflicting**, with **asymmetric** counts:

```
frame (t=1, C1)   p          → (1,0)
frame (t=2, C1)   p          → (1,0)
frame (t=3, C1)   ¬p         → (0,1)
```

| rule | output | reading |
|---|---|---|
| **`majority`** | **positive-support** `(1,0)` | 2 positive frames outweigh 1 negative |
| **`last-wins`** | **negative-support** `(0,1)` | the last frame enumerated wins |
| **`intraframe-only`** | **unsupported** `(0,0)` | divergence across frames is not support |

**Three distinct outputs from one witness.** Asymmetry is essential: the symmetric control `W3`
(1 positive frame, 1 negative) gives `majority ≡ intraframe-only ≡ unsupported` — **2 distinct only.**

---

## 4. `W1` earns its place anyway — it eliminates `last-wins`

`W1` contains a genuine intra-frame contradiction:

```
frame (t=1, C1)   p AND ¬p   → (1,1)   ← genuine conflict
frame (t=2, C1)   p          → (1,0)
frame (t=3, C1)   ¬p         → (0,1)
```

> `[NEG]` **`last-wins` reports `W1` as `negative-support`, NOT as conflict.** It reads only the last
> frame and **discards the internally-contradictory frame entirely.**
>
> **This is a C1 failure — "preserve genuine positive/negative conflict" — on all six of its
> surviving triples**, and the original `KR-COMP` suite could not see it, because there the
> contradiction always sat in the only frame or the last one.

**So the commissioned shape was not wasted. It just does elimination, not separation.**

---

## 5. `C6` — order-invariance · **this lane's criterion, not the commission's**

> **A composition rule must be a function of the evidence SET, not of its enumeration order.**

`W4` is `W2` with the identical evidence multiset **permuted** (asserted in code):

| rule | `W2` | `W4` (permuted) | invariant |
|---|---|---|---|
| `union` | conflicting | conflicting | ✔ |
| `majority` | positive-support | positive-support | ✔ |
| **`last-wins`** | **negative-support** | **positive-support** | **✘** |
| `strict` | unsupported | unsupported | ✔ |
| `intraframe-only` | unsupported | unsupported | ✔ |

> `[NEG]` **`last-wins` is not well-defined on the evidence set.** Its output depends on the order in
> which evidence happens to be enumerated — **not on `time`, which is already a field it could have
> used.** A rule whose verdict changes when the same evidence is listed in a different order is not a
> composition rule; it is an artefact of iteration.
>
> **`C6` is proposed by this lane and is NOT one of the commission's five.** It is offered as a
> candidate criterion, `[PROP]`, not applied retroactively to the `KR-COMP` verdict.

---

## 6. Position after this experiment

| rule | status |
|---|---|
| `union` | **excluded** (`KR-COMP` §6 — ignores the frame) |
| `strict` | **excluded** (`KR-COMP` §6 — destroys genuine conflict) |
| **`last-wins`** | **EXCLUDED — twice, independently**: fails C1 on `W1`; fails C6 (order-dependent) |
| **`majority`** | **survives** — separated from `intraframe-only` by `W2` |
| **`intraframe-only`** | **survives** — separated from `majority` by `W2` |

## 7. The remaining choice is a DECISION, not an experiment

**`W2` separates `majority` from `intraframe-only`. Separating is not deciding.** They disagree about
what cross-frame divergence *means*:

| | on `W2` | the commitment |
|---|---|---|
| **`majority`** | `positive-support` | **divergence across frames is AGGREGATED** — frames vote, the weight of evidence carries |
| **`intraframe-only`** | `unsupported` | **divergence across frames is REFUSED** — a claim supported in one frame and denied in another has no frame-free standing |

> **No experiment settles this**, because both are coherent and the witnesses cannot say which the
> system *ought* to do. It is the same shape as factivity, `M3≅M4` and `𝓑`.
>
> **`intraframe-only` carries a known cost, stated rather than hidden:** it emits `(0,0)` for
> divergence, which is the same `Standing` as *no evidence*. They remain distinguishable **only**
> because the boundary component separates them — verified — which is a further instance of
> `E-FDE-2`/`E-FDE-3`: **`Standing` alone is insufficient.**
>
> **`majority` carries its own cost:** it lets frame counts substitute for evidential weight, and
> nothing tested here says frames should be counted equally.

## 8. Verification

**Adding `W1`–`W4` to the suite breaks nothing for the surviving triples** — `majority` and
`intraframe-only` report `W1` as conflict, report no false conflict on `W2`/`W3`, and keep divergence
distinguishable from genuine no-support, across all φ ⊇ `{time, context}` and all three status
policies. **The only failures are `last-wins`'s six, in §4.**

## 8.1 ⚠️ Scope guard — what "surviving" does and does not mean

**Preserved carefully, per review §5.**

> **This experiment establishes only:**
> ```
> surviving TESTED candidates  =  { majority , intraframe-only }
> ```
> **It does NOT establish that the design space contains only those two operators.**

**Two things that must never be recorded:**

| ❌ do not record | ✅ what is established |
|---|---|
| *"`majority` and `intraframe-only` are the only possible composition semantics"* | they are the **surviving tested candidates** of five enumerated |
| *"order-sensitive composition is invalid"* | **the tested `last-wins` candidate** fails the tested requirements; `C6` is **proposed**, not established |

**The distinction matters for later theory work.** A design-space claim requires an argument that the
enumeration was exhaustive; **none was made, and none is available.** Five rules were enumerated
because five were thought of.

## 8.2 The two negative mechanisms against `last-wins`, kept separate

They are independent, and conflating them would overstate both.

| | mechanism | strength |
|---|---|---|
| **`W1` — semantic failure** | it can **discard an earlier genuine conflict merely because a later frame exists** → `last-wins ⊭ C1` **in the tested setting** | `[NEG]`, scoped to the tested requirements |
| **`W4` — extensional failure** | `E₁ = E₂` as multisets, yet `lastWins(E₁) ≠ lastWins(E₂)`. **If the intended operator is `A : 𝒫(E) → Standing`, enumeration order is not an admissible input** | `[EXP]`/`[NEG]` — **deeper**: it is not a failure of a requirement but of well-definedness |

> **`C6` stays OUTSIDE the retroactive adjudication of `KR-COMP`** and is **not** silently promoted to
> a criterion that was supposedly part of the original experiment. **The `W4` result stands on its own
> as `[EXP]`/`[NEG]` evidence against `last-wins`, independently of whether `C6` is ever ratified.**

## 9. Status

| | class |
|---|---|
| `W2` separates all three surviving rules | `[EXP]` |
| the commissioned shape `W1` does not separate — internal conflict is absorbing | `[NEG]` |
| `last-wins` fails to report genuine conflict when it is not in the last frame | `[NEG]` |
| `last-wins` is order-dependent | `[NEG]` |
| `C6` order-invariance as a criterion | `[PROP]` — this lane's, unratified |
| the `majority` / `intraframe-only` choice | **`[DECISION]`** |
| *"only two composition semantics exist"* | **NOT established — see §8.1** |
| *"order-sensitive composition is invalid"* | **NOT established — see §8.1** |

**Nothing adopted · no rule selected · Theory v1.2 unchanged · `φ` and `ℛ_req` still decisions ·
`Contr` still undefined · kernel NOT SELECTABLE.**

---

## 10. The correct next artifact is a DECISION RECORD, not another experiment

> **Per review §7: do NOT hunt for another witness separating `majority` from `intraframe-only`**
> unless a **new empirical property** can be articulated that one satisfies and the other violates.
> None is currently available — the two are behaviourally separated and normatively undecided.

**Written as** [`Z-DECISION-cross-frame-divergence.md`](Z-DECISION-cross-frame-divergence.md).

**And the ordering is binding:** that decision must be taken **before** either aggregation behaviour is
treated as part of the KnowledgeOS semantic model.
