"""K / Assertion / Sigma / T layer — the canonical theory under test."""
from dataclasses import dataclass, replace
from typing import Optional, Tuple, FrozenSet
import hashlib
from collections import defaultdict

@dataclass(frozen=True)
class Dimension:
    name:str; space:Tuple[str,...]; scale:str
    def admits(self,v): return v in self.space
@dataclass(frozen=True)
class Prop:
    E:str; D:Dimension; V:str
    def well_formed(self): return self.D.admits(self.V)
@dataclass(frozen=True)
class Observation: sensor:str; at:str; reading:str
@dataclass(frozen=True)
class Evidence:
    obs:Observation; polarity:str; state:str; source:str; method:str; provenance:str
    @property
    def ref(self): return hashlib.sha256(f"{self.obs.sensor}|{self.obs.at}|{self.obs.reading}|{self.source}".encode()).hexdigest()[:8]
@dataclass(frozen=True)
class Iv: vf:str; vt:Optional[str]
@dataclass(frozen=True)
class Assertion:
    P:Prop; e:FrozenSet[Evidence]; c:str; t:Iv; Pi:str
    @property
    def id(self):
        return hashlib.sha256("|".join([self.P.E,self.P.D.name,self.P.V,
            ",".join(sorted(f"{x.ref}:{x.polarity}:{x.state}" for x in self.e)),
            self.c,self.t.vf,self.Pi]).encode()).hexdigest()[:10]
@dataclass(frozen=True)
class K:
    A:FrozenSet[Assertion]=frozenset(); R:FrozenSet[Tuple[str,str,str]]=frozenset()
EMPTY=K()
DAGS={"supersedes","refines","derived_from"}
def ids(k): return {a.id for a in k.A}

# ---- Qualification (279/278: policy-parametric) ----
def Qualify(obs, policy_params):
    trusted=policy_params.get("trusted_sources",())
    src=policy_params.get("source_of",{}).get(obs.sensor,obs.sensor)
    if trusted and src not in trusted: return None,"UnqualifiedSource"
    pol=policy_params.get("polarity_of",{}).get(obs.reading,"supports")
    return Evidence(obs,pol,"active",src,policy_params.get("method","direct"),src),"ok"

# ---- Assessment -> Sigma ----
ORD=["None","Weak","Moderate","Strong","VeryStrong"]
def Sigma(a, policy_params=None):
    pp=policy_params or {}
    act=[x for x in a.e if x.state=="active"]
    if pp.get("require_trusted"):
        act=[x for x in act if x.source in pp.get("trusted_sources",())]
    sup=[x for x in act if x.polarity=="supports"]; con=[x for x in act if x.polarity=="contradicts"]
    if sup and con: return ("Contested",ORD[min(max(len(sup),len(con)),4)])
    if sup: return ("Supporting",ORD[min(len(sup),4)])
    if con: return ("Refuting",ORD[min(len(con),4)])
    return ("Neutral","None")
def conflictsWith(a):
    act=[x for x in a.e if x.state=="active"]
    return any(x.polarity=="supports" for x in act) and any(x.polarity=="contradicts" for x in act)

# ---- Relations / contradiction ----
def overlap(x,y):
    xe=x.vt or "9999"; ye=y.vt or "9999"; return x.t.vf<ye and y.t.vf<xe
def contradicts(k,x,y):
    if (x.P.E,x.P.D.name)!=(y.P.E,y.P.D.name) or x.P.V==y.P.V or x.c!=y.c: return False
    xe=x.t.vt or "9999"; ye=y.t.vt or "9999"
    if not (x.t.vf<ye and y.t.vf<xe): return False
    expl={(f,t) for f,t,ty in k.R if ty in ("supersedes","refines")}
    return not((x.id,y.id) in expl or (y.id,x.id) in expl)

# ---- Validity ----
def acyclic(k,fam):
    g=defaultdict(list)
    for f,t,ty in k.R:
        if ty==fam: g[f].append(t)
    W,D,bad=set(),set(),[]
    def dfs(n):
        if n in D: return
        if n in W: bad.append(n); return
        W.add(n)
        for m in g[n]: dfs(m)
        W.discard(n); D.add(n)
    for n in list(g): dfs(n)
    return not bad
def StructuralValid(k):
    seen={}
    for a in k.A:
        if a.id in seen and seen[a.id]!=a: return False,"id collision"
        seen[a.id]=a
    for f,t,_ in k.R:
        if f not in ids(k) or t not in ids(k): return False,f"dangling {f}->{t}"
    for a in k.A:
        if a.t.vt and a.t.vt<a.t.vf: return False,"inverted interval"
    for fam in DAGS:
        if not acyclic(k,fam): return False,f"cycle in {fam}"
    return True,"ok"
def SemanticallyValid(k):
    ok,w=StructuralValid(k)
    if not ok: return False,w
    for a in k.A:
        if not a.P.well_formed(): return False,f"V not in V_D: {a.P.V}"
    return True,"ok"

# ---- Transformation ----
def delta(k,op,arg):
    if op=="assert":
        if not arg.P.well_formed(): return k,"REJECTED(structure): V not in V_D"
        return K(k.A|{arg},k.R),"ok"
    if op=="relate":
        f,t,ty=arg
        if f not in ids(k) or t not in ids(k): return k,"REJECTED(structure): endpoint"
        k2=K(k.A,K and k.R|{arg})
        if ty in DAGS and not acyclic(k2,ty): return k,f"REJECTED(structure): cycle in {ty}"
        return k2,"ok"
    if op=="retract":
        return K(k.A-{arg},frozenset(r for r in k.R if r[0]!=arg.id and r[1]!=arg.id)),"ok"
    return k,"REJECTED(structure): unknown op"
def Replay(H):
    k=EMPTY
    for op,arg in H: k,_=delta(k,op,arg)
    return k
def Lineage(k,aid):
    out=set(); frontier=[aid]
    while frontier:
        n=frontier.pop()
        for f,t,ty in k.R:
            if f==n and ty in ("derived_from","refines") and t not in out:
                out.add(t); frontier.append(t)
    return out
