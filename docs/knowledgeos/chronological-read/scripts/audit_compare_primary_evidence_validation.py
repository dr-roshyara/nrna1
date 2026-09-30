#!/usr/bin/env python3
"""PRIMARY Evidence Validation Experiment -- comparison/statistics step. Read-only;
merges the 4 blind batches, verifies completeness, joins with the (previously
hidden) answer key ONLY NOW, and computes every required distribution, the
business metrics, and Wilson score confidence intervals. Never touches any
production file.
"""
import json
import math
import os
import subprocess
from collections import Counter, defaultdict

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
AUDIT = os.path.join(CR, "audit-p3a")


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def wilson_ci(successes, n, z=1.96):
    if n == 0:
        return (0.0, 0.0)
    p = successes / n
    denom = 1 + z**2 / n
    center = p + z**2 / (2 * n)
    margin = z * math.sqrt(p * (1 - p) / n + z**2 / (4 * n**2))
    lo = (center - margin) / denom
    hi = (center + margin) / denom
    return (max(0, lo), min(1, hi))


def main():
    records = []
    for i in range(1, 5):
        batch = load_jsonl(os.path.join(AUDIT, "ledger", f"PV_batch{i}", "validation.jsonl"))
        records.extend(batch)

    seen = set()
    for r in records:
        assert r["pv_id"] not in seen, f"duplicate {r['pv_id']}"
        seen.add(r["pv_id"])
    assert len(records) == 100, f"expected 100 records, got {len(records)}"
    expected_ids = {f"PV{i:04d}" for i in range(1, 101)}
    assert seen == expected_ids, f"missing/extra pv_ids: {expected_ids ^ seen}"

    FIDELITY = {"VERBATIM", "FAITHFUL_PARAPHRASE", "PARTIAL", "MISREPRESENTED", "NOT_FOUND"}
    SUPPORT = {"EXPLICIT_RELATIONSHIP", "STRONG_IMPLICIT_RELATIONSHIP", "WEAK_INFERENCE",
               "NO_RELATIONSHIP", "CONTRADICTORY", "UNRESOLVED"}
    STRENGTH = {"E0", "E1", "E2", "E3", "E4"}
    for r in records:
        assert r["evidence_fidelity"] in FIDELITY, f"{r['pv_id']}: bad fidelity {r['evidence_fidelity']}"
        assert r["evidence_strength"] in STRENGTH, f"{r['pv_id']}: bad strength {r['evidence_strength']}"
        assert r["relationship_supported"] in SUPPORT, f"{r['pv_id']}: bad support {r['relationship_supported']}"

    print(f"OK: 100/100 records present, unique, schema-valid.\n")

    # ---- B. Source fidelity ----
    fidelity_counts = Counter(r["evidence_fidelity"] for r in records)
    print("=== B. Source fidelity ===")
    for k in ("VERBATIM", "FAITHFUL_PARAPHRASE", "PARTIAL", "MISREPRESENTED", "NOT_FOUND"):
        n = fidelity_counts.get(k, 0)
        lo, hi = wilson_ci(n, 100)
        print(f"  {k}: {n} ({n}%) [Wilson 95% CI: {lo*100:.1f}%-{hi*100:.1f}%]")

    # ---- C. Relationship support ----
    support_counts = Counter(r["relationship_supported"] for r in records)
    print("\n=== C. Relationship support ===")
    for k in ("EXPLICIT_RELATIONSHIP", "STRONG_IMPLICIT_RELATIONSHIP", "WEAK_INFERENCE",
              "NO_RELATIONSHIP", "CONTRADICTORY", "UNRESOLVED"):
        n = support_counts.get(k, 0)
        lo, hi = wilson_ci(n, 100)
        print(f"  {k}: {n} ({n}%) [Wilson 95% CI: {lo*100:.1f}%-{hi*100:.1f}%]")

    # ---- D. Evidence strength ----
    strength_counts = Counter(r["evidence_strength"] for r in records)
    print("\n=== D. Evidence strength ===")
    for k in ("E0", "E1", "E2", "E3", "E4"):
        n = strength_counts.get(k, 0)
        lo, hi = wilson_ci(n, 100)
        print(f"  {k}: {n} ({n}%) [Wilson 95% CI: {lo*100:.1f}%-{hi*100:.1f}%]")

    # ---- E. Supported relationship types ----
    type_counts = Counter(r.get("supported_relationship_type") or "null/none" for r in records)
    print("\n=== E. Supported relationship types ===")
    for k, n in type_counts.most_common():
        print(f"  {k}: {n}")

    # ---- Failure modes ----
    fm_counts = Counter()
    for r in records:
        for fm in (r.get("failure_modes") or []):
            fm_counts[fm] += 1
    prov_concerns = sum(1 for r in records if r.get("provenance_classification_concern"))
    print(f"\n=== Failure modes (a record may have 0+ ) ===")
    for k in sorted(fm_counts):
        print(f"  {k}: {fm_counts[k]}")
    print(f"  PROVENANCE_CLASSIFICATION_CONCERN raised: {prov_concerns}")

    # ---- Business metrics ----
    n = 100
    e3_e4 = strength_counts.get("E3", 0) + strength_counts.get("E4", 0)
    explicit_or_strong = support_counts.get("EXPLICIT_RELATIONSHIP", 0) + support_counts.get("STRONG_IMPLICIT_RELATIONSHIP", 0)
    print(f"\n=== Business metrics (n=100 sample; population=1,104) ===")
    lo, hi = wilson_ci(e3_e4, n)
    print(f"PRIMARY Evidence Recovery Rate (E3+E4 / n): {e3_e4}/100 = {e3_e4}% "
          f"[Wilson 95% CI: {lo*100:.1f}%-{hi*100:.1f}%]")
    lo, hi = wilson_ci(explicit_or_strong, n)
    print(f"PRIMARY Relationship Support Rate (EXPLICIT+STRONG_IMPLICIT / n): {explicit_or_strong}/100 = "
          f"{explicit_or_strong}% [Wilson 95% CI: {lo*100:.1f}%-{hi*100:.1f}%]")

    # ---- F. Existing P3a comparison (answer key revealed NOW, not before) ----
    answer_key = json.load(open(os.path.join(AUDIT, "primary_validation_answer_key.json"), encoding="utf-8"))
    already_judged = [(r, answer_key[r["pv_id"]]) for r in records if answer_key[r["pv_id"]]["existing_p3a_pair_id"]]
    never_pair = [r for r in records if not answer_key[r["pv_id"]]["existing_p3a_pair_id"]]
    print(f"\n=== F. Existing P3a comparison ===")
    print(f"Sampled pairs that are already-judged P3a pairs: {len(already_judged)}")
    print(f"Sampled pairs never constituted as a P3a pair at all: {len(never_pair)}")

    agree = disagree = 0
    false_unwitnessed = 0  # existing=UNWITNESSED but validated relationship_supported is EXPLICIT/STRONG_IMPLICIT
    ontology_gap_cases = sum(1 for r in records if r.get("ontology_gap") or r.get("supported_relationship_type") == "ONTOLOGY-GAP")
    disagreement_detail = []
    for r, ak in already_judged:
        existing_rel = ak["existing_p3a_relationship"]
        validated_rel = r.get("supported_relationship_type")
        if existing_rel == validated_rel:
            agree += 1
        else:
            disagree += 1
            disagreement_detail.append((r["pv_id"], ak["existing_p3a_pair_id"], existing_rel, validated_rel,
                                         r["relationship_supported"], r["evidence_strength"]))
        if existing_rel == "UNWITNESSED" and r["relationship_supported"] in (
            "EXPLICIT_RELATIONSHIP", "STRONG_IMPLICIT_RELATIONSHIP"
        ):
            false_unwitnessed += 1

    total_judged = len(already_judged)
    if total_judged:
        lo, hi = wilson_ci(agree, total_judged)
        print(f"Agreement rate (of {total_judged} already-judged pairs): {agree}/{total_judged} = "
              f"{100*agree/total_judged:.1f}% [Wilson 95% CI: {lo*100:.1f}%-{hi*100:.1f}%]")
        print(f"Disagreement rate: {disagree}/{total_judged} = {100*disagree/total_judged:.1f}%")

    unwitnessed_judged = sum(1 for r, ak in already_judged if ak["existing_p3a_relationship"] == "UNWITNESSED")
    if unwitnessed_judged:
        lo, hi = wilson_ci(false_unwitnessed, unwitnessed_judged)
        print(f"'False-UNWITNESSED' rate (of {unwitnessed_judged} sampled pairs that were originally "
              f"UNWITNESSED): {false_unwitnessed}/{unwitnessed_judged} = "
              f"{100*false_unwitnessed/unwitnessed_judged:.1f}% now validated as EXPLICIT/STRONG_IMPLICIT "
              f"[Wilson 95% CI: {lo*100:.1f}%-{hi*100:.1f}%]")

    print(f"Ontology-gap rate (of 100 sampled): {ontology_gap_cases}/100 = {ontology_gap_cases}%")

    # Potential P3a miss rate: of all VALIDATED relationships (EXPLICIT or STRONG_IMPLICIT,
    # regardless of whether already a P3a pair), how many does current P3a NOT correctly
    # represent (either never a pair at all, or a pair whose relationship disagrees)?
    validated_relationships = [r for r in records if r["relationship_supported"] in
                                ("EXPLICIT_RELATIONSHIP", "STRONG_IMPLICIT_RELATIONSHIP")]
    misses = 0
    for r in validated_relationships:
        ak = answer_key[r["pv_id"]]
        if not ak["existing_p3a_pair_id"]:
            misses += 1  # never a pair at all
        elif ak["existing_p3a_relationship"] != r.get("supported_relationship_type"):
            misses += 1  # a pair exists but P3a's relationship doesn't match
    if validated_relationships:
        lo, hi = wilson_ci(misses, len(validated_relationships))
        print(f"\nPotential P3a Miss Rate (of {len(validated_relationships)} validated relationships): "
              f"{misses}/{len(validated_relationships)} = {100*misses/len(validated_relationships):.1f}% "
              f"[Wilson 95% CI: {lo*100:.1f}%-{hi*100:.1f}%]")

    # write detailed comparison JSON for the report
    out = {
        "n": 100, "population": 1104,
        "fidelity_counts": dict(fidelity_counts), "support_counts": dict(support_counts),
        "strength_counts": dict(strength_counts), "type_counts": dict(type_counts),
        "failure_mode_counts": dict(fm_counts), "provenance_concerns": prov_concerns,
        "already_judged_n": total_judged, "never_pair_n": len(never_pair),
        "agree": agree, "disagree": disagree,
        "false_unwitnessed": false_unwitnessed, "unwitnessed_judged_n": unwitnessed_judged,
        "ontology_gap_cases": ontology_gap_cases,
        "validated_relationships_n": len(validated_relationships), "misses": misses,
        "disagreement_detail": [
            {"pv_id": d[0], "p3a_pair_id": d[1], "existing_relationship": d[2],
             "validated_relationship": d[3], "validated_support": d[4], "validated_strength": d[5]}
            for d in disagreement_detail
        ],
    }
    with open(os.path.join(AUDIT, "primary_evidence_validation_comparison.json"), "w", encoding="utf-8") as f:
        json.dump(out, f, ensure_ascii=False, indent=1)
    print(f"\nOK: comparison detail written to primary_evidence_validation_comparison.json")


if __name__ == "__main__":
    main()
