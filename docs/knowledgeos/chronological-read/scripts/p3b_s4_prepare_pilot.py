#!/usr/bin/env python3
"""P3b S4 PILOT PREPARATION — §19.1 S4 row, §19.2 persisted agent contract, §19.3 dispatch inputs, of the frozen core
v1.6.5 (G-LOG-0012), under the sealed hold-out HS-3d32dd44d162 (G-LOG-0018) and decisions G-LOG-0017 (pilot 30,
OMQ-14 per-label reading scope, H-03 P3B-R1 baseline).

It does three things and dispatches nothing:
  1 PILOT SELECTION. Population = P3B-R1 labels (OB0001–OB0003) ∩ discovery labels ∩ Tier U/Z, sorted by
    working_label; `random.Random(PILOT_SEED).sample(population, 30)`. Every pilot label therefore has an R1 baseline
    and lies outside the hold-out. Batching: 6 batches of 5 labels, in selection order sorted by working_label.
  2 AGENT CONTRACT (§19.2). The contract is assembled from the frozen protocol's own text: the sections §19.2 lists
    are copied **verbatim** (extracted by heading), preceded by a P3b execution preamble (inputs, the reader, the
    decisions in force for the pilot, the output files) and followed by the output schema. Nothing is paraphrased.
    Written to prompts/<stamp>_p3b-agent-contract-r2.md; its sha256 goes into every slice and output record.
  3 INPUT SLICES. One JSON per label under _batch_input_r2/s4-pilot/: the label's H-04 bundle materialized from the
    DISCOVERY index (hash-verified), its discovery-basis stage-1 search records, the P3a pair records it touches,
    its tier and checklist membership from P3B-SAMPLE-PLAN.jsonl, and the provenance ids. The R1 baseline is
    deliberately NOT in the slice (the R1-vs-R2 comparison must be blind). Slices never contain hold-out records.

Usage:  python3 p3b_s4_prepare_pilot.py [--dry-run [--emit=<dir outside the repository>]]
Exit:   0 PASS · 1 FAIL · 2 usage error
"""
import collections
import datetime
import glob
import importlib.util
import json
import os
import platform
import random
import re
import subprocess
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
_spec = importlib.util.spec_from_file_location("p3b_discovery_io", os.path.join(_HERE, "p3b_discovery_io.py"))
dio = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(dio)
s3 = dio.s3
canon, sha256_bytes, CR, REPO_ROOT = s3.canon, s3.sha256_bytes, s3.CR, s3.REPO_ROOT

PROTOCOL = "prompts/20260924_1505_p3b-phase1-continuation-protocol-v1.6.5.md"
PROTOCOL_SHA256 = "b70fc216ea6a1cde5a8efbfc1ea2ac1397f18b519bf1c04c292a44d7415ee3f7"
SEAL_ID = "HS-3d32dd44d162"
PILOT_SIZE, BATCH_SIZE, PILOT_SEED = 30, 5, 20260924
RUN_ID = "S4-PILOT-R2-001"
SLICE_DIR = "_batch_input_r2/s4-pilot"
OUT_MANIFEST = "_batch_input_r2/P3B-S4-PILOT-MANIFEST.json"          # outside the agents' slice dir
OUT_SNAPSHOT = "_batch_input_r2/P3B-BATCH-SNAPSHOT-S4-PILOT.json"          # §19.5
ENFORCEMENT = ("scripts/p3b_read_source.py", "scripts/p3b_discovery_io.py", "scripts/p3b_s3_mechanical_prep.py")

# Sections §19.2 requires in the contract, copied verbatim (heading text as it appears in the frozen protocol).
CONTRACT_SECTIONS = [
    "## 1A Research-first principle", "## 1B Three research output layers", "## 1C Historical time vs research time",
    "## 1D Discovery is allowed; canonicalization is not", "## 1E Openness",
    "## 3.4 Firewalled and secondary files inside the boundary",
    "## 9.8 Per-label procedure", "## 9A Researcher role", "## 9B Research discovery loop",
    "## 9C Research checklist",
    "# 11. EVIDENCE MODEL", "# 12. RELATIONSHIP MODEL", "# 13. RESEARCH REGISTER", "# 14. TEMPORAL / HINDSIGHT CONTROLS",
    "# 16. AI INTERPRETATION BOUNDARY", "# 18. PROVENANCE REQUIREMENTS", "# 20. QUALITY GATES",
]


