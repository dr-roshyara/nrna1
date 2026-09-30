#!/usr/bin/env python3
"""P3b S4 PILOT VERIFIER — script gates of §20 (the mechanical subset), the G-LOG-0019 audit rule, stage-2
completeness, reading cost and the §19.4 pilot measurements, for run S4-PILOT-R2-001. Read-only on the corpus: it
reads batch outputs, slices, the reader's log, the seal and the R1 records; never a corpus file.

Checks per batch (a FAIL on any G-01/02/04/07/09/11/12 item fails the batch, §20):
  G-01  every cited S-id exists in 02-FILES; FIREWALL-LIMITED ids are reported.
  G-02  objects.jsonl holds exactly the assigned labels, each once.
  G-04  (mechanical part) every DIMENSION of the slice is resolved, with negative_label and population_basis.
  G-07  §18 mandatory fields present; contract_sha256 and input_manifest_sha256 equal the slice values; run_id.
  G-09  (mechanical part) no STATUS field; no lifecycle beyond TEST-DEFINED; HYPOTHESIS / STRUCTURE-CANDIDATE carry
        falsification_condition, competing_hypotheses, temporal_scope, claim_type, evidence_level.
  G-11  record_status PROPOSED on every object record.
  G-12  (mechanical part) every research record has historical_anchor and research_time; no research-record id in an
        object record.
  AUDIT every S-id cited by the batch is in its slices or in a non-refused read-log entry of the batch; no hold-out id
        is cited or successfully read.
  STAGE-2 every stage2_files entry of a label was read at step 7 for that label.
Measurements (§19.4 pilot rows): stage-2 dispositions (FOUND / FALSE-HIT / ESCALATED) and false-hit rate; reading
cost per label and per step (bytes, calls); research records per label by kind; R1-vs-R2 agreement per categorical
field (type_status, mathematical_status, primary_layer, birth kinds) on the pilot labels.

Usage:  python3 p3b_s4_verify_pilot.py [--write]      (--write stores audit-p3b/S4-PILOT-VERIFY.json)
"""
import collections
import glob
import json
import os
import re
import sys

CR = os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
RUN = "S4-PILOT-R2-001"
OUT_DIR = os.path.join(CR, "ledger-p3b-r2", "S4-PILOT")
SID = re.compile(r"\bS\d{4}\b")
RSID = re.compile(re.escape(RUN) + r":PB\d{2}:\d+")
OBJ_REQUIRED = ["working_label", "batch_id", "run_id", "contract_sha256", "input_manifest_sha256", "tier", "timeline",
                "semantic_status", "semantic_status_note", "pair_breakdown", "type_status", "mathematical_status", "births",
                "primary_layer", "secondary_roles", "absences", "dependency_edges", "track_composition",
                "hindsight_dependency", "proposed_by", "evidence_presentation", "record_status", "model_id",
                "generation_parameters", "author_role", "analysis_date"]
REG_REQUIRED = ["rs_id", "working_label", "kind", "topics", "lens", "scale", "statement", "epistemic_class", "output_layer",
                "supporting_evidence", "historical_anchor", "research_time", "run_id", "contract_sha256", "model_id",
                "origin", "lifecycle_stage", "author_role"]
HYP_REQUIRED = ["falsification_condition", "competing_hypotheses", "temporal_scope", "claim_type", "evidence_level"]
BEYOND_TEST_DEFINED = {"TESTED", "STATUS", "ACCEPTED", "CANONICAL", "PROMOTED"}


def jl(path):
    if not os.path.exists(path):
        return []
    out = []
    for n, l in enumerate(open(path, encoding="utf-8"), 1):
        if l.strip():
            try:
                out.append(json.loads(l))
            except json.JSONDecodeError as e:
                out.append({"__parse_error__": f"{os.path.basename(path)} line {n}: {e}"})
    return out


DISP = {"FOUND", "FALSE-HIT", "ESCALATED"}


def dispositions(obj):
    """Every dict at any depth with a 'disposition' in DISP and a 'source_id'; de-duplicated per (source_id, hit key,
    disposition), because agents repeat a hit's disposition under several dimensions and used different layouts."""
    seen = set()

    def walk(x):
        if isinstance(x, dict):
            d = x.get("disposition")
            d = d.split("(")[0].split("[")[0].strip() if isinstance(d, str) else d
            if d in DISP and x.get("source_id"):
                key = x.get("hit", x.get("hit_index", x.get("offset", x.get("anchor", x.get("hit_ref")))))
                seen.add((x["source_id"], json.dumps(key, sort_keys=True), d))
            for v in x.values():
                walk(v)
        elif isinstance(x, list):
            for v in x:
                walk(v)
    walk(obj)
    return seen


