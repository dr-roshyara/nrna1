# Round 48-00 — Architectural Decision Quality Criteria (ADQC) v1.1

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** Strategic DDD (Phase II) · **Built against:** Certified Release v1.0 + Strategic Domain Landscape v1.0
**Status:** 📏 EVALUATION RUBRIC (v1.1 — revised per architect review). The software-side verification engine. **Every Strategic DDD decision (Round 48+) is scored against these criteria.**
**Date:** 2026-06-26 · *(v1.0 → v1.1: renamed Q1/Q2/Q4; sharpened Q3/Q7/Q8; added Q10/Q11; added categories + weights + 4-level scale + Fail→ADR rule. Prior scorecards in 48-01/49-01/49-03 used v1.0; future passes use v1.1.)*

> **Why.** Phase II optimizes *architecture quality*. The ADQC turns SD-1..7 + Forbidden Transformations + ownership architecture + boundary-evidence criteria (`Round47-OP`) into a **per-decision scorecard.** It evaluates **decisions**, not diagrams.

## Verdict scale (4 levels)
**PASS · MINOR CONCERN · MAJOR CONCERN · FAIL.** *(was Pass/Concern/Fail — "Concern" covered too much.)*

## Gating & the Fail rule
- **Gating (blocking) criteria: Q7 Anonymity and Q9 Certification Compliance.** A **FAIL** on either **blocks** the decision (Critical Fail) — mirrors the governance validator's "Critical blocks."
- **Every FAIL on *any* criterion MUST produce** an **ADR**, an **Architecture Issue**, or a **Governance Change Request** (SD-6). **Nothing fails silently.**

## Criteria (categorized + weighted)

### Semantic Quality
| # | Criterion | Question | Weight |
|---|-----------|----------|-------:|
| **Q1** | **Domain Semantic Integrity** *(was "Semantic fidelity")* | Does the decision **preserve** the certified concept's meaning (Canonical Vocabulary / ontology)? | 2 |
| **Q2** | **Ownership Integrity** | Exactly **one authoritative owner** per system-of-record (**many consumers permitted**)? | 2 |

### Architectural Quality
| # | Criterion | Question | Weight |
|---|-----------|----------|-------:|
| **Q3** | **Autonomy** | Can the context perform its **core business decisions** without another context participating **synchronously**? | 2 |
| **Q4** | **Cohesion** | Does the context have **high business cohesion** (one cohesive responsibility, not SRP code-cohesion)? | 2 |
| **Q5** | **Coupling** | Dependencies minimal, read-only/async — no **temporal coupling** (A *cannot continue until* B responds)? | 2 |
| **Q10** | **Business Invariant Integrity** *(new)* | Are domain invariants preserved **without a cross-context transaction**? | 3 |
| **Q11** | **Context Boundary Clarity** *(new)* | Can a developer clearly tell **which context owns a decision** (no two equally responsible)? | 2 |

### Governance Quality
| # | Criterion | Question | Weight |
|---|-----------|----------|-------:|
| **Q6** | **Traceability** | Declares Package/Vocabulary/Ontology/Landscape versions built against (SD-7)? | 1 |
| **Q9** | **Certification Compliance** 🔒 | Consumes only admitted concepts; honors Forbidden Transformations + 5 constraints + SD-1..7? | **5 (gating)** |

### High-Assurance Quality
| # | Criterion | Question | Weight |
|---|-----------|----------|-------:|
| **Q7** | **Anonymity** 🔒 | Is it **impossible to *reconstruct*** voter↔vote identity — not merely "no foreign key," but no re-identification via `user_id`+timestamp+IP+logs+ordering? | **5 (gating)** |

### Evolution Quality
| # | Criterion | Question | Weight |
|---|-----------|----------|-------:|
| **Q8** | **Evolutionary Stability** | Will it survive **knowledge evolution** of any release type (Patch/Minor/Major — Breaking flagged), without rework? | 1 |

## Scoring
- Each criterion: PASS / MINOR CONCERN / MAJOR CONCERN / FAIL, weighted.
- **Critical Fail** = FAIL on Q7 **or** Q9 → decision blocked regardless of total.
- Weighted total gives an **Architecture-Quality** indicator across the five categories; MAJOR CONCERNs are addressed before finalizing; MINOR CONCERNs recorded.
- **Q10 is one of the strongest predictors of bad decomposition** — a cross-context-transaction invariant almost always means the boundary is wrong.

## Q10 / Q11 rationale (the new criteria)
- **Q10 Business Invariant Integrity:** Strategic DDD ultimately protects business invariants (Evidence, Finality, Mandate, Determination each have invariants). **FAIL:** an invariant requires multiple contexts to commit together → the boundary is misplaced.
- **Q11 Context Boundary Clarity:** **FAIL:** two contexts appear equally responsible for a decision → boundary ambiguity (the most common DDD defect).

## Use
Applied as a summary scorecard in Context Mapping, Bounded-Context confirmation, and Aggregate design. **Any FAIL → ADR / Architecture Issue / Governance Change Request** (never silent). Gating FAIL (Q7/Q9) → do not produce the architecture; explain why (protocol self-review).

---

*Round 48-00 — Architectural Decision Quality Criteria — v1.1 ISSUED.*
*11 criteria in 5 categories (Semantic Q1-Q2 · Architectural Q3-Q5,Q10-Q11 · Governance Q6,Q9 · High-Assurance Q7 · Evolution Q8); weighted; 4-level scale (PASS/MINOR/MAJOR/FAIL); Q7 Anonymity + Q9 Certification gating (weight 5). NEW: Q10 Invariant Integrity (no cross-context txn), Q11 Boundary Clarity. Q7 = impossible reconstruction (not just linkage). Every FAIL → ADR/Issue/GCR. Reusable high-assurance DDD framework.*
