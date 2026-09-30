#!/usr/bin/env python3
"""P3a (A11) — bin-pack the 1,793 reconciliation pairs into N_BATCHES balanced batches
(LPT: sort by weight descending, assign each to the currently-lightest bin), mirroring
plan_families_batches.py's proven pattern from P2b. weight = a_row_count + b_row_count
(a pair touching two heavily-evidenced labels costs more agent-reading-time than one
touching two singletons). A pair is never split across batches.

Writes `20-FAMILIES/_batch_manifest_p3a.jsonl` and one input-slice JSON per batch at
`20-FAMILIES/_batch_input/RP00NN.json` (containing only that batch's pair records).
"""
import json
import os
import subprocess

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")
BATCH_INPUT = os.path.join(FAM, "_batch_input")

N_BATCHES = 30


def main():
    derived = json.load(open(os.path.join(FAM, "_derived.json"), encoding="utf-8"))
    pairs = derived["reconciliation_pairs"]

    weighted = sorted(pairs, key=lambda p: p["a_row_count"] + p["b_row_count"], reverse=True)

    bins = [{"weight": 0, "pairs": []} for _ in range(N_BATCHES)]
    for p in weighted:
        w = p["a_row_count"] + p["b_row_count"]
        lightest = min(bins, key=lambda b: b["weight"])
        lightest["pairs"].append(p)
        lightest["weight"] += w

    os.makedirs(BATCH_INPUT, exist_ok=True)
    manifest_path = os.path.join(FAM, "_batch_manifest_p3a.jsonl")
    with open(manifest_path, "w", encoding="utf-8") as mf:
        for i, b in enumerate(bins, start=1):
            batch_id = f"RP{i:04d}"
            batch_pairs = sorted(b["pairs"], key=lambda p: p["pair_id"])
            slice_path = os.path.join(BATCH_INPUT, f"{batch_id}.json")
            with open(slice_path, "w", encoding="utf-8") as sf:
                json.dump({"batch_id": batch_id, "pairs": batch_pairs}, sf, ensure_ascii=False, indent=1)
            mf.write(json.dumps({
                "batch_id": batch_id,
                "pair_ids": [p["pair_id"] for p in batch_pairs],
                "pair_count": len(batch_pairs),
                "weight": b["weight"],
                "status": "PENDING",
            }) + "\n")

    counts = [len(b["pairs"]) for b in bins]
    weights = [b["weight"] for b in bins]
    print(f"OK: {manifest_path} written — {N_BATCHES} batches")
    print(f"pair_count per batch: min={min(counts)} max={max(counts)}")
    print(f"weight per batch: min={min(weights)} max={max(weights)}")


if __name__ == "__main__":
    main()
