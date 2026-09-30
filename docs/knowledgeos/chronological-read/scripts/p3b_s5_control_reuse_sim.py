#!/usr/bin/env python3
"""P3b S5 H-17 design evidence: type-I error of two tests under control-label reuse (MAJOR-2 of the v2.2
certification, G-LOG-0038/0039). Synthetic simulation only: reads no repository data, no corpus, no sealed material.
DESIGN EVIDENCE, NOT A TUNING MECHANISM: no threshold, parameter or cell rule is derived from its output.

Null model (latent-label null, after the certifier's /tmp/s5cert/reuse*.py): every label has a latent propensity
u ~ N(0,1); a pair's class outcome Y ~ Bernoulli(logistic(a + b*(u_i+u_j)/sqrt 2)); each distinct pair is analysed once
(same Y wherever it appears); candidate pairs are label-disjoint pairs drawn at random, so the generator link carries no
information (H0 true). r = 2 control pairs per candidate, disjoint within the matched set, reused freely across sets.

Tests compared:
  MS  matched-set test (v2.2): exact Poisson-binomial tail of sum over sets of candidate outcomes, per-set role
      exchangeability, every appearance of a control counted.
  LC  label-cluster test (v2.3 specification): units = distinct sets; blocks = connected components of the label graph
      (labels joined when they co-occur in any unit); within each block the candidate role is permuted among units with
      the block's candidate count fixed; T = number of candidate units showing the class; exact p = P(sum of independent
      per-block hypergeometrics >= T) by convolution (no Monte-Carlo, no seed). Blocks without both roles are
      uninformative and dropped.

Regimes: R1 shared pool (G-SHARED-GROUP-like: 898 labels, m = 295); R2 small shared pools (120/40, 60/25);
R3 control sets drawn from a fixed random subset of 40 labels of a 120-label pool (heavy control reuse, connected);
R4 control sets drawn from a separate pool of 40 labels sharing no label with any candidate (the certifier's inflation
regime): LC is untestable there by construction (no block holds both roles), which the output counts.

  python3 p3b_s5_control_reuse_sim.py [draws]      (prints {header, body} JSON)
"""
import hashlib
import json
import math
import os
import platform
import subprocess
import sys

import numpy as np

SEED = 20261012
ALPHAS = (0.05, 0.01, 0.10 / 64)
A_INT = -1.4


def pb_tail(probs, x):
    d = np.zeros(len(probs) + 1)
    d[0] = 1.0
    for p in probs:
        d[1:] = d[1:] * (1 - p) + d[:-1] * p
        d[0] *= (1 - p)
    return float(d[x:].sum())


def hyper_pmf(N, K, n):
    lo, hi = max(0, n - (N - K)), min(n, K)
    denom = math.comb(N, n)
    out = np.zeros(n + 1)
    for x in range(lo, hi + 1):
        out[x] = math.comb(K, x) * math.comb(N - K, n - x) / denom
    return out


def lc_p(units, roles, ys):
    parent = {}

    def find(x):
        while parent.setdefault(x, x) != x:
            parent[x] = parent[parent[x]]
            x = parent[x]
        return x
    for u in units:
        a, b = find(u[0]), find(u[1])
        if a != b:
            parent[a] = b
    blocks = {}
    for u, r, y in zip(units, roles, ys):
        blocks.setdefault(find(u[0]), []).append((r, y))
    dist = np.array([1.0])
    t_obs = 0
    for bl in blocks.values():
        n_c = sum(1 for r, _ in bl if r == "C")
        if n_c == 0 or n_c == len(bl):
            continue
        k = sum(y for _, y in bl)
        t_obs += sum(y for r, y in bl if r == "C")
        dist = np.convolve(dist, hyper_pmf(len(bl), k, n_c))
    if len(dist) == 1:
        return None
    return float(dist[t_obs:].sum())


def run(rng, PL, m, b, N, ctrl_pool=None):
    rej = {"MS": {a: 0 for a in ALPHAS}, "LC": {a: 0 for a in ALPHAS}}
    lc_untestable = 0
    for _ in range(N):
        u = rng.normal(size=PL)
        cand = rng.permutation(PL)[:2 * m].reshape(m, 2)
        if ctrl_pool is None:
            pool = np.arange(PL)
        elif ctrl_pool == "separate":
            pool = np.setdiff1d(np.arange(PL), cand.ravel())[:40]
        else:
            pool = rng.permutation(PL)[:ctrl_pool]
        ys = {}

        def Y(i, j):
            key = (min(i, j), max(i, j))
            if key not in ys:
                z = A_INT + b * (u[i] + u[j]) / math.sqrt(2)
                ys[key] = int(rng.random() < 1 / (1 + math.exp(-z)))
            return ys[key], key
        yc, yk, unit_role = [], [], {}
        for c in cand:
            y, key = Y(*c)
            yc.append(y)
            unit_role[key] = "C"
            avail = np.setdiff1d(pool, c)
            pk = rng.choice(avail, 4, replace=False)
            row = []
            for i, j in ((pk[0], pk[1]), (pk[2], pk[3])):
                y2, key2 = Y(i, j)
                row.append(y2)
                unit_role.setdefault(key2, "K")
            yk.append(row)
        yc, yk = np.array(yc), np.array(yk)
        t = yc + yk.sum(1)
        p_ms = pb_tail(t / 3.0, int(yc.sum()))
        units = list(unit_role)
        p_lc = lc_p(units, [unit_role[x] for x in units], [ys[x] for x in units])
        for a in ALPHAS:
            rej["MS"][a] += p_ms <= a
            if p_lc is not None:
                rej["LC"][a] += p_lc <= a
        lc_untestable += p_lc is None
    return {"MS": {f"{a:.5f}": round(rej["MS"][a] / N, 4) for a in ALPHAS},
            "LC": {f"{a:.5f}": round(rej["LC"][a] / N, 4) for a in ALPHAS}, "LC_untestable": lc_untestable, "draws": N}


def main():
    draws = int(sys.argv[1]) if len(sys.argv) > 1 else 1000
    rng = np.random.default_rng(SEED)
    body = {}
    for name, PL, m, pool in (("R1_shared_898_m295", 898, 295, None), ("R2_shared_120_m40", 120, 40, None),
                              ("R2_shared_60_m25", 60, 25, None), ("R3_ctrlpool40_of_120_m40", 120, 40, 40), ("R4_separate_pool40_m40", 120, 40, "separate")):
        for b in (0.0, 2.0, 4.0):
            body[f"{name}_b{b}"] = run(rng, PL, m, b, draws if PL < 800 else max(200, draws // 4), pool)
    blob = subprocess.run(["git", "hash-object", os.path.abspath(__file__)], capture_output=True, text=True).stdout.strip()
    txt = json.dumps(body, indent=1, sort_keys=True)
    header = {"script_name": "scripts/p3b_s5_control_reuse_sim.py", "script_version": blob, "seed": SEED,
              "parameters": {"draws": draws, "alphas": list(ALPHAS), "intercept": A_INT}, "input_hashes": {},
              "output_sha256": hashlib.sha256(txt.encode()).hexdigest(), "python_version": sys.version.split()[0],
              "numpy_version": np.__version__, "platform": platform.platform(),
              "note": "design evidence only; synthetic; not a tuning mechanism"}
    print(json.dumps({"header": header, "body": body}, indent=1, sort_keys=True))
    return 0


if __name__ == "__main__":
    sys.exit(main())
