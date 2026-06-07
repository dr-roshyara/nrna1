# Round 17 — Discovery Debt Register

**Date:** 2026-06-07

**Status:** Initialized (ARB Corrections Applied)

**Purpose:** Track questions discovered during Round 16 and Round 17 investigation that cannot be resolved with current evidence. Prevent known unknowns from being forgotten.

---

## How This Register Works

A mature discovery program produces three outputs:

1. **Answers** — Questions investigated and resolved with evidence
2. **New Questions** — Questions discovered during investigation
3. **Known Unknowns** — Questions identified but deferred pending future discovery

This register tracks the third category: known unknowns that are out of scope for Step 2 but important for future work.

| ID | Question | Why Unresolved | Why Out of Step 2 Scope | Priority | Recommended Discovery | Source |
|----|----------|-----------------|------------------------|----------|------------------------|--------|
| (ID) | (Unanswered question) | (Evidence gap) | (Scope limitation) | High / Medium / Low | (Future discovery stream) | (Stream reference) |

---

## Initial Discovery Debt Items

These items are identified from Round 16 and Step 2 investigation as important questions that cannot be resolved within Step 2 scope.

---

### D1: What is Legitimacy?

**Question:** What exactly constitutes legitimacy in the context of elections? How is it established? How is it lost? How is it restored?

**Why Unresolved:** 
- Legitimacy identified as recurring concept across discovery
- No explicit definition found in repository
- Appears to be organizational/philosophical concept, not operational concept
- Insufficient evidence to determine scope

**Why Out of Step 2 Scope:**
- Step 2 focuses on context relationships and evidence roles
- Legitimacy definition belongs to problem-space governance discovery
- Organizational bylaws and governance policies not yet reviewed

**Priority:** High

**Recommended Discovery:** 
- Separate problem-space governance discovery
- Organizational stakeholder interviews
- Review of organizational bylaws and governance policies

**Source:** Governance & Authority stream

---

### D2: Who Resolves Disputes?

**Question:** In case of election dispute, who has authority to investigate and make a binding resolution?

**Why Unresolved:**
- Dispute & Challenge stream identifies absence of explicit challenge authority
- Challenge mechanisms not documented in examined sources
- Authority chain for disputes unclear

**Why Out of Step 2 Scope:**
- Step 2 focuses on understanding what disputes exist, not who resolves them
- Resolution authority belongs to subsequent dispute resolution discovery
- May require organizational stakeholder input

**Priority:** High

**Recommended Discovery:**
- Dedicated Dispute Resolution domain discovery
- Stakeholder interviews on actual dispute handling
- Organizational policy review

**Source:** Dispute & Challenge stream

---

### D3: What are the Actual Governance Practices?

**Question:** How does the organization actually apply governance rules in practice? What informal governance exists beyond documented rules?

**Why Unresolved:**
- Governance Problem-Space stream identifies gap between solution-space (architecture) and problem-space (practice)
- Repository contains only architectural governance
- Organizational governance practices not documented

**Why Out of Step 2 Scope:**
- Step 2 investigates documented governance structure
- Actual practices require organizational interviews and observation
- Problem-space governance belongs to dedicated discovery

**Priority:** High

**Recommended Discovery:**
- Problem-space governance discovery
- Organizational interviews and observation
- Informal governance documentation

**Source:** Governance & Authority stream

---

### D4: What is the Significance of Voter/Vote Separation?

**Question:** The system structurally separates voter identity from votes. What is the significance of this design choice? What consequences follow from this separation? What problems does it solve?

**Why Unresolved:** 
- Evidence stream confirms technical separation exists
- Purpose or significance of separation not explicitly documented in sources
- Could serve legal, security, privacy, or organizational purposes

**Why Out of Step 2 Scope:**
- Step 2 focuses on understanding Evidence role and current relationships
- Significance of design choice belongs to design rationale discovery
- May require architectural history or domain expertise

**Priority:** Medium

**Recommended Discovery:** 
- Design rationale discovery
- Architect interviews on historical decisions
- Electoral law and best practices research

**Source:** Evidence stream

---

### D5: Are Coercion-Related Concerns Relevant to This Domain?

