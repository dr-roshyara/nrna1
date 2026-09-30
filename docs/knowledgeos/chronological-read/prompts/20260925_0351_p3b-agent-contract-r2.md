# P3b AGENT CONTRACT — S5 production batches (run id `<batch_id>-R2`)

**Governing documents:** frozen P3b core **v1.7** (`prompts/20260924_2311_p3b-phase1-continuation-protocol-v1.7.md`, sha256 `38021aa4328c1fae23edf2eeab068d1a8d446d6197aac6567681a72687502d12`), H-19 addendum v1.5 (hold-out
sealed as HS-3d32dd44d162), approved S5 plan v2.3.2 (`audit-p3b/20260925_0106_s5-plan-v2.3.md`, sha256 `f89a16824efaeea08d54fbc857b4cf36184cb698deb482550026fe194b3c7de1`), governance log up to G-LOG-0041. The
sections under "PROTOCOL TEXT" are copied verbatim from v1.7, and the core governs. Sections D (closed values) and E
(exact output schema) are binding. This preamble states how S5 applies the core.

## A. Inputs, reading, isolation

1. **Inputs:** this contract and your batch's slice files (`docs/knowledgeos/chronological-read/_batch_input_r2/s5/<batch_id>/<working_label>.json`). A slice holds
   the bundle, `family_md`, the stage-1 search records, `stage2_files`, the P3a pair records, `source_tracks` (with the
   OMQ-07 mapping), `source_meta` (02-FILES provenance and dates), `in_checklist`, **`hub` and `hub_record`**,
   `semantic_status_mechanical` (§12.2.4 rows 0/3/4, computed by script) and `quarantine` (counts of input records
   withheld under addendum §6; a withheld record is not evidence of absence and is never looked up). The discovery-filtered orchestrator flags
   extract is `docs/knowledgeos/chronological-read/_batch_input_r2/s5/09-ORCHESTRATOR-FLAGS.discovery.md` (known corpus failure modes; §9.2). Nothing else is input.
2. **Corpus text:** `python3 docs/knowledgeos/chronological-read/scripts/p3b_read_source.py --run <batch_id>-R2 --batch
   <batch_id> --label <working_label> --step <1|7|10> S#### [...]`. For a hit term of ≤ 2 code points (Stage 2A):
   `python3 docs/knowledgeos/chronological-read/scripts/p3b_stage2a_scan.py --run <batch_id>-R2 --batch <batch_id>
   --label <working_label> --step 7 --term <term> S#### [...]`. Both log themselves and refuse sealed files. For an S-id
   without `source_meta`, record its provenance as unknown; never look it up.
3. **Never** open, list or search any other file or directory: not other batches' slices, ledgers, logs, governance
   files, S3 outputs, the seal, the hold-out, or another agent's scratch.
4. **Scratch:** only `/tmp/p3b-s5-scratch/<batch_id>/`.
5. **No helper agents, forks or sub-agents.**
6. **Reading scope (OMQ-14):** rows in historical order; whole-file reading of the sources carrying a birth, a change of
   definition, type or meaning, or a contradiction (step 1). **Stage 2 covers every file in `stage2_files`** (whole-file
   reading, or Stage 2A/2B for terms of ≤ 2 code points). A FOUND always needs a whole-file reading with the reader. The
   absence procedure is never thinned (§19.4).
7. **Hub labels (`"hub": true`; v1.7 §11.4):** stage 2 — including Stage 2A and 2B — is **not performed**. Every absence
   dimension whose stage-1 result has hits resolves `ESCALATED`, with an `escalations` entry `{field:
   "absences.<dimension>", reason: "LOAD", hub_record: <the slice's hub_record>}`. Never FOUND, never
   GENUINELY-UNDEFINED-AFTER-CENSUS for such a dimension. `stage2_dispositions` stays empty. A NEGATIVE-CENSUS dimension
   resolves as for any label. Material found at step 1 for a hit-bearing dimension goes into that escalation's
   `detail` and a P1-gap record, never FOUND. **No step-10 read of a hub's stage-1 hit file** unless OMQ-14 already
   requires that file at step 1. A GAP record on such a dimension uses `gap_status: "NOT-FOUND-LOAD-ESCALATED"`.
