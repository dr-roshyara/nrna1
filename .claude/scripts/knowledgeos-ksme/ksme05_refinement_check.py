#!/usr/bin/env python3
"""
Follow-up to ksme05.py: does F4's K_O partition (27,398 classes, already
self-robust) REFINE the F2-based K_R (17,129 classes)? Cardinality alone
(27398 > 17129) is necessary but not sufficient for a refinement claim --
verify directly: every F4-class must be a subset of some K_R(F2)-class.
"""
import sys, os, itertools, json, time
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from ksme04 import (reachable_closure, obs_partition, refine_to_fixpoint,
                     class_id_map, F_content_status, F_AR, SPACE, OP_NAMES)
import ksme05
from ksme05 import distinct_models, model_key

t0 = time.time()
E = reachable_closure(SPACE)

# recompute K_R(F2) via the same incremental-intersection method
running_sig = {s: () for s in E}
for model in distinct_models():
    classes, rounds = refine_to_fixpoint(E, model, F_content_status)
    m = class_id_map(classes)
    for s in E:
        running_sig[s] = running_sig[s] + (m[s],)

KR_F2_class_of = {}
for s, sig in running_sig.items():
    KR_F2_class_of.setdefault(sig, len(KR_F2_class_of) if sig not in KR_F2_class_of else KR_F2_class_of[sig])
# build proper class ids
sig_to_id = {}
for sig in running_sig.values():
    if sig not in sig_to_id:
        sig_to_id[sig] = len(sig_to_id)
KR_F2_id = {s: sig_to_id[sig] for s, sig in running_sig.items()}
print("K_R(F2) classes:", len(sig_to_id))

F4_classes = obs_partition(E, F_AR)
F4_id = {}
for cid, cls in enumerate(F4_classes):
    for s in cls:
        F4_id[s] = cid
print("F4 K_O classes:", len(F4_classes))

# check refinement: every F4-class subset of a single K_R(F2)-class?
violations = 0
sample_violation = None
for cls in F4_classes:
    kr_ids = set(KR_F2_id[s] for s in cls)
    if len(kr_ids) > 1:
        violations += 1
        if sample_violation is None:
            sample_violation = (cls[0], cls[1] if len(cls) > 1 else None, kr_ids)

result = {
    "K_R_F2_classes": len(sig_to_id),
    "F4_K_O_classes": len(F4_classes),
    "F4_refines_KR_F2_cleanly": violations == 0,
    "violations": violations,
    "disposition": ("F4 REFINES K_R(F2): every F4 class sits inside one K_R(F2) class -- "
                     "F4 draws AT LEAST all robustly-necessary distinctions, PLUS extra ones"
                     if violations == 0 else
                     "F4 and K_R(F2) are INCOMPARABLE: some F4 class spans multiple K_R(F2) "
                     "classes, meaning F4 actually MERGES some robustly-necessary distinctions "
                     "despite being nominally finer overall -- a real, reportable finding, not "
                     "assumed away"),
    "wall_time_seconds": round(time.time() - t0, 1),
}
print(json.dumps(result, indent=2, default=str))
out = "/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/e80b6590-f013-40ab-b3b7-5adc71f528e7/scratchpad/results05_refinement.json"
with open(out, "w") as f:
    json.dump(result, f, indent=2, default=str)
