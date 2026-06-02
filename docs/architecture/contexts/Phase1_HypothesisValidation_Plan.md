# Phase 1: Hypothesis Validation (Operational Plan)

**Status:** Proposed For ARB Decision  
**Purpose:** Reduce architectural uncertainty through empirical observation  
**Success Metric:** Confidence changed on at least one hypothesis, one architectural decision revisited  
**Exit Criteria:** Learning saturation, not calendar duration

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
| **H8** | Verification exhibits characteristics of an independent bounded context | Does verification deserve its own lifecycle, language, and invariants? |

---

## Section 3: Work Classification Gate (STEP 0)

Before any activity, classify the work:

| Classification | Use This Plan? | Use Different Path? |
|:---|:---|:---|
| **Type A:** Architectural Validation | ✅ Yes | No |
| **Type B:** Product Development | ❌ No | Standard development process |
| **Type C:** Bug Fix | ❌ No | Standard bug fix process |
| **Type D:** Technical Maintenance | ❌ No | Standard maintenance process |

**This plan applies ONLY to Type A work.** All other work bypasses this framework and uses standard development processes.

---

## Section 4: Allowed & Forbidden Activities

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

## Section 5: Observation Metrics

| Metric | What Changes If This Triggers? | Observation Method |
|:-------|:------|:---|
| **A: Unexpected Event Clusters** | Hypothesis H1–H5 (context coherence) | Monitor for co-dependent state changes across context boundaries during live elections |
| **B: Cross-Boundary Traffic** | Hypothesis H7 (Evaluation boundary) | Measure frequency/density of Evidence↔Evaluation synchronous calls |
| **C: Invariant Enforcement Difficulty** | Hypothesis H3 (invariant ownership) | Track complexity/latency spikes when validating I-1, EVI-5, VR-4 |
| **D: Architectural Surprises** | All hypotheses (scope discovery) | Log unmapped exceptions, transactional states, or event sequences not captured in Phase 0A |
| **E: Decision Sensitivity** | Hypothesis H4, H6 (boundary definition) | Identify if payload field changes threaten aggregate boundaries |

**Key Question for Each Metric:** What architectural decision could materially change if this metric triggers?

---

## Section 6: Decision Delta Template

After observation period, record:

| Hypothesis | Confidence Before | Observation | Confidence After | Resulting Decision |
|:---|:---|:---|:---|:---|
| H1 | Plausible | [Real data here] | [New confidence] | Stronger / Weaker / Rejected / Keep |
| H2 | Plausible | [Real data here] | [New confidence] | Stronger / Weaker / Rejected / Keep |
| (repeat for H3–H8) | | | | |

**Confidence Levels (Qualitative):**
- **Very Weak:** Hypothesis barely coherent; likely false
- **Weak:** Hypothesis has internal contradictions or gaps
- **Plausible:** Hypothesis makes sense; insufficient data to judge
- **Strong:** Evidence supports hypothesis; alternative explanations eliminated
- **Very Strong:** Hypothesis survived multiple falsification attempts

**Possible Decision Outcomes:**
- **Stronger:** Observation increases confidence level
- **Weaker:** Observation decreases confidence level
- **Rejected:** Observation falsifies hypothesis completely
- **Keep:** No material evidence either direction

---

## Section 7: Exit Criteria (Learning-Based)

**Phase 1 ends when:**

✅ At least one hypothesis confidence changed materially  
✅ At least one architectural decision was revisited  
✅ Decision Delta matrix completed  
✅ Additional observation generates no new architectural insights (learning saturation)  

**Phase 1 does NOT end when:**

❌ Calendar duration expires (e.g., 30 days)  
❌ Code is complete  
❌ Tables exist  
❌ Aggregates are designed  
❌ Repositories are implemented  

**Note:** Duration may be 2 weeks or 8 weeks—learning saturation determines exit, not calendar dates.

---

## Section 8: The Gating Question

Before any Phase 1 activity, ask:

```
What observation would materially change
an architectural decision?
```

**If answer exists:** Proceed.  
**If answer is "nothing":** STOP. This work is not justified.

---

## Timeline (Approximate; Driven by Learning Saturation)

**Day 1:** ARB decision recorded  
**Day 2:** This plan committed  
**Day 3:** Deploy minimal observation capture (`Infrastructure/Discovery/`)  
**Days 4–7:** Collect real domain events from first election cycle  
**Weeks 2+:** Continuous observation through subsequent election cycles  
**Exit Point:** When Decision Delta shows confidence changes on H1–H8 and no new architectural insights emerge  

Duration may range from 2–8 weeks depending on event frequency and learning pace.  

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
