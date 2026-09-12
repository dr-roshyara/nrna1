"""KR-NEFF-2026-09-02 — Effective hypothesis complexity.  [EXP]

Commissioned question:
  What is the mathematically relevant complexity of a hypothesis space, and how does that
  complexity alter admissibility, evidence burden, validation and determination?

Tests whether |H| or some N_eff predicts the ACTUAL multiplicity burden.
Does NOT introduce candidate_count into the theory.
"""
import random, math, statistics as st
from statistics import NormalDist
N=NormalDist()

# ---------------------------------------------------------------- spaces with equal |H|, different structure
def make_space(kind, n, rho=0.0, groups=None):
    """Return (labels, group_id per candidate, within-group correlation)."""
    if kind=="independent":       return [f"H{i}" for i in range(n)], list(range(n)), 0.0
    if kind=="grouped":
        g=groups or max(1,n//20)
        return [f"H{i}" for i in range(n)], [i%g for i in range(n)], 1.0   # exact duplicates in a group
    if kind=="correlated":
        return [f"H{i}" for i in range(n)], list(range(n)), rho            # equicorrelated
    raise ValueError(kind)

def draw_scores(gid, rho, rng, sigma):
    """Equicorrelated / grouped scores under the NULL (no candidate has an advantage)."""
    n=len(gid)
    if rho>=1.0:                                  # grouped: identical within a group
        gs={g: rng.gauss(0,sigma) for g in set(gid)}
        return [gs[g] for g in gid]
    if rho<=0.0:
        return [rng.gauss(0,sigma) for _ in range(n)]
    common=rng.gauss(0,sigma*math.sqrt(rho))      # equicorrelation via a shared component
    return [common + rng.gauss(0,sigma*math.sqrt(1-rho)) for _ in range(n)]

# ---------------------------------------------------------------- the empirical burden
def empirical_neff(kind, n, rho=0.0, groups=None, trials=4000, alpha=0.05, sigma=1.0, seed=5):
    """The multiplicity burden MEASURED: the τ that yields a family-wise false-admit rate of α
    under the NULL. Then invert Bonferroni to read off the implied N_eff."""
    rng=random.Random(seed)
    labels,gid,r = make_space(kind,n,rho,groups)
    maxes=[max(draw_scores(gid,r,rng,sigma)) for _ in range(trials)]
    maxes.sort()
    tau = maxes[int((1-alpha)*trials)]            # empirical (1-α) quantile of the max
    z   = tau/sigma
    p_single = 1-N.cdf(z)
    n_eff = alpha/p_single if p_single>0 else float("inf")
    return dict(kind=kind, n=n, rho=r, groups=len(set(gid)),
                tau_empirical=round(tau,4), z=round(z,4),
                N_eff=round(n_eff,1), N_eff_over_n=round(n_eff/n,4))
