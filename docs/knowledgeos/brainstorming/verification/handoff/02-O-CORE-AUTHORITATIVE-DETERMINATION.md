---
artifact: 02 · O-CORE-AUTHORITATIVE-DETERMINATION
status: **𝒪_core is DECOMPOSED-CLOSED but NOT MINIMAL — it must NOT be frozen as-is**
---

# 𝒪_core — Authoritative Determination

**I flagged in the previous turn that I had never verified `𝒪_core`. I have now read its sources.**
**The result contradicts the assumption that it is ready to freeze.**

## What Step 272A derives — the candidate universe (19 operations, 5 families)
```
O_S = {Assert, Retract, Supersede, Infer, Merge}                          semantic state
O_E = {Support, Refute, Qualify, Assess, DetectContradiction, Resolve}    evidence/epistemic
O_O = {Query, Compare, Identity, Equal}                                   observation/equivalence
O_H = {Replay, Trace}                                                     historical
O_G = {Authorize, Validate}                                               governance
O_sem = O_S ∪ O_E ∪ O_O ∪ O_H ∪ O_G
EXCLUDED: Serialize, Deserialize, Save, Load  — representation, not semantics
```
272A's own correction: *"`Assert` is a state transition, `Query` is an observation, `Authorize` is a
governance predicate, `Serialize` is a representation function. They cannot be treated as one homogeneous
algebra."*

## What Step 277 concludes — and this is decisive
```
𝒯_candidate = {Assert, Retract, Supersede, Merge, Split, LinkEvidence}
𝒯_candidate ≠ 𝒯_minimal
"𝒯_candidate is not yet proven minimal."
Classification: CLOSED          <- the DECOMPOSITION
Minimality: OPEN                <- the KERNEL
K-sufficiency: OPEN
Global theory closure: OPEN
```

> ## **AUTHORITATIVE STATUS: `𝒪_core` is CLOSED as a TAXONOMY and OPEN as a KERNEL.**
> **Freezing it now would freeze an explicitly unproven minimality claim.** Step 277 says so in its own
> boxed conclusions. **P0 "freeze canonical `𝒪_core`" must become "verify then freeze."**

## Two conflicts between `𝒯_candidate` and this stream's executed algebra

| Operation | 277 | This stream | Conflict |
|---|---|---|---|
| **`Split`** | in `𝒯_candidate`, "minimality unresolved" | **executed: LOSSY on `ℛ`** — `Split;Merge` does not recover relations | **`Split` cannot be a state primitive without a declared `ℛ` policy.** Unreconciled. |
| **`LinkEvidence`** | in `𝒯_candidate` | **absent** — `e` is a field of the assertion, fixed at creation | **`LinkEvidence` would MUTATE an assertion**, contradicting `ReplayAssertion`'s immutability invariant. Unreconciled. |
| **`relate`** | not listed separately | **present** — adds an `ℛ` edge of any type | 277's `Supersede` covers one relation type; the general case is unlisted. |

**Required action before freeze:** run the operation-necessity test
`o is primitive ⟺ ∃r ∈ R_mandatory : r ∉ Closure(𝒯_{-o})` — **277 states the criterion and does not
execute it.**
