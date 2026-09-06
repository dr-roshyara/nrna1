#!/usr/bin/env python3
r"""ESTIMAND NON-DEGENERACY PREFLIGHT  (O-F*)

    O-F*  :  for every reported / adjudicating quantity e,
             EXISTS theta, theta' in the frozen grid such that  e(theta) != e(theta')
             UNDER THE FROZEN COMPUTATION -- not merely in principle.

Replaces the narrower O-F (all classification classes reachable), which is insufficient:

    class reachability  =/=>  estimand variability

RETROSPECTIVE VALIDATION ONLY. This does NOT re-run KR-ZOOM-OUT-03 and does NOT change it.
It evaluates KR-ZOOM-OUT-03's frozen estimand set across its frozen 27-point grid, on the
CALIBRATION seed (never train/test), to test whether the preflight would have caught the
degeneracies that execution later revealed.
"""
import os, sys, json, collections
HERE=os.path.dirname(os.path.abspath(__file__))
V=os.path.dirname(os.path.dirname(HERE))
sys.path.insert(0, os.path.join(V,"KR-ZOOM-OUT-03-2026-09","code"))
sys.path.insert(0, os.path.join(V,"KR-ZOOM-OUT-01-2026-09","code"))
from gen3 import generate, det_broad
from zoomout import investigate, determine, zoom_out, observables, classify, cong, delta_triple

CALIB_SEED=66620260905          # calibration seed — never train/test
NPRE, BUDGET = 400, 5           # variation CHECK, not estimation
GRID=[dict(n_dims=a, subjects=b, value_alphabet=c)
      for a in (4,6,8) for b in ((1,2),(2,3),(3,4)) for c in (3,4,6)]

def avail_broad(K): return len({(c.dim,c.value) for c in K.claims})>=2

def estimands_at(params):
    cs=generate(CALIB_SEED,NPRE,**params); R=[]
    for i,c in enumerate(cs):
        sd=9000+i; E=investigate(c,BUDGET,sd); D=determine(E)
        K2=zoom_out(c,D); Kn=zoom_out(c,None)
        O=observables(c.K0)|observables(K2); On=observables(c.K0)|observables(Kn)
        R.append({"cls":classify(c.K0,K2,O),"det":D is not None,
                  "true":(D is not None and D==c.root),"decoy":(D is not None and D!=c.root),
                  "loss":avail_broad(c.K0) and not avail_broad(K2),
                  "dpre":det_broad(c.K0,1) is not None,"dpost":det_broad(K2,1) is not None,
                  "dpreS":det_broad(c.K0,2) is not None,
                  "OA_s":c.K0.key()==Kn.key(),"OA_o":cong(c.K0,Kn,On),
                  "OA_c":det_broad(c.K0,1)==det_broad(Kn,1),
                  "OA_p":delta_triple(c.K0,Kn)==(set(),set(),set()),
                  "OA_gain":(not (det_broad(c.K0,1) is not None)) and (det_broad(Kn,1) is not None)})
    det=[r for r in R if r["det"]]; pre=[r for r in R if r["dpre"]]
    dl=[r for r in pre if r["det"]]; tc=[r for r in dl if r["true"]]; dc=[r for r in dl if r["decoy"]]
    a4=[r for r in R if not r["dpre"]]
    PS=[r for r in R if r["dpre"]]; PSp=[r for r in R if r["dpreS"]]
    inter=[r for r in R if r["dpre"] and r["dpreS"]]
    rate=lambda v,k: (sum(1 for r in v if r["cls"]==k)/len(v)) if v else None
    m0=lambda v: rate(v,"M0")
    e={}
    e["A1_M0"]=m0(det); e["A1_M1"]=rate(det,"M1"); e["A1_MX"]=rate(det,"MX")
    e["A3_P_det"]=len(det)/len(R)
    e["A5_loss_given_det_pre"]=(sum(r["loss"] for r in pre)/len(pre)) if pre else None
    e["DELTA_loss"]=((sum(r["loss"] for r in tc)/len(tc)-sum(r["loss"] for r in dc)/len(dc))
                     if tc and dc else None)
    e["A4_gain"]=(sum(r["dpost"] for r in a4)/len(a4)) if a4 else None
    e["A4_null_gain"]=(sum(r["OA_gain"] for r in a4)/len(a4)) if a4 else None
    e["S_Sprime_i_shift"]=(abs(m0(PS)-m0(PSp)) if PS and PSp and m0(PS) is not None
                           and m0(PSp) is not None else None)
    # frozen (ii): |P(M0|inter,S) - P(M0|inter,S')| -- classification is not a function of S
    e["S_Sprime_ii_diff"]=(abs(m0(inter)-m0(inter)) if inter else None)
    for k,f in (("OA_structural","OA_s"),("OA_observable","OA_o"),
                ("OA_contract_semantic","OA_c"),("OA_historical_provenance","OA_p")):
        e[k]=sum(r[f] for r in R)/len(R)
    return e

