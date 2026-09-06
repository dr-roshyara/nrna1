#!/usr/bin/env python3
r"""KR-ZOOM-03 executor — the real E^- intervention.

FROZEN BEFORE EXECUTION (pre-registration §E.2, ratified 2026-09-05):
  primary   Determine = Option 3 (supported + competitors EPISTEMICALLY excluded)
  secondary Determine = Option 1 (existence)  -- reported, never adjudicated
  floor     |Delta| >= 0.10 on BOTH splits = material; 0.05-0.10 borderline; <0.05 negligible
  Delta     = P(flip | root-cause dim) - P(flip | decoy dim), within stratum

CALIBRATION FROZEN (declared pre-analysis, gate 0.30-0.80):
  chain=(3,4)  s_true=(3,4)  s_dec=(1,2)  n_decoy=(2,2)  BUDGET=5  -> base Determine ~= 0.38
"""
import os, sys, json, collections, statistics
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from nexus3 import (generate, investigate, eliminate_dim, determine_opt3, determine_opt1,
                    assessed, DIMENSIONS)

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SEEDS = {"train": 20260904, "test": 88020260904}
N = 2000
GEN = dict(chain=(3,4), s_true=(3,4), s_dec=(1,2), n_decoy=(2,2))
B0, B1 = 1, 5                      # E0 = before investigation, E1 = binding budget
FLOOR, BORDER = 0.10, 0.05

def det(E, contract): return determine_opt3(E) if contract=="opt3" else determine_opt1(E)

def zero(c, d, budget, seed, contract):
    """Zero_Q(d|E) <=> Determine(Q|K,E) == Determine(Q|E^-_d(K),E).
    PAIRED: identical seed and budget in both branches (control Z-E)."""
    a = det(investigate(c, budget, seed), contract)
    b = det(investigate(eliminate_dim(c, d), budget, seed), contract)
    return a == b

def view_restricted(E, allowed):
    """H3c: hold EVIDENCE fixed, change only the VIEW. No re-investigation."""
    return tuple(l for l in E if l.dst[0] in allowed and l.src[0] in allowed)

def classes(c):
    root = c.root[0]
    chain = {d for d in c.chain_dims if d != root}
    decoy = {d for d in c.decoy_dims if d != root and d not in chain}
    untouched = set(DIMENSIONS) - {root} - chain - decoy - {c.anchor[0]}
    return {"root": [root], "chain": sorted(chain), "decoy": sorted(decoy),
            "untouched": sorted(untouched)[:3]}

def run(split, contract):
    cases = generate(SEEDS[split], N, **GEN)
    rows, base = [], 0
    for i, c in enumerate(cases):
        sd = 40000 + i
        E1full = investigate(c, B1, sd)
        if det(E1full, contract) is not None: base += 1
        for cls, dims in classes(c).items():
            for d in dims:
                z0 = zero(c, d, B0, sd, contract)
                z1 = zero(c, d, B1, sd, contract)
                rows.append({"case": i, "class": cls, "dim": d,
                             "chain_len": len(c.chain_dims),
                             "zero0": z0, "zero1": z1, "flip": z0 and not z1})
        # ---- Z-F null intervention: eliminate a dimension that is not in the state at all
        rows.append({"case": i, "class": "NULL", "dim": "__none__", "chain_len": len(c.chain_dims),
                     "zero0": True, "zero1": zero(c, "__nonexistent__", B1, sd, contract),
                     "flip": not zero(c, "__nonexistent__", B1, sd, contract)})
        # ---- H3c: evidence FIXED, view narrowed to the anchor dimension only
        narrow = det(view_restricted(E1full, {c.anchor[0]}), contract)
        rows.append({"case": i, "class": "H3c_focus_only", "dim": "-",
                     "chain_len": len(c.chain_dims), "zero0": True,
                     "zero1": narrow == det(E1full, contract),
                     "flip": narrow != det(E1full, contract)})
    return rows, base / len(cases)

def rate(rows, cls):
    sub = [r for r in rows if r["class"] == cls]
    return {"n": sum(r["flip"] for r in sub), "d": len(sub),
            "rate": round(sum(r["flip"] for r in sub)/len(sub), 5) if sub else None}

