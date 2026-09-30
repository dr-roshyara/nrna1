#!/usr/bin/env python3
"""P2b — mechanical verification of one label-family batch's output. Exits non-zero on
failure. Lighter than P1's verify_batch.py since P2b agents format already-derived data
rather than performing original extraction — checks completeness and citation
sanity (every cited source_id must be a real source_id somewhere in the corpus), not
semantic correctness (that is the job of the occasional self-audit spot-check, A8-style,
run by the orchestrator across a sample, not by this script)."""
import json
import os
import re
import subprocess
import sys

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")

SID_RE = re.compile(r"\bS\d{4}\b")


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def fail(msg):
    print(f"FAIL: {msg}")
    sys.exit(1)


def main(batch_id):
    manifest = load_jsonl(os.path.join(FAM, "_batch_manifest.jsonl"))
    batch = next((b for b in manifest if b["batch_id"] == batch_id), None)
    if batch is None:
        fail(f"{batch_id} not found in _batch_manifest.jsonl")

    all_valid_sids = {f["source_id"] for f in load_jsonl(os.path.join(CR, "02-FILES.jsonl"))}

    missing_files = []
    empty_files = []
    missing_label_mention = []
    bad_citations = {}

    for label in batch["labels"]:
        path = os.path.join(FAM, f"{label}.md")
        if not os.path.isfile(path):
            missing_files.append(label)
            continue
        text = open(path, encoding="utf-8").read()
        if not text.strip():
            empty_files.append(label)
            continue
        if label not in text:
            missing_label_mention.append(label)
        cited = set(SID_RE.findall(text))
        bad = cited - all_valid_sids
        if bad:
            bad_citations[label] = sorted(bad)

    if missing_files:
        fail(f"{len(missing_files)} labels have no family file: {missing_files[:10]}")
    if empty_files:
        fail(f"{len(empty_files)} family files are empty: {empty_files[:10]}")
    if missing_label_mention:
        fail(f"{len(missing_label_mention)} family files never mention their own "
             f"working_label string: {missing_label_mention[:10]}")
    if bad_citations:
        sample = dict(list(bad_citations.items())[:5])
        fail(f"{len(bad_citations)} family files cite source_ids that do not exist "
             f"anywhere in the corpus (fabricated citation): {sample}")

    print(f"PASS: {batch_id} — {len(batch['labels'])} family files, all non-empty, "
          f"all cited source_ids verified real")
    return 0


if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("usage: verify_families_batch.py <batch_id>")
        sys.exit(2)
    sys.exit(main(sys.argv[1]))
