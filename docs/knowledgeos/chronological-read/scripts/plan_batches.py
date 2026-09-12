#!/usr/bin/env python3
"""P0.5 — partition validated, unique, non-firewalled files into batches of BATCH_SIZE."""
import json
import os
import subprocess

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
IN_JSONL = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read/00-ROADMAP-VALIDATED.jsonl")
OUT_JSONL = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read/01-BATCH-MANIFEST.jsonl")
BATCH_SIZE = 40

def main():
    records = [json.loads(l) for l in open(IN_JSONL, encoding="utf-8")]
    eligible = [
        r for r in records
        if r["resolve"] in ("RESOLVED", "REPAIRED") and r.get("dup") == "UNIQUE"
    ]
    eligible.sort(key=lambda r: r["line_no"])  # source_id order = roadmap order

    batches = []
    i = 0
    bnum = 0
    while i < len(eligible):
        chunk = eligible[i:i + BATCH_SIZE]
        bnum += 1
        batches.append({
            "batch_id": f"B{bnum:04d}",
            "source_ids": [r["source_id"] for r in chunk],
            "paths": [r["path"] for r in chunk],
            "mtimes": [r["file_mtime"] for r in chunk],
            "mtime_blocks": [r.get("mtime_block") for r in chunk],
            "first_mtime": chunk[0]["file_mtime"],
            "last_mtime": chunk[-1]["file_mtime"],
            "status": "PENDING",
            "dispatched_at": None,
            "verified": False,
            "split_reason": None,
        })
        i += BATCH_SIZE

    with open(OUT_JSONL, "w", encoding="utf-8") as out:
        for b in batches:
            out.write(json.dumps(b, ensure_ascii=False) + "\n")

    print("=== PHASE 0.5 — BATCH PLAN SUMMARY ===")
    print(f"eligible files:  {len(eligible)}")
    print(f"batches created: {len(batches)}  (batch size {BATCH_SIZE})")
    print(f"last batch size: {len(batches[-1]['source_ids'])}")
    print(f"output: {OUT_JSONL}")

if __name__ == "__main__":
    main()
