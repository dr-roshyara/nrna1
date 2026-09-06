#!/usr/bin/env python3
r"""KR-ZOOM-OUT-01 executor — run STRICTLY against the frozen pre-registration.

FROZEN (ratified 2026-09-05, not tunable after outcomes):
  E1  P(K_t <=_O K_{t+1})                              answerability retained     -> separates M0
  E2  P(K_t ~=_O K_{t+1})                              observably unchanged       -> M1 vs rest
  E2b P(exists o: Value changes | <=_O holds)          answer changed, still askable -> M3b observable signature
  E3  P(detail falls | <=_O holds)                     CONDITIONAL by construction -> M2 vs M3
  E4  P(Delta_circ != {} | M3)                         existing material qualified -> M3a vs M3b
  E5  P(gain) - P(gain | null round-trip)              PRIMARY. determination GAINED at Q_broad
  winner only if a class > 0.60 on BOTH splits; MX first-class
  floor 0.10 material / 0.05 borderline, both splits
  Determine = KR-ZOOM-03 Option 3, verbatim, for BOTH Q_focus and Q_broad
"""
import os, sys, json, collections
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from zoomout import (generate, investigate, determine, zoom_out, observables, avail, value,
                     preceq, cong, value_changed, delta_triple, classify, State, Claim, ANOMALY)

ROOT=os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SEEDS={"train":20260904,"test":88020260904}; N=1500; BUDGET=5
FLOOR, BORDER, WINNER = 0.10, 0.05, 0.60

def E_broad(K):
    """Q_broad = 'what is the operational state of Nexus?'. Same Option 3 contract; only the
    inquiry TARGET differs (pre-reg decision 4)."""
    return tuple((("nexus","state"),(c.dim,c.value)) for c in K.claims)

def det_broad(K): return determine(E_broad(K))
def frac(n,d): return {"n":n,"d":d,"rate":round(n/d,5) if d else None}

def run(split):
    cases=generate(SEEDS[split],N); rows=[]
    for i,c in enumerate(cases):
        sd=9000+i
        E=investigate(c,BUDGET,sd); D=determine(E)
        K2=zoom_out(c,D); O=observables(c.K0)|observables(K2)
        b0, b1 = det_broad(c.K0), det_broad(K2)
        # ---- O-A null round-trip: zoom in, investigate NOTHING, zoom out
        Kn=zoom_out(c,None); On=observables(c.K0)|observables(Kn)
        minus,circ,plus = delta_triple(c.K0,K2)
        rows.append({
          "case":i,"determined_focus":D is not None,
          "cls":classify(c.K0,K2,O),
          "preceq":preceq(c.K0,K2,O), "cong":cong(c.K0,K2,O),
          "value_changed":value_changed(c.K0,K2,O),
          "detail_falls":len(K2.claims)<len(c.K0.claims),
          "delta_circ":len(circ)>0,"delta_minus":len(minus),"delta_plus":len(plus),
          "broad_before":b0 is not None,"broad_after":b1 is not None,
          "broad_gain":(b0 is None and b1 is not None),
          "broad_loss":(b0 is not None and b1 is None),
          # O-A, four equality dimensions measured SEPARATELY
          "OA_structural":  c.K0.key()==Kn.key(),
          "OA_observable":  cong(c.K0,Kn,On),
          "OA_contract":    det_broad(c.K0)==det_broad(Kn),
          "OA_provenance":  delta_triple(c.K0,Kn)==(set(),set(),set()),
          "OA_gain":        (det_broad(c.K0) is None and det_broad(Kn) is not None),
          # O-B determined nothing ; O-C determined a decoy
          "OB":D is None, "OC":(D is not None and D!=c.root)})
        # ---- O-D  n-cycle round trip with NO new evidence: fidelity must stay 1.000
        Kc=c.K0
        for _ in range(3): Kc=zoom_out(Case_like(c,Kc),None)
        rows[-1]["OD_fidelity"]=Kc.key()==c.K0.key()
    return rows

class Case_like:
    def __init__(self,c,K): self.K0=K; self.links=c.links; self.root=c.root; self.covered=c.covered; self.detail_subjects=c.detail_subjects

