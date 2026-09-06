#!/usr/bin/env python3
r"""KR-REP-REDUCTION-2026-09 — generation, metrics, persistence.

Design S12-S13: full population persisted, both splits, every level.
FT-1..FT-7 run FIRST; no result is reported from a run with a failing fidelity test.
"""
import json, os, sys, random, datetime, platform, collections
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from carrier import Case, Rec, V, SOURCES, TIMES, N_REC, Q, TOTALS
from pipeline import build_chain, CHAIN, C, O, LEVEL_FIELDS, E_S
from metrics import H_hat, H_hat_cond, N_viol, fiber_stats, bootstrap_ci
from fidelity import run_fidelity

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
N_CASES = 40000
SEED_TRAIN, SEED_TEST = 20260903, 77020260903
LEVELS = ["R5", "R4", "R3", "R2"]

def gen(n, seed):
    rng = random.Random(seed)
    return [Case(tuple(Rec(v=rng.choice(V), s=rng.choice(SOURCES), t=rng.choice(TIMES))
                       for _ in range(N_REC))) for _ in range(n)]

def rep_key(case, level):
    if level == "R2": return tuple((r.rank, r.s) for r in case.recs)
    if level == "D":  return tuple((r.v, r.s, r.t) for r in case.recs)
    return tuple((r.v, r.s) for r in case.recs)

def encoded_size(case, level):
    return len(json.dumps(rep_key(case, level), separators=(",", ":")))

def analyse(cases, conv, split, persist=False):
    chains = [build_chain(D, conv) for D in cases]
    qs = [Q(D) for D in cases]
    out = {}
    lvl_rows = []
    for lvl in LEVELS:
        reps = [rep_key(ch[lvl], lvl) for ch in chains]
        srcs = [{r.s for r in D.recs} for D in cases]
        A = sum(C(ch[lvl], lvl, s) for ch, s in zip(chains, srcs)) / len(cases)
        dec = [O(ch[lvl]) for ch in chains]
        F  = sum(1 for c, s, d, q in zip(chains, srcs, dec, qs)
                 if C(c[lvl], lvl, s) and d == q) / len(cases)
        F_src  = sum(1 for d, q in zip(dec, qs) if d[0] == q[0]) / len(cases)
        F_dec  = sum(1 for d, q in zip(dec, qs) if d[1] == q[1]) / len(cases)
        nv = N_viol(reps, qs)
        hq = H_hat_cond(qs, reps); hr = H_hat_cond(reps, qs); hh = H_hat(reps)
        fs = fiber_stats(reps, qs)
        ci = bootstrap_ci(lambda d: H_hat_cond([x[0] for x in d], [x[1] for x in d]),
                          list(zip(qs, reps)), B=120, seed=7)
        out[lvl] = {"A_n": round(A,5), "F_n": round(F,5),
                    "F_argmax_component": round(F_src,5), "F_decile_component": round(F_dec,5),
                    "H_hat_Q_given_R": round(hq,5), "H_hat_Q_given_R_ci95": ci,
                    "H_hat_R_given_Q": round(hr,5), "H_hat_R": round(hh,5),
                    "N_viol": nv, "adequate": (nv == 0), **fs,
                    "mean_encoded_bytes": round(sum(encoded_size(ch[lvl], lvl)
                                                    for ch in chains)/len(cases),2),
                    "cardinality": len(set(reps)),
                    "fields_per_record": len(LEVEL_FIELDS[lvl])}
        if persist:
            for i, (ch, q) in enumerate(zip(chains, qs)):
                lvl_rows.append({"cid": i, "sp": split, "n": lvl,
                                 "R": list(rep_key(ch[lvl], lvl)),
                                 "A": C(ch[lvl], lvl, srcs[i]), "O": list(O(ch[lvl])),
                                 "Q": list(q)})
    return out, chains, qs, lvl_rows

def zero_probe(chains, qs, conv, cap=4000):
    """design S14/H-RR2: is Zero at level n ASSOCIATED with adequacy at n-1?
    Zero uses the TYPED per-level operator, never D\\S."""
    rows = []
    for lvl, nxt in (("R5","R4"), ("R4","R3"), ("R3","R2")):
        z_count = 0; n = 0
        for ch in chains[:cap]:
            R = ch[lvl]
            for i in range(N_REC):
                n += 1
                if O(E_S(R, lvl, {i})) == O(R): z_count += 1
        rows.append({"level": lvl, "next": nxt, "elimination_tests": n,
                     "zero_rate": round(z_count/n, 5)})
    return rows

if __name__ == "__main__":
    print("generating…", flush=True)
    tr, te = gen(N_CASES, SEED_TRAIN), gen(N_CASES, SEED_TEST)

    print("running FT-1..FT-7 (before any result)…", flush=True)
    ft = run_fidelity(tr, te, "ascending")
    all_pass = all(v.get("pass") for v in ft.values())
    for k, v in ft.items(): print(f"   {k:34s} pass={v.get('pass')}")
    if not all_pass:
        json.dump(ft, open(f"{ROOT}/results/fidelity.json","w"), indent=1, default=str)
        sys.exit("FIDELITY FAILURE — no results reported (design S16)")

    res = {"_meta": dict(experiment_id="KR-REP-REDUCTION-2026-09",
                         design="KR-REP-REDUCTION-DESIGN-2026-09.md",
                         theory="v1.2 UNCHANGED", kernel="NOT SELECTED",
                         n_cases_per_split=N_CASES, seeds={"train":SEED_TRAIN,"test":SEED_TEST},
                         date=datetime.date.today().isoformat(),
                         python=platform.python_version()),
           "fidelity": ft}

    for conv in ("ascending", "descending"):
        print(f"analysing rank_convention={conv}…", flush=True)
        a_tr, ch_tr, q_tr, rows = analyse(tr, conv, "train", persist=(conv=="ascending"))
        a_te, _, _, _          = analyse(te, conv, "test")
        res[f"train_{conv}"] = a_tr
        res[f"test_{conv}"]  = a_te
        res[f"zero_probe_{conv}"] = zero_probe(ch_tr, q_tr, conv)
        if conv == "ascending":
            os.makedirs(f"{ROOT}/corpus", exist_ok=True)
            with open(f"{ROOT}/corpus/levels.jsonl","w") as f:
                for r in rows: f.write(json.dumps(r, separators=(",",":"))+"\n")
            with open(f"{ROOT}/corpus/cases.jsonl","w") as f:
                for i, D in enumerate(tr):
                    f.write(json.dumps({"cid":i,"sp":"train","seed":SEED_TRAIN,
                        "D":[[r.v,r.s,r.t] for r in D.recs],"Q":list(Q(D))},
                        separators=(",",":"))+"\n")
                for i, D in enumerate(te):
                    f.write(json.dumps({"cid":i,"sp":"test","seed":SEED_TEST,
                        "D":[[r.v,r.s,r.t] for r in D.recs],"Q":list(Q(D))},
                        separators=(",",":"))+"\n")
    json.dump(res, open(f"{ROOT}/results/metrics.json","w"), indent=1, default=str)
    print("\nDONE -> results/metrics.json, corpus/cases.jsonl, corpus/levels.jsonl")
