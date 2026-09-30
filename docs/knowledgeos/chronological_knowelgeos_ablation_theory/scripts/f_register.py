#!/usr/bin/env python3
"""F-P0 — register, resolve and identify every F-ID (§F-4, §F-5). Mechanical only: hashes and classifies bytes,
never interprets content. Mirrors chronological-read/scripts/validate_roadmap.py (v3.5 A4), with F-specific rules.

  python3 f_register.py          # first run: writes F-MANIFEST.jsonl and the REGISTERED/RESOLVED/IDENTIFIED events
  python3 f_register.py --check  # later runs: recompute and compare with the manifest; writes nothing
  python3 f_register.py --dry-run [--dump PATH]   # compute and summarise only (bootstrap report); writes nothing
                                                  # except the optional dump, which must be outside the repo

Writes only inside the F-Series folder. Refuses to overwrite an existing manifest (the manifest is frozen, §F-4).
"""
import collections
import json
import os
import re
import subprocess
import sys

import f_common as C

LINE_RE = re.compile(r"^(F\d{4})\t([A-Z][a-z]{2}) +(\d{1,2}) (\d{2}:\d{2}) (.+)$")


def repair(rel_path, listed_paths, s_paths):
    """v3.5 A4 repair (unique filename continuation in the same directory), made stricter for F (§F-4.3):
    the candidate must not already be listed under another F-ID and must not be an S-Series path."""
    d, frag = os.path.dirname(rel_path), os.path.basename(rel_path)
    ad = os.path.join(C.REPO, d)
    if not frag or not os.path.isdir(ad):
        return None, f"directory {d!r} absent or empty fragment"
    cands = sorted(f for f in os.listdir(ad) if f.startswith(frag) and os.path.isfile(os.path.join(ad, f)))
    if len(cands) != 1:
        return None, f"{len(cands)} candidates in {d} starting with {frag!r}: {cands[:6]}"
    cand = os.path.join(d, cands[0])
    if cand in listed_paths:
        return None, f"unique candidate {cand!r} is already listed under another F-ID"
    if cand in s_paths:
        return None, f"unique candidate {cand!r} is an S-Series path (02-FILES.jsonl)"
    return cand, f"unique filename in {d} starting with {frag!r}"


def kind_of(b, path):
    if len(b) == 0:
        return "EMPTY"
    if b"\x00" in b:
        return "BINARY"
    try:
        b.decode("utf-8")
    except UnicodeDecodeError:
        return "NON-TEXT"
    return "TEXT"


def build():
    lines = [l.rstrip("\n") for l in open(os.path.join(C.REPO, C.FLIST_REL), encoding="utf-8") if l.strip()]
    s_paths = {json.loads(l)["path"] for l in open(os.path.join(C.REPO, "docs/knowledgeos/chronological-read/02-FILES.jsonl"))}
    s_sha = {}
    for l in open(os.path.join(C.REPO, "docs/knowledgeos/chronological-read/00-ROADMAP-VALIDATED.jsonl")):
        r = json.loads(l)
        if r.get("sha256"):
            s_sha.setdefault(r["sha256"], []).append(r["source_id"])
    tracked = set(subprocess.check_output(["git", "ls-files"], text=True, cwd=C.REPO).splitlines())
    parsed = []
    for i, line in enumerate(lines, 1):
        m = LINE_RE.match(line)
        if not m:
            sys.exit(f"FATAL: list line {i} does not match 'F#### <Mon> <d> <HH:MM> <path>': {line!r}")
        parsed.append((i,) + m.groups())
    ids = [p[1] for p in parsed]
    dup_ids = [k for k, v in collections.Counter(ids).items() if v > 1]
    if dup_ids:
        sys.exit(f"FATAL: duplicate F-IDs in list: {dup_ids}")
    listed = {p[5] for p in parsed}
    rows, first_by_sha = [], {}
    for order, fid, mon, day, hhmm, path in parsed:
        r = {"f_id": fid, "list_order": order, "list_mtime": f"{mon} {day} {hhmm}", "listed_path": path,
             "resolve": None, "resolved_path": None, "repair_evidence": None, "content_sha256": None,
             "size_bytes": None, "file_mtime": None, "extension": None, "kind": None, "n_chars": None,
             "n_pages": None, "git_tracked": None, "dup_within_f": None, "content_equals_s_sources": [],
             "is_s_path": path in s_paths}
        if any(path.startswith(p) for p in C.FIREWALL_PREFIXES):
            r["resolve"] = "FIREWALL-LIMITED"
            rows.append(r)
            continue
        rp = path if os.path.isfile(os.path.join(C.REPO, path)) else None
        if rp:
            r["resolve"] = "RESOLVED"
        else:
            rp, ev = repair(path, listed - {path}, s_paths)
            r["repair_evidence"] = ev
            r["resolve"] = "REPAIRED" if rp else "UNRESOLVABLE"
            if not rp:
                rows.append(r)
                continue
        r["resolved_path"] = rp
        if any(rp.startswith(p) for p in C.SELF_PREFIXES):
            r["resolve"] = "SELF-CITATION-EXCLUDED"
            rows.append(r)
            continue
        b = open(os.path.join(C.REPO, rp), "rb").read()
        h = C.sha256_bytes(b)
        r.update(content_sha256=h, size_bytes=len(b), file_mtime=int(os.path.getmtime(os.path.join(C.REPO, rp))),
                 extension=os.path.splitext(rp)[1] or None, kind=kind_of(b, rp), git_tracked=rp in tracked,
                 content_equals_s_sources=s_sha.get(h, []))       # identity fact only; no S evidence is imported (§F-3)
        if r["kind"] == "TEXT":
            t = b.decode("utf-8")
            r["n_chars"], r["n_pages"] = len(t), len(C.pages_of(t))
        r["dup_within_f"] = first_by_sha.get(h)
        first_by_sha.setdefault(h, fid)
        rows.append(r)
    return rows


