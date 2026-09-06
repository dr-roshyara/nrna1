#!/usr/bin/env python3
r"""KR-ZOOM-OUT-03 — EXECUTION + ANALYSIS of the frozen protocol.

AUTHORIZED 2026-09-05 after the calibration gate was MET. NOTHING is modified in response to
calibration: S' stays (tau_s=1, tau_m=2); the sparse M2/M3a classes are reported, not redesigned;
the grid is not re-selected; n is not increased to manufacture power for component (ii).

FROZEN, from the pre-registration:
  population   P = P_det u P_notdet   -- STRATIFY, never filter
  A1/A2 descriptive class distributions · A3 population composition
  A4  directed exploratory/secondary   · A5 primary DESCRIPTIVE phenotype
  Delta_loss  primary ADJUDICATED effect; floor .10 material / .05 borderline, BOTH splits
  winner       a class > 0.60 within P_det on BOTH splits; MX first-class
  strata       evidence-graph size, root depth -- IMMUTABLE, never dropped
  controls     O-A (4 equalities) O-B O-C O-D O-E O-F O-G
  params       n_dims=4 subjects=(1,2) value_alphabet=6   (calibration-selected, frozen)
"""
import os, sys, json, collections
HERE=os.path.dirname(os.path.abspath(__file__)); sys.path.insert(0,HERE)
sys.path.insert(0, os.path.join(os.path.dirname(os.path.dirname(HERE)),
                                "KR-ZOOM-OUT-01-2026-09","code"))
from gen3 import generate, det_broad
from zoomout import (investigate, determine, zoom_out, observables, classify, cong,
                     delta_triple, value_changed)

ROOT=os.path.dirname(HERE)
SEEDS={"train":20260904,"test":88020260904}
N, BUDGET = 1500, 5
PARAMS=dict(n_dims=4, subjects=(1,2), value_alphabet=6)
FLOOR, BORDER, WINNER = 0.10, 0.05, 0.60

def E_broad(K): return tuple((("nexus","state"),(c.dim,c.value)) for c in K.claims)
def avail_broad(K):  return len({b for _,b in E_broad(K)}) >= 2      # contract APPLICABLE
def root_depth(c):
    dist={("network","network_egress"):0}; fr=[("network","network_egress")]
    while fr:
        nd=fr.pop(0)
        for (a,b) in c.links:
            if a==nd and b not in dist: dist[b]=dist[nd]+1; fr.append(b)
    return dist.get(c.root, -1)
def frac(n,d): return {"n":n,"d":d,"rate":round(n/d,5) if d else None}

def run(split):
    rows=[]
    for i,c in enumerate(generate(SEEDS[split], N, **PARAMS)):
        sd=9000+i
        E=investigate(c,BUDGET,sd); D=determine(E)                  # Q_focus
        K2=zoom_out(c,D); Kn=zoom_out(c,None)                       # O-A null round-trip
        O=observables(c.K0)|observables(K2); On=observables(c.K0)|observables(Kn)
        av0, av1 = avail_broad(c.K0), avail_broad(K2)
        dpre_S  = det_broad(c.K0,1) is not None
        dpost_S = det_broad(K2,1)   is not None
        dpre_Sp = det_broad(c.K0,2) is not None
        rows.append({
          "case":i, "cls":classify(c.K0,K2,O),
          "det_focus": D is not None, "true_cause": (D is not None and D==c.root),
          "decoy":     (D is not None and D!=c.root),
          "avail_pre":av0, "avail_post":av1, "LOSS": av0 and not av1,
          "det_pre_S":dpre_S, "det_post_S":dpost_S, "det_pre_Sprime":dpre_Sp,
          "gain": (not dpre_S) and dpost_S,
          "graph_size":len(c.links), "root_depth":root_depth(c),
          "OA_structural": c.K0.key()==Kn.key(),
          "OA_observable": cong(c.K0,Kn,On),
          "OA_contract":   det_broad(c.K0,1)==det_broad(Kn,1),
          "OA_provenance": delta_triple(c.K0,Kn)==(set(),set(),set()),
          "OA_gain": (not dpre_S) and (det_broad(Kn,1) is not None),
          "value_changed": value_changed(c.K0,K2,O)})
    return rows

