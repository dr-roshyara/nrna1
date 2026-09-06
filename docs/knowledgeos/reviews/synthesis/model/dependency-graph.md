# 2B-4 · Logical Dependency Graph (conceptual, pre-implementation)

**Tested against the corpus, per the instruction's specific queries.**

```
                      KNOWER
              (purpose · frame · Ideal-State ownership)
                         │  owns/authorizes
        ┌────────────────┼─────────────────────────────┐
        ▼                ▼                             │
   G (goal)         IdealState ──derives──► EC         │
        │                        (025e)     │          │
        ▼                                   ▼          │
  SOURCE OBSERVATION ──► SEMANTIC OBSERVATION          │   (Option 3 ⟦READ⟧)
        │                        │                     │
        ▼                        ▼                     │
   EVIDENCE ──(dependency resolution FIRST, EXP-01)──► AGGREGATION
        │                                              │
        ▼                                              │
  DETERMINATION / WARRANT  (candidate host: step-008)  │
        │                                              │
        ▼                                              │
   KnowledgeState K_t ──► Zero(K,G,EC) = Z_t           │
                                 │                     │
                                 ▼                     │
                       PROPOSAL (Lord, 025g)           │
                                 │                     │
                                 ▼                     │
                  DECISION + AUTHORIZATION (Sārathi, 025h)
                                 │        ▲
                                 ▼        │ Knower authority (C-014)
                              ACTION ─────┘
                                 │
                                 ▼
                        new observation (loop)

  VALIDATION: cross-cutting — evidence layer (EXP-01) · kernel (Step 50) · system/repo (109–124)
  GOVERNANCE: cross-cutting — 025f algebra · Step 104 · the Step-121 gap finding
```

## The instruction's specific queries, answered

| Query | Supported? | Evidence |
|---|---|---|
| `evidence → determination` | **YES (weakly)** — R1 states the direction; the formal hosting is the unverified step-008 mapping | 234405; OQ-03 |
| `determination → proposal` | **YES via K_t/Z_t** — Lord consumes the determined state, not raw evidence | 025g signature ⟦READ⟧ |
| `proposal → decision` | **YES (explicit)** | 025h ⟦READ⟧ |
| `decision → action` | **YES (explicit)** — with authorization interposed | 025h, 025z ⟦TITLE⟧ |
| `purpose/context precedes determination` | **YES (structural)** — `Zero` cannot be computed without `G` and `EC` (⟦C⟧ *"Zero cannot be computed from the goal alone"* — EC required); EC derives from the Knower-owned Ideal State | 025d/025e/Q18 ⟦READ⟧ |
| `validation operates across the entire chain` | **YES** — three distinct validation loci at three levels | EXP-01 · 050 · 109–124 |

⚠ Every arrow is **conceptual and evidence-cited**; none is an implementation commitment. The graph is
a *reconstruction of what the corpus supports*, not a design.
