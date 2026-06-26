# Round 48-00 — Architectural Decision Quality Criteria (ADQC)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Strategic DDD (Phase II) · **Built against:** Certified Release v1.0 + Strategic Domain Landscape v1.0
**Status:** 📏 EVALUATION RUBRIC — the software-side equivalent of the governance verification engine. **Every Strategic DDD decision (Round 48+) is scored against these criteria.**
**Date:** 2026-06-26

> **Why now (not another governance doc).** Phase I optimized *knowledge quality*; Phase II optimizes *architecture quality*. The ADQC is the instrument for that shift — it turns the certified constraints (SD-1..7, Forbidden Transformations, Anonymity, traceability) into a **checklist every context-mapping, bounded-context, and aggregate decision must pass.** It supports the doing; it is not new theory.

## The criteria (9)

| # | Criterion | Question | Fail signal |
|---|-----------|----------|-------------|
| **Q1 Semantic fidelity** | Does the decision preserve the certified concept's meaning? | drift from Canonical Vocabulary / ontology meaning |
| **Q2 Ownership consistency** | One semantic owner per authoritative truth (R45)? | two contexts own the same system-of-record |
| **Q3 Autonomy** | Can the context decide/operate without synchronous dependence on others? | needs another context's write to function |
| **Q4 Cohesion** | One decision ownership per context (single reason to change)? | a context answers two unrelated decisions |
| **Q5 Coupling** | Are dependencies read-only/downstream and minimal? | bidirectional writes; chatty sync coupling |
| **Q6 Traceability** | Declares Package/Vocabulary/Ontology/Landscape versions it was built against (SD-7)? | no version provenance |
| **Q7 Anonymity** | Does it preserve the supreme invariant (never store/link/reconstruct voter↔vote)? | any path that could link identity to vote |
| **Q8 Evolutionary stability** | Will it survive a Minor knowledge release without rework? | breaks on terminology/derived-concept change |
| **Q9 Certification compliance** | Consumes only admitted concepts; honors Forbidden Transformations + 5 constraints + SD-1..7? | uses Held/Blocked concept; violates a Forbidden Transformation |

## Scoring

Each decision: **Pass / Concern / Fail** per criterion. **Q7 (Anonymity) and Q9 (Certification compliance) are gating** — a Fail on either **blocks** the decision (mirrors the governance validator's "Critical blocks"). Q1–Q6, Q8 Concerns are recorded and addressed; they do not block unless they accumulate.

## Relationship to existing discipline

- Q9 enforces SD-1..7 + Forbidden Transformations + the 5 carried constraints (R45/R46).
- Q2/Q3/Q4/Q5 operationalize the ownership architecture (R45) and standard DDD context-design heuristics.
- Q7 is the schema/query/test enforcement of the Anonymity invariant (already realized in code).
- Q6/Q8 enforce Knowledge Release Governance traceability + SemVer-survivability.

**Use:** applied as a summary scorecard in Round 48 (context map), Round 49 (bounded contexts), Round 50 (aggregates). A decision that Fails a gating criterion is revised or routed to a governance item (never silently overridden — SD-6).

---

*Round 48-00 — Architectural Decision Quality Criteria — ISSUED (rubric for Strategic DDD).*
*9 criteria; Q7 Anonymity + Q9 Certification-compliance are gating. Operationalizes SD-1..7 + Forbidden Transformations + ownership architecture as a per-decision scorecard. Applied from Round 48 on.*