def git(*a):
    return subprocess.run(["git", "-C", REPO_ROOT, *a], capture_output=True, text=True).stdout.strip()


def extract(lines, heading_prefix):
    """Lines from the heading starting with heading_prefix up to (excluding) the next heading of the same or a
    higher level."""
    start = next(i for i, l in enumerate(lines) if l.startswith(heading_prefix))
    level = len(heading_prefix) - len(heading_prefix.lstrip("#"))
    end = len(lines)
    for j in range(start + 1, len(lines)):
        m = re.match(r"^(#+) ", lines[j])
        if m and len(m.group(1)) <= level:
            end = j
            break
    return lines[start:end]


PREAMBLE = """# P3b AGENT CONTRACT — S4 PILOT, run {run_id}

**Governing documents:** frozen P3b core v1.6.5 (`{protocol}`, sha256 `{protocol_sha}`), H-19 addendum v1.5
(hold-out sealed as `{seal_id}`), governance log entries up to G-LOG-0019. The sections under "PROTOCOL TEXT" are
copied verbatim from the frozen core, and the core governs. Section E (output schema) is assembled from them. This
preamble only states how this run applies the core.

## A. What you receive, what you may read

1. **Inputs:** this contract and your slice files (`{slice_dir}/<working_label>.json`), one per assigned label. Each
   slice holds the label's bundle, its family `.md` text (`family_md`, the §9.8 step-1 "family .md"), its stage-1
   search records, the P3a pair records it touches, per-source provisional track tags, and whether the label is in
   the checklist population. Nothing else is input.
2. **Corpus text:** read a source ONLY with
   `python3 docs/knowledgeos/chronological-read/scripts/p3b_read_source.py --run {run_id} --batch <batch_id> --label
   <working_label> --step <1|7|10> S#### [S#### ...]`. It returns the content identified by the S2b manifest (decoded
   as UTF-8 with replacement), logs the call itself, and refuses sealed hold-out files. `--step` names the §9.8 step
   the read serves: 1 (timeline), 7 (stage-2 reading), 10 (research).
   **Never open a corpus file by path, never list or browse repository directories, and never read any other file**:
   not other slices, the ledgers, P3a/P3b outputs, the governance log, the S3 outputs, the seal, or the hold-out file.
   A refused read is not an error to work around: the reader logs it; note it in the register if it matters, and continue.
3. **Reading scope (OMQ-14, per-label):** the label's rows in historical order, and whole-file reading of the sources
   that carry a birth, a change of definition, type or meaning, or a contradiction (§9.8 step 1). **Stage 2 (§11.4): read
   whole every file keyed in `raw_hits` or `ledger_hits` of the label's LABEL-HITS record** (listed in the slice as
   `stage2_files`), and disposition each hit. The absence procedure is never thinned (§19.4); the pilot measures its full
   cost. `raw_hits` offsets refer to the A.4-normalized text, not to the reader's decoded output; use them to locate, not
   to quote.
4. **Search-record format.** Your slice's `search_records` hold one `LABEL-HITS` record for the label (`terms[]`;
   `ledger_hits` = {{source_id: [[anchor, term_index], ...]}} for matches in contribution rows; `raw_hits` =
   {{source_id: [[character_offset, term_index], ...]}} for matches in the normalized file text, keyed by the file's
   S-id) and one `DIMENSION` record per absence dimension (`hits_ref` points to the LABEL-HITS record; `negative_label`
   is `NEGATIVE-CENSUS` only when there is no hit at all on the discovery basis).
5. **Not supplied in this pilot:** `09-ORCHESTRATOR-FLAGS.md` (a §9.2 input). Record in the register any false cognate
   or truncation you notice.

## B. Decisions in force for this pilot

- **Population basis.** The hold-out is sealed. Every search record, absence resolution and negative label carries
  `population_basis: "DISCOVERY-POPULATION ({seal_id})"`. Your slice's search records are already on that basis.
- **semantic_status (§12.2):** H-11a–d are not decided. Tier Z → `semantic_status: null` with
  `semantic_status_note: "NO-PAIR-EVIDENCE — withheld pending H-11a"` (§9.3). Tier U → `semantic_status: null` with
  `semantic_status_note: "PENDING-H-11"` (G-LOG-0019). Always write `pair_breakdown` from the P3a pair records in your
  slice. Record contradiction (D4) and two-concept (D5) evidence from the label's rows in the **object** field
  `semantic_evidence` (layer A), so §12.2.4 rows 1–2 can be applied after H-11 without re-reading.
- **No STATUS and no TESTED (H-16 undecided).** Research records may reach at most `TEST-DEFINED` (§13.9).
  No `SUPPORTED-IN-CORPUS` or `REFUTED-IN-CORPUS` is assigned.
- **Tier X** labels are not in this pilot.
- **Evidence presentation:** `V1-PLUS-ROWS` (H-04, G-LOG-0007). Your slice's bundle is the verbatim row evidence. P1
  assessment fields (`label_confidence`, `unknown_candidate`, `completeness`, `missing[]`, `review_flag`) are
  assessments, not SOURCE (§11.1).
- **Checklist (§9C):** apply the 23 questions in full, and record which were examined, only if your slice says
  `"in_checklist": true`. Otherwise consider them and record only positive findings. The population is given; never
  choose it.

## C. Output files (append-only; one line per record; canonical JSON)

- `docs/knowledgeos/chronological-read/ledger-p3b-r2/S4-PILOT/<batch_id>/objects.jsonl`: one object record per
  assigned label (schema in section E).
- `docs/knowledgeos/chronological-read/ledger-p3b-r2/S4-PILOT/<batch_id>/register.jsonl`: research records (§13.6).
- `docs/knowledgeos/chronological-read/ledger-p3b-r2/S4-PILOT/<batch_id>/p1-gap-capture.jsonl`: material that P1
  should have captured (§11.4, `P3B-P1-GAP-CAPTURE.jsonl` records), per the core's schema.
- The reader writes its own log (`ledger-p3b-r2/{run_id}/READ-LOG.jsonl`); do not write or edit it.

Write nothing else anywhere. Every S-id you cite must be in your slice or in a non-refused read-log entry of your batch
(the audit checks this and rejects any hold-out id). Every record carries `run_id: "{run_id}"`, `batch_id`, `contract_sha256` (from your slice),
`model_id`, `generation_parameters` (as exposed), `record_status: "PROPOSED"` (object records), and `author_role`.

---

# PROTOCOL TEXT (verbatim from the frozen core v1.6.5)

"""

