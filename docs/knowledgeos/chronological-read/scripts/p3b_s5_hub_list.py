#!/usr/bin/env python3
"""P3b S5 hub list (core v1.7 §19.4, G-LOG-0033). Deterministic, S3-only: a discovery label is a HUB when its predicted
S5 stage-2 load exceeds the frozen threshold. Predicted load = dispositions = (distinct stage-1 hit keys) x (the label's
absence dimensions), from P3B-DISCOVERY-SEARCH.jsonl. Threshold 4,081 = the largest single-label load processed and
audited in S4 R2.2 (glyph-register-v3-collision-inventory-2026-09, G-LOG-0024). Stage-2 bytes and file reads come from
the git blob sizes of P3B-IDENTITY-MANIFEST.jsonl. Reads no corpus content and no sealed artifact.

  python3 p3b_s5_hub_list.py            (writes P3B-S5-HUBS.jsonl: header record, then one record per hub)
"""
import collections
import hashlib
import json
import os
import platform
import subprocess
import sys

CR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
THRESHOLD = 4081
RULE = "T4: predicted dispositions > 4081 (largest single-label load processed and audited in S4 R2.2)"
INPUTS = ["P3B-DISCOVERY-SEARCH.jsonl", "P3B-SAMPLE-PLAN.jsonl", "P3B-IDENTITY-MANIFEST.jsonl"]


def sha(path):
    with open(os.path.join(CR, path), "rb") as f:
        return hashlib.sha256(f.read()).hexdigest()


def jl(name):
    with open(os.path.join(CR, name), encoding="utf-8") as f:
        return [json.loads(line) for line in f if line.strip()]


def main():
    man = {r["source_id"]: r for r in jl("P3B-IDENTITY-MANIFEST.jsonl") if "source_id" in r}
    specs = [(s, r["blob_spec"]) for s, r in man.items() if r.get("blob_spec_type") == "GIT-OBJECT"]
    out = subprocess.run(["git", "cat-file", "--batch-check=%(objectsize)"], cwd=CR, capture_output=True, text=True,
                         input="\n".join(b for _, b in specs) + "\n").stdout.split("\n")
    size = {s: int(o) for (s, _), o in zip(specs, out) if o.strip().isdigit()}
    recs = jl("P3B-DISCOVERY-SEARCH.jsonl")[1:]
    hits = {r["label"]: r for r in recs if r["record"] == "LABEL-HITS"}
    dims = collections.Counter(r["label"] for r in recs if r["record"] == "DIMENSION")
    plan = jl("P3B-SAMPLE-PLAN.jsonl")[1:]
    body = []
    for r in sorted(plan, key=lambda x: x["working_label"]):
        lab = r["working_label"]
        keys, files = set(), set()
        for kind in ("ledger_hits", "raw_hits"):
            for s, occ in ((hits.get(lab) or {}).get(kind) or {}).items():
                files.add(s)
                keys.update((s, kind, json.dumps(x)) for x in occ)
        disp = len(keys) * dims[lab]
        if disp > THRESHOLD:
            body.append({"working_label": lab, "tier": r["tier"], "in_checklist": r["in_checklist"],
                         "predicted_dispositions": disp, "distinct_hit_keys": len(keys), "absence_dimensions": dims[lab],
                         "predicted_stage2_bytes": sum(size.get(s, 0) for s in files), "predicted_stage2_file_reads": len(files),
                         "rule": RULE, "threshold": THRESHOLD, "reason": "LOAD",
                         "treatment": "v1.7 §11.4 hub exception: per-label procedure performed; stage 2 not performed; "
                                      "every absence dimension with stage-1 hits resolves ESCALATED (LOAD)",
                         "evidence_unavailable": "stage-2 dispositions and FOUND/GENUINELY-UNDEFINED resolutions for "
                                                 "hit-bearing absence dimensions"})
    lines = [json.dumps(b, sort_keys=True, ensure_ascii=False) for b in body]
    blob = subprocess.run(["git", "hash-object", os.path.abspath(__file__)], capture_output=True, text=True).stdout.strip()
    header = {"header": {"artifact": "P3B-S5-HUBS.jsonl", "script_name": "scripts/p3b_s5_hub_list.py",
                         "script_version": blob, "parameters": {"threshold": THRESHOLD, "rule": RULE},
                         "input_hashes": {p: sha(p) for p in INPUTS}, "counts": {"population": len(plan), "hubs": len(body)},
                         "output_sha256": hashlib.sha256(("\n".join(lines) + "\n").encode("utf-8")).hexdigest(),
                         "python_version": sys.version.split()[0], "platform": platform.platform(),
                         "population_basis": "DISCOVERY-POPULATION (HS-3d32dd44d162)"}}
    with open(os.path.join(CR, "P3B-S5-HUBS.jsonl"), "w", encoding="utf-8") as f:
        f.write(json.dumps(header, sort_keys=True, ensure_ascii=False) + "\n" + "\n".join(lines) + "\n")
    print(json.dumps(header["header"]["counts"]), header["header"]["output_sha256"])
    return 0


if __name__ == "__main__":
    sys.exit(main())
