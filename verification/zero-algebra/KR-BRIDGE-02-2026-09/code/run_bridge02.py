#!/usr/bin/env python3
r"""KR-BRIDGE-02-ZERO-PRESERVATION-PI-Q-2026-09 — runner.

KR-BRIDGE-01 returned OUTCOME A (no bridge) for ONE Pi and ONE Q. Its own §C named
the largest open gap: Q4/Q5/Q10 are unanswered because both were held fixed.
This experiment varies BOTH, under an enforced read-disjointness gate.

PRE-REGISTERED HYPOTHESES (declared before execution, never revised after):
  H1  NO BRIDGE            RD == 0 for every admissible (Pi,Q) and every T.
  H2  CONDITIONAL BRIDGE   RD != 0 for at least one admissible (Pi,Q,T), and the
                           sign/size replicates on the held-out TEST split.
  H3  DIRECTIONAL          if H2 holds, Zero predicts INadequacy (RD < 0) wherever
                           the transformation is redundancy-removing.
A result that appears on TRAIN and not on TEST is reported as NON-REPLICATING, never
as a finding.

DO NOT MODIFY: Theory v1.2, KR-ZERO / KR-REP-REDUCTION / KR-BRIDGE-01 results,
kernel operators. No carrier is declared. No algebra is declared.
"""
import json, os, sys, math, random, itertools, collections, datetime, platform
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from bridge import (Case, Rec, VALUES, SOURCES, TAGS, TIMES, N_REC,
                    TRANSFORMS, E_S)
from observables import PIS, QS, grid, admissible

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SEED_TRAIN, SEED_TEST = 20260904, 88020260904   # same seeds as KR-BRIDGE-01
N_CASES = 8000          # smaller than BRIDGE-01's 25000: the grid is 16x larger
MAXS    = 2

def gen(n, seed):
    rng = random.Random(seed)
    return [Case(tuple(Rec(v=rng.choice(VALUES), src=rng.choice(SOURCES),
                           tag=rng.choice(TAGS), t=rng.choice(TIMES))
                       for _ in range(N_REC))) for _ in range(n)]

def rep_key(R):
    return tuple((r.v, r.src, r.tag, r.rank) for r in R.recs)

SUBSETS = [S for m in range(1, MAXS+1) for S in itertools.combinations(range(N_REC), m)]

def redundancy(D):
    return len(D.recs) - len({r.v for r in D.recs})

# ---------------------------------------------------------------- Zero, per Pi
def zero_flags(cases, tname):
    """Returns {pi_name: [[bool per S] per case]}.
    T(D) and T(E_S(D)) are computed ONCE and shared across all Pi -- Zero's
    definition is unchanged, only its observable varies."""
    T = TRANSFORMS[tname][0]
    out = {p: [] for p in PIS}
    for D in cases:
        R0 = T(D)
        RS = [T(E_S(D, set(S), "source")) for S in SUBSETS]
        for p, (f, _, _) in PIS.items():
            a = f(R0)
            out[p].append([a == f(r) for r in RS])
    return out

# ---------------------------------------------------------------- adequacy, per Q
def adequacy_flags(cases, tname):
    """Adequate(D,T,Q) iff D's R-fiber is Q-HOMOGENEOUS over the population.
    Uses Q only. Zero uses Pi only. Disjoint code paths AND disjoint field reads."""
    T = TRANSFORMS[tname][0]
    keys = [rep_key(T(D)) for D in cases]
    out = {}
    for q, (qf, _, _) in QS.items():
        fib = collections.defaultdict(set)
        for k, D in zip(keys, cases): fib[k].add(qf(D))
        out[q] = [len(fib[k]) == 1 for k in keys]
    return out, len(set(keys))

# ---------------------------------------------------------------- 2x2 statistics
def stats(cell):
    nz  = cell["Z+A"] + cell["Z+I"]; nnz = cell["NZ+A"] + cell["NZ+I"]
    pz  = cell["Z+A"]/nz  if nz  else None
    pnz = cell["NZ+A"]/nnz if nnz else None
    a,b,c,d = cell["Z+A"]+.5, cell["Z+I"]+.5, cell["NZ+A"]+.5, cell["NZ+I"]+.5
    orat = (a*d)/(b*c); se = math.sqrt(1/a+1/b+1/c+1/d)
    return {"cells": dict(cell), "n": sum(cell.values()),
            "P_A_given_Z":  round(pz,5)  if pz  is not None else None,
            "P_A_given_NZ": round(pnz,5) if pnz is not None else None,
            "risk_difference": round(pz-pnz,5) if None not in (pz,pnz) else None,
            "odds_ratio": round(orat,4),
            "or_ci95": [round(math.exp(math.log(orat)-1.96*se),4),
                        round(math.exp(math.log(orat)+1.96*se),4)],
            "degenerate": (nz == 0 or nnz == 0)}

