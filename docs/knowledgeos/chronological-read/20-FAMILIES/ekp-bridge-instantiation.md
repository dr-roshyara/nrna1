# ekp-bridge-instantiation

**Scope(s):** CROSS-OBJECT · **Row count:** 7 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** K=(A,R) live instance, |A|=40,|R|=59 · **Aliases:** EXP-11
**Candidate group membership (NOT an identity claim):**
- G0406: [`ekp-bridge-instantiation` · `knowledge-state-formalization`] — explicit agent-stated uncertainty: 'ekp-bridge-instantiation' POSSIBLY relates to 'knowledge-state-formalization' (batch B0041). Note: The finding that K=(A,R) is empirically instantiated by the real Engineering Knowledge Platform document graph, not by PublicDigit's election domain as Step 267 assumed -- a Type-1 semantic realization Step 267 missed by searching the wrong system.
- G1703: [`ekp-bridge-instantiation` · `knowledge-state-formalization`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0041, scope CROSS-OBJECT): The finding that K=(A,R) is empirically instantiated by the real Engineering Knowledge Platform document graph, not by PublicDigit's election domain as Step 267 assumed -- a Type-1 semantic realization Step 267 missed by searching the wrong system. _[relation_to_existing: POSSIBLY:knowledge-state-formalization]_

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1682] §"K = (A, R) IS INSTANTIATED. |A|=40, |R|=59, with a typed relation vocabulary of {n} kinds. This is a Type-1 semantic realization in Step 267's own taxonomy -- and Step 267 did not find it, because it searched PublicDigit's election domain instead of the EKP."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1711. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S1682 |
| invariants | PRESENT | S1682, S1711 |
| dependencies | PRESENT | S1682, S1685, S1691, S1693 |
| assumptions | PRESENT | S1682 |
| semantics | PRESENT | S1685, S1711 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1682 |
| experiments | PRESENT | S1682, S1693 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
| Statement | Stated | Source ID | Anchor |
|---|---|---|---|
| a governed document carrying a knowledge_id in frontmatter counts as an Assertion-analog | USED-UNSTATED | S1682 | governed documents carrying a knowledge_id (|A|) |
| a check with an empty population is not a genuine pass | EXPLICIT | S1682 | vacuity guard: a check with an empty population is not a pass |

## All rows (source_id order)