def stratified_delta(rows):
    """Delta within stratum (chain length), Mantel-Haenszel style weighting."""
    out, num, den = [], 0.0, 0.0
    for L in sorted({r["chain_len"] for r in rows}):
        R = [r for r in rows if r["chain_len"]==L and r["class"]=="root"]
        D = [r for r in rows if r["chain_len"]==L and r["class"]=="decoy"]
        if len(R) < 50 or len(D) < 50: continue
        pr, pd = sum(r["flip"] for r in R)/len(R), sum(r["flip"] for r in D)/len(D)
        w = len(R)*len(D)/(len(R)+len(D)); num += w*(pr-pd); den += w
        out.append({"chain_len": L, "n_root": len(R), "n_decoy": len(D),
                    "p_root": round(pr,5), "p_decoy": round(pd,5), "delta": round(pr-pd,5)})
    return {"strata": out, "MH_delta": round(num/den,5) if den else None}

if __name__ == "__main__":
    RES = {}
    for contract in ("opt3", "opt1"):
        RES[contract] = {}
        for split in ("train","test"):
            rows, base = run(split, contract)
            RES[contract][split] = {
                "base_determine_rate": round(base,5),
                "calibration_gate_0.30_0.80": 0.30 <= base <= 0.80,
                "flip_root":      rate(rows,"root"),
                "flip_chain":     rate(rows,"chain"),
                "flip_decoy":     rate(rows,"decoy"),
                "flip_untouched": rate(rows,"untouched"),
                "Z_F_null_intervention": rate(rows,"NULL"),
                "H3c_focus_induced":     rate(rows,"H3c_focus_only"),
                "delta_marginal": round(rate(rows,"root")["rate"] - rate(rows,"decoy")["rate"], 5),
                "delta_stratified": stratified_delta(rows)}
    # verdict on the PRIMARY contract only
    d = [RES["opt3"][s]["delta_stratified"]["MH_delta"] for s in ("train","test")]
    verdict = ("MATERIAL — H3a supported in tested regime" if all(x is not None and abs(x)>=FLOOR for x in d)
               else "BORDERLINE — reported, not claimed" if all(x is not None and abs(x)>=BORDER for x in d)
               else "NEGLIGIBLE — below the frozen floor")
    RES["_verdict"] = {"primary_contract":"opt3","MH_delta_train":d[0],"MH_delta_test":d[1],
                       "floor":FLOOR,"borderline":BORDER,"VERDICT":verdict}
    RES["_P_Z3"] = {"prediction":"under Option 1 base Determine > 0.95 and |Delta| < 0.05",
        "base_determine_opt1": [RES["opt1"][s]["base_determine_rate"] for s in ("train","test")],
        "delta_opt1": [RES["opt1"][s]["delta_stratified"]["MH_delta"] for s in ("train","test")]}
    json.dump(RES, open(f"{ROOT}/data/results03.json","w"), indent=1)
    for contract in ("opt3","opt1"):
        print(f"\n{'='*74}\nCONTRACT = {contract}{'   (PRIMARY, frozen)' if contract=='opt3' else '   (secondary, reported only)'}\n{'='*74}")
        for s in ("train","test"):
            v=RES[contract][s]
            print(f"  {s}: base Determine={v['base_determine_rate']}  gate={v['calibration_gate_0.30_0.80']}")
            for k in ("flip_root","flip_chain","flip_decoy","flip_untouched","Z_F_null_intervention","H3c_focus_induced"):
                r=v[k]; print(f"      {k:26s} {r['n']:>6}/{r['d']:<6} = {r['rate']}")
            print(f"      delta marginal            {v['delta_marginal']}")
            print(f"      delta STRATIFIED (MH)     {v['delta_stratified']['MH_delta']}   strata={len(v['delta_stratified']['strata'])}")
    print(f"\n{'='*74}\nVERDICT (primary contract opt3): {verdict}")
    print(f"  MH delta train={d[0]}  test={d[1]}  floor={FLOOR}")
    print(f"\nP-Z3 (my pre-registered prediction): base_opt1={RES['_P_Z3']['base_determine_opt1']}  delta_opt1={RES['_P_Z3']['delta_opt1']}")