def analyse(cases, split):
    ADM, BLOCKED = grid()
    red = [redundancy(D) for D in cases]
    per_T = {}
    for tname in TRANSFORMS:
        zf = zero_flags(cases, tname)
        af, ndist = adequacy_flags(cases, tname)
        per_T[tname] = {"zero": zf, "adeq": af, "n_distinct_representations": ndist}
    rows = {}
    for pair in ADM:
        p, q = pair["Pi"], pair["Q"]
        for tname in TRANSFORMS:
            zf = per_T[tname]["zero"][p]; af = per_T[tname]["adeq"][q]
            cell = collections.Counter(); by_r = collections.defaultdict(collections.Counter)
            for ci in range(len(cases)):
                a = af[ci]
                for si in range(len(SUBSETS)):
                    z = zf[ci][si]
                    c = "Z+A" if z and a else "Z+I" if z else "NZ+A" if a else "NZ+I"
                    cell[c] += 1; by_r[red[ci]][c] += 1
            st = stats(cell)
            st["adequacy_rate"] = round(sum(af)/len(af), 5)
            st["zero_rate"] = round((cell["Z+A"]+cell["Z+I"])/st["n"], 5)
            st["by_redundancy"] = {str(k): {**dict(v),
                "risk_difference": stats(v)["risk_difference"]} for k, v in sorted(by_r.items())}
            rows[f"{p}|{q}|{tname}"] = st
    return {"split": split, "n_cases": len(cases), "rows": rows,
            "admissible_pairs": ADM, "blocked_pairs": BLOCKED,
            "n_distinct_representations": {t: per_T[t]["n_distinct_representations"]
                                           for t in TRANSFORMS}}

# ---------------------------------------------------------------- controls
def controls(cases, a_tr):
    r = {}
    # CONTROL G (new, this experiment): the gate must actually block something.
    ADM, BLOCKED = grid()
    r["CONTROL_G_disjointness_gate_bites"] = {
        "n_admissible": len(ADM), "n_blocked": len(BLOCKED),
        "PASS": len(BLOCKED) > 0,
        "note": "if the gate blocked nothing it would not be a gate"}
    # CONTROL H (new): Pi and Q must be independently varying -- some (Pi,Q) pairs
    # must differ in Zero rate at fixed Q, and in adequacy at fixed Pi.
    zr = {p: {a_tr['rows'][k]['zero_rate'] for k in a_tr['rows']
              if k.startswith(p + "|")} for p in PIS}
    ar = {q: {a_tr['rows'][k]['adequacy_rate'] for k in a_tr['rows']
              if f"|{q}|" in k} for q in QS}
    r["CONTROL_H_Pi_varies_zero"] = {
        "distinct_zero_rates_per_Pi": {p: len(v) for p, v in zr.items()},
        "PASS": len({tuple(sorted(v)) for v in zr.values()}) > 1,
        "note": "different Pi must produce different Zero behaviour, else Pi is inert"}
    r["CONTROL_I_Q_varies_adequacy"] = {
        "distinct_adequacy_rates_per_Q": {q: len(v) for q, v in ar.items()},
        "PASS": len({tuple(sorted(v)) for v in ar.values()}) > 1,
        "note": "different Q must produce different adequacy, else Q is inert"}
    # CONTROL B/C carried forward from KR-BRIDGE-01 under the ORIGINAL Q.
    afA,_ = adequacy_flags(cases, "T_A_preserving")
    afF,_ = adequacy_flags(cases, "T_F_destroying")
    afG,_ = adequacy_flags(cases, "T_G_recoding")
    q0 = "Q_argmax_ntags"
    rA = sum(afA[q0])/len(cases); rF = sum(afF[q0])/len(cases); rG = sum(afG[q0])/len(cases)
    r["CONTROL_A_preserving_is_adequate"]   = {"rate": round(rA,5), "PASS": rA > 0.95}
    r["CONTROL_B_destroying_is_inadequate"] = {"rate": round(rF,5), "PASS": rF < 0.05}
    r["CONTROL_C_invertible_recoding_matches_A"] = {
        "A_rate": round(rA,5), "G_rate": round(rG,5), "PASS": rA == rG}
    r["CONTROL_E_test_separates_A_from_F"] = {"PASS": rA - rF > 0.5}
    return r

