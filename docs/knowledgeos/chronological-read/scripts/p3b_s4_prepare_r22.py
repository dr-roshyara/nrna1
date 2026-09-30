#!/usr/bin/env python3
"""P3b S4 R2.2 PREPARATION — re-run of the non-accepted pilot batches PB02–PB05 (G-LOG-0021) under the frozen core
v1.6.6 (G-LOG-0022), as run S4-PILOT-R2-002. Same labels and batches as run S4-PILOT-R2-001; nothing is re-sampled.

Differences from the R2-001 preparation (each fixes a finding of the S4 report, audit-p3b/20260924_1846_s4-pilot-report.md):
  D1 closed value lists in the contract (the P3b verifier's enums, scripts/verify_reconciliation_objects_batch.py,
     plus the v1.6.6 values)                                                                   [report D-1]
  D2 `source_meta` in every slice: the 02-FILES provenance, date basis, explicit dates and mtime_block of every S-id
     the slice mentions, so agents can apply G-08 and §14.2 themselves                          [report D-2]
  D3 one exact JSON schema for object, register and P1-gap records; per-hit dispositions in ONE list
     (`stage2_dispositions`)                                                                    [report D-3, D-8]
  D4 v1.6.6 rules in the preamble: UNSUPPLIED-DIMENSION, census–reading disagreement → ESCALATED,
     BIRTH-UNRESOLVED-MTIME-ONLY, Stage 2A/2B with scripts/p3b_stage2a_scan.py                  [G-LOG-0021/0022]
  D5 isolation: a private scratch directory per batch; no helper agents or forks; no access to other batches'
     scratch                                                                                    [report D-7]
The contract's protocol text is copied verbatim from v1.6.6 (same 17 sections as R2-001).

Usage:  python3 p3b_s4_prepare_r22.py [--dry-run [--emit=<dir outside the repository>]]
"""
import collections
import datetime
import importlib.util
import json
import os
import platform
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
_spec = importlib.util.spec_from_file_location("pilot", os.path.join(_HERE, "p3b_s4_prepare_pilot.py"))
pilot = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(pilot)
dio, s3 = pilot.dio, pilot.s3
canon, sha256_bytes, CR, REPO_ROOT, git, extract = s3.canon, s3.sha256_bytes, s3.CR, s3.REPO_ROOT, pilot.git, pilot.extract

PROTOCOL = "prompts/20260924_1853_p3b-phase1-continuation-protocol-v1.6.6.md"
PROTOCOL_SHA256 = "1eb7ac4e258c81318690f7ddfb804dd1d6b505a1a73aa1d2c9a07ec07693fe3f"
RUN_ID = "S4-PILOT-R2-002"
PRIOR_MANIFEST = "_batch_input_r2/P3B-S4-PILOT-MANIFEST.json"
BATCHES_RERUN = ("PB02", "PB03", "PB04", "PB05")
SLICE_DIR = "_batch_input_r2/s4-pilot-r22"
OUT_MANIFEST = "_batch_input_r2/P3B-S4-R22-MANIFEST.json"
OUT_SNAPSHOT = "_batch_input_r2/P3B-BATCH-SNAPSHOT-S4-R22.json"
SCRATCH_ROOT = "/tmp/p3b-s4-r22-scratch"
ENFORCEMENT = ("scripts/p3b_read_source.py", "scripts/p3b_discovery_io.py", "scripts/p3b_s3_mechanical_prep.py",
               "scripts/p3b_stage2a_scan.py", "scripts/p3b_s4_verify_r22.py")
META_FIELDS = ("status", "provenance", "best_historical_date", "best_historical_date_basis", "explicit_dates",
               "mtime_block", "file_mtime")

