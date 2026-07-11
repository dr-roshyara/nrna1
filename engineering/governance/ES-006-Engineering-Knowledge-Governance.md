# ES-006 — Engineering Knowledge Governance

**Status:** PROPOSED · part of the [Standards Index](STANDARDS_INDEX.md)
**Purpose:** how ENGINEERING knowledge is harvested, promoted, and retired — the lifecycle of the platform's own learning.
**Scope:** engineering knowledge only (patterns, evidence, research artifacts, standards candidates). **Explicitly OUT of scope: Project Knowledge** — a separate bounded context; its standards (PKS-class) arrive only after its pilot and qualification. This scoping prevents ES-006 from slowly absorbing project concepts.
**Authority:** Decision Authority (ARB).
**Qualification Method:** promotion-ladder audits (does every promoted item carry qualification evidence?) + evidence-register integrity checks.
**Supersedes:** the MEMORY-resident text of the knowledge research freeze (now hosted here).
**Decision authority (compliance):** Human + AI — the AI evaluates, governance decides (see the Decision Authority & Verification Matrix in the Standards Index).
**Related Standards:** ES-001 (burden of proof) · ES-003 (the qualification step of the ladder) · ES-005 (research-tier placement).

## Hosted rules (canonical here; previously MEMORY-only or board-resident)

**ES-006.1 — The Promotion Ladder** *(ARB, refined 2026-07-11)*:

```text
Research → Pilot → Qualification → Engineering Standard → Stable Engineering Capability
```

Qualification sits between research and engineering — nothing is promoted because it is a good idea; everything is promoted because operational evidence demonstrated necessity (the burden-of-proof rule, R-37, applied to promotion). **Even an architecture must earn its existence** — "no reference architecture needed" is a SUCCESS outcome of a qualification. Maturity vocabulary: Evidence = proven · Pattern = proven · Capability = strong hypothesis · Principle = research question.

**ES-006.2 — The Knowledge Research Freeze** *(ARB 2026-07-11 — a milestone, not a rule)*. The RQ-002 knowledge theory (General Knowledge Constitution + Project Knowledge Strategic Model, `docs/implementation/`) is complete-enough-to-be-falsified and frozen: no theoretical refinement; changes only from pilot/operational evidence. Hierarchy: Constitution → Engineering Platform → Project Knowledge → Pilot Evidence — **no Level 5 exists; do not invent one.**

**ES-006.3 — Harvest Discipline** *(consolidated from the harvest program rulings)*. External knowledge enters as pattern cards with source provenance (`../knowledge/patterns/` + `sources/`); evaluation is pattern-by-pattern at two levels (provider mechanism vs engineering pattern); the Pattern Evidence Register tracks the four frozen convergence metrics (independent sources · contradictions · implementation evidence · promotion status) — **no composite scores**. Research dossiers are input-only: never architecture until promoted through ES-006.1.

**ES-006.4 — The Harvest Question** *(ARB 2026-07-11 — the objective is knowledge discovery, never rule extraction)*. The standing engineering question at completion of project work (EP-02 / retrospective) is:

> **"Did this work produce reusable engineering knowledge?"**

It is a **question, not a mandate**: most tickets honestly answer **No, and No is a fully valid outcome** — continue work, harvest nothing. The forbidden framing is *"can we create a new rule?"* — asking for rules makes people find rules. Only a **Yes** proceeds:

```text
Project Work → Observe → DetermineReusePotential ── No ──► continue work (the common case)
                                   │ Yes
                                   ▼
                        DetermineArtifactType   (pattern card · guide · qualification improvement ·
                                   │             candidate standard · research — or, on reflection, nothing)
                                   ▼
                        ES-006.1 Promotion Ladder → Qualification → Engineering
```

The platform seeks **reusable engineering knowledge** (never "AI rules" — the platform is provider- and AI-neutral); a standard is only one possible destination, and the right representation emerges from the evidence (consistent with the constitutional telos: knowledge exists to satisfy knowledge needs; documentation is merely one possible representation). Decisions are cataloged in the [Engineering Decision Model](../architecture/reference/Engineering_Decision_Model.md).

## Registered (pointers)

| Item | Home |
|---|---|
| Pattern cards EPC-001..018 + Pattern Evidence Register | `../knowledge/patterns/engineering_pattern_cards_agent_skills.md` (+ siblings) |
| RQ-002 research corpus (Constitution, taxonomy, meta-model, strategic model, assembly research) | `docs/implementation/RQ-002_*` + `Project_Knowledge_Strategic_Model.md` — research tier, project-side by ES-005.3 |
| EKP (incumbent project-knowledge governance) | `docs/knowledge/` — **disposition PENDING ARB** (metadata model aligned; consumption model falsified by E-1) |
| Retrospective inbox (deferred concepts + triggers) | `.claude/plans/AIP-iteration-1-construction.md` (frozen until the retrospective) |
