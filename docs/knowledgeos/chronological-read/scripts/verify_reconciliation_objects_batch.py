#!/usr/bin/env python3
"""P3b — verify one per-object roll-up batch's ledger output. Mechanical checks only
(closed-list enum membership, citation reality, coverage) — never re-judges the
verdicts themselves (R13).

Usage: python3 verify_reconciliation_objects_batch.py OB0001
Expects ledger-p3b/OB00NN/objects.jsonl — one JSON object per assigned label with the
required roll-up fields.
"""
import json
import os
import re
import subprocess
import sys

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")
LEDGER = os.path.join(CR, "ledger-p3b")

SEMANTIC_STATUS_ENUM = {"RECONCILED", "IDENTITY-UNWITNESSED", "HOMONYM-SPLIT", "CONTESTED"}
TYPE_STATUS_ENUM = {"CLOSED", "INCOMPLETE", "UNTYPED"}
MATH_STATUS_ENUM = {"CONSISTENT", "INCONSISTENT", "UNDER-SPECIFIED",
                     "UNDECIDABLE-FROM-CORPUS", "NOT-APPLICABLE"}
LAYER_ENUM = {"FOUNDATIONAL", "DERIVED", "OPERATIONAL", "META-THEORETICAL", "LAYER-UNRESOLVED"}
EDGE_KIND_ENUM = {"DEFINITIONAL", "DERIVATIONAL", "USAGE", "EXPLANATORY", "VALIDATION", "GOVERNANCE"}
BIRTH_STATUS_PREFIXES = ("ESTABLISHED-", "MOVED", "UNORDERED-BLOCK", "NOT-EVIDENCED-IN-CAPTURE")
ABSENCE_STATUS_ENUM_PREFIXES = ("FOUND", "GENUINELY-UNDEFINED-AFTER-CENSUS")

SID_RE = re.compile(r"\bS\d{4}\b")


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def fail(msg):
    print(f"FAIL: {msg}")
    sys.exit(1)


def main():
    if len(sys.argv) != 2:
        fail("usage: verify_reconciliation_objects_batch.py <batch_id>")
    batch_id = sys.argv[1]

    manifest = load_jsonl(os.path.join(FAM, "_batch_manifest_p3b.jsonl"))
    entry = next((m for m in manifest if m["batch_id"] == batch_id), None)
    if entry is None:
        fail(f"{batch_id} not found in _batch_manifest_p3b.jsonl")
    expected_labels = set(entry["labels"])

    all_files = load_jsonl(os.path.join(CR, "02-FILES.jsonl"))
    all_source_ids = {f["source_id"] for f in all_files}
    all_labels_universe = set(
        json.load(open(os.path.join(FAM, "_derived.json"), encoding="utf-8"))["nodes"].keys()
    )

    ledger_path = os.path.join(LEDGER, batch_id, "objects.jsonl")
    if not os.path.exists(ledger_path):
        fail(f"missing {ledger_path}")
    records = load_jsonl(ledger_path)

    seen = set()
    for r in records:
        label = r.get("working_label")
        if label not in expected_labels:
            fail(f"{label!r} not assigned to {batch_id}")
        if label in seen:
            fail(f"duplicate record for {label!r}")
        seen.add(label)

        ss = r.get("semantic_status")
        if ss not in SEMANTIC_STATUS_ENUM:
            fail(f"{label}: semantic_status '{ss}' not in closed list")
        ts = r.get("type_status")
        if ts not in TYPE_STATUS_ENUM:
            fail(f"{label}: type_status '{ts}' not in closed list")
        ms = r.get("mathematical_status")
        if ms not in MATH_STATUS_ENUM:
            fail(f"{label}: mathematical_status '{ms}' not in closed list")

        layer = r.get("primary_layer")
        if layer not in LAYER_ENUM:
            fail(f"{label}: primary_layer '{layer}' not in closed list")
        for role in (r.get("secondary_roles") or []):
            if role not in LAYER_ENUM:
                fail(f"{label}: secondary_role '{role}' not in closed list")

        births = r.get("births")
        if not isinstance(births, dict):
            fail(f"{label}: missing/invalid 'births' object")
        for kind, status in births.items():
            if not isinstance(status, str) or not status.startswith(BIRTH_STATUS_PREFIXES):
                fail(f"{label}: births[{kind}]={status!r} does not start with an allowed status")

        absences = r.get("absences_resolved")
        if not isinstance(absences, dict):
            fail(f"{label}: missing/invalid 'absences_resolved' object")
        for dim, status in absences.items():
            if not isinstance(status, str) or not status.startswith(ABSENCE_STATUS_ENUM_PREFIXES):
                fail(f"{label}: absences_resolved[{dim}]={status!r} invalid")

        for edge in (r.get("dependency_edges") or []):
            if edge.get("kind") not in EDGE_KIND_ENUM:
                fail(f"{label}: dependency edge kind {edge.get('kind')!r} not in closed list")
            target = edge.get("target_label")
            if target not in all_labels_universe:
                fail(f"{label}: dependency edge target {target!r} is not a real label")

        if not r.get("what_says_this_for_status"):
            fail(f"{label}: missing 'what_says_this_for_status' (required — statuses must cite evidence)")

        blob = json.dumps(r)
        for sid in set(SID_RE.findall(blob)):
            if sid not in all_source_ids:
                fail(f"{label}: cites {sid} which is not a real source_id in 02-FILES.jsonl")

    missing = expected_labels - seen
    if missing:
        fail(f"{batch_id}: {len(missing)} assigned labels have no record: {sorted(missing)[:10]}")

    print(f"PASS: {batch_id} — {len(records)} object roll-ups verified")


if __name__ == "__main__":
    main()
