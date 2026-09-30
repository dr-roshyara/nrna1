#!/usr/bin/env python3
"""P3b (A11 "PER OBJECT") — script half. For every one of the 2,497 labels, assemble
(never decide, R13) everything an agent needs to roll up the per-object statuses:

  own family data      candidate_births, lifecycle_candidate/evidence, completeness
                        (incl. which dimensions are NOT-EVIDENCED-IN-CAPTURE and so
                        need a targeted search), primary_layer/secondary_roles
                        provisional, rationale_evidence, assumption_register,
                        files_touching (all from derive_families.py's P2b output)
  pairs_touching        every P3a-decided pair (31-RECONCILIATION-PAIRS.jsonl) where
                        this label is 'a' or 'b', with the verdict AND the other
                        label's name, so the agent can roll up semantic_status/
                        type_status without re-deciding any pair
  node info             group_ids, notations, aliases (for absence-search targets)

Writes `20-FAMILIES/_derived.json["reconciliation_objects"]` (extends in place) — one
entry per label. Nothing here is a status verdict.
"""
import json
import os
import re
import subprocess

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def main():
    derived_path = os.path.join(FAM, "_derived.json")
    derived = json.load(open(derived_path, encoding="utf-8"))
    nodes = derived["nodes"]
    families = derived["families"]

    pairs = load_jsonl(os.path.join(CR, "31-RECONCILIATION-PAIRS.jsonl"))
    pairs_by_label = {}
    for p in pairs:
        for me, other in ((p["a"], p["b"]), (p["b"], p["a"])):
            pairs_by_label.setdefault(me, []).append({
                "pair_id": p["pair_id"], "with_label": other,
                "relationship": p["relationship"], "basis": p["basis"],
                "type_compatibility": p["type_compatibility"],
                "what_says_this": p.get("what_says_this"),
                "what_would_make_this_wrong": p.get("what_would_make_this_wrong"),
                "homonym_positive_evidence": p.get("homonym_positive_evidence"),
                "corpus_wide_search_note": p.get("corpus_wide_search_note"),
                "notes_for_p3b": p.get("notes_for_p3b"),
            })

    objects = {}
    for label, fam in families.items():
        node = nodes[label]
        absences = [dim for dim, v in fam["completeness"].items() if v["status"] == "NOT-EVIDENCED-IN-CAPTURE"]
        objects[label] = {
            "working_label": label,
            "notations": node.get("notations"), "aliases": node.get("aliases"),
            "group_ids": sorted(node.get("group_ids") or []),
            "row_count": fam["row_count"],
            "candidate_births": fam["candidate_births"],
            "last_seen": fam["last_seen"],
            "lifecycle_candidate": fam["lifecycle_candidate"],
            "lifecycle_evidence": fam["lifecycle_evidence"],
            "completeness": fam["completeness"],
            "completeness_absences": absences,
            "primary_layer_provisional": fam["primary_layer_provisional"],
            "secondary_roles_provisional": fam["secondary_roles_provisional"],
            "rationale_evidence": fam["rationale_evidence"],
            "assumption_register": fam["assumption_register"],
            "files_touching": fam["files_touching"],
            "pairs_touching": pairs_by_label.get(label, []),
            "pair_count": len(pairs_by_label.get(label, [])),
        }

    derived["reconciliation_objects"] = objects
    derived["reconciliation_objects_meta"] = {
        "generated_by": "scripts/derive_reconciliation_objects.py",
        "object_count": len(objects),
        "objects_with_zero_pairs": sum(1 for o in objects.values() if o["pair_count"] == 0),
        "objects_with_absences": sum(1 for o in objects.values() if o["completeness_absences"]),
    }

    with open(derived_path, "w", encoding="utf-8") as fh:
        json.dump(derived, fh, ensure_ascii=False, indent=1)

    print(f"OK: {derived_path} extended with {len(objects)} reconciliation objects")
    print(f"  objects with zero pairs (no P2a group / all-cross-group-only): "
          f"{derived['reconciliation_objects_meta']['objects_with_zero_pairs']}")
    print(f"  objects with >=1 NOT-EVIDENCED-IN-CAPTURE completeness dimension: "
          f"{derived['reconciliation_objects_meta']['objects_with_absences']}")


if __name__ == "__main__":
    main()
