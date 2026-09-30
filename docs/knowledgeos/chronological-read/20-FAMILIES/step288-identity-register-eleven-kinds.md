# step288-identity-register-eleven-kinds

**Scope(s):** THEORY-LEVEL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `11 identity kinds x 12 questions` · **Aliases:** `Step 288 identity register`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0052, scope THEORY-LEVEL): Step 288's identity register: eleven identity kinds (Entity, Assertion, State/K_t, History, Event, Operation, Authority-act, Policy, Provenance, Record, Knowledge-artifact) scored against twelve mandate questions (stability, immutability, version-in-identity, scoping, survival under merge/transform/retraction, semantic-vs-referential, collision model, canonicalization, decision procedure). Finds only 2 of 11 established (Assertion, with a live id=H(...) contradiction; Provenance) and 5 of 11 wholly absent (State, Event, Operation, Authority-act, Policy); classifies id=H(P,e,c,t,Pi) as an internally inconsistent hash-based implementation suggestion, not a definition, contradicting corpus principles 25I.29-30.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2140] §"**Score: 2 of 11 established (Assertion — with a live contradiction; Provenance). 5 of 11 wholly absent.**"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2140. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2140 |
| informal_meaning | PRESENT | S2140 |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S2140 |
| invariants | PRESENT | S2140 |
| dependencies | PRESENT | S2140 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2140 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Scores eleven identity kinds (Entity, Assertion, State/K_t, History, Event, Operation, Authority-act, Policy, Provenance, Record, Knowledge-artifact) against twelve mandate questions (what it identifies, stability, immutability, version-in-identity, scoping, survival under merge/transform/retraction, semantic-vs-referential nature, collision model, canonicalization, decision procedure): only 2 of 11 are established (Assertion, via id=H(P,e,c,t,Pi), though carrying a live contradiction; and Provenance, via Pi, safe from t=0), while State (K_t), Event, Operation, Authority-act, and Policy are wholly absent on every column (5 of 11 with no identity treatment at all) [S2140]. Directly answers the mandate's question about id=H(P,e,c,t,Pi): it is a hash-based implementation suggestion that is internally inconsistent, not a definition -- supported by three lines of evidence: (1) executed proof that mutable e.state changes the hash on withdrawal, dangling every relational edge pointing at the old id, violating StructuralValid's no-dangling clause; (2) executed proof that H(x)=H(y) is meaningful only relative to a canonicalization; (3) the corpus itself ruled against exactly this construction before it existed (25I.29: identity cannot simply be Hash(currentRepresentation); 25I.30: KnowledgeIdentity is persistent, KnowledgeState evolves; 258.2: identity persistence != value equality; 258.18: identity alone cannot define Knowledge-State semantics; 38.26: a unique identifier is strong evidence, not metaphysical truth). Classified as a CORPUS-PRINCIPLE-vs-LATER-CONSTRUCTION CONFLICT, a governance matter, not an engineering fix; a candidate repair (projecting mutable state out of id) is recorded but explicitly not adopted or recommended [S2140].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2140] types=[ANALYSIS, EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Scores eleven identity kinds (Entity, Assertion, State/K_t, History, Event, Operation, Authority-act, Policy, Provenance, Record, Knowledge-artifact) against twelve mandate questions (what it identifies, stability, immutability, version-in-identity, scoping, survival under merge/transform/retraction, semantic-vs-referential nature, collision model, canonicalization, decision procedure): only 2 of 11 are established (Assertion, via id=H(P,e,c,t,Pi), though carrying a live contradiction; and Provenance, via Pi, safe from t=0), while State (K_t), Event, Operation, Authority-act, and Policy are wholly absent on every column (5 of 11 with no identity treatment at all)." (anchor: "**Score: 2 of 11 established (Assertion — with a live contradiction; Provenance). 5 of 11 wholly absent.**")
- [S2140] types=[ARGUMENT, CORRECTION] scope=OBJECT — "Directly answers the mandate's question about id=H(P,e,c,t,Pi): it is a hash-based implementation suggestion that is internally inconsistent, not a definition -- supported by three lines of evidence: (1) executed proof that mutable e.state changes the hash on withdrawal, dangling every relational edge pointing at the old id, violating StructuralValid's no-dangling clause; (2) executed proof that H(x)=H(y) is meaningful only relative to a canonicalization; (3) the corpus itself ruled against exactly this construction before it existed (25I.29: identity cannot simply be Hash(currentRepresentation); 25I.30: KnowledgeIdentity is persistent, KnowledgeState evolves; 258.2: identity persistence != value equality; 258.18: identity alone cannot define Knowledge-State semantics; 38.26: a unique identifier is strong evidence, not metaphysical truth). Classified as a CORPUS-PRINCIPLE-vs-LATER-CONSTRUCTION CONFLICT, a governance matter, not an engineering fix; a candidate repair (projecting mutable state out of id) is recorded but explicitly not adopted or recommended." (anchor: "$$\boxed{\textbf{A hash-based IMPLEMENTATION SUGGESTION that is internally inconsistent — not a definition.}}$$")
- [S2140] types=[DEFINITION, LIMITATION] scope=OBJECT — "Recovers a corpus criterion for when identity operationally matters: OperationalIdentity(x) iff there exists a mandatory transformation T in the (unenumerated) transformation set 𝒯 such that changing id(x) can change T(K,c) (258.20), generalized to any property p via Relevant(p) iff some mandatory T distinguishes states differing only in p (258.21). Both definitions quantify over 𝒯, which is unenumerated across the whole programme, so the criterion is judged correct in form but currently inapplicable in practice." (anchor: "$$OperationalIdentity(x) \iff \exists T \in \mathcal T:\ \text{changing } id(x) \text{ can change } T(K,c) \qquad (\texttt{258.20})$$ ... ⚠️ **Both quantify over `𝒯`, which is unenumerated. The criterion is correct and inapplicable.**")

## Notes for P3
Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.
