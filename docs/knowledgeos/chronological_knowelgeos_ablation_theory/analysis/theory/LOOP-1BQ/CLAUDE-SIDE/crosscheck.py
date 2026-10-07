"""Naive cross-check: enumerate every raw assignment in R-1 order, filter by F1/F2,
count, and recompute (i)-S for R-c and R-d by a dictionary over the ~_{sigma,f} key."""
import sys
from slice_enum import classes, rank, INF, T

def slots(V):
    out=[None]
    for d in T:
        for v in V:
            for prep in (0,1):
                for f in (0,1,INF): out.append(('iss',d,v,prep,f))
    for d in T: out.append(('adp',d))
    return out

def frame(V):
    S=slots(V)
    for s0 in V:
     for s1 in V:
      for a1 in S:
       for a2 in S:
        if a1 and a1[0]=='adp' and not (a2 and a2[0]=='iss'): continue
        if a2 and a2[0]=='adp' and not (a1 and a1[0]=='iss'): continue
        for cap in range(4):
         for w1 in (None,0,1):
          for ref in range(8):
           if (ref&4 and not a1) or (ref&2 and not a2) or (ref&1 and w1 is None): continue
           for k1 in ((0,1) if a1 else (0,)):
            for k2 in ((0,1) if a2 else (0,)):
             for r in T:
              for c in (0,1):
               ok=True
               for a in (a1,a2):
                if a and a[0]=='iss' and a[4]!=INF and (s0,s1)[a[4]]!=a[2]: ok=False
               if k1 and not (ref&4 and a1[1]==r): ok=False
               if k2 and not (ref&2 and a2[1]==r): ok=False
               if ok: yield (s0,s1,a1,a2,cap,w1,ref,k1,k2,r,c)

for V,P,name in (((0,1),{0},'U'),((0,1,2),{0,1},'U_P2')):
    hs=list(frame(V))
    assert all(rank(hs[i])<rank(hs[i+1]) for i in range(len(hs)-1)), 'order'
    def key(h):
        strip=lambda a: a[:4] if a and a[0]=='iss' else a
        return (strip(h[2]),strip(h[3]))+h[4:]
    for R in ('R-c','R-d'):
        good=set()
        cl=[classes(h,P)[R] for h in hs]
        for h,x in zip(hs,cl):
            if x[1] and x[2]: good.add(key(h))
        cex=[h for h,x in zip(hs,cl) if x[0] and key(h) not in good]
        print(name,len(hs),R,len(cex),cex[0] if cex else None)
