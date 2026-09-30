#!/usr/bin/env python3
"""P3b S5 PREPARATION (plan v2.3.2 §B, §O items O-4/O-5/O-6; G-LOG-0041). Dispatches nothing.

Modes
  --build                 compute weights, predicted loads, the deterministic packing (§9.4 weight within tier, H-12
                          manifest-time limits), every slice's canonical hash, and the S5 agent contract; write
                          `_batch_manifest_p3b_r2.jsonl` (§19.5 header + one line per batch) and
                          `prompts/<stamp>_p3b-agent-contract-r2.md`. Slices are NOT written (about 157 MB); their
                          hashes are frozen in the manifest.
  --materialize OB####    at dispatch time only: rebuild that batch's slices, verify each against the manifest hash,
                          quarantine-check them, and write `_batch_input_r2/s5/OB####/<label>.json` plus the §19.5
                          batch snapshot `_batch_input_r2/s5/OB####/P3B-BATCH-SNAPSHOT.json`. Refuses on any mismatch.
  --emit=<dir>            with --build or --materialize: write to <dir> (outside the repository) instead, for review.
  --check                 recompute the manifest in memory and compare byte-for-byte with the committed one.

Frozen inputs checked: v1.7, plan v2.3.2, hub list; the seal is asserted SEALED at start and end. Reads only allowlisted,
discovery-filtered inputs through p3b_s5_common. Hold-out lists are never printed; slices are checked with
`quarantine_hits` (counts only).

Approved parameters (G-LOG-0041; never changed here):
  L_max 5 · checklist ≤ 4 · Σ predicted dispositions ≤ 4,306 · Σ predicted stage-2 bytes ≤ 8,633,127 ·
  Σ predicted step-1 bytes ≤ 1,801,465 (manifest-time packing limits, not verifier gates) · hub batches: label,
  checklist and step-1 limits only · batch ids from OB0004 · run id <batch>-R2 · re-run <batch>-R2.2 (§20).
"""
import collections
import datetime
import importlib.util
import json
import os
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
_spec = importlib.util.spec_from_file_location("p3b_s5_common", os.path.join(_HERE, "p3b_s5_common.py"))
c = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(c)
s3, CR, canon, sha256_bytes = c.s3, c.CR, c.canon, c.sha256_bytes

L_MAX, CHECKLIST_MAX, DISP_MAX, S2_MAX, S1_MAX = 5, 4, 4306, 8633127, 1801465
FIRST_BATCH = 4
MANIFEST = "_batch_manifest_p3b_r2.jsonl"
SLICE_ROOT = "_batch_input_r2/s5"
# Contract revision (§26 change control). Revision 1 = prompts/20260925_0351 (OB0004-R2, FAILED under it: G-LOG-0047).
# Revision 2 corrects two contract defects exposed by that run (P1-gap generation_parameters; hit_key type) and states the
# run-id placeholder for re-runs; its slices are written under a revision-specific root so revision-1 slices stay as run.
# Revision 3 (G-LOG-0050) keeps revision 2 and makes whole-file reading observable: reading is not delivery, the paged
# reader proves it, the CONTRACT-DEVIATION route is explicit, and the OMQ-14 step-1 scope is stated (OB0004-R2.2 audit).
CONTRACT_REVISION = 3
CONTRACT_SUPERSEDES = {"path": "prompts/20260925_1056_p3b-agent-contract-r2.md",
                       "sha256": "7a78c435ea7200c6af17c7b62fd037e0844c5ca98fe1adf5e3ed73dc8a589ef7",
                       "manifest_output_sha256": "0504282b10e794f807937f6ea64be4d6e10d0647377da42def008bb41bcbfdf3"}
ACTIVE_SLICE_ROOT = f"{SLICE_ROOT}/rev{CONTRACT_REVISION}"
SCRATCH_ROOT = "/tmp/p3b-s5-scratch"
FLAGS_EXTRACT = "_batch_input_r2/s5/09-ORCHESTRATOR-FLAGS.discovery.md"
META_FIELDS = ("status", "provenance", "best_historical_date", "best_historical_date_basis", "explicit_dates",
               "mtime_block", "file_mtime")
POSITIVE_BASES = ("CORROBORATED", "INFERRED")                          # H-11d option (i)
TIER_Z_NOTE = "NO-PAIR-EVIDENCE — out of domain (H-11a option iii)"     # H-11a option (iii)
TRACK_MAPPING_2 = {"TRACK-A-PHASE-MEASURE": "A", "TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED": "B",
                   "TRACK-B-GAP-DISCOVERY": "B", "UNCLASSIFIED": "UNCLASSIFIED"}   # OMQ-07 mapping (2)

# The §19.2 contract sections (the same list as the S4 contracts), copied verbatim from v1.7. Defined here rather than
# imported from the pre-seal S4 pilot script, so S5 never loads a module that reads census or seal lists (G-LOG-0042).
CONTRACT_SECTIONS = [
    "## 1A Research-first principle", "## 1B Three research output layers", "## 1C Historical time vs research time",
    "## 1D Discovery is allowed; canonicalization is not", "## 1E Openness",
    "## 3.4 Firewalled and secondary files inside the boundary",
    "## 9.8 Per-label procedure", "## 9A Researcher role", "## 9B Research discovery loop",
    "## 9C Research checklist",
    "# 11. EVIDENCE MODEL", "# 12. RELATIONSHIP MODEL", "# 13. RESEARCH REGISTER", "# 14. TEMPORAL / HINDSIGHT CONTROLS",
    "# 16. AI INTERPRETATION BOUNDARY", "# 18. PROVENANCE REQUIREMENTS", "# 20. QUALITY GATES",
]


