# witness-not-cause-or-authority

**Scope(s):** OBJECT · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Witness != Authority`, `Witness != Cause` · **Aliases:** `'it is in Git, therefore it is approved' anti-pattern`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope OBJECT): Step 191's finding that a Git commit (or any artifact) can witness a transition without being its cause or conferring authority on it; grounds a rule for KnowledgeOS hooks that a detected GitChange becomes only a CandidateSemanticChange pending policy classification.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1394] §"GitCommit \neq SemanticDecision. The semantic classification must come from context."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1394. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows) — this DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S1394 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S1394 |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1394 |
| Examples | PRESENT | S1394 |
| Warnings | PRESENT | S1394 |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Applies the witness/authority distinction to KnowledgeOS hooks: a hook detecting a GitChange should not declare an ArchitectureChange directly, but classify it as a CandidateSemanticChange, letting a deterministic policy decide whether the semantic boundary was crossed. [S1394]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1394] types=[DISTINCTION, EXAMPLE] scope=OBJECT — "A Git commit is not automatically an ArchitectureDecision (it may contain formatting, typo fixes, refactoring, dependency bumps, or an architecture change); semantic classification must come from context, not from the mere fact of being committed." (anchor: "GitCommit \neq SemanticDecision. The semantic classification must come from context.")
- [S1394] types=[INVARIANT, WARNING] scope=THEORY-LEVEL — "A commit can witness a transition (Witness(C123,Transition)) without being its cause or its authority; explicitly names and rejects the anti-pattern 'it is in Git, therefore it is approved' -- Git provides evidence of what changed, not governance legitimacy." (anchor: "Witness\neq Cause and Witness\neq Authority. ... 'It is in Git, therefore it is approved.' No.")
- [S1394] types=[ARGUMENT] scope=OBJECT — "Applies the witness/authority distinction to KnowledgeOS hooks: a hook detecting a GitChange should not declare an ArchitectureChange directly, but classify it as a CandidateSemanticChange, letting a deterministic policy decide whether the semantic boundary was crossed." (anchor: "GitChange \xrightarrow{Classification} CandidateSemanticChange. Then a deterministic policy can determine whether it crosses the semantic boundary.")

## Notes for P3
Lifecycle (DORMANT) rests on recency heuristics only — no explicit retraction, supersession, or self-contradiction signal was found in this label's own rows. No internal tension noticed across this label's own rows for this batch.
