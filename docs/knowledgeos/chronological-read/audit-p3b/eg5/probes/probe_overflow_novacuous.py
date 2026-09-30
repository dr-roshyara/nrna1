import sys, os
sys.path.insert(0, "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/chronological-read/scripts/tests")
sys.path.insert(0, "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/chronological-read/scripts")
import r7_full_fixture as FX

ST = FX.base()
OB, R7 = FX.OB, FX.R7

def renumber_and_overflow_no_collision(st):
    import collections
    ctx = st["ctx"]
    IDX = {lab: L["index"] for lab, L in st["plan"]["labels"].items()}
    for key, fld, pre in (("reg", "rs_id", ""), ("gap", "gap_id", "G")):
        k = collections.Counter()
        for r in ctx[key]:
            lab = r["working_label"]
            k[lab] += 1
            r[fld] = f"{R7}:{OB}:{pre}{1000*IDX[lab]+k[lab]}"
            r["run_id"] = R7
    # now push ONE register record's n far out of range, to a value that collides with NOTHING
    r0 = ctx["reg"][0]
    r0["rs_id"] = f"{R7}:{OB}:999999"

body = FX.verify(ST, renumber_and_overflow_no_collision)
print("result:", body["result"])
print([x for x in body["failures"] if "G-07" in x or "R7-U" in x][:10])
