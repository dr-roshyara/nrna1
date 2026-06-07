# Round 24C — Election Integrity Literature Mapping

**Date:** 2026-06-07

**Phase:** Literature-to-ARB Mapping (Pre-Decision Governance Support)

**Status:** Complete — Awaiting ARB Review

**Purpose:** Map strategic concepts from election integrity literature against DDD classification framework. Determine whether each concept represents a bounded context, domain capability, quality attribute, or cross-cutting concern. This is evidence mapping only — no architecture or boundary decisions.

---

## 1. Classification Framework

For each concept, the ARB must determine:

| Classification | Definition | Example |
|---------------|-----------|---------|
| **Bounded Context** | Owns unique decisions, has distinct language, requires independently evolving model | Trust Attestation, Authorization |
| **Domain Capability** | A coherent set of business functionality that may cross contexts but is not independently evolving | Verification checks, Evidence sealing |
| **Quality Attribute** | A system property that is assured across contexts, not owned by any single context | Privacy, Auditability |
| **Cross-Cutting Concern** | Affects multiple contexts but does not constitute its own decision boundary | Security, Compliance |

---

## 2. Concept Mapping

### 2.1 Privacy

**Definition (literature):** Votes cannot be linked to individual voters. Voter cannot prove vote choice to others. Voter cannot be coerced.

**Sub-concepts identified in literature:**
- Anonymity (vote cannot be linked to voter)
- Receipt-Freeness (voter cannot prove vote choice)
- Coercion Resistance (voter cannot be coerced during process)

**Evidence from Rounds 17-23:**
- Anonymity: explicitly supported — no user_id in votes table, vote hash unlinkability, encrypted vote storage
- Receipt-Freeness: not addressed — system provides receipts for verifiability, creating potential receipt-freeness concern
- Coercion Resistance: not addressed — no advanced coercion resistance mechanisms

**Candidate contexts that touch this concept:**
- Voting (anonymity, receipt generation)
- Trust Attestation (verification process)
- Audit (privacy in logging)

**DDD Classification Assessment:**

| Sub-concept | Owns Unique Decisions? | Distinct Language? | Requires Independent Model? | Classification |
|------------|----------------------|-------------------|---------------------------|----------------|
| Anonymity | No — enforced by Voting model design (no user_id) | No — uses voting language | No — embedded in Voting | **Quality Attribute** |
| Receipt-Freeness | No — would be a constraint on Voting behavior | No | No | **Quality Attribute** |
| Coercion Resistance | No — would require additional mechanisms but no unique decision ownership | No | No | **Quality Attribute** |

**Assessment: Privacy is a QUALITY ATTRIBUTE — not a bounded context.**

Privacy is assured across the Voting context through design constraints (no user_id, anonymous hashes, encrypted storage). It does not own decisions, does not have its own language, and does not require an independently evolving model. It is enforced within Voting, not managed by a separate context.

---

### 2.2 Verifiability

**Definition (literature):** Voters and/or observers can verify that votes were correctly recorded and counted.

**Sub-concepts identified in literature:**
- Individual Verifiability (voter verifies own vote)
- Universal Verifiability (any observer verifies all votes)
- Vote Correctness (proof that ballot is well-formed)

**Evidence from Rounds 17-23:**
- Individual Verifiability: explicitly supported — receipt hash verification, code-based verification, published results
- Universal Verifiability: not observed — no public verification mechanism; Governance Evidence Replay aligned but internal and deferred
- Vote Correctness: not formally evidenced — application-level validation exists but no cryptographic proofs

**Candidate contexts that touch this concept:**
- Voting (receipt verification, integrity checksums)
- Results/Tallying (count verification)
- Governance Evidence Replay (replay verification)
- Verification (potential — currently a function within Voting)

**DDD Classification Assessment:**

