#!/usr/bin/env python3
"""Provenance trace of the 1,464 undefined-relationship signals found by Stage 1
of the P3a quality gate v2 (docs/knowledgeos/chronological-read/audit-p3a/
STAGE1-EMPIRICAL-PROBE.md, section 0). Read-only against all production files.
Writes only under audit-p3a/.

For every DEPENDENCY or LINEAGE_CLAIM signal between two real labels that
derive_reconciliation.py never used, this records: which file it lives in, which
folder, which source_id/row, the exact field and text, whether the containing file
is P1-classified PRIMARY or SECONDARY-SYNTHESIS (the project's own existing
per-file provenance field, from 02-FILES.jsonl), and resolves the referenced object
(for lineage_claims whose target is a source_id or a label-substring paraphrase).

Provenance category (A-F) is assigned by a documented, auditable rule, not asserted:
  - file provenance == PRIMARY            -> B (direct extraction from primary text)
    (P1's own contract requires dependencies/lineage_claims to reflect what the
    source states, never agent inference beyond it -- verified by spot-check
    separately, not assumed blanket-true here)
  - file provenance == SECONDARY-SYNTHESIS -> C by default (the containing file is
    itself a later synthesis of earlier material), refined to D or E using the
    file's own path/summary/contribution_assessment text (never folder name alone)
  - file provenance == PROVENANCE-UNRESOLVED -> F
This script computes the category; a human (or a separate spot-check pass) verifies
it against raw source text for a sample -- see STAGE2 spot-check notes in the
companion summary markdown.
"""
import json
import os
import re
import subprocess
from collections import defaultdict, Counter

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")
AUDIT = os.path.join(CR, "audit-p3a")

SID_RE = re.compile(r"^S\d{4}$")

