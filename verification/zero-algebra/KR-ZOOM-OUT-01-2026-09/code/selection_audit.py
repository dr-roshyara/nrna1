#!/usr/bin/env python3
r"""SELECTION AUDIT for KR-ZOOM-OUT-02's population definition.

THIS IS NOT A RE-ANALYSIS OF KR-ZOOM-OUT-01. No verdict is revised. The output is a
RECOMMENDATION about which population KR-ZOOM-OUT-02 should pre-register.

The causal chain:

    K_t --investigate--> E --Determine--> D --zoom_out(K_t, D)--> K_{t+1} --> {class, E5}

  D is an ANCESTOR of the outcome (zoom_out CONSUMES it), not a descendant.
  So conditioning on D is NOT collider-conditioning on the outcome.

  BUT: graph structure causes BOTH D and, via the root dimension, the detail claims that
  drive M0.  Conditioning on D therefore SELECTS ON GRAPH STRUCTURE, which independently
  drives M0.  That is a backdoor, and it is testable.

Three candidate populations:
  P_all    every case                                  (KR-ZOOM-OUT-01's frozen choice)
  P_det    cases where a determination was REACHED      <- conditions on the investigation OUTCOME
  P_reach  cases where the root is reachable within budget by ground truth
                                                        <- a PRE-INVESTIGATION property of K_t alone
"""
import sys, os, collections, json, statistics
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from zoomout import *
from run_out01 import det_broad, SEEDS, N, BUDGET

def reachable(c, budget):
    """GROUND-TRUTH reachability: is the root within `budget` probes of the anchor?
    A property of K_t and the graph ALONE -- it does not look at the investigation."""
    dist={("network","network_egress"):0}; frontier=[("network","network_egress")]
    while frontier:
        nd=frontier.pop(0)
        for (a,b) in c.links:
            if a==nd and b not in dist:
                dist[b]=dist[nd]+1; frontier.append(b)
    return c.root in dist and dist[c.root] <= budget

def build(split):
    rows=[]
    for i,c in enumerate(generate(SEEDS[split],N)):
        sd=9000+i; E=investigate(c,BUDGET,sd); D=determine(E)
        K2=zoom_out(c,D); O=observables(c.K0)|observables(K2)
        rows.append({"chain":len([1 for _ in c.links]), "depth":len(c.detail_subjects),
                     "det":D is not None, "reach":reachable(c,BUDGET),
                     "cls":classify(c.K0,K2,O),
                     "gain":(det_broad(c.K0) is None and det_broad(K2) is not None),
                     "nlinks":len(c.links)})
    return rows

def share(rows,k): return (sum(1 for r in rows if r["cls"]==k)/len(rows)) if rows else None

if __name__=="__main__":
    OUT={}
    for split in ("train","test"):
        R=build(split)
        # ---- 1. is D associated with structure?  (the backdoor's first leg)
        det=[r["nlinks"] for r in R if r["det"]]; nod=[r["nlinks"] for r in R if not r["det"]]
        # ---- 2. the three populations
        P={"P_all":R, "P_det":[r for r in R if r["det"]], "P_reach":[r for r in R if r["reach"]]}
        # ---- 3. M0 within STRATA of structure, inside each population
        strat={}
        for name,pop in P.items():
            by=collections.defaultdict(list)
            for r in pop: by[r["nlinks"]].append(r)
            rows=[(L,len(v),share(v,"M0"),share(v,"M1")) for L,v in sorted(by.items()) if len(v)>=60]
            wsum=sum(n for _,n,m,_ in rows if m is not None)
            mh=(sum(n*m for _,n,m,_ in rows if m is not None)/wsum) if wsum else None
            strat[name]={"crude_M0":share(pop,"M0"),"crude_M1":share(pop,"M1"),
                         "stratified_M0":round(mh,5) if mh else None,
                         "n":len(pop),"strata":[{"nlinks":L,"n":n,"M0":round(m,4) if m is not None else None,
                                                 "M1":round(m1,4) if m1 is not None else None}
                                                for L,n,m,m1 in rows]}
        OUT[split]={"link_count_mean_determined":round(statistics.mean(det),3),
                    "link_count_mean_not_determined":round(statistics.mean(nod),3),
                    "populations":strat,
                    "P_det_vs_P_reach_overlap":round(
                        sum(1 for r in R if r["det"] and r["reach"])/max(1,sum(1 for r in R if r["reach"])),4)}
    json.dump(OUT,open(f"{os.path.dirname(os.path.dirname(os.path.abspath(__file__)))}/data/selection_audit.json","w"),indent=1)
    for s in ("train","test"):
        o=OUT[s]; print(f"\n{'='*74}\n{s.upper()}\n{'='*74}")
        print(f"  BACKDOOR LEG 1 — is D associated with graph structure?")
        print(f"    mean |links|  determined={o['link_count_mean_determined']}   not-determined={o['link_count_mean_not_determined']}")
        print(f"  P(determined | reachable) = {o['P_det_vs_P_reach_overlap']}")
        print(f"\n  {'population':10s} {'n':>6s} {'crude M0':>10s} {'strat M0':>10s} {'crude M1':>10s}")
        for k,v in o["populations"].items():
            print(f"  {k:10s} {v['n']:>6} {v['crude_M0']:>10.4f} {str(v['stratified_M0']):>10s} {v['crude_M1']:>10.4f}")
        print("  M0 by structural stratum (P_det):")
        for st in o["populations"]["P_det"]["strata"][:8]:
            print(f"      nlinks={st['nlinks']:<3} n={st['n']:<5} M0={st['M0']}  M1={st['M1']}")
