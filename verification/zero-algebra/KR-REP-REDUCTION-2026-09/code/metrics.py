r"""Design S10-S11: the metric vector, fiber statistics, entropy with bias correction."""
import math, collections, random

def _plugin_H(counts):
    n = sum(counts.values())
    if n == 0: return 0.0
    return -sum((c/n) * math.log2(c/n) for c in counts.values() if c > 0)

def H_hat(values, bias_correct=True):
    """marginal entropy, Miller-Madow corrected:  H_MM = H_plugin + (K-1)/(2N ln2)"""
    c = collections.Counter(values); n = sum(c.values())
    h = _plugin_H(c)
    if bias_correct and n > 0:
        h += (len(c) - 1) / (2 * n * math.log(2))
    return h

def H_hat_cond(xs, ys, bias_correct=True):
    """H(X|Y) = sum_y p(y) H(X | Y=y), Miller-Madow corrected within each fiber"""
    by = collections.defaultdict(collections.Counter)
    for x, y in zip(xs, ys): by[y][x] += 1
    n = len(xs); h = 0.0
    for y, c in by.items():
        ny = sum(c.values()); hy = _plugin_H(c)
        if bias_correct and ny > 0:
            hy += (len(c) - 1) / (2 * ny * math.log(2))
        h += (ny / n) * hy
    return h

def N_viol(reps, qs):
    """design S11 -- conflicting Q-pairs inside the same R-fiber.
    Computed per fiber in O(N), not by enumerating O(N^2) pairs."""
    by = collections.defaultdict(collections.Counter)
    for r, q in zip(reps, qs): by[r][q] += 1
    total = 0
    for r, c in by.items():
        n = sum(c.values())
        total += n*(n-1)//2 - sum(k*(k-1)//2 for k in c.values())
    return total

def fiber_stats(reps, qs):
    by = collections.defaultdict(collections.Counter)
    for r, q in zip(reps, qs): by[r][q] += 1
    sizes = [sum(c.values()) for c in by.values()]
    conflicted = sum(1 for c in by.values() if len(c) > 1)
    return {"n_fibers": len(by), "max_fiber": max(sizes) if sizes else 0,
            "mean_fiber": round(sum(sizes)/len(sizes), 3) if sizes else 0,
            "singleton_fibers": sum(1 for s in sizes if s == 1),
            "conflicted_fibers": conflicted}

def bootstrap_ci(fn, data, B=200, seed=1, alpha=0.05):
    """percentile bootstrap CI for a statistic over paired data"""
    rng = random.Random(seed); n = len(data); out = []
    for _ in range(B):
        samp = [data[rng.randrange(n)] for _ in range(n)]
        out.append(fn(samp))
    out.sort()
    lo = out[int(alpha/2 * B)]; hi = out[min(B-1, int((1-alpha/2) * B))]
    return (round(lo, 5), round(hi, 5))
