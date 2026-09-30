# authority-gate-binding-question

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Authorize:(N x A x Policy)->C, Gate=AND g_i · **Aliases:** G1 authority-to-gate binding
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0041, scope OBJECT: The unresolved question of which competence/authority binds to which policy gate; Authorize is shown to be a signature without a body, with no AuthorityAct type and three concrete counterexamples (revocation, policy-version mismatch, authority-less execution).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1679 §"What binds the act to the command? Nothing. c_t records by, not which act. ... Verdict: REFUTED"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1692. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1679 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S1679, S1692 |
| invariants | PRESENT | S1679 |
| dependencies | PRESENT | S1679, S1692 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1679 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S1679] (ARGUMENT/COUNTEREXAMPLE/CORRECTION) G1's previous 'CORPUS ESTABLISHES' verdict for authority-to-gate binding is REFUTED: Authorize:(N x A x Policy)->C is an arity/argument-name signature with no body anywhere in the corpus (grep for definitional forms returns zero hits), N (the Knower) is never defined, and three counterexamples show a revoked authority, a policy-version mismatch, or a bypassed authorization can each mutate K undetected.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows
- [S1679] types=['ARGUMENT', 'COUNTEREXAMPLE', 'CORRECTION'] scope=OBJECT — "G1's previous 'CORPUS ESTABLISHES' verdict for authority-to-gate binding is REFUTED: Authorize:(N x A x Policy)->C is an arity/argument-name signature with no body anywhere in the corpus (grep for definitional forms returns zero hits), N (the Knower) is never defined, and three counterexamples show a revoked authority, a policy-version mismatch, or a bypassed authorization can each mutate K undetected." (anchor: "What binds the act to the command? Nothing. c_t records by, not which act. ... Verdict: REFUTED")
- [S1692] types=['EXTENSION'] scope=OBJECT — "Proposes (VERIFIER RECOMMENDS, TG-01) a typed AuthorityAct record and revised Authorize/Command/Execute signatures requiring an actId, arguing it closes the three counterexamples CE-G1-1/2/3 (revocation, policy-version drift, authorization bypass) and makes 'two commands, one act' / 'one command, several acts' expressible and the estate's already-observed grantId collision detectable; explicitly frames the proposal as extending the existing humanActRef field (its ancestor becomes actText) rather than creating a second mechanism." (anchor: "AuthorityAct = (actId, actor, role, scope, recordedAt, actText, recordedBy) ... Implementation correspondence: humanActRef (132/132, measured) is the ANCESTOR of actText. This EXTENDS the running field; it does not create a second mechanism.")

## Notes for P3
None beyond what is recorded above.