- [S1682] types=['EXPERIMENTAL-RESULT', 'VALIDATION'] scope=CROSS-OBJECT — "EXP-11 scans docs/knowledge/ at runtime, finds 40 governed documents carrying a knowledge_id and 59 typed relationship edges across a measured set of relation-type kinds, concluding K=(A,R) has a real live instance in the Engineering Knowledge Platform -- the strongest empirical support any theory object in this reconstruction has -- correcting Step 267's search of the wrong domain (PublicDigit elections)." (anchor: "K = (A, R) IS INSTANTIATED. |A|=40, |R|=59, with a typed relation vocabulary of {n} kinds. This is a Type-1 semantic realization in Step 267's own taxonomy -- and Step 267 did not find it, because it searched PublicDigit's election domain instead of the EKP.")
- [S1682] types=['EXPERIMENTAL-RESULT', 'LIMITATION', 'WARNING'] scope=THEORY-LEVEL — "EXP-15 executes five theory invariants (supersedes acyclicity/antisymmetry; superseded_by as converse of supersedes; status=superseded implies superseded_by set; single authoritative doc per topic+context; all relation targets resolve) against the real EKP graph and reports population counts alongside violation counts, finding four of five invariants have zero or near-zero population (vacuous, i.e. never exercised by real data) rather than genuinely passing; supersession/implementation machinery is entirely unexercised in the only running instance available." (anchor: "A VACUOUS result is NOT a pass: it means the real data contains no instance of the construct, so the theory's invariant about it has never been exercised by reality. ... Four of the five invariant checks are therefore vacuous.")
- [S1685] types=['RESTATEMENT', 'LIMITATION'] scope=OBJECT — "The reconstruction's honest scorecard rates K=(A,R) as EXECUTED as a shape (via the EKP instantiation) but UNRESOLVED as a minimality or sufficiency claim, since EXP-3 (referenced) shows the answer depends on an operation set the corpus never closes; likewise Sigma_lifecycle-perp-Sigma_source-trust is IMPLEMENTED+EMPIRICALLY OBSERVED while Sigma_epistemic is UNRESOLVED/absent from the implementation entirely, and any quantitative epistemic measure is REFUTED as a measurement (mean-over-ladder flips, AggregateSupport unbounded/non-idempotent, IndependenceFactor conflates depth with independence, confidence not a probability without a declared (Omega,F,P))." (anchor: "K = (A, R) as a shape: EXECUTED (40 assertions, 59 typed relations, live). K = (A,R) as minimal/sufficient: UNRESOLVED")
- [S1691] types=['EXTENSION'] scope=OBJECT — "Proposes (labelled PROPOSED, not a finding) splitting Assertion into a structural part A_struct=(id,P,c,t,Pi), depending only on computable nodes, plus an evidence/status qualification layer (e,sigma) that crosses Step 266 §266.31's named judgement boundary; K_struct=(A_struct,R) would then lie in the computable core and is claimed to be exactly the object the EKP already runs (40 cards, 59 edges)." (anchor: "Split the assertion: A_struct = (id, P, c, t, Pi) and A = A_struct + e + sigma. A_struct depends only on unblocked nodes, so K_struct = (A_struct, R) IS in the computable core -- and it is exactly the object the EKP already runs. ... This session offers it as PROPOSED, not as a finding.")
- [S1693] types=['EXPERIMENTAL-RESULT', 'CORRECTION'] scope=CROSS-OBJECT — "Finding KG-6 (called 'the most useful finding in this document'): running the actual knowledge-lint.php against docs/knowledge/ confirms 37 governed documents scanned, all passing, and re-derives the EKP instantiation of K (|A|=40, |R|=59, six relation types in live use, zero unresolved id-typed edges), directly refuting Step 267's headline claim that K=(A,R) has 'IMPLEMENTATION MISSING' -- Step 267 searched PublicDigit's election domain (GovernanceLineageGraph, EvidenceSet, decisionId, integrityHash) instead of the EKP; the corrected, narrower conclusion is that K's structural part IS implemented and passing, while K's epistemic part (e, sigma) is absent." (anchor: "K = (A, R) IS INSTANTIATED AND RUNNING in this repository, in the Engineering Knowledge Platform. Step 267 declared K = (A,R) "IMPLEMENTATION MISSING" because it searched PublicDigit's election domain ... the corpus's headline empirical result -- "K is not implemented" -- is REFUTED.")
- [S1711] types=['CORRECTION', 'RETRACTION'] scope=METHODOLOGICAL — "Finding IR-3: the session's own earlier report of 2 violations for the I4 (single-authoritative-per-topic) invariant is explicitly identified as an artefact of substituting knowledge_type as a fallback proxy for the linter's actual topic field (which no governed document declares); under the linter's real condition the population is 0, and the earlier 2-violation figure is explicitly withdrawn." (anchor: "This session's own I4 = 2 violations was an artefact of a proxy for topic; withdrawn. ... the linter's rule fires only when an explicit topic is present, and no governed document declares one. Under the linter's own condition the population is 0. The earlier number was an artefact of this session's fallback to knowledge_type as a proxy, and is withdrawn.")
- [S1711] types=['VALIDATION', 'RESTATEMENT'] scope=THEORY-LEVEL — "Closes with a deliberately positive summary of five capabilities genuinely, executably true today: a machine-validated knowledge state of the theory's exact shape (40 assertions, 59 typed relations, 6 relation types, referential integrity holding); two orthogonal, schema-enforced status axes; a typed provenance graph with branching detection (4 tests); a real constitutional self-amendment rule (ADR + supersession + ARB, frozen machine-enforced); and a fail-closed-by-construction deepest assurance mechanism (currently inert); states this is more than the corpus credits itself with, located in a different place than the corpus looked." (anchor: "That is more than the corpus credits itself with, and it is in a different place than the corpus looked. ... A knowledge state of the theory's exact shape exists and is machine-validated. ... A real self-amendment rule exists for the constitution ... The deepest assurance mechanism is fail-closed by construction")

## Notes for P3
- This label's own rows carry RETRACTION-typed row(s) ['S1711'], yet the mechanical CONTESTED flag did not fire (lifecycle_candidate=DORMANT). This looks like an instance of the documented under-firing failure mode; flagging for P3 review.
