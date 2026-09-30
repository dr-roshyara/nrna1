#!/usr/bin/env python3
"""P3a close — concatenate all ledger-p3a/RP00NN/pairs.jsonl into
31-RECONCILIATION-PAIRS.jsonl (pair_id order) and assert the TERMINAL condition:
every one of the 1,793 reconciliation_pairs derived by derive_reconciliation.py has
exactly one verdict, with valid closed-list enum values. Mirrors close_phase1.py's
concat-and-assert pattern.
"""
import json
import os
import re
import subprocess

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")
LEDGER = os.path.join(CR, "ledger-p3a")

RELATIONSHIP_ENUM = {"SAME", "REFINEMENT", "EXTENSION", "REDEFINITION", "REPLACEMENT",
                      "SPECIALIZATION", "DERIVED-FROM", "CONTINUATION", "HOMONYM",
                      "INDEPENDENT", "UNWITNESSED"}
BASIS_ENUM = {"CORROBORATED", "SOURCE-CLAIMED-ONLY", "INFERRED", "NONE"}
TYPE_COMPAT_ENUM = {"COMPATIBLE", "PARTIALLY-COMPATIBLE", "INCOMPATIBLE", "UNKNOWN"}
SID_RE = re.compile(r"\bS\d{4}\b")


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def pid_num(pid):
    m = re.match(r"RP(\d+)", pid)
    return int(m.group(1)) if m else -1


def main():
    derived = json.load(open(os.path.join(FAM, "_derived.json"), encoding="utf-8"))
    expected_pairs = derived["reconciliation_pairs"]
    expected_ids = {p["pair_id"] for p in expected_pairs}

    manifest = load_jsonl(os.path.join(FAM, "_batch_manifest_p3a.jsonl"))
    assert all(m["status"] == "DONE" for m in manifest), "not all P3a batches are DONE"

    all_verdicts = []
    seen_ids = set()
    for m in manifest:
        batch_id = m["batch_id"]
        path = os.path.join(LEDGER, batch_id, "pairs.jsonl")
        verdicts = load_jsonl(path)
        for v in verdicts:
            pid = v["pair_id"]
            assert pid not in seen_ids, f"duplicate verdict for {pid} (batch {batch_id})"
            seen_ids.add(pid)
            assert v["relationship"] in RELATIONSHIP_ENUM, f"{pid}: bad relationship {v['relationship']}"
            assert v["basis"] in BASIS_ENUM, f"{pid}: bad basis {v['basis']}"
            assert v["type_compatibility"] in TYPE_COMPAT_ENUM, f"{pid}: bad type_compatibility"
            v["_batch_id"] = batch_id
            all_verdicts.append(v)

    missing = expected_ids - seen_ids
    extra = seen_ids - expected_ids
    assert not missing, f"{len(missing)} pairs have no verdict: {sorted(missing)[:10]}"
    assert not extra, f"{len(extra)} verdicts reference unknown pair_ids: {sorted(extra)[:10]}"
    assert len(all_verdicts) == len(expected_pairs) == 1793, \
        f"count mismatch: {len(all_verdicts)} verdicts vs {len(expected_pairs)} expected pairs"

    all_verdicts.sort(key=lambda v: pid_num(v["pair_id"]))

    out_path = os.path.join(CR, "31-RECONCILIATION-PAIRS.jsonl")
    with open(out_path, "w", encoding="utf-8") as f:
        for v in all_verdicts:
            f.write(json.dumps(v, ensure_ascii=False) + "\n")

    from collections import Counter
    rel_counts = Counter(v["relationship"] for v in all_verdicts)
    basis_counts = Counter(v["basis"] for v in all_verdicts)
    tc_counts = Counter(v["type_compatibility"] for v in all_verdicts)

    print(f"OK: {out_path} written — {len(all_verdicts)} pair verdicts, TERMINAL assertions pass")
    print(f"relationship distribution: {dict(rel_counts)}")
    print(f"basis distribution: {dict(basis_counts)}")
    print(f"type_compatibility distribution: {dict(tc_counts)}")


if __name__ == "__main__":
    main()
