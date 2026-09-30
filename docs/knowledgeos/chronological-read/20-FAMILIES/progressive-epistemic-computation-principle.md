# progressive-epistemic-computation-principle

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "Level* = argmin[Compute(L)+ExpectedError(L)]", "Progressive Epistemic Computation" · **Aliases:** "HSMM Escalation Score", "anytime KnowledgeOS"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0012, scope OBJECT: "Formal principle: KnowledgeOS shall not perform more expensive inference than required by evidence/temporal-complexity/context/uncertainty/consequence, subject to Assurance(L)>=RequiredAssurance and Applicability(L)=true; operationalized via an HSMM Escalation Score E_HSMM = weighted sum of uncertainty, duration dependence, conflict, regime-change signal, temporal volatility, missing-observation complexity, with escalation threshold calibrated from false-negative/false-positive/compute-budget costs rather than an arbitrary confidence cutoff; five 'anytime' response levels (Insufficient Evidence / Deterministic Result / Fast Probabilistic Estimate / Duration-Aware HSMM Estimate / Smoothed Deep Analysis) so the system never pays Level-4 cost for a Level-1 question; every computational shortcut must declare what it approximates and what uncertainty it introduces (worked YAML example)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0467 §"HSMM Escalation Score: E_HSMM = w1*U + w2*D + w3*C + w4*R + w5*V + w6*M. ... The threshold itself should be calibrated ... tau = f(false negative cost, false positive cost, computational budget)."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0665 §"KnowledgeOS Epistemic Intermediate Representation (EIR) ... KnowledgeOS could change the computational economics of AI systems. ... This is the only argument in the entire corpus that justifies a Kernel by what it saves rather than by what it protects."]
- CANDIDATE-FORMAL-BIRTH: [S0467 §"HSMM Escalation Score: E_HSMM = w1*U + w2*D + w3*C + w4*R + w5*V + w6*M. ... The threshold itself should be calibrated ... tau = f(false negative cost, false positive cost, computational budget)."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0665. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S0665), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S0467 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0467 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0467, S0467 |
| dependencies | PRESENT | S0467, S0467, S0467 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S0467 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Illustrates the accuracy principle ('every computational shortcut must declare what it approximates') with a worked inference-explanation YAML contrasted against a bare 'AI is 97% confident' statement. [S0467]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0467] types=[FORMALIZATION] scope=OBJECT — "HSMM Escalation Score combines uncertainty, duration dependence, conflict, regime-change signal, temporal volatility, and missing-observation complexity into a single escalation trigger, with the threshold itself calibrated from decision costs rather than fixed arbitrarily -- 'the question is not can we run HSMM, it is is the expected accuracy gain worth the computational cost and risk.'" (anchor: "HSMM Escalation Score: E_HSMM = w1*U + w2*D + w3*C + w4*R + w5*V + w6*M. ... The threshold itself should be calibrated ... tau = f(false negative cost, false positive cost, computational budget).")
- [S0467] types=[INVARIANT, CONSTRAINT] scope=THEORY-LEVEL — "Accuracy-preserving pruning invariant (Zero+Godel+Negative-Epistemology combination): duration/state-space pruning must record discarded probability mass explicitly rather than silently treating it as impossible; approximation error must always be retained or bounded, never erased by the optimization." (anchor: "Pruning must never silently become 'impossible.' If the discarded probability mass is epsilon, we record it. ... duration_pruning: retained_probability: 0.997, discarded_tail: 0.003. ... Optimization may reduce computation; it may not erase uncertainty.")
- [S0467] types=[EXAMPLE, ARGUMENT] scope=CROSS-OBJECT — "Illustrates the accuracy principle ('every computational shortcut must declare what it approximates') with a worked inference-explanation YAML contrasted against a bare 'AI is 97% confident' statement." (anchor: "HSMM was not used because observed duration distributions are sufficiently close to the geometric assumption, current state entropy is low, evidence coverage is high, and the decision is below the HSMM escalation risk threshold. That is much better than: 'AI is 97% confident.'")
- [S0665] types=[CONCEPT, HYPOTHESIS] scope=OBJECT — "Introduces the Epistemic Intermediate Representation (EIR) design proposal and identifies its accompanying claim ('KnowledgeOS could change the computational economics of AI systems') as the corpus's only argument justifying a Kernel by what it saves (cost) rather than what it protects (invariants/accountability/admission); flags the source supplies no measurements, so this is a hypothesis about economics, not evidence of it, marked research-only; notes EIR itself sits at representation-altitude, outside the Kernel even if adopted, with no placement claimed by the source." (anchor: "KnowledgeOS Epistemic Intermediate Representation (EIR) ... KnowledgeOS could change the computational economics of AI systems. ... This is the only argument in the entire corpus that justifies a Kernel by what it saves rather than by what it protects.")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
- family.files_touching lists ['S0470'] in addition to the source_ids that appear in family.rows — no row from ['S0470'] appears in this label's row list. Noted as a data-completeness oddity for P3, consistent with a pattern seen in other labels processed in this batch.
- Rows for this label were captured under more than one scope tag (['CROSS-OBJECT', 'OBJECT', 'THEORY-LEVEL']) — this may reflect genuine cross-scope relevance (e.g. an OBJECT used at THEORY-LEVEL) rather than a labeling error, but P3 may want to confirm.