8. **Research reads (OMQ-16):** step-10 reads are **label-local** (only files in your label's slice). The batch's total
   step-10 bytes are **capped at 900,000**; a re-read counts again. When the cap is reached, stop step-10 reading;
   records keep their reached stage, and the exhaustion is recorded in the **batch report** by the orchestrator (no
   register value is introduced). No record quota and no minimum (D-38).
9. **Search-record format:** as in the S4 contracts (`LABEL-HITS` with `ledger_hits` / `raw_hits`; one `DIMENSION`
   record per absence dimension; `negative_label` is `NEGATIVE-CENSUS` only when there is no hit at all).

## B. Decisions in force (G-LOG-0027, G-LOG-0039, G-LOG-0041)

- **Population basis:** `DISCOVERY-POPULATION (HS-3d32dd44d162)` on every search record, absence and negative label.
- **semantic_status (§12.2.4, H-11a–d decided):** Tier Z → `null`, note `"NO-PAIR-EVIDENCE — out of domain (H-11a option iii)"`, rule `ROW-0`. Tier U: apply rows
  1 (D4 contradiction evidence → `CONTESTED`) and 2 (D5 positive two-concept evidence on the label → `HOMONYM-SPLIT`;
  H-11b (ii): a HOMONYM pair verdict alone is **not** HOMONYM-SPLIT) from the label's rows, with the evidence in
  `semantic_evidence`; otherwise take `semantic_status_mechanical` from the slice (rows 3/4: H-11c (i) every consumed
  pair positive, H-11d (i) positive bases CORROBORATED and INFERRED). Name the rule in `semantic_status_rule`.
- **No STATUS, no TESTED in batches:** research records reach at most `TEST-DEFINED` (TESTED and STATUS happen in the
  separate test pass, plan §C.2).
- **Rulings F1–F3 (G-LOG-0027):** F1 a NOT-CONFIRMED timeline point keeps its order value with
  `date_applies_to_file: NOT-CONFIRMED`; F2 a hit whose only relation to the label is naming a file of the object
  (index row, listing, file map, path) is `FALSE-HIT`; F3 every Stage 2B disposition lists the Stage 2A `offsets` it
  covers, each logged occurrence exactly once.
- **v1.6.6/v1.7 rules** (protocol text below): UNSUPPLIED-DIMENSION; census–reading disagreement → ESCALATED;
  BIRTH-UNRESOLVED-MTIME-ONLY; Stage 2A/2B; the hub exception.
- **G-08:** SECONDARY-SYNTHESIS, PROVENANCE-UNRESOLVED or unknown-provenance files never alone establish a birth, a FOUND
  or a CONTESTED status; `ESCALATED[G-08: …]` as in S4 (G-LOG-0023).
- **OMQ-07 (mapping (2)):** `TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED` counts as **Track B** for every D-23 purpose (see
  `source_tracks.<S>.d23_track`); the tag stays distinct in `track_composition`.
- **A field the closed values cannot represent:** null, an `escalations` entry (reason SCHEMA-LIMITATION) and a
  SCHEMA-LIMITATION register record. Never invent a value.
- **Layer A stays free of layer B/C** (G-12). **Evidence presentation** `V1-PLUS-ROWS`.
- **Checklist (§9C):** full 23 questions and `checklist_examined` only if `"in_checklist": true`.
- **Model:** record your exact served model id string and generation parameters on every record (the model is
  claude-opus-5-5; its context-window variants are the same model).

## C. Output files (canonical JSON lines: sorted keys, compact separators, UTF-8)

- `docs/knowledgeos/chronological-read/ledger-p3b-r2/<batch_id>-R2/objects.jsonl` — one object record per label;
- `.../<batch_id>-R2/register.jsonl` — research records; `.../<batch_id>-R2/p1-gap-capture.jsonl` — P1-gap records.

Write nothing else. Every S-id you cite must be in your slice or in a non-refused read-log entry of your batch. Cite
each S-id individually: **never write an S-id range** (such as `S0101–S0120` or `S0101..S0120`); a range is read as
citing every S-id in its interval and fails the batch's quarantine check (G-LOG-0045 item 3). Every
record carries `run_id`, `batch_id`, `contract_sha256` and `model_id`.

## D. Closed values (binding)

```json
{
 "type_status": [
  "CLOSED",
  "INCOMPLETE",
  "UNTYPED",
  null
 ],
 "mathematical_status": [
  "CONSISTENT",
  "INCONSISTENT",
  "UNDER-SPECIFIED",
  "UNDECIDABLE-FROM-CORPUS",
  "NOT-APPLICABLE",
  null
 ],
 "primary_layer": [
  "FOUNDATIONAL",
  "DERIVED",
  "OPERATIONAL",
  "META-THEORETICAL",
  "LAYER-UNRESOLVED"
 ],
 "secondary_roles[]": [
  "FOUNDATIONAL",
  "DERIVED",
  "OPERATIONAL",
  "META-THEORETICAL"
 ],
 "dependency_edges[].kind": [
  "DEFINITIONAL",
  "DERIVATIONAL",
  "USAGE",
  "EXPLANATORY",
  "VALIDATION",
  "GOVERNANCE"
 ],
 "births.<kind>": [
  "ESTABLISHED-<KIND>-BIRTH[S####]",
  "MOVED[S####, quote]",
  "UNORDERED-BLOCK[BULK-nn]",
  "BIRTH-UNRESOLVED-MTIME-ONLY[S####]",
  "ESCALATED[TIMESTAMP-ANOMALY: reason]",
  "ESCALATED[G-08: sole basis S#### is SECONDARY-SYNTHESIS|PROVENANCE-UNRESOLVED|UNKNOWN]",
  "NOT-EVIDENCED-IN-CAPTURE"
 ],
 "absences.<dimension>.resolution": [
  "FOUND",
  "GENUINELY-UNDEFINED-AFTER-CENSUS",
  "FIREWALL-BLOCKED",
  "ESCALATED"
 ],
 "escalations[].reason": [
  "LOAD",
  "SCHEMA-LIMITATION",
  "TIMESTAMP-ANOMALY",
  "G-08",
  "CENSUS-READING-DISAGREEMENT",
  "CONTRACT-DEVIATION",
  "OTHER"
 ],
 "stage2_dispositions[].by_dimension.<dimension>": [
  "FOUND",
  "FALSE-HIT",
  "UNSUPPLIED-DIMENSION",
  "ESCALATED"
 ],
 "stage2_dispositions[].method": [
  "WHOLE-FILE",
  "STAGE-2A-2B"
 ],
 "timeline[].order": [
  "ORDERED",
  "UNORDERED-BLOCK"
 ],
 "timeline[].date_applies_to_file": [
  "CONFIRMED",
  "NOT-CONFIRMED",
  "NOT-ASSESSED"
 ],
 "timeline[].change_vs_previous": [
  "FIRST",
  "RESTATES",
  "EXTENDS",
  "NARROWS",
  "CHANGES-DEFINITION",
  "CHANGES-TYPE",
  "CHANGES-TERM",
  "CONTRADICTS",
  "RETRACTS",
  "NOT-COMPARABLE"
 ],
 "semantic_status": [
  null,
  "CONTESTED",
  "HOMONYM-SPLIT",
  "RECONCILED(<relationship>/<basis>, type <tc>)[; …]",
  "IDENTITY-UNWITNESSED"
 ],
 "semantic_status_rule": [
  "ROW-0",
  "ROW-1",
  "ROW-2",
  "ROW-3",
  "ROW-4"
 ],
 "record_status": [
  "PROPOSED"
 ],
 "lifecycle_stage (register)": [
  "OBSERVED",
  "ANALYSED",
  "HYPOTHESIS-STATED",
  "TEST-DEFINED"
 ],
 "gap_status (register kind GAP)": [
  "NOT-FOUND-IN-CAPTURE",
  "NOT-FOUND-BOUNDED",
  "NOT-FOUND-AFTER-CENSUS",
  "NOT-FOUND-LOAD-ESCALATED"
 ],
 "tier": [
  "U",
  "Z"
 ],
 "lens (register)": [
  "MATHEMATICAL",
  "STATISTICAL",
  "DDD",
  "LOGIC",
  "EPISTEMIC",
  "CHRONOLOGICAL",
  "MIXED"
 ],
 "scale (register)": [
  "OBJECT"
 ],
 "output_layer (register)": [
  "B",
  "C"
 ]
}
```

## E. Exact output schema (binding; the verifier validates it)

```json
{
 "object_record": {
  "working_label": "str",
  "batch_id": "str",
  "run_id": "str",
  "contract_sha256": "str",
  "input_manifest_sha256": "str",
  "tier": "U|Z",
  "tier_causing_pair_ids": "[str]",
  "hub": "bool (copied from the slice)",
  "timeline": "[{source_id, historical_position, date_basis, order, states:{epistemic_class: SOURCE|INFERENCE, quote|step}, change_vs_previous, date_applies_to_file}]  (F1: a point whose date is NOT-CONFIRMED keeps its order value with date_applies_to_file NOT-CONFIRMED)",
  "timeline_summary": "{first_lexical, first_conceptual, first_formal, first_operational, first_governance, later_support[], later_refinement[], contradicted_by[], rejected_by[], current_lifecycle}",
  "semantic_status": "enum (§12.2.4 with H-11a–d; Tier Z: null)",
  "semantic_status_rule": "ROW-0..ROW-4",
  "semantic_status_note": "str (Tier Z: the H-11a note)",
  "semantic_evidence": "{d4:[{source_id, quote}], d5:[{source_id, quote}]}",
  "pair_breakdown": "{<relationship>|<basis>: count}",
  "type_status": "enum, or null with an `escalations` entry for it",
  "mathematical_status": "enum, or null with an `escalations` entry for it",
  "primary_layer": "enum",
  "secondary_roles": "[enum]",
  "escalations": "[{field, reason: enum, detail, hub_record?}]  (hub: one LOAD entry per hit-bearing absence dimension, carrying the slice's hub_record; a field the closed values cannot represent: reason SCHEMA-LIMITATION plus a SCHEMA-LIMITATION register record)",
  "births": "{lexical, conceptual, formal, operational, governance: enum string}",
  "absences": "{<dimension>: {resolution: enum, negative_label, population_basis, supplied_by: {source_id, anchor, quote} | null, reason, relative_timing?: LATER-THAN-FIRST-APPEARANCE (only for a FOUND from a Track-B source over a Track-A object, §14.5)}}",
  "stage2_dispositions": "[{source_id, hit_kind: raw|ledger, hit_key, term_index, method: enum, by_dimension: {<every DIMENSION of the label>: enum}, offsets?: [int], reason}]  -- one entry per DISTINCT stage-1 hit key; for method STAGE-2A-2B, `offsets` lists the Stage-2A offsets (from the read log) the disposition covers, and every logged occurrence is covered exactly once (F3). Hub labels: EMPTY (stage 2 not performed)",
  "census_reading_disagreements": "[{dimension, source_id, anchor, quote, stage1_terms}]",
  "dependency_edges": "[{target_label, kind: enum, source_id, quote}]",
  "status_basis": "{<status field>: {sources:[S####], rule_or_reasoning, epistemic_class, what_says_this, what_would_make_this_wrong, anti_projection}}",
  "track_composition": "{<track_tag>: count}",
  "hindsight_dependency": "[str]",
  "superseded_by_sources": "[{source_id, position}]",
  "proposed_by": "AI-AGENT",
  "evidence_presentation": "V1-PLUS-ROWS",
  "record_status": "PROPOSED",
  "model_id": "exact served id string",
  "generation_parameters": "str|obj",
  "author_role": "str",
  "analysis_date": "YYYY-MM-DD",
  "checklist_examined": "[int] (only if in_checklist)"
 },
 "register_record": {
  "rs_id": "<run_id>:<batch_id>:<n>",
  "batch_id": "str",
  "working_label": "str",
  "kind": "§13.2 kind",
  "topics": "[str]",
  "lens": "enum",
  "scale": "OBJECT",
  "statement": "str",
  "epistemic_class": "§11.1",
  "output_layer": "B|C",
  "supporting_evidence": "[{source_id, anchor|quote, evidence_kind: CORPUS|EXTERNAL-THEORY}]",
  "historical_anchor": "[{source_id, historical_position, date_basis}]",
  "research_time": "ISO datetime",
  "run_id": "str",
  "contract_sha256": "str",
  "model_id": "str",
  "generation_parameters": "str|obj",
  "origin": "P3B",
  "related_labels": "[str]",
  "derived_from_records": "[rs_id]",
  "lifecycle_stage": "enum",
  "author_role": "str",
  "gap_status": "enum (kind GAP only)",
  "proposed_topic_definitions": "{PROPOSED:<topic>: definition} (only if a PROPOSED topic is used)",
  "profile (HYPOTHESIS / STRUCTURE-CANDIDATE)": "falsification_condition, validation_question, competing_hypotheses[], contradicting_evidence[] with search record, temporal_scope, claim_type, evidence_level, test_plan_sha256 (if TEST-DEFINED); STRUCTURE-CANDIDATE adds §13.11 fields"
 },
 "p1_gap_record": {
  "gap_id": "<run_id>:<batch_id>:G<n>",
  "batch_id": "str",
  "working_label": "str",
  "source_id": "str",
  "anchor": "str",
  "quote": "str",
  "what_p1_missed": "str",
  "dimension": "str|null",
  "found_via": "STAGE-2|STEP-1-READING",
  "run_id": "str",
  "contract_sha256": "str",
  "model_id": "str"
 }
}
```

---

# PROTOCOL TEXT (verbatim from the frozen core v1.7)

## 1A Research-first principle (D-27)

Stated at the head of this document. Operationally it means:
1. the per-label procedure (§9.8) is the controlled core, and the research discovery loop (§9B) runs around it;
2. the researcher is a senior multidisciplinary analyst, not an extractor or a classification engine (§9A);
3. the research register is the **primary discovery channel**, not an exception report (§13.1);
4. the existing schema and vocabularies are **operational constraints, not a claim of completeness** (§1E);
5. research runs at **three scales**: object, cross-object and corpus (§9D, §9E);
6. every serious hypothesis is exposed to **disconfirmation** (§13.10), and every structure candidate meets a
   formal standard (§13.11).

## 1B Three research output layers — never collapsed (D-28)

| Layer | What it holds | Epistemic classes allowed | Where it lives | Example |
|---|---|---|---|---|
| **A. Historical reconstruction** | what the corpus states or demonstrates at its chronological point | `SOURCE`, `INFERENCE` (step written out) | object records: statuses, births, the per-label `timeline` (§14.3) | "S0472 defines K as …" |
| **B. Research observation / discovery** | what the researcher notices while analysing the evidence | `RESEARCH-OBSERVATION`, `DOMAIN-INTERPRETATION`, `EXTERNAL-THEORY-COMPARISON` | research register (§13) | "S0472 and S0618 appear to use K in incompatible ways." |
| **C. Research hypothesis / proposal** | a possible explanation or improved structure; provisional and testable | `RESEARCH-SUGGESTION`, `HYPOTHESIS`, `THEORY-CANDIDATE` | research register (§13) | "Hypothesis: K may denote two distinct objects that were historically conflated." |

Rules:
1. Layer-B and layer-C content **never** enters a layer-A field. A hypothesis is never written into historical
   reconstruction as though the earlier source contained it (enforced by G-12).
2. Layer A may be **informed** by B/C only through a new, source-grounded layer-A record, never by copying.
   Example: a hypothesis prompts a search, and the search finds a SOURCE.
3. Every register record points to the layer-A records and S-ids it rests on. Layer A never depends on the register.

## 1C Historical time vs research time (D-29)

| | Meaning | Recorded as |
|---|---|---|
| **Historical time** | when evidence actually appears in the corpus | the S-id and its historical position with date basis (§14.2) |
| **Research time** | when the researcher recognized a pattern | `research_time` (UTC timestamp) + `run_id` + `contract_sha256` on every register record |

"S0400 contains the first evidence of X" is historical. "During analysis on 2026-…, the researcher recognized that
X and Y may form a partial order" is research-time. **A research-time statement is never backdated to the S-id it
cites.** The register records both, in separate fields.

## 1D Discovery is allowed; canonicalization is not (D-30)

| Allowed in P3b (register, labelled) | Not allowed in P3b |
|---|---|
| "There appears to be a lattice structure over these statuses." (HYPOTHESIS) | "KnowledgeOS theory contains a lattice." |
| "This looks like an aggregate boundary." (DDD HYPOTHESIS) | "This is the canonical aggregate boundary." |
| "Label X may conflate identity and state." (RESEARCH-SUGGESTION) | splitting, merging or renaming label X |
| "The schema cannot express this relation." (SCHEMA-LIMITATION) | adding an enum value |
| "The roll-up rule mishandles this case." (METHODOLOGICAL-DEFICIENCY) | changing the rule mid-run |

Canonical adoption belongs to v3.5 P5 (validation) and P6 (governance), through recorded human acts.

## 1E Openness — the corpus may challenge the method (D-31)

The protocol must remain open to theory structures that are not present in its initial methodology, terminology or
ontology. Therefore:
- existing enums are **operational constraints** on the reconciliation output, not the vocabulary of the theory;
- existing DDD models, mathematical interpretations and object boundaries are **hypotheses and candidates**;
- existing terminology is **evidence**, not necessarily canonical terminology;
- the research register's topic vocabulary is **seeded, not complete** (§13.3);
- when the evidence does not fit the method, the researcher reports it (`SCHEMA-LIMITATION`,
  `METHODOLOGICAL-DEFICIENCY`) **instead of adapting the evidence to fit the method**. The method changes only
  through change control (§26).

---

## 3.4 Firewalled and secondary files inside the boundary

- **`FIREWALL-LIMITED` (12):** inside the registry, never read, never quoted, never inferred (v3.5 A0).
  An absence search that would require reading one records `FIREWALL-BLOCKED` for that file (§11.4).
- **`SECONDARY-SYNTHESIS` (739):** inside the corpus and citable, but carry the † mark (v3.5 A13) and are
  subject to the hindsight rules of §14 (D-11).
- **`PROVENANCE-UNRESOLVED` (14):** citable with † and a `provenance-unresolved` flag. They cannot be
  the sole basis of any `ESTABLISHED-*` or `FOUND` verdict (D-11).

## 9.8 Per-label procedure (the agent's fixed order)

```
FOR label IN batch (sorted by working_label):
  1  read the label bundle, its family .md, and every row the bundle cites, IN HISTORICAL ORDER (§14.2 date
     rules; BULK blocks unordered); read whole every source file that carries a birth, a change of definition,
     type or meaning, or a contradiction (reading scope §9B / OMQ-14); build the per-label timeline (§14.3)
  2  semantic_status   ← roll-up of pairs_touching ONLY (§12.2: derived constraints D1–D5 plus the options
                          chosen under H-11); no pair is re-judged; Tier Z → null + NO-PAIR-EVIDENCE note;
                          always write pair_breakdown (counts by relationship × basis)
  3  type_status       ← from completeness.type_signature / formal_definition evidence
  4  mathematical_status ← from formal rows; STAT-/MATH-QUESTION review_flags; NOT-APPLICABLE only with reason
  5  births            ← inspect each CANDIDATE-*-BIRTH against its SOURCE ROW AND FILE (§14.2):
                          ESTABLISHED-*-BIRTH[S] | MOVED[S_earlier, quote] | UNORDERED-BLOCK[block] |
                          BIRTH-UNRESOLVED-MTIME-ONLY[S] (v1.6.6, §14.2 rule 2) | NOT-EVIDENCED-IN-CAPTURE
  6  layer             ← settle primary_layer + secondary_roles (B3), citing rows
  7  absences          ← consume the stage-1 search record (§11.4); do stage-2 whole-file reads where
                          stage 1 produced hits (Stage 2A/2B for terms ≤ 2 characters, v1.6.6; hub labels: stage 2 not performed, §11.4, v1.7); resolve each dimension to FOUND[S §anchor] |
                          GENUINELY-UNDEFINED-AFTER-CENSUS | FIREWALL-BLOCKED | ESCALATED
  8  dependency edges  ← only with a source row that states the dependency; co-occurrence ≠ edge
  9  what_says_this / what_would_make_this_wrong ← one line per status
 10  research         ← run the discovery loop (§9B) and the checklist (§9C) over steps 1–8; record every
                          observation, gap, suggestion, hypothesis or deficiency in the research register (§13);
                          never alter 1–8 because of a register record
 11  self-check        ← §20 per-record checks, including G-12 layer separation; then NEXT label
```

## 9A Researcher role — senior multidisciplinary analysis (D-32)

The agent works as a combination of **senior mathematician · senior statistician · DDD/domain architect ·
computer-logic/theory researcher · epistemic/provenance analyst**. It is not a document extractor or a
classification engine. It is expected to make substantive research observations, and doing so is part of the job.

While reading, it actively investigates whether the evidence contains:

| Lens | Structures to look for (non-exhaustive) |
|---|---|
| **Mathematical** | sets/subsets · equivalence relations · partial orders · lattices · graphs · state spaces · transitions · functions/mappings · invariants · algebraic structures · measures · probabilities · compositional structures · fixed points · monotonicity · conservation / non-collapse rules · dependency structures · counterexamples · undefined or indeterminate states |
| **Statistical** | populations · samples · observations · variables · distributions · conditional relationships · independence/dependence · missingness · measurement definitions · uncertainty · sampling problems · selection effects · false positives/negatives · calibration · reproducibility · falsifiability |
| **DDD / domain** | bounded contexts · aggregates · entities · value objects · identities · invariants · commands · events · policies · capabilities · state transitions · domain services · ownership · authority · terminology boundaries · contextual meanings · identity vs similarity · domain dependencies |
| **Logic / theory** | definitions · axioms · propositions · implications · contradictions · necessary vs sufficient conditions · invariants · exceptions · counterexamples · state-transition rules · hidden assumptions · undefined terms · type incompatibilities · circular definitions · non-equivalent formulations |

Discipline that goes with the role:
- Domain knowledge **informs the research register**. It never sets a layer-A status (§11.1). A lens reading of a
  source is `DOMAIN-INTERPRETATION` or `RESEARCH-OBSERVATION`, never `SOURCE`.
- **No discovery is forced into an existing enum** (§12.4, §13.5). If nothing fits, record a `SCHEMA-LIMITATION`.
- A lens that finds nothing records nothing. Absence of a positive finding needs no entry.

## 9B Research discovery loop (around the per-label procedure)

```
READ CHRONOLOGICALLY (the label's evidence in historical order; whole files per the reading scope)
  → RECONSTRUCT HISTORICAL STATE at each timeline point               [layer A]
  → COMPARE WITH THE PREVIOUS STATE
  → IDENTIFY CHANGE / CONTINUITY / CONTRADICTION                      [layer A: descriptive; layer B: meaning]
  → MATHEMATICAL · STATISTICAL · DDD/DOMAIN · LOGIC/THEORY ANALYSIS   (§9A lenses)
  → RESEARCH OBSERVATION                                             [layer B]
  → RESEARCH SUGGESTION / HYPOTHESIS / GAP                            [layer C / gap record]
  → TEST, OR DEFINE WHAT WOULD TEST IT                                (within the admitted corpus; §13.4)
  → RECORD WITH PROVENANCE                                           (§13.6 schema; §1C research time)
  → DO NOT CANONICALIZE                                              (§1D)
```

The per-object procedure (§9.8) runs **underneath** this loop, unchanged in its controls.

**Chronology is both an evidence constraint and a research instrument.** It is not reduced to computing
`best_historical_date`. The researcher:
1. reads a label's evidence in historical order (§14.2), never in `source_id` order (v3.5 R1: ingestion ≠ argument
   order);
