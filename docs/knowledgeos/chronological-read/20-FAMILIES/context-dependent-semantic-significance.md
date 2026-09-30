# context-dependent-semantic-significance

**Scope(s):** THEORY-LEVEL · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** SemanticTransition=f(Mutation,Context,Policy), Sigma(m,C) · **Aliases:** path is not domain semantics

**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- **OBJECT-INDEX**, batch B0034, scope THEORY-LEVEL: Step 191's finding that the same mutation type can be technical in one context and semantic in another (e.g. README.md vs. the official Architecture Constitution), so semantic significance is a function of mutation+context+policy, not the mutation alone; includes the warning against path-based heuristics and the principle that AI classification is itself an epistemic assertion, not governance truth.

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1394 §"README.md changed. Normally: \Sigma=0. But if that README is the official Architecture Constitution then: \Sigma=1."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1394. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction rows found). This DORMANT classification is a heuristic based on how recently (by source_id, last_seen=S1394) this label was last used in the captured contribution set, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1394 |
| examples | PRESENT | S1394 |
| warnings | PRESENT | S1394 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE

## Assumption register

NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S1394]` types=[EXAMPLE, EXTENSION] scope=OBJECT — "Semantic significance is contextual, not intrinsic to a mutation type: the same kind of edit (a README change) can be Sigma=0 in general but Sigma=1 if that file is the official Architecture Constitution, so SemanticTransition=f(Mutation,Context,Policy) rather than a function of the mutation alone." (anchor: "README.md changed. Normally: \Sigma=0. But if that README is the official Architecture Constitution then: \Sigma=1.")
- `[S1394]` types=[WARNING, LIMITATION] scope=THEORY-LEVEL — "Warns against the existing implementation heuristic 'if path starts with architecture/ require architecture approval': path-based rules are useful heuristics but not universally valid semantic classification, since the real classification function is f(Content,Context,Intent,Policy)." (anchor: "Path\Rightarrow SemanticMeaning is not universally valid.")
- `[S1394]` types=[PRINCIPLE] scope=THEORY-LEVEL — "Deterministic assurance is still achievable despite context-dependence: once a semantic policy is declared (e.g. SemanticClass=ArchitectureRelevant requires ADR+ArchitectAuthority+Witness), enforcement of that declared contract can be fully deterministic even though classifying content into the policy requires judgment." (anchor: "Once the semantic policy is declared: Policy(C) the enforcement can be deterministic.")
- `[S1394]` types=[PRINCIPLE, WARNING] scope=THEORY-LEVEL — "AI may propose a ClassificationCandidate, but the final classification should follow Rule+Evidence+HumanReview where required; AI classification is itself an epistemic assertion and must not silently become governance truth." (anchor: "AI classification is itself an epistemic assertion. It must not silently become governance truth.")

## Notes for P3

All 4 rows trace to a single source document (S1394); the evidentiary base for this label is broad in row count but narrow in provenance (one authoring pass, one document).