ENUMS = {  # the P3b verifier's closed lists (verify_reconciliation_objects_batch.py) + v1.6.6 values
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
    "stage2_dispositions[].by_dimension.<dimension>": ["FOUND", "FALSE-HIT", "UNSUPPLIED-DIMENSION", "ESCALATED"],
    "stage2_dispositions[].method": ["WHOLE-FILE", "STAGE-2A-2B"],
    "timeline[].order": ["ORDERED", "UNORDERED-BLOCK"],
    "timeline[].date_applies_to_file": ["CONFIRMED", "NOT-CONFIRMED", "NOT-ASSESSED"],
    "timeline[].change_vs_previous": ["FIRST", "RESTATES", "EXTENDS", "NARROWS", "CHANGES-DEFINITION", "CHANGES-TYPE",
                                      "CHANGES-TERM", "CONTRADICTS", "RETRACTS", "NOT-COMPARABLE"],
    "semantic_status": [None],
    "record_status": ["PROPOSED"],
    "lifecycle_stage (register)": ["OBSERVED", "ANALYSED", "HYPOTHESIS-STATED", "TEST-DEFINED"],
    "tier": ["U", "Z"],
    "lens (register)": ["MATHEMATICAL", "STATISTICAL", "DDD", "LOGIC", "EPISTEMIC", "CHRONOLOGICAL", "MIXED"],
    "scale (register)": ["OBJECT"],
    "output_layer (register)": ["B", "C"],
}

SCHEMA = {
    "object_record": {
        "working_label": "str", "batch_id": "str", "run_id": "str", "contract_sha256": "str",
        "input_manifest_sha256": "str", "tier": "U|Z", "tier_causing_pair_ids": "[str]",
        "timeline": "[{source_id, historical_position, date_basis, order, states:{epistemic_class: SOURCE|INFERENCE, quote|step}, change_vs_previous, date_applies_to_file}]",
        "timeline_summary": "{first_lexical, first_conceptual, first_formal, first_operational, first_governance, later_support[], later_refinement[], contradicted_by[], rejected_by[], current_lifecycle}",
        "semantic_status": "null", "semantic_status_note": "str (see B)", "semantic_evidence": "{d4:[{source_id, quote}], d5:[{source_id, quote}]}",
        "pair_breakdown": "{<relationship>|<basis>: count}",
        "type_status": "enum, or null with an `escalations` entry for it", "mathematical_status": "enum, or null with an `escalations` entry for it",
        "primary_layer": "enum", "secondary_roles": "[enum]",
        "escalations": "[{field, reason}]  (§16.4: a field the closed values cannot represent is escalated, never given an invented value; also file a SCHEMA-LIMITATION record for the label, G-LOG-0023)",
        "births": "{lexical, conceptual, formal, operational, governance: enum string}",
        "absences": "{<dimension>: {resolution: enum, negative_label, population_basis, supplied_by: {source_id, anchor, quote} | null, reason}}",
        "stage2_dispositions": "[{source_id, hit_kind: raw|ledger, hit_key: int offset (raw) or the anchor string exactly as in the search record (ledger), term_index: int, method: enum, by_dimension: {<every DIMENSION of the label>: enum}, reason}]  -- exactly ONE entry per stage-1 hit (source_id, hit_kind, hit_key, term_index); a FALSE-HIT is written for every dimension",
        "census_reading_disagreements": "[{dimension, source_id, anchor, quote, stage1_terms}]  (v1.6.6 §11.4)",
        "dependency_edges": "[{target_label, kind: enum, source_id, quote}]",
        "status_basis": "{<status field>: {sources:[S####], rule_or_reasoning, epistemic_class, what_says_this, what_would_make_this_wrong, anti_projection}}",
        "track_composition": "{<track_tag>: count}", "hindsight_dependency": "[str]", "superseded_by_sources": "[{source_id, position}]",
        "proposed_by": "AI-AGENT", "evidence_presentation": "V1-PLUS-ROWS", "record_status": "PROPOSED",
        "model_id": "str", "generation_parameters": "str|obj", "author_role": "str", "analysis_date": "YYYY-MM-DD",
        "checklist_examined": "[int] (only if in_checklist)",
    },
    "register_record": {
        "rs_id": "<run_id>:<batch_id>:<n>", "batch_id": "str", "working_label": "str", "kind": "§13.2 kind", "topics": "[str]",
        "lens": "MATHEMATICAL|STATISTICAL|DDD|LOGIC|EPISTEMIC|CHRONOLOGICAL|MIXED", "scale": "OBJECT",
        "statement": "str", "epistemic_class": "§11.1", "output_layer": "B|C",
        "supporting_evidence": "[{source_id, anchor|quote, evidence_kind: CORPUS|EXTERNAL-THEORY}]",
        "historical_anchor": "[{source_id, historical_position, date_basis}]", "research_time": "ISO datetime",
        "run_id": "str", "contract_sha256": "str", "model_id": "str", "generation_parameters": "str|obj", "origin": "P3B", "related_labels": "[str]",
        "derived_from_records": "[rs_id]", "lifecycle_stage": "enum", "author_role": "str",
        "proposed_topic_definitions": "{PROPOSED:<topic>: definition} (only if a PROPOSED topic is used)",
        "profile (HYPOTHESIS / STRUCTURE-CANDIDATE)": "falsification_condition, validation_question, competing_hypotheses[], contradicting_evidence[] with search record, temporal_scope, claim_type, evidence_level, test_plan_sha256 (if TEST-DEFINED); STRUCTURE-CANDIDATE adds §13.11 fields",
    },
    "p1_gap_record": {
        "gap_id": "<run_id>:<batch_id>:G<n>", "batch_id": "str", "working_label": "str", "source_id": "str", "anchor": "str",
        "quote": "str", "what_p1_missed": "str", "dimension": "str|null", "found_via": "STAGE-2|STEP-1-READING",
        "run_id": "str", "contract_sha256": "str", "model_id": "str",
    },
}

