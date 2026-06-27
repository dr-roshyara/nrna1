# Round 6E — Legitimacy Model Synthesis

**Date:** 2026-06-03  
**Objective:** Test whether Legitimacy is a bounded context, core domain, emergent domain, or conceptual lens  
**Candidate Status:** Legitimacy is the strongest remaining candidate to be tested—not proven

---

## Critical Framing

After Rounds 6A–6D:
- Evidence was a candidate → Stress testing ruled it out as independent BC
- Verification was a candidate → Stress testing revealed it's multiple layers
- Legitimacy emerges as strongest candidate → Must be tested with same rigor

**This round exists to test, not to confirm.**

---

## Research Questions

### Q1: What Observable Conditions Create Legitimacy?

**Operational question** (not philosophical)

From stress testing, multiple conditions appear to create legitimacy:

| Condition | From Scenario | Observable? |
|-----------|---|---|
| Authority approves | 2A, 2B | YES — Authority A says "eligible" |
| Membership status matches | 2A | YES — Status field = "current" |
| Verification confirms | All | YES — Proof of rule-following |
| Time constraint satisfied | 2C | YES — Vote happens during window |
| No disqualifying event | 3 | YES — No fraud detected |
| Appeals exhausted | 5 | YES — No pending challenge |
| Evidence supports decision | All | YES — Record exists |

**Questions:**
1. Are ALL of these required, or only some?
2. Do they have priority (if two conflict, which wins)?
3. Can legitimacy exist with only one condition?
4. Do conditions change by election mode (Election-Only vs. Full Membership)?

---

### Q2: Which Actors Can Establish Legitimacy?

**Identifies specific roles** (not "who" but "which role")

From stress testing:

| Actor | Can establish legitimacy? | Evidence |
|-------|---|---|
| Governance | PARTIAL | Defines rules; doesn't directly approve |
| Authority | YES | Authority A, B1, B2, C1 directly approve |
| Membership System | YES | Membership status determines eligibility |
| Verification | NO | Verification proves; doesn't establish |
| Election | PARTIAL | Voting itself happens, but doesn't establish eligibility |
| Evidence | NO | Evidence records; doesn't establish |
| Appeals Authority | MAYBE | Can override earlier decision; establishes new legitimacy? |
| Fraud Investigator | NO | Removes legitimacy; doesn't establish |

**Questions:**
1. Are Authorities the only legitimacy creators? (Or are there others?)
2. Can Governance directly establish legitimacy, or only Authority?
3. Can Membership System establish legitimacy without Authority approval?
4. Does Appeals Authority create new legitimacy or restore old?

---

### Q3: Which Events Invalidate Legitimacy?

**Reveals triggers** (not just actors, but state changes)

From stress testing:

| Event | Invalidates legitimacy? | Evidence |
|-------|---|---|
| Fraud detection | YES | Scenario 3: duplicate votes void legitimacy |
| Membership revocation | YES | Scenario 2C: revocation post-vote invalidates |
| Authority reversal | YES | Scenario 2B: if Authority B overrides Authority A |
| Appeal outcome | MAYBE | Scenario 5: appeal authority could overturn |
| Time expiration | MAYBE | If voting window closes |
| Governance rule change | MAYBE | If Governance changes legitimacy rule |
| New evidence | MAYBE | If Evidence reveals fraud post-verification |

**Questions:**
1. Can a legitimate decision become illegitimate? (Yes, from Scenario 2C)
2. Once illegitimate, can it become legitimate again? (Appeals suggest yes)
3. Does "legitimacy destroyed" mean vote is void or decision is reconsidered?
4. Who decides if an invalidating event actually invalidates?

---

### Q4: Is Legitimacy Truly Temporal?

**Core question from Scenario 2C**

Evidence suggests legitimacy has time dimension:

```
T0: Voter eligible (pre-vote)
T1: Voter votes (eligible at vote moment)
T2: Voter still eligible (post-vote, pre-revocation)
T3: Voter revoked (no longer legitimate)
T4: Vote still counts? (legitimacy at T1 was real; revocation at T3 doesn't erase it)
```

**Questions:**
1. Is legitimacy evaluated at a single point-in-time (T1: vote moment)?
2. Or is legitimacy a continuous state (must be legitimate throughout)?
3. If continuous: when do we check? (At vote? At count? At certification?)
4. If point-in-time: is historical legitimacy preserved in Evidence?
5. Can revocation after vote change historical legitimacy?

**Architectural implication:** If legitimacy is temporal, the domain has time as a first-class concept.

