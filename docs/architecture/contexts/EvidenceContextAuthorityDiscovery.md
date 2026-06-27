# Round 6F — Authority Investigation Framework

**Date:** 2026-06-03  
**Objective:** Design investigation framework for Authority as a domain concept  
**Critical Framing:** Authority appears repeatedly in legitimacy creation, destruction, appeals, and governance. This framework identifies key questions to be answered in 6F.1 (Discovery) and 6F.2 (Stress Test). No conclusions have been drawn yet.

---

## Context from Prior Rounds

**Round 6E revealed:** "Legitimacy is a temporal state of authorization."

**The architectural gap:** We understand Legitimacy (effect) better than Authority (cause).

**The inversion problem:** In strategic DDD, we should understand sources before effects. Authority may be more fundamental than currently assumed.

---

## Investigation Structure

Four discovery themes, eight questions, organized to build understanding progressively.

---

## Theme A — Authority Identity

### Q1: What Is an Authority?

**Operational question** (not "who is an authority" but "what makes something an authority")

From stress testing, Authority appears in multiple contexts:

| Authority Type | From Scenario | Description |
|---|---|---|
| Individual Authority | 2A, 2B | HR manager approves membership |
| Committee Authority | 4 | Regional committee approves |
| Role Authority | 2B | "Authority A" (title/function) |
| Institutional Authority | Implied | Election Commission certifies |
| Governance Authority | 1C | Governance sets rules; observer implements |
| Appeal Authority | 5 | Reviews and can overturn |
| Fraud Authority | 3 | Detects and investigates |

**Questions this raises:**
1. Are these all "Authority" in the same sense, or different concepts?
2. What property makes something an Authority?
3. Is Authority a role, a function, an institution, or a person?
4. Can Authority be distributed (many people hold same authority)?
5. Can Authority be hierarchical (Authority of Authorities)?

**Hypothesis to test:**

```
Authority is not a person or role.
Authority is a decision function:
"The power to make binding decisions
in a specific domain
that others will recognize as legitimate."
```

---

### Q2: Who Can Become an Authority?

**Discovery question** (not assuming authority is always a human)

Candidates for Authority:

| Candidate | Could become Authority? | Evidence |
|---|---|---|
| Individual person | YES | Scenarios 2A, 2B: HR manager approves |
| Elected committee | YES | Scenario 4: Regional committees |
| Appointed body | YES | Scenario 1C: Observer certification |
| Organization | MAYBE | Election Commission? |
| Algorithm/Rule | MAYBE | Automated eligibility check? |
| Election result | MAYBE | "The vote decided this" |
| Governance | MAYBE | Governance sets rules, but does it approve? |
| Membership status | MAYBE | Being a member grants authority? |
| Time/Temporal rule | MAYBE | "Authority by default after X days" |

**Critical questions:**
1. Can a machine/algorithm be an Authority?
2. Can a rule be an Authority?
3. Can the absence of contradiction be Authority? (Nobody objected = approved?)
4. Is there a minimum/maximum Authority (can anyone be Authority? Only one Authority per domain?)

**Hypothesis to test:**

```
Authority is not tied to humans.
Authority is a function that can be
assigned to individuals, committees,
organizations, algorithms, or rules.

Anything that makes binding decisions
recognized as legitimate can be Authority.
```

---

## Theme B — Authority Lifecycle

### Q3: Can Authority Be Delegated?

**Critical architectural question**

From stress testing, delegation appears implicitly:

- NRNA President → Election Committee (certifies election)
- Governance → Regional Authority A (approves members)
- Central Governance → Appeal Authority (reconsiders decisions)

**If authority can be delegated:**
- Creates an authority graph
- Creates delegation chain ("who authorized this authority?")
- Creates potential conflicts (delegate overrides principal?)

**If authority cannot be delegated:**
- Authority is fixed/inherent
- Conflicts are resolved by precedence, not delegation
- Authority cannot be temporarily transferred

**Questions:**
1. Does delegated authority have same weight as original?
2. Can authority be partially delegated (some decisions yes, others no)?
3. Can authority be revoked from a delegate?
4. Does delegation create responsibility chain?

**Hypothesis to test:**

```
Authority can be delegated.
Delegated authority retains full power.
Revocation of delegation is possible.
Delegation creates an authority hierarchy.
```

---

### Q4: Who Revokes Authority?

**Authority destruction question**

From stress testing, authority appears revocable:

| Authority | Who revokes it? | Evidence |
|---|---|---|
| HR Manager | Their supervisor? Governance? | Implied in Scenario 2B |
| Regional Committee | Central Governance? | Implied in Scenario 4 |
| Appeal Authority | Governance? | Implied in Scenario 5 |
| Authority that made bad decision | Higher Authority? Same Authority? | Scenario 2C: revocation by Governance |

**Questions:**
1. Can Authority revoke itself?
2. Is there always a higher Authority that can revoke?
3. Is revocation automatic (failure to perform) or intentional (deciding to remove)?
4. What happens to decisions made by revoked Authority?

**Critical hypothesis:**

```
If Authority is revoked,
what happens to decisions it made?

Are they still legitimate?
Can they be overturned?
Is there an appeals process?
```

This creates a temporal dimension to Authority.

---

### Q5: Is Authority Temporal?

**The temporal question from Legitimacy**

If Legitimacy is temporal, and Authority creates Legitimacy, then Authority itself may be temporal.

**Scenarios from stress testing:**

| Scenario | Authority change | Result |
|---|---|---|
| 2C: Revocation after vote | Membership revoked | Vote still counts (Legitimacy at T1 was real) |
| 2B: Authority disagreement | Authority B overrides A | Which was "real" authority? |
| 5: Appeal authority | Original decision by A, overturned by Appeal Authority | Did original Authority lose power at T3? |
| 3: Fraud detected | Fraud Authority revokes approval | Legitimate at T1, illegitimate at T4 |

**Questions:**
1. Does Authority exist at a point-in-time, or continuously?
2. Can Authority be granted and revoked during an election?
3. Does Authority retroactively change (decision by A becomes invalid when A is revoked)?
4. Is there a difference between "Authority existed" and "Authority's decision still stands"?

**Hypothesis to test:**

```
Authority is temporal.

Authority can be granted at T1,
exercised at T2,
revoked at T3.

A decision made at T2 by Authority
may retain legitimacy even if Authority
is revoked at T3.

This creates temporal authorization.
```

---

## Theme C — Authority Trust

### Q6: Who Verifies Authorities?

**The recursive question**

This may be the most important question in Round 6F.

From stress testing:
- Verification proves participant legitimacy (Q2A, Q2B)
- But who proves Authority legitimacy?

**The recursion:**

```
Verification verifies participants.

But who verifies verifiers?

Who verifies that Authority A
has the right to approve?

Who verifies that Authority A
is correctly applying the rules?
```

**Cases from stress testing:**

| Case | Authority | Who verifies? |
|---|---|---|
| HR Manager approves | HR Manager | Governance? Their supervisor? |
| Regional Committee approves | Regional Committee | Central Governance? |
| Appeal Authority overturns | Appeal Authority | Governance? Constitutional court? |
| Fraud Authority detects | Fraud Authority | Who checks fraud investigator? |

**Questions:**
1. Is there a verification process for Authorities?
2. Does verification of Authority happen before or after their decision?
3. Can an Authority's decision be overturned by a higher Authority?
4. Is there a highest Authority that cannot be verified?

**Critical hypothesis:**

```
If Verification is the mechanism
that proves legitimacy,

and Authority is the source
that creates legitimacy,

then verification of Authority
is the key to trusting the system.

Who verifies the verifier?
Who authorizes the authority?
```

---

### Q7: Can Authorities Disagree?

**Conflict resolution question**

Stress testing showed this clearly (Scenario 2B):
- Authority A: "Applicant" (not eligible)
- Authority B: "Current member" (eligible)
- Governance rule: "HR is authority of record"

**If Authorities can disagree:**
- Creates conflict resolution requirement
- Creates precedence/priority question
- Creates appeals process

**If Authorities cannot disagree:**
- Authority decisions are deterministic
- No conflicts are possible
- Simpler model

**Questions:**
1. Can two equal Authorities disagree?
2. If they disagree, who decides?
3. Is there always a precedence rule?
4. Can both be "correct" from their perspective?

---

## Theme D — Authority and Legitimacy

### Q8: Can Legitimacy Exist Without Authority?

**Foundational question**

This tests whether Authority is necessary for Legitimacy.

**To be investigated in 6F.1:**

Can legitimacy exist without authorization from a recognized Authority?

Examples to test:
- Democratic majority vote
- Silent consent (nobody objected)
- Inherited/delegated authority
- Time-based eligibility

---

### Q9: Can Authority Exist Without Legitimacy?

**The inverse question** (equally important)

This tests whether Authority automatically implies Legitimacy.

**Examples to investigate:**

