"""1ay: per-variable support on the A' ACT and NORM datasets (committed before the 1ax coding exists). Deterministic.
Inputs: LOOP-1AX/A/CODING.json (coder), LOOP-1AX/B/REVIEW.json (reviewer; corrections applied where `field` is a dotted path
act.<f> / norm.<f>; unparseable corrections are REPORTED, never applied), LOOP-1AX/LINEAGE.json, LOOP-1AR/EVENTS.json (v1 values).
POLARITY  ACT: + ACT_OCCURRED; - ACT_REFUSED/ACT_WITHDRAWN/ACT_REVERSED; ACT_ATTEMPTED/NONE/UNKNOWN excluded.
          NORM: + PERMITTED/AUTHORIZED/ELIGIBLE/DELEGATED/REQUIRED; - PROHIBITED/UNAUTHORIZED/INELIGIBLE; STATUS_CONSTRAINT/NONE/UNKNOWN excluded.
PAIRS     same dataset, same normalized operation (ACT: act.operation; NORM: norm.about_operation), opposite polarity.
FIELDS    A' fields (ACT: actor, target; NORM: about_actor, about_target, stated_by) + v1 fields via lineage (a k s t e x h c r),
          the latter flagged V1-CODED. A value is KNOWN unless empty/UNK/UNKNOWN/n/a/NOT_EVIDENCED/None or a 'same:' token.
WITNESS   STRICT for v: v known & different; every other compared field known & equal.
          POSSIBLE for v: v known & different; every other field equal or unknown.
CLUSTERS  a record's cluster = its span section (row); independence = distinct cluster pairs; disjoint = max pairwise-disjoint set.
VERDICT   >= 2 disjoint strict: <DATASET>-EMPIRICALLY-SUPPORTED · 1: WEAK · 0 strict but >= 1 possible: NOT-DEMONSTRATED (possible)
          · none: NOT-DEMONSTRATED · variable with < 2 known distinct values in the dataset: UNTESTABLE.
Usage: python3 support_1ay.py"""
import itertools, json, os
H = os.path.dirname(os.path.abspath(__file__)); TH = os.path.dirname(H)
UNK = {"", "UNK", "UNKNOWN", "N/A", "NOT_EVIDENCED", "NONE", "NULL"}
def known(v): return v is not None and str(v).strip().upper() not in UNK and not str(v).startswith("same:")
def norm_op(v): return str(v or "").strip().upper()
coding = {r["record_id"]: r for r in json.load(open(os.path.join(TH, "LOOP-1AX", "A", "CODING.json")))["records"]}
review = json.load(open(os.path.join(TH, "LOOP-1AX", "B", "REVIEW.json")))
applied, unapplied = [], []
for c in review.get("corrected", []):
    rid, f = c.get("record_id"), str(c.get("field", ""))
    parts = f.split(".")
    if rid in coding and len(parts) == 2 and parts[0] in ("act", "norm") and "reviewer" in c:
        coding[rid][parts[0]][parts[1]] = c["reviewer"]; applied.append((rid, f))
    else: unapplied.append(c)
lin = {l["record_id"]: l["v1_event_id"] for l in json.load(open(os.path.join(TH, "LOOP-1AX", "LINEAGE.json")))["lineage"]}
v1 = {e["event_id"]: e for e in json.load(open(os.path.join(TH, "LOOP-1AR", "EVENTS.json")))["events"]}
sect = {r["record_id"]: (r.get("section") or r["record_id"]) for r in json.load(open(os.path.join(TH, "LOOP-1AX", "A", "RECORDS.json")))["records"]}
POL = {"ACT": ({"ACT_OCCURRED"}, {"ACT_REFUSED", "ACT_WITHDRAWN", "ACT_REVERSED"}),
       "NORM": ({"PERMITTED", "AUTHORIZED", "ELIGIBLE", "DELEGATED", "REQUIRED"}, {"PROHIBITED", "UNAUTHORIZED", "INELIGIBLE"})}
V1F = ["a", "k", "s", "t", "e", "x", "h", "c", "r"]
def rows(ds):
    out = []
    for rid, r in coding.items():
        comp = r["act"] if ds == "ACT" else r["norm"]
        val = norm_op(comp.get("state") if ds == "ACT" else comp.get("deontic"))
        pos, neg = POL[ds]
        if val not in pos | neg: continue
        f = {"actor": comp.get("actor"), "target": comp.get("target")} if ds == "ACT" else \
            {"actor": comp.get("about_actor"), "target": comp.get("about_target"), "stated_by": comp.get("stated_by")}
        ev = v1.get(lin.get(rid), {})
        f.update({"v1_" + k: ev.get(k) for k in V1F})
        out.append({"rid": rid, "pol": val in pos, "op": norm_op(comp.get("operation") if ds == "ACT" else comp.get("about_operation")), "f": f, "cl": sect[rid]})
    return out
def disjoint(pairs):
    s = [frozenset(p) for p in {tuple(sorted(p)) for p in pairs}]
    for n in range(len(s), 0, -1):
        if any(all(not (a & b) for a, b in itertools.combinations(c, 2)) for c in itertools.combinations(s, n)): return n
    return 0
res = {"labels": "A' datasets; v1_* fields are V1-CODED (weaker epistemic basis); support never aggregated across datasets",
       "corrections_applied": applied, "corrections_unapplied": unapplied, "datasets": {}}
for ds in ("ACT", "NORM"):
    R = rows(ds); fields = list(R[0]["f"]) if R else []
    out = {"n_records": len(R), "n_clusters": len({x["cl"] for x in R}), "variables": {}}
    for v in fields:
        strict, poss = [], []
        vals = {str(x["f"][v]) for x in R if known(x["f"][v])}
        for p, q in itertools.combinations(R, 2):
            if p["pol"] == q["pol"] or p["op"] != q["op"] or not p["op"]: continue
            if not (known(p["f"][v]) and known(q["f"][v]) and p["f"][v] != q["f"][v]): continue
            others = [w for w in fields if w != v]
            if all(known(p["f"][w]) and known(q["f"][w]) and p["f"][w] == q["f"][w] for w in others): strict.append((p, q))
            elif all((not known(p["f"][w])) or (not known(q["f"][w])) or p["f"][w] == q["f"][w] for w in others): poss.append((p, q))
        cp = [(p["cl"], q["cl"]) for p, q in strict]; dj = disjoint(cp) if cp else 0
        verdict = ("UNTESTABLE" if len(vals) < 2 else f"{ds}-EMPIRICALLY-SUPPORTED" if dj >= 2 else "WEAK" if dj == 1
                   else "NOT-DEMONSTRATED (possible)" if poss else "NOT-DEMONSTRATED")
        out["variables"][v] = {"verdict": verdict, "strict_pairs": [[p["rid"], q["rid"]] for p, q in strict], "disjoint_clusters": dj,
                               "distinct_cluster_pairs": len({tuple(sorted(c)) for c in cp}), "possible_pairs": [[p["rid"], q["rid"]] for p, q in poss][:12],
                               "n_possible": len(poss), "known_values": len(vals)}
    res["datasets"][ds] = out
print(json.dumps(res, indent=1, ensure_ascii=False, default=str))
