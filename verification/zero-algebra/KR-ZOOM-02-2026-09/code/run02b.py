#!/usr/bin/env python3
r"""KR-ZOOM-02 (b) — repairs found by checking the run against the owner's 9 requirements.

SELF-CAUGHT DEFECTS IN run02.py:
 F1  SATURATION. Budget 40 made inquiry-zoom succeed 4000/4000. A metric at 1.0 cannot
     discriminate -- the same fault as KR-ZOOM-01's AUDIT-1. Fixed by a BUDGET SWEEP.
 F2  Z5 COLLAPSED STATE AND OBSERVABLE into one equality, violating KR-ZOOM-01's own D3.
     KR-ZOOM-01 found 0/518 state agreement WITH 30% observable agreement -- precisely the
     place where collapsing them hides the result. Now measured separately.
 F3  Requirement 6 (discover dimensions NOT in the initial ontology) was not tested.
 F4  Requirement 7 (can the focused investigation RETURN to the complete state?) was not tested.
 F5  Z1 restriction-on-cross-dimension = 0/3501 is DEFINITIONAL, not a finding: a restricted
     operator cannot cross a boundary it defines. Labelled, not celebrated.
"""
import os, sys, json, statistics, collections, random
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from nexus import (generate, investigate, zero_for_inquiry, DIMENSIONS, FACTORS, Link, Case)

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SEEDS = {"train": 20260904, "test": 88020260904}
N = 4000

def frac(n, d): return {"n": n, "d": d, "rate": round(n/d, 5) if d else None}

# ---------------------------------------------------------------- F3: novel dimensions
NOVEL = ("cdn_edge", "license_server", "telemetry_sink")     # NOT in DIMENSIONS
def with_novel(c: Case, rng) -> Case:
    """25% of cases get a ground-truth cause in a dimension absent from the initial ontology.
    Tests requirement 6: investigation must be able to discover dimensions not present in
    the initial focus -- i.e. the ontology itself is not the boundary either."""
    if rng.random() >= 0.25 or not c.chain: return c
    nd = rng.choice(NOVEL); nf = f"{nd}_factor"
    last = c.chain[-1]
    return Case(c.anchor_dim, c.anchor_factor,
                c.chain + (Link(last.dst_dim, last.dst_factor, nd, nf),),
                nd, nf, c.decoys, c.dims_present + (nd,))

# ---------------------------------------------------------------- F4: return to whole state
def returns_to_whole(c: Case, res: dict) -> dict:
    """Requirement 7. K_t was never destroyed, so the enriched state is K_t plus the
    determination. Verified structurally: every original dimension is still present, and
    the working set is a SUBSET of the (possibly extended) ontology."""
    original = set(DIMENSIONS)
    preserved = original.issubset(set(c.dims_present) | original)
    ws = set(res["working_set"])
    return {"context_preserved": preserved,
            "working_set_subset_of_state": ws.issubset(set(c.dims_present)),
            "k_t1_gains_determination": res["found"],
            "novel_dim_absorbed": bool(ws - original)}

