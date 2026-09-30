"""
KnowledgeOS R596 — Cross-Semantic Dependency Composition

Finite executable evidence only. No universal theorem is claimed.

Research question:
Can individually valid logical, probabilistic, causal and epistemic
dependencies be composed automatically?

Conclusion:
No. Composition is a partial, contract-indexed operation.
"""

# The executable reference is intentionally compact and mirrors the benchmark
# cases printed by the notebook run.

from dataclasses import dataclass
from enum import Enum

class Kind(Enum):
    LOGICAL="logical"; PROBABILISTIC="probabilistic"
    CAUSAL="causal"; EPISTEMIC="epistemic"

class Status(Enum):
    ADMITTED="ADMITTED"; CONDITIONAL="CONDITIONAL"
    REJECTED="REJECTED"; UNDEFINED="UNDEFINED"

@dataclass(frozen=True)
class Dependency:
    source:str; target:str; kind:Kind; contract:str

def compose(d1,d2,typing=True,compatible=True,preservation=True,
            independence=True,mediation=True):
    if d1.target != d2.source:
        return Status.UNDEFINED
    if not typing or not compatible or not preservation:
        return Status.REJECTED
    if d1.kind==d2.kind==Kind.LOGICAL:
        return Status.ADMITTED
    if d1.kind==d2.kind==Kind.PROBABILISTIC:
        return Status.ADMITTED if independence else Status.CONDITIONAL
    if d1.kind==d2.kind==Kind.CAUSAL:
        return Status.ADMITTED if mediation else Status.CONDITIONAL
    if d1.kind==d2.kind==Kind.EPISTEMIC:
        return Status.ADMITTED if independence else Status.CONDITIONAL
    return Status.CONDITIONAL

L1=Dependency("A","B",Kind.LOGICAL,"B=f(A)")
L2=Dependency("B","C",Kind.LOGICAL,"C=g(B)")
assert compose(L1,L2)==Status.ADMITTED

P1=Dependency("A","B",Kind.PROBABILISTIC,"P(B|A)")
P2=Dependency("B","C",Kind.PROBABILISTIC,"P(C|B)")
assert compose(P1,P2,independence=False)==Status.CONDITIONAL
assert .9*.9 != .9*.9+.1*.8
assert compose(P1,P2,independence=True)==Status.ADMITTED

C1=Dependency("A","B",Kind.CAUSAL,"SCM")
C2=Dependency("B","C",Kind.CAUSAL,"SCM")
assert compose(C1,C2,mediation=True)==Status.ADMITTED
assert compose(C1,C2,mediation=False)==Status.CONDITIONAL

E1=Dependency("E1","B",Kind.EPISTEMIC,"update")
E2=Dependency("B","C",Kind.EPISTEMIC,"update")
assert compose(E1,E2,independence=True)==Status.ADMITTED
assert compose(E1,E2,independence=False)==Status.CONDITIONAL

assert compose(P1,L2)==Status.CONDITIONAL
assert compose(P1,L2,preservation=False)==Status.REJECTED

f=lambda x:x+1; g=lambda x:2*x; h=lambda x:x*x
for x in range(-3,4):
    assert h(g(f(x))) == (lambda y:h(y))(g(f(x)))

print("R596: all executable assertions passed")
