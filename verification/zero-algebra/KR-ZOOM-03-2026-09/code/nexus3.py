r"""KR-ZOOM-03 — the cross-dimension counterfactual, with a REAL intervention.

FROZEN CONTRACT (KR-ZOOM-03-PREREGISTRATION-2026-09.md §E.2, ratified before execution):

    Determine  <=>  EXISTS! h in A(E) :
        terminal(h|E)                            (a) investigation bottomed out
      & support(h|E) >= tau_s = 1                (b) supported
      & |A(E) \ {h}| >= 1                        (c) A RIVAL WAS ACTUALLY ASSESSED
      & forall h' in A(E)\{h}: sup(h)-sup(h') >= tau_m = 1   (d) every assessed rival beaten

    A(E) = the ASSESSED candidate set -- what the investigation actually encountered.
    Exclusion is over A(E), NEVER over the generator's inventory.
    Condition (c) is the owner's caveat: you cannot exclude what you never considered.

    Zero_Q(d | E)  <=>  Determine(Q | K, E) == Determine(Q | E^-_d(K), E)
    ZERO-FLIP(d)   <=>  Zero at E0  AND  NOT Zero at E1
"""
import random
from dataclasses import dataclass
from typing import Dict, List, Tuple, Set

DIMENSIONS = ("repositories","users","storage","network","cpu_ram","backups","security",
              "traffic","ci_cd","config","monitoring","external_api","scheduled_jobs","auth")
TAU_S, TAU_M = 1, 1

@dataclass(frozen=True)
class Link:
    src: Tuple[str,str]; dst: Tuple[str,str]

@dataclass(frozen=True)
class Case:
    anchor: Tuple[str,str]
    links: Tuple[Link,...]          # ALL links: true chain + decoy chains, indistinguishable a priori
    root: Tuple[str,str]            # ground truth
    decoy_termini: Tuple[Tuple[str,str],...]
    chain_dims: Tuple[str,...]
    decoy_dims: Tuple[str,...]

def _f(rng, d): return (d, f"{d}_f{rng.randint(0,3)}")

def generate(seed:int, n:int, s_true=(2,3), s_dec=(1,2), n_decoy=(2,3), chain=(2,4)) -> List[Case]:
    """DECLARED PRECONDITION (pre-reg §D.3/E): decoys emit CHAINS with competing support, so
    condition (d) cannot be satisfied by the mere absence of rivals."""
    rng = random.Random(seed); out=[]
    for _ in range(n):
        anchor = ("network","network_egress")
        links=[]; cur=anchor; cdims=[]
        for _ in range(rng.randint(*chain)):
            nd = rng.choice([d for d in DIMENSIONS if d!=cur[0]])
            nxt=_f(rng,nd); links.append(Link(cur,nxt)); cur=nxt; cdims.append(nd)
        root=cur
        # CALIBRATION FIX (pre-analysis, declared): converging support must originate from nodes
        # the investigation can actually REACH, or the link is never acquired and the evidence
        # is invisible. The first draft drew sources at random -> acquired support was always 1.
        path=[anchor]+[l.dst for l in links]
        for _ in range(rng.randint(*s_true)-1):
            links.append(Link(rng.choice(path), root))
        # decoy CHAINS, same shape, with their own converging support
        dterm=[]; ddims=[]
        for _ in range(rng.randint(*n_decoy)):
            cur2=anchor
            for _ in range(rng.randint(1,3)):
                nd=rng.choice([d for d in DIMENSIONS if d!=cur2[0]])
                nxt=_f(rng,nd); links.append(Link(cur2,nxt)); cur2=nxt; ddims.append(nd)
            if cur2!=root:
                dterm.append(cur2)
                path2=[anchor]+[l.dst for l in links]
                for _ in range(rng.randint(*s_dec)-1):
                    links.append(Link(rng.choice(path2), cur2))
        out.append(Case(anchor, tuple(links), root, tuple(dterm), tuple(cdims), tuple(ddims)))
    return out

# ---------------------------------------------------------------- typed elimination
def eliminate_dim(c: Case, dim: str) -> Case:
    """E^-_d(K): the dimension is removed FROM THE KNOWLEDGE STATE. Every link touching it
    becomes unavailable -- the evidence it carried no longer exists."""
    keep = tuple(l for l in c.links if l.src[0]!=dim and l.dst[0]!=dim)
    return Case(c.anchor, keep, c.root, c.decoy_termini, c.chain_dims, c.decoy_dims)

# ---------------------------------------------------------------- inquiry-zoom investigation
def investigate(c: Case, budget:int, seed:int):
    """Inquiry-zoom: focus on the anchor, expand freely, never delete. Returns acquired E."""
    rng=random.Random(seed); E=[]; frontier=[c.anchor]; seen=set(); probes=0
    while frontier and probes<budget:
        rng.shuffle(frontier); node=frontier.pop()
        if node in seen: continue
        seen.add(node); probes+=1
        for l in c.links:
            if l.src==node:
                E.append(l); frontier.append(l.dst)
    return tuple(E)

# ---------------------------------------------------------------- the frozen contract
def assessed(E) -> Set[Tuple[str,str]]:
    return {l.dst for l in E}

def support(E, h) -> int:
    return sum(1 for l in E if l.dst==h)

def terminal(E, h) -> bool:
    return not any(l.src==h for l in E)

def determine_opt3(E):
    """PRIMARY, frozen. Returns the determined h, or None."""
    A = assessed(E)
    if len(A) < 2: return None                       # (c) no rival was assessed -> NOT DETERMINED
    winners=[]
    for h in A:
        if not terminal(E,h): continue               # (a)
        s=support(E,h)
        if s < TAU_S: continue                       # (b)
        if all(s-support(E,h2) >= TAU_M for h2 in A if h2!=h):   # (d) beats EVERY assessed rival
            winners.append(h)
    return winners[0] if len(winners)==1 else None    # EXISTS! -- uniqueness required

def determine_opt1(E):
    """SECONDARY, reported only. Existence: any supported explanation."""
    A=assessed(E)
    for h in A:
        if support(E,h)>0: return h
    return None