def main():
    rows = build()
    if "--dry-run" in sys.argv:
        summarise(rows, None)
        if "--dump" in sys.argv:
            dump = sys.argv[sys.argv.index("--dump") + 1]
            if os.path.abspath(dump).startswith(C.REPO):
                sys.exit("REFUSED: --dump must be outside the repository")
            with open(dump, "w", encoding="utf-8") as f:
                for r in rows:
                    f.write(json.dumps(r, sort_keys=True, ensure_ascii=False) + "\n")
        return 0
    if "--check" in sys.argv:
        old = C.read_jsonl(C.MANIFEST)
        strip = lambda r: {k: v for k, v in r.items() if k != "file_mtime"}
        same = [strip(a) for a in old] == [strip(b) for b in rows]
        print("MANIFEST CHECK:", "IDENTICAL" if same else "DIFFERENT")
        return 0 if same else 1
    if os.path.exists(C.MANIFEST) or os.path.exists(C.STATE):
        sys.exit("REFUSED: manifest or state ledger already exists (frozen). Use --check.")
    with open(C.guard(C.MANIFEST), "w", encoding="utf-8") as f:
        for r in rows:
            f.write(json.dumps(r, sort_keys=True, ensure_ascii=False) + "\n")
    msha = C.sha256_file(C.MANIFEST)
    lsha = C.sha256_file(os.path.join(C.REPO, C.FLIST_REL))
    for r in rows:
        ev = {"manifest_sha256": msha, "list_sha256": lsha, "list_order": r["list_order"]}
        C.record_event(r["f_id"], "REGISTERED", ev, run_id="F-P0")
        if r["resolve"] == "FIREWALL-LIMITED":
            C.record_event(r["f_id"], "FIREWALL-LIMITED", ev, run_id="F-P0")
            continue
        if r["resolve"] == "UNRESOLVABLE":
            C.record_event(r["f_id"], "RESOLUTION-FAILED", dict(ev, repair_evidence=r["repair_evidence"]), run_id="F-P0")
            continue
        C.record_event(r["f_id"], "RESOLVED", dict(ev, resolved_path=r["resolved_path"], resolve=r["resolve"],
                                                   repair_evidence=r["repair_evidence"]), run_id="F-P0")
        if r["resolve"] == "SELF-CITATION-EXCLUDED":
            C.record_event(r["f_id"], "SELF-CITATION-EXCLUDED", ev, run_id="F-P0")
            continue
        ev2 = dict(ev, content_sha256=r["content_sha256"], size_bytes=r["size_bytes"], kind=r["kind"])
        state = {"TEXT": "IDENTIFIED", "EMPTY": "EMPTY", "BINARY": "BINARY", "NON-TEXT": "NON-TEXT"}[r["kind"]]
        C.record_event(r["f_id"], state, ev2, run_id="F-P0")
    summarise(rows, msha)


def summarise(rows, msha):
    c = collections.Counter(r["resolve"] for r in rows)
    k = collections.Counter(r["kind"] for r in rows if r["kind"])
    print(f"entries {len(rows)} | resolve {dict(c)} | kind {dict(k)}")
    print(f"dup_within_f {sum(1 for r in rows if r['dup_within_f'])} | content_equals_s {sum(1 for r in rows if r['content_equals_s_sources'])}"
          f" | untracked {sum(1 for r in rows if r['git_tracked'] is False)} | pages total {sum(r['n_pages'] or 0 for r in rows)}"
          f" | chars total {sum(r['n_chars'] or 0 for r in rows)} | manifest sha256 {msha}")


if __name__ == "__main__":
    sys.exit(main())
