# DECISION RECORD
# What does cross-frame divergence mean in KnowledgeOS evaluation?

**Status** **`[DEFERRED]`** — **BLOCKED by [`DECISION-02`](DECISION-02-semantic-status-of-the-frame.md).**
This lane supplies evidence, options and consequences; **it does not adjudicate.**

> ## ⛔ DEFERRED — do not decide `majority` vs `intraframe-only` yet
>
> **Governance ruling, 2026-09-02.** This record is **not withdrawn** — its evidence stands and is the
> input to the decision. But the choice **must not be taken now**, for two reasons:
>
> 1. **It is formulated at the wrong level.** Choosing an aggregation algorithm now would make a
>    **hidden decision about the ontology of frames** while appearing to decide an algorithm.
> 2. **`DECISION-01`'s `C7` instance cannot even be applied yet.** Whether refining a timestamp is a
>    *non-evidential* change depends on whether frames are part of the evidence — **so the `C7`
>    objection to `majority` is CONTINGENT** on [`DECISION-02`](DECISION-02-semantic-status-of-the-frame.md).
>
> **Ratified meanwhile:** [`DECISION-01`](DECISION-01-non-evidential-invariance.md) — *semantic
> evaluation must be invariant under non-evidential variation*; `C6` and `C7` are **instances**.
>
> **Read §8 as superseded.** The rest stands.
**Commissioned by** the `KR-COMP-SEP` review §7: *"The correct next artifact is a decision record, not
another experiment."*
**Baseline** v1.2 unchanged · nothing adopted · **binding ordering: this decision precedes treating
either aggregation behaviour as part of the KnowledgeOS semantic model.**

---

# 1. The question

Two commitments concerning `p` sit in **different frames** under `φ = {time, context}` — one supports
`p`, the other supports `¬p`, and **neither frame is internally contradictory.**

> **What is the resulting Standing?**

`KR-COMP-SEP` reduced five enumerated rules to two. **They give different answers, and both are
coherent.**

# 2. Why no experiment settles it

`W2` separates them **behaviourally**. It cannot say which the system **ought** to do. The disagreement
is about the **meaning** of cross-frame divergence, and meaning is not measured.

**Sixth item to reach this category**, after factivity, `M3≅M4`, `𝓑`, `φ` and `ℛ_req`.

---

# 3. The two alternatives

## 3.1 `majority` — cross-frame divergence is **aggregated**

```
frames vote;  the weight of evidence across frames determines standing
W2 (2 positive frames, 1 negative)  →  positive-support
```

**Semantic commitment:** frames carry **voting weight**, and that weight is **equal per frame**.

> **Unresolved assumption, stated by the review and unresolved by any experiment:**
> **why should two frames supporting `p` outweigh one frame supporting `¬p`?**
> **Nothing tested establishes equal frame weight.**

## 3.2 `intraframe-only` — cross-frame divergence is **refused**

```
a claim supported in one frame and denied in another has NO frame-free standing
W2  →  unsupported (0,0)
```

**Semantic commitment:** standing is **frame-relative**, and there is no frame-free aggregate to
report when frames disagree.

---

# 4. Evidence bearing on the choice

## 4.1 `C7` — frame-refinement invariance · **NEW, and it is asymmetric**

The review permitted a further witness **only if a new empirical property could be articulated that
one satisfies and the other violates.** One could be, a priori:

> **`C7`** Refining the frame partition — recording a frame feature at **finer resolution**, without
> adding, removing or altering **any** evidence — must not change the verdict.

**Construction:** the *same three evidence items*; the two positives either share a timestamp or carry
distinct ones. **Nothing about the evidence differs — only the recording resolution of `time`.**

| rule | coarse (2 frames) | fine (3 frames) | invariant |
|---|---|---|---|
| **`majority`** | **unsupported** | **positive-support** | **✘** |
| **`intraframe-only`** | unsupported | unsupported | **✔** |
| *(`union`, `strict`, `last-wins`)* | — | — | ✔ |

> `[EXP]` **`majority` reports a different verdict for identical evidence, purely because the clock
> was read more finely.**

**And this is not a fixable defect — it is constitutive.** A frame is created by *any* difference in a
frame feature, so **frame count is an artefact of recording resolution.** Any rule that *counts*
frames inherits that artefact. **You cannot have cross-frame aggregation without frame individuation
becoming an input.**

> **The `majority` cost is therefore the price of the "aggregate" option itself, not a flaw in one
> implementation of it.**

## 4.2 The shape of this defect is already familiar

**`last-wins` was eliminated for depending on something that is not the evidence** — enumeration
order. **`C7` shows `majority` depends on something that is not the evidence** — timestamp resolution.

> ### This is the crux of the decision.
> **If `C6` (order-invariance) is accepted as a criterion, then `C7` is a criterion of the same kind,
> and accepting one while rejecting the other requires a stated reason.**
>
> The decision about `majority` vs `intraframe-only` is therefore **coupled** to a prior decision:
> **does the programme accept "invariance under non-evidential variation" as a class of criterion?**
> `C6` and `C7` are both `[PROP]`, both this lane's, **both unratified.**

---

# 5. Costs — concrete, both sides

| | `majority` | `intraframe-only` |
|---|---|---|
| **frame weight** | assumes **equal weight per frame**, unestablished | **asserts there is NO legitimate cross-frame operation** — itself a semantic commitment, not a neutral default |
| **the deeper objection** | ⚠️ **under Option A it makes REPRESENTATION GRANULARITY SEMANTICALLY OBSERVABLE** — stronger than "assumes equal weights". Under Option B it does not arise | — |
| **`C7`** | **violates** — verdict depends on recording resolution | satisfies |
| **boundary vocabulary** | no change needed | **requires a NEW `BoundaryCondition`** — see §5.1 |
| **determination** | yields a determinate standing in divergence cases | **blocks** determination there |
| **`Standing` collision** | none | emits `(0,0)`, identical to *no support* |

