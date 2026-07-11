# ES-002 — Engineering Execution

**Status:** PROPOSED · part of the [Standards Index](STANDARDS_INDEX.md)
**Purpose:** how engineering work is performed — the execution lifecycle and its standing defaults.
**Scope:** every engineering work item, any engineer (human or AI), any track.
**Authority:** Decision Authority (ARB).
**Qualification Method:** EP-02 completion reviews per work item; operational qualifications (OQ) for the lifecycle itself.
**Supersedes:** the MEMORY-resident texts of the implementation-first default and the observation stop (now hosted here).
**Enforcement:** AI (approval gates: Human) (see the Enforcement Matrix in the Standards Index).
**Related Standards:** ES-001 (authority) · ES-003 (verification of executed work) · ES-004 (the reports execution produces).

## Registered execution rules (governed homes — pointers, never copies)

| Rule | One-line statement | Canonical home |
|---|---|---|
| **EEP** (Engineering Execution Protocol) | plan → independent review → approval → implement → verify → report → decide; provider- and project-independent; Adopted · **Stable** | `Engineering_Execution_Protocol.md` (this directory) — its Stable status forbids absorption-by-copy |
| EP-01 · EP-02 · EP-03 · EP-01-Light | PublicDigit's **binding** of the EEP (plan-first, completion review, readiness review, lightweight gate + report format) | `docs/implementation/Implementation_Process_v1.1_Draft.md` §EP — bindings are project property (the protocol: "projects bind it; they do not fork it") |
| ER-01..ER-04 | original engineering rules (frozen process) | `docs/implementation/Implementation_Process_v1.0.md` (FROZEN) |
| ER-05..ER-09 | convergence · UL-before-PL · behavior-not-transport · reviews-record/implementations-repair · project-knowledge (PROPOSED, paused) | `Implementation_Process_v1.1_Draft.md` §ER |
| Registry-first workflow | every `.claude` runtime asset: register → review → implement → verify, with the five-question trace | `.claude/platform/registry.yaml` header (binding) + `developer_guide/ai_platform/01_registry_first_workflow.md` |

## Hosted rules (this document is their canonical home; previously MEMORY-only)

**ES-002.1 — Implementation-First Default** *(ARB 2026-07-10)*. The architecture and platform have demonstrated stability: every new ticket is an IMPLEMENTATION ticket by default. The question is *"can I implement this capability within the approved architecture?"* — only a NO, carrying implementation evidence of insufficiency, opens an ADR/ARB discussion.

**ES-002.2 — Observation Stop During Execution** *(ARB 2026-07-10)*. Unless implementation exposes a genuine deficiency in the platform, no further platform observations are recorded during ticket work — only the implementation evidence the current ticket requires. Everything else waits for the retrospective. (Standing execution instruction; companion: every change either produces or consumes executable evidence.)
