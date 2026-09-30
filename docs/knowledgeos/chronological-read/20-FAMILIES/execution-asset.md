# execution-asset

**Scope(s):** OBJECT · **Row count:** 7 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `AST-nnn`; `Execution Asset`; `Runtime Asset` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G1103: `capability` · `execution-asset` — labels co-occur in the same contribution's labels[] 5 separate times across the corpus. Relationship not yet decided (P3); the two labels are heavily co-discussed (5 of this label's 7 rows also carry `capability`) but remain distinct per this batch's non-identity rule.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0001, scope OBJECT: "A registered runtime artifact (prompt, hook, instruction document) with a runtime moment and five-question lineage; resolves R-1's 'missing AI-execution layer' proposal."

No `single_candidate_flags` recorded.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0008 §"R-1 resolved — the \"missing AI-execution layer\" ALREADY EXISTS ... VERDICT: the concept exists and is GOVERNED — it is the Platform Registry's asset model"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0008 §"The corrected canonical chain: MISSION (enacted) → STRATEGY → PRINCIPLE → DESIGN POLICY → CAPABILITY ..."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0031. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is entirely empty (no retraction, no supersession, no self-contradiction flag). This DORMANT classification is a heuristic based on recency of last use (by source_id) — all 7 rows date to the same short window (2026-08-02/03), so "dormant" likely reflects that this concept was settled quickly (confirmed as an existing, already-governed construct) rather than abandoned.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0026 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0008 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0008, S0009, S0026 (x2), S0031 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0008, S0026 |

## Rationale
The one `rationale_evidence` entry (ANALYSIS) explains the ontological placement of Execution Assets: "Exactly one archetype has execution as its essence: CAPABILITY (I-11 verbatim: 'Knowledge does not execute. Capabilities execute.'); RUNTIME ASSETS execute at the boundary (AST-nnn); an executable ARTIFACT... is executable only as a representation-dimension property, not an archetype property — executability does not change its kind." [S0026]. This closes the gap of why Execution Assets are a distinct kind from both Capability (which owns "execution as essence") and ordinary executable Artifacts (whose executability is incidental, not archetypal). `rationale_truncated_count` is 0, so no further rationale rows exist beyond this one.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S0008] types=[CORRECTION, VALIDATION] scope=CROSS-OBJECT, 2026-08-03 — "The reviewer's proposed new concept, 'AI Execution Assets'... is checked before admitting: AST-011 is a registered prompt template...; a closed runtime_moment_enum exists (SESSION_START, PRE_ACTION, POST_ARTIFACT, SESSION_END, ON_DEMAND)...; R-42 scopes the registry to platform assets only. Verdict: the concept already exists and is governed as the Platform Registry's AST asset model — a prompt is an AST with a runtime moment and five-question lineage. What is actually missing is one LINK, not one layer... R-1 downgrades from 'missing semantic layer' to 'unlinked existing layer', recorded as SC-6... occurrence #12 of model amnesia avoided by checking." (anchor: "R-1 resolved — the \"missing AI-execution layer\" ALREADY EXISTS ... VERDICT: the concept exists and is GOVERNED — it is the Platform Registry's asset model") — `missing`: "capability-to-AST link (SC-6)"; `lineage_claims`: SOURCE-CLAIMED-REDEFINITION of R-1. Also carries label `capability`.
- [S0008] types=[FORMALIZATION, CORRECTION] scope=THEORY-LEVEL, 2026-08-03 — "A corrected canonical chain replaces the reviewer's proposed one: MISSION(enacted) -> STRATEGY -> PRINCIPLE -> DESIGN POLICY -> CAPABILITY; ...CAPABILITY -realized as-> script, -runtime-faced by-> EXECUTION ASSET(AST) -> RUNTIME ADAPTER; WORK -produces-> EVIDENCE RECORDS -harvested by-> EVIDENCE PROTOCOL -(n≈3)-> PLATFORM." (anchor: "The corrected canonical chain: MISSION (enacted) → STRATEGY → PRINCIPLE → DESIGN POLICY → CAPABILITY ...") — also carries labels `knowledge-flow`, `capability`.
- [S0008] types=[OPEN-QUESTION] scope=CROSS-OBJECT, 2026-08-03 — "SC-6 (new): the capability model and Execution Assets remain unlinked — R-1's true residue, a new ARB linkage question." (anchor: "SC-6 | capability model ↔ Execution Assets unlinked (R-1's true residue) — new — ARB linkage question") — also carries label `capability`.
- [S0009] types=[VALIDATION] scope=OBJECT, 2026-08-03 — "Candidate F, Runtime Assets, is confirmed as a kind (HIGH) and is the only candidate already first-class and governed: AST-nnn, runtime_moment_enum, five-question lineage, R-42's boundary ruling." (anchor: "F · Runtime Assets | CONFIRMED as a KIND — the only candidate already executable") — `dependencies`: R-42.
- [S0026] types=[ANALYSIS] scope=THEORY-LEVEL, 2026-08-03 — see Rationale section above (full statement). — `dependencies`: I-11. Also carries label `capability`.
- [S0026] types=[OPEN-QUESTION] scope=OBJECT, 2026-08-03 — "U-ONT-3: the CAP<->AST link (SC-6), externally standard and internally undeclared — declaring the link is governance work already on the record." (anchor: "U-ONT-3 | The CAP↔AST link (SC-6) — realization's inner edge is externally standard and internally undeclared") — `dependencies`: SC-6. Also carries label `capability`.
- [S0031] types=[VALIDATION] scope=OBJECT, 2026-08-02 — "registry.yaml already assigns stable identifiers (CMP-nnn for components, AST-nnn for assets) so ADRs and reviews reference ids, not paths, meaning references survive relocation — flagged as the single most important extraction-safety property, already built. R-42 already scopes the registry to platform assets only... The reserved namespace registry/... has its trigger already recorded: 'when a second runtime adapter exists'." (anchor: "...references survive relocation — the single most important extraction-safety property, already built") — `dependencies`: R-42.

## Notes for P3
Strong, coherent evidentiary base: 7 rows across 3 source documents (S0008, S0009, S0026, S0031 — 4 distinct documents), all from the same short window (2026-08-02/03), converging on one settled verdict (Execution Asset / AST-nnn is a confirmed, already-governed kind, resolving reviewer proposal R-1) plus one still-open governance linkage question (SC-6: capability-model <-> AST link, raised identically in rows 1, 3, and 6). P3 should note SC-6 recurs three times verbatim as an open item — worth checking whether it was later closed elsewhere in the corpus. The `files_touching` list includes S0010 and S0234, neither of which appears among this label's 7 rows — possible under-capture worth flagging to P2a maintainers. The G1103 co-occurrence with `capability` (5 of 7 rows) reflects that Execution Asset was defined largely in contradistinction to Capability ("Knowledge does not execute. Capabilities execute." vs. "RUNTIME ASSETS execute at the boundary") — a real conceptual pairing, but per this batch's rule no identity is asserted.