2. reconstructs what is present at each stage;
3. separates what was expressed at time t from what becomes visible only later;
4. identifies changes, continuities, contradictions, refinements, rejected ideas, terminology changes,
   mathematical changes and domain-boundary changes;
5. never uses a later interpretation to rewrite an earlier state (v3.5 R10; §14.4);
6. **remains free to recognize, from later evidence, that an earlier interpretation (the corpus's own, P2's, P3a's,
   or a previous agent's) was incomplete, ambiguous or wrong**; and
7. records that recognition as a **later research observation or hypothesis**, carrying research time (§1C),
   never as a silent rewrite of the historical record.

**Reading scope (OMQ-14).** "Reading chronologically" means reading **each label's evidence** chronologically: its
rows, and in full the source files named in step 1 of §9.8. It does **not** mean re-reading all 2,779 files in
sequence. That would restart P1, which D-04 forbids and the human's boundary decision excludes ("do not restart the
corpus read"). Whether a wider reading scope is wanted is OMQ-14.

## 9C Research checklist — what the researcher must think about

**The checklist is a cognitive control mechanism, not a documentation obligation.** It exists so that the
researcher asks the right questions, not so that 2,497 × 23 answers get written down. No object is required to
yield positive answers, and a "no" is never recorded. **Only positive findings are recorded, as research records or
timeline content.**

It is applied **in full, with a short record of which questions were examined**, to the labels in the **checklist
population** defined by the sampling rule below. For every other label the researcher still considers it, and
records only positive findings.

```
 1 What exactly does the source claim?               13 Is there a DDD/domain interpretation?
 2 What changed from the previous chronological state?14 Is there an invariant?
 3 What remained invariant?                          15 Is there a transition rule?
 4 Is the terminology stable?                        16 Is there a missing definition?
 5 Is the identity stable?                           17 Is there a missing relationship?
 6 Is the type stable?                               18 Is there a missing measurement or test?
 7 Is the mathematical meaning stable?               19 Is there a counterexample?
 8 Is the operational meaning stable?                20 Does a later document clarify, or merely reinterpret, an earlier concept?
 9 Is there a contradiction?                         21 Would the interpretation survive without the later document?
10 Is there a hidden distinction?                    22 What would falsify the current interpretation?
11 Is there a possible mathematical structure?       23 What research question should be carried forward?
12 Is there a statistical interpretation?
```

Questions 1–3 feed layer A (the timeline). Questions 20–21 are the anti-projection test (§14.4). All others feed
the register.

### 9C.1 Checklist population — a sampling decision, not an importance judgment (D-36, corrected v1.4)

Choosing which objects get the full analysis is a **sampling decision**. If only objects already believed to be
important get full analysis, the method systematically misses obscure structures, low-frequency invariants, rare
counterexamples, unexpected boundaries and weak but fundamental relationships. The researcher therefore **never**
chooses the population. It is computed mechanically by the sample-plan script (Appendix A.5) and recorded in
`P3B-SAMPLE-PLAN.jsonl`.

```
checklist population (per tier run)  =  PURPOSIVE set  ∪  STRATIFIED RANDOM sample
```

**Purposive set — mechanical definitions (v1.4).** "Formal row" := a contribution row with a non-empty
`type_signature`. The v1.3 criteria are replaced, because they selected ≥ 56.7% of labels (audit report `20260924_1135_p3b-protocol-v1.3-adversarial-audit.md` §0) and so were not
a high-signal minority. The **recommended** criteria (OMQ-15 decides), with sizes measured 2026-09-24 over all 2,497
labels (Tier-X labels among them are sampled in their own S6 run):

| Criterion | Labels |
|---|--:|
| a CONTRADICTION-type row | 245 |
| ≥ 2 formal rows (non-empty `type_signature`) | 193 |
| a MATH-/STAT-/TYPE-QUESTION review_flag | 190 |
| a **strong** lineage claim: `SOURCE-CLAIMED-` REPLACEMENT, REDEFINITION, RETRACTION, CONTRADICTION or SEPARATION (v3.5-listed kinds only) | 217 |
| **Union** | **589 (23.6%)** |

Alternatives measured: without strong lineage, 501 (20.1%); v1.3 criteria without Tier X, 1,326. MIXED track is
**not** a purposive criterion until OMQ-07 is decided (C5). **Tier X is not a purposive criterion for the S5 run**:
Tier-X labels are held until H-02, so they are sampled by the same rule in their own run (S6).

**Stratified random sample.** Drawn only from labels **not** in the purposive set, so it includes ordinary labels on
purpose:
- **Strata (v1.4, three dimensions, so strata stay populated):** `row_count` band (1 · 2–3 · 4–9 · ≥ 10) ×
  `pair_count` band (0 · 1–2 · ≥ 3) × provenance mix (PRIMARY-only · any non-PRIMARY). Other variables (layer,
  birth count, track) are recorded per label for analysis, not used to stratify. The cut-points are proposed; OMQ-15
  decides.
- **Allocation:** proportional to stratum size, with a floor of `f` labels per non-empty stratum and a total size of
  `n`. `f` and `n` are OMQ-09/OMQ-15.
- **Draw:** Appendix A.5 (labels sorted by `working_label`, `random.Random(seed)`, seed recorded).
- **Inclusion probabilities** are recorded per sampled label, as stratum sample size ÷ stratum size.

**Statistical rules (RC-9):**
1. **Pre-registered outcome.** Before S4, the outcome metric is fixed in `P3B-SAMPLE-PLAN.jsonl`:
   *label yields ≥ 1 record, passing G-09 and G-12, of kind HYPOTHESIS, STRUCTURE-CANDIDATE, SCHEMA-LIMITATION or
   GAP-with-NOT-FOUND-AFTER-CENSUS*; secondary: count of such records. (v1.5: the same mechanical criterion for every
   kind. The v1.4 "audit-confirmed" wording measured GAPs unevenly, because the audit only samples them.)
2. **Weighting.** Every corpus-level rate estimated from the random component is weighted by inverse inclusion
   probability. Unweighted rates are reported only as sample descriptives.
3. **Purposive vs random comparison.** The phase report gives the outcome rate in the purposive set and the
   weighted rate in the non-purposive population. The second estimates what the purposive criteria miss.
4. **Blinding (partial).** The agent is told only that a label is **in the population**, never why (purposive or
   random), and never sees the sample plan. The label's own evidence may reveal purposive signals (a CONTRADICTION row,
   a review flag), so this blinding is **partial** and is reported as such.
5. **Design frozen before S4.** Criteria, strata, allocation, `n`, `f` and seed are fixed before the pilot. A later
   change needs change control, a **new seed**, and preservation of the old plan. Both analyses are reported. Sample
   sizes may be informed by S3 **population counts**, never by observed research outcomes.

Labels outside the population still get the full per-label procedure (§9.8) and positive-only research.

# 11. EVIDENCE MODEL

## 11.1 Epistemic classes (every claim in every output carries exactly one)

| Class | Meaning | May set a P3b status? |
|---|---|---|
| `SOURCE` | stated in a corpus file, cited `[S#### §anchor]` with a verbatim quote | yes |
| `INFERENCE` | follows from cited SOURCE by a stated reasoning step | yes, with basis `INFERRED` and the step written out |
| `RESEARCH-OBSERVATION` | something the researcher notices in the evidence (layer B) | no — research register only |
| `DOMAIN-INTERPRETATION` | uses outside knowledge (mathematics, logic, DDD, statistics …) to read a source | no — annotates, or feeds the register |
| `RESEARCH-SUGGESTION` | a proposed better interpretation, structure or method (layer C) | no — research register only |
| `HYPOTHESIS` | a proposed, testable claim not yet supported (layer C) | no — research register only |
| `THEORY-CANDIDATE` | a proposed theoretical structure (layer C); typically CORPUS scale | no — research register only |
| `EXTERNAL-THEORY-COMPARISON` | correspondence of an observed structure to a known external structure (§13.12) | no — research register only; never historical evidence |
| `VERIFIED` | a mechanical check or a completed test produced it | only for mechanical fields |
| `GOVERNANCE` | a recorded human act | only via §17 |

v3.5 R6 is applied literally: source claim ≠ our assessment ≠ agent observation, kept in separate fields.

## 11.2 Minimum evidence per status (mandatory)

| Output | Minimum evidence |
|---|---|
| semantic_status | the list of consumed `pair_id`s with their verdicts; rule applied (§12.2) |
| type_status = CLOSED | a cited row with a complete type signature |
| mathematical_status ≠ NOT-APPLICABLE | cited formal row(s); for INCONSISTENT, the two conflicting statements quoted |
| ESTABLISHED-*-BIRTH | the birth row, its file's date basis (§14.2), and a statement that no earlier row in the label's history matches |
| MOVED | the earlier row with a verbatim quote |
| FOUND (absence) | `[S#### §anchor]` + quote, read in the whole file (stage 2) |
| GENUINELY-UNDEFINED-AFTER-CENSUS | a persisted stage-1 search record with `negative_label = NEGATIVE-CENSUS` and, where stage 1 had hits, the stage-2 disposition of every hit |
| BIRTH-UNRESOLVED-MTIME-ONLY (v1.6.6) | the birth row, date basis MTIME, `date_applies_to_file: NOT-CONFIRMED`, and the file's `mtime_block` value (not matching `^BULK-`; null, `"None"` and date strings count as no block, A.10) |
| ESCALATED with reason `LOAD` (hub label, v1.7) | the label listed in `P3B-S5-HUBS.jsonl`; the dimension's stage-1 search record with hits; the escalation reason `LOAD` and the label's full hub record line |
| UNSUPPLIED-DIMENSION (stage-2 disposition, v1.6.6) | `[S####]` + the reason the file concerns the same object but does not supply the dimension |
| dependency edge | a cited row stating the dependency, and the edge kind |

## 11.3 Insufficient alone (inherited from v3.5 A11, extended by the KSME-22D finding)

