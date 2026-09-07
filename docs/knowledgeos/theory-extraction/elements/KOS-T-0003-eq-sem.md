# `KOS-T-0003` — `≡_sem`

**`[EXP]` extraction record · stress case: *a relation, not an object*.**
**Adjudicates nothing.**

| | |
|---|---|
| **Category** | 🔴 **NO FITTING CATEGORY.** `≡_sem` is a **relation**; the 19 have no `Relations`. Provisionally `Concepts` **under protest** |
| **Status** | `[UN]` · `status_chain: candidate` · Grounding **mixed** |

## Definitions

| ID | definition | source | implementation |
|---|---|---|---|
| **`D-01`** | `K_1 ≡_sem^{Q,Γ,𝒪} K_2` iff **determinations match** for all `q∈Q`, `o∈𝒪` | CLOSURE-4 | ⭐ ships a tester: `"""CLOSURE-4: Observational Semantic Equivalence Tester."""` |
| **`D-02`** | one slot of **seven**: `𝔎 = (K, =_str, ≡_sem, ≈_obs, SameId, ≡_H, ≡_P)` | `261.20`/`261.25` | ⭐ **`result-produced-on-it`** — `eq_struct`, `eq_semantic`, `eq_obs`, `eq_prov` in `research/exec/e_equality.py` |
| **`D-03`** | *the reviewer's correction:* `D-01` is really **`≈_{Q,Γ,𝒪}`, contextual observational equivalence** — *"two representations can produce the same answers for the selected queries while differing in other observations"* | senior-mathematician review §8 | — |

## ⭐ The stress result: `D-01 ~ D-03` is a relationship the K schema could not express

`D-03` does not compete with `D-01`. **It says `D-01` is correct and mis-named** — it belongs in the
`≈_obs` slot of `D-02`'s seven, not the `≡_sem` slot.

$$\boxed{\textbf{MD-017's six relationships cannot say "correct, but filed under the wrong name".}}$$

**Nearest available:** `refinement` — **wrong**, nothing is refined. **`new_representation`** — wrong,
the representation is unchanged. **⇒ recorded as `unresolved_equivalence` with a written protest.**
**New relationship value proposed** in `03` §4: **`misattribution`**.

## ⭐ And the code declares its own stipulation

`e_equality.py`, verbatim in the body:

```python
def eq_semantic(x,y):
    # "same knowledge semantics" -- the ONLY reading the corpus offers is a NAME.
    # Any concrete procedure must choose what to discard. Decision 3 (254) is OPEN.
```

$$\boxed{\textbf{The implementation states that it is a STIPULATION and names the open decision it presupposes.}}$$

**Executed result on it:** *"semantic ⇒ structural? **FAILS** → inclusion is **STRICT**"* — a
**genuine negative result about `≡_sem`**, produced on a stipulated body.

## `Latest` · dependencies · open questions

**mention** 2026-09-06 · **implementation** 2026-08-31 · **refinement** the reviewer's renaming,
2026-09-02 · **governed decision** 🔴 **`never`** *(applicable · searched `governance/` · no instance found)* *(CLOSURE-4 declared itself `RATIFIED`; two reviews
rejected that; no act exists)*.
**Depends on:** `K` · **`Decision 3` (`Π ∈ ≡?`) — OPEN, and the code says so** · `Q`, `Γ`, `𝒪`.
**Open:** adopt `D-01` as `≈_obs` and leave `≡_sem` open? · are `D-02`'s seven relations exhaustive?

---

# Schema v2 revalidation — **appended 2026-09-07, v1 text above unchanged**

`kind:` **`relation`** · `Category` (v1) `Concepts` **under protest — protest preserved**.

## Dispositions (Defect C) — **3, unchanged**

| disposition | occurrence |
|---|---|
| **1 · definitions** | `D-01`, `D-02`, `D-03` |
| **2 · separate candidates** | `=_str` · `≈_obs` · `SameId` · `≡_H` · `≡_P` — **the siblings in `D-02`'s 7-tuple are distinct relations**, IDs reserved on extraction *(reclassified `R-6`)* |
| **relationship** | `D-01 ~ D-03` = **`misattribution`** (local value; MD-017 untouched) |

## Implementation (Defect A) — ⭐ **the cleanest `stipulated` in the estate**

`D-02` — `result-produced-on-it` · **`stipulated`, and the code says so:**

```python
# "same knowledge semantics" -- the ONLY reading the corpus offers is a NAME.
# Any concrete procedure must choose what to discard. Decision 3 (254) is OPEN.
```

`selection: stipulated`. **Its executed result — *semantic ⇒ structural FAILS, inclusion is STRICT* —
is a genuine negative finding produced ON a stipulated body, and it does not close `CR-4`.**
