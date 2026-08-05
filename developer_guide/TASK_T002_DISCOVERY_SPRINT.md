# TASK: T-002 Domain Discovery – Trust Levels

**Objective:** Determine whether Trust Levels are a true domain requirement or a technical enhancement driven by policy variability.

**Non-Objective:** Do NOT write code, create migrations, or modify any files.

---

## Phase 0 – Trust Ownership Discovery

**Question:** Who owns trust decisions in the system?

**Deliverable:** `TRUST_OWNERSHIP_ANALYSIS.md`

Answer these questions:

1. **Who can attest trust today?**
   - Election Officer (for a specific election)?
   - Organisation Administrator (for the whole organisation)?
   - Membership Officer (across all contexts)?
   - Platform (central authority)?

2. **Who can revoke trust?**
   - Same person who attested?
   - Higher authority?
   - Anyone?

3. **Who defines trust requirements?**
   - Public Digit platform (one size fits all)?
   - Organisation (each org sets its own rules)?
   - Election (each election sets its own rules)?
   - Individual officers (case-by-case)?

4. **Can two organisations trust the same person differently?**
   - Example: Political Party requires government ID verification
   - Example: NGO accepts officer attestation
   - Same person, different trust levels in different orgs?
   - Is this even supported?

5. **Can one election require stronger trust than another election?**
   - Example: Board election (high stakes) vs. committee vote (routine)
   - Same organisation, different elections, different trust requirements?

6. **What is the trust boundary?**
   - Trust is an organisation property? (User is trusted by Org A but not Org B)
   - Trust is a global property? (User is trusted everywhere or nowhere)

7. **Is trust transferable across organisations?**
   - User verified in Organisation A (e.g., NGO)
   - Does Organisation B trust that verification?
   - Options:
     * Always (verification is global)
     * Never (each org re-verifies)
     * Policy-controlled (org can choose)
     * Case-by-case (officer decides)

**Deliverable Format:**

```
Question 1: Who can attest trust?
Answer: Election Officer (per election)
Evidence: VoterVerificationController is officer-scoped
Impact: Trust is election-level, not organisation-level

Question 2: Who can revoke trust?
Answer: Same officer who attested
Evidence: revoked_by field requires same officer
Impact: Trust changes require officer review

[continue for all 6 questions...]
```

**Why This Matters:**

If trust is organisation-scoped, Trust Levels are organisation configuration.

If trust is election-scoped, Trust Levels are election configuration.

If trust is officer-scoped (case-by-case), Trust Levels may not exist at all.

The ownership structure determines the entire architecture.

---

## Phase 1 – Current State Analysis

**Question:** Where and how is verification status checked in the system?

**Deliverable:** `TRUST_LEVEL_CURRENT_STATE.md`

Search and document:

1. All places where verification status is checked
2. All places where eligibility decisions are made  
3. All places where authorization decisions are made
4. All places where "verified" is treated as binary (true/false, active/revoked)

For each location provide:
- File path
- Class name
- Method name
- Business decision being made
- Current check (what conditions must be true)

**Example format:**
```
app/Models/Member.php::getVotingRightsAttribute()
  Checks: Member is verified AND membership_status = active
  Decision: Grant full voting rights
  Current check: if (verified && status == 'active')
```

---

## Phase 2 – Business Need Analysis

**Question:** For each Public Digit use case, does binary verification suffice?

**Deliverable:** `TRUST_LEVEL_BUSINESS_NEEDS.md`

For each use case, answer:

### Use Cases to Analyze:

1. **Membership Application Approval**
   - Who needs to verify? (Officer, Admin, Committee?)
   - What triggers approval? (Identity check, membership fee, background check?)
   - Does different evidence require different trust decisions?

2. **Election Voting Participation**
   - Who verifies? (Election officer for each election?)
   - What makes a voter eligible? (Just verified? Or verified + member?)
   - Do different elections require different verification levels?

3. **Candidate Nomination**
   - Who can nominate? (Members only? Verified members only?)
   - Different requirements than voting?

4. **Delegate Rights**
   - Can delegate (someone with power to vote on behalf of another)?
   - Different trust requirements than voting?

5. **Governance Proposals**
   - Who can submit proposals? (Any member? Verified member?)
   - What evidence is required?

6. **Organisation Types**
   - NGO (small, trust-based)
   - Political Party (formal identity requirements)
   - Union (membership-based)
   - Cooperative (member-verified)
   - Association (officer-attested)

### For Each Scenario, Answer:

