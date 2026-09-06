#!/usr/bin/env python3
r"""KR-ZOOM-01 analysis. Counts and exact classifications only.
NO "Zoom Score" is computed (spec §16). Every metric reports numerator AND denominator."""
import os, sys, json, collections
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
rows = [json.loads(l) for l in open(f"{ROOT}/data/ledger.jsonl")]

def frac(n, d): return {"n": n, "d": d, "rate": round(n/d, 5) if d else None}

def analyse(split):
    R  = [r for r in rows if r.get("split") == split and "record" not in r]
    P  = [r for r in rows if r.get("split") == split and r.get("record") == "PATH"]
    CF = [r for r in rows if r.get("split") == split and r.get("record") == "CF"]
    out = {}

    # ---------------- H1 recursive refinement
    out["H1_zoom_admissible"]  = frac(sum(r["zoom_admissible"] for r in R), len(R))
    out["H1_zoom_nontrivial"]  = frac(sum(r["zoom_nontrivial"] for r in R), len(R))
    out["H1_by_direction"] = {d: frac(sum(r["zoom_nontrivial"] for r in R if r["traversal_direction"]==d),
                                      sum(1 for r in R if r["traversal_direction"]==d))
                              for d in ("INWARD","OUTWARD","RETROSPECTIVE","PROSPECTIVE")}
    NT = [r for r in R if r["zoom_nontrivial"]]

    # ---------------- H2 resolution-relative atomicity
    out["H2_atomicity_transitions"] = frac(sum(1 for r in NT if r.get("h2_transitions")), len(NT))
    out["H2_transition_pairs"] = dict(collections.Counter(
        t for r in NT for t in r.get("h2_transitions", [])))

    # ---------------- H3 Zero instability, Q FIXED
    z_r0_zero, z_r0_nonzero = 0, 0
    for r in R:
        for zt in r.get("zero_tests", []):
            (z_r0_zero if zt["zero_at_current_resolution"] else z_r0_nonzero).__str__()
    zc = collections.Counter()
    seen = set()
    for r in R:
        if r["case_id"] in seen: continue
        seen.add(r["case_id"])
        for zt in r.get("zero_tests", []):
            zc["zero" if zt["zero_at_current_resolution"] else "nonzero"] += 1
    out["H3_r0_zero_rate"] = frac(zc["zero"], zc["zero"]+zc["nonzero"])
    n_h3 = sum(1 for r in NT if r.get("h3_nonzero_at_r1"))
    out["H3_zoomed_states_with_a_nonzero_dim"] = frac(n_h3, len(NT))
    z1 = collections.Counter()
    for r in NT:
        for zt in r.get("zero_tests_r1", []):
            z1["zero" if zt["zero_at_current_resolution"] else "nonzero"] += 1
    out["H3_r1_zero_rate"] = frac(z1["zero"], z1["zero"]+z1["nonzero"])

    # ---------------- H4 resolution-dependent determination
    out["H4_classes"] = dict(collections.Counter(r["h4_class"] for r in NT))
    out["H4_state_differs_but_same_observable"] = frac(
        sum(1 for r in NT if r["h4_state_differs"] and r["h4_class"]=="same_observable"), len(NT))

    # ---------------- H5 re-basing
    out["H5_rebasing"] = frac(sum(1 for r in NT if r["rebasing_observed"]), len(NT))
    out["H5_contract_determinate"] = frac(
        sum(1 for r in NT if r.get("rebasing",{}).get("contract_determinate")),
        sum(1 for r in NT if r["rebasing_observed"]))

    # ---------------- H6 Q-reconstruction
    out["H6_reconstruction_equal"] = frac(
        sum(1 for r in NT if r.get("q_reconstruction",{}).get("equal_to_x_r")), len(NT))

    # ---------------- §12 path / order — four SEPARATE equalities
    PA = [r for r in P if r["both_admissible"]]
    keys = ("final_state_equal","observable_equal","intermediate_state_equal","relation_set_equal")
    out["PATH_pairs_both_admissible"] = frac(len(PA), len(P))
    out["PATH"] = {k: frac(sum(1 for r in PA if r["path_comparison"][k]), len(PA)) for k in keys}
    out["PATH_by_pair"] = {}
    for pr in sorted({tuple(r["pair"]) for r in PA}):
        sub=[r for r in PA if tuple(r["pair"])==pr]
        out["PATH_by_pair"]["+".join(pr)] = {k: frac(sum(1 for r in sub if r["path_comparison"][k]), len(sub))
                                             for k in keys}
    out["PATH_joint"] = dict(collections.Counter(
        tuple(r["path_comparison"][k] for k in keys) for r in PA))
    out["PATH_joint"] = {str(k): v for k, v in out["PATH_joint"].items()}

    # ---------------- §13 counterfactual, split by the ANCHOR trap (D2)
    for cls in ("NON_ANCHOR","ANCHOR"):
        sub=[r for r in CF if r["anchor_class"]==cls]
        z  =[r for r in sub if r["zero_at_r0"]]
        out[f"CF_{cls}"] = {
          "n": len(sub),
          "obs_differs": frac(sum(1 for r in sub if r["obs_retain"]!=r["obs_delete"]), len(sub)),
          "exposed_structure_differs": frac(sum(1 for r in sub if r["exposed_structure_differs"]), len(sub)),
          "ZERO_at_r0_and_structure_differs": frac(
              sum(1 for r in z if r["exposed_structure_differs"]), len(z))}
    return out

res = {s: analyse(s) for s in ("train","test")}
res["controls"] = {r["split"]: r["counts"] for r in rows if r.get("record")=="CONTROLS"}
C=[r for r in rows if r.get("record")=="CONTRAST"]
res["contrast_arm"] = {"n": len(C),
    "obs_changed_when_Q_changed": frac(sum(1 for r in C if r["obs_r0"]!=r["obs_r1"]), len(C)),
    "note": "VARYING-Q arm. Never pooled with the primary arm (D1)."}
json.dump(res, open(f"{ROOT}/data/analysis.json","w"), indent=1, default=str)

def show(k, v, ind=0):
    pad="  "*ind
    if isinstance(v,dict) and set(v)>={"n","d"}:
        print(f"{pad}{k:44s} {v['n']:>7}/{v['d']:<7} = {v['rate']}")
    elif isinstance(v,dict):
        print(f"{pad}{k}"); [show(a,b,ind+1) for a,b in v.items()]
    else: print(f"{pad}{k:44s} {v}")
for s in ("train","test"):
    print(f"\n{'='*78}\n{s.upper()}\n{'='*78}")
    for k,v in res[s].items(): show(k,v)
print(f"\n{'='*78}\nCONTRAST / CONTROLS\n{'='*78}")
show("contrast_arm", res["contrast_arm"])
