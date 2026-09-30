# kos-maturity-ladders

**Scope(s):** THEORY-LEVEL · **Row count:** 5 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** A0..A6, AgentCapability<=GovernanceAssurance, G0..G6, K0..K6 · **Aliases:** Governance/Knowledge/Agent maturity ladders
**Candidate group membership (NOT an identity claim):**
- **G0281** [`kos-maturity-ladders` · `operating-model-maturity-ladder`] — explicit agent-stated uncertainty: 'kos-maturity-ladders' POSSIBLY relates to 'operating-model-maturity-ladder' (batch B0025). Note: Step 132's three independent seven-level maturity ladders for Governance (G0-G6), Knowledge (K0-K6), and Agent (A0-A6) capability, plus the design principle AgentCapability<=GovernanceAssurance guarding against agent capability outpacing governance/evidence maturity; distinct from but parallel to the earlier M0-M6 operating-model-maturity-ladder (S1024/Step 125), which measures the operating model as a whole rather than these three separate dimensions.
- **G0768** [`agent-autonomy-class-model` · `kos-maturity-ladders`] — labels share the notation 'A0..A6' — ⚠ likely noise (agent review): generic zero-indexed ladder/level numbering; plausible independent coinage between an autonomy-class model and a maturity-ladder object.

## Sources (how this label entered the ledger)
- **PROPOSAL**, batch B0025, scope THEORY-LEVEL (relation_to_existing: POSSIBLY:operating-model-maturity-ladder): Step 132's three independent seven-level maturity ladders for Governance (G0-G6), Knowledge (K0-K6), and Agent (A0-A6) capability, plus the design principle AgentCapability<=GovernanceAssurance guarding against agent capability outpacing governance/evidence maturity; distinct from but parallel to the earlier M0-M6 operating-model-maturity-ladder (S1024/Step 125), which measures the operating model as a whole rather than these three separate dimensions.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1031 §"G0 Governance exists only in documents ... G6 Governance closes the runtime feedback loop."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1031 §"G0 Governance exists only in documents ... G6 Governance closes the runtime feedback loop."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1043. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1031, S1043 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1031 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1031 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows
- [S1031] types=['FORMALIZATION'] scope=THEORY-LEVEL — "Defines a seven-level Governance maturity ladder G0-G6 (documents only -> indexed documents -> registered decisions -> decisions with authority/provenance/lifecycle -> decisions connected to implementation -> decisions connected to deterministic verification -> closed runtime feedback loop) and requires establishing whether governance concepts are represented as first-class KnowledgeOS semantic objects or merely documents/processes." (anchor: "G0 Governance exists only in documents ... G6 Governance closes the runtime feedback loop.")
- [S1031] types=['FORMALIZATION'] scope=THEORY-LEVEL — "Defines a seven-level Knowledge maturity ladder K0-K6 (documents -> searchable -> structured -> governed -> evidence-linked -> machine-traversable -> continuously verified), with K6 as the approximate target and the current level to be reconstructed by evidence." (anchor: "K0 Documents ... K6 Continuously verified knowledge.")
- [S1031] types=['FORMALIZATION'] scope=THEORY-LEVEL — "Defines a seven-level Agent maturity ladder A0-A6 (standalone tool -> repository-aware -> knowledge-aware -> governed -> evidence-producing -> authorized-action -> continuously assured), estimating current Claude/Codex architecture as beyond A1 but requiring evidence to establish the exact level." (anchor: "A0 Standalone AI tool ... A6 Continuously assured agent.")
- [S1031] types=['PRINCIPLE', 'WARNING'] scope=THEORY-LEVEL — "States the crucial architecture-gap risk: A5 agent capability paired with only K2 knowledge maturity is dangerous (AgentCapability > KnowledgeAssurance creates architectural risk), formalizing the design principle AgentCapability ≤ GovernanceAssurance — the more powerful the agent, the stronger the surrounding governance/evidence architecture must be; worked example — an agent that can Read+Write+Deploy while KnowledgeOS only knows Documents+Search means the agent can act faster than the organization can establish what should be true, 'unacceptable for governed engineering.'" (anchor: "AgentCapability ≤ GovernanceAssurance")
- [S1043] types=['FORMALIZATION'] scope=THEORY-LEVEL — "Defines a seven-level architectural maturity ladder (Level 0 files scattered, Level 1 structured artifacts with known locations/formats/conventions, Level 2 registry with stable identities/metadata, Level 3 governance integration, Level 4 evidence integration, Level 5 Assurance Graph with cross-context relationships/traceability/historical reconstruction, Level 6 governed autonomous engineering Context->Reasoning->Authorization->Action->Evidence->Verification); argues maturity is multi-dimensional (Maturity=f(Governance,Knowledge,Evidence,Assurance,Agent,Traceability)) since a platform may be Level 5 in deterministic assurance but Level 2 in knowledge authority — not a single number, distinct from the earlier per-dimension G0-G6/K0-K6/A0-A6 ladders (S1031)." (anchor: "Level 0 Files ... Level 6 Governed autonomous engineering. Maturity = f(Governance,Knowledge,Evidence,Assurance,Agent,Traceability).")

## Notes for P3
None beyond what is recorded above.