```
Use Case: X
Current Binary Model: verified = true/false

Question 1: Does this suffice?
  Answer: Yes / No / Partially

Question 2: If no, what distinction is needed?
  Example: "Political parties need government ID verification, 
           NGOs accept officer attestation only"

Question 3: Is this distinction an ORGANIZATION POLICY CHOICE 
            or a DOMAIN REQUIREMENT?
  Answer: Policy choice / Domain requirement / Both

Question 4: What business ACTION becomes possible only with Trust Levels?
  Bad justification: "More flexible"
  Good justification: "Delegate voting requires High Assurance,
                       ordinary voting accepts Officer Verified"
  
  Answer what specific action/decision differs.
  If no action differs → Trust Levels may not be needed.
```

---

## Phase 3 – Trust Level Matrix

**Only proceed if Phase 2 proves a need.**

**Question:** If trust levels are needed, what are they?

**Deliverable:** `TRUST_LEVEL_MATRIX.md`

For each proposed trust level, document:

```
Trust Level: Officer Verified

Definition: 
  Identity attested by elected/appointed officer through review

Granted Capabilities:
  - Membership approval
  - Voting eligibility
  - Proposal submission

Denied Capabilities:
  - Delegate voting on behalf of others
  - Access to sensitive governance data

Evidence Requirements:
  - Officer review (no documents required)
  - No government ID needed

Expiry Rules:
  - Never expires (organisation policy may override)

Revocation Rules:
  - Officer can revoke (e.g., if fraud discovered)
  - Automatic revocation: membership cancelled
```

---

## Phase 4 – Impact Analysis

**Question:** If trust levels are implemented, what changes?

**Deliverable:** `TRUST_LEVEL_IMPACT_ANALYSIS.md`

Identify what would need to change:

1. **Policies affected:**
   - `VoterVerificationPolicy` – checks trust level instead of binary
   - `MembershipPolicy` – checks trust level
   - `CandidacyPolicy` – checks trust level
   - (List all)

2. **Eligibility rules affected:**
   - Election voting: minimum trust level?
   - Nomination: minimum trust level?
   - Delegation: different level than voting?

3. **Authorization rules affected:**
   - Who can approve which trust levels?
   - Officer vs. Organisation vs. System?

4. **Existing records affected:**
   - How many verifications exist?
   - How are they backfilled to new levels?
   - Is there a safe migration?

5. **Administrative burden:**
   - Must organisations configure trust policies?
   - Do default policies exist?
   - Training required for officers?

---

## Phase 5 – Recommendation

**Choose one and provide rationale:**

### Option A: Keep Binary Verification

```
Verdict: verified = true/false is sufficient

Rationale:
  - Use case analysis shows no need for gradations
  - Trust requirements are policy-driven, not level-driven
  - Complexity not justified by business need
  - T-003 (Trust Policies) addresses variability better
```

### Option B: Introduce Trust Levels Now

```
Verdict: Implement trust levels in T-002

Rationale:
  - Use cases require multiple levels:
    [list specific scenarios]
  - Business need clear:
    [explain what decisions change per level]
  - Migration path safe:
    [explain how existing data maps]
```

### Option C: Implement Trust Policies First

```
Verdict: Do T-003 (Trust Policies) before T-002 (Trust Levels)

Rationale:
  - Organizations have different requirements:
    [give examples: NGO vs. political party]
  - Trust policies define the levels needed
  - Levels should emerge from policies, not precede them
  - Order: T-003 → T-002 (rather than T-002 → T-003)
```

---

## Success Criteria

Phase 1-4 complete:
- [ ] Current state documented (all verification checks found)
- [ ] Business needs analyzed (for each use case, need confirmed or denied)
- [ ] Trust matrix created (if needed)
- [ ] Impact assessed (affected policies, records, administrative burden)
- [ ] Recommendation clear (A, B, or C with solid rationale)

## What NOT to Do

❌ Write code  
❌ Create migrations  
❌ Modify any files  
❌ Design UI forms  
❌ Create enum definitions  
❌ Assume the answer before analyzing  

## What WILL Happen After

After discovery is complete:

1. **Architect review** – Does the analysis reveal true domain need?
2. **Validate recommendation** – Is A, B, or C correct?
3. **Sequence decision** – If both T-002 and T-003 are needed, which first?
4. **THEN implement** – Only the recommended path

---

**Estimated Effort:** 4-6 hours  
**Deliverables:** 4 markdown documents  
**Code Changes:** 0

**Goal:** Answer the question "Are Trust Levels a domain requirement?" with evidence, not intuition.
