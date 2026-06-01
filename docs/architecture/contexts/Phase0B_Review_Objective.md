# Phase 0B Architecture Review Board — Review Objective

**Context:** Evidence Context DD.4 Aggregate Discovery (Complete)  
**Scope:** Decide whether Phase 1 Discovery Instrumentation should proceed  
**Decision Authority:** Architecture Review Board  
**Timeline:** Single review session  

---

## Primary Question

> **Have we learned enough about the domain to justify instrumentation?**

This is **not** a project management question ("Can we start coding?").

This is an **architecture question** ("Does discovery evidence support the next phase?").

---

## What This Review Decides

### Five Questions This Review Will Answer

1. **Is Evidence Context a real bounded context?** (Not just an audit table)
2. **Are the context boundaries defensible?** (Can they survive challenge?)
3. **Are the aggregate hypotheses worthy of observation?** (What would falsify them?)
4. **Are the core invariants strong enough?** (Do they protect constitutional integrity?)
5. **Is Phase 1 correctly scoped?** (Instrumentation only, not implementation?)

### ❌ Will NOT Be Decided

- **Aggregate implementations** (still hypotheses; DD.5 validates them)
- **Persistence schema** (deferred until aggregates confirmed)
- **Repository contracts** (depends on aggregate discovery)
- **Verification architecture** (future context; only requirements documented)
- **Event sourcing strategy** (persistence decision, not domain decision)
- **Replay certification design** (Replay Context scope, not Evidence)

**This distinction is critical.** Approval of this review does not finalize architecture. It authorizes observation and learning.

---

## The Five ARB Decisions

### Decision 1: Is Evidence a Real Bounded Context?

