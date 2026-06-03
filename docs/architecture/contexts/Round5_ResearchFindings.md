# Round 5 — Research Findings

**Purpose:** Present architectural intent discovery results on D1, D2, D3  
**Date:** 2026-06-03  
**Method:** Decision-focused research on git history, ADRs, code documentation  
**Scope:** Evidential findings only; no design recommendations; no governance decisions

---

## Research Performed

### Research Step 1: Commit Message Analysis

**Searched:** Git commit 89914ce3 ("security domain created")

**Finding:** Commit message provides minimal detail:
- Message: "security domain created"
- No explanation of purpose
- No architectural rationale
- Scope: 949 files changed (large refactor across security domain)

**Observations:**
- Large simultaneous introduction suggests intentional redesign
- Minimal commit message suggests either quick implementation or historical gap in documentation

---

### Research Step 2: D.0.3c Phase Definition Search

**Searched:** Entire codebase for D.0.3c, D-phase, middleware retirement references

**Finding:** D.0.3c is referenced but NOT defined:

| Reference Location | Content |
|-------------------|---------|
| DivergenceObserved docstring | "Critical telemetry event for D-phase migration. Zero crossings is the gate condition for D.0.3c (middleware retirement)" |
| SovereigntyBoundaryCrossed docstring | "Gate condition for D.0.3c (middleware retirement)" |
| ARB_Review_Package.md | Documents D.0.3c as undefined |
| Repository search result | No phase definition found in docs/, ADRs, roadmap, or code comments |

**Observation:**
- Two security domain events explicitly reference D.0.3c as a gate condition
- No artifact in the codebase defines what D.0.3c is, when it occurs, or what it means
- Critical architectural artifact appears to be missing or external to this repository

---

### Research Step 3: Architecture Decision Records (ADRs)

**Searched:** ADR documents for security recording, event strategy, domain event design

**Findings:**

| ADR | Topic | Result |
|-----|-------|--------|
| ADR-001 | Constitutional Capability Sovereignty | Not relevant to security recording |
| ADR-003 | Lifecycle vs Phase Projection | Defines general phase concepts; does not define D.0.3c |
| No ADR found | Security Event Recording Strategy | **Architectural decision not recorded** |
| No ADR found | D.0.3c Migration Phase Definition | **Critical artifact not documented** |

**Observation:**
- No architectural decision record captures why SecurityEventRecorder exists or how it relates to domain events
- No decision record documents the security event recording strategy
- Absence of ADR suggests decision may not have been formally captured

---

## D1 Findings: SecurityEventRecorder Classification

### Evidence

**Operational Status:**
- Actively called in TrustPolicyEvaluator during every vote evaluation (line 68)
- Tested in ElectionSecurityEvent test suite (SecurityEventRecorderTest.php)
- Writes to ElectionSecurityEvent database table (append-only)

**Operational Behavior:**
- Fire-and-forget semantics: returns void, never throws, never awaits
- Never blocks voting: recording failure caught and logged (line 31-37 of SecurityEventRecorder)
- Records AFTER all trust decisions are made (Step 7 in TrustPolicyEvaluator)

**Privacy Design:**
- Raw IP addresses hashed before SecurityEventRecorder receives them (TrustEvidencePrivacyPolicy, line 42)
- `voter_slug_id` explicitly set to null (SecurityEventRecorder line 55)
- No user_id stored
- No raw device fingerprints stored

**Storage Invariants:**
- Append-only: ElectionSecurityEvent prevents updates (save() method throws)
- Immutable: No delete operations permitted (delete() method throws)
- Retention policy: 730 days default

### Current Confidence: HIGH

**Basis for confidence:**
- Multiple independent code sources confirm operational behavior consistently
- Test suite validates fire-and-forget semantics
- Privacy design is observable in execution flow
- Immutability constraints are enforced in persistence layer

**Strength of confidence:**
- Code inspection provides direct evidence
- Behavior is not speculative or inferred
- Implementation details can be independently verified

### Unknowns

