# pseudo-evidence-pramanabhasa-detection

**Scope(s):** OBJECT · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Hetvabhasa (as evidence-imitator)`, `Pramanabhasa` · **Aliases:** `evidence-authenticity check`, `hallucination detection`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0006`, scope `OBJECT`: The proposal that KnowledgeOS must distinguish genuine evidence/knowledge-sources from things that only appear to be one (pramanabhasa), mapped explicitly to AI hallucination/false-citation failure modes; recurs in S0223 and S0224.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0223 §"pseudo-evidence as something that appears like evidence but lacks the logical force needed to establish the thesis... Claim: Library X supports feature Y. Evidence: Generated citation. Problem: No real source exists ... H-KOS-EvidenceAuthenticity-001"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0224. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0224 |
| examples | PRESENT | S0223, S0224 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0223] types=['HYPOTHESIS', 'EXAMPLE'] scope=OBJECT — "Names hetvabhasa (pseudo-evidence) as the classification for AI-hallucinated citations (evidence-looking objects that are NOT valid evidence) and proposes H-KOS-EvidenceAuthenticity-001: KnowledgeOS SHALL distinguish evidence from pseudo-evidence by evaluating whether the evidence possesses the logical force required for the conclusion." (anchor: "pseudo-evidence as something that appears like evidence but lacks the logical force needed to establish the thesis... Claim: Library X supports feature Y. Evidence: Generated citation. Problem: No real source exists ... H-KOS-EvidenceAuthenticity-001")
- [S0224] types=['DISTINCTION', 'EXAMPLE'] scope=OBJECT — "Explains pramana vs pramanabhasa (knowledge-source imitator) and maps it directly to hallucinated citations, false confidence, plausible-but-unsupported answers, and outdated assumptions, via a Claim -> Knowledge Source Classification -> {Valid Source | Fake Source} -> Knowledge State pipeline." (anchor: "Pramana (valid knowledge source) vs Pramanabhasa (knowledge-source imitator)... mistakes are not caused by a bad knowledge source; rather, something only appears to be a genuine source.")
- [S0224] types=['HYPOTHESIS'] scope=OBJECT — "Drafts ADR-KOS-EPI-003 requiring KnowledgeOS to distinguish valid evidence mechanisms from evidence-like artifacts." (anchor: "ADR-KOS-EPI-003 Knowledge Source vs Knowledge Imitator: KnowledgeOS SHALL distinguish valid evidence mechanisms from evidence-like artifacts.")

## Notes for P3
- Thin evidence base (n=3 rows) — treat conclusions here as provisional.
- Completeness is sparse even relative to its row count (only 2/12 dimensions PRESENT) — most of this object's shape is NOT-EVIDENCED-IN-CAPTURE.
