---
name: evidence_context_dd4_architecture
description: "Evidence Context DD.4 architecture — discovery instrumentation approach, hypotheses instead of aggregates, scenario saturation exit criteria"
metadata: 
  node_type: memory
  type: project
  originSessionId: d5e01507-9e85-498a-bf95-02711af72d39
---

# Evidence Bounded Context — DD.4 Discovery Architecture (2026-06-01)

## Current Status
**Phase:** DD.4 Aggregate Discovery (in progress)  
**Next:** DD.5 Scenario Analysis (when scenario saturation criteria met)  
**Approval:** Chief Architect ✅ APPROVED WITH NON-BLOCKING RECOMMENDATIONS

## Core Architecture Decision

Evidence Context uses **discovery instrumentation** (Infrastructure/Discovery/) to observe real domain events before finalizing aggregate boundaries. No Domain layer code until DD.5 confirms ownership.

## Key Locked-In Decisions

| Decision | Status | Why |
|----------|--------|-----|
| Verification is separate future context | ✅ Final | Verification consumes evidence, doesn't own it |
| Evidence/Evaluation boundary strict | ✅ Final | Evidence = facts; Evaluation = interpretation |
| EVI-5: No indirect voter re-identification | ✅ Final | Blocks field combinations, not just user_id |
| Scenario saturation exit criteria | ✅ Final | Replaces arbitrary time gates |
| Discovery/ folder naming | ✅ Final | Prevents accidental architecture of temp tables |
| CapturedDomainEvent (not EvidenceRecord) | ✅ Final | Signals instrumentation, not domain entity |
| Hypotheses, not candidates | ✅ Final | Removes confirmation bias in discovery |

## What Exists vs What's Open

### Locked In (Won't Change)
- Observation Context (upstream, exists)
- Replay Context (exists, uses ConstitutionalEvidenceSnapshot as input)
- Verification requirements (VR-1..VR-5) as domain requirements
- Constitutional authority chain: Observation → Evidence → Evaluation → Legitimacy → Governance

### Open (Decided in DD.5)
- Is ConstitutionalEvidenceSnapshot an aggregate root?
- Is EvaluationEnvelope owned by Evidence or Evaluation Context?
- What are exact consistency boundaries?
- Does EvidenceTimeline need to be an aggregate?

## Phase 1 Code Footprint

**Total:** ~245 lines (all infrastructure, zero domain logic)

- Migration: `evidence_capture` table
- CapturedDomainEvent: data carrier (NOT domain entity)
- DiscoveryEventStore: Eloquent model
- EvidenceCaptureAdapter: configurable event map
- Tests: 4 tests (capture + EVI-5 verification)

**No Domain/ folder created. Domain modeling deferred to Phase 2+ (after DD.5).**

## Critical Reminder

> *The purpose of the instrumentation is to learn about the domain.*
> *The purpose of the domain is not to justify the instrumentation.*

Discovery is tool-mediated learning about consistency boundaries, not code-based implementation of what you think you know.

## ARB Recommendations (Non-Blocking)

1. Remove duplicate election_id from payload (only in aggregate_reference)
2. Add capture_version to schema for evolution tracking
3. Watch EvidenceCaptureAdapter growth (refactor to strategies when >20 entries)
4. Add "Limits of Discovery Instrumentation" section to docs
5. Create Scenario Catalog before DD.5
6. Create Invariant Ownership Matrix before DD.5

None are blocking. Can be done during Phase 1 or before DD.5.

## Exit Criteria for DD.4 → DD.5 Transition

Do NOT proceed to domain modeling until ALL met:

```
✓ Event saturation reached (3 elections, no new event structures)
✓ Scenario matrix complete (all dispute scenarios exercised)
✓ Invariant assignment complete (each invariant has owner)
✓ Consistency boundaries identified (what must be atomic)
✓ Evidence/Evaluation boundary validated (no logic leakage)
✓ Discovery data reviewed (real capture table data examined)
```

**Saturation-based, not time-based.** May take 3 weeks or 3 months.

## Related Memories

- [[phase1_constitutional_parity_strategy.md]] — Earlier constitutional parity work (Phase D context)
- [[phase2_semantic_modeling.md]] — Semantic modeling principles
- [[phase2_architecture_discipline.md]] — Architecture discipline patterns

## Implementation Plan Location

`claude/plans/read-and-understand-what-playful-lampson.md` — Full Phase 0 + Phase 1 breakdown with 8 detailed tasks
