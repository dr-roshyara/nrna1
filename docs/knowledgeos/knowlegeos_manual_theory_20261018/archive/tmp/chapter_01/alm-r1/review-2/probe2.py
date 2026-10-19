import sys, random; sys.path.insert(0, sys.argv[1])
from alm_r1 import *
from oracle_r1 import Oracle, to_dict, theta_dict
from tests_r1_exhaustive import TEMPLATES, N_T
from itertools import product
ROOTSET = frozenset({("R", ("*", "*"))})
grid=dict(C1=["a","b"],RD1=[{"decision":"persist"},{"decision":"cascade"}],delta=["independent","dependent"],
          BASIS=["any","all"],C10=["given","no_same_instant"],iota_g=["closed","half_open"],iota_c=["closed","half_open"],
          C6=["off","later_prevails"],BAR=["any"])
rnd=random.Random(7); vdis=cdis=n=0; ex={}
extra=[Act("Ab2","ADOPT","a",tau=1,ord=13,subject="s"), Act("gRb2","GRANT","R",tau=1,ord=14,grantee="b",scope=frozenset({("ADOPT","s")}),period=(1,2))]
for trial in range(6000):
    k=rnd.randint(2,6); idx=rnd.sample(range(N_T+2),k)
    H=[]
    for i in idx:
        t=rnd.randint(1,4)
        H.append(TEMPLATES(t)[i] if i<N_T else replace(extra[i-N_T],tau=t,period=(t,t+1) if extra[i-N_T].period else None))
    cfg={kk:rnd.choice(v) for kk,v in grid.items()}; T=Theta(root=ROOTSET,**cfg)
    rho={a.id:rnd.choice([None,a.tau,a.tau+1,a.tau+3]) for a in H} if cfg["C1"]=="a" else {}
    imp=History(H,T,rho); O=Oracle([to_dict(a) for a in H],theta_dict(T),rho); fps=O.fixed_points()
    n+=1
    if len(fps)!=1 or any(fps[0][a.id]!=imp.valid(a.id) for a in H):
        vdis+=1; ex.setdefault(("valid",cfg["C1"],cfg["C10"],cfg["delta"]),([(a.id,a.tau) for a in H],rho)); continue
    for c in [a for a in H if a.kind=="ADOPT"]:
        for t in range(1,7):
            if imp.current(c.id,t)!=O.current(fps[0],c.id,t):
                cdis+=1; ex.setdefault(("current",cfg["C1"],cfg["C6"],cfg["RD1"]["decision"],cfg["delta"]),([(a.id,a.tau) for a in H],rho,c.id,t))
print("trials",n,"valid disagreements",vdis,"current disagreements",cdis)
for k,v in list(ex.items())[:8]: print(k,v)
