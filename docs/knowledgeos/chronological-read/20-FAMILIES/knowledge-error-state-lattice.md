# knowledge-error-state-lattice

**Scope(s):** OBJECT · **Row count:** 5 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `VALID/UNKNOWN/CONFLICTED/SUPERSEDED/INVALID/REFUTED` · **Aliases:** `KnowledgeState enum`
**Candidate group membership (NOT an identity claim):**
- **G1160** [`hetvabhasa-fallacy-invariants` · `knowledge-error-state-lattice`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0006, scope OBJECT): A recurring (S0226, S0227, S0228, S0230) proposed multi-valued knowledge-state enum replacing binary true/false, explicitly preserving conflicted/superseded/erroneous states rather than deleting or collapsing them.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0226] §"Equally supported contradictory inferences SHALL remain unresolved until additional evidence changes the epistemic state."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0230] §"How do we know that we know?"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0230. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S0226 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S0227, S0228, S0230 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S0226, S0227, S0228 |
| Dependencies | PRESENT | S0227, S0228 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| Examples | PRESENT | S0228, S0230 |
| Warnings | PRESENT | S0226 |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Satpratipaksa (competing reasoning: two equally valid-looking arguments) is warned to be a case current AI systems fail at, because they resolve to 'highest probability answer' instead of surfacing conflict. Example: 'Technology X is secure' with Evidence A (audit passed) vs Evidence B (critical vulnerability discovered) should yield KnowledgeState=CONFLICTED, not KnowledgeState=FALSE. Derived invariant H-KOS-Competing-Reasoning-001 [S0226]. Badhita (overridden by stronger evidence; Nyaya example 'Fire is cold because it is a substance', defeated by direct perception) is called 'perhaps the most important for temporal knowledge', mapped to: old knowledge 'Version 1.0 is secure' overridden by new evidence 'Critical CVE discovered'; the old statement is not deleted but transitions Knowledge State VALID -> SUPERSEDED_BY_EVIDENCE. Derived invariant H-KOS-Evidence-Override-001 [S0226].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0226] types=[ANALYSIS, INVARIANT, WARNING] scope=THEORY-LEVEL — "Satpratipaksa (competing reasoning: two equally valid-looking arguments) is warned to be a case current AI systems fail at, because they resolve to 'highest probability answer' instead of surfacing conflict. Example: 'Technology X is secure' with Evidence A (audit passed) vs Evidence B (critical vulnerability discovered) should yield KnowledgeState=CONFLICTED, not KnowledgeState=FALSE. Derived invariant H-KOS-Competing-Reasoning-001." (anchor: "Equally supported contradictory inferences SHALL remain unresolved until additional evidence changes the epistemic state.")
- [S0226] types=[ANALYSIS, INVARIANT] scope=THEORY-LEVEL — "Badhita (overridden by stronger evidence; Nyaya example 'Fire is cold because it is a substance', defeated by direct perception) is called 'perhaps the most important for temporal knowledge', mapped to: old knowledge 'Version 1.0 is secure' overridden by new evidence 'Critical CVE discovered'; the old statement is not deleted but transitions Knowledge State VALID -> SUPERSEDED_BY_EVIDENCE. Derived invariant H-KOS-Evidence-Override-001." (anchor: "Higher authority evidence may invalidate a conclusion while preserving historical lineage.")
- [S0227] types=[DEFINITION, INVARIANT] scope=OBJECT — "From Tarka-Sangraha's dedicated section on Mithyajnana (erroneous apprehension), proposes an Answer + confidence + error possibility + contradiction state model, and a Knowledge State enum (VALID/UNKNOWN/CONFLICTED/SUPERSEDED/INVALID) replacing a bare Answer. Derived invariant H-KOS-ErrorState-001, explicitly connected to 'contradiction tolerance, fallibilism, temporal revision' from the author's own prior work (unnamed within this file)." (anchor: "Incorrect cognition SHALL be represented explicitly, not deleted.")
- [S0228] types=[DEFINITION, INVARIANT, EXAMPLE] scope=OBJECT — "From Keith's 'Knowledge and Error' / 'Logical Errors' chapters, proposes a five-valued state (VALID/UNKNOWN/CONFLICTED/SUPERSEDED/REFUTED) replacing binary true/false; worked example: two conflicting observations of PostgreSQL version (15 vs 14) should not have one deleted but represented as 'Conflict detected / Resolution pending'. Derived invariant KOS-ERROR-001." (anchor: "Contradictions are knowledge objects, not failures.")
- [S0230] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Poses the Yoga Sutra's epistemological question and derives three knowledge states: Valid Knowledge (Evidence->Observation->Validated Knowledge, e.g. test passed/production metric confirms/architecture rule satisfied), False Knowledge (Assumption->Belief->Wrong decision, worked example: 'We think this service owns customer identity' but another system actually owns it), and Uncertain Knowledge (Hypothesis->Investigation->Evidence required); formalized as a KnowledgeClaim schema (statement, type[observed/inferred/assumed], confidence, evidence, owner, validation_status)." (anchor: "How do we know that we know?")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
