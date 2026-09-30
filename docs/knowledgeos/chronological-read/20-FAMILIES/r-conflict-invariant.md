# r-conflict-invariant

**Scope(s):** THEORY-LEVEL · **Row count:** 17 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** R-CONFLICT · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G1138: [`migration-plan-amendment-chain` · `r-conflict-invariant`] — labels co-occur in the same contribution's labels[] 4 separate times across the corpus

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0004 · scope THEORY-LEVEL: The adopted invariant that a governance-evidence conflict must never be resolved by silently choosing a side, deleting history, or rewriting sequence; operationalized via STRICT EXTENSION vs PREFIX DISAGREEMENT tests and later a CASE alpha/beta pre-switch/post-switch split.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0128 §"R-CONFLICT (PROPOSED): a conflict in the authority record is never resolved by choosing a side. seq density (O-6) makes loss detectable; a resolution that leaves a seq gap is invalid by construction."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0131 §"STRICT EXTENSION ... append the runtime tail M+1…N ... PREFIX DISAGREEMENT ... MUST NOT be resolved mechanically."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0300. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type=True

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0130 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0131, S0140, S0159, S0160, S0299 |
| type_signature | PRESENT | S0131 |
| invariants | PRESENT | S0128, S0160 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0129, S0298, S0300 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0137, S0138, S0141, S0299 |
| experiments | PRESENT | S0300 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The R-CONFLICT decision (D2) binds how the relocation (D1) may be implemented: the migration must preserve sequence integrity and provenance, must not delete or re-create history, and if a relocated record and a runtime record ever diverge, R-CONFLICT governs — neither side may be silently chosen [S0130].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0128] types=[INVARIANT] scope=THEORY-LEVEL — "Proposes R-CONFLICT: any resolution of a governance-evidence conflict is invalid by construction if it leaves a sequence gap, chooses one side silently, or is otherwise not seq-density-preserving." (anchor: "R-CONFLICT (PROPOSED): a conflict in the authority record is never resolved by choosing a side. seq density (O-6) makes loss detectable; a resolution that leaves a seq gap is invalid by construction.")
- [S0129] types=[PRINCIPLE] scope=THEORY-LEVEL — "A candidate governance principle proposed alongside R-CONFLICT and other estate principles (Recording ≠ Asserting; Reference ≠ Ownership): History ≠ Reconstruction Guess — recorded as a recommendation, not yet adopted at this point in the file." (anchor: "History ≠ Reconstruction Guess")
- [S0130] types=[ANALYSIS] scope=CROSS-OBJECT — "The R-CONFLICT decision (D2) binds how the relocation (D1) may be implemented: the migration must preserve sequence integrity and provenance, must not delete or re-create history, and if a relocated record and a runtime record ever diverge, R-CONFLICT governs — neither side may be silently chosen." (anchor: "D2 constrains how D1 is implemented — this is the load-bearing consequence.")
- [S0131] types=[FORMALIZATION] scope=THEORY-LEVEL — "R-CONFLICT is operationalized into exactly two mechanically-distinguishable divergence shapes: STRICT EXTENSION (the common prefix is byte-identical and one side merely extends further — resolved automatically as a union, not a choice) and PREFIX DISAGREEMENT (the common prefix itself differs — a genuine authority conflict that must be recorded, preserved on both sides, and escalated to a PO/ARB act, never resolved mechanically)." (anchor: "STRICT EXTENSION ... append the runtime tail M+1…N ... PREFIX DISAGREEMENT ... MUST NOT be resolved mechanically.")
- [S0137] types=[WARNING] scope=OBJECT — "RC-9: the plan's 'common prefix byte-identical' comparison rule for CASE A does not define its canonicalization method (e.g. key-order sorting), and since JSON decode/encode preserves insertion order, two semantically equal but differently-ordered records would compare unequal, producing a false escalation to CASE B rather than a false silent merge (CASE A) — the safe failure direction, but ambiguous as written." (anchor: "RC-9 — 'canonical re-encode' is not defined ... a false CASE B (escalation) rather than a false CASE A (silent merge) — the safe direction")
- [S0138] types=[WARNING] scope=OBJECT — "RD-3: AMD4 routes every Phase-7 mismatch to the same reconciliation logic (CASE A/B), but CASE A appends the runtime tail as a legitimate extension, meaning a post-writer-switch write to the demoted store would be silently imported into the authoritative store — contradicting the plan's own rule that re-promoting a demoted source is a governance act, not a merge." (anchor: "RD-3 — Phase-7 mismatch disposition routes post-demotion writes into §6 CASE A, which would import non-authoritative bytes that §8 says require a governance act")
- [S0140] types=[EXTENSION, FORMALIZATION] scope=OBJECT — "AMD5 splits the Phase-7 mismatch disposition into two named cases with opposite treatment: CASE α (pre-writer-switch, authoritatively-produced bytes) reconciles under the existing R-CONFLICT logic, while CASE β (post-writer-switch, written against an already-demoted store) is quarantined, recorded as a conflict, and escalated — never imported — with a conservative tie-break (unresolvable ordering defaults to CASE β)." (anchor: "CASE α — pre-writer-switch ... RECONCILE under §6 ... CASE β — post-writer-switch ... not authoritative — written against a demoted store ... QUARANTINE · record the conflict · ESCALATE. Never imported.")
- [S0141] types=[WARNING, CONTRADICTION] scope=OBJECT — "Two of AMD5's own acceptance criteria are jointly unsatisfiable after a CASE β event: criterion 12 requires the runtime source to equal 'manifest + reconciled delta' before removal, but criterion 15 defines CASE β bytes as deliberately never reconciled, so the removal gate could never be satisfied, be permanently blocked, or be satisfied only by destroying the quarantined bytes — none of which the plan states." (anchor: "RD-3·a — CASE β has no terminating condition, and criteria 12 and 15 are jointly unsatisfiable in it ... after a β event the runtime source therefore never equals manifest + reconciled delta, so §4.4's own gate can never be satisfied")
- [S0159] types=[EXTENSION, FORMALIZATION] scope=CROSS-OBJECT — "Reframes the R-CONFLICT concept as an event stream (EvidenceQualified events from two sources triggering EvidenceConflictDetected, then Conflict Resolution, then ConflictResolved), arguing this gives much stronger reconstruction capability than treating the conflict as an overwritten database row." (anchor: "The conflict history becomes a domain event stream rather than simply an overwritten database row. That gives KnowledgeOS much stronger reconstruction capability.")
- [S0160] types=[FORMALIZATION] scope=OBJECT — "Models the R-CONFLICT idea as a first-class EvidenceConflict domain aggregate (with ConflictId, ConflictingEvidence[], DetectedAt, ConflictType, ResolutionStatus, ResolutionAuthority, ResolutionDecision fields), owned by the Knowledge Evidence Context, whose invariant requires any resolution to preserve provenance, history, and reconstruction capability." (anchor: "EvidenceConflict ... - ConflictId - ConflictingEvidence[] - DetectedAt - ConflictType - ResolutionStatus - ResolutionAuthority - ResolutionDecision ... A conflict resolution must preserve: provenance; history; reconstruction capability.")
- [S0298] types=[DISTINCTION] scope=THEORY-LEVEL — "Two DDD non-confusions asserted as load-bearing for the migration's DV-3 correction: the durable store may already contain copied governance records before authority transfers, and those copied records do not themselves establish authority; the authority-boundary discriminator must be the domain event (the transfer), never physical file position." (anchor: "RECORD EXISTENCE != AUTHORITY ESTABLISHMENT; PHYSICAL STORAGE ORDER != DOMAIN EVENT ORDER")
- [S0298] types=[PRINCIPLE] scope=METHODOLOGICAL — "The assurance-tooling boundary discipline: an author's own trace/test suite is required delivered evidence but never discharges the independent-review gate." (anchor: "A self-check that can assert its own success is EVIDENCE, never assurance.")
- [S0299] types=[WARNING] scope=THEORY-LEVEL — "Names and corrects an inverted rationale (the original plan said a move would 'break the enumeration'; the corrected reasoning is the opposite -- a move makes a comparison PASS when it should FAIL) -- a general anti-pattern for any evidence-quarantine mechanism." (anchor: "QUARANTINE LAUNDERING: moving a post-demotion record out of the demoted store would hide the quarantined evidence, making the source spuriously satisfy the final-rehash comparison and incorrectly opening the removal branch.")
- [S0299] types=[FORMALIZATION] scope=OBJECT — "The load-bearing premise of the C-4 termination argument, moved inside the argument by this correction; without it the argument for where evidence lands would appear to terminate after one iteration when it does not." (anchor: "PREMISE 2: a migration mechanical write on a store confers no authority, and is evidenced by a governance append that describes it -- which is what makes the placement-decision recursion terminate.")
- [S0300] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "A reproduced concurrency experiment (mktemp dirs via --dir, never against the live corpus) showing the read-modify-write append path has no lock/lease/CAS, so a losing writer's transaction vanishes while the surviving sequence remains perfectly dense -- refuting an earlier absolute 'no record silently dropped' claim." (anchor: "23 of 30 concurrent-append trials silently lost a transition; every survivor was dense and monotonic -- density is NOT a completeness proof.")
- [S0300] types=[DISTINCTION] scope=THEORY-LEVEL — "A DDD reading of the quarantine mechanism: a single undisposed quarantine can block migration completion indefinitely, by design, because the aggregate refuses an invalid state transition rather than because of a tooling limitation." (anchor: "quarantine is a domain state with a business consequence, not a directory condition: an undisposed conflict means the migration aggregate cannot legitimately transition to its completed state.")
- [S0300] types=[DISTINCTION] scope=THEORY-LEVEL — "Distinguishes the migration's terminal cleanup act from the R-CONFLICT-forbidden act of destroying historical governance evidence." (anchor: "Removal (Phase 7) is not Deletion: evidence persists at the verified durable target, a byte-verified copy exists and is authoritative, history is relocated with provenance intact -- and removal occurs only after Phase 4 hash verification passed and Phase 5 switched.")

## Notes for P3
(none beyond what is captured above)
