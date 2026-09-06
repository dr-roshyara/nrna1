r"""KR-ZOOM-OUT-03 — generator with CLAIM-STRUCTURE parameters exposed.

Why this file exists: `KR-ZOOM-OUT-02` was STOPPED by its own calibration gate because its grid
contained no parameter the gated quantity was sensitive to. The claim-structure parameters were
hard-coded inside KR-ZOOM-OUT-01's generator:

    rng.sample(DIMS, 8)                 -> n_dims          FIXED at 8
    rng.sample(SUBJ, rng.randint(2,3))  -> subjects_per_dim FIXED at 2-3
    f"v{rng.randint(0,3)}"              -> value_alphabet   FIXED at 4

Those three decide how often a unique (dim, value) pair beats every rival by tau_m, i.e. they
decide Determine(Q_broad). They are now parameters.

KR-ZOOM-OUT-01's `zoomout.py` is NOT modified -- it belongs to a completed experiment.
Everything else (investigate / determine / zoom_out / classify) is imported from it unchanged.
"""
import os, sys, random
sys.path.insert(0, os.path.join(os.path.dirname(os.path.dirname(os.path.dirname(
    os.path.abspath(__file__)))), "KR-ZOOM-OUT-01-2026-09", "code"))
from zoomout import Claim, State, Case, DIMS, SUBJ, ANOMALY, TAU_S

def generate(seed, n, *, n_dims=8, subjects=(2,3), value_alphabet=4,
             chain=(3,4), s_true=(3,4), s_dec=(1,2), n_dec=(2,2),
             p_prior_wrong=0.5, p_cover=0.6, n_detail=(2,4)):
    rng=random.Random(seed); out=[]
    for _ in range(n):
        claims=[]
        for d in rng.sample(DIMS, min(n_dims, len(DIMS))):
            for s in rng.sample(SUBJ, min(rng.randint(*subjects), len(SUBJ))):
                claims.append(Claim(d, s, f"v{rng.randint(0, value_alphabet-1)}"))
        prior = rng.choice(DIMS) if rng.random()<p_prior_wrong else "unknown"
        claims.append(Claim(ANOMALY[0], ANOMALY[1], prior))
        links=[]; cur=("network","network_egress"); path=[cur]
        for _ in range(rng.randint(*chain)):
            nd=rng.choice([d for d in DIMS if d!=cur[0]]); nxt=(nd,f"{nd}_f{rng.randint(0,3)}")
            links.append((cur,nxt)); cur=nxt; path.append(cur)
        root=cur
        for _ in range(rng.randint(*s_true)-1): links.append((rng.choice(path),root))
        for _ in range(rng.randint(*n_dec)):
            c2=("network","network_egress"); p2=[c2]
            for _ in range(rng.randint(1,3)):
                nd=rng.choice([d for d in DIMS if d!=c2[0]]); nx=(nd,f"{nd}_f{rng.randint(0,3)}")
                links.append((c2,nx)); c2=nx; p2.append(c2)
            if c2!=root:
                for _ in range(rng.randint(*s_dec)-1): links.append((rng.choice(p2+path),c2))
        dsub=tuple(rng.sample(SUBJ, min(rng.randint(*n_detail), len(SUBJ))))
        for s in dsub: claims.append(Claim(root[0], s, f"d{rng.randint(0,3)}", detail=True))
        covered=frozenset(s for s in dsub if rng.random()<p_cover)
        out.append(Case(State(tuple(claims)), tuple(links), root, covered, dsub))
    return out

def det_broad(K, tau_m=1):
    E=tuple((("nexus","state"),(c.dim,c.value)) for c in K.claims)
    A={b for _,b in E}
    if len(A)<2: return None
    sup={h:sum(1 for _,b in E if b==h) for h in A}
    term={h for h in A if not any(a==h for a,_ in E)}
    win=[h for h in A if h in term and sup[h]>=TAU_S
         and all(sup[h]-sup[h2]>=tau_m for h2 in A if h2!=h)]
    return win[0] if len(win)==1 else None
