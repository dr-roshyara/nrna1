#!/usr/bin/env python3
"""Run only after verify_reconciliation_pairs_batch.py <batch_id> has PASSED. Flips
that batch's status to DONE in _batch_manifest_p3a.jsonl. Mirrors the P1/P2b
manifest-status-update step (merge_batch.py's bookkeeping half).
"""
import json
import os
import subprocess
import sys

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
FAM = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read/20-FAMILIES")
MANIFEST = os.path.join(FAM, "_batch_manifest_p3a.jsonl")


def main():
    if len(sys.argv) != 2:
        print("usage: mark_p3a_batch_done.py <batch_id>")
        sys.exit(1)
    batch_id = sys.argv[1]
    rows = [json.loads(l) for l in open(MANIFEST, encoding="utf-8") if l.strip()]
    found = False
    for r in rows:
        if r["batch_id"] == batch_id:
            r["status"] = "DONE"
            found = True
    if not found:
        print(f"FAIL: {batch_id} not in manifest")
        sys.exit(1)
    with open(MANIFEST, "w", encoding="utf-8") as f:
        for r in rows:
            f.write(json.dumps(r) + "\n")
    done = sum(1 for r in rows if r["status"] == "DONE")
    print(f"OK: {batch_id} marked DONE ({done}/{len(rows)} done)")


if __name__ == "__main__":
    main()
