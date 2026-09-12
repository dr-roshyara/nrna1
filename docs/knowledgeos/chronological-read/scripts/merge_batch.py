#!/usr/bin/env python3
"""A6 post-verification merge: fold a passed batch's index-proposals into the shared
index (NONE relation) or the unresolved-candidates file (POSSIBLY:* relation, or any
UNKNOWN-OBJECT-CANDIDATE row from contributions.jsonl), append a read-status line, and
mark the batch DONE. Run only after verify_batch.py has passed for this batch."""
import json
import os
import subprocess
import sys

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")


def load_jsonl(path):
    if not os.path.isfile(path):
        return []
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def append_jsonl(path, rows):
    with open(path, "a", encoding="utf-8") as f:
        for r in rows:
            f.write(json.dumps(r, ensure_ascii=False) + "\n")


def main(batch_id):
    ledger_dir = os.path.join(CR, "ledger", batch_id)
    proposals = load_jsonl(os.path.join(ledger_dir, "index-proposals.jsonl"))
    contributions = load_jsonl(os.path.join(ledger_dir, "contributions.jsonl"))
    files = load_jsonl(os.path.join(ledger_dir, "files.jsonl"))

    index_path = os.path.join(CR, "11-OBJECT-INDEX.jsonl")
    unresolved_path = os.path.join(CR, "11-UNRESOLVED-CANDIDATES.jsonl")
    status_path = os.path.join(CR, "09-READ-STATUS.jsonl")

    existing_labels = {r["working_label"] for r in load_jsonl(index_path)}

    new_index_rows = []
    new_unresolved_rows = []
    for p in proposals:
        rel = p.get("relation_to_existing", "NONE")
        if p["working_label"] in existing_labels:
            continue  # already registered by an earlier-merged batch
        if rel == "NONE":
            new_index_rows.append(p)
            existing_labels.add(p["working_label"])
        else:
            new_unresolved_rows.append({"kind": "PROPOSAL", "batch_id": batch_id, **p})

    for c in contributions:
        if "UNKNOWN-OBJECT-CANDIDATE" in (c.get("labels") or []):
            new_unresolved_rows.append({
                "kind": "UNKNOWN-OBJECT-CANDIDATE",
                "batch_id": batch_id,
                "source_id": c["source_id"],
                "anchor": c.get("anchor"),
                "candidate_of": (c.get("unknown_candidate") or {}).get("candidate_of"),
                "why_uncertain": (c.get("unknown_candidate") or {}).get("why_uncertain"),
            })

    append_jsonl(index_path, new_index_rows)
    append_jsonl(unresolved_path, new_unresolved_rows)

    type_counts = {}
    scope_counts = {}
    for c in contributions:
        for t in c.get("types") or []:
            type_counts[t] = type_counts.get(t, 0) + 1
        scope_counts[c.get("scope")] = scope_counts.get(c.get("scope"), 0) + 1
    review_flags = sum(1 for c in contributions if c.get("review_flag"))
    lineage_claims = sum(len(c.get("lineage_claims") or []) for c in contributions)

    append_jsonl(status_path, [{
        "batch_id": batch_id,
        "files": len(files),
        "contributions": len(contributions),
        "new_labels_registered": len(new_index_rows),
        "unresolved_candidates_added": len(new_unresolved_rows),
        "type_counts": type_counts,
        "scope_counts": scope_counts,
        "review_flags": review_flags,
        "lineage_claims": lineage_claims,
    }])

    # mark DONE in manifest
    manifest_path = os.path.join(CR, "01-BATCH-MANIFEST.jsonl")
    rows = load_jsonl(manifest_path)
    for r in rows:
        if r["batch_id"] == batch_id:
            r["status"] = "DONE"
            r["verified"] = True
    with open(manifest_path, "w", encoding="utf-8") as f:
        for r in rows:
            f.write(json.dumps(r, ensure_ascii=False) + "\n")

    print(f"MERGED {batch_id}: +{len(new_index_rows)} labels, +{len(new_unresolved_rows)} unresolved, "
          f"{len(files)} files, {len(contributions)} contributions -> DONE")


if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("usage: merge_batch.py <batch_id>")
        sys.exit(2)
    main(sys.argv[1])
