"""Step 279 reference implementation — faithful to the revised execution specification.
Components 1-15. No fallbacks, no silent repair, no implicit boolean conversion."""
from dataclasses import dataclass, field
from typing import Optional, Tuple, FrozenSet, Dict, List, Callable, Any
import hashlib, json

# ---------- Result domain (279 §6) : NO implicit boolean conversion ----------
PASS, DENY, CONDITIONAL, UNKNOWN, CONFLICT, INDETERMINATE = \
    "PASS","DENY","CONDITIONAL","UNKNOWN","CONFLICT","INDETERMINATE"
PRECEDENCE = [DENY, CONFLICT, CONDITIONAL, UNKNOWN, PASS]          # 279 §7

@dataclass(frozen=True)
class Verdict:
    value: str
    reason: str = ""
    conditional: Optional["Conditional"] = None
    def __repr__(self): return f"{self.value}({self.reason})" if self.reason else self.value

@dataclass(frozen=True)
class Conditional:                                                  # 279 §8  (R,S,M,ρ)
    required: FrozenSet[str]; satisfied: FrozenSet[str]
    missing: FrozenSet[str];  strategy: str                         # WAIT|REQUEST|ESCALATE|REASSESS|BLOCK
STRATEGIES = {"WAIT","REQUEST","ESCALATE","REASSESS","BLOCK"}

# ---------- Rule schema (279 §6) ----------
@dataclass(frozen=True)
class Rule:
    id: str
    predicate: Callable[[Dict], Verdict]
    applicability: Callable[[Dict], bool] = lambda ctx: True
    evidence: Tuple[str,...] = ()
    authority: Optional[str] = None
    parameters: Tuple[Tuple[str,Any],...] = ()
    dependencies: Tuple[str,...] = ()
    inputs: Tuple[str,...] = ()          # declared input keys — used by F13

def EvaluateRule(K,E,C,A,pi,r) -> Verdict:                          # 279 §6
    ctx = {"K":K,"E":E,"C":C,"A":A,"pi":pi}
    if not r.applicability(ctx): return Verdict(UNKNOWN,"NotApplicable")
    v = r.predicate(ctx)
    if not isinstance(v,Verdict): raise TypeError(f"rule {r.id} returned non-Verdict {type(v)}")
    if v.value not in (PASS,DENY,CONDITIONAL,UNKNOWN,CONFLICT):
        raise ValueError(f"rule {r.id} returned {v.value} outside declared domain")
    return v

# ---------- Combination algebra (279 §7) ----------
def Combine(results: List[Verdict]) -> Verdict:
    if not results: return Verdict(INDETERMINATE,"NoResults")
    if any(v.value==DENY for v in results):
        return Verdict(DENY, ";".join(v.reason for v in results if v.value==DENY))
    if any(v.value==CONFLICT for v in results):
        return Verdict(CONFLICT, ";".join(v.reason for v in results if v.value==CONFLICT))
    conds=[v for v in results if v.value==CONDITIONAL]
    if conds:
        R=frozenset().union(*[c.conditional.required for c in conds])
        S=frozenset().union(*[c.conditional.satisfied for c in conds])
        M=frozenset().union(*[c.conditional.missing for c in conds])
        strat=next((c.conditional.strategy for c in conds if c.conditional.strategy=="BLOCK"),
                   conds[0].conditional.strategy)
        return Verdict(CONDITIONAL,"combined",Conditional(R,S,M,strat))
    if any(v.value==UNKNOWN for v in results):
        return Verdict(UNKNOWN, ";".join(v.reason for v in results if v.value==UNKNOWN))
    if all(v.value==PASS for v in results): return Verdict(PASS)
    return Verdict(INDETERMINATE,"UncoveredCombination")

# ---------- Policy schema (279 §5) ----------
class PolicyError(Exception): pass

@dataclass(frozen=True)
class Policy:
    id: str; version: str
    rules: Tuple[Rule,...]
    parameters: Tuple[Tuple[str,Any],...]
    authority: Optional[str]
    validity: Tuple[str,Optional[str]]          # [t_s, t_e)
    scope: FrozenSet[str]
    metadata: Tuple[Tuple[str,str],...] = ()
    activated: bool = False
    def __post_init__(self):
        if self.authority is None: raise PolicyError("missing authority")
        ts,te=self.validity
        if te is not None and te<=ts: raise PolicyError("malformed validity interval")
        if not self.scope: raise PolicyError("inconsistent scope: empty")
        ids=[r.id for r in self.rules]
        if len(ids)!=len(set(ids)): raise PolicyError("invalid rules: duplicate rule id")
    @property
    def PID(self) -> str:                                            # 279 §11
        c=json.dumps({"id":self.id,"version":self.version,
                      "rules":sorted(r.id for r in self.rules),
                      "parameters":sorted(self.parameters),
                      "authority":self.authority,"validity":list(self.validity),
                      "scope":sorted(self.scope)},sort_keys=True)
        return hashlib.sha256(c.encode()).hexdigest()[:16]