def extract(lines, heading_prefix):
    """Lines from the heading starting with heading_prefix up to (excluding) the next heading of the same or a higher
    level (identical to the S4 pilot's extractor)."""
    start = next(i for i, l in enumerate(lines) if l.startswith(heading_prefix))
    level = len(heading_prefix) - len(heading_prefix.lstrip("#"))
    end = len(lines)
    for j in range(start + 1, len(lines)):
        m = re.match(r"^(#+) ", lines[j])
        if m and len(m.group(1)) <= level:
            end = j
            break
    return lines[start:end]

ENUMS = {
    "type_status": ["CLOSED", "INCOMPLETE", "UNTYPED", None],
    "mathematical_status": ["CONSISTENT", "INCONSISTENT", "UNDER-SPECIFIED", "UNDECIDABLE-FROM-CORPUS", "NOT-APPLICABLE", None],
    "primary_layer": ["FOUNDATIONAL", "DERIVED", "OPERATIONAL", "META-THEORETICAL", "LAYER-UNRESOLVED"],
    "secondary_roles[]": ["FOUNDATIONAL", "DERIVED", "OPERATIONAL", "META-THEORETICAL"],
    "dependency_edges[].kind": ["DEFINITIONAL", "DERIVATIONAL", "USAGE", "EXPLANATORY", "VALIDATION", "GOVERNANCE"],
    "births.<kind>": ["ESTABLISHED-<KIND>-BIRTH[S####]", "MOVED[S####, quote]", "UNORDERED-BLOCK[BULK-nn]",
                      "BIRTH-UNRESOLVED-MTIME-ONLY[S####]", "ESCALATED[TIMESTAMP-ANOMALY: reason]",
                      "ESCALATED[G-08: sole basis S#### is SECONDARY-SYNTHESIS|PROVENANCE-UNRESOLVED|UNKNOWN]",
                      "NOT-EVIDENCED-IN-CAPTURE"],
    "absences.<dimension>.resolution": ["FOUND", "GENUINELY-UNDEFINED-AFTER-CENSUS", "FIREWALL-BLOCKED", "ESCALATED"],
    "escalations[].reason": ["LOAD", "SCHEMA-LIMITATION", "TIMESTAMP-ANOMALY", "G-08", "CENSUS-READING-DISAGREEMENT",
                             "CONTRACT-DEVIATION", "OTHER"],
    "stage2_dispositions[].by_dimension.<dimension>": ["FOUND", "FALSE-HIT", "UNSUPPLIED-DIMENSION", "ESCALATED"],
    "stage2_dispositions[].method": ["WHOLE-FILE", "STAGE-2A-2B"],
    "timeline[].order": ["ORDERED", "UNORDERED-BLOCK"],
    "timeline[].date_applies_to_file": ["CONFIRMED", "NOT-CONFIRMED", "NOT-ASSESSED"],
    "timeline[].change_vs_previous": ["FIRST", "RESTATES", "EXTENDS", "NARROWS", "CHANGES-DEFINITION", "CHANGES-TYPE",
                                      "CHANGES-TERM", "CONTRADICTS", "RETRACTS", "NOT-COMPARABLE"],
    "semantic_status": [None, "CONTESTED", "HOMONYM-SPLIT", "RECONCILED(<relationship>/<basis>, type <tc>)[; …]",
                        "IDENTITY-UNWITNESSED"],
    "semantic_status_rule": ["ROW-0", "ROW-1", "ROW-2", "ROW-3", "ROW-4"],
    "record_status": ["PROPOSED"],
    "lifecycle_stage (register)": ["OBSERVED", "ANALYSED", "HYPOTHESIS-STATED", "TEST-DEFINED"],
    "gap_status (register kind GAP)": ["NOT-FOUND-IN-CAPTURE", "NOT-FOUND-BOUNDED", "NOT-FOUND-AFTER-CENSUS",
                                       "NOT-FOUND-LOAD-ESCALATED"],
    "tier": ["U", "Z"],
    "lens (register)": ["MATHEMATICAL", "STATISTICAL", "DDD", "LOGIC", "EPISTEMIC", "CHRONOLOGICAL", "MIXED"],
    "scale (register)": ["OBJECT"],
    "output_layer (register)": ["B", "C"],
}

