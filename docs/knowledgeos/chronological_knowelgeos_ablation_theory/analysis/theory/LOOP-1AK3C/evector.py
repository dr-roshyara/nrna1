"""1ak-3c: re-test the 1am-2b reconciled RAISE events with evidence as a vector (committed before its first run).
Input: LOOP-1AM2/R2/B/REVIEW.json corrected_events (read-only). No corpus read. Deterministic.

Coding rule (frozen):
  repetition units = {instances, occurrences, slices, independent slices, tickets*, demonstrations}; breadth units = {contexts}.
  Intervals, using only the formal bounds 1 <= breadth <= repetition (each instance lies in exactly one context):
    repetition known v -> rep=[v,v], breadth=[1,v];  breadth known b -> breadth=[b,b], rep=[b,inf];
    stated bound 'lo-hi' -> rep=[lo,hi], breadth=[1,hi];  value UNK or unit UNK -> [0,inf] on both.
Eligibility: P = PROMOTED and ground != CHOICE; Q = NOT-PROMOTED and ground == RULE; status EXCLUDED dropped.
Monotone models (Q falsifies iff it is CERTAINLY >= P on the model's components):
  M_rep   : outcome monotone in repetition only   -> falsified iff rep(Q).lo  >= rep(P).hi
  M_br    : outcome monotone in breadth only      -> falsified iff br(Q).lo   >= br(P).hi
  M_vec   : monotone in the product order         -> falsified iff both of the above hold for one pair
Variants (frozen): V0 all events · VX exclude P with exception YES (H-X: exceptions lie outside the monotone guard) ·
  VE25 drop E25 (the contested legality reading) · VX+VE25.
Usage: python3 evector.py [--selftest]"""
import json
import math
import os
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
SRC = os.path.join(HERE, "..", "LOOP-1AM2", "R2", "B", "REVIEW.json")
REP = ("instances", "occurrences", "slices", "independent slices", "demonstrations")
INF = math.inf


def vec(ev):
    v, u, b = ev.get("value"), (ev.get("unit") or "UNK").lower(), ev.get("bound")
    if u.startswith("tickets"): u = "slices"
    if b and isinstance(b, str) and "-" in b and v == "UNK":
        lo, hi = (int(x) for x in b.split("-")); return (lo, hi), (1, hi)
    if not isinstance(v, int) or u == "unk": return (0, INF), (0, INF)
    if u in REP: return (v, v), (1, v)
    if u == "contexts": return (v, INF), (v, v)
    return (0, INF), (0, INF)


def pools(events, vx=False, drop=()):
    E = [e for e in events if e.get("status") != "EXCLUDED" and e["id"] not in drop]
    P = [e for e in E if e["outcome"] == "PROMOTED" and e["ground"] != "CHOICE" and not (vx and e.get("exception") == "YES")]
    Q = [e for e in E if e["outcome"] == "NOT-PROMOTED" and e["ground"] == "RULE"]
    return P, Q


def falsifiers(P, Q):
    out = {"M_rep": [], "M_br": [], "M_vec": []}
    for p in P:
        rp, bp = vec(p["evidence"])
        for q in Q:
            rq, bq = vec(q["evidence"])
            r, b = rq[0] >= rp[1], bq[0] >= bp[1]
            if r: out["M_rep"].append((p["id"], q["id"]))
            if b: out["M_br"].append((p["id"], q["id"]))
            if r and b: out["M_vec"].append((p["id"], q["id"]))
    return out


def selftest():
    P = [{"id": "p", "outcome": "PROMOTED", "ground": "UNK", "evidence": {"value": 1, "unit": "contexts"}}]
    Q = [{"id": "q", "outcome": "NOT-PROMOTED", "ground": "RULE", "evidence": {"value": 1, "unit": "occurrences"}}]
    f = falsifiers(P, Q)
    ok = [("occurrence=1 vs context=1: breadth falsifies (1>=1)", f["M_br"] == [("p", "q")]),
          ("rep of a context-counted P is unbounded: no rep falsifier", f["M_rep"] == []),
          ("vec needs both", f["M_vec"] == []),
          ("UNK never falsifies", falsifiers([{**P[0], "evidence": {"value": "UNK", "unit": "slices"}}], Q)["M_br"] == []),
          ("bound 2-3 -> rep [2,3]", vec({"value": "UNK", "unit": "independent slices", "bound": "2-3"}) == ((2, 3), (1, 3)))]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    ev = json.load(open(SRC))["corrected_events"]
    res = {}
    for name, vx, drop in (("V0", False, ()), ("VX", True, ()), ("VE25", False, ("E25",)), ("VX+VE25", True, ("E25",))):
        P, Q = pools(ev, vx, drop); f = falsifiers(P, Q)
        res[name] = {"P": [p["id"] for p in P], "Q": [q["id"] for q in Q],
                     **{m: {"verdict": "FALSIFIED" if f[m] else "NOT FALSIFIED", "pairs": f[m]} for m in f}}
    print(json.dumps({"labels": "FORMAL CONSEQUENCES of the reviewed 1am-2b coding under a frozen interval recoding; not theory",
                      "vectors": {e["id"]: [list(x) for x in vec(e["evidence"])] for e in ev if e.get("status") != "EXCLUDED"},
                      "results": res}, indent=1, default=str))