| Question | Why It Matters |
|----------|---------------|
| **Is SecurityEventRecorder permanent or temporary?** | Affects architectural permanence; operational status does not determine lifespan |
| **What is D.R.3 referenced in docstring?** | If D.R.3 contradicts "infrastructure" classification, assessment may need revision |
| **Will SecurityEventRecorder be replaced by another mechanism?** | Operational status does not determine whether replacement is planned |

---

## D2 Findings: Domain Events Status

### Evidence

**Operational Status:**
- Five domain event classes defined: ObservationRecorded, LegitimacyGranted, LegitimacyEvaluated, DivergenceObserved, SovereigntyBoundaryCrossed
- All introduced in commit 89914ce3a (2026-05-29)
- No dispatch sites found (grep exhaustive)
- No event listeners registered in EventServiceProvider
- No test files dispatch these events

**Design Quality:**
- Complete class definitions with typed payloads
- Detailed docstrings describing intended semantics
- Consistent with domain event naming patterns
- Appears to be intentional design, not throwaway code

**Design Differences from SecurityEventRecorder:**
- LegitimacyGranted carries `voterIdentifier` field (SecurityEventRecorder nulls this)
- DivergenceObserved carries `voterIdentifier` field
- SovereigntyBoundaryCrossed carries `voterIdentifier` field
- Domain events use voter identity; operational recorder explicitly avoids it

**D.0.3c References:**
- DivergenceObserved docstring: "Critical telemetry event for D-phase migration. Zero crossings is the gate condition for D.0.3c (middleware retirement)"
- SovereigntyBoundaryCrossed docstring: "Gate condition for D.0.3c (middleware retirement)"
- Two of five events explicitly reference D.0.3c as gate condition

### Current Confidence: MEDIUM

**Basis for confidence:**
- Code clearly shows operational status (inactive, not dispatched)
- Docstrings suggest migration context
- Design quality indicates intentional work, not accidental code

**Limitation of confidence:**
- D.0.3c is not defined in repository; cannot determine what "gate condition" means
- Multiple valid interpretations remain possible given undefined D.0.3c
- Purpose of voterIdentifier field unknown from code inspection alone

### Multiple Valid Interpretations

| Interpretation | Supporting Evidence | Status |
|----------------|-------------------|--------|
| Migration instrumentation; will be removed after D.0.3c completes | Two events reference D.0.3c migration gate; no dispatch suggests not yet integrated | Plausible |
| Future architecture; will be dispatched after D.0.3c | Design quality suggests intentional architecture; docstrings are detailed | Plausible |
| Abandoned experiment; no longer planned | No dispatch, no listeners, no integration | Plausible but less likely |
| Architectural exploration; both approaches considered | Both SecurityEventRecorder and domain events designed; no clear winner yet | Plausible |

**Confidence gap reason:**
- Cannot determine which interpretation is correct without D.0.3c definition
- Code inspection alone cannot provide semantic intent

### Unknowns

| Question | Why It Matters |
|----------|---------------|
| **What is D.0.3c?** | Two events reference it as gate condition; definition not in repository |
| **What is voterIdentifier in the events?** | Field exists; semantic meaning unknown from code alone |
| **Will domain events eventually be dispatched?** | Operational status does not determine future integration |
| **Are domain events temporary or permanent?** | Critical for understanding their role in architecture |

---

## D3 Findings: Relationship Between Systems

### Evidence

**Different Operational Status:**
- SecurityEventRecorder: actively dispatched, tested, writes to database
- Domain events: not dispatched, no listeners, no consumers

**Different Design Philosophy:**
- SecurityEventRecorder: explicitly nulls voter identity (privacy-preserving)
- Domain events: carry voterIdentifier field (voter-linked)

**Different Architectural Vocabulary:**
- SecurityEventRecorder docstring: "fire-and-forget," "infrastructure," "observational"
- Domain events docstrings: "SOVEREIGN," "gate condition," "migration"

**Simultaneous Introduction:**
- Both introduced in same commit (89914ce3, 2026-05-29)
- No evidence of replacement or deprecation of one by the other
- Suggests intentional design decision to include both

**Different Decoupling Factors:**
- SecurityEventRecorder: no external references in docstrings
- Domain events: explicitly reference D.0.3c migration phase
- Suggests they may be decoupled from same external factors

