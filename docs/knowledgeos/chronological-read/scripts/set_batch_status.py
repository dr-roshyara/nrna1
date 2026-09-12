#!/usr/bin/env python3
"""Update one batch's status in 01-BATCH-MANIFEST.jsonl in place."""
import json
import os
import subprocess
import sys

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
MANIFEST = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read/01-BATCH-MANIFEST.jsonl")


def main(batch_id, status):
    rows = [json.loads(l) for l in open(MANIFEST, encoding="utf-8")]
    found = False
    for r in rows:
        if r["batch_id"] == batch_id:
            r["status"] = status
            found = True
    if not found:
        print(f"FAIL: {batch_id} not in manifest", file=sys.stderr)
        sys.exit(1)
    with open(MANIFEST, "w", encoding="utf-8") as f:
        for r in rows:
            f.write(json.dumps(r, ensure_ascii=False) + "\n")
    print(f"{batch_id} -> {status}")


if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("usage: set_batch_status.py <batch_id> <status>")
        sys.exit(2)
    main(sys.argv[1], sys.argv[2])