def mh(rows, num_pred, den_pred, strata_keys):
    """Stratified (MH-weighted) difference of two conditional rates. Strata IMMUTABLE."""
    by=collections.defaultdict(list)
    for r in rows: by[tuple(r[k] for k in strata_keys)].append(r)
    wsum=dsum=0.0; used=[]
    for key,v in sorted(by.items()):
        a=[r for r in v if num_pred(r)]; b=[r for r in v if den_pred(r)]
        if not a or not b: continue
        pa=sum(r["LOSS"] for r in a)/len(a); pb=sum(r["LOSS"] for r in b)/len(b)
        w=len(a)*len(b)/(len(a)+len(b)); wsum+=w; dsum+=w*(pa-pb)
        used.append({"stratum":str(key),"n_true":len(a),"n_decoy":len(b),
                     "p_true":round(pa,5),"p_decoy":round(pb,5),"delta":round(pa-pb,5),
                     "SPARSE": len(a)<30 or len(b)<30})
    return {"MH_delta":round(dsum/wsum,5) if wsum else None,
            "n_informative_strata":len(used),"strata":used,
            "n_sparse_strata":sum(1 for u in used if u["SPARSE"])}

def analyse(rows):
    det=[r for r in rows if r["det_focus"]]; nod=[r for r in rows if not r["det_focus"]]
    Pdet_S=[r for r in rows if r["det_pre_S"]]
    A5pop=[r for r in Pdet_S]
    dl_pop=[r for r in Pdet_S if r["det_focus"]]
    tc=[r for r in dl_pop if r["true_cause"]]; dc=[r for r in dl_pop if r["decoy"]]
    dist_det=collections.Counter(r["cls"] for r in det)
    dist_nod=collections.Counter(r["cls"] for r in nod)
    a4pop=[r for r in rows if not r["det_pre_S"]]
    # ---- S / S' : (i) total shift, (ii) intersection (DECLARED underpowered)
    PS=[r for r in rows if r["det_pre_S"]]; PSp=[r for r in rows if r["det_pre_Sprime"]]
    inter=[r for r in rows if r["det_pre_S"] and r["det_pre_Sprime"]]
    m0=lambda v:(sum(1 for r in v if r["cls"]=="M0")/len(v)) if v else None
    out={
     "A1_class_distribution_P_det":{k:frac(dist_det[k],len(det)) for k in ("M0","M1","M2","M3a","M3b","MX")},
     "A2_class_distribution_P_notdet":{k:frac(dist_nod[k],len(nod)) for k in ("M0","M1","M2","M3a","M3b","MX")},
     "A3_population_composition":{"P_det":frac(len(det),len(rows)),"P_notdet":frac(len(nod),len(rows))},
     "A5_primary_descriptive":frac(sum(r["LOSS"] for r in A5pop), len(A5pop)),
     "DELTA_LOSS_primary_adjudicated":{
        "crude": (round(sum(r["LOSS"] for r in tc)/len(tc) - sum(r["LOSS"] for r in dc)/len(dc),5)
                  if tc and dc else None),
        "n_true_cause":len(tc),"n_decoy":len(dc),
        "stratified": mh(dl_pop, lambda r:r["true_cause"], lambda r:r["decoy"],
                         ("graph_size","root_depth"))},
     "A4_secondary":{"gain":frac(sum(r["gain"] for r in a4pop),len(a4pop)),
                     "null_roundtrip_gain":frac(sum(r["OA_gain"] for r in a4pop),len(a4pop))},
     "S_Sprime":{"i_total_shift":{"M0_in_P_det_S":round(m0(PS),5) if PS else None,
                                  "M0_in_P_det_Sprime":round(m0(PSp),5) if PSp else None,
                                  "shift":round(abs(m0(PS)-m0(PSp)),5) if PS and PSp else None,
                                  "n_S":len(PS),"n_Sprime":len(PSp)},
                 "ii_intersection_UNDERPOWERED":{"n_intersection":len(inter),
                                  "M0":round(m0(inter),5) if inter else None,
                                  "declared_limitation":"pre-registration §7.1 — S' base 0.0683"}},
     "controls":{k:frac(sum(r[k] for r in rows),len(rows)) for k in
                 ("OA_structural","OA_observable","OA_contract","OA_provenance")},
     "O_B_determined_nothing":frac(len(nod),len(rows)),
     "O_C_decoy_share":frac(sum(1 for r in det if r["decoy"]),len(det)),
     "value_changed":frac(sum(r["value_changed"] for r in rows),len(rows)),
    }
    # ---- O-G stratum-artifact check
    og=[]
    for k in ("M0","M1","M2","M3a","M3b","MX"):
        rate=dist_det[k]/len(det) if det else 0
        for sn,sv in (("P_det",len(det)/len(rows)),("P_notdet",len(nod)/len(rows))):
            if abs(rate-sv)<=0.005:
                og.append({"class":k,"class_rate":round(rate,5),"stratum":sn,
                           "stratum_size":round(sv,5),
                           "note":"reported as a stratum artifact under O-G (protocol classification, not a causal explanation)"})
    out["O_G_stratum_artifacts"]=og
    return out

