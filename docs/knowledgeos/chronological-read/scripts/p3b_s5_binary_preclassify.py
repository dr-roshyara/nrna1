#!/usr/bin/env python3
"""Activation step 5 (R7 addendum §15; r5 addendum items 6–7): binary PRE-CLASSIFICATION of the required binary files.
Authorized by the human act recorded as G-LOG-0094 (Freeze 1 + binary read). Bounded:

  1. METADATA ONLY → the file list: required stage-2 files of the S5 floor (non-hub labels; stage2_files = the keys of
     raw_hits ∪ ledger_hits of the LABEL-HITS record, as p3b_s5_prepare builds them) ∩ identity-manifest
     search_eligibility = BINARY. Cross-checked against the recorded aggregate (12 files: 2 PNG, 3 ZIP, 7 NUL; 41 labels).
  2. ONE checker read: the bytes of exactly those files through the seal-aware resolver (refuses sealed files).
  3. IV-1a (G-LOG-0095): the stage-1 NORMALIZED offsets are re-based to the raw decoded coordinate and VERIFIED
     (`p3b_s5_offset_rebase`), then the FROZEN classifier `r5.binary_preclassify` runs unchanged on the verified raw
     offsets. Mapping uncertainty ⇒ HUMAN-REVIEW, never FALSE-HIT.

Output: per file the format, stage-1 hit count, mapping VERIFIED/UNVERIFIED counts, region counts, the candidate
(FALSE-HIT / HUMAN-REVIEW) with its reasons, byte size, label count. NO file content is ever printed or written.
  cd scripts && python3 -B p3b_s5_binary_preclassify.py --out ../audit-p3b/20260928_BINARY-PRECLASSIFICATION.json
"""
import argparse
import collections
import hashlib
import importlib.util
import json
import os
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
CR = os.path.dirname(HERE)
sys.path.insert(0, HERE)


def _load(name):
    s = importlib.util.spec_from_file_location(name, os.path.join(HERE, name + ".py"))
    m = importlib.util.module_from_spec(s)
    s.loader.exec_module(m)
    return m


r5 = _load("p3b_s5_r5")
s3 = _load("p3b_s3_mechanical_prep")
dio = _load("p3b_discovery_io")
c = _load("p3b_s5_common")
RB = _load("p3b_s5_offset_rebase")                          # IV-1a (G-LOG-0095)
EXPECTED = {"files": 12, "SIGNATURE-PNG": 2, "SIGNATURE-ZIP": 3, "NUL-BYTE": 7, "labels": 41}


def jl(path):
    with open(os.path.join(CR, path), encoding="utf-8") as f:
        return [json.loads(l) for l in f if l.strip()]


def derive():
    """The HISTORICAL frame definition (G-LOG-0054 measurement, 2026-09-25, reproduced exactly from metadata on
    2026-09-28: 12 files, 41 labels, 1,731,013 bytes): prepare's Context — which applies the H-19 quarantine to the
    stage-1 hits — non-hub stage-2 hits (raw ∪ ledger) of the plan labels, ∩ search_eligibility = BINARY. (The raw
    absence search WITHOUT the quarantine wrongly adds one sealed hold-out file; never use it.)"""
    P = _load("p3b_s5_prepare")
    ctx = P.Context()
    binary = {r["source_id"] for r in jl("P3B-IDENTITY-MANIFEST.jsonl") if r.get("search_eligibility") == "BINARY"}
    offsets, labels_of = collections.defaultdict(set), collections.defaultdict(set)
    for lab in ctx.plan:
        if lab in ctx.hubs:
            continue
        h = ctx.hits.get(lab) or {}
        terms = h.get("terms") or []
        for s in set(h.get("raw_hits") or {}) | set(h.get("ledger_hits") or {}):
            if s in binary:
                labels_of[s].add(lab)
                offsets[s] |= {(o, terms[t]) for o, t in (h.get("raw_hits") or {}).get(s, [])}   # (stage-1 offset, term)
    return sorted(labels_of), offsets, labels_of, len(ctx.plan), len(set(ctx.hubs) & set(ctx.plan))


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--out", required=True)
    a = ap.parse_args()
    files, offsets, labels_of, n_floor, n_hub = derive()
    got = {"files": len(files), "labels": len(set().union(*labels_of.values())) if labels_of else 0}
    resolver = dio.discovery_resolver()
    blobs = resolver.read_many(files)                       # the ONE checker read (seal-aware; refuses sealed files)
    recs, fmts = [], collections.Counter()
    for s in files:
        b = blobs[s]
        pc = RB.preclassify(b, sorted(offsets[s]))          # IV-1a verified re-basing around the FROZEN classifier
        fmts[pc["classifier"]["format"]] += 1
        st = collections.Counter(h["status"] for h in pc["hits"])
        recs.append({"source_id": s, "bytes": len(b), "content_sha256": hashlib.sha256(b).hexdigest(),
                     "format": pc["classifier"]["format"], "n_stage1_hits": len(offsets[s]), "labels": len(labels_of[s]),
                     "mapping": {"VERIFIED": st.get("VERIFIED", 0), "UNVERIFIED": st.get("UNVERIFIED", 0),
                                 "chunk_level": pc["chunk_level"], "file_status": pc["file_status"]},
                     "regions": dict(collections.Counter(pc["classifier"]["hits"].values())),
                     "candidate": pc["candidate"], "human_review_reasons": pc["reasons"]})
    got.update(fmts)
    check = {k: {"expected": v, "observed": got.get(k, 0), "match": got.get(k, 0) == v} for k, v in EXPECTED.items()}
    body = {"artifact": "BINARY-PRECLASSIFICATION", "authority": "G-LOG-0094", "classifier": "p3b_s5_r5.binary_preclassify (frozen, unchanged)",
            "driver_sha256": hashlib.sha256(open(__file__, "rb").read()).hexdigest(),
            "floor_labels": n_floor, "hub_labels_excluded": n_hub, "aggregate_check": check,
            "total_bytes": sum(r["bytes"] for r in recs), "files": recs,
            "note": "candidates only; each file needs a human decision FALSE-HIT / NOT-CONSUMED-ESCALATED / EXTRACT"}
    text = json.dumps(body, indent=1, sort_keys=True, ensure_ascii=False)
    if any(c.quarantine_hits(text)):
        raise SystemExit("REFUSED: output failed the quarantine scan; nothing written")
    with open(a.out, "w", encoding="utf-8") as f:
        f.write(text + "\n")
    print(json.dumps({"written": a.out, "aggregate_check": check, "candidates": dict(collections.Counter(r["candidate"] for r in recs))}))


if __name__ == "__main__":
    main()
