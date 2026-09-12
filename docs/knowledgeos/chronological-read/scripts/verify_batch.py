#!/usr/bin/env python3
"""A7 — mechanical verification of one batch's ledger output. Exits non-zero on failure."""
import json
import os
import subprocess
import sys

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")

ALLOWED_FILE_STATUS = {"CONTENT", "FIREWALL-LIMITED"}
TYPES = {
    "CONCEPT", "DEFINITION", "FORMALIZATION", "AXIOM", "PRINCIPLE", "INVARIANT",
    "ASSUMPTION", "EXPLANATION", "ARGUMENT", "ANALYSIS", "WARNING", "CONSTRAINT",
    "DISTINCTION", "EXAMPLE", "COUNTEREXAMPLE", "EXPERIMENT", "EXPERIMENTAL-RESULT",
    "EXTENSION", "ALTERNATIVE", "CORRECTION", "RETRACTION", "CONTRADICTION",
    "IMPLEMENTATION", "VALIDATION", "GOVERNANCE", "LIMITATION", "OPEN-QUESTION",
    "FUTURE-RESEARCH", "RESTATEMENT", "HYPOTHESIS",
}


def fail(msg):
    print(f"FAIL: {msg}")
    sys.exit(1)


def main(batch_id):
    manifest_path = os.path.join(CR, "01-BATCH-MANIFEST.jsonl")
    manifest = [json.loads(l) for l in open(manifest_path, encoding="utf-8")]
    batch = next((b for b in manifest if b["batch_id"] == batch_id), None)
    if batch is None:
        fail(f"{batch_id} not found in manifest")
    expected_ids = set(batch["source_ids"])

    ledger_dir = os.path.join(CR, "ledger", batch_id)
    files_path = os.path.join(ledger_dir, "files.jsonl")
    contrib_path = os.path.join(ledger_dir, "contributions.jsonl")
    proposals_path = os.path.join(ledger_dir, "index-proposals.jsonl")
    summary_path = os.path.join(ledger_dir, "summary.md")

    if not os.path.isfile(files_path):
        fail(f"missing required output {files_path}")
    if not os.path.isfile(summary_path):
        print(f"NOTE: {batch_id} has no summary.md on disk — the harness sometimes "
              f"refuses Write for report-shaped filenames from subagents. Persist the "
              f"agent's returned final message to this path manually before treating "
              f"the batch as fully closed (not required for merge_batch.py to proceed).")

    files = [json.loads(l) for l in open(files_path, encoding="utf-8") if l.strip()]
    if len(files) != len(expected_ids):
        fail(f"files.jsonl has {len(files)} records, expected {len(expected_ids)}")
    got_ids = {r["source_id"] for r in files}
    if got_ids != expected_ids:
        missing = expected_ids - got_ids
        extra = got_ids - expected_ids
        fail(f"source_id set mismatch: missing={missing} extra={extra}")

    for r in files:
        if r.get("status") not in ALLOWED_FILE_STATUS:
            fail(f"{r['source_id']}: invalid status {r.get('status')!r}")
        if r["status"] == "CONTENT":
            if not r.get("summary", "").strip():
                fail(f"{r['source_id']}: empty summary")
            if not r.get("contribution_assessment", "").strip():
                fail(f"{r['source_id']}: empty contribution_assessment")

    proposed_labels = set()
    if os.path.isfile(proposals_path):
        for l in open(proposals_path, encoding="utf-8"):
            if l.strip():
                proposed_labels.add(json.loads(l)["working_label"])

    index_path = os.path.join(CR, "11-OBJECT-INDEX.jsonl")
    existing_labels = set()
    if os.path.isfile(index_path):
        for l in open(index_path, encoding="utf-8"):
            if l.strip():
                existing_labels.add(json.loads(l)["working_label"])
    allowed_labels = existing_labels | proposed_labels | {"UNKNOWN-OBJECT-CANDIDATE"}

    contributions = []
    if os.path.isfile(contrib_path):
        contributions = [json.loads(l) for l in open(contrib_path, encoding="utf-8") if l.strip()]

    for c in contributions:
        if c["source_id"] not in expected_ids:
            fail(f"contribution source_id {c['source_id']} not in batch")
        if not (c.get("anchor") or "").strip():
            fail(f"contribution in {c['source_id']} has empty or null anchor")
        types = c.get("types") or []
        if not types or not set(types) <= TYPES:
            fail(f"contribution in {c['source_id']} has invalid types {types}")
        for lbl in c.get("labels") or []:
            if lbl not in allowed_labels and lbl != "UNKNOWN-OBJECT-CANDIDATE":
                fail(f"contribution in {c['source_id']} uses unregistered label {lbl!r}")
        for a in c.get("assumptions") or []:
            if not isinstance(a, dict):
                fail(f"contribution in {c['source_id']} has a non-object assumption entry: {a!r}")
            if a.get("stated") not in ("EXPLICIT", "USED-UNSTATED"):
                fail(f"contribution in {c['source_id']} has invalid assumption.stated {a.get('stated')!r}")
            if not a.get("anchor", "").strip():
                fail(f"contribution in {c['source_id']} has assumption with empty anchor")

    # NOTE: a git-status-based cross-batch write check was tried and dropped — under
    # PARALLEL_BATCHES > 1 (A6/R12), sibling batches legitimately write concurrently,
    # so a global git status scan cannot distinguish "this batch wrote outside its
    # directory" from "a sibling batch is mid-write elsewhere." No pre-dispatch
    # filesystem snapshot is taken (that would require pausing sibling agents), so
    # this check would need one to be sound. The content checks above (source_id set
    # membership, closed vocabulary, non-empty required fields) already verify
    # correctness of what IS in this batch's own directory; that is what this script
    # certifies. Path containment is enforced by instruction (the contract's "write
    # ONLY here" + "Forbidden: writing outside the output dir"), not re-verified here.

    print(f"PASS: {batch_id} — {len(files)} files, {len(contributions)} contributions")
    return 0


if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("usage: verify_batch.py <batch_id>")
        sys.exit(2)
    sys.exit(main(sys.argv[1]))
