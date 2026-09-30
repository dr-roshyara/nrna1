# k-equality-membership-underdetermination

**Scope(s):** OBJECT · **Row count:** 7 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** K1=K2?, structural/content-only/content+status/identity-only equality · **Aliases:** EXP-8, EXP-9
**Candidate group membership (NOT an identity claim):**
- G0420: [`congruence-unconstructed-not-undecidable` · `k-equality-membership-underdetermination`] — explicit agent-stated uncertainty: 'congruence-unconstructed-not-undecidable' POSSIBLY relates to 'k-equality-membership-underdetermination' (batch B0041). Note: A sharpening of Step 260's own premises (equivalence defined by universal quantification over an unenumerated operation set T, decidability marked UNRESOLVED at 260.15): since T has no extension, the equivalence relation is not merely undecidable but UNCONSTRUCTED (a relation defined by a quantifier ranging over an open collection is not yet a relation); traces a five-link dependency chain by which K*, A-in-K membership, K1=K2 state equality, the Step 259 sufficiency theorem, and Step 266's minimality marking all inherit this unconstructedness.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0041 · scope OBJECT: Four defensible definitions of assertion-membership-in-K and state-equality K1=K2 that disagree on the same test cases, executed against a reference kernel; grounds 12-IDENTITY-EQUALITY-GAP.md.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1678 §"probes on which the four equalities DISAGREE: ... => 'assertion in K' is NOT a well-defined predicate of the theory. It is a family of four predicates, and the corpus fixes none of them."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1726. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (DORMANT) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S1678 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1678, S1693, S1708 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1678 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1678, S1693, S1708, S1726 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1678] types=[EXPERIMENTAL-RESULT, COUNTEREXAMPLE] scope=OBJECT — "EXP-8 defines four candidate 'assertion in K' predicates (structural, content-only, content+status, identity-only) and shows they disagree on probe assertions differing only in source/status or id, concluding membership is a family of four predicates, none fixed by the corpus; every theorem quantifying over 'A in K' is therefore underdetermined." (anchor: "probes on which the four equalities DISAGREE: ... => 'assertion in K' is NOT a well-defined predicate of the theory. It is a family of four predicates, and the corpus fixes none of them.")
- [S1678] types=[EXPERIMENTAL-RESULT, COUNTEREXAMPLE] scope=OBJECT — "EXP-9 partitions five constructed knowledge states under each of the four equality definitions and finds different partition sizes/classes for each, showing K1=K2 has no fixed truth value; Step 260 requires the equivalence to be a congruence for a transformation algebra T that Step 266 leaves unenumerated, so the relation cannot even be constructed." (anchor: "The SAME five states fall into different numbers of equivalence classes depending on the equality chosen. 'K1 = K2' therefore has no truth value in the theory as it stands.")
- [S1693] types=[EXPERIMENTAL-RESULT, CORRECTION] scope=OBJECT — "Finding KG-5, independently corroborating exp_identity.py's EXP-8/EXP-9: the same five constructed states partition into 5, 2, 3, and 5 equivalence classes respectively under structural, content-only, content+status, and identity-only equality, and disagree on 2 of 3 membership probes, showing A-in-K and K1=K2 are not well-defined (not merely uncomputed) since the corpus rules on none of the four equalities (Step 261 names but does not decide; Step 266 marks semantic equality domain-semantics-open)." (anchor: "The mandate requires K1=K2 and A in K to be COMPUTABLE. They are not yet WELL-DEFINED: the same five states fall into 2, 3, 5 or 5 equivalence classes depending on which corpus-sourced equality is used, and the corpus rules on none of them.")
- [S1708] types=[EXPERIMENTAL-RESULT, VALIDATION] scope=THEORY-LEVEL — "TEST 6, the decisive test: two different histories (H_a: assert(v); H_b: assert(s), assert(v), withdraw(scanner)) replay to structurally-equal states K(H_a)=K(H_b); of seven audit-relevant questions posed against K alone, exactly the three that are provenance/lineage questions (current state, origin of each assertion, derivation) are answerable, and exactly the four history-dependent questions (was anything withdrawn, was anything contradicted, how many revisions, who withdrew a source) are not; concludes Provenance and Lineage belong to the state K, while History does not reduce to K, making Step 247's meta-structure script-K_t=(K_t,H_t) a mathematical necessity rather than a mere convenience." (anchor: "The three ANSWERABLE questions are exactly PROVENANCE and LINEAGE. The four unanswerable ones are exactly HISTORY. CONCLUSION (executed): Provenance and Lineage belong to the STATE; History does NOT reduce to the state, and Step 247's K_t=(K_t,H_t) is therefore NECESSARY, not merely convenient.")
- [S1726] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "EXP-8: four defensible equalities (structural, content-only, content+status, identity-only) disagree on 2 of 3 membership probes for 'is assertion A in K'; the corpus fixes none of them (Step 261 names structural vs. semantic equality without ruling; Step 266 marks semantic equality 'yellow: domain semantics' i.e. open), so every theorem quantifying over 'A in K' is underdetermined." (anchor: "=> 'assertion in K' is NOT a well-defined predicate of the theory.")
- [S1726] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "EXP-9: the same 5 constructed states partition into 5, 2, 3, and 5 equivalence classes respectively under structural, content-only, content+status, and identity-only equality; 'K1=K2' therefore has no truth value as the theory stands, and Step 260's requirement that the equivalence be a congruence for T cannot even be checked since T is unenumerated (Step 266)." (anchor: "=> The SAME five states fall into different numbers of equivalence classes
     depending on the equality chosen.")
- [S1726] types=[EXPERIMENTAL-RESULT, CORRECTION] scope=OBJECT — "EXP-10/10b: a hand-picked merge triple came out associative for both a conflict-marking and a latest-wins rule, proving nothing; an exhaustive search over the full small state space (64 ordered triples) found 0 associativity counterexamples for conflict-marking but 4 genuine counterexamples for latest-wins (witness: A=3.69@t10, B=3.69@t10, C=3.70@t10 gives (A*B)*C≠A*(B*C)). Conclusion: 'merge converges' (Step 025l) is a property of a specific rule, and the corpus states the claim without naming the rule — a convergence theorem with no named conflict-resolution operator has no content." (anchor: "=> The exhaustive result -- not an assertion -- is what stands.")

## Notes for P3
(none beyond what is captured above)
