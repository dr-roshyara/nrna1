# 07 — Policy / Knowledge Separation Result
**`exec/f16_f19_f20.py` → `OUT-F16-F19-F20.txt`** — **F16 PASS**

```
1. Policy governs T          EvaluatePolicy(P-infra v1) = PASS      P ∉ 𝒜   -> policy is NOT knowledge content
2. Knowledge ABOUT policy    assert (P-infra, PolicyStatus, authoritative) -> 'ok'   |𝒜|=1
3. It has full epistemics    Σ=('Supporting','Weak')   Π=HPA   id=…
4. Policy change as knowledge assert (P-infra, PolicyStatus, superseded);
                             relate(new, old, supersedes)   |𝒜|=2 |ℛ|=1  old RETAINED
```

| Required | Observed |
|---|---|
| `GovernancePolicy ∉ K` | **YES** — `P` is a `Policy` object, never an `Assertion` |
| `KnowledgeAboutPolicy ∈ K` | **YES** — two assertions with `Σ`, `Π`, `ℛ`, History |
| policy governs `T` without becoming content | **YES** |
| assertions about policy carry epistemic status | **YES** |
| policy changes become knowledge/events | **YES** — supersession relation, old retained |

> **The distinction holds because they are objects of different types in different bounded contexts.**
> A `Policy` has `Gates`, a `ValidityInterval` and a `ResolutionBehavior`. An `Assertion` about a policy
> has a proposition, evidence, context, validity and provenance. **Neither can be substituted for the
> other, and both are representable simultaneously.**

`Primary classification of the remaining G-P1 item: G (Governance)` — the *runtime* is absent, the
*separation* is proven.
