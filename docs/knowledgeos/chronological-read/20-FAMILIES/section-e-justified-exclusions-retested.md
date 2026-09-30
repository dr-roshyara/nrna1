# section-e-justified-exclusions-retested

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "EXCLUDED (8 constructs)"
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0067, scope OBJECT: "Re-test showing a prior 'Section E' claim of eight justified kernel-exclusions holds only relative to the stipulated K4 basis, with some constructs becoming required under the derived K9 basis."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2794 §"Third robustness axis: the source program's OWN section-F sensitivity, crossed with basis (K_4 vs K_9) and with the C-row mapping. Also tests section E's claim that eight constructs are JUSTIFIED EXCLUSIONS from any kernel."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S2794 §"Third robustness axis: the source program's OWN section-F sensitivity, crossed with basis (K_4 vs K_9) and with the C-row mapping. Also tests section E's claim that eight constructs are JUSTIFIED EXCLUSIONS from any kernel."] (same anchor as lexical birth)
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S2794 (single row/single source). Candidate lifecycle: ACTIVE.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). ACTIVE is a recency heuristic (this row is from a later batch, B0067), not confirmation of adoption.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S2794 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2794 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty. `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S2794] types=[EXPERIMENT, COUNTEREXAMPLE] scope=OBJECT, path `docs/knowledgeos/reviews/exec/k9_triple.py` — "Header and Section-E re-test: re-tests a prior claim (from the source program's 'Section E') that eight named constructs (Measurement, Missingness, Determination, Q_t, Assessment, Qualification, Gamma, Authorization) are 'justified exclusions' from any kernel, by checking whether each is excluded from the closure under both K4 and the newly-derived K9 basis." (anchor: "Third robustness axis: the source program's OWN section-F sensitivity, crossed with basis (K_4 vs K_9) and with the C-row mapping. Also tests section E's claim that eight constructs are JUSTIFIED EXCLUSIONS from any kernel.")

  Embedded experiment record: hypothesis — "Section E's eight 'justified exclusions' are exclusions relative to the stipulated K4 basis only, not absolute exclusions from any kernel"; setup — the reused graph G ("epistemic" variant) and a modified "structural-only" variant G_str differing only in the InvariantReg edge, plus K4 and K9 seed sets; method — for each of the 8 excluded constructs, check membership in Closure(K4) and Closure(K9), counting flips from excluded-under-K4 to required-under-K9; result — "Some number of the 8 constructs flip from excluded to required when moving from K4 to the derived K9 basis"; interpretation — "The exclusions were justified relative to the stipulated K4 basis, not absolutely — a basis-relative claim was being read as an absolute one"; limitations — "Depends on the correctness of the K9 mapping and the reused graph"; conclusion — "Section E's exclusion claim requires re-qualification as basis-relative rather than universal."

## Notes for P3

- This is a self-correcting result: it retests and narrows an earlier absolute-sounding claim ("eight justified exclusions from any kernel") into a basis-relative one (justified only under K4, not under the newly derived K9). P3 should treat the original "Section E" claim (source not captured directly in this label's own rows — only referenced) as superseded/qualified by this row, and may want to locate and cross-reference the original Section-E source document.
- The row's `path` points to a Python script (`docs/knowledgeos/reviews/exec/k9_triple.py`), not a markdown document — unusual among this batch's mostly-markdown sources; P3 may want to verify this executable-artifact provenance is handled consistently with the ledger's general documentation-first evidentiary model.
