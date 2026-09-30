# producer-verifier-separation

**Scope(s):** METHODOLOGICAL · **Row count:** 15 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G0042: co-occurs with `hpa-review-gate-protocol` — explicit agent-stated uncertainty: 'hpa-review-gate-protocol' POSSIBLY relates to 'producer-verifier-separation' (batch B0005). Note: The recurring method of independently re-measuring a phase deliverable's tier-1 claims against the working tree rather than accepting its self-report, issuing PASS/PASS WITH FINDINGS/CONDITIONAL PASS verdicts with bounded-scope erratum dispositions, used across P2 and P3 of the EP-01 study.
- G1141: co-occurs with `inv-attr-2` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- G1387: co-occurs with `workflow-lifecycle-engine` — labels co-occur in the same contribution's labels[] 4 separate times across the corpus
- G1388: co-occurs with `inv-attr-1` — labels co-occur in the same contribution's labels[] 3 separate times across the corpus

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0002, scope METHODOLOGICAL: "The R-34/P-2 discipline that a producing lane must never verify or accept its own work."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0057 §"Architecture correction delivered. Verification #3 is required. This session does not verify its own correction."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S0078 §"Three corrections applied under G-KOS-ARCHBASE3-CORRECT ... implementing Verification #2's R-1/R-2/R-3 and nothing else"]
- CANDIDATE-GOVERNANCE-BIRTH: [S0164 §"Independence — the seven criteria, all required ... distinct process/session identity ... is not a continuation/compaction/fork/resume lineage of a barred producer"]

## Lifecycle

