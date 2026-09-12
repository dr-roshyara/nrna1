#!/usr/bin/env python3
"""P0 — validate the chronological roadmap. Mechanical only; reads no file content."""
import hashlib
import json
import os
import re
import subprocess
import sys
from collections import defaultdict

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
ROADMAP = os.path.join(REPO_ROOT, "docs/knowledgeos/brainstorming/20260909-185001_files-to-read-one-by-one.log.md")
OUTPUT_DIR_PREFIX = "docs/knowledgeos/chronological-read/"
FIREWALL_PREFIXES = ["docs/knowledgeos/brainstorming/three_model_convergence/"]
OUT_JSONL = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read/00-ROADMAP-VALIDATED.jsonl")

LINE_RE = re.compile(r"^(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}) (.+)$")


def sha256_of(path):
    h = hashlib.sha256()
    with open(path, "rb") as f:
        for chunk in iter(lambda: f.read(1 << 20), b""):
            h.update(chunk)
    return h.hexdigest()


def is_firewalled(rel_path):
    return any(rel_path.startswith(p) for p in FIREWALL_PREFIXES)


def is_self_citation(rel_path):
    return rel_path.startswith(OUTPUT_DIR_PREFIX)


def attempt_repair(rel_path):
    """One cheap repair: look for a unique filename continuation in the same directory."""
    directory = os.path.dirname(rel_path)
    fragment = os.path.basename(rel_path)
    abs_dir = os.path.join(REPO_ROOT, directory)
    if not os.path.isdir(abs_dir):
        return None, None
    candidates = [f for f in os.listdir(abs_dir) if f.startswith(fragment) and fragment]
    if len(candidates) == 1:
        repaired_rel = os.path.join(directory, candidates[0])
        return repaired_rel, f"unique filename in {directory} starting with {fragment!r}"
    return None, None


def main():
    with open(ROADMAP, encoding="utf-8") as f:
        lines = [l.rstrip("\n") for l in f if l.strip()]

    records = []
    hash_to_first_source_id = {}
    mtime_groups = defaultdict(list)

    for i, line in enumerate(lines, start=1):
        m = LINE_RE.match(line)
        if not m:
            print(f"FATAL: line {i} does not match <date> <time> <path>: {line!r}", file=sys.stderr)
            sys.exit(1)
        date, time_, rel_path = m.groups()
        source_id = f"S{i:04d}"
        file_mtime_roadmap = f"{date} {time_}"

        rec = {
            "source_id": source_id,
            "line_no": i,
            "roadmap_date": date,
            "roadmap_time": time_,
            "path": rel_path,
            "resolve": None,
            "repair_evidence": None,
            "sha256": None,
            "file_mtime": None,
            "dup": None,
            "mtime_block": None,
        }

        if is_firewalled(rel_path):
            rec["resolve"] = "FIREWALL-LIMITED"
            records.append(rec)
            continue

        abs_path = os.path.join(REPO_ROOT, rel_path)
        if os.path.isfile(abs_path):
            resolved_rel = rel_path
            rec["resolve"] = "RESOLVED"
        else:
            repaired_rel, evidence = attempt_repair(rel_path)
            if repaired_rel and os.path.isfile(os.path.join(REPO_ROOT, repaired_rel)):
                resolved_rel = repaired_rel
                rec["resolve"] = "REPAIRED"
                rec["repair_evidence"] = evidence
                rec["path"] = repaired_rel
            else:
                rec["resolve"] = "UNRESOLVABLE"
                records.append(rec)
                continue

        if is_self_citation(resolved_rel):
            rec["resolve"] = "SELF-CITATION-EXCLUDED"
            records.append(rec)
            continue

        abs_resolved = os.path.join(REPO_ROOT, resolved_rel)
        rec["sha256"] = sha256_of(abs_resolved)
        rec["file_mtime"] = int(os.path.getmtime(abs_resolved))
        mtime_groups[rec["file_mtime"]].append(source_id)

        if rec["sha256"] in hash_to_first_source_id:
            rec["dup"] = f"EXACT-DUPLICATE-OF:{hash_to_first_source_id[rec['sha256']]}"
        else:
            hash_to_first_source_id[rec["sha256"]] = source_id
            rec["dup"] = "UNIQUE"

        records.append(rec)

    # mtime_block assignment: >=5 files sharing exact same mtime = bulk block
    block_id = 0
    mtime_to_block = {}
    for mtime, sids in mtime_groups.items():
        if len(sids) >= 5:
            block_id += 1
            mtime_to_block[mtime] = f"BULK-{block_id:02d}"

    by_sid = {r["source_id"]: r for r in records}
    for mtime, block in mtime_to_block.items():
        for sid in mtime_groups[mtime]:
            by_sid[sid]["mtime_block"] = block

    with open(OUT_JSONL, "w", encoding="utf-8") as out:
        for r in records:
            out.write(json.dumps(r, ensure_ascii=False) + "\n")

    # summary
    from collections import Counter
    counts = Counter(r["resolve"] for r in records)
    dup_unique = sum(1 for r in records if r.get("dup") == "UNIQUE")
    dup_dupe = sum(1 for r in records if (r.get("dup") or "").startswith("EXACT-DUPLICATE-OF"))
    print("=== PHASE 0 — ROADMAP VALIDATION SUMMARY ===")
    print(f"total lines:            {len(records)}")
    for status, n in counts.most_common():
        print(f"  {status:22s} {n}")
    print(f"unique content (sha256): {dup_unique}")
    print(f"exact duplicates:        {dup_dupe}")
    print(f"bulk mtime blocks:       {len(mtime_to_block)}  (>=5 files sharing one mtime)")
    for mtime, block in sorted(mtime_to_block.items(), key=lambda kv: kv[1]):
        print(f"    {block}: {len(mtime_groups[mtime])} files at mtime {mtime}")
    print(f"output: {OUT_JSONL}")


if __name__ == "__main__":
    main()
