# Authority Discovery Findings

**Date:** 2026-06-03  
**Source:** Round 6F.1 Authority Discovery (Q1-Q9 Matrix)  
**Status:** Intermediate findings; no conclusions yet  
**Next Phase:** Round 6F.2 Authority Stress Test (attempt to break candidate hypotheses)

---

## Summary of Investigation

Nine discovery questions (Q1-Q9) were investigated across five domains (Membership, Election, Appeal, Fraud, Governance). Each question was populated with concrete domain examples from stress tests and constitutional patterns.

Result: Authority appears complex enough to warrant stress testing before any architectural classification.

---

## Cross-Domain Patterns

Derived from observations across five domains. Each is a **candidate pattern**, not a conclusion.

### Candidate Pattern A: Authority Operates Within Defined Scope

**Observations:**
- Membership Authority: can decide membership status; cannot decide election results
- Election Authority: can certify counts; cannot revoke membership
- Appeal Authority: can overturn eligibility decisions; cannot investigate fraud directly
- Fraud Authority: can invalidate results; cannot change membership rules
- Governance Authority: can set rules; cannot directly approve individuals

**Pattern Statement:**
Observed: Authority appears to operate within a **bounded scope of decision**.

**Implications for 6F.2:**
Test whether Authority without scope boundary is still Authority.
Test whether scope-crossing creates conflict or delegation.

---

### Candidate Pattern B: Authority Appears to Require Recognition

**Observations:**
- Membership Committee: must be "recognized" as having authority (not self-claimed)
- Observers: must be "recognized" as legitimate (voters accept the count)
- Appeal Authority: must be "recognized" as legitimate (process seen as fair)
- Fraud Authority: must be "recognized" as honest (accusations believed)
- Governance: must be "recognized" as legitimate (rules accepted, not imposed)

**Pattern Statement:**
Observed: Authority appears to require **explicit recognition or acceptance** beyond mere claim.

**Conflict Embedded:**
This distinguishes "claimed authority" from "legitimate authority" — the critical gap identified in Q9.

---

### Candidate Pattern C: Authority Appears Temporal

**Observations:**
- Membership Committee: authority lasts for elected term; revocable on structural change
- Observers: authority lasts during counting; expires after certification
- Appeal Authority: authority lasts during appeal window; expires at deadline
- Fraud Authority: authority lasts throughout and post-election; can revoke even certified results
- Governance: authority lasts until superseded or constitutional change

**Pattern Statement:**
Observed: Authority appears to have **defined temporal boundaries** (start, duration, end, potential revocation).

**Implications for 6F.2:**
Test whether Authority can be unlimited in time.
Test whether decisions outlast the Authority that created them.

---

### Candidate Pattern D: Authority Appears Challengeable

**Observations:**
- Membership Committee: decisions can be appealed to Governance
- Observer count: disagreement triggers recount process
- Appeal Authority: prior decisions can be overturned
- Fraud Authority: accusations can be challenged; process reviewed
- Governance: decisions can be overridden by Constitution or higher Governance

**Pattern Statement:**
Observed: Authority decisions appear to be **challengeable or revisable** through defined processes.

**Implications for 6F.2:**
Test whether Authority without challenge mechanism is still Authority.
Test whether all challenges eventually exhaust (appeal final).

---

### Candidate Pattern E: Authority Requires Chain of Origin

**Observations:**
- Membership Committee: requires Governance appointment or constitutional definition
- Observers: require Governance designation or constitutional role
- Appeal Authority: requires Governance definition of process
- Fraud Authority: requires Governance or constitutional authorization
- Governance: requires Constitution or prior Governance (circular, or terminating in Constitution)

**Pattern Statement:**
Observed: Authority appears to require **traceable origin** to another Authority or to Governance/Constitution.

**Implications for 6F.2:**
Test whether self-authorizing Authority can exist.
Test whether infinite delegation chains are possible.

---

## Cross-Domain Conflicts

Conflicts that emerged across multiple domains. These are not errors; they signal deeper design questions.

### Conflict 1: Authority vs. Legitimacy

**Manifestation:**
- Q8 suggests Legitimacy requires Authority
- Q9 suggests Authority does NOT guarantee Legitimacy
- Example: Corruption Committee claims authority; nobody recognizes legitimacy

**DDD Implication:**
Authority and Legitimacy are **not equivalent concepts**.

**For 6F.2:**
This is the central stress point. Stress test: Authority Without Recognition.

---

### Conflict 2: Scope and Precedence

**Manifestation:**
- When Membership Authority (says "eligible") conflicts with Election Authority (says "check signature"), which wins?
- Governance rule: "HR is authority of record" — but who enforces?

**DDD Implication:**
Scope boundaries are real, but **precedence rules are needed to resolve boundary conflicts**.

**For 6F.2:**
Stress test: Competing Authorities with no precedence rule.

---

### Conflict 3: Delegation and Responsibility

**Manifestation:**
- A delegates to B; B delegates to C
- If decision is wrong, who is responsible? A? B? C?
- If C revokes decision, can B object?

**DDD Implication:**
Delegation creates **chains, not simple transfers**. Accountability is unclear.

**For 6F.2:**
Stress test: Infinite Authority Chain. Circular Authority. Self-Authorizing Authority.

---

### Conflict 4: Temporal Authority and Decision Persistence

**Manifestation:**
- Fraud Authority (temporal) revokes decision by Observer Authority (now expired)
- Is the revocation legitimate? Observer no longer has authority to defend decision

**DDD Implication:**
Decisions may **outlast** the Authority that created them. Creating new temporal complications.

**For 6F.2:**
Stress test: Revoked Authority's decisions. Temporal boundary crossing.

---

## Major Unknowns