**Question:** Is voter coercion a concern within this organization's governance model? If so, what mechanisms address it?

**Why Unresolved:** 
- Threat model initially included coercion as a concern
- Evidence stream did not identify coercion prevention mechanisms in repository
- Unclear whether coercion is an actual organizational concern or theoretical security concern

**Why Out of Step 2 Scope:**
- Step 2 focuses on discovering actual governance relationships and evidence roles
- Coercion may not be a discovered governance concern
- Should only become debt item if evidence suggests it is a governance concern

**Priority:** Low

**Recommended Discovery:** 
- If Step 2 evidence suggests coercion is organizational concern: security governance discovery
- If not discovered as concern: monitor but defer

**Source:** Threat model (not yet discovered in Step 2)

---

### D6: What is the Relationship Between Legitimacy and Authority?

**Question:** Can authority exist without legitimacy? Can legitimacy be questioned? Who decides if an authority is legitimate?

**Why Unresolved:** 
- Both Legitimacy and Authority identified as recurring concepts
- Relationship between them unclear
- Appears philosophical/organizational, not technical

**Why Out of Step 2 Scope:**
- Step 2 investigates how Governance and Authority are structured technically
- Legitimacy relationship belongs to governance philosophy discovery
- May require organizational/political expertise

**Priority:** High

**Recommended Discovery:**
- Governance philosophy and policy discovery
- Organizational leadership interviews
- Political science or governance research

**Source:** Governance & Authority stream

---

### D7: Are There Constitutional Amendments?

**Question:** Can the constitutional rules be changed? If so, who can change them and what process must be followed?

**Why Unresolved:** 
- Constitutional rules identified as potentially distinct domain
- No amendment process documented in examined sources
- Unclear if constitution is fixed or changeable

**Why Out of Step 2 Scope:**
- Step 2 focuses on current constitutional rules, not rule evolution
- Amendment processes belong to organizational governance discovery
- May be outside election system scope

**Priority:** Low

**Recommended Discovery:**
- Organizational governance and policy discovery
- Leadership interviews on governance evolution
- Bylaws and policy evolution history

**Source:** Constitutional Rule Discovery stream

---

### D8: What is Trust?

**Question:** What constitutes trust in this organization? How is trust established? How is it lost? How is it restored?

**Why Unresolved:**
- Trust identified as one of five strongest recurring concepts across discovery
- No explicit definition found in repository
- Appears to be organizational/relational concept, not technical
- Unclear whether trust is individual, procedural, organizational, or evidential

**Why Out of Step 2 Scope:**
- Step 2 investigates context relationships and evidence roles
- Trust definition and mechanisms belong to dedicated discovery
- Trust may span multiple governance domains
- Requires organizational understanding beyond code analysis

**Priority:** High

**Recommended Discovery:**
- Problem-space governance discovery (Trust focus)
- Organizational stakeholder interviews
- Governance philosophy investigation
- Trust mechanism documentation

**Source:** Governance & Authority / Dispute & Challenge streams

---

### D9: What Constitutes Acceptable Evidence for Governance Decisions?

**Question:** What evidence standards must be met for governance decisions? Who establishes those standards? Are standards documented or implicit?

**Why Unresolved:**
- Evidence identified as one of five strongest recurring concepts
- Evidence mechanisms exist but standards unclear
- No explicit "evidence sufficiency" criteria found in sources

**Why Out of Step 2 Scope:**
- Step 2 investigates what evidence exists and how it flows
- Evidence standards and acceptance criteria belong to governance philosophy discovery
- May require organizational leadership input

**Priority:** Medium

**Recommended Discovery:**
- Governance philosophy and policy discovery (Evidence focus)
- Dispute resolution discovery (evidence standards for appeals)
- Organizational leadership interviews on decision criteria

**Source:** Evidence / Dispute & Challenge streams

---

## Discovery Debt Summary

| Priority | Count | Examples |
|----------|-------|----------|
| High | 4 | D1 (Legitimacy), D2 (Dispute Resolution), D3 (Actual Practices), D6 (Authority-Legitimacy), D8 (Trust) |
| Medium | 2 | D4 (Voter Separation Significance), D9 (Evidence Standards) |
| Low | 2 | D5 (Coercion Concerns), D7 (Constitutional Amendments) |

