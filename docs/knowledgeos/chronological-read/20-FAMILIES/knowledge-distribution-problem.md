# knowledge-distribution-problem

**Scope(s):** THEORY-LEVEL · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0679** [`capability-c14-policy-enforcement` · `ekp-eks-03-evidence-chain-path-coupling` · `knowledge-distribution-problem`] — an UNKNOWN-OBJECT-CANDIDATE row (batch B0006, source S0234) named these as alternative candidates for one piece of evidence. why_uncertain: EKS-01..04 may be the same capability-failure observations already tracked elsewhere as the knowledge-distribution-problem / EKS-03 path-coupling / C-14 enforcement-gap objects, or a distinct AIP-local enumeration of them

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0002, scope THEORY-LEVEL: The broader problem that governed operational rules are not reliably discovered by the session that needs them at runtime.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0048 §"Recording a rule is not sufficient. The responsible session must reliably discover and apply the current rule."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0206. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0048 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0048 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0206 |
| experiments | PRESENT | S0206 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S0048] (ANALYSIS) A two-specimen class of evidence (the placement rule violated within the hour of its own registration; four Governance sessions behaving inconsistently the same day) is generalized into a first-class EKS architecture problem: governance knowledge exists in the repository but is not consistently propagated to every execution role at runtime.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows
- [S0048] types=['PRINCIPLE'] scope=THEORY-LEVEL — "A second, broader candidate EKS requirement (registered as an addendum the same day) generalizes beyond placement: operational rules must be discoverable by the responsible session at startup, and inherited path precedent must never override derived placement; a rule's existence in a README is not sufficient if the executing session has no reason to open it." (anchor: "Recording a rule is not sufficient. The responsible session must reliably discover and apply the current rule.")
- [S0048] types=['ANALYSIS'] scope=THEORY-LEVEL — "A two-specimen class of evidence (the placement rule violated within the hour of its own registration; four Governance sessions behaving inconsistently the same day) is generalized into a first-class EKS architecture problem: governance knowledge exists in the repository but is not consistently propagated to every execution role at runtime." (anchor: "the programme has governance knowledge, but the runtime does not consistently propagate the current governed knowledge to every execution role.")
- [S0206] types=['WARNING', 'EXPERIMENTAL-RESULT'] scope=OBJECT — "First-hand evidence for a knowledge-distribution defect: inject-context.sh injects the ENTIRE MEMORY.md (188,730 bytes) and ENTIRE CONTEXT.md (954,708 bytes) unconditionally, with no selection/scoping/relevance filter/size bound (≈1.14MB per session start, plus the active plan and today's session log); in this very baseline-writing session, the SessionStart hook output (1.2MB) was truncated by its consumer, which persisted it to a file and delivered only a 2KB preview -- 'the knowledge-distribution mechanism exceeded its consumer's ingestion capacity and silently degraded to a pointer.' Strongest possible evidence that the mechanism exists, is active, and does not reliably deliver." (anchor: "The knowledge-distribution defect ... inject-context.sh injects the entire MEMORY.md and the entire CONTEXT.md ... .claude/MEMORY.md 188,730 bytes ... .claude/CONTEXT.md 954,708 bytes ... injected per session start ≈ 1.14 MB ... the SessionStart hook output was 1.2 MB and was truncated by the consumer")

## Notes for P3
None beyond what is recorded above.