def Applicable(pi:Policy,t:str)->bool:                               # 279 §12
    ts,te=pi.validity
    return ts<=t and (te is None or t<te)

def EvaluatePolicy(pi,K,E,C,A)->Verdict:
    return Combine([EvaluateRule(K,E,C,A,pi,r) for r in pi.rules])

# ---------- Authority / Authorization (279 §9, §10) ----------
@dataclass(frozen=True)
class Authority:
    id: str; subject: str
    scope: FrozenSet[str]; jurisdiction: FrozenSet[str]
    validity: Tuple[str,Optional[str]]
    human_act_ref: str                    # "the mechanism records authority; it does not grant it"
    revoked_at: Optional[str] = None
    def active_at(self,t)->bool:
        ts,te=self.validity
        if not(ts<=t and (te is None or t<te)): return False
        return not(self.revoked_at is not None and t>=self.revoked_at)

@dataclass(frozen=True)
class Operation:
    id: str; scope: FrozenSet[str]; jurisdiction: FrozenSet[str]; kind: str="assert"

def Authorize(auths:List[Authority], o:Operation, pi:Optional[Policy], t:str,
              precedence:Optional[Dict[str,int]]=None)->Verdict:      # 279 §9,§10
    if not auths: return Verdict(DENY,"NoAuthority")
    active=[a for a in auths if a.active_at(t)]
    if not active: return Verdict(DENY,"NoAuthority")
    ok=[a for a in active if o.scope<=a.scope and o.jurisdiction<=a.jurisdiction]
    bad=[a for a in active if a not in ok]
    if ok and bad:                                                    # F4: conflicting determinations
        if precedence:
            best=max(ok+bad,key=lambda a:precedence.get(a.id,0))
            return Verdict(PASS,f"PrecedenceResolved:{best.id}") if best in ok \
                   else Verdict(DENY,f"PrecedenceResolved:{best.id}")
        return Verdict(DENY,"AuthorityConflict")
    if not ok: return Verdict(DENY,"ScopeOrJurisdictionExceeded")
    return Verdict(PASS,f"Permit:{ok[0].id}")

# ---------- Historical policy store / supersession / overlap (279 §13,§14,§15) ----------
class PolicyStore:
    def __init__(self): self._h:List[Policy]=[]; self._sup:List[Tuple[str,str]]=[]; self.audit=[]
    def activate(self,pi:Policy):
        for p in self._h:
            if p.PID==pi.PID: raise PolicyError("duplicate version identity")
        object.__setattr__(pi,"activated",True)
        self._h.append(pi); self.audit.append(("activate",pi.id,pi.version,pi.PID))
    def supersede(self,new:Policy,old:Policy): self._sup.append((new.PID,old.PID))
    def history(self)->List[Policy]: return list(self._h)            # never overwritten
    def PolicyAt(self,t:str)->Verdict:                               # 279 §13,§16
        c=[p for p in self._h if Applicable(p,t)]
        if not c: return Verdict(UNKNOWN,"NoApplicablePolicy")
        if len(c)>1: return Verdict(CONFLICT,"OverlappingPolicies:"+",".join(p.version for p in c))
        return Verdict(PASS,c[0].version)
    def get(self,t:str)->Optional[Policy]:
        c=[p for p in self._h if Applicable(p,t)]
        return c[0] if len(c)==1 else None

def ClassifyOverlap(p1:Policy,p2:Policy,precedence=False,layered=False)->str:  # 279 §15
    def ov(a,b):
        (a1,a2),(b1,b2)=a.validity,b.validity
        a2=a2 or "9999"; b2=b2 or "9999"
        return a1<b2 and b1<a2
    if not ov(p1,p2) or not (p1.scope & p2.scope): return "DISJOINT"
    if precedence: return "PRECEDENCE"
    if layered:    return "LAYERED"
    return "CONFLICT"
