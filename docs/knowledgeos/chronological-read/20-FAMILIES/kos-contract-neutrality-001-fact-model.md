# kos-contract-neutrality-001-fact-model

**Scope(s):** OBJECT · **Row count:** 9 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `L0-L5 pipeline` · **Aliases:** `KOS-CONTRACT-NEUTRALITY-001`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0003`, scope `OBJECT`: Track 1's separate, non-Track-2 architecture: a layered pipeline from source language through language-neutral L3 facts, L4 cohesion semantics, and L5 LCOM4 metric, to conformance evidence.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0084 §"Component(fact, "L3 Fact Model", "Language-neutral schema", "Analysed-unit kind, stable identity, method identity, target, qualifier kind, callable/invocation, access mode, determinability, property access")"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0084 §"Component(fact, "L3 Fact Model", "Language-neutral schema", "Analysed-unit kind, stable identity, method identity, target, qualifier kind, callable/invocation, access mode, determinability, property access")"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0468 §"Decision: C — separate grant on the same lane. ... The grant is the authority; the assignment text cannot manufacture authority. ... The new Pass-1 grant should be very narrowly worded ... It should not authorize: changing the contract; modifying LCOM4; implementing Python; ... The Pass-1 output should remain an evidence determination, not an architecture decision."]

## Lifecycle
last_seen: S0468. Candidate lifecycle: **CONTESTED**. Evidence: contested_by_own_contradiction_type: true (this label's own rows contain a CONTRADICTION-type entry)

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S0164, S0164 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0084 |
| type_signature | PRESENT | S0084 |
| invariants | PRESENT | S0084, S0468 |
| dependencies | PRESENT | S0468 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0084, S0084, S0164 |
| experiments | PRESENT | S0164, S0164 |
| open_questions | PRESENT | S0164 |

## Rationale
Analysis: Root-cause classification of the 12 PHP/Python LCOM4-collector divergences in the KOS-CONTRACT-NEUTRALITY-001 experiment: the seven pinned cohesion decisions agreed everywhere reachable; every code-side defect is an instance of one architectural decision (approximating a language grammar with regular expressions). [S0164] As an alternative, Recommends Option D (contract stratification): a language-neutral fact schema (L3), a per-language extraction binding (L2), and language-neutral cohesion rules (L3→L5), with the conformance boundary declared at the fact model so extraction may be shared without circularity because the contract says so. [S0164]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0084] types=['FORMALIZATION', 'DEFINITION'] scope=OBJECT — "The L3 Fact Model is a language-neutral schema whose fields are: analysed-unit kind, stable identity, method identity, target, qualifier kind, callable/invocation, access mode, determinability, and property access." (anchor: "Component(fact, "L3 Fact Model", "Language-neutral schema", "Analysed-unit kind, stable identity, method identity, target, qualifier kind, callable/invocation, access mode, determinability, property access")")
- [S0084] types=['INVARIANT', 'WARNING'] scope=OBJECT — "Qualifier kind (an L3 fact-model field) must be preserved as recorded and must never be inferred backward from the L5 final cohesion metric." (anchor: "Rel_U(cohesion, fact, "Qualifier kind is preserved, not inferred from final metric")")
- [S0084] types=['WARNING', 'CONSTRAINT'] scope=OBJECT — "Conformance evidence requires the declared node set and edge set in addition to the final LCOM4 metric; the metric value alone is insufficient to demonstrate conformance." (anchor: "Rel_U(conformance, metric, "Metric alone is insufficient")")
- [S0164] types=['ANALYSIS', 'EXPERIMENTAL-RESULT'] scope=OBJECT — "Root-cause classification of the 12 PHP/Python LCOM4-collector divergences in the KOS-CONTRACT-NEUTRALITY-001 experiment: the seven pinned cohesion decisions agreed everywhere reachable; every code-side defect is an instance of one architectural decision (approximating a language grammar with regular expressions)." (anchor: "Not one of the twelve divergences is a disagreement about cohesion ... nine have a lexical root · two a structural/grammar root · one is a contract question · zero are disagreements about the cohesion model")
- [S0164] types=['EXPERIMENTAL-RESULT', 'LIMITATION'] scope=OBJECT — "Measured finding that the PHP reference collector (already AST-based) diverges from Python on the NEW-5 qualifier-kind case because Node\Name::toString() collapses fully-qualified and unqualified name kinds to the same string before any contract logic runs — an information-loss defect at the extraction API, not a considered semantic decision." (anchor: "The reference is already Option B, and it still produced NEW-5 ... An AST is necessary and demonstrably not sufficient.")
- [S0164] types=['CONTRADICTION', 'OPEN-QUESTION'] scope=OBJECT — "Identifies a collision between two pinned contract decisions (behavioural-dependency rule vs as-written-spelling rule) that both implementations have been silently resolving via an accident of their own extraction API rather than a ruled decision, and returns the question undecided to PO/ARB with four plausible readings (R1..R4) and their costs." (anchor: "intra_class_calls says the relationship is *semantic* ... own_class_name_resolution says recognition is *syntactic* ... The contract does not say which decision governs when they conflict")
- [S0164] types=['ALTERNATIVE', 'ARGUMENT'] scope=OBJECT — "Recommends Option D (contract stratification): a language-neutral fact schema (L3), a per-language extraction binding (L2), and language-neutral cohesion rules (L3→L5), with the conformance boundary declared at the fact model so extraction may be shared without circularity because the contract says so." (anchor: "stop claiming that PHP comprehension is language-neutral, state where the neutrality boundary is, and delegate everything below it to software that already knows the grammar.")
- [S0164] types=['WARNING', 'CONSTRAINT'] scope=OBJECT — "Warns that treating Option A (incremental scanner repair) as if it closes 'all divergences' would silently sweep the undecided NEW-5 semantic question in with ordinary bug fixes, which Decision 1 explicitly reserves to PO/ARB." (anchor: "A cannot address NEW-5 and, per Decision 1, must not try ... encode an undecided semantic as settled — the exact failure Decision 1 exists to prevent.")
- [S0468] types=['GOVERNANCE'] scope=OBJECT — "Unrelated appended governance decision: authorizes a new, narrowly-scoped Pass-1 grant under KOS-CONTRACT-NEUTRALITY-001 for evidence reconciliation only (not architecture decision), kept strictly separate from the existing V-3 grant (bounded to two PHP-binding-semantics questions); neither existing V-3 record is treated as authoritative merely by recency ('no averaging, merging, or latest-therefore-authoritative shortcut'); corrects a prior AST-019 misattribution -- AST-019 is unrelated and closed, with no authority relationship to KOS-CONTRACT-NEUTRALITY-001." (anchor: "Decision: C — separate grant on the same lane. ... The grant is the authority; the assignment text cannot manufacture authority. ... The new Pass-1 grant should be very narrowly worded ... It should not authorize: changing the contract; modifying LCOM4; implem…")

## Notes for P3
- This label's own rows include a CONTRADICTION-type entry — the lifecycle is CONTESTED and P3 should reconcile the conflicting claims rather than pick one silently.
