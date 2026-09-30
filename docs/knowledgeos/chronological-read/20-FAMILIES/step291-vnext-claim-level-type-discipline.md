# step291-vnext-claim-level-type-discipline

**Scope(s):** METHODOLOGICAL · **Row count:** 7 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Audit H, carrier/status/inference-rule triple, ten rejected inference patterns · **Aliases:** carrier-change inference rejection rule, claim-level type discipline
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0053, scope METHODOLOGICAL): A proposed standing programme invariant (Step 291 vNext Audit H): reject any inference where the CARRIER, ABSTRACTION LEVEL, SPEECH-ACT STATUS or GOVERNANCE STATUS changes without an explicit derivation. Ten named inference patterns (e.g. PureClaimSetUnion -> the full knowledge-state algebra K, Sigma-order -> K-order, candidate -> canonical, dependency-edge -> cycle-membership, classification-closed -> membership-closed, derivable-repair-class -> one specific implementation, unblocked-analysis -> resolved-condition, bounded-family -> selected-relation, graph-leverage -> next-action) are tested and all ten rejected; six of the ten had actually been committed by Steps 287-291 themselves and are corrected by this rule.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2196 §"This programme has repeatedly found the same class of mistake: a true statement at level A is silently promoted to a statement at level B. ... ADD §X — CLAIM-LEVEL TYPE DISCIPLINE"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2196 §"This programme has repeatedly found the same class of mistake: a true statement at level A is silently promoted to a statement at level B. ... ADD §X — CLAIM-LEVEL TYPE DISCIPLINE"]

## Lifecycle
last_seen: S2203. Candidate lifecycle: ACTIVE.
Evidence: none recorded (retracted_by/superseded_by empty, contested flag false). The ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used in the ledger, not a confirmed retirement and not a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2200 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2196, S2201, S2203 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The evidence base attributes the following rationale to this object (as recorded, cited to its source):

- **[CONSTRAINT/EXPLANATION]** [S2200]: Formally documents the graph's edge-generation rules for the first time in explicit form: an edge is admitted only when a cited corpus passage states B requires something from A (not mere co-mention); a definitional identification contributes exactly one directed edge (a prior version's bidirectional treatment was removed); a candidate/unratified proposal is modelled as its own node rather than folded into a definitional edge (the Step-290 correction to the Z-1 cycle); blocked dependencies are included as edges (blockage is a status, not an absence); transitive edges are excluded.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2196] types=[PRINCIPLE, GOVERNANCE] scope=METHODOLOGICAL — "Requires a new mandatory section formalizing 'Claim-Level Type Discipline' as a standing programme invariant: for every headline result, record source carrier, target carrier, source status, target status and inference rule, and reject any inference where carrier, semantic level, or speech-act status changes without an explicit derivation; supplies eight worked mandatory test cases (Sigma->K, PureClaimSetUnion->K, candidate->canonical, dependency->cycle, classification->membership, definition->decision-procedure, derivable->unique-implementation, unblocked->resolved)." (anchor: "This programme has repeatedly found the same class of mistake: a true statement at level A is silently promoted to a statement at level B. ... ADD §X — CLAIM-LEVEL TYPE DISCIPLINE")
- [S2196] types=[CONSTRAINT] scope=OBJECT — "Requires that the word 'forced' in the identity-repair finding (id = H(P,e,c,t,Pi) re-keying on withdrawal) be scoped precisely across six distinct propositions (observed failure of current construction, necessity of a revision-stable mechanism, necessity of preserving historical identity, necessity of some replacement mechanism, uniqueness of the specific proposed repair, governance authorization), and that if multiple repair formulas satisfy the invariants, the finding must be stated as 'the repair class is forced, the implementation is not.'" (anchor: "AUDIT E — IDENTITY "FORCED" CLAIM ... If multiple identity repairs satisfy the required invariants, state that the repair class is forced but the implementation is not.")
- [S2200] types=[CONSTRAINT, EXPLANATION] scope=METHODOLOGICAL — "Formally documents the graph's edge-generation rules for the first time in explicit form: an edge is admitted only when a cited corpus passage states B requires something from A (not mere co-mention); a definitional identification contributes exactly one directed edge (a prior version's bidirectional treatment was removed); a candidate/unratified proposal is modelled as its own node rather than folded into a definitional edge (the Step-290 correction to the Z-1 cycle); blocked dependencies are included as edges (blockage is a status, not an absence); transitive edges are excluded." (anchor: "EDGE-GENERATION RULES admitted: a cited passage states that B REQUIRES SOMETHING FROM A rejected: co-mention ... definitions: an IDENTIFICATION contributes ONE edge ... candidates: a CANDIDATE proposal is modelled as its own node ... blocked: blocked dependencies ARE included ... transitive: NOT included")
- [S2201] types=[GOVERNANCE, RESTATEMENT] scope=METHODOLOGICAL — "Documents, inline at the top of the headline artifact, that this version of Step 291 is explicitly NOT FROZEN and that a subsequent adversarial audit (the vNext, 8 audits) downgrades six of its claims for overstatement while confirming the underlying substance stands." (anchor: "NOT FROZEN — step-291/11_VNEXT-CLOSURE-AUDIT.md. A reviewer audit ruled "do not freeze Step 291 yet" and ran eight audits over this artifact. The substance stands; six claims are DOWNGRADED for overstatement")
- [S2203] types=[CORRECTION] scope=OBJECT — "Corrects the prior 'the repair is forced' identity finding into six separated propositions, concluding only that a revision-stable identity mechanism CLASS is forced (the current construction demonstrably fails under withdrawal, and StructuralValid plus persistent-identity principles jointly require some replacement), while the specific replacement formula is not entailed - at least three distinct candidate repairs (projecting state out of id, a surrogate key, versioned identity) each independently satisfy the required invariants, so asserting one specific formula was an unwarranted derivable-implies-unique-implementation leap." (anchor: "AUDIT E ... The repair CLASS is forced (revision-stable identity); no specific replacement formula is entailed by the experiment. ... at least three repairs satisfy the invariants — the derivable ⇒ one-architecture leap, committed by me")
- [S2203] types=[PRINCIPLE, VALIDATION] scope=METHODOLOGICAL — "States and tests the Claim-Level Type Discipline rule (Audit H) against ten named inference patterns (e.g. PureClaimSetUnion->K, Sigma->K, candidate->canonical, dependency->cycle-membership, classification->membership, definition->decision-procedure, derivable-repair->unique-implementation, unblocked->resolved, bounded-family->selected-relation, graph-leverage->next-action); all ten are rejected as invalid without explicit derivation, and six of the ten were found to have actually been committed somewhere across Steps 287-291 themselves, now corrected." (anchor: "Reject any inference where the CARRIER, ABSTRACTION LEVEL, SPEECH-ACT STATUS or GOVERNANCE STATUS changes without an explicit derivation. ... All ten tested. All ten rejected. Six of the ten were actually committed by Steps 287–291")
- [S2203] types=[VALIDATION] scope=METHODOLOGICAL — "Applies the mandate's required final test (would two reasonable readers derive different answers about what is established) to the pre- and post-audit versions of Step 291, concluding four specific ambiguous phrasings ('classification closed', 'the repair is forced', '22 operations', an unstated graph universe) each previously admitted a stronger misreading, and that the audit's four corresponding corrections remove that ambiguity." (anchor: "Could two reasonable readers derive different answers about what has been established? Before this audit: yes ... After: the 9-row taxonomy, the repair-class scoping, "currently identified", and the stated universes remove all four.")

## Notes for P3
(Own observation) Nothing unusual noticed while drafting this file beyond what is already recorded above.
