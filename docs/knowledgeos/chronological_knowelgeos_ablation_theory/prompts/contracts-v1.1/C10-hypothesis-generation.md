# C10 — Hypothesis generation (Level 3) · v1.1

**Treatment:** ADAPTED from P3B v1.7 §13 (layer-C kinds, core schema, §13.10 disconfirmation discipline, §13.10a
pre-registration), per protocol §F-3.2 rows 34–35. **Code:** `f_checks.check_research`. **Gate:** `RESEARCHED`.

## 1. What may be generated
Kinds (closed): `SUGGESTION-RESEARCH` · `SUGGESTION-METHOD` · `HYPOTHESIS` · `STRUCTURE-CANDIDATE` (a proposed
structure *for KnowledgeOS*). Level-2 kinds are refused here (they belong in `ANALYSIS.jsonl`). `THEORY-CANDIDATE` is an
epistemic class allowed only at scale CORPUS, i.e. at a checkpoint, never from one file.

## 2. Record — `research.jsonl`
P3B §13.6 core fields (`rs_id FRS-F####-NNN`, kind, `level: 3`, topics, lens, scale, statement, epistemic_class,
`output_layer: "C"`, supporting_evidence[{f_id, page, quote, evidence_kind}], historical_anchor, research_time, run_id,
contract_sha256, model_id, related_f_ids, lifecycle_stage, author_role) plus `analysis_refs[]` (the Level-2 records
it rests on) and, for HYPOTHESIS / STRUCTURE-CANDIDATE:
- `falsification_condition`, `validation_question`, `competing_hypotheses[]`;
- `contradicting_evidence[]` + `disconfirmation_search` (population, method, completeness, bound, result, negative
  label — P3B §13.10 A–D, as far as they can be performed within this file and earlier AUDITED files);
- **`preregistration`** — frozen at birth:
```json
{"population_rule":"which F-IDs the test will use (e.g. all AUDITED text F-IDs at CP-01, duplicates collapsed)",
 "temporal_scope":"list-order window","selection_rule":"…","comparison_rule":"control or competing hypothesis",
 "stopping_rule":"…","prediction":"what SUPPORTED would look like","registered_after_f":"F####",
 "outcome_vocabulary":["SUPPORTED","UNSUPPORTED","UNDETERMINED"]}
```

## 3. Rules
No outcome is written per file. A hypothesis is never edited after registration; a changed hypothesis is a new record
citing the old one (P3B §12.3 append-only). Hypotheses are about what the F-corpus supports — never "KnowledgeOS is …".
