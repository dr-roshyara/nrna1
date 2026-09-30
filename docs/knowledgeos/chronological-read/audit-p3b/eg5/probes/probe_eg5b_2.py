import os, sys, json, re
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
B = st["ctx"]["batch"] if "batch" in st["ctx"] else None
print("batch:", st["plan"].get("batch"))

def mut_register_rs_id_only(st2):
    # rewrite ONLY the register records of SGL_LAB to use the dispatched run id in rs_id/run_id
    # (numbering kept as-is: single label, so no collision risk)
    for r in st2["ctx"]["reg"]:
        if r.get("working_label") == SGL_LAB:
            old = r["rs_id"]
            # replace the run-prefix (batch-R7) with the dispatched run id, keep :<n> suffix
            m = re.match(r"^(.*?):(OB\d+):(\d+)$", old)
            assert m, old
            r["rs_id"] = f"{sgl_run}:{m.group(2)}:{m.group(3)}"
            r["run_id"] = sgl_run

body = FX.verify(st, mut_register_rs_id_only)
print("=== TEST 2: register rs_id/run_id = dispatched id (single label, no collision) ===")
print("result:", body["result"])
print("predicates:", body["predicates"])
print([x[:160] for x in body["failures"] if "G-07" in x][:10])
