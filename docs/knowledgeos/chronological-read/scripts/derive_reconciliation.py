#!/usr/bin/env python3
"""P3a (A11) — RECONCILIATION, pair-evidence script half. R13: scripts derive what can
be derived (pairing, evidence assembly); agents interpret (relationship, basis,
type_compatibility). This script makes NO identity or compatibility decision — it only
assembles, for every pair a P3 agent must judge, the evidence the protocol requires it
to weigh.

Two kinds of pairs, per A11:
  WITHIN-GROUP  every pair of members inside a P2a CANDIDATE GROUP with >=2 members.
  CROSS-GROUP   "across groups when a SOURCE-CLAIMED row links them" — a lineage_claim
                on a row of label X whose target string matches label Y's own
                working_label/notation/alias, where X and Y do not already share a
                P2a group.

DESIGN NOTE (superseding an earlier version of this script): an earlier draft grouped
pairs into connected components via union-find for batching locality. That produced one
983-label, 1518-pair mega-component — CO-OCCURRENCE (the WEAKEST signal type, per P2a's
own signal-strength ranking) chains unrelated labels transitively (A co-occurs with B in
file 1, B with C in file 2 -> A and C end up "connected" despite never co-occurring
together). A single agent cannot sensibly reconcile 983 labels at once, and grouping by
weak-signal transitivity defeats the purpose of a self-contained dispatch unit. Fixed by
dropping components entirely: pairs are batched directly and independently (P3a, this
script + plan_reconciliation_pairs_batches.py) since each pair's evidence bundle is
already fully self-contained (both labels' rows + lineage claims + group evidence — no
component needed). The per-object roll-up that DOES need every pair touching a given
label (A11's per-object statuses) is deferred to a separate P3b pass
(derive_reconciliation_objects.py), run after all P3a pair batches are merged, which
looks up this label's pair verdicts by simple dictionary lookup — no transitive closure
required for that either.

Writes `20-FAMILIES/_derived.json["reconciliation_pairs"]` (extends in place) — a flat
list of pair evidence bundles. Nothing here is a verdict.
"""
import json
import os
import re
import subprocess
from collections import defaultdict

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")


def row_brief(c):
    return {
        "source_id": c["source_id"],
        "anchor": c.get("anchor"),
        "types": c.get("types"),
        "statement": c.get("statement"),
        "type_signature": c.get("type_signature"),
        "explicit_date": c.get("explicit_date"),
    }


