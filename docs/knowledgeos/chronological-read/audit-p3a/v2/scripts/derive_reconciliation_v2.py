#!/usr/bin/env python3
"""EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH.

P3A-V2 candidate-generation engine. Read-only against all production files
(`_derived.json`, `02-FILES.jsonl`, `03-CONTRIBUTIONS.jsonl`). Writes only under
`audit-p3a/v2/output/`. Never touches `31-RECONCILIATION-PAIRS.jsonl`,
`_derived.json`, or any other production artifact.

Implements mechanisms A-H from P3A-V2-REPAIR-SPECIFICATION.md/CANDIDATE-GENERATION
-SPEC.md. Per R13, this script DERIVES CANDIDATE SIGNALS ONLY — it makes zero
identity/relationship/basis/type_compatibility decisions. A "candidate" here is
exactly what A11 calls a pair to be adjudicated, nothing more; generating one is
not equivalent to regenerating a production P3a relationship.

Mechanisms (each candidate signal records exactly which one produced it, per
§6H's provenance-aware requirement):
  A. WITHIN-GROUP           unchanged from v1 -- P2a candidate group co-membership
  B. DEPENDENCY             a row's `dependencies[]` entry exactly matches another
                             real label (a mechanical fact P1 already captured;
                             v1 never read this field at all)
  C. LINEAGE_SOURCEID       a lineage_claims[].target is a bare source_id; resolved
                             via a direct source_id -> owning-label(s) index (the
                             "canonical source register" per spec section 6C) --
                             never a "representative row" substitute
  D. LINEAGE_EXACT          unchanged from v1 -- target is an exact label/notation/
                             alias string match
  E. LINEAGE_SUBSTRING      target string contains a real label as a contiguous
                             substring, not an exact match -- confidence graded
                             HIGH (target starts with the label followed by a
                             possessive/descriptive marker, e.g. "label's ...") or
                             MEDIUM (label appears anywhere else in the string).
                             Deterministic string rule only -- no edit-distance,
                             no embedding similarity, no fuzzy matching.
  F. PROSE_TRIGGER          the row's own `statement` contains one of a fixed,
                             closed set of relationship verbs (extends, corrects,
                             refines, replaces, derives from, continues,
                             specializes, redefines) within one sentence of a real
                             label's exact string appearing in that same statement.
                             This NEVER determines a relationship type -- it only
                             flags that a candidate pair should exist, tagged with
                             which verb triggered it, for a human/agent to adjudicate.
  G. cross-group support is implicit: mechanisms B/C/E/F all naturally produce
     CROSS-GROUP candidates (the two labels need not share a P2a group) exactly
     like D already did in v1.
  H. Provenance is carried on every candidate: `source_provenance` and
     `target_provenance` (PRIMARY/SECONDARY-SYNTHESIS/PROVENANCE-UNRESOLVED),
     read from `02-FILES.jsonl`, never flattened or omitted.
"""
import json
import os
import re
import subprocess
from collections import defaultdict

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")
V2_OUT = os.path.join(CR, "audit-p3a", "v2", "output")

SID_RE = re.compile(r"^S\d{4}$")

# Closed, fixed vocabulary for mechanism F -- deliberately narrow (§6F: "generate
# candidate evidence, not automatically determine the final relationship").
RELATIONSHIP_VERBS = [
    "extends", "extending", "extension of",
    "corrects", "correcting", "correction of",
    "refines", "refining", "refinement of",
    "replaces", "replacing", "replacement of",
    "derives from", "derived from",
    "continues", "continuing", "continuation of",
    "specializes", "specializing", "specialization of",
    "redefines", "redefining", "redefinition of",
]


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