PREAMBLE = """# P3b AGENT CONTRACT — S4 R2.2 re-run, run {run_id}

**Governing documents:** frozen P3b core **v1.6.6** (`{protocol}`, sha256 `{protocol_sha}`), H-19 addendum v1.5
(hold-out sealed as HS-3d32dd44d162), governance log up to G-LOG-0023. The sections under "PROTOCOL TEXT" are copied
verbatim from v1.6.6, and the core governs. Sections D (closed values) and E (exact output schema) are binding
specifications for this run. This preamble states how the run applies the core.

## A. Inputs, reading, isolation

1. **Inputs:** this contract and your slice files (`{slice_dir}/<working_label>.json`). A slice holds the bundle, the
   family `.md` (`family_md`), the stage-1 search records, `stage2_files`, the P3a pair records, per-source
   provisional track tags, **`source_meta` (02-FILES provenance and dates of every S-id the slice mentions)**, and
   `in_checklist`. Nothing else is input.
2. **Corpus text:** `python3 docs/knowledgeos/chronological-read/scripts/p3b_read_source.py --run {run_id} --batch
   <batch_id> --label <working_label> --step <1|7|10> S#### [...]`. For a hit term of ≤ 2 code points (Stage 2A):
   `python3 docs/knowledgeos/chronological-read/scripts/p3b_stage2a_scan.py --run {run_id} --batch <batch_id> --label
   <working_label> --step 7 --term <term> S#### [...]`. Both log themselves and refuse sealed files. For any S-id not
   in your slice's `source_meta` whose provenance you need, record it as unknown; do not look it up.
3. **Never** open, list or search any other file or directory: not other slices, ledgers, logs, governance files, S3
   outputs, the seal, the hold-out, or any other agent's scratch.
4. **Scratch:** use only `{scratch_root}/<batch_id>/` for temporary files. Never read or list another directory under
   `{scratch_root}`.
5. **No helper agents, forks or sub-agents.** Do all reading and judgement yourself.
6. **Reading scope (OMQ-14):** rows in historical order; whole-file reading of the sources carrying a birth, a change of
   definition, type or meaning, or a contradiction (step 1). **Stage 2 covers every file in `stage2_files`**: whole-file
   reading with the reader, or Stage 2A/2B with the scanner for hit terms of ≤ 2 code points (v1.6.6 §11.4). A FOUND
   always needs a whole-file reading with the reader. The absence procedure is never thinned (§19.4). Your own scripts
   may do bookkeeping over reader output, but never replace whole-file reading for terms of more than 2 code points.
   A refused read is not an error to work around: it is logged; note it in the register if it matters.
7. **Search-record format:** `LABEL-HITS` (`terms[]`; `ledger_hits` = {{source_id: [[anchor, term_index], ...]}};
   `raw_hits` = {{source_id: [[offset, term_index], ...]}}; offsets are into the A.4-normalized (casefolded for
   multi-character terms) text: use them to locate, never to quote) and one `DIMENSION` record per absence dimension
   (`negative_label` is `NEGATIVE-CENSUS` only when there is no hit at all on the discovery basis).
8. **Not supplied:** `09-ORCHESTRATOR-FLAGS.md` (§9.2; G-LOG-0019).

## B. Decisions in force

- **Population basis:** `DISCOVERY-POPULATION (HS-3d32dd44d162)` on every search record, absence and negative label.
- **semantic_status (§12.2):** null. Tier Z note `"NO-PAIR-EVIDENCE — withheld pending H-11a"`; Tier U note
  `"PENDING-H-11"`. `pair_breakdown` is always written; D4/D5 evidence goes in `semantic_evidence` (layer A).
- **No STATUS, no TESTED (H-16):** research records reach at most `TEST-DEFINED`.
- **v1.6.6 rules** (all in the protocol text below):
  - a hit on the same object that does not supply the dimension is `UNSUPPLIED-DIMENSION`;
  - GENUINELY-UNDEFINED-AFTER-CENSUS when stage 1 is NEGATIVE-CENSUS, or every hit is FALSE-HIT or
    UNSUPPLIED-DIMENSION;
  - NEGATIVE-CENSUS contradicted by a step-1 whole-file reading resolves `ESCALATED`, entered in
    `census_reading_disagreements` (and as a P1-gap record), **never FOUND**;
  - an MTIME-only birth outside a BULK block (date not confirmed) is `BIRTH-UNRESOLVED-MTIME-ONLY[S]`.
- **G-08 (use `source_meta`):** a SECONDARY-SYNTHESIS or PROVENANCE-UNRESOLVED file can never alone establish a
  birth, a FOUND or a CONTESTED status (§14.2 rule 3). An S-id without a `source_meta` entry counts as UNKNOWN and can
  never be the sole basis either. If such a file is the only basis: a FOUND resolves `ESCALATED`; a birth is MOVED to an
  earlier PRIMARY row if one exists, otherwise `ESCALATED[G-08: sole basis S#### is <provenance>]` (G-LOG-0023).
- **A field the closed values cannot represent** is left null with an `escalations` entry and a SCHEMA-LIMITATION
  record (§12.4, §16.4). Never invent a value.
- **Layer A stays free of layer B/C:** no register id, no pointer to the register and no hypothesis text in any
  object-record field (G-12, §1B rule 3).
- **Evidence presentation:** `V1-PLUS-ROWS`. P1 assessment fields are assessments, not SOURCE (§11.1).
- **Checklist (§9C):** full 23 questions and `checklist_examined` only if `"in_checklist": true`; otherwise consider
  them and record only positive findings.

## C. Output files (canonical JSON lines: sorted keys, compact separators, UTF-8)

- `docs/knowledgeos/chronological-read/ledger-p3b-r2/S4-R22/<batch_id>/objects.jsonl`: one object record per label.
- `.../S4-R22/<batch_id>/register.jsonl`: research records.
- `.../S4-R22/<batch_id>/p1-gap-capture.jsonl`: P1-gap records.

Write nothing else. Every S-id you cite must be in your slice or in a non-refused read-log entry of your batch. Every
record carries `run_id`, `batch_id`, `contract_sha256`, and `model_id` (your exact identifier).

## D. Closed values (binding)

```json
{enums}
```

## E. Exact output schema (binding; the verifier validates it)

```json
{schema}
```

---

# PROTOCOL TEXT (verbatim from the frozen core v1.6.6)

"""


