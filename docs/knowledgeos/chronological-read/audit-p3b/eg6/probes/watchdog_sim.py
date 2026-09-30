#!/usr/bin/env python3
"""EG-6 v3 F-02: operating characteristics of the post-canary systemic-failure watchdog (synthetic simulation only).
Rule: after every K completed post-canary attempts, STOP if P(X >= f | n, Q_STAR) <= ALPHA_W (equivalently, the exact
one-sided Clopper-Pearson lower (1-ALPHA_W) bound on q exceeds Q_STAR), with n completed attempts and f failures
(classes X, A1, A2) so far. Q_STAR is where M = 4 gives P(all 396 batches pass) = 0.90."""
import math, random

B, M, LEVEL = 396, 4, 0.90
Q_STAR = (1 - LEVEL ** (1 / B)) ** (1 / M)
K, ALPHA_W = 20, 0.01


def log_binom_tail(n, f, q):
    """log P(X >= f), X ~ Bin(n, q), by exact log-space summation."""
    if f <= 0:
        return 0.0
    lg = lambda k: math.lgamma(n + 1) - math.lgamma(k + 1) - math.lgamma(n - k + 1) + k * math.log(q) + (n - k) * math.log1p(-q)
    terms = [lg(k) for k in range(f, n + 1)]
    m = max(terms)
    return m + math.log(sum(math.exp(t - m) for t in terms))


def triggers(n, f):
    return log_binom_tail(n, f, Q_STAR) <= math.log(ALPHA_W)


def run(q, rng):
    n = f = 0
    for b in range(B):
        for a in range(M):
            n += 1
            fail = rng.random() < q
            f += fail
            if n % K == 0 and triggers(n, f):
                return True, n
            if not fail:
                break
    return False, n


def main():
    rng = random.Random(20260929)
    print(f"Q_STAR = {Q_STAR:.4f} (M={M}, P(all {B} pass) = {LEVEL} at q = Q_STAR); cadence K = {K}; alpha_w = {ALPHA_W}")
    # the smallest f that triggers at a few n (the mechanical table the tool would print)
    for n in (20, 40, 100, 200, 400, 800):
        fmin = next(f for f in range(n + 1) if triggers(n, f))
        print(f"  n = {n:4d}: STOP iff f >= {fmin:4d}  (q̂ >= {fmin / n:.3f})")
    print("true q   P(STOP)   median attempts at STOP   P(all pass | q, M=4)")
    for q in (0.02, 0.05, 0.10, Q_STAR, 0.16, 0.20, 0.30):
        res = [run(q, rng) for _ in range(2000)]
        stops = sorted(n for s, n in res if s)
        med = stops[len(stops) // 2] if stops else None
        print(f"{q:6.3f}   {len(stops) / len(res):7.3f}   {str(med):>8s}              {(1 - q ** M) ** B:.3f}")


if __name__ == "__main__":
    main()
