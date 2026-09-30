#!/usr/bin/env python3
"""P2b batching: partition the 2,497 labels into N balanced batches (LPT bin-packing on
row_count, so each batch's dispatched agent gets roughly equal total work regardless of
how skewed individual labels' row counts are), and write one input-slice JSON per batch
containing ONLY that batch's labels' full derived data (never the whole 56MB
_derived.json) — mirrors P1's print_batch.py giving each agent just its own slice.

Writes:
  20-FAMILIES/_batch_manifest.jsonl   {batch_id, labels[], total_rows, status}
  20-FAMILIES/_batch_input/LB0001.json ... one per batch, {label: family_data, ...}
"""
import json
import os
import subprocess

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")
BATCH_INPUT_DIR = os.path.join(FAM, "_batch_input")

N_BATCHES = 25


def main():
    derived = json.load(open(os.path.join(FAM, "_derived.json"), encoding="utf-8"))
    families = derived["families"]
    nodes = derived["nodes"]

    labels_sorted = sorted(families.items(), key=lambda kv: -kv[1]["row_count"])

    bins = [{"labels": [], "total_rows": 0} for _ in range(N_BATCHES)]
    for label, fam in labels_sorted:
        target = min(bins, key=lambda b: b["total_rows"])
        target["labels"].append(label)
        target["total_rows"] += fam["row_count"]

    os.makedirs(BATCH_INPUT_DIR, exist_ok=True)
    manifest = []
    for i, b in enumerate(bins, 1):
        batch_id = f"LB{i:04d}"   # Label-family Batch
        manifest.append({"batch_id": batch_id, "labels": sorted(b["labels"]),
                          "total_rows": b["total_rows"], "status": "PENDING"})
        slice_data = {}
        for label in b["labels"]:
            slice_data[label] = {
                "node_metadata": {k: v for k, v in nodes[label].items() if k != "sources"} | {
                    "sources": nodes[label]["sources"]},
                "family": families[label],
            }
        with open(os.path.join(BATCH_INPUT_DIR, f"{batch_id}.json"), "w", encoding="utf-8") as fh:
            json.dump(slice_data, fh, ensure_ascii=False, indent=1)

    with open(os.path.join(FAM, "_batch_manifest.jsonl"), "w", encoding="utf-8") as fh:
        for m in manifest:
            fh.write(json.dumps(m, ensure_ascii=False) + "\n")

    print(f"OK: {N_BATCHES} batches written to {BATCH_INPUT_DIR}")
    for m in manifest:
        print(f"  {m['batch_id']}: {len(m['labels'])} labels, {m['total_rows']} total rows")


if __name__ == "__main__":
    main()