class ReconciliationEngineV2:
    def __init__(self, derived, files):
        self.families = derived["families"]
        self.nodes = derived["nodes"]
        self.groups = derived["groups"]
        self.file_by_sid = {f["source_id"]: f for f in files}
        self.all_labels = set(self.nodes.keys())

        self.string_to_label = defaultdict(set)
        for label, node in self.nodes.items():
            self.string_to_label[label].add(label)
            for n in node.get("notations") or []:
                self.string_to_label[n].add(label)
            for a in node.get("aliases") or []:
                self.string_to_label[a].add(label)

        self.sid_to_labels = defaultdict(set)
        for label, fam in self.families.items():
            for r in fam["rows"]:
                self.sid_to_labels[r["source_id"]].add(label)

        # candidates[pair] = list of signal dicts
        self.candidates = defaultdict(list)

    def file_provenance(self, source_id):
        f = self.file_by_sid.get(source_id)
        return f.get("provenance") if f else None

    def label_provenance(self, label):
        """§6H: a candidate must carry the provenance of the TARGET label's own
        evidence, not just the citing side. Since mechanisms B/D/E/F name a whole
        label (not one specific row, unlike C's exact source_id), report the
        label's dominant provenance: PRIMARY if any row is PRIMARY, else
        SECONDARY-SYNTHESIS if any row is that, else PROVENANCE-UNRESOLVED/None."""
        rows = self.families.get(label, {}).get("rows", [])
        provs = {self.file_provenance(r["source_id"]) for r in rows}
        if "PRIMARY" in provs:
            return "PRIMARY"
        if "SECONDARY-SYNTHESIS" in provs:
            return "SECONDARY-SYNTHESIS"
        if "PROVENANCE-UNRESOLVED" in provs:
            return "PROVENANCE-UNRESOLVED"
        return None

    def add_signal(self, label_a, label_b, mechanism, confidence, detail):
        if label_a == label_b:
            return
        pair = tuple(sorted([label_a, label_b]))
        self.candidates[pair].append({
            "mechanism": mechanism, "confidence": confidence, **detail,
        })

    # ---- A. WITHIN-GROUP (unchanged from v1) ----
    def mechanism_a_within_group(self):
        for g in self.groups:
            members = [m for m in g["members"] if m in self.nodes]
            if len(members) < 2:
                continue
            for i in range(len(members)):
                for j in range(i + 1, len(members)):
                    self.add_signal(members[i], members[j], "A_WITHIN_GROUP", "GROUP",
                                     {"group_id": g["group_id"], "group_kind": g["kind"],
                                      "why_grouped": g.get("why_grouped")})

    # ---- B. DEPENDENCY ----
    def mechanism_b_dependency(self):
        for label, fam in self.families.items():
            for r in fam["rows"]:
                for dep in (r.get("dependencies") or []):
                    if dep in self.all_labels and dep != label:
                        self.add_signal(label, dep, "B_DEPENDENCY", "MECHANICAL-EXACT", {
                            "source_id": r["source_id"], "row_label": label,
                            "dependency_value": dep, "statement": r.get("statement"),
                            "source_provenance": self.file_provenance(r["source_id"]),
                            "target_provenance": self.label_provenance(dep),
                        })

    # ---- C. LINEAGE_SOURCEID ----
    def mechanism_c_lineage_sourceid(self):
        for label, fam in self.families.items():
            for r in fam["rows"]:
                for lc in (r.get("lineage_claims") or []):
                    target = lc.get("target") or ""
                    if SID_RE.match(target):
                        owners = self.sid_to_labels.get(target, set()) - {label}
                        if len(owners) == 1:
                            target_label = next(iter(owners))
                            self.add_signal(label, target_label, "C_LINEAGE_SOURCEID", "HIGH", {
                                "source_id": r["source_id"], "row_label": label,
                                "lineage_kind": lc.get("kind"), "lineage_target_sid": target,
                                "lineage_quote": lc.get("quote"),
                                "source_provenance": self.file_provenance(r["source_id"]),
                                "target_source_id": target,
                                "target_provenance": self.file_provenance(target),
                            })
                        elif len(owners) > 1:
                            self.add_signal(label, sorted(owners)[0], "C_LINEAGE_SOURCEID", "AMBIGUOUS", {
                                "source_id": r["source_id"], "row_label": label,
                                "lineage_kind": lc.get("kind"), "lineage_target_sid": target,
                                "lineage_quote": lc.get("quote"),
                                "note": f"source_id {target} is owned by {len(owners)} labels: "
                                        f"{sorted(owners)} -- ambiguous, not auto-resolved to one",
                                "source_provenance": self.file_provenance(r["source_id"]),
                            })
                        # else: target source_id not found anywhere -- not a candidate

    # ---- D. LINEAGE_EXACT (unchanged from v1) ----
    def mechanism_d_lineage_exact(self):
        for label, fam in self.families.items():
            for r in fam["rows"]:
                for lc in (r.get("lineage_claims") or []):
                    target = lc.get("target") or ""
                    if target in self.all_labels and target != label:
                        self.add_signal(label, target, "D_LINEAGE_EXACT", "HIGH", {
                            "source_id": r["source_id"], "row_label": label,
                            "lineage_kind": lc.get("kind"), "lineage_target_label": target,
                            "lineage_quote": lc.get("quote"),
                            "source_provenance": self.file_provenance(r["source_id"]),
                            "target_provenance": self.label_provenance(target),
                        })
                    elif target in self.string_to_label and target not in self.all_labels:
                        # notation/alias exact match
                        for tl in self.string_to_label[target]:
                            if tl != label:
                                self.add_signal(label, tl, "D_LINEAGE_EXACT", "HIGH", {
                                    "source_id": r["source_id"], "row_label": label,
                                    "lineage_kind": lc.get("kind"), "lineage_target_label": tl,
                                    "lineage_target_string": target, "lineage_quote": lc.get("quote"),
                                    "source_provenance": self.file_provenance(r["source_id"]),
                                    "target_provenance": self.label_provenance(tl),
                                })

    # ---- E. LINEAGE_SUBSTRING (deterministic, graded confidence) ----
    def mechanism_e_lineage_substring(self):
        for label, fam in self.families.items():
            for r in fam["rows"]:
                for lc in (r.get("lineage_claims") or []):
                    target = lc.get("target") or ""
                    if target in self.all_labels or SID_RE.match(target):
                        continue  # handled by D or C
                    best_match = None
                    for tl in self.all_labels:
                        if tl == label or len(tl) <= 8:
                            continue
                        if tl not in target:
                            continue
                        if best_match is None or len(tl) > len(best_match):
                            best_match = tl
                    if best_match:
                        tl = best_match
                        idx = target.find(tl)
                        after = target[idx + len(tl):idx + len(tl) + 3]
                        confidence = "HIGH" if after.startswith("'s") else "MEDIUM"
                        self.add_signal(label, tl, "E_LINEAGE_SUBSTRING", confidence, {
                            "source_id": r["source_id"], "row_label": label,
                            "lineage_kind": lc.get("kind"), "lineage_target_string": target,
                            "matched_label": tl, "lineage_quote": lc.get("quote"),
                            "source_provenance": self.file_provenance(r["source_id"]),
                            "target_provenance": self.label_provenance(tl),
                        })
                        # deterministic: longest real substring match only, no double-counting

    # ---- F. PROSE_TRIGGER (closed verb list, same-statement co-mention only) ----
    def mechanism_f_prose_trigger(self):
        # DISCLOSED MITIGATION (found during controlled validation, not assumed up
        # front): a sample check found single-word, no-hyphen labels (pks,
        # capability, mission, provenance, KnowledgeOS, evidence, viewpoint,
        # ontology -- exactly 8 of 2,497 labels) generate 74.1% of all
        # prose-trigger-only candidates purely because their string is common
        # generic vocabulary that co-occurs with a relationship verb in unrelated
        # statements. Per §6F's explicit instruction not to create uncontrolled
        # false relationships, these 8 labels are excluded from serving as the
        # MATCHED TARGET for this mechanism only (mechanisms B-E are unaffected --
        # they require a structured field, not prose co-occurrence, so this
        # false-positive mode does not apply to them).
        generic_single_word_labels = {l for l in self.all_labels if "-" not in l}
        for label, fam in self.families.items():
            for r in fam["rows"]:
                statement = (r.get("statement") or "").lower()
                if not statement:
                    continue
                hit_verbs = [v for v in RELATIONSHIP_VERBS if v in statement]
                if not hit_verbs:
                    continue
                for tl in self.all_labels:
                    if tl == label or len(tl) <= 8 or tl in generic_single_word_labels:
                        continue
                    tl_words = tl.replace("-", " ")
                    if tl.lower() not in statement and tl_words.lower() not in statement:
                        continue
                    # already covered by a structured field? skip (avoid double-signal
                    # noise -- this mechanism is a SAFETY NET for prose with NO
                    # structured lineage_claims/dependencies entry at all)
                    has_structured = any(
                        (lc.get("target") == tl) or (lc.get("target") or "").find(tl) >= 0
                        for lc in (r.get("lineage_claims") or [])
                    ) or (tl in (r.get("dependencies") or []))
                    if has_structured:
                        continue
                    self.add_signal(label, tl, "F_PROSE_TRIGGER", "LOW", {
                        "source_id": r["source_id"], "row_label": label,
                        "matched_label": tl, "matched_verbs": hit_verbs,
                        "statement": r.get("statement"),
                        "source_provenance": self.file_provenance(r["source_id"]),
                        "target_provenance": self.label_provenance(tl),
                        "note": "PROSE_TRIGGER is a candidate-generation signal ONLY -- "
                                "it never determines a relationship type or basis; an "
                                "adjudicator must independently verify the prose actually "
                                "supports a relationship, per §6F",
                    })

    def run_all(self):
        self.mechanism_a_within_group()
        self.mechanism_b_dependency()
        self.mechanism_c_lineage_sourceid()
        self.mechanism_d_lineage_exact()
        self.mechanism_e_lineage_substring()
        self.mechanism_f_prose_trigger()
        return self.candidates


