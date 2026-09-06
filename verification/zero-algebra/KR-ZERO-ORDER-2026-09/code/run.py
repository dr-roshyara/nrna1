#!/usr/bin/env python3
import json, os, sys, datetime, platform
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import experiments as E
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
WIT = os.path.join(ROOT,"witnesses"); os.makedirs(WIT, exist_ok=True)
META = dict(experiment_id="KR-ZERO-ORDER-2026-09", theory="v1.2 UNCHANGED", theory_v13=False,
            kernel="NOT SELECTED", algebra="none introduced", ratified="nothing",
            base_seed=E.SEED, cases=E.N, powerset_bound=E.MAXN,
            date=datetime.date.today().isoformat(), python=platform.python_version())
S=[("O1_minimal_k",E.O1_minimal_k),("O2_robustness",E.O2_robustness),("O3_drivers",E.O3_drivers),
   ("O4_cross_context",E.O4_cross_context),("O5_k_monotone",E.O5_k_monotone_in_m),
   ("O6_ladder",E.O6_ladder)]
if __name__=="__main__":
    res={}
    for n,fn in S:
        o=fn(); w=o.pop("witnesses",None)
        if w: json.dump(w,open(f"{WIT}/{n}_witnesses.json","w"),indent=1,default=str)
        res[n]=o; print("ran",n,flush=True)
    json.dump(dict(_meta=META,**res),open(os.path.join(ROOT,"property-results.json"),"w"),
              indent=1,default=str)
    json.dump({"base_seed":E.SEED,"cases":E.N,"powerset_bound":E.MAXN,"deterministic":True},
              open(os.path.join(ROOT,"seeds.json"),"w"),indent=1)
    print("DONE.")
