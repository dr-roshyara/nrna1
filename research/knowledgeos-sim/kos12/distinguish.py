"""KR-DIST-2026-09-02 — Can effective complexity be defined through DISTINGUISHABILITY? [EXP]

Commissioned question:
  Can effective hypothesis complexity be defined through distinguishability/equivalence of
  hypotheses under a specified evidence-and-observation regime Λ?

  H_i ~_Λ H_j   iff  indistinguishable w.r.t. the observables relevant to Λ
  candidate:    effective complexity ≈ |H / ~_Λ|

This connects N_eff to the still-open ≡_sem. Tested, not assumed.
"""
import math, itertools, random

# ---------------------------------------------------------------- the regime
class Regime:
    """Λ = (m observations, σ noise, resolution δ). δ is what the regime can RESOLVE."""
    def __init__(self, m, sigma=1.0, alpha=0.05):
        self.m, self.sigma, self.alpha = m, sigma, alpha
        self.se = sigma/math.sqrt(m)
    def resolvable(self, sd_diff):
        """Two hypotheses are DISTINGUISHABLE if their score difference has sd above what the
        regime can resolve at level α (a two-sample z-style criterion)."""
        from statistics import NormalDist
        return sd_diff > NormalDist().inv_cdf(1-self.alpha/2)*self.se

# ---------------------------------------------------------------- pairwise distinguishability
def sd_diff_equicorr(sigma, rho):    return math.sqrt(2*sigma**2*(1-rho))
def sd_diff_grouped(sigma, same):    return 0.0 if same else math.sqrt(2)*sigma

def indistinguishable_matrix(kind, n, rho, groups, Lam):
    """Boolean matrix: True = INDISTINGUISHABLE under Λ."""
    M=[[False]*n for _ in range(n)]
    for i in range(n):
        for j in range(n):
            if i==j: M[i][j]=True; continue
            if kind=="grouped":
                sd=sd_diff_grouped(Lam.sigma, (i%groups)==(j%groups))
            else:
                sd=sd_diff_equicorr(Lam.sigma, rho)
            M[i][j] = not Lam.resolvable(sd)
    return M

# ---------------------------------------------------------------- IS IT AN EQUIVALENCE RELATION?
def transitivity_witness(kind, n, rho, groups, Lam):
    """Search for i~j, j~k, but NOT i~k. If found, ~_Λ is NOT transitive and H/~ is ill-defined."""
    M=indistinguishable_matrix(kind,n,rho,groups,Lam)
    for i,j,k in itertools.permutations(range(min(n,12)),3):
        if M[i][j] and M[j][k] and not M[i][k]:
            return (i,j,k)
    return None

def chain_witness(sigma, Lam, step_frac=0.9, length=12):
    """The classic construction: a CHAIN of pairwise-indistinguishable hypotheses whose
    endpoints ARE distinguishable. Means spaced just below the resolution limit."""
    from statistics import NormalDist
    delta = NormalDist().inv_cdf(1-Lam.alpha/2)*Lam.se
    step  = delta*step_frac                       # each neighbour pair is indistinguishable
    mus   = [i*step for i in range(length)]
    near  = not Lam.resolvable(abs(mus[1]-mus[0])/math.sqrt(2)*math.sqrt(2))
    ends  = Lam.resolvable(abs(mus[-1]-mus[0])/math.sqrt(2)*math.sqrt(2))
    return dict(delta=round(delta,4), step=round(step,4), n=length,
                neighbours_indistinguishable=near, endpoints_distinguishable=ends,
                span=round(mus[-1]-mus[0],4))

# ---------------------------------------------------------------- packing number (the repair)
def packing_number(span, Lam):
    """If ~ is not transitive, the quotient is ill-defined. A δ-PACKING number is well-defined:
    the largest set of pairwise-distinguishable hypotheses."""
    from statistics import NormalDist
    delta = NormalDist().inv_cdf(1-Lam.alpha/2)*Lam.se*math.sqrt(2)
    return max(1, int(span/delta)+1)