def main():
    derived = json.load(open(os.path.join(FAM, "_derived.json"), encoding="utf-8"))
    files = load_jsonl(os.path.join(CR, "02-FILES.jsonl"))

    engine = ReconciliationEngineV2(derived, files)
    candidates = engine.run_all()

    os.makedirs(V2_OUT, exist_ok=True)
    out_path = os.path.join(V2_OUT, "P3A-V2-CANDIDATES.jsonl")
    with open(out_path, "w", encoding="utf-8") as f:
        for pair, signals in sorted(candidates.items()):
            mechanisms = sorted(set(s["mechanism"] for s in signals))
            best_confidence = min(
                (s["confidence"] for s in signals),
                key=lambda c: {"MECHANICAL-EXACT": 0, "HIGH": 1, "GROUP": 2,
                                "MEDIUM": 3, "AMBIGUOUS": 4, "LOW": 5}.get(c, 9),
            )
            f.write(json.dumps({
                "label_a": pair[0], "label_b": pair[1],
                "mechanisms": mechanisms, "best_confidence": best_confidence,
                "signal_count": len(signals), "signals": signals,
            }, ensure_ascii=False) + "\n")

    from collections import Counter
    mech_pair_counts = Counter()
    for pair, signals in candidates.items():
        for m in set(s["mechanism"] for s in signals):
            mech_pair_counts[m] += 1

    print(f"OK: {out_path} written")
    print(f"Total distinct candidate pairs (V2): {len(candidates)}")
    print("Pairs contributed per mechanism (a pair may count under multiple mechanisms):")
    for m, n in sorted(mech_pair_counts.items()):
        print(f"  {m}: {n}")


if __name__ == "__main__":
    main()