last_seen: S0401. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S0401), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0164, S0177, S0234, S0385, S0400, S0401 |
| dependencies | PRESENT | S0376, S0382, S0385, S0387, S0391, S0400, S0401 (×2) |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0057, S0177, S0385, S0387, S0400, S0401 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0074, S0382, S0385 |
| experiments | PRESENT | S0401 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — no row in this label's family is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S0057] types=[PRINCIPLE] scope=METHODOLOGICAL — completeness N/A — "The producer/verifier separation discipline is restated and enforced: the correcting session explicitly refuses to verify its own correction, naming a fresh Verification #3 as the required next actor." (anchor: "Architecture correction delivered. Verification #3 is required. This session does not verify its own correction.")
- [S0074] types=[WARNING] scope=OBJECT — completeness N/A — "The correcting lane discloses, rather than silently resolving, that it is in fact the same process that authored Verification #2, contradicting the delivered prompt's claim of a fresh terminal, and states its choice to proceed anyway is preserved at near-zero cost since every edit is a single-line revert." (anchor: "The correction prompt states the performer is not Verification #2 ... This pen IS the Verification #2 author.")
- [S0078] types=[IMPLEMENTATION] scope=OBJECT — also labeled `census-method-defect-pattern`, completeness N/A — "The refinement document's own banner records that it was subsequently corrected under a separate grant to apply exactly Verification #2's R-1/R-2/R-3 findings and nothing else, with the correcting pen disclosed as the same process that authored Verification #2 itself." (anchor: "Three corrections applied under G-KOS-ARCHBASE3-CORRECT ... implementing Verification #2's R-1/R-2/R-3 and nothing else")
- [S0164] types=[CONSTRAINT, GOVERNANCE] scope=METHODOLOGICAL — also labeled `inv-attr-2` — "States a seven-criterion independence test (process identity, non-authorship of the artifact/prior corrections/prior verifications, non-lineage from a barred producer, non-inherited working context) with the rule that a different session ID alone is insufficient; if lineage cannot be established the classification must be NOT ATTESTED, never PASS." (anchor: "Independence — the seven criteria, all required ... distinct process/session identity ... is not a continuation/compaction/fork/resume lineage of a barred producer")
- [S0177] types=[RESTATEMENT, PRINCIPLE] scope=THEORY-LEVEL — also labeled `deterministic-assurance-track2-program` — "Records the estate's six-item keep-apart discipline as ESTABLISHED, cited as the invariant set the deterministic-assurance capability must never violate." (anchor: "Keep-apart list (G-4, corpus): Recording ≠ Asserting · Evidence ≠ Proof · Reference ≠ Ownership · Author ≠ Independent Reviewer · Self-check ≠ Independent Assurance · Execution ≠ Governance.")
- [S0184] types=[GOVERNANCE, CONSTRAINT] scope=OBJECT — also labeled `kos-aip-gov-state-durability-program` — "A registered grant explicitly restores a producer's authoring eligibility while permanently barring it from reviewing or accepting its own amendment (AMD6), which the reviewer here reads directly from the record rather than inferring." (anchor: "ELIGIBILITY OUTCOME AS RECORDED: bc1b47ef - AUTHORING ELIGIBLE, REVIEWING BARRED, ACCEPTING BARRED ... A FRESH INDEPENDENT ARCHITECTURE PROCESS - REVIEWING ... REQUIRED.")
- [S0234] types=[INVARIANT] scope=OBJECT — also labeled `grant-record`, `human-act`, `mutation-ownership-invariant`, `inv-attr-2` — "Grants: a grant without a humanActRef is refused by the engine ('the record never manufactures authority -- G-2/R5b'). Exactly one mutation owner per work item is mechanically enforced (I-1). Closure is a governance act (G-1): COMPLETE refuses any writer but governance/human. Producer != acceptor (R-34, INV-ATTR-2): a process cannot verify/accept its own work; separation is declared, not attestable." (anchor: "Exactly one mutation owner per work item is a mechanically enforced invariant (I-1)")
- [S0376] types=[LIMITATION, GOVERNANCE] scope=OBJECT — also labeled `workflow-lifecycle-engine` — "Identifies a governance-model gap: because AST-019's implementing session never registered a lane or recorded its runtime identity, the producer-verifier separation principle cannot be mechanically enforced for its future verification (no producer identity exists to exclude), and the operating model as written has no lane-shape at all for a human-authorized amendment slice landing on a STOPPED work item." (anchor: "F-3 ... AST-019's implementing session held no registered lane on this work item, and its runtime identity is recorded nowhere by UUID ... the producer bar for AST-019's future verification is not mechanically enforceable -- there is no recorded producer identity to exclude")
- [S0382] types=[WARNING] scope=OBJECT — also labeled `workflow-lifecycle-engine` — "Observes that the accumulating list of barred identities across this multi-step governance saga has grown to 16 entries, to the point that the requirement for a genuinely fresh session (rather than any reused process) has become the sole remaining mechanism for fielding an eligible verifier at all -- recorded as a one-occurrence observation, not promoted to a general rule." (anchor: "sixteen barred identities means the eligible pool is nearly exhausted. The 'genuinely fresh session' requirement is now load-bearing rather than ceremonial -- it is the only remaining way to field a verifier at all.")
- [S0385] types=[PRINCIPLE, WARNING] scope=THEORY-LEVEL — also labeled `workflow-lifecycle-engine` — "States the circularity bar as a general governance principle: a candidate must never use the very capability it is appointed to verify (or any unauthorized capability) to manufacture its own activation, because doing so would make the eventual verification verdict rest on the subject's own unproven correctness -- self-identified independently by the candidate before any instruction forbade it." (anchor: "Using an unverified capability to manufacture the authority to verify that capability makes the verification worthless. A PASS would rest on the subject's own correctness -- the thing in question.")
- [S0387] types=[PRINCIPLE, RESTATEMENT] scope=METHODOLOGICAL — "Extends the producer-verifier separation principle (R-34/EP-02) to defect remediation: the process that authored a governance defect may analyze it fully and recommend a remedy with grounds, but must not itself decide which recovery path (proceed with documented defect vs. discard and re-appoint) is taken -- that decision is reserved for the PO/ARB." (anchor: "the author of the defect does not rule on its remedy. ... Recovery determination -- recommend A, on grounds, not convenience ... R-34/EP-02: the author of the defect does not rule on its remedy.")
- [S0391] types=[GOVERNANCE] scope=METHODOLOGICAL — also labeled `workflow-lifecycle-engine` — "Prescribes a full governed repair sequence (human CONTINUATION -> fresh implementation candidate declaration -> Governance appoints THROUGH the appointment engine, explicitly not hand-composed as in ASD-001 -> human START -> EP-01 plan approval -> RED/GREEN -> re-verification by a fourth process excluded from all three prior roles -> separate PO/ARB adoption+authorization decisions), directly applying the ASD-001 lesson by requiring the repair's own appointment go through the canonical engine this time." (anchor: "re-verification by a process that is NOT 84c0f6f6 (first verifier), NOT 1899d8bf (producer), NOT 5928b9f9 (this governance process) ... Step 3 is where the ASD-001 lesson is spent. The appointment for the repair slice must go through AST-018 appoint") — lineage claim: SOURCE-CLAIMED-EXTENSION of ASD-001 (S0387).
- [S0400] types=[PRINCIPLE, GOVERNANCE] scope=THEORY-LEVEL — also labeled `inv-attr-1` — "States the corollary that carries the load: independence must be assessed on disclosed prior participation, never merely on identity difference, since a different UUID is necessary but not sufficient evidence of true independence; the resulting safe rule is that a restarted process is treated as a new candidate until Governance explicitly re-establishes its participation, explicitly rejecting as dangerous the alternative of treating UUIDs as durable, permanent identities." (anchor: "Independence must be declared and assessed on PRIOR PARTICIPATION, not on identity difference. A different UUID is necessary but not sufficient evidence of independence. ... A restarted process is a new candidate until Governance explicitly establishes its participation.")
- [S0401] types=[VALIDATION, EXPERIMENTAL-RESULT] scope=OBJECT — also labeled `inv-attr-1` — "Confirms O-5's abstract danger with a concrete live instance: the governance-recording process that scoped and authorized REPAIR-001 reappeared under a new runtime identifier (e40f3fd0) after a restart, which appears on no identity bar list, so the mechanical appointment check (AST-018 appoint) would have accepted it as independent -- the bar had to be applied manually by the PO/ARB on participation grounds since the mechanism itself would not have caught it." (anchor: "That process authored ASD-001, scoped REPAIR-001, recorded the CONTINUATION (seq 5) and the grant G-REPAIR-001, and wrote the candidate-declaration prompt. Appointing it would collapse the separation between Governance analysis and Implementation ... The mechanism would not have caught this. AST-018 appoint might well have accepted e40f3fd0.") — lineage claim: SOURCE-CLAIMED-EXTENSION of observation O-5 (S0400).
- [S0401] types=[PRINCIPLE, GOVERNANCE] scope=METHODOLOGICAL — also labeled `inv-attr-1` — "States that a process recording a governance decision, though holding no formal workflow lane, is itself a participation act that self-bars that process from later filling an independence-sensitive role on the same matter -- governance-recording capacity is explicitly not exempt from participation-based exclusion." (anchor: "This process added itself to the implementer bar the moment it recorded §2-§6. Governance-recording is not a lane, but it is participation.")

## Notes for P3

- This is my own observation: this label participates in 4 candidate groups (G0042, G1141, G1387, G1388) — a comparatively dense cross-linkage that may be worth prioritizing in P3 reconciliation.
