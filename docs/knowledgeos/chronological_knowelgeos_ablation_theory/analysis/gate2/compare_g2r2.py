"""Mechanical comparison of the Gate 2 r2 reference vs blind results under pre-registration r2-7 (C-1..C-7).
TRUE classes = {HOLDS, VACUOUS}; a difference inside TRUE (HOLDS vs VACUOUS) is CLASS_READING, a truth-value
difference is SUBSTANTIVE. Nothing is edited or averaged. Read-only; standard library; deterministic."""
import json
import os
import sys

H = os.path.dirname(os.path.abspath(__file__))
REF = json.load(open(os.path.join(H, "results_ref_r2.json")))
BL = json.load(open(os.path.join(H, "BLIND-HEADLESS-r2", "results.json")))["models"]
TRUE = {"HOLDS", "VACUOUS"}
out = {k: [] for k in ("C-1", "C-2", "C-3", "C-4", "C-5", "C-6")}
tally = {k: {"compared": 0, "exact": 0, "class_reading": 0, "substantive": 0} for k in out}


def cmp_class(crit, key, a, b):
    t = tally[crit]; t["compared"] += 1
    if a == b: t["exact"] += 1; return
    if a in TRUE and b in TRUE: t["class_reading"] += 1; out[crit].append(["CLASS_READING", key, a, b]); return
    t["substantive"] += 1; out[crit].append(["SUBSTANTIVE", key, a, b])


def wlen(w):
    if w is None: return None
    return w.get("length") if isinstance(w, dict) else None


for mdl, R in REF.items():
    for inst, ri in R["instances"].items():
        bi = BL[mdl][inst]
        if ri == "NOT_APPLICABLE" or bi.get("applicable") is False:
            cmp_class("C-2", [mdl, inst, "applicability"], "NOT_APPLICABLE" if ri == "NOT_APPLICABLE" else "APPLICABLE",
                      "NOT_APPLICABLE" if bi.get("applicable") is False else "APPLICABLE"); continue
        for a, b, nm in ((ri["counts"]["states"], bi["state_count"], "states"), (ri["counts"]["reachable"], bi["reachable_states"], "reachable"),
                         (ri["counts"]["reachable_N"], bi["reachable_N_states"], "reachable_N")):
            tally["C-1"]["compared"] += 1
            if a == b: tally["C-1"]["exact"] += 1
            else: tally["C-1"]["substantive"] += 1; out["C-1"].append(["SUBSTANTIVE", [mdl, inst, nm], a, b])
        for p, rv in ri["properties"].items():
            bv = bi["properties"][p]; cmp_class("C-2", [mdl, inst, p], rv["class"], bv["class"])
            if rv["class"] == "FAILS" and bv["class"] == "FAILS":
                tally["C-6"]["compared"] += 1
                if rv["len"] == wlen(bv["witness"]): tally["C-6"]["exact"] += 1
                else: tally["C-6"]["substantive"] += 1; out["C-6"].append(["SUBSTANTIVE", [mdl, inst, p], rv["len"], wlen(bv["witness"])])
        for ax, props in ri["ablation"].items():
            for p, rc in props.items():
                cmp_class("C-4", [mdl, inst, "-" + ax, p], rc, bi["ablation"][ax]["properties"][p])
        bm = bi["minimal_sets"]
        for p in sorted(set(ri["minimal_added_sets"]) | set(bm)):
            a = sorted(map(sorted, ri["minimal_added_sets"].get(p, []))); b = sorted(map(sorted, bm.get(p, [])))
            tally["C-5"]["compared"] += 1
            if a == b: tally["C-5"]["exact"] += 1
            else:
                # a property absent on one side because its class is VACUOUS there (reading 6) is a class reading
                kind = "CLASS_READING" if (not a or not b) else "SUBSTANTIVE"
                tally["C-5"]["class_reading" if kind == "CLASS_READING" else "substantive"] += 1
                out["C-5"].append([kind, [mdl, inst, "minimal", p], a, b])
        tally["C-5"]["compared"] += 1
        if sorted(ri["redundant_added_axioms"]) == sorted(bi["redundant_added_axioms"]): tally["C-5"]["exact"] += 1
        else: tally["C-5"]["substantive"] += 1; out["C-5"].append(["SUBSTANTIVE", [mdl, inst, "redundant"], ri["redundant_added_axioms"], bi["redundant_added_axioms"]])
    # scenarios (chain3)
    rs, bs = R["scenarios"], BL[mdl]["chain3"]["scenarios"]
    def breach(x):
        if isinstance(x, dict) and "reachable" in x: return x["reachable"]
        return x
    pairs = [("S0", rs["S0"], breach(bs["S0"])), ("S1", rs["S1"], breach(bs["S1"])), ("S2:N", rs["S2:N"], breach(bs["S2"]["N"])),
             ("S2:PS", rs["S2:PS"], breach(bs["S2"]["PS"])), ("S3", rs["S3"], breach(bs["S3"])),
             ("S4:u", rs["S4:u"], bs["S4"]["u_changes"]["answer"]), ("S6", rs["S6"], breach(bs["S6"]))]
    if "S4:b" in rs: pairs.append(("S4:b", rs["S4:b"], bs["S4"]["b_changes"]["answer"]))
    if "S4-T" in rs: pairs.append(("S4-T", rs["S4-T"], breach(bs["S4-T"])))
    pairs.append(("S5", rs["S5"], breach(bs["S5"]) if isinstance(bs["S5"], dict) else bs["S5"]))
    for nm, a, b in pairs:
        na = a if isinstance(a, (bool, str)) else (a is not None)
        tally["C-3"]["compared"] += 1
        if na == b: tally["C-3"]["exact"] += 1
        else: tally["C-3"]["substantive"] += 1; out["C-3"].append(["SUBSTANTIVE", [mdl, "chain3", nm], na, b])

json.dump({"rule": "pre-registration r2-7; TRUE = {HOLDS, VACUOUS}", "tally": tally, "differences": out}, sys.stdout, indent=1, default=str)
print()