SCHEMA = {
    "object_record": {
        "working_label": "str", "batch_id": "str", "run_id": "str", "contract_sha256": "str",
        "input_manifest_sha256": "str", "tier": "U|Z", "tier_causing_pair_ids": "[str]", "hub": "bool (copied from the slice)",
        "timeline": "[{source_id, historical_position, date_basis, order, states:{epistemic_class: SOURCE|INFERENCE, quote|step}, change_vs_previous, date_applies_to_file}]  (F1: a point whose date is NOT-CONFIRMED keeps its order value with date_applies_to_file NOT-CONFIRMED)",
        "timeline_summary": "{first_lexical, first_conceptual, first_formal, first_operational, first_governance, later_support[], later_refinement[], contradicted_by[], rejected_by[], current_lifecycle}",
        "semantic_status": "enum (§12.2.4 with H-11a–d; Tier Z: null)", "semantic_status_rule": "ROW-0..ROW-4",
        "semantic_status_note": "str (Tier Z: the H-11a note)", "semantic_evidence": "{d4:[{source_id, quote}], d5:[{source_id, quote}]}",
        "pair_breakdown": "{<relationship>|<basis>: count}",
        "type_status": "enum, or null with an `escalations` entry for it", "mathematical_status": "enum, or null with an `escalations` entry for it",
        "primary_layer": "enum", "secondary_roles": "[enum]",
        "escalations": "[{field, reason: enum, detail, hub_record?}]  (hub: one LOAD entry per hit-bearing absence dimension, carrying the slice's hub_record; a field the closed values cannot represent: reason SCHEMA-LIMITATION plus a SCHEMA-LIMITATION register record)",
        "births": "{lexical, conceptual, formal, operational, governance: enum string}",
        "absences": "{<dimension>: {resolution: enum, negative_label, population_basis, supplied_by: {source_id, anchor, quote} | null, reason, relative_timing?: LATER-THAN-FIRST-APPEARANCE (only for a FOUND from a Track-B source over a Track-A object, §14.5)}}",
        "stage2_dispositions": "[{source_id, hit_kind: raw|ledger, hit_key: EXACTLY the stage-1 hit's key as in the slice's LABEL-HITS record, with the same JSON type (hit_kind raw: the integer character offset, never a string; hit_kind ledger: the anchor value unchanged), term_index: integer, method: enum, by_dimension: {<every DIMENSION of the label>: enum}, offsets?: [int], reason}]  -- one entry per DISTINCT stage-1 hit key; for method STAGE-2A-2B, `offsets` lists the Stage-2A offsets (from the read log) the disposition covers, and every logged occurrence is covered exactly once (F3). Hub labels: EMPTY (stage 2 not performed)",
        "census_reading_disagreements": "[{dimension, source_id, anchor, quote, stage1_terms}]",
        "dependency_edges": "[{target_label, kind: enum, source_id, quote}]",
        "status_basis": "{<status field>: {sources:[S####], rule_or_reasoning, epistemic_class, what_says_this, what_would_make_this_wrong, anti_projection}}",
        "track_composition": "{<track_tag>: count}", "hindsight_dependency": "[str]", "superseded_by_sources": "[{source_id, position}]",
        "proposed_by": "AI-AGENT", "evidence_presentation": "V1-PLUS-ROWS", "record_status": "PROPOSED",
        "model_id": "exact served id string", "generation_parameters": "str|obj", "author_role": "str", "analysis_date": "YYYY-MM-DD",
        "checklist_examined": "[int] (only if in_checklist)",
    },
    "register_record": {
        "rs_id": "<run_id>:<batch_id>:<n>", "batch_id": "str", "working_label": "str", "kind": "§13.2 kind", "topics": "[str]",
        "lens": "enum", "scale": "OBJECT", "statement": "str", "epistemic_class": "§11.1", "output_layer": "B|C",
        "supporting_evidence": "[{source_id, anchor|quote, evidence_kind: CORPUS|EXTERNAL-THEORY}]",
        "historical_anchor": "[{source_id, historical_position, date_basis}]", "research_time": "ISO datetime",
        "run_id": "str", "contract_sha256": "str", "model_id": "str", "generation_parameters": "str|obj", "origin": "P3B",
        "related_labels": "[str]", "derived_from_records": "[rs_id]", "lifecycle_stage": "enum", "author_role": "str",
        "gap_status": "enum (kind GAP only)",
        "proposed_topic_definitions": "{PROPOSED:<topic>: definition} (only if a PROPOSED topic is used)",
        "profile (HYPOTHESIS / STRUCTURE-CANDIDATE)": "falsification_condition, validation_question, competing_hypotheses[], contradicting_evidence[] with search record, temporal_scope, claim_type, evidence_level, test_plan_sha256 (if TEST-DEFINED); STRUCTURE-CANDIDATE adds §13.11 fields",
    },
    "p1_gap_record": {
        "gap_id": "<run_id>:<batch_id>:G<n>", "batch_id": "str", "working_label": "str", "source_id": "str", "anchor": "str",
        "quote": "str", "what_p1_missed": "str", "dimension": "str|null", "found_via": "STAGE-2|STEP-1-READING",
        "run_id": "str", "contract_sha256": "str", "model_id": "str", "generation_parameters": "str|obj",
    },
}

