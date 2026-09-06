# 09 — Closure Dimension Matrix

**`FC ≠ CC ≠ EC ≠ GC`. The invalid inferences `FC ∧ CC ⇒ EC` and `FC ∧ CC ⇒ GC` are explicitly blocked.**

| Construct | Formal | Computational | Empirical | Governance |
|---|---|---|---|---|
| **K** | **CLOSED** `[F]` | **CLOSED** `[E]` | **L5** `[R]` 37 docs | not claimed |
| **ℛ** | **CLOSED** | **CLOSED** | **L5** typed edges in the real graph | not claimed |
| **Σ** | **CLOSED** | **CLOSED** | **NOT OBSERVABLE** | not claimed |
| **Q_t** | **CLOSED** F17/F18 | **CLOSED** 10/10 | **NOT OBSERVABLE** — EKP has no inquiry register | not claimed |
| **O_core** | **CLOSED** | **CLOSED** | partial | not claimed |
| **Identity** | **CLOSED** | **CLOSED** *(after C-NEW fix)* | **L5** `knowledge_id` | not claimed |
| **Equality** | **CLOSED** | **CLOSED** | **L5** byte-identical ×2 | not claimed |
| **Evidence** | **CLOSED** | **CLOSED** | **NOT OBSERVABLE** | not claimed |
| **Policy** | **CLOSED** | **CLOSED** | **L5 partial** — lint is a policy evaluator | **NOT CLAIMED** |
| **Authority** | **CLOSED** | **CLOSED** formal | **L5 partial** — enum only | **NOT CLAIMED** |
| **Authorization** | **CLOSED** | **CLOSED** formal; **runtime ABSENT** | NOT OBSERVABLE | **NOT CLAIMED** |
| **T** | **CLOSED** | **CLOSED** | NOT OBSERVABLE | not claimed |
| **History** | **CLOSED** | **CLOSED** | L1 — git only | not claimed |
| **Provenance** | **CLOSED** | **CLOSED** | NOT OBSERVABLE | not claimed |
| **Lineage** | **CLOSED** | **CLOSED** | **L5** `derived_from` edges | not claimed |
| **Replay** | **CLOSED** | **CLOSED** | NOT OBSERVABLE | not claimed |
| **Measurement** | **model CLOSED** | **executor ABSENT** | NOT OBSERVABLE | not claimed |
| **Missingness** | **CLOSED** (Step 281) | **CLOSED** 7/7 | NOT OBSERVABLE | not claimed |

## Column verdicts
```
FC = TRUE        every construct is formally closed; 0 theory-critical gaps
CC = MOSTLY TRUE 3 open: Authorize runtime, measurement executor, C-NEW harness fix
EC = FALSE       8/24 at L5; 15 constructs NOT OBSERVABLE; Critical Failure #7 repaired but
                 the second Step-280 reason is untouched
GC = NOT CLAIMED no ratification has occurred; authority is recorded, never granted
```

> **A red empirical cell is not a red theoretical cell.** Fifteen constructs are unobservable **because
> the EKP does not implement them**, not because the theory cannot define them. Every one of those
> fifteen passes TC-1 through TC-8.
