import os, sys, json
HERE = "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/chronological-read/scripts/tests"
SCRIPTS = os.path.dirname(HERE)
sys.path.insert(0, HERE)
sys.path.insert(0, SCRIPTS)
import r7_full_fixture as FX
import p3b_s5_r7_universe as U

SGL_LAB = "relation-8-tuple-vs-triple-reduction"

st = FX.base()
owners = U.run_owner(st["plan"])
sgl_run = next(r for r, (lab, role, _) in owners.items() if lab == SGL_LAB)
print("dispatched run id for SINGLE label:", sgl_run)

def mut_obj_run_id_only(st2):
    o = FX.objs_of(st2, SGL_LAB)
    o["run_id"] = sgl_run           # the DISPATCHED id, per rev3 item(3); NOT <B>-R7
    # leave register/gap untouched: only the OBJECT record uses the dispatched id

body = FX.verify(st, mut_obj_run_id_only)
print("=== TEST 1: object run_id = dispatched id (single label, no collision) ===")
print("result:", body["result"])
print("predicates:", body["predicates"])
print([x[:160] for x in body["failures"] if "G-07" in x][:10])
