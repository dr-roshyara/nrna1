# Stress Test of the Extraction Machinery

**2026-09-07.** Three concepts of **deliberately different kinds**, run against the schema the `K`
pilot produced. **Optimized for finding where the machinery breaks, not for covering the list.**

| case | kind tested | record |
|---|---|---|
| **`δ`** | a **function**, colliding with a **measured constant** | [`KOS-T-0002`](./elements/KOS-T-0002-delta.md) |
| **`≡_sem`** | a **relation**, not an object | [`KOS-T-0003`](./elements/KOS-T-0003-eq-sem.md) |
| **`Qualify`** | an **operation / process** | [`KOS-T-0004`](./elements/KOS-T-0004-Qualify.md) |

$$\boxed{\textbf{The machinery survived — with FOUR schema defects, all found by contact, none by design.}}$$

---

## 1. ⭐⭐⭐ Defect A — `Implementation` must distinguish **derived** from **stipulated**

**The single most important result of the stress test.** All three concepts are recorded by the
estate as **OPEN / undefined**, and all three have **running code with results**:

| element | the theory says | the code says |
|---|---|---|
| **`Qualify`** | **`G1` — named, typed, NO BODY** (three lanes, independently) | **four bodies**, substantive, exercised |
| **`≡_sem`** | **OPEN** (TODO Group D) | `eq_semantic` + an executed negative result |
| **`δ`** | *"no commit case; executed `K₁ is K₀`"* | `delta` **commits**: `return K(k.A\|{arg}, k.R),"ok"` |

**And two of the three declare their own status in the source:**

```python
# ---- Qualification (279/278: policy-parametric) ----          ← behaviour is an INPUT

def eq_semantic(x,y):
    # "same knowledge semantics" -- the ONLY reading the corpus offers is a NAME.
    # Any concrete procedure must choose what to discard. Decision 3 (254) is OPEN.
```

$$\boxed{\begin{array}{c}\textbf{All three have STIPULATED bodies and no DERIVED bodies.}\\ \textbf{The four-level } Implementation \textbf{ vocabulary cannot say that, and it must.}\end{array}}$$

**`[PROP]` schema change:** every `Implementation` value carries a qualifier —
**`derived`** *(the body follows from the theory)* · **`stipulated`** *(the body is chosen; the choice
is an input)* · **`unknown`**.

⛔ **Why this is not a small point.** Without it, the record for `Qualify` reads *"implemented"* and a
reader concludes `G1` is closed. **The hard rule runs both ways: code does not ratify a theory, and
code does not refute a theory gap.**

## 2. 🔴 Defect B — the 19 categories have **no `Operations`** and **no `Relations`**

`K` fit `Concepts` because `K` is an object. **All three stress cases are not objects:**

| element | what it is | category available |
|---|---|:--:|
| `δ` | an operation / transition function | 🔴 **none** |
| `Qualify` | an operation / process | 🔴 **none** |
| `≡_sem` | a **relation** | 🔴 **none** |

All three are filed as `Concepts` **under written protest**. ⚠️ **Filing an operation as a concept
collapses object and operation — the exact distinction the estate protects as
`Command ≠ Transformation` (§256.21) and as `Guidance ≠ Authority`.**

**`[PROP]` two categories added:** **`Operations`** and **`Relations`**.

`[INF]` **This is the charter's own prediction coming true** — *"a category the corpus demands and
this list lacks is itself a finding."* **Three of the first four elements needed one.** ⭐ **Which is
evidence the 19 were a reasonable hypothesis and not a schema: they broke where a schema would have
forced silence.**

## 3. 🔴 Defect C — `D-nn` needs a rule against **homonyms**

`δ` appears as a transition function **and** as `δ = 0.3099`, `FR-001`'s measured resolution constant.

**The `K` pilot never needed this rule** — all ten `K` definitions were genuinely of `K`. Here, a
naïve enumeration would have produced `D-05: δ = 0.3099`, **asserting that a number is a candidate
definition of the transition function.**

$$\boxed{\textbf{A homonym is not a definition. } D\text{-}nn \textbf{ enumerates definitions of ONE element.}}$$

**`[PROP]` rule:** a same-glyph occurrence denoting a different object is **routed to `H1`** (glyph
registry, archaeology) and recorded on the element as `homonyms:`, never as a `D-nn`.

## 4. 🔴 Defect D — MD-017's six relationships cannot express **misattribution**

`≡_sem`'s `D-01` and `D-03` stand in a relationship the taxonomy has no value for. `D-03` says
`D-01` **is correct and filed under the wrong name** — it belongs in `≈_obs`, not `≡_sem`.

| MD-017 value | why it fails |
|---|---|
| `refinement` | nothing is refined |
| `new_representation` | the representation is unchanged |
| `contradiction` | they do not contradict — one endorses the other |
| `unresolved_equivalence` | **used, with a written protest** — but the relationship *is* known |

**`[PROP]` new value: `misattribution`** — *A is correct as a definition of a different named
relation.* ⚠️ **Proposed to this project's own vocabulary; MD-017 belongs to 3MC and is not amended
by me.**

## 5. What did **not** break

| mechanism | held? |
|---|---|
| **enumerate-then-not-merge** | ✅ 4 + 3 + 4 definitions, **none merged** |
| **`unresolved_equivalence` as default** | ✅ carried every unevidenced pair, and **surfaced Defect D by being visibly wrong** |
| **four `Latest` sub-fields** | ✅ and **`governed decision` came back EMPTY for all four elements** |
| **scope-declared measurement** | ✅ no contamination after the `K` rule |
| **the four hard rules** | ✅ nothing adjudicated · **`Qualify`'s four bodies did not ratify `D-01`** · no canonical anything |
| **dependency propagation** | ✅ every element's dependencies are themselves unsettled |

## 6. The cross-cutting finding

$$\boxed{\begin{array}{c}\textbf{Four elements. Four EMPTY } governed\text{-}decision \textbf{ fields.}\\ K \cdot \delta \cdot \equiv_{sem} \cdot Qualify \textbf{ — the four most-cited objects in the theory —}\\ \textbf{have NEVER been decided, while three of them have running code.}\end{array}}$$

`[INF]` **That is a coherent picture, not a contradiction.** The estate built implementations to
*investigate* concepts it had deliberately not decided. **The extraction schema's job is to hold both
facts at once, and Defect A is the field that lets it.**

## 7. Verdict on the machinery

**It generalizes.** The same record shape accommodated an object, a function, a relation and an
operation — and where it could not, **it failed loudly rather than silently**: three protested
categories, one protested relationship, one refused `D-nn`.

$[REC]$ **Apply Defects A–D to the schema before the next increments** (`Σ`, `Π`, `Zero`, `ℐ`).
**Two of the four (`A`, `C`) would have produced wrong records if left unfixed** — `Qualify` reading
as *implemented*, and `δ = 0.3099` as a definition.

⚠️ **`[PROP]` on all four. None is applied. Amending this project's own schema is a small decision;
amending MD-017 is 3MC's.**

## 8. And one correction to my own prior reporting

I wrote *"`δ` has no commit case (executed: `K₁ is K₀`)"* in `NG-2` and in the forward plan **without
a qualifier.** The claim is real — it is about `Γ`, which is derived and not a component of `K`, so
**epistemic** commitment has nowhere to write. **But my phrasing let it read as "`δ` does nothing",
which `delta`'s `return K(k.A|{arg}, k.R),"ok"` refutes.** Corrected in `KOS-T-0002`; the `Γ` blocker
stands unchanged.
