#!/usr/bin/env python3
r"""SENSITIVITY DEMONSTRATION — required BEFORE freezing (the KR-ZOOM-OUT-02 rule).

    A calibration grid MUST contain at least one parameter the GATED QUANTITY is
    demonstrably sensitive to.  A grid whose span on the gated quantity is smaller
    than the gate's own width is not a grid.

Gated quantity: base Determine(Q_broad) under S = (tau_s=1, tau_m=1).  Gate width = 0.50.
One parameter at a time, all others at their KR-ZOOM-OUT-01 values.
Runs on a DEDICATED SENSITIVITY SEED, disjoint from calibration and from train/test.
"""
import os, sys, json
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from gen3 import generate, det_broad

ROOT=os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SENS_SEED=77720260905          # dedicated: not 55520260905, not 20260904/88020260904
N=1200; GATE=(0.30,0.80)

def base(**kw):
    cs=generate(SENS_SEED, N, **kw)
    return round(sum(1 for c in cs if det_broad(c.K0,1) is not None)/len(cs), 5)

SWEEPS={
 "n_dims":            [{"n_dims":v} for v in (3,4,6,8,10,14)],
 "subjects":          [{"subjects":v} for v in ((1,1),(1,2),(2,3),(3,4),(4,5))],
 "value_alphabet":    [{"value_alphabet":v} for v in (2,3,4,6,8)],
 "p_prior_wrong":     [{"p_prior_wrong":v} for v in (0.2,0.35,0.5)],
 "p_cover":           [{"p_cover":v} for v in (0.6,0.75,0.9)],
 "n_detail":          [{"n_detail":v} for v in ((1,2),(1,3),(2,4))],
}
if __name__=="__main__":
    out={}
    print(f"gated quantity: base Determine(Q_broad)|S · gate {GATE} · width {GATE[1]-GATE[0]:.2f}\n")
    for name,points in SWEEPS.items():
        vals=[(list(p.values())[0], base(**p)) for p in points]
        span=round(max(v for _,v in vals)-min(v for _,v in vals),5)
        inband=[str(k) for k,v in vals if GATE[0]<=v<=GATE[1]]
        out[name]={"points":[{"value":str(k),"base":v} for k,v in vals],"span":span,
                   "in_band_at":inband,
                   "SENSITIVE":span >= (GATE[1]-GATE[0])*0.5}
        flag=" <== SENSITIVE" if out[name]["SENSITIVE"] else ""
        print(f"  {name:16s} span={span:<9} " + "  ".join(f"{k}:{v}" for k,v in vals) + flag)
        if inband: print(f"      {'':14s} in band at: {', '.join(inband)}")
    sens=[k for k,v in out.items() if v["SENSITIVE"]]
    out["_verdict"]={"gate":GATE,"gate_width":GATE[1]-GATE[0],
        "criterion":"span >= half the gate width",
        "SENSITIVE_PARAMETERS":sens,
        "RULE_SATISFIED":len(sens)>0,
        "note":"a grid built ONLY from insensitive parameters is what stopped KR-ZOOM-OUT-02"}
    json.dump(out,open(f"{ROOT}/data/sensitivity.json","w"),indent=1)
    print(f"\nSENSITIVE PARAMETERS: {sens}")
    print(f"RULE SATISFIED: {out['_verdict']['RULE_SATISFIED']}")
