# bc7-domain-model

**Scope(s):** OBJECT · **Row count:** 18 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G0283: links this to `kos-domain-model-preview` — explicit agent-stated uncertainty: 'kos-domain-model-preview' POSSIBLY relates to 'bc7-domain-model' (batch B0025). Note: Step 137's opening question and eighteen candidate semantic objects (Authority, Decision, Policy, Exception, Knowledge, Claim, Evidence, Observation, FitnessRule, Verification, Finding, Recommendation, Authorization, Action, Agent, Session, Artifact, RuntimeState) for the consolidated KnowledgeOS domain model.
- G0284: links this to `kos-canonical-domain-model` — explicit agent-stated uncertainty: 'kos-canonical-domain-model' POSSIBLY relates to 'bc7-domain-model' (batch B0025). Note: Step 137's consolidated canonical KnowledgeOS domain model: per-object conceptual schemas for all 18 candidate objects, aggregate candidates, mutable/immutable classification, domain events, and the closed-loop engineering-platform redefinition of KnowledgeOS.
- G0895: links this to `domain-events-bc7` — working_label token overlap Jaccard=0.50 (shared tokens: ['bc7', 'domain'])
- G1117: links this to `workitem-aggregate` — labels co-occur in the same contribution's labels[] 6 separate times across the corpus
- G1120: links this to `census-method-defect-pattern` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- G1121: links this to `workflow-mechanism` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0002, scope OBJECT: "The tactical DDD domain model for BC-7 (KOS-ARCH-BASELINE-003) and its full proposal/verification/refinement/correction chain."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0070 §"aggregate candidates · aggregate-root decision · entities · value objects · domain events · domain policies · invariants · ubiquitous language"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0070 §"aggregate candidates · aggregate-root decision · entities · value objects · domain events · domain policies · invariants · ubiquitous language"]

## Lifecycle

last_seen: S0080. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S0080), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S0071 (×2) |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0080 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0073, S0076, S0079 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0071 |

## Rationale

