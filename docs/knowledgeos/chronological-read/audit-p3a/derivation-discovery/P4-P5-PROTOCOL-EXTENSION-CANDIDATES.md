# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# P4/P5 Protocol Extension Candidates

**Purpose:** determine which of the 12 proposed evaluation dimensions are
already covered, partially covered, or entirely missing from Master Protocol
v3.5's existing schema (§13). **Date:** 2026-09-21. **Status:** EXPERIMENTAL —
none of these are adopted; each is a `PROTOCOL-EXTENSION-CANDIDATE` for a
future, separate governance decision. **Authoritative:** NO.

| Dimension | Already covered by | Coverage | Belongs to |
|---|---|---|---|
| **1. Correctness** | `mathematical_status` (CONSISTENT/INCONSISTENT/UNDER-SPECIFIED/UNDECIDABLE-FROM-CORPUS/NOT-APPLICABLE) | **Partial** — covers internal consistency, not "is the math/logic actually valid" in a proof-checked sense | P5 (validation vector: `proof`) |
| **2. Completeness** | P2b's 12-dimension `completeness` roll-up (PRESENT/PARTIAL/NOT-EVIDENCED-IN-CAPTURE) | **Covered**, but at the label level, not the per-derivation level this task needs (does THIS specific formulation cover the intended concept fully, vs. does the LABEL have a definition at all) | Extend P3b's per-candidate record (Preservation Spec), not a new P5 field |
| **3. Consistency** (with other established concepts) | `type_compatibility` (Q2) covers pairwise formal compatibility; nothing covers "is this consistent with the WIDER established theory" | **Partial** | P5 (`independent_validation`) + P4 (membership must check this) |
| **4. Generality** | Nothing | **MISSING** | `PROTOCOL-EXTENSION-CANDIDATE`: a new P5 or P4 field, e.g. `generality_evidence` |
| **5. Parsimony** | Nothing | **MISSING** | `PROTOCOL-EXTENSION-CANDIDATE` — likely P4 (membership/preference), since parsimony is a selection criterion, not a validity fact |
| **6. Derivational transparency** | Nothing directly; `rationale_evidence` (P2b) captures WHY a concept was introduced, not whether its derivation is reproducible | **Partial, indirect** | `PROTOCOL-EXTENSION-CANDIDATE` for P5 |
| **7. Dependency quality** | Nothing — this is exactly the gap the Dependency Model document addresses | **MISSING**, now specified (not adopted) in this session's own Dependency Model document | `PROTOCOL-EXTENSION-CANDIDATE` for P3b-extension (discovery) + P5 (quality judgment) |
| **8. Robustness** | Nothing — `dependency_overlap`/common-mode detection (this session's new proposal) is a prerequisite for ever assessing this | **MISSING** | P5, once dependency quality (item 7) exists |
| **9. Computability** | The existing `validation_status` vector already has an `implementation` and `test` means; not yet connected to any candidate-derivation record | **Partial** (the vector exists; nothing populates it for a specific derivation candidate yet) | P5, using the already-defined vector |
| **10. DDD/domain coherence** | `primary_layer`/`secondary_roles` (P2b/P3b) address architectural placement; not a coherence CHECK | **Partial** | P4 (membership should require this) |
| **11. Empirical support** | The existing `validation` vector's `experiment`/`simulation` means already exist structurally | **Covered in principle, unpopulated in practice** — the Discovery Audit found real experimental evidence (e.g. F8's falsification test) that could already populate this vector, but no candidate-level record currently exists to attach it to | P5, once the Preserved-Alternatives Schema exists to hold it |
| **12. Falsifiability** | Nothing | **MISSING** | `PROTOCOL-EXTENSION-CANDIDATE` for P5 — though the Discovery Audit found genuine corpus practice of exactly this (F8's own explicit falsification test design) even without a named field for it |

## Summary

Of 12 proposed dimensions: **3 are already reasonably covered** by existing
fields (2. Completeness, 9. Computability's vector, 11. Empirical support's
vector — the last two structurally present but currently unpopulated for any
individual derivation candidate); **4 are partially covered** (1, 3, 6, 10);
**5 are entirely missing** (4. Generality, 5. Parsimony, 7. Dependency
quality, 8. Robustness, 12. Falsifiability).

**None of these gaps are filled by this session's work.** Per §13's explicit
instruction, they are named here as candidates for a future governance
decision, not silently added to any P3/P5 schema. The one exception already
built and disclosed elsewhere in this session: the Dependency Model document
(item 7) proposes a concrete representation, but that document itself states
it is a specification, not an adopted schema change.

## Recommendation for sequencing (not a decision — a suggestion for whoever
makes the P4/P5 governance call)

Items 2, 9, and 11 require no new field, only new *usage* of fields that
already exist — the lowest-cost, most protocol-conformant place to start, if
and when a validation phase is authorized. Items 4, 5, 7, 8, 12 require actual
new protocol text and are correctly the larger, separate decision this
document flags rather than resolves.
