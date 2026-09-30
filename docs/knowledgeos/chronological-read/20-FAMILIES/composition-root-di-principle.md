# composition-root-di-principle

**Scope(s):** METHODOLOGICAL · **Row count:** 6 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** Composition Root, Spring DI principle
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0005, scope METHODOLOGICAL): The proposal to adopt dependency-inversion + a single composition root for KnowledgeOS capability wiring, explicitly separated from adopting the Spring framework itself; DI assembles capability, it does not create authority.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0166 §"Objects should receive their dependencies from an external composition mechanism instead of creating them internally."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0166 §"Layer 1 — Domain Ports ... Layer 2 — Capability Adapters ... Layer 3 — Platform Composition Root"]
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0166 §"I would not immediately create ADR-KOS-003 yet. I would first register this as a future architecture exploration / candidate decision, similar to EKS-05."]

## Lifecycle
last_seen: S0181. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type: true.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0166 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0166 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0166 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0166 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S0166]** types=[PRINCIPLE, DEFINITION] scope=METHODOLOGICAL — "States the dependency-inversion principle KnowledgeOS should adopt independent of any specific framework: dependencies are injected via a Composition Root, not constructed internally by domain services." (anchor: "Objects should receive their dependencies from an external composition mechanism instead of creating them internally.")
- **[S0166]** types=[DISTINCTION, WARNING] scope=THEORY-LEVEL — "Warns that a DI container answers 'which implementation should I call' while Governance answers 'which authority is allowed to decide' — the container cannot decide governance ownership, so the two must not be conflated." (anchor: "DI ≠ Authority Resolution ... DI assembles capability. Governance establishes legitimacy.")
- **[S0166]** types=[CONCEPT, EXTENSION] scope=THEORY-LEVEL — "Proposes a three-layer pattern for KnowledgeOS: bounded contexts own domain ports (e.g. EvidenceStore, EvidenceVerifier), technical adapters implement them, and a Platform Composition Root binds ports to concrete adapters via configuration ('Capability Binding')." (anchor: "Layer 1 — Domain Ports ... Layer 2 — Capability Adapters ... Layer 3 — Platform Composition Root")
- **[S0166]** types=[WARNING, CONSTRAINT] scope=METHODOLOGICAL — "Warns against Spring's worst anti-patterns (field injection hiding dependencies, service-locator/full-container injection, excessive constructor parameters, implicit/circular wiring); an explicit research pass corroborates each warning against external DI/hexagonal literature." (anchor: "Avoid @Autowired Everything everything; ... field injection, service locators and container injection ... constructor over-injection")
- **[S0166]** types=[GOVERNANCE] scope=METHODOLOGICAL — "Recommends registering the composition-root/DI proposal as a future architecture exploration (EKS-06) rather than freezing an ADR immediately, since several concepts touch existing boundaries not yet validated." (anchor: "I would not immediately create ADR-KOS-003 yet. I would first register this as a future architecture exploration / candidate decision, similar to EKS-05.")
- **[S0181]** types=[CONTRADICTION] scope=CROSS-OBJECT — "Surfaces T4: the DI-principle proposal explicitly wants the principle without the Spring framework, yet the kernel-technology brainstorm pairs the same principle with a recommended Spring/Kotlin runtime." (anchor: "DI principle vs framework import: adopt the DI/composition-root principle without adopting Spring — the corpus wants the first, but the kernel file pairs the principle with the Spring runtime.")

## Notes for P3
- Own observation: the CONTESTED flag is evidenced (see Lifecycle section); P3 should read the underlying contradiction/retraction rows before treating this label as settled either way.
- Own observation: ungrouped in P2a — no co-occurrence or notation signal tied it to another label; may be a genuinely isolated object, or simply under-linked by the mechanical pass.
- Own observation: no rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) row was found for this label — its purpose/motivation, if any, is carried only in DEFINITION/FORMALIZATION-typed rows.
