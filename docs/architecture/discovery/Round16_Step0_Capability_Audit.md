# Round 16 Step 0 — Capability Audit

**Purpose:** Classify discovered capabilities by type. Separate business from technical, supporting, and workflow concerns.

**Status:** Approved with observations

**Date:** 2026-06-06

---

## Classification Summary

| Classification | Count | Should Drive Contexts? |
|---|---|---|
| Business Capability | 8 | YES |
| Domain Capability | 8 | YES |
| Supporting Capability | 1 | MAYBE |
| Technical Capability | 8 | NO |
| Workflow Activity | 5 | NO |

---

## Critical Finding

**Technical capabilities must NEVER drive bounded contexts.**

Items that were incorrectly discovered as capabilities but must not create context boundaries:

* Multi-Tenancy Isolation → Infrastructure
* Session Management → Infrastructure
* Permission Enforcement → Infrastructure
* Demo Mode Management → Testing/Infrastructure
* Voting Code Distribution → Implementation (contains domain behavior — requires review)
* Vote Collection (storage) → Infrastructure (contains domain behavior — requires review)

Preventing these from accidentally becoming bounded contexts was the primary objective of this audit.

**Status: ACHIEVED.**

---

## Provisional Classifications

### Business Capabilities

1. **Conduct Elections** ✓
2. **Register Voters** ✓
3. **Register Candidates** ✓
4. **Cast Vote** ✓
5. **Count Election Results** ✓
6. **Publish Results** ✓
7. **Audit Elections** ✓
8. **Verify My Vote** ✓

### Domain Capabilities

1. **Define Governance Rules** ✓
2. **Delegate Authority** ✓
3. **Verify Voter Eligibility** ✓
4. **Certify Election Results** ✓
5. **Challenge Eligibility** ✓
6. **Challenge Results** ✓
7. **Maintain Authority** (provisional)
8. **Enable Vote Anonymity** (provisional)

### Supporting Capabilities

1. **Verify Election Integrity** (audit support)

### Technical Capabilities (Infrastructure — Do Not Drive Contexts)

1. Multi-Tenancy Isolation
2. Session Management
3. Permission Enforcement
4. Demo Mode Management
5. Voting Code Distribution ← *Contains domain behavior; provisional*
6. Vote Collection (storage) ← *Contains domain behavior; provisional*
7. Audit Trail Maintenance ← *Split: Business Auditability + Technical Log Storage*

### Workflow Activities (Not Independent Capabilities)

1. Ballot Rendering
2. Ballot Preparation
3. Voting Workflow Management
4. Position and Post Definition
5. Vote Counting ← *Actually a Business Capability; reclassify*

---

## Observations for Future Refinement

### 1. Voting Code Distribution

**Current Classification:** Technical Capability

**Challenge:** May contain domain behavior. The business requirement is "Authorize eligible voter to participate." Voting codes may be the domain mechanism for granting voting rights.

**Status:** Provisional. Tactical DDD may reveal this is Domain Capability.

---

### 2. Vote Collection

**Current Classification:** Technical Capability

**Challenge:** Distinction between "Collect Vote" (domain) vs. "Store Vote" (technical) requires investigation.

**Status:** Provisional. May need to split into two capabilities.

---

### 3. Vote Counting

**Current Classification:** Workflow Activity (challenged)

**Challenge:** Vote Counting is explicitly a Business Capability. Stakeholders absolutely care about how votes are counted and when.

**Status:** Reclassify as Business Capability.

---

### 4. Audit Trail Maintenance

**Current Classification:** Supporting Capability (mixed)

**Challenge:** Appears to merge:
- "Provide Election Auditability" (Business Capability)
- "Store Audit Records" (Technical Capability)

**Status:** Provisional. Tactical DDD should separate these.

---

## DDD Governance Decision

**Question:** Does this document need another review cycle before bounded context discovery?

**Answer:** No.

**Rationale:**

The remaining disagreements are **classification refinements**, not **fundamental misunderstandings**.

The document has achieved its primary architectural objective:

```text
✓ Separated business from technical concerns
✓ Prevented technical capabilities from driving contexts
✓ Identified provisional domain capabilities
✓ Flagged uncertain classifications for investigation
```

The next architectural learning will come from **Bounded Context Discovery**, not from further capability classification.

---

## Approval Status

```text
Round16_Step0_Capability_Audit.md

Status:
✓ APPROVED

Confidence:
High

Conditions:
1. Classifications remain provisional
2. Tactical DDD may revise classifications
3. Bounded Context Discovery should use:
   - Business Capabilities (8)
   - Domain Capabilities (8)
   NOT Technical Capabilities or Workflow Activities
4. Technical capabilities must NEVER drive context boundaries
```

---

## Next Phase

**Move to:** Round16_Step1_Bounded_Context_Discovery_Workbook.md

**Using as inputs:**
- Business Capabilities (8)
- Domain Capabilities (8)
- Clean Capability List (16 business-focused items)

**Do NOT:**
- Create another audit
- Create another normalization document
- Create another review pass

**The audit has done its job. Discovery now begins.**

---

**STATUS: Audit Complete and Approved**

**NEXT: Bounded Context Discovery**
