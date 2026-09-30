
from dataclasses import dataclass
from enum import Enum
from typing import Tuple, Any

class Kind(str, Enum):
    LOGICAL="LOGICAL"; PROBABILISTIC="PROBABILISTIC"; CAUSAL="CAUSAL"; EPISTEMIC="EPISTEMIC"

class Status(str, Enum):
    ADMITTED="ADMITTED"; CONDITIONAL="CONDITIONAL"; REJECTED="REJECTED"; UNDEFINED="UNDEFINED"

@dataclass(frozen=True)
class Contract:
    regime: str
    scope: str
    temporal: str
    assumptions: Tuple[str,...]=()
    preservation: Tuple[str,...]=()

@dataclass(frozen=True)
class Dep:
    src: str; tgt: str; kind: Kind; contract: Contract; witness_id: str

@dataclass(frozen=True)
class Composite:
    src: str; tgt: str; kind: Kind; contract: Contract
    path: Tuple[str,...]; status: Status; witness_signature: Tuple[Any,...]

def path(x):
    return (x.src,x.tgt) if isinstance(x,Dep) else x.path
def wid(x):
    return x.witness_id if isinstance(x,Dep) else x.witness_signature

def compose(d1, d2):
    if d1.tgt != d2.src:
        return Composite(d1.src,d2.tgt,d2.kind,d2.contract,path(d1)+path(d2),
                         Status.UNDEFINED,("endpoint-mismatch",))
    if d1.contract.scope != d2.contract.scope:
        return Composite(d1.src,d2.tgt,d2.kind,d2.contract,path(d1)+path(d2),
                         Status.REJECTED,("scope-mismatch",))
    if d1.contract.temporal != d2.contract.temporal:
        status=Status.CONDITIONAL
        extra=("temporal-bridge",)
    else:
        status=Status.ADMITTED
        extra=()
    conditions=list(d1.contract.assumptions+d2.contract.assumptions+extra)
    if d1.kind==d2.kind==Kind.PROBABILISTIC and "factorization" not in conditions:
        status=Status.CONDITIONAL; conditions.append("factorization")
    elif d1.kind==d2.kind==Kind.CAUSAL and "full_mediation" not in conditions:
        status=Status.CONDITIONAL; conditions.append("full_mediation")
    elif d1.kind==d2.kind==Kind.EPISTEMIC and "nonduplication" not in conditions:
        status=Status.CONDITIONAL; conditions.append("nonduplication")
    elif d1.kind != d2.kind:
        status=Status.CONDITIONAL; conditions.append("semantic_bridge")
    c=Contract(
        regime=d1.contract.regime+"∘"+d2.contract.regime,
        scope=d1.contract.scope,
        temporal=d1.contract.temporal if d1.contract.temporal==d2.contract.temporal else "bridged",
        assumptions=tuple(sorted(set(conditions))),
        preservation=tuple(sorted(set(d1.contract.preservation+d2.contract.preservation))),
    )
    return Composite(d1.src,d2.tgt,d2.kind,c,path(d1)+path(d2),status,
                     (wid(d1),wid(d2),status.value,c))

# Strict equality is NOT semantic equality: nested witness trees differ by grouping.
def strict_equal(a,b): return a == b

# Canonical semantic equivalence flattens witness nesting and ignores proof-tree bracketing.
def semantic_signature(x):
    return (
        x.src,x.tgt,x.kind.value,x.path,x.status.value,
        x.contract.scope,x.contract.temporal,
        tuple(sorted(x.contract.assumptions)),
        tuple(sorted(x.contract.preservation))
    )
def semantically_equivalent(a,b): return semantic_signature(a)==semantic_signature(b)

def make3(kind=Kind.LOGICAL, assumptions=(), scopes=("S","S","S"), temporals=("T","T","T")):
    c1=Contract(kind.value,scopes[0],temporals[0],tuple(assumptions),("Z",))
    c2=Contract(kind.value,scopes[1],temporals[1],tuple(assumptions),("Z",))
    c3=Contract(kind.value,scopes[2],temporals[2],tuple(assumptions),("Z",))
    return (Dep("A","B",kind,c1,"d1"), Dep("B","C",kind,c2,"d2"), Dep("C","D",kind,c3,"d3"))

def assoc(d1,d2,d3):
    left=compose(compose(d1,d2),d3)
    right=compose(d1,compose(d2,d3))
    return left,right,strict_equal(left,right),semantically_equivalent(left,right)

def run():
    results=[]
    # 1-4 homogeneous regimes
    for k,ass in [
        (Kind.LOGICAL,()),
        (Kind.PROBABILISTIC,("factorization",)),
        (Kind.CAUSAL,("full_mediation",)),
        (Kind.EPISTEMIC,("nonduplication",))
    ]:
        results.append((f"homogeneous-{k.value}", assoc(*make3(k,ass))))
    # 5-7 same regime but missing composition assumptions: still semantically associative
    for k in (Kind.PROBABILISTIC,Kind.CAUSAL,Kind.EPISTEMIC):
        results.append((f"conditional-{k.value}", assoc(*make3(k,()))))
    # 8 mixed regimes: every bridge is conditional, but grouping must not change semantic result
    ds=make3(Kind.LOGICAL)
    d1=ds[0]; d2=Dep("B","C",Kind.PROBABILISTIC,d1.contract,"d2p")
    d3=Dep("C","D",Kind.CAUSAL,d1.contract,"d3c")
    results.append(("mixed-regime",assoc(d1,d2,d3)))
    # 9 scope mismatch at final boundary
    results.append(("scope-mismatch",assoc(*make3(Kind.LOGICAL,scopes=("S","S","T")))))
    # 10 temporal mismatch
    results.append(("temporal-mismatch",assoc(*make3(Kind.LOGICAL,temporals=("T0","T0","T1")))))
    # 11 partial domain / endpoint mismatch: both groupings cannot produce an admitted chain
    bad3=(Dep("A","B",Kind.LOGICAL,Contract("L","S","T"),"d1"),
          Dep("X","C",Kind.LOGICAL,Contract("L","S","T"),"d2"),
          Dep("C","D",Kind.LOGICAL,Contract("L","S","T"),"d3"))
    results.append(("partial-domain",assoc(*bad3)))
    # 12 witness equivalence: strict fails, semantic succeeds
    left,right,se,eq=assoc(*make3())
    assert not se and eq
    # 13 congruence: replace d2 by semantically equivalent witness id
    a=make3()
    b=(a[0],Dep("B","C",a[1].kind,a[1].contract,"d2_equivalent"),a[2])
    l1=compose(compose(*a[:2]),a[2]); l2=compose(compose(*b[:2]),b[2])
    congruent=semantically_equivalent(l1,l2)
    results.append(("witness-congruence",(l1,l2,l1==l2,congruent)))
    # 14 counterexample: naive strict associativity claim is false
    strict_fail=not assoc(*make3())[2]
    results.append(("strict-associativity-counterexample",strict_fail))
    return results

if __name__=="__main__":
    r=run()
    for name,val in r:
        if isinstance(val, tuple) and len(val)==4:
            print(name, "strict=",val[2], "semantic=",val[3])
        else:
            print(name, val)
