#!/usr/bin/env python3
"""P3b CENSUS-PROVENANCE AUDIT, calibrated (read-only). Evidence for the provenance question raised by the S3 dry run
(session 2026-09-24): can the content P1 used be identified for every CONTENT file in the Phase-1 census?

Identity reference: the sha256 recorded by P0 in 00-ROADMAP-VALIDATED.jsonl (the v3.5 R11 identity
`source_id + commit + sha256`; 02-FILES.jsonl dropped the hash). Calibration showed that the recorded `commit` alone is
the weaker anchor (5 files present at their recorded commit do not match P0's hash there).

For every CONTENT file of 02-FILES.jsonl it searches, in order, for a copy whose sha256 equals P0's hash:
  recorded commit → the unique 36-char-prefix commit (if the recorded string is not an object) → every version of
  the path in git history → HEAD → working tree.
It records every matching location, and classifies:
  C1 VERIFIED-AT-RECORDED-COMMIT       P0 hash = content at the recorded commit
  C2 VERIFIED-BY-P0-HASH-ELSEWHERE     P0 hash = content at another location; subtype says why the recorded commit fails:
       RECORDED-COMMIT-STRING-CORRUPT · COMMITTED-AFTER-RECORDED-COMMIT · UNTRACKED-WORKING-TREE-ONLY ·
       RECORDED-COMMIT-HOLDS-OTHER-VERSION
  C3 P0-HASH-MISASSIGNED               the content at the recorded commit carries a hash that P0 recorded under another
                                       source_id of the same roadmap (a hash permutation); P1's summary is kept for review
  C4 UNVERIFIED                        no copy matches P0's hash, and no permutation explains it
Also recorded: is_binary; for .pyc (timestamp-based, PEP 552), whether the source size in the header equals the size
of the P0-verified corpus source .py.

Read-only. Output (with --write): audit-p3b/CENSUS-PROVENANCE-AUDIT.jsonl (header + one record per CONTENT file).
"""
import collections
import datetime
import hashlib
import json
import os
import platform
import struct
import subprocess
import sys

REPO = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO, "docs/knowledgeos/chronological-read")
OUT = os.path.join(CR, "audit-p3b", "CENSUS-PROVENANCE-AUDIT.jsonl")


def canon(o):
    return json.dumps(o, sort_keys=True, separators=(",", ":"), ensure_ascii=False)


def sha(b):
    return hashlib.sha256(b).hexdigest()


class Git:
    def __init__(self):
        self.p = subprocess.Popen(["git", "-C", REPO, "cat-file", "--batch"], stdin=subprocess.PIPE, stdout=subprocess.PIPE)

    def blob(self, spec):
        self.p.stdin.write((spec + "\n").encode("utf-8")); self.p.stdin.flush()
        h = self.p.stdout.readline().decode("utf-8").split()
        if len(h) < 3 or h[1] != "blob":
            return None
        d = self.p.stdout.read(int(h[2])); self.p.stdout.read(1)
        return d


def run(*a):
    return subprocess.run(["git", "-C", REPO, *a], capture_output=True)


