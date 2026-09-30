"""Values-blind self-test of canon.py + compare_mech.py. Prints categories and tallies only, never result values."""
import copy, json, subprocess, sys
from compare_mech import compare, vtype
H = "/".join(__file__.split("/")[:-1]) or "."
def canon(g, p): return json.loads(subprocess.run([sys.executable, H + "/canon.py", g, p], capture_output=True, text=True, check=True).stdout)
ok = True
for g, p in (("G2", H + "/../gate2/results_ref_r2.json"), ("G25", H + "/../gate25/results_ref.json")):
    R = canon(g, p); t, d = compare(R, copy.deepcopy(R))
    exact = all(v["EXACT"] == sum(v.values()) for v in t.values()); ok &= exact and not d
    print(g, "identity:", "all EXACT" if exact and not d else "FAIL")  # no per-criterion counts: they are value-dependent (F-LOG-0069)
    I = copy.deepcopy(R); e = I["entries"]; ks = sorted(e)
    k_int = next(k for k in ks if vtype(e[k]["v"]) == "int" and e[k]["parent"] is None)
    k_cls = next(k for k in ks if k.startswith("C-2") and vtype(e[k]["v"]) == "class" and any(x["parent"] == k for x in e.values()))
    k_dep = next(k for k in ks if e[k]["parent"] == k_cls)
    k_del = next(k for k in ks if k.startswith("C-4")); k_typ = next(k for k in ks if k.startswith("C-5"))
    e[k_int]["v"] += 1
    e[k_cls]["v"] = "FAILS" if e[k_cls]["v"] != "FAILS" else "HOLDS"
    del e[k_del]; e[k_typ]["v"] = "HOLDS"; e["C-2|ZZ|ZZ|ZZ"] = {"c": "C-2", "v": "HOLDS", "parent": None}; I["unmapped"] = ["foo"]
    t, d = compare(R, I); got = {x["key"]: x["cat"] for x in d}
    exp = {k_int: "NUMERIC-MISMATCH", k_cls: "SEMANTIC-MISMATCH", k_dep: "NOT-COMPARED(parent)", k_del: "MISSING",
           k_typ: "STRUCTURAL-MISMATCH", "C-2|ZZ|ZZ|ZZ": "EXTRA", "unmapped|foo": "EXTRA"}
    good = all(got.get(k) == c for k, c in exp.items()) and len([x for x in d if x["key"] not in exp]) == 0
    ok &= good; print(g, "mutations:", "as designed" if good else "FAIL", sorted(set(exp.values())))
sys.exit(0 if ok else 1)