def main():
    derived_path = os.path.join(FAM, "_derived.json")
    derived = json.load(open(derived_path, encoding="utf-8"))
    nodes = derived["nodes"]
    families = derived["families"]
    groups = derived["groups"]

    # target string -> owning label(s), for cross-group lineage-claim matching
    string_to_label = defaultdict(set)
    for label, node in nodes.items():
        string_to_label[label].add(label)
        for n in node.get("notations") or []:
            string_to_label[n].add(label)
        for a in node.get("aliases") or []:
            string_to_label[a].add(label)

    # ---- WITHIN-GROUP pairs ----
    within_pairs = {}  # (a,b) sorted tuple -> {group_evidence: [...]}
    for g in groups:
        members = [m for m in g["members"] if m in nodes]
        if len(members) < 2:
            continue
        for i in range(len(members)):
            for j in range(i + 1, len(members)):
                a, b = sorted([members[i], members[j]])
                key = (a, b)
                within_pairs.setdefault(key, {"group_evidence": []})
                within_pairs[key]["group_evidence"].append({
                    "group_id": g["group_id"], "kind": g["kind"], "why_grouped": g.get("why_grouped"),
                })

    # ---- CROSS-GROUP pairs (lineage_claims linking labels not co-grouped) ----
    cross_pairs = {}
    for label, fam in families.items():
        for c in fam["rows"]:
            for lc in (c.get("lineage_claims") or []):
                target = lc.get("target")
                if not target:
                    continue
                for tl in string_to_label.get(target, set()):
                    if tl == label:
                        continue
                    a, b = sorted([label, tl])
                    if (a, b) in within_pairs:
                        continue  # already fully evidenced as a within-group pair
                    cross_pairs.setdefault((a, b), {"lineage_claims": []})
                    cross_pairs[(a, b)]["lineage_claims"].append({
                        "from_label": label, "to_label": tl, "source_id": c["source_id"],
                        "kind": lc.get("kind"), "target_string": target, "quote": lc.get("quote"),
                    })

    def build_pair_record(a, b, extra):
        rows_a = families.get(a, {}).get("rows", [])
        rows_b = families.get(b, {}).get("rows", [])
        a_strings = {a} | set(nodes[a].get("notations") or []) | set(nodes[a].get("aliases") or [])
        b_strings = {b} | set(nodes[b].get("notations") or []) | set(nodes[b].get("aliases") or [])
        claims_a_to_b = [
            {"source_id": c["source_id"], "kind": lc.get("kind"), "quote": lc.get("quote"),
             "target_string": lc.get("target")}
            for c in rows_a for lc in (c.get("lineage_claims") or []) if lc.get("target") in b_strings
        ]
        claims_b_to_a = [
            {"source_id": c["source_id"], "kind": lc.get("kind"), "quote": lc.get("quote"),
             "target_string": lc.get("target")}
            for c in rows_b for lc in (c.get("lineage_claims") or []) if lc.get("target") in a_strings
        ]
        contested_a = any("CONTRADICTION" in (c.get("types") or []) for c in rows_a)
        contested_b = any("CONTRADICTION" in (c.get("types") or []) for c in rows_b)
        return {
            "a": a, "b": b,
            "a_row_count": len(rows_a), "b_row_count": len(rows_b),
            "a_rows_sample": [row_brief(c) for c in rows_a[:20]],
            "b_rows_sample": [row_brief(c) for c in rows_b[:20]],
            "a_rows_truncated": max(0, len(rows_a) - 20),
            "b_rows_truncated": max(0, len(rows_b) - 20),
            "a_notations": nodes[a].get("notations"), "b_notations": nodes[b].get("notations"),
            "a_aliases": nodes[a].get("aliases"), "b_aliases": nodes[b].get("aliases"),
            "lineage_claims_a_to_b": claims_a_to_b,
            "lineage_claims_b_to_a": claims_b_to_a,
            "a_has_contradiction_type_row": contested_a,
            "b_has_contradiction_type_row": contested_b,
            **extra,
        }

    pairs = []
    for (a, b), ev in within_pairs.items():
        pairs.append(build_pair_record(a, b, {
            "pair_kind": "WITHIN-GROUP", "group_evidence": ev["group_evidence"],
        }))
    for (a, b), ev in cross_pairs.items():
        pairs.append(build_pair_record(a, b, {
            "pair_kind": "CROSS-GROUP", "group_evidence": [],
            "cross_group_lineage_claims": ev["lineage_claims"],
        }))

    pairs.sort(key=lambda r: (r["a"], r["b"]))
    for i, p in enumerate(pairs, start=1):
        p["pair_id"] = f"RP{i:04d}"

    derived["reconciliation_pairs"] = pairs
    derived["reconciliation_pairs_meta"] = {
        "generated_by": "scripts/derive_reconciliation.py",
        "within_group_pair_count": len(within_pairs),
        "cross_group_pair_count": len(cross_pairs),
        "total_pair_count": len(pairs),
    }

    with open(derived_path, "w", encoding="utf-8") as fh:
        json.dump(derived, fh, ensure_ascii=False, indent=1)

    print(f"OK: {derived_path} extended with {len(pairs)} reconciliation pairs")
    print(f"  within-group: {len(within_pairs)}  cross-group: {len(cross_pairs)}")


if __name__ == "__main__":
    main()
