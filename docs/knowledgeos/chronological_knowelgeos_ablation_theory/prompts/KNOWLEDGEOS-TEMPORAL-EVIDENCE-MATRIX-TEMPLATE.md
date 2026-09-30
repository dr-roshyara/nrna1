# Temporal-semantics evidence matrix — TEMPLATE (question-indexed; empty; no corpus read)

| | |
|---|---|
| Kind | design template. ⚠ authority: generated. Filled only under an L0 corpus release, **after** Gate 2 and Gate 2.5 are independently reproduced |
| Principle | evidence is collected **per question, never per model**. The model columns are filled only at the end, from the evidence cells, by an explicit mapping. That prevents model-driven evidence selection |
| Order of sources | CAP-001 §9 → EPIC-004 → other targeted sources, each under its own L0 release. Pointer targets are read only if released |

## Questions (each linked to the existing vocabulary; no new ids for existing concepts)

| EQ | Question | OQ | RB-1 need |
|---|---|---|---|
| EQ-1 | What exactly does authorization authorize (scope, object, duration)? | OQ-T1 | Q11 |
| EQ-2 | Is eligibility checked at authorization, at promotion, or both? | OQ-T1 | Q1, Q10 |
| EQ-3 | Can adoption survive a loss of eligibility or evidence? | OQ-T2 | Q5, Q6 |
| EQ-4 | Is revocation automatic or an explicit act? | OQ-T2 | Q6 |
| EQ-5 | Is reassessment / validation mandatory, and is it a condition or an expectation? | OQ-T4 | Q5 |
| EQ-6 | What happens when the bar or rule changes? | OQ-T3 | Q12 |
| EQ-7 | What happens to pending (authorized, not promoted) items? | OQ-T3 | Q7 |
| EQ-8 | What happens to already adopted items? | OQ-T2, OQ-T3 | Q7, Q8 |
| EQ-9 | Is grandfathering explicit? | OQ-T3 | Q7 |
| EQ-10 | Is retroactivity explicit? | OQ-T3 | Q8 |
| EQ-11 | Can later evidence overturn or confirm an adoption? | OQ-T4 | Q9 |
| EQ-12 | Is promotion an event, a state, or both? | — | Q10 |
| EQ-13 | Is there any support for the floor EF = EB ∨ E0 (evidence existing below the bar being sufficient for authorization)? | (spec modelling choice) | Q2, Q4 |

## Evidence cell (one row per passage; the fields are kept separate and never merged)

| EQ | source id | source sha256 | anchor (file + line/byte span) | exact wording | historical date | source claim (what the text asserts) | our assessment (support / contradict / silent / ambiguous for EQ) | reader class | L0 release id |
|---|---|---|---|---|---|---|---|---|---|

> **Superseded for execution by `analysis/evidence_phase/EVIDENCE-SCHEMA.md`** (the closed cell schema, the 5-way cell vocabulary SUPPORTED/REFUTED/AMBIGUOUS/SILENT/OUT-OF-SCOPE, CONFLICTING at the proposition level, the mechanical derivation). The sections below remain as the question index.

## Per-question outcome (after all released sources are read)
- **Evidence status:** SUPPORTED / CONTRADICTED / CONFLICTING / SILENT (an evidence gap).
- Silence is never read as support. Conflicting evidence is reported with dates, never averaged.

## Model implication (last; mechanical)
- For each MT model, which EQ outcomes it is **consistent with / inconsistent with / cannot express** (FRN-3 gaps).
- This produces no selection. It is input to the L0 research-decision package.

## Appendix A — elimination mapping (fixed BEFORE any corpus read; design only)

What each EQ outcome would bear on. It is written now so that elimination cannot be fitted to the evidence afterwards. An outcome **constrains**; it never selects. SILENT constrains nothing.

| EQ | If SUPPORTED that … | Bears on | Formal consequence (Gate 2.5 facts, secondary) |
|---|---|---|---|
| EQ-2 | eligibility / floor is re-checked at promotion | MT1 vs MT2 | inconsistent with MT1-P1 (strict TOCTOU is possible only there); MT2 enforces it by T-EVENT |
| EQ-2 | authorization is a durable entitlement (snapshot) | MT1 vs MT2 | consistent with MT1; MT2 adds a check the source denies |
| EQ-3 | adoption survives loss of evidence | P0 vs P1 | inconsistent with P0 (PERSIST unreachable there) |
| EQ-4 | revocation is an explicit act | P0 vs P1 | consistent with P1 (REVOC-EXPLICIT); P0 invalidates automatically |
| EQ-4 | invalidation is automatic | P0 vs P1 | consistent with P0 (AUTO-INVAL) |
| EQ-5 | validation is a **condition** of promotion | beyond the family | no MT model encodes validation as a precondition (FRN-3 gap) → a refinement candidate |
| EQ-6/7/8/9/10 | a bar change affects pending / adopted items | A6 / T5 | outside the A6 = u′ = u base; T5 only shows reachability. Refinement candidate; A6 is **not** revised here |
| EQ-13 | an exception below the bar requires evidence (E0) | EF | consistent with EF = EB ∨ E0 as the floor |
| EQ-13 | an exception needs no evidence at all | EF | inconsistent with every T-AUTH model's floor → a refinement candidate |
| EQ-13 | no exception below the bar is valid | EB-only | the R-39 shape would be a violation, not a mechanism: an L0 question, not a model result |
| EQ-1, EQ-11, EQ-12 | (scope, later evidence, event vs state) | FRN-3 gaps | no current model distinguishes these; they are recorded for refinement |

- Consequences are only as strong as the secondary reproduction. They are re-confirmed after the non-Claude verification.
