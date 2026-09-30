# lineage-47-tests-inflation

**Scope(s):** CROSS-OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `GovernanceLineageGraphTest: 4 tests`, `php artisan test --filter=Lineage: 47 passed` · **Aliases:** `PL-7`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0041`, scope `CROSS-OBJECT`: The corpus's most-repeated implementation claim ('provenance/lineage is realized in production, GovernanceLineageGraph, 47 tests', cited in >=15 verification artifacts) is reproduced exactly via php artisan test --filter=Lineage (47 passed, 125 assertions across 18 test classes), but only one class (GovernanceLineageGraphTest, 4 tests) actually exercises the typed provenance graph; the other 43 tests merely share the substring 'Lineage' in unrelated class names (membership-lifecycle, voting-eligibility, election-security). The underlying claim is true but its evidential weight is ~12x overstated in 14 downstream artifacts that propagated the round number.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1709 §"This session reproduced the figure exactly: $ php artisan test --filter=Lineage / Tests: 11 deprecated, 47 passed (125 assertions). ... The filter matches 18 test classes, of which exactly ONE -- GovernanceLineageGraphTest, with 4 tests -- exercises the typed provenance graph. ... The underlying claim is TRUE: a typed provenance graph with branching exists, is tested, and passes. Its evidential weight is 4 tests, not 47 -- ... The inflation happened downstream, where the round number propagated into fourteen further artifacts as though it were the evidence for the provenance claim."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1720. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1709 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1709, S1720 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1709] types=['EXPERIMENTAL-RESULT', 'CORRECTION'] scope=CROSS-OBJECT — "Finding PL-7: re-running php artisan test --filter=Lineage reproduces the corpus's oft-cited '47 tests' figure exactly (47 passed, 125 assertions across 18 test classes), but inspection shows only one class, GovernanceLineageGraphTest (4 tests: test_graph_stores_and_retrieves_node, test_successors_returns_linked_nodes, test_branches_detects_fork, test_linear_graph_has_no_branches), actually exercises the typed provenance graph -- the other 43 tests (e.g. DivergenceSeverityTest, VotingEligibilityPolicyTest) merely share the substring 'Lineage' in an unrelated class name; the underlying claim (a typed, tested provenance graph exists) is TRUE, but its evidential weight is 4 tests not 47 -- the origin artifact EMPIRICAL-KERNEL-TEST.md correctly names the same four tests, but the inflated round number propagated unchecked into fourteen further downstream verification artifacts as if it were the evidence." (anchor: "This session reproduced the figure exactly: $ php artisan test --filter=Lineage / Tests: 11 deprecated, 47 passed (125 assertions). ... The filter matches 18 test classes, of which exactly ONE -- GovernanceLineageGraphTest, with 4 tests -- exercises the typed …")
- [S1720] types=['EXPERIMENTAL-RESULT', 'CORRECTION'] scope=OBJECT — "G-35 (HIGH): the widely-cited '47 tests' figure for the lineage/provenance implementation is a --filter=Lineage name-match spanning 18 unrelated PHP test classes, of which only 4 actually exercise the provenance graph; the figure reproduces exactly but its evidential weight is overstated roughly 12x across 14 citing artifacts." (anchor: "**G-35** | "47 tests" is a `--filter=Lineage` name-match over 18 unrelated classes; **4** exercise the provenance graph.")

## Notes for P3
- Thin evidence base (n=2 rows) — treat conclusions here as provisional.
- Completeness is sparse even relative to its row count (only 2/12 dimensions PRESENT) — most of this object's shape is NOT-EVIDENCED-IN-CAPTURE.
