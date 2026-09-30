# corpus-theory-audit-kr-zero-vs-kr-rep-2026-09

**Scope(s):** THEORY-LEVEL · **Row count:** 3 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "103/256 R1 contexts contain metadata"; "CORPUS-THEORY-AUDIT.md"
**Aliases:** "KR-ZERO vs KR-REP corpus-theory audit findings"
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0063, scope THEORY-LEVEL: "The reported findings of an executed CORPUS-THEORY-AUDIT.md: the old KR-ZERO corpus independently reproduces its published 1,395-case, {k=1:1252,k=2:25,k=3:3,irreducible:115} distribution from cases.jsonl/cases.csv/property-results.json; confirms old R1-R4 are independently-sampled PARALLEL representation classes with no R_n->R_{n-1} transformation in the generator (not a reduction hierarchy); and discovers the old generator is itself DEFECTIVE -- R1 was supposed to mean token-only, but 103 of 256 R1-labelled contexts contain metadata because three generator code paths bypass the class constructor -- so the old representation-class experiment cannot be used to make representation-reduction claims. Confirms the new R5->R4->R3->R2 reduction chain, inquiry Q, constraints C, decoder O, adequacy, fiber analysis, H(R|Q), the metric vector, and held-out evaluation are ALL currently ABSENT from the repository. Commissions a design-only (no implementation) follow-up task producing KR-REP-REDUCTION-DESIGN-2026-09.md with sixteen required design questions ... plus eight explicit stop conditions, concluding with a binary verdict READY FOR IMPLEMENTATION / NOT READY."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2644 §"the old generator itself is defective. R1 was supposed to mean token-only, but 103/256 R1 contexts contain metadata because three generator shapes bypass the class constructor."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2644 §"Do NOT assume that E_S(D)=D\S is valid ... Define Q : X -> Answers BEFORE defining transformations T5...T2 ... At the end provide a concise verdict: READY FOR IMPLEMENTATION or NOT READY."]

## Lifecycle
last_seen: S2644. Candidate lifecycle: ACTIVE. Evidence: no retraction, no superseding row, no self-contradiction flag; ACTIVE here is a heuristic based on recency of the (single) source_id S2644, which underlies all three captured rows, not a confirmed ongoing-work status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2644 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2644, S2644 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The audit's purpose is to determine whether the corpus's older KR-ZERO representation-class experiment can support representation-reduction claims, and whether a newer KR-REP reduction-chain theory is implemented anywhere yet. It finds the old generator itself defective — 103 of 256 R1-labelled ("token-only") contexts actually contain metadata because three generator code paths bypass the class constructor — which independently disqualifies the old experiment from supporting reduction claims regardless of the separate R1–R4-vs-hierarchy naming issue [S2644]. It further finds that none of the elements the new theory needs (the R5→R2 chain itself, an independently-defined inquiry Q, constraints C, decoder O, fiber analysis, H(R|Q), the metric vector, held-out evaluation) exist anywhere in the repository yet, so the audit concludes the next step must be a design-only task, not implementation [S2644]. It closes by specifying a sixteen-section design-only prompt (for `KR-REP-REDUCTION-DESIGN-2026-09.md`) plus eight stop conditions and a mandatory binary READY-FOR-IMPLEMENTATION / NOT-READY verdict, explicitly forbidding code or dataset generation at this stage [S2644].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S2644] types=[EXPERIMENTAL-RESULT, LIMITATION] scope=OBJECT — "Reports the CORPUS-THEORY-AUDIT.md finding that the old KR-ZERO representation-class generator is itself defective: 103 of 256 contexts labelled R1 (intended to mean token-only) actually contain metadata, because three code paths in the generator bypass its own class constructor — meaning the old representation-class experiment cannot be used to support any claim about representation reduction, independent of the R1-R4-vs-hierarchy naming-collision issue." (anchor: "the old generator itself is defective. R1 was supposed to mean token-only, but 103/256 R1 contexts contain metadata because three generator shapes bypass the class constructor.")
- [S2644] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Confirms the CORPUS-THEORY-AUDIT.md's finding that none of the elements the new representation-reduction theory requires (the R5->R2 chain itself, an independently-defined Q, C, O, fiber analysis, H(R|Q), the metric vector, held-out evaluation) exist anywhere in the repository yet, so the next task must be experiment DESIGN, never immediate dataset construction or implementation." (anchor: "The critical finding is that the new chain does not currently exist in the repository. The audit explicitly finds the chain, inquiry Q, constraints C, decoder O, adequacy, fiber analysis, H(R|Q), metric vector, and held-out evaluation absent.")
- [S2644] types=[GOVERNANCE, EXPLANATION] scope=OBJECT — "Specifies the sixteen-section design-only prompt for KR-REP-REDUCTION-DESIGN-2026-09.md (carrier; source representation D; inquiry Q defined independently and prior to the transformations; contract C; a fixed decoder O separate from the optimal O*; the genuine sequential T5..T2 chain; an operationalized multi-dimensional notion of 'reduction' that never equates fewer digits with less information; preservation/fiber/boundary tests; held-out methodology; dataset schema/generator/factor-fidelity tests explicitly required to prevent repeating the old generator's metadata-leak defect; falsification criteria) plus eight stop conditions and a mandatory binary READY-FOR-IMPLEMENTATION / NOT-READY verdict, explicitly forbidding code implementation or dataset generation at this stage." (anchor: "Do NOT assume that E_S(D)=D\\S is valid ... Define Q : X -> Answers BEFORE defining transformations T5...T2 ... At the end provide a concise verdict: READY FOR IMPLEMENTATION or NOT READY.")

## Notes for P3
All three rows share a single source_id (S2644), from `.../20260903-093000_prompt-design-the-r5-r2-reduction-experiment.md`. This label documents a real generator-defect finding (metadata leakage in 103/256 nominally token-only R1 contexts) plus a design-only commissioning prompt — it is essentially a self-contained audit-and-commission unit. Note the git status snapshot at the start of this session shows `verification/zero-algebra/KR-REP-REDUCTION-2026-09/results/metrics.json` as a modified file in the working tree — that is live repository state, not part of this label's captured evidence, and is mentioned here only as an observation for P3, not as a source_id-backed claim.
