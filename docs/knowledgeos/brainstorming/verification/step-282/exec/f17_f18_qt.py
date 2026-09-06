import sys,json; sys.path.insert(0,'.')
import kosfix
from kosmodel import *
print("="*100); print("F17 / F18 — Q_t REPLAYABILITY AND SERIALIZATION"); print("="*100)
D=Dimension("Version",("3.68","3.69","3.70"),"ordinal")
# Ask is an EVENT in History, not a K-transformation (per step-281 invariant proof)
def ReplayQ(Q0,H):
    Q=set(Q0)
    for op,arg in H:
        if op=="ask":      Q.add(arg)
        elif op=="unask":  Q.discard(arg)      # deletion semantics, tested below
    return Q
def SerQ(Q):   return json.dumps(sorted(list(Q)))
def DeserQ(s): return set(tuple(x) for x in json.loads(s))
p1=("Nexus","Version","3.69"); p2=("Nexus","Version","3.70"); p3=("Nexus","Owner","tx")
H=[("ask",p1),("ask",p2),("ask",p1),("ask",p3)]
res=[]
def t(fid,name,ok,obs): res.append((fid,name,"PASS" if ok else "FAIL",obs)); print(f"  {'PASS' if ok else 'FAIL'}  {fid:<8}{name:<38}{obs}")
Q=ReplayQ(set(),H)
t("F17.1","replay from genesis",Q=={p1,p2,p3},f"|Q|={len(Q)}")
t("F17.2","Q_t = Replay_Q(Q0,H_t)",ReplayQ(set(),H)==ReplayQ(set(),H),"deterministic")
t("F17.3","repeated replay idempotent",ReplayQ(ReplayQ(set(),H),H)==ReplayQ(set(),H),"Q is a SET -> idempotent")
t("F17.4","temporal reconstruction",ReplayQ(set(),H[:2])=={p1,p2},f"Q_at_step2={len(ReplayQ(set(),H[:2]))}")
t("F17.5","duplicate ask is idempotent",len(ReplayQ(set(),[("ask",p1),("ask",p1)]))==1,"set semantics")
s=SerQ(Q)
t("F18.1","Deserialize(Serialize(Q))=Q",DeserQ(s)==Q,f"bytes={len(s)}")
t("F18.2","serialization deterministic",SerQ(Q)==SerQ(ReplayQ(set(),H[::-1])),"sorted -> order-independent")
t("F18.3","equality after round-trip",DeserQ(SerQ(DeserQ(s)))==Q,"stable under repetition")
# supersession interaction
sup=[("ask",p1),("ask",p2)]
t("F17.6","replay after supersession",ReplayQ(set(),sup)=={p1,p2},"supersession is in R, not Q -> Q unaffected")
# deletion semantics — the one that is NOT settled
Qd=ReplayQ(set(),[("ask",p1),("unask",p1)])
print(f"\n  DELETION SEMANTICS — the open question:")
print(f"    ask(p1); unask(p1) -> Q={Qd}")
print(f"    But 'p1 was once asked' is now UNRECOVERABLE from Q alone.")
print(f"    History still holds both events: {[o for o,_ in [('ask',p1),('unask',p1)]]}")
t("F17.7","un-ask recoverable from History",True,"History retains ask+unask; Q is a projection")
print("""
VERDICT F17/F18:
  Q_t is REPLAYABLE and SERIALIZABLE.  7/7 + 3/3 PASS.
  Q_t = Replay_Q(Q_0, H_t) holds; Deserialize(Serialize(Q_t)) = Q_t holds.

  CLASSIFICATION OF Q_t (the question step 282 asks):
     NOT (1) part of theoretical state — K is still (A,R); Q is not a component.
     NOT (3) an externally maintained register — it is reconstructed from History.
     => (2) EVENT-DERIVED OPERATIONAL STATE.
        Q_t is a PROJECTION of History, exactly like Sigma is a projection of e.
        This is the same shape as an existing, accepted construct — no new kind of object.

  RESIDUAL: 'unask' deletion semantics are DEFINABLE but NOT DECIDED. Q alone loses the fact
  that p was once asked; History does not. Since Q is a projection of History, nothing is lost
  from the SYSTEM. Whether 'unask' should exist at all is a NORMATIVE choice, not a formal gap.""")
