"""Reference canonicalizers (CANONICAL-SCHEMA.md §2, §3, §5). Pure relocation + frozen vocabulary; no value logic.
Usage: python3 canon.py G2|G25 <reference results.json>  > ref.canon.json"""
import json
import sys

CLASSES = {"HOLDS", "FAILS", "VACUOUS", "NOT_APPLICABLE", "NOT_REPRESENTABLE"}


def norm(v):
    """§3 vocabulary normalization; anything else is returned unchanged (never coerced)."""
    if isinstance(v, str):
        t = v.strip().upper().replace("-", "_").replace(" ", "_")
        if t == "REACHABLE": return True
        if t == "NOT_REACHABLE": return False
        if t in CLASSES: return t
    return v


def sets(L): return sorted(sorted(s) for s in L)


class Out:
    def __init__(self, gate): self.gate = gate; self.e = {}
    def put(self, crit, key, v, parent=None): self.e["|".join([crit] + list(key))] = {"c": crit, "v": v, "parent": parent}
    def dump(self): json.dump({"gate": self.gate, "entries": self.e}, sys.stdout, indent=0, sort_keys=True); print()


def g2(d):
    o = Out("G2")
    for m, R in d.items():
        for i, ri in R["instances"].items():
            if ri == "NOT_APPLICABLE": o.put("C-1", [m, i, "applicability"], "NOT_APPLICABLE"); continue
            o.put("C-1", [m, i, "applicability"], "APPLICABLE")
            for k in ("states", "reachable", "reachable_N"): o.put("C-1", [m, i, k], ri["counts"][k])
            for p, pv in ri["properties"].items():
                o.put("C-2", [m, i, p], norm(pv["class"]))
                o.put("C-6", [m, i, p, "len"], pv["len"], parent="|".join(["C-2", m, i, p]))
            for ax, props in ri["ablation"].items():
                for p, c in props.items(): o.put("C-4", [m, i, "-" + ax, p], norm(c))
            for p, L in ri["minimal_added_sets"].items(): o.put("C-5", [m, i, "minimal", p], sets(L))
            o.put("C-5", [m, i, "redundant"], sorted(ri["redundant_added_axioms"]))
        for s, v in R["scenarios"].items():
            o.put("C-3", [m, "chain3", s], True if isinstance(v, list) else (False if v is None else norm(v)))
    return o


def g25(d):
    o = Out("G25")
    for m, R in d.items():
        for i, ri in R["instances"].items():
            for k in ("states", "reachable", "reachable_ad1"): o.put("C-1", [m, i, k], ri["counts"][k])
            for p, pv in ri["properties"].items():
                o.put("C-2", [m, i, p], norm(pv["class"]))
                o.put("C-6", [m, i, p, "len"], pv["len"], parent="|".join(["C-2", m, i, p]))
            for ax, props in ri["ablation"].items():
                for p, c in props.items(): o.put("C-4", [m, i, "-" + ax, p], norm(c))
            for p, L in ri["minimal_added_sets"].items(): o.put("C-5", [m, i, "minimal", p], sets(L))
            o.put("C-5", [m, i, "redundant"], sorted(ri["redundant_added_axioms"]))
        for t, sv in R["scenarios"].items():
            k0 = [m, "chain3", t]; o.put("C-3", k0, bool(sv["reachable"])); par = "|".join(["C-3"] + k0)
            if t == "REP" or not sv["reachable"]: continue
            o.put("C-3", k0 + ["len"], sv["len"], parent=par)
            for tt in ("t_a", "t_p"):
                for f in ("e", "u", "E0", "EB"):
                    v = sv[tt][f]; o.put("C-3", k0 + [tt, f], sorted(v) if isinstance(v, list) else v, parent=par)
    return o


if __name__ == "__main__":
    gate, path = sys.argv[1], sys.argv[2]
    {"G2": g2, "G25": g25}[gate](json.load(open(path))).dump()
