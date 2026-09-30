#!/usr/bin/env python3
"""P1c (A9) — close Phase 1: concat all per-batch ledger output into 02-FILES.jsonl and
03-CONTRIBUTIONS.jsonl (source_id order), assert every unique accessible file appears
exactly once, and attach date evidence (best_historical_date + basis) to each files.jsonl
record. Does not do the near-dup scan or source-register build (separate scripts)."""
import json
import os
import re
import subprocess
import sys

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")


def load_jsonl(path):
    if not os.path.isfile(path):
        return []
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def sid_num(sid):
    m = re.match(r"S(\d+)", sid)
    return int(m.group(1)) if m else -1


def best_historical_date(rec, roadmap_mtime):
    """explicit_dates[] (already covers internal/commission dates per the extraction
    contract) takes priority over file_mtime. Returns (date_or_None, basis).
    Falls back to the roadmap's own mtime (00-ROADMAP-VALIDATED.jsonl, computed at P0)
    when the ledger record itself left file_mtime null — mechanical gap-fill from
    already-captured data, never fabricated."""
    explicit = rec.get("explicit_dates") or []
    explicit = [d for d in explicit if d]
    if explicit:
        return min(explicit), "EXPLICIT"
    mtime = rec.get("file_mtime") or roadmap_mtime
    if mtime:
        return mtime, "MTIME"
    return None, "NONE"


def main():
    manifest = load_jsonl(os.path.join(CR, "01-BATCH-MANIFEST.jsonl"))
    done_batches = [r["batch_id"] for r in manifest if r["status"] == "DONE"]
    non_done = [r["batch_id"] for r in manifest if r["status"] != "DONE"]
    if non_done:
        print(f"FAIL: {len(non_done)} batches not DONE: {non_done[:10]}...")
        sys.exit(1)

    roadmap = load_jsonl(os.path.join(CR, "00-ROADMAP-VALIDATED.jsonl"))
    roadmap_mtime = {r["source_id"]: r.get("file_mtime") for r in roadmap}
    expected_ids = {
        r["source_id"] for r in roadmap
        if r["resolve"] in ("RESOLVED", "REPAIRED") and r.get("dup") == "UNIQUE"
    }

    all_files = []
    all_contribs = []
    for batch_id in sorted(done_batches, key=lambda b: int(b[1:])):
        ledger_dir = os.path.join(CR, "ledger", batch_id)
        files = load_jsonl(os.path.join(ledger_dir, "files.jsonl"))
        contribs = load_jsonl(os.path.join(ledger_dir, "contributions.jsonl"))
        for f in files:
            f["_batch_id"] = batch_id
        for c in contribs:
            c["_batch_id"] = batch_id
        all_files.extend(files)
        all_contribs.extend(contribs)

    all_files.sort(key=lambda r: sid_num(r["source_id"]))
    all_contribs.sort(key=lambda r: sid_num(r["source_id"]))

    # TERMINAL assertion: every unique accessible file appears exactly once
    got_ids = [f["source_id"] for f in all_files]
    got_set = set(got_ids)
    if len(got_ids) != len(got_set):
        from collections import Counter
        dupes = [sid for sid, n in Counter(got_ids).items() if n > 1]
        print(f"FAIL: {len(dupes)} source_ids appear more than once in ledger output: {dupes[:10]}")
        sys.exit(1)
    missing = expected_ids - got_set
    extra = got_set - expected_ids
    if missing or extra:
        print(f"FAIL: mismatch vs roadmap-eligible set. missing={len(missing)} extra={len(extra)}")
        if missing:
            print("  sample missing:", sorted(missing, key=sid_num)[:10])
        if extra:
            print("  sample extra:", sorted(extra, key=sid_num)[:10])
        sys.exit(1)

    # attach date evidence
    basis_counts = {}
    for f in all_files:
        date, basis = best_historical_date(f, roadmap_mtime.get(f["source_id"]))
        f["best_historical_date"] = date
        f["best_historical_date_basis"] = basis
        basis_counts[basis] = basis_counts.get(basis, 0) + 1

    with open(os.path.join(CR, "02-FILES.jsonl"), "w", encoding="utf-8") as fh:
        for f in all_files:
            fh.write(json.dumps(f, ensure_ascii=False) + "\n")
    with open(os.path.join(CR, "03-CONTRIBUTIONS.jsonl"), "w", encoding="utf-8") as fh:
        for c in all_contribs:
            fh.write(json.dumps(c, ensure_ascii=False) + "\n")

    print(f"OK: 02-FILES.jsonl written — {len(all_files)} records (assertion passed: "
          f"exactly matches {len(expected_ids)} roadmap-eligible unique files, no duplicates)")
    print(f"OK: 03-CONTRIBUTIONS.jsonl written — {len(all_contribs)} records")
    print(f"date-evidence basis counts: {basis_counts}")
    status_counts = {}
    for f in all_files:
        status_counts[f["status"]] = status_counts.get(f["status"], 0) + 1
    print(f"files.jsonl status counts: {status_counts}")


if __name__ == "__main__":
    main()