---

## Key Insight

A mature discovery program does not try to answer all questions at once.

Instead it:

```
Answers what it can investigate
    ↓
Documents what it cannot investigate
    ↓
Prioritizes unanswered questions
    ↓
Schedules future discovery
```

This register enables the second and third steps.

---

### D10: How Is Evidence Hashed for Determinism?

**Question:** ReplayEvidenceEnvelope produces deterministic hash for replay verification. What algorithm ensures determinism across runtimes/platforms?

**Why Unresolved:**
- Code uses SHA256 but object serialization method unclear
- Different PHP versions may serialize differently

**Why Out of Step 2 Scope:**
- Cryptographic implementation details
- Not directly related to strategic discovery questions

**Priority:** Medium

**Recommended Discovery:** Cryptographic determinism audit, separate from strategic discovery

**Source:** Stream 1 (ReplayEvidenceEnvelope.php)

---

### D11: What Is the Relationship Between ConstitutionalObservationContext and Evidence?

**Question:** ConstitutionalObservationContext appears alongside Evidence in multiple code paths. Is it evidence metadata or separate concept?

**Why Unresolved:**
- Code passes both together but relationship unclear
- Could be evidence classification or interpretation

**Why Out of Step 2 Scope:**
- Requires deeper investigation of PolicySequence behavior
- May be architecture-specific detail

**Priority:** Medium

**Recommended Discovery:** Investigate during Stream 4 (Audit) or Stream 5 (Constitutional Rules)

**Source:** Stream 1 (SecurityEventRecorder.php, TrustPolicyEvaluator.php)

---

### D12: How Does Eligibility Evidence Enable Divergence Detection?

**Question:** ParticipationEligibilityEvidence is hashed and used to detect divergence. What mechanism identifies divergence?

**Why Unresolved:**
- Evidence shows hash is computed and stored
- But divergence detection algorithm not examined

**Why Out of Step 2 Scope:**
- Requires investigation of divergence detection logic
- May be constitutional governance detail

**Priority:** Medium

**Recommended Discovery:** Investigate during Stream 4 (Audit) or Stream 5 (Constitutional Rules)

**Source:** Stream 1 (TrustPolicyEvaluator.php, VoteController.php)

---

### D13: What Determines "Sufficient" vs "Insufficient" Evidence?

**Question:** TrustEvaluationState includes SUFFICIENT_EVIDENCE and INSUFFICIENT_EVIDENCE states. What policies determine sufficiency thresholds?

**Why Unresolved:**
- Evidence evaluation states exist in code
- But sufficiency criteria not yet located

**Why Out of Step 2 Scope:**
- Requires deep investigation of PolicySequence evaluation
- May reveal trust domain boundaries

**Priority:** **HIGH — STRATEGIC INTEREST**

**Rationale:** If Evidence is recurring everywhere, understanding how sufficiency is determined may be more strategically important than understanding what evidence is stored. This question bridges evidence role and governance decision-making.

**Recommended Discovery:** HIGH PRIORITY for Stream 1 deepening or subsequent investigation

**Source:** Stream 1 (TrustEvaluationState enum, PolicySequence classes)

---

## Discovery Debt Summary (Updated)

| Priority | Count | Examples |
|----------|-------|----------|
| High (Strategic) | 1 | D13 (Sufficiency determination) |
| High (Operational) | 5 | D1, D2, D3, D6, D8 |
| Medium | 5 | D4, D9, D10, D11, D12 |
| Low | 2 | D5, D7 |

---

### D18: Why Is ParticipationEligibilityEvidence Frozen, Hashed, Deterministic, and Replay-Addressable?

**Question:** ParticipationEligibilityEvidence contains deterministic hash and is explicitly described as "replay-addressable." This is atypical for ordinary audit logging. Why does eligibility evidence require these properties?

**Why Unresolved:**
- Eligibility evidence is frozen at evaluation time with deterministic hash
- Comments describe it as designed for replay systems to detect divergence
- Operational use case alone does not require these properties
- May indicate deeper governance concern

**Why Out of Step 2 Scope:**
- Stream 4 identifies the artifact but not its significance
- Requires understanding of governance intent
- May belong to Constitutional Rule discovery (Stream 5)

