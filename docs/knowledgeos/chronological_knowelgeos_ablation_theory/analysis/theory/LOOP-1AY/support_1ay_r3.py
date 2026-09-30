"""1ay r3 (committed before its first run; disclosed defect fixes of r2, whose outputs stand).
Fixes, both specified from the r2 confounding diagnostic (DIAG-r2-confounding.txt), not from any verdict:
 F1 DUPLICATE CONCEPT: A' `target` and v1 `v1_t` denote the same concept; r2 compared both, so a pair differing only in target
    could never be STRICT. r3 drops v1_t (the A' field is the reviewed, span-anchored one).
 F2 CIRCULARITY: in the NORM dataset v1 `v1_s` is the authorization state of the target, i.e. the norm itself (F-LOG-0170 D9);
    comparing it makes every permission contrast differ 'in state' by construction. r3 drops v1_s from NORM comparisons only.
Everything else is r2 unchanged. Usage: python3 support_1ay_r3.py"""
import os
H = os.path.dirname(os.path.abspath(__file__))
src = open(os.path.join(H, "support_1ay_r2.py")).read()
pre, rest = src.split("rest = open", 1)
outer = {"__file__": os.path.join(H, "support_1ay_r2.py")}
exec(compile(pre, "r2-pre", "exec"), outer)
ns = outer["ns"]
_rows = ns["rows"]
def rows(ds):
    R = _rows(ds)
    for x in R:
        x["f"].pop("v1_t", None)
        if ds == "NORM": x["f"].pop("v1_s", None)
    return R
ns["rows"] = rows
body = open(os.path.join(H, "support_1ay.py")).read().split("res = {", 1)[1]
exec(compile("res = {" + body.replace('"labels": "A\' datasets', '"labels": "r3 (r2 + F1 drop v1_t, F2 drop v1_s in NORM); A\' datasets'), "r3", "exec"), ns)