**Question:** Does Evidence Context have:
- [ ] Its own mission (not just technical audit)?
- [ ] Its own ubiquitous language (distinct from Evaluation/Legitimacy)?
- [ ] Its own authority boundaries (what it owns vs. doesn't own)?
- [ ] Its own invariants (not borrowed from other contexts)?

**Artifacts to Review:**
- EvidenceContext.md, Sections 1-3 (Mission, Language, Authority)
- EvidenceAggregateDiscovery.md, Step 4 (Authority Chain)

**Review Goal:** Determine whether Evidence Context has sufficient depth and authority to justify a bounded context (not merely an audit log).

---

### Decision 2: Are the Boundaries Defensible?

**Current Model:**
```
Observation → Evidence → Evaluation → Legitimacy → Governance
```

**ARB Challenge Questions:**
- Why isn't Evaluation inside Evidence? (Answer: Interpretation ≠ Preservation)
- Why isn't Legitimacy inside Evaluation? (Answer: Authority ≠ Interpretation)
- Why isn't Verification inside Evidence? (Answer: Independent verification ≠ evidence capture)
- Why isn't Replay inside Evidence? (Answer: Validation ≠ preservation; Replay consumes Evidence)

**Artifacts to Review:**
- EvidenceContext.md, Section 9 (Relationships)
- EvidenceAggregateDiscovery.md, Step 4 (Authority Chain)
- Phase0B_ARB_ReviewFramework.md, Krishna Lens (Strategy questions)

**Review Goal:** Determine whether the five-context chain (Observation → Evidence → Evaluation → Legitimacy → Governance) can survive architectural challenge and whether future contexts are correctly deferred.

---

### Decision 3: Are Aggregate Hypotheses Mature Enough?

**Current Leading Hypothesis:**
```
A frozen evidence preservation consistency boundary
may exist.

It is currently named ConstitutionalEvidenceSnapshot,
but that name and role are subject to validation.
```

**ARB Must Ask:**
- What would falsify this hypothesis? (Answer: Scenarios showing evidence isn't frozen atomically)
- Which DD.5 scenarios test this most? (Answer: SC-06 Suspicious Activity, SC-07 Replay)
- What evidence supports it now? (Answer: EVI-1, I-1, I-5 all point to snapshot immutability)
- Is it worthy of Phase 1 observation? (Answer: Yes; capture snapshots and test against invariants)

**Artifacts to Review:**
- EvidenceAggregateDiscovery.md, Hypothesis A & B (aggregate candidates)
- EvidenceInvariantOwnershipMatrix.md (which hypotheses own which invariants)
- EvidenceScenarioCatalog.md (which scenarios exercise snapshot behavior)

**Review Goal:** Determine whether the aggregate hypothesis (frozen evidence preservation boundary) is reasonable enough to test empirically, and identify which DD.5 scenarios will most aggressively test it.

---

### Decision 4: Are Core Invariants Strong Enough?

**Constitutional-Level Invariants (Shani Lens):**

| Invariant | Strength | Enforcement | ARB Question |
|-----------|----------|------------|--------------|
| **EVI-1** (Evidence frozen) | Constitutional | Aggregate immutability | Can evidence ever be re-derived or re-fetched? |
| **EVI-5** (No indirect re-id) | Constitutional | Ingestion ACL + temporal fuzzing | Can timestamp + device + region still identify voter? |
| **I-1** (Immutable after publication) | Constitutional | Sealed aggregate | Is there ANY path to post-publication mutation? |
| **VR-4** (Verification independent) | Constitutional | Context separation | Can verification authority be coerced by governance? |

**Artifacts to Review:**
- EvidenceInvariantOwnershipMatrix.md (all 18 invariants, confidence levels)
- EvidenceContext.md, Section 5 (Invariants I-1..I-8 and enforcement)
- EvidenceEventTaxonomy.md, Section 4 (Privacy Scrutiny & EVI-5 hardening)

**Review Goal:** Determine whether the four constitutional-level invariants (EVI-1, EVI-5, I-1, VR-4) are sufficient to protect the evidence lifecycle, and whether enforcement mechanisms are structural or require Phase 1 hardening.

**Critical Focus:** Temporal fuzzing strategy for EVI-5 requires explicit ARB approval before Phase 1 implementation.

---

### Decision 5: Is Phase 1 Correctly Scoped?

**Phase 1 Scope:**
```
✅ ALLOWED:
  - captured_domain_events table (discovery instrumentation, not domain model)
  - CapturedDomainEvent (data carrier, not domain entity)
  - EvidenceCaptureAdapter (observes real events)
  - Tests verifying EVI-5 sanitization

❌ NOT ALLOWED:
  - Domain layer aggregates (hypotheses only, not implemented)
  - Repositories (deferred to DD.5)
  - Laravel domain models (no Domain/ folder until aggregates confirmed)
  - Verification Context (future; only requirements documented)
  - Final persistence schema (follows from aggregate discovery)
```

**Artifacts to Review:**
- Phase0B_ARB_ReviewFramework.md, Krishna Lens (Scope questions)
- Plan file: read-and-understand-what-playful-lampson.md (Phase 1 deliverables)
- EvidenceInvariantOwnershipMatrix.md (which invariants enforce Phase 1)

**Review Goal:** Confirm Phase 1 scope is limited to discovery instrumentation (captured_domain_events table, EVI-5 sanitization, observation adapters) and explicitly rejects aggregate implementation, repositories, and domain models.

**Important Distinction:** `captured_domain_events` is observation infrastructure, NOT domain persistence.

---

## ARB Decision Voting

For each decision, ARB votes:

| Decision | Approved | Approved w/ Conditions | Rework | Notes |
|----------|----------|------------------------|--------|-------|
| 1. Real BC? | ☐ | ☐ | ☐ | Evidence Context depth and authority |
| 2. Defensible Boundaries? | ☐ | ☐ | ☐ | Five-context chain resilience |
| 3. Mature Hypotheses? | ☐ | ☐ | ☐ | Testability and falsifiability |
| 4. Strong Invariants? | ☐ | ☐ | ☐ | Constitutional-level protection |
| 5. Correct Phase 1 Scope? | ☐ | ☐ | ☐ | Instrumentation vs. implementation boundary |

**ARB Consensus:** After voting on all five decisions, ARB selects one of the three possible verdicts (Approved / Approved With Conditions / Rework Required).

### Possible ARB Outcomes

**Option A: Approved**
- All five decisions pass without conditions
- Phase 1 immediately proceeds with full scope

**Option B: Approved With Conditions** (most likely)
- Phase 1 proceeds with specific conditions:
  1. ARB explicit approval of temporal fuzzing strategy (EVI-5) before implementation
  2. Phase 1 must include tests verifying EVI-5 payload sanitization works
  3. Monthly checkpoints on DD.5 scenario saturation progress
  4. Confirm use of `captured_domain_events` naming (not `evidence_capture`) to prevent scope confusion

**Option C: Rework Required**
- One or more of the five decisions fails
- Phase 0A rework required before Phase 1 can proceed
- Specific blockers documented for remediation

---

## What Approval Does NOT Mean

This review does **not** approve:

- ❌ Aggregate implementation (ConstitutionalEvidenceSnapshot is hypothesis)
- ❌ Persistence layer (deferred to post-DD.5)
- ❌ Repository pattern (deferred to post-DD.5)
- ❌ Evaluation Context separation (may or may not happen)
- ❌ Verification architecture (future context)
- ❌ Event sourcing strategy (persistence decision)

Approval means: **"The domain has been learned enough to justify observation."**

Not: **"The domain design is final."**

---

## Success Criteria for Phase 1

If the ARB approves Phase 1, it must be successful by these measurable criteria:

### Phase 1 Succeeds When

✅ **Real Events Captured**
- At least 3+ complete election cycles run through captured_domain_events table
- Event structures stabilize (no new event types for 2+ cycles)

✅ **EVI-5 Sanitization Works**
- Payload review shows zero user_id, email, or raw IP address
- Temporal fuzzing (minute-level granularity) is consistently applied
- No field combination could reconstruct voter identity

✅ **Observation Patterns Emerge**
- Event frequency, timing, and distribution data available for analysis
- Data patterns inform DD.5 scenario design
- Real election behavior validates Phase 0A assumptions

✅ **Aggregate Hypotheses Tested**
- Hypotheses (ConstitutionalEvidenceSnapshot, etc.) are stronger OR falsified
- At least one hypothesis requires significant revision
- DD.5 can proceed with empirical data, not pure speculation

✅ **At Least One DD.4 Assumption Validated or Disproven**
- Example: "Evidence is frozen at evaluation" — test by checking if captured data matches
- Example: "Observation/Evaluation separation works" — test by analyzing captured event language
- Validates that Phase 0A thinking was sound (or corrects it)

### Phase 1 Exit Criteria

Phase 1 completes when:

1. All success criteria above are met
2. Captured data has been reviewed and documented
3. DD.5 Scenario Analysis can begin with empirical evidence
4. Team has confidence in aggregate hypotheses (validated or refined)

---

## Post-Review Actions

### If Approved (or Approved With Conditions)

1. **Phase 1 Begins:**
   - Build captured_domain_events table
   - Wire EvidenceCaptureAdapter to observed events
   - Implement EVI-5 payload sanitization
   - Write tests for invariant enforcement

2. **Monthly Checkpoints:**
   - Track DD.5 scenario saturation
   - Monitor captured event structures for stability
   - Prepare for hypothesis validation

3. **After Phase 1 (~4 weeks):**
   - Execute DD.5 Scenario Analysis
   - Test aggregate hypotheses against real data
   - Validate invariant enforcement

### If Rework Required

1. **Identify blockers:** Which decision failed? Why?
2. **Rework Phase 0A:** Address the failure
3. **Resubmit to ARB:** Phase 1 remains blocked until re-approval

---

## Review Preparation Checklist

**Before ARB meeting starts:**

- [ ] All five Phase 0A artifacts available and current
- [ ] Five ARB decisions are clear (above)
- [ ] Preliminary architect position documented
- [ ] ARB members understand: this is about learning readiness, not final decisions
- [ ] Timeboxing: allocation ~2-3 hours for thorough review

**During review:**

- [ ] Work through each of the five decisions in order
- [ ] Challenge hypotheses; do not accept on faith
- [ ] Pay special attention to Shani lens (invariants)
- [ ] Reach consensus on phase 1 conditions (if any)

**After review:**

- [ ] Document ARB verdict and conditions
- [ ] Distribute to team
- [ ] Proceed with Phase 1 (if approved)

---

## Questions This Review Answers

| Question | Decided By | Outcome |
|----------|-----------|---------|
| Is Evidence Context real? | Ganesha Lens | Yes |
| Are boundaries defensible? | Krishna Lens | Yes, pending DD.5 test |
| Can we observe without implementing? | All Lenses | Yes |
| Are invariants protected? | Shani Lens | Mostly; EVI-5 needs hardening |
| Should Phase 1 proceed? | All Lenses Combined | Yes, with conditions |

---

## Final Reminder

> **This review is about justifying observation, not finalizing architecture.**
>
> If the ARB approves this review, it means:
>
> ✅ We understand the domain well enough to build discovery instrumentation
> ✅ The hypotheses are reasonable enough to test empirically
> ✅ Phase 1 observation will generate data to validate or falsify those hypotheses
> ✅ We are NOT committing to the current design; we are committing to learning
>
> That is exactly where a healthy DDD team should be at this stage.
