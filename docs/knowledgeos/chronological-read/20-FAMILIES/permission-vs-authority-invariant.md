# permission-vs-authority-invariant

**Scope(s):** THEORY-LEVEL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Permission(a,o) !=> Authority(a,o) · **Aliases:** technical access != governance legitimacy
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 196's key inequality that technical permission to perform an operation never implies domain authority to make its result authoritative; grounds the DatabaseState!=GovernanceTruth distinction and the ValidTransition formalization requiring authority as necessary-but-not-sufficient.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1414 §"Permission(a,o)\not\Rightarrow Authority(a,o) ... This should become one of the constitutional principles of KnowledgeOS."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1414 §"ValidTransition = Authorization \land EvidenceSufficiency \land Preconditions \land InvariantPreservation. ... Authority is necessary but not sufficient."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1414. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1414 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1414 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1414 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S1414]** types=[INVARIANT] scope=THEORY-LEVEL — "The key inequality: technical Permission(a,o) never implies domain Authority(a,o); worked example -- an administrator technically able to execute UPDATE architecture_decision SET status='APPROVED' does not thereby possess Authority(Admin,ApproveArchitecture); proposed as a constitutional principle." (anchor: "Permission(a,o)\not\Rightarrow Authority(a,o) ... This should become one of the constitutional principles of KnowledgeOS.")
- **[S1414]** types=[INVARIANT, DISTINCTION] scope=THEORY-LEVEL — "A database field showing status=APPROVED is not itself governance truth; the domain question ('was the approval actually authorized?') requires validating the transition that produced the state, not just reading the resulting field." (anchor: "DatabaseState \neq GovernanceTruth. The database is evidence of a state representation. The domain requires validation of the transition that produced it.")
- **[S1414]** types=[FORMALIZATION, PRINCIPLE] scope=THEORY-LEVEL — "Refines Valid(tau)=Auth(tau) AND Pre(tau) AND Evidence(tau) AND Invariant(tau): having authority to approve is necessary but not sufficient for a valid transition -- an authorized approval can still be invalid if evidence is missing or preconditions fail, protecting against the governance error 'the responsible person approved it, therefore it is valid.' Authority != Correctness." (anchor: "ValidTransition = Authorization \land EvidenceSufficiency \land Preconditions \land InvariantPreservation. ... Authority is necessary but not sufficient.")

## Notes for P3
- Own observation: completeness is thin — only formal_definition, invariants, semantics is PRESENT; most dimensions are NOT-EVIDENCED-IN-CAPTURE, consistent with a thin or narrowly-scoped source base rather than a claim that the object lacks these properties.
- Own observation: ungrouped in P2a — no co-occurrence or notation signal tied it to another label; may be a genuinely isolated object, or simply under-linked by the mechanical pass.
- Own observation: no rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) row was found for this label — its purpose/motivation, if any, is carried only in DEFINITION/FORMALIZATION-typed rows.
