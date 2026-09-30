# kos-pointer-layer-principle

**Scope(s):** THEORY-LEVEL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `PLP-01` · **Aliases:** `Agent pointer layer`, `Pointer-Layer Principle`
**Candidate group membership (NOT an identity claim):**
- **G0282**: [`kos-architecture-fitness-rules` · `kos-pointer-layer-principle`] — explicit agent-stated uncertainty: 'kos-pointer-layer-principle' POSSIBLY relates to 'kos-architecture-fitness-rules' (batch B0025). Note: Step 134's Pointer-Layer Principle PLP-01 (agent-specific artifacts, e.g. AGENTS.md/.claude/.codex/memory, may point to authoritative knowledge but must not silently redefine it) and the diagrammed pointer-layer architecture, operationalizing the same concern as AFR-11/AFR-12 with a distinct named principle.

## Sources (how this label entered the ledger)
- **PROPOSAL** batch `B0025`, scope `THEORY-LEVEL`: Step 134's Pointer-Layer Principle PLP-01 (agent-specific artifacts, e.g. AGENTS.md/.claude/.codex/memory, may point to authoritative knowledge but must not silently redefine it) and the diagrammed pointer-layer architecture, operationalizing the same concern as AFR-11/AFR-12 with a distinct named principle.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1035 §"Does Codex consume the same knowledge authority? If not: KnowledgeAuthorityFragmentation."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1035 §"Does Codex consume the same knowledge authority? If not: KnowledgeAuthorityFragmentation."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1035. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1035, S1035 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1035 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1035 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1035] types=['FORMALIZATION', 'WARNING'] scope=THEORY-LEVEL — "Classifies the Claude harness as AgentIntegration (instructions/settings/hooks/commands/memory/workflow, explicitly not enterprise knowledge authority) and poses the Codex symmetry question — if Codex consumes the same knowledge authority, semantic symmetry is achieved; if not, KnowledgeAuthorityFragmentation results; diagrams the pointer-layer architecture (Authoritative Knowledge -> {Claude Adapter->.claude/->Agent, Codex Adapter->.codex/->Agent})." (anchor: "Does Codex consume the same knowledge authority? If not: KnowledgeAuthorityFragmentation.")
- [S1035] types=['INVARIANT'] scope=THEORY-LEVEL — "Classifies AGENTS.md as an OperatingContract boundary artifact (how the agent should behave, how to discover KnowledgeOS, what tools may be used, what verification is required) that must not become KnowledgeAuthority, formalizing the Pointer-Layer Principle PLP-01, applying to Claude, Codex, and future agents." (anchor: "PLP-01: Agent-specific artifacts may point to authoritative engineering knowledge but must not silently redefine it.")
- [S1035] types=['FORMALIZATION'] scope=THEORY-LEVEL — "Extends PLP-01 to .claude/memory/ (desired status ContextualMemory, potentially CandidateKnowledge, but not automatically AuthoritativeKnowledge), giving the full memory-promotion pipeline, and requiring investigation of whether the existing session-logging/observation/knowledge-artifact/verification/governance mechanisms are explicitly connected or merely adjacent." (anchor: "Agent observation → Local memory → Candidate claim → Evidence → Verification → Governed knowledge")

## Notes for P3
(none beyond what is noted above)
