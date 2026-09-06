# `KR-COMP-2026-09`
# Composition, Frame Qualification and Standing Aggregation

**Commissioned as a COUPLED system**, not a rule beauty-contest:
`Evidence combination + frame qualification + standing aggregation`.
**Baseline** v1.2 **unchanged** · no v1.3 · `app/` untouched · **nothing adopted** ·
deterministic, no seed. **Code** `kos12/comp.py` · **Runner** `run_comp.py` ·
**Results** `results/comp/*.json` (5).

**Predecessor:** `KR-CONTR-FDE-2026-09` — `E-FDE-5` established that composition and frame
qualification are coupled. This experiment tests the coupling directly.

---

## 1. Design — a triple, not a rule

The object under test is

```
( aggregation rule ,  frame qualifier φ ,  status policy )
```

swept over the **full cross-product — 5 × 6 × 3 = 90 combinations.** **No rule was selected in
advance**; rules were enumerated first and witnesses second, as `KR-CONTR-EVAL` §21.1 requires.

| dimension | values |
|---|---|
| **rule** | `union` · `majority` · `last-wins` · `strict` · `intraframe-only` |
| **φ** — which features must AGREE for two opposed items to be genuinely opposed | `∅` · `{time}` · `{context}` · `{layer}` · `{time,context}` · `{time,context,layer}` |
| **status policy** | `filter` · `keep` · `demote` |

**Making the status policy a swept parameter was deliberate** — whether supersession belongs *to*
composition or *before* it is itself a design question, and §5 shows the answer.

**Pipeline:** `evidence → [status policy] → [partition by φ] → [within-frame standing] → [aggregate]`.

**The five criteria are the commission's, verbatim**, each a predicate with witnesses:
C1 preserve genuine conflict · C2 supersession is not conflict · C3 preserve boundary distinctions ·
C4 no false opposition · C5 remain explicit about the frame.

---

## 2. Result

> ### **18 of 90 combinations satisfy all five criteria.**
> ### **Every single one has `φ ⊇ {time, context}`.**

| dimension | pass rate |
|---|---|
| **φ = `∅`** | **0 / 15** |
| **φ = `{time}`** | **0 / 15** |
| **φ = `{context}`** | **0 / 15** |
| **φ = `{layer}`** | **0 / 15** |
| **φ = `{time, context}`** | **9 / 15** |
| **φ = `{time, context, layer}`** | **9 / 15** |

| rule | pass rate | | status policy | pass rate |
|---|---|---|---|---|
| `union` | **0 / 18** | | `filter` | 6 / 30 |
| `strict` | **0 / 18** | | `keep` | 6 / 30 |
| `majority` | 6 / 18 | | `demote` | 6 / 30 |
| `last-wins` | 6 / 18 | | | |
| `intraframe-only` | 6 / 18 | | | |

---

## 3. The frame is load-bearing; the rule is underdetermined

> `[EXP]` **`E-FDE-5` is CONFIRMED and SHARPENED. Composition and frame qualification are coupled —
> and the frame is the load-bearing half.**

**You cannot choose the rule before φ**: the rule ranking changes with φ (2 distinct rankings).
**But once `φ ⊇ {time, context}`, three rules tie at 5/5** — `majority`, `last-wins` and
`intraframe-only` are **indistinguishable under these criteria**.

> **The criteria do not select a composition rule. They select a FRAME QUALIFIER.**
>
> This inverts the shape of the original question. `KR-COMP` was expected to answer *"which
> composition rule?"* — it answers *"which frame qualifier"*, and reports the rule as **still open**.

**Why the surviving rules agree:** with a frame qualifier in force, **conflict is detected WITHIN a
frame**, and the aggregation rule only decides what happens **across** frames. The witnesses place all
genuine contradictions inside a single frame, so the across-frame policy never gets to matter. **A
suite that separated the rules would need a witness with genuine conflict in one frame and divergence
across others** — this one has none, and that is a limitation of the suite, not a result.

---

## 4. `φ` is non-additive — neither feature suffices alone

> `[EXP]` **`{time}` alone: 0/15. `{context}` alone: 0/15. `{time, context}`: 9/15.**

Each feature is needed by a different witness — `TemporalConflict` needs `time`, `ContextConflict`
needs `context` — so **the adequacy of φ is not a property of its individual features.** Adding
`layer` on top changes nothing (9/15 either way): **`layer` is not required by any tested witness.**

---

## 5. Supersession is subsumed by temporal separation

> `[EXP]` **The status policy is completely irrelevant: `filter`, `keep` and `demote` all score
> 6/30.** Not "roughly equal" — **identical.**