Same symbol · same name · same short code · similar wording · temporal proximity · same document ·
embedding or lexical similarity · a P3a relationship on a different pair · a mechanical clue alone. KSME-22D
measured the last point: in 6 of 15 cases the mechanical clue was a false cognate, yet whole-file reading found
the real relationship (`audit-p3a/KSME-22D-L2-PILOT-RESULT.md`).

## 11.4 Absence search — two stages (D-08)

**Stage 1 — mechanical (AUTOMATICALLY SAFE).** For every `NOT-EVIDENCED-IN-CAPTURE` dimension, a script
searches (a) `03-CONTRIBUTIONS.jsonl` and (b) the raw text of every `CONTENT` file in `02-FILES.jsonl`, read as the blob its `P3B-IDENTITY-MANIFEST.jsonl` row specifies (v1.6.5), for the
label, its notations (Unicode, LaTeX and ASCII variants), aliases, and group co-members' notations. It writes one
search record: `{label, dimension, terms[], scope: CORPUS-WIDE|LEDGER-ONLY, files_searched, firewall_skipped[],
hits[{source_id, anchor_or_offset, matched_term}], negative_label, population_basis, identity_summary}` (`population_basis` per §3.6; `identity_summary` = `{stasis_unobservable: n, p0_row_exception: n}`, the counts, among the files searched, of `historical_linkage = STASIS-UNOBSERVABLE` and of `content_identity = PATH-CONTENT-P0-ROW-MISALIGNED`, additive reporting that changes no label, v1.6.5). `negative_label = NEGATIVE-CENSUS` only if
both (a) and (b) ran corpus-wide and found nothing; otherwise, when nothing was found, `NEGATIVE-BOUNDED`.

**Stage 2 — AI whole-file reading (AI REVIEW).** For every hit, the agent reads the **whole** hit file (never
the snippet alone) and decides: `FOUND` (the file supplies the missing dimension for this object),
`FALSE-HIT` (term matched, referent differs, with reason), `UNSUPPLIED-DIMENSION` (v1.6.6: the file concerns the same
object, but does not supply the dimension tested, with reason), or `ESCALATED`. A dimension becomes
`GENUINELY-UNDEFINED-AFTER-CENSUS` only when stage 1 is NEGATIVE-CENSUS, or every stage-1 hit is FALSE-HIT or
UNSUPPLIED-DIMENSION.

**Census–reading disagreement (v1.6.6).** When stage 1 is NEGATIVE-CENSUS for a dimension but a whole-file reading
done under §9.8 step 1 finds material that would supply it, the dimension resolves `ESCALATED`, never FOUND, and the
disagreement is recorded (the source, anchor and quote, and the terms stage 1 used). FOUND remains a stage-2
disposition only (§11.2).

**Stage 2A/2B for short terms (v1.6.6).** When a hit's matched term has ≤ 2 characters after A.4 normalization, the
hit file is handled in two steps instead of one whole-file reading ("characters" = Unicode code points after A.4
normalization). **2A (script):** a mechanical scan of the whole file records every occurrence of the term with its
offset, together with the file's sha256 (equal to its identity-manifest `content_sha256`, A.4) and the scanning
algorithm's name and version. **2B (AI REVIEW):** the context of each recorded occurrence is read and dispositioned
FALSE-HIT, UNSUPPLIED-DIMENSION or ESCALATED. A FOUND from such a file still requires the whole file to be read as in
stage 2. The threshold is fixed; the agent does not choose it.

**Hub labels (v1.7).** For a label listed in `P3B-S5-HUBS.jsonl` (§19.4), stage 2 — including Stage 2A and 2B — is
**not performed**. Every absence dimension whose stage-1 result has hits (its `P3B-DISCOVERY-SEARCH.jsonl` record's
`hits_ref` label carries at least one ledger or raw hit) resolves `ESCALATED`, with escalation reason `LOAD` and the
label's full hub record line from `P3B-S5-HUBS.jsonl`. No hit is sampled, and no hit file is read for stage 2; a
hit-bearing dimension of a hub is never FOUND and never GENUINELY-UNDEFINED-AFTER-CENSUS. A NEGATIVE-CENSUS dimension
of a hub resolves as for any label, including the census–reading disagreement rule. Material that step-1 reading finds
for a hit-bearing dimension of a hub is recorded in that dimension's escalation, not as FOUND, and is captured as a
`P1-GAP` candidate as for a census–reading disagreement. Every other step of the per-label procedure (§9.8) is
unchanged for hub labels. **Representation for the H-19 baseline (§9F):** the S5c script treats a label's label-level
predicate over absence resolutions as `UNDETERMINED` exactly when the label is listed in `P3B-S5-HUBS.jsonl`, so the
label leaves that denominator; its other fields count as for any label. The hub exception applies to no label outside
`P3B-S5-HUBS.jsonl`.

**Found material** that P1 missed is recorded as a `P1-GAP` correction candidate (§12.3); P1 artifacts are
not edited (D-12). v3.5 A11 says "found → back to P1-style capture"; this annex performs that capture into an
append-only `P3B-P1-GAP-CAPTURE.jsonl`, never into `03-CONTRIBUTIONS.jsonl`.

## 11.5 Frozen verdict ≠ frozen evidence presentation (D-22)

Two different things are frozen in P3a, and they are treated differently:

| | What it is | Status in P3b |
|---|---|---|
| **P3a verdict** | a pair's relationship, basis and type_compatibility in `31-RECONCILIATION-PAIRS.jsonl` | **frozen and consumed as-is.** P3b never changes, re-derives or overrides it |
| **P3a evidence presentation** | the row digest P3a reviewers were shown, built by V1's `row_brief()` | **known defective** (it dropped `dependencies[]`, `lineage_claims[]`, `invariants[]`, `assumptions[]`, completeness, `missing[]` and file provenance). P3b is not required to reproduce it |

The question behind H-04 is therefore **not** "adopt P3A-V2". It is:

> **May V2's row-level presenter, `row_bundle_v2`, be used as a read-only evidence-preparation layer for P3b's
> per-label bundles, without changing any frozen P3a verdict?**

Scope of what V2 has been shown to do: the V2 validation covers candidate generation and evidence-bundle field
completeness (`P3A-V2-REPAIR-VALIDATION-REPORT.md`, `P3A-V2-FIELD-PRESERVATION-MATRIX.md`). It covers **no
adjudication**. `row_bundle_v2` is a pure function of one contribution row and its `02-FILES.jsonl` metadata. It
copies fields and decides nothing, which is why it is the only V2 component this annex proposes to use.

**Consequence.** P3b agents may now see evidence the P3a reviewers did not. If that evidence bears against a
consumed pair verdict, the agent **does not** adjust the roll-up. It records a `VERDICT-EVIDENCE-CONFLICT`
research record (topic in §13.3) citing the pair id and the rows, and the label is escalated for H-02 consideration.
The roll-up still uses the frozen verdict.

**Alternative if H-04 declines:** agents receive V1-style bundles **plus** direct read access to the full rows in
`03-CONTRIBUTIONS.jsonl` (no information is withheld either way; only the presentation differs).

---

# 12. RELATIONSHIP MODEL

## 12.1 What P3b may and may not decide

P3b **may** decide: per-object statuses; births; layers; absences; dependency edges.
P3b **may not** decide: any pair relationship, basis or type-compatibility; identity/merge of labels; a new enum
value; canonical form.

## 12.2 semantic_status — what v3.5 determines, and what it leaves open (D-09, rewritten in v1.1)

v1.0 proposed a four-row rule table reconstructed from the first run's inline rules. Checking that table against
v3.5's text showed that **one row contradicts v3.5** and **one row is not derivable from it**. v1.1 therefore
splits the rule into **derived constraints**, which are binding because v3.5's text determines them, and
**undetermined choices**, which are human decisions H-11a…d and are not resolved here.

### 12.2.1 The complete v3.5 text on semantic_status (searched 2026-09-24 across v3.5, the extraction contract, prompt1 and prompt2)

1. A11: *"Two questions per pair, answered independently, then a per-object roll-up."*
2. A11: *"PER OBJECT (roll-up of its pairs): semantic_status ∈ { RECONCILED, IDENTITY-UNWITNESSED, HOMONYM-SPLIT,
   CONTESTED }"*.
3. A11 TERMINAL: *"every group and every load-bearing object has pair records, the three per-object statuses …"*.
4. B4 table: `semantic_status` · P3 · the same four values (group "Identity").
5. B4 compact line, the **only worked example**: `RECONCILED(REPLACEMENT/CORROBORATED, type INCOMPATIBLE)`.
6. Related, not the same field: lifecycle `CONTESTED (CONTRADICTION rows)` (A10, B4).
7. A11 Q1: *"default: UNWITNESSED / NONE"*; HOMONYM *"requires positive evidence of two different concepts"*.
8. B4 footer: *"'Uncontradicted' ≠ true"*.

No v3.5 text defines any of the four values in prose, or gives a roll-up rule.

### 12.2.2 Derived constraints (binding)

| # | Constraint | Derived from |
|---|---|---|
| **D1** | `RECONCILED` requires at least one consumed pair. It is written as `RECONCILED(<relationship>/<basis>…)`, which has no content without a pair | texts 2 and 5 |
| **D2** | A label with no pair receives **none** of the four values from P3b. The empty roll-up carries no identity evidence. v3.5's defaults are conservative (text 7), and "uncontradicted ≠ true" (text 8). **Pairless ≠ reconciled** | texts 2, 7, 8 |
| **D3** | A CORROBORATED REPLACEMENT (and, by the same reading, REDEFINITION) is **compatible with RECONCILED** and does not by itself make a label CONTESTED. **v1.0 row 2 and the first run's rule contradicted this** | text 5 |
| **D4** | `CONTESTED` requires contradiction evidence: a CONTRADICTION-type row on the label, or a SOURCE-CLAIMED-CONTRADICTION lineage claim, bearing on the label's identity or meaning | texts 6, 7 (by analogy with the lifecycle field) |
| **D5** | `HOMONYM-SPLIT` requires positive evidence of two different concepts | text 7 |

**What D2 means for execution:** Tier Z labels get `semantic_status: null` + note `NO-PAIR-EVIDENCE` (§9.3).
Choosing a permanent value for them is H-11a.

### 12.2.3 Undetermined choices (human decisions; not resolved here)

| ID | Question | Options | Recommendation and why | What turns on it |
|---|---|---|---|---|
| **H-11a** | Permanent semantic_status for the 1,120 pairless labels | (i) `IDENTITY-UNWITNESSED` — nothing witnesses the label's identity relation to any other form; (ii) a new value such as `NO-PAIR-EVIDENCE` — changes a v3.5 closed list, so it needs a governance act at v3.5 level; (iii) out of the semantic_status domain — interpret v3.5 TERMINAL's "every load-bearing object has pair records" as meaning semantic_status applies only to paired objects | **(iii) with the note kept**, because it adds no value to a closed list and matches TERMINAL's wording. **Uncertainty:** "load-bearing" is undefined for objects in v3.5. And a pairless label may still hide several forms in its own rows: 143 pairless labels have ≥ 10 rows, and `knowledgeos-kernel-concept` shows multi-form labels exist. Those cases go to `HIDDEN-DISTINCTION` signals whichever option is chosen | 1,120 labels |
| **H-11b** | Does a pair verdict of HOMONYM between labels A and B make **A itself** HOMONYM-SPLIT? | (i) yes (first run, 7 of 11 cases); (ii) no — the HOMONYM verdict separates A from B, so it is a reconciliation outcome for A (`RECONCILED(HOMONYM/…)` is well-formed under D1). HOMONYM-SPLIT is then reserved for label-internal evidence that A's own rows carry two concepts | **(ii)**, because a split of the P2a *group* is not a split of the *label*, and (i) would mark both members of every HOMONYM pair as split. **Uncertainty:** under the reading that v3.5's "object" is the candidate group (OMQ-13), (i) is the natural reading | labels touching 61 HOMONYM pairs |
| **H-11c** | Aggregation when a label's pairs are mixed (some positive, some UNWITNESSED) | (i) all pairs must be positive for RECONCILED, otherwise IDENTITY-UNWITNESSED; (ii) any CORROBORATED positive pair suffices; (iii) aggregate only over pairs from identity-bearing group kinds (EXACT-STRING-REUSE, SHARED-NOTATION, SHARED-ALIAS, POSSIBLY-RELATION), leaving out CO-OCCURRENCE and STRING-SIMILARITY | **(i)**, because it follows v3.5's conservative default. Its known cost: 832 CO-OCCURRENCE groups (the weakest P2a signal, `09-ORCHESTRATOR-FLAGS.md`) will pull many labels to IDENTITY-UNWITNESSED. That cost is visible because `pair_breakdown` is mandatory, so the choice can be revisited without re-running agents | most paired labels |
| **H-11d** | Which bases count as positive for D1/H-11c? | (i) CORROBORATED and INFERRED; (ii) CORROBORATED only; (iii) all except NONE, including SOURCE-CLAIMED-ONLY | **(i)**. SOURCE-CLAIMED-ONLY is excluded because v3.5 R7 says *"A source's lineage statement is SOURCE-CLAIMED-\*, never the fact"*. INFERRED is included because v3.5 defines it as continuity shown, with the inference stated. **Uncertainty:** R7 says *"a fact needs corroboration"*, which argues for (ii) | 209 SOURCE-CLAIMED-ONLY and 246 INFERRED pairs |

