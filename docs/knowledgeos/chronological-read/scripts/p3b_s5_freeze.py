#!/usr/bin/env python3
"""P3b S5 discovery-records freeze (plan v2.3.2 §M "Discovery-side baseline for S5c"; addendum v1.5 §7; deliverable
O-20; MINOR-13). Written ONCE, before any unseal request.

Writes `audit-p3b/S5-DISCOVERY-RECORDS-FREEZE.json`: a hashed manifest of
  * the PRIMARY S5 record set: object and register records of the given S5 run files (batches, and S5a/S5b pass
    register files); excluded and counted: COMPARISON (§K rerun, run id `OB####-R2S`) records and S5a same-model
    re-analysis dispositions; with `--state`, runs whose state is FAILED or INCOMPLETE are excluded too (their output is
    kept as evidence but is not a primary result, §20/§22);
  * the persisted PREDICTION set: every TEST-DEFINED register record carrying a hold-out prediction (`prediction_id`,
    or a non-empty `predictions` list; addendum §7 "Format").
Refuses unless the seal is SEALED (asserted at start and end), refuses to overwrite an existing freeze, and refuses if
any frozen record fails the quarantine scan (counts only; addendum §6).

  p3b_s5_freeze.py FILE_OR_DIR [...] [--state P3B-STATE.json] [--out audit-p3b/S5-DISCOVERY-RECORDS-FREEZE.json]
"""
import argparse
import json
import os
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, _HERE)
import p3b_s5_ops_lib as ops  # noqa: E402

c = ops.load_common()
OUT = "audit-p3b/S5-DISCOVERY-RECORDS-FREEZE.json"
RECORD_FILES = ("objects.jsonl", "register.jsonl")


def is_prediction(rec):
    return rec.get("lifecycle_stage") == "TEST-DEFINED" and (bool(rec.get("prediction_id")) or bool(rec.get("predictions")))


def collect(paths):
    files = []
    for p in paths:
        ap = ops.abspath(p)
        if os.path.isdir(ap):
            for root, dirs, fs in os.walk(ap):
                dirs.sort()
                files += [os.path.join(root, f) for f in sorted(fs) if f in RECORD_FILES]
        else:
            files.append(ap)
    return sorted(set(files))


def excluded_runs(state_path):
    if not state_path:
        return set()
    stm = ops.load_script("p3b_s5_state")
    st = stm.load(state_path)
    return {r["run_id"] for b in st["batches"].values() for r in b["runs"] if r["state"] in ("FAILED", "INCOMPLETE")}


def build(paths, state_path=None):
    scanner = ops.load_script("p3b_s5_quarantine_scan")
    files = collect(paths)
    if not files:
        raise c.S5Error("no record files given")
    ops.check_paths(files + ([state_path] if state_path else []))
    bad_runs = excluded_runs(state_path)
    file_rows, rec_hashes, pred_rows = [], [], []
    tot = {"comparison": 0, "reanalysis": 0, "failed_or_incomplete_run": 0}
    q_hits = 0
    for k, fp in enumerate(files, 1):
        recs = ops.read_jsonl(fp)
        n_in = {"comparison": 0, "reanalysis": 0, "failed_or_incomplete_run": 0}
        kept = 0
        for r in recs:
            if ops.is_comparison(r):
                n_in["comparison"] += 1
                continue
            if ops.is_reanalysis(r):
                n_in["reanalysis"] += 1
                continue
            if r.get("run_id") in bad_runs:
                n_in["failed_or_incomplete_run"] += 1
                continue
            nl, nf = scanner.scan_text(json.dumps(r, ensure_ascii=False), r.get("working_label"))
            q_hits += 1 if (nl or nf) else 0
            h = ops.canon_hash(r)
            rid = ops.record_id(r)
            rec_hashes.append([rid, h])
            kept += 1
            if is_prediction(r):
                pred_rows.append([rid, h])
        for k in tot:
            tot[k] += n_in[k]
        file_rows.append({"path": ops.display(fp, k), "sha256": ops.sha256_path(fp),
                          "records": len(recs), "frozen_records": kept, "excluded": n_in})
    if q_hits:
        raise c.S5Error(f"{q_hits} record(s) fail the quarantine scan; run p3b_s5_quarantine_scan.py first")
    rec_hashes.sort()
    pred_rows.sort()
    ids = [x[0] for x in rec_hashes]
    if len(set(ids)) != len(ids):
        raise c.S5Error("duplicate record ids in the primary record set")
    body = {"artifact": OUT, "seal_id": c.SEAL_ID, "seal_state_at_freeze": "SEALED",
            "population_basis": c.POPULATION_BASIS, "files": file_rows,
            "primary_records": {"n": len(rec_hashes), "set_sha256": ops.canon_hash(rec_hashes), "records": rec_hashes},
            "prediction_set": {"n": len(pred_rows), "set_sha256": ops.canon_hash(pred_rows), "records": pred_rows},
            "excluded_totals": tot,
            "exclusion_rules": ["COMPARISON (§K rerun) records", "S5a same-model re-analysis dispositions",
                                "runs FAILED or INCOMPLETE in P3B-STATE.json (with --state)"]}
    return body, files


def freeze(paths, out=OUT, state_path=None):
    c.assert_sealed()
    if os.path.exists(ops.abspath(out)):
        raise c.S5Error("the freeze is written once; it already exists")
    body, files = build(paths, state_path)
    body_text = ops.canon(body)
    inputs = files + ([ops.abspath(state_path)] if state_path else [])
    hdr = c.header(__file__, ops.header_inputs(inputs), {"state": bool(state_path)}, body_text)
    c.assert_sealed()
    ops.write_new(out, json.dumps({"header": hdr, "body": body}, indent=1, sort_keys=True, ensure_ascii=False) + "\n")
    return hdr, body


def main(argv=None):
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    ap.add_argument("paths", nargs="+")
    ap.add_argument("--state")
    ap.add_argument("--out", default=OUT)
    a = ap.parse_args(argv)
    try:
        hdr, body = freeze(a.paths, a.out, a.state)
        print(json.dumps({"primary_records": body["primary_records"]["n"],
                          "primary_set_sha256": body["primary_records"]["set_sha256"],
                          "predictions": body["prediction_set"]["n"],
                          "prediction_set_sha256": body["prediction_set"]["set_sha256"],
                          "excluded": body["excluded_totals"], "output_sha256": hdr["output_sha256"]}, sort_keys=True))
        return 0
    except c.S5Error as ex:
        print(f"REFUSED: {ex}", file=sys.stderr)
        return 1


if __name__ == "__main__":
    sys.exit(main())
