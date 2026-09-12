#!/usr/bin/env python3
"""Print a batch's file list in the exact '  Sxxxx | path | mtime | block' format for
pasting verbatim into a dispatch prompt -- avoids hand-transcription typos."""
import json
import os
import subprocess
import sys

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
MANIFEST = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read/01-BATCH-MANIFEST.jsonl")

def main(batch_id):
    rows = [json.loads(l) for l in open(MANIFEST, encoding="utf-8")]
    b = next(r for r in rows if r["batch_id"] == batch_id)
    for sid, path, mtime, block in zip(b["source_ids"], b["paths"], b["mtimes"], b["mtime_blocks"]):
        print(f"  {sid} | {path} | {mtime} | {block}")

if __name__ == "__main__":
    main(sys.argv[1])