**Priority:** **HIGH — STRATEGIC INTEREST**

**Recommended Discovery:** Stream 5 (Constitutional Rules) should investigate what governance guarantee this hashing and determinism protect

**Source:** Stream 4 (ParticipationEligibilityEvidence.php)

---

### D19: When Will Governance Replay Be Operationally Integrated?

**Question:** GovernanceStateReconstructionService is deferred to Phase 6. What are preconditions for operational integration?

**Why Unresolved:** 
- Interface exists but implementation deferred
- No timeline or prerequisites documented
- Relationship to operational audit unclear

**Why Out of Step 2 Scope:**
- Implementation planning, not evidence discovery

**Priority:** Medium

**Recommended Discovery:** Investigate Phase 6 plan

**Source:** Stream 4 (GovernanceStateReconstructionService.php)

---

### D20: Does SecurityEventRecorder Data Support Replay?

**Question:** Are SecurityEventRecorder logs intended to support governance replay, or purely operational observability?

**Why Unresolved:**
- No coupling to ReplayEvidenceEnvelope
- Design intent not explicitly documented

**Why Out of Step 2 Scope:**
- Requires design history or architect interview

**Priority:** Medium

**Recommended Discovery:** Architect intent investigation

**Source:** Stream 4 (SecurityEventRecorder.php, ReplayEvidenceEnvelope.php)

---

### D21: What Determines Replay-Readiness of Evidence?

**Question:** What makes evidence "replay-addressable"? Some evidence has deterministic hashes, others do not.

**Why Unresolved:**
- No explicit criteria documented
- Unclear if all evidence must be replay-ready

**Why Out of Step 2 Scope:**
- Requires deeper investigation of replay infrastructure design

**Priority:** Medium

**Recommended Discovery:** Investigate TrustEvaluationEnvelope construction for replay-readiness criteria

**Source:** Stream 4 (ParticipationEligibilityEvidence.php, TrustPolicyEvaluator.php)

---

### D22: Where Do Constitutional State Transition Rules Originate?

**Question:** ElectionConstitution.RULES defines state transitions, roles, and preconditions. Are these rules derived from organizational bylaws, domain requirements, or technical constraints?

**Why Unresolved:**
- Rules exist in code but no source document found
- Comments describe them as "CRITICAL RULES" but no rationale provided

**Why Out of Step 2 Scope:**
- Requires organizational knowledge or domain expert interview
- May require access to external governance documents

**Priority:** HIGH

**Recommended Discovery:** Stream 6 (Dispute & Challenge) or stakeholder interviews on rule origins

**Source:** Stream 5 (ElectionConstitution.php)

---

### D23: What Determines the Preconditions for Each Action?

**Question:** Why does `submit_for_approval` require `timezone_set` but `begin_setup` does not?

**Why Unresolved:**
- Precondition selection not explicitly documented
- Pattern not obvious from rules alone

**Why Out of Step 2 Scope:**
- Requires business logic understanding

**Priority:** MEDIUM

**Recommended Discovery:** Investigate business rationale for each precondition choice

**Source:** Stream 5 (ConstitutionalTransitionGuard.php:177-202)

---

### D24: Why Is `capacity_eligibility` a Precondition?

**Question:** Capacity eligibility affects approval workflow, but is it really a precondition for the action or a gating policy?

**Why Unresolved:**
- Unlike other preconditions, capacity is not a "fact" but a policy
- Suggests different category of rule

**Why Out of Step 2 Scope:**
- Requires architectural design understanding

**Priority:** MEDIUM

**Recommended Discovery:** Clarify distinction between fact-based and policy-based preconditions

**Source:** Stream 5 (ConstitutionalTransitionGuard.php:198-227)

---

### D25: Where Are Frozen Constitutional Policies Defined?

**Question:** ConstitutionalArticlesSnapshot captures network binding, device binding, trust overlay settings. Where do these defaults come from?

**Why Unresolved:**
- No source found for policy values
- Unknown if they're organization-specific or global defaults

**Why Out of Step 2 Scope:**
- Requires investigation of election initialization logic
- May be in application layer not yet examined

