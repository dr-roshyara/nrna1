import sys, random
sys.path.insert(0, sys.argv[1])
import numpy as np
exec(open(sys.path[0]+'/q2r1.py').read().split('N=int(')[0])
def coh_w(rs,parts):
    L=lab(parts); res=[]
    for i,p in enumerate(parts):
        ins=tot=0.0
        for s in rs:
            k=len(s)
            if k<2: continue
            for a in s&p:
                for b in s:
                    if b!=a: tot+=1/(k-1); ins+=(L[b]==i)/(k-1)
        res.append(ins/tot if tot else float('nan'))
    return res
for ename,enc in ENCS:
  for rlab,rows in [('TA',TA),('TA+TB',TA+TB)]:
    for variant in ('weighted','drop rows>=10 concepts'):
      rs=rsets(enc,rows)
      f=coh_w if variant=='weighted' else cohesion
      if variant!='weighted': rs=[s for s in rs if len(s)<10]
      c=f(rs,[CB1,CB23]); rng=random.Random(3); r9=[];r14=[]
      for _ in range(2000):
        rp=randparts([9,14],rng); cc=f(rs,rp); r9.append(cc[0]); r14.append(cc[1])
      r9=np.array([v for v in r9 if v==v]); r14=np.array([v for v in r14 if v==v])
      print(ename,rlab,variant,f'CB1 {c[0]:.2f} mean {r9.mean():.2f} P>= {(r9>=c[0]).mean():.3f} | CB23 {c[1]:.2f} mean {r14.mean():.2f} P>= {(r14>=c[1]).mean():.4f}')