| Sub-concept | Owns Unique Decisions? | Distinct Language? | Requires Independent Model? | Classification |
|------------|----------------------|-------------------|---------------------------|----------------|
| Individual Verifiability | No — implemented as methods on Vote model | No — uses voting language | No | **Domain Capability** (embedded in Voting) |
| Universal Verifiability | Yes — would require independent verification decisions | Potentially — verification vs. voting language | Perhaps — could evolve independently | **Potential Bounded Context** (currently unresolved) |
| Vote Correctness | No — validation check on vote submission | No | No | **Domain Capability** |

**Assessment: Verifiability is primarily a DOMAIN CAPABILITY, with Universal Verifiability as a POTENTIAL BOUNDED CONTEXT.**

Individual verifiability is embedded within Voting as receipt and checksum methods. It does not require its own context. Universal verifiability, if required, would likely need a separate verification mechanism with independent decision ownership (is the evidence sufficient? does the tally match?). Whether this warrants a bounded context depends on whether universal verifiability is a governance requirement (D42B).

---

### 2.3 Accountability

**Definition (literature):** All election actions can be traced to specific actors, and there are mechanisms for dispute resolution and consequence enforcement.

**Sub-concepts identified in literature:**
- Audit Trail (actions recorded with actor, timestamp, context)
- Dispute Resolution (mechanisms to challenge and resolve)
- Consequence Enforcement (governance decisions about impact)

**Evidence from Rounds 17-23:**
- Audit Trail: explicitly supported — ElectionAuditLog, SecurityEventRecorder, per-voter JSONL files
- Dispute Resolution: partially observed via distributed mechanisms (Arbitration, Governance Evidence Replay, Governance decisions) — no explicit challenge mechanism
- Consequence Enforcement: unresolved — D35/D36/D37 (legitimacy consequences, invocation, enforcement not observed)

**Candidate contexts that touch this concept:**
- Audit (operational recording)
- Governance Evidence Replay (verification)
- Constitutional Governance (decision recording)
- Arbitration/Legitimacy (consequence determination)
- Challenge/Dispute (distributed capability)

**DDD Classification Assessment:**

| Sub-concept | Owns Unique Decisions? | Distinct Language? | Requires Independent Model? | Classification |
|------------|----------------------|-------------------|---------------------------|----------------|
| Audit Trail | Yes — what to log, when to rotate | Yes — audit, log, event, record | Yes — fire-and-forget, no coupling | **Bounded Context** (Audit) |
| Dispute Resolution | Distributed — no single owner | No — borrows from arbitration, replay | Distributed | **Distributed Capability** (Challenge/Dispute) |
| Consequence Enforcement | Unresolved — D35/D36/D37 | Potentially — legitimacy, arbitration language | Unresolved | **Unresolved** (Arbitration/Legitimacy) |

**Assessment: Accountability spans multiple DDD classifications.**

- Audit Trail is a **Bounded Context** (Audit — accepted in Round 23 evidence assessment)
- Dispute Resolution is a **Distributed Capability** (Challenge/Dispute — Outcome F from Stream 6A)
- Consequence Enforcement is **Unresolved** (Arbitration/Legitimacy — boundary pending D35/D36/D37)

---

### 2.4 Coercion Resistance

**Definition (literature):** Voter cannot be coerced into voting a particular way, nor can they prove to a coercer how they voted. Stronger than receipt-freeness.

**Evidence from Rounds 17-23:**
- Vote anonymity provides basic protection (cannot prove vote choice)
- No advanced coercion resistance mechanisms observed
- No documented threat model for coercion
- Device fingerprint tracking could theoretically enable coercion (potential concern)

**Candidate contexts that touch this concept:**
- Voting (anonymity)
- Trust Attestation (verification process)
- None currently own coercion resistance decisions

**DDD Classification Assessment:**

| Sub-concept | Owns Unique Decisions? | Distinct Language? | Requires Independent Model? | Classification |
|------------|----------------------|-------------------|---------------------------|----------------|
| Coercion Resistance (basic) | No — enforced by Voting model | No | No | **Quality Attribute** |
| Coercion Resistance (advanced) | Would require new mechanisms | Would potentially require new language | Unlikely — would be additional constraints on Voting | **Quality Attribute** |

**Assessment: Coercion Resistance is a QUALITY ATTRIBUTE — not a bounded context.**

