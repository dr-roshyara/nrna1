# `oq4-witness/` — `V2`: the `Adequacy ≠ Realization` Witness

**2026-09-07 · `OQ-4` · `[EXP]`** · per the approved plan Phase 3.

> ⛔ **Scope-bound and nothing more.** This decides **nothing** about the carrier question, does not
> select Carrier A or B, does not resolve the A/B seam, modifies no theory document, and promotes
> nothing to `[THM]`. Theory v1.2 FROZEN · kernel NOT SELECTED · nothing adjudicated.

---

## 0. The result

$$\boxed{\textbf{EXHIBITED — by two independent routes, on the first run, with no tuning.}}$$

| route | frame | `Ĥ(Q\|R)` | `N_viol` | `F` | |
|---|---|---:|---:|---:|:--:|
| **control** | published `(Q, C, O)` | **0.00000** | **0** | **1.00000** | the design defect, reproduced |
| **1 — weaker decoder** | `(Q, C, O_weak)` | **0.00000** | **0** | **0.52065** | ✅ **WITNESS** |
| **2 — binding constraint** | `(Q, C ∧ k\text{-anon}(2), O)` | **0.00000** | **0** | **0.57652** | ✅ **WITNESS** |

**`Theory 05 §3` recorded:** *"The separation is real … but this experiment could not exhibit it, and
**no experiment yet has**. That is the honest position."* Its `[REC]` named the two required designs.
**Both were built, both were declared before the run, and both produced a witness.**

## 1. Exactly what produced it — the full provenance

