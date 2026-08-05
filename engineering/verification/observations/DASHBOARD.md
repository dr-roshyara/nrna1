# Engineering Evidence Dashboard

> **PROJECTION — derived, non-authoritative. Regenerate at will:** `php scripts/observations/dashboard-renderer.php`
> Measured subject: **engineering behavior and outcomes — never platform activity.**

## Observation streams

- **Static metrics:** 3 snapshots · mean CBO: 3.98 · WARN classes: 17 · hotspots: 19 · latest commit: `f2ac054c8`
- **Test presence:** 1 observations · production-without-tests events: 0
- **LCOM4:** 2 runs · worst: Committee LCOM4=16 · CommitteeType LCOM4=14 · Member LCOM4=12
- **Operational evidence:** OE entries: 4

## Recommendations (advisory — the developer decides)

- issued: 10 · decided: 2 (accepted 2 · ignored 0 · deferred 0) · **open: 8**
- *acceptance is measured, never assumed — issuance counts alone are vanity*

## Recommendation Lifecycle (the KPI — where does the pipeline break?)

**issued 10 → decided 1 → outcomes 1 → assessments 1** *(unique recommendations per stage; drop-offs are findings, not failures — an organizational mirror)*

### Lead times (measurements only — sample sizes shown; no causal conclusions)

- recommendation to decision: **6.5h** (n=1)
- decision to outcome: **8.8h** (n=1)
- outcome to assessment: **0.1h** (n=1)

### Evidence Velocity (the operational KPI — completed recommendation cycles per week)

- completed cycles: 1 · observation window: 0.6 days · *rate withheld — window under 7 days is never extrapolated into a weekly rate*
- *a completed cycle = recommendation → decision → outcome → assessment; everything downstream, including learning, depends on this rate*

### Loop Completion (the bottleneck monitor — Evidence Velocity rises only when these gaps close)

- needs decision: **9**
- needs outcome: **0** *(ACCEPTED decisions only)*
- needs assessment: **0**
- complete: **1**
- closed by IGNORED decision: **0** *(lifecycle ends at the decision — no outcome expected)*
- deferred: **0** *(awaiting re-decision)*

## Recommendation Effectiveness (per rule)

| Rule | Issued | Accepted | Ignored | Deferred | SUPPORTED | PARTIALLY_SUPPORTED | NOT_SUPPORTED | INCONCLUSIVE |
|---|---:|---:|---:|---:|---:|---:|---:|---:|
| R1 | 1 | 1 | 0 | 0 | 0 | 0 | 0 | 1 |
| R5 | 9 | 0 | 0 | 0 | 0 | 0 | 0 | 0 |

## Outcome questions — the loop is measured HERE

| Question | Evidence | Fills from |
|---|---|---|
| Are recommendations accepted? | **NO DATA YET** | AI workflow log |
| Which recommendations are ignored? | **NO DATA YET** | AI workflow log |
| Do warnings change code (behavioral milestone)? | **NO DATA YET** | usage log (spike plan) |
| Which collectors never influence decisions? | **NO DATA YET** | usage log (spike plan) |
| Do recommendations prevent defects? | **NO DATA YET** | outcome tracking (Run 2 design) |
| Is delivery becoming faster? | **NO DATA YET** | trend across commits |
| Does engineering confidence increase? | **NO DATA YET** | workflow log (confidence field, staged) |
| Which governance rules actually matter? | **NO DATA YET** | OE register recurrence |

*Empty cells are deliberate: the questions stand visible until real work fills them. The dashboard reports and asks; it never judges.*
