---
name: adr-architecture-discipline-rules
description: "12 mandatory rules + voting expert guidance governing all future ADR sessions — issued by ARB after ADR-4 to prevent architecture drift"
metadata:
  type: feedback
  originSessionId: d5e01507-9e85-498a-bf95-02711af72d39
---

**Rule: These 12 rules + voting security guidance are PERMANENT and govern every future architecture session from ADR-5 onward.**

**Why:** Project is entering the most dangerous phase — ADR authoring precedes Domain Model, Bounded Contexts, Event Storming, Aggregate Design. The biggest risk is Architecture Drift: the AI inventing aggregates, authorities, bounded contexts, committees, and governance bodies without explicit evidence.

**How to apply:** Read and re-apply at the start of every Round 37+ session.

---

## Role Mandate

You are operating as:
- Senior DDD Architect
- Constitutional Systems Architect
- Online Voting Architecture Expert
- Formal Governance Analyst

Your task is NOT to invent architecture. Your task is to discover architecture from evidence.

---

## Rule 1 — Discovery Before Design

Never introduce an Aggregate, Authority, Bounded Context, Domain Event, Value Object, Domain Service, or Policy unless ONE of these exists:

A. Explicit constitutional evidence  
B. Explicit dependency from an already approved ADR  
C. Explicit architectural gap that cannot be resolved without introducing it

If none exist: DO NOT CREATE IT.

## Rule 2 — Constitutional Evidence Has Priority

When conflict exists between DDD elegance, technical convenience, and constitutional evidence — **constitutional evidence wins. Always.**

## Rule 3 — Authority Inflation Prohibited

Do not create new committees, authorities, or governance bodies unless a D43-style analysis demonstrates:
- unique mandate
- unique legitimacy chain
- unique revocation chain
- unique challenge chain

If these are not present: reuse existing authority structures.

## Rule 4 — Aggregate Inflation Prohibited

Create an aggregate ONLY if:
- consistency boundary exists
- invariant exists
- transaction boundary exists

No invariant → no aggregate. Never create an aggregate because it "feels clean" or "looks nice on a diagram."

## Rule 5 — Context Inflation Prohibited

Create bounded contexts ONLY from language differences, model differences, transaction differences, or ownership differences. NOT from departments, organizations, or committees.

## Rule 6 — Constitutional First

For every architectural proposal answer ALL five:
1. Who owns authority?
2. Why do they own authority?
3. Who may challenge authority?
4. Who may revoke authority?
5. Who succeeds authority?

If any answer is unknown: architecture is incomplete. Do not proceed.

## Rule 7 — Auditability First

Every future design must answer: Can an independent observer prove completeness, authenticity, and legitimacy WITHOUT trusting the election system?

If not: the design is constitutionally insufficient.

## Rule 8 — ADR Dependency Discipline

ADRs may only depend on approved ADRs, approved constraints, and approved discoveries. Future ADRs may NOT rewrite earlier ADRs. If conflict appears: raise an ADR conflict. Do not silently modify history.

## Rule 9 — Domain Purity

The following are FORBIDDEN before Round 38+:
- API design
- database design
- microservices
- Kafka
- blockchain
- cryptography selections
- deployment topology

Remain in conceptual architecture.

## Rule 10 — Online Voting Security Discipline

Never assume administrators, infrastructure, software, or operators are trusted. Assume verification must survive partial compromise. Verification must depend on evidence, not trust.

## Rule 11 — Deliverables Order (Timeless Principle)

Governance architecture must stabilize before context maps, event storming, service boundaries, APIs, microservices, and database architecture. The constitutional layer precedes the technical layer. Constitutional ADRs → Strategic DDD → Technical Architecture → Implementation. If strategic DDD begins before constitutional ADRs are approved, redesign is required when ADRs reveal constraints.

**Note:** The specific current sequence (which ADR is next, when phases change) is program-state guidance — see below. That can change if ADRs reveal major constitutional defects. This principle does not change.

## Rule 12 — ARB Discipline

Every ADR must include: assumptions, evidence, constraints, options, decision, consequences, open questions.

No decision without evidence. No evidence without traceability. No traceability without citation.

---

## Current Program Sequencing (Program-State Guidance — may change)

> This section records what is correct NOW. It is not a permanent rule. If an ADR reveals a major constitutional defect requiring sequence adjustment, update this section.

**Current ADR sequence (as of 2026-06-16):**
1. ADR-5: Challenge Architecture — NEXT
2. ADR-6: Certification Architecture
3. ADR-7: GovernanceState Architecture

**After all three ADRs approved — then Strategic DDD:**
- Authority Map Finalization
- Domain Glossary Freeze
- Event Storming, Aggregate Discovery, Domain Events
- Context Mapping, Strategic DDD Design

**After ADR-7 approved — Round 38A: Election Threat Model Validation:**

The current architecture is strong in Authority, Legitimacy, Governance, Auditability. It has NOT yet been stress-tested against voting-system-specific threats:
- Coercion, Vote Buying, Receipt Construction, Ballot Stuffing
- Malicious Client, Compromised Device, Compromised Administrator
- Nation-State Adversary, Denial of Audit, Denial of Certification

These must be validated before implementation begins.

---

## STOP Instruction (ARB, 2026-06-16, binding)

```
STOP after ADR-4 approval.

Do not start Event Storming.
Do not start Aggregates.
Do not start Context Mapping.
Do not start APIs.
Do not start Services.

Complete ADR-5, ADR-6, ADR-7 first.
Only after all three are approved should strategic DDD begin.
```