PREAMBLE = """# P3b AGENT CONTRACT — S5 production batches (run id `<run_id>`: `<batch_id>-R2`, or `<batch_id>-R2.<n>` for a re-run)

**Contract revision {revision}** (§26 change control, G-LOG-0048 and G-LOG-0050). Supersedes `{supersedes}` (sha256
`{supersedes_sha}`) for batches dispatched from now on; batches run under earlier revisions keep their contract.
Revision 2 corrections stand: (1) P1-gap records carry `generation_parameters`, as every record does (§B; schema E
`p1_gap_record`); (2) `hit_key` keeps the stage-1 hit's exact key and JSON type (an integer offset for raw hits);
(3) `<run_id>` is the run id you are dispatched with; every path, reader call and record uses it.
Revision 3 makes whole-file reading observable; the reading rules themselves are unchanged:
(4) **Reading is not delivery.** A file may be recorded `WHOLE-FILE`, or ground a FOUND (§11.4: a FOUND always needs
a whole-file reading), a birth, or a timeline point classified NARROWS, CHANGES-DEFINITION, CHANGES-TYPE,
CHANGES-TERM, CONTRADICTS or RETRACTS, **only if you consumed the complete file**. A
GENUINELY-UNDEFINED-AFTER-CENSUS rests on its stage-2 dispositions, each made by a complete whole-file reading or,
for a term of at most 2 code points, through Stage 2A/2B as §11.4 permits (unchanged). Whole-file reading is done with the
paged reader (§A.2): every page 1..N of the file, each read directly as the command's output (never redirected to a
file, never sampled). The verifier re-computes every page hash and **fails the run** if any page of such a file is
missing. Saving reader output and inspecting parts of it is not reading.
(5) **If you cannot complete a required whole-file reading**, record an `escalations` entry with reason
`CONTRACT-DEVIATION` whose `detail` names the S-id and the reason; the dependent field or dimension resolves `ESCALATED` (never
FOUND, never GENUINELY-UNDEFINED-AFTER-CENSUS, never `WHOLE-FILE`). This is an acceptable outcome; a `WHOLE-FILE`
claim for a file you did not read completely is a protocol violation.
(6) **OMQ-14 step-1 scope:** the whole-file reading of step 1 covers every source of the label's rows whose row
`types` include CONTRADICTION, CORRECTION or RETRACTION, in addition to the sources carrying a birth or a change of
definition, type or meaning.

**Governing documents:** frozen P3b core **v1.7** (`{protocol}`, sha256 `{protocol_sha}`), H-19 addendum v1.5 (hold-out
sealed as {seal_id}), approved S5 plan v2.3.2 (`{plan}`, sha256 `{plan_sha}`), governance log up to G-LOG-0048. The
sections under "PROTOCOL TEXT" are copied verbatim from v1.7, and the core governs. Sections D (closed values) and E
(exact output schema) are binding. This preamble states how S5 applies the core.

## A. Inputs, reading, isolation

1. **Inputs:** this contract and your batch's slice files (`{slice_root}/<batch_id>/<working_label>.json`). A slice holds
   the bundle, `family_md`, the stage-1 search records, `stage2_files`, the P3a pair records, `source_tracks` (with the
   OMQ-07 mapping), `source_meta` (02-FILES provenance and dates), `in_checklist`, **`hub` and `hub_record`**,
   `semantic_status_mechanical` (§12.2.4 rows 0/3/4, computed by script) and `quarantine` (counts of input records
   withheld under addendum §6; a withheld record is not evidence of absence and is never looked up). The discovery-filtered orchestrator flags
   extract is `{flags}` (known corpus failure modes; §9.2). Nothing else is input.
2. **Corpus text:** `python3 docs/knowledgeos/chronological-read/scripts/p3b_read_source.py --run <run_id> --batch
   <batch_id> --label <working_label> --step <1|7|10> S#### [...]`. For a hit term of ≤ 2 code points (Stage 2A):
   `python3 docs/knowledgeos/chronological-read/scripts/p3b_stage2a_scan.py --run <run_id> --batch <batch_id>
   --label <working_label> --step 7 --term <term> S#### [...]`. **Whole-file reading (revision 3):** `python3
   docs/knowledgeos/chronological-read/scripts/p3b_read_source.py --run <run_id> --batch <batch_id> --label
   <working_label> --step <1|7|10> --page <k> S####` for every k = 1..N (the header of each page states `page k/N`;
   pages are 20,000 characters). Both tools log themselves and refuse sealed files. For an S-id
   without `source_meta`, record its provenance as unknown; never look it up.
3. **Never** open, list or search any other file or directory: not other batches' slices, ledgers, logs, governance
   files, S3 outputs, the seal, the hold-out, or another agent's scratch.
4. **Scratch:** only `{scratch_root}/<batch_id>/`.
5. **No helper agents, forks or sub-agents.**
6. **Reading scope (OMQ-14):** rows in historical order; whole-file reading of the sources carrying a birth, a change of
   definition, type or meaning, or a contradiction (step 1). **Stage 2 covers every file in `stage2_files`** (whole-file
   reading, or Stage 2A/2B for terms of ≤ 2 code points). A FOUND always needs a whole-file reading with the reader. The
   absence procedure is never thinned (§19.4).
7. **Hub labels (`"hub": true`; v1.7 §11.4):** stage 2 — including Stage 2A and 2B — is **not performed**. Every absence
   dimension whose stage-1 result has hits resolves `ESCALATED`, with an `escalations` entry `{{field:
   "absences.<dimension>", reason: "LOAD", hub_record: <the slice's hub_record>}}`. Never FOUND, never
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

- **Population basis:** `{basis}` on every search record, absence and negative label.
- **semantic_status (§12.2.4, H-11a–d decided):** Tier Z → `null`, note `"{tz_note}"`, rule `ROW-0`. Tier U: apply rows
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
  {model}; its context-window variants are the same model).

## C. Output files (canonical JSON lines: sorted keys, compact separators, UTF-8)

- `docs/knowledgeos/chronological-read/ledger-p3b-r2/<run_id>/objects.jsonl` — one object record per label;
- `.../<run_id>/register.jsonl` — research records; `.../<run_id>/p1-gap-capture.jsonl` — P1-gap records.

Write nothing else. Every S-id you cite must be in your slice or in a non-refused read-log entry of your batch. Cite
each S-id individually: **never write an S-id range** (such as `S0101–S0120` or `S0101..S0120`); a range is read as
citing every S-id in its interval and fails the batch's quarantine check (G-LOG-0045 item 3). Every
record carries `run_id`, `batch_id`, `contract_sha256` and `model_id`.

## D. Closed values (binding)

```json
{enums}
```

## E. Exact output schema (binding; the verifier validates it)

```json
{schema}
```

---

# PROTOCOL TEXT (verbatim from the frozen core v1.7)

"""


def predicted_loads(pop, hits, dims, size, bi):
    out = {}
    for lab in pop:
        keys, files = set(), set()
        for kind in ("ledger_hits", "raw_hits"):
            for s, occ in ((hits.get(lab) or {}).get(kind) or {}).items():
                files.add(s)
                keys.update((s, kind, json.dumps(x)) for x in occ)
        rowsrc = {x["source_id"] for x in bi.get(lab, {}).get("rows", [])}
        out[lab] = {"dispositions": len(keys) * len(dims.get(lab, [])), "stage2_bytes": sum(size.get(s, 0) for s in files),
                    "stage1_bytes": sum(size.get(s, 0) for s in rowsrc)}
    return out


