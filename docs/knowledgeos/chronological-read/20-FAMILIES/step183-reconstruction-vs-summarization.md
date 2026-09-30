# step183-reconstruction-vs-summarization

**Scope(s):** THEORY-LEVEL · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Knowledge_then != Knowledge_now, R(D_t)=f(K_{<=t},E_{<=t},A_{<=t},G_{<=t}), reconstruct as knowable at t, not with hindsight · **Aliases:** reconstruction defined precisely
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 183 defines reconstruction precisely: a future engineer must recover why decision D_t was made without personal memory, Slack archaeology, asking the original architect, undocumented assumptions, or an LLM guessing -- R(D)={Context,Problem,Evidence,Knowledge,Uncertainty,Alternatives,Constraints,Authority,Decision,Authorization,Action,Outcome}, refined with TemporalState into R(D_t)=f(K_<=t,E_<=t,A_<=t,G_<=t). Requires reconstruction to use only what was knowable AT t, never later knowledge (worked example: a 2026 decision using E_2026 must not be reconstructed using a 2027 discovery that an assumption was wrong -- 'the reconstruction must not silently inject the 2027 knowledge. Otherwise we create historical falsification'), requiring KnowledgeOS to support both a historical perspective (what was knowable then) and a current perspective (what we know now), giving Knowledge_then != Knowledge_now. Distinguishes reconstruction from mere summarization: a summary states a flat conclusion; a reconstruction preserves the full evidence/alternative/constraint/authority chain with each element's role explicit -- judged much closer to true organizational knowledge.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1377] §"The future engineer must reconstruct the decision as it was knowable at t. Not with hindsight. ... the reconstruction of the 2026 decision must not silently inject the 2027 knowledge. Otherwise we create historical falsification. ... Knowledge_{then} ≠ Knowledge_{now}."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1377. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1377 |
| type_signature | PRESENT | S1377 |
| invariants | PRESENT | S1377 |
| dependencies | PRESENT | S1377 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1377 |
| examples | PRESENT | S1377 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S1377]` types=[DEFINITION/INVARIANT] scope=THEORY-LEVEL — "Defines reconstruction R(D)={Context,Problem,Evidence,Knowledge,Uncertainty,Alternatives,Constraints,Authority,Decision,Authorization,Action,Outcome}, refined to R(D_t)=f(K_<=t,E_<=t,A_<=t,G_<=t), as recovering why a decision was made without personal memory, Slack archaeology, the original architect, undocumented assumptions, or LLM guessing. Requires reconstruction use only what was knowable AT the decision time t, never later knowledge (worked example: a 2026 decision must not be re-explained using a 2027 discovery) -- 'otherwise we create historical falsification' -- requiring KnowledgeOS to support both a historical perspective and a current perspective, Knowledge_then != Knowledge_now." (anchor: "The future engineer must reconstruct the decision as it was knowable at t. Not with hindsight. ... the reconstruction of the 2026 decision must not silently inject the 2027 knowledge. Otherwise we create historical falsification. ... Knowledge_{then} ≠ Knowledge_{now}.")
- `[S1377]` types=[DISTINCTION/EXAMPLE] scope=THEORY-LEVEL — "Distinguishes reconstruction from mere summarization: a summary collapses to a single flat conclusion; a reconstruction preserves the full evidence/alternative/constraint/authority chain (worked example contrasting a one-line summary with a detailed reconstruction narrative), judged much closer to what organizational knowledge actually means." (anchor: "A summary says: 'The team decided to migrate Nexus because the existing system was outdated.' A reconstruction says: At time t, the system was running version X under constraints Y. Evidence E1 showed A. Evidence E2 indicated B but had lower authority... The second is much closer to what we mean by organizational knowledge.")

## Notes for P3
- Thin evidentiary base (2 row(s) captured) — classification here should be treated as provisional pending further corpus passes.