### Current Confidence: MEDIUM

**Basis for confidence:**
- Both systems clearly exist and observable differences are real
- Design choices are documented and verifiable

**Limitation of confidence:**
- Relationship interpretation depends on D.0.3c definition (external to repository)
- Possible relationships remain unconfirmed by code alone

### Possible Relationships

| Relationship Model | Mechanism | Status |
|-------------------|-----------|--------|
| **Supplementary** | Both permanent; serve different constituencies | Possible |
| **Sequential** | Domain events will replace SecurityEventRecorder after D.0.3c | Possible; depends on D.0.3c definition |
| **Complementary** | Each serves different architectural boundary; coexist permanently | Possible |
| **Competing** | Alternative approaches being explored; one will be selected | Possible but less likely |
| **Unrelated** | Different concerns, different timelines, no interaction | Possible |

### Unknowns

| Question | Why It Matters |
|----------|---------------|
| **Will domain events replace SecurityEventRecorder?** | Determines architectural trajectory |
| **Are they designed for different audiences?** | voterIdentifier presence suggests voter-level capability vs. SecurityEventRecorder's audit-level aggregation |
| **Is D.0.3c completion the trigger for change?** | If yes, relationship depends on D.0.3c definition |
| **Do they belong to the same bounded context?** | Affects Evidence Context design scope |

---

## H1 Impact: Evidence Context Hypothesis

### Original Hypothesis

**H1:** Evidence is a valid, independent bounded context

---

### Evidence Before Round 5

- Bounded context language identified (observational, frozen, preservation)
- Invariants articulated (EVI-1, EVI-5, I-1, VR-4)
- Aggregate candidates proposed (ConstitutionalEvidenceSnapshot)
- Scenario matrix drafted (7 scenarios through constitutional authority chain)

**Confidence before Round 5:** Plausible

---

### Evidence After Round 5

**Strengthening H1:**
- SecurityEventRecorder exhibits evidence preservation characteristics (append-only, immutable, privacy-conscious)
- Operational security recording mechanism already exists and functions independently
- Security domain created with substantial architectural redesign (commit 89914ce3 indicates intentional domain separation)

**Potentially Weakening H1:**
- Domain events carry voterIdentifier field (opposite design philosophy from evidence preservation principles)
- No clear boundary between Evidence and Evaluation contexts in the implementation

**Neutral Evidence:**
- Domain events are not yet operational; cannot evaluate their architectural role
- SecurityEventRecorder's relationship to Evidence Context unclear (could be infrastructure supporting Evidence, or unrelated system)

### Current Confidence on H1

**Before Round 5:** Plausible  
**After Round 5:** Strengthened (but not proven)

**Reasoning:**
- SecurityEventRecorder demonstrates that evidence preservation as an architectural concern is real and implemented
- Large security domain refactor suggests intentional separation of concerns
- D2/D3 remain unresolved; cannot determine whether domain events represent Evidence Context or different capability

---

## Discovery Boundaries

### This Research Can Answer

✓ What code exists (five domain events, SecurityEventRecorder)  
✓ What is operational (SecurityEventRecorder is; domain events are not)  
✓ What is inactive (five domain events not dispatched)  
✓ What references exist (D.0.3c references, commit history)  
✓ What architectural artifacts are missing (D.0.3c definition not in repository)  
✓ What design differences exist (voter identity handling, dispatch methods)  

### This Research Cannot Answer

✗ What D.0.3c means (requires external knowledge)  
✗ Why the events were created (requires author intent)  
✗ Whether events are temporary or permanent (requires D.0.3c definition)  
✗ Intended future architecture (requires author roadmap)  
✗ Whether Evidence Context should exist (requires ARB judgment)  
✗ Whether to proceed with design (requires governance decision)  

### Where Discovery Stops

**This document provides:** Evidence, confidence levels, unknowns

**This document does NOT provide:** Design recommendations, governance decisions, architectural conclusions

**Next decision point:** ARB evaluates evidence and decides next steps

---

## Open Questions

