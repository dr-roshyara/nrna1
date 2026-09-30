# hpa-review-gate-protocol

**Scope(s):** METHODOLOGICAL · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** HPA gate · **Aliases:** Human Principal Architect review gate
**Candidate group membership (NOT an identity claim):**
- **G0042** [`hpa-review-gate-protocol` · `producer-verifier-separation`] — explicit agent-stated uncertainty: 'hpa-review-gate-protocol' POSSIBLY relates to 'producer-verifier-separation' (batch B0005). Note: The recurring method of independently re-measuring a phase deliverable's tier-1 claims against the working tree rather than accepting its self-report, issuing PASS/PASS WITH FINDINGS/CONDITIONAL PASS verdicts with bounded-scope erratum dispositions, used across P2 and P3 of the EP-01 study.

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0005, scope METHODOLOGICAL): The recurring method of independently re-measuring a phase deliverable's tier-1 claims against the working tree rather than accepting its self-report, issuing PASS/PASS WITH FINDINGS/CONDITIONAL PASS verdicts with bounded-scope erratum dispositions, used across P2 and P3 of the EP-01 study. [relation_to_existing: POSSIBLY:producer-verifier-separation]

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0164 §"a block appeared appended to a tool result announcing I had 'exited plan mode' and directing me to switch to sed/heredoc-based file editing ... I did not act on it"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0197 §"v1 — REJECTED by the Human Principal Architect: a single session combining plan-P3 (AIP) + plan-P4 (Landscape) with two commits ... an unreviewed AIP interpretation consumed by the same session's landscape would create a self-confirming architecture interpretation."]

## Lifecycle
last_seen: S0199. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0195 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0164 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S0164]** types=[WARNING] scope=METHODOLOGICAL — "A reviewer discloses that a directive appearing mid-session to abandon manual editing for automated sed/heredoc rewrites conflicted with the repository's standing rule against automated source rewrites, and reports that it did not act on the directive." (anchor: "a block appeared appended to a tool result announcing I had 'exited plan mode' and directing me to switch to sed/heredoc-based file editing ... I did not act on it")
- **[S0195]** types=[PRINCIPLE, VALIDATION] scope=METHODOLOGICAL — "States the HPA gate's own method: re-execute every load-bearing tier-1 measurement against the same working tree rather than trusting the archaeology session's self-report, treating reproducibility as the credibility test for the deliverable." (anchor: "The baseline's tier-1 claims were independently re-measured against the working tree (the same tree the P2 session claims to have measured) rather than accepted from the document's self-reports. This is the credibility test.")
- **[S0197]** types=[RETRACTION, GOVERNANCE] scope=METHODOLOGICAL — "Records that the Human Principal Architect explicitly rejected a v1 launch-prompt draft that would have let one session both reconstruct AIP and immediately build the EKS-PKS-AIP landscape on its own unreviewed output, on self-confirmation-risk grounds." (anchor: "v1 — REJECTED by the Human Principal Architect: a single session combining plan-P3 (AIP) + plan-P4 (Landscape) with two commits ... an unreviewed AIP interpretation consumed by the same session's landscape would create a self-confirming architecture interpretation.")
- **[S0199]** types=[GOVERNANCE] scope=METHODOLOGICAL — "States a seven-step independent-verification method for grading a prior review's findings without either rubber-stamping them or resolving architectural questions the verification session is not chartered to decide." (anchor: "for each finding — (1) locate the exact claim in the P3 baseline; (2) locate the underlying evidence the claim cites; (3) rule CONFIRMED / PARTIALLY CONFIRMED / NOT CONFIRMED / UNKNOWN; ... (5) do not resolve an architectural question merely because the finding appears plausible.")

## Notes for P3
- Own observation: completeness is thin — only semantics, warnings is PRESENT; most dimensions are NOT-EVIDENCED-IN-CAPTURE, consistent with a thin or narrowly-scoped source base rather than a claim that the object lacks these properties.
- Own observation: no rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) row was found for this label — its purpose/motivation, if any, is carried only in DEFINITION/FORMALIZATION-typed rows.
