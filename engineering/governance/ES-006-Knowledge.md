# ES-006 — Knowledge

**Status:** PROPOSED · **Owner:** Decision Authority · part of the [Standards Index](STANDARDS_INDEX.md).

## Hosted rules (canonical here; previously MEMORY-only or board-resident)

**ES-006.1 — The Promotion Ladder** *(ARB, refined 2026-07-11)*:

```text
Research → Pilot → Qualification → Engineering Standard → Stable Engineering Capability
```

Qualification sits between research and engineering — nothing is promoted because it is a good idea; everything is promoted because operational evidence demonstrated necessity (the burden-of-proof rule, R-37, applied to promotion). **Even an architecture must earn its existence** — "no reference architecture needed" is a SUCCESS outcome of a qualification. Maturity vocabulary: Evidence = proven · Pattern = proven · Capability = strong hypothesis · Principle = research question.

**ES-006.2 — The Knowledge Research Freeze** *(ARB 2026-07-11 — a milestone, not a rule)*. The RQ-002 knowledge theory (General Knowledge Constitution + Project Knowledge Strategic Model, `docs/implementation/`) is complete-enough-to-be-falsified and frozen: no theoretical refinement; changes only from pilot/operational evidence. Hierarchy: Constitution → Engineering Platform → Project Knowledge → Pilot Evidence — **no Level 5 exists; do not invent one.**

**ES-006.3 — Harvest Discipline** *(consolidated from the harvest program rulings)*. External knowledge enters as pattern cards with source provenance (`../knowledge/patterns/` + `sources/`); evaluation is pattern-by-pattern at two levels (provider mechanism vs engineering pattern); the Pattern Evidence Register tracks the four frozen convergence metrics (independent sources · contradictions · implementation evidence · promotion status) — **no composite scores**. Research dossiers are input-only: never architecture until promoted through ES-006.1.

## Registered (pointers)

| Item | Home |
|---|---|
| Pattern cards EPC-001..018 + Pattern Evidence Register | `../knowledge/patterns/engineering_pattern_cards_agent_skills.md` (+ siblings) |
| RQ-002 research corpus (Constitution, taxonomy, meta-model, strategic model, assembly research) | `docs/implementation/RQ-002_*` + `Project_Knowledge_Strategic_Model.md` — research tier, project-side by ES-005.3 |
| EKP (incumbent project-knowledge governance) | `docs/knowledge/` — **disposition PENDING ARB** (metadata model aligned; consumption model falsified by E-1) |
| Retrospective inbox (deferred concepts + triggers) | `.claude/plans/AIP-iteration-1-construction.md` (frozen until the retrospective) |
