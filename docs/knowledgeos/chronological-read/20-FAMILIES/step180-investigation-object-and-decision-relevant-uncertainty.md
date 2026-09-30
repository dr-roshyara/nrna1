# step180-investigation-object-and-decision-relevant-uncertainty

**Scope(s):** `THEORY-LEVEL` · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Formalize only where the domain semantics justify formalization`, `Investigation = <Question,Scope,Hypothesis,RequiredEvidence,Owner,Status,Outcome>`, `InvestigationPriority = f(Impact,DecisionRelevance,Risk,Cost)`, `RequiredEvidence proportional to Risk x Impact x Uncertainty` · **Aliases:** `not every unknown needs investigation; evidence proportional to consequence`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 180 proposes a first-class Investigation object <Question,Scope,Hypothesis,RequiredEvidence,Owner,Status,Outcome> giving the pipeline Unknown->Investigation->Evidence->Determination, a mechanism moving from uncertainty toward knowledge. States not every unknown warrants investigation (a trivial example: what color was the engineer's coffee mug -- no governance value), formalizing InvestigationPriority=f(Impact,DecisionRelevance,Risk,Cost) to prevent KnowledgeOS from trying to resolve every possible uncertainty; introduces 'decision-relevant uncertainty' -- an unknown that could change the decision (e.g. an estimate x=8+-3 straddling a decision boundary x<10) matters, one that cannot affect the decision may be left unresolved. States RequiredEvidence should scale proportional to Risk x Impact x Uncertainty (documentation heading change vs production migration), explicitly a design principle not a final formula, and gives the closing methodological rule 'formalize only where the domain semantics justify formalization', warning specifically against introducing false-precision numeric confidence scores (confidence_score=0.873) prematurely everywhere.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1373] §"Investigation = <Question,Scope,Hypothesis,RequiredEvidence,Owner,Status,Outcome>. Then: Unknown → Investigation → Evidence → Determination. ... InvestigationPriority = f(Impact,DecisionRelevance,Risk,Cost). ... Not all uncertainty is equally decision-relevant. ... RequiredEvidence ∝ Risk×Impact×Uncertainty. ... Formalize only where the domain semantics justify formalization."
- CANDIDATE-CONCEPTUAL-BIRTH: [S1373] §"Investigation = <Question,Scope,Hypothesis,RequiredEvidence,Owner,Status,Outcome>. Then: Unknown → Investigation → Evidence → Determination. ... InvestigationPriority = f(Impact,DecisionRelevance,Risk,Cost). ... Not all uncertainty is equally decision-relevant. ... RequiredEvidence ∝ Risk×Impact×Uncertainty. ... Formalize only where the domain semantics justify formalization."
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S1373`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1373 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1373 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S1373]` types=[CONCEPT, PRINCIPLE] scope=THEORY-LEVEL — "Proposes a first-class Investigation object <Question,Scope,Hypothesis,RequiredEvidence,Owner,Status,Outcome>, giving Unknown->Investigation->Evidence->Determination as a mechanism moving from uncertainty toward knowledge. States not every unknown warrants investigation (trivial unknowns have no governance value), formalizing InvestigationPriority=f(Impact,DecisionRelevance,Risk,Cost) and introducing 'decision-relevant uncertainty' (an unknown able to change a decision, e.g. an estimate straddli…" (anchor: "Investigation = <Question,Scope,Hypothesis,RequiredEvidence,Owner,Status,Outcome>. Then: Unknown → Investigation → Evidence → Determination. ... InvestigationPriority = f(Impact,DecisionRelevance,Risk…")

## Notes for P3
- Thin evidentiary base (1 row) — any relationship claims beyond what is listed here would be unsupported.
- Ungrouped: no mechanical cross-link signal connected this label to any other label in P2a.
- No rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) rows were found for this label in the capture; the object's motivation is not evidenced here.