---

### Q5: What Invariant Exists Across All Legitimacy Decisions?

**DDD-focused question** (seeks domain invariant)

Looking across all scenarios for common pattern:

**Candidate invariants:**

| Invariant | Evidence | Holds? |
|-----------|----------|--------|
| "Legitimacy requires Authority approval" | Scenarios 2A, 2B | MOSTLY (but Q2 asks if other creators exist) |
| "Legitimacy can be verified" | All scenarios | YES (even when overturned) |
| "Legitimacy is documented in Evidence" | All scenarios | YES |
| "Legitimacy can be challenged" | Scenarios 5 (appeals) | YES (suggests legitimacy is revisable) |
| "Legitimacy is temporal" | Scenarios 2C | YES (legitimacy at T1 ≠ legitimacy at T3) |
| "Once legitimacy is granted, it endures unless explicitly revoked" | Scenarios 2C, 3, 5 | MAYBE (revocation and appeal both challenge this) |

**The deepest question:** Is there a single invariant that *all* legitimacy decisions must satisfy?

Example candidates:
- "Legitimacy requires documented authority decision" (?)
- "Legitimacy is revisable; immutability is not guaranteed" (?)
- "Legitimacy is bounded by temporal scope" (?)

---

### Q6: Can Legitimacy Exist Without Verification? Without Evidence?

**Reveals if Legitimacy is emergent or independent**

**Scenario A: No Verification**

Suppose Governance says: "Authority A's word is sufficient. No verification needed."

- Can legitimacy exist without proof?
- Answer from stress test: Scenario 5 (appeals) suggests yes—Authority A can be trusted without re-verification
- Implication: Verification is not required for legitimacy; Authority is

**Scenario B: No Evidence**

Suppose Governance says: "We will not record authority decisions."

- Can legitimacy exist without documented proof?
- Answer from stress test: Scenario 3 (fraud) suggests NO—without Evidence, fraud cannot be detected
- Implication: Evidence is required for legitimacy trust

**Scenario C: No Governance**

Suppose we remove Governance entirely and let each Authority decide independently.

- Can legitimacy exist without Governance rules?
- Answer from stress test: Scenario 4 (multi-region) suggests MAYBE—regional authorities create local legitimacy
- Implication: Governance may not be required for legitimacy; Authority is

**Critical finding:** Legitimacy requires Authority + Evidence, but may not require Governance or Verification as separate domains.

This suggests Legitimacy may be **emergent**: composed of collaborating domains rather than a single bounded context.

---

## Legitimacy Definition Attempt

Based on six questions, attempt to define Legitimacy:

**Working definition:**

```
Legitimacy is a temporal state of authorization.

It is created when an Authority (recognized by Governance
or local context) makes an explicit decision favoring participation.

It is evidenced through recorded authorization.

It endures until an invalidating event occurs:
  - Explicit revocation
  - Fraud detection
  - Temporal boundary crossing
  - Appeal outcome

It can be challenged but remains presumptively valid
until challenge succeeds.

It is individual to voter + election context.
```

**Observations:**
- Legitimacy is not a thing (noun); it's a state (adjective)
- Legitimacy is not owned by a single context; it's produced by Authority + Evidence + Governance collaboration
- Legitimacy requires Verification to maintain trust, but not to exist
- Legitimacy is temporal; time is an axis

---

## Legitimacy-Producing Decisions

Distilled from stress testing, these decisions establish legitimacy:

| Decision | Owner | Creates? | Authority? |
|----------|-------|----------|-----------|
| Authority approves participant | Authority | YES | Direct authority |
| Governance sets eligibility rule | Governance | PARTIAL | Enables Authority |
| Evidence records authority decision | Evidence | NO | Documents |
| Verification confirms rule-following | Verification | NO | Proves compliance |
| Membership establishes status | Membership | YES | Membership authority |
| Appeals overturn decision | Appeals Authority | YES | Higher authority |
| Governance defines temporal boundary | Governance | PARTIAL | Enables Authority |

**Pattern:** Legitimacy is created by actors with explicit authority (Authority, Membership, Appeals Authority), enabled by Governance, recorded by Evidence, proven by Verification.

---

## Legitimacy-Destroying Decisions

These decisions remove legitimacy:

| Decision | Destroyer | Destroys? | Authority? |
|----------|-----------|-----------|-----------|
| Fraud detected | Fraud Investigator | YES | Special authority |
| Membership revoked | Membership Authority | YES | Membership authority |
| Appeal overturns | Appeals Authority | YES | Higher authority |
| Authority reverses | Authority | YES | Same authority |
| Governance rule changes retroactively | Governance | MAYBE | Constitutional authority |
| Temporal boundary passes | Time | YES | System-defined |

