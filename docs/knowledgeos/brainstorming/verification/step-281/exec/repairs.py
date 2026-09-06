"""Step 281 — the three candidate repairs, each IMPLEMENTED so selection is executed, not argued."""
from dataclasses import dataclass, field
from typing import FrozenSet, Dict, Optional, Tuple
import sys; sys.path.insert(0,'.')
from kosmodel import *

# ---------------- Repair A — explicit BOTTOM assertion ----------------
BOTTOM="BOTTOM"
def A_bottom_assertion(propkey, c, t, pi):
    E,Dname,_=propkey
    D=Dimension(Dname,(BOTTOM,),"nominal")     # BOTTOM must be admissible => its own value space
    return Assertion(Prop(E,D,BOTTOM),frozenset(),c,t,pi)
class RepairA:
    name="A (bottom assertion)"
    def __init__(self): self.K=EMPTY
    def ask(self,propkey,c="prod",t=None,pi="inquiry"):
        a=A_bottom_assertion(propkey,c,t or Iv("2026-01-01",None),pi)
        self.K,_=delta(self.K,"assert",a); return a
    def state(self,propkey,real):
        E,Dn,V=propkey
        bot=[a for a in self.K.A if a.P.E==E and a.P.D.name==Dn and a.P.V==BOTTOM]
        if real is not None:
            s=Sigma(real)
            return ("Asked" if bot else "?", {"Supporting":"Supported","Refuting":"Refuted",
                    "Contested":"Conflicted","Neutral":"Unknown"}[s[0]])
        return ("Asked","Absent") if bot else ("NotAsked","-")

# ---------------- Repair B — inquiry register Q_t ----------------
class RepairB:
    name="B (inquiry register Q_t)"
    def __init__(self): self.K=EMPTY; self.Q=set()
    def ask(self,propkey,**kw): self.Q.add(propkey); return propkey
    def state(self,propkey,real):
        if propkey not in self.Q: return ("NotAsked","-")
        if real is None: return ("Asked","Absent")
        s=Sigma(real)
        return ("Asked",{"Supporting":"Supported","Refuting":"Refuted",
                         "Contested":"Conflicted","Neutral":"Unknown"}[s[0]])

# ---------------- Repair C2 — typed epistemic state Sigma* = I x E ----------------
class RepairC2:
    name="C2 (typed Sigma* = I x E)"
    def __init__(self): self.K=EMPTY; self.eps:Dict=dict()
    def ask(self,propkey,**kw): self.eps[propkey]=("Asked",None); return propkey
    def state(self,propkey,real):
        if propkey not in self.eps: return ("NotAsked","-")
        if real is None: return ("Asked","Absent")
        s=Sigma(real)
        return ("Asked",{"Supporting":"Supported","Refuting":"Refuted",
                         "Contested":"Conflicted","Neutral":"Unknown"}[s[0]])

# ---------------- Orphan — STRUCTURAL, per 281.7/281.8 ----------------
def is_orphan(K,a): return not any(a.id in (f,t) for f,t,_ in K.R)