Like privacy, coercion resistance is a system property that is assured through design constraints and enforcement mechanisms, not through independent decision ownership. It does not have its own language, does not own decisions, and does not require an independent model.

---

## 3. Classification Summary

| Concept | Classification | Evidence Confidence |
|---------|---------------|-------------------|
| **Privacy** | **Quality Attribute** (enforced within Voting) | HIGH |
| **Verifiability** | **Domain Capability** (Individual: embedded in Voting; Universal: potential future context) | MEDIUM |
| **Accountability** | **Mixed** (Audit: Bounded Context; Dispute: Distributed Capability; Consequence: Unresolved) | HIGH |
| **Coercion Resistance** | **Quality Attribute** (not currently evidenced) | MEDIUM |

---

## 4. Mapping to ARB Deliberation Questions

### Q8 (New ARB Question): Does each candidate bounded context own business decisions, or does it merely implement an election quality attribute?

Applying this to current candidates:

| Candidate | Owns Business Decisions? | Or Quality Attribute? | Classification Confidence |
|-----------|------------------------|----------------------|--------------------------|
| Trust Attestation | ✅ Yes — verification decisions | Business Context | HIGH |
| Eligibility | ✅ Yes — eligibility decisions | Business Context | HIGH |
| Authorization | ✅ Yes — permission decisions | Business Context | HIGH |
| Constitutional Governance | ✅ Yes — lifecycle decisions | Business Context | HIGH |
| Audit | ✅ Yes — recording decisions | Business Context | HIGH |
| Voting | ✅ Yes — vote recording decisions | Business Context | HIGH |
| Results/Tallying | ❌ No unique decisions observed | **Potential Quality Attribute** (of Voting) | MEDIUM |
| Governance Evidence Replay | ⚠️ Yes — verification decisions, but deferred | Business Context or Domain Capability | MEDIUM |
| Arbitration/Legitimacy | ✅ Yes — validity decisions | Business Context (boundary with Governance unresolved) | MEDIUM |
| Challenge/Dispute | ❌ No unique decisions observed | Distributed Capability | HIGH |

**Key insight:** Results/Tallying and Challenge/Dispute are the only candidates that do not own unique business decisions. This supports the evidence analysis from Rounds 22-23.

---

## 5. Impact on ARB Deliberation

### What This Mapping Confirms

1. **Privacy is NOT a bounded context** — it is a quality attribute assured within Voting. No new context needed.

2. **Verifiability is partially a domain capability, partially a potential context** — individual verifiability is embedded in Voting; universal verifiability may warrant its own mechanism but is not currently required.

3. **Accountability spans multiple DDD patterns** — Audit is a context, Challenge/Dispute is distributed, Arbitration/Consequence enforcement is unresolved. This is consistent with current candidate structure.

4. **Coercion Resistance is NOT a bounded context** — it is a quality attribute. No new context needed.

### No Changes to Candidate Context Proposals

The literature mapping does **not** suggest new contexts, mergers, or splits. It confirms that the current candidate structure from Rounds 19-23 aligns well with formal election integrity theory. The literature independently validates the separation between Eligibility, Authorization, Voting, Audit, and Arbitration as distinct concerns.

---

## 6. Remaining Governance Questions

**Q1:** Should universal verifiability be a governance requirement, or is individual verifiability sufficient?

**Q2:** If universal verifiability is required, does it warrant a separate Verification context, or is it a capability within Governance Evidence Replay?

**Q3:** Does the organization have a documented threat model that would determine whether coercion resistance is a concern?

**Q4:** Is the literature-supported separation of Privacy, Verifiability, and Accountability into distinct concepts a useful lens for future discovery phases?

---

**Round 24C Election Integrity Literature Mapping — READY FOR ARB REVIEW**

**4 strategic concepts classified. 0 new bounded contexts suggested. 0 candidate mergers or splits proposed. Current candidate structure validated against formal election integrity theory. Q8 (decision ownership vs. quality attribute) added to ARB deliberation framework. ARB now has sufficient evidence for Round 25 Context Acceptance Decisions.**
