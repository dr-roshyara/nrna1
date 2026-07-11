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

## Research method (ARB enhancement, 2026-07-11 — governs the literature review)

**Standing instruction to any external research tool:** *"Do NOT design our architecture. Perform a systematic literature review. Produce a Normalized Knowledge Model."* Raw prose is not a deliverable; structured knowledge is.

**1. Research quality gates** — the review is incomplete until each gate is answered:
| Gate | Question |
|---|---|
| Evidence completeness | Are multiple independent sources converging? |
| Source diversity | Academic AND industry AND tooling ecosystems represented? |
| Recency | Are we relying on pre-AI-era assumptions where that matters? |
| Contradiction surface | Are disagreements between sources explicitly mapped, not averaged away? |

**2. Evidence classification** — every finding is labeled: **Observed practice** ("teams use X") vs **Claimed benefit** ("X improves Y") vs **Evidence strength** (anecdotal · case study · wide adoption without proof · empirical validation). Descriptive consensus is never treated as causal evidence.

**3. Normalized Knowledge Model (NKM)** — the unit of research output, one per concept:
```text
Concept · Purpose · Variants · Preconditions · Failure modes · Evidence level
```
The synthesis step consumes NKMs, never raw articles.

**4. Negative findings are mandatory** — what does NOT work, where approaches failed, what was abandoned and why. A review reporting only successes has failed the survivorship-bias check.

**5. Promotion criteria** — a researched concept may enter the candidate ubiquitous language only if: cross-domain evidence (≥2 independent disciplines from the table above) · survives the contradiction surface · aligns with existing principles (anonymity, provenance, append-only) · respects rule parsimony (no rule explosion).

**6. Time-box + confidence** — each objective above is time-boxed; findings carry an explicit confidence level (High/Medium/Low by source quality and consistency); a provisional conclusion with a marked uncertainty beats an unbounded search.

**7. Feedback loop** — implementation later becomes a research *producer*: failed implementations return here as first-class evidence for the next research cycle.

*(This method section serves EPIC-002 first. If a second research effort adopts it unchanged, it earns promotion to a platform research protocol — evidence first, per the placement litmus.)*

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