def analyse(rows):
    n=len(rows); P=[r for r in rows if r["preceq"]]
    M3=[r for r in rows if r["cls"] in ("M3a","M3b")]
    dist=collections.Counter(r["cls"] for r in rows)
    gain=sum(r["broad_gain"] for r in rows); gain_null=sum(r["OA_gain"] for r in rows)
    return {
      "n":n,
      "calibration_base_determine_Q_broad":frac(sum(r["broad_before"] for r in rows),n),
      "determine_Q_focus":frac(sum(r["determined_focus"] for r in rows),n),
      "E1_answerability_retained":frac(sum(r["preceq"] for r in rows),n),
      "E2_observably_unchanged":frac(sum(r["cong"] for r in rows),n),
      "E2b_value_changed_given_preceq":frac(sum(r["value_changed"] for r in P),len(P)),
      "E3_detail_falls_given_preceq":frac(sum(r["detail_falls"] for r in P),len(P)),
      "E4_delta_circ_given_M3":frac(sum(r["delta_circ"] for r in M3),len(M3)),
      "E5_gain":frac(gain,n), "E5_gain_null_roundtrip":frac(gain_null,n),
      "E5_DELTA":round(gain/n - gain_null/n,5),
      "broad_loss":frac(sum(r["broad_loss"] for r in rows),n),
      "classification":{k:frac(v,n) for k,v in sorted(dist.items())},
      "controls":{
        "O_A_structural":frac(sum(r["OA_structural"] for r in rows),n),
        "O_A_observable":frac(sum(r["OA_observable"] for r in rows),n),
        "O_A_contract_semantic":frac(sum(r["OA_contract"] for r in rows),n),
        "O_A_historical_provenance":frac(sum(r["OA_provenance"] for r in rows),n),
        "O_B_determined_nothing":frac(sum(r["OB"] for r in rows),n),
        "O_C_determined_a_decoy":frac(sum(r["OC"] for r in rows),n),
        "O_D_ncycle_fidelity":frac(sum(r["OD_fidelity"] for r in rows),n)}}

if __name__=="__main__":
    R={s:analyse(run(s)) for s in ("train","test")}
    d=[R[s]["E5_DELTA"] for s in ("train","test")]
    verdict=("MATERIAL — E5 supported in tested regime" if all(abs(x)>=FLOOR for x in d)
             else "BORDERLINE — reported, not claimed" if all(abs(x)>=BORDER for x in d)
             else "NEGLIGIBLE — below the frozen floor")
    win=[k for k in ("M0","M1","M2","M3a","M3b","MX")
         if all(R[s]["classification"].get(k,{"rate":0})["rate"]>WINNER for s in ("train","test"))]
    R["_verdict"]={"E5_delta":d,"floor":FLOOR,"VERDICT":verdict,
        "single_winner":win[0] if win else None,
        "winner_rule":"a class must exceed 0.60 on BOTH splits; MX counts like any other"}
    json.dump(R,open(f"{ROOT}/data/results_out01.json","w"),indent=1)
    for s in ("train","test"):
        v=R[s]; print(f"\n{'='*76}\n{s.upper()}  (n={v['n']}, budget={BUDGET})\n{'='*76}")
        for k in ("calibration_base_determine_Q_broad","determine_Q_focus","E1_answerability_retained",
                  "E2_observably_unchanged","E2b_value_changed_given_preceq","E3_detail_falls_given_preceq",
                  "E4_delta_circ_given_M3","E5_gain","E5_gain_null_roundtrip","broad_loss"):
            r=v[k]; print(f"  {k:38s} {r['n']:>6}/{r['d']:<6} = {r['rate']}")
        print(f"  {'E5_DELTA (PRIMARY)':38s} {v['E5_DELTA']}")
        print("  classification:")
        for k,r in v["classification"].items(): print(f"      {k:5s} {r['n']:>6}/{r['d']:<6} = {r['rate']}")
        print("  controls:")
        for k,r in v["controls"].items(): print(f"      {k:28s} {r['n']:>6}/{r['d']:<6} = {r['rate']}")
    print(f"\n{'='*76}\nPRIMARY VERDICT: {verdict}   (E5 delta train={d[0]} test={d[1]}, floor={FLOOR})")
    print(f"SINGLE WINNER (>0.60 both splits): {R['_verdict']['single_winner']}")