**The reason is structural.** Once `time ∈ φ`, superseded evidence sits in a **different frame** from
what supersedes it, regardless of how status is handled. The frame qualifier already does the work.

> **Supersession is therefore not an independent mechanism in the tested space — it is a special case
> of temporal separation.** `[EXP]`, scoped to these witnesses.
>
> **Consequence for the design:** a system that implements a frame qualifier over `time` does **not**
> additionally need a supersession rule for this purpose. Whether it needs one for *other* purposes —
> retirement, lifecycle — is untouched and remains `[OPEN]`.

---

## 6. Two rules are excluded, for opposite reasons

| rule | fails | why |
|---|---|---|
| **`union`** | **C3, C5** | it takes any positive and any negative **across** frames, so it reports frame-separated cases as conflict and **collapses `TemporalConflict` and `ContextConflict` onto `DirectContradiction`**. It ignores the frame it was given |
| **`strict`** | **C1** | it maps conflict to "no support", **destroying the genuine positive/negative conflict** the representation exists to carry |

> **This resolves the apparent tension with `KR-CONTR-FDE` §7**, which found that *"only `union`
> produces a conflicting Standing."* Correct — **but only in the absence of a frame.** `union` is the
> only rule that produces conflict *without* a frame, and it is **excluded once a frame exists**. The
> surviving rules produce conflict **within** frames.

---

## 7. Robustness — the one criterion I had to operationalize is not carrying the result

**C5 (*"remain explicit about the evaluation frame"*) is the only criterion the commission stated
conceptually rather than operationally.** I rendered it as *the frame declaration must have
consequences*, with a clause making `φ = ∅` fail by stipulation. **That clause could have manufactured
the headline**, so the sweep was re-run with **C5 dropped entirely**:

| | with C5 | **C1–C4 only** |
|---|---|---|
| passing | 18 / 90 | **18 / 90** |
| φ that can pass | `{time,context}`, `{time,context,layer}` | **identical** |
| rules that can pass | majority, last-wins, intraframe-only | **identical** |
| `φ = ∅` can pass | no | **no** |
| `union` can pass | no | **no** |

> **The verdict is unchanged. C5 contributes nothing to the exclusion.** `φ = ∅` and `union` are
> excluded by **C3** on their own merits — witnesses: `TemporalConflict ≡ DirectContradiction` and
> `ContextConflict ≡ DirectContradiction`. **The stipulation was not doing the work.**

*(An earlier version of C5 ignored the φ it was passed and tested rule-level φ-sensitivity instead.
That let **45 of 90** pass and was vacuous. Corrected before any figure above was taken.)*

---

## 8. What this settles

| | class |
|---|---|
| a composition mechanism CAN satisfy all five criteria simultaneously | `[EXP]` |
| **every satisfying combination has `φ ⊇ {time, context}`** | `[EXP]` |
| **φ is non-additive** — neither `time` nor `context` alone suffices | `[EXP]` |
| **the rule is underdetermined once φ is adequate** — 3 rules tie | `[EXP]` |
| **the status policy is irrelevant; supersession is subsumed by temporal separation** | `[EXP]` |
| `union` collapses frame-separated cases onto contradiction | `[NEG]` |
| `strict` destroys genuine conflict | `[NEG]` |
| **the criteria select a FRAME QUALIFIER, not a composition rule** | `[EXP]` |

## 9. What this does NOT settle

- **No composition rule is selected.** Three remain, and this suite cannot separate them.
- **`φ` is not settled.** `{time, context}` is the smallest adequate qualifier **over the tested
  witnesses**; a different witness set could demand more. **Choosing φ is a decision.**
- **`ℛ_req` is not settled** — and it continues to set everything (fourth independent occurrence).
- **`Contr` is still not defined.** This experiment says what a *composition* must do; it does not
  define the contradiction relation.
- **`Zero` is untouched.**
- **`layer` is not shown unnecessary** — only that no tested witness requires it.
- **Nothing is adopted. Theory v1.2 unchanged. Kernel NOT SELECTABLE.**

## 10. Recommended next

> `[PROP]` **A witness that separates the three surviving rules** — genuine conflict inside one frame
> **and** divergence across others. Until such a witness exists, *"which composition rule?"* is not an
> answerable question, and asking it would produce an arbitrary choice dressed as a result.

**Then, and separately:** the `φ` decision and the `ℛ_req` decision. **Both are decisions, not
experiments** — the fourth and fifth items to reach that category, after factivity, `M3≅M4` and `𝓑`.
