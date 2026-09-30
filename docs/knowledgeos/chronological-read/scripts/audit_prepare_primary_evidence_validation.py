#!/usr/bin/env python3
"""PRIMARY Evidence Validation Experiment -- sampling step. Read-only against
audit-p3a/P3A-UNDEFINED-RELATIONSHIP-PROVENANCE.jsonl and the production ledger
(used only to build a PRIVATE answer key, never shown to blind reviewers).

Population: the 1,104 candidate pairs with >=1 category-B (PRIMARY-file) signal.

Stratification (per pair, from the signal(s) it carries):
  DEP_ONLY              only DEPENDENCY signals (all resolution_method=
                        DEPENDENCY_EXACT_LABEL_MATCH -- this IS "exact-label
                        relationship" per the request's stratum 5)
  LINEAGE_SOURCEID_ONLY only LINEAGE_CLAIM signals, all resolved via
                        LINEAGE_CLAIM_TARGET_IS_SOURCE_ID (request's stratum 4)
  LINEAGE_SUBSTRING_ONLY only LINEAGE_CLAIM signals, all resolved via
                        LINEAGE_CLAIM_TARGET_CONTAINS_LABEL_SUBSTRING (stratum 6)
  LINEAGE_MIXED         only LINEAGE_CLAIM signals, but the pair carries both
                        source-id- and substring-resolved claims
  BOTH_DEP_AND_LINEAGE  the pair carries at least one DEPENDENCY signal AND at
                        least one LINEAGE_CLAIM signal (request's stratum 3)
These five strata jointly cover the six dimensions requested (dependencies-only,
lineage-only, both, source-id-based, exact-label, non-exact-label) since
"exact-label" is definitionally identical to "has a DEPENDENCY signal" in this
corpus (dependencies are only ever exact-string matches, by construction of
derive_reconciliation.py's own matching rule).

Allocation: proportional to stratum size, rounded, with a minimum floor of 5 per
non-empty stratum (documented, not silently applied) and the remainder assigned
to the largest stratum so the total is exactly 100. Sampling is `random.Random`
with a fixed, recorded seed, `random.sample` (without replacement) within each
stratum.

Writes:
  audit-p3a/primary_validation_answer_key.json   {pv_id: {candidate_pair_id,
      existing_p3a_pair_id, existing_p3a_relationship, stratum}}  (PRIVATE)
  audit-p3a/_batch_input/PV_batch{N}.json        4 blinded batches of 25 pairs,
      each pair given a neutral pv_id (no RP#### number, no verdict, no stratum
      label) plus every signal it carries and enough raw-file navigation info
      (file path, source_id, exact quoted/extracted text) to look the evidence
      up directly in the source file -- exactly as much as a first-time
      reviewer needs, no more.
"""
import json
import os
import random
import subprocess
from collections import defaultdict

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
AUDIT = os.path.join(CR, "audit-p3a")
BATCH_INPUT = os.path.join(AUDIT, "_batch_input")

SEED = 20260921
TOTAL_SAMPLE = 100
N_BATCHES = 4
MIN_PER_STRATUM = 5


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def classify_stratum(signals):
    types = set(s["signal_type"] for s in signals)
    methods = set(s["resolution_method"] for s in signals if s["provenance_level"] == "B")
    has_dep = "DEPENDENCY" in types
    has_lin = "LINEAGE_CLAIM" in types
    has_sid = "LINEAGE_CLAIM_TARGET_IS_SOURCE_ID" in methods
    has_sub = "LINEAGE_CLAIM_TARGET_CONTAINS_LABEL_SUBSTRING" in methods
    if has_dep and has_lin:
        return "BOTH_DEP_AND_LINEAGE"
    if has_dep:
        return "DEP_ONLY"
    if has_sid and has_sub:
        return "LINEAGE_MIXED"
    if has_sid:
        return "LINEAGE_SOURCEID_ONLY"
    if has_sub:
        return "LINEAGE_SUBSTRING_ONLY"
    return "OTHER"  # should not occur; safety net