**Pattern:** Legitimacy is destroyed by actors with authority to revoke (same authorities that created it, or higher authorities).

---

## Is Legitimacy a Bounded Context?

**Assessment based on six questions:**

| Criterion | Result | Assessment |
|-----------|--------|-----------|
| **Owns domain decisions?** | Multiple contexts own legitimacy decisions | NO — decisions are distributed |
| **Has consistent ubiquitous language?** | "Legitimacy" used consistently across domains | MAYBE — but meaning varies by context |
| **Boundary is clear?** | Authority/Governance/Verification/Evidence roles blur | NO — boundaries are fuzzy |
| **Is independent?** | Legitimacy requires collaboration with other contexts | NO — is emergent |
| **Makes unique decisions?** | Authority makes "approve" decisions; Governance makes "rule" decisions | NO — constituent decisions belong elsewhere |

**Conclusion:** Legitimacy does NOT appear to be a traditional bounded context.

---

## Is Legitimacy a Core Domain?

**Assessment:**

**Arguments for:**
- Legitimacy is what the system is built to answer ("Is this election legitimate?")
- All other domains serve legitimacy (Authority defines authority, Evidence provides proof material, Verification confirms)
- Strategic differentiator of NRNA (privacy-preserving legitimacy verification)
- Business value is entirely about legitimacy

**Arguments against:**
- Legitimacy is not owned by a single domain; it emerges from collaboration
- No single context makes legitimacy decisions
- Legitimacy is conceptual; tactical decisions live in Authority, Governance, Verification

**Nuance:** Legitimacy may be **core value** (what matters most) without being **core domain** (a bounded context).

---

## Is Legitimacy a Higher-Order Domain?

**Hypothesis:** Legitimacy is a **domain ecosystem**—a set of collaborating domains united by purpose, not by a single bounded context.

```
Legitimacy Ecosystem
├── Governance (defines rules)
├── Authority (creates authority)
├── Membership (supplies status)
├── Verification (proves compliance)
├── Appeals (reconsiders decisions)
├── Fraud Investigation (detects invalidity)
├── Evidence (records decisions)
└── Election (context for voting)
```

This would mean:
- No "Legitimacy Context" exists as a bounded context
- Instead, multiple domains collaborate to produce legitimate elections
- Each domain has clear responsibilities and boundaries
- Legitimacy is the emergent property of their collaboration

**This may be the most accurate model.**

---

## Legitimacy as a Conceptual Lens

**Alternative hypothesis:** Legitimacy is primarily a conceptual lens—a way to talk about the system—rather than a domain itself.

Similar to how "security" is conceptual (cuts across domains) rather than a bounded context.

If true:
- We don't design a "Legitimacy Context"
- Instead, we design Governance, Authority, Evidence, etc. with legitimacy in mind
- Each domain contributes to legitimacy without owning it

**This is simpler architecturally; may be more pragmatic.**

---

## Candidates After Round 6E

Based on synthesis:

### Likely Core Domain
**Governance** — Defines constitutional rules; owns authority hierarchy; decides legitimacy standards

### Likely Supporting Domains
**Authority** — Creates legitimacy through approval decisions
**Membership** — Supplies eligibility facts; manages status changes
**Evidence** — Records decisions; provides audit trail
**Verification** — Proves compliance; maintains trust

### Likely Infrastructure/Generic
**Election** — Conducting vote (generic operational concern)
**Appeals** — Process management (generic governance concern)
**Fraud Investigation** — Anomaly detection (generic security concern)

### Likely Conceptual Lens (not a domain)
**Legitimacy** — Emergent property of Governance + Authority + Verification + Evidence

---

## Success Criteria Met?

✅ Legitimacy concept explicitly defined
✅ Legitimacy-producing decisions identified
✅ Legitimacy-destroying decisions identified  
✅ Temporal legitimacy analyzed (critical finding)
✅ Relationships between domains clarified
✅ Legitimacy candidate tested rigorously

---

**Status: Round 6E complete. Legitimacy is the strongest value concept in the system, but likely NOT a bounded context. Instead, Legitimacy appears to be an emergent property produced by collaboration between Governance, Authority, Membership, Evidence, and Verification domains. Recommend architectural restructuring with Legitimacy as organizing principle, not as a domain boundary.**

**Ready for Round 7: Context Mapping and Domain Ownership finalization.**