def blob_sizes():
    man = {r["source_id"]: r for r in c.jl("P3B-IDENTITY-MANIFEST.jsonl") if "source_id" in r}
    specs = [(s, r["blob_spec"]) for s, r in man.items() if r.get("blob_spec_type") == "GIT-OBJECT"]
    import subprocess
    got = subprocess.run(["git", "cat-file", "--batch-check=%(objectsize)"], cwd=CR, capture_output=True, text=True,
                         input="\n".join(b for _, b in specs) + "\n").stdout.split("\n")
    return {s: int(o) for (s, _), o in zip(specs, got) if o.strip().isdigit()}


def pack(labels, plan, W, load, hub_batch):
    bins = []
    for lab in sorted(labels, key=lambda x: (-W[x], x)):
        chk = 1 if plan[lab]["in_checklist"] else 0
        for bn in bins:
            if len(bn["labels"]) >= L_MAX or bn["checklist"] + chk > CHECKLIST_MAX:
                continue
            if bn["stage1_bytes"] + load[lab]["stage1_bytes"] > S1_MAX:
                continue
            if not hub_batch and (bn["dispositions"] + load[lab]["dispositions"] > DISP_MAX
                                  or bn["stage2_bytes"] + load[lab]["stage2_bytes"] > S2_MAX):
                continue
            bn["labels"].append(lab)
            bn["checklist"] += chk
            for k in ("dispositions", "stage2_bytes", "stage1_bytes"):
                bn[k] += load[lab][k] if (k == "stage1_bytes" or not hub_batch) else 0
            break
        else:
            bins.append({"labels": [lab], "checklist": chk, "stage1_bytes": load[lab]["stage1_bytes"],
                         "dispositions": 0 if hub_batch else load[lab]["dispositions"],
                         "stage2_bytes": 0 if hub_batch else load[lab]["stage2_bytes"]})
    return bins


def mechanical_semantic(tier, pairs):
    """§12.2.4 rows 0/3/4 with H-11a (iii), H-11c (i), H-11d (i). Rows 1/2 need reading and are the agent's."""
    if tier == "Z" or not pairs:
        return {"rule": "ROW-0", "value": None, "note": TIER_Z_NOTE}
    if all(p.get("basis") in POSITIVE_BASES for p in pairs):
        return {"rule": "ROW-3", "note": None,
                "value": "; ".join(f"RECONCILED({p['relationship']}/{p['basis']}, type {p.get('type_compatibility')})"
                                   for p in sorted(pairs, key=lambda x: x["pair_id"]))}
    return {"rule": "ROW-4", "value": "IDENTITY-UNWITNESSED", "note": None}


