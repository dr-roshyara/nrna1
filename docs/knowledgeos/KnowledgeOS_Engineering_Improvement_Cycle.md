# The Engineering Improvement Cycle — a published domain process

| | |
|---|---|
| **Kind** | **PUBLISHED DOMAIN PROCESS** — a process *description*, deliberately **NOT** a bounded context, **NOT** an aggregate, **NOT** a new layer |
| **Authority** | Generated — never authoritative without human review |
| **Status** | CANDIDATE *(commissioned by the chief-architect review 2026-08-04, WP-Next)* |
| **Evidence base** | `KnowledgeOS_Recommendation_Lifecycle_Discovery.md` *(the empirical run records — RD-1..RD-7)* · the observation streams under `engineering/verification/observations/` |

---

## The process

```
Engineering Reality
      │  collectors observe (metrics · test presence · LCOM4)
      ▼
Observation            an instrument saw something — verdict-free
      │  deterministic rules evaluate
      ▼
Recommendation         advisory — the developer decides, always
      │  a developer decides: ACCEPTED · IGNORED · DEFERRED (+ rationale, reason code)
      ▼
Decision               IGNORED ends the lifecycle here — that is a COMPLETE path, not a gap
      │  commits accumulate (≥ MIN_COMMITS)
      ▼
Outcome                what measurably changed — raw record, assessment-free
      │  deterministic policy interprets
      ▼
Assessment             SUPPORTED · PARTIALLY_SUPPORTED · NOT_SUPPORTED · INCONCLUSIVE
      │  history accumulates
      ▼
Learning (FUTURE)      consumes assessments, never raw metric deltas — gated on evidence volume
```

## Process rules (all pre-existing — this document restates, it does not legislate)

1. **Every stage transition is a recorded event** with a timestamp and provenance; projections (funnel · lead times · Evidence Velocity · Loop Completion · effectiveness) are read-side interpretations and never write back.
2. **Recommendations are advisory; decisions belong to developers.** A recommendation with no decision is meaningful operational data.
3. **Projections never infer missing data.** Sub-week windows withhold rates; INCONCLUSIVE is a first-class verdict; IGNORED is closure, not a gap.
4. **The process is falsifiable end to end** — it can show that a rule never helps, that recommendations go undecided, or that KnowledgeOS itself changes nothing.

## What this document is NOT

- Not a bounded-context proposal. The concepts here live today as **application-level workflow objects + read-side projections**.
- Not a model change. Nothing in the domain changed when this was written.

## The staged DDD decision (owned by evidence, not by this document)

If roughly six months of operation give these concepts **lifecycle · invariants · versioning · policies · effectiveness · history · analytics**, DDD will indicate a bounded context of their own. If they remain orchestration around observations, they remain an application service. **The recorded watch criteria — not preference — decide.**

---

*Traceability: WP-Next (Recommendation Loop Completion), chief-architect review 2026-08-04 · Discovery Freeze v1+v2 respected — this describes an existing process from existing records; no concept minted.*
