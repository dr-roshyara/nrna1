# evidence-classes-e1-e5

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `E1 Documentary`, `E2 Repository`, `E3 Runtime`, `E4 Experimental`, `E5 Historical` · **Aliases:** `Evidence Mapping Record classes`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope OBJECT): Step 160's five non-interchangeable evidence classes for architecture claims: E1 Documentary (architecture docs/ADRs/specs/governance decisions), E2 Repository (source code/config/schemas/tests), E3 Runtime (logs/executions/infrastructure observations), E4 Experimental (controlled/reproducible tests), E5 Historical (previous states/decisions/evolution records); paired with the invariant Architectural Authority != Implementation Evidence (E1 may be authoritative for intent, but implementation claims generally need E2+E3+E4) and a per-proposition Evidence Mapping Record schema (AM-XXX: Concept/Target/Current/Evidence/Source/Evidence type/Status/Confidence/Gap/Next verification).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1352] §"E1 — Documentary ... E2 — Repository ... E3 — Runtime ... E4 — Experimental ... E5 — Historical. These should never be treated as interchangeable. ... E1 ≠ E2. A document saying that something exists does not prove that the implementation exists."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1352. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows) — this DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S1352 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S1352, S1352 |
| Dependencies | PRESENT | S1352 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1352, S1352 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1352] types=[DEFINITION, DISTINCTION] scope=THEORY-LEVEL — "Five non-interchangeable evidence classes for architecture claims: E1 Documentary, E2 Repository, E3 Runtime, E4 Experimental, E5 Historical; a document (E1) asserting something exists does not prove implementation (E2) exists (E1 != E2). Each proposition gets a canonical Evidence Mapping Record (AM-XXX: Concept, Target statement, Current implementation, Evidence, Source, Evidence type, Status, Confidence, Gap, Next verification), under the rule 'one architectural claim may have many pieces of evidence, but every evidence item must have a traceable origin.'" (anchor: "E1 — Documentary ... E2 — Repository ... E3 — Runtime ... E4 — Experimental ... E5 — Historical. These should never be treated as interchangeable. ... E1 ≠ E2. A document saying that something exists does not prove that the implementation exists.")
- [S1352] types=[PRINCIPLE, DISTINCTION] scope=THEORY-LEVEL — "Evidence-hierarchy rule: implementation claims generally require E2+E3+E4 (repository/runtime/experimental) rather than E1 (documentary) alone; but for architectural intent E1 may itself be authoritative. Yields the distinction Architectural Authority != Implementation Evidence." (anchor: "For implementation claims, we generally want: E2 + E3 + E4 rather than relying solely on: E1. For architectural intent, however: E1 may be authoritative. This gives us an important distinction: Architectural Authority ≠ Implementation Evidence.")

## Notes for P3
Lifecycle (DORMANT) rests on recency heuristics only — no explicit retraction, supersession, or self-contradiction signal was found in this label's own rows. No internal tension noticed across this label's own rows for this batch.