class Context:
    def __init__(self):
        c.verify_frozen()
        c.assert_sealed()
        self.plan = c.s5_population()
        self.hubs = c.hubs()
        self.hits, self.dims = c.discovery_search()
        self.bi = c.bundle_index()
        self.fm = c.files_meta()
        self.pairs_all = {p["pair_id"]: p for p in c.discovery_pairs()}
        self.size = blob_sizes()
        dv = json.load(open(os.path.join(CR, "20-FAMILIES", "_derived.json"), encoding="utf-8"))
        self.objs = dv["reconciliation_objects"]
        self.rows_lines = [l for l in open(os.path.join(CR, "03-CONTRIBUTIONS.jsonl"), "rb").read().split(b"\n") if l.strip()]
        self.files_lines = [l for l in open(os.path.join(CR, "02-FILES.jsonl"), "rb").read().split(b"\n") if l.strip()]
        self.load = predicted_loads(self.plan, self.hits, self.dims, self.size, self.bi)
        pc = collections.Counter()
        for p in self.pairs_all.values():
            pc[p["a"]] += 1
            pc[p["b"]] += 1
        self.W = {lab: self.bi.get(lab, {}).get("distinct_rows", 0) + 3 * pc[lab] + 2 * len(self.dims.get(lab, []))
                  for lab in self.plan}
        self.quarantined_family_md = set()
        self.quarantined_records = {}                             # label -> withheld-record counts (idempotent)
        self.inputs = ["P3B-SAMPLE-PLAN.jsonl", c.HUBS, "P3B-DISCOVERY-SEARCH.jsonl",
                       "_batch_input_r2/bundle_index_discovery.jsonl", "02-FILES.jsonl", "03-CONTRIBUTIONS.jsonl",
                       "31-RECONCILIATION-PAIRS.jsonl", "20-FAMILIES/_derived.json", "P3B-IDENTITY-MANIFEST.jsonl",
                       "P3B-HOLDOUT-SEAL.json", c.PROTOCOL, c.PLAN]
        c.check_inputs(self.inputs)

    def batches(self):
        out = []
        for hub_batch in (False, True):
            for tier in ("U", "Z"):
                labs = [l for l in self.plan if self.plan[l]["tier"] == tier and (l in self.hubs) == hub_batch]
                for bn in pack(labs, self.plan, self.W, self.load, hub_batch):
                    bn.update({"tier": tier, "hub_batch": hub_batch})
                    out.append(bn)
        for i, bn in enumerate(out):
            bn["batch_id"] = f"OB{FIRST_BATCH + i:04d}"
            bn["run_id"] = bn["batch_id"] + "-R2"
        return out

    def slice(self, lab, batch_id, contract_sha, input_manifest_sha, sets):
        bundle, ok_b, probs = s3.materialize_bundle(self.bi[lab], self.rows_lines, self.files_lines, self.objs)
        if not ok_b:
            raise c.S5Error(f"bundle {lab}: {probs[:3]}")
        fam_path = os.path.join(CR, "20-FAMILIES", f"{lab}.md")
        if os.path.exists(fam_path):
            c.check_inputs([fam_path], purpose=c.FAMILY_MD_PURPOSE)           # G-LOG-0045 item 1 (scoped allowlist)
        fam = open(fam_path, encoding="utf-8", newline="").read() if os.path.exists(fam_path) else ""
        fam_status = "PRESENT" if fam else "MISSING"
        if fam and any(c.quarantine_hits(fam, lab, sets)):         # addendum §6: a quarantined input never feeds discovery
            fam, fam_status = "", "QUARANTINED (addendum §6)"
            self.quarantined_family_md.add(lab)
        ptouch = sorted({p["pair_id"] if isinstance(p, dict) else p for p in self.objs.get(lab, {}).get("pairs_touching") or []})
        pairs = [self.pairs_all[p] for p in ptouch if p in self.pairs_all]
        # addendum §6 record-level quarantine (G-LOG-0045 item 3): a pair, source or reconciliation-object entry that
        # cites a hold-out file (explicitly or through an S-id range) is withheld; recorded as counts only.
        q = lambda x: any(c.quarantine_hits(x if isinstance(x, str) else canon(x), lab, sets))
        kept = [p for p in pairs if not q(p)]
        rec = bundle["reconciliation_object"]
        rec_pt = (rec or {}).get("pairs_touching") if isinstance(rec, dict) else None
        rec_kept = [p for p in rec_pt if not q(p)] if isinstance(rec_pt, list) else None
        src_kept = [s for s in bundle["sources_verbatim"] if not q(s)]
        quarantine = {"p3a_pairs_withheld": len(pairs) - len(kept),
                      "reconciliation_pairs_withheld": len(rec_pt) - len(rec_kept) if rec_kept is not None else 0,
                      "sources_withheld": len(bundle["sources_verbatim"]) - len(src_kept)}
        if any(quarantine.values()):
            if rec_kept is not None:
                bundle["reconciliation_object"] = dict(rec, pairs_touching=rec_kept)
            bundle["sources_verbatim"] = src_kept
            pairs = kept
            self.quarantined_records[lab] = quarantine
        search = ([self.hits[lab]] if lab in self.hits else []) + self.dims.get(lab, [])
        sl = {"working_label": lab, "batch_id": batch_id, "run_id": batch_id + "-R2", "contract_sha256": contract_sha,
              "input_manifest_sha256": input_manifest_sha, "tier": self.plan[lab]["tier"],
              "in_checklist": self.plan[lab]["in_checklist"], "population_basis": c.POPULATION_BASIS,
              "bundle": bundle, "family_md": fam, "family_md_status": fam_status,
              "family_md_sha256": sha256_bytes(fam.encode("utf-8")),
              "source_tracks": {s["source_id"]: {"track_tag": s["track_tag"],
                                                 "d23_track": TRACK_MAPPING_2.get(s["track_tag"], "UNCLASSIFIED")}
                                for s in self.bi[lab]["sources"]},
              "search_records": search,
              "stage2_files": sorted({s for r in search if r["record"] == "LABEL-HITS"
                                      for s in list(r.get("raw_hits") or {}) + list(r.get("ledger_hits") or {})}),
              "p3a_pairs": pairs, "pairs_touching_missing": [p for p in ptouch if p not in self.pairs_all],
              "semantic_status_mechanical": mechanical_semantic(self.plan[lab]["tier"], pairs),
              "hub": lab in self.hubs, "hub_record": self.hubs.get(lab), "quarantine": quarantine}
        text_ids = canon(sl)
        _, HF, _ = sets
        sids = sorted(set(re.findall(r"\bS\d{4}\b", text_ids)) - HF)
        sl["source_meta"] = {s: {k: self.fm[s].get(k) for k in META_FIELDS} for s in sids if s in self.fm}
        text = canon(sl)
        nl, nf = c.quarantine_hits(text, lab, sets)
        if nl or nf:
            raise c.S5Error(f"slice {lab} fails the quarantine check ({nl} label names, {nf} file ids)")
        return text


def build_contract(stamp):
    plines = open(os.path.join(CR, c.PROTOCOL), encoding="utf-8").read().split("\n")
    parts = ["\n".join(extract(plines, h)).rstrip() + "\n" for h in CONTRACT_SECTIONS]
    text = PREAMBLE.format(protocol=c.PROTOCOL, protocol_sha=c.PROTOCOL_SHA256, seal_id=c.SEAL_ID, plan=c.PLAN,
                           plan_sha=c.PLAN_SHA256, slice_root="docs/knowledgeos/chronological-read/" + ACTIVE_SLICE_ROOT,
                           revision=CONTRACT_REVISION, supersedes=CONTRACT_SUPERSEDES["path"],
                           supersedes_sha=CONTRACT_SUPERSEDES["sha256"],
                           flags="docs/knowledgeos/chronological-read/" + FLAGS_EXTRACT, scratch_root=SCRATCH_ROOT,
                           basis=c.POPULATION_BASIS, tz_note=TIER_Z_NOTE, model=c.MODEL_ID,
                           enums=json.dumps(ENUMS, indent=1, ensure_ascii=False),
                           schema=json.dumps(SCHEMA, indent=1, ensure_ascii=False)) + "\n".join(parts)
    return f"prompts/{stamp}_p3b-agent-contract-r2.md", text


