#!/usr/bin/env python3
"""Read-only hold-out filter for the G-LOG-0141 extension (scope: all discovery-population files of 02-FILES.jsonl
and chronological-read/20-FAMILIES/).

Inside this script only, it uses the pinned identity manifest and the seal (via p3b_discovery_io.discovery_resolver,
which verifies the manifest pin and the hold-out FILE-list hash) and the seal's hold-out LABEL list.

Outputs (only allowed items; sealed names, S-ids, labels and hashes are never printed or written):
  CORPUS-ALLOWED.tsv    source_id, registry date, path, content sha256, disk status — chronological (date, source_id)
  FAMILIES-ALLOWED.tsv  family file, sha256 — a family file is WITHHELD if any hold-out label occurs in its text
                        or equals its file stem (conservative)
  stdout                counts only, plus a count for a given list of previously displayed family names

    python3 -I corpus_filter.py [--check-names FILE]
"""
import hashlib
import importlib.util
import json
import os
import re
import sys

W = "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1-s5-g1/docs/knowledgeos/chronological-read"
SCRIPTS = os.path.join(W, "scripts")
REPO = "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1"
FAMILIES = os.path.join(W, "20-FAMILIES")
REGISTRY = os.path.join(W, "02-FILES.jsonl")


def slug(s):
    return re.sub(r"[^a-z0-9]+", "-", s.lower()).strip("-")


def main(argv):
    sys.path.insert(0, SCRIPTS)
    spec = importlib.util.spec_from_file_location("dio", os.path.join(SCRIPTS, "p3b_discovery_io.py"))
    dio = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(dio)
    res = dio.discovery_resolver()                                   # verifies manifest pin + file-list hash
    seal = dio.load_seal()
    labels = list(seal.get("holdout_labels") or [])
    label_hash_ok = hashlib.sha256(json.dumps(sorted(labels)).encode()).hexdigest() == seal.get("holdout_label_list_sha256")

    dates = {}
    with open(REGISTRY, encoding="utf-8") as f:
        for line in f:
            line = line.strip()
            if not line:
                continue
            r = json.loads(line)
            sid = r.get("source_id")
            if sid:
                dates[sid] = str(r.get("best_historical_date") or r.get("mtime") or "")

    corpus, n_sealed, disk = [], 0, {"match": 0, "changed": 0, "missing": 0}
    for sid, r in res.rows.items():
        if sid in res.sealed:
            n_sealed += 1
            continue
        path = r.get("current_path") or r.get("historical_path") or ""
        p = os.path.join(REPO, path)
        if os.path.isfile(p):
            with open(p, "rb") as f:
                st = "match" if hashlib.sha256(f.read()).hexdigest() == r.get("content_sha256") else "changed"
        else:
            st = "missing"
        disk[st] += 1
        corpus.append((dates.get(sid, ""), sid, path, r.get("content_sha256", ""), st))
    corpus.sort()
    with open("CORPUS-ALLOWED.tsv", "w", encoding="utf-8") as f:
        f.write("date\tsource_id\tpath\tsha256\tdisk\n")
        for row in corpus:
            f.write("\t".join(row) + "\n")

    lab_slugs = {slug(l) for l in labels}
    fam_allowed, fam_withheld = [], 0
    withheld_names = set()
    for fn in sorted(os.listdir(FAMILIES)):
        p = os.path.join(FAMILIES, fn)
        if not os.path.isfile(p):
            continue
        with open(p, "rb") as f:
            data = f.read()
        text = data.decode("utf-8", errors="replace")
        stem = os.path.splitext(fn)[0]
        if slug(stem) in lab_slugs or any(l and l in text for l in labels):
            fam_withheld += 1
            withheld_names.add(fn)
            continue
        fam_allowed.append((fn, hashlib.sha256(data).hexdigest()))
    with open("FAMILIES-ALLOWED.tsv", "w", encoding="utf-8") as f:
        f.write("family_file\tsha256\n")
        for fn, h in fam_allowed:
            f.write(f"{fn}\t{h}\n")

    print(f"label-list hash verified: {label_hash_ok}")
    print(f"CORPUS allowed {len(corpus)} · sealed excluded {n_sealed} · on disk: {disk}")
    print(f"FAMILIES allowed {len(fam_allowed)} · withheld {fam_withheld}")
    if "--check-names" in argv:
        names = [l.strip() for l in open(argv[argv.index("--check-names") + 1], encoding="utf-8") if l.strip()]
        print(f"previously displayed family names: {len(names)} checked · {sum(1 for n in names if n in withheld_names)} withheld by the label rule")


if __name__ == "__main__":
    main(sys.argv[1:])
