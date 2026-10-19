#!/usr/bin/env python3
"""Implements PROTOCOL.md (sha256 5cf1cce8fcf93ad668845816e5ab5a6cac15100882a1774c62ada9aa03efd682) exactly."""
import os, platform, random, sys
sys.path.insert(0, os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "o10a"))
import o10a_checks as X

SEED, PER_Q, QS = 20261019, 100, (0.1, 0.2, 0.3)
L = X.all_hist(3)

def eq_rel(lab):
    return lab

def greatest_test(cand, feas):
    in_F = any(X.subset_rel(cand, f, L) and X.subset_rel(f, cand, L) for f in feas)
    return in_F and all(X.subset_rel(f, cand, L) for f in feas)

def same_rel(a, b):
    return X.subset_rel(a, b, L) and X.subset_rel(b, a, L)

def candidates(Dsig):
    star = X.star(L, Dsig)
    w1 = {h: (len(h), Dsig[h]) for h in L}
    w2 = {h: (len(h), Dsig[h], Dsig[h[:-1]] if h else None) for h in L}
    w3 = {h: h for h in L}
    w4 = {h: (len(h),) + tuple(Dsig[h[:t]] for t in range(1, len(h) + 1)) for h in L}
    return star, {"W1": w1, "W2": w2, "W3": w3, "W4": w4}

if __name__ == "__main__":
    rnd = random.Random(SEED)
    res = {"C2_fail": 0, "C4_fail": 0, "rejected": 0, "accepted": 0, "W5_detected": 0}
    det = {k: [0, 0] for k in ("W1", "W2", "W3", "W4")}      # [differs-from-star, detected]
    sizes = []
    for q in QS:
        acc = 0
        while acc < PER_Q:
            m = rnd.choice((1, 2))
            ds = [{h: int(rnd.random() < q) for h in L} for _ in range(m)]
            Dsig = {h: tuple(d[h] for d in ds) for h in L}
            feas = X.pc_feasible(L, Dsig)
            if not (10 <= len(feas) <= 20000):
                res["rejected"] += 1
                continue
            acc += 1; res["accepted"] += 1; sizes.append(len(feas))
            star, wrong = candidates(Dsig)
            if not greatest_test(star, feas):
                res["C2_fail"] += 1
            for k, w in wrong.items():
                if not same_rel(w, star):
                    det[k][0] += 1
                    det[k][1] += not greatest_test(w, feas)
            s = {h: rnd.randrange(2) for h in L}
            truth = any(not X.determines(f, s, L) for f in feas)
            if truth != (not X.determines(star, s, L)):
                res["C4_fail"] += 1
            if (not X.determines({h: h for h in L}, s, L)) != truth:
                res["W5_detected"] += 1
    sizes.sort()
    print("env:", platform.python_version(), platform.system(), platform.release())
    print("results:", res)
    print("feasible |F| per accepted instance: min", sizes[0], "median", sizes[len(sizes) // 2], "max", sizes[-1])
    for k, (n_diff, n_det) in det.items():
        print(f"{k}: differs from ≡* on {n_diff} instances; detected on {n_det}")
