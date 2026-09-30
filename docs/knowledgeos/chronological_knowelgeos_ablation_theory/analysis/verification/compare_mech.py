"""Mechanical comparator (CANONICAL-SCHEMA.md §6). No expected values, no preference, no interpretation.
Usage: python3 compare_mech.py ref.canon.json ind.canon.json [--tally-only]  > DIFF.json"""
import json
import sys

CLASSES = {"HOLDS", "FAILS", "VACUOUS", "NOT_APPLICABLE", "NOT_REPRESENTABLE"}
TRUE = {"HOLDS", "VACUOUS"}
CATS = ("EXACT", "MISSING", "EXTRA", "STRUCTURAL-MISMATCH", "NUMERIC-MISMATCH", "SEMANTIC-MISMATCH", "NOT-COMPARED(parent)")


def vtype(v):
    if v is None: return "null"
    if isinstance(v, bool): return "bool"
    if isinstance(v, int): return "int"
    if isinstance(v, list): return "list"
    if isinstance(v, str): return "class" if v in CLASSES else "str"
    return "other"


def classify(a, b):
    ta, tb = vtype(a), vtype(b)
    if ta == "other" or tb == "other" or ta != tb: return "STRUCTURAL-MISMATCH"
    if a == b: return "EXACT"
    return "NUMERIC-MISMATCH" if ta == "int" else "SEMANTIC-MISMATCH"


def compare(R, I):
    if R["gate"] != I["gate"]: raise SystemExit("gate mismatch")
    re, ie = R["entries"], I["entries"]; res = {}
    def cat(k):
        if k in res: return res[k]
        if k not in ie: res[k] = ("MISSING", None); return res[k]
        par = re[k]["parent"]
        if par is not None and cat(par)[0] != "EXACT": res[k] = ("NOT-COMPARED(parent)", None); return res[k]
        a, b = re[k]["v"], ie[k]["v"]; c = classify(a, b)
        res[k] = (c, {"both_true": a in TRUE and b in TRUE} if c == "SEMANTIC-MISMATCH" and vtype(a) == "class" else None)
        return res[k]
    for k in re: cat(k)
    for k in ie:
        if k not in re: res[k] = ("EXTRA", None)
    for k in I.get("unmapped", []): res["unmapped|" + k] = ("EXTRA", None)
    tally = {}
    for k, (c, _) in res.items():
        crit = k.split("|")[0]; tally.setdefault(crit, {x: 0 for x in CATS})[c] += 1
    diffs = [{"key": k, "cat": c, "ref": re.get(k, {}).get("v"), "ind": ie.get(k, {}).get("v"), **({"note": n} if n else {})}
             for k, (c, n) in sorted(res.items()) if c != "EXACT"]
    return tally, diffs


if __name__ == "__main__":
    R, I = json.load(open(sys.argv[1])), json.load(open(sys.argv[2]))
    tally, diffs = compare(R, I)
    out = {"schema": "CANONICAL-SCHEMA.md", "tally": tally}
    if "--tally-only" not in sys.argv: out["differences"] = diffs
    json.dump(out, sys.stdout, indent=1, sort_keys=True, default=str); print()