Questions that cannot be answered within single domain; require stress testing or additional investigation.

### Unknown 1: Minimum Authority

Can Authority be **implicit** or **default**?

Example: "If nobody objects, approval is granted." Is silence an Authority?

Current hypothesis: Authority requires **explicit designation**.

**For 6F.2:** Authority Without Recognition stress test will help clarify.

---

### Unknown 2: Authority Hierarchy

Is there a **maximum Authority** that cannot be revoked?

Current observation: Every Authority has a revocation path (Governance, Constitution, higher Authority).

Question: Does the chain terminate? Is Constitution "final authority"?

**For 6F.2:** Infinite Authority Chain stress test.

---

### Unknown 3: Authority Without Governance

Can Authority exist in a **region or context** that Governance doesn't directly control?

Example: Regional Authority self-organizes; Governance has no knowledge.

Current hypothesis: Such Authority would be **contested or unrecognized** (back to Pattern B: recognition is required).

**For 6F.2:** Authority Without Governance stress test.

---

### Unknown 4: Legitimacy Creation

If Authority is **not sufficient** for Legitimacy (Q9), what **is** required?

Observations point to:
- Recognition/acceptance
- Procedural fairness
- Scope clarity
- Temporal clarity
- Constitutional alignment

Are all required? Or just some?

**For 6F.2:** This is deferred; 6F.2 will test what happens when each element is missing.

---

## Candidate Hypotheses

Based on patterns and conflicts. **Not yet proven. To be tested in 6F.2.**

### Hypothesis H-A: Single Authority Concept

```
Authority is a unified concept.

All domains manifest the same pattern:
- Defined scope
- Required recognition
- Temporal boundaries
- Challenge mechanisms
- Chain of origin

If H-A is true:
→ Authority could be a bounded context
→ Or Authority could be a primitive (like Identity, Time)
```

**Status:** Plausible; requires 6F.2 to confirm pattern holds under stress.

---

### Hypothesis H-B: Authority Family

```
Authority is not one thing.

Instead:
- Membership Authority (behaves X)
- Election Authority (behaves Y)
- Appeal Authority (behaves Z)
- Fraud Authority (behaves W)
- Governance Authority (behaves V)

If H-B is true:
→ Authority is an umbrella concept
→ Multiple sub-types with different rules
→ May not be a bounded context
```

**Status:** Possible; would emerge if 6F.2 finds major behavioral differences.

---

### Hypothesis H-C: Authority Is Cross-Cutting

```
Authority behaves like Identity, Time, or Trust.

Not owned by a single context.
Not a bounded context.
Present everywhere.

Characteristics:
- Orthogonal to domain boundaries
- Same behavior across domains
- Primitive concept, not derived

If H-C is true:
→ Authority is not a bounded context
→ Authority is an architectural primitive
→ Authority is in all contexts simultaneously
```

**Status:** Emerging as likely; H-A would disprove this; H-B would partially disprove this.

---

### Hypothesis H-D: Authority and Legitimacy Are Interdependent

```
From Q8 + Q9:

Q8: Legitimacy appears to require Authority.
Q9: Authority does NOT guarantee Legitimacy.

Implication:
Authority + Recognition = Legitimacy
Authority without Recognition = Claimed, not Legitimate

Legitimacy does not require Authority?
(Maybe Legitimacy can exist through other means?)
```

**Status:** Q9 observations suggest YES; Q8 observations suggest Authority is necessary; both together are contradictory unless Authority and Legitimacy are **independent but related**.

**For 6F.2:** Authority Without Recognition stress test is crucial.

---

## Hypotheses Requiring Stress Testing

This list is **prioritized** for 6F.2. Each stress test targets one or more hypotheses.

| Stress Test | Targets | Primary Question |
|-------------|---------|------------------|
| **Authority Without Recognition** | H-B, H-D | Is claimed Authority sufficient, or is recognition required? |
| **Authority Without Governance** | H-A, H-C | Can Authority exist outside Governance structure? |
| **Authority Without Evidence** | H-A | Can Authority decide without documented basis? |
| **Authority Without Verification** | H-A, H-C | Can Authority exist without meta-verification? |
| **Competing Authorities** | H-A, H-B | What happens if precedence rule is absent? |
| **Circular Authorities** | H-A, H-B | Can Authority delegate to source? (A → B → A) |
| **Self-Authorizing Authority** | H-A, H-B, H-C | Can Authority designate itself without external source? |
| **Revoked Authority** | H-A, H-B, H-D | Do revoked Authority's decisions persist? Do they remain legitimate? |

---

## Inputs to Round 6F.2

From 6F.1, pass to 6F.2:

**Strongest Candidate Patterns** (most likely to survive stress):
- Pattern A: Scope-bounded Authority
- Pattern B: Authority requires recognition
- Pattern C: Authority is temporal

**Weakest Candidate Pattern** (most likely to break):
- Pattern E: Authority requires chain of origin (self-authorization may disprove this)

**Critical Distinction**:
Authority ≠ Legitimacy (Q9 finding is non-obvious and crucial)

**Most Important Stress Test**:
Authority Without Recognition (targets the Authority/Legitimacy distinction)

---

## What Is NOT Here

**Not included:**
❌ Bounded-context classification  
❌ Core vs. supporting domain decision  
❌ Tactical design recommendations  
❌ Aggregate design  
❌ Repository patterns  
❌ Code structure  

**Why:**
All of these are premature until 6F.2 completes. Stress testing may invalidate premises.

---

## Next Action

→ **Proceed to Round 6F.2 Authority Stress Test**

Do not pause for synthesis.

Do not wait for "better understanding."

Test the hypotheses. Let the results speak.

---

**Status:** Round 6F.1 complete. Findings documented. Authority hypotheses ready for stress testing.
