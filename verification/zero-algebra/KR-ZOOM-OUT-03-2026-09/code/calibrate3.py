#!/usr/bin/env python3
r"""KR-ZOOM-OUT-03 — CALIBRATION GATE. A SEPARATE EXECUTABLE.

Writes data/calibration.json and EXITS. Contains NO analysis of the frozen estimands.
DEDICATED SEED, disjoint from the sensitivity seed AND from train/test.

Amended adequacy criterion (KR-ZOOM-OUT-02 §6.2a, ratified 2026-09-05):
    grid adequate  <=>  at least one admissible point, not a lone preselected boundary hit.
    in-band count is REPORTED, not thresholded.  Post-hoc grid expansion PROHIBITED.

AUTHORITY: generator parameters only, every attempt logged; may declare the gate unmeetable and
STOP the experiment; may not modify estimands, populations, thresholds, strata, contracts, controls.
"""
import os, sys, json, collections
HERE=os.path.dirname(os.path.abspath(__file__)); sys.path.insert(0, HERE)
sys.path.insert(0, os.path.join(os.path.dirname(os.path.dirname(HERE)),
                                "KR-ZOOM-OUT-01-2026-09","code"))
from gen3 import generate, det_broad
from zoomout import investigate, determine, zoom_out, observables, classify

ROOT=os.path.dirname(HERE)
CALIB_SEED = 66620260905      # dedicated: != 77720260905 (sensitivity), != train/test
NCAL, BUDGET, GATE = 1200, 5, (0.30, 0.80)

# FROZEN GRID (pre-registration §4) — built only from parameters shown sensitive. NOT expandable.
GRID=[dict(n_dims=a, subjects=b, value_alphabet=c)
      for a in (4,6,8) for b in ((1,2),(2,3),(3,4)) for c in (3,4,6)]

def probe(params):
    cs=generate(CALIB_SEED, NCAL, **params)
    base  = sum(1 for c in cs if det_broad(c.K0,1) is not None)/len(cs)
    baseS = sum(1 for c in cs if det_broad(c.K0,2) is not None)/len(cs)
    cls=collections.Counter(); detf=0; decoy=0
    for i,c in enumerate(cs):
        E=investigate(c,BUDGET,8880+i); D=determine(E)
        if D is not None:
            detf+=1
            if D!=c.root: decoy+=1
        K2=zoom_out(c,D); cls[classify(c.K0,K2,observables(c.K0)|observables(K2))]+=1
    return {"params":{k:str(v) for k,v in params.items()},
            "base_determine_Q_broad_S":round(base,5),
            "base_determine_Q_broad_Sprime":round(baseS,5),
            "determine_Q_focus":round(detf/len(cs),5),
            "decoy_share_of_determined":round(decoy/detf,5) if detf else None,
            "class_reachability":{k:cls[k] for k in ("M0","M1","M2","M3a","M3b","MX")},
            "O_F_all_classes_reachable":all(cls[k]>0 for k in ("M0","M1","M2","M3a","M3b","MX")),
            "in_band":GATE[0] <= base <= GATE[1]}

if __name__=="__main__":
    log=[probe(p) for p in GRID]
    adm=[r for r in log if r["in_band"] and r["O_F_all_classes_reachable"]]
    # deterministic selection, declared: the admissible point CLOSEST TO THE BAND CENTRE
    centre=(GATE[0]+GATE[1])/2
    chosen=min(adm, key=lambda r:(abs(r["base_determine_Q_broad_S"]-centre),
                                  str(r["params"]))) if adm else None
    vals=[r["base_determine_Q_broad_S"] for r in log]
    out={"experiment":"KR-ZOOM-OUT-03","calibration_seed":CALIB_SEED,"n":NCAL,
         "gate":{"quantity":"base Determine(Q_broad) under S","band":GATE},
         "criterion":"KR-ZOOM-OUT-02 §6.2a amended: gate reachability; in-band count reported, not thresholded",
         "grid_size":len(GRID),"grid_span":round(max(vals)-min(vals),5),
         "grid_range":[min(vals),max(vals)],
         "IN_BAND_COUNT_diagnostic":len(adm),
         "lone_boundary_hit":len(adm)==1,
         "selection_rule":"admissible point closest to the band centre; ties by param string",
         "GATE_MET":chosen is not None,"chosen":chosen,"attempt_log":log,
         "VERDICT":("GATE MET — the frozen analysis may proceed" if chosen else
                    "GATE NOT MET — THE EXPERIMENT STOPS. Grid expansion is PROHIBITED; a new "
                    "design round is required.")}
    os.makedirs(f"{ROOT}/data",exist_ok=True)
    json.dump(out,open(f"{ROOT}/data/calibration.json","w"),indent=1)
    print(f"grid: {len(GRID)} points · range {out['grid_range']} · span {out['grid_span']}")
    print(f"IN-BAND (reported diagnostic): {len(adm)} of {len(GRID)}   lone boundary hit: {out['lone_boundary_hit']}")
    print(f"\nGATE MET: {out['GATE_MET']}")
    if chosen:
        print(f"  chosen params          : {chosen['params']}")
        print(f"  base Determine(Q_b)|S  : {chosen['base_determine_Q_broad_S']}  (band {GATE}, centre {centre})")
        print(f"  base under S'          : {chosen['base_determine_Q_broad_Sprime']}")
        print(f"  Determine(Q_focus)     : {chosen['determine_Q_focus']}")
        print(f"  decoy share (O-C comparator): {chosen['decoy_share_of_determined']}")
        print(f"  O-F reachability       : {chosen['class_reachability']}")
    print(f"\nVERDICT: {out['VERDICT']}")
