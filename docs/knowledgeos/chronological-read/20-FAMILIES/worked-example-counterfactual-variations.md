# worked-example-counterfactual-variations

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Conflict(PaymentConfirmed(S))`, `MissingPremise`, `rho_AI` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G1088: `worked-example-counterfactual-variations` · `worked-example-counterfactual-manager-rejects` — working_label token overlap Jaccard=0.50 (shared tokens: counterfactual, example, worked). Relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope OBJECT: "Five deliberate counterfactual stress-tests on the base example: missing premise, explicit rejection, conflicting sources, an AI-invented unauthorized rule, and a rule-version change, each demonstrating a specific Part XXI safeguard concretely."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2786 §"Suppose the payment record cannot be retrieved. ... The system must not conclude not-ReleasePermitted(S). ... MissingEvidence ⇏ False. ... PaymentRejected != PaymentUnknown. ... Conflict(PaymentConfirmed(S)) ... Zero_release(K)=false."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S2786 §"Suppose the payment record cannot be retrieved. ... The system must not conclude not-ReleasePermitted(S). ... MissingEvidence ⇏ False. ... PaymentRejected != PaymentUnknown. ... Conflict(PaymentConfirmed(S)) ... Zero_release(K)=false."]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2786. Candidate lifecycle: ACTIVE. Evidence: no `retracted_by`, no `superseded_by`, `contested_by_own_contradiction_type: false`. Both of this label's rows come from a single source (S2786); the ACTIVE reading is a recency heuristic based on where S2786 sits in the corpus, not a confirmed ongoing-use claim.

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
| semantics | PRESENT | S2786 |
| examples | PRESENT | S2786, S2786 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2786 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (`rationale_evidence` is empty in the family data).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE (`assumption_register` is empty in the family data).

## All rows (source_id order)
- [S2786] types=[EXAMPLE, EXPERIMENT] scope=OBJECT — "§21A.20-21A.23: walks through four deliberate counterfactual variations on a base worked example. (1) Missing premise: an unretrievable payment record gives ReasoningFailure=MissingPremise; the system must not conclude ¬ReleasePermitted(S), leaving Δ_release={PaymentConfirmed(S)} and the proposition Undetermined — MissingEvidence⇏False. (2) Explicit rejection: PaymentStatus(S)=Rejected supports p1'=¬PaymentConfirmed(S), a materially different reason for the same gap (PaymentRejected≠PaymentUnknown, illustrating Unknown≠False while noting explicit contradictory evidence CAN support a negative proposition). (3) Conflicting sources: one system reports PaymentConfirmed(S) while the authoritative ledger reports ¬PaymentConfirmed(S); the system must not silently pick one but must produce a Conflict object preserving Support(p1), Support(¬p1), Sources, Times, Authority, Provenance — until ConflictResolved, Δ_release≠∅ and Zero_release(K)=false. (4) AI-invented rule: an AI proposes an unregistered threshold rule; since the organizational rule registry contains no such rule, Authorized(ρ_AI)=false and the engine must classify it UnauthorizedRule regardless of model confidence or plausibility — AIGeneratedRule⇏AuthorizedRule." (anchor: "Suppose the payment record cannot be retrieved. ... The system must not conclude not-ReleasePermitted(S)...")
- [S2786] types=[EXAMPLE, PRINCIPLE] scope=OBJECT — "§21A.24: a fifth counterfactual — the release rule is revised from v3 to v4 (adding an InsuranceVerified conjunct); Proof_v3 remains historically valid under v3 while being incomplete under v4, so KnowledgeOS preserves Proof_v3 and marks the current determination Superseded or RequiresReevaluation rather than rewriting history, directly instantiating Part XXI's HistoricalValidity≠CurrentValidity principle." (anchor: "Rule version v3 ... Later, version v4 adds InsuranceVerified. ... The old proof remains historically valid under v3. But under v4, the proof is incomplete. ... marks the current determination as Superseded or RequiresReevaluation. It does not rewrite history.")

## Notes for P3
- Observation: this is a thin (2-row), single-source family, but the two rows together enumerate five distinct counterfactual variations (missing premise, explicit rejection, conflicting sources, AI-invented unauthorized rule, rule-version change) — each illustrating a different Part XXI safeguard per the source note. The file lists them individually inside the two rows' statements rather than as five separate rows, since that is how the derived data groups them.
- Observation: this label's candidate group G1088 (`worked-example-counterfactual-manager-rejects`) is a plausible sibling worked-example family from the same Part XXI theory-development effort; P3 may want to check whether "explicit rejection" (variation 2 above) and the sibling label's "manager-rejects" scenario are the same worked example described twice or genuinely distinct — this file makes no such claim, only flags it.