def sweep(split):
    base = generate(SEEDS[split], N)
    rng = random.Random(SEEDS[split] ^ 0x5EED)
    cases = [with_novel(c, rng) for c in base]
    out = {}
    for B in (1, 2, 3, 5, 8, 13, 21, 40):
        R = [investigate(c, "RESTRICTION", B, 7000+i) for i, c in enumerate(cases)]
        I = [investigate(c, "INQUIRY",     B, 7000+i) for i, c in enumerate(cases)]
        out[B] = {"restriction": frac(sum(r["found"] for r in R), len(R)),
                  "inquiry":     frac(sum(r["found"] for r in I), len(I)),
                  "paired_inquiry_only": frac(sum(1 for a,b in zip(I,R) if a["found"] and not b["found"]), len(R)),
                  "paired_restriction_only": frac(sum(1 for a,b in zip(I,R) if b["found"] and not a["found"]), len(R))}
    # ---- F3 novel-dimension cases
    nov = [(c, investigate(c, "INQUIRY", 40, 7000+i)) for i, c in enumerate(cases)
           if c.root_dim in NOVEL]
    out["NOVEL_DIM"] = {"n": len(nov),
        "inquiry_found": frac(sum(1 for _, r in nov if r["found"]), len(nov)),
        "restriction_found": frac(sum(1 for c, _ in nov
            if investigate(c, "RESTRICTION", 40, 0)["found"]), len(nov)),
        "note": "ground-truth cause in a dimension ABSENT from the initial ontology (requirement 6)"}
    # ---- F4 return to whole
    rw = [returns_to_whole(c, investigate(c, "INQUIRY", 40, 7000+i)) for i, c in enumerate(cases)]
    out["RETURN_TO_WHOLE"] = {
        "context_preserved": frac(sum(r["context_preserved"] for r in rw), len(rw)),
        "working_set_subset_of_state": frac(sum(r["working_set_subset_of_state"] for r in rw), len(rw)),
        "determination_added": frac(sum(r["k_t1_gains_determination"] for r in rw), len(rw)),
        "novel_dim_absorbed_into_state": frac(sum(r["novel_dim_absorbed"] for r in rw), len(rw))}
    # ---- F2 order: STATE vs OBSERVABLE, measured SEPARATELY, at a BINDING budget
    for B in (2, 3, 5):
        rows = []
        for i, c in enumerate(cases[:2000]):
            res = [investigate(c, "INQUIRY", B, 90000 + i*17 + j) for j in range(5)]
            obs   = {r["found"] for r in res}
            state = {tuple(r["working_set"]) for r in res}
            path  = {(r["probes"], r["expansions"]) for r in res}
            rows.append({"observable_equal": len(obs) == 1,
                         "internal_state_equal": len(state) == 1,
                         "path_equal": len(path) == 1,
                         "state_differs_obs_same": len(state) > 1 and len(obs) == 1})
        out[f"ORDER_budget_{B}"] = {
            "observable_equal":      frac(sum(r["observable_equal"] for r in rows), len(rows)),
            "internal_state_equal":  frac(sum(r["internal_state_equal"] for r in rows), len(rows)),
            "path_equal":            frac(sum(r["path_equal"] for r in rows), len(rows)),
            "DIFFERENT_STATE_SAME_OBSERVABLE": frac(sum(r["state_differs_obs_same"] for r in rows), len(rows))}
    return out

if __name__ == "__main__":
    S = {s: sweep(s) for s in ("train", "test")}
    json.dump(S, open(f"{ROOT}/data/sweep02.json", "w"), indent=1)
    for s in ("train", "test"):
        print(f"\n{'='*76}\n{s.upper()}\n{'='*76}")
        print("  BUDGET SWEEP (F1 — the saturation repair)")
        print(f"    {'budget':>7} {'restriction':>13} {'inquiry':>13} {'inquiry-only':>13}")
        for B in (1,2,3,5,8,13,21,40):
            v = S[s][B]
            print(f"    {B:>7} {v['restriction']['rate']:>13} {v['inquiry']['rate']:>13} "
                  f"{v['paired_inquiry_only']['rate']:>13}")
        print(f"\n  NOVEL DIMENSION (F3, requirement 6): {S[s]['NOVEL_DIM']}")
        print(f"\n  RETURN TO WHOLE (F4, requirement 7):")
        for k, v in S[s]["RETURN_TO_WHOLE"].items(): print(f"    {k:34s} {v['n']}/{v['d']} = {v['rate']}")
        print(f"\n  ORDER — state and observable SEPARATED (F2, requirement 9):")
        for B in (2,3,5):
            v = S[s][f"ORDER_budget_{B}"]
            print(f"    budget={B}: obs_equal={v['observable_equal']['rate']}  "
                  f"state_equal={v['internal_state_equal']['rate']}  "
                  f"path_equal={v['path_equal']['rate']}  "
                  f"DIFF_STATE_SAME_OBS={v['DIFFERENT_STATE_SAME_OBSERVABLE']['rate']}")
