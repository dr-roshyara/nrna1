"""1ay r2: per-variable support on the A' datasets over NORMALIZED fields (committed before the 1ay-n mapping exists).
Differences from r1 (whose outputs stand): (i) act.operation/actor/target and norm.about_operation/about_actor/about_target/stated_by
are replaced by the 1ay-n `mapped` code, reviewer-corrected where the 1ay-n review gives a correction; a value with no mapping, or
mapped UNMAPPABLE, is UNKNOWN; (ii) R06 and R07 (identical span; schema §1) are merged into one record R06+R07: for each component
field the two values are kept if equal, else UNKNOWN; polarity is taken only if equal, else the merged record is excluded from that
dataset. Everything else (polarity sets, pairing on equal operation, STRICT/POSSIBLE, clusters, verdicts) is r1's logic unchanged.
Usage: python3 support_1ay_r2.py"""
import importlib.util, json, os
H = os.path.dirname(os.path.abspath(__file__)); TH = os.path.dirname(H)
src = open(os.path.join(H, "support_1ay.py")).read().split("res = {")[0]          # r1 definitions and coding load, unchanged
ns = {"__file__": os.path.join(H, "support_1ay.py")}; exec(compile(src, "support_1ay.py", "exec"), ns)
coding = ns["coding"]
mp = {(i["record_id"], i["field"]): i.get("mapped") for i in json.load(open(os.path.join(TH, "LOOP-1AYN", "A", "MAPPING.json")))["items"]}
rv = json.load(open(os.path.join(TH, "LOOP-1AYN", "B", "REVIEW.json")))
for c in rv.get("corrected", []):
    if c.get("record_id") and c.get("field") and "reviewer" in c: mp[(c["record_id"], c["field"])] = c["reviewer"]
FIELDS = {"act": ("operation", "actor", "target"), "norm": ("about_operation", "about_actor", "about_target", "stated_by")}
unm = 0
for rid, r in coding.items():
    for comp, fs in FIELDS.items():
        for f in fs:
            m = mp.get((rid, f"{comp}.{f}"))
            if r[comp].get(f) not in (None, "", "NONE", "UNKNOWN", "UNK") and (m is None or str(m).upper() == "UNMAPPABLE"): unm += 1
            r[comp][f] = None if (m is None or str(m).upper() == "UNMAPPABLE") else m
if "R06" in coding and "R07" in coding:
    a, b = coding.pop("R06"), coding.pop("R07"); m = {"record_id": "R06+R07", "act": {}, "norm": {}}
    for comp in ("act", "norm"):
        for f in set(a[comp]) | set(b[comp]):
            m[comp][f] = a[comp].get(f) if a[comp].get(f) == b[comp].get(f) else None
    coding["R06+R07"] = m; ns["sect"]["R06+R07"] = ns["sect"].get("R06"); ns["lin"]["R06+R07"] = ns["lin"].get("R06")
ns["coding"] = coding
rest = open(os.path.join(H, "support_1ay.py")).read().split("res = {", 1)[1]
exec(compile("res = {" + rest.replace('"labels": "A\' datasets', '"labels": "r2 NORMALIZED; A\' datasets'), "support_1ay_r2.py", "exec"), ns)
