"""KR-HILBERT-2026-09 — does a Hilbert-space representation SURVIVE KnowledgeOS?  [EXP]

Governing principle (protocol §0):
    DO NOT ASK HILBERT SPACE TO EXPLAIN KNOWLEDGEOS.
    ASK HILBERT SPACE TO SURVIVE KNOWLEDGEOS.

FR-001 is FROZEN and is a TEST CONSTRAINT, not a target to be replaced.
Protocol §4: participation ratio ≠ effective hypothesis complexity — not to be collapsed.
"""
import math
from statistics import NormalDist

# ---- eigenvalues of the correlation matrix, closed form for the tested families
def eigs_equicorr(n, rho):
    return [1+(n-1)*rho] + [1-rho]*(n-1)

def eigs_block(n, groups):
    """n candidates in `groups` blocks of exact duplicates: block size b, ρ=1 within."""
    b = n//groups
    return [float(b)]*groups + [0.0]*(n-groups)

# ---- candidate spectral functionals for "effective complexity"
def participation_ratio(l):
    s1=sum(l); s2=sum(x*x for x in l)
    return (s1*s1)/s2 if s2>0 else 0.0

def spectral_entropy_exp(l):
    s=sum(l); p=[x/s for x in l if x>0]
    H=-sum(x*math.log(x) for x in p)
    return math.exp(H)

def trace_over_max(l):
    return sum(l)/max(l) if max(l)>0 else 0.0

def rank_at(l, tol=1e-9):
    return sum(1 for x in l if x>tol)

def naive_ess(n, rho):
    return n/(1+(n-1)*rho) if rho>0 else n

FUNCTIONALS = {"participation ratio": participation_ratio,
               "exp(spectral entropy)": spectral_entropy_exp,
               "trace/λ_max": trace_over_max,
               "rank": rank_at}

# ---- MEASURED N_eff from KR-NEFF-2026-09-02 (evaluator-side ground truth for this test)
MEASURED = {
 ("independent",1000,0.0,None): 940.6,
 ("grouped",1000,None,200):     201.9,
 ("grouped",1000,None,50):       51.1,
 ("equicorr",1000,0.1,None):    751.8,
 ("equicorr",1000,0.5,None):    200.6,
 ("equicorr",1000,0.9,None):      9.0,
 ("equicorr",1000,0.95,None):     4.5,
}
def spectrum(kind,n,rho,groups):
    if kind=="independent": return [1.0]*n
    if kind=="grouped":     return eigs_block(n,groups)
    return eigs_equicorr(n,rho)

# ---- H8: is ε-distance an equivalence relation?  (FR-001 constraint)
def epsilon_distance_transitive(eps=0.5):
    """Vectors on a line at spacing 0.9ε: adjacent pairs within ε, endpoints not."""
    step=0.9*eps; xs=[i*step for i in range(12)]
    adjacent = all(abs(xs[i+1]-xs[i])<eps for i in range(len(xs)-1))
    endpoints = abs(xs[-1]-xs[0])>=eps
    return dict(eps=eps, step=round(step,4), n=len(xs),
                adjacent_within_eps=adjacent, endpoints_beyond_eps=endpoints,
                transitive=not(adjacent and endpoints))

# ---- H3: orthogonality vs probabilistic independence
def orthogonality_vs_independence():
    """Uncorrelated ⇏ independent for non-Gaussian variables.
    Witness: X ~ Uniform{-1,0,1}, Y = 1 if X==0 else 0.  Cov = 0, but Y is a function of X."""
    import itertools
    dom=[-1,0,1]; p=1/3
    EX=sum(x*p for x in dom); EY=sum((1 if x==0 else 0)*p for x in dom)
    EXY=sum(x*(1 if x==0 else 0)*p for x in dom)
    cov=EXY-EX*EY
    # dependence: P(Y=1|X=0)=1 != P(Y=1)=1/3
    dependent = abs(1.0 - EY) > 1e-9
    return dict(cov=round(cov,12), orthogonal=abs(cov)<1e-12,
                probabilistically_independent=not dependent,
                witness="X~U{-1,0,1}, Y=1[X=0]")
