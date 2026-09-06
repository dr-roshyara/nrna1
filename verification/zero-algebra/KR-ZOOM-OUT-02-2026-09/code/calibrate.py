#!/usr/bin/env python3
r"""KR-ZOOM-OUT-02 — CALIBRATION GATE. A SEPARATE EXECUTABLE.

It writes data/calibration.json and EXITS. It contains NO analysis of the frozen estimands.
It runs on a DEDICATED SEED, disjoint from train/test, so nothing seen here is information
about the analysis data.

AUTHORITY: it may adjust GENERATOR PARAMETERS ONLY, logging every adjustment, and it may
declare the gate UNMEETABLE and STOP the experiment. It may not touch estimands, populations,
thresholds, strata, contracts or controls.
"""
import os, sys, json, collections
sys.path.insert(0, os.path.join(os.path.dirname(os.path.dirname(os.path.dirname(
    os.path.abspath(__file__)))), "KR-ZOOM-OUT-01-2026-09", "code"))
from zoomout import (generate, investigate, determine, zoom_out, observables, classify, TAU_S)

ROOT=os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CALIB_SEED = 55520260905          # DEDICATED — not 20260904, not 88020260904
NCAL, BUDGET = 1200, 5
GATE = (0.30, 0.80)

def E_broad(K): return tuple((("nexus","state"),(c.dim,c.value)) for c in K.claims)

def det_broad(K, tau_m=1):
    E=E_broad(K); A={b for _,b in E}
    if len(A)<2: return None
    sup={h:sum(1 for _,b in E if b==h) for h in A}
    term={h for h in A if not any(a==h for a,_ in E)}
    win=[h for h in A if h in term and sup[h]>=TAU_S
         and all(sup[h]-sup[h2]>=tau_m for h2 in A if h2!=h)]
    return win[0] if len(win)==1 else None

# ---- declared parameter grid. Calibration may move ONLY within it.
GRID=[dict(p_prior_wrong=pw, p_cover=pc, n_detail=nd)
      for pw in (0.5,0.35,0.2) for pc in (0.6,0.75,0.9) for nd in ((2,4),(1,3),(1,2))]

def probe(params):
    cs=generate(CALIB_SEED, NCAL, **params)
    base=sum(1 for c in cs if det_broad(c.K0,1) is not None)/len(cs)
    baseS=sum(1 for c in cs if det_broad(c.K0,2) is not None)/len(cs)
    cls=collections.Counter(); decoy=0; detf=0
    for i,c in enumerate(cs):
        E=investigate(c,BUDGET,7770+i); D=determine(E)
        if D is not None:
            detf+=1
            if D!=c.root: decoy+=1
        K2=zoom_out(c,D); cls[classify(c.K0,K2,observables(c.K0)|observables(K2))]+=1
    return {"params":params,"base_determine_Q_broad_S":round(base,5),
            "base_determine_Q_broad_Sprime":round(baseS,5),
            "determine_Q_focus":round(detf/len(cs),5),
            "decoy_share_of_determined":round(decoy/detf,5) if detf else None,
            "class_reachability":{k:cls[k] for k in ("M0","M1","M2","M3a","M3b","MX")},
            "O_F_all_classes_reachable":all(cls[k]>0 for k in ("M0","M1","M2","M3a","M3b","MX"))}

if __name__=="__main__":
    log=[]; chosen=None
    for p in GRID:
        r=probe(p); log.append(r)
        if GATE[0] <= r["base_determine_Q_broad_S"] <= GATE[1] and r["O_F_all_classes_reachable"]:
            chosen=r; break
    out={"experiment":"KR-ZOOM-OUT-02","calibration_seed":CALIB_SEED,"n":NCAL,
         "gate":{"quantity":"base Determine(Q_broad) under S","band":GATE},
         "grid_size":len(GRID),"attempts":len(log),"attempt_log":log,
         "GATE_MET":chosen is not None,"chosen":chosen,
         "authority":"generator parameters only; may STOP the experiment; may not modify the protocol",
         "VERDICT":("GATE MET — execution may proceed to the frozen analysis"
                    if chosen else
                    "GATE NOT MET WITHIN THE DECLARED GRID — THE EXPERIMENT STOPS. "
                    "A new, separately frozen design round is required.")}
    os.makedirs(f"{ROOT}/data",exist_ok=True)
    json.dump(out,open(f"{ROOT}/data/calibration.json","w"),indent=1)
    print(f"attempts: {len(log)} of {len(GRID)}")
    for r in log[:8]:
        print(f"  {str(r['params']):58s} base_S={r['base_determine_Q_broad_S']:<8} "
              f"O_F={r['O_F_all_classes_reachable']}")
    print(f"\nGATE MET: {out['GATE_MET']}")
    if chosen:
        print(f"  chosen params        : {chosen['params']}")
        print(f"  base Determine(Q_b)|S: {chosen['base_determine_Q_broad_S']}   (band {GATE})")
        print(f"  base under S'        : {chosen['base_determine_Q_broad_Sprime']}")
        print(f"  Determine(Q_focus)   : {chosen['determine_Q_focus']}")
        print(f"  decoy share (for O-C): {chosen['decoy_share_of_determined']}")
        print(f"  O-F reachability     : {chosen['class_reachability']}")
    print(f"\nVERDICT: {out['VERDICT']}")