### 12.2.4 Resulting rule (applied only after H-11a…d are decided; precedence top-down; the record names the rule and the pair ids)

```
0  no pair                                      → null + NO-PAIR-EVIDENCE        (D2; permanent value per H-11a)
1  D4 contradiction evidence present            → CONTESTED
2  D5 positive two-concept evidence on the label→ HOMONYM-SPLIT                  (scope per H-11b)
3  pairs positive per H-11c/H-11d               → RECONCILED(<relationship>/<basis>, type <tc>) per pair, listed
4  otherwise                                    → IDENTITY-UNWITNESSED
```

Rows 0, 3 and 4 are mechanical once H-11 is decided. Rows 1 and 2 need AI reading of the label's rows (§15).

## 12.3 Corrections, supersession, append-only history

- No existing record is edited or deleted. A different interpretation produces a **new** record plus a
  `P3B-COR-####` entry: `{target_record, target_artifact, reason, evidence, new_record_ref, author_role, date}`.
- Supersession is represented by the new record's `supersedes` field and the COR entry, never by removal.
- File classes are in §24.2.

## 12.4 Ambiguity

A relationship that fits none of the closed values is **never coerced** (KSME-20 Adjudication Contract §2 item 10).
P3b records it as a research record (kind `SCHEMA-LIMITATION`, topic `ONTOLOGY-GAP`) with the quote, and the P3b
status proceeds on the closed values only. The same applies to anything that semantic_status, type_status,
mathematical_status, layer, dependency kinds, or the seeded topic vocabulary cannot represent (§13.5).

---

# 13. RESEARCH REGISTER — THE PRIMARY DISCOVERY CHANNEL

## 13.1 Definition and standing (D-10, amended in v1.2)

The research register (`P3B-RESEARCH-REGISTER.jsonl`) holds every research record produced during P3b.
**Research records are not merely exception reports. They are the primary discovery channel through which P3b can
expose structures that the reconciliation model did not anticipate.** They hold layers B and C (§1B).

Invariants, unchanged from v1.1 and binding:
- **A research record never changes a P3b status, a P3a verdict, or any frozen artifact.**
- A research record is never a canonical theory element (§1D).
- ("Research signal", as used in v1.0/v1.1, is any research record. Ids stay `P3B-RS-#####`.)

## 13.2 Record kinds (closed; small; operational)

| Kind | Output layer | Purpose |
|---|---|---|
| `OBSERVATION` | B | something noticed in the evidence |
| `GAP` | B | something that appears to be missing (§13.4) |
| `SUGGESTION-RESEARCH` | C | a better interpretation, a missing concept or structure (§13.7) |
| `SUGGESTION-METHOD` | C | a better methodological solution (§13.7) |
| `HYPOTHESIS` | C | a falsifiable explanatory claim |
| `STRUCTURE-CANDIDATE` | C | a candidate mathematical / statistical / DDD / logical structure (with `lens`) |
| `SCHEMA-LIMITATION` | B | the existing vocabulary cannot represent what the evidence shows (§13.5) |
| `METHODOLOGICAL-DEFICIENCY` | B | the current P3b model, schema or procedure is inadequate for the evidence (§13.8) |

Kinds are closed because the gates, the lifecycle and the audit are defined per kind. Adding a kind is change
control (§26).

**The research ontology is not closed merely because the storage schema is closed** (D-37). A discovery that
cannot be faithfully represented by any existing kind is a legitimate discovery. It is recorded as
`SCHEMA-LIMITATION`, states which epistemic type of finding it is and why no kind fits, and is proposed for a new
record kind through change control (H-14). It is never squeezed into the nearest kind.

**THEORY-CANDIDATE is not a record kind (v1.5, V-I1).** A theory candidate is a `HYPOTHESIS` (or `STRUCTURE-CANDIDATE`)
record at `scale: CORPUS` with `epistemic_class: THEORY-CANDIDATE`. Wherever this protocol says "THEORY-CANDIDATE
record", it means that. Its obligations are those of its kind plus the CORPUS mandatory content (§9E).

## 13.3 Topic vocabulary (seeded, extensible — D-33)

Every record carries one or more **topics**. The seed below is the v1.1 signal vocabulary, each topic grounded in a
documented occurrence in this corpus. **The seed is not assumed complete** (§1E).

| Topic | Meaning | Grounding |
|---|---|---|
| `CONTRADICTION` | two sources assert incompatible claims about one form | v3.5 B2 type; P3a CONTRADICTION rows |
| `AMBIGUITY` | one source statement supports ≥ 2 readings | P3a notes_for_p3b |
| `HIDDEN-DISTINCTION` | one label appears to carry ≥ 2 distinct objects | "≥4 mutually incompatible K formulations" in `knowledgeos-kernel-concept` (`.claude/CONTEXT.md`) |
| `POSSIBLE-DUPLICATE` | two labels appear to denote one object | P2a design ("K-state", "knowledge-state", "K*") |
| `TERMINOLOGY-INSTABILITY` | the name changes while the role appears stable, or the reverse | v3.5 B6 (K_t / K*_t / S_t) |
| `TYPE-DRIFT` | type signature changes while the semantic role appears stable | v3.5 B6 (δ_A → δ_B) |
| `INVARIANT-CANDIDATE` | a statement that looks like a non-collapse rule or invariant | v3.5 B6 ("This distinction must never be collapsed") |
| `TRANSITION-RULE-CANDIDATE` | a statement about how a state or status changes | recurring in the kernel family |
| `MATHEMATICAL-STRUCTURE` | an unexplained order, algebra, measure or other mathematical relationship | "four partial orders", authority algebra (F-lane state file, EXTERNAL-LANE) |
| `MEASUREMENT-PROBLEM` | a quantity named without a population, sample or valid test | v3.5 B6 ("P(A)=1.2 called a probability") |
| `COUNTEREXAMPLE` | a source case that breaks a stated claim | v3.5 B2 type COUNTEREXAMPLE |
| `ONTOLOGY-GAP` | a real relationship that fits no closed value | the two confirmed gaps (`KSME-20-P3A-ERROR-TAXONOMY.md` §6) |
| `UNRESOLVED-RELATIONSHIP` | a connection between labels that share no P3a pair | §9.4 |
| `IMPORTANT-ABSENCE` | a load-bearing dimension that is GENUINELY-UNDEFINED-AFTER-CENSUS | v3.5 B6 last case |
| `HINDSIGHT-RISK` | a reading that depends on a later source | §14 |
| `CROSS-TRACK` | the evidence for an object spans Track A and Track B | `KSME-20-P3A-TRACK-PROVENANCE-AUDIT.md` |
| `VERDICT-EVIDENCE-CONFLICT` | evidence visible to P3b (for example through `row_bundle_v2`) bears against a frozen P3a pair verdict the roll-up consumes | §11.5; the `row_brief()` defect (`P3A-IMPLEMENTATION-CONFORMANCE-AUDIT.md`) |

**Extensibility.** A researcher may use a topic not in the seed by writing it as `PROPOSED:<NAME>` together with a
one-sentence definition and the record that first needed it. It is registered in `P3B-TOPIC-PROPOSALS.jsonl` and
**usable immediately** in the register. Proposed topics enter the seed only by change control (§26), after review.
Examples the researcher may need: `STATE-MACHINE-CANDIDATE`, `ORDER-OR-LATTICE-CANDIDATE`,
`COMPOSITIONAL-STRUCTURE`, `PROBABILISTIC-STRUCTURE`, `DDD-BOUNDARY-CANDIDATE`, `IDENTITY-STATE-CONFLATION`,
`MISSING-TRANSITION`, `MISSING-INVARIANT`. **They are examples, not pre-approved topics**; each needs its
definition when first used. This keeps discovery open while the vocabulary stays governed. Topic tags never
touch the closed enums of the object records (G-05).

## 13.4 Research gaps (kind `GAP`)

For every gap the record states:
- **what** is missing (definition, operation, transition, invariant, measurement definition, population/sample,
  dependency, domain boundary, identity distinction, evidence, test, counterexample, terminology, historical
  transition …);
- **where** the gap becomes visible (S-ids, timeline point);
- **status**, distinguishing clearly:
  - `NOT-FOUND-IN-CAPTURE`: not in the ledger, and no corpus search performed;
  - `NOT-FOUND-BOUNDED`: a search was performed but not corpus-wide;
  - `NOT-FOUND-AFTER-CENSUS`: corpus-wide stage-1 search empty, and every hit resolved FALSE-HIT or UNSUPPLIED-DIMENSION by stage 2 (§11.4, v1.6.6);
  - `NOT-FOUND-LOAD-ESCALATED` (v1.7): a hub label's dimension with stage-1 hits, for which stage 2 was not performed (§11.4 hub exception); it asserts nothing about the hits;
  - **never** "does not exist" or "never existed". The corpus is a finite capture; `NOT-FOUND-AFTER-CENSUS` is the
    strongest negative P3b may assert (v3.5 R17);
- the search record, if a search was performed (the stage-1 schema of §11.4);
- **what evidence would resolve it.**

Where a gap concerns a completeness dimension of the label itself, the absence procedure of §11.4 governs, and the
gap record references its result rather than duplicating it.

## 13.5 Schema limitation (kind `SCHEMA-LIMITATION`)

Used when the evidence shows something that semantic_status, type_status, mathematical_status, layer, dependency
kinds or the seeded topics cannot represent. **The observation is never forced into the nearest category.** The
record states: source evidence · the exact observation · why the existing vocabulary is insufficient · candidate
interpretation · possible mathematical or domain significance · what evidence would confirm or refute it. A
schema limitation may propose a new concept, relation, structure, invariant, transition rule or domain distinction
as a research discovery. **It never becomes a canonical enum or theory element automatically.**

## 13.6 Record schema — core plus profiles (corrected v1.4)

