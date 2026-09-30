#!/usr/bin/env python3
"""P3a — verify one pair-reconciliation batch's ledger output. Mirrors
verify_families_batch.py's lightweight discipline: mechanical checks only (closed-list
enum membership, citation reality, coverage), never a re-judgment of the verdicts
themselves (R13 — agents interpret, scripts don't second-guess).

Usage: python3 verify_reconciliation_pairs_batch.py RP0001
Expects ledger-p3a/RP00NN/pairs.jsonl — one JSON object per assigned pair_id with the
required verdict fields.
"""
import json
import os
import re
import subprocess
import sys

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")
LEDGER = os.path.join(CR, "ledger-p3a")

RELATIONSHIP_ENUM = {"SAME", "REFINEMENT", "EXTENSION", "REDEFINITION", "REPLACEMENT",
                      "SPECIALIZATION", "DERIVED-FROM", "CONTINUATION", "HOMONYM",
                      "INDEPENDENT", "UNWITNESSED"}
BASIS_ENUM = {"CORROBORATED", "SOURCE-CLAIMED-ONLY", "INFERRED", "NONE"}
TYPE_COMPAT_ENUM = {"COMPATIBLE", "PARTIALLY-COMPATIBLE", "INCOMPATIBLE", "UNKNOWN"}
NEGATIVE_LABEL_ENUM = {"NEGATIVE-BOUNDED", "NEGATIVE-CENSUS", None}

SID_RE = re.compile(r"\bS\d{4}\b")


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def fail(msg):
    print(f"FAIL: {msg}")
    sys.exit(1)


def main():
    if len(sys.argv) != 2:
        fail("usage: verify_reconciliation_pairs_batch.py <batch_id>")
    batch_id = sys.argv[1]

    manifest = load_jsonl(os.path.join(FAM, "_batch_manifest_p3a.jsonl"))
    entry = next((m for m in manifest if m["batch_id"] == batch_id), None)
    if entry is None:
        fail(f"{batch_id} not found in _batch_manifest_p3a.jsonl")
    expected_pair_ids = set(entry["pair_ids"])

    slice_data = json.load(open(os.path.join(FAM, "_batch_input", f"{batch_id}.json"), encoding="utf-8"))
    pair_by_id = {p["pair_id"]: p for p in slice_data["pairs"]}

    all_files = load_jsonl(os.path.join(CR, "02-FILES.jsonl"))
    all_source_ids = {f["source_id"] for f in all_files}

    ledger_path = os.path.join(LEDGER, batch_id, "pairs.jsonl")
    if not os.path.exists(ledger_path):
        fail(f"missing {ledger_path}")
    verdicts = load_jsonl(ledger_path)

    seen_ids = set()
    for v in verdicts:
        pid = v.get("pair_id")
        if pid not in expected_pair_ids:
            fail(f"{pid} not assigned to {batch_id}")
        if pid in seen_ids:
            fail(f"duplicate verdict for {pid}")
        seen_ids.add(pid)

        src = pair_by_id[pid]
        if v.get("a") != src["a"] or v.get("b") != src["b"]:
            fail(f"{pid}: a/b mismatch vs assigned pair ({v.get('a')},{v.get('b')}) != ({src['a']},{src['b']})")

        rel = v.get("relationship")
        if rel not in RELATIONSHIP_ENUM:
            fail(f"{pid}: relationship '{rel}' not in closed list")
        basis = v.get("basis")
        if basis not in BASIS_ENUM:
            fail(f"{pid}: basis '{basis}' not in closed list")
        tc = v.get("type_compatibility")
        if tc not in TYPE_COMPAT_ENUM:
            fail(f"{pid}: type_compatibility '{tc}' not in closed list")

        if rel == "INDEPENDENT":
            neg_label = v.get("negative_verdict_label")
            if neg_label != "NEGATIVE-CENSUS":
                fail(f"{pid}: relationship INDEPENDENT requires negative_verdict_label == "
                     f"NEGATIVE-CENSUS (a stated corpus-wide search), got '{neg_label}' — "
                     f"per A11's NEGATIVE-VERDICT BAR, a group/batch-bounded null search is "
                     f"UNWITNESSED/NEGATIVE-BOUNDED, never INDEPENDENT")
            if not v.get("corpus_wide_search_note"):
                fail(f"{pid}: INDEPENDENT verdict missing corpus_wide_search_note "
                     f"(what was searched, corpus-wide, that came back empty)")
        if rel == "HOMONYM":
            if not (v.get("what_says_this") and "SEPARATION" in json.dumps(v.get("what_says_this"))
                    or v.get("homonym_positive_evidence")):
                fail(f"{pid}: relationship HOMONYM requires positive evidence of two different "
                     f"concepts (a SOURCE-CLAIMED-SEPARATION row, or documented different "
                     f"responsibility + no continuity) — 'homonym_positive_evidence' field "
                     f"is empty")

        if not v.get("what_says_this"):
            fail(f"{pid}: missing 'what_says_this' (required per A11 pair record)")
        if not v.get("what_would_make_this_wrong"):
            fail(f"{pid}: missing 'what_would_make_this_wrong' (required per A11 pair record)")

        # citation reality check across all string-valued evidence fields
        blob = json.dumps(v)
        for sid in set(SID_RE.findall(blob)):
            if sid not in all_source_ids:
                fail(f"{pid}: cites {sid} which is not a real source_id in 02-FILES.jsonl")

    missing = expected_pair_ids - seen_ids
    if missing:
        fail(f"{batch_id}: {len(missing)} assigned pairs have no verdict: {sorted(missing)[:10]}")

    print(f"PASS: {batch_id} — {len(verdicts)} pair verdicts verified")


if __name__ == "__main__":
    main()