| Question | Source | Category |
|----------|--------|----------|
| **What is D.0.3c?** | Domain event docstrings | Critical for D2 and D3 |
| **Will domain events be dispatched?** | Inactive status observed; future unknown | Critical for H1 and Evidence Context scope |
| **Will SecurityEventRecorder be replaced?** | Operational status confirmed; permanence unknown | Important for architectural alignment |
| **What does voterIdentifier contain?** | Field exists; semantic unknown | Important for privacy implications |
| **Are the two systems related?** | Both exist; relationship unconfirmed | Important for bounded context boundaries |
| **Is Evidence Context a single context or multiple?** | Hypothesis undecided | Critical for design scope |

---

## ARB Inputs

### Finding 1: SecurityEventRecorder is Operational Infrastructure

**Input to ARB:**
- SecurityEventRecorder actively records security observations during voting
- Fire-and-forget semantics prevent recording failures from affecting voting
- Privacy design explicitly removes voter identifying information
- Immutability constraints preserve evidence integrity

**Confidence:** HIGH

**Available for ARB consideration:**
- Permanent architecture or temporary mechanism?
- Part of Evidence Context or independent system?
- Should Evidence Context design assume SecurityEventRecorder as permanent?

---

### Finding 2: Domain Events Exist but Are Not Integrated

**Input to ARB:**
- Five domain events designed with voter identity linkage
- None are dispatched in current system
- Two reference D.0.3c migration gate condition
- Design quality suggests intentional work, not dead code

**Confidence:** MEDIUM (status clear, purpose unclear)

**Available for ARB consideration:**
- Are events migration-only instrumentation or future permanent architecture?
- Does D.0.3c definition exist outside this repository?
- What is the intended timeline for domain event integration?

---

### Finding 3: D.0.3c Is Undefined in Repository

**Input to ARB:**
- Two domain events explicitly reference D.0.3c as gate condition
- No phase definition, roadmap entry, ADR, or documentation found
- Critical artifact either exists externally or is missing

**Confidence:** CERTAIN (absence is confirmed)

**Available for ARB consideration:**
- Can design proceed without D.0.3c definition?
- Should author be consulted on D.0.3c scope and status?
- Is D.0.3c a documented phase or an undocumented concept?

---

### Finding 4: Two Security Recording Approaches Coexist

**Input to ARB:**
- SecurityEventRecorder: voter identity explicitly nulled
- Domain events: voter identity explicitly present in payload
- Both introduced in same commit; no deprecation signals
- Different design philosophies suggest different purposes or audiences

**Confidence:** HIGH (both exist and differ observably)

**Available for ARB consideration:**
- Do both approaches serve different constituencies and should coexist?
- Is one approach superseding the other?
- How do they relate to Evidence Context boundaries?

---

### Finding 5: H1 (Evidence Context) Hypothesis is Strengthened by Operational Evidence

**Input to ARB:**
- SecurityEventRecorder demonstrates evidence preservation as real architectural concern
- Security domain refactor indicates intentional separation of concerns
- Evidence of domain boundary implementation, not just hypothesis

**Confidence:** MEDIUM (strengthened but not proven permanent)

**Available for ARB consideration:**
- What impact does strengthened H1 have on future investment decisions?
- Does SecurityEventRecorder represent the Evidence Context infrastructure layer?
- Are domain events part of Evidence Context or separate capability?

---

## Summary

| Component | Confidence | Status |
|-----------|-----------|--------|
| **D1: SecurityEventRecorder = Infrastructure** | HIGH | Operational status confirmed; permanence unknown |
| **D2: Domain Events = [Status Unknown]** | MEDIUM | Design quality confirmed; purpose depends on D.0.3c |
| **D3: Relationship = [Unknown]** | MEDIUM | Both exist; relationship depends on D.0.3c |
| **H1: Evidence Context = Valid BC** | Moderately Strengthened | Operational evidence supports hypothesis; D2/D3 unresolved limits confidence |
| **Critical Missing Artifact: D.0.3c Definition** | N/A | References exist; definition not in repository |

---

**Status: Round 5 research complete. Evidence collected on D1, D2, D3, and H1. ARB has inputs for governance decision.**

**Next Step:** ARB decision on how to address D.0.3c undefined status and D2/D3 uncertainty.