def main():
    write = "--write" in sys.argv[1:]
    g = Git()
    road = {json.loads(l)["source_id"]: json.loads(l) for l in open(os.path.join(CR, "00-ROADMAP-VALIDATED.jsonl"))}
    files = [json.loads(l) for l in open(os.path.join(CR, "02-FILES.jsonl"), encoding="utf-8") if l.strip()]
    by_path = {f["path"]: f for f in files}
    p0_owner = collections.defaultdict(list)
    for sid, r in road.items():
        p0_owner[r.get("sha256")].append(sid)
    head = run("rev-parse", "HEAD").stdout.decode().strip()
    records = []
    for f in files:
        if f["status"] != "CONTENT":
            continue
        sid, path, rc = f["source_id"], f["path"], f["commit"]
        p0 = road.get(sid, {}).get("sha256")
        rc_exists = run("cat-file", "-e", rc + "^{commit}").returncode == 0
        locs, found_bytes = [], None

        def test(label, data):
            nonlocal found_bytes
            if data is not None and p0 and sha(data) == p0:
                locs.append(label)
                found_bytes = found_bytes or data

        rec_bytes = g.blob(f"{rc}:{path}") if rc_exists else None
        test("recorded_commit", rec_bytes)
        upc = None
        if not rc_exists:
            r = run("rev-parse", "--verify", "-q", rc[:36] + "^{commit}")
            upc = r.stdout.decode().strip() if r.returncode == 0 else None
            if upc:
                rec_bytes = g.blob(f"{upc}:{path}")
                test("unique_prefix_commit", rec_bytes)
        hist = [l.split()[0] for l in run("log", "--all", "--full-history", "--format=%H", "--", path).stdout.decode().split("\n") if l.strip()]
        history_match = None
        for h in hist:
            b = g.blob(f"{h}:{path}")
            if b is not None and p0 and sha(b) == p0:
                history_match = h[:12]
                found_bytes = found_bytes or b
                break
        if history_match:
            locs.append(f"history:{history_match}")
        test("HEAD", g.blob(f"{head}:{path}"))
        wt = os.path.join(REPO, path)
        wt_bytes = open(wt, "rb").read() if os.path.isfile(wt) else None
        test("working_tree", wt_bytes)

        if "recorded_commit" in locs:
            cls, sub = "C1 VERIFIED-AT-RECORDED-COMMIT", None
        elif locs:
            cls = "C2 VERIFIED-BY-P0-HASH-ELSEWHERE"
            if not rc_exists:
                sub = "RECORDED-COMMIT-STRING-CORRUPT"
            elif rec_bytes is not None:
                sub = "RECORDED-COMMIT-HOLDS-OTHER-VERSION"
            elif history_match:
                sub = "COMMITTED-AFTER-RECORDED-COMMIT"
            else:
                sub = "UNTRACKED-WORKING-TREE-ONLY"
        elif rec_bytes is not None and sha(rec_bytes) in p0_owner and sid not in p0_owner[sha(rec_bytes)]:
            cls, sub = "C3 P0-HASH-MISASSIGNED", "content hash recorded by P0 under " + ",".join(p0_owner[sha(rec_bytes)])
        else:
            cls, sub = "C4 UNVERIFIED", None
        data = found_bytes or rec_bytes or wt_bytes
        rec = {"source_id": sid, "path": path, "recorded_commit": rc, "recorded_commit_exists": rc_exists,
               "unique_prefix_commit": upc, "p0_sha256": p0, "p0_file_mtime": road.get(sid, {}).get("file_mtime"),
               "p1_file_mtime": f.get("file_mtime"), "matching_locations": locs, "class": cls, "subtype": sub,
               "is_binary": (b"\x00" in data[:8192]) if data is not None else None}
        if path.endswith(".pyc") and data is not None and len(data) >= 16:
            flags = struct.unpack("<I", data[4:8])[0]
            src_size = struct.unpack("<I", data[12:16])[0] if flags == 0 else None
            src_path = os.path.dirname(os.path.dirname(path)) + "/" + os.path.basename(path).split(".cpython")[0] + ".py"
            src = by_path.get(src_path)
            src_ok = None
            if src and src_size is not None:
                sp0 = road.get(src["source_id"], {}).get("sha256")
                for spec in (f"{src['commit']}:{src_path}", f"{head}:{src_path}"):
                    b = g.blob(spec)
                    if b is not None and sha(b) == sp0:
                        src_ok = len(b) == src_size
                        break
            rec["pyc"] = {"flags": flags, "header_source_size": src_size,
                          "source_in_corpus": src["source_id"] if src else None,
                          "source_size_matches_p0_verified_source": src_ok}
        if cls.startswith("C3"):
            rec["p1_summary_head"] = (f.get("summary") or "")[:160]
        records.append(rec)

    summary = collections.Counter(r["class"] for r in records)
    subs = collections.Counter(r["subtype"] for r in records if r["class"].startswith("C2"))
    body = "\n".join(canon(r) for r in records) + "\n"
    header = {"artifact": "audit-p3b/CENSUS-PROVENANCE-AUDIT.jsonl", "script_name": "p3b_census_provenance_audit.py",
              "script_version": run("hash-object", os.path.abspath(__file__)).stdout.decode().strip(), "head_commit": head,
              "identity_reference": "00-ROADMAP-VALIDATED.jsonl sha256 (P0; v3.5 R11)",
              "records": len(records), "classes": dict(summary), "c2_subtypes": dict(subs),
              "python_version": platform.python_version(),
              "run_timestamp_utc": datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ"),
              "output_sha256": sha(body.encode("utf-8")), "read_only": True}
    print(json.dumps({k: header[k] for k in ("records", "classes", "c2_subtypes")}, ensure_ascii=False))
    if write:
        os.makedirs(os.path.dirname(OUT), exist_ok=True)
        with open(OUT, "w", encoding="utf-8") as fh:
            fh.write(canon({"header": header}) + "\n" + body)
        print("wrote", os.path.relpath(OUT, REPO))
    return 0


if __name__ == "__main__":
    sys.exit(main())
