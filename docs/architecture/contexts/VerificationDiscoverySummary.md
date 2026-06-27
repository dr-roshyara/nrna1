# Verification Discovery Summary

**Date:** 2026-06-03  
**Phase:** 2 (Rounds 6A–6F)  
**Status:** Phase 2 discoveries complete. Not yet architectural conclusions.  
**Governance:** ARB Decision Record — Phase 2 Governance Gate (approved)  
**Purpose:** Preserve Phase 2 findings for Round 7 context mapping exploration.

**IMPORTANT:** This document captures discoveries, observations, and provisional findings. These are NOT architectural facts. They are input to Round 7 context mapping, where boundaries will be tested and refined.

---

## Executive Summary

Phase 2 investigation expanded from the original H1 hypothesis (Evidence Context) to discover a broader constitutional ontology involving Verification, Legitimacy, Authority, and Recognition.

**Key Finding:** Evidence and Verification emerged as **co-equal constitutional pillars**, neither subordinate to the other. This finding came from iterative discovery: Evidence → Verification → Legitimacy → Authority → Recognition, with each round revealing that the previous lens was insufficient.

**What This Means for Round 7:**
- Both modes (Election-Only and Full Membership) need verification mechanisms
- Verification is not subordinate to Evidence
- Authority, Legitimacy, and Recognition are independently significant concepts
- These four concepts must be mapped to bounded contexts or cross-cutting capabilities

---

## Election-Only Mode Discoveries

### Core Pattern
```
Organization holds voter list
Organization counts votes
Organization certifies results
```

### Verification Activities (A1–A5)

**A1: Identity Verification**
- **Discovery:** Organization verifies voter eligibility against pre-election voter list
- **Authorities:** Organization (primary), Election Committee (secondary)
- **Evidence Required:** Voter list, voter identity proof, authorization records
- **Status:** Operational in current system

**A2: Vote Count Verification**
- **Discovery:** Organization counts votes; may recount if dispute
- **Authorities:** Election Committee (primary), Organization (oversight)
- **Evidence Required:** Physical ballots (if paper), vote records, count logs, audit trails
- **Status:** Operational in current system

