# Trust Domain Ubiquitous Language

**Status:** Active Domain Modeling  
**Date:** May 30, 2026  
**Scope:** Public Digit Identity Verification and Trust Architecture

---

## Core Principle

Every term in this language answers: **"What trust decision does this enable?"**

---

## Primary Terms

### Verified

**Definition:**
A trusted officer has reviewed available evidence and attested, based on judgment and organizational standards, that the claimed identity can be trusted for a defined purpose and time period.

**Purpose:**
Establish foundational trust in identity itself.

**Enables:**
- Eligibility evaluation
- Audit trail creation
- Trust context for anomaly detection

**Does NOT Enable:**
- Voting (eligibility must also be true)
- Membership (membership application must be approved)
- Delegation (authorization must be granted)
- Administrative actions (role assignment required)

**Event vs. State:**
- Event: "Officer verified identity"
- State: "Verified" (current condition)

**Related Terms:**
Evidence, Trust Level, Verification Officer, Attestation, Verification Decision

---

### Trust Level

**Definition:**
The degree of assurance an organization has established, based on verification evidence and organizational policy, that a participant's identity is trustworthy for specific actions.

**Purpose:**
Accommodate different organizational risk profiles. Political parties, NGOs, unions, and cooperatives require different assurance levels.

**Possible Values:**
- `Unverified` — no trust decision made
- `Provisionally Trusted` — identity plausible, awaiting confirmation
- `Officer Verified` — single officer attestation
- `Organization Verified` — multiple officers or documentation
- `High Assurance` — government-issued evidence
- `Revoked` — trust explicitly withdrawn

**Enables:**
- Eligibility rules (election requires minimum trust level)
- Risk-based decisions (anomalies trigger different actions)
- Audit categorization (result confidence tied to trust level)
- Evolution of assurance (path from Provisionally Trusted → Organization Verified)

**Does NOT Enable:**
- Voting (eligibility must also be true)
- Authorization (role assignment required)

**Related Terms:**
Verified, Assurance Level, Evidence, Verification Officer, Trust Policy

---

### Eligible

**Definition:**
The participant meets all organizational requirements to take part in a specific process (election, membership, governance action) at a specific time.

**Purpose:**
Separate identity trust from process eligibility. Verified identity is necessary but not sufficient.

**Eligibility Dimensions:**
- Membership status (Active/Expired/Suspended)
- Fee payment status (Paid/Partial/Unpaid/Exempt)
- Membership type (Full/Associate/etc.)
- Election assignment (enrolled for specific election)
- Timing (election voting window open?)
- Geographic scope (member of correct org/region?)

**Enables:**
- Voter assignment
- Participation gates
- Audit legitimacy (person had valid standing at vote time)

**Does NOT Enable:**
- Voting without authorization (separate permission required)
- Candidacy (separate rules apply)
- Delegation (separate authorization required)
- Administrative actions (role authorization required)

**Event vs. State:**
- State: Eligibility is current condition (assessed at action time)
- Can change (membership expires, fees unpaid, status changes)

**Related Terms:**
Verified, Authorized, Eligibility Decision, Voting Eligibility, Membership Eligibility

---

### Authorized

**Definition:**
The participant has been granted permission by the organization to perform a specific action in a specific context.

**Purpose:**
Control who can do what, independent of identity trust or process eligibility.

**Authorization Dimensions:**
- Role-based (Chief Officer, Deputy, Commissioner, Member, Voter)
- Action-based (Cast Vote, Approve Candidate, Create Election, Publish Results)
- Scope-based (Election-specific, Organization-wide, Committee-scoped)
- Time-based (valid until date X, renewable, revocable)

**Enables:**
- Action execution (cast vote, approve membership, create election)
- Capability checking (precondition enforcement)
- Audit accountability (recorded who performed action)
- Role delegation (transfer authority to another participant)

**Does NOT Enable:**
- Trust in identity (separate from verification)
- Eligibility for unrelated processes

**Related Terms:**
Verified, Eligible, Role, Capability, Permission, Delegation

---

## Secondary Terms

### Attestation

**Definition:**
An officer's formal statement that evidence supporting an identity claim has been reviewed and found sufficient for organizational trust purposes.

**Purpose:**
Create accountability for trust decisions. Make decisions auditable.

