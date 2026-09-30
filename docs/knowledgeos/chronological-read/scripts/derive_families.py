#!/usr/bin/env python3
"""P2b (A10) — FAMILIES, script half. For every one of the 2,497 working_label nodes
already identified in P2a, mechanically derive everything a script can safely derive
(R13) and extend `20-FAMILIES/_derived.json` in place with a new "families" section:

  rows                 every contribution row (full dict) whose labels[] contains this
                        label, source_id order
  candidate_births      per kind (lexical/conceptual/formal/operational/governance):
                        earliest matching row, or null -> NOT-EVIDENCED-IN-CAPTURE.
                        CANDIDATE-*-BIRTH only — P3 promotes to ESTABLISHED.
  last_seen            latest source_id among this label's rows
  lifecycle_candidate  ACTIVE | DORMANT | SOURCE-CLAIMED-RETRACTED |
                        SOURCE-CLAIMED-SUPERSEDED | CONTESTED (mechanical heuristic,
                        candidate only — never final, per protocol)
  completeness         roll-up over 12 dimensions -> PRESENT[S-ids] |
                        NOT-EVIDENCED-IN-CAPTURE
  rationale_evidence   EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE rows (capped, "+N more"
                        noted) for the agent to write the rationale block from
  assumption_register   every {statement, stated, source_id, anchor} touching this label
  files_touching       source_ids of files.jsonl records whose objects_touched includes
                        this label (cross-check against the rows list)

Never reads corpus source text — works entirely from 02-FILES.jsonl,
03-CONTRIBUTIONS.jsonl, and P2a's own node/group data (already in _derived.json).
"""
import json
import os
import re
import subprocess
from collections import defaultdict

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")

RATIONALE_TYPES = {"EXPLANATION", "ARGUMENT", "ANALYSIS", "ALTERNATIVE"}
CAP_ROWS_SHOWN_IN_RATIONALE = 15

COMPLETENESS_DIMENSIONS = {
    "purpose_rationale": lambda c: bool(set(c.get("types") or []) & RATIONALE_TYPES),
    "informal_meaning": lambda c: "DEFINITION" in (c.get("types") or []) and c.get("completeness") in ("INFORMAL-ONLY", "PARTIAL"),
    "formal_definition": lambda c: bool(set(c.get("types") or []) & {"FORMALIZATION", "AXIOM"}) or (
        "DEFINITION" in (c.get("types") or []) and c.get("completeness") == "COMPLETE"),
    "type_signature": lambda c: c.get("type_signature") is not None,
    "invariants": lambda c: bool(c.get("invariants")) or "INVARIANT" in (c.get("types") or []),
    "dependencies": lambda c: bool(c.get("dependencies")),
    "assumptions": lambda c: bool(c.get("assumptions")),
    "semantics": lambda c: bool(set(c.get("types") or []) & {"DISTINCTION", "RESTATEMENT", "PRINCIPLE"}),
    "examples": lambda c: bool(set(c.get("types") or []) & {"EXAMPLE", "COUNTEREXAMPLE"}),
    "warnings": lambda c: "WARNING" in (c.get("types") or []),
    "experiments": lambda c: bool(set(c.get("types") or []) & {"EXPERIMENT", "EXPERIMENTAL-RESULT"}),
    "open_questions": lambda c: bool(set(c.get("types") or []) & {"OPEN-QUESTION", "FUTURE-RESEARCH"}),
}

BIRTH_KIND_MATCHERS = {
    "lexical": lambda c: True,
    "conceptual": lambda c: "CONCEPT" in (c.get("types") or []),
    "formal": lambda c: bool(set(c.get("types") or []) & {"FORMALIZATION", "AXIOM"}),
    "operational": lambda c: bool(set(c.get("types") or []) & {"IMPLEMENTATION", "EXPERIMENT"}),
    "governance": lambda c: "GOVERNANCE" in (c.get("types") or []),
}


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def sid_num(sid):
    m = re.match(r"S(\d+)", sid)
    return int(m.group(1)) if m else -1


