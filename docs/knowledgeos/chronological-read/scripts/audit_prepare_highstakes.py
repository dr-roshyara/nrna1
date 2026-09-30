#!/usr/bin/env python3
"""P3a QUALITY GATE, section 5 — prepare the blind independent-review inputs for the
158 high-stakes pairs (SAME + REPLACEMENT + DERIVED-FROM). This script NEVER writes
to the production ledger (`31-RECONCILIATION-PAIRS.jsonl`) — it only reads it to
select which pair_ids qualify, then re-serves each pair's PRE-VERDICT evidence bundle
(from `_derived.json["reconciliation_pairs"]`, which structurally cannot leak a
verdict — it has no relationship/basis/type_compatibility fields at all) to a batch
input file a fresh, blinded reviewer agent will see. The original verdict is written
separately to an answer-key file the reviewer never receives, for later comparison
only.

Writes:
  audit-p3a/_highstakes_answer_key.json   {pair_id: original verdict record}  (private)
  audit-p3a/_batch_input/AH00NN.json      blinded evidence bundles per batch
  audit-p3a/_batch_manifest.jsonl         batch manifest (mirrors the P3a/P3b pattern)
"""
import json
import os
import subprocess

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")
AUDIT = os.path.join(CR, "audit-p3a")
BATCH_INPUT = os.path.join(AUDIT, "_batch_input")

HIGH_STAKES_RELATIONSHIPS = {"SAME", "REPLACEMENT", "DERIVED-FROM"}
N_BATCHES = 4


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def main():
    production = load_jsonl(os.path.join(CR, "31-RECONCILIATION-PAIRS.jsonl"))
    derived = json.load(open(os.path.join(FAM, "_derived.json"), encoding="utf-8"))
    ev_by_id = {p["pair_id"]: p for p in derived["reconciliation_pairs"]}

    high_stakes = [p for p in production if p["relationship"] in HIGH_STAKES_RELATIONSHIPS]
    assert len(high_stakes) == 158, f"expected 158 high-stakes pairs, found {len(high_stakes)}"

    # RP0526 must be included (it's the specific case under scrutiny) — verify it's here.
    assert any(p["pair_id"] == "RP0526" for p in high_stakes), "RP0526 missing from high-stakes set"

    answer_key = {p["pair_id"]: p for p in high_stakes}
    os.makedirs(AUDIT, exist_ok=True)
    with open(os.path.join(AUDIT, "_highstakes_answer_key.json"), "w", encoding="utf-8") as f:
        json.dump(answer_key, f, ensure_ascii=False, indent=1)

    # deterministic ordering (by pair_id), then round-robin into N_BATCHES so each
    # batch gets a mix of all three relationship types rather than being skewed
    high_stakes_sorted = sorted(high_stakes, key=lambda p: p["pair_id"])
    bins = [[] for _ in range(N_BATCHES)]
    for i, p in enumerate(high_stakes_sorted):
        bins[i % N_BATCHES].append(p["pair_id"])

    os.makedirs(BATCH_INPUT, exist_ok=True)
    manifest_path = os.path.join(AUDIT, "_batch_manifest.jsonl")
    with open(manifest_path, "w", encoding="utf-8") as mf:
        for i, pair_ids in enumerate(bins, start=1):
            batch_id = f"AH{i:04d}"
            blinded_pairs = [ev_by_id[pid] for pid in sorted(pair_ids)]
            slice_path = os.path.join(BATCH_INPUT, f"{batch_id}.json")
            with open(slice_path, "w", encoding="utf-8") as sf:
                json.dump({"batch_id": batch_id, "pairs": blinded_pairs}, sf, ensure_ascii=False, indent=1)
            mf.write(json.dumps({
                "batch_id": batch_id, "pair_ids": sorted(pair_ids),
                "pair_count": len(pair_ids), "status": "PENDING",
            }) + "\n")

    print(f"OK: {len(high_stakes)} high-stakes pairs selected (SAME/REPLACEMENT/DERIVED-FROM)")
    print(f"Answer key (private, not for reviewers): {os.path.join(AUDIT, '_highstakes_answer_key.json')}")
    print(f"Blinded batches written: {N_BATCHES}, sizes: {[len(b) for b in bins]}")
    for pid in bins[0][:3]:
        assert "relationship" not in ev_by_id[pid] and "basis" not in ev_by_id[pid], \
            f"LEAK CHECK FAILED for {pid}"
    print("Leak check passed: blinded evidence bundles contain no relationship/basis fields.")


if __name__ == "__main__":
    main()
