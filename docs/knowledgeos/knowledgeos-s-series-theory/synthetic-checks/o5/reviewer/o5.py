import itertools
from fractions import Fraction as F
# O5-5
dM={0:(1,1),1:(1,2),2:(0,2)}; out=[0,1,0]
def loss(dC,s0,fd,T=6):
    tot=F(0)
    for t in range(T+1):
        err=0
        for h in itertools.product([0,1],repeat=t):
            m=0;c=s0
            for a in h: m=dM[m][a]; c=dC[c][a]
            err+= fd[c]!=out[m]
        tot+=F(err,2**t)
    return tot/7
C={0:(1,0),1:(1,0)}
print("C",float(loss(C,0,[0,1])))
best=None;allr=[]
for t00,t01,t10,t11 in itertools.product([0,1],repeat=4):
  dC={0:(t00,t01),1:(t10,t11)}
  for fd in itertools.product([0,1],repeat=2):
    L=loss(dC,0,fd); allr.append((L,dC,fd))
allr.sort(key=lambda x:x[0]); print("best 2-state recursive",float(allr[0][0]),allr[0][1:])
# constant
print("const0",float(loss({0:(0,0),1:(0,0)},0,[0,0])),"const1",float(loss({0:(0,0),1:(0,0)},0,[1,1])))
# snapshot partitions of M states (not necessarily congruent): state=g(m), decoder best per block
def snap(g):
    tot=F(0)
    for t in range(7):
        cnt={}
        for h in itertools.product([0,1],repeat=t):
            m=0
            for a in h:m=dM[m][a]
            cnt.setdefault(g[m],[0,0]); cnt[g[m]][out[m]]+=1
        tot+=F(sum(min(v) for v in cnt.values()),2**t)
    return tot/7
for g in [(0,0,1),(0,1,0),(0,1,1),(0,0,0)]:
    print("snapshot g",g,float(snap(g)))
# fixed decoder per block across time
def snapfixed(g):
    best=9
    for fd in itertools.product([0,1],repeat=2):
        tot=F(0)
        for t in range(7):
            e=0
            for h in itertools.product([0,1],repeat=t):
                m=0
                for a in h:m=dM[m][a]
                e+= fd[g[m]]!=out[m]
            tot+=F(e,2**t)
        best=min(best,tot/7)
    return best
for g in [(0,0,1),(0,1,0),(0,1,1)]:
    print("snapshot fixed-decoder g",g,float(snapfixed(g)))
# O5-3 brute force
syms=['x0','x1','y0','y1']
def run3(dC,fx,fy,T):
    res=[]
    for t in range(T+1):
        ex=ey=0
        for h in itertools.product(range(4),repeat=t):
            x=y=0;c=0
            for a in h:
                if a<2:x=a
                else:y=a-2
                c=dC[c][a]
            ex+=fx[c]!=x; ey+=fy[c]!=y
        res.append(F(ex+ey,2*4**t))
    return res
T=5;bests=[]
for tr in itertools.product([0,1],repeat=8):
    dC={0:tr[:4],1:tr[4:]}
    for fx in itertools.product([0,1],repeat=2):
      for fy in itertools.product([0,1],repeat=2):
        r=run3(dC,fx,fy,T); bests.append((sum(r),r,tr,fx,fy))
bests.sort(key=lambda z:z[0])
trackx=run3({0:(0,1,0,0),1:(0,1,1,1)},(0,1),(0,0),T)
print("trackx",[float(v) for v in trackx], "sum",float(sum(trackx)))
print("pred per-t", [0.5*0.5*(1-2**-t) for t in range(T+1)])
for b in bests[:4]: print(float(b[0]),[float(v) for v in b[1]],b[2:])
# per-t domination check
better=[b for b in bests if all(v<=w for v,w in zip(b[1],trackx)) and b[1]!=trackx]
print("dominating trackx:",len(better))
