#!/usr/bin/env python3
"""P3b — bin-pack the 2,497 per-object roll-ups into N_BATCHES balanced batches (LPT
by weight = row_count + 3*pair_count + 2*len(completeness_absences), since pairs and
absence-searches cost more agent time per unit than a plain row). Mirrors
plan_families_batches.py / plan_reconciliation_pairs_batches.py's proven pattern. A
label is never split across batches.

Writes `20-FAMILIES/_batch_manifest_p3b.jsonl` and one input-slice JSON per batch at
`20-FAMILIES/_batch_input/OB00NN.json`.
"""
import json
import os
import subprocess

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")
BATCH_INPUT = os.path.join(FAM, "_batch_input")

N_BATCHES = 30


def weight_of(obj):
    return obj["row_count"] + 3 * obj["pair_count"] + 2 * len(obj["completeness_absences"])


def main():
    derived = json.load(open(os.path.join(FAM, "_derived.json"), encoding="utf-8"))
    objects = derived["reconciliation_objects"]

    items = sorted(objects.items(), key=lambda kv: weight_of(kv[1]), reverse=True)

    bins = [{"weight": 0, "labels": []} for _ in range(N_BATCHES)]
    for label, obj in items:
        lightest = min(bins, key=lambda b: b["weight"])
        lightest["labels"].append(label)
        lightest["weight"] += weight_of(obj)

    os.makedirs(BATCH_INPUT, exist_ok=True)
    manifest_path = os.path.join(FAM, "_batch_manifest_p3b.jsonl")
    with open(manifest_path, "w", encoding="utf-8") as mf:
        for i, b in enumerate(bins, start=1):
            batch_id = f"OB{i:04d}"
            batch_labels = sorted(b["labels"])
            slice_path = os.path.join(BATCH_INPUT, f"{batch_id}.json")
            with open(slice_path, "w", encoding="utf-8") as sf:
                json.dump({"batch_id": batch_id,
                           "objects": {l: objects[l] for l in batch_labels}},
                          sf, ensure_ascii=False, indent=1)
            mf.write(json.dumps({
                "batch_id": batch_id,
                "labels": batch_labels,
                "label_count": len(batch_labels),
                "weight": b["weight"],
                "status": "PENDING",
            }) + "\n")

    counts = [len(b["labels"]) for b in bins]
    weights = [b["weight"] for b in bins]
    print(f"OK: {manifest_path} written — {N_BATCHES} batches")
    print(f"label_count per batch: min={min(counts)} max={max(counts)}")
    print(f"weight per batch: min={min(weights)} max={max(weights)}")


if __name__ == "__main__":
    main()
