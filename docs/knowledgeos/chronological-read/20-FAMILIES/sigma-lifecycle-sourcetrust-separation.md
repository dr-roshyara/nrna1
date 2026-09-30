# sigma-lifecycle-sourcetrust-separation

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Sigma (epistemic support) = ABSENT`; `authority (source trust)`; `status (lifecycle)` · **Aliases:** EXP-12
**Candidate group membership (NOT an identity claim):**
- G0410: co-listed with `epistemic-vs-governance-status-orthogonality` — explicit agent-stated uncertainty (batch B0041): "A proposed (not yet established) orthogonality test between epistemic assessment status and governance/administrative status, illustrated by a proposition that is epistemically well-supported but administratively rejected; framed as needing corpus confirmation, feeding into Step 272's Sigma derivation." Relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0041, scope OBJECT — "Empirical confirmation that the EKP's status and authority axes vary independently (7 observed pairs vs 5x5 max), already schema-declared as independent; but neither axis is the theory's Sigma (epistemic support) and no field in the running system records whether a claim is supported by evidence."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1682 §"EMPIRICAL CONFIRMATION of ARC D's separation -- and the separation was ALREADY RUNNING, schema-enforced, before the theory derived it ... NEITHER axis is the theory's Sigma ... There is NO field in the running system that records whether a claim is SUPPORTED BY EVIDENCE."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1703. Candidate lifecycle: CONTESTED.
Evidence: no retracted_by, no superseded_by, but `contested_by_own_contradiction_type` is true. This tracks a real internal tension in the rows themselves: S1682 (EXP-12) reports 7 observed (status,authority) pairs across 40 cards as empirical confirmation of declared independence, while S1690 measures a near-identical setup (39 documents) and finds only 6/40 cells occupied with a perfect draft⇔provisional biconditional (13/13), which it explicitly frames as CONTRADICTING the independence claim ("not independent evidence for Sigma-perp-Gamma"). S1703 (Finding SG-7) reconciles by noting only two axes (status, authority) are implemented at all, and none of the five theoretical Sigma axes (evidence, supersession-as-state, validity, asked) are enforced — concluding Sigma "has never been tested by reality."

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1682, S1690 |
| dependencies | PRESENT | S1682, S1690, S1703 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1682, S1690, S1703 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (family.rationale_evidence is empty; the substantive argument lives in the experiment records below rather than a separately classified rationale row)

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S1682]` types=[EXPERIMENTAL-RESULT, VALIDATION, LIMITATION] scope=OBJECT — "EXP-12 counts (status, authority) combinations across the 40 EKP cards and finds more distinct pairs than either axis alone has values, empirically confirming the two axes vary independently (matching the schema's own stated independence); however neither axis is the theory's Sigma (epistemic support), so the running system implements Lifecycle x SourceTrust but not Lifecycle x SourceTrust x EpistemicSupport." Missing: "an epistemic-support field/axis in the running system." Dependencies: `docs/knowledge/schema/statuses.yaml`, `docs/knowledge/schema/authorities.yaml`. Invariant: "status is independent of authority (schema-stated)." Experiment: hypothesis "status and authority vary independently in the real data, but neither is the theory's epistemic-support Sigma"; method "Counter over status, authority, and their joint pairs" over 40 cards; result "7 observed pairs over 5 statuses and 5 authority ranks"; conclusion "reality supplies two axes (Lifecycle, SourceTrust); the theory needs three including Sigma_epistemic which is absent." (anchor: "EMPIRICAL CONFIRMATION of ARC D's separation -- and the separation was ALREADY RUNNING, schema-enforced, before the theory derived it ... NEITHER axis is the theory's Sigma ... There is NO field in the running system that records whether a claim is SUPPORTED BY EVIDENCE.")
- `[S1690]` types=[EXPERIMENTAL-RESULT, CONTRADICTION] scope=OBJECT, explicit_date=2026-08-30 — "Measuring (status,authority) pairs across all 39 frontmatter-bearing EKP documents finds only six of forty possible cells occupied, with a perfect biconditional between status=draft and authority=provisional (13/13 each direction), directly contradicting the schema comment's claim that status is independent of authority: on the running estate the two dimensions are deterministically linked on the draft axis, so the independence declaration is unfalsified as a permission but unexercised (and empirically false) as a fact, and is therefore not independent evidence for Sigma-perp-Gamma; additionally 93 of 132 markdown files under docs/knowledge/ carry no frontmatter at all, constituting a real running instance of the 'not assessed' state the theory says it cannot express." Dependency: `docs/knowledge/ frontmatter measurement`. Invariant: "status is independent of authority (schema comment, empirically contradicted)." Lineage claim: SOURCE-CLAIMED-CONTRADICTION targeting "schema comment asserting status/authority independence" — quote: "CONTRADICTED BY MEASUREMENT." Experiment: same hypothesis as S1682's setup but over 39 documents; result "only 6/40 cells occupied; draft<=>provisional biconditional 13/13 in each direction"; conclusion "CONTRADICTED BY MEASUREMENT -- not independent evidence for Sigma-perp-Gamma." (anchor: "draft <=> provisional is a perfect biconditional: 13 of 13 in each direction. ... On the running estate the two dimensions are deterministically linked on the draft axis. The declaration is unfalsified as a PERMISSION and unexercised as a FACT -- so it is NOT independent evidence for Sigma perp Gamma.")
- `[S1703]` types=[EXPERIMENTAL-RESULT, LIMITATION] scope=CROSS-OBJECT (co-labeled with `knowledge-lint-coverage-gap`) — "Finding SG-7: measuring the real EKP finds only two axes implemented (status as a lifecycle/governance-process axis with 4 distinct values across 38 documents; authority as a source-trust axis with 4 values; 7 observed joint pairs confirming independence), and zero of the five theoretical Sigma axes (evidence, supersession-as-state, validity, asked) as enforced concepts -- no field records evidential support, no lint rule (of 16 listed identifiers) mentions evidence; concludes Sigma has never been tested by reality (no instance) while the EKP itself has no mechanism at all for epistemic status, so a factually-refuted document with status=approved/authority=authoritative has no way to record that fact." Missing: "an epistemic-support field or lint rule in the EKP." Dependency: `exec/exp_ekp_bridge.py EXP-12, EXP-14`. (anchor: "The running system has TWO of the five axes ... and ZERO of the evidence, supersession-as-state, validity, or asked axes as ENFORCED concepts. No field records whether a claim is supported by evidence; no lint rule mentions evidence. ... Against the theory: Sigma has never been tested by reality. It has no instance.")

## Notes for P3
Internal tension flagged mechanically (`contested_by_own_contradiction_type: true`) and confirmed by close reading: S1682 (40 cards, 7 pairs) reads as confirming independence while S1690 (39 documents, 6/40 cells, a perfect draft⇔provisional biconditional) reads as contradicting it — the two experiments used slightly different document counts/snapshots, which may explain rather than resolve the tension; P3 should treat this as a genuine open empirical question, not reconcile it silently. `family.files_touching` lists S1682, S1685, S1690, S1692 — but the three actual rows cite S1682, S1690, and S1703 only; S1685, S1692, and S1703 do not perfectly overlap between `files_touching` and `rows`, which looks like a P2a bookkeeping discrepancy worth flagging rather than silently reconciling. This label is also co-listed on S1703 with `knowledge-lint-coverage-gap`, a candidate part-of/overlap relationship for P3 to evaluate (not asserted here as identity).
