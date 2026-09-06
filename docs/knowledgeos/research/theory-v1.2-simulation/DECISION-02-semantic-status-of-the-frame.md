# DECISION 02 — What is the semantic status of a frame?
## **`[DECISION REQUIRED]`** · blocks `Z-DECISION-cross-frame-divergence`

**This lane supplies the options and their consequences. It does not adjudicate.**
**Baseline** v1.2 unchanged · nothing adopted · kernel NOT SELECTABLE.

> ### The question, before any aggregation algorithm:
> ## **Does `φ` identify semantically meaningful evaluation frames, or does it merely organize evidence?**

---

# 1. Why this comes first

**Otherwise we make a hidden decision about the ontology of frames while appearing to decide an
aggregation algorithm.**

And it is not only a matter of ordering — **`DECISION-01` cannot be applied until this is settled**:
whether refining a timestamp is a *non-evidential* change depends entirely on whether frames are part
of the evidence. See `DECISION-01` §2.

# 2. The current `φ` wording is operationally useful and conceptually insufficient

`KR-COMP` describes `φ` as *"which features must agree for two opposed items to be genuinely
opposed."* **That is a detection criterion, not an ontology.** It does not answer **what a frame is
for**:

```
an equivalence class of evidence?        an evaluation context?
a semantic world?                        a temporal/contextual coordinate?
a partition used ONLY for contradiction detection?
an object that can itself carry evidential weight?
```

**These are different semantics with different consequences**, and the experiments do not distinguish
them — every result so far is compatible with all of them.

> ⚠️ **`φ = {time, context}` is therefore NOT promoted to a semantic primitive.** It is the smallest
> adequate qualifier **over the tested witnesses**, and nothing more.

---

# 3. The two options

## Option A — a frame is a representational partition

```
frame boundaries are REPRESENTATIONAL and cannot legitimately determine semantic standing
```

| | |
|---|---|
| **commits to** | how evidence is chunked is an artefact of recording, not a fact about the world |
| **consequence for `C7`** | **`majority` VIOLATES `DECISION-01`** — it counts representational boundaries |
| **consequence** | > **`majority` makes representation granularity semantically observable** |
| **what remains** | `intraframe-only`, or a third operator (§5) |

**This is the stronger objection to `majority`**, and it displaces the weaker one. The problem is not
merely *"it assumes equal frame weights"* — it is that **a quantity with no evidential status becomes
visible in the verdict.**

## Option B — a frame is a semantic context

```
frame boundaries are SEMANTICALLY MEANINGFUL
```

| | |
|---|---|
| **commits to** | frame individuation is **part of the evidential content** |
| **consequence for `C7`** | **`majority` does NOT violate `DECISION-01`** — the refinement test compares two genuinely different evidential situations |
| **what it then owes** | a semantics for **how frames relate** and **how their evidential weight is determined** |

> ### And under Option B the load-bearing constraint is:
> ```
>              frame count   ≠   evidential weight
> ```
> **unless explicitly decided.** Option B does not license `majority`; it only removes the `C7`
> objection. `majority` would still need a **stated basis** for `w(fᵢ) = 1`.

---

# 4. The decision procedure

| # | decision | status |
|---|---|---|
| **D1** | ratify evidence-set invariance | ✅ **`[DECIDED]`** — `DECISION-01` |
| **D2** | **define the semantic role of `φ`** — semantic frames, or evidence organization? | ⬅ **THIS RECORD. REQUIRED.** |
| **D3** | is cross-frame aggregation semantically legitimate? *(meaningful only after D2)* | blocked |
| **D4** | **if aggregation is legitimate, do NOT automatically choose `majority`** — a semantic basis for `w(f₁)…w(fₙ)`, and for `w(fᵢ)=1`, must be given. Otherwise "majority" is **count-of-representational-partitions**, not **weight-of-evidence** | blocked |
| **D5** | if aggregation is not legitimate, choose `intraframe-only` — and **explicitly accept** that cross-frame divergence yields no frame-free determination, **extending `Boundary` with an explicit divergence condition rather than letting `(0,0)` carry the meaning implicitly** | blocked |

---

# 5. A third option, deliberately kept open

**`intraframe-only` must not be chosen merely because `majority` violates `C7`.** That would convert

> *"aggregation is representation-sensitive"*

into

> *"therefore aggregation is forbidden"* — **and the implication does not follow.**

**The candidate shape worth considering** — `[PROP]`, **explicitly NOT proposed as architecture:**

```
        cross-frame divergence   →   EXPLICITLY UNRESOLVED

        Evaluation = ( frame-relative standings , divergence )

        …and a SEPARATE determination operation decides whether a
          frame-free conclusion is justified.
```

It would potentially preserve **all** of: evidence from every frame · frame-relative standing ·
explicit divergence · no arbitrary frame counting · no false identification with `NoEvidence` · and a
later route to determination.

> **The move is to stop requiring the composition operator to manufacture a frame-free `Standing` at
> all.** Both known costs come from forcing a two-bit frame-free answer out of a situation that does
> not have one.

**The design space is not established to contain only `majority` and `intraframe-only`** — those are
the surviving *tested* candidates of five enumerated, and no exhaustiveness argument exists.

---

# 6. What a decision on D2 must state

1. **Option A or B** — representational partition, or semantic context.
2. **If B:** what makes a frame a frame; how frames relate; and whether frame count may contribute to
   evidential weight at all.
3. **Whether `φ = {time, context}` is the frame vocabulary**, or only an adequate detection criterion
   over the tested witnesses.
4. **Whether `DECISION-01`'s `C7` instance bites on `majority`** — which follows from 1, and should be
   recorded as following, not decided separately.

**Nothing adopted · Theory v1.2 unchanged · `Contr` still undefined · kernel NOT SELECTABLE.**