**Enables:**
- Audit trail (who decided, when, based on what)
- Revocation authority (officers who can attest can revoke)
- Dispute resolution (evidence exists if trust questioned)

**Related Terms:**
Verified, Evidence, Verification Officer, Trust Decision

---

### Evidence

**Definition:**
Any artifact or observation that informs a trust decision.

**Purpose:**
Provide factual basis for trust decisions.

**Evidence Types (from current implementation):**
1. Membership Application Data (self-provided by applicant)
2. Officer Attestation Records (IP, device fingerprint, officer notes)
3. Voting Session Evidence (device continuity, network continuity)
4. Trust Context Snapshots (frozen eligibility at vote time)
5. Audit Trail Events (official record of decisions)

**Evidence Quality Hierarchy:**
- Email confirmation (lowest)
- Self-provided document
- Officer attestation
- Government-issued ID
- Multi-party verification (highest)

**Enables:**
- Trust weighting (strong evidence → high trust; weak evidence → caution)
- Revocation triggers (contradictory evidence may require revocation)
- Audit confidence (evidence quality affects result reliability)

**Related Terms:**
Verified, Attestation, Trust Level, Evidence Weighting

---

### Verification Officer

**Definition:**
An authorized participant (organization staff or volunteer) who reviews evidence and makes attestation decisions about identity trustworthiness.

**Purpose:**
Centralize trust decision authority. Create accountability.

**Officer Responsibilities:**
- Review participant-provided evidence
- Make attestation decision (verified/not verified)
- Capture decision rationale (notes)
- Capture decision context (IP, device fingerprint)
- Authority to revoke verification if evidence becomes questionable

**Enables:**
- Accountability (decisions attributed to specific person)
- Audit trail (verifiable who attested when)
- Governance (can require officer approval for high-stakes votes)

**Related Terms:**
Attestation, Verified, Trust Decision, Verification Officer Role

---

### Verification Decision

**Definition:**
The outcome of an officer's review: either verification is attested (approved) or verification is withheld (denied).

**Purpose:**
Formalize the trust decision as discrete, auditable event.

**Decision States:**
- `Attested` — officer verified identity
- `Withheld` — officer could not verify identity
- `Revoked` — previously attested verification withdrawn

**Enables:**
- Eligibility precondition check
- Audit record (explicit decision exists)
- Appeal process (if withheld, participant can provide additional evidence)

**Related Terms:**
Verified, Attestation, Evidence, Trust Decision Reversal

---

### Revocation

**Definition:**
An officer's decision to withdraw previously attested trust in an identity.

**Purpose:**
Update trust state when new evidence contradicts prior attestation.

**Revocation Triggers (Governance decides, not Verification domain):**
- New contradictory evidence emerged
- Participant requested withdrawal
- Organizational policy mandates re-verification (timeout)
- Trust suspension due to other factors

**Critical: Revocation Does NOT Automatically:**
- Invalidate past votes (governance decides)
- Block future voting (eligibility might persist)
- Void election results (governance decides)
- Trigger audit reopening (governance decides)

**Enables:**
- Updated trust state (future eligibility accounts for revocation)
- Audit clarity (record shows verification was attested, now withdrawn)

**Event vs. State:**
- Event: "Officer revoked verification"
- State: "Revoked" (current trust state)

**Related Terms:**
Verified, Trust Level, Verification Officer, Governance Policy

---

## Semantic Distinction Table

| Term | Decides | Persists | Changed By | Does Not Decide |
|------|---------|----------|-----------|-----------------|
| **Verified** | Trust in identity | Until revoked | Officer attestation | Voting, membership, roles |
| **Trust Level** | Assurance degree | Until re-verified | Evidence review | Specific actions |
| **Eligible** | Process participation | Per-process | Status changes, fees, time | Identity trust |
| **Authorized** | Action permission | Per-role | Role assignment, governance | Identity or eligibility |

---

## Key Architectural Insight

**Verification does not grant rights.**

```
Verified
    ↓ (enables evaluation of)
Eligibility
    ↓ (enables evaluation of)
Authorization
    ↓ (enables)
Action
```

This chain is compositional and separable. Each step is independent.

---

**Last Updated:** May 30, 2026  
**Domain:** Trust Attestation  
**Next:** Map to current implementation in CURRENT_IMPLEMENTATION_MAPPING.md
