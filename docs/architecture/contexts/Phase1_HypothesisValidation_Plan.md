# Phase 1: Hypothesis Validation (Operational Plan)

**Status:** ARB Approved With Conditions (C1–C6)  
**Duration:** 30-day observation window  
**Purpose:** Reduce architectural uncertainty through empirical observation  
**Success Metric:** Confidence changed on at least one hypothesis, one architectural decision revisited

---

## Section 1: Purpose

Phase 1 exists to reduce architectural uncertainty by observing the running NRNA system.

Phase 1 does **not** exist to:
- Implement Evidence Context
- Design aggregates
- Create repositories
- Design persistence models
- Select technology
- Plan DD.8 tactical design

---

## Section 2: Hypotheses Under Observation

| ID | Hypothesis | Decision at Stake |
|:---|-----------|-------------------|
| **H1** | Evidence is a valid, independent bounded context | Should Evidence remain separate from Election administration? |
| **H2** | Evidence possesses an independent runtime lifecycle | Does Evidence mature on a different timeline than voting sessions? |
| **H3** | Evidence exclusively owns its documented invariants | Can Evidence context enforce all its rules without external validation? |
| **H4** | A stable evidence preservation boundary exists | Does `ConstitutionalEvidenceSnapshot` meaningfully contain frozen state? |
| **H5** | Evidence observations form distinct behavioral clusters | Do event patterns naturally separate audit generation from feature consumption? |
| **H6** | Historical data ownership belongs inside Evidence Context | Should archival proof live here or be distributed elsewhere? |
| **H7** | Evaluation is a clean, separate bounded context | Can evaluation be decoupled entirely from evidence preservation? |
| **H8** | Verification requires an eventual dedicated context | Does verification deserve its own lifecycle, language, and invariants? |

---

## Section 3: Allowed & Forbidden Activities

### Allowed

✅ Temporary observation capture (`Infrastructure/Discovery/`)  
✅ Event classification and observation  
✅ Boundary interaction measurement  
✅ Lifecycle analysis  
✅ Invariant ownership testing  
✅ Context interaction density analysis  

### Forbidden

❌ Aggregate root design  
❌ Repository implementation  
❌ Domain service creation  
❌ Production persistence models  
❌ Cryptographic design  
❌ Technology selection  
❌ DD.8 tactical planning  

---

## Section 4: Observation Metrics

| Metric | What Changes If This Triggers? | Observation Method |
|:-------|:------|:---|
| **A: Unexpected Event Clusters** | Hypothesis H1–H5 (context coherence) | Monitor for co-dependent state changes across context boundaries during live elections |
| **B: Cross-Boundary Traffic** | Hypothesis H7 (Evaluation boundary) | Measure frequency/density of Evidence↔Evaluation synchronous calls |
| **C: Invariant Enforcement Difficulty** | Hypothesis H3 (invariant ownership) | Track complexity/latency spikes when validating I-1, EVI-5, VR-4 |
| **D: Architectural Surprises** | All hypotheses (scope discovery) | Log unmapped exceptions, transactional states, or event sequences not captured in Phase 0A |
| **E: Decision Sensitivity** | Hypothesis H4, H6 (boundary definition) | Identify if payload field changes threaten aggregate boundaries |

**Key Question for Each Metric:** What architectural decision could materially change if this metric triggers?

---

## Section 5: Decision Delta Template

After observation period, record:

| Hypothesis | Confidence Before | Observation | Confidence After | Resulting Decision |
|:---|:---|:---|:---|:---|
| H1 | 60% | [Real data here] | [New confidence] | Stronger / Weaker / Rejected / Keep |
| H2 | 60% | [Real data here] | [New confidence] | Stronger / Weaker / Rejected / Keep |
| (repeat for H3–H8) | | | | |

Possible confidence changes:
- **Stronger:** Observation validates hypothesis
- **Weaker:** Observation creates doubt
- **Rejected:** Observation falsifies hypothesis completely
- **Keep:** No material change

---

## Section 6: Exit Criteria

**Phase 1 ends when:**

✅ At least one hypothesis confidence changed materially (±20%)  
✅ At least one architectural decision was revisited  
✅ Decision Delta matrix completed  
✅ Real events captured from at least 2–3 complete election cycles  

**Phase 1 does NOT end when:**

❌ Code is complete  
❌ Tables exist  
❌ Aggregates are designed  
❌ Repositories are implemented  
❌ Documentation is finished  

---

## Section 7: The Gating Question

Before any Phase 1 activity, ask:

```
What observation would materially change
an architectural decision?
```

**If answer exists:** Proceed.  
**If answer is "nothing":** STOP. This work is not justified.

---

## Timeline

**Day 1:** ARB decision recorded  
**Day 2:** This plan committed  
**Day 3:** Deploy minimal observation capture (`Infrastructure/Discovery/`)  
**Days 4–7:** Collect real domain events  
**Days 8–28:** Continuous observation  
**Day 30:** Close observation window, generate Decision Delta  

---

## Success Definition

Phase 1 succeeds when:

✅ We understand **why** the architecture needs to change (or why it doesn't)  
✅ Confidence on at least one hypothesis shifted materially  
✅ Next phase (DD.5 or DD.8) proceeds with empirical grounding  

Phase 1 fails when:

❌ We created more code without learning anything  
❌ All hypotheses remain unchanged  
❌ Observation was theater, not evidence  

---

**After this plan is committed, the next work is implementation of observation capture. No more documents. Learning comes from observing the running system.**