**Priority:** MEDIUM

**Recommended Discovery:** Investigate policy initialization and election creation workflow

**Source:** Stream 5 (ConstitutionalArticlesSnapshot.php)

---

### D26: What Organizational Authority Established Suspension Rules?

**Question:** Suspension/resume capability appears designed for platform intervention. What regulatory or organizational authority requires this capability?

**Why Unresolved:**
- Rules exist but no documented justification
- Suggests external requirement (regulatory, legal, organizational)

**Why Out of Step 2 Scope:**
- Requires organizational knowledge

**Priority:** MEDIUM

**Recommended Discovery:** Determine if suspension is legal requirement, organizational policy, or platform design choice

**Source:** Stream 5 (ElectionConstitution.php:125-146)

---

### D27: Where Did the 40-Voter Free/Paid Threshold Originate?

**Question:** isCapacityEligible() uses 40 voters as free tier threshold. Is this based on capacity, business model, or organizational policy?

**Why Unresolved:**
- Magic number with no documented rationale
- Implementation is incomplete (payment check stubbed)

**Why Out of Step 2 Scope:**
- Business model question

**Priority:** LOW

**Recommended Discovery:** Determine threshold origin and business model rationale

**Source:** Stream 5 (ConstitutionalTransitionGuard.php:219)

---

### D28: How Are Chief and Deputy Roles Assigned?

**Question:** ElectionConstitution specifies chief and deputy roles. Who has authority to appoint them? What does appointment mean?

**Why Unresolved:**
- ElectionOfficer model has 'appointed_by' field but assignment logic not examined
- Role lifecycle not clear

**Why Out of Step 2 Scope:**
- Requires investigation of officer appointment workflow

**Priority:** MEDIUM

**Recommended Discovery:** Investigate officer appointment use cases and authorization

**Source:** Stream 5 (ElectionConstitution.php, ElectionOfficer model)

---

### D29: What Do appointed_by and term_ends_at Mean?

**Question:** ElectionOfficer tracks who appointed the officer and when their term ends. These suggest temporal role management. How are expired terms handled?

**Why Unresolved:**
- Fields exist but enforcement not examined
- May indicate rules about role expiration

**Why Out of Step 2 Scope:**
- Requires investigation of officer lifecycle

**Priority:** MEDIUM

**Recommended Discovery:** Investigate role term enforcement and expiration handling

**Source:** Stream 5 (ElectionOfficer model)

---

### D30: Are Rules in Code Intentional or Temporary?

**Question:** Rules are hard-coded in PHP arrays, requiring code deployment to change. Is this the intended design (immutable constitution) or incomplete (awaiting rule engine)?

**Why Unresolved:**
- No documentation of architectural decision
- Stub comments suggest some features incomplete

**Why Out of Step 2 Scope:**
- Requires architect decision documentation or interview

**Priority:** HIGH

**Recommended Discovery:** Architect decision investigation on rule deployment model

**Source:** Stream 5 (ElectionConstitution.php)

---

### D31: What Other Rules Might Be Incomplete?

**Question:** Payment authorization (capacity_eligibility) is stubbed and non-functional. Are other rules similarly incomplete?

**Why Unresolved:**
- Only one stub found in visible source
- Full codebase audit not conducted

**Why Out of Step 2 Scope:**
- Requires comprehensive codebase search for TODO/stub comments

**Priority:** MEDIUM

**Recommended Discovery:** Audit for other TODO/stub comments indicating incomplete rules

**Source:** Stream 5 (ConstitutionalTransitionGuard.php:224-225)

---

### D32: Are Frozen Policies Inherited or Explicit?

**Question:** ConstitutionalArticlesSnapshot is frozen at election creation. Are policies inherited from organization defaults or explicitly configured per-election?

**Why Unresolved:**
- Election creation logic not examined
- No configuration source found

**Why Out of Step 2 Scope:**
- Requires investigation of initialization logic in application layer

**Priority:** MEDIUM

**Recommended Discovery:** Investigate election initialization to determine policy source

**Source:** Stream 5 (ConstitutionalArticlesSnapshot.php)

---

**Status: Updated After Stream 5**

**Next Update: During subsequent stream investigations as new debt items are discovered**

