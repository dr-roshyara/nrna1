"""Part XIII - information-theoretic check, and Part XV - causal/model
criticism.  Pure-python, no external deps."""
import math, random
from collections import Counter

def H(p):
    return -sum(x*math.log2(x) for x in p if x > 0)

def MI(joint):
    """joint: dict[(x,y)] = prob"""
    px, py = Counter(), Counter()
    for (x, y), p in joint.items(): px[x] += p; py[y] += p
    return sum(p*math.log2(p/(px[x]*py[y])) for (x, y), p in joint.items() if p > 0)

def context_experiment():
    """X = (port, context).  The signal Y is the ambiguous token 'available'
    with probability q, else the explicit port.
      WITH meaning-assignment : S  = (Y, context)      (context-indexed)
      WITHOUT                 : S' = Y                 (raw encoding)
    Data-processing: S' is a function of S, so I(X;S') <= I(X;S).
    The question is whether the LOSS is inquiry-relevant."""
    ports = [8081, 8082]; ctxs = ["C_internal", "C_external"]
    q = 0.5                      # probability the report is the ambiguous token
    joint_S, joint_Sp = {}, {}
    for p in ports:
        for c in ctxs:
            px = 0.25
            for y, py in [("available", q), (str(p), 1-q)]:
                x = (p, c)
                joint_S[(x, (y, c))] = joint_S.get((x, (y, c)), 0) + px*py
                joint_Sp[(x, y)] = joint_Sp.get((x, y), 0) + px*py
    return dict(H_X=H([0.25]*4), I_X_S=MI(joint_S), I_X_Sprime=MI(joint_Sp),
                loss=MI(joint_S)-MI(joint_Sp), dpi_ok=MI(joint_Sp) <= MI(joint_S)+1e-12)

def confounding_experiment(n=4000, seed=11):
    """Z -> X, Z -> Y, with NO X -> Y edge.  OLS of Y on X fits well.
    fit != validation; regression != causality."""
    rng = random.Random(seed)
    sx = sy = sxy = sxx = 0.0
    xs, ys = [], []
    for _ in range(n):
        z = rng.gauss(0, 1)
        x = z + rng.gauss(0, .3)
        y = 2*z + rng.gauss(0, .3)          # true causal effect of X on Y is 0
        xs.append(x); ys.append(y)
    mx = sum(xs)/n; my = sum(ys)/n
    sxy = sum((a-mx)*(b-my) for a, b in zip(xs, ys))
    sxx = sum((a-mx)**2 for a in xs)
    beta = sxy/sxx
    syy = sum((b-my)**2 for b in ys)
    r2 = (sxy**2)/(sxx*syy)
    return dict(n=n, seed=seed, ols_beta=beta, r2=r2,
                true_causal_effect=0.0,
                association_strong=r2 > 0.5, causal_claim_licensed=False)