**Core (every record):** `rs_id` · `kind` · `topics[]` · `lens` (MATHEMATICAL | STATISTICAL | DDD | LOGIC |
EPISTEMIC | CHRONOLOGICAL | MIXED) · `scale` (OBJECT | CROSS-OBJECT | CORPUS) · `statement` · `epistemic_class`
(§11.1) · `output_layer` (B | C) · `supporting_evidence[]` (S-id + anchor/quote, each with `evidence_kind`: CORPUS |
EXTERNAL-THEORY, §13.12) · `historical_anchor` (the S-ids' historical positions with date basis) · `research_time` ·
`run_id` · `contract_sha256` · `model_id` (§18) · `origin` (P3B | EXTERNAL-LANE) · `related_labels[]` ·
`derived_from_records[]` · `lifecycle_stage` (§13.9) · `author_role`.

**Profiles by kind** (only these add obligations):

| Kind | Adds |
|---|---|
| **OBSERVATION** | nothing. `contradicting_evidence[]` is **optional** (RC-10): an observation may remain an observation, cheaply |
| GAP | the §13.4 fields |
| SUGGESTION-RESEARCH / SUGGESTION-METHOD | the §13.7 fields |
| SCHEMA-LIMITATION | the §13.5 fields |
| METHODOLOGICAL-DEFICIENCY | the §13.8 fields |
| **HYPOTHESIS / STRUCTURE-CANDIDATE** (incl. class THEORY-CANDIDATE) | `falsification_condition` · `validation_question` · `competing_hypotheses[]` · the disconfirmation record (§13.10, `contradicting_evidence[]` **mandatory with its search record**) · `temporal_scope` (§14.4c) · STRUCTURE-CANDIDATE additionally §13.11 |
| any record at scale CROSS-OBJECT or CORPUS | the §9E mandatory content for that scale |

## 13.7 Suggestions (kinds `SUGGESTION-RESEARCH`, `SUGGESTION-METHOD`)

When the researcher believes there is a better interpretation, better architecture, missing concept, missing
mathematical structure or better method, it says so as a suggestion. **It never silently modifies the protocol or
the historical interpretation.** Each suggestion contains:

1 observation · 2 evidence · 3 current interpretation or treatment · 4 suggested alternative · 5 reason ·
6 problem it solves · 7 possible risks · 8 evidence needed to validate it ·
9 `execution_impact`: `NONE` (record only) | `DEFER-TO-P4+` | `AFFECTS-METHOD` (→ §13.8 path).

Worked example (format only, not a finding):
> SUGGESTION-RESEARCH · topic `PROPOSED:IDENTITY-STATE-CONFLATION` (definition given) · The object model may be
> conflating state and identity. Evidence: Sxxxx, Syyyy. Current treatment: one working_label. Alternative:
> distinguish object identity from state representation. Reason: the same label occurs with incompatible formal
> definitions. Validation: inspect the chronological transitions and counterexamples. Execution impact: NONE.

## 13.8 Methodological deficiency (kind `METHODOLOGICAL-DEFICIENCY`)

If the evidence demonstrates that the P3b model, ontology, terminology, schema, aggregation rule or procedure is
inadequate, the researcher reports it **rather than adapting the evidence to fit the method**. It may suggest a
missing field, relation, topic or kind; an inadequate classification; a problematic aggregation rule; a
mathematical model that better explains the evidence; a different DDD boundary; a statistical test; or a missing
validation experiment.

Fields: the evidence · the component affected (section/field/rule) · how the evidence conflicts with it ·
suggested remedy · `execution_impact` ∈ {`NONE`, `AFFECTS-CURRENT-BATCH`, `AFFECTS-METHOD`}.

Routing: `AFFECTS-CURRENT-BATCH` or `AFFECTS-METHOD` → escalation (§22) to the human. **The method changes only
through change control (§26).** Execution continues under the current contract unless the human stops it, or the
deficiency makes a gate unpassable. In that case the stop-the-line rule applies (§23).

## 13.9 Lifecycle

```
OBSERVED → ANALYSED → HYPOTHESIS-STATED → TEST-DEFINED → TESTED → STATUS
```
- `OBSERVED`: quote(s) + S-ids + the record that surfaced it. (AI)
- `ANALYSED`: competing explanations listed, at least two when two exist. (AI)
- `HYPOTHESIS-STATED`: one falsifiable statement, epistemic class HYPOTHESIS. (AI)
- `TEST-DEFINED`: what evidence would refute it, and where to look. (AI)
- `TESTED`: the test is run **within the admitted corpus**: stage-1-style search, whole-file reading, or a formal or
  mathematical check. (AI or script)
- `STATUS` ∈ {`SUPPORTED-IN-CORPUS`, `REFUTED-IN-CORPUS`, `UNDETERMINED-FROM-CORPUS`, `ROUTED-TO-GOVERNANCE`}. (AI
  records; consequential routing is human, §17). `SUPPORTED-IN-CORPUS` is not validation in the v3.5 P5 sense.

**Obligation in P3b.** Every record noticed must reach at least `OBSERVED`. **An observation may legitimately
remain an observation** (D-38). Not every observation leads to a hypothesis, and the protocol **must not** be
satisfied by manufacturing hypotheses. A hypothesis is stated only when the researcher has a genuine explanatory
claim that could turn out false. The register audit checks for manufactured hypotheses (§21 item 5).

Every `HYPOTHESIS`, `STRUCTURE-CANDIDATE` and `THEORY-CANDIDATE` must reach at least `TEST-DEFINED`, with a
falsification condition **and** the disconfirmation plan of §13.10. `TESTED` and `STATUS` are performed within the budget fixed under H-12 / OMQ-16, on records selected **by rule, not by agent
choice** (RC-9g): every CORPUS-scale record, plus a seeded random share of the others (share fixed under OMQ-16). This
prevents testing only likely winners. **When `TESTED` is recorded, all four disconfirmation searches must have been
performed.** Testing never blocks batch acceptance, and untested records carry forward to
P4–P7 as research input.

## 13.9a STATUS assignment rules (RC-3, new in v1.4)

A STATUS is assigned **only** by these rules. Anything not meeting them is `UNDETERMINED-FROM-CORPUS`.

| STATUS | Required |
|---|---|
| **SUPPORTED-IN-CORPUS** | (1) **A**: positive support from at least **N_support** independent PRIMARY sources (independence per §9E.3; **N_support is fixed by H-16**); for CROSS-OBJECT, **SUPPORTED-IN-CORPUS is not available at freeze** (known limitation L-1, §36): a single CROSS-OBJECT finding is at most UNDETERMINED-FROM-CORPUS, and recurrence is assessed only at CORPUS scale; for CORPUS, at least N_support independent occurrences (§9E.3); **and** (2) **B** and **D** performed at **census scope** (§13.10a: LEXICAL over the full Appendix A.4 corpus lists with `NEGATIVE-CENSUS`; STRUCTURAL-ENUMERATION over the structure's full X), on the population and temporal scope **frozen at TEST-DEFINED** and persisted in an earlier run (§13.10a), with no unresolved contradiction or counterexample (an item is *resolved* only by a recorded non-applicability check showing it does not bear on H as stated; such checks are audited, §21 item 6); **and** (3) **C**: at least one recorded item of corpus evidence that favours H over each listed competing hypothesis; **and** (4) for DISCOVERY findings, a pooled control comparison that is **DIFFERENTIATED** under the pre-registered rule (§9E.2 item 6), and for CORPUS findings the derived comparison (§9E.2 item 8). A comparison that is absent, unregistered, `UNDERPOWERED`, `UNMAPPED`, or made under `blinding_level: PARTIAL` does **not** satisfy this condition (§9E.2 item 6); **and** (5) no Tier-X participant before H-02; **and** (6) every item in the basis is `evidence_kind: CORPUS` |
| **REFUTED-IN-CORPUS** | a **verified** contradiction or counterexample from a PRIMARY source **within H's temporal scope** (§14.4c), quoted, with the check that it applies to H as stated (not to a variant) |
| **UNDETERMINED-FROM-CORPUS** | everything else, including: support below N_support; B or D not exhaustive; a competing hypothesis not discriminated; controls undifferentiated |
| **ROUTED-TO-GOVERNANCE** | a human-review routing (§17); never a truth value |

Explicit prohibitions:
- **Absence of contradiction is never confirmation.** An empty B, even exhaustive, satisfies only condition (2).
- **"Not found" is never "false".** Failing to find support gives UNDETERMINED, never REFUTED.
- **External theory never sets a corpus STATUS** (condition 6; §13.12).
- **Recurrence alone never supports.** Frequency counts only through independent occurrences, and still needs
  (2)–(4).

G-09 checks every STATUS against this table.

## 13.9b Evidence level of CORPUS theory candidates (human decision 2026-09-24; D-68)

Every CORPUS theory candidate carries `evidence_level`, **computed** from recorded results and never asserted:

| Level | Requires |
|---|---|
| `PATTERN-OBSERVED` | the record exists with its evidence (a pattern exists) |
| `RECURRENT` | ≥ N_support independent occurrences (§9E.3) |
| `DISCRIMINATING` | RECURRENT, and the derived control comparison is DIFFERENTIATED (§9E.2 item 8) |
| `PREDICTIVE` | DISCRIMINATING, and ≥ 1 hold-out prediction scored PREDICTION-CONFIRMED with none PREDICTION-FAILED (only once the H-19 addendum is approved, §9F) |
| `THEORY-SUPPORTED` | PREDICTIVE, and STATUS = SUPPORTED-IN-CORPUS (§13.9a) |

Rules:
- **`claim_type`** ∈ {`EMPIRICAL-GENERALIZATION`, `HISTORICAL`, `DEFINITIONAL`, `STRUCTURAL`, `EXPLANATORY`} (closed) is
  declared at TEST-DEFINED and frozen with the test plan (it is part of `test_plan_sha256`, §13.10a). Declaring a
  non-empirical type only lowers the attainable level, so it cannot be used to evade a test (v1.6.3).
- Levels are cumulative. A candidate whose `claim_type` is not EMPIRICAL-GENERALIZATION can reach at most
  DISCRIMINATING in P3b, because it cannot be predictive.
- **No level, including THEORY-SUPPORTED, means "verified" or canonical.** Canonical adoption remains v3.5 P5/P6,
  through recorded human acts (§1D).
- Pattern confirmation (RECURRENT) and predictive confirmation (PREDICTIVE) are always reported separately.

## 13.10 Disconfirmation discipline (D-38)

Do not search only for confirmation. For every serious hypothesis H (every HYPOTHESIS, STRUCTURE-CANDIDATE and
THEORY-CANDIDATE):

| Search | Question | Recorded as |
|---|---|---|
| **A — support** | What evidence supports H? | `supporting_evidence[]` |
| **B — contradiction** | What evidence contradicts H? | `contradicting_evidence[]`, **with the search record** (terms, scope, negative label) even when empty |
| **C — discrimination** | What evidence would distinguish H from a competing H2? Where would it be found? | `competing_hypotheses[]` + `discriminating_evidence` |
| **D — counterexample** | Can a counterexample destroy H? Where would one be? | `counterexample_search` (scope, result, negative label) |

**Every operation A–D records** (RC-8, v1.4):
`population` (the enumerated set of labels/objects/sources, or the corpus scope with its file list reference) ·
`method` ∈ {`LEXICAL` (stage-1-style search, Appendix A.4), `STRUCTURAL-ENUMERATION` (check the claimed property on
every tuple of a finite enumerated X), `WHOLE-FILE-READING` (read the listed files whole)} · `completeness` ∈
{`EXHAUSTIVE`, `SAMPLED` (with seed and size)} · `termination_bound` (the size of the population or tuple space; for
STRUCTURAL-ENUMERATION |X|ᵏ for a k-ary property) · `result` · `negative_label` where nothing was found
(NEGATIVE-BOUNDED | NEGATIVE-CENSUS). A counterexample search over a finite X is exhaustive enumeration, which
terminates. A lexical search is bounded by the corpus file list. **An unbounded search is not a valid operation.**

At `TEST-DEFINED`, B–D are **planned** (what, where). At `TESTED`, A–D are **performed**. A hypothesis with an empty
B that has no search record is **confirmation-only** and fails G-09. Elegant patterns found through selective
reading are the specific failure this rule exists to catch.

## 13.10a Pre-registration and freezing of tests (V-C1 v1.5; corrected v1.6, W-C2)

At `TEST-DEFINED` the record fixes, and hashes as `test_plan_sha256`, the **test plan**:
- the hypothesis statement, `claim_type` (§13.9b) and `competing_hypotheses[]`;
- for A–D: population, method, **search terms**;
- `temporal_scope` (computed, §14.4c);
- for structural claims, X **as a mechanical definition**;
- the decision rule for STATUS (§13.9a);
- the stopping rule (termination bound).

**After TEST-DEFINED none of these may change in that record.**

**Enforced ordering (v1.6).** The TEST-DEFINED record is persisted in an **earlier run** than any TESTED record for the
same hypothesis: a different `run_id`, with the plan hash written into that run's `P3B-INPUT-MANIFEST.json` entry
before the testing run starts. G-09 fails a TESTED record whose plan was not persisted in an earlier run. A plan written
in the same run as its test is not pre-registered.

**Search terms are not free.** LEXICAL B and D use **at least** the mechanical Appendix A.4 terms of **every
participant label** (label, notations, aliases), plus any terms the researcher adds. Dropping a mechanical term is not
permitted.

**X is not a hand list.** For STRUCTURAL-ENUMERATION, X is defined as either (a) the members of a generator candidate
set, or (b) a predicate over labels or records that a script evaluates on the snapshot (for example "all labels in
group G0174", "all labels with at least two rows whose `type_signature` is non-empty"). The script's output is X. A list of ids written by
hand is not a valid X. **A predicate may reference only fields of frozen artifacts or of generator outputs persisted before the plan**:
`02-FILES.jsonl`, `03-CONTRIBUTIONS.jsonl`, `20-FAMILIES/_derived.json`, `31-RECONCILIATION-PAIRS.jsonl`, and
non-SELF-DERIVED generator candidates. It may **not** reference register fields (topics, `related_labels`, record ids) or
any other researcher-written or agent-written field (including P3b timelines), and contains **at most one id-valued
atom**. An *id-valued atom* is a comparison of a field with **one specific group id or one specific non-SELF-DERIVED
generator candidate id**. Comparisons with label ids, source ids or pair ids are **never** permitted. Regex, substring or prefix matching on identifier-like fields (`path`, `working_label`, `source_id`,
`pair_id`, `group_id`) is not permitted at all (v1.6.3). It never lists, includes or excludes literal label or source ids. Candidates of SELF-DERIVED generators
(G-TOPIC, G-HYP) are not valid X (v1.6.2, B5).

**Census scope for B and D.** A LEXICAL B or D covers the full Appendix A.4 corpus lists and records `NEGATIVE-CENSUS`
when empty. A narrower search is `NEGATIVE-BOUNDED` and cannot satisfy §13.9a condition (2).

**Narrowing is a new record, flagged.** A narrower population, scope, term set or X after seeing results creates a
**new** hypothesis record. It cites the original and every counterexample or contradiction its narrowing excludes, and
carries `POST-HOC-NARROWED`. The original keeps its result. A POST-HOC-NARROWED record can reach SUPPORTED-IN-CORPUS
only on support evidence that is independent of the sources cited in the original record's A–D results.

## 13.11 Formal standard for structure candidates (D-39, corrected v1.4)

"There appears to be a lattice" is not enough for serious mathematical research, and **resemblance is never a
structure claim**. A `STRUCTURE-CANDIDATE` states its structure formally, **where applicable**, for its lens. Any field
it cannot fill is written as `NOT-DETERMINED`, never omitted, because the unknowns are part of the finding.

**Relation source (RC-7a).** Every relation or operation carries `relation_source`:
- `CORPUS-STATED [S-ids]`: a source defines or asserts the relation;
- `RESEARCHER-CONSTRUCTED`: the researcher defines the relation from the data (its definition is written out).

Only `CORPUS-STATED` may be described as "the corpus defines …". A `RESEARCHER-CONSTRUCTED` structure is always
described as "a structure the researcher constructed over …" (hindsight test T-H1).

**Property status (RC-7b).** Each claimed property carries exactly one of:
`STATED-BY-SOURCE [S-ids]` · `VERIFIED-ON-ALL-ENUMERATED-INSTANCES (n, over the finite X)` ·
`INSTANCES-CONSISTENT (m of n checked; SAMPLED)` · `NOT-DETERMINED` · `VIOLATED [S-ids / tuple]`.
A universal property is never "observed". It is stated by a source, verified on a finite enumeration, or open.

**Weakest-structure rule (RC-7c).** The candidate is **named by the weakest structure its STATED/VERIFIED properties
establish**. Example: reflexive + transitive verified, antisymmetry NOT-DETERMINED → *preorder*, not partial order;
partial order with joins and meets NOT-DETERMINED → *partial order*, not lattice. Stronger structures appear only in
`competing_structures[]` (RC-7d), with the properties that would have to hold.

| Lens | Required specification |
|---|---|
| **Mathematical** | underlying set X and its elements (as corpus objects) · relation(s)/operation(s) with `relation_source` and definition · each claimed property with its RC-7b status (for example reflexive, antisymmetric, transitive, closure, identity, associativity, commutativity, joins/meets, monotonicity, fixed points) · the name by the weakest-structure rule · `competing_structures[]` · invariants · counterexamples · mapping to corpus objects · evidence for and against the mapping |
| **Statistical** | population · unit of observation · variables and measurement definitions · sampling or selection mechanism · claimed dependence or distribution · missingness · the test that would assess it, and whether the corpus can support it |
| **DDD / domain** | candidate bounded context and its language · aggregate root, entities, value objects · invariants and who enforces them · commands, events, policies · ownership and authority · boundary evidence (terminology shifts, meaning changes) · counter-evidence · `relation_source` for each boundary claim |
| **Logic / theory** | terms and definitions · axioms or premises (with `relation_source`) · the inference claimed · consistency concerns (contradictions, circularity) · necessary vs sufficient conditions · counterexamples |

Example shape (mathematical):
```
Candidate  (X, ≤)  — named: PREORDER (weakest-structure rule)
X          = { … corpus objects … }
≤          relation_source: RESEARCHER-CONSTRUCTED; defined as: …
Properties reflexive  VERIFIED-ON-ALL-ENUMERATED-INSTANCES (n=…)
           transitive VERIFIED-ON-ALL-ENUMERATED-INSTANCES (n=…)
           antisymmetric NOT-DETERMINED
Competing  partial order (needs antisymmetry) · lattice (needs antisymmetry + joins + meets)
Temporal   ACROSS-TIME (§14.4c)
Status     UNDETERMINED-FROM-CORPUS (§13.9a)
```

## 13.12 Corpus evidence vs external theoretical comparison (D-40)

Two different questions, kept apart:

| | Question | Class | May support |
|---|---|---|---|
| **Corpus evidence** | "Does the admitted corpus contain evidence for this?" | SOURCE / INFERENCE / RESEARCH-OBSERVATION | layer-A claims (SOURCE/INFERENCE only); register records |
| **External theoretical comparison** | "Does this observed structure correspond to a known mathematical, statistical, logical or DDD structure (a known lattice, algebra, statistical model, transition system, logic, pattern)?" | `EXTERNAL-THEORY-COMPARISON` | register records only |

Rules:
1. External comparison is **never historical evidence** and never supports a layer-A field (G-12).
2. It names what it compares against (standard definition, theorem, model, pattern) and states the correspondence
   as a mapping, with any mismatch.
3. Correspondence to a known structure is not evidence that the corpus intended it, which would be hindsight.
4. External comparison uses general domain knowledge. It never uses F-only or F-lane material (§3, §7).
5. Register lifecycle status for an external comparison is recorded separately as `external_correspondence` ∈
   {`CORRESPONDS`, `CORRESPONDS-PARTIALLY`, `DOES-NOT-CORRESPOND`, `NOT-DETERMINED`}. It never changes the
   corpus-evidence status.
6. Correspondence **never** supports "KnowledgeOS *is* structure S", and never enters the basis of any corpus STATUS
   (§13.9a condition 6).

---

# 14. TEMPORAL / HINDSIGHT CONTROLS

## 14.1 Principle

Chronological position is evidence infrastructure, not causality (v3.5 R1). A later document never rewrites what an
earlier document meant (v3.5 R10).

## 14.2 Birth and date rules (D-11)

1. Only a date that applies **to the file itself** can position the file. A date the file cites for another
   artifact or event never sets its position. (Evidence: F-lane §1 measured P3A's minimum-explicit-date rule wrong
   for 3 of 10 files, all synthesis documents citing older dates.)
2. Every births inspection records the birth file's `best_historical_date_basis` from `02-FILES.jsonl`, and the
   agent confirms from the whole file that the basis applies to the file itself. **From v1.6.2 the same check is
   recorded as `date_applies_to_file` on every timeline point whose source is a dated source (§14.3, A.10).** Otherwise the birth is
   `UNORDERED-BLOCK` or escalated as `TIMESTAMP-ANOMALY`. **v1.6.6:** when the birth file's only date basis is MTIME, the
   date is not confirmed for the file, and the file is in no BULK block, the birth is `BIRTH-UNRESOLVED-MTIME-ONLY[S]`;
   the timestamp is kept as provenance only and never establishes the birth. This outcome applies **in place of**
   UNORDERED-BLOCK or TIMESTAMP-ANOMALY for that case; UNORDERED-BLOCK remains for BULK-block files (rule 4) and
   TIMESTAMP-ANOMALY for other unconfirmed date bases.
3. A `SECONDARY-SYNTHESIS` or `PROVENANCE-UNRESOLVED` file **cannot alone establish** a birth, a FOUND, or a
   CONTESTED status. It may corroborate a PRIMARY source, or it produces a `HINDSIGHT-RISK` signal.
4. Inside a BULK/mtime block, order is UNORDERED (v3.5 R1).

## 14.3 Per-label timeline (layer A; mandatory where evidence exists; otherwise NOT-EVIDENCED-IN-CAPTURE)

**Timeline points** (new in v1.2, D-28): an ordered list, in historical order (§14.2), one entry per source that
states something about the label: `{source_id, historical_position, date_basis, order: ORDERED | UNORDERED-BLOCK,
states (SOURCE quote or INFERENCE with step), change_vs_previous, date_applies_to_file}`, where
`date_applies_to_file` ∈ {`CONFIRMED`, `NOT-CONFIRMED`, `NOT-ASSESSED`} is the agent's §14.2 rule-2 check **for every
point whose source is a dated source** (A.10). Object records also carry `superseded_by_sources[]` (the sources
corroborating a SUPERSEDED lifecycle, each with its dated position or `UNDATED`) (v1.6.2, B3). For these sources the
**file-level test of A.10 governs** (dated source), because they are sources rather than timeline points. For RETRACTS
points, A.10's point-level test applies (v1.6.3). `change_vs_previous` ∈ {`FIRST`, `RESTATES`,
`EXTENDS`, `NARROWS`, `CHANGES-DEFINITION`, `CHANGES-TYPE`, `CHANGES-TERM`, `CONTRADICTS`, `RETRACTS`,
`NOT-COMPARABLE`}. These classes are **descriptive**: they say what the text does relative to the previous point.
What the change *means* (for example "these are two different objects") is a layer-B/C research record, not a
timeline value.

**Summary fields:** `first_lexical` · `first_conceptual` · `first_formal` · `first_operational` · `first_governance`
(= v3.5's five birth kinds) · `later_support[]` · `later_refinement[]` · `contradicted_by[]` · `rejected_by[]` ·
`current_lifecycle` (v3.5 B4 lifecycle, still SOURCE-CLAIMED-* until corroborated).

## 14.4 Anti-projection test (per record, AI self-check, audited §21)

For every status that cites a source later than the object's first appearance, the agent answers in the record:
*"Would this status be the same if only sources up to the cited early source existed?"* If not, the status carries
`hindsight_dependency: [S-ids]` and a `HINDSIGHT-RISK` signal.

## 14.4a Later recognition of earlier inadequacy (v1.2)

When later evidence shows that an earlier interpretation was incomplete, ambiguous or wrong, the researcher records
a research record (OBSERVATION or HYPOTHESIS) with `historical_anchor` set to the earlier point and `research_time`
set to now. This covers the source's own interpretation, P2's labelling, P3a's verdict, or an earlier agent's
record. **The earlier timeline point is not edited.** If a layer-A field is itself wrong, correction follows §12.3
(new record + `P3B-COR`). §14.4's anti-projection test still applies to every layer-A status.

## 14.4b Historical time vs research time — enforcement

§1C defines the distinction. G-12 checks that no register record's `research_time` claim appears in a layer-A field,
and that no layer-A timeline point cites a register record as its basis.

## 14.4c Temporal scope of structures (RC-4; corrected v1.5, V-C2; corrected v1.6, W-C1)

§1C separates research time from historical time for **statements**. Structures need the same protection. Every
HYPOTHESIS and STRUCTURE-CANDIDATE, and every CROSS-OBJECT/CORPUS finding, carries `temporal_scope`, **computed
mechanically** (Appendix A.10), never chosen by the researcher.

**Normative definition: Appendix A.10 (v1.6.1).** This section explains; A.10 defines. Where they differ, A.10
governs. That is how v1.6.1 removes the section/algorithm drift found in two review rounds.

**Dated position (v1.6; aligned with A.10 in v1.6.1).** Temporal scope uses **dates only**, never `source_id` order
(v3.5 R1: ingestion order is not argument order). A timeline point has a **dated position** iff its source's record in
`02-FILES.jsonl` has `best_historical_date_basis = EXPLICIT`, `order_evidence` other than exactly `SOURCE_ID`, and
`mtime_block` not matching `^BULK-`, **and** the timeline point carries `date_applies_to_file: CONFIRMED`, the agent's
recorded §14.2 rule-2 confirmation. A point without that confirmation is undated. Measured: 916 files meet the file-level
conditions. The position is that
calendar date (day granularity). A source with an MTIME basis, or SOURCE_ID-only order evidence, or in a block
(`mtime_block` matching `^BULK-`), has **no dated position**. Measured: 1,167 of 2,779 files have an EXPLICIT basis;
1,612 are MTIME-only. MTIME is excluded because file times were rewritten during corpus relocation. A STEP-NUMBER
orders sources only within its own series, and is not used for cross-series temporal scope.

**Definitions.**
- `birth(p)` = the dated position of participant p's earliest timeline point that has one. If p has **no** dated
  point, p is undated.
- `end(p)` = the **minimum** over all dated RETRACTS points of p and all dated sources in `superseded_by_sources[]`.
  Otherwise +∞. **If any RETRACTS point or any source in `superseded_by_sources[]` is undated, the record is
  UNORDERED** (never +∞): an undated ending could be earlier than every dated one (v1.6.2, B3).
- `evidence(R)` = the dated positions of every source cited as supporting the relation.

| Value | Condition (computed) | How it may be described |
|---|---|---|
| `UNORDERED` | any participant is undated, or any relation-evidence source has no dated position | no temporal claim at all |
| `WITHIN-SLICE [t]` | with t* = the latest of all `birth(p)` and all `evidence(R)`: every `end(p)` is **strictly later** than t* (a same-day end counts as not alive). Recorded t = t* | "at [t], the corpus shows …"; the structure coexisted historically |
| `ACROSS-TIME` | otherwise | "**across** [periods], the researcher constructed …". **Never** "the corpus had …" at any historical point |

Rules:
1. `WITHIN-SLICE` means **coexistence at a point**, including the relation's own evidence. **v1.6 deletes the v1.5
   clause "or all relation evidence in one source"**, which let a later synthesis document attribute a relation to
   an earlier point (W-C1).
2. An ACROSS-TIME structure is a **research-time construct**. It may never be described, in any field or report, as
   having existed at a historical point (hindsight test T-H6).
3. **REFUTED within scope.** For WITHIN-SLICE [t], a counterexample must come from a source with a dated position at
   or before t, or from a participant state alive at t.
   **Support within scope (v1.6.1, C2).** For WITHIN-SLICE [t], items of A (support) and C (discrimination) count toward
   §13.9a **only if their dated position is ≤ t**. Support from later or undated sources requires a **new** record,
   whose scope is recomputed, so that later evidence can never back "at [t], the corpus shows …". The rest of this rule
   continues: For ACROSS-TIME, any PRIMARY counterexample among the
   participants' timelines counts. UNORDERED hypotheses can be refuted only by a counterexample that does not depend
   on order.
4. `temporal_scope` is computed at TEST-DEFINED **on the snapshot hashed then** (§19.5), and frozen with the test plan
   (§13.10a). G-09 recomputes it on **that** snapshot. A later timeline correction does not alter the frozen value; it
   triggers transitive re-examination (§9E), which creates a new record.
5. G-09 and G-13 check the field, and flag historical-point wording ("at S####", "the corpus had", "in August …") on
   ACROSS-TIME and UNORDERED records for audit.

## 14.5 Track separation (D-13)

Every cited S-id carries a mechanically computed track tag, from the directory mapping in
`KSME-20-P3A-TRACK-PROVENANCE-AUDIT.md`: TRACK-A-PHASE-MEASURE · TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED ·
TRACK-B-GAP-DISCOVERY · UNCLASSIFIED. Each object record carries `track_composition` (counts per tag).
An object whose evidence spans A and B is flagged MIXED and raises a `CROSS-TRACK` signal.

**Track-B rule (D-23, tightened in v1.1).** For an object whose earliest evidence is Track A, a Track-B source:

| May be cited as | May NOT be cited as |
|---|---|
| evidence of **later** discovery, extension or refinement: `later_support[]`, `later_refinement[]` (§14.3) | an `ESTABLISHED-*-BIRTH` or `MOVED` for the object |
| corroboration of a Track-A source, with its track tag shown | the **sole** basis of any status, FOUND, or dependency edge |
| contradiction: `contradicted_by[]`, feeding D4 only together with the Track-A statement it contradicts | the meaning the Track-A form *had* (the anti-projection test, §14.4) |
| a research signal of any type | |

A Track-B source may resolve an absence dimension only as `FOUND[S####]` with its track tag and
`relative_timing: LATER-THAN-FIRST-APPEARANCE` recorded, meaning "the corpus later supplied this", never "the object
always had this." What remains open under OMQ-07 is only the **directory mapping** (the MD-043 subcluster doubt),
not the rule.

---

# 16. AI INTERPRETATION BOUNDARY

1. AI output is always a **recommendation** with its evidence (v3.5 R20). It enters the ledger with
   `author_role: AI-AGENT` and never with a governance status.
1a. **AI is expected to do substantive research** (§9A): to notice, compare, abstract, apply domain knowledge,
   propose alternatives, hypothesise and challenge the method. Reticence is not a virtue here. Mislabelling is
   the failure mode, not boldness (G-12).
2. AI may not: decide pairs; merge, rename or split labels; add enum values or record kinds; promote a proposed
   topic into the seed; write to frozen artifacts; set `ACCEPTED`; set a research record to a governance outcome
   (only `ROUTED-TO-GOVERNANCE`); present a research record as SOURCE or place it in a layer-A field; read F-only
   or firewalled files; cite its own earlier output as SOURCE (v3.5 R8); change the method (§13.8 routes it).
3. DOMAIN-INTERPRETATION is labelled as such and is never the basis of a layer-A status. It is a normal input to
   the research register.
4. Every agent works from the persisted, hashed contract (§19.2). An agent that cannot follow the contract for a
   label records `ESCALATED` for that label and continues with the next.

## 16.5 Proposal vs acceptance (D-24, new in v1.1)

> **AI may propose a classification. A P3b record becomes ACCEPTED only through the defined sequence:
> verifier PASS (§20) → audit disposition complete (§21) → human acceptance entry (H-06, §25).**

Mechanics that make this unambiguous later:
1. Every record an agent writes to `ledger-p3b-r2/**/objects.jsonl` carries `record_status: PROPOSED`, and every
   AI-derived field carries `proposed_by: AI-AGENT`. That applies in particular to type_status,
   mathematical_status, layer, births, absence FOUND, dependency edges, and semantic_status rows 1–2. **These
   files are never authoritative**, however complete they look.
2. `ACCEPTED` exists in exactly one place: `32-RECONCILIATION-OBJECTS.jsonl`. Every record there carries
   `acceptance_ref` pointing to the `P3B-GOVERNANCE-LOG.md` entry, `verifier_ref` and `audit_ref`.
3. Nothing downstream (P4–P7, reports, the F-lane, research-signal testing that claims a P3b status) may consume
   a record that lacks `acceptance_ref`. Gate G-11 checks this (§20).
4. A human may accept a batch with named exceptions. Excepted labels stay PROPOSED and are listed in the log.

---

# 18. PROVENANCE REQUIREMENTS

**Mandatory on every P3b object record:** `working_label` · `batch_id` · `run_id` · `contract_sha256` ·
`input_manifest_sha256` · `tier` + causing `pair_id`s · every status with its cited `S####` and anchor or
quote, and the rule or reasoning used · `epistemic_class` per claim · `author_role` · `analysis_date` ·
`negative_label` for every absence · `track_composition` · `hindsight_dependency` (may be empty) ·
`what_says_this` / `what_would_make_this_wrong` per status · `record_status` (PROPOSED in the ledger) ·
`proposed_by` per AI-derived field · `pair_breakdown` (counts by relationship × basis; empty for Tier Z) ·
`semantic_status_note` (Tier Z) · `evidence_presentation: V1-PLUS-ROWS | ROW-BUNDLE-V2` (per H-04).
**AI provenance (v1.4, RC-11), on every object record, research record and audit record:** `model_id` (exact model
identifier and version), `generation_parameters` (as exposed by the runtime), `contract_sha256`, `run_id`. The same
contract on a different model is a **different experiment**, recorded as a different run.
**Additionally in `32-RECONCILIATION-OBJECTS.jsonl` only:** `acceptance_ref`, `verifier_ref`, `audit_ref`.

**Mandatory on every research record:** the §13.6 schema, including `historical_anchor` and `research_time`
kept in separate fields (§1C). **Mandatory on every object record (v1.2 addition):** the per-label `timeline`
(§14.3).

**Optional:** source path (derivable from S-id); free-text reviewer notes; cross-references to F-lane records
(EXTERNAL-LANE only).

Source path, commit and sha256 resolve through `02-FILES.jsonl` and `P3B-IDENTITY-MANIFEST.jsonl` (identity per v3.5 R11, resolved per Appendix A.3b); line numbers are never
anchors.

---

# 20. QUALITY GATES

| Gate | Checks | Mechanism |
|---|---|---|
| G-01 Corpus integrity | every cited S-id ∈ `02-FILES.jsonl`; no F-id; no firewalled file quoted | script |
| G-02 Identity integrity | exactly the assigned labels, each once; no unknown label; no renamed or merged label | script |
| G-03 Duplicate handling | a duplicate file (`08-OVERLAP-REGISTER.jsonl`) is never counted as independent corroboration (v3.5 R9) | script |
| G-04 Evidence completeness | every status meets §11.2 minimum evidence; every absence has a search record and negative label | script |
| G-05 Relationship classification | closed lists only (existing verifier's enums); semantic_status satisfies D1–D5 (§12.2.2) and, once decided, the H-11 choices; Tier Z records have `semantic_status: null` + note; `pair_breakdown` matches the consumed pairs exactly | script |
| G-06 Semantic interpretation | blind audit sample (§21) — disagreements individually dispositioned | AI audit + human |
| G-07 Provenance | all §18 mandatory fields present; contract hash matches | script |
| G-08 Hindsight | no birth, FOUND or CONTESTED rests only on SECONDARY-SYNTHESIS, PROVENANCE-UNRESOLVED or Track-B-over-Track-A sources; anti-projection answer present | script + audit |
| G-09 Register non-interference and status integrity | no research record altered a status or verdict; no record set to a governance outcome; every HYPOTHESIS/STRUCTURE-CANDIDATE/THEORY-CANDIDATE has a falsification condition, a disconfirmation plan, and `temporal_scope`; every A–D operation records population, method, completeness, termination bound and result (§13.10); **every STATUS satisfies §13.9a** (no confirmation by absence; no REFUTED from not-found; no EXTERNAL-THEORY item in a STATUS basis; SUPPORTED only with a DIFFERENTIATED comparison); every STRUCTURE-CANDIDATE has `relation_source`, RC-7b property statuses, a weakest-structure name and `competing_structures[]` (§13.11); every GAP has a §13.4 status; every SUGGESTION/DEFICIENCY has `execution_impact` ; `test_plan_sha256` unchanged between TEST-DEFINED and TESTED (§13.10a); `evidence_level` and `claim_type` present, and `evidence_level` equal to its recomputation from recorded results (§13.9b); `temporal_scope` equals its recomputation on the snapshot hashed at TEST-DEFINED (§14.4c rule 4); `population_basis` present on every search record and STATUS (§3.6) | **script** for presence, structure and recomputable fields; **audit** (§21 item 6) for the judgment conditions: §13.9a (3) "favours H", a "verified" counterexample, and fidelity to the DESCRIPTIVE/DISCOVERY mapping |
| G-12 Layer and time separation | no layer-B/C content (register ids, `research_time`, RESEARCH-OBSERVATION/SUGGESTION/HYPOTHESIS/THEORY-CANDIDATE text) in a layer-A field; no timeline point cites a register record as basis; every register record has both `historical_anchor` and `research_time`; `PROPOSED:*` topics each have a definition | script + audit (§21 item 5) |
| G-13 Cross-scale findings | every CROSS-OBJECT/CORPUS record carries the §9E mandatory content for its scale (participants with their own evidence, acceptance state and tier; `derived_from_records`; for CORPUS `participating_findings`, `underlying_objects`, `independent_occurrences` with exclusions, `exceptions`, `explanatory_content`); `generator_basis` DESCRIPTIVE/DISCOVERY; control comparison for DISCOVERY; recurrence counts use only §9E.3 independent occurrences (no SELF-DERIVED, no non-PRIMARY, no overlapping sets); `temporal_scope` present and historical-point wording flagged on ACROSS-TIME records; Tier-X participants → `exposure: TIER-X`; `P3B-CROSS-CANDIDATES.jsonl` and `P3B-PASS-SNAPSHOT.json` hashes match the pass record; every candidate in the approved plan dispositioned ; **no DESCRIPTIVE or CONTROL set counted as an occurrence** (Appendix A.8); control comparisons pooled per pre-registered generator × class, with the decision per §9E.2 item 6; `blinding_level` recorded | script |
| G-10 Reproducibility | re-running the scripts on the recorded input manifest reproduces the mechanical fields byte-for-byte | script |
| G-11 Acceptance integrity | every ledger record is `record_status: PROPOSED`; every record in `32-RECONCILIATION-OBJECTS.jsonl` has resolvable `acceptance_ref`, `verifier_ref`, `audit_ref`; no downstream artifact cites a P3b status lacking them; no `P3B-R1` record is cited as a P3b result | script |

**Batch fails** if any of G-01…G-05 or G-07…G-12 fails (G-13 applies to the S5a/S5b/S6a passes, which fail and re-run on the same terms), or if G-06 finds a disagreement dispositioned as a
protocol violation (not a judgment call).
**On failure:** the batch output is kept (never deleted), marked `FAILED` in the manifest with the failing gate,
and a new run (`OB####-R2.2`) is planned with the cause recorded. No partial acceptance of a failed batch.
**A batch can be accepted** when all gates pass and H-06 is recorded.

---