if __name__ == "__main__":
    tr, te = gen(N_CASES, SEED_TRAIN), gen(N_CASES, SEED_TEST)
    print("analysing TRAIN…", flush=True); a_tr = analyse(tr, "train")
    print("analysing TEST…",  flush=True); a_te = analyse(te, "test")
    print("controls…", flush=True);        ctl  = controls(tr, a_tr)

    # ------------------------------------------------ H1 / H2 / H3 adjudication
    NONZERO, DEGEN, REPLICATED, NONREPL = [], [], [], []
    for k, v in a_tr["rows"].items():
        if v["degenerate"]: DEGEN.append(k); continue
        rd = v["risk_difference"]
        if rd is not None and abs(rd) > 1e-12:
            NONZERO.append(k)
            rt = a_te["rows"][k]["risk_difference"]
            same_sign = rt is not None and (rd > 0) == (rt > 0) and abs(rt) > 1e-12
            close     = rt is not None and abs(rd - rt) < 0.05
            (REPLICATED if (same_sign and close) else NONREPL).append(
                {"key": k, "rd_train": rd, "rd_test": rt,
                 "same_sign": same_sign, "within_0.05": close})
    verdict = ("H2 SUPPORTED — a conditional bridge replicates"
               if REPLICATED else
               "H1 NOT REFUTED — no admissible (Pi,Q,T) shows a replicating RD != 0")
    adjud = {"n_admissible_cells": len(a_tr["rows"]),
             "n_degenerate_cells": len(DEGEN), "degenerate_cells": DEGEN,
             "n_nonzero_RD_train": len(NONZERO),
             "replicating": REPLICATED, "non_replicating": NONREPL,
             "VERDICT": verdict,
             "H3_directional": ("not assessed — H2 unsupported" if not REPLICATED else
                                [x for x in REPLICATED if "dedup" in x["key"]])}

    META = dict(experiment_id="KR-BRIDGE-02-ZERO-PRESERVATION-PI-Q-2026-09",
                extends="KR-BRIDGE-01-ZERO-PRESERVATION-2026-09 (OUTCOME A)",
                closes="Q4 / Q5 / Q10 of KR-BRIDGE-01 §C",
                theory="v1.2 FROZEN", kernel="NOT SELECTED", algebra="none",
                carrier="EXPERIMENTAL ONLY — not declared",
                seeds={"train": SEED_TRAIN, "test": SEED_TEST}, n_cases=N_CASES,
                S_sizes=list(range(1, MAXS+1)),
                n_Pi=len(PIS), n_Q=len(QS), n_T=len(TRANSFORMS),
                date=datetime.date.today().isoformat(), python=platform.python_version())

    json.dump({"_meta": META, "controls": ctl, "adjudication": adjud,
               "admissible_pairs": a_tr["admissible_pairs"],
               "blocked_pairs": a_tr["blocked_pairs"],
               "train": a_tr["rows"], "test": a_te["rows"],
               "n_distinct_representations": a_tr["n_distinct_representations"]},
              open(f"{ROOT}/results/bridge02.json", "w"), indent=1, default=str)

    print(f"\nGATE: {len(a_tr['admissible_pairs'])} admissible, "
          f"{len(a_tr['blocked_pairs'])} blocked  -> {len(a_tr['rows'])} (Pi,Q,T) cells")
    print("\nCONTROLS:")
    for k, v in ctl.items(): print(f"  {'PASS' if v.get('PASS') else 'FAIL'}  {k}")
    print(f"\nADJUDICATION: {verdict}")
    print(f"  degenerate cells (Zero always/never fires): {len(DEGEN)}")
    print(f"  cells with RD != 0 on TRAIN: {len(NONZERO)}")
    print(f"  of those, REPLICATING on TEST: {len(REPLICATED)}")
    for x in REPLICATED[:40]:
        print(f"    REPLICATES  {x['key']:62s} RD_tr={x['rd_train']:+.5f} "
              f"RD_te={x['rd_test']:+.5f}")
    for x in NONREPL[:20]:
        print(f"    non-repl.   {x['key']:62s} RD_tr={x['rd_train']:+.5f} "
              f"RD_te={x['rd_test']}")