def compute(stamp=None, contract_rel=None):
    ctx = Context()
    sets = c.holdout_sets()
    if contract_rel is None:
        contract_rel, contract = build_contract(stamp)
    else:
        contract = open(os.path.join(CR, contract_rel), encoding="utf-8").read()
    contract_sha = sha256_bytes(contract.encode("utf-8"))
    input_hashes = {i: c.sha256_file(i) for i in ctx.inputs}
    input_manifest_sha = sha256_bytes(canon(input_hashes).encode())
    batches = ctx.batches()
    lines = []
    for bn in batches:
        sl_hash = {lab: sha256_bytes(ctx.slice(lab, bn["batch_id"], contract_sha, input_manifest_sha, sets).encode())
                   for lab in bn["labels"]}
        lines.append({"batch_id": bn["batch_id"], "run_id": bn["run_id"], "tier": bn["tier"], "hub_batch": bn["hub_batch"],
                      "labels": bn["labels"], "checklist": bn["checklist"],
                      "in_checklist": {l: bool(ctx.plan[l]["in_checklist"]) for l in bn["labels"]},
                      "weights": {l: ctx.W[l] for l in bn["labels"]},
                      "predicted": {k: bn[k] for k in ("dispositions", "stage2_bytes", "stage1_bytes")},
                      "slice_sha256": sl_hash, "contract_sha256": contract_sha, "input_manifest_sha256": input_manifest_sha})
    body = "\n".join(canon(x) for x in lines) + "\n"
    return ctx, contract_rel, contract, contract_sha, input_hashes, input_manifest_sha, batches, body, lines


R7_ENTRY_FIELDS = ("run_id", "slice_sha256", "rev3_slice_sha256", "r7_plan_sha256", "legacy_ledger_sha256", "legacy_dirs")


def r7_base_consistency(base_rows, r7_rows):
    """G-LOG-0102: an R7 production manifest is consistent with its revision-3 base iff the batches and order are the
    same, each run id is exactly B-R2 → B-R7, the recorded rev3 slice hashes equal the base's, and every other entry
    field is identical. -> violations."""
    F = []
    if [r["batch_id"] for r in base_rows] != [r["batch_id"] for r in r7_rows]:
        return ["R7 manifest batch ids or order differ from the revision-3 base"]
    for b, r in zip(base_rows, r7_rows):
        bid = b["batch_id"]
        if b.get("run_id") != f"{bid}-R2" or r.get("run_id") != f"{bid}-R7":
            F.append(f"{bid}: run_id {b.get('run_id')!r} → {r.get('run_id')!r} is not B-R2 → B-R7")
        if r.get("rev3_slice_sha256") != b.get("slice_sha256"):
            F.append(f"{bid}: recorded rev3 slice hashes ≠ the base's")
        if {k: v for k, v in r.items() if k not in R7_ENTRY_FIELDS} != {k: v for k, v in b.items() if k not in R7_ENTRY_FIELDS}:
            F.append(f"{bid}: entry differs from the base beyond the R7 fields")
    return F


def write_manifest(root, ctx, contract_rel, contract_sha, input_hashes, input_manifest_sha, body, lines):
    labs = [l for x in lines for l in x["labels"]]
    hdr = c.header(__file__, ctx.inputs,
                   {"L_max": L_MAX, "checklist_max": CHECKLIST_MAX, "dispositions_max": DISP_MAX,
                    "stage2_bytes_max": S2_MAX, "stage1_bytes_max": S1_MAX, "first_batch": FIRST_BATCH,
                    "packing": "§9.4 weight row_count+3·pair_count+2·|absences|, first-fit decreasing within tier; hub batches separate (no stage-2 limits)"},
                   body, {"artifact": MANIFEST, "contract": {"path": contract_rel, "sha256": contract_sha,
                                                              "sections_verbatim": CONTRACT_SECTIONS,
                                                              "revision": CONTRACT_REVISION,
                                                              "supersedes": CONTRACT_SUPERSEDES},
                          "slice_root": ACTIVE_SLICE_ROOT,
                          "input_manifest_sha256": input_manifest_sha,
                          "quarantined_family_md": sorted(ctx.quarantined_family_md),
                          "quarantined_records": dict({"labels": len(ctx.quarantined_records)},
                                                      **{k: sum(v[k] for v in ctx.quarantined_records.values()) for k in
                                                         ("p3a_pairs_withheld", "reconciliation_pairs_withheld",
                                                          "sources_withheld")}),
                          "counts": {"batches": len(lines), "nonhub_batches": sum(1 for x in lines if not x["hub_batch"]),
                                     "hub_batches": sum(1 for x in lines if x["hub_batch"]), "labels": len(labs),
                                     "distinct_labels": len(set(labs))}})
    with open(os.path.join(root, MANIFEST), "w", encoding="utf-8") as f:
        f.write(canon({"header": hdr}) + "\n" + body)