| Case | Authority exists? | Legitimacy? | Question |
|---|---|---|---|
| Corrupt Authority | YES | NO? | Do corrupt decisions have legitimacy? |
| Expired Authority | YES | NO? | Do expired authorities' decisions still count? |
| Disputed Authority | YES | NO? | Do challenged authorities have legitimacy? |
| Unauthorized claim | YES | NO? | Does claiming authority create it? |
| Authority without consent | YES | NO? | Does authority require acceptance? |

**Critical cases:**

**Case A: Self-Authorization**
```
"I declare myself Authority."

Does this create legitimacy?
Who accepts it?
Why?
```

**Case B: Infinite Chain**
```
A authorized B
B authorized C
C authorized D

Who authorized A?
Does the chain terminate?
```

**The inverse hypothesis:**

```
Authority ≠ Legitimacy

Authority is necessary but may not be sufficient for Legitimacy.

Legitimacy may require Authority + Consent + Governance + Time.
```

---

## Investigation Questions Summary

Nine questions organized by discovery theme:

| Theme | Questions | Status |
|---|---|---|
| **Identity** | Q1, Q2 | To be investigated in 6F.1 |
| **Lifecycle** | Q3, Q4, Q5 | To be investigated in 6F.1 |
| **Trust** | Q6, Q7 | To be investigated in 6F.1 |
| **Legitimacy** | Q8, Q9 | To be investigated in 6F.1 |

---

## Hypotheses to Test (NOT Conclusions)

These are starting hypotheses for 6F.1 and 6F.2. None are proven yet.

**Hypothesis A (from Q1-Q2):**
Authority may be a functional property (not person-dependent).

**Hypothesis B (from Q3-Q5):**
Authority may be temporal (can be granted, revoked, delegated).

**Hypothesis C (from Q6-Q7):**
Authority may require verification and resolution mechanisms.

**Hypothesis D (from Q8-Q9):**
Authority and Legitimacy may be distinct concepts that both matter.

---

## What These Hypotheses Do NOT Claim

❌ Authority is foundational
❌ Authority creates Legitimacy unilaterally  
❌ Authority → Authorization → Legitimacy hierarchy
❌ Authority is more fundamental than Legitimacy

These are all to be tested in 6F.1 and 6F.2.

---

## Critical Unknown: Authority's True Nature

After nine questions, Authority remains:

```
Not proven to be:
- A bounded context
- A domain
- A primitive
- Cross-cutting
- Hierarchical
- Temporal
- Sufficient for Legitimacy

All remain hypotheses to test.
```

---

## Critical Unanswered Questions

These belong to Round 7+ (Context Mapping):

1. **Who is the Constitutional Authority?**
   - Is there an ultimate Authority that cannot be challenged?
   - Does NRNA Governance fulfill this role?

2. **How does Authority relate to other potential domains?**
   - Is Governance the Authority Definer?
   - Are Membership, Election, Appeals Authority Types?

3. **Is Authority a bounded context or a cross-cutting concept?**
   - Does Authority belong in one context or multiple?
   - Is Authority the organizing principle?

4. **How are different Authority types related?**
   - Authority hierarchy (A authorizes B to authorize C)
   - Authority delegation (A delegates to B)
   - Authority conflict (A and B both claim authority)

---

## Architectural Implication

**Before Round 6F:** Legitimacy appeared to be the core concept.

**After Round 6F (hypothesis):** Authority may be more fundamental than Legitimacy.

This inverts the architectural model:

```
Old model (from 6E):
  Governance + Verification + Evidence
    = Legitimacy

New model (from 6F):
  Authority (authorized by Governance)
    → creates Legitimacy
    → verified by Verification
    → recorded in Evidence
```

This reframes the entire architecture around Authority as the source.

---

## Next Steps: 6F.1 and 6F.2

### Round 6F.1: Authority Discovery

Answer the nine questions using evidence from:
- Constitutional examples
- Election scenarios
- Membership cases
- Fraud scenarios
- Appeal processes

For each question, collect patterns that emerge across domains.

### Round 6F.2: Authority Stress Test

Try to break the Authority model:
- Authority without governance
- Authority without evidence
- Authority without verification
- Competing authorities
- Circular authorities
- Authority without consent
- Infinite authority chains
- Revoked authorities claiming legitimacy
- Self-authorizing authorities

### Then: Round 7 Context Mapping

Only after 6F.1 and 6F.2 are complete should Context Mapping proceed.

---

**Status: Round 6F Framework complete. Nine discovery questions identified. No conclusions drawn. Investigation framework ready for 6F.1 and 6F.2 execution.**

**HOLD: Do NOT create Round 7 until 6F.1 and 6F.2 are complete.**
