import sys, collections
sys.path.insert(0, "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/chronological-read/scripts/tests")
sys.path.insert(0, "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/chronological-read/scripts")
import r7_full_fixture as FX

EMPTY_LAB = FX.EMPTY_LAB
ST = FX.base()
reg = collections.Counter(r["working_label"] for r in ST["ctx"]["reg"])
gap = collections.Counter(g["working_label"] for g in ST["ctx"]["gap"])
print("EMPTY label:", EMPTY_LAB)
print("register records for EMPTY label:", reg.get(EMPTY_LAB, 0))
print("gap records for EMPTY label:", gap.get(EMPTY_LAB, 0))
print("all labels register counts:", dict(reg))
print("all labels gap counts:", dict(gap))
# does run_outputs even place these anywhere on disk? EMPTY label has no run, so where would they go?
plan = ST["plan"]
print("EMPTY label runs:", plan["labels"][EMPTY_LAB]["runs"])
out = FX.run_outputs(ST)
print("run dirs with data:", list(out.keys()))