WorkItem is tested and confirmed as the aggregate root: its identity, lifecycle, four invariants (I-1..I-4), and consistency boundary are all internal to a single JSON record, needing no cross-record data to enforce. [S0071] Human Act is rejected as aggregate root on two independent grounds: it has neither identity nor lifecycle inside BC-7, and promoting it would duplicate a concept another (Knowledge Engineering/Governance) context owns and would improperly drag authority semantics into a context that cannot evaluate authority (ES-005.4). [S0071]

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S0070] types=[GOVERNANCE] scope=OBJECT, completeness N/A (missing: unspecified) — "KOS-ARCH-BASELINE-003 is commissioned as pure tactical DDD discovery for BC-7, requiring an aggregate-root decision, entities, value objects, domain events, policies, invariants, and ubiquitous language as a proposal only." (anchor: "aggregate candidates · aggregate-root decision · entities · value objects · domain events · domain policies · invariants · ubiquitous language")
- [S0070] types=[CONSTRAINT] scope=OBJECT, also labeled `adr-aip-04`, completeness N/A (missing: unspecified) — "The commission's forbidden-classes list mechanically excludes implementation, the workflow_engine itself, and any ADR-AIP-04/role-model decision from this tactical discovery work." (anchor: "Implementation · code changes · component movement · workflow_engine changes · ADR-AIP-04 decision · role-model ownership decision.")
- [S0071] types=[ANALYSIS] scope=OBJECT, also labeled `workitem-aggregate`, completeness N/A (missing: unspecified) — "WorkItem is tested and confirmed as the aggregate root: its identity, lifecycle, four invariants (I-1..I-4), and consistency boundary are all internal to a single JSON record, needing no cross-record data to enforce." (anchor: "Candidate A — Work Item Aggregate ... Result: passes every test. Critically, all four invariants are internal to it")
- [S0071] types=[CORRECTION] scope=OBJECT, also labeled `workitem-aggregate`, completeness N/A (missing: unspecified) — "Assignment is rejected as aggregate root because the single-mutation-owner invariant (I-1) spans the whole set of assignments and cannot be stated from inside one, and HANDOFF is atomic across two assignments, making Assignment an entity inside WorkItem, not a root." (anchor: "Candidate B — Assignment Aggregate (rejected) ... I-1 is an invariant over the set of assignments, not over one.")
- [S0071] types=[CORRECTION] scope=OBJECT, also labeled `workitem-aggregate`, completeness N/A (missing: unspecified) — "Workflow is rejected as aggregate root (it is a fixed classifier with no lifecycle or state of its own) and reclassified as a Value Object on the WorkItem root." (anchor: "Candidate C — Workflow Aggregate (rejected — and reclassified) ... Workflow is a Value Object on the root, not an aggregate.")
- [S0071] types=[ANALYSIS] scope=OBJECT, also labeled `human-act`, completeness N/A (missing: unspecified) — "Human Act is rejected as aggregate root on two independent grounds: it has neither identity nor lifecycle inside BC-7, and promoting it would duplicate a concept another (Knowledge Engineering/Governance) context owns and would improperly drag authority semantics into a context that cannot evaluate authority (ES-005.4)." (anchor: "Candidate D — Human Act Aggregate (rejected, on two independent grounds) ... Promoting it to an aggregate root would duplicate a concept another context owns")
- [S0071] types=[OPEN-QUESTION] scope=OBJECT, also labeled `workitem-aggregate`, completeness N/A (missing: unspecified) — "Eleven open questions (OQ-1..OQ-11) are carried, explicitly not decided: role-model ownership deferred to ADR-AIP-04, session-name uniqueness scope, whether CONTINUATION/FAIL are real or dead vocabulary (0/117 observed), whether Grant is an entity or immutable record, and whether one or two aggregates is correct (flagged as the single largest structural choice)." (anchor: "OQ-9 One aggregate or two (RA-4)? ... the single largest structural choice in the model")
- [S0072] types=[VALIDATION] scope=OBJECT, completeness N/A (missing: unspecified) — "Verification #1 of the BC-7 domain model re-derives twelve load-bearing claims independently from the mechanism source and 13 live records, confirming eleven and falsifying one (F-1), while judging the falsification does not overturn the aggregate decision." (anchor: "RECOMMENDATION — VERIFIED WITH NOTES ... Twelve load-bearing claims were re-derived from source and records; eleven confirmed, one falsified (F-1).")
- [S0072] types=[CORRECTION] scope=OBJECT, also labeled `workitem-aggregate`, completeness N/A (missing: unspecified) — "F-1: the proposal's claim that the `authorized` command never consults the session is false as written, because it requires and validates session registration as a precondition before answering, even though the verdict itself (grant-scope equality) does not use the session." (anchor: "The proposal's claim that authorized 'never consults the session' is FALSE. The command requires --session and refuses on an unregistered session")
- [S0073] types=[WARNING] scope=OBJECT, completeness N/A (missing: unspecified) — "Risk R-2: the refinement's proposed replacement text for the DP-6 table row quotes and replaces only two of the row's three cells, which if applied literally would silently drop the invariant-class marker cell." (anchor: "DP-6's replacement is an incomplete edit instruction. ... Applied literally it would drop the class marker")
- [S0074] types=[CONSTRAINT] scope=OBJECT, completeness N/A (missing: unspecified) — "The correcting lane deliberately leaves three items from Verification #2 (dating all censuses generally, a dangling grant-amendment instruction, and the authorizationLinkage open question) outside its authorized scope, reporting rather than absorbing them." (anchor: "No finding required redesign, so the STOP clause was never triggered. ... R-4 ... R-5 ... R-7 / OQ-13 — correctly deferred, deferral not recorded as a decision.")
- [S0075] types=[VALIDATION] scope=OBJECT, also labeled `census-method-defect-pattern`, completeness N/A (missing: unspecified) — "Verification #3 confirms all three of the correction's own repairs (R-1/R-2/R-3) as accurate and re-derived from primary evidence, and that the change boundary held (all hunks fall inside the four declared sites, nothing reaches BC-7 ownership, the aggregate, ADR-AIP-03, or CAP-14)." (anchor: "EXECUTIVE VERDICT — VERIFIED WITH NOTES ... All three corrections (R-1, R-2, R-3) are accurate")
- [S0076] types=[GOVERNANCE] scope=OBJECT, also labeled `census-method-defect-pattern`, completeness N/A (missing: unspecified) — "The PO/ARB accepts Verification #3 (VERIFIED WITH NOTES) via five explicit acts: accept the correction, classify F-1/F-2 as knowledge-integrity (not architecture) findings, decline to reopen the architecture model, route the recurring measurement-provenance defect to future KES governance work, and keep the independence limitation on permanent record." (anchor: "1. Accept BC-7 correction. 2. Record F-1 and F-2 as knowledge integrity findings. 3. Do not reopen architecture model. 4. Route measurement provenance issue to future KES governance work. 5. Keep independence limitation recorded.")
- [S0076] types=[WARNING] scope=OBJECT, also labeled `workflow-mechanism`, completeness N/A (missing: unspecified) — "A record defect is disclosed rather than repaired: the Verification #3 lane ran and was accepted despite never having been formally STARTed in the workflow record, named as the same class as an earlier off-record verification and live evidence for confirmed gaps G-3 and EKS-01." (anchor: "The Verification #3 lane was never STARTed on the record. ... nothing was back-dated, and the acceptance stands on the report's substance")
- [S0077] types=[GOVERNANCE] scope=OBJECT, also labeled `workitem-aggregate`, completeness N/A (missing: unspecified) — "The BC-7 tactical domain model is formally accepted as refined and corrected, the registration explicitly summarizing the full assurance chain (four verification passes, three architecture passes, one eligibility rejection, one correction cycle) as evidence it was never accepted merely on its author's word." (anchor: "Four independent verification passes, three architecture passes, one eligibility rejection, one correction cycle — the model was never accepted on its author's word.")
- [S0077] types=[LIMITATION] scope=OBJECT, completeness N/A (missing: unspecified) — "Two qualifications (a chain-level independence weakness; the Verification #3 off-record-START defect) are carried forward as permanent, never-repaired-away qualifications on the accepted model's assurance, rather than being resolved or hidden." (anchor: "Both are permanent qualifications on this chain's assurance, disclosed at the moment of acceptance rather than after it.")
- [S0079] types=[WARNING] scope=OBJECT, also labeled `workflow-mechanism`, completeness N/A (missing: unspecified) — "The BASELINE-003 Verification #3 off-record-START defect is restated here as standing, unrepaired evidence for the confirmed governance gaps G-3 and EKS-01, closed in the record with the defect explicitly stated rather than hidden." (anchor: "Verification #3 of KOS-ARCH-BASELINE-003 ran off-record ... Live evidence for confirmed gap G-3 and for EKS-01; no START was back-dated.")
- [S0080] types=[PRINCIPLE] scope=OBJECT, completeness N/A (missing: unspecified) — "A DDD maturity point is registered as settled: having finished the BC-7 strategic and tactical architecture arc, further work on it should be driven only by new evidence or explicit change pressure, never by open-ended architectural polishing." (anchor: "Do not reopen BC-7 absent new evidence or explicit change pressure ... subsequent work should be driven by new evidence or explicit change pressure, not by endless architectural polishing.")

## Notes for P3

- This label sits in 6 candidate groups — a relatively high cross-reference count for this batch, worth prioritizing in P3's reconciliation queue.