def main():
    derived_path = os.path.join(FAM, "_derived.json")
    derived = json.load(open(derived_path, encoding="utf-8"))
    nodes = derived["nodes"]

    contributions = load_jsonl(os.path.join(CR, "03-CONTRIBUTIONS.jsonl"))
    files = load_jsonl(os.path.join(CR, "02-FILES.jsonl"))
    max_sid_num = max(sid_num(f["source_id"]) for f in files)

    label_to_rows = defaultdict(list)
    for c in contributions:
        for l in (c.get("labels") or []):
            if l != "UNKNOWN-OBJECT-CANDIDATE":
                label_to_rows[l].append(c)

    label_to_files = defaultdict(list)
    for f in files:
        for l in (f.get("objects_touched") or []):
            label_to_files[l].append(f["source_id"])

    # global lineage-claim target index: does anything claim RETRACTION/REPLACEMENT
    # against this label (by working_label, notation, or alias string)?
    retraction_targets = defaultdict(list)   # target_string -> [(source_id, kind)]
    for c in contributions:
        for lc in (c.get("lineage_claims") or []):
            target = lc.get("target")
            kind = lc.get("kind")
            if target and kind:
                retraction_targets[target].append({"source_id": c["source_id"], "kind": kind,
                                                     "quote": lc.get("quote", "")})

    families = {}
    no_rows_count = 0
    for label, node in nodes.items():
        rows = sorted(label_to_rows.get(label, []), key=lambda c: sid_num(c["source_id"]))
        if not rows:
            no_rows_count += 1

        candidate_births = {}
        for kind, matcher in BIRTH_KIND_MATCHERS.items():
            match = next((c for c in rows if matcher(c)), None)
            candidate_births[kind] = {
                "source_id": match["source_id"], "anchor": match.get("anchor")
            } if match else None

        last_seen = rows[-1]["source_id"] if rows else None

        # lineage-claim-based lifecycle signals: check working_label + notations + aliases
        target_strings = {label} | set(node.get("notations") or []) | set(node.get("aliases") or [])
        claims_against = []
        for ts in target_strings:
            claims_against.extend(retraction_targets.get(ts, []))
        retracted = [c for c in claims_against if c["kind"] == "SOURCE-CLAIMED-RETRACTION"]
        superseded = [c for c in claims_against if c["kind"] in
                      ("SOURCE-CLAIMED-REPLACEMENT", "SOURCE-CLAIMED-REDEFINITION")]
        # BUGFIX (found by an LB0018 P2b agent spot-check): CONTESTED must only fire on
        # genuinely contentious lineage kinds. The original code counted ANY 2+
        # lineage_claims of ANY kind (including benign ones like EXTENSION/DERIVATION/
        # CONTINUATION/IDENTITY/SPECIALIZATION) as "contested", producing false
        # positives with empty retracted_by/superseded_by evidence. Restricted here to
        # the kinds that actually signal contention.
        contentious_kinds = {"SOURCE-CLAIMED-RETRACTION", "SOURCE-CLAIMED-REPLACEMENT",
                              "SOURCE-CLAIMED-REDEFINITION", "SOURCE-CLAIMED-SEPARATION",
                              "SOURCE-CLAIMED-CONTRADICTION"}
        contentious_claims_against = [c for c in claims_against if c["kind"] in contentious_kinds]
        contested_types = any("CONTRADICTION" in (c.get("types") or []) for c in rows)

        if retracted:
            lifecycle = "SOURCE-CLAIMED-RETRACTED"
        elif superseded:
            lifecycle = "SOURCE-CLAIMED-SUPERSEDED"
        elif contested_types or len(contentious_claims_against) >= 2:
            lifecycle = "CONTESTED"
        elif last_seen and (sid_num(last_seen) >= 0.75 * max_sid_num):
            lifecycle = "ACTIVE"
        elif last_seen:
            lifecycle = "DORMANT"
        else:
            lifecycle = "DORMANT"   # no rows at all -> nothing to be active about

        completeness = {}
        for dim, matcher in COMPLETENESS_DIMENSIONS.items():
            matching_sids = [c["source_id"] for c in rows if matcher(c)]
            completeness[dim] = {"status": "PRESENT", "source_ids": matching_sids} if matching_sids \
                else {"status": "NOT-EVIDENCED-IN-CAPTURE", "source_ids": []}

        rationale_rows = [c for c in rows if set(c.get("types") or []) & RATIONALE_TYPES]
        rationale_evidence = [
            {"source_id": c["source_id"], "types": c.get("types"), "anchor": c.get("anchor"),
             "statement": c.get("statement")}
            for c in rationale_rows[:CAP_ROWS_SHOWN_IN_RATIONALE]
        ]
        rationale_truncated = max(0, len(rationale_rows) - CAP_ROWS_SHOWN_IN_RATIONALE)

        assumption_register = []
        for c in rows:
            for a in (c.get("assumptions") or []):
                assumption_register.append({
                    "statement": a.get("statement"), "stated": a.get("stated"),
                    "source_id": c["source_id"], "anchor": a.get("anchor"),
                })

        families[label] = {
            "row_count": len(rows),
            "rows": rows,
            "candidate_births": candidate_births,
            "last_seen": last_seen,
            "lifecycle_candidate": lifecycle,
            "lifecycle_evidence": {"retracted_by": retracted, "superseded_by": superseded,
                                    "contested_by_own_contradiction_type": contested_types},
            "completeness": completeness,
            "rationale_evidence": rationale_evidence,
            "rationale_truncated_count": rationale_truncated,
            "assumption_register": assumption_register,
            "files_touching": sorted(set(label_to_files.get(label, [])), key=sid_num),
            "primary_layer_provisional": "LAYER-UNRESOLVED",
            "secondary_roles_provisional": [],
        }

    derived["families"] = families
    derived["families_meta"] = {
        "generated_by": "scripts/derive_families.py",
        "labels_with_zero_contribution_rows": no_rows_count,
    }

    with open(derived_path, "w", encoding="utf-8") as fh:
        json.dump(derived, fh, ensure_ascii=False, indent=1)

    print(f"OK: {derived_path} extended with per-label family data for {len(families)} labels")
    print(f"labels with zero contribution rows (metadata-only): {no_rows_count}")
    from collections import Counter
    lc_counts = Counter(f["lifecycle_candidate"] for f in families.values())
    print(f"lifecycle_candidate distribution: {dict(lc_counts)}")


if __name__ == "__main__":
    main()
