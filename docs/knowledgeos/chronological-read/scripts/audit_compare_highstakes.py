#!/usr/bin/env python3
"""P3a QUALITY GATE, section 5 — compare the 158 blind independent verdicts
(audit-p3a/ledger/AH00NN/verdicts.jsonl) against the original P3a production
verdicts (the private answer key, audit-p3a/_highstakes_answer_key.json). Read-only;
writes only a new audit report file, never touches the production ledger.
"""
import json
import os
import subprocess
from collections import defaultdict, Counter

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
AUDIT = os.path.join(CR, "audit-p3a")


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def main():
    answer_key = json.load(open(os.path.join(AUDIT, "_highstakes_answer_key.json"), encoding="utf-8"))

    independent = {}
    for batch_id in ("AH0001", "AH0002", "AH0003", "AH0004"):
        path = os.path.join(AUDIT, "ledger", batch_id, "verdicts.jsonl")
        for r in load_jsonl(path):
            assert r["pair_id"] not in independent, f"duplicate independent verdict for {r['pair_id']}"
            independent[r["pair_id"]] = r

    missing = set(answer_key.keys()) - set(independent.keys())
    extra = set(independent.keys()) - set(answer_key.keys())
    assert not missing, f"missing independent verdicts for: {sorted(missing)}"
    assert not extra, f"independent verdicts for unknown pairs: {sorted(extra)}"
    assert len(independent) == 158

    by_original_rel = defaultdict(lambda: {"agree": [], "disagree": []})
    for pid, orig in answer_key.items():
        ind = independent[pid]
        orig_rel = orig["relationship"]
        ind_rel = ind["independent_relationship"]
        entry = {"pair_id": pid, "a": orig["a"], "b": orig["b"],
                 "original_relationship": orig_rel, "independent_relationship": ind_rel,
                 "independent_confidence": ind.get("confidence")}
        if orig_rel == ind_rel:
            by_original_rel[orig_rel]["agree"].append(entry)
        else:
            by_original_rel[orig_rel]["disagree"].append(entry)

    table_rows = []
    total_n = total_agree = 0
    for rel in ("SAME", "REPLACEMENT", "DERIVED-FROM"):
        agree = by_original_rel[rel]["agree"]
        disagree = by_original_rel[rel]["disagree"]
        n = len(agree) + len(disagree)
        pct = 100.0 * len(agree) / n if n else 0.0
        table_rows.append({"relationship": rel, "n": n, "agree": len(agree),
                            "disagree": len(disagree), "agreement_pct": round(pct, 1)})
        total_n += n
        total_agree += len(agree)

    disagreement_detail = []
    for rel in ("SAME", "REPLACEMENT", "DERIVED-FROM"):
        for entry in by_original_rel[rel]["disagree"]:
            disagreement_detail.append(entry)

    what_disagreements_became = Counter(e["independent_relationship"] for e in disagreement_detail)

    report = {
        "total_high_stakes_pairs": total_n,
        "total_agreement": total_agree,
        "overall_agreement_pct": round(100.0 * total_agree / total_n, 1) if total_n else 0.0,
        "table": table_rows,
        "disagreement_detail": disagreement_detail,
        "what_disagreements_became": dict(what_disagreements_became),
    }

    out_path = os.path.join(AUDIT, "highstakes_agreement_report.json")
    with open(out_path, "w", encoding="utf-8") as f:
        json.dump(report, f, ensure_ascii=False, indent=1)

    print(f"OK: {out_path} written")
    print(f"Overall: {total_agree}/{total_n} = {report['overall_agreement_pct']}% agreement")
    for row in table_rows:
        print(f"  {row['relationship']}: {row['agree']}/{row['n']} = {row['agreement_pct']}%")
    print(f"Disagreements ({len(disagreement_detail)} total) became: {dict(what_disagreements_became)}")


if __name__ == "__main__":
    main()