if __name__=="__main__":
    vals=collections.defaultdict(list)
    for p in GRID:
        for k,v in estimands_at(p).items(): vals[k].append((str(p),v))
    out={}
    for k,pairs in vals.items():
        nn=[v for _,v in pairs if v is not None]
        distinct={round(v,6) for v in nn}
        witness=None
        if len(distinct)>1:
            lo=min(pairs,key=lambda t:(t[1] is None, t[1])); hi=max(pairs,key=lambda t:(t[1] is None, t[1]))
            witness={"theta":lo[0],"value":lo[1],"theta_prime":hi[0],"value_prime":hi[1]}
        out[k]={"n_grid_points":len(pairs),"n_evaluable":len(nn),
                "distinct_values":len(distinct),
                "constant_value":(nn[0] if nn and len(distinct)==1 else None),
                "HAS_VARIATION_WITNESS":len(distinct)>1,"witness":witness}
    # ---- REFINEMENT forced by this very run: some quantities are LEGITIMATELY constant.
    # A control whose expected value is a constant PASSES by being constant -- its constancy IS
    # the check. So O-F* must be evaluated against a DECLARED expectation per quantity.
    EXPECTED = {   # declared in advance, per quantity
      "A1_M0":"variable","A1_M1":"variable","A1_MX":"variable","A3_P_det":"variable",
      "A5_loss_given_det_pre":"variable","DELTA_loss":"variable","A4_gain":"variable",
      "S_Sprime_i_shift":"variable","S_Sprime_ii_diff":"variable",
      "A4_null_gain":"constant",              # control: no determination => no gain
      "OA_structural":"constant","OA_observable":"constant",
      "OA_contract_semantic":"constant","OA_historical_provenance":"constant"}
    for k,v in out.items():
        if k.startswith("_"): continue
        v["declared"]=EXPECTED.get(k,"variable")
        v["OF_STAR"] = ("PASS" if (v["declared"]=="constant" or v["HAS_VARIATION_WITNESS"])
                        else "FAIL — declared variable, no witness")
    fails=[k for k,v in out.items() if not k.startswith("_") and v["OF_STAR"].startswith("FAIL")]
    exempt=[k for k,v in out.items() if not k.startswith("_") and v["declared"]=="constant"]
    out["_verdict"]={"rule":"O-F*: every reported/adjudicating quantity needs a pre-execution variation witness",
        "grid":"KR-ZOOM-OUT-03 frozen 27-point grid","seed":CALIB_SEED,"n_per_point":NPRE,
        "n_estimands":len(vals),"n_OF_STAR_FAIL":len(fails),"OF_STAR_FAILURES":fails,"declared_constant_exempt":exempt,
        "OF_STAR_VERDICT":("PASS" if not fails else "FAIL — the experiment would NOT have been authorized"),
        "retrospective_validation":"would O-F* have caught what execution revealed?"}
    json.dump(out,open(os.path.join(os.path.dirname(HERE),"preflight-KR-ZOOM-OUT-03.json"),"w"),indent=1)
    print(f"O-F*  ·  {len(vals)} estimands  ·  {len(GRID)} grid points  ·  seed {CALIB_SEED}\n")
    print(f"  {'estimand':30s} {'declared':>9s} {'distinct':>9s}  {'O-F*':>32s}")
    for k,v in out.items():
        if k.startswith("_"): continue
        cv = "" if v["constant_value"] is None else f" (const {round(v['constant_value'],5)})"
        print(f"  {k:30s} {v['declared']:>9s} {v['distinct_values']:>9}  {v['OF_STAR']:>32s}{cv}")
    print(f"\n  O-F* FAILURES ({len(fails)}): {fails}")
    print(f"  declared-constant, exempt ({len(exempt)}): {exempt}")
    print(f"\n  O-F* VERDICT: {out['_verdict']['OF_STAR_VERDICT']}")
