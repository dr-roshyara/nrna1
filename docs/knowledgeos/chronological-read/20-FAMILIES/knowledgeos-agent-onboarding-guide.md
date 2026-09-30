# knowledgeos-agent-onboarding-guide

**Scope(s):** METHODOLOGICAL · **Row count:** 10 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0002, scope METHODOLOGICAL): The developer guide's 'linked, not copied' method for onboarding any AI coding agent onto an existing KnowledgeOS.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0066 §"Configure the AI agent to navigate and consume KnowledgeOS; do not copy KnowledgeOS into the agent harness."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0066 §"KnowledgeOS / Governance ↓ Architecture ↓ DDD principles ↓ Approved ADRs ↓ Approved Design ↓ Implementation ↓ Verification"]
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0066 §"Definition of Done ... KnowledgeOS has not been duplicated · architecture has not been duplicated · ... cold-boot qualification passes · independent architecture verification passes"]

## Lifecycle
last_seen: S0066. Candidate lifecycle: DORMANT.
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
| semantics | PRESENT | S0066 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0066 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S0066]** types=[PRINCIPLE] scope=METHODOLOGICAL — "The guide's central rule for onboarding any AI agent: the agent harness must be configured to navigate and consume KnowledgeOS, never to copy KnowledgeOS content into itself." (anchor: "Configure the AI agent to navigate and consume KnowledgeOS; do not copy KnowledgeOS into the agent harness.")
- **[S0066]** types=[PRINCIPLE] scope=METHODOLOGICAL — "A single project engineering truth (KnowledgeOS/governance/architecture/DDD/ADRs/shared scripts) is meant to be consumed by multiple peer AI execution harnesses (Claude, Codex, future agents) without duplication." (anchor: "One project engineering truth. Multiple AI execution harnesses.")
- **[S0066]** types=[PRINCIPLE] scope=METHODOLOGICAL — "The Golden Rule for agent-harness content: the agent harness may reference KnowledgeOS via pointers but must never reproduce its contents, since reproduction creates a competing source of truth." (anchor: "Linked, not copied.")
- **[S0066]** types=[PRINCIPLE] scope=METHODOLOGICAL — "The DDD onboarding mindset instructs an agent to begin any domain-behavior change by identifying the responsibility/boundary being changed, never by first picking a class to edit, so the existing code structure cannot dictate the domain model." (anchor: "Start with the responsibility and boundary being changed—not with the class to edit.")
- **[S0066]** types=[CONCEPT] scope=METHODOLOGICAL — "An explicit authority hierarchy is defined so an agent cannot make an implementation decision and then retroactively treat that implementation as if it were architectural authority." (anchor: "KnowledgeOS / Governance ↓ Architecture ↓ DDD principles ↓ Approved ADRs ↓ Approved Design ↓ Implementation ↓ Verification")
- **[S0066]** types=[CONSTRAINT] scope=METHODOLOGICAL — "A list of explicit stop conditions requires an agent to identify conflict, cite evidence, explain impact, stop, and ask for resolution, rather than guessing past an unclear authority or scope boundary." (anchor: "Stop when: architectural authority conflicts · governance authority is unclear · domain ownership is unclear · ... a verification gate produces an unexplained failure · the requested change exceeds its scope")
- **[S0066]** types=[DISTINCTION] scope=METHODOLOGICAL — "Four authorization states (Authorized, Implemented, Verified, Completed) must be kept distinct by an operating agent, since a proposal, code, or passing tests do not by themselves constitute approval or completion." (anchor: "Architecture proposal exists ≠ Architecture approved ... Code exists ≠ Implementation authorized ... Tests pass ≠ Work completed")
- **[S0066]** types=[PRINCIPLE] scope=METHODOLOGICAL — "The guide's closing one-sentence mental model restates the golden rule as the foundation for a multi-agent, DDD-oriented engineering platform in which multiple agents can evolve independently without creating competing sources of engineering truth." (anchor: "Configure AI agents to consume KnowledgeOS; never configure KnowledgeOS to belong to an AI agent.")
- **[S0066]** types=[GOVERNANCE] scope=METHODOLOGICAL — "A Definition-of-Done checklist for AI-agent integration requires demonstrating non-duplication of KnowledgeOS/architecture/DDD/governance/scripts, protected existing harnesses, defined stop conditions, and both a read-only cold-boot qualification and an independent architecture verification pass." (anchor: "Definition of Done ... KnowledgeOS has not been duplicated · architecture has not been duplicated · ... cold-boot qualification passes · independent architecture verification passes")
- **[S0066]** types=[WARNING] scope=METHODOLOGICAL — "State artifacts such as agent-specific memory/context files must not be assumed authoritative or safely shared across agents merely because another agent needs project context; ownership, authority, and write-access must be established first." (anchor: "Do not automatically assume that an agent-specific memory file is the canonical project state. ... Ownership must first be established.")

## Notes for P3
- Own observation: completeness is thin — only semantics, warnings is PRESENT; most dimensions are NOT-EVIDENCED-IN-CAPTURE, consistent with a thin or narrowly-scoped source base rather than a claim that the object lacks these properties.
- Own observation: ungrouped in P2a — no co-occurrence or notation signal tied it to another label; may be a genuinely isolated object, or simply under-linked by the mechanical pass.
- Own observation: no rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) row was found for this label — its purpose/motivation, if any, is carried only in DEFINITION/FORMALIZATION-typed rows.
