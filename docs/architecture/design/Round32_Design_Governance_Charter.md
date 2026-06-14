# Round 32 — Design Governance Charter

**Date:** 2026-06-08

**Phase:** Design Phase Governance Establishment

**Type:** Authoritative Governance Document

**Authority:** Architecture Review Board Chair

**Purpose:** Establish HOW design will be governed. NOT to perform design or make design decisions.

---

## 1. Context & Authority

**Approved Prerequisite State:**
- Rounds 17-29: Discovery COMPLETE
- Round 30: Assessment COMPLETE
- Round 31: Readiness Review APPROVED
- Round 31A: Design Authorization APPROVED (Option B)

**Design Authorization Status:**
Conditionally authorized. Unresolved governance questions remain active.

**This Charter Governs:**
All design work conducted in Round 32 and beyond, pending governance resolution.

---

## 2. Authority Structure

**Architecture Review Board (ARB):**
- Final authority on governance matters
- Approval required for:
  - Design direction changes
  - Governance question responses
  - Escalations from design team
  - Transition to next phase

**ADR (Architecture Decision Record) Authority:**
- Design team author ADRs
- ADRs require ARB review before being marked as APPROVED
- ADRs may be marked PROPOSED during design
- PROPOSED ADRs may be refined without re-review

**Escalation Authority:**
- Design team escalates governance ambiguities to ARB
- ARB responds per governance process timing
- No design decision may proceed without governance clarity

---

## 3. ADR Requirements for Design Phase

**Mandatory ADRs:**
Every design decision that affects:
- Aggregate boundaries
- Context boundaries
- Invariant enforcement
- Decision ownership
- Discovered model refinement

requires an ADR.

**ADR Lifecycle:**
1. PROPOSED — Design team author, documents decision rationale
2. REVIEW — ARB review (may occur during design)
3. APPROVED — ARB approves, decision is binding

**ADR Content Requirements:**
- Title
- Status (PROPOSED / APPROVED)
- Decision (what is being decided)
- Rationale (why this decision)
- Alternatives (what else was considered)
- Consequences (what changes as result)
- Foundation (reference to Round 17-31A artifacts)
- Governance dependencies (if any)

**ADR Authority Rule:**
Any ADR affecting:
- Context boundaries
- Aggregate boundaries
- Invariant ownership
- D35 / D36 / D37 / ADH-1
- D42B

requires ARB approval before implementation.

---

## 4. Literature Usage Governance

**Literature may:**
- Inform design decisions
- Suggest patterns and approaches
- Provide reference implementations
- Support D42B investigation

**Literature may NOT:**
- Override discovered contexts
- Override discovered aggregates
- Override discovered invariants
- Override discovered decision ownership
- Redefine ubiquitous language
- Force redesign of discovery findings

**Governance Rule:**
If literature suggests redesigning a discovery finding, escalate to ARB before proceeding.

Example:
- ✅ "ElectionGuard uses PublicDigitalBallotBox for verifiability" (informational)
- ❌ "Therefore Voting must own verifiability" (override of D42B discovery)

---

## 5. Governance Question Handling (D35, D36, D37, ADH-1)

**Active Unresolved Questions:**

**D35 — Legitimacy Consequences**
**D36 — Arbitration Invocation Authority**
**D37 — Legitimacy Enforcement**
**ADH-1 — Authority Hierarchy**

**Governance Rule:**
Design may NOT assume answers to these questions.

Design may prepare integration patterns for:
- When D35 is resolved
- When D36 is resolved
- When D37 is resolved
- When ADH-1 is resolved

but may not finalize implementation patterns.

**Escalation Requirement:**
If design encounters an ambiguity that depends on D35/D36/D37/ADH-1, escalate to ARB immediately.

**Resolution Process:**
When D35/D36/D37/ADH-1 are resolved:
1. ARB Design Review required
2. Impact assessment on design completed
3. Rework authorization (if required)
4. Design team proceeds or redesigns

---

## 6. Design Knowledge Gap Handling (D42B)

**D42B Classification:** Design Knowledge Gap

**D42B Question:** Who owns the verifiability guarantee? Or is verifiability an implicit property?

**Governance Rule:**
D42B investigation is authorized during design phase.

Investigation must:
- Evaluate evidence from approved sources
- Produce recommendation with rationale
- Follow literature governance rules

Investigation must NOT:
- Override discovered discovery findings
- Presuppose solution
- Force new bounded contexts
- Redesign Vote aggregate outside authorized scope

**D42B Escalation:**
When investigation produces recommendation, escalate to ARB with full analysis.

ARB approves integration of D42B decision into design.

---

## 7. Design Review Process

**ARB Design Reviews Required:**

**Periodic Governance Review 1**
- Review initial ADRs created
- Assess governance question handling
- Confirm boundaries are being respected
- Authorize continuation

**Periodic Governance Review 2**
- Review D42B investigation progress
- Assess governance compliance
- Authorize continuation

**Periodic Governance Review 3 — Design Phase Closure**
- All ADRs reviewed and approved
- Governance compliance verified
- Authorization for Round 32 closure

**Review Authority:**
ARB has sole authority to approve or reject design direction.

---

## 8. Governance Compliance Definition

**Design is governance-compliant when:**

✅ All design decisions have ADRs
✅ All ADRs reference Round 17-31A foundation
✅ Discovered contexts may not be merged, split, or redefined without ADR + ARB approval
✅ Discovered aggregates may not be dissolved without ADR + ARB approval
✅ Discovered invariants may not be weakened without ADR + ARB approval
✅ Discovered decision ownership may not be reassigned without ADR + ARB approval
✅ D35/D36/D37/ADH-1 questions are tracked but not assumed answered
✅ D42B investigation is tracked per governance rules
✅ All governance ambiguities escalated to ARB
✅ Literature is used per literature governance rules, never overriding discovery

**Design is NOT governance-compliant when:**

❌ Design decisions exist without ADRs
❌ ADRs exist without governance foundation reference
❌ Discovered contexts are merged without ADR + ARB approval
❌ Discovered aggregates are dissolved without ADR + ARB approval
❌ Discovered invariants are weakened without ADR + ARB approval
❌ Design assumes answers to D35/D36/D37/ADH-1
❌ D42B investigation is conducted without governance oversight
❌ Literature is used to override discovery findings

---

## 9. Exit Criteria for Round 32

**Round 32 is COMPLETE when:**

✅ All design decisions have been documented as ADRs
✅ All ADRs have been reviewed by ARB (minimum: PROPOSED status)
✅ Governance compliance has been verified
✅ ARB Design Review 3 (completion) has been conducted
✅ ARB has approved transition to next phase

**Round 32 is NOT COMPLETE until:**

❌ Governance obligations are satisfied
❌ ARB design review process is complete
❌ ARB has explicitly approved round closure

---

## ARB Governance Charter Signature

**Round 32 Design Governance Charter APPROVED**

**Governance Authority:** Architecture Review Board Chair

**Effective Date:** 2026-06-08

**Authority Scope:**
All design work conducted under Option B conditional design authorization.

**Escalation Authority:**
ARB retains sole authority over:
- Governance question responses
- Discovery model changes
- Governance compliance verification
- Phase transitions

**Governance Checkpoint Process:**
ARB conducts periodic design reviews per governance review schedule.

---

## Charter Scope Verification

**This charter GOVERNS design.**

It does NOT:
- Design aggregates
- Design contexts
- Design events or commands
- Design APIs or services
- Allocate design scope
- Schedule design work
- Propose design solutions

**Governance only. Design planning belongs in Round 32A.**