def main():
    recs = load_jsonl(os.path.join(AUDIT, "P3A-UNDEFINED-RELATIONSHIP-PROVENANCE.jsonl"))
    by_pair = defaultdict(list)
    for r in recs:
        by_pair[r["candidate_pair_id"]].append(r)

    production = load_jsonl(os.path.join(CR, "31-RECONCILIATION-PAIRS.jsonl"))
    prod_by_pair = {tuple(sorted([p["a"], p["b"]])): p for p in production}

    primary_pairs = {}
    for pid, sigs in by_pair.items():
        if any(s["provenance_level"] == "B" for s in sigs):
            primary_pairs[pid] = sigs
    assert len(primary_pairs) == 1104, f"expected 1104 PRIMARY pairs, got {len(primary_pairs)}"

    strata = defaultdict(list)
    for pid, sigs in primary_pairs.items():
        strata[classify_stratum(sigs)].append(pid)

    print("Population by stratum:")
    for k, v in sorted(strata.items(), key=lambda kv: -len(kv[1])):
        print(f"  {k}: {len(v)}")

    # allocation: proportional, floor MIN_PER_STRATUM, remainder to largest stratum
    pop_size = sum(len(v) for v in strata.values())
    alloc = {}
    for k, v in strata.items():
        n = round(TOTAL_SAMPLE * len(v) / pop_size)
        alloc[k] = max(MIN_PER_STRATUM, n) if len(v) >= MIN_PER_STRATUM else len(v)
    diff = TOTAL_SAMPLE - sum(alloc.values())
    largest = max(strata, key=lambda k: len(strata[k]))
    alloc[largest] += diff
    for k in alloc:
        alloc[k] = min(alloc[k], len(strata[k]))
    shortfall = TOTAL_SAMPLE - sum(alloc.values())
    if shortfall > 0:
        # redistribute shortfall to strata with remaining capacity, largest first
        for k in sorted(strata, key=lambda k: -len(strata[k])):
            capacity = len(strata[k]) - alloc[k]
            take = min(capacity, shortfall)
            alloc[k] += take
            shortfall -= take
            if shortfall <= 0:
                break

    print("\nAllocation (target 100):")
    for k, v in sorted(alloc.items(), key=lambda kv: -kv[1]):
        print(f"  {k}: {v} (of {len(strata[k])} available)")
    print(f"  TOTAL: {sum(alloc.values())}")

    rng = random.Random(SEED)
    sampled_pair_ids = []
    stratum_of = {}
    for k, n in alloc.items():
        chosen = rng.sample(strata[k], n)
        sampled_pair_ids.extend(chosen)
        for c in chosen:
            stratum_of[c] = k

    rng.shuffle(sampled_pair_ids)  # so batches mix strata, not grouped

    answer_key = {}
    blinded_records = []
    for i, pid in enumerate(sampled_pair_ids, start=1):
        pv_id = f"PV{i:04d}"
        a, b = pid.split("__")
        sigs = primary_pairs[pid]
        existing = prod_by_pair.get(tuple(sorted([a, b])))
        answer_key[pv_id] = {
            "candidate_pair_id": pid, "stratum": stratum_of[pid],
            "existing_p3a_pair_id": existing["pair_id"] if existing else None,
            "existing_p3a_relationship": existing["relationship"] if existing else None,
            "existing_p3a_basis": existing["basis"] if existing else None,
        }
        blinded_signals = []
        for s in sigs:
            if s["provenance_level"] != "B":
                continue  # only show the PRIMARY-provenance signal(s) for this pair
            blinded_signals.append({
                "signal_type": s["signal_type"],
                "source_file": s["source_file"],
                "source_id": s["source_id"],
                "row_label": s["row_label"],
                "original_text": s["original_text"],
                "dependency_value": s["dependency_value"],
                "lineage_claim_kind": s["lineage_claim_kind"],
                "lineage_claim_target": s["lineage_claim_target"],
                "lineage_claim_quote": s["lineage_claim_quote"],
                "target_label": s["target_label"],
                "target_source_id": s["target_source_id"],
                "target_file": s["target_file"],
            })
        blinded_records.append({
            "pv_id": pv_id, "object_a": a, "object_b": b,
            "signals": blinded_signals,
        })

    os.makedirs(AUDIT, exist_ok=True)
    with open(os.path.join(AUDIT, "primary_validation_answer_key.json"), "w", encoding="utf-8") as f:
        json.dump(answer_key, f, ensure_ascii=False, indent=1)

    os.makedirs(BATCH_INPUT, exist_ok=True)
    per_batch = TOTAL_SAMPLE // N_BATCHES
    for b_i in range(N_BATCHES):
        start = b_i * per_batch
        end = start + per_batch if b_i < N_BATCHES - 1 else TOTAL_SAMPLE
        batch = blinded_records[start:end]
        with open(os.path.join(BATCH_INPUT, f"PV_batch{b_i+1}.json"), "w", encoding="utf-8") as f:
            json.dump({"batch_id": f"PV_batch{b_i+1}", "pairs": batch}, f, ensure_ascii=False, indent=1)
        print(f"batch PV_batch{b_i+1}: {len(batch)} pairs")

    # leak check -- look for an actual P3a pair_id token or the answer-key field
    # name, not the generic English word "verdict" (which appears legitimately in
    # unrelated corpus content, e.g. a label about the corpus's own internal
    # verdicts on a mathematical question)
    import re as _re
    blob = json.dumps(blinded_records)
    assert "existing_p3a" not in blob, "LEAK CHECK FAILED: answer-key field name present"
    assert not _re.search(r"\bRP\d{4}\b", blob), "LEAK CHECK FAILED: a P3a pair_id token is present"
    print("\nLeak check passed: blinded batch files contain no P3a pair_id or verdict data.")
    print(f"Seed: {SEED} | Population: {pop_size} | Sample: {TOTAL_SAMPLE}")


if __name__ == "__main__":
    main()