if __name__=="__main__":
    R={s:analyse(run(s)) for s in ("train","test")}
    d=[R[s]["DELTA_LOSS_primary_adjudicated"]["stratified"]["MH_delta"] for s in ("train","test")]
    ok=all(x is not None for x in d)
    verdict=("MATERIAL — Delta_loss supported in the tested regime" if ok and all(abs(x)>=FLOOR for x in d)
             else "BORDERLINE — reported, not claimed" if ok and all(abs(x)>=BORDER for x in d)
             else "NEGLIGIBLE — below the frozen floor" if ok else "NOT ESTIMABLE")
    win=[k for k in ("M0","M1","M2","M3a","M3b","MX")
         if all(R[s]["A1_class_distribution_P_det"][k]["rate"]>WINNER for s in ("train","test"))]
    R["_verdict"]={"primary":"Delta_loss (stratified)","MH_delta":d,"floor":FLOOR,
                   "VERDICT":verdict,"single_winner":win[0] if win else None,
                   "note":"GO does not mean the hypothesis is supported; it meant the experiment passed its pre-registered conditions for execution."}
    json.dump(R,open(f"{ROOT}/data/results03.json","w"),indent=1)
    for s in ("train","test"):
        v=R[s]; print(f"\n{'='*78}\n{s.upper()}   n={N}  params={PARAMS}\n{'='*78}")
        print(f"  A3 composition   P_det={v['A3_population_composition']['P_det']['rate']}  P_notdet={v['A3_population_composition']['P_notdet']['rate']}")
        print("  A1 class distribution within P_det:")
        for k,r in v["A1_class_distribution_P_det"].items(): print(f"      {k:4s} {r['n']:>5}/{r['d']:<5} = {r['rate']}")
        print(f"  A5 primary descriptive (loss | Det_pre=1): {v['A5_primary_descriptive']['n']}/{v['A5_primary_descriptive']['d']} = {v['A5_primary_descriptive']['rate']}")
        dl=v["DELTA_LOSS_primary_adjudicated"]
        print(f"  DELTA_LOSS crude={dl['crude']}  (n_true={dl['n_true_cause']}, n_decoy={dl['n_decoy']})")
        print(f"  DELTA_LOSS STRATIFIED (MH) = {dl['stratified']['MH_delta']}  strata={dl['stratified']['n_informative_strata']} (sparse {dl['stratified']['n_sparse_strata']})")
        sp=v["S_Sprime"]
        print(f"  S/S' (i) shift={sp['i_total_shift']['shift']}  n_S={sp['i_total_shift']['n_S']} n_S'={sp['i_total_shift']['n_Sprime']}")
        print(f"  S/S' (ii) intersection n={sp['ii_intersection_UNDERPOWERED']['n_intersection']} M0={sp['ii_intersection_UNDERPOWERED']['M0']}  [UNDERPOWERED, declared]")
        print(f"  controls O-A: " + " ".join(f"{k.split('_')[1]}={r['rate']}" for k,r in v["controls"].items()))
        print(f"  O-G stratum artifacts: {len(v['O_G_stratum_artifacts'])}")
        for g in v["O_G_stratum_artifacts"]: print(f"      {g['class']} rate={g['class_rate']} ~ {g['stratum']}={g['stratum_size']}")
    print(f"\n{'='*78}\nPRIMARY VERDICT: {verdict}\n  MH Delta_loss train={d[0]} test={d[1]}  floor={FLOOR}")
    print(f"  single winner (>0.60 both splits, within P_det): {R['_verdict']['single_winner']}")
