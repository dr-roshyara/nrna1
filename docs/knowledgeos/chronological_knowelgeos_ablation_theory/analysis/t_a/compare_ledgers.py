"""Deterministic passage-level comparison of two sealed T-A ledgers. Never averages; never resolves.

Matching rule (fixed before inspecting the comparison output; stated here so it can be audited):
  two records refer to the same passage iff their source family (F0018 / ES-006) is equal and the
  token-set Jaccard similarity of `original_wording` (lowercased word tokens of length >= 3) is >= 0.30.
Classes per matched pair within the SAME test:
  UNKNOWN      either record has assigned_kind UNKNOWN (kind-specific tests only)
  AGREEMENT    same classification (and, for AMBIGUOUS, the same competing_classification)
  DISAGREEMENT otherwise
Unmatched records: SELF-ONLY / OTHER-ONLY. Pairs matching across DIFFERENT tests are listed as CROSS-TEST
(the same passage filed under different questions) and are not counted as agreement.
Usage: python3 compare_ledgers.py SELF.jsonl OTHER.jsonl OTHER_LABEL > comparison.json
"""
import json
import re
import sys

KIND_FREE = {"F-A6", "F-A0"}


def fam(r):
    return "F0018" if "F0018" in (r["source_id"] + r["source_version"]) else "ES-006"


def toks(s):
    return {w for w in re.findall(r"[a-z0-9≥]+", s.lower()) if len(w) >= 3}


def jac(a, b):
    return len(a & b) / len(a | b) if a | b else 0.0


def kind(r):
    return (r.get("operation") or {}).get("assigned_kind")


def main(sp, op, label):
    S = [json.loads(l) for l in open(sp, encoding="utf-8") if l.strip()]
    O = [json.loads(l) for l in open(op, encoding="utf-8") if l.strip()]
    pairs = [(i, j, jac(toks(a["original_wording"]), toks(b["original_wording"])))
             for i, a in enumerate(S) for j, b in enumerate(O) if fam(a) == fam(b)]
    pairs = [p for p in pairs if p[2] >= 0.30]
    rows, s_used, o_used = [], set(), set()
    for i, j, sim in sorted(pairs, key=lambda p: (-p[2], p[0], p[1])):
        a, b = S[i], O[j]
        if a["test"] != b["test"]:
            rows.append({"class": "CROSS-TEST", "self": i, "other": j, "sim": round(sim, 2),
                         "self_test": a["test"], "other_test": b["test"],
                         "self_cls": a["classification"], "other_cls": b["classification"]})
            continue
        if i in s_used or j in o_used:
            continue
        s_used.add(i); o_used.add(j)
        if a["test"] not in KIND_FREE and "UNKNOWN" in (kind(a), kind(b)) and a["test"].startswith("F-"):
            c = "UNKNOWN"
        elif a["classification"] == b["classification"] and \
                (a["classification"] != "AMBIGUOUS" or a.get("competing_classification") == b.get("competing_classification")):
            c = "AGREEMENT"
        else:
            c = "DISAGREEMENT"
        rows.append({"class": c, "test": a["test"], "self": i, "other": j, "sim": round(sim, 2),
                     "self_cls": a["classification"], "self_comp": a.get("competing_classification"),
                     "other_cls": b["classification"], "other_comp": b.get("competing_classification"),
                     "self_kind": kind(a), "other_kind": kind(b), "wording_self": a["original_wording"][:90]})
    cross_s = {r["self"] for r in rows if r["class"] == "CROSS-TEST"}
    cross_o = {r["other"] for r in rows if r["class"] == "CROSS-TEST"}
    for i, a in enumerate(S):
        if i not in s_used:
            rows.append({"class": "SELF-ONLY", "test": a["test"], "self": i, "self_cls": a["classification"],
                         "self_comp": a.get("competing_classification"), "also_cross_test": i in cross_s,
                         "wording_self": a["original_wording"][:90]})
    for j, b in enumerate(O):
        if j not in o_used:
            rows.append({"class": f"{label}-ONLY", "test": b["test"], "other": j, "other_cls": b["classification"],
                         "other_comp": b.get("competing_classification"), "also_cross_test": j in cross_o,
                         "wording_other": b["original_wording"][:90]})
    summary = {}
    for r in rows:
        summary[r["class"]] = summary.get(r["class"], 0) + 1
    json.dump({"rule": "same source family and Jaccard(original_wording tokens>=3 chars) >= 0.30",
               "self": sp, "other": op, "summary": summary, "rows": rows}, sys.stdout, indent=1, ensure_ascii=False)
    print()


if __name__ == "__main__":
    main(*sys.argv[1:4])