SCHEMA = """

---

# E. OUTPUT SCHEMA (assembled from §1B, §9.8, §14.3, §18 and §13.6 above; the core governs on any doubt)

**Object record** (one per label, layer A):
`working_label` · `batch_id` · `run_id` · `contract_sha256` · `input_manifest_sha256` (from the slice) · `tier` +
causing `pair_id`s · `timeline` (§14.3 points and summary fields) · `semantic_status` + `semantic_status_note` +
`pair_breakdown` · `type_status` · `mathematical_status` · `births` (keyed by `lexical` / `conceptual` / `formal` / `operational` / `governance`; per CANDIDATE-*-BIRTH:
`ESTABLISHED-*-BIRTH[S]` | `MOVED[S_earlier, quote]` | `UNORDERED-BLOCK[block]` | `NOT-EVIDENCED-IN-CAPTURE`) ·
`semantic_evidence` {{`d4`: [{{S, quote}}], `d5`: [{{S, quote}}]}} · `primary_layer` + `secondary_roles` · `absences` (keyed by the DIMENSION record's `dimension`: `FOUND[S §anchor]` |
`GENUINELY-UNDEFINED-AFTER-CENSUS` | `FIREWALL-BLOCKED` | `ESCALATED`, with the consumed search record's
`negative_label` and `population_basis`, and each stage-2 hit disposition FOUND / FALSE-HIT(reason) / ESCALATED) ·
`dependency_edges` (only with a source row that states the dependency) · every status with its cited `S####`, anchor
or quote, and rule or reasoning · `epistemic_class` per claim · `what_says_this` / `what_would_make_this_wrong` per
status · `anti_projection` per status (the §14.4 answer) · `track_composition` (from the slice's provisional track tags) · `hindsight_dependency` · `superseded_by_sources[]` · `proposed_by: "AI-AGENT"` per
AI-derived field · `evidence_presentation: "V1-PLUS-ROWS"` · `record_status: "PROPOSED"` · `model_id` ·
`generation_parameters` · `author_role` · `analysis_date` · `checklist_examined` (question numbers, only if
in_checklist).

**Research record:** `working_label` (the label whose analysis produced it) plus the §13.6 core (`rs_id` = `<run_id>:<batch_id>:<n>`, `kind`, `topics[]`, `lens`, `scale`
(OBJECT in this pilot), `statement`, `epistemic_class`, `output_layer`, `supporting_evidence[]` with
`evidence_kind`, `historical_anchor`, `research_time`, `run_id`, `contract_sha256`, `model_id`, `origin: "P3B"`,
`related_labels[]`, `derived_from_records[]`, `lifecycle_stage`, `author_role`), plus the profile for its kind.
HYPOTHESIS and STRUCTURE-CANDIDATE add `falsification_condition`, `validation_question`, `competing_hypotheses[]`, the
§13.10 disconfirmation record, `temporal_scope`, `claim_type` and `evidence_level`; STRUCTURE-CANDIDATE adds §13.11.
At `TEST-DEFINED`, `test_plan_sha256` = sha256 of the canonical JSON (sorted keys, compact separators, UTF-8) of the
§13.10a plan fields: statement, claim_type, competing_hypotheses, the A–D population/method/search_terms,
temporal_scope, the X definition (structural claims), the STATUS decision rule and the stopping rule.
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
        failures.append("frozen protocol hash mismatch")
    seal = dio.load_seal()
    if seal.get("state") != "SEALED" or seal.get("seal_id") != SEAL_ID:
        failures.append("the hold-out is not sealed as HS-3d32dd44d162")
    resolver = dio.discovery_resolver()                        # validates the seal against the approved hash
    H, HF = set(seal["holdout_labels"]), set(seal["holdout_files"])
    if not dry_run and "## G-LOG-0019" not in open(os.path.join(CR, "P3B-GOVERNANCE-LOG.md"), encoding="utf-8").read():
        failures.append("G-LOG-0019 (the decisions this contract cites) is not recorded")
    for rel in ("scripts/p3b_s4_prepare_pilot.py",) + ENFORCEMENT:
        if not dry_run and git("status", "--porcelain", "--", os.path.join(CR, rel)) != "":
            failures.append(f"a real run requires {rel} to be committed and unmodified")

    # ---- 1 pilot selection ----
    tiers = {json.loads(l)["working_label"]: json.loads(l)["tier"]
             for l in open(os.path.join(CR, "P3B-TIERS.jsonl"), encoding="utf-8").read().split("\n")[1:] if l}
    r1 = set()
    for fp in sorted(glob.glob(os.path.join(CR, "ledger-p3b", "OB000[123]", "objects.jsonl"))):
        for l in open(fp, encoding="utf-8"):
            if l.strip():
                o = json.loads(l)
                r1.add(o.get("working_label") or o.get("label"))
    d_index_lines = [l for l in open(os.path.join(CR, "_batch_input_r2/bundle_index_discovery.jsonl"), "rb").read().split(b"\n") if l]
    d_index = {json.loads(l)["working_label"]: json.loads(l) for l in d_index_lines[1:]}
    population = sorted(x for x in r1 if x in d_index and x not in H and tiers.get(x) in ("U", "Z"))
    pilot = sorted(random.Random(PILOT_SEED).sample(population, PILOT_SIZE))
    batches = {f"PB{i // BATCH_SIZE + 1:02d}": pilot[i:i + BATCH_SIZE] for i in range(0, len(pilot), BATCH_SIZE)}

    # ---- 2 contract ----
    plines = open(os.path.join(CR, PROTOCOL), encoding="utf-8").read().split("\n")
    parts = []
    for h in CONTRACT_SECTIONS:
        try:
            parts.append("\n".join(extract(plines, h)).rstrip() + "\n")
        except StopIteration:
            failures.append(f"contract section not found: {h}")
    contract = PREAMBLE.format(run_id=RUN_ID, protocol=PROTOCOL, protocol_sha=PROTOCOL_SHA256, seal_id=SEAL_ID,
                               slice_dir="docs/knowledgeos/chronological-read/" + SLICE_DIR) + "\n".join(parts) + SCHEMA
    contract_bytes = contract.encode("utf-8")
    contract_sha = sha256_bytes(contract_bytes)
    stamp = datetime.datetime.now().strftime("%Y%m%d_%H%M")
    contract_rel = f"prompts/{stamp}_p3b-agent-contract-r2.md"

    # ---- 3 slices ----
    rows_lines = [l for l in open(os.path.join(CR, "03-CONTRIBUTIONS.jsonl"), "rb").read().split(b"\n") if l.strip()]
    files_lines = [l for l in open(os.path.join(CR, "02-FILES.jsonl"), "rb").read().split(b"\n") if l.strip()]
    objs = json.load(open(os.path.join(CR, "20-FAMILIES/_derived.json"), encoding="utf-8"))["reconciliation_objects"]
    search = collections.defaultdict(list)
    for l in open(os.path.join(CR, "P3B-DISCOVERY-SEARCH.jsonl"), encoding="utf-8").read().split("\n")[1:]:
        if l:
            r = json.loads(l)
            if r["label"] in pilot:
                search[r["label"]].append(r)
    pairs = {json.loads(l)["pair_id"]: json.loads(l) for l in open(os.path.join(CR, "31-RECONCILIATION-PAIRS.jsonl"), encoding="utf-8") if l.strip()}
    plan = {json.loads(l)["working_label"]: json.loads(l)
            for l in open(os.path.join(CR, "P3B-SAMPLE-PLAN.jsonl"), encoding="utf-8").read().split("\n")[1:] if l}
    input_hashes = {rel: s3.sha256_file(rel) for rel in (
        PROTOCOL, "P3B-HOLDOUT-SEAL.json", "P3B-IDENTITY-MANIFEST.jsonl", "_batch_input_r2/bundle_index_discovery.jsonl",
        "P3B-DISCOVERY-SEARCH.jsonl", "P3B-SAMPLE-PLAN.jsonl", "P3B-TIERS.jsonl", "03-CONTRIBUTIONS.jsonl",
        "02-FILES.jsonl", "20-FAMILIES/_derived.json", "31-RECONCILIATION-PAIRS.jsonl",
        *[f"ledger-p3b/OB000{i}/objects.jsonl" for i in (1, 2, 3)])}
    fam_texts = {}
    for lab in pilot:
        fam_path = os.path.join(CR, "20-FAMILIES", f"{lab}.md")
        if os.path.exists(fam_path):
            fam_texts[lab] = open(fam_path, encoding="utf-8", newline="").read()
            input_hashes[f"20-FAMILIES/{lab}.md"] = s3.sha256_file(f"20-FAMILIES/{lab}.md")
        else:
            failures.append(f"family .md missing for {lab}")
            fam_texts[lab] = ""
    input_manifest_sha = sha256_bytes(canon(input_hashes).encode())
    hpat = re.compile(r"(?<![A-Za-z0-9._-])(" + "|".join(map(re.escape, sorted(H, key=len, reverse=True))) + r")(?![A-Za-z0-9._-])")
    slices = {}
    for batch_id, labs in batches.items():
        for lab in labs:
            bundle, ok_b, probs = s3.materialize_bundle(d_index[lab], rows_lines, files_lines, objs)
            fam_text = fam_texts[lab]
            if not ok_b:
                failures.append(f"bundle {lab}: {probs[:3]}")
            ptouch = sorted({p["pair_id"] if isinstance(p, dict) else p for p in objs[lab].get("pairs_touching") or []})
            sl = {"working_label": lab, "batch_id": batch_id, "run_id": RUN_ID, "contract_sha256": contract_sha,
                  "input_manifest_sha256": input_manifest_sha, "tier": tiers[lab],
                  "in_checklist": plan[lab]["in_checklist"],
                  "population_basis": f"DISCOVERY-POPULATION ({SEAL_ID})", "bundle": bundle,
                  "family_md": fam_text, "family_md_sha256": sha256_bytes(fam_text.encode("utf-8")),
                  "source_tracks": {s["source_id"]: {"track_tag": s["track_tag"], "status": "PROVISIONAL-PENDING-OMQ-07"}
                                    for s in d_index[lab]["sources"]},
                  "search_records": search[lab],
                  "stage2_files": sorted({s for r in search[lab] if r["record"] == "LABEL-HITS"
                                          for s in list(r["raw_hits"]) + list(r["ledger_hits"])}),
                  "p3a_pairs": [pairs[p] for p in ptouch if p in pairs],
                  "pairs_touching_missing": [p for p in ptouch if p not in pairs]}
            text = canon(sl)
            if hpat.search(text):
                failures.append(f"slice {lab} names a hold-out label")
            allowed = {s for s, labs in seal.get("hf_sid_mentions_in_discovery_bundles", {}).items() if lab in labs}
            if (set(re.findall(r"\bS\d{4}\b", text)) & HF) - allowed:
                failures.append(f"slice {lab} cites a hold-out file")
            slices[lab] = text

    family_hashes = {k: v for k, v in input_hashes.items() if k.startswith("20-FAMILIES/") and k.endswith(".md")}
    snapshot = {"run_id": RUN_ID, "batches": {b: {"slices": {lab: sha256_bytes(slices[lab].encode()) for lab in labs}}
                                              for b, labs in batches.items()},
                "02-FILES.jsonl": input_hashes["02-FILES.jsonl"], "contract_sha256": contract_sha,
                "enforcement_blobs": {rel: git("hash-object", os.path.join(CR, rel)) for rel in ENFORCEMENT}}
    ok = not failures
    manifest = {"script_name": "p3b_s4_prepare_pilot.py", "script_version": git("hash-object", os.path.abspath(__file__)),
                "script_committed_unmodified": git("status", "--porcelain", "--", os.path.abspath(__file__)) == "",
                "run_id": RUN_ID, "seal_id": SEAL_ID, "pilot_seed": PILOT_SEED, "pilot_size": PILOT_SIZE,
                "population": {"definition": "P3B-R1 ∩ discovery labels ∩ Tier U/Z", "size": len(population)},
                "pilot_labels": pilot, "batches": batches,
                "contract": {"path": contract_rel, "sha256": contract_sha, "sections_verbatim": CONTRACT_SECTIONS},
                "input_hashes": input_hashes, "input_manifest_sha256": input_manifest_sha,
                "slices": {lab: sha256_bytes(t.encode()) for lab, t in slices.items()},
                "checklist_in_pilot": sum(1 for x in pilot if plan[x]["in_checklist"]),
                "tiers_in_pilot": dict(collections.Counter(tiers[x] for x in pilot)),
                "family_md_hashes": family_hashes,
                "enforcement_blobs": snapshot["enforcement_blobs"],
                "parameters": {"pilot_size": PILOT_SIZE, "batch_size": BATCH_SIZE, "seed": PILOT_SEED,
                               "population": "P3B-R1 ∩ discovery labels ∩ Tier U/Z", "draw": "random.Random(seed).sample(sorted population, 30), sorted"},
                "output_sha256": sha256_bytes(canon({"contract": contract_sha, "slices": {lab: sha256_bytes(t.encode()) for lab, t in slices.items()}}).encode()),
                "python_version": platform.python_version(), "platform": platform.platform(),
                "result": "PASS" if ok else "FAIL", "failures": failures,
                "run_timestamp_utc": datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ")}

    print(f"S4 PILOT PREP: population {len(population)} (R1 ∩ discovery ∩ U/Z) · pilot {len(pilot)} · batches "
          f"{ {k: len(v) for k, v in batches.items()} } · tiers {manifest['tiers_in_pilot']} · in checklist {manifest['checklist_in_pilot']}")
    print(f"  contract {contract_rel}: {len(contract_bytes)} bytes, {len(parts)} verbatim sections, sha256 {contract_sha[:16]}")
    print(f"  slices {len(slices)} · input manifest {input_manifest_sha[:16]}")
    for f_ in failures:
        print("  FAIL:", f_)

    def write(root, contract_path):
        os.makedirs(os.path.join(root, SLICE_DIR), exist_ok=True)
        with open(contract_path, "wb") as f:
            f.write(contract_bytes)
        for lab, t in slices.items():
            with open(os.path.join(root, SLICE_DIR, f"{lab}.json"), "w", encoding="utf-8") as f:
                f.write(t + "\n")
        for name, obj in ((OUT_MANIFEST, manifest), (OUT_SNAPSHOT, snapshot)):
            with open(os.path.join(root, name), "w", encoding="utf-8") as f:
                json.dump(obj, f, ensure_ascii=False, indent=1, sort_keys=True)
                f.write("\n")
    if emit is not None:
        write(emit, os.path.join(emit, os.path.basename(contract_rel)))
        print(f"emitted to {emit} (review only)")
    if ok and not dry_run:
        write(CR, os.path.join(CR, contract_rel))
        print(f"wrote {contract_rel}, {len(slices)} slices, {OUT_MANIFEST}")
    print("S4 PILOT PREP RESULT:", "PASS" if ok else "FAIL")
    return 0 if ok else 1


if __name__ == "__main__":
    sys.exit(main())
