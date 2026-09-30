# agent-autonomy-class-model

**Scope(s):** THEORY-LEVEL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `A0..A6`, `A2: capability MUST NOT be interpreted as authority`, `AgentAuthority ⊆ DelegatedAuthority` · **Aliases:** `Step 124 autonomy boundary`, `autonomy classes`
**Candidate group membership (NOT an identity claim):**
- **G0768** [`agent-autonomy-class-model` · `kos-maturity-ladders`] — labels share the notation 'A0..A6' — ⚠ likely noise (agent review): generic zero-indexed ladder/level numbering; plausible independent coinage between an autonomy-class model and a maturity-ladder object.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0025, scope THEORY-LEVEL): Step 124's seven-level agent autonomy-class scale (A0 Read .. A6 Change organizational authority) plus the Capability/Permission/Delegation/Authority distinction and constitutional agent-rule A2, governing what technical capability an agent may exercise versus what it is authorized to do.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1023] §"AgentAuthority ⊆ DelegatedAuthority."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1023] §"AgentAuthority ⊆ DelegatedAuthority."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1023. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S1023 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1023 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1023] types=[PRINCIPLE, FORMALIZATION] scope=THEORY-LEVEL — "Defines the autonomy boundary: an agent may possess technical capability greater than its permitted authority, which is acceptable only if the enforcement boundary prevents unauthorized action (AgentAuthority ⊆ DelegatedAuthority); distinguishes four non-interchangeable concepts Capability, Permission, Delegation, Authority, illustrated by an agent having kubectl-delete credentials (Capability=True) while governance sets ProductionDelete=False (Authority=False) — the agent must not treat the credential as authorization." (anchor: "AgentAuthority ⊆ DelegatedAuthority.")
- [S1023] types=[PRINCIPLE, EXTENSION] scope=THEORY-LEVEL — "Adds agent-specific constitutional rule A2 ('technical capability MUST NOT be interpreted as organizational authority'), stated as complementary to C2 (Authority); notes autonomy is action-specific rather than agent-wide — RunArchitectureCheck can often be autonomous while Finding->ChangeProductionArchitecture may require human authority." (anchor: "A2: Technical capability MUST NOT be interpreted as organizational authority.")
- [S1023] types=[FORMALIZATION] scope=THEORY-LEVEL — "Defines a seven-level autonomy-class scale for agent actions: A0 Read/inspect, A1 Analyze/reason over evidence, A2 Verify/execute deterministic checks, A3 Recommend/propose action, A4 Execute reversible low-risk action within explicit delegation, A5 Execute governed production action requiring explicit delegated authority, A6 Change organizational authority requiring explicit human/organizational governance; worked through experiments A1-A5 (read=ALLOW; run tests=ALLOW if tool access permitted; create remediation proposal=ALLOW since a proposal is not yet an organizational decision; merge to protected production branch=DENY without explicit delegation [A4/A5]; modify the KnowledgeOS Constitution itself=A6, DENY without constitutional authority)." (anchor: "A0 — Read ... A6 — Change organizational authority")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