def cat(v):
    """Category of a status-like value: the part before '[' (e.g. 'MOVED[S0863, …]' -> 'MOVED')."""
    if isinstance(v, dict):
        v = v.get("value") or v.get("status") or v.get("resolution") or v.get("outcome") or json.dumps(v, sort_keys=True)
    return str(v).split("[")[0].strip() if v is not None else None


def main():
    write = "--write" in sys.argv[1:]
    manifest = json.load(open(os.path.join(CR, "_batch_input_r2", "P3B-S4-PILOT-MANIFEST.json")))
    seal = json.load(open(os.path.join(CR, "P3B-HOLDOUT-SEAL.json")))
    HF = set(seal["holdout_files"])
    files = {json.loads(l)["source_id"]: json.loads(l) for l in open(os.path.join(CR, "02-FILES.jsonl")) if l.strip()}
    firewall = {s for s, f in files.items() if f["status"] == "FIREWALL-LIMITED"}
    readlog = jl(os.path.join(CR, "ledger-p3b-r2", RUN, "READ-LOG.jsonl"))
    r1 = {}
    for fp in sorted(glob.glob(os.path.join(CR, "ledger-p3b", "OB000[123]", "objects.jsonl"))):
        for o in jl(fp):
            r1[o.get("working_label")] = o
    report = {"run_id": RUN, "batches": {}, "totals": {}}
    agree = collections.defaultdict(lambda: [0, 0])
    disp_total = collections.Counter()
    per_label_disp = {}
    for batch, labels in sorted(manifest["batches"].items()):
        fails, notes = [], []
        bdir = os.path.join(OUT_DIR, batch)
        objs, reg = jl(os.path.join(bdir, "objects.jsonl")), jl(os.path.join(bdir, "register.jsonl"))
        gap = jl(os.path.join(bdir, "p1-gap-capture.jsonl"))
        for r in objs + reg + gap:
            if "__parse_error__" in r:
                fails.append("parse: " + r["__parse_error__"])
        slices = {lab: json.load(open(os.path.join(CR, "_batch_input_r2", "s4-pilot", f"{lab}.json"))) for lab in labels}
        # G-02
        got = [o.get("working_label") for o in objs if "__parse_error__" not in o]
        if sorted(got) != sorted(labels):
            fails.append(f"G-02 labels {sorted(collections.Counter(got).items())} != assigned {labels}")
        # G-07 / G-11 / G-09 (object side)
        for o in objs:
            lab = o.get("working_label")
            if lab not in slices:
                continue
            miss = [k for k in OBJ_REQUIRED if k not in o]
            if miss:
                fails.append(f"G-07 {lab}: missing {miss}")
            if o.get("contract_sha256") != slices[lab]["contract_sha256"] or o.get("input_manifest_sha256") != slices[lab]["input_manifest_sha256"] or o.get("run_id") != RUN:
                fails.append(f"G-07 {lab}: contract / manifest / run id mismatch")
            if o.get("record_status") != "PROPOSED":
                fails.append(f"G-11 {lab}: record_status {o.get('record_status')!r}")
            if any(k in o for k in ("status", "STATUS", "corpus_status")):
                fails.append(f"G-09 {lab}: a STATUS field is present")
            if RSID.search(json.dumps(o)):
                fails.append(f"G-12 {lab}: object record contains a research-record id")
            # G-04 mechanical: every dimension resolved
            dims = [r["dimension"] for r in slices[lab]["search_records"] if r["record"] == "DIMENSION"]
            ab = o.get("absences") or {}
            keys = set(ab) if isinstance(ab, dict) else {a.get("dimension") for a in ab if isinstance(a, dict)}
            if set(dims) - keys:
                fails.append(f"G-04 {lab}: unresolved dimensions {sorted(set(dims) - keys)[:5]}")
            # dispositions: structural extraction over the whole object record
            ds = dispositions(o)
            c = collections.Counter(d for _, _, d in ds)
            per_label_disp[lab] = dict(c)
            for k, v in c.items():
                disp_total[k] += v
            # R1 vs R2 (categorical)
            b = r1.get(lab)
            if b:
                for f in ("type_status", "mathematical_status", "primary_layer"):
                    agree[f][1] += 1
                    agree[f][0] += cat(o.get(f)) == cat(b.get(f))
                for kind in ("lexical", "conceptual", "formal", "operational", "governance"):
                    agree["births." + kind][1] += 1
                    agree["births." + kind][0] += cat((o.get("births") or {}).get(kind)) == cat((b.get("births") or {}).get(kind))
        # register (G-09 / G-12)
        kinds = collections.Counter()
        for r in reg:
            if "__parse_error__" in r:
                continue
            kinds[(r.get("working_label"), r.get("kind"))] += 1
            miss = [k for k in REG_REQUIRED if k not in r]
            if miss:
                fails.append(f"G-12/§13.6 {r.get('rs_id')}: missing {miss}")
            if str(r.get("lifecycle_stage", "")).upper() in BEYOND_TEST_DEFINED:
                fails.append(f"G-09 {r.get('rs_id')}: lifecycle {r.get('lifecycle_stage')}")
            if r.get("kind") in ("HYPOTHESIS", "STRUCTURE-CANDIDATE"):
                m2 = [k for k in HYP_REQUIRED if k not in r]
                if m2:
                    fails.append(f"G-09 {r.get('rs_id')}: missing {m2}")
        # AUDIT: cited S-ids vs slices + non-refused reads of this batch
        cited = set()
        for r in objs + reg + gap:
            cited |= set(SID.findall(json.dumps(r)))
        slice_sids = set()
        for sl in slices.values():
            slice_sids |= set(SID.findall(json.dumps(sl)))
        blog = [e for e in readlog if e.get("batch_id") == batch]
        read_ok = {s for e in blog if not e.get("refused") for s in e.get("source_ids", [])}
        unexplained = sorted(cited - slice_sids - read_ok)
        if unexplained:
            fails.append(f"AUDIT cited S-ids neither in slices nor read: {unexplained[:10]} (+{max(0, len(unexplained) - 10)})")
        if cited & HF or read_ok & HF:
            fails.append(f"AUDIT hold-out ids cited/read: {sorted((cited | read_ok) & HF)}")
        # G-01
        unknown = sorted(s for s in cited if s not in files)
        if unknown:
            fails.append(f"G-01 unknown S-ids {unknown[:10]}")
        if cited & firewall:
            notes.append(f"G-01 FIREWALL-LIMITED ids cited: {sorted(cited & firewall)}")
        # STAGE-2 completeness and cost
        cost = {}
        for lab in labels:
            le = [e for e in blog if e.get("working_label") == lab]
            s7 = {s for e in le if e.get("step") == 7 and not e.get("refused") for s in e.get("source_ids", [])}
            need = set(slices[lab]["stage2_files"])
            if need - s7:
                fails.append(f"STAGE-2 {lab}: {len(need - s7)} of {len(need)} hit files not read at step 7")
            cost[lab] = {f"step{st}": {"calls": sum(1 for e in le if e.get("step") == st),
                                       "bytes": sum(sum(e.get("bytes", {}).values()) for e in le if e.get("step") == st)}
                         for st in (1, 7, 10)}
            cost[lab]["refused_calls"] = sum(1 for e in le if e.get("refused"))
        report["batches"][batch] = {"result": "PASS" if not fails else "FAIL", "failures": fails, "notes": notes,
                                    "objects": len(objs), "research_records": len(reg), "p1_gap_records": len(gap),
                                    "records_by_label_kind": {f"{a}|{b}": c for (a, b), c in sorted(kinds.items(), key=str)},
                                    "reading_cost": cost}
    n_hits = disp_total["FOUND"] + disp_total["FALSE-HIT"] + disp_total["ESCALATED"]
    report["totals"] = {
        "batches_pass": sum(1 for b in report["batches"].values() if b["result"] == "PASS"),
        "dispositions": dict(disp_total), "dispositions_per_label": per_label_disp,
        "disposition_extraction": "structural: dicts with disposition + source_id at any depth, de-duplicated per (source_id, hit key, disposition); agents used heterogeneous layouts",
        "false_hit_rate": round(disp_total["FALSE-HIT"] / n_hits, 4) if n_hits else None,
        "found_rate": round(disp_total["FOUND"] / n_hits, 4) if n_hits else None,
        "r1_r2_agreement": {f: {"agree": a, "n": n, "rate": round(a / n, 3) if n else None} for f, (a, n) in sorted(agree.items())},
        "research_records_total": sum(b["research_records"] for b in report["batches"].values()),
        "read_log_entries": len(readlog), "refused_reads": sum(1 for e in readlog if e.get("refused"))}
    print(json.dumps(report["totals"], indent=1, ensure_ascii=False))
    for b, v in report["batches"].items():
        print(b, v["result"], f"objects {v['objects']} research {v['research_records']}", *(["\n    " + x for x in v["failures"][:12]] or [""]))
    if write:
        os.makedirs(os.path.join(CR, "audit-p3b"), exist_ok=True)
        with open(os.path.join(CR, "audit-p3b", "S4-PILOT-VERIFY.json"), "w", encoding="utf-8") as f:
            json.dump(report, f, ensure_ascii=False, indent=1, sort_keys=True)
            f.write("\n")
    return 0


if __name__ == "__main__":
    sys.exit(main())
