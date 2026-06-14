# Architecture Baseline v1.0

**Date:** 2026-06-13  
**Status:** Declared  
**Purpose:** Formal baseline of the NRNA constitutional governance platform architecture after completing discovery → evidence → review → decision cycle.

## Scope

This baseline captures all architectural decisions, context classifications, and governance principles established across Frontend Discovery Phase, Backend Discovery Phase, and Strategic DDD Assessment (Rounds 1-6).

## Context Classifications

| Context | Status | Evidence Basis |
|---------|--------|----------------|
| Election Governance | ✅ **Confirmed Core Domain** | Aggregate (4/4), Constitution SSOT, capability language, eligibility ownership, events, lifecycle derivation |
| Voting | ⏸ **Candidate** | Own language/services/persistence, no aggregate, no events, eligibility owned by Governance |
| Membership | ⏸ **Candidate** | Insufficient aggregate and ownership analysis |
| Organisation | ⏸ **Candidate** | Insufficient aggregate and boundary analysis |
| Trustworthiness | ❓ **Unresolved** | Evidence supports both independent and supporting-subdomain interpretations |

## Architectural Decisions (ADRs)

| ADR | Status | Summary |
|-----|--------|---------|
| ADR-001: Reuse Before Create | ✅ Accepted | Before creating new abstractions, prove existing ones cannot be reused |
| ADR-002: Incremental Frontend DDD Adoption | ✅ Accepted | No rewrite. DDD for new code only. Boy Scout Rule. |
| ADR-003: Shell Visual Identity | ✅ Accepted | Dark blue header (`primary-800/950/900`), premium trust, gold accents |
| ADR-004: Strategic Context Boundaries | ✅ Accepted | Only Election Governance confirmed. Voting/Membership/Organisation candidate. Trustworthiness unresolved. |
| ADR-005: Language Ownership & Governance | ✅ Accepted | `ElectionConstitution` is language authority. All other representations are derived and must be synchronized. |

## Architecture Review Gates

| Gate | Status | Summary |
|------|--------|---------|
| ARG-01: Strategic Context Boundaries | ✅ Approved | Election Governance confirmed. Voting/Membership/Organisation candidate. Trustworthiness unresolved. |
| **ARG-02: Language Governance** | ✅ Approved | Governance language changes across multiple layers (Constitution → Domain → Application → UX). Frontend language may diverge from backend governance language. Governance explanations are not consistently surfaced. Ownership and translation rules for governance language remain unresolved, deferred to ADR-006. |

## Architecture Principles

| Principle | Source |
|-----------|--------|
| Context status determined by evidence, not architectural preference | ARG-01 |
| Constitution is SSOT for lifecycle governance | ADR-004 |
| Capabilities are the ubiquitous language (14 constitutional actions) | ADR-004 |
| Frontend visualizes authority — does not determine it | ADR-002, ARG-01 |
| State is derived from business facts, not stored | Discovery |
| Language authority is `ElectionConstitution` — all other representations are derived | ADR-005 |

## What This Baseline Does NOT Authorize

- ❌ Context extraction or decomposition
- ❌ Microservice boundaries or separation
- ❌ Aggregate refactoring
- ❌ Trust context separation
- ❌ Voting context promotion
- ❌ Frontend domain extraction beyond existing patterns

## Baseline Artifacts

```
architecture/
├── frontend/
│   ├── 3 ADRs
│   ├── 8 discovery documents
│   ├── 2 migration plans
│   ├── 1 phase closure
│   ├── 1 page doc
│   └── 1 README
│
├── backend/
│   ├── 7 discovery documents (Rounds 1-5)
│   └── 1 phase closure
│
├── strategic/
│   ├── ROUND6_EVIDENCE_REGISTER
│   ├── ELIGIBILITY_OWNERSHIP_ANALYSIS
│   ├── CONTEXT_MAP
│   ├── BOUNDED_CONTEXT_ASSESSMENT
│   ├── ARCHITECTURE_HEALTH_REPORT
│   ├── ARG-01_ARCHITECTURE_REVIEW_GATE
│   ├── ADR-004-Strategic-Context-Boundaries
│   └── ADR-005-Language-Ownership-and-Governance
│
└── ARCHITECTURE_BASELINE_V1.md ← This file
```

## Status: STOP

The discovery → evidence → review → decision cycle is complete. This baseline declares the architecture as understood and agreed. No further refactoring, extraction, or decomposition is authorized without a new ADR supported by new evidence.

**Next phase:** Operational development within the documented architecture boundaries.
