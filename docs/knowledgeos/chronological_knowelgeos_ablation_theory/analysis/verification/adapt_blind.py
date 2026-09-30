"""Adapters for the blind headless Claude results (CANONICAL-SCHEMA.md §4): RELOCATION + §3 vocabulary only.
Written from the key structure of the sealed files (keytree.py), never from their values. No value is computed,
inferred, defaulted or repaired; a canonical key without a source field is omitted (-> MISSING); every source field
not relocated is listed in `unmapped` as a de-duplicated path pattern (-> EXTRA).
Declared relocation choices (the adapter's C-7 readings):
  G2 : `witness.length` or, where the verifier names it so, `example.length` -> C-6 len; scenario `answer` / `u_changes` /
       `b_changes` -> C-3 value; `status` -> C-1 applicability (raw string, vocabulary-normalized only).
  G25: scenario variant `strict_order` -> C-3 (the spec's ordered pattern, distinct steps); `same_step_allowed` unmapped;
       `properties.REP` on chain3 -> C-3|M|chain3|REP (the reference reports REP as a chain3 scenario); REP elsewhere unmapped.
Usage: python3 adapt_blind.py G2|G25 <results.json>  > ind.canon.json"""
import json
import re
import sys

from canon import norm, sets

E, UN = {}, set()


def put(crit, key, v, parent=None): E["|".join([crit] + list(key))] = {"c": crit, "v": v, "parent": parent}


def un(path, d, used):
    for k in d:
        if k not in used: UN.add(re.sub(r"\.models\.[^.]+", ".models.*", path + "." + k))


def pat(*p): return "." + ".".join(p)


def wl(pv):
    for wk in ("witness", "example"):
        w = pv.get(wk)
        if isinstance(w, dict) and "length" in w: return w["length"]
    return "__absent__"


def g2(d):
    for m, M in d["models"].items():
        for i, I in M.items():
            P = pat("models", m, "*"); un(P, I, {"status", "state_count", "reachable_states", "reachable_N_states", "properties",
                                                   "ablation", "minimal_sets", "redundant_added_axioms", "scenarios"})
            if "status" in I: put("C-1", [m, i, "applicability"], norm(I["status"]))
            for src, dst in (("state_count", "states"), ("reachable_states", "reachable"), ("reachable_N_states", "reachable_N")):
                if src in I: put("C-1", [m, i, dst], I[src])
            for p, pv in I.get("properties", {}).items():
                un(P + ".properties.*", pv, {"class", "witness", "example"})
                if "class" in pv: put("C-2", [m, i, p], norm(pv["class"]))
                L = wl(pv)
                if L != "__absent__": put("C-6", [m, i, p, "len"], L, parent="|".join(["C-2", m, i, p]))
            for ax, A in I.get("ablation", {}).items():
                un(P + ".ablation.*", A, {"properties"})
                for p, pv in A.get("properties", {}).items():
                    if "class" in pv: put("C-4", [m, i, "-" + ax, p], norm(pv["class"]))
            for p, L in I.get("minimal_sets", {}).items(): put("C-5", [m, i, "minimal", p], sets(L))
            if "redundant_added_axioms" in I: put("C-5", [m, i, "redundant"], sorted(I["redundant_added_axioms"]))
            for s, sv in I.get("scenarios", {}).items():
                if isinstance(sv, dict) and s == "S2":
                    for sub, x in sv.items(): put("C-3", [m, i, "S2:" + sub], norm(x.get("answer")) if isinstance(x, dict) else norm(x))
                elif isinstance(sv, dict) and s == "S4":
                    for sub, key in (("u_changes", "S4:u"), ("b_changes", "S4:b")):
                        if sub in sv: put("C-3", [m, i, key], sv[sub])
                elif isinstance(sv, dict):
                    un(P + ".scenarios.*", sv, {"answer"})
                    if "answer" in sv: put("C-3", [m, i, s], norm(sv["answer"]))
                else: put("C-3", [m, i, s], norm(sv))


def g25(d):
    for m, M in d["models"].items():
        un(pat("models", m), M, {"instances"})
        for i, I in M["instances"].items():
            P = pat("models", m, "instances", "*")
            un(P, I, {"counts", "properties", "ablation", "minimal_sets", "redundant_added_axioms", "scenarios"})
            for k in ("states", "reachable", "reachable_ad1"):
                if k in I.get("counts", {}): put("C-1", [m, i, k], I["counts"][k])
            for p, pv in I.get("properties", {}).items():
                if p == "REP":
                    if i == "chain3" and "class" in pv: put("C-3", [m, "chain3", "REP"], norm(pv["class"]))
                    else: UN.add(P + ".properties.REP")
                    continue
                un(P + ".properties.*", pv, {"class", "witness"})
                if "class" in pv: put("C-2", [m, i, p], norm(pv["class"]))
                L = wl(pv)
                if L != "__absent__": put("C-6", [m, i, p, "len"], L, parent="|".join(["C-2", m, i, p]))
            for ax, A in I.get("ablation", {}).items():
                for p, pv in A.items():
                    if p == "counts": UN.add(P + ".ablation.*.counts"); continue
                    if isinstance(pv, dict) and "class" in pv: put("C-4", [m, i, "-" + ax, p], norm(pv["class"]))
            for p, L in I.get("minimal_sets", {}).items(): put("C-5", [m, i, "minimal", p], sets(L))
            if "redundant_added_axioms" in I: put("C-5", [m, i, "redundant"], sorted(I["redundant_added_axioms"]))
            for t, sv in I.get("scenarios", {}).items():
                un(P + ".scenarios.*", sv, {"strict_order"})
                so = sv.get("strict_order")
                if not isinstance(so, dict): continue
                k0 = [m, i, t]; par = "|".join(["C-3"] + k0)
                if "exists" in so: put("C-3", k0, norm(so["exists"]))
                w = so.get("witness")
                if isinstance(w, dict) and "length" in w: put("C-3", k0 + ["len"], w["length"], parent=par)
                ev = so.get("events")
                if isinstance(ev, dict):
                    for tt in ("t_a", "t_p"):
                        x = ev.get(tt)
                        if not isinstance(x, dict): continue
                        un(P + ".scenarios.*.strict_order.events.*", x, {"e", "u", "E0", "EB"})
                        for f in ("e", "u", "E0", "EB"):
                            if f in x: put("C-3", k0 + [tt, f], sorted(x[f]) if isinstance(x[f], list) else x[f], parent=par)


if __name__ == "__main__":
    gate = sys.argv[1]; {"G2": g2, "G25": g25}[gate](json.load(open(sys.argv[2])))
    json.dump({"gate": gate, "entries": E, "unmapped": sorted(UN)}, sys.stdout, indent=0, sort_keys=True); print()
