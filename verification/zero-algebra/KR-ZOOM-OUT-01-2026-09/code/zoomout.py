r"""KR-ZOOM-OUT-01 — domain, operators, and the frozen measurements.

RUN STRICTLY AGAINST KR-ZOOM-OUT-01-PREREGISTRATION-2026-09.md (RATIFIED, FROZEN 2026-09-05).
Nothing below may be tuned after seeing outcomes: estimands, directionality, thresholds, the
Determine contract, the observable family O, classification rules, control design.

    Avail(o,K)   the observable can still be EVALUATED under the declared contract
    Value(o,K)   the value returned, when Avail
    K <=_O K'    forall o: Avail(o,K) => Avail(o,K')          ANSWERABILITY, not answers
    K ~=_O K'    <=_O both ways AND all available Values agree

ZOOM-OUT IS NOT HARD-CODED TO A CATEGORY. Whether it adds, replaces, consolidates or retracts is
decided by the DATA (does the determination cover the superseded subjects?), so M0/M1/M2/M3a/M3b
are all REACHABLE -- which control O-F requires before any metric may be reported.
"""
import random
from dataclasses import dataclass
from typing import Dict, Tuple, Optional, FrozenSet

DIMS = ("repositories","users","storage","network","cpu_ram","backups","security",
        "traffic","ci_cd","config","monitoring","external_api","scheduled_jobs","auth")
SUBJ = ("health","load","policy","owner","trend")
ANOMALY = ("network","egress_cause")          # the Nexus 70 GB/day attribution
TAU_S = TAU_M = 1

@dataclass(frozen=True)
class Claim:
    dim:str; subj:str; value:str; detail:bool=False

@dataclass(frozen=True)
class State:
    claims: Tuple[Claim,...]
    def key(self): return frozenset((c.dim,c.subj,c.value) for c in self.claims)

@dataclass(frozen=True)
class Case:
    K0: State
    links: Tuple[Tuple[Tuple[str,str],Tuple[str,str]],...]   # (src,dst) evidence graph
    root: Tuple[str,str]
    covered: FrozenSet[str]        # subjects the determination CAN still answer after consolidation
    detail_subjects: Tuple[str,...]

# ---------------------------------------------------------------- generator
def generate(seed:int, n:int, chain=(3,4), s_true=(3,4), s_dec=(1,2), n_dec=(2,2),
             p_prior_wrong=0.5, p_cover=0.6, n_detail=(2,4)) -> list:
    rng=random.Random(seed); out=[]
    for _ in range(n):
        claims=[]
        for d in rng.sample(DIMS,8):
            for s in rng.sample(SUBJ, rng.randint(2,3)):
                claims.append(Claim(d,s,f"v{rng.randint(0,3)}"))
        # the prior attribution of the anomaly -- sometimes WRONG, sometimes unknown
        prior = rng.choice(DIMS) if rng.random()<p_prior_wrong else "unknown"
        claims.append(Claim(ANOMALY[0],ANOMALY[1],prior))
        # evidence graph: true chain + decoy chains (KR-ZOOM-03 shape, frozen)
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
        # DETAIL claims about the root dimension, which a determination may consolidate
        dsub=tuple(rng.sample(SUBJ, rng.randint(*n_detail)))
        for s in dsub: claims.append(Claim(root[0],s,f"d{rng.randint(0,3)}",detail=True))
        covered=frozenset(s for s in dsub if rng.random()<p_cover)
        out.append(Case(State(tuple(claims)), tuple(links), root, covered, dsub))
    return out

# ---------------------------------------------------------------- investigation (KR-ZOOM-02/03)
def investigate(c:Case, budget:int, seed:int):
    rng=random.Random(seed); E=[]; frontier=[("network","network_egress")]; seen=set(); p=0
    while frontier and p<budget:
        rng.shuffle(frontier); nd=frontier.pop()
        if nd in seen: continue
        seen.add(nd); p+=1
        for (a,b) in c.links:
            if a==nd: E.append((a,b)); frontier.append(b)
    return tuple(E)

# ------------------------------- FROZEN Determine contract (KR-ZOOM-03 Option 3, verbatim)
def determine(E):
    A={b for _,b in E}
    if len(A)<2: return None                                  # (c) a rival must be assessed
    sup={h:sum(1 for _,b in E if b==h) for h in A}
    term={h for h in A if not any(a==h for a,_ in E)}
    win=[h for h in A if h in term and sup[h]>=TAU_S
         and all(sup[h]-sup[h2]>=TAU_M for h2 in A if h2!=h)]
    return win[0] if len(win)==1 else None

# ---------------------------------------------------------------- ZOOM-OUT
def zoom_out(c:Case, D:Optional[Tuple[str,str]]) -> State:
    """Return to the broader context carrying what the investigation produced.
    NOT hard-coded to a category -- the DATA decide:
      * no determination                      -> unchanged
      * determination, no conflicting prior   -> pure addition
      * determination, conflicting prior      -> supersede the attribution (value changes)
      * consolidation of detail claims        -> covered subjects survive as a summary,
                                                 UNCOVERED subjects are RETRACTED (loss is possible)
    """
    if D is None: return c.K0
    keep=[]
    for cl in c.K0.claims:
        if (cl.dim,cl.subj)==ANOMALY: continue                      # superseded attribution
        if cl.detail and cl.dim==D[0] and cl.subj not in c.covered: continue   # RETRACTED
        if cl.detail and cl.dim==D[0] and cl.subj in c.covered: continue       # consolidated
        keep.append(cl)
    keep.append(Claim(ANOMALY[0],ANOMALY[1],D[0]))                  # the determination
    for s in sorted(c.covered):                                     # the summary that still answers
        keep.append(Claim(D[0],s,"summary"))
    return State(tuple(keep))

# ---------------------------------------------------------------- Avail / Value / relations
def observables(K:State): return {(c.dim,c.subj) for c in K.claims}
def avail(o,K): return o in observables(K)
def value(o,K):
    for c in K.claims:
        if (c.dim,c.subj)==o: return c.value
    return None
def preceq(K,K2,O): return all(avail(o,K2) for o in O if avail(o,K))
def cong(K,K2,O):
    return (preceq(K,K2,O) and preceq(K2,K,O)
            and all(value(o,K)==value(o,K2) for o in O if avail(o,K)))
def value_changed(K,K2,O):
    return any(avail(o,K) and avail(o,K2) and value(o,K)!=value(o,K2) for o in O)

def delta_triple(K,K2):
    a={(c.dim,c.subj):c.value for c in K.claims}; b={(c.dim,c.subj):c.value for c in K2.claims}
    minus={k for k in a if k not in b}
    plus ={k for k in b if k not in a}
    circ ={k for k in a if k in b and a[k]!=b[k]}     # transformed / qualified
    return minus,circ,plus

# ---------------------------------------------------------------- classification (frozen)
def classify(K,K2,O):
    p = preceq(K,K2,O); c_ = cong(K,K2,O)
    _,circ,_ = delta_triple(K,K2)
    n0,n1 = len(K.claims), len(K2.claims)
    if not p:            return "M0"                       # answerability destroyed
    if c_:               return "M1"                       # observably unchanged
    if n1 < n0:          return "M2"                       # detail falls, distinctions kept
    if n1 > n0:          return "M3b" if circ else "M3a"
    return "MX"
