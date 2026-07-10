# EPIC-002 — Problem Statement (Research Charter for Strategic Discovery)

**Kind:** research charter — NOT an IDD, NOT an ADR. This document is the constitution of the Strategic Discovery phase: it defines the problem, the questions, and the boundaries of the research. It decides nothing about the solution.
**Authorized:** ARB 2026-07-11. **Activated:** after the EPIC-001 retrospective + formal closure.
**Role instruction (ARB, binding for this phase):** *Do not think like a software architect during EPIC-002 — think like a researcher.* The researcher asks **"what exists?"**; the architect asks "what should we build?". EPIC-002 stays in the first role until the ARB authorizes an IDD.

---

## Problem

PublicDigit's constitutional correction loop (EPIC-001) produces a complete, provenance-carrying event trail — but **current operational evidence is implicit**. Events exist as messaging artifacts (outbox/inbox rows, correlation chains), not as *constitutional evidence*: nothing yet defines what makes a record admissible, immutable, publishable, replayable, or forensically inspectable. For a platform whose value proposition is trust, evidence is the next capability — and today nobody can state precisely what "Evidence" owns, where its boundaries lie, or whether it is one bounded context at all.

## Candidate bounded context

**Evidence** — explicitly a *candidate*. Discovery may conclude the candidate is too large, contains two or more contexts, or should merge with an existing concern. **Discovery must not assume its own answer.**

## Objectives (understand — not design)

1. **Ownership** — what does Evidence own vs merely observe? What do Contestation/Adjudication/Election/Messaging own that Evidence must never duplicate?
2. **Provenance** — how do the existing CorrelationId/CausationId chains (Constitutional Audit Invariant, ADR-MP-06) relate to evidential provenance and chain of custody?
3. **Admissibility** — what distinguishes *constitutional evidence* from telemetry? Who decides admissibility, and when?
4. **Immutability** — what must be tamper-evident vs merely append-only vs merely logged? What does the anonymity invariant (CI-5/Q7 — no voter↔vote linkage, ever) forbid evidence from containing?
5. **Replay** — does replay belong inside Evidence, beside it, or as a platform capability? What does "deterministic replay over constitutional history" require of evidence records?
6. **Evidence lifecycle** — capture → preservation → verification → publication → (retention/expiry?). Which stages exist in this domain at all?
7. **Interactions** — how does Evidence relate to Contestation (challenges cite evidence), Adjudication (`EvidenceEnvelopeRef` already exists in Determination — upstream dependency per the dependency map), and Legitimacy (hypothesis: read model — falsification question in scope).

## Out of scope (hard boundaries)

- ❌ No implementation. ❌ No aggregates. ❌ No repositories. ❌ No APIs. ❌ No Laravel. ❌ No IDD.
- ❌ No engineering-platform changes (production subsystem — demonstrated insufficiency only).
- ❌ No reopening of closed decisions without evidence (R49-07 order, frozen Blueprint, ADR corpus).

## Literature review (multi-disciplinary — the core research activity; meaningful time, not a day or two)

| Discipline | Topics |
|---|---|
| **Domain** | election audits · constitutional governance · parliamentary procedure · administrative law and adjudication workflows |
| **Evidence** | digital evidence management · chain of custody · provenance models (e.g. W3C PROV) · evidential reasoning |
| **Architecture** | event sourcing in regulated systems · CQRS · audit systems · tamper-evident/transparency logs (Merkle structures, WORM) |
| **Voting** | end-to-end verifiable voting · risk-limiting audits (RLAs) · election integrity and observation |

Purpose: **validate or falsify our own model** — identify durable concepts that belong in the ubiquitous language; never copy systems.

## Deliverables (Strategic Discovery artifacts ONLY)

1. **Literature review** (state of the art → PublicDigit gap analysis)
2. **Candidate ubiquitous language** for the evidence domain
3. **Ownership map** (what the candidate context owns / observes / must never touch)
4. **Context map** (relationships to the implemented core; builds on `EPIC-002_Context_Dependency_Map_Draft.md`)
5. **Open questions register** (what discovery could not resolve)
6. **Recommendation** — one bounded context / several / merge — with evidence
7. **STOP → ARB review**: the ARB decides whether the accumulated evidence authorizes an IDD.

## Success criterion

The Strategic Discovery Report can **confirm or change** the current understanding of the Evidence candidate. A report that merely restates the going-in assumption has failed; a report that falsifies it with evidence has succeeded.

---
*Charter for EPIC-002 Strategic Discovery. Inputs: EPIC-001 evidence chain · `EPIC-002_Context_Dependency_Map_Draft.md` · ADR-MP-06 provenance model · anonymity invariants (ADR-T11/CI-5/Q7). Traceability: ARB rulings 2026-07-10/11 (discovery-first, candidate-BC framing, researcher role).*