| | |
|---|---|
| **carrier** | `Rec(v, s, t, rank)` inside `Case(recs)` — `verification/zero-algebra/KR-REP-REDUCTION-2026-09/code/carrier.py`, **imported unmodified** |
| **representation level** | **`R5`**, fields `{v, s}` — the **only adequate level** in the published run |
| **chain** | `D → R5=T5(D) → R4=T4(R5) → R3=T3(R4) → R2=T2(R3)` |
| **inquiry `Q`** | `(argmax_source(D), decile(Σv))` — defined in `carrier.py` before any transformation exists |
| **`N_CASES`** | **40 000** |
| **seed** | **20260903** (identical to `run.py`'s `SEED_TRAIN`) |
| **`rank_convention`** | `ascending` (the published primary) |
| **`k`** | **2**, declared before the run |
| **records per case** | `N_REC = 3` · alphabets `\|V\|=12`, `\|S\|=3`, `\|T\|=8`, `\|TOTALS\|=124` |
| **implementation** | `oq4-witness/exec/oq4_witness.py`, importing `carrier.py`, `pipeline.py`, `metrics.py` — **nothing rebuilt** |
| **the cell** | `level = R5`, `Ĥ(Q\|R)=0`, `N_viol=0`, and `F = 0.52065` (route 1) / `F = 0.57652` (route 2) |

**Reproduce:** `python3 docs/knowledgeos/brainstorming/verification/gap-discovery/oq4-witness/exec/oq4_witness.py`
— deterministic, no dependencies; transcript `exec/OUT-oq4_witness.txt`, machine-readable
`exec/oq4_witness.json`.

## 2. Route 1 — a decoder weaker than the information

**`O_weak`, declared in the script before the run**, fixed in advance, reading only `R`:

```python
def O_weak(R):
    nums = R.numeric()
    return (R.recs[0].s, decile(sum(nums)))    # FIRST record's source, not the argmax
```

The published `O` takes an **argmax** over the numeric field; `O_weak` reads the **first record's
source**. It cannot see which record is largest. Its decile component is untouched.

| | |
|---|---:|
| `F_n` | **0.52065** |
| argmax component | **0.52065** ← where `O_weak` is weaker |
| decile component | **1.00000** ← unchanged **by construction** |

⭐ **The loss is attributable to exactly one component of `Q`, and the other is exactly 1.** `R5`
determines `Q`; **`O_weak` does not compute the determining function.** That is Theory 05's reason (1),
isolated.

## 3. Route 2 — a constraint that bites at an adequate level

`Theory 05` names the example itself: *"a `k`-anonymity floor."* Implemented as declared:
`C ∧ (|fiber(R)| ≥ k)`, `k = 2`.

| | |
|---|---:|
| cases blocked | **16 939**  (**42.35 %**) |
| `A_n` with k-anon | 0.57652 |
| `F_n` | **0.57652** |
| **singleton fibers at `R5`** | **16 939** of 26 826 |

$$\boxed{\textbf{blocked cases} = \textbf{16\,939} = \textbf{singleton fibers, exactly.}}$$

⭐ **The mechanism is exact, not statistical.** A singleton fiber is **maximally adequate** — its `Q`
is determined with certainty — and **maximally re-identifying** — `k`-anonymity forbids decoding it.
**The same property that makes the representation adequate is the property that makes it
impermissible to use.**

`[EXP]` This is Theory 05's reason (2): `Adequate ∧ ¬C(R) ⇒ not realizable, though the information is
present`. **Exhibited with `F = 1 − 16939/40000` to the digit.**

## 4. What is established, and what is not

| ✅ established `[EXP]` | 🔴 not established |
|---|---|
| the two predicates **come apart** on this carrier, at `R5`, under two independent frames | that they come apart on **any other** carrier — that is `OQ-3`, **not attempted** |
| the separation has **two independent mechanisms**, and each was isolated | any general **theorem**. `[EXP]`, never `[THM]` — a theorem needs a proof |
| **adequacy is independent of `O` and of `C`** — `Ĥ(Q\|R)=0` and `N_viol=0` were identical across all three frames | that `O_weak` or `k=2` are the *right* frame — they are **declared instruments**, not proposals |
| the control **reproduces** the published `F=1.0` at `R5` | the `[CONJ]` *Realization ⟹ Adequacy* — untouched here |

⚠️ **Adequacy remains population-dependent by construction** (`Theory 04`'s own `[DEFECT]`): it is a
property of `(T, Q, population)`. **This witness inherits that**, and any transfer must re-establish it.

⚠️ **No tuning was applied and none was needed.** Both frames were written into the script before
execution; the first run produced both witnesses. **Had neither produced one, the negative result and
the exhausted space would stand here in their place.**

## 5. One correction — to my own `V1` wording, not to `Theory 13`

`V1` (`carrier-options/`) states: *"no `(value, source, tag, time)` record exists in any `.py` in the
repository."* **Reading `carrier.py` for this experiment shows what `Theory 13` was paraphrasing:**

```python
@dataclass(frozen=True)
class Rec:
    v: Optional[int] = None    # magnitude
    s: str = "A"               # source
    t: Optional[int] = None    # timestamp
    rank: Optional[int] = None # within-case rank
```

$$\boxed{\textbf{Rec}(v,\ s,\ t,\ rank) \quad\text{is plainly what }\; r = (value,\ source,\ tag,\ time) \;\text{ paraphrases.}}$$

**My wording was literal and misleading, and I correct it:** the exact 4-tuple as written appears
nowhere, **but `Theory 13` was describing a real record, loosely.** `V1`'s *conclusion* is unaffected —
two implemented carriers, `OQ-1` is reconcile-or-ratify — and the `Item(...)` 7-field carrier I cited
is `KR-ZERO-ALGEBRA`'s, a **different** experiment's carrier from this one's `Rec`.

⚠️ **Which sharpens `V1` rather than weakening it: the zero-algebra estate itself runs on at least
two record types** — `Item(token, source, uncertainty, scope, polarity, node, edge_to)` and
`Rec(v, s, t, rank)`. **Recorded for Governance. `Theory 13` is not modified, and I do not call it
an error.**

## 6. Scope — stated as a limit, not a hedge

```
carrier              Rec(v,s,t,rank) / Case  — KR-REP-REDUCTION only
level                R5
inquiry              (argmax_source, decile(Σv))
population           N = 40 000, seed 20260903
records per case     N_REC = 3
rank convention      ascending
frames tested        (Q,C,O)  (Q,C,O_weak)  (Q, C∧k-anon(2), O)
```

**Nothing outside that box is claimed.** In particular `|D| ≤ 6` is **not** this experiment's bound —
it runs at `N_REC = 3` — and the representation ladder `R1…R4` is a **different question** from the
domain-size bound. `OQ-3` remains exactly where the plan puts it.
