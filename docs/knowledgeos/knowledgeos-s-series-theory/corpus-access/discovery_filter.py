#!/usr/bin/env python3
"""Read-only hold-out filter for Session B corpus reads (G-LOG-0141).

For every file under the given brainstorming folders, decide ALLOWED or WITHHELD, inside this script only:
WITHHELD iff the file's content sha256, or its repository-relative path, matches a row of the sealed hold-out
files (H-19 seal), as resolved by the project's own `p3b_discovery_io.discovery_resolver()` (manifest pin
G-LOG-0013 and seal-list hash G-LOG-0016 are verified there).

Output (stdout and the TSV): only ALLOWED paths with their sha256 and an in-census flag, plus a COUNT of withheld
files. Sealed names, S-ids and hashes are never printed or written.

    python3 -I discovery_filter.py <out.tsv> <folder> [<folder> ...]
"""
import hashlib
import importlib.util
import os
import sys

SCRIPTS = "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1-s5-g1/docs/knowledgeos/chronological-read/scripts"
REPO = "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1"


def load(name):
    spec = importlib.util.spec_from_file_location(name, os.path.join(SCRIPTS, name + ".py"))
    mod = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(mod)
    return mod


def norm(p):
    return os.path.normpath(str(p or "")).lstrip("./")


def main(out, folders):
    sys.path.insert(0, SCRIPTS)
    dio = load("p3b_discovery_io")
    res = dio.discovery_resolver()                     # raises unless both pins verify
    rows = res.rows
    sealed_sha, sealed_path = set(), set()
    for sid in res.sealed:
        r = rows.get(sid) or {}
        if r.get("content_sha256"):
            sealed_sha.add(r["content_sha256"])
        for k in ("current_path", "historical_path", "path"):
            if r.get(k):
                sealed_path.add(norm(r[k]))
    census_sha = {r.get("content_sha256") for r in rows.values() if r.get("content_sha256")}
    allowed, withheld = [], 0
    for folder in folders:
        for root, _, files in os.walk(folder):
            for fn in sorted(files):
                p = os.path.join(root, fn)
                with open(p, "rb") as f:
                    h = hashlib.sha256(f.read()).hexdigest()
                rel = norm(os.path.relpath(os.path.realpath(p), os.path.realpath(REPO)))
                if h in sealed_sha or rel in sealed_path:
                    withheld += 1
                    continue
                allowed.append((rel, h, "in-census" if h in census_sha else "not-in-census"))
    with open(out, "w", encoding="utf-8") as f:
        f.write("path\tsha256\tcensus\n")
        for rel, h, c in sorted(allowed):
            f.write(f"{rel}\t{h}\t{c}\n")
    print(f"ALLOWED {len(allowed)} · WITHHELD {withheld} · seal state {'SEALED' if res.sealed else 'UNSEALED'} · "
          f"in-census {sum(1 for a in allowed if a[2] == 'in-census')}")


if __name__ == "__main__":
    main(sys.argv[1], sys.argv[2:])
