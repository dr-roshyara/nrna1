"""PAIRED re-analysis.  O2-A and O2-B drew (t,p) from different contract POOLS, which
desynchronizes the RNG stream -- so they differ in their TRANSFORMATION mix too, not only
in contracts.  Given that irreducibility is overwhelmingly transformation-driven, the
unpaired comparison cannot separate a contract effect from a transformation-mix effect.

This re-analysis holds (D, T) FIXED and varies only the contract.  Fully paired.
"""
import itertools, random, collections, json, os
from zero_algebra import *
from generators import corpus
from interaction_order import minimal_k
from experiments import SEED, N, MAXN, TSET, PSET, NONCANCEL

def run():
    rng = random.Random(SEED + 99)
    ctxs = []
    for c in corpus(N, SEED + 99):
        D = c["rep"]
        if not (2 <= len(D) <= MAXN): continue
        ctxs.append({"id": c["id"], "D": D, "T": rng.choice(TSET), "cls": c["cls"],
                     "n": len(D)})
    per_contract = {p: {"tests":0, "irre":0} for p in PSET}
    per_transform = {t: {"tests":0, "irre":0} for t in TSET}
    paired = {"P9":{"tests":0,"irre":0}, "nonP9":{"tests":0,"irre":0}}
    for c in ctxs:
        D, t = c["D"], c["T"]
        for p in PSET:
            if contract_is_vacuous(D, t, p): continue
            for m in range(2, c["n"]+1):
                if len(list(itertools.combinations(range(c["n"]), m))) < 2: continue
                k, w = minimal_k(D, t, p, m)
                irr = (k is None and w is not None)
                per_contract[p]["tests"] += 1; per_contract[p]["irre"] += irr
                per_transform[t]["tests"] += 1; per_transform[t]["irre"] += irr
                key = "P9" if p == "P9_balance" else "nonP9"
                paired[key]["tests"] += 1; paired[key]["irre"] += irr
    def rate(d): return round(d["irre"]/d["tests"], 4) if d["tests"] else None
    return {"design": "PAIRED -- identical (D,T) contexts evaluated under every contract",
            "n_contexts": len(ctxs), "seed": SEED+99,
            "by_contract": {p: {**v, "rate": rate(v)} for p, v in sorted(per_contract.items())},
            "by_transformation": {t: {**v, "rate": rate(v)} for t, v in sorted(per_transform.items())},
            "paired_cancelling_vs_not": {k: {**v, "rate": rate(v)} for k, v in paired.items()},
            "interpretation": ("if the P9 and non-P9 rates are close under the PAIRED design, "
                               "the contract is not the driver -- the transformation is")}

if __name__ == "__main__":
    r = run()
    ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    json.dump(r, open(os.path.join(ROOT, "paired-reanalysis.json"), "w"), indent=1)
    print(f"PAIRED design, {r['n_contexts']} contexts\n")
    print("by TRANSFORMATION:")
    for t, v in sorted(r["by_transformation"].items(), key=lambda kv:-(kv[1]["rate"] or 0)):
        print(f"   {t:22s} {v['irre']:5d}/{v['tests']:5d}  = {v['rate']}")
    print("\nby CONTRACT:")
    for p, v in sorted(r["by_contract"].items(), key=lambda kv:-(kv[1]["rate"] or 0)):
        print(f"   {p:22s} {v['irre']:5d}/{v['tests']:5d}  = {v['rate']}")
    print("\nPAIRED cancelling vs non-cancelling:")
    for k, v in r["paired_cancelling_vs_not"].items():
        print(f"   {k:8s} {v['irre']:5d}/{v['tests']:5d} = {v['rate']}")