# --- verification/audit vs synthesis/theory-construction refinement, from path ---
# (never folder alone per instruction -- these path markers are cross-checked
# against the file's own `summary`/`contribution_assessment` before being trusted;
# see the "path_hint_confirmed_by_content" field on each record)
VERIFICATION_PATH_MARKERS = ("verification", "gap-discovery", "audit", "falsif", "review")
SYNTHESIS_PATH_MARKERS = ("synthesis", "consolidat", "theory-extraction", "reconstruction",
                          "three_model_convergence", "canonical")


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def main():
    files = load_jsonl(os.path.join(CR, "02-FILES.jsonl"))
    file_by_sid = {f["source_id"]: f for f in files}

    derived = json.load(open(os.path.join(FAM, "_derived.json"), encoding="utf-8"))
    fam = derived["families"]
    nodes = derived["nodes"]
    all_labels = set(nodes.keys())

    production = load_jsonl(os.path.join(CR, "31-RECONCILIATION-PAIRS.jsonl"))
    prod_by_pair = {tuple(sorted([p["a"], p["b"]])): p for p in production}

    def file_meta(source_id):
        f = file_by_sid.get(source_id)
        if not f:
            return dict(path=None, folder=None, provenance=None, status=None,
                        file_mtime=None, best_historical_date=None, commit=None)
        path = f["path"]
        folder = os.path.dirname(path)
        return dict(path=path, folder=folder, provenance=f.get("provenance"),
                    status=f.get("status"), file_mtime=f.get("file_mtime"),
                    best_historical_date=f.get("best_historical_date"), commit=f.get("commit"))

    def path_hints(path):
        p = (path or "").lower()
        hints = []
        if any(m in p for m in VERIFICATION_PATH_MARKERS):
            hints.append("VERIFICATION")
        if any(m in p for m in SYNTHESIS_PATH_MARKERS):
            hints.append("SYNTHESIS")
        return hints

    def assign_category(file_prov, path_hint_list, content_confirms):
        if file_prov == "PRIMARY":
            return "B", "file provenance=PRIMARY (P1-classified original brainstorming content); dependencies/lineage_claims are P1-contract-required to reflect explicit source text, not agent inference"
        if file_prov == "SECONDARY-SYNTHESIS":
            if "VERIFICATION" in path_hint_list and content_confirms:
                return "D", "file provenance=SECONDARY-SYNTHESIS; path contains a verification/audit marker, confirmed by file summary/contribution_assessment content"
            if "SYNTHESIS" in path_hint_list and content_confirms:
                return "E", "file provenance=SECONDARY-SYNTHESIS; path contains a synthesis/theory-construction marker, confirmed by file summary/contribution_assessment content"
            return "C", "file provenance=SECONDARY-SYNTHESIS; no confirmed verification/synthesis path marker -- default to general reconstruction/interpretation"
        if file_prov == "PROVENANCE-UNRESOLVED":
            return "F", "file provenance=PROVENANCE-UNRESOLVED (P1 itself could not establish this file's own provenance)"
        return "F", "no file record found for this source_id in 02-FILES.jsonl (source_id unresolved)"

    def content_confirms_hint(source_id, hint_list):
        """Cross-check a path-based hint against the file's own summary/
        contribution_assessment text (never trust folder name alone)."""
        if not hint_list:
            return False
        f = file_by_sid.get(source_id)
        if not f:
            return False
        text = ((f.get("summary") or "") + " " + (f.get("contribution_assessment") or "")).lower()
        if "VERIFICATION" in hint_list and any(
            w in text for w in ("verif", "audit", "falsif", "review", "check", "test")
        ):
            return True
        if "SYNTHESIS" in hint_list and any(
            w in text for w in ("synthes", "consolidat", "reconstruct", "compil", "canonical")
        ):
            return True
        return False

    # source_id -> owning label(s), for bare-source-id lineage_claim targets
    sid_to_labels = defaultdict(set)
    for lbl, f in fam.items():
        for r in f["rows"]:
            sid_to_labels[r["source_id"]].add(lbl)

    def resolve_label_for_string(target, exclude_label):
        """Find which real label a lineage_claim target string most likely refers to:
        exact match, a bare source_id (resolved via the source_id->label index), or
        the longest real label that appears as a substring."""
        if target in all_labels:
            return target, "EXACT_MATCH"
        if SID_RE.match(target):
            owners = sid_to_labels.get(target, set()) - {exclude_label}
            if len(owners) == 1:
                return next(iter(owners)), "SOURCE_ID_OWNER_LOOKUP"
            elif len(owners) > 1:
                # ambiguous: source_id shared by multiple labels -- pick none automatically,
                # caller should not silently guess; record as unresolved-ambiguous
                return None, "SOURCE_ID_OWNER_AMBIGUOUS"
            return None, None
        best = None
        for lbl in all_labels:
            if lbl != exclude_label and len(lbl) > 8 and lbl in target:
                if best is None or len(lbl) > len(best):
                    best = lbl
        if best:
            return best, "SUBSTRING_MATCH"
        return None, None

    def target_label_representative_source(label):
        """Pick a representative source_id for a label (earliest by best_historical_date
        among its rows) to report the target's own file/folder/provenance."""
        rows = fam.get(label, {}).get("rows", [])
        if not rows:
            return None
        def sort_key(r):
            f = file_by_sid.get(r["source_id"])
            val = (f.get("best_historical_date") if f else None) or "9999-99-99"
            return str(val)
        rows_sorted = sorted(rows, key=sort_key)
        return rows_sorted[0]["source_id"]

    records = []
    signal_counter = 0

    for label, f in fam.items():
        for r in f["rows"]:
            source_id = r["source_id"]
            fm = file_meta(source_id)
            hints = path_hints(fm["path"])
            confirms = content_confirms_hint(source_id, hints)
            category, category_basis = assign_category(fm["provenance"], hints, confirms)

            # ---- DEPENDENCY signals ----
            for dep in (r.get("dependencies") or []):
                if dep in all_labels and dep != label:
                    signal_counter += 1
                    pair = tuple(sorted([label, dep]))
                    existing = prod_by_pair.get(pair)
                    records.append({
                        "signal_id": f"UP{signal_counter:05d}",
                        "candidate_pair_id": "__".join(pair),
                        "label_a": pair[0], "label_b": pair[1],
                        "signal_type": "DEPENDENCY",
                        "source_file": fm["path"], "source_folder": fm["folder"],
                        "source_path": fm["path"],
                        "source_row_id": source_id, "source_id": source_id,
                        "row_label": label,
                        "original_text": r.get("statement"),
                        "dependency_value": dep,
                        "lineage_claim": None, "lineage_claim_kind": None,
                        "lineage_claim_target": None, "lineage_claim_quote": None,
                        "resolution_method": "DEPENDENCY_EXACT_LABEL_MATCH",
                        "existing_p3a_pair": existing is not None,
                        "existing_p3a_pair_id": existing["pair_id"] if existing else None,
                        "existing_p3a_relationship": existing["relationship"] if existing else None,
                        "corpus_role": fm["provenance"],
                        "corpus_tier": fm["folder"],
                        "source_role": fm["status"],
                        "canonical_source": None,
                        "provenance_level": category,
                        "provenance_basis": category_basis,
                        "path_hints": hints, "path_hint_content_confirmed": confirms,
                        "created_at": fm["best_historical_date"],
                        "modified_at": fm["file_mtime"],
                        "target_label": dep, "target_resolution_method": "DEPENDENCY_IS_LABEL_ITSELF",
                        "target_source_id": target_label_representative_source(dep),
                        "target_file": None, "target_folder": None, "target_provenance": None,
                    })
                    # fill target file info
                    tsid = records[-1]["target_source_id"]
                    if tsid:
                        tfm = file_meta(tsid)
                        records[-1]["target_file"] = tfm["path"]
                        records[-1]["target_folder"] = tfm["folder"]
                        records[-1]["target_provenance"] = tfm["provenance"]

            # ---- LINEAGE_CLAIM signals (non-exact-match only -- exact matches are
            # already used by derive_reconciliation.py and out of scope for "undefined") ----
            for lc in (r.get("lineage_claims") or []):
                target = lc.get("target") or ""
                if target in all_labels:
                    continue  # already usable by the existing pipeline if same-pair
                resolved_label, method = resolve_label_for_string(target, label)
                if not resolved_label:
                    continue  # points outside the label universe entirely -- not a candidate signal
                signal_counter += 1
                pair = tuple(sorted([label, resolved_label]))
                existing = prod_by_pair.get(pair)
                # BUGFIX (found during the PRIMARY Evidence Validation experiment,
                # PV0033/PV0087): when the claim's own target IS a specific source_id
                # (method == SOURCE_ID_OWNER_LOOKUP), use that exact source_id's own
                # file -- never fall back to "some representative row of the label,"
                # which silently substitutes a different, possibly unrelated file.
                # The representative-row fallback remains correct/necessary only for
                # DEPENDENCY signals and SUBSTRING_MATCH lineage claims, where the
                # claim genuinely names a label as a whole, not a specific row.
                if method == "SOURCE_ID_OWNER_LOOKUP":
                    tsid = target
                else:
                    tsid = target_label_representative_source(resolved_label)
                tfm = file_meta(tsid) if tsid else file_meta(None)
                records.append({
                    "signal_id": f"UP{signal_counter:05d}",
                    "candidate_pair_id": "__".join(pair),
                    "label_a": pair[0], "label_b": pair[1],
                    "signal_type": "LINEAGE_CLAIM",
                    "source_file": fm["path"], "source_folder": fm["folder"],
                    "source_path": fm["path"],
                    "source_row_id": source_id, "source_id": source_id,
                    "row_label": label,
                    "original_text": r.get("statement"),
                    "dependency_value": None,
                    "lineage_claim": lc, "lineage_claim_kind": lc.get("kind"),
                    "lineage_claim_target": target, "lineage_claim_quote": lc.get("quote"),
                    "resolution_method": (
                        "LINEAGE_CLAIM_TARGET_IS_SOURCE_ID" if SID_RE.match(target)
                        else "LINEAGE_CLAIM_TARGET_CONTAINS_LABEL_SUBSTRING"
                    ),
                    "existing_p3a_pair": existing is not None,
                    "existing_p3a_pair_id": existing["pair_id"] if existing else None,
                    "existing_p3a_relationship": existing["relationship"] if existing else None,
                    "corpus_role": fm["provenance"],
                    "corpus_tier": fm["folder"],
                    "source_role": fm["status"],
                    "canonical_source": None,
                    "provenance_level": category,
                    "provenance_basis": category_basis,
                    "path_hints": hints, "path_hint_content_confirmed": confirms,
                    "created_at": fm["best_historical_date"],
                    "modified_at": fm["file_mtime"],
                    "target_label": resolved_label, "target_resolution_method": method,
                    "target_source_id": tsid,
                    "target_file": tfm["path"], "target_folder": tfm["folder"],
                    "target_provenance": tfm["provenance"],
                })

    out_path = os.path.join(AUDIT, "P3A-UNDEFINED-RELATIONSHIP-PROVENANCE.jsonl")
    with open(out_path, "w", encoding="utf-8") as fh:
        for rec in records:
            fh.write(json.dumps(rec, ensure_ascii=False) + "\n")

    # ---- summaries ----
    distinct_pairs = set(r["candidate_pair_id"] for r in records)
    print(f"OK: {out_path} written -- {len(records)} individual signals, "
          f"{len(distinct_pairs)} distinct candidate pairs")

    prov_counts = Counter(r["provenance_level"] for r in records)
    print("\nProvenance level (per SIGNAL, not per pair):")
    total = len(records)
    for cat in "ABCDEF":
        n = prov_counts.get(cat, 0)
        print(f"  {cat}: {n} ({100*n/total:.1f}%)")

    # per-pair category: a pair may have multiple signals of different categories;
    # report the "strongest" (alphabetically earliest = closest to primary) category per pair
    pair_categories = defaultdict(set)
    for r in records:
        pair_categories[r["candidate_pair_id"]].add(r["provenance_level"])
    pair_best_cat = Counter()
    for pair, cats in pair_categories.items():
        pair_best_cat[min(cats)] += 1
    print("\nProvenance level (per PAIR, best/earliest category among that pair's signals):")
    for cat in "ABCDEF":
        n = pair_best_cat.get(cat, 0)
        print(f"  {cat}: {n} ({100*n/len(distinct_pairs):.1f}%)")

    already_judged = sum(1 for r in records if r["existing_p3a_pair"])
    print(f"\nSignals whose pair already exists in the 1793-pair P3a corpus: "
          f"{sum(1 for pr in distinct_pairs if any(r['existing_p3a_pair'] for r in records if r['candidate_pair_id']==pr))}")
    print(f"Signals whose pair was never constituted as any P3a pair: "
          f"{sum(1 for pr in distinct_pairs if not any(r['existing_p3a_pair'] for r in records if r['candidate_pair_id']==pr))}")

    # folder-level summary
    folder_stats = defaultdict(lambda: {"files": set(), "pairs": set(), "cat": Counter()})
    for r in records:
        fs = folder_stats[r["source_folder"]]
        fs["files"].add(r["source_file"])
        fs["pairs"].add(r["candidate_pair_id"])
        fs["cat"][r["provenance_level"]] += 1

    with open(os.path.join(AUDIT, "P3A-UNDEFINED-RELATIONSHIP-PROVENANCE-SUMMARY.md"), "w", encoding="utf-8") as fh:
        fh.write("# P3A Undefined-Relationship Provenance — Summary\n\n")
        fh.write("Mechanically generated from `P3A-UNDEFINED-RELATIONSHIP-PROVENANCE.jsonl`. "
                 "Every number below is an exact count over that file, not a sample.\n\n")

        fh.write("## Central question\n\n")
        fh.write(f"Total candidate relationship SIGNALS: **{total}**\n")
        fh.write(f"Total distinct candidate PAIRS: **{len(distinct_pairs)}**\n\n")
        fh.write("| Provenance category | Signals | % of signals | Pairs (best category) | % of pairs |\n")
        fh.write("|---|--:|--:|--:|--:|\n")
        cat_names = {
            "A": "A — Original primary source (explicit statement)",
            "B": "B — Direct extraction (from PRIMARY-provenance file)",
            "C": "C — Reconstruction/interpretation (SECONDARY-SYNTHESIS, unconfirmed sub-type)",
            "D": "D — Verification/audit artifact (SECONDARY-SYNTHESIS, verification-confirmed)",
            "E": "E — Synthesis/theory construction (SECONDARY-SYNTHESIS, synthesis-confirmed)",
            "F": "F — Unknown provenance",
        }
        for cat in "ABCDEF":
            n = prov_counts.get(cat, 0)
            pn = pair_best_cat.get(cat, 0)
            fh.write(f"| {cat_names[cat]} | {n} | {100*n/total:.1f}% | {pn} | {100*pn/len(distinct_pairs):.1f}% |\n")

        fh.write("\n## Folder-level summary\n\n")
        fh.write("| Folder | Files | Candidate pairs | A | B | C | D | E | F |\n")
        fh.write("|---|--:|--:|--:|--:|--:|--:|--:|--:|\n")
        for folder, fs in sorted(folder_stats.items(), key=lambda kv: -len(kv[1]["pairs"])):
            row = [fs["cat"].get(c, 0) for c in "ABCDEF"]
            fh.write(f"| `{folder}` | {len(fs['files'])} | {len(fs['pairs'])} | " +
                     " | ".join(str(x) for x in row) + " |\n")

        # file-level summary (top files by candidate-pair contribution)
        file_stats = defaultdict(lambda: {"pairs": set(), "cat": Counter(), "folder": None, "signal_types": Counter()})
        for r in records:
            fs = file_stats[r["source_file"]]
            fs["pairs"].add(r["candidate_pair_id"])
            fs["cat"][r["provenance_level"]] += 1
            fs["folder"] = r["source_folder"]
            fs["signal_types"][r["signal_type"]] += 1
        fh.write("\n## File-level summary (top 40 by candidate-pair contribution)\n\n")
        fh.write("| File | Folder | Candidate pairs | Signal types | Dominant provenance |\n")
        fh.write("|---|---|--:|---|---|\n")
        for file, fs in sorted(file_stats.items(), key=lambda kv: -len(kv[1]["pairs"]))[:40]:
            dom = fs["cat"].most_common(1)[0][0]
            sigtypes = ",".join(f"{k}={v}" for k, v in fs["signal_types"].items())
            fh.write(f"| `{file}` | `{fs['folder']}` | {len(fs['pairs'])} | {sigtypes} | {dom} |\n")

        # reconstruction-loop candidates
        loop_candidates = [r for r in records if r["signal_type"] == "LINEAGE_CLAIM"
                           and r["corpus_role"] == "SECONDARY-SYNTHESIS"
                           and r["target_provenance"] == "SECONDARY-SYNTHESIS"]
        fh.write(f"\n## PROVENANCE-CONTAMINATION-RISK candidates\n\n")
        fh.write(f"Lineage-claim signals where BOTH the claiming file and the target label's "
                 f"representative file are SECONDARY-SYNTHESIS (a later synthesis citing another "
                 f"later synthesis): **{len(loop_candidates)}**\n\n")
        for r in loop_candidates[:30]:
            fh.write(f"- `{r['signal_id']}` {r['label_a']} <-> {r['label_b']} "
                     f"(source `{r['source_file']}` -> target `{r['target_file']}`)\n")

    print(f"\nOK: summary markdown written to P3A-UNDEFINED-RELATIONSHIP-PROVENANCE-SUMMARY.md")
    print(f"PROVENANCE-CONTAMINATION-RISK candidates: {len(loop_candidates)}")


if __name__ == "__main__":
    main()