def main():
    args = sys.argv[1:]
    dry_run = "--dry-run" in args
    emit = next((a.split("=", 1)[1] for a in args if a.startswith("--emit=")), None)
    if [a for a in args if a != "--dry-run" and not a.startswith("--emit=")] or \
            (emit is not None and (not dry_run or os.path.abspath(emit).startswith(REPO_ROOT + os.sep))):
        print("usage error: [--dry-run [--emit=<dir outside the repository>]]", file=sys.stderr)
        return 2
    failures = []
    if s3.sha256_file(PROTOCOL) != PROTOCOL_SHA256:
        failures.append("frozen protocol v1.6.6 hash mismatch")
    seal = dio.load_seal()
    dio.discovery_resolver()                                        # validates the seal's file list
    if seal.get("state") != "SEALED" or seal.get("seal_id") != "HS-3d32dd44d162":
        failures.append("the hold-out is not sealed as HS-3d32dd44d162")
    H, HF = set(seal["holdout_labels"]), set(seal["holdout_files"])
    if not dry_run:
        for rel in ("scripts/p3b_s4_prepare_r22.py", "scripts/p3b_s4_prepare_pilot.py") + ENFORCEMENT:
            if git("status", "--porcelain", "--", os.path.join(CR, rel)) != "":
                failures.append(f"a real run requires {rel} to be committed and unmodified")
        if "## G-LOG-0023" not in open(os.path.join(CR, "P3B-GOVERNANCE-LOG.md"), encoding="utf-8").read():
            failures.append("G-LOG-0023 (the R2.2 run-level choices) is not recorded")
    prior = json.load(open(os.path.join(CR, PRIOR_MANIFEST)))
    batches = {b: prior["batches"][b] for b in BATCHES_RERUN}

    # contract
    plines = open(os.path.join(CR, PROTOCOL), encoding="utf-8").read().split("\n")
    parts = ["\n".join(extract(plines, h)).rstrip() + "\n" for h in pilot.CONTRACT_SECTIONS]
    contract = PREAMBLE.format(run_id=RUN_ID, protocol=PROTOCOL, protocol_sha=PROTOCOL_SHA256,
                               slice_dir="docs/knowledgeos/chronological-read/" + SLICE_DIR, scratch_root=SCRATCH_ROOT,
                               enums=json.dumps(ENUMS, indent=1, ensure_ascii=False),
                               schema=json.dumps(SCHEMA, indent=1, ensure_ascii=False)) + "\n".join(parts)
    contract_bytes = contract.encode("utf-8")
    contract_sha = sha256_bytes(contract_bytes)
    contract_rel = f"prompts/{datetime.datetime.now().strftime('%Y%m%d_%H%M')}_p3b-agent-contract-r22.md"

    # slices: reuse the R2-001 slice content (bundle, family md, search records, stage2_files, pairs, tracks,
    # checklist) and add source_meta; provenance ids are re-issued for the new run/contract
    files = {json.loads(l)["source_id"]: json.loads(l) for l in open(os.path.join(CR, "02-FILES.jsonl"), encoding="utf-8") if l.strip()}
    input_hashes = dict(prior["input_hashes"])
    for rel, h in list(input_hashes.items()):                       # every inherited hash re-verified
        if rel.startswith("prompts/20260924_1505"):
            continue
        if s3.sha256_file(rel) != h:
            failures.append(f"input {rel} changed since R2-001")
    input_hashes[PROTOCOL] = PROTOCOL_SHA256
    input_hashes.pop("prompts/20260924_1505_p3b-phase1-continuation-protocol-v1.6.5.md", None)
    input_manifest_sha = sha256_bytes(canon(input_hashes).encode())
    hpat = re.compile(r"(?<![A-Za-z0-9._-])(" + "|".join(map(re.escape, sorted(H, key=len, reverse=True))) + r")(?![A-Za-z0-9._-])")
    slices = {}
    for batch_id, labs in batches.items():
        for lab in labs:
            base = json.load(open(os.path.join(CR, "_batch_input_r2", "s4-pilot", f"{lab}.json"), encoding="utf-8"))
            prior_sha = prior["slices"][lab]
            if sha256_bytes(canon(base).encode()) != prior_sha:
                failures.append(f"R2-001 slice {lab} differs from its recorded hash")
            text_for_ids = canon(base)
            sids = sorted(set(re.findall(r"\bS\d{4}\b", text_for_ids)) - HF)
            base.update({"run_id": RUN_ID, "contract_sha256": contract_sha, "input_manifest_sha256": input_manifest_sha,
                         "source_meta": {s: {k: files[s].get(k) for k in META_FIELDS} for s in sids if s in files}})
            text = canon(base)
            if hpat.search(text):
                failures.append(f"slice {lab} names a hold-out label")
            allowed = {s for s, ls in seal.get("hf_sid_mentions_in_discovery_bundles", {}).items() if lab in ls}
            if (set(re.findall(r"\bS\d{4}\b", text)) & HF) - allowed:
                failures.append(f"slice {lab} cites a hold-out file")
            slices[lab] = text

    ok = not failures
    snapshot = {"run_id": RUN_ID, "batches": {b: {"slices": {l: sha256_bytes(slices[l].encode()) for l in labs}}
                                              for b, labs in batches.items()},
                "02-FILES.jsonl": s3.sha256_file("02-FILES.jsonl"), "contract_sha256": contract_sha,
                "enforcement_blobs": {rel: git("hash-object", os.path.join(CR, rel)) for rel in ENFORCEMENT}}
    manifest = {"script_name": "p3b_s4_prepare_r22.py", "script_version": git("hash-object", os.path.abspath(__file__)),
                "run_id": RUN_ID, "reruns": "S4-PILOT-R2-001 batches " + ", ".join(BATCHES_RERUN) + " (G-LOG-0021)",
                "protocol": PROTOCOL, "protocol_sha256": PROTOCOL_SHA256, "batches": batches,
                "contract": {"path": contract_rel, "sha256": contract_sha, "sections_verbatim": pilot.CONTRACT_SECTIONS},
                "enums": ENUMS, "schema_sha256": sha256_bytes(canon(SCHEMA).encode()), "scratch_root": SCRATCH_ROOT,
                "input_hashes": input_hashes, "input_manifest_sha256": input_manifest_sha,
                "slices": {l: sha256_bytes(t.encode()) for l, t in slices.items()},
                "enforcement_blobs": snapshot["enforcement_blobs"],
                "output_sha256": sha256_bytes(canon({"contract": contract_sha, "slices": {l: sha256_bytes(t.encode()) for l, t in slices.items()}}).encode()),
                "python_version": platform.python_version(), "platform": platform.platform(),
                "result": "PASS" if ok else "FAIL", "failures": failures,
                "run_timestamp_utc": datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ")}
    print(f"S4 R2.2 PREP: batches {list(batches)} · labels {sum(map(len, batches.values()))} · contract {len(contract_bytes)} bytes "
          f"sha {contract_sha[:16]} · slices {len(slices)} · source_meta ids {sum(len(json.loads(t)['source_meta']) for t in slices.values())}")
    for f_ in failures:
        print("  FAIL:", f_)

    def write(root, cpath):
        os.makedirs(os.path.join(root, SLICE_DIR), exist_ok=True)
        with open(cpath, "wb") as f:
            f.write(contract_bytes)
        for lab, t in slices.items():
            with open(os.path.join(root, SLICE_DIR, f"{lab}.json"), "w", encoding="utf-8") as f:
                f.write(t + "\n")
        for name, obj in ((OUT_MANIFEST, manifest), (OUT_SNAPSHOT, snapshot)):
            with open(os.path.join(root, name), "w", encoding="utf-8") as f:
                json.dump(obj, f, ensure_ascii=False, indent=1, sort_keys=True)
                f.write("\n")
    if emit is not None:
        os.makedirs(os.path.join(emit, "_batch_input_r2"), exist_ok=True)
        write(emit, os.path.join(emit, os.path.basename(contract_rel)))
        print(f"emitted to {emit} (review only)")
    if ok and not dry_run:
        write(CR, os.path.join(CR, contract_rel))
        print(f"wrote {contract_rel}, {len(slices)} slices, {OUT_MANIFEST}, {OUT_SNAPSHOT}")
    print("S4 R2.2 PREP RESULT:", "PASS" if ok else "FAIL")
    return 0 if ok else 1


if __name__ == "__main__":
    sys.exit(main())
