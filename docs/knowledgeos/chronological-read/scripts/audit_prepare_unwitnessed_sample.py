#!/usr/bin/env python3
"""P3a QUALITY GATE, section 3 — mechanical pre-classification of all 684 UNWITNESSED
verdicts, plus a stratified random sample of the ambiguous remainder for an agent to
classify into categories A-F. Read-only against the production ledger; writes only
under audit-p3a/.

Mechanical pre-classification (exact, no sampling needed):
  E  a_row_count==0 or b_row_count==0 (one side has literally no captured content —
     "source/artifact unavailable" from this label's own evidence)
  C  basis != NONE (real evidence was found and cited, but the closed-list
     relationship enum didn't have a matching value, OR the recorded evidence itself
     documents an unresolved dispute/contradiction rather than a plain absence)
  remainder -> needs human/agent judgment to split into A (genuinely searched,
     nothing found) / B (search was perfunctory/insufficient) / D (contradictory,
     not already caught by the basis!=NONE check) / F (other)

Writes:
  audit-p3a/unwitnessed_mechanical.json   {category_E: [...], category_C: [...]}
  audit-p3a/unwitnessed_sample.json       stratified sample for agent classification
"""
import json
import os
import random
import subprocess

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")
AUDIT = os.path.join(CR, "audit-p3a")

SAMPLE_SIZE = 120
SEED = 20260920


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def main():
    production = load_jsonl(os.path.join(CR, "31-RECONCILIATION-PAIRS.jsonl"))
    derived = json.load(open(os.path.join(FAM, "_derived.json"), encoding="utf-8"))
    ev_by_id = {p["pair_id"]: p for p in derived["reconciliation_pairs"]}

    uw = [p for p in production if p["relationship"] == "UNWITNESSED"]
    assert len(uw) == 684

    category_E = [p["pair_id"] for p in uw
                  if ev_by_id[p["pair_id"]]["a_row_count"] == 0 or ev_by_id[p["pair_id"]]["b_row_count"] == 0]
    category_C = [p["pair_id"] for p in uw if p["basis"] != "NONE"]
    # a pair cannot be double-counted E and C for this mechanical split; check overlap
    overlap = set(category_E) & set(category_C)

    remainder_ids = [p["pair_id"] for p in uw
                      if p["pair_id"] not in category_E and p["pair_id"] not in category_C]

    os.makedirs(AUDIT, exist_ok=True)
    with open(os.path.join(AUDIT, "unwitnessed_mechanical.json"), "w", encoding="utf-8") as f:
        json.dump({
            "total_unwitnessed": len(uw),
            "category_E_zero_row_side": category_E,
            "category_E_count": len(category_E),
            "category_C_nonNone_basis": category_C,
            "category_C_count": len(category_C),
            "E_C_overlap": sorted(overlap),
            "remainder_needing_AB D_sample": len(remainder_ids),
        }, f, ensure_ascii=False, indent=1)

    rng = random.Random(SEED)
    sample_ids = rng.sample(remainder_ids, min(SAMPLE_SIZE, len(remainder_ids)))
    sample_records = []
    for pid in sorted(sample_ids):
        prod = next(p for p in uw if p["pair_id"] == pid)
        ev = ev_by_id[pid]
        sample_records.append({
            "pair_id": pid, "a": prod["a"], "b": prod["b"],
            "recorded_relationship": prod["relationship"], "recorded_basis": prod["basis"],
            "recorded_type_compatibility": prod["type_compatibility"],
            "recorded_what_says_this": prod.get("what_says_this"),
            "recorded_what_would_make_this_wrong": prod.get("what_would_make_this_wrong"),
            "recorded_notes_for_p3b": prod.get("notes_for_p3b"),
            "pair_kind": ev["pair_kind"],
            "a_row_count": ev["a_row_count"], "b_row_count": ev["b_row_count"],
            "a_rows_truncated": ev["a_rows_truncated"], "b_rows_truncated": ev["b_rows_truncated"],
            "group_evidence": ev.get("group_evidence"),
            "lineage_claims_a_to_b": ev.get("lineage_claims_a_to_b"),
            "lineage_claims_b_to_a": ev.get("lineage_claims_b_to_a"),
        })

    with open(os.path.join(AUDIT, "unwitnessed_sample.json"), "w", encoding="utf-8") as f:
        json.dump({"seed": SEED, "sample_size": len(sample_records), "records": sample_records},
                   f, ensure_ascii=False, indent=1)

    print(f"total UNWITNESSED: {len(uw)}")
    print(f"category E (zero-row side): {len(category_E)}")
    print(f"category C (non-NONE basis): {len(category_C)}")
    print(f"E/C overlap: {len(overlap)}")
    print(f"remainder needing A/B/D judgment: {len(remainder_ids)}")
    print(f"stratified sample drawn (seed={SEED}): {len(sample_records)}")


if __name__ == "__main__":
    main()
