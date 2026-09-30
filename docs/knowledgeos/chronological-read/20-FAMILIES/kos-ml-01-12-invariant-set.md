# kos-ml-01-12-invariant-set

**Scope(s):** THEORY-LEVEL · **Row count:** 5 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `KOS-ML-01..KOS-ML-12` · **Aliases:** `Assessment Separation, Leakage Prevention, Association/Causation Separation, etc.`
**Candidate group membership (NOT an identity claim):**
- **G0886**: [`kos-global-invariant-set-20` · `kos-ml-01-12-invariant-set`] — working_label token overlap Jaccard=0.60 (shared tokens: ['invariant', 'kos', 'set'])

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope THEORY-LEVEL): Twelve candidate invariants extracted from statistical learning theory: KOS-ML-01 a candidate knowledge artifact and its assessment are distinct semantic objects; KOS-ML-02 assessment must declare construction-evidence vs assessment-evidence; KOS-ML-03 leakage prevention; KOS-ML-04 assessment must cover the whole production pipeline not just the final artifact; KOS-ML-05 criterion explicitness; KOS-ML-06 no assessment metric is universal truth; KOS-ML-07 association != causation; KOS-ML-08 discovery produces candidates not authority; KOS-ML-09 complexity cost awareness; KOS-ML-10 independence must not be assumed merely from separate generation; KOS-ML-11 rare-but-important evidence must not be excluded by prevalence-based discovery; KOS-ML-12 every method must disclose its assumptions/invariants/sensitivities. Explicitly staged as SOURCE FACT -> DDD INTERPRETATION -> RESEARCH HYPOTHESIS -> EXPERIMENT -> GOVERNANCE -> ADOPT/ADAPT/REJECT, not yet Constitution-level.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0469] §"variables were selected using the complete dataset before cross-validation. The resulting CV error was dramatically optimistic — 3% versus a true error of 50%. ... Cross-validation must cover the entire modeling sequence, including selection/filtering steps."
- CANDIDATE-CONCEPTUAL-BIRTH: [S0469] §"Four agents agreeing does not automatically create truth. If they all depend on the same evidence, their apparent independence is false. ... Independence provenance ... Assessment independence must itself be represented and assessed."
- CANDIDATE-FORMAL-BIRTH: [S0469] §"KOS-ML-01 -- Assessment Separation ... KOS-ML-12 -- Method Assumption Disclosure. ... SOURCE FACT -> DDD INTERPRETATION -> KNOWLEDGEOS RESEARCH HYPOTHESIS -> EXPERIMENT / ARCHITECTURE ANALYSIS -> GOVERNANCE -> ADOPT / ADAPT / REJECT."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0469] §"KOS-ML-01 -- Assessment Separation ... KOS-ML-12 -- Method Assumption Disclosure. ... SOURCE FACT -> DDD INTERPRETATION -> KNOWLEDGEOS RESEARCH HYPOTHESIS -> EXPERIMENT / ARCHITECTURE ANALYSIS -> GOVERNANCE -> ADOPT / ADAPT / REJECT."

## Lifecycle
last_seen: S0469. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows) — this DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S0469 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S0469, S0469, S0469, S0469 |
| Dependencies | PRESENT | S0469, S0469, S0469, S0469, S0469 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S0469 |
| Examples | PRESENT | S0469, S0469, S0469 |
| Warnings | PRESENT | S0469, S0469 |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0469] types=[EXAMPLE, INVARIANT] scope=OBJECT — "Cross-validation-leakage example (3% apparent error vs 50% true error) generalizes into a Knowledge Evaluation Leakage Gate: assessment must not consume information that leaked from the assessment population into candidate construction, ranked HIGH-VALUE/HIGH-CONFIDENCE." (anchor: "variables were selected using the complete dataset before cross-validation. The resulting CV error was dramatically optimistic — 3% versus a true error of 50%. ... Cross-validation must cover the entire modeling sequence, including selection/filtering steps.")
- [S0469] types=[WARNING, CONCEPT] scope=OBJECT — "Ensemble/random-forest decorrelation reframed as Independent Assessment Aggregation with the explicit warning that multiple assessments are not independent merely because they were generated separately -- particularly relevant to the platform's existing V-3 independence-gate work; formalized as KOS-ML-10." (anchor: "Four agents agreeing does not automatically create truth. If they all depend on the same evidence, their apparent independence is false. ... Independence provenance ... Assessment independence must itself be represented and assessed.")
- [S0469] types=[WARNING, EXAMPLE] scope=OBJECT — "Rare-evidence-exclusion warning (KOS-ML-11): prevalence-based discovery mechanisms can systematically exclude rare-but-high-consequence engineering knowledge (a single specific migration failure), so prevalence/consequence/confidence/novelty/rarity must be tracked as separate dimensions, not conflated into one frequency score." (anchor: "high-confidence or high-lift rules with low support may never be found because of the support threshold. ... rare + high consequence ... 'This migration procedure failed once under a very specific condition.' Frequency-based discovery may discard it. ... Frequency is not equivalent to importance.")
- [S0469] types=[DISTINCTION, EXAMPLE] scope=OBJECT — "Model bias vs estimation bias distinction generalized into 'Domain limitation != Inference limitation' -- distinguishing 'our evidence cannot represent this' from 'our inference method failed', valuable for KnowledgeOS assessment diagnostics." (anchor: "model bias — mismatch between the true function and the best representation available in the model class; estimation bias — error introduced by the estimation procedure itself. ... 'Our evidence cannot represent this phenomenon.' is different from: 'Our inference method failed to recover the phenomenon.'")
- [S0469] types=[GOVERNANCE, FORMALIZATION] scope=THEORY-LEVEL — "Complete twelve-item KOS-ML invariant set with an explicit staged promotion pipeline (Source Fact -> DDD Interpretation -> Research Hypothesis -> Experiment -> Governance -> Adopt/Adapt/Reject), and a six-theme research backlog KR-01..KR-06 (Knowledge Assessment, Epistemic Leakage & Boundary Control, Knowledge Stability & Uncertainty, Knowledge Complexity & Parsimony, Knowledge Discovery, Discovery Error & Independence)." (anchor: "KOS-ML-01 -- Assessment Separation ... KOS-ML-12 -- Method Assumption Disclosure. ... SOURCE FACT -> DDD INTERPRETATION -> KNOWLEDGEOS RESEARCH HYPOTHESIS -> EXPERIMENT / ARCHITECTURE ANALYSIS -> GOVERNANCE -> ADOPT / ADAPT / REJECT.")

## Notes for P3
Carries 1 candidate group membership (G0886); P3 should decide whether it reflects the same underlying object as the other label(s) in that group, or merely a surface-signal coincidence. Lifecycle (DORMANT) rests on recency heuristics only — no explicit retraction, supersession, or self-contradiction signal was found in this label's own rows.
