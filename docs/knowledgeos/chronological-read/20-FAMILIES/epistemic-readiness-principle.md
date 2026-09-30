# epistemic-readiness-principle

**Scope(s):** OBJECT · **Row count:** 10 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Epistemic Readiness, NO_BASIS / INCOMPLETE_CONTEXT / CONTEXT_CONFLICT / UNKNOWN, R=(B,C,K) · **Aliases:** Zero Gate + Context Completeness Engine
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope OBJECT): Combines the Zero lens ('no computation without sufficient basis') and the Leonardo lens ('no decision from an incomplete bounded context') into a single kernel primitive: readiness R=(B,C,K) where B=prerequisite basis (binary per-item, product must be 1), C=context completeness (a vector/ratio over relevant dimensions e.g. domain/authority/temporal/dependencies/operational/security, bounded to the relevant DDD context not the whole universe), K=contextual consistency; four resulting states NO_BASIS (B=0), INCOMPLETE_CONTEXT (B=1,C=0), CONTEXT_CONFLICT (B=1,C=1,K=0), READY (B=1,C=1,K=1) -- only READY enters ordinary inference; formalizes EpistemicCondition != PrerequisiteCondition and an Evidence Acquisition Planner that retrieves only the targeted missing context dimension rather than everything.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0470 §"Zero asks: 'What happens when a prerequisite is missing?' Leonardo asks: 'Have we understood the whole relevant context before we decide?' Together: No computation without sufficient basis / No decision from an incomplete context."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0470 §"Unknown -> ActionableUnknown. ... status: CONTEXT_INCOMPLETE, missing: [dependency_graph, governance_owner]. ... An agent receiving UNKNOWN may hallucinate. An agent receiving this structured status can perform a targeted action."]
- CANDIDATE-FORMAL-BIRTH: [S0470 §"Z-KOS Computational Gate: An inference process shall not execute when a constitutive prerequisite for that inference is absent. ... If the request lacks context, there is no reason to spend 100 units discovering that. The kernel can spend O(1) ... and return CONTEXT_MISSING."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0470. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by/superseded_by empty, contested flag false). The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the ledger, not a confirmed retirement and not a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0470 |
| type_signature | PRESENT | S0470 |
| invariants | PRESENT | S0470 |
| dependencies | PRESENT | S0470 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0470 |
| examples | PRESENT | S0470 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0470 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0470] types=[PRINCIPLE] scope=THEORY-LEVEL — "Core complementary-constraint thesis combining Zero (basis presence) and Leonardo (context completeness) into two joint requirements for the AI-efficiency kernel." (anchor: "Zero asks: 'What happens when a prerequisite is missing?' Leonardo asks: 'Have we understood the whole relevant context before we decide?' Together: No computation without sufficient basis / No decision from an incomplete context.")
- [S0470] types=[DISTINCTION, EXAMPLE] scope=OBJECT — "Distinguishes UNKNOWN from NO_BASIS/NOT_APPLICABLE/CONTRADICTORY; illustrated by an architecture-approval question where the kernel has no proposal identity/owning context/rules/evidence/authority -- the correct result is NO_BASIS, not a low-confidence percentage, since 'absence of a constitutive prerequisite is not an epistemic state.'" (anchor: "the kernel must not interpret missing inputs as a low-confidence knowledge state. ... UNKNOWN from NO_BASIS and NOT_APPLICABLE and CONTRADICTORY. ... EpistemicCondition != PrerequisiteCondition.")
- [S0470] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Z-KOS Computational Gate formalized (Prerequisites(M,Q)=R; if R not subset of AvailableKnowledge then Inference(M,Q)=NoBasis, distinct from False/UnknownState), acting simultaneously as epistemic safeguard and computational optimization -- avoiding e.g. a 100-unit HSMM/LLM computation when the missing prerequisite could be detected at near-constant cost." (anchor: "Z-KOS Computational Gate: An inference process shall not execute when a constitutive prerequisite for that inference is absent. ... If the request lacks context, there is no reason to spend 100 units discovering that. The kernel can spend O(1) ... and return CONTEXT_MISSING.")
- [S0470] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Context completeness modeled as a vector over relevant dimensions (not a single ratio), enabling an Evidence Acquisition Planner to retrieve only the specific under-covered dimension (e.g. operations at 20%) rather than retrieving everything, formalized as an optimization E*=argmin Cost(E) subject to Prerequisites(E)=1, ContextCompleteness(E)>=tau, and eventually Assurance(E)>=required." (anchor: "context_completeness: domain: 1.0, authority: 1.0, temporal: 0.8, dependencies: 0.6, operational: 0.4, security: 1.0. ... the system doesn't need to retrieve everything. It needs to target: operations. ... targeted evidence acquisition.")
- [S0470] types=[EXAMPLE, INVARIANT] scope=OBJECT — "Context-as-part-of-identity: the word 'approved' means something different in ArchitectureGovernance (authority ARB, effect architecture-baseline-eligible) versus Security (authority SecurityBoard, effect security-review-complete) context, so a KnowledgeKey must include Context/Concept/Meaning/Version, not just the bare concept string -- also acts as an LLM efficiency mechanism (search space and prompt size shrink while semantic precision rises)." (anchor: "local truth != context-free truth ... do not import concepts across contexts without translating them. ... KnowledgeKey = (Context, Concept, Meaning, Version). Same expression. Different knowledge object.")
- [S0470] types=[CONSTRAINT, DISTINCTION] scope=OBJECT — "Leonardo's 'complete context' is scoped to one DDD bounded context, not the whole universe; DDD defines the boundary, Leonardo tests completeness inside it, producing an exceptionally clean DDD->BoundedContext->Leonardo->ContextCompleteness->Inference pipeline." (anchor: "'Complete context' must not mean: retrieve everything about everything. ... Completeness_bounded_context, not Completeness_universe. ... DecisionRelevantBoundedContext.")
- [S0470] types=[INVARIANT, FUTURE-RESEARCH] scope=THEORY-LEVEL — "New invariant guarding against a dangerous optimization: aggressive context compression (e.g. 90% size reduction, 70% latency reduction) is invalid if it removed a constitutive prerequisite (Zero) or a required contextual dimension (Leonardo); anticipates Escher (what invariant survives transformation) as a future third lens completing a 'safe compression triad.'" (anchor: "Compression must preserve epistemic readiness. ... ER(RawContext) = ER(CompressedContext) for the intended decision, or at least a formally accepted bound on the difference. ... Zero + Leonardo + Escher becomes a powerful safe compression triad.")
- [S0470] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Adds a fourth condition CONTEXT_CONFLICT (context populated but internally contradictory, e.g. ADR says approved while a governance record says rejected) distinct from NO_BASIS and INCOMPLETE_CONTEXT, completing a readiness tuple R=(B,C,K) with a truth table mapping B/C/K combinations to NO_BASIS/INCOMPLETE_CONTEXT/CONFLICTED_CONTEXT/READY -- only READY enters ordinary inference." (anchor: "ADR says approved / governance record says rejected / runtime follows neither. That is neither Zero nor incomplete context. It is Conflict. ... R=(B,C,K) ... NO_BASIS / INCOMPLETE_CONTEXT / CONFLICTED_CONTEXT / READY.")
- [S0470] types=[CONCEPT, EXAMPLE] scope=CROSS-OBJECT — "Proposed readiness telemetry (prerequisite_status, context_status, missing_prerequisites, missing_context, inference.executed, action.type) converts a bare UNKNOWN into an 'ActionableUnknown' that an agent can act on directly instead of potentially hallucinating." (anchor: "Unknown -> ActionableUnknown. ... status: CONTEXT_INCOMPLETE, missing: [dependency_graph, governance_owner]. ... An agent receiving UNKNOWN may hallucinate. An agent receiving this structured status can perform a targeted action.")
- [S0470] types=[PRINCIPLE] scope=THEORY-LEVEL — "Reframes the optimization target from LLM speed to LLM necessity; final Epistemic Readiness Principle: KnowledgeOS shall distinguish absence of prerequisites, incomplete contextual understanding, contextual conflict, and genuine unresolved uncertainty, and shall not invoke expensive inference until prerequisites and decision-relevant bounded context are sufficiently established -- also reducing a major hallucination class caused by missing context being mistaken for uncertain knowledge." (anchor: "The kernel should not optimize: 'How can I make the LLM answer faster?' It should optimize: 'How can I determine whether an LLM is needed at all?' ... Epistemic Readiness Principle ... Less unnecessary retrieval + Less unnecessary inference + Less unnecessary LLM usage.")

## Notes for P3
Single-candidate attribution uncertainty was flagged during P2a for this label:
  - [S1472] (batch B0036): The plan demands a definition for a symbol 'Readiness' used in a load-bearing inequality at step 025a-1, unclear whether this is the same object as the epistemic-readiness-principle R=(B,C,K) recorded in the object index or a distinct, metric-scoped notion from the SNF verification track.
