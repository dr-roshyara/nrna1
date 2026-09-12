# knowledgeos-sim — KR-SIM-2026-09-02  `[EXP]`

Simulation of **KnowledgeOS Theory v1.1** (= Theory v1.0 `DEF-1..33 / AX-1..7 / THM-1..10 / I1..I9`
plus the v1.1 correction `E_t` ≠ `K_t`, `K_t = Γ(E_t,Q,C,EC) ⊆ E_t`).

**NOT** canonical architecture · **NOT** production implementation · **NOT** a final kernel ·
**NOT** a proven theory · **NOT** a validated ontology. A simulation *of the theory*.

```
python3 run_experiment.py     # ~20 s, no dependencies; writes results/*.json
```

| Module | Role |
|---|---|
| `kos/types.py` | §25 type table (26 types) + notation-collision audit |
| `kos/world.py` | latent reality; `World.truth` is **evaluator-only** (oracle independence, §24) |
| `kos/inquiry.py` | `Q`, `S^epi`, `EC`, `I_t`, `Req`, `Sat`, `Δ_t`, `Zero`, `Adequate` |
| `kos/state.py` | `E_t`; `Γ` knowledge attribution; four-way unknown taxonomy |
| `kos/transitions.py` | Acquire · Qualify · Interpret · OpenHypothesisSpace · Assess · Determine · Reject · Accept · Revise · Supersede · Propose · Decide · Authorize · Act |
| `kos/scenarios.py` | §5 families A–J |
| `kos/properties.py` | §21 P1–P20, each flagged `definitional` or not |
| `kos/adversarial.py` | §20 adversarial cases |
| `kos/factivity.py` | the factivity impossibility witness |
| `kos/audits.py` | §23 smuggling · §24 oracle independence · §25 types · §26 circularity |
| `kos/randomized.py` | §21–22, 10 000 trials, guard activation + conditional rates |

Write-up: `docs/knowledgeos/research/theory-v1.1-simulation/`.
Deterministic: scenario seed `20260902`; randomized seeds `1, 7, 13, 101, 2718` × 2 000.
