"""1av pilot scorer (frozen before any coding). Deterministic. Inputs: P1/CODING.json, P2/CODING.json, LOOP-1AR audit classes.
Reports (no adoption threshold is applied here; the adoption threshold for F3 belongs to the human):
  agreement + Cohen's kappa P1~P2 on observation_type (4 classes) and on the binary ACT vs non-ACT;
  agreement P1~P2 on source_act.operation; dual-nature frequency (F4) per coder and agreed;
  UNKNOWN share (F5); convergence of each coder with the reconciled 1ar audit class mapped to r2 types
  (ACT-PERFORMED/ACT-REFUSED -> ACT_OBSERVATION; PERMISSION-STATEMENT -> NORM_STATEMENT; GENERIC-PRACTICE -> GENERIC_PRACTICE;
  UNCLEAR -> UNKNOWN; reviewer class where the 1ar review gives one, else worker class).
Usage: python3 score_1av.py P1.json P2.json AUDIT_WORKER.json AUDIT_REVIEW.json"""
import collections, json, sys


def kappa(a, b):
    n = len(a); po = sum(x == y for x, y in zip(a, b)) / n
    ca, cb = collections.Counter(a), collections.Counter(b); pe = sum(ca[k] * cb[k] for k in set(ca) | set(cb)) / (n * n)
    return round(po, 3), (round((po - pe) / (1 - pe), 3) if pe < 1 else None)


MAP = {"ACT-PERFORMED": "ACT_OBSERVATION", "ACT-REFUSED": "ACT_OBSERVATION", "PERMISSION-STATEMENT": "NORM_STATEMENT",
       "GENERIC-PRACTICE": "GENERIC_PRACTICE", "UNCLEAR": "UNKNOWN"}


def load(p): return {e["event_id"]: e for e in json.load(open(p))["events"]}


def main(p1, p2, aw, ar):
    A, B = load(p1), load(p2); ids = sorted(set(A) & set(B))
    audit = {e["event_id"]: e["class"] for e in json.load(open(aw))["events"]}
    rev = json.load(open(ar)).get("classes_reviewer", {}) or {}
    for k, v in (rev.items() if isinstance(rev, dict) else []):
        if isinstance(v, str) and k in audit: audit[k] = v
    t = lambda E, i: (E[i].get("observation_type") or "UNKNOWN").strip().upper()
    ta, tb = [t(A, i) for i in ids], [t(B, i) for i in ids]
    out = {"n": len(ids), "missing": sorted(set(A) ^ set(B))}
    out["type_4class"] = dict(zip(["agreement", "kappa"], kappa(ta, tb)))
    out["type_act_binary"] = dict(zip(["agreement", "kappa"], kappa([x == "ACT_OBSERVATION" for x in ta], [x == "ACT_OBSERVATION" for x in tb])))
    op = lambda E, i: ((E[i].get("source_act") or {}).get("operation") or "UNKNOWN").strip().upper()
    out["source_act_operation"] = dict(zip(["agreement", "kappa"], kappa([op(A, i) for i in ids], [op(B, i) for i in ids])))
    d = lambda E, i: (E[i].get("dual") or "UNK").strip().upper()
    out["dual_YES"] = {"P1": sum(d(A, i) == "YES" for i in ids), "P2": sum(d(B, i) == "YES" for i in ids),
                       "both": sum(d(A, i) == "YES" and d(B, i) == "YES" for i in ids)}
    out["unknown_share"] = {"P1": round(ta.count("UNKNOWN") / len(ids), 3), "P2": round(tb.count("UNKNOWN") / len(ids), 3)}
    au = [MAP.get(audit.get(i, "UNCLEAR"), "UNKNOWN") for i in ids]
    out["vs_audit"] = {"P1": dict(zip(["agreement", "kappa"], kappa(ta, au))), "P2": dict(zip(["agreement", "kappa"], kappa(tb, au)))}
    out["type_disagreements"] = {i: [x, y] for i, x, y in zip(ids, ta, tb) if x != y}
    out["counts"] = {"P1": collections.Counter(ta), "P2": collections.Counter(tb), "audit": collections.Counter(au)}
    print(json.dumps(out, indent=1, ensure_ascii=False, default=dict))


if __name__ == "__main__":
    main(*sys.argv[1:5])