def main():
    args = sys.argv[1:]
    emit = next((a.split("=", 1)[1] for a in args if a.startswith("--emit=")), None)
    if emit is not None and os.path.abspath(emit).startswith(c.REPO_ROOT + os.sep):
        print("--emit must be outside the repository", file=sys.stderr)
        return 2
    if "--build" in args:
        stamp = datetime.datetime.now().strftime("%Y%m%d_%H%M")
        ctx, crel, contract, csha, ih, ims, batches, body, lines = compute(stamp=stamp)
        labs = [l for x in lines for l in x["labels"]]
        assert len(labs) == len(set(labs)) == 1975, "every S5 label exactly once"
        root = emit or CR
        os.makedirs(os.path.join(root, "prompts"), exist_ok=True)
        with open(os.path.join(root, crel), "w", encoding="utf-8") as f:
            f.write(contract)
        write_manifest(root, ctx, crel, csha, ih, ims, body, lines)
        nh = sum(1 for x in lines if not x["hub_batch"])
        print(f"S5 PREP BUILD: {len(lines)} batches ({nh} non-hub, {len(lines) - nh} hub), {len(labs)} labels; "
              f"contract {crel} sha {csha[:16]}; manifest body sha {sha256_bytes(body.encode())[:16]}")
        c.assert_sealed()
        return 0
    if "--check" in args:
        mlines = open(os.path.join(CR, MANIFEST), encoding="utf-8").read().split("\n")
        hdr = json.loads(mlines[0])["header"]
        base = hdr.get("rev3_base")             # R7 activation (G-LOG-0102): the historical base ≠ the current revision
        ref = base or hdr
        ctx, crel, contract, csha, ih, ims, batches, body, lines = compute(contract_rel=ref["contract"]["path"])
        same = sha256_bytes(body.encode()) == ref["output_sha256"] and csha == ref["contract"]["sha256"]
        viol = []
        if base is not None:
            viol = r7_base_consistency([json.loads(l) for l in body.split("\n") if l],
                                       [json.loads(l) for l in mlines[1:] if l])
        print("S5 PREP CHECK:", "IDENTICAL" if same and not viol else "DIFFERS",
              "(revision-3 base; R7 entries consistent)" if base is not None and same and not viol else "",
              "; ".join(viol[:3]))
        c.assert_sealed()
        return 0 if same and not viol else 1
    if "--materialize-all" in args:
        # R7 activation dry run (G-LOG-0101): every batch's slices, hash-verified against the manifest, written ONLY to an
        # --emit directory OUTSIDE the repository (never the production slice root). One Context for all batches.
        if emit is None:
            print("--materialize-all requires --emit=<dir outside the repository>", file=sys.stderr)
            return 2
        lines_m = open(os.path.join(CR, MANIFEST), encoding="utf-8").read().split("\n")
        hdr = json.loads(lines_m[0])["header"]
        ctx = Context()
        sets = c.holdout_sets()
        if {i: c.sha256_file(i) for i in ctx.inputs} != hdr["input_hashes"]:
            print("REFUSED: an input changed since the manifest was built", file=sys.stderr)
            return 1
        n = 0
        for l in lines_m[1:]:
            if not l:
                continue
            entry = json.loads(l)
            bid = entry["batch_id"]
            texts = {lab: ctx.slice(lab, bid, entry["contract_sha256"], entry["input_manifest_sha256"], sets)
                     for lab in entry["labels"]}
            bad = [lab for lab, t in texts.items() if sha256_bytes(t.encode()) != entry["slice_sha256"][lab]]
            if bad:
                print(f"REFUSED: slice hash mismatch in {bid} for {len(bad)} label(s)", file=sys.stderr)
                return 1
            d = os.path.join(emit, bid)
            os.makedirs(d, exist_ok=True)
            for lab, t in texts.items():
                with open(os.path.join(d, f"{lab}.json"), "w", encoding="utf-8") as f:
                    f.write(t + "\n")
            n += 1
        print(f"S5 MATERIALIZE-ALL: {n} batches verified against the manifest; written to {emit} (outside the repository)")
        c.assert_sealed()
        return 0
    if "--materialize" in args:
        bid = args[args.index("--materialize") + 1]
        lines_m = open(os.path.join(CR, MANIFEST), encoding="utf-8").read().split("\n")
        hdr = json.loads(lines_m[0])["header"]
        entry = next((json.loads(l) for l in lines_m[1:] if l and json.loads(l)["batch_id"] == bid), None)
        if entry is None:
            print(f"unknown batch {bid}", file=sys.stderr)
            return 2
        ctx = Context()
        sets = c.holdout_sets()
        if {i: c.sha256_file(i) for i in ctx.inputs} != hdr["input_hashes"]:
            print("REFUSED: an input changed since the manifest was built", file=sys.stderr)
            return 1
        texts = {lab: ctx.slice(lab, bid, entry["contract_sha256"], entry["input_manifest_sha256"], sets) for lab in entry["labels"]}
        bad = [lab for lab, t in texts.items() if sha256_bytes(t.encode()) != entry["slice_sha256"][lab]]
        if bad:
            print(f"REFUSED: slice hash mismatch for {bad}", file=sys.stderr)
            return 1
        root = emit or CR
        sroot = hdr.get("slice_root", SLICE_ROOT)                      # revision-specific root (contract revision >= 2)
        d = os.path.join(root, sroot, bid)
        if os.path.exists(d) and emit is None:
            print(f"REFUSED: {sroot}/{bid} already exists (slices are written once)", file=sys.stderr)
            return 1
        os.makedirs(d, exist_ok=True)
        for lab, t in texts.items():
            with open(os.path.join(d, f"{lab}.json"), "w", encoding="utf-8") as f:
                f.write(t + "\n")
        snap_body = canon({"batch_id": bid, "slices": entry["slice_sha256"], "02-FILES.jsonl": c.sha256_file("02-FILES.jsonl"),
                           "contract_sha256": entry["contract_sha256"], "manifest_output_sha256": hdr["output_sha256"]})
        with open(os.path.join(d, "P3B-BATCH-SNAPSHOT.json"), "w", encoding="utf-8") as f:
            f.write(canon({"header": c.header(__file__, ctx.inputs + [MANIFEST], {"batch_id": bid}, snap_body),
                           "body": json.loads(snap_body)}) + "\n")
        print(f"S5 MATERIALIZE {bid}: {len(texts)} slices verified against the manifest; written to {d}")
        c.assert_sealed()
        return 0
    print(__doc__, file=sys.stderr)
    return 2


if __name__ == "__main__":
    sys.exit(main())
