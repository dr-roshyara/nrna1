# prime-implicate-minimal-gap-hypothesis

**Scope(s):** OBJECT · **Row count:** 5 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `K∪H⊨r while K⊭r, H minimal`, `MinimalGap(K,r)` · **Aliases:** `minimal missing assumptions for Gap`
**Candidate group membership (NOT an identity claim):**
- **G0570**: candidate group with `gap-formalization` — explicit agent-stated uncertainty: 'prime-implicate-minimal-gap-hypothesis' POSSIBLY relates to 'gap-formalization' (batch B0061). Note: Hypothesis that Gap could be formalized via prime-implicate-style minimal sets of missing assumptions needed to entail a requirement, refining the prior definition Gap = {r | not Sat(K,r)}. (mechanical signal only; relationship not yet decided, P3).



## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0061, scope OBJECT): Hypothesis that Gap could be formalized via prime-implicate-style minimal sets of missing assumptions needed to entail a requirement, refining the prior definition Gap = {r | not Sat(K,r)}.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2520] §"a prime implicate as a minimal clause entailed by the KB ... MinimalGap(K,r) ... K\cup H\models r while K\not\models r and H is minimal"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2520] §"a prime implicate as a minimal clause entailed by the KB ... MinimalGap(K,r) ... K\cup H\models r while K\not\models r and H is minimal"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2524] §"KR.1 Representation Layers [PROP] ... KR.6 Open-World Constraint [ESTABLISHED] — Supported by DL's open-world semantics ... KR.10 Expressiveness and Tractability [ESTABLISHED] — Now a KnowledgeOS methodological principle"

## Lifecycle
last_seen: S2526. Candidate lifecycle: ACTIVE. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S2520, S2523 |
| Type signature | PRESENT | S2520, S2521, S2523 |
| Invariants | PRESENT | S2523, S2526 |
| Dependencies | PRESENT | S2526 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S2524, S2526 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2520] types=[FORMALIZATION, HYPOTHESIS, EXTENSION] scope=CROSS-OBJECT — "Prime implicates (minimal clauses entailed by a KB) suggest a formal candidate for Gap via MinimalGap(K,r): a minimal set H of missing assumptions such that K∪H⊨r while K⊭r, refining Gap beyond the prior definition Gap = {r | not Sat(K,r)}; the extraction's own mapping ('minimal sets of assumptions that explain observations') is explicitly refined rather than adopted verbatim." (anchor: "a prime implicate as a minimal clause entailed by the KB ... MinimalGap(K,r) ... K\cup H\models r while K\not\models r and H is minimal")
- [S2521] types=[HYPOTHESIS, LIMITATION] scope=CROSS-OBJECT — "Restates the minimal-support-completion candidate for Gap (K∪H⊨r, K⊭r, H minimal under a declared criterion) grounded in prime implicates, explicitly flagged as only a research candidate that must not be adopted as Gap's definition until its relationship to evidence, boundary, determination and provenance is established." (anchor: "Instead of merely Gap(K,r)=\neg Eval(K,r), we can investigate whether a gap admits a minimal support completion K\cup H\models r ... This is only a research candidate. It must not be adopted as the definition of Gap until its relationship to evidence, boundary, determination and provenance has been established.")
- [S2523] types=[FORMALIZATION, CORRECTION] scope=CROSS-OBJECT — "Explicitly rejects LCS/MSC/Unification=Gap, treating them as computational machinery that may participate in a future Gap operation; refines the Gap candidate into Gap*(K,r) = {H | K∪H⊨r and K⊭r} (all minimal-support completions, not a single set), replacing the boolean Gap(K,r)=¬Sat(K,r) — a research candidate, not a final definition." (anchor: "LCS/MSC/Unification\neq Gap ... They provide computational machinery that may participate in a future Gap operation ... Gap^*(K,r)=\{H\mid K\cup H\models r \land K\not\models r\}")
- [S2524] types=[RESTATEMENT, GOVERNANCE] scope=THEORY-LEVEL — "Restates the ten-section KR.1-KR.10 theory insertion verbatim from S2523 with an explicit status tag assigned to each section: KR.1 Representation [PROP], KR.2 Reasoning [PROP], KR.3 Subsumption [PROP], KR.4 Classification [PROP], KR.5 Consistency [PROP], KR.6 Open-World Constraint promoted to [ESTABLISHED], KR.7 Evaluation [PROP], KR.8 Explanation/Gap [PROP], KR.9 Transition [PROP] (Boundary != FrameAxiom retained), KR.10 Expressiveness/Tractability promoted to [ESTABLISHED] as a KnowledgeOS methodological principle -- the only two sections elevated above [PROP]." (anchor: "KR.1 Representation Layers [PROP] ... KR.6 Open-World Constraint [ESTABLISHED] — Supported by DL's open-world semantics ... KR.10 Expressiveness and Tractability [ESTABLISHED] — Now a KnowledgeOS methodological principle")
- [S2526] types=[CORRECTION, DISTINCTION] scope=CROSS-OBJECT — "Rejects Gap = non-entailed-fluent as too narrow: K⊭r could indicate any of nine distinct states (false, unknown, insufficient evidence, evaluator unavailable, contradiction, missing assumption, scope exclusion, unobservable, theory incomplete) which prior contradiction experiments showed must not collapse into one non-entailment category; NonEntailment != Gap, but NonEntailment -> GapCandidate is legitimate as a weaker relation." (anchor: "unsatisfied requirements = non-entailed fluents ... K\not\models r could mean: false, unknown, insufficient evidence, evaluator unavailable, contradiction, missing assumption, scope exclusion, unobservable, theory incomplete ... NonEntailment \neq Gap but NonEntailment \rightarrow GapCandidate is legitimate.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