**A3: Result Certification**
- **Discovery:** Organization certifies count is correct and publishes results
- **Authorities:** Organization (final authority; may defer to Organization's Committee)
- **Evidence Required:** Count verification records, audit logs, certification document
- **Status:** Operational in current system

**A4: Appeal Processing**
- **Discovery:** If dispute raised (wrong count claimed, voter excluded), organization may recount or investigate
- **Authorities:** Organization (decides), Organization's Appeal Committee (if exists)
- **Evidence Required:** Dispute claim, recount results, investigation findings
- **Status:** Partially operational (exists, but not formally defined)

**A5: Fraud Detection**
- **Discovery:** Organization may investigate irregularities post-election (duplicate votes, invalid identities)
- **Authorities:** Organization, potentially external auditors
- **Evidence Required:** Vote records, voter list, device logs, timestamps
- **Status:** Ad-hoc (not formally designed)

### Key Observations
- Verification is entirely Organization-controlled
- No external voter verification capability
- No voter list publication (voters accept on organization's authority)
- Aggregate verification (cannot verify individual voter's vote without identifying them)
- Evidence is required for all verification steps

### Unresolved Questions
- Can voters self-verify their vote was counted?
- What happens if Organization is dishonest in count?
- Is external audit possible without breaking anonymity?
- What determines count is "correct"?

---

## Full Membership Mode Discoveries

### Core Pattern
```
Public voter list exists
Voter challenge mechanism available
Candidate publication
Governance transparency required
```

### Verification Activities (B1–B5)

**B1: Voter Eligibility Verification**
- **Discovery:** Public voter list published; voters can challenge if wrongly excluded/included
- **Authorities:** Membership Context (publishes), Governance (sets rules), Voters (challenge authority)
- **Evidence Required:** Member records, payment records, suspension status, challenge documentation
- **Status:** Designed but not yet operational (state gate: `setup_administration`)

**B2: Candidate Eligibility Verification**
- **Discovery:** Candidates published; members can challenge if ineligible
- **Authorities:** Governance (sets eligibility rules), Membership (certifies candidates meet rules)
- **Evidence Required:** Member records, position requirements, qualification documentation
- **Status:** Partially designed (candidate publication exists; challenge mechanism incomplete)

**B3: Vote Count Verification**
- **Discovery:** Count is public; observers certify; voters can verify aggregate (not individual)
- **Authorities:** Election Committee (counts), Observers (certify), Voters (aggregate verification)
- **Evidence Required:** Ballot records, observer reports, vote tallies, audit logs
- **Status:** Designed but not fully implemented (observer mechanism exists; public reporting incomplete)

**B4: Appeal Processing**
- **Discovery:** If voter challenges eligibility decision, appeal goes to Governance
- **Authorities:** Governance (appeals authority), original decision maker (must respond)
- **Evidence Required:** Original decision, appeal claim, supporting documentation, rule interpretation
- **Status:** Not yet designed (appeal mechanism missing)

**B5: Fraud Detection and Challenge**
- **Discovery:** If fraud alleged (duplicate votes, invalid votes), can be challenged and investigated
- **Authorities:** Governance (investigates), Voters (allege), Election Committee (examines records)
- **Evidence Required:** Vote records, voter list, device logs, investigation findings
- **Status:** Not yet designed (no formal fraud investigation process)

### Key Observations
- Verification distributed across multiple authorities (Membership, Governance, Election Committee, Voters)
- Public transparency required (lists, counts published)
- Challenge mechanisms are central to legitimacy
- Voter participation in verification (self-verification, aggregate verification, fraud reporting)
- Recognition is significant (voters recognize other voters; voters recognize Organization legitimacy through transparency)

### Unresolved Questions
- How many voters must accept legitimacy for election to be valid?
- Can Governance reverse Membership's eligibility decisions?
- What constitutes valid fraud evidence?
- How many appeals are normal vs. sign of deeper problem?
- Can voters collude on challenges?

---

## Cross-Mode Findings

### Finding C1: Evidence Does Not Decide

**Observation:**
Evidence (vote records, audit logs, voter lists) describes what happened. Evidence does not answer whether what happened was legitimate.

**Examples:**
- Vote count of 100-95 (evidence) does not tell us if count is correct (verification)
- Voter list (evidence) does not tell us if exclusion was fair (legitimacy)
- Audit log (evidence) does not tell us if process was honest (authority legitimacy)

**Implication for Architecture:**
Evidence and Verification are separate concerns. Evidence answers "what is recorded?" Verification answers "is this legitimate?"

**Status:** HIGH confidence, STRONG evidence

---

### Finding C2: Evidence Knows Mode

**Observation:**
Evidence storage and structure is mode-dependent.

- **Election-Only:** Evidence is Organization-internal (voter list, votes, counts); limited external verification possible
- **Full Membership:** Evidence is published (voter list, candidates, counts); external verification expected

**Implication for Architecture:**
Evidence context (if it exists as bounded context) must be aware of mode. Or mode-awareness is a cross-cutting concern.

**Status:** HIGH confidence, STRONG evidence

---

### Finding C3: Evidence and Verification Are Co-Equal

**Observation:**
Neither Evidence nor Verification is foundational to the other. Both are required and independent.

- Evidence without Verification = dead storage (facts without judgment)
- Verification without Evidence = blind judgment (judgment without facts)

**Implication for Architecture:**
Evidence and Verification are separate bounded contexts (or capabilities), neither subordinate. Both must exist; neither can replace the other.

**Architectural insight:** This is not a hierarchy (Evidence → Verification). This is a **constitutional pair**: Evidence and Verification are co-equal pillars of electoral integrity.

**Status:** HIGH confidence (from Phase 2 investigation); ARCHITECTURAL HYPOTHESIS (not yet proven in context mapping)

---

## Authority Discoveries (from Round 6F)

### Pattern A1: Authority is Scope-Bounded
Authority operates within defined scope. Membership Authority cannot decide election results. Election Authority cannot revoke membership.

### Pattern A2: Authority Requires Recognition
Authority claims must be recognized as legitimate (by voters, by other authorities, by governance rules) to be binding. Claimed authority ≠ legitimate authority.

### Pattern A3: Authority is Temporal
Authority has start time, duration, and expiration. Authority can be revoked. Decisions may outlast the authority that created them.

### Pattern A4: Authority is Challengeable
Authority decisions can be challenged through defined processes (appeals, recount, investigation). Challenges do not mean authority was wrong; they mean authority can be questioned.

### Pattern A5: Authority Requires Chain of Origin
Authority appears to require traceable origin to another authority, governance rule, or constitutional definition. Self-authorizing authority (without external source) does not appear legitimate.

### Hypothesis H-A: Single Authority Concept
Authority is unified across domains. **STATUS:** Significantly weakens under stress testing. Multiple domains show different authority behaviors and requirements.

### Hypothesis H-B: Authority Family
Authority varies by domain. Multiple behavioral types. **STATUS:** Survives all stress tests. Remains viable.

### Hypothesis H-C: Authority Is Cross-Cutting
Authority behaves like Identity, Time, Trust. Present everywhere. Orthogonal to domain boundaries. **STATUS:** Survives all stress tests. Remains viable.

---

## Legitimacy Discoveries (from Round 6E)

### Key Finding: Legitimacy ≠ Authority
Authority can exist without legitimacy (claimed but not recognized). Legitimacy appears to require authority plus recognition.

**Legitimacy Formula (provisional):** Authority + Recognition + [additional factors] = Legitimacy

**Additional factors (incomplete list):**
- Procedural fairness
- Scope clarity
- Temporal clarity
- Constitutional alignment
- Transparency (in Full Membership mode)

### Temporal Nature
Legitimacy appears to have temporal boundaries:
- Can be granted
- Can be maintained through recognition
- Can be revoked
- Can be restored through process

### Emergent Property (Hypothesis)
Legitimacy may be emergent (created by combination of factors) rather than primitive. **Status:** Observation, not proven.

---

## Recognition Discoveries

### Pattern: Recognition Enables Legitimacy
Observed across all domains:
- Voters accept observer count because they recognize observer authority
- Governance rules accepted because members recognize governance legitimacy
- Membership decisions honored because members recognize Membership Committee authority

### Pattern: Recognition Can Vary
Some voters may not recognize authority; others do. Consensus recognition creates legitimacy; contested recognition creates illegitimacy.

### Pattern: Recognition is Distinct from Authority
An authority can claim legitimacy (through recognition) without having decision-making power. Example: Independent auditor (has recognition authority, limited decision authority).

### Architectural Significance
Recognition appears to be a missing piece in many contexts. **Status:** Discovered but not yet investigated deeply. May be architecturally significant (H-B) or may be subordinate to Legitimacy/Authority.

---

## Unresolved Questions Entering Round 7

### Q1: Authority Architecture
- Is Authority H-B (family) or H-C (cross-cutting)?
- Can Authority exist without Governance?
- Is there a minimum Authority (can silence = approval)?
- Does constitutional hierarchy terminate?

### Q2: Legitimacy Architecture
- What is minimum set of factors creating legitimacy?
- Is legitimacy temporal or permanent?
- Can legitimacy be delegated?
- Does legitimacy require unanimous recognition?

### Q3: Recognition Architecture
- Is Recognition a domain concept, or cross-cutting?
- Who grants recognition? (voters, organization, governance?)
- Can recognition be revoked?
- Is recognition the same as legitimacy?

### Q4: Verification Boundaries
- Is Verification a bounded context?
- Does Verification own Authority decisions?
- Does Verification own Legitimacy judgments?
- Does Verification interact with Evidence? (If both are co-equal, how?)

### Q5: Evidence Boundaries
- Is Evidence a bounded context?
- What owns Evidence storage?
- What owns Evidence privacy (anonymity, immutability)?
- Does Evidence Context include audit logs or only votes?

### Q6: Membership/Governance Interaction
- Do Membership and Governance share Authority responsibility?
- Who decides voter eligibility? (Membership alone? Governance? Both?)
- Can Governance override Membership decisions?
- Is there a precedence rule?

### Q7: Cross-Mode Implications
- Are Election-Only and Full Membership fundamentally different bounded contexts?
- Or are they configuration variants of same contexts?
- Do context boundaries change between modes?

---

## Assumptions Entering Round 7

### A1: Evidence and Verification Are Separate
Proceeding with the discovery that these are co-equal, not hierarchical. To be tested in context mapping.

### A2: Authority is Likely H-B or H-C
Single unified Authority (H-A) appears insufficient. Proceeding with assumption that Authority varies by domain or is cross-cutting. To be tested.

### A3: Legitimacy Requires Authority + Recognition
Proceeding with formula: Authority + Recognition + [factors] = Legitimacy. To be validated in context mapping.

### A4: Governance and Membership Are Separate
Assuming Governance (rules, policy) and Membership (eligibility decisions) are distinct concepts. To be validated.

### A5: Recognition is Architecturally Significant
Assuming Recognition is not merely subordinate to Legitimacy; it may be independent architectural concept. To be investigated.

---

## What These Discoveries Mean for Round 7

Round 7 context mapping must answer:

1. **Where do Evidence, Verification, Authority, Legitimacy, Recognition live?**
   - Which are bounded contexts?
   - Which are capabilities within contexts?
   - Which are cross-cutting?

2. **How do these concepts interact?**
   - Does Verification consume Evidence?
   - Does Authority depend on Recognition?
   - Does Legitimacy depend on Verification?

3. **How do modes affect boundaries?**
   - Do Election-Only and Full Membership share contexts?
   - Are contexts the same, with different policies?
   - Are boundaries mode-dependent?

4. **What are the external dependencies?**
   - Which contexts depend on Governance?
   - Which depend on Membership?
   - Which are self-contained?

---

## What These Discoveries Do NOT Mean

❌ **Not concluded:** Evidence is a bounded context (may be capability, may be infrastructure)  
❌ **Not concluded:** Verification is a bounded context (same uncertainty)  
❌ **Not concluded:** Authority is H-B (may be H-C, may be different)  
❌ **Not concluded:** Legitimacy is emergent (observation, not proven)  
❌ **Not concluded:** Recognition is architecturally significant (hypothesis, not tested)  
❌ **Not concluded:** Any boundaries are final (Round 7 explores candidates)  

---

## Phase 2 → Round 7 Handoff

**Phase 2 delivered:**
- Material architectural findings
- Reduced uncertainty on core concepts
- Multiple candidate hypotheses (H-A, H-B, H-C)
- Clear identification of unresolved questions

**Round 7 will:**
- Map proposed boundaries against discovered concepts
- Test whether boundaries remain coherent
- Validate or revise hypotheses
- Determine if Evidence/Verification/Authority/Legitimacy/Recognition are contexts or capabilities
- Update assumptions based on boundary testing

**Governance:** Round 7 remains exploratory. Boundaries are candidates. All assumptions must be documented. Review required before proceeding to Round 8 (tactical design).

---

**Status:** Phase 2 discoveries preserved for Round 7 exploration.  
**Next Action:** Begin Round 7 Candidate Context Mapping.