## 5.1 The `intraframe-only` cost is sharper than "a collision" — measured

Under `intraframe-only`, `W2` yields:

```
standing = unsupported (0,0)          boundary = (None, None)      ← "no deficiency"
```

> **That pair is incoherent: the boundary says nothing is wrong, the standing says there is no
> support.** It is distinguishable from genuine `NoEvidence` — which carries
> `(observation, unobserved)` — **only accidentally**, because one happens to have a boundary and the
> other happens not to.
>
> `[EXP]` **Choosing `intraframe-only` REQUIRES extending the boundary vocabulary** with a condition
> such as `cross-frame-divergence`. **Choosing `majority` does not.** This is a concrete, costed
> downstream consequence, not a stylistic preference.

---

# 6. Affected invariants

| | effect |
|---|---|
| `I1`–`I5` `Contr ≠ {unknown family}` | **unaffected** — both keep contradiction distinct |
| `I11` `Contr ≠ Satisfied` `[PROP]` | **unaffected** under both |
| **`E-FDE-2` / `E-FDE-3`** — `Standing` alone is insufficient | **`intraframe-only` STRENGTHENS both**: it produces a second, independent reason why the boundary component is load-bearing |
| **`C6`** `[PROP]` order-invariance | satisfied by both |
| **`C7`** `[PROP]` refinement-invariance | **violated by `majority`** |

---

# 7. Downstream consequences

| area | `majority` | `intraframe-only` |
|---|---|---|
| **`Zero`** | `Zero` consumes `(Standing, Boundary)` unchanged | **`Zero` must be able to read a new boundary condition**; until then a divergent state and an empty one are separated only accidentally (§5.1) |
| **`Determination`** | proceeds — divergence yields a determinate standing | **blocked in divergence cases.** Whether that is a defect or the correct refusal **is the decision** |
| **lifecycle / retirement** | unaffected — `KR-COMP` §5 showed supersession is subsumed by temporal separation and the status policy is irrelevant | unaffected, same reason |
| **kernel candidacy** | **none.** Composition is a semantic mechanism, derivable from the candidate powers; **`C-1`** binds — no mechanism enters the kernel because KnowledgeOS can use it | **none**, same |

> **Neither option has kernel implications. The kernel remains NOT SELECTABLE under both.**

---

# 8. What this lane observes — `[PROP]`, and it is not an adjudication

> ## ⛔ SUPERSEDED — this observation is WITHDRAWN.
>
> `[PROP]` ~~**`intraframe-only`**, on the `C7` ground alone.~~
>
> **Withdrawn for a reason this lane had not identified.** The argument treated *"non-evidential"* as
> settled, but the term **presupposes the frame ontology that `DECISION-02` has yet to fix.** Under
> Option B — frames as semantic contexts — **`majority` does not violate `C7` at all**, because the
> refinement test then compares two genuinely different evidential situations.
>
> **And the inference was too fast in a second way:** it converted *"aggregation is
> representation-sensitive"* into *"therefore aggregation is forbidden."* **That implication does not
> follow.** `intraframe-only` carries its own semantic commitment — *that there is no legitimate
> operation for resolving information across frames* — which is asserted, not established.
>
> **The `C7` measurement stands. The inference drawn from it did not.**

The original reasoning is retained below for the record, marked as superseded.

**The reasoning, and its limits.** The programme eliminated `last-wins` for depending on a
non-evidential input. `C7` shows `majority` does the same thing. **Treating the two differently would
require a reason, and this lane has not found one.** That is an argument from **consistency of
criteria**, not from evidence about cross-frame divergence — and the criterion it appeals to (`C7`) is
**itself `[PROP]` and unratified**, so the argument is exactly as strong as that criterion and no
stronger.

**Against `intraframe-only`:** it requires a new boundary condition, and it blocks determination in
divergence cases. **Both are real, and if the programme judges determination-blocking unacceptable,
`majority` is the coherent choice** — accepting that verdicts then depend on timestamp resolution, and
saying so openly.

**A third possibility is not excluded** (§10) — and is now developed in
[`DECISION-02` §5](DECISION-02-semantic-status-of-the-frame.md): **stop requiring the composition
operator to manufacture a frame-free `Standing` at all.** Both known costs arise from forcing a
two-bit frame-free answer out of a situation that does not have one.

---

# 9. What a decision must state

1. **Which semantics** — aggregate, or refuse.
2. **Whether `C6` and `C7` are ratified as criteria**, and if only one, on what ground.
3. **If `intraframe-only`:** the new `BoundaryCondition`, and whether `Zero` must consume it.
4. **If `majority`:** whether frame weight is equal, and if not, what determines it — and an explicit
   acknowledgement that verdicts vary with recording resolution.
5. **That the decision precedes** treating either behaviour as part of the semantic model.

---

# 10. ⚠️ Scope guard

> **The design space is not established to contain only these two operators.**
> What is established: `{majority, intraframe-only}` are the **surviving TESTED candidates** of five
> enumerated. Five were enumerated because five were thought of; **no exhaustiveness argument was made
> or is available.**
>
> **A third operator satisfying `C7` without blocking determination is not excluded by anything here**,
> and would be a legitimate response to this decision record rather than a departure from it.

---

**Theory v1.2 unchanged · no v1.3 · nothing adopted · `Contr` still undefined · `φ` and `ℛ_req` still
decisions · kernel NOT SELECTABLE.**
