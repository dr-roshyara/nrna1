# Phase 2B — Theory-Construction Engine

⛔ **EMPTY BY DESIGN. Not yet implemented.**

Per protocol §3A. This directory holds the **executable** Phase-2 engine and its
output; the expert (2A) reconstruction lands in the parent folder.

## The rule that governs everything here

> **Phase-2 software is a theory-construction and evidence-management system.**
> ⛔ **It is NOT an implementation of the final KnowledgeOS theory.**

Never hard-coded (`Q28`): a canonical object · a correct theory · a privileged
structure · a fixed set of bounded contexts · a central concept. The engine holds
rival candidates side by side and reports their evidence, derivations,
contradictions, failed tests and maturity. **It does not pick.**

## Sequence

```
protocol → schemas → engine → run on F0001-F0025
        → compare with 2A → improve BOTH → run again → freeze → scale
```

2A runs first; 2B is compared against it — not the reverse.

## What agreement is expected on

| Expect match | Expect divergence |
|---|---|
| structure · provenance · bookkeeping · consistency · reproducibility | identity decisions (§19A) · formalization staging · pruning of candidates |

Every divergence is classified in `../ENGINE-COMPARISON.md` and explained (`Q29`).
⛔ The expert side is not privileged: Step-1's `AUDIT-05` was a case where the
executable check was right and the specification was wrong.

**Freeze needs two runs, not one.**
