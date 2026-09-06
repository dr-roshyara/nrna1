# 02 — Distinguishability Proof
**`exec/test_distinguishability.py` → `OUT-DISTINGUISHABILITY.txt`**

| ID | State | Observed under Repair B | Distinguishable |
|---|---|---|---|
| M1 | Not Asked | `('NotAsked','-')` | **✅** |
| M2 | Asked + Absent | `('Asked','Absent')` | **✅** |
| M3 | Asked + Unknown | `('Asked','Unknown')` | **✅** |
| M4 | Supported | `('Asked','Supported')` | **✅** |
| M5 | Refuted | `('Asked','Refuted')` | **✅** |
| M6 | Conflicted | `('Asked','Conflicted')` | **✅** |
| M7 | Orphan | `is_orphan=True`, epistemic state **unchanged** | **✅ structurally** |

**Observed: 6/6 distinct epistemic tuples for M1–M6.**

## M7 is orthogonal, not a seventh epistemic value — executed
```
same assertion, epistemic state held fixed at ('Asked','Supported')
   no ℛ edge   -> is_orphan = True
   add ℛ edge  -> is_orphan = False
```
> **M7 varies while M4 is held constant.** Orphanhood is therefore a **structural/relational** predicate
> over `(𝒜,ℛ)`, exactly as 281.7/281.8 require, and **not** a value of `Σ`.
> **`is_orphan(K,a) ⟺ ∄(f,t,ty) ∈ ℛ : a.id ∈ {f,t}`** — derivable from `K` as it already stands, with **no
> extension.** This closes T-2 without any addition to the theory.

## The Step 280 failure, re-checked
```
'not asked' = ('NotAsked','-')   vs   'absent' = ('Asked','Absent')   DISTINCT
```
**Critical Failure #7 no longer fires.**
